<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan halaman form pendaftaran.
     */
    public function index()
    {
        $kelasList = Kelas::where('is_active', true)->orderBy('nama_kelas')->get();

        return view('welcome', compact('kelasList'));
    }

    /**
     * Memproses penyimpanan pendaftaran baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:50', 'unique:pendaftarans,nim'],
            'kelas' => ['required', 'string', 'max:100'],
            'no_telp' => ['required', 'string', 'max:30'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM ini sudah terdaftar sebelumnya.',
            'kelas.required' => 'Silakan pilih kelas Anda.',
            'no_telp.required' => 'No. Telepon / WhatsApp wajib diisi.',
        ]);

        Pendaftaran::create($validated);

        return redirect()->back()->with('success', 'Pendaftaran Anda berhasil dikirim!');
    }
}
