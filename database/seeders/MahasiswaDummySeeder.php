<?php

namespace Database\Seeders;

use App\Models\Pendaftaran;
use App\Models\PointHistory;
use App\Models\Presensi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaDummySeeder extends Seeder
{
    /**
     * Seed data mahasiswa dummy & riwayat point untuk pengujian lokal (tanpa menyentuh Google Spreadsheet).
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('mahasiswa123');

        $students = [
            [
                'nim' => '250414001',
                'nama' => 'Zaki Yusron H',
                'email' => 'zakiyh782@gmail.com',
                'kelas' => '2IF-01',
                'no_telp' => '083861669565',
                'hadir_pertemuan' => [1, 2, 3],
                'points' => [
                    ['tipe' => 'tambah', 'kategori' => 'Kehadiran', 'jumlah' => 1, 'keterangan' => 'Hadir tepat waktu Sesi 1'],
                    ['tipe' => 'tambah', 'kategori' => 'Aktif di Kelas', 'jumlah' => 1, 'keterangan' => 'Bertanya saat sesi materi proposal'],
                    ['tipe' => 'tambah', 'kategori' => 'Presentasi', 'jumlah' => 3, 'keterangan' => 'Presentasi ide proposal tim'],
                    ['tipe' => 'tambah', 'kategori' => 'Tugas Tepat Waktu', 'jumlah' => 2, 'keterangan' => 'Mengumpulkan draft tepat waktu'],
                ],
            ],
            [
                'nim' => '250414002',
                'nama' => 'Randie Azahli',
                'email' => 'randie.azahli@fellow.lpkia.ac.id',
                'kelas' => '2IF-01',
                'no_telp' => '085700321122',
                'hadir_pertemuan' => [1, 2],
                'points' => [
                    ['tipe' => 'tambah', 'kategori' => 'Kehadiran', 'jumlah' => 1, 'keterangan' => 'Hadir tepat waktu Sesi 1'],
                    ['tipe' => 'tambah', 'kategori' => 'Tugas Tepat Waktu', 'jumlah' => 2, 'keterangan' => 'Submit tugas analisis masalah'],
                    ['tipe' => 'tambah', 'kategori' => 'Presentasi', 'jumlah' => 3, 'keterangan' => 'Presentasi kelompok terbaik'],
                ],
            ],
            [
                'nim' => '250414003',
                'nama' => 'Siti Nabila Putri',
                'email' => 'siti.nabila@fellow.lpkia.ac.id',
                'kelas' => '1IF-01',
                'no_telp' => '081223344556',
                'hadir_pertemuan' => [1, 2, 3],
                'points' => [
                    ['tipe' => 'tambah', 'kategori' => 'Kehadiran', 'jumlah' => 1, 'keterangan' => 'Hadir Sesi 1'],
                    ['tipe' => 'tambah', 'kategori' => 'Aktif di Kelas', 'jumlah' => 1, 'keterangan' => 'Menjawab kuis pemateri'],
                    ['tipe' => 'tambah', 'kategori' => 'Tugas Tepat Waktu', 'jumlah' => 2, 'keterangan' => 'Pengumpulan latihan proposal'],
                ],
            ],
            [
                'nim' => '250414004',
                'nama' => 'Riyan Handriyana',
                'email' => 'riyan.h@fellow.lpkia.ac.id',
                'kelas' => '1IF-02',
                'no_telp' => '082112233445',
                'hadir_pertemuan' => [1, 2],
                'points' => [
                    ['tipe' => 'tambah', 'kategori' => 'Kehadiran', 'jumlah' => 1, 'keterangan' => 'Hadir Sesi 1'],
                    ['tipe' => 'tambah', 'kategori' => 'Presentasi', 'jumlah' => 3, 'keterangan' => 'Presentasi bab pendahuluan'],
                    ['tipe' => 'kurang', 'kategori' => 'Terlambat Kelas', 'jumlah' => 1, 'keterangan' => 'Terlambat 15 menit di Sesi 2'],
                ],
            ],
            [
                'nim' => '250414005',
                'nama' => 'Dimas Pratama Putra',
                'email' => 'dimas.pratama@fellow.lpkia.ac.id',
                'kelas' => '2IF-02',
                'no_telp' => '089655443322',
                'hadir_pertemuan' => [1],
                'points' => [
                    ['tipe' => 'tambah', 'kategori' => 'Kehadiran', 'jumlah' => 1, 'keterangan' => 'Hadir Sesi 1'],
                    ['tipe' => 'tambah', 'kategori' => 'Aktif di Kelas', 'jumlah' => 1, 'keterangan' => 'Aktif berdiskusi di kelas'],
                ],
            ],
            [
                'nim' => '250414006',
                'nama' => 'Alya Rahmawati',
                'email' => 'alya.rahma@fellow.lpkia.ac.id',
                'kelas' => '1IF-01',
                'no_telp' => '087811223344',
                'hadir_pertemuan' => [1, 2],
                'points' => [
                    ['tipe' => 'tambah', 'kategori' => 'Tugas Tepat Waktu', 'jumlah' => 2, 'keterangan' => 'Mengumpulkan revisi tepat waktu'],
                ],
            ],
            [
                'nim' => '250414007',
                'nama' => 'Fajar Nugraha',
                'email' => 'fajar.nugraha@fellow.lpkia.ac.id',
                'kelas' => '1IF-02',
                'no_telp' => '085211998877',
                'hadir_pertemuan' => [1],
                'points' => [
                    ['tipe' => 'tambah', 'kategori' => 'Kehadiran', 'jumlah' => 1, 'keterangan' => 'Hadir Sesi 1'],
                ],
            ],
            [
                'nim' => '250414008',
                'nama' => 'Bagas Aditya',
                'email' => 'bagas.aditya@fellow.lpkia.ac.id',
                'kelas' => '2IF-02',
                'no_telp' => '081399887766',
                'hadir_pertemuan' => [],
                'points' => [],
            ],
        ];

        foreach ($students as $data) {
            $mhs = Pendaftaran::updateOrCreate(
                ['nim' => $data['nim']],
                [
                    'nama' => $data['nama'],
                    'email' => $data['email'],
                    'password' => $defaultPassword,
                    'kelas' => $data['kelas'],
                    'no_telp' => $data['no_telp'],
                    'status_kehadiran' => count($data['hadir_pertemuan']) > 0 ? 'hadir' : 'belum_hadir',
                ]
            );

            // Seed Presensi
            foreach ($data['hadir_pertemuan'] as $sesi) {
                Presensi::updateOrCreate(
                    [
                        'pendaftaran_id' => $mhs->id,
                        'pertemuan' => $sesi,
                    ],
                    [
                        'status' => 'hadir',
                        'waktu_hadir' => now()->subDays(max(0, 3 - $sesi)),
                    ]
                );
            }

            // Seed Point Histories (hanya jika belum ada riwayat agar tidak dobel saat re-seed)
            if ($mhs->pointHistories()->count() === 0 && !empty($data['points'])) {
                $total = 0;
                foreach ($data['points'] as $pt) {
                    PointHistory::create([
                        'pendaftaran_id' => $mhs->id,
                        'tipe' => $pt['tipe'],
                        'kategori' => $pt['kategori'],
                        'jumlah' => $pt['jumlah'],
                        'keterangan' => $pt['keterangan'],
                        'created_by' => 'Nao',
                    ]);

                    if ($pt['tipe'] === 'tambah') {
                        $total += $pt['jumlah'];
                    } else {
                        $total = max(0, $total - $pt['jumlah']);
                    }
                }

                $mhs->update(['total_point' => $total]);
            }
        }
    }
}

