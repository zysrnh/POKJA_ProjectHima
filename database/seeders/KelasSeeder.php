<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultClasses = [
            '1IF-01',
            '1IF-02',
            '1IF-03',
            '2IF-01',
            '2IF-02',
            '2IF-03',
            '3IF-01',
            '3IF-02',
            '4IF-01',
        ];

        foreach ($defaultClasses as $kelasName) {
            Kelas::updateOrCreate(
                ['nama_kelas' => $kelasName],
                ['is_active' => true]
            );
        }
    }
}
