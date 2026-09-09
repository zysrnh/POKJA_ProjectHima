<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Menampilkan daftar akun admin.
     */
    public function index()
    {
        $admins = User::latest()->paginate(10);

        return view('admin.users.index', compact('admins'));
    }

    /**
     * Menampilkan form tambah admin baru.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan data admin baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'name.required' => 'Nama admin wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar sebagai admin.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', "Akun admin '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Menampilkan form edit akun admin.
     */
    public function edit($id)
    {
        $admin = User::findOrFail($id);

        return view('admin.users.edit', compact('admin'));
    }

    /**
     * Memperbarui data akun admin.
     */
    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'name.required' => 'Nama admin wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
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
     * Menghapus akun admin.
     */
    public function destroy($id)
    {
        if ($id == Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun admin yang sedang Anda gunakan saat ini.');
        }

        $admin = User::findOrFail($id);
        $name = $admin->name;
        $admin->delete();

        return redirect()->route('admin.users.index')->with('success', "Akun admin '{$name}' berhasil dihapus.");
    }
}
