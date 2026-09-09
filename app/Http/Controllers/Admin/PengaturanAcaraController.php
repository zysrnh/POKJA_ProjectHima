<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanAcaraController extends Controller
{
    /**
     * Menampilkan form pengaturan informasi acara.
     */
    public function index()
    {
        $pengaturan = Pengaturan::firstOrCreate(
            ['id' => 1],
            [
                'nama_acara' => 'POKJA HIMA IF',
                'tanggal_acara' => '13 Oktober 2026',
                'jam_acara' => '08:00 WIB - Selesai',
                'lokasi_acara' => 'Ruangan 105',
                'deskripsi_acara' => 'Pelaksanaan Program Kerja Himpunan Mahasiswa Informatika (HIMA IF)',
            ]
        );

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    /**
     * Memperbarui pengaturan informasi acara.
     */
    public function update(Request $request)
    {
        $pengaturan = Pengaturan::firstOrCreate(['id' => 1]);

        // Sanitasi
        $request->merge([
            'nama_acara' => trim(strip_tags($request->input('nama_acara', ''))),
            'tanggal_acara' => trim(strip_tags($request->input('tanggal_acara', ''))),
            'jam_acara' => trim(strip_tags($request->input('jam_acara', ''))),
            'lokasi_acara' => trim(strip_tags($request->input('lokasi_acara', ''))),
            'deskripsi_acara' => trim(strip_tags($request->input('deskripsi_acara', ''))),
        ]);

        $validated = $request->validate([
            'nama_acara' => ['required', 'string', 'max:100'],
            'tanggal_acara' => ['required', 'string', 'max:100'],
            'jam_acara' => ['required', 'string', 'max:100'],
            'lokasi_acara' => ['required', 'string', 'max:100'],
            'deskripsi_acara' => ['nullable', 'string', 'max:500'],
        ], [
            'nama_acara.required' => 'Nama acara wajib diisi.',
            'tanggal_acara.required' => 'Tanggal acara wajib diisi.',
            'jam_acara.required' => 'Waktu / jam acara wajib diisi.',
            'lokasi_acara.required' => 'Lokasi / ruangan acara wajib diisi.',
        ]);

        $pengaturan->update($validated);

        return redirect()->route('admin.pengaturan.index')->with('success', 'Informasi acara berhasil diperbarui!');
    }
}
