<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengaturan::updateOrCreate(
            ['id' => 1],
            [
                'nama_acara' => 'POKJA HIMA IF',
                'tanggal_acara' => '13 Oktober 2026',
                'jam_acara' => '08:00 WIB - Selesai',
                'lokasi_acara' => 'Ruangan 105',
                'deskripsi_acara' => 'Innovative Idea to Great Proposal',
                'cp_nama' => 'Admin HIMA IF',
                'cp_nomor' => '083861669565',
            ]
        );
    }
}
