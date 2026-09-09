@extends('admin.layouts.app')

@section('title', 'Tambah Admin Baru')
@section('header_title', 'Tambah Admin Baru')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="bg-white border border-gray-300 p-6 sm:p-8 shadow-sm">
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h3 class="text-base font-bold uppercase tracking-wide text-gray-900">Formulir Pendaftaran Admin Baru</h3>
            <p class="text-xs text-gray-500 mt-1">Akun yang dibuat akan dapat login dan mengelola sistem POKJA.</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 p-3.5 bg-red-50 border border-red-600 text-red-800 text-xs sm:text-sm">
                <p class="font-semibold mb-1">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700">
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
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                    Nama Lengkap Admin <span class="text-red-600">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    placeholder="Contoh: Budi Santoso"
                    required
                    class="w-full bg-gray-50 border @error('name') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                >
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: Email -->
            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                    Alamat Email <span class="text-red-600">*</span>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="Contoh: budi@pokja.com"
                    required
                    class="w-full bg-gray-50 border @error('email') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                >
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: Password -->
            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                    Password (Minimal 6 Karakter) <span class="text-red-600">*</span>
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••"
                    required
                    class="w-full bg-gray-50 border @error('password') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                >
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 pt-3">
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs sm:text-sm font-semibold uppercase tracking-wider transition cursor-pointer"
                >
                    Simpan Admin
                </button>
                <a 
                    href="{{ route('admin.users.index') }}" 
                    class="px-5 py-2.5 border border-gray-300 hover:bg-gray-100 text-gray-700 text-xs sm:text-sm font-semibold uppercase tracking-wider transition"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
