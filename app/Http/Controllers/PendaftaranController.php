<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan halaman form pendaftaran dan info jadwal acara.
     */
    public function index()
    {
        $kelasList = Kelas::where('is_active', true)->orderBy('nama_kelas')->get();
        $pengaturan = Pengaturan::firstOrCreate(
            ['id' => 1],
            [
                'nama_acara' => 'POKJA HIMA IF',
                'tanggal_acara' => '13 Oktober 2026',
                'jam_acara' => '08:00 WIB - Selesai',
                'lokasi_acara' => 'Ruangan 105',
                'deskripsi_acara' => 'Pelaksanaan Program Kerja Himpunan Mahasiswa Informatika (HIMA IF)',
            ]
        );

        return view('welcome', compact('kelasList', 'pengaturan'));
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
            'email' => trim(strip_tags(strtolower($request->input('email', '')))),
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
                'digits:9', 
                'unique:pendaftarans,nim'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                'ends_with:@fellow.lpkia.ac.id',
                'unique:pendaftarans,email'
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
            'nim.digits' => 'NIM harus tepat 9 digit angka.',
            'nim.unique' => 'NIM ini sudah terdaftar sebelumnya.',

            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.ends_with' => 'Email harus menggunakan domain resmi kampus (@fellow.lpkia.ac.id).',
            'email.max' => 'Alamat email maksimal 150 karakter.',
            'email.unique' => 'Alamat email ini sudah terdaftar sebelumnya.',
            
            'kelas.required' => 'Silakan pilih kelas Anda.',
            'kelas.exists' => 'Kelas yang dipilih tidak valid atau belum terdaftar.',
            
            'no_telp.required' => 'No. Telepon / WhatsApp wajib diisi.',
            'no_telp.regex' => 'Format nomor WhatsApp tidak valid. Gunakan nomor Indonesia yang diawali 08 (panjang 10–14 digit).',
        ]);

        Pendaftaran::create($validated);

        // Sinkronisasi otomatis ke Google Sheets via Webhook
        $webhookUrl = env('GOOGLE_SHEET_WEBHOOK_URL');
        if (!empty($webhookUrl)) {
            try {
                Http::timeout(5)->post($webhookUrl, [
                    'nama' => $validated['nama'],
                    'nim' => $validated['nim'],
                    'email' => $validated['email'],
                    'kelas' => $validated['kelas'],
                    'no_telp' => $validated['no_telp'],
                    'created_at' => now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s'),
                ]);
            } catch (\Throwable $e) {
                Log::warning('Sinkronisasi Google Sheet Webhook gagal: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Pendaftaran Anda berhasil dikirim!');
    }
}
