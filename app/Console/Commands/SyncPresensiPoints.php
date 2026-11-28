<?php

namespace App\Console\Commands;

use App\Models\PointHistory;
use App\Models\Presensi;
use Illuminate\Console\Command;

class SyncPresensiPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presensi:sync-points
        {--pertemuan= : Filter hanya untuk pertemuan tertentu (1-7)}
        {--dry-run : Simulasi tanpa menulis perubahan ke database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill +1 Point Kehadiran secara aman & anti-dobel untuk mahasiswa yang sudah tercatat hadir di presensis';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $pertemuanFilter = $this->option('pertemuan');
        $dryRun = (bool) $this->option('dry-run');

        if ($pertemuanFilter !== null && $pertemuanFilter !== '') {
            if (!ctype_digit((string) $pertemuanFilter) || (int) $pertemuanFilter < 1 || (int) $pertemuanFilter > 7) {
                $this->error('Parameter --pertemuan harus berupa angka 1 sampai 7.');
                return self::FAILURE;
            }
            $pertemuanFilter = (int) $pertemuanFilter;
        } else {
            $pertemuanFilter = null;
        }

        $query = Presensi::with('pendaftaran')
            ->where('status', 'hadir')
            ->orderBy('pendaftaran_id', 'asc')
            ->orderBy('pertemuan', 'asc');

        if ($pertemuanFilter !== null) {
            $query->where('pertemuan', $pertemuanFilter);
        }

        $presensis = $query->get();

        if ($presensis->isEmpty()) {
            $this->info('Tidak ada data presensi hadir yang ditemukan.');
            return self::SUCCESS;
        }

        $manualKehadiranCount = PointHistory::where('tipe', 'tambah')
            ->where('kategori', PointHistory::KATEGORI_KEHADIRAN)
            ->where(function ($q) {
                $q->whereNull('keterangan')
                    ->orWhere('keterangan', 'not like', 'Presensi Pertemuan %');
            })
            ->count();

        if ($manualKehadiranCount > 0) {
            $this->warn("Catatan: Ditemukan {$manualKehadiranCount} riwayat manual kategori 'Kehadiran' (non-presensi otomatis). Sinkronisasi tetap memeriksa unik per \"Presensi Pertemuan X\".");
        }

        $checked = 0;
        $awarded = 0;
        $skipped = 0;

        foreach ($presensis as $presensi) {
            $checked++;
            $pendaftaran = $presensi->pendaftaran;
            $pertemuan = (int) $presensi->pertemuan;

            if (!$pendaftaran || $pertemuan < 1 || $pertemuan > 7) {
                $skipped++;
                continue;
            }

            if (PointHistory::hasPresensiPoint($pendaftaran->id, $pertemuan)) {
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $awarded++;
                $this->line("[DRY-RUN] +1 Point -> {$pendaftaran->nim} | {$pendaftaran->nama} (Pertemuan {$pertemuan})");
                continue;
            }

            if (PointHistory::awardPresensiPoint($pendaftaran->id, $pertemuan, 'Sinkronisasi Presensi')) {
                $awarded++;
                $this->line("+1 Point -> {$pendaftaran->nim} | {$pendaftaran->nama} (Pertemuan {$pertemuan})");
            } else {
                $skipped++;
            }
        }

        $this->newLine();
        $this->info(sprintf(
            'Selesai%s! Diperiksa: %d | Ditambahkan (+1 Point): %d | Sudah tercatat / dilewati: %d',
            $dryRun ? ' (DRY-RUN)' : '',
            $checked,
            $awarded,
            $skipped
        ));

        return self::SUCCESS;
    }
}
