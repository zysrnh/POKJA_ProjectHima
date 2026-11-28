<?php

namespace Tests\Feature;

use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use App\Models\PointHistory;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PresensiPointSyncTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $migrations = glob(database_path('migrations/*.php')) ?: [];
        sort($migrations);

        foreach ($migrations as $migrationFile) {
            if (str_contains(basename($migrationFile), 'add_pertemuan_to_kuesioner_tables')) {
                continue;
            }

            Artisan::call('migrate', ['--path' => 'database/migrations/' . basename($migrationFile)]);
        }

        if (Schema::hasTable('kuesioner_pertanyaans') && !Schema::hasColumn('kuesioner_pertanyaans', 'pertemuan')) {
            Schema::table('kuesioner_pertanyaans', function (Blueprint $table) {
                $table->unsignedSmallInteger('pertemuan')->nullable();
            });
        }

        if (Schema::hasTable('kuesioner_jawabans') && !Schema::hasColumn('kuesioner_jawabans', 'pertemuan')) {
            Schema::table('kuesioner_jawabans', function (Blueprint $table) {
                $table->unsignedSmallInteger('pertemuan')->default(1);
            });
        }
    }

    private function createMahasiswa(string $nim, string $nama): Pendaftaran
    {
        return Pendaftaran::create([
            'nama' => $nama,
            'nim' => $nim,
            'email' => $nim . '@student.test',
            'password' => Hash::make('Password123'),
            'kelas' => '2IF-01',
            'no_telp' => '081234567890',
            'status_kehadiran' => 'terdaftar',
            'total_point' => 0,
        ]);
    }

    private function makeQrPayload(int $pertemuan, string $salt): string
    {
        $window = (int) floor(time() / 5);
        $token = substr(hash_hmac('sha256', "POKJA_P{$pertemuan}_{$window}", $salt), 0, 16);

        return "POKJA:P{$pertemuan}:{$token}:{$window}";
    }

    public function test_qr_scan_awards_one_point_and_rescan_does_not_duplicate(): void
    {
        $salt = 'test_pokja_salt_123';
        Pengaturan::create([
            'nama_acara' => 'POKJA HIMA IF',
            'pertemuan_aktif' => 1,
            'is_scan_aktif' => true,
            'token_salt' => $salt,
        ]);

        $mahasiswa = $this->createMahasiswa('250414001', 'Mahasiswa Scan Satu');
        $payload = $this->makeQrPayload(1, $salt);

        $this->postJson(route('presensi.scan'), [
            'nim' => $mahasiswa->nim,
            'qr_payload' => $payload,
        ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'already' => false,
                'point_awarded' => true,
                'total_point' => 1,
                'pertemuan' => 1,
            ]);

        $mahasiswa->refresh();
        $this->assertSame(1, $mahasiswa->total_point);
        $this->assertDatabaseHas('presensis', [
            'pendaftaran_id' => $mahasiswa->id,
            'pertemuan' => 1,
            'status' => 'hadir',
        ]);
        $this->assertDatabaseHas('point_histories', [
            'pendaftaran_id' => $mahasiswa->id,
            'tipe' => 'tambah',
            'kategori' => PointHistory::KATEGORI_KEHADIRAN,
            'jumlah' => 1,
            'keterangan' => 'Presensi Pertemuan 1',
            'created_by' => 'Sistem Presensi QR',
        ]);

        // Scan ulang di pertemuan yang sama tidak boleh menambah poin lagi
        $this->postJson(route('presensi.scan'), [
            'nim' => $mahasiswa->nim,
            'qr_payload' => $payload,
        ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'already' => true,
                'point_awarded' => false,
                'total_point' => 1,
            ]);

        $mahasiswa->refresh();
        $this->assertSame(1, $mahasiswa->total_point);
        $this->assertSame(1, PointHistory::where('pendaftaran_id', $mahasiswa->id)->count());
    }

    public function test_admin_toggle_manual_awards_and_revokes_attendance_point(): void
    {
        $admin = User::create([
            'name' => 'Adinda Admin',
            'email' => User::SUPER_ADMIN_EMAIL,
            'password' => Hash::make('Password123'),
        ]);

        $mahasiswa = $this->createMahasiswa('250414002', 'Mahasiswa Toggle');

        // Catat poin manual lain agar memastikan tidak ikut terhapus saat presensi dibatalkan
        PointHistory::create([
            'pendaftaran_id' => $mahasiswa->id,
            'tipe' => 'tambah',
            'kategori' => 'Aktif di Kelas',
            'jumlah' => 2,
            'keterangan' => 'Bertanya materi',
            'created_by' => 'Adinda Admin',
        ]);
        PointHistory::recalculateTotalPoint($mahasiswa->id);

        // Toggle HADIR untuk Pertemuan 2
        $this->actingAs($admin)
            ->patchJson(route('admin.presensi.toggle', $mahasiswa->id), [
                'pertemuan' => 2,
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'status' => 'HADIR',
                'total_point' => 3,
            ]);

        $mahasiswa->refresh();
        $this->assertSame(3, $mahasiswa->total_point);
        $this->assertDatabaseHas('point_histories', [
            'pendaftaran_id' => $mahasiswa->id,
            'kategori' => PointHistory::KATEGORI_KEHADIRAN,
            'keterangan' => 'Presensi Pertemuan 2',
            'created_by' => 'Adinda Admin',
        ]);

        // Toggle kembali menjadi BELUM HADIR -> hanya poin presensi pertemuan 2 yang dicabut
        $this->actingAs($admin)
            ->patchJson(route('admin.presensi.toggle', $mahasiswa->id), [
                'pertemuan' => 2,
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'status' => 'BELUM HADIR',
                'total_point' => 2,
            ]);

        $mahasiswa->refresh();
        $this->assertSame(2, $mahasiswa->total_point);
        $this->assertDatabaseMissing('presensis', [
            'pendaftaran_id' => $mahasiswa->id,
            'pertemuan' => 2,
        ]);
        $this->assertDatabaseMissing('point_histories', [
            'pendaftaran_id' => $mahasiswa->id,
            'keterangan' => 'Presensi Pertemuan 2',
        ]);
        $this->assertDatabaseHas('point_histories', [
            'pendaftaran_id' => $mahasiswa->id,
            'kategori' => 'Aktif di Kelas',
            'keterangan' => 'Bertanya materi',
        ]);
    }

    public function test_sync_presensi_points_command_backfills_missed_attendance_idempotently(): void
    {
        $mhsMissedBoth = $this->createMahasiswa('250414003', 'Sudah Absen Lama');
        $mhsPartial = $this->createMahasiswa('250414004', 'Sudah Dapat P1 Saja');
        $mhsNotPresent = $this->createMahasiswa('250414005', 'Belum Hadir');

        // Seed presensi hadir lama yang belum kebagian poin
        Presensi::create([
            'pendaftaran_id' => $mhsMissedBoth->id,
            'pertemuan' => 1,
            'status' => 'hadir',
            'waktu_hadir' => now()->subDays(7),
        ]);
        Presensi::create([
            'pendaftaran_id' => $mhsMissedBoth->id,
            'pertemuan' => 2,
            'status' => 'hadir',
            'waktu_hadir' => now()->subDay(),
        ]);

        Presensi::create([
            'pendaftaran_id' => $mhsPartial->id,
            'pertemuan' => 1,
            'status' => 'hadir',
            'waktu_hadir' => now()->subDays(7),
        ]);
        Presensi::create([
            'pendaftaran_id' => $mhsPartial->id,
            'pertemuan' => 2,
            'status' => 'hadir',
            'waktu_hadir' => now()->subDay(),
        ]);
        // Simulasikan P1 sudah pernah dapat poin sebelum backfill
        PointHistory::awardPresensiPoint($mhsPartial->id, 1, 'Sistem Presensi QR');

        // Record non-hadir tidak boleh diberi poin
        Presensi::create([
            'pendaftaran_id' => $mhsNotPresent->id,
            'pertemuan' => 1,
            'status' => 'alpha',
            'waktu_hadir' => now(),
        ]);

        // Dry-run tidak mengubah database
        $this->artisan('presensi:sync-points', ['--dry-run' => true])
            ->assertSuccessful();

        $mhsMissedBoth->refresh();
        $mhsPartial->refresh();
        $this->assertSame(0, $mhsMissedBoth->total_point);
        $this->assertSame(1, $mhsPartial->total_point);

        // Eksekusi sync pertama -> mengisi P1 & P2 untuk mhsMissedBoth, dan P2 saja untuk mhsPartial
        $this->artisan('presensi:sync-points')
            ->assertSuccessful();

        $mhsMissedBoth->refresh();
        $mhsPartial->refresh();
        $mhsNotPresent->refresh();

        $this->assertSame(2, $mhsMissedBoth->total_point);
        $this->assertSame(2, $mhsPartial->total_point);
        $this->assertSame(0, $mhsNotPresent->total_point);
        $this->assertSame(2, PointHistory::where('pendaftaran_id', $mhsMissedBoth->id)->count());
        $this->assertSame(2, PointHistory::where('pendaftaran_id', $mhsPartial->id)->count());

        // Eksekusi sync kedua (idempoten) -> tidak boleh mendobel poin
        $this->artisan('presensi:sync-points')
            ->assertSuccessful();

        $mhsMissedBoth->refresh();
        $mhsPartial->refresh();
        $this->assertSame(2, $mhsMissedBoth->total_point);
        $this->assertSame(2, $mhsPartial->total_point);
        $this->assertSame(2, PointHistory::where('pendaftaran_id', $mhsMissedBoth->id)->count());
        $this->assertSame(2, PointHistory::where('pendaftaran_id', $mhsPartial->id)->count());
    }

    public function test_sync_presensi_points_rejects_invalid_pertemuan_option(): void
    {
        $this->artisan('presensi:sync-points', ['--pertemuan' => '9'])
            ->assertFailed();
    }

    public function test_manual_point_store_and_destroy_keep_total_point_in_sync_with_presensi_point(): void
    {
        $admin = User::create([
            'name' => 'Adinda Admin',
            'email' => User::SUPER_ADMIN_EMAIL,
            'password' => Hash::make('Password123'),
        ]);

        $mahasiswa = $this->createMahasiswa('250414006', 'Mahasiswa Gabungan Poin');
        PointHistory::awardPresensiPoint($mahasiswa->id, 1, 'Sistem Presensi QR');

        $this->actingAs($admin)
            ->post(route('admin.poin.store'), [
                'pendaftaran_id' => $mahasiswa->id,
                'tipe' => 'tambah',
                'kategori' => 'Aktif di Kelas',
                'keterangan' => 'Menjawab kuis',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $mahasiswa->refresh();
        $this->assertSame(2, $mahasiswa->total_point);

        $manualHistory = PointHistory::where('pendaftaran_id', $mahasiswa->id)
            ->where('kategori', 'Aktif di Kelas')
            ->firstOrFail();

        $this->actingAs($admin)
            ->delete(route('admin.poin.destroyHistory', $manualHistory->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $mahasiswa->refresh();
        $this->assertSame(1, $mahasiswa->total_point);
    }
}
