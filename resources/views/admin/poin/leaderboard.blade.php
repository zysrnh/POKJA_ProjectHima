@extends('admin.layouts.app')

@section('title', 'Leaderboard PointKu')
@section('header_title', 'Leaderboard Point Keaktifan')

@section('content')
<style>
    @keyframes podiumGrow {
        0% {
            transform: scaleY(0);
        }
        100% {
            transform: scaleY(1);
        }
    }
    .podium-bar {
        transform-origin: bottom;
        animation: podiumGrow 0.45s cubic-bezier(0.2, 0.9, 0.3, 1) both;
    }
    .podium-col:hover .podium-avatar {
        transform: translate(-2px, -3px);
        box-shadow: 5px 5px 0px 0px #000000 !important;
    }
    .podium-avatar {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
</style>

@php
    $maxPoint = max(1, (int) ($rankedMahasiswa->max('total_point') ?? 1));
@endphp

<div class="space-y-6 sm:space-y-8">

    <!-- ============================================================== -->
    <!-- 1. HEADER & FILTER KELAS                                       -->
    <!-- ============================================================== -->
    <div class="snap-in-1 bg-white border-2 border-black px-5 py-4 sm:px-6 sm:py-4 shadow-[5px_5px_0px_0px_#000000] flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-400 text-black border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000] shrink-0 font-black text-base">
                ★
            </div>
            <div>
                <h3 class="text-base sm:text-lg font-black uppercase tracking-tight text-[#1A467C]">
                    LEADERBOARD POINTKU
                </h3>
                <p class="text-[11px] font-semibold text-gray-600">
                    Podium peringkat teratas & klasemen mahasiswa berdasarkan total point keaktifan.
                </p>
            </div>
        </div>

        <form action="{{ route('admin.poin.leaderboard') }}" method="GET" class="flex items-center gap-2 shrink-0">
            <div class="relative flex-1 sm:w-48">
                <select 
                    name="kelas" 
                    onchange="this.form.submit()"
                    class="w-full bg-[#F8FBFE] border-2 border-black font-bold font-mono text-xs px-3.5 py-2.5 pr-8 appearance-none focus:bg-white focus:outline-none cursor-pointer shadow-[2px_2px_0px_0px_#000000]"
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
            @if ($kelasFilter)
                <a 
                    href="{{ route('admin.poin.leaderboard') }}" 
                    class="btn-smooth px-3 py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black text-xs font-black shadow-[2px_2px_0px_0px_#000000]"
                    title="Reset Kelas"
                >
                    ✕
                </a>
            @endif
        </form>
    </div>

    <!-- ============================================================== -->
    <!-- 2. TOP 3 PODIUM CHART BATANG VERTIKAL KE ATAS                  -->
    <!-- ============================================================== -->
    @if ($topThree->count() > 0)
        @php
            $first = $topThree->get(0);
            $second = $topThree->get(1);
            $third = $topThree->get(2);

            // Urutan Podium Batang: Kiri (#2), Tengah Paling Tinggi (#1), Kanan (#3)
            $podiumColumns = [
                [
                    'rank' => 2,
                    'label' => 'PERINGKAT #2',
                    'barBg' => 'bg-sky-200',
                    'badgeBg' => 'bg-[#2A82C6] text-white',
                    'barHeight' => 'h-32 sm:h-40',
                    'delay' => '0.08s',
                    'mhs' => $second,
                ],
                [
                    'rank' => 1,
                    'label' => 'PERINGKAT #1',
                    'barBg' => 'bg-amber-300',
                    'badgeBg' => 'bg-black text-amber-300',
                    'barHeight' => 'h-44 sm:h-56',
                    'delay' => '0s',
                    'mhs' => $first,
                ],
                [
                    'rank' => 3,
                    'label' => 'PERINGKAT #3',
                    'barBg' => 'bg-orange-200',
                    'badgeBg' => 'bg-[#CA2C2A] text-white',
                    'barHeight' => 'h-24 sm:h-32',
                    'delay' => '0.16s',
                    'mhs' => $third,
                ],
            ];
        @endphp

        <div class="snap-in-2 bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] p-5 sm:p-8">
            <div class="flex items-center justify-between gap-2 border-b-2 border-black pb-3.5 mb-6 sm:mb-8">
                <div class="flex items-center gap-2.5">
                    <span class="bg-amber-400 text-black text-xs font-black px-2.5 py-0.5 border border-black">TOP 3</span>
                    <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                        Podium Peringkat Teratas
                    </h4>
                </div>
                @if ($kelasFilter)
                    <span class="text-xs font-mono font-black bg-blue-50 text-[#1A467C] border-2 border-black px-2.5 py-0.5">
                        Kelas: {{ $kelasFilter }}
                    </span>
                @endif
            </div>

            <!-- Area Grafik Batang Podium Ke Atas -->
            <div class="max-w-3xl mx-auto pt-2">
                <div class="grid grid-cols-3 gap-3 sm:gap-8 items-end border-b-[4px] border-black px-2 sm:px-6">
                    @foreach ($podiumColumns as $col)
                        <div class="podium-col flex flex-col items-center text-center">
                            @if ($col['mhs'])
                                @php $mhs = $col['mhs']; @endphp

                                <!-- Info Mahasiswa di Atas Batang -->
                                <div class="mb-3 sm:mb-4 flex flex-col items-center w-full">
                                    <!-- Avatar Kotak -->
                                    <div class="podium-avatar relative w-12 h-12 sm:w-16 sm:h-16 bg-white text-[#1A467C] border-[3px] border-black flex items-center justify-center font-black text-sm sm:text-lg shadow-[3px_3px_0px_0px_#000000] overflow-hidden">
                                        @if ($mhs->avatarUrl())
                                            <img src="{{ $mhs->avatarUrl() }}" alt="Avatar {{ $mhs->nama }}" class="w-full h-full object-cover">
                                        @else
                                            {{ $mhs->initials() }}
                                        @endif
                                    </div>

                                    <!-- Nama & Kelas -->
                                    <h5 class="font-black text-[11px] sm:text-sm text-gray-950 uppercase tracking-tight mt-2.5 line-clamp-1 max-w-full px-1" title="{{ $mhs->nama }}">
                                        {{ $mhs->nama }}
                                    </h5>
                                    <p class="font-mono text-[10px] sm:text-xs font-bold text-[#1A467C] mt-0.5">
                                        {{ $mhs->kelas }}
                                    </p>

                                    <!-- Badge Point Sejajar 1 Baris -->
                                    <div class="mt-2 bg-white border-2 border-black px-2.5 py-1 sm:px-3.5 sm:py-1 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1">
                                        <span class="font-mono font-black text-sm sm:text-base text-gray-950 leading-none">{{ (int) $mhs->total_point }}</span>
                                        <span class="text-[9px] sm:text-[10px] font-black uppercase text-[#15803d] leading-none">Pts</span>
                                    </div>
                                </div>

                                <!-- Tiang Batang Vertikal Menjulang Ke Atas -->
                                <div 
                                    class="podium-bar w-full {{ $col['barHeight'] }} {{ $col['barBg'] }} border-t-[3px] border-x-[3px] border-black shadow-[4px_0px_0px_0px_#000000] flex flex-col items-center justify-center gap-2 py-3 sm:py-4 px-2 relative"
                                    style="animation-delay: {{ $col['delay'] }};"
                                >
                                    <span class="font-mono font-black text-3xl sm:text-5xl text-black/90 leading-none select-none">
                                        #{{ $col['rank'] }}
                                    </span>

                                    <span class="font-mono text-[9px] sm:text-[11px] font-bold text-gray-900 hidden sm:block">
                                        {{ $mhs->nim }}
                                    </span>
                                </div>
                            @else
                                <!-- Slot Kosong jika mahasiswa kurang dari 3 -->
                                <div class="w-full h-16 bg-gray-100 border-t-2 border-x-2 border-dashed border-gray-400 flex items-center justify-center text-[10px] font-bold text-gray-400">
                                    Kosong
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- ============================================================== -->
    <!-- 3. DAFTAR PERINGKAT LENGKAP MAHASISWA                          -->
    <!-- ============================================================== -->
    <div class="snap-in-3 bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
        <div class="px-5 py-3.5 border-b-2 border-black bg-blue-50/70 flex items-center justify-between gap-3">
            <h3 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                Peringkat Lengkap Mahasiswa
            </h3>
            <span class="text-xs font-bold text-[#1A467C] bg-white border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000000]">
                Total: {{ $rankedMahasiswa->count() }} Mahasiswa
            </span>
        </div>

        <div class="divide-y-2 divide-gray-200">
            @forelse ($rankedMahasiswa as $idx => $mhs)
                @php
                    $rank = $idx + 1;
                    $pct = round(((int) $mhs->total_point / $maxPoint) * 100);
                    $barColor = $rank === 1 ? 'bg-amber-400' : ($rank === 2 ? 'bg-[#2A82C6]' : ($rank === 3 ? 'bg-orange-400' : 'bg-[#15803d]'));
                @endphp
                <div class="px-5 py-4 hover:bg-blue-50/40 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <!-- Rank Number Badge -->
                        <div class="w-9 h-9 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_#000000] {{ $rank === 1 ? 'bg-amber-300 text-black' : ($rank === 2 ? 'bg-sky-200 text-black' : ($rank === 3 ? 'bg-orange-200 text-black' : 'bg-gray-100 text-gray-800')) }}">
                            #{{ $rank }}
                        </div>

                        <!-- Student Avatar -->
                        <div class="w-10 h-10 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                            @if ($mhs->avatarUrl())
                                <img src="{{ $mhs->avatarUrl() }}" alt="Avatar {{ $mhs->nama }}" class="w-full h-full object-cover">
                            @else
                                {{ $mhs->initials() }}
                            @endif
                        </div>

                        <!-- Name & NIM -->
                        <div class="min-w-0 space-y-0.5">
                            <p class="font-black text-xs sm:text-sm text-gray-950 truncate">{{ $mhs->nama }}</p>
                            <div class="flex flex-wrap items-center gap-2 text-[11px] font-mono font-bold text-[#1A467C]">
                                <span>{{ $mhs->nim }}</span>
                                <span>•</span>
                                <span class="bg-blue-50 border border-[#1A467C] px-1.5 py-0.2">{{ $mhs->kelas }}</span>
                                <span class="text-gray-500 font-sans font-semibold text-[10px]">({{ (int) $mhs->point_histories_count }}x aktivitas)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Visual Progress Bar + Total Point Badge -->
                    <div class="flex items-center justify-end gap-4 sm:w-72 shrink-0">
                        <div class="hidden sm:block flex-1 bg-gray-100 border-2 border-black h-3.5 p-0.5 shadow-[1px_1px_0px_0px_#000000]">
                            <div class="h-full {{ $barColor }} transition-all duration-300" style="width: {{ max(4, $pct) }}%"></div>
                        </div>

                        <div class="bg-green-100 border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1 shrink-0 min-w-[76px] justify-center">
                            <span class="text-xs font-black text-[#15803d] leading-none">★</span>
                            <span class="font-mono font-black text-sm text-[#15803d] leading-none">{{ (int) $mhs->total_point }}</span>
                            <span class="text-[10px] font-black uppercase text-[#15803d] leading-none">Pts</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-xs font-bold text-gray-500">
                    Belum ada data mahasiswa.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
