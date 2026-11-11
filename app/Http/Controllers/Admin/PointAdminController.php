<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use App\Models\PointHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PointAdminController extends Controller
{
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
            ->orderBy('total_point', 'desc')
            ->orderBy('nama', 'asc')
            ->take(6)
            ->get();

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
     * Tampilkan halaman Leaderboard Peringkat Point Mahasiswa (Podium Top 3 + Peringkat Lengkap).
     */
    public function leaderboard(Request $request)
    {
        $kelasFilter = trim((string) $request->input('kelas', ''));

        $query = Pendaftaran::withCount('pointHistories');

        if ($kelasFilter !== '') {
            $query->where('kelas', $kelasFilter);
        }

        $rankedMahasiswa = $query
            ->orderBy('total_point', 'desc')
            ->orderBy('nama', 'asc')
            ->get();

        $topThree = $rankedMahasiswa->take(3)->values();
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
            'kelasList',
            'kelasFilter',
            'kelasStats'
        ));
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
            'keterangan' => [
                $request->input('tipe') === 'kurang' ? 'required' : 'nullable',
                'string',
                'max:255',
            ],
        ], [
            'pendaftaran_id.required' => 'Silakan pilih mahasiswa terlebih dahulu.',
            'pendaftaran_id.exists' => 'Data mahasiswa tidak ditemukan.',
            'kategori.required' => 'Silakan pilih kategori point.',
            'keterangan.required' => 'Alasan pengurangan point wajib diisi.',
        ]);

        $tipe = $validated['tipe'];
        $kategori = $validated['kategori'];

        if ($tipe === 'tambah') {
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

