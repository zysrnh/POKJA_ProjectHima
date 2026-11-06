<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    /**
     * Menampilkan daftar akun admin.
     * Urutkan Super Admin di posisi paling atas.
     */
    public function index()
    {
        $admins = User::orderByRaw("CASE WHEN LOWER(email) = ? THEN 0 ELSE 1 END", [User::SUPER_ADMIN_EMAIL])
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('admins'));
    }

    /**
     * Menampilkan form tambah admin baru (Hanya Super Admin).
     */
    public function create()
    {
        if (!Auth::user()?->isSuperAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akses ditolak! Hanya Super Admin yang dapat menambahkan akun admin baru.');
        }

        return view('admin.users.create');
    }

    /**
     * Menyimpan data admin baru ke database dengan validasi password ketat (Hanya Super Admin).
     */
    public function store(Request $request)
    {
        if (!Auth::user()?->isSuperAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akses ditolak! Hanya Super Admin yang dapat menambahkan akun admin baru.');
        }

        $request->merge([
            'name' => trim(strip_tags($request->input('name', ''))),
            'email' => strtolower(trim(strip_tags($request->input('email', '')))),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)->letters()->numbers()],
        ], [
            'name.required' => 'Nama admin wajib diisi.',
            'name.min' => 'Nama admin minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar sebagai admin.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password admin minimal 8 karakter dengan kombinasi huruf dan angka.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', "Akun admin '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Menampilkan form edit akun admin.
     * Admin biasa hanya boleh mengedit akunnya sendiri; Super Admin bebas mengedit semua akun.
     */
    public function edit($id)
    {
        $admin = User::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser?->isSuperAdmin() && (int) $admin->id !== (int) Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akses ditolak! Hanya Super Admin yang dapat mengubah data atau password akun admin lain.');
        }

        return view('admin.users.edit', compact('admin'));
    }

    /**
     * Memperbarui data akun admin.
     * Admin biasa hanya boleh memperbarui akunnya sendiri; Super Admin bebas memperbarui semua akun.
     */
    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser?->isSuperAdmin() && (int) $admin->id !== (int) Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akses ditolak! Hanya Super Admin yang dapat mengubah data atau password akun admin lain.');
        }

        $request->merge([
            'name' => trim(strip_tags($request->input('name', ''))),
            'email' => strtolower(trim(strip_tags($request->input('email', '')))),
        ]);

        // Cegah akun Super Admin kehilangan email Super Admin-nya secara tidak sengaja
        if ($admin->isSuperAdmin() && $request->input('email') !== User::SUPER_ADMIN_EMAIL) {
            return back()
                ->withErrors(['email' => 'Email utama Super Admin (' . User::SUPER_ADMIN_EMAIL . ') dikunci dan tidak dapat diubah.'])
                ->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'password' => ['nullable', 'string', Password::min(8)->letters()->numbers()],
            'foto' => [
                'nullable',
                'file',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:2048',
                'dimensions:min_width=50,min_height=50,max_width=4096,max_height=4096',
            ],
        ], [
            'name.required' => 'Nama admin wajib diisi.',
            'name.min' => 'Nama admin minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Password admin minimal 8 karakter dengan kombinasi huruf dan angka.',
            'foto.image' => 'File yang diunggah harus berupa gambar asli.',
            'foto.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
            'foto.mimetypes' => 'Konten file bukan gambar yang valid.',
            'foto.max' => 'Ukuran file foto maksimal 2 MB.',
            'foto.dimensions' => 'Resolusi foto minimal 50x50 piksel dan maksimal 4096x4096 piksel.',
        ]);

        // Proses upload foto profil admin dengan inspeksi struktur binary gambar asli (Anti-Webshell)
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $realPath = $file->getRealPath();
            $imageInfo = @getimagesize($realPath);

            $allowedMimeMap = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
            ];

            if ($imageInfo === false || !isset($imageInfo['mime']) || !array_key_exists($imageInfo['mime'], $allowedMimeMap)) {
                return back()->withErrors(['foto' => 'File gambar rusak atau terdeteksi tidak aman.'])->withInput();
            }

            $safeExtension = $allowedMimeMap[$imageInfo['mime']];
            $filename = 'admin_' . (int) $admin->id . '_' . Str::random(10) . '_' . time() . '.' . $safeExtension;

            $destinationPath = public_path('uploads/avatars');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Hapus foto profil admin lama jika ada
            $oldFiles = glob($destinationPath . DIRECTORY_SEPARATOR . 'admin_' . (int) $admin->id . '_*.*');
            if (is_array($oldFiles)) {
                foreach ($oldFiles as $oldFile) {
                    if (is_file($oldFile)) {
                        @unlink($oldFile);
                    }
                }
            }

            $file->move($destinationPath, $filename);
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $admin->update($updateData);

        $msg = (int) $admin->id === (int) Auth::id()
            ? 'Profil Anda berhasil diperbarui!'
            : "Data akun admin '{$admin->name}' berhasil diperbarui.";

        return redirect()->route('admin.users.index')->with('success', $msg);
    }

    /**
     * Menghapus akun admin (Hanya Super Admin, dan akun Super Admin tidak dapat dihapus).
     */
    public function destroy($id)
    {
        if (!Auth::user()?->isSuperAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akses ditolak! Hanya Super Admin yang memiliki wewenang menghapus akun admin.');
        }

        if ((int) $id === (int) Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun admin yang sedang Anda gunakan saat ini.');
        }

        $admin = User::findOrFail($id);

        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Akun Super Admin utama tidak dapat dihapus.');
        }

        if (User::count() <= 1) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus akun admin terakhir di sistem.');
        }

        // Bersihkan file avatar admin jika ada
        $destinationPath = public_path('uploads/avatars');
        $oldFiles = glob($destinationPath . DIRECTORY_SEPARATOR . 'admin_' . (int) $admin->id . '_*.*');
        if (is_array($oldFiles)) {
            foreach ($oldFiles as $oldFile) {
                if (is_file($oldFile)) {
                    @unlink($oldFile);
                }
            }
        }

        $name = $admin->name;
        $admin->delete();

        return redirect()->route('admin.users.index')->with('success', "Akun admin '{$name}' berhasil dihapus.");
    }
}
