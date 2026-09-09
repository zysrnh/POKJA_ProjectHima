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
            echo '</head>';
            echo '<body style="font-family: Arial, sans-serif; font-size: 11pt;">';
            
            echo '<table border="1" style="border-collapse: collapse; border: 1.5pt solid #1A467C; font-family: Arial, sans-serif; width: 100%;">';
            
            // Set Kolom Lebar
            echo '<colgroup>';
            echo '<col width="50" style="width: 50pt;">';
            echo '<col width="250" style="width: 200pt;">';
            echo '<col width="150" style="width: 120pt;">';
            echo '<col width="100" style="width: 80pt;">';
            echo '<col width="170" style="width: 130pt;">';
            echo '<col width="160" style="width: 120pt;">';
            echo '<col width="200" style="width: 150pt;">';
            echo '</colgroup>';

            // Header Judul Laporan
            echo '<tr><td colspan="7" style="font-size: 16pt; font-weight: bold; color: #1A467C; border: none; height: 35px; vertical-align: middle;">DATA PENDAFTARAN POKJA HIMA IF 2026</td></tr>';
            echo '<tr><td colspan="7" style="font-size: 10pt; color: #555555; border: none; height: 22px; vertical-align: middle;">Dicetak pada: ' . date('d F Y, H:i') . ' WIB | Total Data: ' . $data->count() . ' (Hadir: ' . $totalHadir . ' | Belum Hadir: ' . $totalBelum . ')</td></tr>';
            echo '<tr><td colspan="7" style="border: none; height: 10px;"></td></tr>';

            // Baris Header Kolom
            echo '<tr height="36" style="height: 28pt;">';
            echo '<th style="background-color: #1A467C; color: #FFFFFF; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1.5pt solid #0F2A4A; padding: 8px 5px;">NO</th>';
            echo '<th style="background-color: #1A467C; color: #FFFFFF; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1.5pt solid #0F2A4A; padding: 8px 10px;">NAMA LENGKAP</th>';
            echo '<th style="background-color: #1A467C; color: #FFFFFF; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1.5pt solid #0F2A4A; padding: 8px 10px;">NIM</th>';
            echo '<th style="background-color: #1A467C; color: #FFFFFF; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1.5pt solid #0F2A4A; padding: 8px 10px;">KELAS</th>';
            echo '<th style="background-color: #1A467C; color: #FFFFFF; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1.5pt solid #0F2A4A; padding: 8px 10px;">NO. WHATSAPP</th>';
            echo '<th style="background-color: #1A467C; color: #FFFFFF; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1.5pt solid #0F2A4A; padding: 8px 10px; white-space: nowrap;">STATUS KEHADIRAN</th>';
            echo '<th style="background-color: #1A467C; color: #FFFFFF; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1.5pt solid #0F2A4A; padding: 8px 10px; white-space: nowrap;">WAKTU PENDAFTARAN</th>';
            echo '</tr>';

            // Loop Baris Data
            $no = 1;
            foreach ($data as $item) {
                $bgRow = ($no % 2 == 0) ? '#F8FBFE' : '#FFFFFF';
                $statusText = match ($item->status_kehadiran) {
                    'hadir' => 'HADIR',
                    'tidak_hadir' => 'TIDAK HADIR',
                    default => 'BELUM HADIR',
                };
                $statusStyle = match ($item->status_kehadiran) {
                    'hadir' => 'background-color: #DCFCE7; color: #166534; font-weight: bold;',
                    'tidak_hadir' => 'background-color: #FEE2E2; color: #991B1B; font-weight: bold;',
                    default => 'background-color: #F3F4F6; color: #4B5563; font-weight: bold;',
                };

                // Pastikan format string nomor whatsapp diawali 0
                $phone = $item->no_telp;
                if (!str_starts_with($phone, '0') && !str_starts_with($phone, '+') && !str_starts_with($phone, '62')) {
                    $phone = '0' . $phone;
                }

                echo '<tr height="28" style="height: 22pt; background-color: ' . $bgRow . ';">';
                echo '<td align="center" style="border: 0.5pt solid #B0C4DE; padding: 6px 8px; text-align: center; font-weight: bold; vertical-align: middle;">' . $no++ . '</td>';
                echo '<td style="border: 0.5pt solid #B0C4DE; padding: 6px 10px; font-weight: bold; color: #111827; vertical-align: middle;">' . htmlspecialchars($item->nama) . '</td>';
                echo '<td align="center" style="border: 0.5pt solid #B0C4DE; padding: 6px 10px; text-align: center; font-weight: bold; color: #1A467C; mso-number-format:\'\@\'; vertical-align: middle;">' . htmlspecialchars($item->nim) . '</td>';
                echo '<td align="center" style="border: 0.5pt solid #B0C4DE; padding: 6px 10px; text-align: center; font-weight: bold; background-color: #EEF6FC; mso-number-format:\'\@\'; vertical-align: middle;">' . htmlspecialchars($item->kelas) . '</td>';
                echo '<td align="center" style="border: 0.5pt solid #B0C4DE; padding: 6px 10px; text-align: center; mso-number-format:\'\@\'; vertical-align: middle;">' . htmlspecialchars($phone) . '</td>';
                echo '<td align="center" style="border: 0.5pt solid #B0C4DE; padding: 6px 10px; text-align: center; white-space: nowrap; vertical-align: middle; ' . $statusStyle . '">' . $statusText . '</td>';
                echo '<td align="center" style="border: 0.5pt solid #B0C4DE; padding: 6px 10px; text-align: center; color: #4B5563; white-space: nowrap; vertical-align: middle;">' . ($item->created_at ? $item->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB' : '-') . '</td>';
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
