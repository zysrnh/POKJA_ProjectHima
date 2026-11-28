<?php

namespace Tests\Feature;

use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PresensiSessionTest extends TestCase
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

    public function test_opening_scan_from_pertemuan_tab_activates_that_pertemuan_and_proyektor_token(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => User::SUPER_ADMIN_EMAIL,
            'password' => Hash::make('Password123'),
        ]);

        $pengaturan = Pengaturan::create([
            'nama_acara' => 'POKJA HIMA IF',
            'tanggal_acara' => '13 Oktober 2026',
            'jam_acara' => '08:00 WIB - Selesai',
            'lokasi_acara' => 'Ruangan 105',
            'pertemuan_aktif' => 1,
            'is_scan_aktif' => false,
        ]);

        // Admin opens tab P2 while DB active session is P1 (closed)
        $this->actingAs($admin)
            ->get(route('admin.presensi.index', ['pertemuan' => 2]))
            ->assertOk()
            ->assertSee('Scan Ditutup (P1)')
            ->assertSee('Buka Sesi P2');

        // Clicking "Buka Sesi P2" updates pertemuan_aktif to 2 and opens scan
        $this->actingAs($admin)
            ->from(route('admin.presensi.index', ['pertemuan' => 2]))
            ->post(route('admin.presensi.sesi'), [
                'pertemuan_aktif' => 2,
                'activate_scan' => 1,
            ])
            ->assertRedirect(route('admin.presensi.index', ['pertemuan' => 2]))
            ->assertSessionHas('success', 'Sesi Pertemuan 2 berhasil DIBUKA.');

        $pengaturan->refresh();
        $this->assertSame(2, $pengaturan->pertemuan_aktif);
        $this->assertTrue($pengaturan->is_scan_aktif);

        // Projector token & layar page both reflect P2
        $tokenResponse = $this->actingAs($admin)
            ->getJson(route('admin.presensi.token'))
            ->assertOk()
            ->assertJson([
                'pertemuan' => 2,
                'is_scan_aktif' => true,
            ]);

        $this->assertStringStartsWith('POKJA:P2:', (string) $tokenResponse->json('payload'));

        $this->actingAs($admin)
            ->get(route('admin.presensi.layar'))
            ->assertOk()
            ->assertSee('P2');

        // Jumping to tab P3 while P2 scan is open switches directly to P3 & keeps scan open
        $this->actingAs($admin)
            ->get(route('admin.presensi.index', ['pertemuan' => 3]))
            ->assertOk()
            ->assertSee('Scan Aktif: P2')
            ->assertSee('Buka Sesi P3');

        $this->actingAs($admin)
            ->from(route('admin.presensi.index', ['pertemuan' => 3]))
            ->post(route('admin.presensi.sesi'), [
                'pertemuan_aktif' => 3,
                'activate_scan' => 1,
            ])
            ->assertRedirect(route('admin.presensi.index', ['pertemuan' => 3]))
            ->assertSessionHas('success', 'Sesi Pertemuan 3 berhasil DIBUKA.');

        $pengaturan->refresh();
        $this->assertSame(3, $pengaturan->pertemuan_aktif);
        $this->assertTrue($pengaturan->is_scan_aktif);

        // On active tab P3, toggle_scan closes scan while keeping pertemuan_aktif = 3
        $this->actingAs($admin)
            ->get(route('admin.presensi.index', ['pertemuan' => 3]))
            ->assertOk()
            ->assertSee('Tutup Scan P3');

        $this->actingAs($admin)
            ->from(route('admin.presensi.index', ['pertemuan' => 3]))
            ->post(route('admin.presensi.sesi'), [
                'pertemuan_aktif' => 3,
                'toggle_scan' => 1,
            ])
            ->assertRedirect(route('admin.presensi.index', ['pertemuan' => 3]))
            ->assertSessionHas('success', 'Sesi Pertemuan 3 berhasil DITUTUP.');

        $pengaturan->refresh();
        $this->assertSame(3, $pengaturan->pertemuan_aktif);
        $this->assertFalse($pengaturan->is_scan_aktif);

        // Public /absen scanner page renders with cache-busted pokja-qr-scanner.js
        $this->get(route('presensi.absen'))
            ->assertOk()
            ->assertSee('pokja-qr-scanner.js?v=');
    }
}
