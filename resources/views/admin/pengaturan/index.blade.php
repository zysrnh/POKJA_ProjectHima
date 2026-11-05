@extends('admin.layouts.app')

@section('title', 'Pengaturan Acara')
@section('header_title', 'Pengaturan Acara & Contact Person')

@section('content')
<style>
    /* Staggered Physical Spring Slam (100% Solid Opacity, ZERO Fade) */
    @keyframes sectionSlam {
        0% {
            transform: translateY(-26px) scale(0.97);
        }
        60% {
            transform: translateY(5px) scale(1.008);
        }
        85% {
            transform: translateY(-2px) scale(0.997);
        }
        100% {
            transform: translateY(0) scale(1);
        }
    }

    .snap-card-main {
        animation: sectionSlam 0.32s cubic-bezier(0.18, 0.89, 0.32, 1.28) 0.02s both;
    }
    .snap-section-1 {
        animation: sectionSlam 0.32s cubic-bezier(0.18, 0.89, 0.32, 1.28) 0.08s both;
    }
    .snap-section-2 {
        animation: sectionSlam 0.32s cubic-bezier(0.18, 0.89, 0.32, 1.28) 0.14s both;
    }
    .snap-section-3 {
        animation: sectionSlam 0.32s cubic-bezier(0.18, 0.89, 0.32, 1.28) 0.20s both;
    }

    .card-interactive {
        transition: transform 0.15s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.15s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .card-interactive:hover {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0px 0px #000000 !important;
    }
</style>

<div class="max-w-3xl mx-auto space-y-6">

    <div class="snap-card-main bg-white border-2 border-[#000000] p-5 sm:p-8 shadow-[6px_6px_0px_0px_#000000]">
        
        <!-- Header Banner -->
        <div class="border-b-2 border-[#000000] pb-4 mb-6 flex items-start justify-between gap-3">
            <div>
                <span class="inline-block bg-[#1A467C] text-white text-[10px] font-black uppercase px-2.5 py-0.5 tracking-wider border border-black mb-1.5 shadow-[1px_1px_0px_0px_#000000]">
                    KONTROL SISTEM
                </span>
                <h3 class="text-base sm:text-lg font-black uppercase tracking-tight text-[#1A467C]">
                    Kelola Informasi Acara & Contact Person
                </h3>
                <p class="text-xs font-semibold text-gray-600 mt-1">
                    Informasi ini otomatis disinkronkan ke form pendaftaran, pop-up tiket, dan email mahasiswa.
                </p>
            </div>
            <div class="w-9 h-9 sm:w-10 sm:h-10 bg-[#2A82C6] border-2 border-black flex items-center justify-center text-white shrink-0 shadow-[2px_2px_0px_0px_#000000]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="square" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
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
            <div class="snap-section-1 card-interactive bg-[#F8FBFE] p-4 sm:p-5 border-2 border-[#000000] shadow-[3px_3px_0px_0px_#000000] space-y-4">
                <h4 class="text-xs font-black uppercase tracking-wider text-[#1A467C] flex items-center gap-2 border-b-2 border-black/10 pb-2">
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
                        class="w-full bg-white border-2 @error('nama_acara') border-[#CA2C2A] bg-red-50 @else border-[#000000] @enderror text-gray-950 font-bold text-sm px-3.5 py-2.5 focus:outline-none focus:shadow-[3px_3px_0px_0px_#000000] transition"
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
                        class="w-full bg-white border-2 @error('deskripsi_acara') border-[#CA2C2A] bg-red-50 @else border-[#000000] @enderror text-gray-950 text-sm px-3.5 py-2.5 focus:outline-none focus:shadow-[3px_3px_0px_0px_#000000] transition font-black"
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
                            class="w-full bg-white border-2 @error('tanggal_acara') border-[#CA2C2A] bg-red-50 @else border-[#000000] @enderror text-gray-950 font-bold text-sm px-3.5 py-2.5 focus:outline-none focus:shadow-[3px_3px_0px_0px_#000000] transition"
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
                            class="w-full bg-white border-2 @error('jam_acara') border-[#CA2C2A] bg-red-50 @else border-[#000000] @enderror text-gray-950 font-bold text-sm px-3.5 py-2.5 focus:outline-none focus:shadow-[3px_3px_0px_0px_#000000] transition"
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
                        class="w-full bg-white border-2 @error('lokasi_acara') border-[#CA2C2A] bg-red-50 @else border-[#000000] @enderror text-gray-950 font-bold text-sm px-3.5 py-2.5 focus:outline-none focus:shadow-[3px_3px_0px_0px_#000000] transition"
                    >
                    @error('lokasi_acara')
                        <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Section 2: Link Grup WhatsApp Peserta -->
            <div class="snap-section-2 card-interactive bg-green-50/60 p-4 sm:p-5 border-2 border-[#000000] shadow-[3px_3px_0px_0px_#000000] space-y-4">
                <h4 class="text-xs font-black uppercase tracking-wider text-green-950 flex items-center gap-2 border-b-2 border-black/10 pb-2">
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
                        class="w-full bg-white border-2 @error('link_grup_wa') border-[#CA2C2A] bg-red-50 @else border-[#000000] @enderror text-gray-950 font-bold font-mono text-sm px-3.5 py-2.5 focus:outline-none focus:shadow-[3px_3px_0px_0px_#000000] transition"
                    >
                    <p class="text-[11px] font-semibold text-gray-600 mt-1.5">
                        Tautan ini otomatis terbuka saat calon peserta menekan tombol <strong>"GABUNG GRUP WHATSAPP PESERTA"</strong> pada pop-up sukses pendaftaran & email tiket.
                    </p>
                    @error('link_grup_wa')
                        <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Section 3: Action Buttons -->
            <div class="snap-section-3 flex items-center gap-2.5 pt-2">
                <button 
                    type="submit" 
                    class="btn-smooth px-5 sm:px-6 py-2.5 sm:py-3 bg-[#2A82C6] hover:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-[#000000] shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                >
                    Simpan Semua Pengaturan
                </button>
                <a 
                    href="{{ route('pendaftaran.index') }}" 
                    target="_blank"
                    class="btn-smooth px-4 py-2.5 sm:py-3 border-2 border-[#000000] bg-white text-[#1A467C] hover:bg-blue-50 text-xs sm:text-sm font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] inline-flex items-center gap-1.5"
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
