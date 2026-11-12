<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\KelompokInvitation;
use App\Models\KelompokMember;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $kelompok = Kelompok::where('kode_invite', $kode)->first();
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
     * Memperbarui nama kelompok atau ide judul proposal (khusus Ketua).
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
        ], [
            'nama_kelompok.required' => 'Nama kelompok wajib diisi.',
            'nama_kelompok.unique' => 'Nama kelompok tersebut sudah dipakai kelompok lain.',
        ]);

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

        $invitation = KelompokInvitation::with('kelompok')
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

        $namaAnggota = $targetMember->pendaftaran->nama ?? 'Anggota';
        $targetMember->delete();

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

        $membership = KelompokMember::with(['kelompok.members'])->where('pendaftaran_id', $mahasiswa->id)->first();
        if (!$membership || !$membership->kelompok) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Kamu belum tergabung di kelompok mana pun.');
        }

        $kelompok = $membership->kelompok;

        if ((int) $kelompok->ketua_id === (int) $mahasiswa->id) {
            $namaKelompok = $kelompok->nama_kelompok;
            $kelompok->delete();

            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('success', "Kelompok \"{$namaKelompok}\" telah dibubarkan.");
        }

        $namaKelompok = $kelompok->nama_kelompok;
        $membership->delete();

        return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
            ->with('success', "Kamu telah keluar dari kelompok \"{$namaKelompok}\".");
    }
}

