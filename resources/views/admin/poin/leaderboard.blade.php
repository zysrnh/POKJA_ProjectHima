@extends('admin.layouts.app')

@section('title', 'Leaderboard PointKu')
@section('header_title', 'Leaderboard Point Keaktifan')

@section('content')
<style>
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
    <!-- 1. HEADER & FILTER KELAS                                       -->
    <!-- ============================================================== -->
    <div class="snap-in-1 bg-white border-2 border-black px-4 py-3.5 sm:px-5 sm:py-4 shadow-[5px_5px_0px_0px_#000000] flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 bg-amber-400 text-black border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000] shrink-0 font-black">
                ★
            </div>
            <div>
                <h3 class="text-base sm:text-lg font-black uppercase tracking-tight text-[#1A467C]">
                    LEADERBOARD POINTKU
                </h3>
                <p class="text-[11px] font-semibold text-gray-600">
                    Peringkat mahasiswa berdasarkan total point keaktifan tertinggi.
                </p>
            </div>
        </div>

        <form action="{{ route('admin.poin.leaderboard') }}" method="GET" class="flex items-center gap-2 shrink-0">
            <div class="relative flex-1 sm:w-48">
                <select 
                    name="kelas" 
                    onchange="this.form.submit()"
                    class="w-full bg-[#F8FBFE] border-2 border-black font-bold font-mono text-xs px-3 py-2 pr-8 appearance-none focus:bg-white focus:outline-none cursor-pointer"
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
                    class="btn-smooth px-3 py-2 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black text-xs font-black shadow-[2px_2px_0px_0px_#000000]"
                    title="Reset Kelas"
                >
                    ✕
                </a>
            @endif
        </form>
    </div>

    <!-- ============================================================== -->
    <!-- 2. PODIUM TOP 3 (Neubrutalism Solid Gold / Silver / Bronze)    -->
    <!-- ============================================================== -->
    @if ($topThree->count() > 0)
        @php
            $first = $topThree->get(0);
            $second = $topThree->get(1);
            $third = $topThree->get(2);
            // Urutan tampilan di Desktop: #2 (Kiri), #1 (Tengah Menonjol), #3 (Kanan)
            $podiumItems = [
                ['rank' => 2, 'label' => 'PERINGKAT #2', 'bg' => 'bg-sky-100', 'badgeBg' => 'bg-[#2A82C6] text-white', 'mhs' => $second],
                ['rank' => 1, 'label' => 'PERINGKAT #1', 'bg' => 'bg-amber-300', 'badgeBg' => 'bg-black text-amber-300', 'mhs' => $first],
                ['rank' => 3, 'label' => 'PERINGKAT #3', 'bg' => 'bg-orange-200', 'badgeBg' => 'bg-[#CA2C2A] text-white', 'mhs' => $third],
            ];
        @endphp

        <div class="snap-in-2 grid grid-cols-1 md:grid-cols-3 gap-4 items-end pt-2">
            @foreach ($podiumItems as $pod)
                @if ($pod['mhs'])
                    @php $mhs = $pod['mhs']; @endphp
                    <div class="card-interactive {{ $pod['bg'] }} border-[3px] border-black p-5 shadow-[6px_6px_0px_0px_#000000] text-center flex flex-col items-center {{ $pod['rank'] === 1 ? 'md:-translate-y-2 order-1 md:order-2' : ($pod['rank'] === 2 ? 'order-2 md:order-1' : 'order-3') }}">
                        <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-widest border-2 border-black {{ $pod['badgeBg'] }} shadow-[2px_2px_0px_0px_#000000]">
                            {{ $pod['label'] }}
                        </span>

                        <div class="w-16 h-16 mt-3 bg-white text-[#1A467C] border-[3px] border-black flex items-center justify-center font-black text-lg shadow-[3px_3px_0px_0px_#000000] overflow-hidden">
                            @if ($mhs->avatarUrl())
                                <img src="{{ $mhs->avatarUrl() }}" alt="Avatar {{ $mhs->nama }}" class="w-full h-full object-cover">
                            @else
                                {{ $mhs->initials() }}
                            @endif
                        </div>

                        <h4 class="font-black text-sm sm:text-base text-gray-950 uppercase mt-3 truncate max-w-full">
                            {{ $mhs->nama }}
                        </h4>
                        <p class="font-mono text-xs font-bold text-gray-800 mt-0.5">
                            {{ $mhs->nim }} • <span class="bg-white border border-black px-1.5 py-0.2">{{ $mhs->kelas }}</span>
                        </p>

                        <div class="mt-3.5 bg-white border-2 border-black px-4 py-1.5 shadow-[3px_3px_0px_0px_#000000] inline-flex items-baseline gap-1.5">
                            <span class="font-mono font-black text-xl sm:text-2xl text-gray-950">{{ (int) $mhs->total_point }}</span>
                            <span class="text-[10px] font-black uppercase text-[#1A467C]">Point</span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <!-- ============================================================== -->
    <!-- 3. DAFTAR PERINGKAT LENGKAP                                    -->
    <!-- ============================================================== -->
    <div class="snap-in-3 bg-white border-2 border-black shadow-[6px_6px_0px_0px_#000000] overflow-hidden">
        <div class="px-5 py-3 border-b-2 border-black bg-blue-50/70 flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                Peringkat Lengkap Mahasiswa
            </h3>
            <span class="text-xs font-bold text-[#1A467C] bg-white border-2 border-black px-2.5 py-0.5 shadow-[1px_1px_0px_0px_#000000]">
                Total: {{ $rankedMahasiswa->count() }} Mahasiswa
            </span>
        </div>

        <div class="divide-y-2 divide-gray-200">
            @forelse ($rankedMahasiswa as $idx => $mhs)
                @php $rank = $idx + 1; @endphp
                <div class="px-4 py-3 sm:px-5 sm:py-3.5 hover:bg-blue-50/40 transition flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Rank Number Badge -->
                        <div class="w-8 h-8 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 shadow-[1px_1px_0px_0px_#000000] {{ $rank === 1 ? 'bg-amber-400 text-black' : ($rank === 2 ? 'bg-sky-200 text-black' : ($rank === 3 ? 'bg-orange-300 text-black' : 'bg-gray-100 text-gray-800')) }}">
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
                        <div class="min-w-0">
                            <p class="font-black text-xs sm:text-sm text-gray-950 truncate">{{ $mhs->nama }}</p>
                            <p class="font-mono text-[11px] font-bold text-[#1A467C]">
                                {{ $mhs->nim }} • <span class="bg-blue-50 border border-[#2A82C6] px-1.5 py-0.2">{{ $mhs->kelas }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Right: Total Point -->
                    <div class="shrink-0 text-right">
                        <span class="inline-flex items-center gap-1 bg-green-100 border-2 border-black px-3 py-1 font-mono font-black text-xs sm:text-sm text-[#15803d] shadow-[2px_2px_0px_0px_#000000]">
                            <span>★</span>
                            <span>{{ (int) $mhs->total_point }}</span>
                            <span class="text-[10px] uppercase">Pts</span>
                        </span>
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
