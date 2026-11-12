<?php

namespace App\Http\Controllers;

use App\Mail\KelompokNotificationMail;
use App\Models\Kelompok;
use App\Models\KelompokInvitation;
use App\Models\KelompokMember;
use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MahasiswaKelompokController extends Controller
{
    /**
     * Ambil data mahasiswa yang sedang login dan validasi single-device session.
     */
    private function getAuthenticatedMahasiswa(Request $request): ?Pendaftaran
    {
        if (!session()->has('mahasiswa_id')) {
            return null;
        }

        if (!MahasiswaController::validateActiveDeviceSession($request)) {
            return null;
        }

        return Pendaftaran::find(session('mahasiswa_id'));
    }

    /**
     * Kirim email notifikasi aktivitas kelompok tanpa menggagalkan proses utama bila SMTP bermasalah.
     */
    private function notifyByEmail(
        ?Pendaftaran $penerima,
        string $subjectLine,
        string $badgeText,
        string $badgeColor,
        string $heading,
        string $messageText,
        string $namaKelompok,
        ?string $namaAktor = null,
        ?string $ctaUrl = null,
        string $ctaText = 'Buka Menu Kelompok'
    ): void {
        if (!$penerima || empty($penerima->email) || !filter_var($penerima->email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        static $pengaturanCache = false;
        if ($pengaturanCache === false) {
            $pengaturanCache = Pengaturan::first();
        }

        $targetUrl = $ctaUrl ?: route('mahasiswa.dashboard', ['tab' => 'kelompok']);

        try {
            Mail::to($penerima->email)->send(new KelompokNotificationMail(
                penerima: $penerima,
                pengaturan: $pengaturanCache,
                subjectLine: $subjectLine,
                badgeText: $badgeText,
                badgeColor: $badgeColor,
                heading: $heading,
                messageText: $messageText,
                namaKelompok: $namaKelompok,
                namaAktor: $namaAktor,
                ctaUrl: $targetUrl,
                ctaText: $ctaText,
            ));
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim email notifikasi kelompok ke {$penerima->email}: " . $e->getMessage());
        }
    }

    /**
     * Ekstrak kode undangan kelompok dari input mentah (mendukung kode langsung maupun URL join).
     */
    private function extractInviteCode(string $raw): string
    {
        $clean = trim(strip_tags($raw));

        if (preg_match('/kelompok\/join\/([A-Za-z0-9\-]+)/i', $clean, $matches)) {
            $clean = $matches[1];
        }

        return strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $clean));
    }

    /**
     * Buat kode undangan unik untuk kelompok baru.
     */
    private function generateUniqueInviteCode(): string
    {
        do {
            $code = 'PKJ-' . strtoupper(Str::random(6));
        } while (Kelompok::where('kode_invite', $code)->exists());

        return $code;
    }

    /**
     * Proses inti memasukkan mahasiswa ke dalam kelompok melalui kode undangan.
     */
    private function processJoinByCode(Pendaftaran $mahasiswa, string $rawCode): array
    {
        $kode = $this->extractInviteCode($rawCode);
        if (empty($kode)) {
            return ['ok' => false, 'message' => 'Kode atau link undangan kelompok tidak valid.'];
        }

        $kelompok = Kelompok::with('ketua')->where('kode_invite', $kode)->first();
        if (!$kelompok) {
            return ['ok' => false, 'message' => "Kelompok dengan kode {$kode} tidak ditemukan."];
        }

        if (KelompokMember::where('pendaftaran_id', $mahasiswa->id)->exists()) {
            $existing = KelompokMember::with('kelompok')->where('pendaftaran_id', $mahasiswa->id)->first();
            if ($existing && (int) $existing->kelompok_id === (int) $kelompok->id) {
                return ['ok' => true, 'message' => "Kamu sudah tergabung di kelompok {$kelompok->nama_kelompok}."];
            }
            return ['ok' => false, 'message' => 'Kamu sudah tergabung di kelompok lain. Keluar dulu dari kelompok saat ini jika ingin pindah.'];
        }

        DB::transaction(function () use ($mahasiswa, $kelompok) {
            KelompokMember::create([
                'kelompok_id' => $kelompok->id,
                'pendaftaran_id' => $mahasiswa->id,
                'role' => 'anggota',
                'joined_at' => now(),
            ]);

            KelompokInvitation::where('pendaftaran_id', $mahasiswa->id)
                ->where('kelompok_id', $kelompok->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'accepted',
                    'responded_at' => now(),
                ]);

            KelompokInvitation::where('pendaftaran_id', $mahasiswa->id)
                ->where('kelompok_id', '!=', $kelompok->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'declined',
                    'responded_at' => now(),
                ]);
        });

        if ($kelompok->ketua && (int) $kelompok->ketua->id !== (int) $mahasiswa->id) {
            $this->notifyByEmail(
                penerima: $kelompok->ketua,
                subjectLine: "Anggota Baru Bergabung: {$mahasiswa->nama}",
                badgeText: 'ANGGOTA BARU',
                badgeColor: '#15803d',
                heading: 'Anggota Baru Gabung via Link / QR',
                messageText: "{$mahasiswa->nama} ({$mahasiswa->nim} - {$mahasiswa->kelas}) baru saja bergabung ke kelompok \"{$kelompok->nama_kelompok}\" melalui link atau kode undangan.",
                namaKelompok: $kelompok->nama_kelompok,
                namaAktor: "Anggota Baru: {$mahasiswa->nama} ({$mahasiswa->nim})",
                ctaText: 'Lihat Anggota Tim',
            );
        }

        return ['ok' => true, 'message' => "Berhasil bergabung ke kelompok {$kelompok->nama_kelompok}!"];
    }

    /**
     * Membuat kelompok baru dengan mahasiswa pembuat otomatis sebagai Ketua.
     */
    public function store(Request $request)
    {
        $mahasiswa = $this->getAuthenticatedMahasiswa($request);
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Sesi login Anda tidak valid. Silakan login kembali.');
        }

        if (KelompokMember::where('pendaftaran_id', $mahasiswa->id)->exists()) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Kamu sudah tergabung dalam sebuah kelompok.');
        }

        $request->merge([
            'nama_kelompok' => trim(strip_tags($request->input('nama_kelompok', ''))),
            'judul_proposal' => trim(strip_tags($request->input('judul_proposal', ''))),
        ]);

        $request->validate([
            'nama_kelompok' => ['required', 'string', 'min:2', 'max:80', 'unique:kelompoks,nama_kelompok'],
            'judul_proposal' => ['nullable', 'string', 'max:200'],
        ], [
            'nama_kelompok.required' => 'Nama kelompok wajib diisi.',
            'nama_kelompok.min' => 'Nama kelompok minimal 2 karakter.',
            'nama_kelompok.max' => 'Nama kelompok maksimal 80 karakter.',
            'nama_kelompok.unique' => 'Nama kelompok tersebut sudah dipakai. Silakan pilih nama lain.',
            'judul_proposal.max' => 'Ide/judul proposal maksimal 200 karakter.',
        ]);

        $kelompok = DB::transaction(function () use ($request, $mahasiswa) {
            $newKelompok = Kelompok::create([
                'nama_kelompok' => $request->nama_kelompok,
                'kode_invite' => $this->generateUniqueInviteCode(),
                'ketua_id' => $mahasiswa->id,
                'judul_proposal' => $request->judul_proposal ?: null,
            ]);

            KelompokMember::create([
                'kelompok_id' => $newKelompok->id,
                'pendaftaran_id' => $mahasiswa->id,
                'role' => 'ketua',
                'joined_at' => now(),
            ]);

            KelompokInvitation::where('pendaftaran_id', $mahasiswa->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'declined',
                    'responded_at' => now(),
                ]);

            return $newKelompok;
        });

        return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
            ->with('success', "Kelompok \"{$kelompok->nama_kelompok}\" berhasil dibuat! Kamu sekarang menjabat sebagai Ketua.");
    }

    /**
     * Hapus file foto kelompok dari disk secara aman (pencegahan path traversal via basename).
     */
    private function deleteKelompokFotoFile(?string $fotoFilename): void
    {
        if (empty($fotoFilename)) {
            return;
        }

        $oldPath = public_path('uploads/kelompok/' . basename($fotoFilename));
        if (is_file($oldPath)) {
            @unlink($oldPath);
        }
    }

    /**
     * Validasi binary gambar asli (Anti-Webshell) & simpan foto profil kelompok.
     */
    private function storeValidatedKelompokFoto(UploadedFile $file, Kelompok $kelompok): ?string
    {
        $imageInfo = @getimagesize($file->getRealPath());
        $allowedMimeMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if ($imageInfo === false || !isset($imageInfo['mime']) || !array_key_exists($imageInfo['mime'], $allowedMimeMap)) {
            return null;
        }

        $safeExtension = $allowedMimeMap[$imageInfo['mime']];
        $filename = 'kelompok_' . $kelompok->id . '_' . Str::random(12) . '_' . time() . '.' . $safeExtension;
        $destinationPath = public_path('uploads/kelompok');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $this->deleteKelompokFotoFile($kelompok->foto);
        $file->move($destinationPath, $filename);

        return $filename;
    }

    /**
     * Memperbarui nama kelompok, ide judul proposal, dan foto profil kelompok (khusus Ketua).
     */
    public function update(Request $request)
    {
        $mahasiswa = $this->getAuthenticatedMahasiswa($request);
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Sesi login Anda tidak valid. Silakan login kembali.');
        }

        $membership = KelompokMember::with('kelompok')->where('pendaftaran_id', $mahasiswa->id)->first();
        if (!$membership || !$membership->kelompok || (int) $membership->kelompok->ketua_id !== (int) $mahasiswa->id) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Hanya Ketua kelompok yang dapat mengubah informasi kelompok.');
        }

        $kelompok = $membership->kelompok;

        $request->merge([
            'nama_kelompok' => trim(strip_tags($request->input('nama_kelompok', ''))),
            'judul_proposal' => trim(strip_tags($request->input('judul_proposal', ''))),
        ]);

        $request->validate([
            'nama_kelompok' => ['required', 'string', 'min:2', 'max:80', 'unique:kelompoks,nama_kelompok,' . $kelompok->id],
            'judul_proposal' => ['nullable', 'string', 'max:200'],
            'hapus_foto' => ['nullable', 'boolean'],
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
            'nama_kelompok.required' => 'Nama kelompok wajib diisi.',
            'nama_kelompok.unique' => 'Nama kelompok tersebut sudah dipakai kelompok lain.',
            'foto.image' => 'File foto kelompok harus berupa gambar asli.',
            'foto.mimes' => 'Format foto kelompok harus JPG, JPEG, PNG, atau WEBP.',
            'foto.mimetypes' => 'Konten file foto kelompok bukan gambar yang valid.',
            'foto.max' => 'Ukuran file foto kelompok maksimal 2 MB.',
            'foto.dimensions' => 'Resolusi foto kelompok minimal 50x50 piksel dan maksimal 4096x4096 piksel.',
        ]);

        if ($request->boolean('hapus_foto') && !$request->hasFile('foto')) {
            $this->deleteKelompokFotoFile($kelompok->foto);
            $kelompok->foto = null;
        }

        if ($request->hasFile('foto')) {
            $savedFilename = $this->storeValidatedKelompokFoto($request->file('foto'), $kelompok);
            if (!$savedFilename) {
                return back()->withErrors(['foto' => 'File gambar rusak atau terdeteksi tidak aman.'])->withInput();
            }
            $kelompok->foto = $savedFilename;
        }

        $kelompok->nama_kelompok = $request->nama_kelompok;
        $kelompok->judul_proposal = $request->judul_proposal ?: null;
        $kelompok->save();

        return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
            ->with('success', 'Informasi kelompok berhasil diperbarui!');
    }

    /**
     * Gabung ke kelompok lewat link undangan langsung (GET /mahasiswa/kelompok/join/{kode}).
     */
    public function joinByLink(Request $request, string $kode)
    {
        $cleanKode = $this->extractInviteCode($kode);
        $mahasiswa = $this->getAuthenticatedMahasiswa($request);

        if (!$mahasiswa) {
            session(['pending_kelompok_kode' => $cleanKode]);
            return redirect()->route('mahasiswa.login')
                ->with('success', 'Silakan masuk ke akunmu terlebih dahulu untuk otomatis bergabung ke kelompok.');
        }

        $result = $this->processJoinByCode($mahasiswa, $cleanKode);

        return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
            ->with($result['ok'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Gabung ke kelompok melalui input kode undangan, tempel link, atau scan QR dari kamera.
     */
    public function joinByCode(Request $request)
    {
        $mahasiswa = $this->getAuthenticatedMahasiswa($request);
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
        }

        $request->validate([
            'kode_invite' => ['required', 'string', 'max:255'],
        ], [
            'kode_invite.required' => 'Kode atau link undangan kelompok wajib diisi.',
        ]);

        $result = $this->processJoinByCode($mahasiswa, (string) $request->kode_invite);

        return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
            ->with($result['ok'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Mengirim undangan langsung ke Inbox mahasiswa lain (khusus Ketua).
     */
    public function sendInvite(Request $request)
    {
        $mahasiswa = $this->getAuthenticatedMahasiswa($request);
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
        }

        $membership = KelompokMember::with('kelompok')->where('pendaftaran_id', $mahasiswa->id)->first();
        if (!$membership || !$membership->kelompok || (int) $membership->kelompok->ketua_id !== (int) $mahasiswa->id) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Hanya Ketua kelompok yang dapat mengirim undangan anggota.');
        }

        $request->validate([
            'pendaftaran_id' => ['required', 'integer', 'exists:pendaftarans,id'],
        ], [
            'pendaftaran_id.required' => 'Silakan pilih mahasiswa yang ingin diundang.',
            'pendaftaran_id.exists' => 'Data mahasiswa tidak ditemukan.',
        ]);

        $targetId = (int) $request->pendaftaran_id;
        if ($targetId === (int) $mahasiswa->id) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Tidak bisa mengundang diri sendiri.');
        }

        $target = Pendaftaran::findOrFail($targetId);

        if (KelompokMember::where('pendaftaran_id', $target->id)->exists()) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', "{$target->nama} sudah tergabung di sebuah kelompok.");
        }

        $alreadyPending = KelompokInvitation::where('kelompok_id', $membership->kelompok->id)
            ->where('pendaftaran_id', $target->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', "Undangan untuk {$target->nama} masih menunggu konfirmasi di inbox-nya.");
        }

        KelompokInvitation::create([
            'kelompok_id' => $membership->kelompok->id,
            'pendaftaran_id' => $target->id,
            'invited_by' => $mahasiswa->id,
            'status' => 'pending',
        ]);

        $this->notifyByEmail(
            penerima: $target,
            subjectLine: "Undangan Kelompok: {$membership->kelompok->nama_kelompok}",
            badgeText: 'UNDANGAN KELOMPOK',
            badgeColor: '#1A467C',
            heading: 'Kamu Diundang Bergabung ke Kelompok',
            messageText: "{$mahasiswa->nama} ({$mahasiswa->kelas}) mengundang kamu untuk bergabung ke kelompok \"{$membership->kelompok->nama_kelompok}\". Silakan buka Inbox Kelompok di Portal Mahasiswa untuk menerima atau menolak undangan ini.",
            namaKelompok: $membership->kelompok->nama_kelompok,
            namaAktor: "Ketua: {$mahasiswa->nama}",
            ctaText: 'Buka Inbox Undangan',
        );

        return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
            ->with('success', "Undangan berhasil dikirim ke Inbox {$target->nama} ({$target->nim}).");
    }

    /**
     * Membatalkan undangan yang masih pending (khusus Ketua).
     */
    public function cancelInvite(Request $request, int $invitationId)
    {
        $mahasiswa = $this->getAuthenticatedMahasiswa($request);
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
        }

        $membership = KelompokMember::with('kelompok')->where('pendaftaran_id', $mahasiswa->id)->first();
        if (!$membership || !$membership->kelompok || (int) $membership->kelompok->ketua_id !== (int) $mahasiswa->id) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Hanya Ketua kelompok yang dapat membatalkan undangan.');
        }

        $invitation = KelompokInvitation::where('id', $invitationId)
            ->where('kelompok_id', $membership->kelompok->id)
            ->where('status', 'pending')
            ->first();

        if ($invitation) {
            $invitation->delete();
        }

        return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
            ->with('success', 'Undangan berhasil dibatalkan.');
    }

    /**
     * Merespon undangan kelompok di Inbox (terima / tolak).
     */
    public function respondInvite(Request $request, int $invitationId)
    {
        $mahasiswa = $this->getAuthenticatedMahasiswa($request);
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
        }

        $request->validate([
            'action' => ['required', 'in:accept,decline'],
        ]);

        $invitation = KelompokInvitation::with('kelompok.ketua')
            ->where('id', $invitationId)
            ->where('pendaftaran_id', $mahasiswa->id)
            ->where('status', 'pending')
            ->first();

        if (!$invitation || !$invitation->kelompok) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Undangan sudah tidak berlaku atau telah diproses.');
        }

        if ($request->action === 'decline') {
            $invitation->status = 'declined';
            $invitation->responded_at = now();
            $invitation->save();

            if ($invitation->kelompok->ketua && (int) $invitation->kelompok->ketua->id !== (int) $mahasiswa->id) {
                $this->notifyByEmail(
                    penerima: $invitation->kelompok->ketua,
                    subjectLine: "Undangan Ditolak: {$mahasiswa->nama}",
                    badgeText: 'UNDANGAN DITOLAK',
                    badgeColor: '#CA2C2A',
                    heading: 'Undangan Kelompok Ditolak',
                    messageText: "{$mahasiswa->nama} ({$mahasiswa->nim} - {$mahasiswa->kelas}) menolak undangan untuk bergabung ke kelompok \"{$invitation->kelompok->nama_kelompok}\".",
                    namaKelompok: $invitation->kelompok->nama_kelompok,
                    namaAktor: "Ditolak oleh: {$mahasiswa->nama}",
                );
            }

            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('success', "Undangan dari kelompok \"{$invitation->kelompok->nama_kelompok}\" telah ditolak.");
        }

        if (KelompokMember::where('pendaftaran_id', $mahasiswa->id)->exists()) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Kamu sudah tergabung di kelompok lain.');
        }

        DB::transaction(function () use ($invitation, $mahasiswa) {
            KelompokMember::create([
                'kelompok_id' => $invitation->kelompok_id,
                'pendaftaran_id' => $mahasiswa->id,
                'role' => 'anggota',
                'joined_at' => now(),
            ]);

            $invitation->status = 'accepted';
            $invitation->responded_at = now();
            $invitation->save();

            KelompokInvitation::where('pendaftaran_id', $mahasiswa->id)
                ->where('id', '!=', $invitation->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'declined',
                    'responded_at' => now(),
                ]);
        });

        if ($invitation->kelompok->ketua && (int) $invitation->kelompok->ketua->id !== (int) $mahasiswa->id) {
            $this->notifyByEmail(
                penerima: $invitation->kelompok->ketua,
                subjectLine: "Undangan Diterima: {$mahasiswa->nama}",
                badgeText: 'UNDANGAN DITERIMA',
                badgeColor: '#15803d',
                heading: 'Undangan Kelompok Diterima',
                messageText: "{$mahasiswa->nama} ({$mahasiswa->nim} - {$mahasiswa->kelas}) telah menerima undangan dan resmi bergabung ke kelompok \"{$invitation->kelompok->nama_kelompok}\".",
                namaKelompok: $invitation->kelompok->nama_kelompok,
                namaAktor: "Anggota Baru: {$mahasiswa->nama}",
                ctaText: 'Lihat Anggota Tim',
            );
        }

        return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
            ->with('success', "Selamat! Kamu resmi bergabung di kelompok \"{$invitation->kelompok->nama_kelompok}\".");
    }

    /**
     * Mengeluarkan anggota dari kelompok (khusus Ketua).
     */
    public function removeMember(Request $request, int $memberId)
    {
        $mahasiswa = $this->getAuthenticatedMahasiswa($request);
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
        }

        $myMembership = KelompokMember::with('kelompok')->where('pendaftaran_id', $mahasiswa->id)->first();
        if (!$myMembership || !$myMembership->kelompok || (int) $myMembership->kelompok->ketua_id !== (int) $mahasiswa->id) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Hanya Ketua kelompok yang dapat mengeluarkan anggota.');
        }

        $targetMember = KelompokMember::with('pendaftaran')
            ->where('id', $memberId)
            ->where('kelompok_id', $myMembership->kelompok->id)
            ->first();

        if (!$targetMember) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Anggota tidak ditemukan dalam kelompok ini.');
        }

        if ((int) $targetMember->pendaftaran_id === (int) $mahasiswa->id) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Ketua tidak dapat mengeluarkan diri sendiri.');
        }

        $targetPendaftaran = $targetMember->pendaftaran;
        $namaAnggota = $targetPendaftaran->nama ?? 'Anggota';
        $namaKelompok = $myMembership->kelompok->nama_kelompok;
        $targetMember->delete();

        $this->notifyByEmail(
            penerima: $targetPendaftaran,
            subjectLine: "Pemberitahuan Keanggotaan Kelompok: {$namaKelompok}",
            badgeText: 'DIKELUARKAN',
            badgeColor: '#CA2C2A',
            heading: 'Kamu Dikeluarkan dari Kelompok',
            messageText: "Ketua kelompok ({$mahasiswa->nama}) telah mengeluarkan kamu dari kelompok \"{$namaKelompok}\". Kamu dapat bergabung atau membuat kelompok lain melalui Portal Mahasiswa.",
            namaKelompok: $namaKelompok,
            namaAktor: "Ketua: {$mahasiswa->nama}",
        );

        return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
            ->with('success', "{$namaAnggota} berhasil dikeluarkan dari kelompok.");
    }

    /**
     * Menyerahkan jabatan Ketua kepada anggota lain (khusus Ketua).
     */
    public function transferKetua(Request $request, int $memberId)
    {
        $mahasiswa = $this->getAuthenticatedMahasiswa($request);
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
        }

        $myMembership = KelompokMember::with('kelompok')->where('pendaftaran_id', $mahasiswa->id)->first();
        if (!$myMembership || !$myMembership->kelompok || (int) $myMembership->kelompok->ketua_id !== (int) $mahasiswa->id) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Hanya Ketua kelompok yang dapat menyerahkan jabatan.');
        }

        $targetMember = KelompokMember::with('pendaftaran')
            ->where('id', $memberId)
            ->where('kelompok_id', $myMembership->kelompok->id)
            ->first();

        if (!$targetMember || (int) $targetMember->pendaftaran_id === (int) $mahasiswa->id) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Pilih anggota kelompok yang valid.');
        }

        DB::transaction(function () use ($myMembership, $targetMember) {
            $kelompok = $myMembership->kelompok;
            $kelompok->ketua_id = $targetMember->pendaftaran_id;
            $kelompok->save();

            $myMembership->role = 'anggota';
            $myMembership->save();

            $targetMember->role = 'ketua';
            $targetMember->save();
        });

        $namaBaru = $targetMember->pendaftaran->nama ?? 'Anggota';

        $this->notifyByEmail(
            penerima: $targetMember->pendaftaran,
            subjectLine: "Penunjukan Ketua Kelompok Baru: {$myMembership->kelompok->nama_kelompok}",
            badgeText: 'KETUA BARU',
            badgeColor: '#D97706',
            heading: 'Kamu Resmi Menjadi Ketua Kelompok',
            messageText: "{$mahasiswa->nama} telah menyerahkan jabatan Ketua kelompok \"{$myMembership->kelompok->nama_kelompok}\" kepada kamu. Kamu kini memiliki akses penuh untuk mengelola anggota & undangan tim.",
            namaKelompok: $myMembership->kelompok->nama_kelompok,
            namaAktor: "Ketua Sebelumnya: {$mahasiswa->nama}",
            ctaText: 'Kelola Kelompok Sekarang',
        );

        return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
            ->with('success', "Jabatan Ketua berhasil diserahkan kepada {$namaBaru}.");
    }

    /**
     * Keluar dari kelompok (untuk Anggota) atau Bubarkan kelompok (untuk Ketua).
     */
    public function leaveOrDisband(Request $request)
    {
        $mahasiswa = $this->getAuthenticatedMahasiswa($request);
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
        }

        $membership = KelompokMember::with(['kelompok.ketua', 'kelompok.members.pendaftaran'])
            ->where('pendaftaran_id', $mahasiswa->id)
            ->first();

        if (!$membership || !$membership->kelompok) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Kamu belum tergabung di kelompok mana pun.');
        }

        $kelompok = $membership->kelompok;

        if ((int) $kelompok->ketua_id === (int) $mahasiswa->id) {
            $namaKelompok = $kelompok->nama_kelompok;
            $oldFoto = $kelompok->foto;
            $formerMembers = $kelompok->members
                ->filter(fn ($m) => (int) $m->pendaftaran_id !== (int) $mahasiswa->id)
                ->pluck('pendaftaran')
                ->filter();

            $kelompok->delete();
            $this->deleteKelompokFotoFile($oldFoto);

            foreach ($formerMembers as $exMember) {
                $this->notifyByEmail(
                    penerima: $exMember,
                    subjectLine: "Kelompok Dibubarkan: {$namaKelompok}",
                    badgeText: 'KELOMPOK BUBAR',
                    badgeColor: '#CA2C2A',
                    heading: 'Kelompok Telah Dibubarkan',
                    messageText: "Ketua kelompok ({$mahasiswa->nama}) telah membubarkan kelompok \"{$namaKelompok}\". Silakan buat kelompok baru atau gabung ke kelompok lain di Portal Mahasiswa.",
                    namaKelompok: $namaKelompok,
                    namaAktor: "Ketua: {$mahasiswa->nama}",
                );
            }

            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('success', "Kelompok \"{$namaKelompok}\" telah dibubarkan.");
        }

        $namaKelompok = $kelompok->nama_kelompok;
        $ketua = $kelompok->ketua;
        $membership->delete();

        if ($ketua && (int) $ketua->id !== (int) $mahasiswa->id) {
            $this->notifyByEmail(
                penerima: $ketua,
                subjectLine: "Anggota Keluar: {$mahasiswa->nama}",
                badgeText: 'ANGGOTA KELUAR',
                badgeColor: '#D97706',
                heading: 'Anggota Meninggalkan Kelompok',
                messageText: "{$mahasiswa->nama} ({$mahasiswa->nim} - {$mahasiswa->kelas}) telah keluar dari kelompok \"{$namaKelompok}\".",
                namaKelompok: $namaKelompok,
                namaAktor: "Mantan Anggota: {$mahasiswa->nama}",
                ctaText: 'Lihat Daftar Anggota',
            );
        }

        return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
            ->with('success', "Kamu telah keluar dari kelompok \"{$namaKelompok}\".");
    }
}

