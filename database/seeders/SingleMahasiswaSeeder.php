<?php

namespace Database\Seeders;

use App\Models\Pendaftaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SingleMahasiswaSeeder extends Seeder
{
    /**
     * Seed satu akun mahasiswa khusus tanpa menyentuh data peserta lain.
     */
    public function run(): void
    {
        Pendaftaran::updateOrCreate(
            ['nim' => '250414099'],
            [
                'nama' => 'Nao Mahasiswa',
                'email' => 'nao.mahasiswa@fellow.lpkia.ac.id',
                'password' => Hash::make('mahasiswa123'),
                'kelas' => '2IF-01',
                'no_telp' => '081234567890',
                'status_kehadiran' => 'belum_hadir',
            ]
        );
    }
}
