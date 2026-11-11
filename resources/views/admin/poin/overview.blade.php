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
                    ['rank' => 2, 'label' => 'PERINGKAT #2', 'bg' => 'bg-sky-100', 'badgeBg' => 'bg-[#2A82C6] text-white', 'mhs' => $second],
                    ['rank' => 1, 'label' => 'PERINGKAT #1', 'bg' => 'bg-amber-300', 'badgeBg' => 'bg-black text-amber-300', 'mhs' => $first],
                    ['rank' => 3, 'label' => 'PERINGKAT #3', 'bg' => 'bg-orange-200', 'badgeBg' => 'bg-[#CA2C2A] text-white', 'mhs' => $third],
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6 items-stretch pt-1">
                @foreach ($podiumItems as $pod)
                    @if ($pod['mhs'])
                        @php $mhs = $pod['mhs']; @endphp
                        <div class="card-interactive {{ $pod['bg'] }} border-2 border-black p-4 sm:p-5 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between gap-4 {{ $pod['rank'] === 1 ? 'order-1 md:order-2' : ($pod['rank'] === 2 ? 'order-2 md:order-1' : 'order-3') }}">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-12 h-12 bg-white text-[#1A467C] border-2 border-black flex items-center justify-center font-black text-sm shrink-0 overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                                    @if ($mhs->avatarUrl())
                                        <img src="{{ $mhs->avatarUrl() }}" alt="Avatar {{ $mhs->nama }}" class="w-full h-full object-cover">
                                    @else
                                        {{ $mhs->initials() }}
                                    @endif
                                </div>
                                <div class="min-w-0 space-y-1">
                                    <span class="inline-block px-2 py-0.5 text-[9px] font-black uppercase border border-black {{ $pod['badgeBg'] }}">
                                        {{ $pod['label'] }}
                                    </span>
                                    <h5 class="font-black text-xs sm:text-sm text-gray-950 uppercase truncate">{{ $mhs->nama }}</h5>
                                    <p class="font-mono text-[11px] font-bold text-gray-800">{{ $mhs->nim }} • {{ $mhs->kelas }}</p>
                                </div>
                            </div>
                            <div class="bg-white border-2 border-black px-3 py-2 shadow-[2px_2px_0px_0px_#000000] text-center shrink-0">
                                <span class="font-mono font-black text-base sm:text-lg text-gray-950 block leading-none">{{ (int) $mhs->total_point }}</span>
                                <span class="text-[9px] font-black uppercase text-[#1A467C] mt-0.5 block">Pts</span>
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

        <form action="{{ route('admin.poin.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="tipe" value="tambah">
            <input type="hidden" name="pendaftaran_id" id="add-pendaftaran-id" required>

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
                <span class="bg-[#1A467C] text-white text-[10px] font-black uppercase px-1.5 py-0.5 border border-black">RIWAYAT POINT</span>
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
                        <p class="text-xs font-bold text-gray-500">Belum ada riwayat point untuk <strong>${nama}</strong>.</p>
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
                                title="Hapus riwayat point ini"
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

