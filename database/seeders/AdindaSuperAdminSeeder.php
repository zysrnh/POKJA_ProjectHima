<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdindaSuperAdminSeeder extends Seeder
{
    /**
     * Seed satu akun Super Admin untuk Adinda tanpa menyentuh data admin lain.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'adinda@hmif.my.id'],
            [
                'name' => 'Adinda',
                'password' => Hash::make('admin12345'),
                'email_verified_at' => now(),
            ]
        );
    }
}
