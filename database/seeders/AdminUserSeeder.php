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
        $admins = [
            [
                'email' => 'naoo@hmif.my.id',
                'name' => 'Nao',
                'password' => Hash::make('admin12345'),
            ],
            [
                'email' => 'admin1@hmif.my.id',
                'name' => 'Admin 1 POKJA',
                'password' => Hash::make('admin12345'),
            ],
            [
                'email' => 'admin2@hmif.my.id',
                'name' => 'Admin 2 POKJA',
                'password' => Hash::make('admin12345'),
            ],
            [
                'email' => 'presensi@pokja.com',
                'name' => 'Admin Presensi & Acara',
                'password' => Hash::make('HimaIF2026*Presensi!Acc'),
            ],
            [
                'email' => 'sekretariat@pokja.com',
                'name' => 'Admin Sekretariat',
                'password' => Hash::make('Pokja2026$Sekretariat!Pro'),
            ],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => $admin['password'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
