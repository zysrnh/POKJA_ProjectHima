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

        $pendaftarans = $query->paginate(15)->withQueryString();

        // Statistik
        $totalPendaftar = Pendaftaran::count();
        $pendaftarHariIni = Pendaftaran::whereDate('created_at', Carbon::today())->count();
        $totalKelas = Kelas::where('is_active', true)->count();
        
        // Pilihan kelas untuk filter
        $kelasList = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');

        return view('admin.dashboard', compact(
            'pendaftarans',
            'totalPendaftar',
            'pendaftarHariIni',
            'totalKelas',
            'kelasList',
            'search',
            'kelasFilter'
        ));
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

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:50', 'unique:pendaftarans,nim,' . $pendaftaran->id],
            'kelas' => ['required', 'string', 'max:100'],
            'no_telp' => ['required', 'string', 'max:30'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM ini sudah terdaftar pada data lain.',
            'kelas.required' => 'Silakan pilih kelas.',
            'no_telp.required' => 'No. Telepon / WhatsApp wajib diisi.',
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

        $data = $query->get();
        $filename = 'data-pendaftar-pokja-' . date('Y-m-d_His') . '.csv';

        return new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['No', 'Nama Lengkap', 'NIM', 'Kelas', 'No. Telepon / WA', 'Tanggal Pendaftaran'], ';');

            $no = 1;
            foreach ($data as $item) {
                fputcsv($handle, [
                    $no++,
                    $item->nama,
                    "'" . $item->nim,
                    $item->kelas,
                    "'" . $item->no_telp,
                    $item->created_at ? $item->created_at->format('d-m-Y H:i') : '-',
                ], ';');
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
