@extends('admin.layouts.app')

@section('title', 'Daftar Point Mahasiswa')
@section('header_title', 'Daftar Point Mahasiswa')

@section('content')
<style>
    @keyframes brutalSlamIn {
        0% { transform: translateY(-90px) scale(0.82); }
        55% { transform: translateY(14px) scale(1.04); }
        80% { transform: translateY(-5px) scale(0.99); }
        100% { transform: translateY(0) scale(1); }
    }
    .modal-slam-in {
        animation: brutalSlamIn 0.28s cubic-bezier(0.2, 0.9, 0.3, 1.25) both !important;
    }
    .card-interactive {
        transition: transform 0.15s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.15s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .card-interactive:hover {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0px 0px #000000 !important;
    }
</style>

<div class="space-y-4 sm:space-y-6">

    <!-- ============================================================== -->
    <!-- 1. TOMBOL AKSI CEPAT & HEADER BANNER                           -->
    <!-- ============================================================== -->
    <div class="snap-in-1 bg-white border-2 border-[#000000] px-4 py-3 sm:px-5 sm:py-3.5 shadow-[5px_5px_0px_0px_#000000] flex items-center justify-between gap-3">
        <h3 class="text-base sm:text-xl font-black uppercase tracking-tight text-[#1A467C]">
            POINTKU POKJA
        </h3>

        <div class="flex items-center gap-2 shrink-0">
            <button 
                type="button" 
                onclick="openPointModal('add')"
                title="Tambah Point"
                aria-label="Tambah Point"
                class="btn-smooth w-9 h-9 sm:w-10 sm:h-10 bg-[#15803d] hover:bg-[#166534] text-white border-2 border-black shadow-[2px_2px_0px_0px_#000000] flex items-center justify-center cursor-pointer"
            >
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
            </button>

            <button 
                type="button" 
                onclick="openPointModal('reduce')"
                title="Kurangi Point"
                aria-label="Kurangi Point"
                class="btn-smooth w-9 h-9 sm:w-10 sm:h-10 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border-2 border-black shadow-[2px_2px_0px_0px_#000000] flex items-center justify-center cursor-pointer"
            >
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="3" d="M20 12H4" />
                </svg>
            </button>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 2. FILTER & PENCARIAN BAR                                      -->
    <!-- ============================================================== -->
    <div class="snap-in-2 bg-white border-2 border-black p-3 sm:p-4 shadow-[4px_4px_0px_0px_#000000]">
        <form action="{{ route('admin.poin.mahasiswa') }}" method="GET" class="flex flex-col md:flex-row items-stretch gap-2.5">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search }}"
                    placeholder="Cari berdasarkan NIM, Nama, atau Kelas..."
                    class="w-full bg-[#F8FBFE] border-2 border-black text-xs sm:text-sm font-semibold px-3 py-2 pl-8 focus:bg-white focus:outline-none focus:shadow-[2px_2px_0px_0px_#000000] transition"
                >
                <svg class="w-4 h-4 text-gray-500 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Filter Kelas -->
            <div class="relative w-full md:w-44">
                <select 
                    name="kelas" 
                    class="w-full bg-[#F8FBFE] border-2 border-black font-bold font-mono text-xs px-3 py-2.5 pr-8 appearance-none focus:bg-white focus:outline-none cursor-pointer"
                >
                    <option value="">-- SEMUA KELAS --</option>
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->nama_kelas }}" {{ $kelasFilter === $k->nama_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-black">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- Urutkan -->
            <div class="relative w-full md:w-48">
                <select 
                    name="sort" 
                    class="w-full bg-[#F8FBFE] border-2 border-black font-bold text-xs px-3 py-2.5 pr-8 appearance-none focus:bg-white focus:outline-none cursor-pointer"
                >
                    <option value="point_desc" {{ $sort === 'point_desc' ? 'selected' : '' }}>Point Tertinggi</option>
                    <option value="point_asc" {{ $sort === 'point_asc' ? 'selected' : '' }}>Point Terendah</option>
                    <option value="nama_asc" {{ $sort === 'nama_asc' ? 'selected' : '' }}>Nama (A - Z)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-black">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <div class="flex items-center gap-1.5 shrink-0">
                <button 
                    type="submit" 
                    class="btn-smooth px-5 py-2 bg-[#1A467C] hover:bg-[#0F2A4A] text-white text-xs font-black uppercase tracking-wider border-2 border-black shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    Filter
                </button>
                @if ($search || $kelasFilter || $sort !== 'point_desc')
                    <a 
                        href="{{ route('admin.poin.mahasiswa') }}" 
                        class="btn-smooth px-3 py-2 bg-white hover:bg-gray-100 text-gray-800 border-2 border-black text-xs font-black shadow-[2px_2px_0px_0px_#000000]"
                        title="Reset Filter"
                    >
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- ============================================================== -->
    <!-- 4. TABEL DESKTOP DAFTAR POINT MAHASISWA                        -->
    <!-- ============================================================== -->
    <div class="snap-in-6 hidden lg:block bg-white border-2 border-black shadow-[6px_6px_0px_0px_#000000] overflow-hidden">
        <div class="px-5 py-3 border-b-2 border-black bg-blue-50/70 flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                Daftar Mahasiswa & Peringkat Point Keaktifan
            </h3>
            <span class="text-xs font-bold text-[#1A467C] bg-white border-2 border-black px-2.5 py-0.5 shadow-[1px_1px_0px_0px_#000000]">
                Total: {{ $pendaftarans->total() }} Mahasiswa
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="border-b-2 border-black bg-[#1A467C] text-white uppercase font-black text-[11px] tracking-wider">
                        <th class="py-3.5 px-3 w-14 text-center">No</th>
                        <th class="py-3.5 px-3.5">Nama Mahasiswa</th>
                        <th class="py-3.5 px-3">NIM</th>
                        <th class="py-3.5 px-3 text-center">Kelas</th>
                        <th class="py-3.5 px-3 text-center">Total Point</th>
                        <th class="py-3.5 px-3 text-center">Aktivitas</th>
                        <th class="py-3.5 px-3 text-center w-40">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pendaftarans as $index => $item)
                        @php
                            $rankNum = $pendaftarans->firstItem() + $index;
                        @endphp
                        <tr class="hover:bg-blue-50/50 transition">
                            <td class="py-3 px-3 text-center font-mono font-black text-gray-800">
                                @if ($sort === 'point_desc' && empty($search) && empty($kelasFilter) && $rankNum <= 3)
                                    <span class="inline-flex items-center justify-center w-6 h-6 border border-black text-xs font-black {{ $rankNum === 1 ? 'bg-amber-400 text-black' : ($rankNum === 2 ? 'bg-sky-200 text-black' : 'bg-orange-300 text-black') }}">
                                        #{{ $rankNum }}
                                    </span>
                                @else
                                    {{ $rankNum }}
                                @endif
                            </td>
                            <td class="py-3 px-3.5 font-bold text-gray-950">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                                        @if ($item->avatarUrl())
                                            <img src="{{ $item->avatarUrl() }}" alt="Avatar {{ $item->nama }}" class="w-full h-full object-cover">
                                        @else
                                            {{ $item->initials() }}
                                        @endif
                                    </div>
                                    <span>{{ $item->nama }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-3 font-mono font-bold text-gray-900">
                                {{ $item->nim }}
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="inline-block bg-blue-50 border border-[#2A82C6] px-2 py-0.5 text-xs text-[#1A467C] font-bold font-mono">
                                    {{ $item->kelas }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="inline-flex items-center gap-1 bg-green-100 border-2 border-black px-3 py-1 font-mono font-black text-sm text-[#15803d] shadow-[2px_2px_0px_0px_#000000]">
                                    <span>★</span>
                                    <span>{{ (int) $item->total_point }}</span>
                                    <span class="text-[10px] uppercase">Pts</span>
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center font-mono text-xs font-bold text-gray-600">
                                {{ $item->point_histories_count }}x tercatat
                            </td>
                            <td class="py-3 px-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Riwayat Point Button -->
                                    <button 
                                        type="button"
                                        onclick="openHistoryModal({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->nim }}')"
                                        class="btn-smooth w-8 h-8 bg-[#2A82C6] hover:bg-[#1A467C] text-white border-2 border-black flex items-center justify-center shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
                                        title="Lihat Riwayat Point {{ $item->nama }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="square" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>

                                    <!-- Quick Add Point (+) -->
                                    <button 
                                        type="button"
                                        onclick="quickSelectStudent('add', {{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->nim }}', '{{ addslashes($item->kelas) }}', {{ (int) $item->total_point }})"
                                        class="btn-smooth w-8 h-8 bg-[#15803d] hover:bg-[#166534] text-white border-2 border-black flex items-center justify-center shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
                                        title="Tambah Point untuk {{ $item->nama }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="square" stroke-width="3" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>

                                    <!-- Quick Reduce Point (-) -->
                                    <button 
                                        type="button"
                                        onclick="quickSelectStudent('reduce', {{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->nim }}', '{{ addslashes($item->kelas) }}', {{ (int) $item->total_point }})"
                                        class="btn-smooth w-8 h-8 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border-2 border-black flex items-center justify-center shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
                                        title="Kurangi Point untuk {{ $item->nama }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="square" stroke-width="3" d="M20 12H4" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-gray-500 font-bold text-xs sm:text-sm">
                                Belum ada data mahasiswa yang cocok dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pendaftarans->hasPages())
            <div class="px-5 py-3 border-t-2 border-black bg-white">
                {{ $pendaftarans->links() }}
            </div>
        @endif
    </div>

    <!-- ============================================================== -->
    <!-- 5. MOBILE CARDS VIEW                                           -->
    <!-- ============================================================== -->
    <div class="snap-in-6 block lg:hidden space-y-3">
        @forelse ($pendaftarans as $index => $item)
            <div class="card-interactive bg-white border-2 border-black p-3.5 shadow-[3px_3px_0px_0px_#000000] space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-9 h-9 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden shadow-[1px_1px_0px_0px_#000000]">
                            @if ($item->avatarUrl())
                                <img src="{{ $item->avatarUrl() }}" alt="Avatar {{ $item->nama }}" class="w-full h-full object-cover">
                            @else
                                {{ $item->initials() }}
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-black text-sm text-gray-950 truncate">{{ $item->nama }}</h4>
                            <p class="font-mono text-xs font-bold text-[#1A467C]">{{ $item->nim }} • {{ $item->kelas }}</p>
                        </div>
                    </div>

                    <span class="shrink-0 inline-flex items-center gap-1 bg-green-100 border-2 border-black px-2.5 py-0.5 font-mono font-black text-xs text-[#15803d] shadow-[1px_1px_0px_0px_#000000]">
                        ★ {{ (int) $item->total_point }} Pts
                    </span>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                    <span class="text-[11px] font-mono font-bold text-gray-500">
                        {{ $item->point_histories_count }}x riwayat point
                    </span>
                    <div class="flex items-center gap-1.5">
                        <button 
                            type="button"
                            onclick="openHistoryModal({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->nim }}')"
                            class="btn-smooth px-2.5 py-1 bg-[#2A82C6] text-white border border-black text-[11px] font-black uppercase shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
                        >
                            Riwayat
                        </button>
                        <button 
                            type="button"
                            onclick="quickSelectStudent('add', {{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->nim }}', '{{ addslashes($item->kelas) }}', {{ (int) $item->total_point }})"
                            class="btn-smooth w-7 h-7 bg-[#15803d] text-white border border-black flex items-center justify-center font-black shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
                            title="Tambah Point"
                        >
                            +
                        </button>
                        <button 
                            type="button"
                            onclick="quickSelectStudent('reduce', {{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->nim }}', '{{ addslashes($item->kelas) }}', {{ (int) $item->total_point }})"
                            class="btn-smooth w-7 h-7 bg-[#CA2C2A] text-white border border-black flex items-center justify-center font-black shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
                            title="Kurangi Point"
                        >
                            -
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border-2 border-black p-6 text-center text-xs font-bold text-gray-500 shadow-[3px_3px_0px_0px_#000000]">
                Belum ada data mahasiswa.
            </div>
        @endforelse

        @if ($pendaftarans->hasPages())
            <div class="pt-2">
                {{ $pendaftarans->links() }}
            </div>
        @endif
    </div>

</div>

<!-- ============================================================== -->
<!-- MODAL 1: TAMBAH POINT (+) NEUBRUTALISM                         -->
<!-- ============================================================== -->
<div id="modal-point-add" class="hidden fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4">
    <div class="modal-slam-in bg-white border-[3px] border-black shadow-[8px_8px_0px_0px_#000000] max-w-md w-full p-5 sm:p-6 relative">
        <button 
            type="button" 
            onclick="closePointModal('add')" 
            class="btn-smooth absolute top-3.5 right-3.5 w-7 h-7 bg-white hover:bg-[#CA2C2A] hover:text-white border-2 border-black flex items-center justify-center text-xs font-black cursor-pointer"
        >
            ✕
        </button>

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-[#15803d] text-white border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <div>
                <span class="bg-[#15803d] text-white text-[10px] font-black uppercase px-1.5 py-0.5 border border-black">APRESIASI KEAKTIFAN</span>
                <h3 class="text-base sm:text-lg font-black uppercase text-gray-950 mt-0.5">Tambah Point Mahasiswa</h3>
            </div>
        </div>

        <form action="{{ route('admin.poin.store') }}" method="POST" onsubmit="return validatePointForm('add')" class="space-y-4">
            @csrf
            <input type="hidden" name="tipe" value="tambah">
            <input type="hidden" name="pendaftaran_id" id="add-pendaftaran-id" required>
            <input type="hidden" name="kategori" id="add-kategori-input" required>

            <!-- 1. Mahasiswa Terpilih / Pencarian -->
            <div>
                <label class="block text-[11px] font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    1. Mahasiswa Tujuan <span class="text-[#CA2C2A]">*</span>
                </label>

                <!-- Mode Cari (Muncul jika belum pilih mahasiswa) -->
                <div class="relative" id="add-search-wrapper">
                    <input 
                        type="text" 
                        id="add-search-input"
                        autocomplete="off"
                        placeholder="Ketik NIM atau Nama mahasiswa..."
                        oninput="filterStudentAutocomplete('add')"
                        onfocus="filterStudentAutocomplete('add')"
                        class="w-full bg-[#F8FBFE] border-2 border-black px-3 py-2.5 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none focus:shadow-[2px_2px_0px_0px_#000000]"
                    >
                    <div 
                        id="add-search-dropdown" 
                        class="hidden absolute left-0 right-0 top-full mt-1 bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] max-h-48 overflow-y-auto z-30 divide-y divide-gray-200"
                    ></div>
                </div>

                <!-- Kartu Mahasiswa Terpilih (Muncul otomatis saat mahasiswa dipilih) -->
                <div id="add-selected-card" class="hidden bg-green-50 border-2 border-black p-3 shadow-[3px_3px_0px_0px_#000000] flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div id="add-selected-initials" class="w-9 h-9 bg-[#15803d] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 shadow-[1px_1px_0px_0px_#000000]">
                            MHS
                        </div>
                        <div class="min-w-0">
                            <p id="add-selected-nama" class="font-black text-xs sm:text-sm text-gray-950 truncate">-</p>
                            <p id="add-selected-meta" class="font-mono text-[11px] font-bold text-[#1A467C]">-</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span id="add-selected-pts" class="bg-white border-2 border-black px-2 py-0.5 font-mono font-black text-xs text-[#15803d]">
                            0 Pts
                        </span>
                        <button 
                            type="button" 
                            onclick="clearSelectedStudent('add')" 
                            class="btn-smooth px-2 py-1 bg-white hover:bg-gray-100 text-gray-800 border-2 border-black text-[10px] font-black uppercase cursor-pointer"
                            title="Ganti Mahasiswa"
                        >
                            Ganti
                        </button>
                    </div>
                </div>
            </div>

            <!-- 2. Pilihan Kategori 1-Klik (Grid 2x2) -->
            <div>
                <label class="block text-[11px] font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    2. Pilih Aktivitas (Klik 1x) <span class="text-[#CA2C2A]">*</span>
                </label>
                <div class="grid grid-cols-2 gap-2.5" id="add-category-grid">
                    @foreach ($kategoriTambah as $key => $info)
                        <button
                            type="button"
                            data-kategori="{{ $key }}"
                            data-point="{{ $info['point'] }}"
                            onclick="selectCategoryCard('add', '{{ $key }}', {{ $info['point'] }}, this)"
                            class="add-cat-btn text-left p-2.5 border-2 border-black bg-[#F8FBFE] hover:bg-green-50 transition flex flex-col justify-between gap-1.5 cursor-pointer shadow-[2px_2px_0px_0px_#000000]"
                        >
                            <span class="font-black text-xs uppercase leading-tight block">{{ $key }}</span>
                            <span class="cat-pill inline-block self-start px-2 py-0.5 text-[11px] font-mono font-black border border-black bg-green-100 text-[#15803d]">
                                +{{ $info['point'] }} PTS
                            </span>
                        </button>
                    @endforeach
                    <button
                        type="button"
                        data-kategori="Custom Point"
                        data-point="0"
                        onclick="selectCategoryCard('add', 'Custom Point', 0, this)"
                        class="add-cat-btn col-span-2 text-left p-2.5 border-2 border-black bg-[#F8FBFE] hover:bg-green-50 transition flex items-center justify-between gap-2 cursor-pointer shadow-[2px_2px_0px_0px_#000000]"
                    >
                        <span class="font-black text-xs uppercase leading-tight">Custom Point (Isi Manual)</span>
                        <span class="cat-pill inline-block px-2 py-0.5 text-[11px] font-mono font-black border border-black bg-green-100 text-[#15803d]">
                            +CUSTOM
                        </span>
                    </button>
                </div>

                <!-- Input Angka Custom Point -->
                <div id="add-custom-point-wrapper" class="hidden mt-2.5">
                    <label class="block text-[11px] font-black uppercase tracking-wider text-[#15803d] mb-1">
                        Jumlah Point Custom (1 - 100) <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <input
                        type="number"
                        id="add-custom-point-input"
                        name="jumlah_custom"
                        min="1"
                        max="100"
                        value="1"
                        oninput="onCustomPointInput('add', this.value)"
                        class="w-full bg-white border-2 border-black px-3 py-2 text-xs sm:text-sm font-mono font-black focus:bg-yellow-50 focus:outline-none"
                    >
                </div>

                <!-- Bar Simulasi Hasil Point -->
                <div class="mt-2.5 px-3 py-2 bg-green-50 border-2 border-black flex items-center justify-between text-xs">
                    <span class="font-black uppercase text-gray-700 text-[11px]">Estimasi Point Akhir:</span>
                    <span id="add-calc-preview" class="font-mono font-black text-xs sm:text-sm text-[#15803d]">+1 Point</span>
                </div>
            </div>

            <!-- 3. Catatan Singkat (1 Baris) -->
            <div>
                <label class="block text-[11px] font-black uppercase tracking-wider text-[#1A467C] mb-1">
                    3. Catatan Singkat (Opsional)
                </label>
                <input 
                    type="text"
                    name="keterangan" 
                    placeholder="Contoh: Aktif bertanya di sesi pematerian..."
                    class="w-full bg-[#F8FBFE] border-2 border-black px-3 py-2 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none"
                >
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button 
                    type="button" 
                    onclick="closePointModal('add')" 
                    class="btn-smooth px-4 py-2.5 bg-white text-gray-900 border-2 border-black text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="btn-smooth px-5 py-2.5 bg-[#15803d] hover:bg-[#166534] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                >
                    + Simpan Point
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 2: KURANGI POINT (-) NEUBRUTALISM                        -->
<!-- ============================================================== -->
<div id="modal-point-reduce" class="hidden fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4">
    <div class="modal-slam-in bg-white border-[3px] border-black shadow-[8px_8px_0px_0px_#000000] max-w-md w-full p-5 sm:p-6 relative">
        <button 
            type="button" 
            onclick="closePointModal('reduce')" 
            class="btn-smooth absolute top-3.5 right-3.5 w-7 h-7 bg-white hover:bg-[#CA2C2A] hover:text-white border-2 border-black flex items-center justify-center text-xs font-black cursor-pointer"
        >
            ✕
        </button>

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-[#CA2C2A] text-white border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="3" d="M20 12H4" />
                </svg>
            </div>
            <div>
                <span class="bg-[#CA2C2A] text-white text-[10px] font-black uppercase px-1.5 py-0.5 border border-black">EVALUASI DISIPLIN</span>
                <h3 class="text-base sm:text-lg font-black uppercase text-gray-950 mt-0.5">Kurangi Point Mahasiswa</h3>
            </div>
        </div>

        <form action="{{ route('admin.poin.store') }}" method="POST" onsubmit="return validatePointForm('reduce')" class="space-y-4">
            @csrf
            <input type="hidden" name="tipe" value="kurang">
            <input type="hidden" name="pendaftaran_id" id="reduce-pendaftaran-id" required>
            <input type="hidden" name="kategori" id="reduce-kategori-input" required>

            <!-- 1. Mahasiswa Terpilih / Pencarian -->
            <div>
                <label class="block text-[11px] font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    1. Mahasiswa Tujuan <span class="text-[#CA2C2A]">*</span>
                </label>

                <!-- Mode Cari -->
                <div class="relative" id="reduce-search-wrapper">
                    <input 
                        type="text" 
                        id="reduce-search-input"
                        autocomplete="off"
                        placeholder="Ketik NIM atau Nama mahasiswa..."
                        oninput="filterStudentAutocomplete('reduce')"
                        onfocus="filterStudentAutocomplete('reduce')"
                        class="w-full bg-[#F8FBFE] border-2 border-black px-3 py-2.5 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none focus:shadow-[2px_2px_0px_0px_#000000]"
                    >
                    <div 
                        id="reduce-search-dropdown" 
                        class="hidden absolute left-0 right-0 top-full mt-1 bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] max-h-48 overflow-y-auto z-30 divide-y divide-gray-200"
                    ></div>
                </div>

                <!-- Kartu Mahasiswa Terpilih -->
                <div id="reduce-selected-card" class="hidden bg-red-50 border-2 border-black p-3 shadow-[3px_3px_0px_0px_#000000] flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div id="reduce-selected-initials" class="w-9 h-9 bg-[#CA2C2A] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 shadow-[1px_1px_0px_0px_#000000]">
                            MHS
                        </div>
                        <div class="min-w-0">
                            <p id="reduce-selected-nama" class="font-black text-xs sm:text-sm text-gray-950 truncate">-</p>
                            <p id="reduce-selected-meta" class="font-mono text-[11px] font-bold text-[#1A467C]">-</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span id="reduce-selected-pts" class="bg-white border-2 border-black px-2 py-0.5 font-mono font-black text-xs text-gray-900">
                            0 Pts
                        </span>
                        <button 
                            type="button" 
                            onclick="clearSelectedStudent('reduce')" 
                            class="btn-smooth px-2 py-1 bg-white hover:bg-gray-100 text-gray-800 border-2 border-black text-[10px] font-black uppercase cursor-pointer"
                            title="Ganti Mahasiswa"
                        >
                            Ganti
                        </button>
                    </div>
                </div>
            </div>

            <!-- 2. Pilihan Kategori Pengurangan 1-Klik (Grid 2x2) -->
            <div>
                <label class="block text-[11px] font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    2. Pilih Jenis Pengurangan (Klik 1x) <span class="text-[#CA2C2A]">*</span>
                </label>
                <div class="grid grid-cols-2 gap-2.5" id="reduce-category-grid">
                    @foreach ($kategoriKurang as $key => $info)
                        <button
                            type="button"
                            data-kategori="{{ $key }}"
                            data-point="{{ $info['point'] }}"
                            onclick="selectCategoryCard('reduce', '{{ $key }}', {{ $info['point'] }}, this)"
                            class="reduce-cat-btn text-left p-2.5 border-2 border-black bg-[#F8FBFE] hover:bg-red-50 transition flex flex-col justify-between gap-1.5 cursor-pointer shadow-[2px_2px_0px_0px_#000000]"
                        >
                            <span class="font-black text-xs uppercase leading-tight block">{{ $key }}</span>
                            <span class="cat-pill inline-block self-start px-2 py-0.5 text-[11px] font-mono font-black border border-black bg-red-100 text-[#CA2C2A]">
                                -{{ $info['point'] }} PTS
                            </span>
                        </button>
                    @endforeach
                    <button
                        type="button"
                        data-kategori="Custom Point"
                        data-point="0"
                        onclick="selectCategoryCard('reduce', 'Custom Point', 0, this)"
                        class="reduce-cat-btn col-span-2 text-left p-2.5 border-2 border-black bg-[#F8FBFE] hover:bg-red-50 transition flex items-center justify-between gap-2 cursor-pointer shadow-[2px_2px_0px_0px_#000000]"
                    >
                        <span class="font-black text-xs uppercase leading-tight">Custom Point (Isi Manual)</span>
                        <span class="cat-pill inline-block px-2 py-0.5 text-[11px] font-mono font-black border border-black bg-red-100 text-[#CA2C2A]">
                            -CUSTOM
                        </span>
                    </button>
                </div>

                <!-- Input Angka Custom Point -->
                <div id="reduce-custom-point-wrapper" class="hidden mt-2.5">
                    <label class="block text-[11px] font-black uppercase tracking-wider text-[#CA2C2A] mb-1">
                        Jumlah Pengurangan Custom (1 - 100) <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <input
                        type="number"
                        id="reduce-custom-point-input"
                        name="jumlah_custom"
                        min="1"
                        max="100"
                        value="1"
                        oninput="onCustomPointInput('reduce', this.value)"
                        class="w-full bg-white border-2 border-black px-3 py-2 text-xs sm:text-sm font-mono font-black focus:bg-yellow-50 focus:outline-none"
                    >
                </div>

                <!-- Bar Simulasi Hasil Point -->
                <div class="mt-2.5 px-3 py-2 bg-red-50 border-2 border-black flex items-center justify-between text-xs">
                    <span class="font-black uppercase text-red-900 text-[11px]">Estimasi Point Akhir:</span>
                    <span id="reduce-calc-preview" class="font-mono font-black text-xs sm:text-sm text-[#CA2C2A]">-1 Point</span>
                </div>
            </div>

            <!-- 3. Alasan Singkat (1 Baris) -->
            <div>
                <label class="block text-[11px] font-black uppercase tracking-wider text-[#1A467C] mb-1">
                    3. Alasan Pengurangan <span class="text-[#CA2C2A]">*</span>
                </label>
                <input 
                    type="text"
                    name="keterangan" 
                    required
                    placeholder="Contoh: Terlambat hadir 15 menit di Sesi 2..."
                    class="w-full bg-[#F8FBFE] border-2 border-black px-3 py-2 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none"
                >
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button 
                    type="button" 
                    onclick="closePointModal('reduce')" 
                    class="btn-smooth px-4 py-2.5 bg-white text-gray-900 border-2 border-black text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="btn-smooth px-5 py-2.5 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                >
                    - Kurangi Point
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 3: RIWAYAT & HAPUS POINT MAHASISWA                       -->
<!-- ============================================================== -->
<div id="modal-point-history" class="hidden fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4">
    <div class="modal-slam-in bg-white border-[3px] border-black shadow-[8px_8px_0px_0px_#000000] max-w-lg w-full p-5 sm:p-6 relative max-h-[85vh] flex flex-col">
        <button 
            type="button" 
            onclick="closeHistoryModal()" 
            class="btn-smooth absolute top-3.5 right-3.5 w-7 h-7 bg-white hover:bg-[#CA2C2A] hover:text-white border-2 border-black flex items-center justify-center text-xs font-black cursor-pointer"
        >
            ✕
        </button>

        <div class="flex items-center gap-3 pb-3.5 border-b-2 border-black shrink-0">
            <div class="w-10 h-10 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="min-w-0 pr-6">
                <span class="bg-[#1A467C] text-white text-[10px] font-black uppercase px-1.5 py-0.5 border border-black">RIWAYAT & KOREKSI POINT</span>
                <h3 id="history-modal-title" class="text-sm sm:text-base font-black uppercase text-gray-950 truncate mt-0.5">Riwayat Point</h3>
            </div>
        </div>

        <div id="history-modal-body" class="py-4 overflow-y-auto space-y-2.5 flex-1">
            <p class="text-center text-xs font-bold text-gray-500 py-8">Memuat riwayat...</p>
        </div>

        <div class="pt-3 border-t-2 border-black flex items-center justify-between gap-2 shrink-0">
            <span class="text-[11px] font-bold text-gray-500">Klik tombol <strong>Hapus</strong> jika ada point yang salah input.</span>
            <button 
                type="button" 
                onclick="closeHistoryModal()" 
                class="btn-smooth px-5 py-2 bg-[#1A467C] text-white border-2 border-black text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
            >
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    const allMahasiswaData = @json($allMahasiswaList);
    const csrfToken = '{{ csrf_token() }}';

    const modalState = {
        add: { studentId: null, currentPoint: null, selectedPoint: 0 },
        reduce: { studentId: null, currentPoint: null, selectedPoint: 0 }
    };

    function getInitials(name) {
        if (!name) return 'MHS';
        const parts = name.trim().split(/\s+/);
        if (parts.length >= 2) {
            return (parts[0][0] + parts[1][0]).toUpperCase();
        }
        return name.substring(0, 2).toUpperCase();
    }

    function ensureDefaultCategory(mode) {
        const firstBtn = document.querySelector(`.${mode}-cat-btn`);
        if (firstBtn && !document.getElementById(`${mode}-kategori-input`).value) {
            const kat = firstBtn.getAttribute('data-kategori');
            const pts = parseInt(firstBtn.getAttribute('data-point') || '0', 10);
            selectCategoryCard(mode, kat, pts, firstBtn);
        } else {
            updateCalcPreview(mode);
        }
    }

    function openPointModal(mode, keepStudent = false) {
        const modal = document.getElementById(`modal-point-${mode}`);
        if (!modal) return;

        if (!keepStudent) {
            clearSelectedStudent(mode, false);
        }
        ensureDefaultCategory(mode);
        modal.classList.remove('hidden');

        if (!keepStudent) {
            setTimeout(() => {
                document.getElementById(`${mode}-search-input`)?.focus();
            }, 60);
        }
    }

    function closePointModal(mode) {
        const modal = document.getElementById(`modal-point-${mode}`);
        if (modal) modal.classList.add('hidden');
        const dropdown = document.getElementById(`${mode}-search-dropdown`);
        if (dropdown) dropdown.classList.add('hidden');
    }

    function quickSelectStudent(mode, id, nama, nim, kelas, totalPoint) {
        selectStudentForPoint(mode, id, nama, nim, kelas, totalPoint);
        openPointModal(mode, true);
    }

    function clearSelectedStudent(mode, focusInput = true) {
        modalState[mode].studentId = null;
        modalState[mode].currentPoint = null;

        const hiddenId = document.getElementById(`${mode}-pendaftaran-id`);
        const searchWrapper = document.getElementById(`${mode}-search-wrapper`);
        const searchInput = document.getElementById(`${mode}-search-input`);
        const dropdown = document.getElementById(`${mode}-search-dropdown`);
        const selectedCard = document.getElementById(`${mode}-selected-card`);

        if (hiddenId) hiddenId.value = '';
        if (searchInput) searchInput.value = '';
        if (dropdown) dropdown.classList.add('hidden');
        if (selectedCard) selectedCard.classList.add('hidden');
        if (searchWrapper) searchWrapper.classList.remove('hidden');

        updateCalcPreview(mode);

        if (focusInput && searchInput) {
            setTimeout(() => searchInput.focus(), 30);
        }
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, ch => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
        }[ch]));
    }

    function filterStudentAutocomplete(mode) {
        const input = document.getElementById(`${mode}-search-input`);
        const dropdown = document.getElementById(`${mode}-search-dropdown`);
        if (!input || !dropdown) return;

        const q = input.value.trim().toLowerCase();
        const matches = allMahasiswaData.filter(m =>
            m.nama.toLowerCase().includes(q) ||
            m.nim.toLowerCase().includes(q) ||
            m.kelas.toLowerCase().includes(q)
        ).slice(0, 10);

        if (matches.length === 0) {
            dropdown.innerHTML = `<div class="p-3 text-xs font-bold text-gray-500 text-center">Mahasiswa tidak ditemukan</div>`;
            dropdown.classList.remove('hidden');
            return;
        }

        dropdown.innerHTML = matches.map((m, idx) => `
            <div 
                data-student-idx="${idx}"
                class="px-3.5 py-2.5 hover:bg-blue-50 cursor-pointer flex items-center justify-between gap-2 transition"
            >
                <div class="min-w-0">
                    <p class="font-black text-xs text-gray-950 truncate">${escapeHtml(m.nama)}</p>
                    <p class="font-mono text-[11px] font-bold text-[#1A467C]">${escapeHtml(m.nim)} • ${escapeHtml(m.kelas)}</p>
                </div>
                <span class="shrink-0 bg-green-100 border border-black px-2 py-0.5 font-mono font-black text-[11px] text-[#15803d]">
                    ${Number(m.total_point) || 0} Pts
                </span>
            </div>
        `).join('');

        dropdown.querySelectorAll('[data-student-idx]').forEach(el => {
            el.addEventListener('click', () => {
                const m = matches[Number(el.dataset.studentIdx)];
                if (m) {
                    selectStudentForPoint(mode, m.id, m.nama, m.nim, m.kelas, m.total_point || 0);
                }
            });
        });

        dropdown.classList.remove('hidden');
    }

    function selectStudentForPoint(mode, id, nama, nim, kelas, totalPoint) {
        modalState[mode].studentId = id;
        modalState[mode].currentPoint = parseInt(totalPoint || 0, 10);

        const hiddenId = document.getElementById(`${mode}-pendaftaran-id`);
        const searchWrapper = document.getElementById(`${mode}-search-wrapper`);
        const dropdown = document.getElementById(`${mode}-search-dropdown`);
        const selectedCard = document.getElementById(`${mode}-selected-card`);

        if (hiddenId) hiddenId.value = id;
        if (dropdown) dropdown.classList.add('hidden');
        if (searchWrapper) searchWrapper.classList.add('hidden');

        if (selectedCard) {
            document.getElementById(`${mode}-selected-initials`).textContent = getInitials(nama);
            document.getElementById(`${mode}-selected-nama`).textContent = nama;
            document.getElementById(`${mode}-selected-meta`).textContent = `${nim} • ${kelas}`;
            document.getElementById(`${mode}-selected-pts`).textContent = `${totalPoint} Pts`;
            selectedCard.classList.remove('hidden');
        }

        updateCalcPreview(mode);
    }

    function selectCategoryCard(mode, kategori, point, btnEl) {
        const isCustom = kategori === 'Custom Point';
        const customWrapper = document.getElementById(`${mode}-custom-point-wrapper`);
        const customInput = document.getElementById(`${mode}-custom-point-input`);

        if (customWrapper && customInput) {
            if (isCustom) {
                customWrapper.classList.remove('hidden');
                customInput.required = true;
                modalState[mode].selectedPoint = Math.max(0, Math.min(100, parseInt(customInput.value || '0', 10)));
            } else {
                customWrapper.classList.add('hidden');
                customInput.required = false;
                modalState[mode].selectedPoint = parseInt(point || 0, 10);
            }
        } else {
            modalState[mode].selectedPoint = parseInt(point || 0, 10);
        }

        const hiddenKat = document.getElementById(`${mode}-kategori-input`);
        if (hiddenKat) hiddenKat.value = kategori;

        const buttons = document.querySelectorAll(`.${mode}-cat-btn`);
        buttons.forEach(btn => {
            const pill = btn.querySelector('.cat-pill');
            const extraSpan = btn.dataset.kategori === 'Custom Point' ? ' col-span-2 flex items-center justify-between gap-2' : ' flex flex-col justify-between gap-1.5';
            if (mode === 'add') {
                btn.className = `add-cat-btn${extraSpan} text-left p-2.5 border-2 border-black bg-[#F8FBFE] hover:bg-green-50 text-gray-950 transition cursor-pointer shadow-[2px_2px_0px_0px_#000000]`;
                if (pill) pill.className = 'cat-pill inline-block self-start px-2 py-0.5 text-[11px] font-mono font-black border border-black bg-green-100 text-[#15803d]';
            } else {
                btn.className = `reduce-cat-btn${extraSpan} text-left p-2.5 border-2 border-black bg-[#F8FBFE] hover:bg-red-50 text-gray-950 transition cursor-pointer shadow-[2px_2px_0px_0px_#000000]`;
                if (pill) pill.className = 'cat-pill inline-block self-start px-2 py-0.5 text-[11px] font-mono font-black border border-black bg-red-100 text-[#CA2C2A]';
            }
        });

        if (btnEl) {
            const activePill = btnEl.querySelector('.cat-pill');
            const extraSpan = btnEl.dataset.kategori === 'Custom Point' ? ' col-span-2 flex items-center justify-between gap-2' : ' flex flex-col justify-between gap-1.5';
            if (mode === 'add') {
                btnEl.className = `add-cat-btn${extraSpan} text-left p-2.5 border-2 border-black bg-[#15803d] text-white transition cursor-pointer shadow-[3px_3px_0px_0px_#000000] -translate-y-0.5`;
                if (activePill) activePill.className = 'cat-pill inline-block self-start px-2 py-0.5 text-[11px] font-mono font-black border border-black bg-white text-[#15803d]';
            } else {
                btnEl.className = `reduce-cat-btn${extraSpan} text-left p-2.5 border-2 border-black bg-[#CA2C2A] text-white transition cursor-pointer shadow-[3px_3px_0px_0px_#000000] -translate-y-0.5`;
                if (activePill) activePill.className = 'cat-pill inline-block self-start px-2 py-0.5 text-[11px] font-mono font-black border border-black bg-white text-[#CA2C2A]';
            }
        }

        updateCalcPreview(mode);
    }

    function onCustomPointInput(mode, val) {
        const parsed = parseInt(val || '0', 10);
        modalState[mode].selectedPoint = isNaN(parsed) ? 0 : Math.max(0, Math.min(100, parsed));
        updateCalcPreview(mode);
    }

    function updateCalcPreview(mode) {
        const preview = document.getElementById(`${mode}-calc-preview`);
        if (!preview) return;

        const delta = modalState[mode].selectedPoint || 0;
        const cur = modalState[mode].currentPoint;

        if (cur === null || cur === undefined) {
            preview.textContent = mode === 'add' ? `+${delta} Pts (Pilih mahasiswa)` : `-${delta} Pts (Pilih mahasiswa)`;
            return;
        }

        if (mode === 'add') {
            const next = cur + delta;
            preview.textContent = `${cur} Pts ➔ ${next} Pts (+${delta})`;
        } else {
            const next = Math.max(0, cur - delta);
            preview.textContent = `${cur} Pts ➔ ${next} Pts (-${delta})`;
        }
    }

    function validatePointForm(mode) {
        const hiddenId = document.getElementById(`${mode}-pendaftaran-id`);
        if (!hiddenId || !hiddenId.value) {
            alert('Silakan pilih mahasiswa terlebih dahulu!');
            document.getElementById(`${mode}-search-input`)?.focus();
            return false;
        }
        return true;
    }

    async function openHistoryModal(id, nama, nim) {
        const modal = document.getElementById('modal-point-history');
        const title = document.getElementById('history-modal-title');
        const body = document.getElementById('history-modal-body');
        if (!modal || !title || !body) return;

        title.textContent = `${nama} (${nim})`;
        body.innerHTML = `<p class="text-center text-xs font-bold text-gray-500 py-8">Memuat riwayat point...</p>`;
        modal.classList.remove('hidden');

        try {
            const res = await fetch(`{{ url('/admin/poin') }}/${id}/riwayat`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            if (!data.success || !data.histories || data.histories.length === 0) {
                body.innerHTML = `
                    <div class="p-6 text-center bg-gray-50 border-2 border-dashed border-gray-300">
                        <p class="text-xs font-bold text-gray-500">Belum ada riwayat point untuk <strong>${escapeHtml(nama)}</strong>.</p>
                    </div>
                `;
                return;
            }

            body.innerHTML = data.histories.map((h, idx) => {
                const isAdd = h.tipe === 'tambah';
                const jumlah = Number(h.jumlah) || 0;
                const deleteUrl = `{{ url('/admin/poin/riwayat') }}/${Number(h.id) || 0}`;
                return `
                    <div class="p-3 border-2 border-black ${isAdd ? 'bg-green-50/70' : 'bg-red-50/70'} shadow-[2px_2px_0px_0px_#000000] flex items-center justify-between gap-3">
                        <div class="flex items-start gap-2.5 min-w-0">
                            <span class="w-8 h-8 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 ${isAdd ? 'bg-[#15803d] text-white' : 'bg-[#CA2C2A] text-white'}">
                                ${isAdd ? '+' : '-'}${jumlah}
                            </span>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="font-black text-xs uppercase text-gray-950">${escapeHtml(h.kategori)}</span>
                                    <span class="text-[10px] font-mono font-semibold text-gray-500">• ${escapeHtml(h.tanggal)}</span>
                                </div>
                                ${h.keterangan ? `<p class="text-xs font-semibold text-gray-700 mt-0.5 break-words">${escapeHtml(h.keterangan)}</p>` : ''}
                                <p class="text-[10px] font-mono text-gray-500 mt-0.5">Oleh: ${escapeHtml(h.created_by)}</p>
                            </div>
                        </div>
                        <form action="${deleteUrl}" method="POST" data-history-idx="${idx}">
                            <input type="hidden" name="_token" value="${escapeHtml(csrfToken)}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button 
                                type="submit" 
                                class="btn-smooth px-2.5 py-1.5 bg-white hover:bg-[#CA2C2A] text-[#CA2C2A] hover:text-white border-2 border-black text-[10px] font-black uppercase cursor-pointer shrink-0 shadow-[1px_1px_0px_0px_#000000]"
                                title="Batalkan & Hapus Riwayat Point Ini"
                            >
                                Hapus
                            </button>
                        </form>
                    </div>
                `;
            }).join('');

            body.querySelectorAll('form[data-history-idx]').forEach(form => {
                form.addEventListener('submit', (e) => {
                    const h = data.histories[Number(form.dataset.historyIdx)];
                    const isAdd = h && h.tipe === 'tambah';
                    const jumlah = Number(h?.jumlah) || 0;
                    const kategori = h?.kategori ?? '';
                    if (!confirm(`Hapus riwayat ${kategori} (${isAdd ? '+' : '-'}${jumlah} Pts) ini? Total point mahasiswa akan otomatis disesuaikan.`)) {
                        e.preventDefault();
                    }
                });
            });
        } catch (err) {
            body.innerHTML = `<p class="text-center text-xs font-bold text-[#CA2C2A] py-6">Gagal memuat riwayat point.</p>`;
        }
    }

    function closeHistoryModal() {
        const modal = document.getElementById('modal-point-history');
        if (modal) modal.classList.add('hidden');
    }

    // Close autocomplete dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#add-search-wrapper')) {
            document.getElementById('add-search-dropdown')?.classList.add('hidden');
        }
        if (!e.target.closest('#reduce-search-wrapper')) {
            document.getElementById('reduce-search-dropdown')?.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closePointModal('add');
            closePointModal('reduce');
            closeHistoryModal();
        }
    });
</script>
@endsection

