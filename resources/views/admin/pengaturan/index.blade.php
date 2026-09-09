@extends('admin.layouts.app')

@section('title', 'Pengaturan Acara')
@section('header_title', 'Pengaturan Acara & Contact Person')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white border-2 border-[#1A467C] p-6 sm:p-8 shadow-[6px_6px_0px_0px_#1A467C]">
        <div class="border-b-2 border-[#1A467C] pb-4 mb-6">
            <span class="inline-block bg-[#1A467C] text-white text-[10px] font-black uppercase px-2.5 py-0.5 tracking-wider mb-1">
                KONTROL SISTEM
            </span>
            <h3 class="text-lg font-black uppercase tracking-tight text-[#1A467C]">Kelola Informasi Acara & Contact Person</h3>
            <p class="text-xs font-semibold text-gray-600 mt-1">Informasi ini akan disinkronkan langsung pada landing page form pendaftaran dan pop-up sukses.</p>
        </div>

        <!-- Flash Alert Error -->
        @if ($errors->any())
            <div class="mb-5 p-4 bg-red-50 border-2 border-[#901C1A] text-red-950 text-xs sm:text-sm font-semibold shadow-[3px_3px_0px_0px_#901C1A]">
                <p class="font-black uppercase mb-1">Terdapat kesalahan input:</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-900">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Pengaturan Acara (Neubrutalism) -->
        <form action="{{ route('admin.pengaturan.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Section 1: Informasi Acara -->
            <div class="bg-[#F8FBFE] p-5 border-2 border-[#1A467C] shadow-[3px_3px_0px_0px_#1A467C] space-y-4">
                <h4 class="text-xs font-black uppercase tracking-wider text-[#1A467C] flex items-center gap-2 border-b border-[#1A467C]/20 pb-2">
                    <svg class="w-4 h-4 text-[#2A82C6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>1. Informasi Pelaksanaan Acara</span>
                </h4>

                <!-- Field: Nama Acara -->
                <div>
                    <label for="nama_acara" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                        Nama Acara <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nama_acara" 
                        name="nama_acara" 
                        value="{{ old('nama_acara', $pengaturan->nama_acara) }}"
                        placeholder="Contoh: POKJA HIMA IF"
                        required
                        class="w-full bg-white border-2 @error('nama_acara') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold text-sm px-3.5 py-2.5 focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                    >
                    @error('nama_acara')
                        <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: Deskripsi / Tema Acara -->
                <div>
                    <label for="deskripsi_acara" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                        Tema Utama Acara (Headline) <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="deskripsi_acara" 
                        name="deskripsi_acara" 
                        value="{{ old('deskripsi_acara', $pengaturan->deskripsi_acara) }}"
                        placeholder="Contoh: Innovative Idea to Great Proposal"
                        required
                        class="w-full bg-white border-2 @error('deskripsi_acara') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 text-sm px-3.5 py-2.5 focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition font-black"
                    >
                    @error('deskripsi_acara')
                        <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid: Tanggal & Waktu -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="tanggal_acara" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                            Tanggal Pelaksanaan <span class="text-[#CA2C2A]">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="tanggal_acara" 
                            name="tanggal_acara" 
                            value="{{ old('tanggal_acara', $pengaturan->tanggal_acara) }}"
                            placeholder="Contoh: 13 Oktober 2026"
                            required
                            class="w-full bg-white border-2 @error('tanggal_acara') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold text-sm px-3.5 py-2.5 focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                        >
                        @error('tanggal_acara')
                            <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jam_acara" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                            Waktu / Jam <span class="text-[#CA2C2A]">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="jam_acara" 
                            name="jam_acara" 
                            value="{{ old('jam_acara', $pengaturan->jam_acara) }}"
                            placeholder="Contoh: 08:00 WIB - Selesai"
                            required
                            class="w-full bg-white border-2 @error('jam_acara') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold text-sm px-3.5 py-2.5 focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                        >
                        @error('jam_acara')
                            <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Field: Lokasi / Ruangan -->
                <div>
                    <label for="lokasi_acara" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                        Lokasi / Tempat / Ruangan <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="lokasi_acara" 
                        name="lokasi_acara" 
                        value="{{ old('lokasi_acara', $pengaturan->lokasi_acara) }}"
                        placeholder="Contoh: Ruangan 105"
                        required
                        class="w-full bg-white border-2 @error('lokasi_acara') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold text-sm px-3.5 py-2.5 focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                    >
                    @error('lokasi_acara')
                        <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Section 2: Link Grup WhatsApp Peserta -->
            <div class="bg-green-50/50 p-5 border-2 border-green-800 shadow-[3px_3px_0px_0px_#166534] space-y-4">
                <h4 class="text-xs font-black uppercase tracking-wider text-green-900 flex items-center gap-2 border-b border-green-800/20 pb-2">
                    <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>2. Link Undangan Grup WhatsApp Peserta</span>
                </h4>

                <div>
                    <label for="link_grup_wa" class="block text-xs font-black uppercase tracking-wider text-green-950 mb-1">
                        Tautan / Link Undangan Grup WhatsApp <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <input 
                        type="url" 
                        id="link_grup_wa" 
                        name="link_grup_wa" 
                        value="{{ old('link_grup_wa', $pengaturan->link_grup_wa ?? 'https://chat.whatsapp.com/') }}"
                        placeholder="https://chat.whatsapp.com/KodeGrupWhatsAppAnda"
                        required
                        class="w-full bg-white border-2 @error('link_grup_wa') border-[#CA2C2A] bg-red-50 @else border-green-800 @enderror text-gray-950 font-bold font-mono text-sm px-3.5 py-2.5 focus:outline-none focus:shadow-[3px_3px_0px_0px_#166534] transition"
                    >
                    <p class="text-[11px] font-semibold text-gray-600 mt-1.5">
                        Tautan ini akan langsung dibuka saat calon peserta menekan tombol <strong>"GABUNG GRUP WHATSAPP PESERTA"</strong> pada pop-up sukses pendaftaran.
                    </p>
                    @error('link_grup_wa')
                        <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 pt-3">
                <button 
                    type="submit" 
                    class="btn-smooth px-6 py-3 bg-[#2A82C6] hover:bg-[#1A467C] active:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-[#1A467C] shadow-[4px_4px_0px_0px_#1A467C] cursor-pointer"
                >
                    Simpan Semua Pengaturan
                </button>
                <a 
                    href="{{ route('pendaftaran.index') }}" 
                    target="_blank"
                    class="btn-smooth px-4 py-3 border-2 border-[#1A467C] bg-white text-[#1A467C] hover:bg-blue-50 text-xs sm:text-sm font-bold uppercase tracking-wider shadow-[3px_3px_0px_0px_#1A467C] inline-flex items-center gap-1.5"
                >
                    <span>Lihat di Form</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
