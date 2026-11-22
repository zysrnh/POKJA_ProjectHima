@extends('admin.layouts.app')

@section('title', 'Overview Kuesioner')
@section('header_title', 'Overview Kuesioner POKJA')

@section('content')
<div class="space-y-6">

    @include('admin.kuesioner.partials.header_and_tabs', [
        'activeRoute' => 'admin.kuesioner.index',
        'subtitleTag' => 'OVERVIEW KUESIONER',
        'pageHeading' => 'Ringkasan & Statistik Kuesioner',
        'pageDesc' => 'Pantau partisipasi pengisian, distribusi pilihan ganda, dan aktivitas pengisian anggota.',
        'showTambahPertanyaan' => true,
    ])

    <!-- 3 KARTU STATISTIK RINGKASAN -->
    <div class="snap-in-2 grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-5 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-black uppercase tracking-wider text-[#1A467C]">Responden P{{ $pertemuanFilter }}</p>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-gray-950 font-mono">{{ $totalResponden }}</span>
                    <span class="text-xs font-bold text-gray-600">/ {{ $totalPeserta }} Anggota</span>
                </div>
            </div>
            <div class="w-11 h-11 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-5 flex items-center justify-between gap-3">
            <div class="flex-1 min-w-0">
                <p class="text-[11px] font-black uppercase tracking-wider text-[#15803d]">Partisipasi Pengisian</p>
                <div class="flex items-center gap-3 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-[#15803d] font-mono">{{ $persenResponden }}%</span>
                    <div class="flex-1 h-2.5 bg-gray-200 border border-black overflow-hidden">
                        <div class="h-full bg-green-600" style="width: {{ min(100, (int) $persenResponden) }}%;"></div>
                    </div>
                </div>
            </div>
            <div class="w-11 h-11 bg-amber-400 text-black border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </div>

        <div class="bg-white border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-5 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-black uppercase tracking-wider text-black">Pertanyaan Aktif (P{{ $pertemuanFilter }})</p>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-black font-mono">{{ $pertanyaans->where('aktif', true)->count() }}</span>
                    <span class="text-xs font-bold text-gray-600">dari {{ $pertanyaans->count() }} item</span>
                </div>
            </div>
            <div class="w-11 h-11 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- PREVIEW 2 KOLOM: PERTANYAAN TERATAS & RESPONDEN TERBARU -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Preview Daftar Pertanyaan -->
        <div class="snap-in-3 bg-white border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-5 flex flex-col justify-between gap-4">
            <div>
                <div class="flex items-center justify-between pb-3 border-b-2 border-black">
                    <h4 class="text-sm font-black uppercase tracking-wider text-[#1A467C]">
                        Preview Pertanyaan (P{{ $pertemuanFilter }})
                    </h4>
                    <a href="{{ route('admin.kuesioner.pertanyaan', ['pertemuan' => $pertemuanFilter]) }}" class="text-xs font-black uppercase text-[#2A82C6] hover:underline">
                        Kelola Semua →
                    </a>
                </div>

                @if($pertanyaans->isEmpty())
                    <p class="text-xs font-bold text-gray-500 py-6 text-center">Belum ada pertanyaan pada pertemuan ini.</p>
                @else
                    <div class="mt-3 space-y-2">
                        @foreach($pertanyaans->take(5) as $q)
                            <div class="p-2.5 bg-gray-50 border border-black flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <span class="px-1.5 py-0.5 bg-[#1A467C] text-white text-[10px] font-black">#{{ $q->urutan }}</span>
                                        <span class="text-[10px] font-black uppercase text-gray-600">{{ \App\Models\KuesionerPertanyaan::TIPE_LABELS[$q->tipe] ?? $q->tipe }}</span>
                                    </div>
                                    <p class="text-xs font-bold text-black mt-1 truncate">{{ $q->pertanyaan }}</p>
                                </div>
                                <span class="text-[10px] font-mono font-black text-gray-600 shrink-0">{{ $q->jawabans_count }} jwb</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Preview Responden Terbaru -->
        <div class="snap-in-3 bg-white border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-5 flex flex-col justify-between gap-4">
            <div>
                <div class="flex items-center justify-between pb-3 border-b-2 border-black">
                    <h4 class="text-sm font-black uppercase tracking-wider text-[#1A467C]">
                        Pengisian Terbaru (P{{ $pertemuanFilter }})
                    </h4>
                    <a href="{{ route('admin.kuesioner.rekap', ['pertemuan' => $pertemuanFilter]) }}" class="text-xs font-black uppercase text-[#2A82C6] hover:underline">
                        Rekap Lengkap →
                    </a>
                </div>

                @if($recentResponden->isEmpty())
                    <p class="text-xs font-bold text-gray-500 py-6 text-center">Belum ada anggota yang mengisi kuesioner P{{ $pertemuanFilter }}.</p>
                @else
                    <div class="mt-3 space-y-2">
                        @foreach($recentResponden as $mhs)
                            @php
                                $jawabanByQ = $mhs->kuesionerJawabans->keyBy('pertanyaan_id');
                                $detailPayload = [
                                    'nama' => $mhs->nama,
                                    'nim' => $mhs->nim,
                                    'kelas' => $mhs->kelas,
                                    'items' => $pertanyaans->map(fn ($q) => [
                                        'pertanyaan' => $q->pertanyaan,
                                        'jawaban' => $jawabanByQ->get($q->id)?->jawaban ?? '-',
                                    ])->values()->all(),
                                ];
                            @endphp
                            <div class="p-2.5 bg-gray-50 border border-black flex items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-black truncate">{{ $mhs->nama }}</p>
                                    <p class="text-[10px] font-mono text-gray-600">{{ $mhs->nim }} • {{ $mhs->kelas }}</p>
                                </div>
                                <button
                                    type="button"
                                    onclick='openDetailJawabanModal(@json($detailPayload))'
                                    class="btn-smooth px-2.5 py-1 bg-white hover:bg-yellow-100 text-black border border-black text-[10px] font-black uppercase cursor-pointer shrink-0"
                                >
                                    Lihat
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- STATISTIK PILIHAN GANDA -->
    @if(!empty($pilihanStats))
        <div class="snap-in-4 bg-white border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-5">
            <div class="pb-3 border-b-2 border-black">
                <h4 class="text-sm font-black uppercase tracking-wider text-[#1A467C]">
                    Statistik Pilihan Ganda (Pertemuan {{ $pertemuanFilter }})
                </h4>
            </div>

            <div class="mt-3.5 grid grid-cols-1 lg:grid-cols-2 gap-3.5">
                @foreach($pertanyaans->where('tipe', 'pilihan') as $q)
                    @php
                        $stat = $pilihanStats[$q->id] ?? ['total' => 0, 'options' => []];
                    @endphp
                    <div class="p-3.5 bg-gray-50 border-2 border-black">
                        <p class="text-xs sm:text-sm font-black text-black mb-1">{{ $q->pertanyaan }}</p>
                        <p class="text-[11px] font-bold text-gray-500 mb-2.5">Total jawaban: {{ $stat['total'] }}</p>
                        <div class="space-y-2">
                            @foreach($stat['options'] as $opt)
                                <div>
                                    <div class="flex items-center justify-between text-xs font-bold text-gray-800 mb-1">
                                        <span>{{ $opt['label'] }}</span>
                                        <span>{{ $opt['count'] }} ({{ $opt['pct'] }}%)</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-white border border-black overflow-hidden">
                                        <div class="h-full bg-[#1A467C]" style="width: {{ min(100, (int) $opt['pct']) }}%;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

@include('admin.kuesioner.partials.pertanyaan_modal')
@include('admin.kuesioner.partials.detail_jawaban_modal')
@endsection
