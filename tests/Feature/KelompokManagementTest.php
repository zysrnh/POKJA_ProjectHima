<?php

namespace Tests\Feature;

use App\Models\Kelompok;
use App\Models\KelompokInvitation;
use App\Models\KelompokMember;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class KelompokManagementTest extends TestCase
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

            $relative = 'database/migrations/' . basename($migrationFile);
            Artisan::call('migrate', ['--path' => $relative]);
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

    private function makeAdmin(string $email = 'panitia@hmif.my.id'): User
    {
        return User::create([
            'name' => 'Admin Test',
            'email' => $email,
            'password' => Hash::make('Password123'),
        ]);
    }

    private function makeMahasiswa(int $num): Pendaftaran
    {
        return Pendaftaran::create([
            'nama' => "Mahasiswa {$num}",
            'nim' => sprintf('250100%02d', $num),
            'email' => "mhs{$num}@student.test",
            'password' => Hash::make('Password123'),
            'kelas' => 'IF-1',
            'no_telp' => '081234567890',
            'status_kehadiran' => 'hadir',
            'total_point' => $num * 5,
        ]);
    }

    public function test_regular_admin_cannot_access_kelompok_management_and_sidebar_menu_hidden(): void
    {
        $regularAdmin = $this->makeAdmin('biasa@hmif.my.id');
        $mhs = $this->makeMahasiswa(1);

        $this->actingAs($regularAdmin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('Kelola Kelompok');

        $this->actingAs($regularAdmin)
            ->get(route('admin.kelompok.index'))
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('error');

        $this->actingAs($regularAdmin)
            ->post(route('admin.kelompok.store'), [
                'nama_kelompok' => 'Tim Ilegal',
                'ketua_id' => $mhs->id,
            ])
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('kelompoks', 0);
    }

    public function test_super_admin_can_create_edit_manage_members_and_disband_kelompok(): void
    {
        $superAdmin = $this->makeAdmin(User::SUPER_ADMIN_EMAIL);
        $m1 = $this->makeMahasiswa(1);
        $m2 = $this->makeMahasiswa(2);
        $m3 = $this->makeMahasiswa(3);
        $m4 = $this->makeMahasiswa(4);
        $m5 = $this->makeMahasiswa(5);

        // Super Admin sees Kelola Kelompok menu on dashboard & can open index/create views
        $this->actingAs($superAdmin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Kelola Kelompok');

        $this->actingAs($superAdmin)
            ->get(route('admin.kelompok.index', ['status' => 'kurang']))
            ->assertOk();

        $this->actingAs($superAdmin)
            ->get(route('admin.kelompok.create'))
            ->assertOk()
            ->assertSee($m1->nama);

        // Super Admin creates group with 1 Ketua + 2 Anggota
        $this->actingAs($superAdmin)
            ->post(route('admin.kelompok.store'), [
                'nama_kelompok' => 'Tim Alpha',
                'judul_proposal' => 'Sistem Absensi Pintar',
                'ketua_id' => $m1->id,
                'anggota_ids' => [$m2->id, $m3->id],
            ])
            ->assertRedirect();

        $kelompok = Kelompok::where('nama_kelompok', 'Tim Alpha')->first();
        $this->assertNotNull($kelompok);
        $this->assertSame($m1->id, (int) $kelompok->ketua_id);
        $this->assertSame(3, $kelompok->members()->count());

        // Edit page renders cleanly
        $this->actingAs($superAdmin)
            ->get(route('admin.kelompok.edit', $kelompok->id))
            ->assertOk()
            ->assertSee('Tim Alpha')
            ->assertSee($m4->nama);

        // Filter query checks
        $this->actingAs($superAdmin)
            ->get(route('admin.kelompok.index', ['status' => 'memenuhi', 'search' => 'Alpha']))
            ->assertOk()
            ->assertSee('Tim Alpha');

        // Cannot exceed MAX_ANGGOTA = 4
        $this->actingAs($superAdmin)
            ->post(route('admin.kelompok.members.add', $kelompok->id), [
                'pendaftaran_ids' => [$m4->id, $m5->id],
            ])
            ->assertRedirect(route('admin.kelompok.edit', $kelompok->id))
            ->assertSessionHas('error');
        $this->assertSame(3, $kelompok->members()->count());

        // Add 1 member (reaches 4/4)
        $this->actingAs($superAdmin)
            ->post(route('admin.kelompok.members.add', $kelompok->id), [
                'pendaftaran_ids' => [$m4->id],
            ])
            ->assertRedirect(route('admin.kelompok.edit', $kelompok->id))
            ->assertSessionHas('success');
        $this->assertSame(4, $kelompok->members()->count());

        // Transfer ketua to m2
        $m2Membership = KelompokMember::where('kelompok_id', $kelompok->id)
            ->where('pendaftaran_id', $m2->id)
            ->firstOrFail();

        $this->actingAs($superAdmin)
            ->patch(route('admin.kelompok.ketua.set', [$kelompok->id, $m2Membership->id]))
            ->assertRedirect(route('admin.kelompok.edit', $kelompok->id))
            ->assertSessionHas('success');

        $kelompok->refresh();
        $this->assertSame($m2->id, (int) $kelompok->ketua_id);

        // Cannot remove active ketua directly
        $this->actingAs($superAdmin)
            ->delete(route('admin.kelompok.members.remove', [$kelompok->id, $m2Membership->id]))
            ->assertRedirect(route('admin.kelompok.edit', $kelompok->id))
            ->assertSessionHas('error');

        // Can remove non-ketua member m4
        $m4Membership = KelompokMember::where('kelompok_id', $kelompok->id)
            ->where('pendaftaran_id', $m4->id)
            ->firstOrFail();

        $this->actingAs($superAdmin)
            ->delete(route('admin.kelompok.members.remove', [$kelompok->id, $m4Membership->id]))
            ->assertRedirect(route('admin.kelompok.edit', $kelompok->id))
            ->assertSessionHas('success');
        $this->assertSame(3, $kelompok->members()->count());

        // Super Admin can rename group & update proposal
        $this->actingAs($superAdmin)
            ->put(route('admin.kelompok.update', $kelompok->id), [
                'nama_kelompok' => 'Tim Alpha Revisi',
                'judul_proposal' => 'Proposal Final',
            ])
            ->assertRedirect(route('admin.kelompok.edit', $kelompok->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('kelompoks', [
            'id' => $kelompok->id,
            'nama_kelompok' => 'Tim Alpha Revisi',
            'judul_proposal' => 'Proposal Final',
        ]);

        // Super Admin can disband group
        $this->actingAs($superAdmin)
            ->delete(route('admin.kelompok.destroy', $kelompok->id))
            ->assertRedirect(route('admin.kelompok.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('kelompoks', 0);
        $this->assertDatabaseCount('kelompok_members', 0);
    }

    public function test_mahasiswa_cannot_self_create_join_leave_or_rename_kelompok(): void
    {
        $m1 = $this->makeMahasiswa(10);
        $m2 = $this->makeMahasiswa(11);

        $sessionData = [
            'mahasiswa_id' => $m1->id,
            'mahasiswa_nim' => $m1->nim,
            'mahasiswa_nama' => $m1->nama,
        ];

        // Student sees managed-by-SU notice when unassigned
        $this->withSession($sessionData)
            ->get(route('mahasiswa.dashboard', ['tab' => 'kelompok']))
            ->assertOk()
            ->assertSee('Dikelola Super Admin')
            ->assertDontSee('Buat Kelompok Sendiri');

        // Student cannot create group
        $this->withSession($sessionData)
            ->post(route('mahasiswa.kelompok.store'), [
                'nama_kelompok' => 'Tim Mahasiswa Sendiri',
            ])
            ->assertRedirect(route('mahasiswa.dashboard', ['tab' => 'kelompok']))
            ->assertSessionHas('error');
        $this->assertDatabaseCount('kelompoks', 0);

        // Super Admin creates group for m1 (Ketua) and m2 (Anggota)
        $kelompok = Kelompok::create([
            'nama_kelompok' => 'Tim Resmi SU',
            'kode_invite' => 'PKJ-RESMI1',
            'ketua_id' => $m1->id,
            'judul_proposal' => 'Judul Awal',
        ]);
        KelompokMember::create([
            'kelompok_id' => $kelompok->id,
            'pendaftaran_id' => $m1->id,
            'role' => 'ketua',
            'joined_at' => now(),
        ]);
        $m2Member = KelompokMember::create([
            'kelompok_id' => $kelompok->id,
            'pendaftaran_id' => $m2->id,
            'role' => 'anggota',
            'joined_at' => now(),
        ]);

        // Verify sidebar/dashboard lock UI for student (no self-create/disband controls)
        $this->withSession($sessionData)
            ->get(route('mahasiswa.dashboard', ['tab' => 'kelompok']))
            ->assertOk()
            ->assertSee('Tim Resmi SU')
            ->assertSee('Edit Proposal / Foto')
            ->assertDontSee('Bubarkan Kelompok');

        // Student Ketua cannot rename group via update endpoint, only judul_proposal updates
        $this->withSession($sessionData)
            ->put(route('mahasiswa.kelompok.update'), [
                'nama_kelompok' => 'Nama Bajakan',
                'judul_proposal' => 'Judul Baru Ketua',
            ])
            ->assertRedirect(route('mahasiswa.dashboard', ['tab' => 'kelompok']))
            ->assertSessionHas('success');

        $kelompok->refresh();
        $this->assertSame('Tim Resmi SU', $kelompok->nama_kelompok);
        $this->assertSame('Judul Baru Ketua', $kelompok->judul_proposal);

        // Verify realtime dashboard sync endpoint works cleanly with Super Admin assigned team
        $this->withSession($sessionData)
            ->get(route('mahasiswa.dashboard.sync'))
            ->assertOk()
            ->assertJson([
                'authenticated' => true,
                'changed' => true,
                'kelompok' => [
                    'has_group' => true,
                    'is_ketua' => true,
                ],
            ]);

        // Student Ketua cannot remove member, transfer ketua, or disband group
        $this->withSession($sessionData)
            ->delete(route('mahasiswa.kelompok.removeMember', $m2Member->id))
            ->assertRedirect(route('mahasiswa.dashboard', ['tab' => 'kelompok']))
            ->assertSessionHas('error');

        $this->withSession($sessionData)
            ->post(route('mahasiswa.kelompok.transferKetua', $m2Member->id))
            ->assertRedirect(route('mahasiswa.dashboard', ['tab' => 'kelompok']))
            ->assertSessionHas('error');

        $this->withSession($sessionData)
            ->post(route('mahasiswa.kelompok.leave'))
            ->assertRedirect(route('mahasiswa.dashboard', ['tab' => 'kelompok']))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('kelompoks', ['id' => $kelompok->id]);
        $this->assertSame(2, $kelompok->members()->count());

        // Pending invitation respond/join is also rejected
        $inv = KelompokInvitation::create([
            'kelompok_id' => $kelompok->id,
            'pendaftaran_id' => $m1->id,
            'invited_by' => $m1->id,
            'status' => 'pending',
        ]);

        $this->withSession($sessionData)
            ->post(route('mahasiswa.kelompok.respondInvite', $inv->id), ['action' => 'accept'])
            ->assertRedirect(route('mahasiswa.dashboard', ['tab' => 'kelompok']))
            ->assertSessionHas('error');

        $this->withSession($sessionData)
            ->get(route('mahasiswa.kelompok.joinLink', ['kode' => 'PKJ-RESMI1']))
            ->assertRedirect(route('mahasiswa.dashboard', ['tab' => 'kelompok']))
            ->assertSessionHas('error');

        $this->withSession($sessionData)
            ->post(route('mahasiswa.kelompok.joinCode'), ['kode_invite' => 'PKJ-RESMI1'])
            ->assertRedirect(route('mahasiswa.dashboard', ['tab' => 'kelompok']))
            ->assertSessionHas('error');
    }
}
