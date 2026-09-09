<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PendaftaranAdminController extends Controller
{
    /**
     * Menampilkan dashboard dan tabel data pendaftar.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kelasFilter = $request->input('kelas');
        $statusFilter = $request->input('status_kehadiran');

        $query = Pendaftaran::query()->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }

        if (!empty($kelasFilter)) {
            $query->where('kelas', $kelasFilter);
        }

        if (!empty($statusFilter)) {
            $query->where('status_kehadiran', $statusFilter);
        }

        $pendaftarans = $query->paginate(15)->withQueryString();

        // Statistik
        $totalPendaftar = Pendaftaran::count();
        $totalHadir = Pendaftaran::where('status_kehadiran', 'hadir')->count();
        $totalBelumHadir = Pendaftaran::where('status_kehadiran', '!=', 'hadir')->count();
        $pendaftarHariIni = Pendaftaran::whereDate('created_at', Carbon::today())->count();
        $totalKelas = Kelas::where('is_active', true)->count();
        
        // Pilihan kelas untuk filter
        $kelasList = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');

        return view('admin.dashboard', compact(
            'pendaftarans',
            'totalPendaftar',
            'totalHadir',
            'totalBelumHadir',
            'pendaftarHariIni',
            'totalKelas',
            'kelasList',
            'search',
            'kelasFilter',
            'statusFilter'
        ));
    }

    /**
     * Quick toggle status kehadiran (AJAX / Normal Form Request).
     */
    public function toggleKehadiran(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        if ($request->has('status')) {
            $targetStatus = in_array($request->input('status'), ['hadir', 'tidak_hadir', 'belum_hadir']) 
                ? $request->input('status') 
                : 'belum_hadir';
            $pendaftaran->status_kehadiran = $targetStatus;
        } else {
            // Default toggle logic: hadir <-> belum_hadir
            $pendaftaran->status_kehadiran = ($pendaftaran->status_kehadiran === 'hadir') ? 'belum_hadir' : 'hadir';
        }

        $pendaftaran->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status_kehadiran' => $pendaftaran->status_kehadiran,
                'is_hadir' => ($pendaftaran->status_kehadiran === 'hadir'),
                'label' => ($pendaftaran->status_kehadiran === 'hadir') ? 'Hadir' : (($pendaftaran->status_kehadiran === 'tidak_hadir') ? 'Tidak Hadir' : 'Belum Hadir'),
                'message' => "Status kehadiran {$pendaftaran->nama} diperbarui menjadi " . ($pendaftaran->status_kehadiran === 'hadir' ? 'Hadir' : 'Belum Hadir') . ".",
            ]);
        }

        return redirect()->back()->with('success', "Status kehadiran {$pendaftaran->nama} berhasil diubah.");
    }

    /**
     * Menampilkan halaman edit data pendaftar.
     */
    public function edit($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('admin.edit', compact('pendaftaran', 'kelasList'));
    }

    /**
     * Memperbarui data pendaftar.
     */
    public function update(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        // Sanitasi
        $request->merge([
            'nama' => trim(strip_tags($request->input('nama', ''))),
            'nim' => trim(strip_tags($request->input('nim', ''))),
            'kelas' => trim(strip_tags($request->input('kelas', ''))),
            'no_telp' => trim(strip_tags($request->input('no_telp', ''))),
            'status_kehadiran' => trim(strip_tags($request->input('status_kehadiran', 'belum_hadir'))),
        ]);

        // Normalisasi telepon
        $rawPhone = preg_replace('/[^0-9]/', '', $request->input('no_telp'));
        if (str_starts_with($rawPhone, '628')) {
            $rawPhone = '08' . substr($rawPhone, 3);
        } elseif (str_starts_with($rawPhone, '8')) {
            $rawPhone = '08' . substr($rawPhone, 1);
        }
        $request->merge(['no_telp' => $rawPhone]);

        $validated = $request->validate([
            'nama' => [
                'required', 
                'string', 
                'min:3', 
                'max:100', 
                'regex:/^[a-zA-Z\s\.\',\-]+$/'
            ],
            'nim' => [
                'required', 
                'numeric', 
                'digits_between:8,20', 
                'unique:pendaftarans,nim,' . $pendaftaran->id
            ],
            'kelas' => [
                'required', 
                'string', 
                'exists:kelas,nama_kelas'
            ],
            'no_telp' => [
                'required', 
                'string', 
                'regex:/^08[1-9][0-9]{7,11}$/'
            ],
            'status_kehadiran' => [
                'required',
                'string',
                'in:hadir,tidak_hadir,belum_hadir',
            ],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.min' => 'Nama lengkap minimal 3 karakter.',
            'nama.regex' => 'Nama lengkap hanya boleh berisi huruf, spasi, titik, koma, tanda petik, dan tanda hubung.',
            
            'nim.required' => 'NIM wajib diisi.',
            'nim.numeric' => 'NIM hanya boleh berisi angka.',
            'nim.digits_between' => 'NIM harus berupa angka dengan panjang 8 hingga 20 digit.',
            'nim.unique' => 'NIM ini sudah terdaftar pada data lain.',
            
            'kelas.required' => 'Silakan pilih kelas.',
            'kelas.exists' => 'Kelas yang dipilih tidak valid atau belum terdaftar.',
            
            'no_telp.required' => 'No. Telepon / WhatsApp wajib diisi.',
            'no_telp.regex' => 'Format nomor WhatsApp tidak valid. Gunakan nomor Indonesia yang diawali 08 (panjang 10–14 digit).',

            'status_kehadiran.required' => 'Status kehadiran wajib dipilih.',
            'status_kehadiran.in' => 'Status kehadiran tidak valid.',
        ]);

        $pendaftaran->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Data pendaftar berhasil diperbarui.');
    }

    /**
     * Menghapus data pendaftar.
     */
    public function destroy($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $nama = $pendaftaran->nama;
        $pendaftaran->delete();

        return redirect()->route('admin.dashboard')->with('success', "Data pendaftar atas nama '{$nama}' berhasil dihapus.");
    }

    /**
     * Export data pendaftar ke file Excel (.xls) berformat styling rapi dan profesional.
     */
    public function exportCsv(Request $request)
    {
        $search = $request->input('search');
        $kelasFilter = $request->input('kelas');
        $statusFilter = $request->input('status_kehadiran');

        $query = Pendaftaran::query()->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }

        if (!empty($kelasFilter)) {
            $query->where('kelas', $kelasFilter);
        }

        if (!empty($statusFilter)) {
            $query->where('status_kehadiran', $statusFilter);
        }

        $data = $query->get();
        $totalHadir = $data->where('status_kehadiran', 'hadir')->count();
        $totalBelum = $data->where('status_kehadiran', '!=', 'hadir')->count();
        $filename = 'data-pendaftar-pokja-' . date('Y-m-d_His') . '.xls';

        return response()->stream(function () use ($data, $totalHadir, $totalBelum) {
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
            echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Data Pendaftar</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
            echo '<style>';
            echo 'table { border-collapse: collapse; width: 100%; font-family: "Segoe UI", Arial, sans-serif; font-size: 11pt; }';
            echo '.title { font-size: 16pt; font-weight: bold; color: #1A467C; text-align: left; height: 35px; }';
            echo '.subtitle { font-size: 10pt; color: #555555; text-align: left; height: 22px; }';
            echo '.th-header { background-color: #1A467C; color: #FFFFFF; font-weight: bold; text-align: center; border: 1.5pt solid #0F2A4A; padding: 10px; height: 30px; font-size: 11pt; }';
            echo '.td-data { border: 0.5pt solid #B0C4DE; padding: 6px 10px; vertical-align: middle; height: 26px; }';
            echo '.td-center { text-align: center; }';
            echo '.td-bold { font-weight: bold; }';
            echo '.text-format { mso-number-format:"\@"; }';
            echo '.row-even { background-color: #F8FBFE; }';
            echo '.row-odd { background-color: #FFFFFF; }';
            echo '.badge-hadir { background-color: #DCFCE7; color: #166534; font-weight: bold; text-align: center; border: 0.5pt solid #86EFAC; }';
            echo '.badge-belum { background-color: #F3F4F6; color: #4B5563; font-weight: bold; text-align: center; border: 0.5pt solid #D1D5DB; }';
            echo '.badge-tidak { background-color: #FEE2E2; color: #991B1B; font-weight: bold; text-align: center; border: 0.5pt solid #FCA5A5; }';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            
            echo '<table>';
            // Header Judul Laporan
            echo '<tr><td colspan="7" class="title">DATA PENDAFTARAN POKJA HIMA IF 2026</td></tr>';
            echo '<tr><td colspan="7" class="subtitle">Dicetak pada: ' . date('d F Y, H:i') . ' WIB | Total Data: ' . $data->count() . ' (Hadir: ' . $totalHadir . ' | Belum Hadir: ' . $totalBelum . ')</td></tr>';
            echo '<tr><td colspan="7" style="height: 12px;"></td></tr>';

            // Baris Header Kolom
            echo '<tr>';
            echo '<th class="th-header" style="width: 50px;">NO</th>';
            echo '<th class="th-header" style="width: 250px;">NAMA LENGKAP</th>';
            echo '<th class="th-header" style="width: 140px;">NIM</th>';
            echo '<th class="th-header" style="width: 100px;">KELAS</th>';
            echo '<th class="th-header" style="width: 170px;">NO. WHATSAPP</th>';
            echo '<th class="th-header" style="width: 140px;">STATUS KEHADIRAN</th>';
            echo '<th class="th-header" style="width: 180px;">WAKTU PENDAFTARAN</th>';
            echo '</tr>';

            // Loop Baris Data
            $no = 1;
            foreach ($data as $item) {
                $rowClass = ($no % 2 == 0) ? 'row-even' : 'row-odd';
                $statusText = match ($item->status_kehadiran) {
                    'hadir' => 'HADIR',
                    'tidak_hadir' => 'TIDAK HADIR',
                    default => 'BELUM HADIR',
                };
                $badgeClass = match ($item->status_kehadiran) {
                    'hadir' => 'badge-hadir',
                    'tidak_hadir' => 'badge-tidak',
                    default => 'badge-belum',
                };

                echo '<tr class="' . $rowClass . '">';
                echo '<td class="td-data td-center" style="font-weight: bold;">' . $no++ . '</td>';
                echo '<td class="td-data td-bold" style="color: #111827;">' . htmlspecialchars($item->nama) . '</td>';
                echo '<td class="td-data td-center td-bold text-format" style="color: #1A467C;">' . htmlspecialchars($item->nim) . '</td>';
                echo '<td class="td-data td-center td-bold text-format" style="background-color: #EEF6FC;">' . htmlspecialchars($item->kelas) . '</td>';
                echo '<td class="td-data td-center text-format">' . htmlspecialchars($item->no_telp) . '</td>';
                echo '<td class="td-data ' . $badgeClass . '">' . $statusText . '</td>';
                echo '<td class="td-data td-center" style="color: #4B5563;">' . ($item->created_at ? $item->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB' : '-') . '</td>';
                echo '</tr>';
            }

            echo '</table>';
            echo '</body>';
            echo '</html>';
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
