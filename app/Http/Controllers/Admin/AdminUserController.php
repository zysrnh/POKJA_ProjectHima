<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        ], [
            'name.required' => 'Nama admin wajib diisi.',
            'name.min' => 'Nama admin minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Password admin minimal 8 karakter dengan kombinasi huruf dan angka.',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $admin->update($updateData);

        return redirect()->route('admin.users.index')->with('success', "Data akun admin '{$admin->name}' berhasil diperbarui.");
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

        $name = $admin->name;
        $admin->delete();

        return redirect()->route('admin.users.index')->with('success', "Akun admin '{$name}' berhasil dihapus.");
    }
}
