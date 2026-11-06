<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan halaman login untuk mahasiswa.
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
     * Memproses login mahasiswa biasa (Email / NIM + Password).
     */
    public function login(Request $request)
    {
        $request->merge([
            'identifier' => trim(strip_tags($request->input('identifier', ''))),
        ]);

        $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'identifier.required' => 'Email kampus atau NIM wajib diisi.',
            'password.required' => 'Password akun wajib diisi.',
        ]);

        $identifier = $request->identifier;

        // Cari berdasarkan Email atau NIM
        $mahasiswa = Pendaftaran::where('email', $identifier)
            ->orWhere('nim', $identifier)
            ->first();

        if (!$mahasiswa) {
            return back()
                ->withInput(['identifier' => $identifier])
                ->with('error', 'Akun tidak ditemukan. Pastikan Anda telah terdaftar sebagai peserta POKJA.');
        }

        // Cek jika akun belum pernah setting password
        if (empty($mahasiswa->password)) {
            return back()
                ->withInput(['identifier' => $identifier])
                ->with('error', 'Akun Anda belum memiliki password. Silakan gunakan tab "Aktivasi QR Tiket" untuk membuat password pertama kali.');
        }

        // Verifikasi password
        if (!Hash::check($request->password, $mahasiswa->password)) {
            return back()
                ->withInput(['identifier' => $identifier])
                ->with('error', 'Password yang Anda masukkan salah. Silakan coba lagi.');
        }

        // Simpan sesi autentikasi mahasiswa
        session([
            'mahasiswa_id' => $mahasiswa->id,
            'mahasiswa_nim' => $mahasiswa->nim,
            'mahasiswa_nama' => $mahasiswa->nama,
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Selamat datang kembali, ' . $mahasiswa->nama . '!');
    }

    /**
     * Memproses verifikasi QR Code pendaftaran untuk aktivasi akun pertama kali.
     */
    public function verifikasiQr(Request $request)
    {
        $rawToken = trim($request->input('token', ''));

        if (empty($rawToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode atau QR tiket tidak boleh kosong.',
            ], 422);
        }

        // Jika input berupa URL penuh (misal hasil scan QR kamera), ekstrak parameter 'token'
        if (str_contains($rawToken, 'token=')) {
            parse_str(parse_url($rawToken, PHP_URL_QUERY) ?? '', $queryParams);
            if (!empty($queryParams['token'])) {
                $rawToken = $queryParams['token'];
            }
        }

        // Cari berdasarkan qr_token rahasia ATAU nim (fallback jika QR tiket fisik/email berisi nim)
        $mahasiswa = Pendaftaran::where('qr_token', $rawToken)
            ->orWhere('nim', $rawToken)
            ->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'QR Tiket pendaftaran tidak valid atau tidak terdaftar di sistem.',
            ], 404);
        }

        // Simpan sesi aktivasi sementara
        session(['aktivasi_mahasiswa_id' => $mahasiswa->id]);

        return response()->json([
            'success' => true,
            'nama' => $mahasiswa->nama,
            'nim' => $mahasiswa->nim,
            'redirect' => route('mahasiswa.setPassword'),
        ]);
    }

    /**
     * Handler langsung jika mahasiswa membuka tautan aktivasi dari QR Code / Email.
     */
    public function aktivasiDirect(Request $request)
    {
        $token = trim($request->query('token', ''));

        if (!empty($token)) {
            $mahasiswa = Pendaftaran::where('qr_token', $token)->first();

            if ($mahasiswa) {
                session(['aktivasi_mahasiswa_id' => $mahasiswa->id]);
                return redirect()->route('mahasiswa.setPassword');
            }
        }

        return redirect()->route('mahasiswa.login')->with('error', 'Tautan aktivasi tidak valid.');
    }

    /**
     * Menampilkan formulir buat password untuk mahasiswa baru.
     */
    public function showSetPassword()
    {
        if (!session()->has('aktivasi_mahasiswa_id')) {
            return redirect()->route('mahasiswa.login')->with('error', 'Sesi aktivasi telah berakhir. Silakan scan/upload QR tiket Anda kembali.');
        }

        $mahasiswa = Pendaftaran::find(session('aktivasi_mahasiswa_id'));

        if (!$mahasiswa) {
            session()->forget('aktivasi_mahasiswa_id');
            return redirect()->route('mahasiswa.login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        return view('mahasiswa.set_password', compact('mahasiswa'));
    }

    /**
     * Menyimpan password baru mahasiswa dan langsung login ke dashboard.
     */
    public function savePassword(Request $request)
    {
        if (!session()->has('aktivasi_mahasiswa_id')) {
            return redirect()->route('mahasiswa.login')->with('error', 'Sesi aktivasi telah berakhir.');
        }

        $mahasiswa = Pendaftaran::find(session('aktivasi_mahasiswa_id'));

        if (!$mahasiswa) {
            session()->forget('aktivasi_mahasiswa_id');
            return redirect()->route('mahasiswa.login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password baru.',
        ]);

        $mahasiswa->password = Hash::make($request->password);
        $mahasiswa->save();

        // Hapus sesi aktivasi sementara
        session()->forget('aktivasi_mahasiswa_id');

        // Login otomatis ke dashboard
        session([
            'mahasiswa_id' => $mahasiswa->id,
            'mahasiswa_nim' => $mahasiswa->nim,
            'mahasiswa_nama' => $mahasiswa->nama,
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Password berhasil dibuat! Akun Anda kini telah aktif.');
    }

    /**
     * Menampilkan dashboard pribadi mahasiswa (profil, tiket, rekap presensi 7 pertemuan).
     */
    public function dashboard()
    {
        if (!session()->has('mahasiswa_id')) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
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
     * Memperbarui profil mahasiswa (email dan foto profil).
     */
    public function updateProfile(Request $request)
    {
        if (!session()->has('mahasiswa_id')) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
        }

        $mahasiswa = Pendaftaran::find(session('mahasiswa_id'));

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                'unique:pendaftarans,email,' . $mahasiswa->id,
            ],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran file foto maksimal 2 MB.',
        ]);

        $mahasiswa->email = strtolower(trim($request->email));

        // Upload foto profil jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'avatar_' . $mahasiswa->nim . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            $destinationPath = public_path('uploads/avatars');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Hapus foto lama jika ada
            if (!empty($mahasiswa->foto) && file_exists(public_path('uploads/avatars/' . $mahasiswa->foto))) {
                @unlink(public_path('uploads/avatars/' . $mahasiswa->foto));
            }

            $file->move($destinationPath, $filename);
            $mahasiswa->foto = $filename;
        }

        $mahasiswa->save();

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Logout mahasiswa dari dashboard.
     */
    public function logout()
    {
        session()->forget(['mahasiswa_id', 'mahasiswa_nim', 'mahasiswa_nama']);

        return redirect()->route('mahasiswa.login')->with('success', 'Anda telah berhasil keluar dari akun mahasiswa.');
    }
}
