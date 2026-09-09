<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan halaman form pendaftaran.
     */
    public function index()
    {
        $kelasList = Kelas::where('is_active', true)->orderBy('nama_kelas')->get();

        return view('welcome', compact('kelasList'));
    }

    /**
     * Memproses penyimpanan pendaftaran baru dengan sanitasi dan validasi ketat.
     */
    public function store(Request $request)
    {
        // 1. Anti-Bot Honeypot check (jika bot mengisi field tersembunyi ini, langsung henti)
        if ($request->filled('website_url')) {
            return redirect()->back()->with('success', 'Pendaftaran Anda berhasil dikirim!');
        }

        // 2. Sanitasi awal input (menghapus tag HTML / spasi berlebih)
        $request->merge([
            'nama' => trim(strip_tags($request->input('nama', ''))),
            'nim' => trim(strip_tags($request->input('nim', ''))),
            'kelas' => trim(strip_tags($request->input('kelas', ''))),
            'no_telp' => trim(strip_tags($request->input('no_telp', ''))),
        ]);

        // 3. Normalisasi nomor telepon ke format standar Indonesia (08xxxxxxxxxx)
        $rawPhone = preg_replace('/[^0-9]/', '', $request->input('no_telp'));
        if (str_starts_with($rawPhone, '628')) {
            $rawPhone = '08' . substr($rawPhone, 3);
        } elseif (str_starts_with($rawPhone, '8')) {
            $rawPhone = '08' . substr($rawPhone, 1);
        }
        $request->merge(['no_telp' => $rawPhone]);

        // 4. Validasi Ketat
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
                'unique:pendaftarans,nim'
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
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.min' => 'Nama lengkap minimal terdiri dari 3 karakter.',
            'nama.max' => 'Nama lengkap maksimal 100 karakter.',
            'nama.regex' => 'Nama lengkap hanya boleh berisi huruf, spasi, titik, koma, tanda petik, dan tanda hubung.',
            
            'nim.required' => 'NIM wajib diisi.',
            'nim.numeric' => 'NIM hanya boleh berisi angka.',
            'nim.digits_between' => 'NIM harus berupa angka dengan panjang 8 hingga 20 digit.',
            'nim.unique' => 'NIM ini sudah terdaftar sebelumnya.',
            
            'kelas.required' => 'Silakan pilih kelas Anda.',
            'kelas.exists' => 'Kelas yang dipilih tidak valid atau belum terdaftar.',
            
            'no_telp.required' => 'No. Telepon / WhatsApp wajib diisi.',
            'no_telp.regex' => 'Format nomor WhatsApp tidak valid. Gunakan nomor Indonesia yang diawali 08 (panjang 10–14 digit).',
        ]);

        Pendaftaran::create($validated);

        return redirect()->back()->with('success', 'Pendaftaran Anda berhasil dikirim!');
    }
}
