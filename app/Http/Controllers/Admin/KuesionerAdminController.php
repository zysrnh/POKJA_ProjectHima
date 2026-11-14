<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KuesionerJawaban;
use App\Models\KuesionerPertanyaan;
use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class KuesionerAdminController extends Controller
{
    /**
     * Halaman utama Admin Kuesioner: Kelola Pertanyaan Dinamis & Rekap Jawaban Anggota per Pertemuan.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $kelasFilter = trim((string) $request->input('kelas', ''));
        $statusFilter = trim((string) $request->input('status', ''));

        $pertemuanAktif = max(1, (int) (Pengaturan::value('pertemuan_aktif') ?? 1));
        $maxPertemuan = max(
            4,
            $pertemuanAktif,
            (int) KuesionerPertanyaan::max('pertemuan'),
            (int) KuesionerJawaban::max('pertemuan')
        );
        $pertemuanList = range(1, $maxPertemuan);

        $pertemuanFilter = max(1, (int) $request->input('pertemuan', $pertemuanAktif));
        if (!in_array($pertemuanFilter, $pertemuanList, true)) {
            $pertemuanList[] = $pertemuanFilter;
            sort($pertemuanList);
        }

        $pertanyaans = KuesionerPertanyaan::forPertemuan($pertemuanFilter)
            ->withCount([
                'jawabans' => fn ($q) => $q->where('pertemuan', $pertemuanFilter),
            ])
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $totalPeserta = Pendaftaran::count();
        $totalResponden = KuesionerJawaban::where('pertemuan', $pertemuanFilter)
            ->distinct('pendaftaran_id')
            ->count('pendaftaran_id');
        $persenResponden = $totalPeserta > 0 ? round(($totalResponden / $totalPeserta) * 100) : 0;

        // Hitung statistik opsi untuk pertanyaan bertipe Pilihan Ganda pada pertemuan terpilih
        $pilihanStats = [];
        $pilihanPertanyaans = $pertanyaans->where('tipe', 'pilihan');
        if ($pilihanPertanyaans->isNotEmpty()) {
            $groupedAnswers = KuesionerJawaban::where('pertemuan', $pertemuanFilter)
                ->whereIn('pertanyaan_id', $pilihanPertanyaans->pluck('id'))
                ->get()
                ->groupBy('pertanyaan_id');

            foreach ($pilihanPertanyaans as $q) {
                $answersForQ = $groupedAnswers->get($q->id, collect());
                $totalForQ = $answersForQ->count();
                $counts = [];
                foreach ((array) ($q->opsi ?? []) as $opt) {
                    $cnt = $answersForQ->where('jawaban', $opt)->count();
                    $counts[] = [
                        'label' => $opt,
                        'count' => $cnt,
                        'pct' => $totalForQ > 0 ? round(($cnt / $totalForQ) * 100) : 0,
                    ];
                }
                $pilihanStats[$q->id] = [
                    'total' => $totalForQ,
                    'options' => $counts,
                ];
            }
        }

        $query = Pendaftaran::with([
                'kuesionerJawabans' => fn ($q) => $q->where('pertemuan', $pertemuanFilter),
            ])
            ->withCount([
                'kuesionerJawabans' => fn ($q) => $q->where('pertemuan', $pertemuanFilter),
            ])
            ->withMax(
                ['kuesionerJawabans' => fn ($q) => $q->where('pertemuan', $pertemuanFilter)],
                'updated_at'
            );

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

        if ($statusFilter === 'sudah') {
            $query->whereHas('kuesionerJawabans', fn ($q) => $q->where('pertemuan', $pertemuanFilter));
        } elseif ($statusFilter === 'belum') {
            $query->whereDoesntHave('kuesionerJawabans', fn ($q) => $q->where('pertemuan', $pertemuanFilter));
        }

        $respondenList = $query
            ->orderByDesc('kuesioner_jawabans_count')
            ->orderBy('nama', 'asc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.kuesioner.index', compact(
            'pertanyaans',
            'kelasList',
            'totalPeserta',
            'totalResponden',
            'persenResponden',
            'pilihanStats',
            'respondenList',
            'search',
            'kelasFilter',
            'statusFilter',
            'pertemuanFilter',
            'pertemuanList',
            'pertemuanAktif'
        ));
    }

    /**
     * Tambah pertanyaan kuesioner dinamis baru.
     */
    public function storePertanyaan(Request $request)
    {
        $data = $this->validatePertanyaan($request);

        if (!isset($data['urutan']) || $data['urutan'] === null) {
            $data['urutan'] = ((int) KuesionerPertanyaan::max('urutan')) + 1;
        }

        KuesionerPertanyaan::create($data);

        $redirectPertemuan = $data['pertemuan'] ?? max(1, (int) $request->input('current_pertemuan', 1));

        return redirect()
            ->route('admin.kuesioner.index', ['pertemuan' => $redirectPertemuan])
            ->with('success', 'Pertanyaan kuesioner berhasil ditambahkan!');
    }

    /**
     * Perbarui pertanyaan kuesioner yang sudah ada.
     */
    public function updatePertanyaan(Request $request, int $id)
    {
        $pertanyaan = KuesionerPertanyaan::findOrFail($id);
        $data = $this->validatePertanyaan($request);

        if ($data['urutan'] === null) {
            $data['urutan'] = $pertanyaan->urutan;
        }

        $pertanyaan->update($data);

        $redirectPertemuan = $data['pertemuan'] ?? max(1, (int) $request->input('current_pertemuan', 1));

        return redirect()
            ->route('admin.kuesioner.index', ['pertemuan' => $redirectPertemuan])
            ->with('success', 'Pertanyaan kuesioner berhasil diperbarui!');
    }

    /**
     * Hapus pertanyaan kuesioner (beserta jawabannya).
     */
    public function destroyPertanyaan(Request $request, int $id)
    {
        $pertanyaan = KuesionerPertanyaan::findOrFail($id);
        $pertanyaan->delete();

        $redirectPertemuan = max(1, (int) $request->input('current_pertemuan', 1));

        return redirect()
            ->route('admin.kuesioner.index', ['pertemuan' => $redirectPertemuan])
            ->with('success', 'Pertanyaan kuesioner berhasil dihapus.');
    }

    /**
     * Export rekap jawaban kuesioner anggota per pertemuan ke format CSV.
     */
    public function exportCsv(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $kelasFilter = trim((string) $request->input('kelas', ''));
        $statusFilter = trim((string) $request->input('status', ''));
        $pertemuanAktif = max(1, (int) (Pengaturan::value('pertemuan_aktif') ?? 1));
        $pertemuanFilter = max(1, (int) $request->input('pertemuan', $pertemuanAktif));

        $pertanyaans = KuesionerPertanyaan::forPertemuan($pertemuanFilter)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $query = Pendaftaran::with([
                'kuesionerJawabans' => fn ($q) => $q->where('pertemuan', $pertemuanFilter),
            ])
            ->withCount([
                'kuesionerJawabans' => fn ($q) => $q->where('pertemuan', $pertemuanFilter),
            ])
            ->withMax(
                ['kuesionerJawabans' => fn ($q) => $q->where('pertemuan', $pertemuanFilter)],
                'updated_at'
            );

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
        if ($statusFilter === 'sudah') {
            $query->whereHas('kuesionerJawabans', fn ($q) => $q->where('pertemuan', $pertemuanFilter));
        } elseif ($statusFilter === 'belum') {
            $query->whereDoesntHave('kuesionerJawabans', fn ($q) => $q->where('pertemuan', $pertemuanFilter));
        }

        $rows = $query
            ->orderByDesc('kuesioner_jawabans_count')
            ->orderBy('nama', 'asc')
            ->get();

        $filename = 'rekap-kuesioner-pertemuan-' . $pertemuanFilter . '-' . now()->timezone('Asia/Jakarta')->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rows, $pertanyaans, $pertemuanFilter) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM agar Excel langsung membaca karakter UTF-8 dengan benar
            fwrite($out, "\xEF\xBB\xBF");

            $headers = ['Pertemuan', 'NIM', 'Nama', 'Kelas', 'Status Pengisian', 'Terakhir Diperbarui'];
            foreach ($pertanyaans as $q) {
                $headers[] = $q->pertanyaan;
            }
            fputcsv($out, $headers);

            foreach ($rows as $row) {
                $jawabanByQ = $row->kuesionerJawabans->keyBy('pertanyaan_id');
                $sudahIsi = $row->kuesioner_jawabans_count > 0;
                $updated = $row->kuesioner_jawabans_max_updated_at
                    ? \Carbon\Carbon::parse($row->kuesioner_jawabans_max_updated_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s')
                    : '-';

                $line = [
                    'Pertemuan ' . $pertemuanFilter,
                    $row->nim,
                    $row->nama,
                    $row->kelas,
                    $sudahIsi ? 'Sudah Mengisi' : 'Belum Mengisi',
                    $updated,
                ];

                foreach ($pertanyaans as $q) {
                    $ans = $jawabanByQ->get($q->id)?->jawaban ?? '';
                    // Lindungi dari CSV formula injection
                    if ($ans !== '' && preg_match('/^[=+\-@]/', $ans)) {
                        $ans = "'" . $ans;
                    }
                    $line[] = $ans;
                }

                fputcsv($out, $line);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Validasi input pertanyaan kuesioner (tanpa tipe rating).
     */
    private function validatePertanyaan(Request $request): array
    {
        $validated = $request->validate([
            'pertanyaan' => ['required', 'string', 'max:1000'],
            'tipe' => ['required', 'in:teks,paragraf,pilihan'],
            'opsi_text' => ['nullable', 'string', 'max:2000'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'pertemuan' => ['nullable', 'integer', 'min:1', 'max:50'],
            'wajib' => ['nullable', 'boolean'],
            'aktif' => ['nullable', 'boolean'],
        ], [
            'pertanyaan.required' => 'Teks pertanyaan wajib diisi.',
            'tipe.in' => 'Tipe pertanyaan hanya boleh Teks, Paragraf, atau Pilihan Ganda.',
        ]);

        $opsiList = null;
        if ($validated['tipe'] === 'pilihan') {
            $lines = preg_split('/\r\n|\r|\n/', (string) ($validated['opsi_text'] ?? '')) ?: [];
            $opsiList = collect($lines)
                ->map(fn ($item) => trim(strip_tags((string) $item)))
                ->filter(fn ($item) => $item !== '')
                ->unique()
                ->values()
                ->all();

            if (count($opsiList) < 2) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'opsi_text' => 'Untuk tipe Pilihan Ganda, masukkan minimal 2 opsi jawaban (pisahkan per baris).',
                ]);
            }
        }

        return [
            'pertanyaan' => trim(strip_tags($validated['pertanyaan'])),
            'tipe' => $validated['tipe'],
            'opsi' => $opsiList,
            'wajib' => $request->boolean('wajib'),
            'urutan' => isset($validated['urutan']) ? (int) $validated['urutan'] : null,
            'aktif' => $request->boolean('aktif'),
            'pertemuan' => isset($validated['pertemuan']) && $validated['pertemuan'] !== '' ? (int) $validated['pertemuan'] : null,
        ];
    }
}
