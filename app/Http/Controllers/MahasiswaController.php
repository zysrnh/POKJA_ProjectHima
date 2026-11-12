<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMahasiswaMail;
use App\Models\KelompokInvitation;
use App\Models\KelompokMember;
use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class MahasiswaController extends Controller
{
    /**
     * Mengikat sesi login mahasiswa ke satu perangkat aktif tunggal (Single-Device Session Lock)
     * dan otomatis menendang/menghapus sesi aktif di perangkat sebelumnya.
     */
    public static function bindSingleDeviceSession(Request $request, Pendaftaran $mahasiswa): void
    {
        $request->session()->regenerate();

        $cacheKey = 'mhs_active_session_' . $mahasiswa->id;
        $previousSession = Cache::get($cacheKey);

        // Hapus row session perangkat lama dari tabel sessions jika berbeda dengan session saat ini
        if (
            is_array($previousSession) &&
            !empty($previousSession['session_id']) &&
            $previousSession['session_id'] !== $request->session()->getId()
        ) {
            try {
                DB::table('sessions')->where('id', $previousSession['session_id'])->delete();
            } catch (\Throwable $e) {
                Log::warning('Gagal menghapus sesi perangkat lama mahasiswa: ' . $e->getMessage());
            }
        }

        $deviceToken = Str::random(40);

        Cache::put($cacheKey, [
            'session_id' => $request->session()->getId(),
            'device_token' => $deviceToken,
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'updated_at' => now()->timestamp,
        ], now()->addDays(7));

        session([
            'mahasiswa_id' => $mahasiswa->id,
            'mahasiswa_nim' => $mahasiswa->nim,
            'mahasiswa_nama' => $mahasiswa->nama,
            'mahasiswa_device_token' => $deviceToken,
        ]);
    }

    /**
     * Memvalidasi apakah sesi perangkat saat ini masih merupakan perangkat aktif tunggal.
     * Jika akun telah login di perangkat baru, bersihkan sesi perangkat lama dan kembalikan false.
     */
    public static function validateActiveDeviceSession(Request $request): bool
    {
        if (!session()->has('mahasiswa_id')) {
            return false;
        }

        $mahasiswaId = session('mahasiswa_id');
        $sessionDeviceToken = (string) session('mahasiswa_device_token', '');
        $cacheKey = 'mhs_active_session_' . $mahasiswaId;
        $activeSession = Cache::get($cacheKey);

        if (is_array($activeSession) && !empty($activeSession['device_token'])) {
            if (empty($sessionDeviceToken) || !hash_equals((string) $activeSession['device_token'], $sessionDeviceToken)) {
                session()->forget(['mahasiswa_id', 'mahasiswa_nim', 'mahasiswa_nama', 'mahasiswa_device_token']);
                return false;
            }
            return true;
        }

        // Jika cache baru saja di-flush oleh admin (optimize:clear), daftarkan ulang token perangkat aktif ini
        $newToken = $sessionDeviceToken ?: Str::random(40);
        Cache::put($cacheKey, [
            'session_id' => $request->session()->getId(),
            'device_token' => $newToken,
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'updated_at' => now()->timestamp,
        ], now()->addDays(7));

        session(['mahasiswa_device_token' => $newToken]);

        return true;
    }

    /**
     * Menampilkan halaman login untuk mahasiswa.
     */
    public function showLogin(Request $request)
    {
        if (session()->has('mahasiswa_id')) {
            if (self::validateActiveDeviceSession($request)) {
                return redirect()->route('mahasiswa.dashboard');
            }

            return redirect()->route('mahasiswa.login')
                ->with('error', 'Sesi Anda telah berakhir karena akun ini baru saja login di perangkat lain.');
        }

        $pengaturan = Pengaturan::first();

        return view('mahasiswa.login', compact('pengaturan'));
    }

    /**
     * Memproses login mahasiswa biasa (Email / NIM + Password) dengan RateLimiter & Session Regeneration.
     */
    public function login(Request $request)
    {
        $request->merge([
            'identifier' => trim(strip_tags($request->input('identifier', ''))),
        ]);

        $request->validate([
            'identifier' => ['required', 'string', 'max:150'],
            'password' => ['required', 'string', 'max:255'],
        ], [
            'identifier.required' => 'Email kampus atau NIM wajib diisi.',
            'password.required' => 'Password akun wajib diisi.',
        ]);

        $identifier = $request->identifier;
        $throttleKey = 'mhs_login:' . Str::lower($identifier) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withInput(['identifier' => $identifier])
                ->with('error', "Terlalu banyak percobaan login gagal. Silakan tunggu {$seconds} detik lagi.");
        }

        // Cari berdasarkan Email atau NIM
        $mahasiswa = Pendaftaran::where('email', $identifier)
            ->orWhere('nim', $identifier)
            ->first();

        if (!$mahasiswa) {
            RateLimiter::hit($throttleKey, 60);
            return back()
                ->withInput(['identifier' => $identifier])
                ->with('error', 'Akun tidak ditemukan. Pastikan Anda telah terdaftar sebagai peserta POKJA.');
        }

        // Cek jika akun belum pernah setting password
        if (empty($mahasiswa->password)) {
            return back()
                ->withInput(['identifier' => $identifier])
                ->with('error', 'Akun Anda belum memiliki password. Silakan gunakan tab "Aktivasi Akun" untuk membuat password pertama kali.');
        }

        // Verifikasi password
        if (!Hash::check($request->password, $mahasiswa->password)) {
            RateLimiter::hit($throttleKey, 60);
            return back()
                ->withInput(['identifier' => $identifier])
                ->with('error', 'Password yang Anda masukkan salah. Silakan coba lagi.');
        }

        RateLimiter::clear($throttleKey);

        // Kunci 1 Akun = 1 Perangkat Aktif & hapus sesi perangkat lama
        self::bindSingleDeviceSession($request, $mahasiswa);

        if (session()->has('pending_kelompok_kode')) {
            return redirect()->route('mahasiswa.kelompok.joinLink', [
                'kode' => session()->pull('pending_kelompok_kode'),
            ]);
        }

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Selamat datang kembali, ' . $mahasiswa->nama . '!');
    }

    /**
     * Mengirim tautan reset password ke email mahasiswa.
     */
    public function sendResetLink(Request $request)
    {
        $request->merge([
            'reset_identifier' => trim(strip_tags($request->input('reset_identifier', ''))),
        ]);

        $request->validate([
            'reset_identifier' => ['required', 'string', 'max:150'],
        ], [
            'reset_identifier.required' => 'Email kampus atau NIM wajib diisi.',
        ]);

        $identifier = $request->reset_identifier;

        $mahasiswa = Pendaftaran::where('email', $identifier)
            ->orWhere('nim', $identifier)
            ->first();

        if (!$mahasiswa) {
            return back()
                ->withInput(['reset_identifier' => $identifier])
                ->with('error_reset', 'Data akun tidak ditemukan. Pastikan Email atau NIM yang dimasukkan sudah benar.');
        }

        if (empty($mahasiswa->email)) {
            return back()
                ->withInput(['reset_identifier' => $identifier])
                ->with('error_reset', 'Akun ini tidak memiliki alamat email yang tercatat. Silakan hubungi panitia POKJA.');
        }

        // Buat temporary signed URL yang kedaluwarsa dalam 60 menit
        $resetUrl = URL::temporarySignedRoute(
            'mahasiswa.showResetPassword',
            now()->addMinutes(60),
            ['id' => $mahasiswa->id]
        );

        $pengaturan = Pengaturan::first();

        try {
            Mail::to($mahasiswa->email)->send(new ResetPasswordMahasiswaMail($mahasiswa, $pengaturan, $resetUrl));
        } catch (\Throwable $e) {
            Log::error('Pengiriman Email Reset Password Mahasiswa Gagal: ' . $e->getMessage());
            return back()
                ->withInput(['reset_identifier' => $identifier])
                ->with('error_reset', 'Gagal mengirim email reset password. Pastikan server email terhubung atau coba lagi nanti.');
        }

        // Sensor sebagian email untuk privasi (contoh: jo***@domain.com)
        $parts = explode('@', $mahasiswa->email);
        $namePart = $parts[0];
        $domainPart = $parts[1] ?? '';
        $maskedName = strlen($namePart) > 2 ? substr($namePart, 0, 2) . str_repeat('*', max(strlen($namePart) - 2, 3)) : $namePart . '***';
        $maskedEmail = $maskedName . '@' . $domainPart;

        return back()->with(
            'success_reset',
            'Tautan atur ulang kata sandi berhasil dikirim ke ' . $maskedEmail . '. Jika belum muncul di Kotak Masuk (Utama), mohon periksa folder SPAM atau tab Promosi di Gmail Anda.'
        );
    }

    /**
     * Menampilkan form reset password dari tautan bertanda tangan (signed URL).
     */
    public function showResetPassword(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('mahasiswa.login')->with('error', 'Tautan atur ulang password tidak valid atau telah kedaluwarsa. Silakan ajukan ulang.');
        }

        $mahasiswa = Pendaftaran::find($id);

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        return view('mahasiswa.reset_password', compact('mahasiswa'));
    }

    /**
     * Memproses pembaruan password dari tautan bertanda tangan (signed URL).
     */
    public function updatePassword(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('mahasiswa.login')->with('error', 'Tautan atur ulang password tidak valid atau telah kedaluwarsa. Silakan ajukan ulang.');
        }

        $mahasiswa = Pendaftaran::find($id);

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password baru.',
        ]);

        $mahasiswa->password = Hash::make($request->password);
        $mahasiswa->save();

        // Kunci 1 Akun = 1 Perangkat Aktif & hapus sesi perangkat lama
        self::bindSingleDeviceSession($request, $mahasiswa);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Password Anda berhasil diperbarui! Selamat datang kembali.');
    }

    /**
     * Memproses verifikasi QR Code pendaftaran untuk aktivasi akun pertama kali.
     */
    public function verifikasiQr(Request $request)
    {
        $rawToken = trim(strip_tags($request->input('token', '')));

        if (empty($rawToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode atau QR pendaftaran tidak boleh kosong.',
            ], 422);
        }

        // Jika input berupa URL penuh (misal hasil scan QR kamera), ekstrak parameter 'token'
        if (str_contains($rawToken, 'token=')) {
            parse_str(parse_url($rawToken, PHP_URL_QUERY) ?? '', $queryParams);
            if (!empty($queryParams['token'])) {
                $rawToken = trim(strip_tags((string) $queryParams['token']));
            }
        }

        // Cari berdasarkan qr_token rahasia ATAU nim (fallback jika QR berisi nim)
        $mahasiswa = Pendaftaran::where('qr_token', $rawToken)
            ->orWhere('nim', $rawToken)
            ->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'QR pendaftaran tidak valid atau tidak terdaftar di sistem.',
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
        $token = trim(strip_tags((string) $request->query('token', '')));

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
            return redirect()->route('mahasiswa.login')->with('error', 'Sesi aktivasi telah berakhir. Silakan scan/upload QR Anda kembali.');
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
            'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password baru.',
        ]);

        $mahasiswa->password = Hash::make($request->password);
        $mahasiswa->save();

        // Hapus sesi aktivasi sementara & kunci ke perangkat aktif ini
        session()->forget('aktivasi_mahasiswa_id');
        self::bindSingleDeviceSession($request, $mahasiswa);

        if (session()->has('pending_kelompok_kode')) {
            return redirect()->route('mahasiswa.kelompok.joinLink', [
                'kode' => session()->pull('pending_kelompok_kode'),
            ]);
        }

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Password berhasil dibuat! Akun Anda kini telah aktif.');
    }

    /**
     * Menampilkan dashboard pribadi mahasiswa.
     */
    public function dashboard(Request $request)
    {
        if (!session()->has('mahasiswa_id')) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
        }

        if (!self::validateActiveDeviceSession($request)) {
            return redirect()->route('mahasiswa.login')->with('error', 'Sesi Anda telah berakhir karena akun ini baru saja login di perangkat lain.');
        }

        $mahasiswa = Pendaftaran::with([
            'presensis',
            'pointHistories' => function ($q) {
                $q->orderBy('created_at', 'desc')->orderBy('id', 'desc');
            },
        ])->find(session('mahasiswa_id'));

        if (!$mahasiswa) {
            session()->forget(['mahasiswa_id', 'mahasiswa_nim', 'mahasiswa_nama', 'mahasiswa_device_token']);
            return redirect()->route('mahasiswa.login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $pengaturan = Pengaturan::first();
        $presensiMap = $mahasiswa->presensis->keyBy('pertemuan');
        $totalHadir = $mahasiswa->presensis->where('status', 'hadir')->count();
        $persenHadir = round(($totalHadir / 7) * 100);

        // Data PointKu & Leaderboard untuk Portal Mahasiswa
        $leaderboardList = Pendaftaran::orderBy('total_point', 'desc')
            ->orderBy('nama', 'asc')
            ->get();

        $topThree = $leaderboardList->take(3)->values();
        $myRankIndex = $leaderboardList->search(fn ($m) => (int) $m->id === (int) $mahasiswa->id);
        $myRank = $myRankIndex !== false ? ($myRankIndex + 1) : '-';
        $totalPeserta = $leaderboardList->count();

        // Data Kelompok & Inbox Undangan Mahasiswa
        $myMembership = KelompokMember::with([
            'kelompok.ketua',
            'kelompok.members' => function ($q) {
                $q->orderByRaw("CASE WHEN role = 'ketua' THEN 0 ELSE 1 END")
                    ->orderBy('joined_at', 'asc')
                    ->with(['pendaftaran.presensis']);
            },
            'kelompok.invitations' => function ($q) {
                $q->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->with('pendaftaran');
            },
        ])->where('pendaftaran_id', $mahasiswa->id)->first();

        $myKelompok = $myMembership?->kelompok;
        $isKetua = $myKelompok && (int) $myKelompok->ketua_id === (int) $mahasiswa->id;

        $kelompokStats = [
            'jumlah_anggota' => 0,
            'total_point' => 0,
            'total_hadir' => 0,
            'persen_hadir' => 0,
        ];

        $kelompokInbox = collect();
        $kandidatAnggotaList = collect();

        if ($myKelompok) {
            $members = $myKelompok->members;
            $memberCount = $members->count();
            $totalGroupPoints = $members->sum(fn ($m) => (int) ($m->pendaftaran->total_point ?? 0));
            $totalGroupHadir = $members->sum(function ($m) {
                return $m->pendaftaran?->presensis?->where('status', 'hadir')->count() ?? 0;
            });
            $persenGroupHadir = $memberCount > 0 ? round(($totalGroupHadir / ($memberCount * 7)) * 100) : 0;

            $kelompokStats = [
                'jumlah_anggota' => $memberCount,
                'total_point' => $totalGroupPoints,
                'total_hadir' => $totalGroupHadir,
                'persen_hadir' => $persenGroupHadir,
            ];

            if ($isKetua) {
                $pendingInvitedIds = $myKelompok->invitations
                    ->where('status', 'pending')
                    ->pluck('pendaftaran_id')
                    ->map(fn ($id) => (int) $id)
                    ->all();

                $kandidatAnggotaList = Pendaftaran::whereDoesntHave('kelompokMember')
                    ->where('id', '!=', $mahasiswa->id)
                    ->orderBy('nama', 'asc')
                    ->get()
                    ->map(function ($mhs) use ($pendingInvitedIds) {
                        return [
                            'id' => (int) $mhs->id,
                            'nama' => $mhs->nama,
                            'nim' => $mhs->nim,
                            'kelas' => $mhs->kelas,
                            'already_invited' => in_array((int) $mhs->id, $pendingInvitedIds, true),
                        ];
                    })->values();
            }
        } else {
            $kelompokInbox = KelompokInvitation::with(['kelompok.ketua', 'kelompok.members', 'inviter'])
                ->where('pendaftaran_id', $mahasiswa->id)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'pengaturan',
            'presensiMap',
            'totalHadir',
            'persenHadir',
            'leaderboardList',
            'topThree',
            'myRank',
            'totalPeserta',
            'myKelompok',
            'isKetua',
            'kelompokStats',
            'kelompokInbox',
            'kandidatAnggotaList'
        ));
    }

    /**
     * Memperbarui profil mahasiswa (email dan foto profil) dengan validasi MIME & Image Binary ketat.
     */
    public function updateProfile(Request $request)
    {
        if (!session()->has('mahasiswa_id')) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
        }

        if (!self::validateActiveDeviceSession($request)) {
            return redirect()->route('mahasiswa.login')->with('error', 'Sesi Anda telah berakhir karena akun ini baru saja login di perangkat lain.');
        }

        $mahasiswa = Pendaftaran::find(session('mahasiswa_id'));

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $request->merge([
            'email' => strtolower(trim(strip_tags($request->input('email', '')))),
        ]);

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
                'file',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:2048',
                'dimensions:min_width=50,min_height=50,max_width=4096,max_height=4096',
            ],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'foto.image' => 'File yang diunggah harus berupa gambar asli.',
            'foto.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
            'foto.mimetypes' => 'Konten file bukan gambar yang valid.',
            'foto.max' => 'Ukuran file foto maksimal 2 MB.',
            'foto.dimensions' => 'Resolusi foto minimal 50x50 piksel dan maksimal 4096x4096 piksel.',
        ]);

        $mahasiswa->email = $request->email;

        // Upload foto profil dengan inspeksi struktur binary gambar asli (Anti-Webshell)
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $realPath = $file->getRealPath();
            $imageInfo = @getimagesize($realPath);

            $allowedMimeMap = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
            ];

            if ($imageInfo === false || !isset($imageInfo['mime']) || !array_key_exists($imageInfo['mime'], $allowedMimeMap)) {
                return back()->withErrors(['foto' => 'File gambar rusak atau terdeteksi tidak aman.']);
            }

            $safeExtension = $allowedMimeMap[$imageInfo['mime']];
            $filename = 'avatar_' . $mahasiswa->nim . '_' . Str::random(12) . '_' . time() . '.' . $safeExtension;

            $destinationPath = public_path('uploads/avatars');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Hapus foto lama jika ada (pastikan hanya basename untuk mencegah path traversal)
            if (!empty($mahasiswa->foto)) {
                $oldFile = $destinationPath . DIRECTORY_SEPARATOR . basename($mahasiswa->foto);
                if (is_file($oldFile)) {
                    @unlink($oldFile);
                }
            }

            $file->move($destinationPath, $filename);
            $mahasiswa->foto = $filename;
        }

        $mahasiswa->save();

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Logout mahasiswa dari dashboard dan invalidasi session secara menyeluruh.
     */
    public function logout(Request $request)
    {
        $mahasiswaId = session('mahasiswa_id');
        if ($mahasiswaId && self::validateActiveDeviceSession($request)) {
            Cache::forget('mhs_active_session_' . $mahasiswaId);
        }

        session()->forget(['mahasiswa_id', 'mahasiswa_nim', 'mahasiswa_nama', 'mahasiswa_device_token']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('mahasiswa.login')->with('success', 'Anda telah berhasil keluar dari akun mahasiswa.');
    }
}
