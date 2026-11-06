@extends('admin.layouts.app')

@section('title', 'Tambah Admin Baru')
@section('header_title', 'Tambah Admin Baru')

@section('content')
<div class="max-w-xl mx-auto">

    <div class="bg-white border-2 border-[#1A467C] p-6 sm:p-8 shadow-[6px_6px_0px_0px_#1A467C]">
        <div class="border-b-2 border-[#1A467C] pb-4 mb-6">
            <span class="inline-block bg-[#1A467C] text-white text-[10px] font-black uppercase px-2.5 py-0.5 tracking-wider mb-1">
                MANAJEMEN PENGGUNA
            </span>
            <h3 class="text-base font-black uppercase tracking-tight text-[#1A467C]">Formulir Pendaftaran Admin Baru</h3>
            <p class="text-xs font-semibold text-gray-600 mt-1">Akun yang dibuat akan dapat login dan mengelola sistem pendaftaran POKJA.</p>
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

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Field: Nama -->
            <div>
                <label for="name" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    Nama Lengkap Admin <span class="text-[#CA2C2A]">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    placeholder="Contoh: Budi Santoso"
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
                    value="{{ old('email') }}"
                    placeholder="Contoh: budi@pokja.com"
                    required
                    class="w-full bg-[#F8FBFE] border-2 @error('email') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold font-mono text-sm px-4 py-3 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                >
                @error('email')
                    <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: Password -->
            <div>
                <label for="password" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    Password (Minimal 8 Karakter Huruf & Angka) <span class="text-[#CA2C2A]">*</span>
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        required
                        class="w-full bg-[#F8FBFE] border-2 @error('password') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold text-sm px-4 py-3 pr-11 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                    >
                    <button 
                        type="button" 
                        onclick="toggleCreateAdminPass()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#1A467C] hover:text-[#2A82C6] focus:outline-none cursor-pointer p-1"
                        title="Lihat / Sembunyikan Password"
                    >
                        <svg id="eye-create-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="square" stroke-width="2.2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eye-create-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
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
                    Simpan Admin
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

<script>
    function toggleCreateAdminPass() {
        const input = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-create-open');
        const eyeClosed = document.getElementById('eye-create-closed');
        if (!input) return;
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>
@endsection
