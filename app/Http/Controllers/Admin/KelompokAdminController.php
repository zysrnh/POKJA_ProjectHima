<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MahasiswaKelompokController;
use App\Models\Kelompok;
use App\Models\KelompokInvitation;
use App\Models\KelompokMember;
use App\Models\Pendaftaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KelompokAdminController extends Controller
{
    /**
     * Batasi seluruh operasi manajemen kelompok hanya untuk Super Admin.
     */
    private function ensureSuperAdmin(): ?RedirectResponse
    {
        if (!Auth::user()?->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak! Menu Kelola Kelompok hanya dapat diakses oleh Super Admin.');
        }

        return null;
    }

    /**
     * Cek apakah ada mahasiswa terpilih yang sudah tergabung di kelompok lain.
     */
    private function findAssignedMemberConflict(iterable $pendaftaranIds): ?string
    {
        $conflict = KelompokMember::with(['pendaftaran', 'kelompok'])
            ->whereIn('pendaftaran_id', $pendaftaranIds)
            ->lockForUpdate()
            ->first();

        if (!$conflict) {
            return null;
        }

        $namaMhs = $conflict->pendaftaran->nama ?? 'Mahasiswa terpilih';
        $namaTim = $conflict->kelompok->nama_kelompok ?? 'kelompok lain';

        return "{$namaMhs} sudah tergabung di kelompok \"{$namaTim}\".";
    }

    /**
     * Daftar seluruh kelompok beserta ketua, anggota, total poin tim, dan statistik kuota.
     */
    public function index(Request $request)
    {
        if ($redirect = $this->ensureSuperAdmin()) {
            return $redirect;
        }

        $search = trim((string) $request->query('search', ''));
        $statusFilter = trim((string) $request->query('status', 'all'));

        $query = Kelompok::with([
            'ketua',
            'members' => fn ($q) => $q
                ->orderByRaw("CASE WHEN role = 'ketua' THEN 0 ELSE 1 END")
                ->orderBy('joined_at', 'asc')
                ->with('pendaftaran'),
        ])->withCount('members');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kelompok', 'like', "%{$search}%")
                    ->orWhere('kode_invite', 'like', "%{$search}%")
                    ->orWhere('judul_proposal', 'like', "%{$search}%")
                    ->orWhereHas('members.pendaftaran', function ($memberQuery) use ($search) {
                        $memberQuery->where('nama', 'like', "%{$search}%")
                            ->orWhere('nim', 'like', "%{$search}%")
                            ->orWhere('kelas', 'like', "%{$search}%");
                    });
            });
        }

        $minAnggota = MahasiswaKelompokController::MIN_ANGGOTA;
        $maxAnggota = MahasiswaKelompokController::MAX_ANGGOTA;

        if ($statusFilter === 'kurang') {
            $query->has('members', '<', $minAnggota);
        } elseif ($statusFilter === 'memenuhi') {
            $query->has('members', '>=', $minAnggota)
                ->has('members', '<', $maxAnggota);
        } elseif ($statusFilter === 'penuh') {
            $query->has('members', '>=', $maxAnggota);
        }

        $kelompoks = $query->latest()->paginate(12)->withQueryString();

        $totalKelompok = Kelompok::count();
        $totalMahasiswaBerkelompok = KelompokMember::count();
        $totalPendaftar = Pendaftaran::count();
        $belumPunyaKelompok = max(0, $totalPendaftar - $totalMahasiswaBerkelompok);

        return view('admin.kelompok.index', compact(
            'kelompoks',
            'search',
            'statusFilter',
            'totalKelompok',
            'totalMahasiswaBerkelompok',
            'belumPunyaKelompok',
            'totalPendaftar'
        ));
    }

    /**
     * Form pembuatan kelompok baru oleh Super Admin.
     */
    public function create()
    {
        if ($redirect = $this->ensureSuperAdmin()) {
            return $redirect;
        }

        $kandidatMahasiswa = Pendaftaran::whereDoesntHave('kelompokMember')
            ->orderBy('kelas', 'asc')
            ->orderBy('nama', 'asc')
            ->get(['id', 'nama', 'nim', 'kelas', 'total_point']);

        return view('admin.kelompok.create', compact('kandidatMahasiswa'));
    }

    /**
     * Simpan kelompok baru beserta Ketua dan anggota awal dalam transaksi atomik.
     */
    public function store(Request $request)
    {
        if ($redirect = $this->ensureSuperAdmin()) {
            return $redirect;
        }

        $request->merge([
            'nama_kelompok' => trim(strip_tags((string) $request->input('nama_kelompok', ''))),
            'judul_proposal' => trim(strip_tags((string) $request->input('judul_proposal', ''))),
        ]);

        $request->validate([
            'nama_kelompok' => ['required', 'string', 'min:2', 'max:80', 'unique:kelompoks,nama_kelompok'],
            'judul_proposal' => ['nullable', 'string', 'max:200'],
            'ketua_id' => ['required', 'integer', 'exists:pendaftarans,id'],
            'anggota_ids' => ['nullable', 'array'],
            'anggota_ids.*' => ['integer', 'distinct', 'exists:pendaftarans,id'],
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
            'nama_kelompok.min' => 'Nama kelompok minimal 2 karakter.',
            'nama_kelompok.max' => 'Nama kelompok maksimal 80 karakter.',
            'nama_kelompok.unique' => 'Nama kelompok tersebut sudah dipakai.',
            'judul_proposal.max' => 'Ide/judul proposal maksimal 200 karakter.',
            'ketua_id.required' => 'Silakan pilih 1 mahasiswa sebagai Ketua kelompok.',
            'ketua_id.exists' => 'Data mahasiswa Ketua tidak ditemukan.',
            'anggota_ids.*.exists' => 'Salah satu mahasiswa anggota yang dipilih tidak ditemukan.',
            'foto.image' => 'File foto kelompok harus berupa gambar asli.',
            'foto.mimes' => 'Format foto kelompok harus JPG, JPEG, PNG, atau WEBP.',
            'foto.mimetypes' => 'Konten file foto kelompok bukan gambar yang valid.',
            'foto.max' => 'Ukuran file foto kelompok maksimal 2 MB.',
            'foto.dimensions' => 'Resolusi foto kelompok minimal 50x50 piksel dan maksimal 4096x4096 piksel.',
        ]);

        $ketuaId = (int) $request->input('ketua_id');
        $anggotaIds = collect($request->input('anggota_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0 && $id !== $ketuaId)
            ->unique()
            ->values();

        $allMemberIds = collect([$ketuaId])->merge($anggotaIds)->values();
        $maxAnggota = MahasiswaKelompokController::MAX_ANGGOTA;

        if ($allMemberIds->count() > $maxAnggota) {
            return back()
                ->withErrors(['anggota_ids' => "Total anggota termasuk Ketua maksimal {$maxAnggota} orang."])
                ->withInput();
        }

        if ($request->hasFile('foto')) {
            $imageInfo = @getimagesize($request->file('foto')->getRealPath());
            if ($imageInfo === false || !in_array($imageInfo['mime'] ?? '', ['image/jpeg', 'image/png', 'image/webp'], true)) {
                return back()->withErrors(['foto' => 'File gambar rusak atau terdeteksi tidak aman.'])->withInput();
            }
        }

        $result = DB::transaction(function () use ($request, $ketuaId, $anggotaIds, $allMemberIds) {
            if ($conflictMsg = $this->findAssignedMemberConflict($allMemberIds)) {
                return ['ok' => false, 'error' => $conflictMsg];
            }

            $kelompok = Kelompok::create([
                'nama_kelompok' => $request->nama_kelompok,
                'kode_invite' => Kelompok::generateUniqueInviteCode(),
                'ketua_id' => $ketuaId,
                'judul_proposal' => $request->judul_proposal ?: null,
            ]);

            $now = now();
            KelompokMember::create([
                'kelompok_id' => $kelompok->id,
                'pendaftaran_id' => $ketuaId,
                'role' => 'ketua',
                'joined_at' => $now,
            ]);

            foreach ($anggotaIds as $anggotaId) {
                KelompokMember::create([
                    'kelompok_id' => $kelompok->id,
                    'pendaftaran_id' => $anggotaId,
                    'role' => 'anggota',
                    'joined_at' => $now,
                ]);
            }

            KelompokInvitation::whereIn('pendaftaran_id', $allMemberIds)
                ->where('status', 'pending')
                ->update([
                    'status' => 'declined',
                    'responded_at' => $now,
                ]);

            return ['ok' => true, 'kelompok' => $kelompok];
        });

        if (!($result['ok'] ?? false)) {
            return back()->with('error', $result['error'] ?? 'Gagal membuat kelompok.')->withInput();
        }

        /** @var Kelompok $kelompok */
        $kelompok = $result['kelompok'];

        if ($request->hasFile('foto')) {
            $savedFilename = Kelompok::storeValidatedFoto($request->file('foto'), $kelompok);
            if ($savedFilename) {
                $kelompok->foto = $savedFilename;
                $kelompok->save();
            }
        }

        return redirect()->route('admin.kelompok.edit', $kelompok->id)
            ->with('success', "Kelompok \"{$kelompok->nama_kelompok}\" berhasil dibuat dengan {$allMemberIds->count()} orang.");
    }

    /**
     * Halaman edit informasi kelompok dan pengelolaan anggota/ketua.
     */
    public function edit($id)
    {
        if ($redirect = $this->ensureSuperAdmin()) {
            return $redirect;
        }

        $kelompok = Kelompok::with([
            'ketua',
            'members' => fn ($q) => $q
                ->orderByRaw("CASE WHEN role = 'ketua' THEN 0 ELSE 1 END")
                ->orderBy('joined_at', 'asc')
                ->with('pendaftaran'),
        ])->findOrFail($id);

        $kandidatMahasiswa = Pendaftaran::whereDoesntHave('kelompokMember')
            ->orderBy('kelas', 'asc')
            ->orderBy('nama', 'asc')
            ->get(['id', 'nama', 'nim', 'kelas', 'total_point']);

        return view('admin.kelompok.edit', compact('kelompok', 'kandidatMahasiswa'));
    }

    /**
     * Perbarui nama kelompok, judul proposal, dan foto kelompok.
     */
    public function update(Request $request, $id)
    {
        if ($redirect = $this->ensureSuperAdmin()) {
            return $redirect;
        }

        $kelompok = Kelompok::findOrFail($id);

        $request->merge([
            'nama_kelompok' => trim(strip_tags((string) $request->input('nama_kelompok', ''))),
            'judul_proposal' => trim(strip_tags((string) $request->input('judul_proposal', ''))),
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
            'nama_kelompok.min' => 'Nama kelompok minimal 2 karakter.',
            'nama_kelompok.max' => 'Nama kelompok maksimal 80 karakter.',
            'nama_kelompok.unique' => 'Nama kelompok tersebut sudah dipakai kelompok lain.',
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

        $kelompok->nama_kelompok = $request->nama_kelompok;
        $kelompok->judul_proposal = $request->judul_proposal ?: null;
        $kelompok->save();

        return redirect()->route('admin.kelompok.edit', $kelompok->id)
            ->with('success', "Informasi kelompok \"{$kelompok->nama_kelompok}\" berhasil diperbarui.");
    }

    /**
     * Menambahkan satu atau beberapa mahasiswa (yang belum ber-kelompok) ke dalam kelompok ini.
     */
    public function addMembers(Request $request, $id)
    {
        if ($redirect = $this->ensureSuperAdmin()) {
            return $redirect;
        }

        $request->validate([
            'pendaftaran_ids' => ['required', 'array', 'min:1'],
            'pendaftaran_ids.*' => ['integer', 'distinct', 'exists:pendaftarans,id'],
        ], [
            'pendaftaran_ids.required' => 'Pilih minimal 1 mahasiswa untuk ditambahkan ke kelompok.',
            'pendaftaran_ids.min' => 'Pilih minimal 1 mahasiswa untuk ditambahkan ke kelompok.',
            'pendaftaran_ids.*.exists' => 'Data mahasiswa yang dipilih tidak ditemukan.',
        ]);

        $selectedIds = collect($request->input('pendaftaran_ids', []))
            ->map(fn ($mhsId) => (int) $mhsId)
            ->filter(fn ($mhsId) => $mhsId > 0)
            ->unique()
            ->values();

        $maxAnggota = MahasiswaKelompokController::MAX_ANGGOTA;

        $result = DB::transaction(function () use ($id, $selectedIds, $maxAnggota) {
            $kelompok = Kelompok::where('id', $id)->lockForUpdate()->firstOrFail();
            $currentCount = KelompokMember::where('kelompok_id', $kelompok->id)->count();
            $remaining = max(0, $maxAnggota - $currentCount);

            if ($selectedIds->count() > $remaining) {
                return [
                    'ok' => false,
                    'error' => $remaining === 0
                        ? "Kelompok \"{$kelompok->nama_kelompok}\" sudah penuh (maksimal {$maxAnggota} anggota)."
                        : "Sisa kuota kelompok ini hanya {$remaining} orang lagi (maksimal {$maxAnggota} anggota).",
                ];
            }

            if ($conflictMsg = $this->findAssignedMemberConflict($selectedIds)) {
                return ['ok' => false, 'error' => $conflictMsg];
            }

            $now = now();
            foreach ($selectedIds as $pendaftaranId) {
                KelompokMember::create([
                    'kelompok_id' => $kelompok->id,
                    'pendaftaran_id' => $pendaftaranId,
                    'role' => 'anggota',
                    'joined_at' => $now,
                ]);
            }

            KelompokInvitation::whereIn('pendaftaran_id', $selectedIds)
                ->where('status', 'pending')
                ->update([
                    'status' => 'declined',
                    'responded_at' => $now,
                ]);

            return ['ok' => true, 'kelompok' => $kelompok, 'addedCount' => $selectedIds->count()];
        });

        if (!($result['ok'] ?? false)) {
            return redirect()->route('admin.kelompok.edit', $id)
                ->with('error', $result['error'] ?? 'Gagal menambahkan anggota.');
        }

        return redirect()->route('admin.kelompok.edit', $id)
            ->with('success', "{$result['addedCount']} mahasiswa berhasil ditambahkan ke kelompok \"{$result['kelompok']->nama_kelompok}\".");
    }

    /**
     * Mengalihkan jabatan Ketua ke anggota lain di dalam kelompok yang sama.
     */
    public function setKetua($id, $memberId)
    {
        if ($redirect = $this->ensureSuperAdmin()) {
            return $redirect;
        }

        $result = DB::transaction(function () use ($id, $memberId) {
            $kelompok = Kelompok::where('id', $id)->lockForUpdate()->firstOrFail();
            $targetMember = KelompokMember::with('pendaftaran')
                ->where('id', $memberId)
                ->where('kelompok_id', $kelompok->id)
                ->firstOrFail();

            if ((int) $kelompok->ketua_id === (int) $targetMember->pendaftaran_id) {
                return ['ok' => false, 'error' => 'Mahasiswa tersebut sudah menjabat sebagai Ketua kelompok.'];
            }

            $kelompok->ketua_id = $targetMember->pendaftaran_id;
            $kelompok->save();

            KelompokMember::where('kelompok_id', $kelompok->id)->update(['role' => 'anggota']);
            $targetMember->role = 'ketua';
            $targetMember->save();

            return [
                'ok' => true,
                'namaKetua' => $targetMember->pendaftaran->nama ?? 'Mahasiswa',
                'namaKelompok' => $kelompok->nama_kelompok,
            ];
        });

        if (!($result['ok'] ?? false)) {
            return redirect()->route('admin.kelompok.edit', $id)
                ->with('error', $result['error'] ?? 'Gagal mengganti Ketua kelompok.');
        }

        return redirect()->route('admin.kelompok.edit', $id)
            ->with('success', "{$result['namaKetua']} kini resmi menjabat sebagai Ketua kelompok \"{$result['namaKelompok']}\".");
    }

    /**
     * Mengeluarkan anggota non-Ketua dari kelompok.
     */
    public function removeMember($id, $memberId)
    {
        if ($redirect = $this->ensureSuperAdmin()) {
            return $redirect;
        }

        $result = DB::transaction(function () use ($id, $memberId) {
            $kelompok = Kelompok::where('id', $id)->lockForUpdate()->firstOrFail();
            $targetMember = KelompokMember::with('pendaftaran')
                ->where('id', $memberId)
                ->where('kelompok_id', $kelompok->id)
                ->firstOrFail();

            if ((int) $targetMember->pendaftaran_id === (int) $kelompok->ketua_id) {
                return [
                    'ok' => false,
                    'error' => 'Ketua kelompok tidak dapat dikeluarkan langsung. Pindahkan jabatan Ketua ke anggota lain terlebih dahulu, atau bubarkan kelompok.',
                ];
            }

            $namaMhs = $targetMember->pendaftaran->nama ?? 'Anggota';
            $targetMember->delete();

            return ['ok' => true, 'namaMhs' => $namaMhs, 'namaKelompok' => $kelompok->nama_kelompok];
        });

        if (!($result['ok'] ?? false)) {
            return redirect()->route('admin.kelompok.edit', $id)
                ->with('error', $result['error'] ?? 'Gagal mengeluarkan anggota.');
        }

        return redirect()->route('admin.kelompok.edit', $id)
            ->with('success', "{$result['namaMhs']} telah dikeluarkan dari kelompok \"{$result['namaKelompok']}\".");
    }

    /**
     * Membubarkan dan menghapus kelompok beserta file fotonya.
     */
    public function destroy($id)
    {
        if ($redirect = $this->ensureSuperAdmin()) {
            return $redirect;
        }

        $kelompok = Kelompok::findOrFail($id);
        $nama = $kelompok->nama_kelompok;
        $foto = $kelompok->foto;

        $kelompok->delete();
        Kelompok::deleteFotoFile($foto);

        return redirect()->route('admin.kelompok.index')
            ->with('success', "Kelompok \"{$nama}\" berhasil dibubarkan.");
    }
}
