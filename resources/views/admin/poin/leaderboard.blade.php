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
        transform: translateY(-4px) scale(1.06);
    }
    .podium-avatar {
        transition: transform 0.2s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    @keyframes adminLbSnapIn {
        0% { opacity: 0; transform: translateY(8px) scale(0.99); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .lb-mode-snap {
        animation: adminLbSnapIn 0.22s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    .lb-bar-fill {
        transition: width 0.55s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .lb-row {
        will-change: transform;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    @keyframes dataFlashPop {
        0% { background-color: #fef08a; }
        100% { background-color: transparent; }
    }
    .realtime-flash {
        animation: dataFlashPop 0.9s ease-out;
    }

    @keyframes statPop {
        0% { transform: scale(1); }
        40% { transform: scale(1.08); }
        100% { transform: scale(1); }
    }
    .stat-pop {
        display: inline-block;
        animation: statPop 0.32s cubic-bezier(0.18, 0.89, 0.32, 1.28);
    }

    @keyframes livePulseRing {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.45; transform: scale(1.25); }
    }
    .live-dot {
        animation: livePulseRing 2s ease-in-out infinite;
    }
</style>

@php
    $maxPointIndividu = max(1, (int) ($rankedMahasiswa->max('total_point') ?? 1));
    $maxPointKelompok = max(1, (int) ($kelompokLeaderboardList->max('total_point') ?? 1));

    $first = $topThree->get(0);
    $second = $topThree->get(1);
    $third = $topThree->get(2);

    $podiumColumnsIndividu = [
        [
            'rank' => 2,
            'barBg' => 'bg-sky-200',
            'barHeight' => 'h-32 sm:h-40',
            'emptyHeight' => 'h-28 sm:h-36',
            'delay' => '0.08s',
            'mhs' => $second,
        ],
        [
            'rank' => 1,
            'barBg' => 'bg-amber-300',
            'barHeight' => 'h-44 sm:h-56',
            'emptyHeight' => 'h-40 sm:h-52',
            'delay' => '0s',
            'mhs' => $first,
        ],
        [
            'rank' => 3,
            'barBg' => 'bg-orange-200',
            'barHeight' => 'h-24 sm:h-32',
            'emptyHeight' => 'h-20 sm:h-28',
            'delay' => '0.16s',
            'mhs' => $third,
        ],
    ];

    $grpFirst = $kelompokTopThree->get(0);
    $grpSecond = $kelompokTopThree->get(1);
    $grpThird = $kelompokTopThree->get(2);

    $podiumColumnsKelompok = [
        [
            'rank' => 2,
            'barBg' => 'bg-sky-200',
            'barHeight' => 'h-32 sm:h-40',
            'emptyHeight' => 'h-28 sm:h-36',
            'delay' => '0.08s',
            'grp' => $grpSecond,
        ],
        [
            'rank' => 1,
            'barBg' => 'bg-amber-300',
            'barHeight' => 'h-44 sm:h-56',
            'emptyHeight' => 'h-40 sm:h-52',
            'delay' => '0s',
            'grp' => $grpFirst,
        ],
        [
            'rank' => 3,
            'barBg' => 'bg-orange-200',
            'barHeight' => 'h-24 sm:h-32',
            'emptyHeight' => 'h-20 sm:h-28',
            'delay' => '0.16s',
            'grp' => $grpThird,
        ],
    ];
@endphp

<div class="space-y-6 sm:space-y-8">

    <!-- 1. HEADER & FILTER KELAS -->
    <div class="snap-in-1 bg-white border-2 border-black px-5 py-4 sm:px-6 sm:py-4 shadow-[5px_5px_0px_0px_#000000] flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-400 text-black border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000] shrink-0 font-black text-base">
                ★
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h3 class="text-base sm:text-lg font-black uppercase tracking-tight text-[#1A467C]">
                        Leaderboard PointKu
                    </h3>
                </div>
                <p class="text-[11px] font-semibold text-gray-600 mt-0.5">
                    Podium peringkat teratas &amp; klasemen peserta serta kelompok secara realtime.
                </p>
            </div>
        </div>

        <form action="{{ route('admin.poin.leaderboard') }}" method="GET" class="flex items-center gap-2 shrink-0">
            <div class="relative flex-1 sm:w-48">
                <select
                    name="kelas"
                    onchange="this.form.submit()"
                    class="w-full appearance-none bg-[#F8FBFE] border-2 border-black text-gray-950 font-black text-xs px-3 py-2.5 pr-8 focus:outline-none focus:bg-white cursor-pointer"
                >
                    <option value="">Semua Kelas</option>
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->nama_kelas }}" {{ $kelasFilter === $k->nama_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-black">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            @if ($kelasFilter)
                <a
                    href="{{ route('admin.poin.leaderboard') }}"
                    class="btn-smooth px-3 py-2.5 bg-gray-100 hover:bg-gray-200 border-2 border-black text-xs font-black uppercase text-gray-900"
                >
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- 2. SWITCHER MODE: PESERTA VS KELOMPOK -->
    <div class="snap-in-2 bg-white border-2 border-black p-2.5 shadow-[4px_4px_0px_0px_#000000] flex flex-wrap items-center justify-between gap-2">
        <div class="grid grid-cols-2 gap-2 w-full sm:w-auto sm:min-w-[320px]">
            <button
                type="button"
                id="adm-lb-btn-individu"
                onclick="switchAdminLbMode('individu')"
                class="btn-smooth px-4 py-2 bg-[#1A467C] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
            >
                Peserta (<span id="adm-lb-count-individu">{{ $totalPeserta }}</span>)
            </button>
            <button
                type="button"
                id="adm-lb-btn-kelompok"
                onclick="switchAdminLbMode('kelompok')"
                class="btn-smooth px-4 py-2 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black text-xs font-black uppercase tracking-wider cursor-pointer"
            >
                Kelompok (<span id="adm-lb-count-kelompok">{{ $totalKelompok }}</span>)
            </button>
        </div>
        <div class="flex items-center justify-between sm:justify-end gap-2 w-full sm:w-auto">
            <span class="hidden sm:inline-block font-mono text-[10px] font-black uppercase text-gray-500 px-2">
                Klasemen Point Keaktifan
            </span>
            <button
                type="button"
                onclick="openAdminLeaderboardCapture()"
                class="btn-smooth w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-amber-300 hover:bg-amber-400 text-black border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="square" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Capture &amp; Share</span>
            </button>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODE 1: LEADERBOARD PESERTA (PODIUM + KLASEMEN LENGKAP)        -->
    <!-- ============================================================== -->
    <div id="adm-lb-view-individu" class="lb-mode-snap space-y-6 sm:space-y-8">

        <!-- Podium Top 3 Peserta -->
        <div id="adm-lb-ind-podium-card" class="snap-in-3 {{ $topThree->count() > 0 ? '' : 'hidden ' }}bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] p-5 sm:p-8">
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

            <div class="max-w-3xl mx-auto pt-2">
                <div id="adm-lb-ind-podium" class="grid grid-cols-3 gap-3 sm:gap-8 items-end border-b-[4px] border-black px-2 sm:px-6">
                    @foreach ($podiumColumnsIndividu as $col)
                        @php $mhs = $col['mhs']; @endphp
                        <div class="podium-col flex flex-col items-center text-center">
                            @if ($mhs)
                                <div class="mb-3 sm:mb-4 flex flex-col items-center w-full">
                                    <div class="podium-avatar relative w-12 h-12 sm:w-16 sm:h-16 bg-white text-[#1A467C] border-[3px] border-black flex items-center justify-center font-black text-sm sm:text-lg shadow-[3px_3px_0px_0px_#000000] overflow-hidden">
                                        @if ($mhs->avatarUrl())
                                            <img src="{{ $mhs->avatarUrl() }}" alt="Foto {{ $mhs->nama }}" class="w-full h-full object-cover">
                                        @else
                                            {{ $mhs->initials() }}
                                        @endif
                                    </div>

                                    <h5 class="font-black text-[11px] sm:text-sm text-gray-950 uppercase tracking-tight line-clamp-1 max-w-full mt-2.5 px-1" title="{{ $mhs->nama }}">
                                        {{ $mhs->nama }}
                                    </h5>
                                    <p class="font-mono text-[10px] sm:text-xs font-bold text-[#1A467C] mt-0.5">
                                        {{ $mhs->kelas }}
                                    </p>

                                    <div class="mt-2 bg-white border-2 border-black px-2.5 py-1 sm:px-3.5 sm:py-1 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1">
                                        <span class="font-mono font-black text-sm sm:text-base text-gray-950 leading-none">{{ (int) $mhs->total_point }}</span>
                                        <span class="text-[9px] sm:text-[10px] font-black uppercase text-[#15803d] leading-none">Pts</span>
                                    </div>
                                </div>

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
                                <div class="w-full {{ $col['emptyHeight'] }} bg-gray-100 border-t-2 border-x-2 border-dashed border-gray-400 flex flex-col items-center justify-center text-[10px] font-bold text-gray-400">
                                    <span class="font-mono font-black text-lg text-gray-400 leading-none">#{{ $col['rank'] }}</span>
                                    <span class="mt-1">Kosong</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Daftar Peringkat Lengkap Peserta (Mulai #4, #1-#3 di Podium) -->
        <div id="adm-lb-ind-list-card" class="snap-in-4 {{ $rankedMahasiswa->count() <= 3 ? 'hidden ' : '' }}bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
            <div class="px-5 py-3.5 border-b-2 border-black bg-blue-50/70 flex items-center justify-between gap-3">
                <h3 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                    Peringkat Lengkap Mahasiswa
                </h3>
                <span id="adm-lb-ind-total-badge" class="text-xs font-bold text-[#1A467C] bg-white border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000000]">
                    {{ $totalPeserta }} Mahasiswa
                </span>
            </div>

            <div id="adm-lb-ind-list" class="divide-y-2 divide-gray-200">
                @foreach ($rankedMahasiswa->slice(3) as $idx => $mhs)
                    @php
                        $rank = $idx + 1;
                        $pct = round(((int) $mhs->total_point / $maxPointIndividu) * 100);
                        $barColor = $rank === 1 ? 'bg-amber-400' : ($rank === 2 ? 'bg-[#2A82C6]' : ($rank === 3 ? 'bg-orange-400' : 'bg-[#15803d]'));
                    @endphp
                    <div
                        data-lb-key="mhs-{{ $mhs->id }}"
                        data-pts="{{ (int) $mhs->total_point }}"
                        class="lb-row px-5 py-4 hover:bg-blue-50/40 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6"
                    >
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-9 h-9 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_#000000] {{ $rank === 1 ? 'bg-amber-300 text-black' : ($rank === 2 ? 'bg-sky-200 text-black' : ($rank === 3 ? 'bg-orange-200 text-black' : 'bg-gray-100 text-gray-800')) }}">
                                #{{ $rank }}
                            </div>

                            <div class="w-10 h-10 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                                @if ($mhs->avatarUrl())
                                    <img src="{{ $mhs->avatarUrl() }}" alt="Foto {{ $mhs->nama }}" class="w-full h-full object-cover">
                                @else
                                    {{ $mhs->initials() }}
                                @endif
                            </div>

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

                        <div class="flex items-center justify-end gap-4 sm:w-72 shrink-0">
                            <div class="hidden sm:block flex-1 bg-gray-100 border-2 border-black h-3.5 p-0.5 shadow-[1px_1px_0px_0px_#000000]">
                                <div class="lb-bar-fill h-full {{ $barColor }}" style="width: {{ max(4, $pct) }}%"></div>
                            </div>

                            <div class="bg-green-100 border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1 shrink-0 min-w-[76px] justify-center">
                                <span class="text-xs font-black text-[#15803d] leading-none">★</span>
                                <span class="font-mono font-black text-sm text-[#15803d] leading-none">{{ (int) $mhs->total_point }}</span>
                                <span class="text-[10px] font-black uppercase text-[#15803d] leading-none">Pts</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="adm-lb-ind-empty" class="{{ $rankedMahasiswa->isEmpty() ? '' : 'hidden ' }}p-8 text-center text-xs font-bold text-gray-500">
                Belum ada data mahasiswa pada filter kelas ini.
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODE 2: LEADERBOARD KELOMPOK (PODIUM + KLASEMEN LENGKAP)       -->
    <!-- ============================================================== -->
    <div id="adm-lb-view-kelompok" class="lb-mode-snap hidden space-y-6 sm:space-y-8">

        <!-- Empty State Kelompok -->
        <div id="adm-lb-grp-empty" class="{{ $kelompokLeaderboardList->isEmpty() ? '' : 'hidden ' }}bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] p-10 text-center">
            <p class="text-sm font-black uppercase text-[#1A467C]">Belum Ada Kelompok Terbentuk</p>
            <p class="text-xs font-semibold text-gray-600 mt-1">
                Klasemen kelompok akan muncul secara otomatis setelah mahasiswa membuat atau bergabung ke kelompok.
            </p>
        </div>

        <div id="adm-lb-grp-content" class="{{ $kelompokLeaderboardList->isEmpty() ? 'hidden ' : '' }}space-y-6 sm:space-y-8">
            <!-- Podium Top 3 Kelompok -->
            <div class="snap-in-3 bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] p-5 sm:p-8">
                <div class="flex items-center justify-between gap-2 border-b-2 border-black pb-3.5 mb-6 sm:mb-8">
                    <div class="flex items-center gap-2.5">
                        <span class="bg-amber-400 text-black text-xs font-black px-2.5 py-0.5 border border-black">TOP 3</span>
                        <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                            Podium Kelompok Teratas
                        </h4>
                    </div>
                    <span class="text-xs font-mono font-black bg-blue-50 text-[#1A467C] border-2 border-black px-2.5 py-0.5">
                        Total Point Tim
                    </span>
                </div>

                <div class="max-w-3xl mx-auto pt-2">
                    <div id="adm-lb-grp-podium" class="grid grid-cols-3 gap-3 sm:gap-8 items-end border-b-[4px] border-black px-2 sm:px-6">
                        @foreach ($podiumColumnsKelompok as $col)
                            @php $grp = $col['grp']; @endphp
                            <div class="podium-col flex flex-col items-center text-center">
                                @if ($grp)
                                    <div class="mb-3 sm:mb-4 flex flex-col items-center w-full">
                                        <div class="podium-avatar relative w-12 h-12 sm:w-16 sm:h-16 bg-[#1A467C] text-white border-[3px] border-black flex items-center justify-center font-black text-sm sm:text-lg shadow-[3px_3px_0px_0px_#000000] overflow-hidden">
                                            @if (!empty($grp->foto_url))
                                                <img src="{{ $grp->foto_url }}" alt="Foto {{ $grp->nama_kelompok }}" class="w-full h-full object-cover">
                                            @else
                                                {{ $grp->initials }}
                                            @endif
                                        </div>

                                        <h5 class="font-black text-[11px] sm:text-sm text-gray-950 uppercase tracking-tight line-clamp-1 max-w-full mt-2.5 px-1" title="{{ $grp->nama_kelompok }}">
                                            {{ $grp->nama_kelompok }}
                                        </h5>
                                        <p class="font-mono text-[10px] sm:text-xs font-bold text-[#1A467C] mt-0.5">
                                            {{ $grp->jumlah_anggota }} Anggota
                                        </p>

                                        <div class="mt-2 bg-white border-2 border-black px-2.5 py-1 sm:px-3.5 sm:py-1 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1">
                                            <span class="font-mono font-black text-sm sm:text-base text-gray-950 leading-none">{{ $grp->total_point }}</span>
                                            <span class="text-[9px] sm:text-[10px] font-black uppercase text-[#15803d] leading-none">Pts</span>
                                        </div>
                                    </div>

                                    <div
                                        class="podium-bar w-full {{ $col['barHeight'] }} {{ $col['barBg'] }} border-t-[3px] border-x-[3px] border-black shadow-[4px_0px_0px_0px_#000000] flex flex-col items-center justify-center gap-2 py-3 sm:py-4 px-2 relative"
                                        style="animation-delay: {{ $col['delay'] }};"
                                    >
                                        <span class="font-mono font-black text-3xl sm:text-5xl text-black/90 leading-none select-none">
                                            #{{ $col['rank'] }}
                                        </span>
                                        <span class="font-mono text-[9px] sm:text-[11px] font-bold text-gray-900 hidden sm:block line-clamp-1 max-w-full">
                                            Ketua: {{ $grp->ketua_nama }}
                                        </span>
                                    </div>
                                @else
                                    <div class="w-full {{ $col['emptyHeight'] }} bg-gray-100 border-t-2 border-x-2 border-dashed border-gray-400 flex flex-col items-center justify-center text-[10px] font-bold text-gray-400">
                                        <span class="font-mono font-black text-lg text-gray-400 leading-none">#{{ $col['rank'] }}</span>
                                        <span class="mt-1">Kosong</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Daftar Peringkat Lengkap Kelompok (Mulai #4, #1-#3 di Podium) -->
            <div id="adm-lb-grp-list-card" class="snap-in-4 {{ $kelompokLeaderboardList->count() <= 3 ? 'hidden ' : '' }}bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
                <div class="px-5 py-3.5 border-b-2 border-black bg-blue-50/70 flex items-center justify-between gap-3">
                    <h3 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                        Peringkat Lengkap Kelompok
                    </h3>
                    <span id="adm-lb-grp-total-badge" class="text-xs font-bold text-[#1A467C] bg-white border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000000]">
                        {{ $totalKelompok }} Kelompok
                    </span>
                </div>

                <div id="adm-lb-grp-list" class="divide-y-2 divide-gray-200">
                    @foreach ($kelompokLeaderboardList->slice(3) as $idx => $grp)
                        @php
                            $rank = $idx + 1;
                            $pct = round(($grp->total_point / $maxPointKelompok) * 100);
                            $barColor = $rank === 1 ? 'bg-amber-400' : ($rank === 2 ? 'bg-[#2A82C6]' : ($rank === 3 ? 'bg-orange-400' : 'bg-[#15803d]'));
                        @endphp
                        <div
                            data-lb-key="grp-{{ $grp->id }}"
                            data-pts="{{ (int) $grp->total_point }}"
                            class="lb-row px-5 py-4 hover:bg-blue-50/40 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6"
                        >
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-9 h-9 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_#000000] {{ $rank === 1 ? 'bg-amber-300 text-black' : ($rank === 2 ? 'bg-sky-200 text-black' : ($rank === 3 ? 'bg-orange-200 text-black' : 'bg-gray-100 text-gray-800')) }}">
                                    #{{ $rank }}
                                </div>

                                <div class="w-10 h-10 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                                    @if (!empty($grp->foto_url))
                                        <img src="{{ $grp->foto_url }}" alt="Foto {{ $grp->nama_kelompok }}" class="w-full h-full object-cover">
                                    @else
                                        {{ $grp->initials }}
                                    @endif
                                </div>

                                <div class="min-w-0 space-y-0.5">
                                    <p class="font-black text-xs sm:text-sm text-gray-950 truncate">{{ $grp->nama_kelompok }}</p>
                                    <div class="flex flex-wrap items-center gap-2 text-[11px] font-mono font-bold text-[#1A467C]">
                                        <span>Ketua: {{ $grp->ketua_nama }}</span>
                                        <span>•</span>
                                        <span class="bg-blue-50 border border-[#1A467C] px-1.5 py-0.2">{{ $grp->jumlah_anggota }} Anggota</span>
                                        <span class="text-gray-500 font-sans font-semibold text-[10px]">(Rata-rata {{ $grp->avg_point }} Pts)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-4 sm:w-72 shrink-0">
                                <div class="hidden sm:block flex-1 bg-gray-100 border-2 border-black h-3.5 p-0.5 shadow-[1px_1px_0px_0px_#000000]">
                                    <div class="lb-bar-fill h-full {{ $barColor }}" style="width: {{ max(4, $pct) }}%"></div>
                                </div>

                                <div class="bg-green-100 border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1 shrink-0 min-w-[76px] justify-center">
                                    <span class="text-xs font-black text-[#15803d] leading-none">★</span>
                                    <span class="font-mono font-black text-sm text-[#15803d] leading-none">{{ $grp->total_point }}</span>
                                    <span class="text-[10px] font-black uppercase text-[#15803d] leading-none">Pts</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

<script src="{{ asset('js/leaderboard-capture.js') }}"></script>
<script>
    const adminLbSyncUrl = @json(route('admin.poin.leaderboard.sync'));
    const adminKelasFilter = @json($kelasFilter);
    let currentAdminLbSig = @json($realtimeSignature);
    let currentAdminLbMode = 'individu';
    let isAdminLbSyncInFlight = false;
    let adminLbPollTimer = null;
    let lastAdminIndPodiumSig = '';
    let lastAdminGrpPodiumSig = '';

    @php
        $initialAdminIndJson = $rankedMahasiswa->values()->map(function ($m, $idx) {
            return [
                'rank' => $idx + 1,
                'nama' => $m->nama,
                'kelas' => $m->kelas,
                'nim' => $m->nim,
                'total_point' => (int) $m->total_point,
                'initials' => $m->initials(),
                'avatar_url' => $m->avatarUrl(),
            ];
        })->all();
        $initialAdminGrpJson = $kelompokLeaderboardList->values()->map(function ($g, $idx) {
            return [
                'rank' => $idx + 1,
                'nama_kelompok' => $g->nama_kelompok,
                'ketua_nama' => $g->ketua_nama,
                'jumlah_anggota' => (int) $g->jumlah_anggota,
                'total_point' => (int) $g->total_point,
                'initials' => $g->initials,
                'foto_url' => $g->foto_url,
            ];
        })->all();
    @endphp
    let latestAdminIndList = @json($initialAdminIndJson);
    let latestAdminGrpList = @json($initialAdminGrpJson);

    function openAdminLeaderboardCapture() {
        if (!window.PokjaLeaderboardCapture) return;
        const isGrp = currentAdminLbMode === 'kelompok';
        const items = isGrp
            ? latestAdminGrpList.map((g, idx) => ({
                rank: g.rank || (idx + 1),
                title: g.nama_kelompok,
                subtitle: `Ketua: ${g.ketua_nama || '-'}`,
                barFooter: `${g.jumlah_anggota || 0} Anggota`,
                points: Number(g.total_point) || 0,
                initials: g.initials || 'KL',
                photoUrl: g.foto_url || null,
            }))
            : latestAdminIndList.map((m, idx) => ({
                rank: m.rank || (idx + 1),
                title: m.nama,
                subtitle: m.kelas || '-',
                barFooter: m.nim || '',
                points: Number(m.total_point) || 0,
                initials: m.initials || '--',
                photoUrl: m.avatar_url || null,
            }));

        window.PokjaLeaderboardCapture.open({
            mode: currentAdminLbMode,
            kelas: isGrp ? '' : (adminKelasFilter || ''),
            items,
        });
    }

    const PODIUM_SLOTS_CFG = [
        { rank: 2, barBg: 'bg-sky-200', barHeight: 'h-32 sm:h-40', emptyHeight: 'h-28 sm:h-36', delay: '0.08s', listIdx: 1 },
        { rank: 1, barBg: 'bg-amber-300', barHeight: 'h-44 sm:h-56', emptyHeight: 'h-40 sm:h-52', delay: '0s', listIdx: 0 },
        { rank: 3, barBg: 'bg-orange-200', barHeight: 'h-24 sm:h-32', emptyHeight: 'h-20 sm:h-28', delay: '0.16s', listIdx: 2 },
    ];

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, ch => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
        }[ch]));
    }

    function replayPodiumBars(containerEl) {
        if (!containerEl) return;
        containerEl.querySelectorAll('.podium-bar').forEach(bar => {
            bar.style.animation = 'none';
            void bar.offsetHeight;
            bar.style.animation = '';
        });
    }

    function replayModeSnap(viewEl) {
        if (!viewEl) return;
        viewEl.classList.remove('lb-mode-snap');
        void viewEl.offsetWidth;
        viewEl.classList.add('lb-mode-snap');
    }

    function switchAdminLbMode(mode) {
        currentAdminLbMode = mode === 'kelompok' ? 'kelompok' : 'individu';
        const isKelompok = currentAdminLbMode === 'kelompok';

        const indView = document.getElementById('adm-lb-view-individu');
        const grpView = document.getElementById('adm-lb-view-kelompok');
        const indBtn = document.getElementById('adm-lb-btn-individu');
        const grpBtn = document.getElementById('adm-lb-btn-kelompok');

        const activeCls = 'btn-smooth px-4 py-2 bg-[#1A467C] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer';
        const inactiveCls = 'btn-smooth px-4 py-2 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black text-xs font-black uppercase tracking-wider cursor-pointer';

        if (indView && grpView) {
            indView.classList.toggle('hidden', isKelompok);
            grpView.classList.toggle('hidden', !isKelompok);
            const visibleView = isKelompok ? grpView : indView;
            replayModeSnap(visibleView);
            replayPodiumBars(document.getElementById(isKelompok ? 'adm-lb-grp-podium' : 'adm-lb-ind-podium'));
        }
        if (indBtn && grpBtn) {
            indBtn.className = isKelompok ? inactiveCls : activeCls;
            grpBtn.className = isKelompok ? activeCls : inactiveCls;
        }

        try {
            window.history.replaceState(null, '', isKelompok ? '#kelompok' : '#peserta');
        } catch (e) {}
    }

    function getAdminDomTopThreeSig(listId) {
        const listEl = document.getElementById(listId);
        if (!listEl) return '';
        return Array.from(listEl.querySelectorAll('.lb-row'))
            .slice(0, 3)
            .map(row => `${row.dataset.lbKey}|${row.dataset.pts || 0}`)
            .join(',');
    }

    function getAdminJsonTopThreeSig(items, prefix) {
        if (!Array.isArray(items)) return '';
        return items
            .slice(0, 3)
            .map(item => `${prefix}-${item.id}|${Number(item.total_point || 0)}`)
            .join(',');
    }

    function popStatCount(elId, countVal) {
        const el = document.getElementById(elId);
        if (!el) return;
        const nextText = String(countVal);
        if (el.textContent.trim() !== nextText) {
            el.textContent = nextText;
            el.classList.remove('stat-pop');
            void el.offsetWidth;
            el.classList.add('stat-pop');
        }
    }

    function renderAdminPodiumSlots(containerId, items, mode, shouldAnimate) {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.innerHTML = PODIUM_SLOTS_CFG.map(slot => {
            const entry = Array.isArray(items) ? items[slot.listIdx] : null;
            if (!entry) {
                return `
                    <div class="podium-col flex flex-col items-center text-center">
                        <div class="w-full ${slot.emptyHeight} bg-gray-100 border-t-2 border-x-2 border-dashed border-gray-400 flex flex-col items-center justify-center text-[10px] font-bold text-gray-400">
                            <span class="font-mono font-black text-lg text-gray-400 leading-none">#${slot.rank}</span>
                            <span class="mt-1">Kosong</span>
                        </div>
                    </div>
                `;
            }

            const isKelompok = mode === 'kelompok';
            const title = isKelompok ? entry.nama_kelompok : entry.nama;
            const subtitle = isKelompok ? `${Number(entry.jumlah_anggota || 0)} Anggota` : entry.kelas;
            const footerLabel = isKelompok ? `Ketua: ${entry.ketua_nama || '-'}` : (entry.nim || '');
            const photoUrl = isKelompok ? entry.foto_url : entry.avatar_url;
            const avatarBoxBg = isKelompok ? 'bg-[#1A467C] text-white' : 'bg-white text-[#1A467C]';
            const avatarInner = photoUrl
                ? `<img src="${escapeHtml(photoUrl)}" alt="Foto ${escapeHtml(title)}" class="w-full h-full object-cover">`
                : escapeHtml(entry.initials || '--');
            const barAnimStyle = shouldAnimate
                ? `animation-delay: ${slot.delay};`
                : 'animation: none;';

            return `
                <div class="podium-col flex flex-col items-center text-center">
                    <div class="mb-3 sm:mb-4 flex flex-col items-center w-full">
                        <div class="podium-avatar relative w-12 h-12 sm:w-16 sm:h-16 ${avatarBoxBg} border-[3px] border-black flex items-center justify-center font-black text-sm sm:text-lg shadow-[3px_3px_0px_0px_#000000] overflow-hidden">
                            ${avatarInner}
                        </div>

                        <h5 class="font-black text-[11px] sm:text-sm text-gray-950 uppercase tracking-tight line-clamp-1 max-w-full mt-2.5 px-1" title="${escapeHtml(title)}">
                            ${escapeHtml(title)}
                        </h5>
                        <p class="font-mono text-[10px] sm:text-xs font-bold text-[#1A467C] mt-0.5">
                            ${escapeHtml(subtitle)}
                        </p>

                        <div class="mt-2 bg-white border-2 border-black px-2.5 py-1 sm:px-3.5 sm:py-1 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1">
                            <span class="font-mono font-black text-sm sm:text-base text-gray-950 leading-none">${Number(entry.total_point || 0)}</span>
                            <span class="text-[9px] sm:text-[10px] font-black uppercase text-[#15803d] leading-none">Pts</span>
                        </div>
                    </div>

                    <div
                        class="podium-bar w-full ${slot.barHeight} ${slot.barBg} border-t-[3px] border-x-[3px] border-black shadow-[4px_0px_0px_0px_#000000] flex flex-col items-center justify-center gap-2 py-3 sm:py-4 px-2 relative"
                        style="${barAnimStyle}"
                    >
                        <span class="font-mono font-black text-3xl sm:text-5xl text-black/90 leading-none select-none">
                            #${slot.rank}
                        </span>
                        <span class="font-mono text-[9px] sm:text-[11px] font-bold text-gray-900 hidden sm:block line-clamp-1 max-w-full">
                            ${escapeHtml(footerLabel)}
                        </span>
                    </div>
                </div>
            `;
        }).join('');
    }

    function buildAdminLbRowHtml(item, maxPoint, mode) {
        const isKelompok = mode === 'kelompok';
        const key = `${isKelompok ? 'grp' : 'mhs'}-${item.id}`;
        const rank = Number(item.rank || 0);
        const totalPoint = Number(item.total_point || 0);
        const pct = Math.max(4, Math.round((totalPoint / Math.max(1, maxPoint)) * 100));

        const rankBadgeClass = rank === 1
            ? 'bg-amber-300 text-black'
            : (rank === 2 ? 'bg-sky-200 text-black' : (rank === 3 ? 'bg-orange-200 text-black' : 'bg-gray-100 text-gray-800'));

        const barColor = rank === 1
            ? 'bg-amber-400'
            : (rank === 2 ? 'bg-[#2A82C6]' : (rank === 3 ? 'bg-orange-400' : 'bg-[#15803d]'));

        const title = isKelompok ? item.nama_kelompok : item.nama;
        const photoUrl = isKelompok ? item.foto_url : item.avatar_url;
        const avatarBg = isKelompok ? 'bg-[#1A467C]' : 'bg-[#2A82C6]';
        const avatarInner = photoUrl
            ? `<img src="${escapeHtml(photoUrl)}" alt="Foto ${escapeHtml(title)}" class="w-full h-full object-cover">`
            : escapeHtml(item.initials || '--');

        const metaHtml = isKelompok
            ? `
                <span>Ketua: ${escapeHtml(item.ketua_nama || '-')}</span>
                <span>•</span>
                <span class="bg-blue-50 border border-[#1A467C] px-1.5 py-0.2">${Number(item.jumlah_anggota || 0)} Anggota</span>
                <span class="text-gray-500 font-sans font-semibold text-[10px]">(Rata-rata ${escapeHtml(item.avg_point ?? 0)} Pts)</span>
            `
            : `
                <span>${escapeHtml(item.nim)}</span>
                <span>•</span>
                <span class="bg-blue-50 border border-[#1A467C] px-1.5 py-0.2">${escapeHtml(item.kelas)}</span>
                <span class="text-gray-500 font-sans font-semibold text-[10px]">(${Number(item.point_histories_count || 0)}x aktivitas)</span>
            `;

        return `
            <div
                data-lb-key="${key}"
                data-pts="${totalPoint}"
                class="lb-row px-5 py-4 hover:bg-blue-50/40 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6"
            >
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-9 h-9 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_#000000] ${rankBadgeClass}">
                        #${rank}
                    </div>

                    <div class="w-10 h-10 ${avatarBg} text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                        ${avatarInner}
                    </div>

                    <div class="min-w-0 space-y-0.5">
                        <p class="font-black text-xs sm:text-sm text-gray-950 truncate">${escapeHtml(title)}</p>
                        <div class="flex flex-wrap items-center gap-2 text-[11px] font-mono font-bold text-[#1A467C]">
                            ${metaHtml}
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 sm:w-72 shrink-0">
                    <div class="hidden sm:block flex-1 bg-gray-100 border-2 border-black h-3.5 p-0.5 shadow-[1px_1px_0px_0px_#000000]">
                        <div class="lb-bar-fill h-full ${barColor}" style="width: ${pct}%"></div>
                    </div>

                    <div class="bg-green-100 border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1 shrink-0 min-w-[76px] justify-center">
                        <span class="text-xs font-black text-[#15803d] leading-none">★</span>
                        <span class="font-mono font-black text-sm text-[#15803d] leading-none">${totalPoint}</span>
                        <span class="text-[10px] font-black uppercase text-[#15803d] leading-none">Pts</span>
                    </div>
                </div>
            </div>
        `;
    }

    function updateAdminLeaderboardWithFlip(containerId, emptyId, items, mode) {
        const container = document.getElementById(containerId);
        const emptyEl = emptyId ? document.getElementById(emptyId) : null;
        if (!container) return;

        const list = Array.isArray(items) ? items : [];
        const listCard = document.getElementById(`${containerId}-card`);
        if (emptyEl) {
            emptyEl.classList.toggle('hidden', list.length > 0);
        }

        const belowPodium = list.slice(3);
        if (listCard) {
            listCard.classList.toggle('hidden', belowPodium.length === 0);
        }

        if (belowPodium.length === 0) {
            container.innerHTML = '';
            return;
        }

        const prevMeta = new Map();
        Array.from(container.querySelectorAll('.lb-row')).forEach((row, idx) => {
            const key = row.dataset.lbKey;
            if (key) {
                prevMeta.set(key, {
                    top: row.getBoundingClientRect().top,
                    pts: Number(row.dataset.pts || 0),
                    idx,
                });
            }
        });

        const maxPoint = Math.max(1, ...list.map(i => Number(i.total_point || 0)));
        container.innerHTML = belowPodium.map(item => buildAdminLbRowHtml(item, maxPoint, mode)).join('');

        const newRows = Array.from(container.querySelectorAll('.lb-row'));
        const flipMoves = [];

        newRows.forEach((row, newIdx) => {
            const key = row.dataset.lbKey;
            const prev = key ? prevMeta.get(key) : null;
            if (!prev) {
                row.classList.add('realtime-flash');
                return;
            }

            const newTop = row.getBoundingClientRect().top;
            const deltaY = prev.top - newTop;
            const ptsChanged = prev.pts !== Number(row.dataset.pts || 0);
            const rankChanged = prev.idx !== newIdx;

            if (Math.abs(deltaY) > 1) {
                row.style.transition = 'none';
                row.style.transform = `translateY(${deltaY}px)`;
                flipMoves.push(row);
            }

            if (ptsChanged || rankChanged) {
                row.classList.add('realtime-flash');
            }
        });

        if (flipMoves.length > 0) {
            void container.offsetHeight;
            requestAnimationFrame(() => {
                flipMoves.forEach(row => {
                    row.style.transition = 'transform 0.55s cubic-bezier(0.22, 1, 0.36, 1)';
                    row.style.transform = 'translateY(0)';
                    const clearFlip = () => {
                        row.style.transition = '';
                        row.style.transform = '';
                    };
                    row.addEventListener('transitionend', clearFlip, { once: true });
                    setTimeout(clearFlip, 650);
                });
            });
        }
    }

    function applyAdminLbSyncPayload(payload) {
        const indList = Array.isArray(payload.individu_leaderboard) ? payload.individu_leaderboard : [];
        const grpList = Array.isArray(payload.kelompok_leaderboard) ? payload.kelompok_leaderboard : [];
        latestAdminIndList = indList;
        latestAdminGrpList = grpList;

        popStatCount('adm-lb-count-individu', payload.total_peserta ?? indList.length);
        popStatCount('adm-lb-count-kelompok', payload.total_kelompok ?? grpList.length);

        const indBadge = document.getElementById('adm-lb-ind-total-badge');
        if (indBadge) indBadge.textContent = `${payload.total_peserta ?? indList.length} Mahasiswa`;

        const grpBadge = document.getElementById('adm-lb-grp-total-badge');
        if (grpBadge) grpBadge.textContent = `${payload.total_kelompok ?? grpList.length} Kelompok`;

        const indPodiumCard = document.getElementById('adm-lb-ind-podium-card');
        if (indPodiumCard) {
            indPodiumCard.classList.toggle('hidden', indList.length === 0);
        }
        const nextIndPodiumSig = getAdminJsonTopThreeSig(indList, 'mhs');
        if (nextIndPodiumSig !== lastAdminIndPodiumSig) {
            lastAdminIndPodiumSig = nextIndPodiumSig;
            renderAdminPodiumSlots('adm-lb-ind-podium', indList, 'individu', true);
        }
        updateAdminLeaderboardWithFlip('adm-lb-ind-list', 'adm-lb-ind-empty', indList, 'individu');

        const grpEmpty = document.getElementById('adm-lb-grp-empty');
        const grpContent = document.getElementById('adm-lb-grp-content');
        if (grpEmpty && grpContent) {
            grpEmpty.classList.toggle('hidden', grpList.length > 0);
            grpContent.classList.toggle('hidden', grpList.length === 0);
        }
        const nextGrpPodiumSig = getAdminJsonTopThreeSig(grpList, 'grp');
        if (nextGrpPodiumSig !== lastAdminGrpPodiumSig) {
            lastAdminGrpPodiumSig = nextGrpPodiumSig;
            renderAdminPodiumSlots('adm-lb-grp-podium', grpList, 'kelompok', true);
        }
        updateAdminLeaderboardWithFlip('adm-lb-grp-list', null, grpList, 'kelompok');
    }

    async function syncAdminLeaderboardNow() {
        if (isAdminLbSyncInFlight) return;
        isAdminLbSyncInFlight = true;

        try {
            const url = `${adminLbSyncUrl}?sig=${encodeURIComponent(currentAdminLbSig)}&kelas=${encodeURIComponent(adminKelasFilter || '')}`;
            const res = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                cache: 'no-store',
            });

            if (res.status === 401 || res.redirected) {
                if (adminLbPollTimer) {
                    clearInterval(adminLbPollTimer);
                }
                return;
            }
            if (!res.ok) return;

            const payload = await res.json();
            const clockEl = document.getElementById('adm-rt-clock');
            if (clockEl) {
                clockEl.textContent = new Date().toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false,
                });
            }

            if (payload && payload.changed) {
                currentAdminLbSig = payload.signature || currentAdminLbSig;
                applyAdminLbSyncPayload(payload);
            }
        } catch (e) {
            // Abaikan error jaringan sementara agar polling 3 detik berikutnya tetap berjalan
        } finally {
            isAdminLbSyncInFlight = false;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        lastAdminIndPodiumSig = getAdminDomTopThreeSig('adm-lb-ind-list');
        lastAdminGrpPodiumSig = getAdminDomTopThreeSig('adm-lb-grp-list');

        if (window.location.hash === '#kelompok') {
            switchAdminLbMode('kelompok');
        }

        if (adminLbPollTimer) {
            clearInterval(adminLbPollTimer);
        }
        adminLbPollTimer = setInterval(() => {
            if (!document.hidden) {
                syncAdminLeaderboardNow();
            }
        }, 3000);

        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                syncAdminLeaderboardNow();
            }
        });
    });
</script>
@endsection
