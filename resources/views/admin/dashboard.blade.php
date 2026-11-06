@extends('admin.layouts.app')

@section('title', 'Data Pendaftar')
@section('header_title', 'Data Pendaftar POKJA')

@section('content')
<style>
    /* Neubrutalism Physical Spring Slam & Snap (100% Solid, Zero Opacity Fade) */
    @keyframes brutalSlamIn {
        0% {
            transform: translateY(-90px) scale(0.82);
        }
        55% {
            transform: translateY(14px) scale(1.04);
        }
        80% {
            transform: translateY(-5px) scale(0.99);
        }
        100% {
            transform: translateY(0) scale(1);
        }
    }

    @keyframes brutalSnapOut {
        0% {
            transform: translateY(0) scale(1);
        }
        30% {
            transform: translateY(8px) scale(1.02);
        }
        100% {
            transform: translateY(-130px) scale(0.7);
        }
    }

    @keyframes bulkBarDrop {
        0% {
            transform: translateY(-22px) scaleY(0.75);
        }
        65% {
            transform: translateY(4px) scaleY(1.02);
        }
        100% {
            transform: translateY(0) scaleY(1);
        }
    }

    .modal-slam-in {
        animation: brutalSlamIn 0.32s cubic-bezier(0.2, 0.9, 0.3, 1.25) both !important;
    }

    .modal-snap-out {
        animation: brutalSnapOut 0.2s cubic-bezier(0.4, 0, 1, 1) both !important;
    }

    .bulk-bar-drop {
        animation: bulkBarDrop 0.28s cubic-bezier(0.34, 1.56, 0.64, 1) both !important;
    }

    /* Interactive Physical Tactile Hover & Pressed Effects */
    .card-interactive {
        transition: transform 0.15s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.15s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .card-interactive:hover {
        transform: translate(-3px, -3px);
        box-shadow: 6px 6px 0px 0px #000000 !important;
    }

    .btn-smooth {
        transition: transform 0.12s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.12s cubic-bezier(0.2, 0.8, 0.2, 1), background-color 0.15s ease;
    }
    .btn-smooth:hover {
        transform: translate(-2px, -2px);
    }
    .btn-smooth:active {
        transform: translate(2px, 2px);
        box-shadow: 0px 0px 0px 0px #000000 !important;
    }
</style>

<div class="space-y-4 sm:space-y-6">

    <!-- ============================================================== -->
    <!-- 2 STAT CARDS (Neubrutalism Responsive Minimalist Style)        -->
    <!-- ============================================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
        <!-- Card 1: Total Pendaftar -->
        <div class="snap-in-1 card-interactive bg-white border-2 border-[#000000] p-4 sm:p-5 shadow-[4px_4px_0px_0px_#000000] flex flex-col justify-between cursor-default">
            <div class="flex items-center justify-between gap-1">
                <span class="text-xs font-black uppercase tracking-wider text-[#1A467C]">Total Pendaftar</span>
                <div class="w-7 h-7 sm:w-8 sm:h-8 bg-[#2A82C6] border-2 border-[#000000] flex items-center justify-center text-white shadow-[1px_1px_0px_0px_#000000]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-black text-gray-950 font-mono" id="stat-total-pendaftar">{{ number_format($totalPendaftar) }}</span>
                <span class="text-[10px] font-black text-[#1A467C] bg-blue-50 border border-[#2A82C6] px-2 py-0.5 uppercase">Mahasiswa</span>
            </div>
        </div>

        <!-- Card 2: Pendaftar Hari Ini -->
        <div class="snap-in-2 card-interactive bg-white border-2 border-[#000000] p-4 sm:p-5 shadow-[4px_4px_0px_0px_#000000] flex flex-col justify-between cursor-default">
            <div class="flex items-center justify-between gap-1">
                <span class="text-xs font-black uppercase tracking-wider text-[#1A467C]">Daftar Hari Ini</span>
                <div class="w-7 h-7 sm:w-8 sm:h-8 bg-[#F59E0B] border-2 border-[#000000] flex items-center justify-center text-black shadow-[1px_1px_0px_0px_#000000]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-black text-gray-950 font-mono">{{ number_format($pendaftarHariIni) }}</span>
                <span class="text-[10px] font-black text-[#B45309] bg-amber-50 border border-[#F59E0B] px-2 py-0.5 uppercase">Hari Ini</span>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- ACTION & FILTER BAR (Clean, Non-overflowing Neubrutalism)      -->
    <!-- ============================================================== -->
    <div class="snap-in-5 bg-white border-2 border-[#000000] p-3 sm:p-4 shadow-[4px_4px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000]">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="space-y-3">
            
            <!-- Row 1: Search Input & Filter Fields -->
            <div class="flex flex-col md:flex-row items-stretch gap-2 sm:gap-3">
                <!-- Search Input with SVG Search Icon -->
                <div class="flex-1 relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}"
                        placeholder="Cari Nama, NIM, Email, No. WA..."
                        class="w-full bg-[#F8FBFE] border-2 border-[#000000] text-gray-900 text-xs sm:text-sm font-semibold px-3 py-2 sm:py-2.5 pl-8 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#000000] transition"
                    >
                    <svg class="w-4 h-4 text-gray-500 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Filter: Kelas (Neubrutalism Custom Chevron) -->
                <div class="relative w-full sm:w-56">
                    <select 
                        name="kelas" 
                        class="w-full bg-[#F8FBFE] border-2 border-[#000000] text-gray-950 text-xs font-bold font-mono px-3 py-2 sm:py-2.5 pr-8 focus:bg-white focus:outline-none focus:shadow-[2px_2px_0px_0px_#000000] transition cursor-pointer appearance-none"
                    >
                        <option value="">-- SEMUA KELAS --</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k }}" {{ $kelasFilter == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-[#000000]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Submit Filter & Reset -->
                <div class="flex items-center gap-1.5 shrink-0">
                    <button 
                        type="submit" 
                        class="btn-smooth px-4 py-2 sm:py-2.5 bg-[#1A467C] hover:bg-[#0F2A4A] text-white text-xs font-black uppercase tracking-wider border-2 border-[#000000] shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                    >
                        Filter
                    </button>
                    @if ($search || $kelasFilter)
                        <a 
                            href="{{ route('admin.dashboard') }}" 
                            class="btn-smooth px-3 py-2 sm:py-2.5 border-2 border-gray-400 bg-white text-gray-700 hover:bg-gray-100 text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] text-center"
                            title="Reset Filter"
                        >
                            ✕
                        </a>
                    @endif
                </div>
            </div>

            <!-- Row 2: Action Buttons (Icon Only Neubrutalism) -->
            <div class="flex items-center justify-end gap-2 pt-1 border-t border-gray-200">
                <!-- Sync Google Sheet Button (Icon Only) -->
                <form action="{{ route('admin.pendaftar.sync') }}" method="POST" class="inline">
                    @csrf
                    <button 
                        type="submit" 
                        onclick="return confirm('Sinkronkan seluruh data di database ke Google Spreadsheet?')"
                        class="btn-smooth w-9 h-9 sm:w-10 sm:h-10 bg-white hover:bg-blue-50 text-[#1A467C] border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                        title="Sinkronkan seluruh data pendaftar ke Google Spreadsheet"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </form>

                <!-- Export Excel Button (Icon Only) -->
                <a 
                    href="{{ route('admin.pendaftar.export', ['search' => $search, 'kelas' => $kelasFilter, 'status_kehadiran' => $statusFilter]) }}" 
                    class="btn-smooth w-9 h-9 sm:w-10 sm:h-10 bg-green-700 hover:bg-green-800 text-white border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                    title="Export data ke file Excel berformat styling rapi (.xls)"
                >
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </a>
            </div>

        </form>
    </div>

    <!-- ============================================================== -->
    <!-- BULK ACTION DOCKED BAR (Neubrutalism Floating Action Bar)     -->
    <!-- ============================================================== -->
    <div id="bulk-action-bar" class="hidden p-3 bg-[#1A467C] border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] text-white flex flex-col sm:flex-row items-center justify-between gap-2.5 transition-all">
        <div class="flex items-center gap-2">
            <span class="bg-[#CA2C2A] text-white text-[10px] font-black px-2 py-0.5 border border-white">TERPILIH</span>
            <span class="text-xs font-black uppercase tracking-wider">
                <span id="selected-count">0</span> Mahasiswa Dipilih
            </span>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <button 
                type="button" 
                onclick="batalPilihSemua()"
                class="btn-smooth flex-1 sm:flex-none px-3 py-1.5 bg-white text-gray-900 border-2 border-black text-xs font-bold uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
            >
                Batal
            </button>
            <button 
                type="button" 
                onclick="confirmBulkDelete()"
                class="btn-smooth flex-1 sm:flex-none px-3.5 py-1.5 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border-2 border-white text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer flex items-center justify-center gap-1.5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>HAPUS TERPILIH</span>
            </button>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MOBILE VIEW: NATIVE APP CARDS (Static, Fit Width, No Overflow)-->
    <!-- ============================================================== -->
    <div class="snap-in-6 block lg:hidden space-y-3">
        <div class="flex items-center justify-between px-1">
            <span class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                Daftar Mahasiswa ({{ $pendaftarans->total() }})
            </span>
            <label class="flex items-center gap-1.5 text-xs font-bold text-gray-700 cursor-pointer">
                <input 
                    type="checkbox" 
                    id="select-all-mobile" 
                    onclick="toggleSelectAll(this)" 
                    class="w-4 h-4 accent-[#CA2C2A]"
                >
                <span>Pilih Semua</span>
            </label>
        </div>

        @forelse ($pendaftarans as $index => $item)
            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $item->no_telp);
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }
                $isHadir = ($item->status_kehadiran === 'hadir');
            @endphp
            <div class="card-interactive bg-white border-2 border-[#000000] shadow-[3px_3px_0px_0px_#000000] p-3.5 space-y-3" id="mobile-card-{{ $item->id }}">
                
                <!-- Card Header: Checkbox, Avatar, Name, NIM, Class Badge -->
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <input 
                            type="checkbox" 
                            class="row-checkbox w-4 h-4 accent-[#1A467C] cursor-pointer shrink-0"
                            value="{{ $item->id }}"
                            data-name="{{ $item->nama }}"
                            onchange="updateBulkBarState()"
                        >
                        <div class="w-9 h-9 bg-[#2A82C6] text-white border-2 border-[#000000] flex items-center justify-center font-black text-xs shrink-0 overflow-hidden shadow-[1px_1px_0px_0px_#000000]">
                            @if ($item->avatarUrl())
                                <img src="{{ $item->avatarUrl() }}" alt="Avatar {{ $item->nama }}" class="w-full h-full object-cover">
                            @else
                                {{ $item->initials() }}
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-black text-sm text-[#000000] leading-tight truncate">{{ $item->nama }}</h4>
                            <p class="font-mono text-xs font-bold text-[#1A467C] mt-0.5">{{ $item->nim }}</p>
                        </div>
                    </div>
                    <span class="shrink-0 bg-blue-50 border-2 border-[#1A467C] px-2 py-0.5 text-[11px] font-black font-mono text-[#1A467C] shadow-[1px_1px_0px_0px_#1A467C]">
                        {{ $item->kelas }}
                    </span>
                </div>

                <!-- Card Details: Email & WhatsApp -->
                <div class="bg-[#F8FBFE] border border-[#1A467C]/30 p-2.5 space-y-1.5 text-xs">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 shrink-0">Email:</span>
                        <span class="font-medium text-gray-900 truncate font-mono text-[11px]">{{ $item->email ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 shrink-0">WhatsApp:</span>
                        <a 
                            href="https://wa.me/{{ $cleanPhone }}" 
                            target="_blank" 
                            class="inline-flex items-center gap-1 text-[#15803d] font-bold font-mono text-xs hover:underline"
                        >
                            <span>{{ $item->no_telp }}</span>
                            <svg class="w-3 h-3 text-[#15803d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Card Footer: Action Buttons (Icon Only Neubrutalism) -->
                <div class="flex items-center justify-between gap-2 pt-2 border-t border-gray-200">
                    <span class="text-[11px] font-mono text-gray-500">
                        {{ $item->created_at ? $item->created_at->timezone('Asia/Jakarta')->format('d/m/y H:i') : '-' }}
                    </span>

                    <!-- Edit & Delete Buttons (Icon Only Neubrutalism) -->
                    <div class="flex items-center gap-1.5">
                        <a 
                            href="{{ route('admin.pendaftar.edit', $item->id) }}" 
                            class="btn-smooth w-8 h-8 bg-amber-400 hover:bg-amber-500 text-black border border-black flex items-center justify-center shadow-[1px_1px_0px_0px_#000000]"
                            title="Edit Data {{ $item->nama }}"
                        >
                            <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <button 
                            type="button"
                            onclick="confirmSingleDelete({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                            class="btn-smooth w-8 h-8 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border border-black flex items-center justify-center shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
                            title="Hapus Data {{ $item->nama }}"
                        >
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-white border-2 border-[#000000] p-8 text-center text-gray-500 font-semibold text-xs shadow-[3px_3px_0px_0px_#000000]">
                Belum ada data pendaftar yang cocok.
            </div>
        @endforelse

        <!-- Pagination Mobile -->
        @if ($pendaftarans->hasPages())
            <div class="pt-2">
                {{ $pendaftarans->links() }}
            </div>
        @endif
    </div>

    <!-- ============================================================== -->
    <!-- DESKTOP VIEW: CLEAN NEUBRUTALISM TABLE (Large Screens Only)    -->
    <!-- ============================================================== -->
    <div class="snap-in-6 hidden lg:block bg-white border-2 border-[#000000] shadow-[6px_6px_0px_0px_#000000] overflow-hidden">
        <div class="px-5 py-3 border-b-2 border-[#000000] bg-blue-50/70 flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-wider text-[#1A467C]">Daftar Mahasiswa Pendaftar</h3>
            <span class="text-xs font-bold text-[#1A467C] bg-white border-2 border-[#000000] px-2.5 py-0.5 shadow-[1px_1px_0px_0px_#000000]">
                Total: {{ $pendaftarans->total() }} Mahasiswa
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="border-b-2 border-[#000000] bg-[#1A467C] text-white uppercase font-black text-[11px] tracking-wider">
                        <th class="py-3.5 px-3 w-10 text-center">
                            <input 
                                type="checkbox" 
                                id="select-all-desktop"
                                onclick="toggleSelectAll(this)"
                                class="w-4 h-4 accent-[#CA2C2A] cursor-pointer"
                                title="Pilih Semua"
                            >
                        </th>
                        <th class="py-3.5 px-3 w-10 text-center">No</th>
                        <th class="py-3.5 px-3.5">Nama Lengkap</th>
                        <th class="py-3.5 px-3">NIM</th>
                        <th class="py-3.5 px-3.5">Email Kampus</th>
                        <th class="py-3.5 px-3 text-center">Kelas</th>
                        <th class="py-3.5 px-3">No. WhatsApp</th>
                        <th class="py-3.5 px-3 text-center">Tgl Daftar</th>
                        <th class="py-3.5 px-3 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pendaftarans as $index => $item)
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $item->no_telp);
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                        @endphp
                        <tr class="hover:bg-blue-100/60 transition-colors" id="row-pendaftar-{{ $item->id }}">
                            <td class="py-3 px-3 text-center">
                                <input 
                                    type="checkbox" 
                                    class="row-checkbox w-4 h-4 accent-[#1A467C] cursor-pointer"
                                    value="{{ $item->id }}"
                                    data-name="{{ $item->nama }}"
                                    onchange="updateBulkBarState()"
                                >
                            </td>
                            <td class="py-3 px-3 text-center text-gray-700 font-mono font-bold">
                                {{ $pendaftarans->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-3.5 font-bold text-gray-950">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 bg-[#2A82C6] text-white border-2 border-[#000000] flex items-center justify-center font-black text-xs shrink-0 overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                                        @if ($item->avatarUrl())
                                            <img src="{{ $item->avatarUrl() }}" alt="Avatar {{ $item->nama }}" class="w-full h-full object-cover">
                                        @else
                                            {{ $item->initials() }}
                                        @endif
                                    </div>
                                    <span>{{ $item->nama }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-3 text-gray-900 font-mono font-bold">
                                {{ $item->nim }}
                            </td>
                            <td class="py-3 px-3.5 text-gray-700 text-xs font-mono">
                                {{ $item->email ?? '-' }}
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="inline-block bg-blue-50 border border-[#2A82C6] px-2 py-0.5 text-xs text-[#1A467C] font-bold font-mono">
                                    {{ $item->kelas }}
                                </span>
                            </td>
                            <td class="py-3 px-3">
                                <a 
                                    href="https://wa.me/{{ $cleanPhone }}" 
                                    target="_blank" 
                                    class="inline-flex items-center gap-1 text-[#15803d] font-mono font-bold hover:underline"
                                >
                                    <span>{{ $item->no_telp }}</span>
                                </a>
                            </td>

                            <td class="py-3 px-3 text-center text-gray-600 text-xs font-mono">
                                {{ $item->created_at ? $item->created_at->timezone('Asia/Jakarta')->format('d/m/y H:i') : '-' }}
                            </td>
                            <td class="py-3 px-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit Button (Icon Only Neubrutalism) -->
                                    <a 
                                        href="{{ route('admin.pendaftar.edit', $item->id) }}" 
                                        class="btn-smooth w-7 h-7 sm:w-8 sm:h-8 bg-amber-400 hover:bg-amber-500 text-black border border-black flex items-center justify-center shadow-[1px_1px_0px_0px_#000000]"
                                        title="Edit Data {{ $item->nama }}"
                                    >
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="square" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <!-- Delete Button (Icon Only Neubrutalism) -->
                                    <button 
                                        type="button"
                                        onclick="confirmSingleDelete({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                                        class="btn-smooth w-7 h-7 sm:w-8 sm:h-8 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border border-[#901C1A] flex items-center justify-center shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
                                        title="Hapus Data {{ $item->nama }}"
                                    >
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="square" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-10 text-center text-gray-500 font-semibold text-xs sm:text-sm">
                                Belum ada data pendaftar yang cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pendaftarans->hasPages())
            <div class="px-5 py-3 border-t-2 border-[#000000] bg-white">
                {{ $pendaftarans->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Floating Toast Container -->
<div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

<!-- ============================================================== -->
<!-- CUSTOM NEUBRUTALISM CONFIRMATION MODAL                         -->
<!-- ============================================================== -->
<div id="modal-delete" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
    <div id="modal-delete-box" class="bg-white border-2 border-[#000000] shadow-[8px_8px_0px_0px_#000000] max-w-md w-full p-5 sm:p-6 relative">
        
        <!-- Close Button -->
        <button 
            type="button" 
            onclick="closeDeleteModal()" 
            class="btn-smooth absolute top-3 right-3 p-1 border border-[#000000] text-black hover:bg-[#CA2C2A] hover:text-white cursor-pointer"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="square" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Header Icon & Title -->
        <div class="flex items-center gap-3 mb-3.5">
            <div class="w-10 h-10 bg-red-100 border-2 border-[#CA2C2A] text-[#CA2C2A] flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_#901C1A]">
                <!-- Clean SVG Alert Triangle Icon -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <span class="text-[10px] bg-[#CA2C2A] text-white font-black px-1.5 py-0.2 uppercase tracking-wider border border-[#901C1A]">PERINGATAN</span>
                <h3 class="text-base sm:text-lg font-black text-[#1A467C] uppercase tracking-tight mt-0.5">
                    Konfirmasi Hapus
                </h3>
            </div>
        </div>

        <!-- Content Description -->
        <p id="modal-delete-message" class="text-xs sm:text-sm font-semibold text-gray-800 leading-relaxed mb-3.5">
            Apakah Anda yakin ingin menghapus data ini?
        </p>

        <div class="p-2.5 bg-red-50 border border-[#CA2C2A] text-[11px] font-bold text-red-950 mb-5 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#CA2C2A] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="square" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Data yang dihapus otomatis <strong>ikut terhapus dari Google Spreadsheet</strong> secara sinkron.</span>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-2.5">
            <button 
                type="button" 
                onclick="closeDeleteModal()" 
                class="btn-smooth px-4 py-2 bg-white text-gray-900 border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
            >
                Batal
            </button>

            <!-- Single Delete Form -->
            <form id="single-delete-form" method="POST" class="inline hidden">
                @csrf
                @method('DELETE')
                <button 
                    type="submit" 
                    class="btn-smooth px-4 py-2 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border-2 border-[#901C1A] text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                >
                    YA, HAPUS SEKARANG
                </button>
            </form>

            <!-- Bulk Delete Form -->
            <form id="bulk-delete-form" action="{{ route('admin.pendaftar.bulk-destroy') }}" method="POST" class="inline hidden">
                @csrf
                <div id="bulk-delete-hidden-inputs"></div>
                <button 
                    type="submit" 
                    class="btn-smooth px-4 py-2 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border-2 border-[#901C1A] text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                >
                    YA, HAPUS SEMUA TERPILIH
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';

    // 1. Bulk Select & Checkbox Logic (Sync across mobile cards & desktop table)
    function toggleSelectAll(master) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = master.checked;
        });

        const masterM = document.getElementById('select-all-mobile');
        const masterD = document.getElementById('select-all-desktop');
        if (masterM) masterM.checked = master.checked;
        if (masterD) masterD.checked = master.checked;

        updateBulkBarState();
    }

    function updateBulkBarState() {
        const checkboxes = document.querySelectorAll('.row-checkbox:checked');
        const count = checkboxes.length;
        const bar = document.getElementById('bulk-action-bar');
        const countSpan = document.getElementById('selected-count');
        const allCheckboxes = document.querySelectorAll('.row-checkbox');

        if (countSpan) countSpan.textContent = count;

        if (count > 0) {
            if (bar.classList.contains('hidden')) {
                bar.classList.remove('hidden');
                bar.classList.add('bulk-bar-drop');
            }
        } else {
            bar.classList.add('hidden');
            bar.classList.remove('bulk-bar-drop');
        }

        const isAllSelected = (allCheckboxes.length > 0 && count === allCheckboxes.length);
        const masterM = document.getElementById('select-all-mobile');
        const masterD = document.getElementById('select-all-desktop');
        if (masterM) masterM.checked = isAllSelected;
        if (masterD) masterD.checked = isAllSelected;
    }

    function batalPilihSemua() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = false);
        const masterM = document.getElementById('select-all-mobile');
        const masterD = document.getElementById('select-all-desktop');
        if (masterM) masterM.checked = false;
        if (masterD) masterD.checked = false;
        updateBulkBarState();
    }

    // 2. Custom Neubrutalism Physical Spring Modal Handlers (100% Solid, NO Fade)
    function confirmSingleDelete(id, nama) {
        const modal = document.getElementById('modal-delete');
        const box = document.getElementById('modal-delete-box');
        const message = document.getElementById('modal-delete-message');
        const singleForm = document.getElementById('single-delete-form');
        const bulkForm = document.getElementById('bulk-delete-form');

        if (modal && box && message && singleForm && bulkForm) {
            message.innerHTML = `Apakah Anda yakin ingin menghapus data pendaftar atas nama <strong>"${nama}"</strong>?`;
            singleForm.action = `{{ url('/admin/pendaftar') }}/${id}`;
            singleForm.classList.remove('hidden');
            bulkForm.classList.add('hidden');

            box.classList.remove('modal-snap-out');
            box.classList.add('modal-slam-in');
            modal.classList.remove('hidden');
        }
    }

    function confirmBulkDelete() {
        const checkboxes = document.querySelectorAll('.row-checkbox:checked');
        const count = checkboxes.length;
        if (count === 0) return;

        const modal = document.getElementById('modal-delete');
        const box = document.getElementById('modal-delete-box');
        const message = document.getElementById('modal-delete-message');
        const singleForm = document.getElementById('single-delete-form');
        const bulkForm = document.getElementById('bulk-delete-form');
        const containerInputs = document.getElementById('bulk-delete-hidden-inputs');

        if (modal && box && message && singleForm && bulkForm && containerInputs) {
            message.innerHTML = `Apakah Anda yakin ingin menghapus <strong>${count} data mahasiswa terpilih</strong> secara massal?`;
            containerInputs.innerHTML = '';
            checkboxes.forEach(cb => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'ids[]';
                hiddenInput.value = cb.value;
                containerInputs.appendChild(hiddenInput);
            });

            singleForm.classList.add('hidden');
            bulkForm.classList.remove('hidden');

            box.classList.remove('modal-snap-out');
            box.classList.add('modal-slam-in');
            modal.classList.remove('hidden');
        }
    }

    function closeDeleteModal() {
        const modal = document.getElementById('modal-delete');
        const box = document.getElementById('modal-delete-box');
        if (modal && box && !modal.classList.contains('hidden')) {
            box.classList.remove('modal-slam-in');
            box.classList.add('modal-snap-out');
            setTimeout(() => {
                modal.classList.add('hidden');
                box.classList.remove('modal-snap-out');
            }, 200);
        }
    }

    // Close on Escape Key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDeleteModal();
    });

    // Solid Snappy Toast Notification (NO Fade)
    function showToast(message, isSuccess = true) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `pointer-events-auto px-4 py-2.5 border-2 border-black shadow-[4px_4px_0px_0px_#000000] text-xs font-black tracking-wide uppercase transition-transform duration-200 transform translate-x-full flex items-center gap-2 ${
            isSuccess 
                ? 'bg-green-700 text-white' 
                : 'bg-[#CA2C2A] text-white'
        }`;
        
        toast.innerHTML = `
            <span>${isSuccess ? '✓' : '✕'}</span>
            <span>${message}</span>
        `;
        
        container.appendChild(toast);
        
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');
        });

        setTimeout(() => {
            toast.classList.remove('translate-x-0');
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 250);
        }, 2500);
    }

    // 3. Quick Toggle Kehadiran (Updates Mobile & Desktop simultaneously with SVG Icons)
    async function toggleKehadiran(id, triggerBtn) {
        const btnM = document.getElementById(`toggle-btn-m-${id}`);
        const btnD = document.getElementById(`toggle-btn-d-${id}`);
        const iconM = document.getElementById(`btn-icon-m-${id}`);
        const iconD = document.getElementById(`btn-icon-d-${id}`);
        const labelM = document.getElementById(`label-status-m-${id}`);
        const labelD = document.getElementById(`label-status-d-${id}`);

        const statHadir = document.getElementById('stat-total-hadir');
        const statBelum = document.getElementById('stat-total-belum-hadir');

        if (btnM) btnM.disabled = true;
        if (btnD) btnD.disabled = true;

        try {
            const url = `{{ url('/admin/pendaftar') }}/${id}/toggle-kehadiran`;
            const response = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({})
            });

            const data = await response.json();

            if (response.ok && data.success) {
                const isHadir = data.is_hadir;

                const checkSvg = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="3" d="M5 13l4 4L19 7" /></svg>`;
                const crossSvg = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>`;

                const updateButtonState = (btn, icon, label) => {
                    if (!btn) return;
                    if (isHadir) {
                        btn.className = btn.className.replace(/bg-gray-\d+/g, '').replace('text-gray-700', '').replace('hover:bg-gray-300', '');
                        btn.classList.add('bg-[#15803d]', 'text-white', 'hover:bg-green-800');
                        if (icon) icon.innerHTML = checkSvg;
                        if (label) label.textContent = 'HADIR';
                    } else {
                        btn.className = btn.className.replace('bg-[#15803d]', '').replace('text-white', '').replace('hover:bg-green-800', '');
                        btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                        if (icon) icon.innerHTML = crossSvg;
                        if (label) label.textContent = 'BELUM';
                    }
                };

                updateButtonState(btnM, iconM, labelM);
                updateButtonState(btnD, iconD, labelD);

                // Update counter numbers dynamically
                if (statHadir && statBelum) {
                    let hadirCount = parseInt(statHadir.textContent.replace(/[^0-9]/g, '')) || 0;
                    let belumCount = parseInt(statBelum.textContent.replace(/[^0-9]/g, '')) || 0;

                    if (isHadir) {
                        hadirCount++;
                        belumCount = Math.max(0, belumCount - 1);
                    } else {
                        hadirCount = Math.max(0, hadirCount - 1);
                        belumCount++;
                    }

                    statHadir.textContent = hadirCount.toLocaleString();
                    statBelum.textContent = belumCount.toLocaleString();
                }

                showToast(data.message, true);
            } else {
                showToast(data.message || 'Gagal mengubah status kehadiran.', false);
            }
        } catch (error) {
            console.error(error);
            showToast('Terjadi kesalahan jaringan.', false);
        } finally {
            if (btnM) btnM.disabled = false;
            if (btnD) btnD.disabled = false;
        }
    }
</script>
@endsection
