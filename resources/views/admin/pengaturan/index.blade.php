@extends('admin.layouts.app')

@section('title', 'Pengaturan Acara')
@section('header_title', 'Pengaturan Informasi Acara & Contact Person')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="bg-white border border-gray-300 p-6 sm:p-8 shadow-sm">
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h3 class="text-base font-bold uppercase tracking-wide text-gray-900">Kelola Informasi Acara & Contact Person</h3>
            <p class="text-xs text-gray-500 mt-1">Informasi ini akan ditampilkan langsung pada halaman form pendaftaran dan pop-up sukses pendaftaran.</p>
        </div>

        <!-- Flash Alert Error -->
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

        <!-- Form Pengaturan Acara -->
        <form action="{{ route('admin.pengaturan.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Section 1: Informasi Acara -->
            <div class="bg-gray-50 p-4 border border-gray-200 space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#1A467C] flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#2A82C6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Informasi Pelaksanaan Acara</span>
                </h4>

                <!-- Field: Nama Acara -->
                <div>
                    <label for="nama_acara" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                        Nama Acara <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nama_acara" 
                        name="nama_acara" 
                        value="{{ old('nama_acara', $pengaturan->nama_acara) }}"
                        placeholder="Contoh: POKJA HIMA IF"
                        required
                        class="w-full bg-white border @error('nama_acara') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:outline-none focus:border-[#2A82C6] focus:ring-1 focus:ring-[#2A82C6] transition"
                    >
                    @error('nama_acara')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: Deskripsi / Tema Acara -->
                <div>
                    <label for="deskripsi_acara" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                        Tema Utama Acara (Headline) <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="deskripsi_acara" 
                        name="deskripsi_acara" 
                        value="{{ old('deskripsi_acara', $pengaturan->deskripsi_acara) }}"
                        placeholder="Contoh: Innovative Idea to Great Proposal"
                        required
                        class="w-full bg-white border @error('deskripsi_acara') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:outline-none focus:border-[#2A82C6] focus:ring-1 focus:ring-[#2A82C6] transition font-bold"
                    >
                    @error('deskripsi_acara')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid: Tanggal & Waktu -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="tanggal_acara" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                            Tanggal Pelaksanaan <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="tanggal_acara" 
                            name="tanggal_acara" 
                            value="{{ old('tanggal_acara', $pengaturan->tanggal_acara) }}"
                            placeholder="Contoh: 13 Oktober 2026"
                            required
                            class="w-full bg-white border @error('tanggal_acara') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:outline-none focus:border-[#2A82C6] focus:ring-1 focus:ring-[#2A82C6] transition"
                        >
                        @error('tanggal_acara')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jam_acara" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                            Waktu / Jam <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="jam_acara" 
                            name="jam_acara" 
                            value="{{ old('jam_acara', $pengaturan->jam_acara) }}"
                            placeholder="Contoh: 08:00 WIB - Selesai"
                            required
                            class="w-full bg-white border @error('jam_acara') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:outline-none focus:border-[#2A82C6] focus:ring-1 focus:ring-[#2A82C6] transition"
                        >
                        @error('jam_acara')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Field: Lokasi / Ruangan -->
                <div>
                    <label for="lokasi_acara" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                        Lokasi / Tempat / Ruangan <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="lokasi_acara" 
                        name="lokasi_acara" 
                        value="{{ old('lokasi_acara', $pengaturan->lokasi_acara) }}"
                        placeholder="Contoh: Ruangan 105"
                        required
                        class="w-full bg-white border @error('lokasi_acara') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:outline-none focus:border-[#2A82C6] focus:ring-1 focus:ring-[#2A82C6] transition"
                    >
                    @error('lokasi_acara')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Section 2: Contact Person (CP) -->
            <div class="bg-gray-50 p-4 border border-gray-200 space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#CA2C2A] flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#CA2C2A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>Contact Person (CP) WhatsApp</span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Field: Nama CP -->
                    <div>
                        <label for="cp_nama" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                            Nama Contact Person <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="cp_nama" 
                            name="cp_nama" 
                            value="{{ old('cp_nama', $pengaturan->cp_nama ?? 'Admin HIMA IF') }}"
                            placeholder="Contoh: Kak Admin / Zaki"
                            required
                            class="w-full bg-white border @error('cp_nama') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:outline-none focus:border-[#2A82C6] focus:ring-1 focus:ring-[#2A82C6] transition"
                        >
                        @error('cp_nama')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Field: Nomor WA CP -->
                    <div>
                        <label for="cp_nomor" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                            Nomor WhatsApp CP <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="tel" 
                            id="cp_nomor" 
                            name="cp_nomor" 
                            value="{{ old('cp_nomor', $pengaturan->cp_nomor ?? '083861669565') }}"
                            placeholder="Contoh: 083861669565"
                            required
                            class="w-full bg-white border @error('cp_nomor') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:outline-none focus:border-[#2A82C6] focus:ring-1 focus:ring-[#2A82C6] transition font-mono"
                        >
                        @error('cp_nomor')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 pt-2">
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-[#2A82C6] hover:bg-[#1A467C] active:bg-[#1A467C] text-white text-xs sm:text-sm font-semibold uppercase tracking-wider transition cursor-pointer"
                >
                    Simpan Semua Pengaturan
                </button>
                <a 
                    href="{{ route('pendaftaran.index') }}" 
                    target="_blank"
                    class="px-4 py-2.5 border border-gray-300 hover:bg-gray-100 text-gray-700 text-xs sm:text-sm font-semibold uppercase tracking-wider transition inline-flex items-center gap-1.5"
                >
                    <span>Lihat di Form</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
