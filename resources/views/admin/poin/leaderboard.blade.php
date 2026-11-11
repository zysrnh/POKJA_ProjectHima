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

@php
    $maxPoint = max(1, (int) ($rankedMahasiswa->max('total_point') ?? 1));
    $chartTopStudents = $rankedMahasiswa->take(8)->values();
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
                    Visualisasi grafik & peringkat mahasiswa berdasarkan perolehan point keaktifan.
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
    <!-- 2. KARTU TOP 3 PERINGKAT TERATAS                               -->
    <!-- ============================================================== -->
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

        <div class="snap-in-2 grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6 items-stretch pt-3">
            @foreach ($podiumItems as $pod)
                @if ($pod['mhs'])
                    @php $mhs = $pod['mhs']; @endphp
                    <div class="card-interactive relative {{ $pod['bg'] }} border-2 border-black px-5 pt-6 pb-5 shadow-[5px_5px_0px_0px_#000000] flex items-center justify-between gap-4">
                        <span class="absolute -top-3 left-4 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider border-2 border-black {{ $pod['badgeBg'] }} shadow-[2px_2px_0px_0px_#000000]">
                            {{ $pod['label'] }}
                        </span>

                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-12 h-12 bg-white text-[#1A467C] border-2 border-black flex items-center justify-center font-black text-sm shrink-0 overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                                @if ($mhs->avatarUrl())
                                    <img src="{{ $mhs->avatarUrl() }}" alt="Avatar {{ $mhs->nama }}" class="w-full h-full object-cover">
                                @else
                                    {{ $mhs->initials() }}
                                @endif
                            </div>
                            <div class="min-w-0 space-y-0.5">
                                <h4 class="font-black text-xs sm:text-sm text-gray-950 uppercase truncate">
                                    {{ $mhs->nama }}
                                </h4>
                                <p class="font-mono text-[11px] font-bold text-gray-800">
                                    {{ $mhs->nim }} • {{ $mhs->kelas }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-white border-2 border-black px-3.5 py-1.5 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1 shrink-0">
                            <span class="font-mono font-black text-base sm:text-lg text-gray-950 leading-none">{{ (int) $mhs->total_point }}</span>
                            <span class="text-[10px] font-black uppercase text-[#1A467C] leading-none">Pts</span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <!-- ============================================================== -->
    <!-- 3. GRAFIK STATISTIK LEADERBOARD (CHART.JS NEUBRUTALISM)        -->
    <!-- ============================================================== -->
    <div class="snap-in-3 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-stretch">

        <!-- GRAFIK KIRI: TOP MAHASISWA BAR CHART -->
        <div class="lg:col-span-7 bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] p-5 sm:p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between gap-2 border-b-2 border-black pb-3.5 mb-4">
                <div class="flex items-center gap-2">
                    <span class="bg-[#1A467C] text-white text-[10px] font-black uppercase px-2 py-0.5 border border-black">GRAFIK INDIVIDU</span>
                    <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                        Perbandingan Point Mahasiswa Teratas
                    </h4>
                </div>
                <span class="text-[11px] font-mono font-bold text-gray-600">
                    Top {{ $chartTopStudents->count() }}
                </span>
            </div>

            <div class="relative w-full h-64 sm:h-72">
                <canvas id="chartTopMahasiswa"></canvas>
            </div>
        </div>

        <!-- GRAFIK KANAN: AKUMULASI POINT PER KELAS -->
        <div class="lg:col-span-5 bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] p-5 sm:p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-2 border-b-2 border-black pb-3.5 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="bg-[#15803d] text-white text-[10px] font-black uppercase px-2 py-0.5 border border-black">GRAFIK KELAS</span>
                        <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                            Total Point per Kelas
                        </h4>
                    </div>
                </div>

                <div class="relative w-full h-44 sm:h-48">
                    <canvas id="chartKelasPoint"></canvas>
                </div>
            </div>

            <!-- Ringkasan Angka per Kelas di Bawah Grafik -->
            <div class="grid grid-cols-2 gap-2.5 mt-4 pt-3.5 border-t-2 border-dashed border-gray-300">
                @foreach ($kelasStats as $ks)
                    <div class="bg-[#F8FBFE] border-2 border-black px-3 py-2 flex items-center justify-between shadow-[2px_2px_0px_0px_#000000]">
                        <div>
                            <span class="font-mono font-black text-xs text-[#1A467C] block">{{ $ks['kelas'] }}</span>
                            <span class="text-[10px] font-bold text-gray-600">{{ $ks['total_mhs'] }} Mhs</span>
                        </div>
                        <span class="font-mono font-black text-xs sm:text-sm text-[#15803d] bg-green-100 border border-black px-2 py-0.5">
                            {{ $ks['total_point'] }} Pts
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- 4. DAFTAR PERINGKAT LENGKAP MAHASISWA                          -->
    <!-- ============================================================== -->
    <div class="snap-in-4 bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
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

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Chart === 'undefined') return;

        Chart.defaults.font.family = '"Plus Jakarta Sans", sans-serif';
        Chart.defaults.font.weight = 'bold';
        Chart.defaults.color = '#111827';

        // 1. Data Grafik Top Mahasiswa
        const topLabels = @json($chartTopStudents->map(fn($m) => \Illuminate\Support\Str::limit($m->nama, 14)));
        const topPoints = @json($chartTopStudents->map(fn($m) => (int) $m->total_point));
        const topBgColors = topPoints.map((_, idx) => {
            if (idx === 0) return '#FCD34D'; // Peringkat #1 Amber Solid
            if (idx === 1) return '#7DD3FC'; // Peringkat #2 Sky Solid
            if (idx === 2) return '#FDBA74'; // Peringkat #3 Orange Solid
            return '#2A82C6';                // Lainnya HIMA Blue Solid
        });

        const ctxTop = document.getElementById('chartTopMahasiswa');
        if (ctxTop) {
            new Chart(ctxTop, {
                type: 'bar',
                data: {
                    labels: topLabels,
                    datasets: [{
                        label: 'Total Point',
                        data: topPoints,
                        backgroundColor: topBgColors,
                        borderColor: '#000000',
                        borderWidth: 2,
                        borderSkipped: false,
                        barPercentage: 0.7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#000000',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12, weight: 'bold' },
                            padding: 10,
                            cornerRadius: 0,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Point';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { color: '#000000', width: 2 },
                            ticks: {
                                font: { size: 10, weight: '800' },
                                color: '#111827'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                font: { size: 11, weight: '800' }
                            },
                            grid: {
                                color: '#E5E7EB',
                                lineWidth: 1
                            },
                            border: { color: '#000000', width: 2 }
                        }
                    }
                }
            });
        }

        // 2. Data Grafik Point per Kelas
        const kelasLabels = @json($kelasStats->pluck('kelas'));
        const kelasPoints = @json($kelasStats->pluck('total_point'));
        const kelasColors = ['#1A467C', '#2A82C6', '#15803d', '#D97706', '#CA2C2A'];

        const ctxKelas = document.getElementById('chartKelasPoint');
        if (ctxKelas) {
            new Chart(ctxKelas, {
                type: 'bar',
                data: {
                    labels: kelasLabels,
                    datasets: [{
                        label: 'Akumulasi Point Kelas',
                        data: kelasPoints,
                        backgroundColor: kelasLabels.map((_, i) => kelasColors[i % kelasColors.length]),
                        borderColor: '#000000',
                        borderWidth: 2,
                        borderSkipped: false,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#000000',
                            padding: 10,
                            cornerRadius: 0,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Total: ' + context.parsed.x + ' Point';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 2,
                                precision: 0,
                                font: { size: 10, weight: '800' }
                            },
                            grid: { color: '#E5E7EB' },
                            border: { color: '#000000', width: 2 }
                        },
                        y: {
                            grid: { display: false },
                            border: { color: '#000000', width: 2 },
                            ticks: {
                                font: { size: 11, weight: '800' },
                                color: '#1A467C'
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
