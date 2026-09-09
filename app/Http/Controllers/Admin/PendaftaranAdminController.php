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
     * Export data pendaftar ke file CSV (Kompatibel Excel).
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
        $filename = 'data-pendaftar-pokja-' . date('Y-m-d_His') . '.csv';

        return new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['No', 'Nama Lengkap', 'NIM', 'Kelas', 'No. Telepon / WA', 'Status Kehadiran', 'Tanggal Pendaftaran'], ';');

            $no = 1;
            foreach ($data as $item) {
                $statusText = match ($item->status_kehadiran) {
                    'hadir' => 'Hadir',
                    'tidak_hadir' => 'Tidak Hadir',
                    default => 'Belum Hadir',
                };

                fputcsv($handle, [
                    $no++,
                    $item->nama,
                    "'" . $item->nim,
                    $item->kelas,
                    "'" . $item->no_telp,
                    $statusText,
                    $item->created_at ? $item->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') . ' WIB' : '-',
                ], ';');
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
