<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan halaman login untuk mahasiswa (menggunakan NIM terdaftar).
     */
    public function showLogin()
    {
        if (session()->has('mahasiswa_id')) {
            return redirect()->route('mahasiswa.dashboard');
        }

        $pengaturan = Pengaturan::first();

        return view('mahasiswa.login', compact('pengaturan'));
    }

    /**
     * Memproses login mahasiswa berdasarkan NIM 9 digit terdaftar.
     */
    public function login(Request $request)
    {
        $request->merge([
            'nim' => trim(strip_tags($request->input('nim', ''))),
        ]);

        $request->validate([
            'nim' => ['required', 'numeric', 'digits:9'],
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.numeric' => 'NIM harus berupa angka.',
            'nim.digits' => 'NIM harus tepat 9 digit angka kampus.',
        ]);

        $mahasiswa = Pendaftaran::where('nim', $request->nim)->first();

        if (!$mahasiswa) {
            return back()
                ->withInput()
                ->with('error', 'NIM ' . $request->nim . ' belum terdaftar sebagai peserta POKJA. Silakan daftar melalui formulir terlebih dahulu.');
        }

        // Simpan sesi autentikasi mahasiswa
        session([
            'mahasiswa_id' => $mahasiswa->id,
            'mahasiswa_nim' => $mahasiswa->nim,
            'mahasiswa_nama' => $mahasiswa->nama,
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Selamat datang, ' . $mahasiswa->nama . '!');
    }

    /**
     * Menampilkan dashboard pribadi mahasiswa (profil, tiket, rekap presensi 7 pertemuan).
     */
    public function dashboard()
    {
        if (!session()->has('mahasiswa_id')) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan masuk dengan NIM Anda terlebih dahulu.');
        }

        $mahasiswa = Pendaftaran::with('presensis')->find(session('mahasiswa_id'));

        if (!$mahasiswa) {
            session()->forget(['mahasiswa_id', 'mahasiswa_nim', 'mahasiswa_nama']);
            return redirect()->route('mahasiswa.login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $pengaturan = Pengaturan::first();
        $presensiMap = $mahasiswa->presensis->keyBy('pertemuan');
        $totalHadir = $mahasiswa->presensis->where('status', 'hadir')->count();
        $persenHadir = round(($totalHadir / 7) * 100);

        return view('mahasiswa.dashboard', compact('mahasiswa', 'pengaturan', 'presensiMap', 'totalHadir', 'persenHadir'));
    }

    /**
     * Logout mahasiswa dari dashboard.
     */
    public function logout()
    {
        session()->forget(['mahasiswa_id', 'mahasiswa_nim', 'mahasiswa_nama']);

        return redirect()->route('mahasiswa.login')->with('success', 'Anda telah berhasil keluar.');
    }
}
