@extends('admin.layouts.app')

@section('title', 'Kelola Point Mahasiswa')
@section('header_title', 'Sistem Point Keaktifan Mahasiswa')

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
    <!-- 2. 3 KARTU STATISTIK (Neubrutalism Style)                      -->
    <!-- ============================================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <!-- Stat 1: Total Mahasiswa -->
        <div class="snap-in-2 card-interactive bg-white border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between">
            <div>
                <span class="text-[11px] font-black uppercase tracking-wider text-[#1A467C] block">Total Mahasiswa</span>
                <span class="text-2xl sm:text-3xl font-black text-gray-950 font-mono mt-1 block">{{ number_format($totalMahasiswa) }}</span>
            </div>
            <div class="w-10 h-10 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <!-- Stat 2: Total Point Diberikan -->
        <div class="snap-in-3 card-interactive bg-white border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between">
            <div>
                <span class="text-[11px] font-black uppercase tracking-wider text-[#15803d] block">Total Point Aktif</span>
                <span class="text-2xl sm:text-3xl font-black text-[#15803d] font-mono mt-1 block">{{ number_format($totalPointDiberikan) }} <span class="text-xs font-bold uppercase">Pts</span></span>
            </div>
            <div class="w-10 h-10 bg-amber-400 text-black border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
        </div>

        <!-- Stat 3: Transaksi Hari Ini -->
        <div class="snap-in-4 card-interactive bg-white border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between">
            <div>
                <span class="text-[11px] font-black uppercase tracking-wider text-[#1A467C] block">Transaksi Hari Ini</span>
                <span class="text-2xl sm:text-3xl font-black text-gray-950 font-mono mt-1 block">{{ number_format($transaksiHariIni) }}</span>
            </div>
            <div class="w-10 h-10 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 3. FILTER & PENCARIAN BAR                                      -->
    <!-- ============================================================== -->
    <div class="snap-in-5 bg-white border-2 border-black p-3 sm:p-4 shadow-[4px_4px_0px_0px_#000000]">
        <form action="{{ route('admin.poin.index') }}" method="GET" class="flex flex-col md:flex-row items-stretch gap-2.5">
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
                        href="{{ route('admin.poin.index') }}" 
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

        <form action="{{ route('admin.poin.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="tipe" value="tambah">
            <input type="hidden" name="pendaftaran_id" id="add-pendaftaran-id" required>

            <!-- Live Search Autocomplete Mahasiswa -->
            <div class="relative" id="add-search-wrapper">
                <label class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                    Cari Mahasiswa (NIM / Nama) <span class="text-[#CA2C2A]">*</span>
                </label>
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
                <div id="add-selected-info" class="hidden mt-2 p-2 bg-green-50 border-2 border-[#15803d] text-xs font-bold text-green-950"></div>
            </div>

            <!-- Pilihan Kategori Aktivitas -->
            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                    Aktivitas / Kategori <span class="text-[#CA2C2A]">*</span>
                </label>
                <select 
                    name="kategori" 
                    id="add-kategori-select" 
                    required 
                    onchange="updateAutoPointBadge('add')"
                    class="w-full bg-[#F8FBFE] border-2 border-black px-3 py-2.5 text-xs sm:text-sm font-bold focus:bg-white focus:outline-none cursor-pointer"
                >
                    <option value="" data-point="0">-- PILIH AKTIVITAS --</option>
                    @foreach ($kategoriTambah as $key => $info)
                        <option value="{{ $key }}" data-point="{{ $info['point'] }}">{{ $info['label'] }}</option>
                    @endforeach
                </select>

                <div class="mt-2 p-2.5 bg-green-50 border-2 border-black flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-700 uppercase">Nilai Point Otomatis:</span>
                    <span id="add-point-badge" class="font-mono font-black text-base text-[#15803d]">+0 Point</span>
                </div>
            </div>

            <!-- Keterangan Tambahan -->
            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                    Keterangan Tambahan (Opsional)
                </label>
                <textarea 
                    name="keterangan" 
                    rows="2" 
                    placeholder="Contoh: Menjawab pertanyaan sesi diskusi materi..."
                    class="w-full bg-[#F8FBFE] border-2 border-black p-2.5 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none"
                ></textarea>
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
                    + Tambah Point Sekarang
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

        <form action="{{ route('admin.poin.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="tipe" value="kurang">
            <input type="hidden" name="pendaftaran_id" id="reduce-pendaftaran-id" required>

            <!-- Live Search Autocomplete Mahasiswa -->
            <div class="relative" id="reduce-search-wrapper">
                <label class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                    Cari Mahasiswa (NIM / Nama) <span class="text-[#CA2C2A]">*</span>
                </label>
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
                <div id="reduce-selected-info" class="hidden mt-2 p-2 bg-red-50 border-2 border-[#CA2C2A] text-xs font-bold text-red-950"></div>
            </div>

            <!-- Pilihan Kategori Pengurangan -->
            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                    Kategori Pengurangan <span class="text-[#CA2C2A]">*</span>
                </label>
                <select 
                    name="kategori" 
                    id="reduce-kategori-select" 
                    required 
                    onchange="updateAutoPointBadge('reduce')"
                    class="w-full bg-[#F8FBFE] border-2 border-black px-3 py-2.5 text-xs sm:text-sm font-bold focus:bg-white focus:outline-none cursor-pointer"
                >
                    <option value="" data-point="0">-- PILIH JENIS PELANGGARAN --</option>
                    @foreach ($kategoriKurang as $key => $info)
                        <option value="{{ $key }}" data-point="{{ $info['point'] }}">{{ $info['label'] }}</option>
                    @endforeach
                </select>

                <div class="mt-2 p-2.5 bg-red-50 border-2 border-black flex items-center justify-between">
                    <span class="text-xs font-bold text-red-900 uppercase">Pengurangan Point:</span>
                    <span id="reduce-point-badge" class="font-mono font-black text-base text-[#CA2C2A]">-0 Point</span>
                </div>
            </div>

            <!-- Alasan Detail (Wajib) -->
            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                    Alasan Detail <span class="text-[#CA2C2A]">*</span>
                </label>
                <textarea 
                    name="keterangan" 
                    rows="2" 
                    required
                    placeholder="Tulis alasan pengurangan point..."
                    class="w-full bg-[#F8FBFE] border-2 border-black p-2.5 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none"
                ></textarea>
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
                    - Kurangi Point Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 3: RIWAYAT POINT MAHASISWA                               -->
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
                <span class="bg-[#1A467C] text-white text-[10px] font-black uppercase px-1.5 py-0.5 border border-black">HISTORI TRANSAKSI</span>
                <h3 id="history-modal-title" class="text-sm sm:text-base font-black uppercase text-gray-950 truncate mt-0.5">Riwayat Point</h3>
            </div>
        </div>

        <div id="history-modal-body" class="py-4 overflow-y-auto space-y-2.5 flex-1">
            <p class="text-center text-xs font-bold text-gray-500 py-8">Memuat riwayat...</p>
        </div>

        <div class="pt-3 border-t-2 border-black flex justify-end shrink-0">
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

    function openPointModal(mode) {
        const modal = document.getElementById(`modal-point-${mode}`);
        if (modal) modal.classList.remove('hidden');
    }

    function closePointModal(mode) {
        const modal = document.getElementById(`modal-point-${mode}`);
        if (modal) modal.classList.add('hidden');
        const dropdown = document.getElementById(`${mode}-search-dropdown`);
        if (dropdown) dropdown.classList.add('hidden');
    }

    function quickSelectStudent(mode, id, nama, nim, kelas, totalPoint) {
        selectStudentForPoint(mode, id, nama, nim, kelas, totalPoint);
        openPointModal(mode);
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

        dropdown.innerHTML = matches.map(m => {
            const safeNama = m.nama.replace(/'/g, "\\'");
            const safeKelas = m.kelas.replace(/'/g, "\\'");
            return `
                <div 
                    onclick="selectStudentForPoint('${mode}', ${m.id}, '${safeNama}', '${m.nim}', '${safeKelas}', ${m.total_point || 0})"
                    class="px-3.5 py-2.5 hover:bg-blue-50 cursor-pointer flex items-center justify-between gap-2 transition"
                >
                    <div class="min-w-0">
                        <p class="font-black text-xs text-gray-950 truncate">${m.nama}</p>
                        <p class="font-mono text-[11px] font-bold text-[#1A467C]">${m.nim} • ${m.kelas}</p>
                    </div>
                    <span class="shrink-0 bg-green-100 border border-black px-2 py-0.5 font-mono font-black text-[11px] text-[#15803d]">
                        ${m.total_point || 0} pts
                    </span>
                </div>
            `;
        }).join('');

        dropdown.classList.remove('hidden');
    }

    function selectStudentForPoint(mode, id, nama, nim, kelas, totalPoint) {
        const hiddenId = document.getElementById(`${mode}-pendaftaran-id`);
        const input = document.getElementById(`${mode}-search-input`);
        const dropdown = document.getElementById(`${mode}-search-dropdown`);
        const info = document.getElementById(`${mode}-selected-info`);

        if (hiddenId) hiddenId.value = id;
        if (input) input.value = `${nama} (${nim})`;
        if (dropdown) dropdown.classList.add('hidden');
        if (info) {
            info.innerHTML = `✓ Terpilih: <strong>${nama}</strong> (${nim} • ${kelas}) — Point saat ini: <strong>${totalPoint} Pts</strong>`;
            info.classList.remove('hidden');
        }
    }

    function updateAutoPointBadge(mode) {
        const select = document.getElementById(`${mode}-kategori-select`);
        const badge = document.getElementById(`${mode}-point-badge`);
        if (!select || !badge) return;

        const opt = select.options[select.selectedIndex];
        const pts = opt ? (opt.getAttribute('data-point') || '0') : '0';
        badge.textContent = mode === 'add' ? `+${pts} Point` : `-${pts} Point`;
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
                        <p class="text-xs font-bold text-gray-500">Belum ada riwayat transaksi point untuk <strong>${nama}</strong>.</p>
                    </div>
                `;
                return;
            }

            body.innerHTML = data.histories.map(h => {
                const isAdd = h.tipe === 'tambah';
                const deleteUrl = `{{ url('/admin/poin/riwayat') }}/${h.id}`;
                return `
                    <div class="p-3 border-2 border-black ${isAdd ? 'bg-green-50/70' : 'bg-red-50/70'} shadow-[2px_2px_0px_0px_#000000] flex items-start justify-between gap-3">
                        <div class="flex items-start gap-2.5 min-w-0">
                            <span class="w-7 h-7 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 ${isAdd ? 'bg-[#15803d] text-white' : 'bg-[#CA2C2A] text-white'}">
                                ${isAdd ? '+' : '-'}${h.jumlah}
                            </span>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="font-black text-xs uppercase text-gray-950">${h.kategori}</span>
                                    <span class="text-[10px] font-mono font-semibold text-gray-500">• ${h.tanggal}</span>
                                </div>
                                ${h.keterangan ? `<p class="text-xs font-semibold text-gray-700 mt-0.5 break-words">${h.keterangan}</p>` : ''}
                                <p class="text-[10px] font-mono text-gray-500 mt-0.5">Oleh: ${h.created_by}</p>
                            </div>
                        </div>
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Hapus item riwayat point ini? Total point mahasiswa akan dihitung ulang.')">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button 
                                type="submit" 
                                class="btn-smooth w-7 h-7 bg-white hover:bg-[#CA2C2A] text-gray-700 hover:text-white border border-black flex items-center justify-center text-xs font-black cursor-pointer shrink-0"
                                title="Batalkan / Hapus transaksi ini"
                            >
                                ✕
                            </button>
                        </form>
                    </div>
                `;
            }).join('');
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

