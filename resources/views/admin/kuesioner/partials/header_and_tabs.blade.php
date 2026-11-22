@php
    $activeRoute = $activeRoute ?? 'admin.kuesioner.index';
@endphp

<!-- CARD 1: HEADER BANNER -->
<div class="snap-in-1 bg-white border-2 border-[#000000] p-4 sm:p-5 shadow-[5px_5px_0px_0px_#000000]">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-block bg-amber-300 border border-black px-2 py-0.5 text-[10px] font-black uppercase tracking-wider mb-1.5">
                {{ $subtitleTag ?? 'KUESIONER PANEL' }}
            </span>
            <h3 class="text-lg sm:text-xl font-black uppercase tracking-tight text-[#1A467C]">
                {{ $pageHeading ?? 'Kuesioner Dinamis POKJA' }}
            </h3>
            <p class="text-xs font-semibold text-gray-600 mt-0.5">
                {{ $pageDesc ?? 'Kelola pertanyaan dinamis serta pantau & ekspor jawaban anggota per pertemuan.' }}
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <button
                type="button"
                id="btnCopyKuesionerLink"
                onclick="copyKuesionerLink()"
                class="btn-smooth px-3.5 py-2 bg-amber-300 hover:bg-amber-400 text-black border-2 border-black font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] inline-flex items-center gap-1.5 cursor-pointer"
                title="Salin link langsung kuesioner Pertemuan {{ $pertemuanFilter }} untuk peserta"
            >
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span id="copyKuesionerLinkText">Salin Link</span>
            </button>

            <a
                href="{{ route('admin.kuesioner.export', ['pertemuan' => $pertemuanFilter, 'search' => $search ?? '', 'kelas' => $kelasFilter ?? '', 'status' => $statusFilter ?? '']) }}"
                class="btn-smooth px-3.5 py-2 bg-green-700 hover:bg-green-800 text-white border-2 border-black font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] inline-flex items-center gap-1.5"
                title="Download Rekap CSV Pertemuan {{ $pertemuanFilter }}"
            >
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Export CSV</span>
            </a>

            @if(!empty($showTambahPertanyaan))
                <button
                    type="button"
                    onclick="openPertanyaanModal('create')"
                    class="btn-smooth px-3.5 py-2 bg-[#1A467C] hover:bg-[#12335C] text-white border-2 border-black font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] inline-flex items-center gap-1.5 cursor-pointer"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Pertanyaan</span>
                </button>
            @endif
        </div>
    </div>
</div>

@if($errors->any())
    <div class="bg-red-50 border-2 border-[#000000] shadow-[4px_4px_0px_0px_#CA2C2A] p-3.5">
        <p class="text-xs font-black uppercase tracking-wider text-red-900 mb-1">Periksa Input Anda:</p>
        <ul class="list-disc list-inside text-xs font-bold text-red-800 space-y-0.5">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- CARD 2: TAB PILIHAN P1 - P6 -->
<div class="snap-in-2 bg-white border-2 border-[#000000] p-3 sm:p-4 shadow-[4px_4px_0px_0px_#000000]">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <div class="flex flex-wrap items-center gap-1.5">
            @foreach($pertemuanList as $pNum)
                <a
                    href="{{ route($activeRoute, array_filter(['pertemuan' => $pNum, 'search' => $search ?? null, 'kelas' => $kelasFilter ?? null, 'status' => $statusFilter ?? null], fn ($v) => $v !== null && $v !== '')) }}"
                    class="btn-smooth px-3 py-1.5 border-2 border-black text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5 {{ (int) $pertemuanFilter === (int) $pNum ? 'bg-[#1A467C] text-white shadow-[2px_2px_0px_0px_#000000]' : 'bg-white hover:bg-gray-100 text-black' }}"
                >
                    <span>P{{ $pNum }}</span>
                    @if((int) $pNum === (int) $pertemuanAktif)
                        <span class="w-2 h-2 bg-amber-300 border border-black inline-block" title="Pertemuan Aktif"></span>
                    @endif
                </a>
            @endforeach
        </div>

        <span class="text-[11px] font-black uppercase tracking-wider text-[#1A467C] bg-blue-50 border border-black px-2.5 py-1">
            Pertemuan {{ $pertemuanFilter }}
        </span>
    </div>
</div>
