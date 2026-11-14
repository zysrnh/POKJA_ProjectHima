<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KuesionerJawaban;
use App\Models\KuesionerPertanyaan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class KuesionerAdminController extends Controller
{
    /**
     * Halaman utama Admin Kuesioner: Kelola Pertanyaan Dinamis & Rekap Jawaban Anggota.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $kelasFilter = trim((string) $request->input('kelas', ''));
        $statusFilter = trim((string) $request->input('status', ''));

        $pertanyaans = KuesionerPertanyaan::withCount('jawabans')
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $totalPeserta = Pendaftaran::count();
        $totalResponden = KuesionerJawaban::distinct('pendaftaran_id')->count('pendaftaran_id');
        $persenResponden = $totalPeserta > 0 ? round(($totalResponden / $totalPeserta) * 100) : 0;

        // Hitung statistik opsi untuk pertanyaan bertipe Pilihan Ganda
        $pilihanStats = [];
        $pilihanPertanyaans = $pertanyaans->where('tipe', 'pilihan');
        if ($pilihanPertanyaans->isNotEmpty()) {
            $groupedAnswers = KuesionerJawaban::whereIn('pertanyaan_id', $pilihanPertanyaans->pluck('id'))
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

        $query = Pendaftaran::with(['kuesionerJawabans'])
            ->withCount('kuesionerJawabans')
            ->withMax('kuesionerJawabans', 'updated_at');

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
            $query->has('kuesionerJawabans');
        } elseif ($statusFilter === 'belum') {
            $query->doesntHave('kuesionerJawabans');
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
            'statusFilter'
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

        return redirect()
            ->route('admin.kuesioner.index')
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

        return redirect()
            ->route('admin.kuesioner.index')
            ->with('success', 'Pertanyaan kuesioner berhasil diperbarui!');
    }

    /**
     * Hapus pertanyaan kuesioner (beserta jawabannya).
     */
    public function destroyPertanyaan(int $id)
    {
        $pertanyaan = KuesionerPertanyaan::findOrFail($id);
        $pertanyaan->delete();

        return redirect()
            ->route('admin.kuesioner.index')
            ->with('success', 'Pertanyaan kuesioner berhasil dihapus.');
    }

    /**
     * Export rekap jawaban kuesioner anggota ke format CSV.
     */
    public function exportCsv(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $kelasFilter = trim((string) $request->input('kelas', ''));
        $statusFilter = trim((string) $request->input('status', ''));

        $pertanyaans = KuesionerPertanyaan::orderBy('urutan')->orderBy('id')->get();

        $query = Pendaftaran::with(['kuesionerJawabans'])
            ->withCount('kuesionerJawabans')
            ->withMax('kuesionerJawabans', 'updated_at');

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
            $query->has('kuesionerJawabans');
        } elseif ($statusFilter === 'belum') {
            $query->doesntHave('kuesionerJawabans');
        }

        $rows = $query
            ->orderByDesc('kuesioner_jawabans_count')
            ->orderBy('nama', 'asc')
            ->get();

        $filename = 'rekap-kuesioner-pokja-' . now()->timezone('Asia/Jakarta')->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rows, $pertanyaans) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM agar Excel langsung membaca karakter dengan benar
            fwrite($handle, "\xEF\xBB\xBF");

            $headers = ['No', 'NIM', 'Nama', 'Kelas', 'Status', 'Terakhir Diisi'];
            foreach ($pertanyaans as $q) {
                $headers[] = $q->pertanyaan;
            }
            fputcsv($handle, $headers);

            foreach ($rows as $idx => $mhs) {
                $ansMap = $mhs->kuesionerJawabans->keyBy('pertanyaan_id');
                $line = [
                    $idx + 1,
                    $mhs->nim,
                    $mhs->nama,
                    $mhs->kelas,
                    $mhs->kuesioner_jawabans_count > 0 ? 'Sudah Mengisi' : 'Belum Mengisi',
                    $mhs->kuesioner_jawabans_max_updated_at
                        ? \Carbon\Carbon::parse($mhs->kuesioner_jawabans_max_updated_at)->timezone('Asia/Jakarta')->format('d/m/Y H:i')
                        : '-',
                ];

                foreach ($pertanyaans as $q) {
                    $ans = $ansMap->get($q->id);
                    $line[] = $ans ? ($ans->jawaban ?? '-') : '-';
                }
                fputcsv($handle, $line);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Validasi & normalisasi payload tambah/edit pertanyaan kuesioner (tanpa rating).
     */
    private function validatePertanyaan(Request $request): array
    {
        $validated = $request->validate([
            'pertanyaan' => ['required', 'string', 'max:1000'],
            'tipe' => ['required', 'in:teks,paragraf,pilihan'],
            'opsi_text' => ['nullable', 'string', 'max:2000'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
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
        ];
    }
}

