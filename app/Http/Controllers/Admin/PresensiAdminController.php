<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Presensi;
use App\Models\Pengaturan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PresensiAdminController extends Controller
{
    /**
     * Dashboard Presensi Admin: Rekap Matriks 7 Pertemuan & Kontrol Sesi.
     */
    public function index(Request $request)
    {
        $pengaturan = Pengaturan::firstOrCreate([], [
            'nama_acara' => 'POKJA HIMA IF',
            'pertemuan_aktif' => 1,
            'is_scan_aktif' => true,
        ]);

        if (empty($pengaturan->token_salt)) {
            $pengaturan->token_salt = bin2hex(random_bytes(16));
            $pengaturan->save();
        }

        $pertemuanDipilih = (int) $request->input('pertemuan', $pengaturan->pertemuan_aktif ?? 1);
        if ($pertemuanDipilih < 1 || $pertemuanDipilih > 7) {
            $pertemuanDipilih = 1;
        }

        $search = $request->input('search');
        $kelasFilter = $request->input('kelas');

        $query = Pendaftaran::with('presensis')->orderBy('nama', 'asc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($kelasFilter)) {
            $query->where('kelas', $kelasFilter);
        }

        $pendaftarans = $query->paginate(25)->withQueryString();
        $kelasList = Kelas::where('is_active', true)->orderBy('nama_kelas', 'asc')->get();

        // Statistik Pertemuan Dipilih
        $totalMahasiswa = Pendaftaran::count();
        $totalHadirPertemuan = Presensi::where('pertemuan', $pertemuanDipilih)->where('status', 'hadir')->count();
        $totalBelumHadirPertemuan = max(0, $totalMahasiswa - $totalHadirPertemuan);

        return view('admin.presensi.index', compact(
            'pengaturan',
            'pendaftarans',
            'kelasList',
            'pertemuanDipilih',
            'totalMahasiswa',
            'totalHadirPertemuan',
            'totalBelumHadirPertemuan',
            'search',
            'kelasFilter'
        ));
    }

    /**
     * Tampilan Layar Proyektor Kelas Full-Screen (QR Refresh 5s + Drop Notif).
     */
    public function layarProyektor()
    {
        $pengaturan = Pengaturan::firstOrCreate([], [
            'nama_acara' => 'POKJA HIMA IF',
            'pertemuan_aktif' => 1,
            'is_scan_aktif' => true,
        ]);

        if (empty($pengaturan->token_salt)) {
            $pengaturan->token_salt = bin2hex(random_bytes(16));
            $pengaturan->save();
        }

        $totalMahasiswa = Pendaftaran::count();
        $totalHadir = Presensi::where('pertemuan', $pengaturan->pertemuan_aktif)->where('status', 'hadir')->count();

        return view('admin.presensi.layar', compact('pengaturan', 'totalMahasiswa', 'totalHadir'));
    }

    /**
     * Endpoint API AJAX: Generate Token QR Proyektor (Refresh Tiap 5 Detik).
     */
    public function getProyektorToken()
    {
        $pengaturan = Pengaturan::first();
        if (!$pengaturan) {
            return response()->json(['error' => 'Pengaturan belum ada'], 404);
        }

        $pertemuan = (int) ($pengaturan->pertemuan_aktif ?? 1);
        $salt = $pengaturan->token_salt ?? 'pokja_default_salt';
        $window = (int) floor(time() / 5);

        // Token hash 16 karakter
        $token = substr(hash_hmac('sha256', "POKJA_P{$pertemuan}_{$window}", $salt), 0, 16);

        // Payload format: POKJA:P[X]:[TOKEN]:[WINDOW]
        $payload = "POKJA:P{$pertemuan}:{$token}:{$window}";

        $totalHadir = Presensi::where('pertemuan', $pertemuan)->where('status', 'hadir')->count();

        return response()->json([
            'pertemuan' => $pertemuan,
            'is_scan_aktif' => (bool) $pengaturan->is_scan_aktif,
            'payload' => $payload,
            'window' => $window,
            'total_hadir' => $totalHadir,
        ]);
    }

    /**
     * Endpoint API AJAX: Ambil data mahasiswa yang baru saja scan (untuk pop-up jatuh di layar proyektor).
     */
    public function getRecentScans(Request $request)
    {
        $pengaturan = Pengaturan::first();
        $pertemuan = (int) ($pengaturan->pertemuan_aktif ?? 1);
        $since = $request->input('since'); // Timestamp ISO atau ID

        $query = Presensi::with('pendaftaran')
            ->where('pertemuan', $pertemuan)
            ->where('status', 'hadir')
            ->latest('waktu_hadir');

        if (!empty($since)) {
            $query->where('waktu_hadir', '>', $since);
        } else {
            $query->limit(5);
        }

        $scans = $query->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'nama' => $p->pendaftaran->nama ?? 'Mahasiswa',
                'nim' => $p->pendaftaran->nim ?? '-',
                'kelas' => $p->pendaftaran->kelas ?? '-',
                'waktu' => $p->waktu_hadir ? $p->waktu_hadir->timezone('Asia/Jakarta')->format('H:i:s') : '-',
                'iso' => $p->waktu_hadir ? $p->waktu_hadir->toIso8601String() : now()->toIso8601String(),
            ];
        });

        $totalHadir = Presensi::where('pertemuan', $pertemuan)->where('status', 'hadir')->count();

        return response()->json([
            'scans' => $scans,
            'total_hadir' => $totalHadir,
            'current_time' => now()->toIso8601String(),
        ]);
    }

    /**
     * Update Kontrol Sesi Scan & Pertemuan Aktif.
     */
    public function updateSesi(Request $request)
    {
        $request->validate([
            'pertemuan_aktif' => 'required|integer|min:1|max:7',
            'is_scan_aktif' => 'required|boolean',
        ]);

        $pengaturan = Pengaturan::firstOrCreate([]);
        $pengaturan->pertemuan_aktif = $request->input('pertemuan_aktif');
        $pengaturan->is_scan_aktif = $request->boolean('is_scan_aktif');
        $pengaturan->save();

        $statusText = $pengaturan->is_scan_aktif ? 'DIBUKA' : 'DITUTUP';
        return redirect()->back()->with('success', "Sesi Pertemuan {$pengaturan->pertemuan_aktif} berhasil {$statusText}.");
    }

    /**
     * Toggle Kehadiran Mahasiswa Manual oleh Admin per Pertemuan.
     */
    public function toggleManual(Request $request, $pendaftaranId)
    {
        $pertemuan = (int) $request->input('pertemuan', 1);
        if ($pertemuan < 1 || $pertemuan > 7) {
            $pertemuan = 1;
        }

        $pendaftaran = Pendaftaran::findOrFail($pendaftaranId);

        $presensi = Presensi::where('pendaftaran_id', $pendaftaran->id)
            ->where('pertemuan', $pertemuan)
            ->first();

        if ($presensi && $presensi->status === 'hadir') {
            // Ubah jadi belum hadir (hapus record presensi pertemuan ini)
            $presensi->delete();
            $statusSekarang = 'BELUM HADIR';
        } else {
            // Tandai hadir
            Presensi::updateOrCreate(
                ['pendaftaran_id' => $pendaftaran->id, 'pertemuan' => $pertemuan],
                ['status' => 'hadir', 'waktu_hadir' => now()]
            );
            $statusSekarang = 'HADIR';
        }

        // Sinkronisasi otomatis ke Google Sheets di background
        self::syncAllWithKehadiranToSheets();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $statusSekarang,
                'message' => "Presensi Pertemuan {$pertemuan} untuk {$pendaftaran->nama} berhasil diubah jadi {$statusSekarang}."
            ]);
        }

        return redirect()->back()->with('success', "Presensi Pertemuan {$pertemuan} untuk {$pendaftaran->nama} berhasil diubah jadi {$statusSekarang}.");
    }

    /**
     * Trigger Manual Sinkronisasi Google Sheets (Sheet 1: Peserta, Sheet 2: Kehadiran).
     */
    public function syncSpreadsheet()
    {
        $success = self::syncAllWithKehadiranToSheets();

        if ($success) {
            return redirect()->back()->with('success', 'Data pendaftar & kehadiran 7 pertemuan berhasil disinkronkan ke Google Sheets (Sheet Peserta & Sheet Kehadiran).');
        }

        return redirect()->back()->with('error', 'Gagal menyinkronkan data ke Google Sheets. Pastikan URL Webhook di .env sudah benar.');
    }

    /**
     * Helper Sinkronisasi 2 Tab Google Sheets: Sheet 1 (Peserta) & Sheet 2 (Kehadiran P1-P7).
     */
    public static function syncAllWithKehadiranToSheets()
    {
        $webhookUrl = env('GOOGLE_SHEET_WEBHOOK_URL');
        if (empty($webhookUrl)) {
            return false;
        }

        try {
            $pendaftarans = Pendaftaran::with('presensis')->orderBy('created_at', 'asc')->get();

            $dataPeserta = [];
            $dataKehadiran = [];

            foreach ($pendaftarans as $index => $p) {
                // 1. Data Sheet 1 (Peserta)
                $dataPeserta[] = [
                    'no' => $index + 1,
                    'nama' => $p->nama,
                    'nim' => $p->nim,
                    'email' => $p->email ?? '-',
                    'kelas' => $p->kelas,
                    'no_telp' => $p->no_telp,
                    'created_at' => $p->created_at ? $p->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') : '-',
                ];

                // 2. Data Sheet 2 (Kehadiran 7 Pertemuan)
                $presensiMap = $p->presensis->keyBy('pertemuan');
                $totalHadir = 0;
                $rowKehadiran = [
                    'no' => $index + 1,
                    'nim' => $p->nim,
                    'nama' => $p->nama,
                    'kelas' => $p->kelas,
                ];

                for ($i = 1; $i <= 7; $i++) {
                    $item = $presensiMap->get($i);
                    if ($item && $item->status === 'hadir') {
                        $rowKehadiran["p{$i}"] = 'HADIR';
                        $totalHadir++;
                    } else {
                        $rowKehadiran["p{$i}"] = '-';
                    }
                }

                $persentase = round(($totalHadir / 7) * 100) . '%';
                $rowKehadiran['total_hadir'] = $totalHadir;
                $rowKehadiran['persentase'] = $persentase;

                $dataKehadiran[] = $rowKehadiran;
            }

            $response = Http::timeout(10)->post($webhookUrl, [
                'action' => 'sync_all_with_kehadiran',
                'pendaftar' => $dataPeserta,
                'kehadiran' => $dataKehadiran,
                // Fallback kompatibilitas untuk Apps Script versi lama
                'data' => $dataPeserta,
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('Sinkronisasi Google Sheet Gagal: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Export Excel (.xls) Rekap Kehadiran 7 Pertemuan.
     */
    public function exportExcel(Request $request)
    {
        $pendaftarans = Pendaftaran::with('presensis')->orderBy('nama', 'asc')->get();

        $filename = 'Rekap_Kehadiran_POKJA_HIMA_IF_' . date('Ymd_His') . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style>';
        echo 'table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }';
        echo 'th { background-color: #1A467C; color: #FFFFFF; font-weight: bold; border: 1px solid #000000; text-align: center; padding: 8px 12px; }';
        echo 'td { border: 1px solid #CCCCCC; padding: 6px 10px; font-size: 11pt; }';
        echo '.text-center { text-align: center; }';
        echo '.bg-hadir { background-color: #DCFCE7; color: #166534; font-weight: bold; text-align: center; }';
        echo '.bg-belum { background-color: #FEE2E2; color: #991B1B; text-align: center; }';
        echo '</style></head><body>';

        echo '<h2 style="font-family: Arial; color: #1A467C; margin-bottom: 4px;">REKAP KEHADIRAN 7 PERTEMUAN - POKJA HIMA IF</h2>';
        echo '<p style="font-family: Arial; font-size: 10pt; color: #555555; margin-top: 0;">Diekspor pada: ' . date('d/m/Y H:i:s') . ' WIB</p>';

        echo '<table><thead><tr>';
        echo '<th>No</th><th>NIM</th><th>Nama Lengkap</th><th>Kelas</th><th>No. WhatsApp</th>';
        for ($i = 1; $i <= 7; $i++) {
            echo "<th>P{$i}</th>";
        }
        echo '<th>Total Hadir</th><th>Persentase</th>';
        echo '</tr></thead><tbody>';

        foreach ($pendaftarans as $index => $p) {
            $presensiMap = $p->presensis->keyBy('pertemuan');
            $totalHadir = 0;

            echo '<tr>';
            echo '<td class="text-center">' . ($index + 1) . '</td>';
            echo '<td style="mso-number-format:\'@\'; font-weight:bold;">' . htmlspecialchars($p->nim) . '</td>';
            echo '<td>' . htmlspecialchars($p->nama) . '</td>';
            echo '<td class="text-center">' . htmlspecialchars($p->kelas) . '</td>';
            echo '<td style="mso-number-format:\'@\';">' . htmlspecialchars($p->no_telp) . '</td>';

            for ($i = 1; $i <= 7; $i++) {
                $item = $presensiMap->get($i);
                if ($item && $item->status === 'hadir') {
                    echo '<td class="bg-hadir">HADIR</td>';
                    $totalHadir++;
                } else {
                    echo '<td class="bg-belum">-</td>';
                }
            }

            $persen = round(($totalHadir / 7) * 100) . '%';
            echo '<td class="text-center" style="font-weight:bold;">' . $totalHadir . ' / 7</td>';
            echo '<td class="text-center" style="font-weight:bold;">' . $persen . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table></body></html>';
        exit;
    }
}
