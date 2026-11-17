<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * Halaman utama sekarang berfungsi sebagai Landing Page Login Mahasiswa
     * karena pendaftaran peserta telah ditutup.
     */
    public function index(Request $request)
    {
        if (session()->has('mahasiswa_id')) {
            if (MahasiswaController::validateActiveDeviceSession($request)) {
                return redirect()->route('mahasiswa.dashboard');
            }

            return redirect()->route('mahasiswa.login')
                ->with('error', 'Sesi Anda telah berakhir karena akun ini baru saja login di perangkat lain.');
        }

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

        return view('welcome', compact('pengaturan'));
    }

    /**
     * Pendaftaran ditutup: tolak submission baru dan arahkan kembali ke halaman login.
     */
    public function store(Request $request)
    {
        return redirect()->route('pendaftaran.index')
            ->with('error', 'Pendaftaran POKJA HIMA IF telah ditutup. Silakan login menggunakan akun yang sudah terdaftar.');
    }

    /**
     * Menampilkan halaman sukses setelah pendaftaran.
     */
    public function sukses()
    {
        $pengaturan = Pengaturan::find(1) ?? new Pengaturan();
        $pendaftaran = null;
        if (session()->has('pendaftaran_id')) {
            $pendaftaran = Pendaftaran::find(session('pendaftaran_id'));
        }

        return view('pendaftaran.sukses', compact('pengaturan', 'pendaftaran'));
    }
}
