<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanAcaraController extends Controller
{
    /**
     * Menampilkan form pengaturan informasi acara dan contact person.
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
                'deskripsi_acara' => 'Innovative Idea to Great Proposal',
                'cp_nama' => 'Admin HIMA IF',
                'cp_nomor' => '083861669565',
            ]
        );

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    /**
     * Memperbarui pengaturan informasi acara dan contact person.
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
            'cp_nama' => trim(strip_tags($request->input('cp_nama', ''))),
            'cp_nomor' => trim(strip_tags($request->input('cp_nomor', ''))),
        ]);

        // Normalisasi nomor telepon CP
        $rawPhone = preg_replace('/[^0-9]/', '', $request->input('cp_nomor'));
        if (str_starts_with($rawPhone, '628')) {
            $rawPhone = '08' . substr($rawPhone, 3);
        } elseif (str_starts_with($rawPhone, '8')) {
            $rawPhone = '08' . substr($rawPhone, 1);
        }
        $request->merge(['cp_nomor' => $rawPhone]);

        $validated = $request->validate([
            'nama_acara' => ['required', 'string', 'max:100'],
            'tanggal_acara' => ['required', 'string', 'max:100'],
            'jam_acara' => ['required', 'string', 'max:100'],
            'lokasi_acara' => ['required', 'string', 'max:100'],
            'deskripsi_acara' => ['nullable', 'string', 'max:500'],
            'cp_nama' => ['required', 'string', 'max:100'],
            'cp_nomor' => ['required', 'string', 'regex:/^08[1-9][0-9]{7,11}$/'],
        ], [
            'nama_acara.required' => 'Nama acara wajib diisi.',
            'tanggal_acara.required' => 'Tanggal acara wajib diisi.',
            'jam_acara.required' => 'Waktu / jam acara wajib diisi.',
            'lokasi_acara.required' => 'Lokasi / ruangan acara wajib diisi.',
            'cp_nama.required' => 'Nama Contact Person (CP) wajib diisi.',
            'cp_nomor.required' => 'Nomor WhatsApp Contact Person wajib diisi.',
            'cp_nomor.regex' => 'Format nomor WhatsApp CP harus diawali 08 (panjang 10–14 digit).',
        ]);

        $pengaturan->update($validated);

        return redirect()->route('admin.pengaturan.index')->with('success', 'Informasi acara dan Contact Person berhasil diperbarui!');
    }
}
