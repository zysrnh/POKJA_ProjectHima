<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PointHistory extends Model
{
    use HasFactory;

    public const KATEGORI_KEHADIRAN = 'Kehadiran';
    public const POIN_KEHADIRAN = 1;

    protected $table = 'point_histories';

    protected $fillable = [
        'pendaftaran_id',
        'tipe',
        'kategori',
        'jumlah',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    /**
     * Format keterangan standar agar poin presensi per pertemuan idempoten & mudah dilacak.
     */
    public static function presensiKeterangan(int $pertemuan): string
    {
        return "Presensi Pertemuan {$pertemuan}";
    }

    /**
     * Cek apakah poin kehadiran otomatis untuk pertemuan tertentu sudah pernah dicatat.
     */
    public static function hasPresensiPoint(int $pendaftaranId, int $pertemuan): bool
    {
        return self::where('pendaftaran_id', $pendaftaranId)
            ->where('tipe', 'tambah')
            ->where('kategori', self::KATEGORI_KEHADIRAN)
            ->where('keterangan', self::presensiKeterangan($pertemuan))
            ->exists();
    }

    /**
     * Hitung ulang total_point pendaftaran secara berurutan dari seluruh riwayat point_histories.
     */
    public static function recalculateTotalPoint(int $pendaftaranId): int
    {
        $histories = self::where('pendaftaran_id', $pendaftaranId)
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $recalculated = 0;
        foreach ($histories as $item) {
            if ($item->tipe === 'tambah') {
                $recalculated += (int) $item->jumlah;
            } else {
                $recalculated = max(0, $recalculated - (int) $item->jumlah);
            }
        }

        Pendaftaran::where('id', $pendaftaranId)->update([
            'total_point' => $recalculated,
        ]);

        return $recalculated;
    }

    /**
     * Catat +1 Point Kehadiran secara atomik & anti-dobel untuk satu sesi pertemuan.
     */
    public static function awardPresensiPoint(
        int $pendaftaranId,
        int $pertemuan,
        ?string $createdBy = 'Sistem Presensi'
    ): bool {
        if ($pertemuan < 1 || $pertemuan > 7) {
            return false;
        }

        return DB::transaction(function () use ($pendaftaranId, $pertemuan, $createdBy) {
            $pendaftaran = Pendaftaran::where('id', $pendaftaranId)->lockForUpdate()->first();
            if (!$pendaftaran) {
                return false;
            }

            if (self::hasPresensiPoint($pendaftaranId, $pertemuan)) {
                return false;
            }

            self::create([
                'pendaftaran_id' => $pendaftaranId,
                'tipe' => 'tambah',
                'kategori' => self::KATEGORI_KEHADIRAN,
                'jumlah' => self::POIN_KEHADIRAN,
                'keterangan' => self::presensiKeterangan($pertemuan),
                'created_by' => $createdBy ?: 'Sistem Presensi',
            ]);

            self::recalculateTotalPoint($pendaftaranId);

            return true;
        });
    }

    /**
     * Cabut riwayat +1 Point Kehadiran otomatis ketika status hadir pertemuan dibatalkan.
     */
    public static function revokePresensiPoint(int $pendaftaranId, int $pertemuan): int
    {
        if ($pertemuan < 1 || $pertemuan > 7) {
            return 0;
        }

        return DB::transaction(function () use ($pendaftaranId, $pertemuan) {
            $pendaftaran = Pendaftaran::where('id', $pendaftaranId)->lockForUpdate()->first();
            if (!$pendaftaran) {
                return 0;
            }

            $deleted = self::where('pendaftaran_id', $pendaftaranId)
                ->where('tipe', 'tambah')
                ->where('kategori', self::KATEGORI_KEHADIRAN)
                ->where('keterangan', self::presensiKeterangan($pertemuan))
                ->delete();

            if ($deleted > 0) {
                self::recalculateTotalPoint($pendaftaranId);
            }

            return $deleted;
        });
    }
}

