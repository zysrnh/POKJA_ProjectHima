@extends('admin.layouts.app')

@section('title', 'Pengaturan Acara')
@section('header_title', 'Pengaturan Informasi Acara')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="bg-white border border-gray-300 p-6 sm:p-8 shadow-sm">
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h3 class="text-base font-bold uppercase tracking-wide text-gray-900">Kelola Informasi Waktu & Lokasi Acara</h3>
            <p class="text-xs text-gray-500 mt-1">Informasi ini akan langsung ditampilkan di halaman utama form pendaftaran mahasiswa.</p>
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

            <!-- Field: Nama Acara -->
            <div>
                <label for="nama_acara" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                    Nama Program Kerja / Acara <span class="text-red-600">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama_acara" 
                    name="nama_acara" 
                    value="{{ old('nama_acara', $pengaturan->nama_acara) }}"
                    placeholder="Contoh: POKJA HIMA IF"
                    required
                    class="w-full bg-gray-50 border @error('nama_acara') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-[#2A82C6] focus:ring-1 focus:ring-[#2A82C6] transition"
                >
                @error('nama_acara')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: Tanggal Acara -->
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
                    class="w-full bg-gray-50 border @error('tanggal_acara') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-[#2A82C6] focus:ring-1 focus:ring-[#2A82C6] transition"
                >
                <p class="mt-1 text-[11px] text-gray-500">Format bebas (Contoh: 13 Oktober 2026, 13-14 Oktober 2026, dsb).</p>
                @error('tanggal_acara')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: Jam / Waktu Acara -->
            <div>
                <label for="jam_acara" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                    Waktu / Jam Pelaksanaan <span class="text-red-600">*</span>
                </label>
                <input 
                    type="text" 
                    id="jam_acara" 
                    name="jam_acara" 
                    value="{{ old('jam_acara', $pengaturan->jam_acara) }}"
                    placeholder="Contoh: 08:00 WIB - Selesai"
                    required
                    class="w-full bg-gray-50 border @error('jam_acara') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-[#2A82C6] focus:ring-1 focus:ring-[#2A82C6] transition"
                >
                @error('jam_acara')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
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
                    placeholder="Contoh: Ruangan 105 / Gedung B"
                    required
                    class="w-full bg-gray-50 border @error('lokasi_acara') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-[#2A82C6] focus:ring-1 focus:ring-[#2A82C6] transition"
                >
                @error('lokasi_acara')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: Deskripsi Singkat -->
            <div>
                <label for="deskripsi_acara" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                    Deskripsi / Catatan Tambahan (Opsional)
                </label>
                <textarea 
                    id="deskripsi_acara" 
                    name="deskripsi_acara" 
                    rows="3"
                    placeholder="Contoh: Harap hadir 15 menit sebelum acara dimulai dan mengenakan pakaian rapi..."
                    class="w-full bg-gray-50 border @error('deskripsi_acara') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-[#2A82C6] focus:ring-1 focus:ring-[#2A82C6] transition"
                >{{ old('deskripsi_acara', $pengaturan->deskripsi_acara) }}</textarea>
                @error('deskripsi_acara')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 pt-3">
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-[#2A82C6] hover:bg-[#1A467C] active:bg-[#1A467C] text-white text-xs sm:text-sm font-semibold uppercase tracking-wider transition cursor-pointer"
                >
                    Simpan Pengaturan Acara
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
