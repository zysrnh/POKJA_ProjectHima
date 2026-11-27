<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\KelompokMember;
use App\Models\Pendaftaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MahasiswaKelompokController extends Controller
{
    public const MIN_ANGGOTA = 3;
    public const MAX_ANGGOTA = 4;

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
     * Tolak seluruh aksi pembentukan, undangan, dan perubahan keanggotaan oleh mahasiswa.
     */
    private function rejectStudentGroupManagement(
        Request $request,
        string $message = 'Pembentukan kelompok dan pengaturan anggota hanya dilakukan oleh Super Admin POKJA.'
    ): RedirectResponse {
        session()->forget('pending_kelompok_kode');

        $mahasiswa = $this->getAuthenticatedMahasiswa($request);
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')
                ->with('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
        }

        return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
            ->with('error', $message);
    }

    /**
     * Pembuatan kelompok mandiri dinonaktifkan (khusus Super Admin).
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->rejectStudentGroupManagement($request);
    }

    /**
     * Memperbarui ide judul proposal dan foto profil kelompok (khusus Ketua).
     * Nama kelompok dan susunan anggota tetap terkunci di Super Admin.
     */
    public function update(Request $request): RedirectResponse
    {
        $mahasiswa = $this->getAuthenticatedMahasiswa($request);
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.login')->with('error', 'Sesi login Anda tidak valid. Silakan login kembali.');
        }

        $membership = KelompokMember::with('kelompok')->where('pendaftaran_id', $mahasiswa->id)->first();
        if (!$membership || !$membership->kelompok || (int) $membership->kelompok->ketua_id !== (int) $mahasiswa->id) {
            return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
                ->with('error', 'Hanya Ketua kelompok yang dapat memperbarui judul proposal dan foto tim.');
        }

        $kelompok = $membership->kelompok;

        $request->merge([
            'judul_proposal' => trim(strip_tags((string) $request->input('judul_proposal', ''))),
        ]);

        $request->validate([
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
            'judul_proposal.max' => 'Ide/judul proposal maksimal 200 karakter.',
            'foto.image' => 'File foto kelompok harus berupa gambar asli.',
            'foto.mimes' => 'Format foto kelompok harus JPG, JPEG, PNG, atau WEBP.',
            'foto.mimetypes' => 'Konten file foto kelompok bukan gambar yang valid.',
            'foto.max' => 'Ukuran file foto kelompok maksimal 2 MB.',
            'foto.dimensions' => 'Resolusi foto kelompok minimal 50x50 piksel dan maksimal 4096x4096 piksel.',
        ]);

        if ($request->boolean('hapus_foto') && !$request->hasFile('foto')) {
            Kelompok::deleteFotoFile($kelompok->foto);
            $kelompok->foto = null;
        }

        if ($request->hasFile('foto')) {
            $savedFilename = Kelompok::storeValidatedFoto($request->file('foto'), $kelompok);
            if (!$savedFilename) {
                return back()->withErrors(['foto' => 'File gambar rusak atau terdeteksi tidak aman.'])->withInput();
            }
            $kelompok->foto = $savedFilename;
        }

        $kelompok->judul_proposal = $request->judul_proposal ?: null;
        $kelompok->save();

        return redirect()->route('mahasiswa.dashboard', ['tab' => 'kelompok'])
            ->with('success', 'Judul proposal dan foto kelompok berhasil diperbarui!');
    }

    /**
     * Join kelompok via link dinonaktifkan (khusus Super Admin).
     */
    public function joinByLink(Request $request, string $kode): RedirectResponse
    {
        return $this->rejectStudentGroupManagement($request);
    }

    /**
     * Join kelompok via kode/QR dinonaktifkan (khusus Super Admin).
     */
    public function joinByCode(Request $request): RedirectResponse
    {
        return $this->rejectStudentGroupManagement($request);
    }

    /**
     * Kirim undangan ke teman dinonaktifkan (khusus Super Admin).
     */
    public function sendInvite(Request $request): RedirectResponse
    {
        return $this->rejectStudentGroupManagement($request);
    }

    /**
     * Batalkan undangan dinonaktifkan (khusus Super Admin).
     */
    public function cancelInvite(Request $request, int $invitationId): RedirectResponse
    {
        return $this->rejectStudentGroupManagement($request);
    }

    /**
     * Respon undangan inbox dinonaktifkan (khusus Super Admin).
     */
    public function respondInvite(Request $request, int $invitationId): RedirectResponse
    {
        return $this->rejectStudentGroupManagement($request);
    }

    /**
     * Keluarkan anggota oleh Ketua dinonaktifkan (khusus Super Admin).
     */
    public function removeMember(Request $request, int $memberId): RedirectResponse
    {
        return $this->rejectStudentGroupManagement($request);
    }

    /**
     * Transfer Ketua oleh mahasiswa dinonaktifkan (khusus Super Admin).
     */
    public function transferKetua(Request $request, int $memberId): RedirectResponse
    {
        return $this->rejectStudentGroupManagement($request);
    }

    /**
     * Keluar / bubarkan kelompok oleh mahasiswa dinonaktifkan (khusus Super Admin).
     */
    public function leaveOrDisband(Request $request): RedirectResponse
    {
        return $this->rejectStudentGroupManagement($request);
    }
}
