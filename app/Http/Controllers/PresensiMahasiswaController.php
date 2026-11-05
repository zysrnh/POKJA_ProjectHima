<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Presensi;
use App\Models\Pengaturan;
use App\Http\Controllers\Admin\PresensiAdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PresensiMahasiswaController extends Controller
{
    /**
     * Halaman Scanner Presensi Mahasiswa di HP (/absen).
     */
    public function showAbsen()
    {
        $pengaturan = Pengaturan::first();
        $mahasiswaLogin = null;

        if (session()->has('mahasiswa_id')) {
            $mahasiswaLogin = Pendaftaran::find(session('mahasiswa_id'));
        }

        return view('presensi.absen', compact('pengaturan', 'mahasiswaLogin'));
    }

    /**
     * Verifikasi keberadaan NIM terdaftar di database via AJAX.
     */
    public function cekNim(Request $request)
    {
        $nim = trim($request->input('nim', ''));

        if (strlen($nim) !== 9 || !ctype_digit($nim)) {
            return response()->json([
                'valid' => false,
                'message' => 'NIM harus tepat 9 digit angka.',
            ], 422);
        }

        $mahasiswa = Pendaftaran::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json([
                'valid' => false,
                'message' => 'NIM tidak terdaftar sebagai peserta POKJA HIMA IF.',
            ], 404);
        }

        return response()->json([
            'valid' => true,
            'nama' => $mahasiswa->nama,
            'kelas' => $mahasiswa->kelas,
        ]);
    }

    /**
     * Proses Scan QR Presensi dari HP Mahasiswa.
     */
    public function submitScan(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|size:9',
            'qr_payload' => 'required|string',
        ]);

        $nim = trim($request->input('nim'));
        $payload = trim($request->input('qr_payload'));

        // 1. Validasi Keberadaan Mahasiswa berdasarkan NIM
        $pendaftaran = Pendaftaran::where('nim', $nim)->first();
        if (!$pendaftaran) {
            return response()->json([
                'success' => false,
                'message' => "NIM {$nim} tidak terdaftar sebagai peserta POKJA HIMA IF.",
            ], 404);
        }

        // 2. Cek Pengaturan & Status Sesi
        $pengaturan = Pengaturan::first();
        if (!$pengaturan) {
            return response()->json([
                'success' => false,
                'message' => "Pengaturan acara belum diatur oleh panitia.",
            ], 500);
        }

        if (!$pengaturan->is_scan_aktif) {
            return response()->json([
                'success' => false,
                'message' => "Sesi presensi saat ini sedang DITUTUP oleh panitia.",
            ], 403);
        }

        // 3. Parsing Format Payload: POKJA:P[Pertemuan]:[Token]:[Window]
        $parts = explode(':', $payload);
        if (count($parts) !== 4 || $parts[0] !== 'POKJA') {
            return response()->json([
                'success' => false,
                'message' => "Format QR Code tidak valid untuk presensi POKJA.",
            ], 422);
        }

        $pertemuanDariQR = (int) str_replace('P', '', $parts[1]);
        $tokenDariQR = $parts[2];
        $windowDariQR = (int) $parts[3];

        // 4. Cek apakah pertemuan QR sesuai dengan Pertemuan Aktif Panitia
        if ($pertemuanDariQR !== (int) $pengaturan->pertemuan_aktif) {
            return response()->json([
                'success' => false,
                'message' => "Sesi yang sedang dibuka panitia adalah Pertemuan {$pengaturan->pertemuan_aktif}, bukan Pertemuan {$pertemuanDariQR}.",
            ], 422);
        }

        // 5. Validasi Dynamic Token (Toleransi 15-20 detik agar aman dari delay internet HP)
        $currentWindow = (int) floor(time() / 5);
        $salt = $pengaturan->token_salt ?? 'pokja_default_salt';

        // Izinkan rentang 3 window terakhir (15 detik) untuk mencegah foto basi tapi mentolerir lag
        $isWindowValid = ($windowDariQR >= ($currentWindow - 3) && $windowDariQR <= ($currentWindow + 1));
        $expectedToken = substr(hash_hmac('sha256', "POKJA_P{$pertemuanDariQR}_{$windowDariQR}", $salt), 0, 16);

        if (!$isWindowValid || !hash_equals($expectedToken, $tokenDariQR)) {
            return response()->json([
                'success' => false,
                'message' => "QR Code sudah KEDALUWARSA (lebih dari 5 detik). Silakan scan QR terbaru yang sedang tampil di proyektor!",
            ], 422);
        }

        // 6. Cek apakah mahasiswa sudah hadir di pertemuan ini
        $sudahHadir = Presensi::where('pendaftaran_id', $pendaftaran->id)
            ->where('pertemuan', $pertemuanDariQR)
            ->where('status', 'hadir')
            ->first();

        if ($sudahHadir) {
            $jam = $sudahHadir->waktu_hadir ? $sudahHadir->waktu_hadir->timezone('Asia/Jakarta')->format('H:i:s') : '-';
            return response()->json([
                'success' => true,
                'already' => true,
                'nama' => $pendaftaran->nama,
                'nim' => $pendaftaran->nim,
                'kelas' => $pendaftaran->kelas,
                'pertemuan' => $pertemuanDariQR,
                'waktu' => $jam,
                'message' => "{$pendaftaran->nama} SUDAH tercatat hadir di Pertemuan {$pertemuanDariQR} pada pukul {$jam} WIB.",
            ]);
        }

        // 7. Simpan Presensi secara Atomic
        try {
            $presensi = Presensi::updateOrCreate(
                [
                    'pendaftaran_id' => $pendaftaran->id,
                    'pertemuan' => $pertemuanDariQR,
                ],
                [
                    'status' => 'hadir',
                    'waktu_hadir' => now(),
                ]
            );

            // Sinkronisasi otomatis ke Google Sheets tab Kehadiran
            PresensiAdminController::syncAllWithKehadiranToSheets();

            $waktuStr = $presensi->waktu_hadir->timezone('Asia/Jakarta')->format('H:i:s');

            return response()->json([
                'success' => true,
                'already' => false,
                'nama' => $pendaftaran->nama,
                'nim' => $pendaftaran->nim,
                'kelas' => $pendaftaran->kelas,
                'pertemuan' => $pertemuanDariQR,
                'waktu' => $waktuStr,
                'message' => "Hadir Berhasil! {$pendaftaran->nama} ({$pendaftaran->kelas}) tercatat pada Pertemuan {$pertemuanDariQR}.",
            ]);
        } catch (\Throwable $e) {
            Log::error('Error saat submit scan presensi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Terjadi kesalahan sistem saat menyimpan presensi.",
            ], 500);
        }
    }
}

