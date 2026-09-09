@extends('admin.layouts.app')

@section('title', 'Edit Akun Admin')
@section('header_title', 'Edit Akun Admin')

@section('content')
<div class="max-w-xl mx-auto">

    <div class="bg-white border-2 border-[#1A467C] p-6 sm:p-8 shadow-[6px_6px_0px_0px_#1A467C]">
        <div class="border-b-2 border-[#1A467C] pb-4 mb-6">
            <span class="inline-block bg-[#1A467C] text-white text-[10px] font-black uppercase px-2.5 py-0.5 tracking-wider mb-1">
                MANAJEMEN PENGGUNA
            </span>
            <h3 class="text-base font-black uppercase tracking-tight text-[#1A467C]">Perbarui Informasi Admin</h3>
            <p class="text-xs font-semibold text-gray-600 mt-1">Kosongkan kolom password jika Anda tidak ingin mengubah password akun.</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 p-4 bg-red-50 border-2 border-[#901C1A] text-red-950 text-xs sm:text-sm font-semibold shadow-[3px_3px_0px_0px_#901C1A]">
                <p class="font-black uppercase mb-1">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-900">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $admin->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Field: Nama -->
            <div>
                <label for="name" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    Nama Lengkap Admin <span class="text-[#CA2C2A]">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $admin->name) }}"
                    required
                    class="w-full bg-[#F8FBFE] border-2 @error('name') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold text-sm px-4 py-3 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                >
                @error('name')
                    <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: Email -->
            <div>
                <label for="email" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    Alamat Email <span class="text-[#CA2C2A]">*</span>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $admin->email) }}"
                    required
                    class="w-full bg-[#F8FBFE] border-2 @error('email') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold font-mono text-sm px-4 py-3 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                >
                @error('email')
                    <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: Password (Optional) -->
            <div>
                <label for="password" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    Password Baru (Opsional)
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Biarkan kosong jika tidak diganti"
                    class="w-full bg-[#F8FBFE] border-2 @error('password') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold text-sm px-4 py-3 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                >
                <p class="text-[11px] font-semibold text-gray-500 mt-1">Isi minimal 6 karakter jika ingin mengganti password admin ini.</p>
                @error('password')
                    <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 pt-3">
                <button 
                    type="submit" 
                    class="btn-smooth px-6 py-3 bg-[#2A82C6] hover:bg-[#1A467C] active:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-[#1A467C] shadow-[3px_3px_0px_0px_#1A467C] cursor-pointer"
                >
                    Simpan Perubahan
                </button>
                <a 
                    href="{{ route('admin.users.index') }}" 
                    class="btn-smooth px-5 py-3 border-2 border-gray-400 bg-white hover:bg-gray-100 text-gray-800 text-xs sm:text-sm font-bold uppercase tracking-wider shadow-[2px_2px_0px_0px_#9ca3af]"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
