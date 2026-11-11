<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $seeders = [
            AdminUserSeeder::class,
            KelasSeeder::class,
            PengaturanSeeder::class,
        ];

        if (app()->environment('local')) {
            $seeders[] = MahasiswaDummySeeder::class;
        }

        $this->call($seeders);
    }
}
