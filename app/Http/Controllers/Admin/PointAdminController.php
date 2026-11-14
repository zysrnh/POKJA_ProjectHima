<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Kelompok;
use App\Models\Pendaftaran;
use App\Models\PointHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PointAdminController extends Controller
{
    public const KATEGORI_CUSTOM = 'Custom Point';

    public const KATEGORI_TAMBAH = [
        'Kehadiran' => [
            'label' => 'Masuk Kelas / Kehadiran (+1 Point)',
            'point' => 1,
        ],
        'Aktif di Kelas' => [
            'label' => 'Aktif di Kelas (Tanya/Jawab) (+1 Point)',
            'point' => 1,
        ],
        'Tugas Tepat Waktu' => [
            'label' => 'Tugas Tepat Waktu (+2 Point)',
            'point' => 2,
        ],
        'Presentasi' => [
            'label' => 'Presentasi di Kelas (+3 Point)',
            'point' => 3,
        ],
    ];

    public const KATEGORI_KURANG = [
        'Tidak Hadir / Membolos' => [
            'label' => 'Tidak Hadir / Membolos (-1 Point)',
            'point' => 1,
        ],
        'Terlambat Kelas' => [
            'label' => 'Terlambat Kelas (-1 Point)',
            'point' => 1,
        ],
        'Tidak Mengumpulkan Tugas' => [
            'label' => 'Tidak Mengumpulkan Tugas (-2 Point)',
            'point' => 2,
        ],
        'Pelanggaran Disiplin' => [
            'label' => 'Pelanggaran Disiplin Kelas (-3 Point)',
            'point' => 3,
        ],
    ];

    /**
     * Tampilkan halaman Overview PointKu (Gabungan Ringkasan Leaderboard, Aktivitas Terbaru, & Daftar Mahasiswa).
     */
    public function index()
    {
        $totalMahasiswa = Pendaftaran::count();
        $totalPointDiberikan = (int) Pendaftaran::sum('total_point');
        $transaksiHariIni = PointHistory::whereDate('created_at', now()->timezone('Asia/Jakarta')->toDateString())->count();

        $topMahasiswa = Pendaftaran::withCount('pointHistories')
            ->withMax('pointHistories', 'created_at')
            ->withMax('pointHistories', 'id')
            ->get()
            ->sort(fn ($a, $b) => $this->compareLeaderboardStudents($a, $b))
            ->values()
            ->take(6)
            ->values();

        $topThree = $topMahasiswa->take(3)->values();
        $previewMahasiswa = $topMahasiswa->take(5)->values();

        $recentActivities = PointHistory::with('pendaftaran')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $allMahasiswaList = Pendaftaran::select('id', 'nama', 'nim', 'kelas', 'total_point')
            ->orderBy('nama', 'asc')
            ->get();

        $kategoriTambah = self::KATEGORI_TAMBAH;
        $kategoriKurang = self::KATEGORI_KURANG;

        return view('admin.poin.overview', compact(
            'totalMahasiswa',
            'totalPointDiberikan',
            'transaksiHariIni',
            'topThree',
            'previewMahasiswa',
            'recentActivities',
            'allMahasiswaList',
            'kategoriTambah',
            'kategoriKurang'
        ));
    }

    /**
     * Tampilkan halaman Daftar Mahasiswa & Kelola Point Lengkap.
     */
    public function mahasiswa(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $kelasFilter = trim((string) $request->input('kelas', ''));
        $sort = trim((string) $request->input('sort', 'point_desc'));

        $query = Pendaftaran::withCount('pointHistories');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        if ($kelasFilter !== '') {
            $query->where('kelas', $kelasFilter);
        }

        if ($sort === 'nama_asc') {
            $query->orderBy('nama', 'asc');
        } elseif ($sort === 'point_asc') {
            $query->orderBy('total_point', 'asc')->orderBy('nama', 'asc');
        } else {
            $query->orderBy('total_point', 'desc')->orderBy('nama', 'asc');
        }

        $pendaftarans = $query->paginate(20)->withQueryString();

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $allMahasiswaList = Pendaftaran::select('id', 'nama', 'nim', 'kelas', 'total_point')
            ->orderBy('nama', 'asc')
            ->get();

        $kategoriTambah = self::KATEGORI_TAMBAH;
        $kategoriKurang = self::KATEGORI_KURANG;

        return view('admin.poin.index', compact(
            'pendaftarans',
            'kelasList',
            'allMahasiswaList',
            'kategoriTambah',
            'kategoriKurang',
            'search',
            'kelasFilter',
            'sort'
        ));
    }

    /**
     * Hitung MD5 signature ringan untuk mendeteksi perubahan leaderboard admin tiap 3 detik.
     */
    private function computeLeaderboardSignature(string $kelasFilter): string
    {
        $pendaftarAgg = DB::table('pendaftarans')
            ->selectRaw('COUNT(*) as c, COALESCE(SUM(total_point), 0) as s, COALESCE(SUM(CASE WHEN foto IS NOT NULL THEN 1 ELSE 0 END), 0) as fc, MAX(updated_at) as m')
            ->first();

        $historyAgg = DB::table('point_histories')
            ->selectRaw('COUNT(*) as c, MAX(id) as m')
            ->first();

        $kelompokAgg = DB::table('kelompoks')
            ->selectRaw('COUNT(*) as c, COALESCE(SUM(id), 0) as s, COALESCE(SUM(CASE WHEN foto IS NOT NULL THEN 1 ELSE 0 END), 0) as fc, MAX(updated_at) as m')
            ->first();

        $memberAgg = DB::table('kelompok_members')
            ->selectRaw('COUNT(*) as c, COALESCE(SUM(id), 0) as s, MAX(updated_at) as m')
            ->first();

        $raw = implode('|', [
            $kelasFilter,
            (int) ($pendaftarAgg->c ?? 0),
            (int) ($pendaftarAgg->s ?? 0),
            (int) ($pendaftarAgg->fc ?? 0),
            (string) ($pendaftarAgg->m ?? ''),
            (int) ($historyAgg->c ?? 0),
            (int) ($historyAgg->m ?? 0),
            (int) ($kelompokAgg->c ?? 0),
            (int) ($kelompokAgg->s ?? 0),
            (int) ($kelompokAgg->fc ?? 0),
            (string) ($kelompokAgg->m ?? ''),
            (int) ($memberAgg->c ?? 0),
            (int) ($memberAgg->s ?? 0),
            (string) ($memberAgg->m ?? ''),
        ]);

        return md5($raw);
    }

    /**
     * Bandingkan dua peserta untuk urutan leaderboard:
     * 1. total_point terbesar
     * 2. Waktu pencapaian poin terakhir paling awal (yang lebih dulu dapat poin di atas)
     * 3. ID transaksi terakhir paling kecil
     * 4. Nama A-Z (jika sama-sama belum punya riwayat poin)
     */
    private function compareLeaderboardStudents(object $a, object $b): int
    {
        if ((int) $a->total_point !== (int) $b->total_point) {
            return (int) $b->total_point <=> (int) $a->total_point;
        }

        $aAt = $a->point_histories_max_created_at ?? null;
        $bAt = $b->point_histories_max_created_at ?? null;
        $aHas = $aAt !== null;
        $bHas = $bAt !== null;

        if ($aHas !== $bHas) {
            return $aHas ? -1 : 1;
        }

        if ($aHas && (string) $aAt !== (string) $bAt) {
            return strcmp((string) $aAt, (string) $bAt);
        }

        $aId = (int) ($a->point_histories_max_id ?? 0);
        $bId = (int) ($b->point_histories_max_id ?? 0);
        if ($aHas && $aId !== $bId) {
            return $aId <=> $bId;
        }

        return strcasecmp((string) $a->nama, (string) $b->nama);
    }

    /**
     * Bangun koleksi leaderboard Peserta & Kelompok untuk halaman dan sinkronisasi JSON.
     */
    private function buildLeaderboardData(string $kelasFilter): array
    {
        $query = Pendaftaran::withCount('pointHistories')
            ->withMax('pointHistories', 'created_at')
            ->withMax('pointHistories', 'id');

        if ($kelasFilter !== '') {
            $query->where('kelas', $kelasFilter);
        }

        $rankedMahasiswa = $query
            ->get()
            ->sort(fn ($a, $b) => $this->compareLeaderboardStudents($a, $b))
            ->values();

        $topThree = $rankedMahasiswa->take(3)->values();

        $kelompokLeaderboardList = Kelompok::with([
            'ketua',
            'members.pendaftaran' => fn ($q) => $q
                ->withMax('pointHistories', 'created_at')
                ->withMax('pointHistories', 'id'),
        ])
            ->get()
            ->map(function ($k) {
                $totalPoint = $k->members->sum(fn ($m) => (int) ($m->pendaftaran->total_point ?? 0));
                $jumlahAnggota = $k->members->count();
                $lastPointAt = $k->members
                    ->pluck('pendaftaran.point_histories_max_created_at')
                    ->filter()
                    ->max();
                $lastPointId = (int) ($k->members
                    ->pluck('pendaftaran.point_histories_max_id')
                    ->filter()
                    ->max() ?? 0);

                return (object) [
                    'id' => (int) $k->id,
                    'nama_kelompok' => $k->nama_kelompok,
                    'initials' => $k->initials(),
                    'foto_url' => $k->fotoUrl(),
                    'ketua_nama' => $k->ketua->nama ?? '-',
                    'ketua_kelas' => $k->ketua->kelas ?? '-',
                    'jumlah_anggota' => $jumlahAnggota,
                    'total_point' => (int) $totalPoint,
                    'avg_point' => $jumlahAnggota > 0 ? round($totalPoint / $jumlahAnggota, 1) : 0,
                    'last_point_at' => $lastPointAt ? (string) $lastPointAt : null,
                    'last_point_id' => $lastPointId,
                ];
            })
            ->sort(function ($a, $b) {
                if ($a->total_point !== $b->total_point) {
                    return $b->total_point <=> $a->total_point;
                }
                $aHas = $a->last_point_at !== null;
                $bHas = $b->last_point_at !== null;
                if ($aHas !== $bHas) {
                    return $aHas ? -1 : 1;
                }
                if ($aHas && $a->last_point_at !== $b->last_point_at) {
                    return strcmp($a->last_point_at, $b->last_point_at);
                }
                if ($aHas && $a->last_point_id !== $b->last_point_id) {
                    return $a->last_point_id <=> $b->last_point_id;
                }
                if ($a->jumlah_anggota !== $b->jumlah_anggota) {
                    return $b->jumlah_anggota <=> $a->jumlah_anggota;
                }
                return strcasecmp($a->nama_kelompok, $b->nama_kelompok);
            })
            ->values();

        $kelompokTopThree = $kelompokLeaderboardList->take(3)->values();

        return [
            'rankedMahasiswa' => $rankedMahasiswa,
            'topThree' => $topThree,
            'totalPeserta' => $rankedMahasiswa->count(),
            'kelompokLeaderboardList' => $kelompokLeaderboardList,
            'kelompokTopThree' => $kelompokTopThree,
            'totalKelompok' => $kelompokLeaderboardList->count(),
        ];
    }

    /**
     * Tampilkan halaman Leaderboard Peringkat Point Mahasiswa & Kelompok.
     */
    public function leaderboard(Request $request)
    {
        $kelasFilter = trim((string) $request->input('kelas', ''));
        $data = $this->buildLeaderboardData($kelasFilter);

        $rankedMahasiswa = $data['rankedMahasiswa'];
        $topThree = $data['topThree'];
        $totalPeserta = $data['totalPeserta'];
        $kelompokLeaderboardList = $data['kelompokLeaderboardList'];
        $kelompokTopThree = $data['kelompokTopThree'];
        $totalKelompok = $data['totalKelompok'];
        $realtimeSignature = $this->computeLeaderboardSignature($kelasFilter);

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        // Statistik akumulasi point per kelas untuk grafik Leaderboard
        $rawKelasStats = Pendaftaran::selectRaw('kelas, COUNT(*) as total_mhs, COALESCE(SUM(total_point), 0) as total_point, COALESCE(ROUND(AVG(total_point), 1), 0) as avg_point')
            ->groupBy('kelas')
            ->get()
            ->keyBy('kelas');

        $kelasStats = $kelasList->map(function ($k) use ($rawKelasStats) {
            $stat = $rawKelasStats->get($k->nama_kelas);
            return [
                'kelas' => $k->nama_kelas,
                'total_mhs' => $stat ? (int) $stat->total_mhs : 0,
                'total_point' => $stat ? (int) $stat->total_point : 0,
                'avg_point' => $stat ? (float) $stat->avg_point : 0.0,
            ];
        })->values();

        return view('admin.poin.leaderboard', compact(
            'rankedMahasiswa',
            'topThree',
            'totalPeserta',
            'kelompokLeaderboardList',
            'kelompokTopThree',
            'totalKelompok',
            'realtimeSignature',
            'kelasList',
            'kelasFilter',
            'kelasStats'
        ));
    }

    /**
     * Endpoint sinkronisasi JSON realtime tiap 3 detik untuk Leaderboard Admin.
     */
    public function leaderboardSync(Request $request)
    {
        $kelasFilter = trim((string) $request->query('kelas', ''));
        $signature = $this->computeLeaderboardSignature($kelasFilter);

        if ((string) $request->query('sig', '') === $signature) {
            return response()->json([
                'changed' => false,
                'signature' => $signature,
            ]);
        }

        $data = $this->buildLeaderboardData($kelasFilter);

        return response()->json([
            'changed' => true,
            'signature' => $signature,
            'kelas_filter' => $kelasFilter,
            'total_peserta' => $data['totalPeserta'],
            'total_kelompok' => $data['totalKelompok'],
            'individu_leaderboard' => $data['rankedMahasiswa']->values()->map(fn ($mhs, $idx) => [
                'id' => (int) $mhs->id,
                'rank' => $idx + 1,
                'nama' => $mhs->nama,
                'nim' => $mhs->nim,
                'kelas' => $mhs->kelas,
                'total_point' => (int) $mhs->total_point,
                'point_histories_count' => (int) ($mhs->point_histories_count ?? 0),
                'initials' => $mhs->initials(),
                'avatar_url' => $mhs->avatarUrl(),
            ])->values(),
            'kelompok_leaderboard' => $data['kelompokLeaderboardList']->values()->map(fn ($grp, $idx) => [
                'id' => (int) $grp->id,
                'rank' => $idx + 1,
                'nama_kelompok' => $grp->nama_kelompok,
                'initials' => $grp->initials,
                'foto_url' => $grp->foto_url,
                'ketua_nama' => $grp->ketua_nama,
                'ketua_kelas' => $grp->ketua_kelas,
                'jumlah_anggota' => (int) $grp->jumlah_anggota,
                'total_point' => (int) $grp->total_point,
                'avg_point' => $grp->avg_point,
            ])->values(),
        ]);
    }

    /**
     * Tampilkan halaman Log Aktivitas Point seluruh mahasiswa.
     */
    public function aktivitas(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $tipeFilter = trim((string) $request->input('tipe', ''));

        $query = PointHistory::with('pendaftaran')->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        if ($tipeFilter !== '' && in_array($tipeFilter, ['tambah', 'kurang'], true)) {
            $query->where('tipe', $tipeFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('kategori', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('created_by', 'like', "%{$search}%")
                  ->orWhereHas('pendaftaran', function ($sub) use ($search) {
                      $sub->where('nama', 'like', "%{$search}%")
                          ->orWhere('nim', 'like', "%{$search}%")
                          ->orWhere('kelas', 'like', "%{$search}%");
                  });
            });
        }

        $histories = $query->paginate(25)->withQueryString();

        $totalTambah = PointHistory::where('tipe', 'tambah')->count();
        $totalKurang = PointHistory::where('tipe', 'kurang')->count();

        return view('admin.poin.aktivitas', compact(
            'histories',
            'totalTambah',
            'totalKurang',
            'search',
            'tipeFilter'
        ));
    }

    /**
     * Simpan transaksi Tambah Point (+) atau Kurangi Point (-).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendaftaran_id' => ['required', 'integer', 'exists:pendaftarans,id'],
            'tipe' => ['required', 'in:tambah,kurang'],
            'kategori' => ['required', 'string', 'max:100'],
            'jumlah_custom' => [
                $request->input('kategori') === self::KATEGORI_CUSTOM ? 'required' : 'nullable',
                'integer',
                'min:1',
                'max:100',
            ],
            'keterangan' => [
                $request->input('tipe') === 'kurang' ? 'required' : 'nullable',
                'string',
                'max:255',
            ],
        ], [
            'pendaftaran_id.required' => 'Silakan pilih mahasiswa terlebih dahulu.',
            'pendaftaran_id.exists' => 'Data mahasiswa tidak ditemukan.',
            'kategori.required' => 'Silakan pilih kategori point.',
            'jumlah_custom.required' => 'Jumlah point custom wajib diisi.',
            'jumlah_custom.integer' => 'Jumlah point custom harus berupa angka bulat.',
            'jumlah_custom.min' => 'Jumlah point custom minimal 1.',
            'jumlah_custom.max' => 'Jumlah point custom maksimal 100.',
            'keterangan.required' => 'Alasan pengurangan point wajib diisi.',
        ]);

        $tipe = $validated['tipe'];
        $kategori = $validated['kategori'];

        if ($kategori === self::KATEGORI_CUSTOM) {
            $jumlah = (int) $validated['jumlah_custom'];
        } elseif ($tipe === 'tambah') {
            if (!isset(self::KATEGORI_TAMBAH[$kategori])) {
                return back()->with('error', 'Kategori penambahan point tidak valid.');
            }
            $jumlah = self::KATEGORI_TAMBAH[$kategori]['point'];
        } else {
            if (!isset(self::KATEGORI_KURANG[$kategori])) {
                return back()->with('error', 'Kategori pengurangan point tidak valid.');
            }
            $jumlah = self::KATEGORI_KURANG[$kategori]['point'];
        }

        $mahasiswa = Pendaftaran::findOrFail($validated['pendaftaran_id']);

        DB::transaction(function () use ($mahasiswa, $tipe, $kategori, $jumlah, $validated) {
            PointHistory::create([
                'pendaftaran_id' => $mahasiswa->id,
                'tipe' => $tipe,
                'kategori' => $kategori,
                'jumlah' => $jumlah,
                'keterangan' => !empty($validated['keterangan']) ? trim($validated['keterangan']) : null,
                'created_by' => Auth::user()?->name ?? 'Admin',
            ]);

            $currentPoint = (int) $mahasiswa->total_point;
            $newPoint = $tipe === 'tambah'
                ? ($currentPoint + $jumlah)
                : max(0, $currentPoint - $jumlah);

            $mahasiswa->update([
                'total_point' => $newPoint,
            ]);
        });

        $simbol = $tipe === 'tambah' ? "+{$jumlah}" : "-{$jumlah}";

        return back()->with('success', "Berhasil mencatat {$simbol} Point ({$kategori}) untuk {$mahasiswa->nama}!");
    }

    /**
     * Ambil daftar riwayat point milik satu mahasiswa (JSON untuk Modal Riwayat).
     */
    public function history($id)
    {
        $mahasiswa = Pendaftaran::findOrFail($id);

        $histories = PointHistory::where('pendaftaran_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function (PointHistory $h) {
                return [
                    'id' => $h->id,
                    'tipe' => $h->tipe,
                    'kategori' => $h->kategori,
                    'jumlah' => (int) $h->jumlah,
                    'keterangan' => $h->keterangan,
                    'created_by' => $h->created_by ?? 'Admin',
                    'tanggal' => $h->created_at
                        ? $h->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i')
                        : '-',
                ];
            });

        return response()->json([
            'success' => true,
            'mahasiswa' => [
                'id' => $mahasiswa->id,
                'nama' => $mahasiswa->nama,
                'nim' => $mahasiswa->nim,
                'kelas' => $mahasiswa->kelas,
                'total_point' => (int) $mahasiswa->total_point,
            ],
            'histories' => $histories,
        ]);
    }

    /**
     * Hapus / batalkan satu item riwayat point dan hitung ulang total_point mahasiswa.
     */
    public function destroyHistory($historyId)
    {
        $history = PointHistory::findOrFail($historyId);
        $mahasiswa = Pendaftaran::findOrFail($history->pendaftaran_id);

        DB::transaction(function () use ($history, $mahasiswa) {
            $history->delete();

            // Hitung ulang total point secara berurutan dari riwayat yang tersisa
            $remaining = PointHistory::where('pendaftaran_id', $mahasiswa->id)
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $recalculated = 0;
            foreach ($remaining as $item) {
                if ($item->tipe === 'tambah') {
                    $recalculated += (int) $item->jumlah;
                } else {
                    $recalculated = max(0, $recalculated - (int) $item->jumlah);
                }
            }

            $mahasiswa->update([
                'total_point' => $recalculated,
            ]);
        });

        return back()->with('success', "Riwayat point untuk {$mahasiswa->nama} berhasil dihapus & total point telah diperbarui.");
    }
}

