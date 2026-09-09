<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pokja.com'],
            [
                'name' => 'Administrator POKJA',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
