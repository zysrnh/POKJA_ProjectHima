@extends('admin.layouts.app')

@section('title', 'Overview PointKu')
@section('header_title', 'Overview PointKu POKJA')

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

<div class="space-y-6 sm:space-y-8">

    <!-- ============================================================== -->
    <!-- 1. HEADER BANNER & TOMBOL AKSI (+ / -)                         -->
    <!-- ============================================================== -->
    <div class="snap-in-1 bg-white border-2 border-[#000000] px-4 py-3.5 sm:px-6 sm:py-4 shadow-[5px_5px_0px_0px_#000000] flex items-center justify-between gap-4">
        <h3 class="text-base sm:text-xl font-black uppercase tracking-tight text-[#1A467C]">
            POINTKU POKJA
        </h3>

        <div class="flex items-center gap-2.5 shrink-0">
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
    <!-- 2. 3 KARTU STATISTIK RINGKASAN                                 -->
    <!-- ============================================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="snap-in-2 card-interactive bg-white border-2 border-black p-4 sm:p-5 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between">
            <div>
                <span class="text-[11px] font-black uppercase tracking-wider text-[#1A467C] block">Total Mahasiswa</span>
                <span class="text-2xl sm:text-3xl font-black text-gray-950 font-mono mt-1.5 block">{{ number_format($totalMahasiswa) }}</span>
            </div>
            <div class="w-11 h-11 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <div class="snap-in-3 card-interactive bg-white border-2 border-black p-4 sm:p-5 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between">
            <div>
                <span class="text-[11px] font-black uppercase tracking-wider text-[#15803d] block">Total Point Aktif</span>
                <span class="text-2xl sm:text-3xl font-black text-[#15803d] font-mono mt-1.5 block">{{ number_format($totalPointDiberikan) }} <span class="text-xs font-bold uppercase">Pts</span></span>
            </div>
            <div class="w-11 h-11 bg-amber-400 text-black border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
        </div>

        <div class="snap-in-4 card-interactive bg-white border-2 border-black p-4 sm:p-5 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between">
            <div>
                <span class="text-[11px] font-black uppercase tracking-wider text-[#1A467C] block">Aktivitas Hari Ini</span>
                <span class="text-2xl sm:text-3xl font-black text-gray-950 font-mono mt-1.5 block">{{ number_format($transaksiHariIni) }}</span>
            </div>
            <div class="w-11 h-11 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 3. PREVIEW LEADERBOARD TOP 3 MAHASISWA                         -->
    <!-- ============================================================== -->
    <div class="snap-in-5 bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] p-5 sm:p-6 space-y-5">
        <div class="flex items-center justify-between gap-3 border-b-2 border-black pb-3.5">
            <div class="flex items-center gap-2.5">
                <span class="bg-amber-400 text-black text-xs font-black px-2.5 py-0.5 border border-black">TOP 3</span>
                <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                    Peringkat Teratas Leaderboard
                </h4>
            </div>
            <a 
                href="{{ route('admin.poin.leaderboard') }}" 
                class="btn-smooth px-3.5 py-1.5 bg-[#1A467C] hover:bg-[#2A82C6] text-white text-[11px] font-black uppercase tracking-wider border-2 border-black shadow-[2px_2px_0px_0px_#000000]"
            >
                Lihat Semua →
            </a>
        </div>

        @if ($topThree->count() > 0)
            @php
                $first = $topThree->get(0);
                $second = $topThree->get(1);
                $third = $topThree->get(2);
                $podiumItems = [
                    ['rank' => 1, 'label' => 'PERINGKAT #1', 'bg' => 'bg-amber-300', 'badgeBg' => 'bg-black text-amber-300', 'mhs' => $first],
                    ['rank' => 2, 'label' => 'PERINGKAT #2', 'bg' => 'bg-sky-100', 'badgeBg' => 'bg-[#2A82C6] text-white', 'mhs' => $second],
                    ['rank' => 3, 'label' => 'PERINGKAT #3', 'bg' => 'bg-orange-200', 'badgeBg' => 'bg-[#CA2C2A] text-white', 'mhs' => $third],
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6 items-stretch pt-3">
                @foreach ($podiumItems as $pod)
                    @if ($pod['mhs'])
                        @php $mhs = $pod['mhs']; @endphp
                        <div class="card-interactive relative {{ $pod['bg'] }} border-2 border-black px-4 pt-5 pb-4 sm:px-5 sm:pt-6 sm:pb-5 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between gap-4">
                            <!-- Badge Peringkat Menempel di Garis Atas Card -->
                            <span class="absolute -top-3 left-4 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider border-2 border-black {{ $pod['badgeBg'] }} shadow-[2px_2px_0px_0px_#000000]">
                                {{ $pod['label'] }}
                            </span>

                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-11 h-11 bg-white text-[#1A467C] border-2 border-black flex items-center justify-center font-black text-sm shrink-0 overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                                    @if ($mhs->avatarUrl())
                                        <img src="{{ $mhs->avatarUrl() }}" alt="Avatar {{ $mhs->nama }}" class="w-full h-full object-cover">
                                    @else
                                        {{ $mhs->initials() }}
                                    @endif
                                </div>
                                <div class="min-w-0 space-y-0.5">
                                    <h5 class="font-black text-xs sm:text-sm text-gray-950 uppercase truncate">{{ $mhs->nama }}</h5>
                                    <p class="font-mono text-[11px] font-bold text-gray-800">{{ $mhs->nim }} • {{ $mhs->kelas }}</p>
                                </div>
                            </div>

                            <div class="bg-white border-2 border-black px-3 py-1.5 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1 shrink-0">
                                <span class="font-mono font-black text-base sm:text-lg text-gray-950 leading-none">{{ (int) $mhs->total_point }}</span>
                                <span class="text-[10px] font-black uppercase text-[#1A467C] leading-none">Pts</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <p class="text-center text-xs font-bold text-gray-500 py-6">Belum ada data mahasiswa.</p>
        @endif
    </div>

    <!-- ============================================================== -->
    <!-- 4. GRID 2 KOLOM: RINGKASAN LIST MAHASISWA & AKTIVITAS TERBARU  -->
    <!-- ============================================================== -->
    <div class="snap-in-6 grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-start">

        <!-- KOLOM KIRI: RINGKASAN DAFTAR POINT MAHASISWA -->
        <div class="bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
            <div class="px-5 py-3.5 border-b-2 border-black bg-blue-50/70 flex items-center justify-between gap-3">
                <h4 class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                    Daftar Point Mahasiswa
                </h4>
                <a 
                    href="{{ route('admin.poin.mahasiswa') }}" 
                    class="btn-smooth px-3 py-1.5 bg-white hover:bg-[#1A467C] text-[#1A467C] hover:text-white text-[10px] font-black uppercase border-2 border-black shadow-[2px_2px_0px_0px_#000000]"
                >
                    Semua Mahasiswa →
                </a>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse ($previewMahasiswa as $idx => $item)
                    <div class="px-5 py-4 hover:bg-blue-50/40 transition flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-9 h-9 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                                @if ($item->avatarUrl())
                                    <img src="{{ $item->avatarUrl() }}" alt="Avatar {{ $item->nama }}" class="w-full h-full object-cover">
                                @else
                                    {{ $item->initials() }}
                                @endif
                            </div>
                            <div class="min-w-0 space-y-0.5">
                                <p class="font-black text-xs sm:text-sm text-gray-950 truncate">{{ $item->nama }}</p>
                                <p class="font-mono text-[11px] font-bold text-[#1A467C]">{{ $item->nim }} • {{ $item->kelas }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            <span class="bg-green-100 border border-black px-2.5 py-1 font-mono font-black text-xs text-[#15803d]">
                                ★ {{ (int) $item->total_point }}
                            </span>
                            <div class="flex items-center gap-2">
                                <button 
                                    type="button"
                                    onclick="openHistoryModal({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->nim }}')"
                                    class="btn-smooth w-8 h-8 bg-[#2A82C6] text-white border border-black flex items-center justify-center text-xs cursor-pointer shadow-[1px_1px_0px_0px_#000000]"
                                    title="Riwayat Point"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="square" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                                <button 
                                    type="button"
                                    onclick="quickSelectStudent('add', {{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->nim }}', '{{ addslashes($item->kelas) }}', {{ (int) $item->total_point }})"
                                    class="btn-smooth w-8 h-8 bg-[#15803d] text-white border border-black flex items-center justify-center font-black text-xs cursor-pointer shadow-[1px_1px_0px_0px_#000000]"
                                    title="Tambah Point"
                                >
                                    +
                                </button>
                                <button 
                                    type="button"
                                    onclick="quickSelectStudent('reduce', {{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->nim }}', '{{ addslashes($item->kelas) }}', {{ (int) $item->total_point }})"
                                    class="btn-smooth w-8 h-8 bg-[#CA2C2A] text-white border border-black flex items-center justify-center font-black text-xs cursor-pointer shadow-[1px_1px_0px_0px_#000000]"
                                    title="Kurangi Point"
                                >
                                    -
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs font-bold text-gray-500">
                        Belum ada data mahasiswa.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KOLOM KANAN: AKTIVITAS POINT TERBARU -->
        <div class="bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
            <div class="px-5 py-3.5 border-b-2 border-black bg-blue-50/70 flex items-center justify-between gap-3">
                <h4 class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                    Aktivitas Point Terbaru
                </h4>
                <a 
                    href="{{ route('admin.poin.aktivitas') }}" 
                    class="btn-smooth px-3 py-1.5 bg-white hover:bg-[#1A467C] text-[#1A467C] hover:text-white text-[10px] font-black uppercase border-2 border-black shadow-[2px_2px_0px_0px_#000000]"
                >
                    Semua Aktivitas →
                </a>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse ($recentActivities as $act)
                    @php
                        $isAdd = $act->tipe === 'tambah';
                        $mhs = $act->pendaftaran;
                    @endphp
                    <div class="px-5 py-4 hover:bg-blue-50/30 transition flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3.5 min-w-0">
                            <span class="w-9 h-9 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_#000000] {{ $isAdd ? 'bg-[#15803d] text-white' : 'bg-[#CA2C2A] text-white' }}">
                                {{ $isAdd ? '+' : '-' }}{{ (int) $act->jumlah }}
                            </span>
                            <div class="min-w-0 space-y-1">
                                <p class="font-black text-xs sm:text-sm text-gray-950 truncate">
                                    {{ $mhs->nama ?? 'Mahasiswa' }}
                                    @if ($mhs)
                                        <span class="font-mono text-[10px] font-bold text-[#1A467C]">({{ $mhs->kelas }})</span>
                                    @endif
                                </p>
                                <p class="text-[11px] font-bold text-gray-700 truncate">
                                    <span class="uppercase font-black">{{ $act->kategori }}</span>
                                    @if ($act->keterangan)
                                        — {{ $act->keterangan }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <span class="shrink-0 font-mono text-[10px] font-bold text-gray-500 pt-0.5">
                            {{ $act->created_at ? $act->created_at->timezone('Asia/Jakarta')->format('d/m H:i') : '-' }}
                        </span>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs font-bold text-gray-500">
                        Belum ada aktivitas point terbaru.
                    </div>
                @endforelse
            </div>
        </div>

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
                        ${m.total_point || 0} Pts
                    </span>
                </div>
            `;
        }).join('');

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
        modalState[mode].selectedPoint = parseInt(point || 0, 10);
        const hiddenKat = document.getElementById(`${mode}-kategori-input`);
        if (hiddenKat) hiddenKat.value = kategori;

        const buttons = document.querySelectorAll(`.${mode}-cat-btn`);
        buttons.forEach(btn => {
            const pill = btn.querySelector('.cat-pill');
            if (mode === 'add') {
                btn.className = 'add-cat-btn text-left p-2.5 border-2 border-black bg-[#F8FBFE] hover:bg-green-50 text-gray-950 transition flex flex-col justify-between gap-1.5 cursor-pointer shadow-[2px_2px_0px_0px_#000000]';
                if (pill) pill.className = 'cat-pill inline-block self-start px-2 py-0.5 text-[11px] font-mono font-black border border-black bg-green-100 text-[#15803d]';
            } else {
                btn.className = 'reduce-cat-btn text-left p-2.5 border-2 border-black bg-[#F8FBFE] hover:bg-red-50 text-gray-950 transition flex flex-col justify-between gap-1.5 cursor-pointer shadow-[2px_2px_0px_0px_#000000]';
                if (pill) pill.className = 'cat-pill inline-block self-start px-2 py-0.5 text-[11px] font-mono font-black border border-black bg-red-100 text-[#CA2C2A]';
            }
        });

        if (btnEl) {
            const activePill = btnEl.querySelector('.cat-pill');
            if (mode === 'add') {
                btnEl.className = 'add-cat-btn text-left p-2.5 border-2 border-black bg-[#15803d] text-white transition flex flex-col justify-between gap-1.5 cursor-pointer shadow-[3px_3px_0px_0px_#000000] -translate-y-0.5';
                if (activePill) activePill.className = 'cat-pill inline-block self-start px-2 py-0.5 text-[11px] font-mono font-black border border-black bg-white text-[#15803d]';
            } else {
                btnEl.className = 'reduce-cat-btn text-left p-2.5 border-2 border-black bg-[#CA2C2A] text-white transition flex flex-col justify-between gap-1.5 cursor-pointer shadow-[3px_3px_0px_0px_#000000] -translate-y-0.5';
                if (activePill) activePill.className = 'cat-pill inline-block self-start px-2 py-0.5 text-[11px] font-mono font-black border border-black bg-white text-[#CA2C2A]';
            }
        }

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
                        <p class="text-xs font-bold text-gray-500">Belum ada riwayat point untuk <strong>${nama}</strong>.</p>
                    </div>
                `;
                return;
            }

            body.innerHTML = data.histories.map(h => {
                const isAdd = h.tipe === 'tambah';
                const deleteUrl = `{{ url('/admin/poin/riwayat') }}/${h.id}`;
                return `
                    <div class="p-3 border-2 border-black ${isAdd ? 'bg-green-50/70' : 'bg-red-50/70'} shadow-[2px_2px_0px_0px_#000000] flex items-center justify-between gap-3">
                        <div class="flex items-start gap-2.5 min-w-0">
                            <span class="w-8 h-8 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 ${isAdd ? 'bg-[#15803d] text-white' : 'bg-[#CA2C2A] text-white'}">
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
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Hapus riwayat ${h.kategori} (${isAdd ? '+' : '-'}${h.jumlah} Pts) ini? Total point mahasiswa akan otomatis disesuaikan.')">
                            <input type="hidden" name="_token" value="${csrfToken}">
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
        } catch (err) {
            body.innerHTML = `<p class="text-center text-xs font-bold text-[#CA2C2A] py-6">Gagal memuat riwayat point.</p>`;
        }
    }

    function closeHistoryModal() {
        const modal = document.getElementById('modal-point-history');
        if (modal) modal.classList.add('hidden');
    }

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

