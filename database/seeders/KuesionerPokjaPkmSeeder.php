<?php

namespace Database\Seeders;

use App\Models\KuesionerPertanyaan;
use Illuminate\Database\Seeder;

class KuesionerPokjaPkmSeeder extends Seeder
{
    /**
     * Seed daftar pertanyaan Kuesioner Pokja PKM (Tanggal + Pilihan Ganda + Uraian).
     */
    public function run(): void
    {
        $opsiSkala = [
            'Sangat Setuju',
            'Setuju',
            'Netral',
            'Tidak Setuju',
        ];

        $items = [
            [
                'urutan' => 1,
                'pertanyaan' => 'Tanggal Pelaksanaan Kegiatan',
                'tipe' => 'tanggal',
                'opsi' => null,
            ],
            [
                'urutan' => 2,
                'pertanyaan' => 'Kegiatan Pokja PKM dilaksanakan sesuai dengan jadwal yang telah ditentukan',
                'tipe' => 'pilihan',
                'opsi' => $opsiSkala,
            ],
            [
                'urutan' => 3,
                'pertanyaan' => 'Fasilitas dan tempat pelaksanaan kegiatan sudah memadai',
                'tipe' => 'pilihan',
                'opsi' => $opsiSkala,
            ],
            [
                'urutan' => 4,
                'pertanyaan' => 'Materi yang disampaikan sesuai dengan kebutuhan peserta dalam menyusun proposal PKM',
                'tipe' => 'pilihan',
                'opsi' => $opsiSkala,
            ],
            [
                'urutan' => 5,
                'pertanyaan' => 'Sesi tanya jawab dan evaluasi membantu peserta memahami materi lebih dalam',
                'tipe' => 'pilihan',
                'opsi' => $opsiSkala,
            ],
            [
                'urutan' => 6,
                'pertanyaan' => 'Panitia memberikan arahan kegiatan dengan jelas',
                'tipe' => 'pilihan',
                'opsi' => $opsiSkala,
            ],
            [
                'urutan' => 7,
                'pertanyaan' => 'Panitia bersikap responsif terhadap pertanyaan dan kendala peserta',
                'tipe' => 'pilihan',
                'opsi' => $opsiSkala,
            ],
            [
                'urutan' => 8,
                'pertanyaan' => 'Saya menjadi lebih memahami proses penyusunan proposal PKM',
                'tipe' => 'pilihan',
                'opsi' => $opsiSkala,
            ],
            [
                'urutan' => 9,
                'pertanyaan' => 'Secara keseluruhan, kegiatan Pokja PKM memberikan manfaat bagi saya',
                'tipe' => 'pilihan',
                'opsi' => $opsiSkala,
            ],
            [
                'urutan' => 10,
                'pertanyaan' => 'Apa hal yang paling kamu sukai dari kegiatan Pokja PKM ini?',
                'tipe' => 'paragraf',
                'opsi' => null,
            ],
            [
                'urutan' => 11,
                'pertanyaan' => 'Apa kendala atau kesulitan yang kamu alami selama mengikuti kegiatan?',
                'tipe' => 'paragraf',
                'opsi' => null,
            ],
            [
                'urutan' => 12,
                'pertanyaan' => 'Menurut kamu, bagian apa yang masih perlu diperbaiki dari pelaksanaan Pokja PKM ini?',
                'tipe' => 'paragraf',
                'opsi' => null,
            ],
            [
                'urutan' => 13,
                'pertanyaan' => 'Apa saran kamu untuk pelaksanaan kegiatan Pokja PKM selanjutnya?',
                'tipe' => 'paragraf',
                'opsi' => null,
            ],
            [
                'urutan' => 14,
                'pertanyaan' => 'Apakah kamu tertarik untuk mengikuti kegiatan yang akan diadakan oleh HMIF di kesempatan berikutnya? Mengapa?',
                'tipe' => 'paragraf',
                'opsi' => null,
            ],
        ];

        foreach ($items as $item) {
            KuesionerPertanyaan::updateOrCreate(
                [
                    'pertemuan' => null,
                    'pertanyaan' => $item['pertanyaan'],
                ],
                [
                    'tipe' => $item['tipe'],
                    'opsi' => $item['opsi'],
                    'wajib' => true,
                    'urutan' => $item['urutan'],
                    'aktif' => true,
                ]
            );
        }
    }
}
