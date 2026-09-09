<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class KelasAdminController extends Controller
{
    /**
     * Menampilkan daftar seluruh kelas di admin.
     */
    public function index()
    {
        $kelasList = Kelas::latest()->paginate(15);

        // Hitung jumlah pendaftar per kelas untuk informasi statistik
        $pendaftarCountPerClass = Pendaftaran::selectRaw('kelas, count(*) as total')
            ->groupBy('kelas')
            ->pluck('total', 'kelas');

        return view('admin.kelas.index', compact('kelasList', 'pendaftarCountPerClass'));
    }

    /**
     * Menampilkan form tambah kelas baru.
     */
    public function create()
    {
        return view('admin.kelas.create');
    }

    /**
     * Menyimpan data kelas baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:50', 'unique:kelas,nama_kelas'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.unique' => 'Nama kelas ini sudah terdaftar.',
        ]);

        // Format nama kelas menjadi huruf kapital rapi
        $namaKelas = strtoupper(trim($validated['nama_kelas']));

        Kelas::create([
            'nama_kelas' => $namaKelas,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('admin.kelas.index')->with('success', "Kelas '{$namaKelas}' berhasil ditambahkan.");
    }

    /**
     * Menampilkan form edit kelas.
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);

        return view('admin.kelas.edit', compact('kelas'));
    }

    /**
     * Memperbarui data kelas.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:50', 'unique:kelas,nama_kelas,' . $kelas->id],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.unique' => 'Nama kelas ini sudah digunakan pada kelas lain.',
        ]);

        $oldName = $kelas->nama_kelas;
        $newName = strtoupper(trim($validated['nama_kelas']));

        $kelas->update([
            'nama_kelas' => $newName,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : false,
        ]);

        // Jika nama kelas berubah, update juga data pendaftar yang lama agar sinkron
        if ($oldName !== $newName) {
            Pendaftaran::where('kelas', $oldName)->update(['kelas' => $newName]);
        }

        return redirect()->route('admin.kelas.index')->with('success', "Data kelas '{$newName}' berhasil diperbarui.");
    }

    /**
     * Menghapus kelas.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $nama = $kelas->nama_kelas;

        $pendaftarCount = Pendaftaran::where('kelas', $nama)->count();
        if ($pendaftarCount > 0) {
            return redirect()->route('admin.kelas.index')->with('error', "Kelas '{$nama}' tidak dapat dihapus karena memiliki {$pendaftarCount} data pendaftar. Anda dapat menonaktifkan status kelas ini.");
        }

        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', "Kelas '{$nama}' berhasil dihapus.");
    }
}
