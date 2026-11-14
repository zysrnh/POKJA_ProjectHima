@extends('admin.layouts.app')

@section('title', 'Kuesioner 6 Pertemuan')
@section('header_title', 'Kuesioner 6 Pertemuan POKJA')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- CARD 1: BANNER UTAMA (Clean Style Konsisten dengan Halaman Admin Lainnya) -->
    <div class="snap-in-1 bg-white border-2 border-[#000000] p-4 sm:p-5 shadow-[5px_5px_0px_0px_#000000]">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base sm:text-xl font-black uppercase tracking-tight text-[#1A467C]">
                    Kuesioner 6 Pertemuan &amp; Rekap Jawaban
                </h3>
                <p class="text-xs font-semibold text-gray-600 mt-0.5">
                    Kelola pertanyaan dinamis (Isian Singkat, Paragraf, Pilihan Ganda) serta pantau &amp; ekspor jawaban anggota.
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a
                    href="{{ route('admin.kuesioner.export', ['pertemuan' => $pertemuanFilter, 'search' => $search, 'kelas' => $kelasFilter, 'status' => $statusFilter]) }}"
                    class="btn-smooth px-3.5 py-2 bg-green-700 hover:bg-green-800 text-white border-2 border-black font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] inline-flex items-center gap-1.5"
                    title="Download Rekap CSV Pertemuan {{ $pertemuanFilter }}"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Export CSV</span>
                </a>

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
            </div>
        </div>
    </div>

    <!-- Alert Validation Errors -->
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
                        href="{{ route('admin.kuesioner.index', ['pertemuan' => $pNum, 'search' => $search, 'kelas' => $kelasFilter, 'status' => $statusFilter]) }}"
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

    <!-- RINGKASAN STATISTIK -->
    <div class="snap-in-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] p-4">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Responden P{{ $pertemuanFilter }}</p>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-[#1A467C]">{{ $totalResponden }}</span>
                <span class="text-xs font-bold text-gray-600">/ {{ $totalPeserta }} Anggota</span>
            </div>
        </div>

        <div class="bg-white border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] p-4">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Partisipasi Pengisian</p>
            <div class="flex items-center gap-3 mt-1.5">
                <span class="text-2xl font-black text-green-700">{{ $persenResponden }}%</span>
                <div class="flex-1 h-2.5 bg-gray-200 border border-black overflow-hidden">
                    <div class="h-full bg-green-600" style="width: {{ min(100, (int) $persenResponden) }}%;"></div>
                </div>
            </div>
        </div>

        <div class="bg-white border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] p-4">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Pertanyaan Aktif (P{{ $pertemuanFilter }})</p>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-black">{{ $pertanyaans->where('aktif', true)->count() }}</span>
                <span class="text-xs font-bold text-gray-600">dari {{ $pertanyaans->count() }} pertanyaan</span>
            </div>
        </div>
    </div>

    <!-- DAFTAR PERTANYAAN DINAMIS -->
    <div class="snap-in-3 bg-white border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-5">
        <div class="flex items-center justify-between gap-2 pb-3 border-b-2 border-black">
            <h4 class="text-sm font-black uppercase tracking-wider text-[#1A467C]">
                Daftar Pertanyaan (Pertemuan {{ $pertemuanFilter }})
            </h4>
            <span class="text-[11px] font-bold text-gray-500">
                {{ $pertanyaans->count() }} item
            </span>
        </div>

        @if($pertanyaans->isEmpty())
            <div class="mt-4 p-5 bg-gray-50 border-2 border-dashed border-gray-300 text-center">
                <p class="text-xs font-black uppercase text-gray-700">Belum Ada Pertanyaan untuk Pertemuan {{ $pertemuanFilter }}</p>
                <p class="text-xs text-gray-500 mt-1">Klik tombol "Tambah Pertanyaan" di atas untuk menambahkan.</p>
            </div>
        @else
            <div class="mt-3.5 space-y-2.5">
                @foreach($pertanyaans as $q)
                    <div class="p-3.5 bg-gray-50 border-2 border-black flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                        <div class="space-y-1.5 min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="px-1.5 py-0.5 bg-[#1A467C] text-white border border-black text-[10px] font-black">
                                    #{{ $q->urutan }}
                                </span>
                                <span class="px-2 py-0.5 bg-white text-black border border-black text-[10px] font-black uppercase">
                                    {{ \App\Models\KuesionerPertanyaan::TIPE_LABELS[$q->tipe] ?? $q->tipe }}
                                </span>
                                <span class="px-2 py-0.5 bg-blue-50 text-[#1A467C] border border-black text-[10px] font-black uppercase">
                                    {{ $q->pertemuan ? 'P' . $q->pertemuan : 'Semua (P1-P6)' }}
                                </span>
                                @if($q->wajib)
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-900 border border-black text-[10px] font-black uppercase">
                                        Wajib
                                    </span>
                                @endif
                                @if(!$q->aktif)
                                    <span class="px-2 py-0.5 bg-gray-200 text-gray-700 border border-black text-[10px] font-black uppercase">
                                        Nonaktif
                                    </span>
                                @endif
                                <span class="text-[11px] font-bold text-gray-600 ml-1">
                                    • {{ $q->jawabans_count }} jawaban
                                </span>
                            </div>

                            <p class="text-xs sm:text-sm font-black text-black leading-snug">
                                {{ $q->pertanyaan }}
                            </p>

                            @if($q->tipe === 'pilihan' && is_array($q->opsi) && count($q->opsi) > 0)
                                <div class="flex flex-wrap gap-1.5 pt-0.5">
                                    @foreach($q->opsi as $opt)
                                        <span class="px-2 py-0.5 bg-white border border-gray-400 text-[11px] font-bold text-gray-700">
                                            {{ $opt }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button
                                type="button"
                                onclick='openPertanyaanModal("edit", @json($q))'
                                class="btn-smooth px-3 py-1.5 bg-white hover:bg-yellow-100 text-black border-2 border-black font-black text-[11px] uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                            >
                                Edit
                            </button>

                            <form
                                action="{{ route('admin.kuesioner.pertanyaan.destroy', $q->id) }}"
                                method="POST"
                                onsubmit="return confirm('Hapus pertanyaan ini? Semua jawaban anggota untuk pertanyaan ini juga akan terhapus.');"
                            >
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="current_pertemuan" value="{{ $pertemuanFilter }}">
                                <button
                                    type="submit"
                                    class="btn-smooth px-3 py-1.5 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border-2 border-black font-black text-[11px] uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                                >
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- STATISTIK PILIHAN GANDA (JIKA ADA) -->
    @if(!empty($pilihanStats))
        <div class="snap-in-3 bg-white border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-5">
            <div class="pb-3 border-b-2 border-black">
                <h4 class="text-sm font-black uppercase tracking-wider text-[#1A467C]">
                    Rekap Pilihan Ganda (Pertemuan {{ $pertemuanFilter }})
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

    <!-- REKAP JAWABAN PER ANGGOTA -->
    <div class="snap-in-4 bg-white border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-5 space-y-4">
        <div class="pb-3 border-b-2 border-black flex items-center justify-between">
            <h4 class="text-sm font-black uppercase tracking-wider text-[#1A467C]">
                Rekap Jawaban Anggota (Pertemuan {{ $pertemuanFilter }})
            </h4>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.kuesioner.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-2.5">
            <input type="hidden" name="pertemuan" value="{{ $pertemuanFilter }}">
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari Nama / NIM..."
                class="px-3 py-2 bg-white border-2 border-black text-xs font-bold text-black focus:outline-none focus:bg-yellow-50"
            >

            <select name="kelas" class="px-3 py-2 bg-white border-2 border-black text-xs font-bold text-black focus:outline-none">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kls)
                    <option value="{{ $kls->nama_kelas }}" {{ $kelasFilter === $kls->nama_kelas ? 'selected' : '' }}>
                        {{ $kls->nama_kelas }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="px-3 py-2 bg-white border-2 border-black text-xs font-bold text-black focus:outline-none">
                <option value="">Semua Status</option>
                <option value="sudah" {{ $statusFilter === 'sudah' ? 'selected' : '' }}>Sudah Mengisi</option>
                <option value="belum" {{ $statusFilter === 'belum' ? 'selected' : '' }}>Belum Mengisi</option>
            </select>

            <div class="flex items-center gap-2">
                <button
                    type="submit"
                    class="btn-smooth flex-1 px-4 py-2 bg-[#1A467C] hover:bg-[#12335C] text-white border-2 border-black font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    Filter
                </button>
                @if($search !== '' || $kelasFilter !== '' || $statusFilter !== '')
                    <a
                        href="{{ route('admin.kuesioner.index', ['pertemuan' => $pertemuanFilter]) }}"
                        class="btn-smooth px-3 py-2 bg-white hover:bg-gray-100 text-black border-2 border-black font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000000]"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Tabel Responden -->
        <div class="overflow-x-auto border-2 border-black">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#1A467C] text-white text-[11px] font-black uppercase tracking-wider">
                    <tr>
                        <th class="px-3 py-2.5 border-b-2 border-r border-black">Anggota</th>
                        <th class="px-3 py-2.5 border-b-2 border-r border-black">Kelas</th>
                        <th class="px-3 py-2.5 border-b-2 border-r border-black">Status</th>
                        <th class="px-3 py-2.5 border-b-2 border-r border-black">Update Terakhir</th>
                        <th class="px-3 py-2.5 border-b-2 border-black text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black text-xs font-bold">
                    @forelse($respondenList as $mhs)
                        @php
                            $sudahIsi = $mhs->kuesioner_jawabans_count > 0;
                            $jawabanByQ = $mhs->kuesionerJawabans->keyBy('pertanyaan_id');
                            $detailPayload = [
                                'nama' => $mhs->nama,
                                'nim' => $mhs->nim,
                                'kelas' => $mhs->kelas,
                                'items' => $pertanyaans->map(function ($q) use ($jawabanByQ) {
                                    return [
                                        'pertanyaan' => $q->pertanyaan,
                                        'jawaban' => $jawabanByQ->get($q->id)?->jawaban ?? '-',
                                    ];
                                })->values()->all(),
                            ];
                        @endphp
                        <tr class="hover:bg-blue-50/60">
                            <td class="px-3 py-2.5 border-r border-black">
                                <div class="font-black text-black">{{ $mhs->nama }}</div>
                                <div class="text-[11px] text-gray-600 font-mono">{{ $mhs->nim }}</div>
                            </td>
                            <td class="px-3 py-2.5 border-r border-black">{{ $mhs->kelas }}</td>
                            <td class="px-3 py-2.5 border-r border-black">
                                @if($sudahIsi)
                                    <span class="inline-block px-2 py-0.5 bg-green-100 text-green-900 border border-black text-[10px] font-black uppercase">
                                        Sudah ({{ $mhs->kuesioner_jawabans_count }})
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-600 border border-black text-[10px] font-black uppercase">
                                        Belum
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-2.5 border-r border-black text-[11px] text-gray-700 font-mono">
                                @if($mhs->kuesioner_jawabans_max_updated_at)
                                    {{ \Carbon\Carbon::parse($mhs->kuesioner_jawabans_max_updated_at)->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-3 py-2.5 text-center">
                                @if($sudahIsi)
                                    <button
                                        type="button"
                                        onclick='openDetailJawabanModal(@json($detailPayload))'
                                        class="btn-smooth px-3 py-1 bg-white hover:bg-yellow-100 text-black border-2 border-black text-[10px] font-black uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                                    >
                                        Lihat Jawaban
                                    </button>
                                @else
                                    <span class="text-gray-400 text-[11px]">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500 font-bold">
                                Tidak ada data anggota sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($respondenList->hasPages())
            <div class="pt-2">
                {{ $respondenList->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah / Edit Pertanyaan -->
<div id="pertanyaanModal" class="fixed inset-0 z-50 hidden bg-black/60 items-center justify-center p-4">
    <div class="bg-white border-[3px] border-black shadow-[8px_8px_0px_0px_#000000] w-full max-w-lg p-5 sm:p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b-2 border-black">
            <h3 id="pertanyaanModalTitle" class="text-base sm:text-lg font-black uppercase tracking-tight text-black">
                Tambah Pertanyaan
            </h3>
            <button
                type="button"
                onclick="closePertanyaanModal()"
                class="w-8 h-8 bg-white hover:bg-red-100 border-2 border-black font-black text-sm flex items-center justify-center cursor-pointer"
            >
                ✕
            </button>
        </div>

        <form id="pertanyaanForm" method="POST" action="{{ route('admin.kuesioner.pertanyaan.store') }}" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="pertanyaanFormMethod" value="POST">
            <input type="hidden" name="current_pertemuan" value="{{ $pertemuanFilter }}">

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">
                    Teks Pertanyaan <span class="text-red-600">*</span>
                </label>
                <textarea
                    id="inputPertanyaan"
                    name="pertanyaan"
                    rows="3"
                    required
                    maxlength="1000"
                    placeholder="Contoh: Apa masukan kamu untuk kegiatan POKJA berikutnya?"
                    class="w-full px-3 py-2 bg-white border-2 border-black text-xs sm:text-sm font-bold text-black focus:outline-none focus:bg-yellow-50"
                ></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">
                        Tipe Jawaban <span class="text-red-600">*</span>
                    </label>
                    <select
                        id="inputTipe"
                        name="tipe"
                        onchange="toggleOpsiField()"
                        class="w-full px-3 py-2 bg-white border-2 border-black text-xs sm:text-sm font-bold text-black focus:outline-none"
                    >
                        <option value="teks">Isian Singkat</option>
                        <option value="paragraf">Paragraf / Uraian</option>
                        <option value="pilihan">Pilihan Ganda</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">
                        Urutan Tampil
                    </label>
                    <input
                        id="inputUrutan"
                        type="number"
                        name="urutan"
                        min="0"
                        max="9999"
                        placeholder="Otomatis"
                        class="w-full px-3 py-2 bg-white border-2 border-black text-xs sm:text-sm font-bold text-black focus:outline-none focus:bg-yellow-50"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">
                    Berlaku Untuk Pertemuan
                </label>
                <select
                    id="inputPertemuan"
                    name="pertemuan"
                    class="w-full px-3 py-2 bg-white border-2 border-black text-xs sm:text-sm font-bold text-black focus:outline-none"
                >
                    <option value="">Semua Pertemuan (P1 - P6)</option>
                    @foreach($pertemuanList as $pNum)
                        <option value="{{ $pNum }}">Pertemuan {{ $pNum }}</option>
                    @endforeach
                </select>
            </div>

            <div id="opsiFieldWrapper" class="hidden">
                <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">
                    Opsi Pilihan Ganda (1 baris = 1 pilihan) <span class="text-red-600">*</span>
                </label>
                <textarea
                    id="inputOpsiText"
                    name="opsi_text"
                    rows="4"
                    placeholder="Sangat Bermanfaat&#10;Cukup Bermanfaat&#10;Perlu Ditingkatkan"
                    class="w-full px-3 py-2 bg-white border-2 border-black text-xs sm:text-sm font-bold text-black focus:outline-none focus:bg-yellow-50"
                ></textarea>
            </div>

            <div class="flex flex-wrap items-center gap-4 pt-1">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input id="inputWajib" type="checkbox" name="wajib" value="1" checked class="w-4 h-4 accent-[#1A467C]">
                    <span class="text-xs font-black uppercase text-black">Wajib Diisi</span>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input id="inputAktif" type="checkbox" name="aktif" value="1" checked class="w-4 h-4 accent-[#1A467C]">
                    <span class="text-xs font-black uppercase text-black">Aktifkan Pertanyaan</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t-2 border-black">
                <button
                    type="button"
                    onclick="closePertanyaanModal()"
                    class="btn-smooth px-4 py-2 bg-white hover:bg-gray-100 text-black border-2 border-black font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="btn-smooth px-5 py-2 bg-[#1A467C] hover:bg-[#12335C] text-white border-2 border-black font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                >
                    Simpan Pertanyaan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Jawaban Anggota -->
<div id="detailJawabanModal" class="fixed inset-0 z-50 hidden bg-black/60 items-center justify-center p-4">
    <div class="bg-white border-[3px] border-black shadow-[8px_8px_0px_0px_#000000] w-full max-w-2xl p-5 sm:p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-3 pb-3 border-b-2 border-black">
            <div>
                <h3 id="detailMhsNama" class="text-base sm:text-lg font-black uppercase tracking-tight text-black">-</h3>
                <p id="detailMhsMeta" class="text-xs font-bold text-gray-600 mt-0.5">-</p>
            </div>
            <button
                type="button"
                onclick="closeDetailJawabanModal()"
                class="w-8 h-8 bg-white hover:bg-red-100 border-2 border-black font-black text-sm flex items-center justify-center cursor-pointer shrink-0"
            >
                ✕
            </button>
        </div>

        <div id="detailJawabanBody" class="mt-4 space-y-3"></div>

        <div class="flex justify-end pt-4 mt-4 border-t-2 border-black">
            <button
                type="button"
                onclick="closeDetailJawabanModal()"
                class="btn-smooth px-4 py-2 bg-[#1A467C] text-white border-2 border-black font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
            >
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    const storePertanyaanUrl = @json(route('admin.kuesioner.pertanyaan.store'));
    const updatePertanyaanUrlTpl = @json(route('admin.kuesioner.pertanyaan.update', ['id' => '__ID__']));
    const defaultAdminPertemuan = @json((string) $pertemuanFilter);

    function toggleOpsiField() {
        const tipe = document.getElementById('inputTipe').value;
        const wrapper = document.getElementById('opsiFieldWrapper');
        if (tipe === 'pilihan') {
            wrapper.classList.remove('hidden');
        } else {
            wrapper.classList.add('hidden');
        }
    }

    function openPertanyaanModal(mode, data = null) {
        const modal = document.getElementById('pertanyaanModal');
        const title = document.getElementById('pertanyaanModalTitle');
        const form = document.getElementById('pertanyaanForm');
        const methodInput = document.getElementById('pertanyaanFormMethod');

        if (mode === 'edit' && data) {
            title.textContent = 'Edit Pertanyaan';
            form.action = updatePertanyaanUrlTpl.replace('__ID__', data.id);
            methodInput.value = 'PUT';

            document.getElementById('inputPertanyaan').value = data.pertanyaan || '';
            document.getElementById('inputTipe').value = data.tipe || 'teks';
            document.getElementById('inputUrutan').value = data.urutan ?? '';
            document.getElementById('inputPertemuan').value = data.pertemuan ? String(data.pertemuan) : '';
            document.getElementById('inputOpsiText').value = Array.isArray(data.opsi) ? data.opsi.join('\n') : '';
            document.getElementById('inputWajib').checked = Boolean(data.wajib);
            document.getElementById('inputAktif').checked = Boolean(data.aktif);
        } else {
            title.textContent = 'Tambah Pertanyaan';
            form.action = storePertanyaanUrl;
            methodInput.value = 'POST';

            document.getElementById('inputPertanyaan').value = '';
            document.getElementById('inputTipe').value = 'teks';
            document.getElementById('inputUrutan').value = '';
            document.getElementById('inputPertemuan').value = defaultAdminPertemuan;
            document.getElementById('inputOpsiText').value = '';
            document.getElementById('inputWajib').checked = true;
            document.getElementById('inputAktif').checked = true;
        }

        toggleOpsiField();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePertanyaanModal() {
        const modal = document.getElementById('pertanyaanModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openDetailJawabanModal(payload) {
        document.getElementById('detailMhsNama').textContent = payload.nama || '-';
        document.getElementById('detailMhsMeta').textContent = `${payload.nim || '-'} • ${payload.kelas || '-'}`;

        const body = document.getElementById('detailJawabanBody');
        body.innerHTML = '';

        const items = Array.isArray(payload.items) ? payload.items : [];
        items.forEach((item, index) => {
            const card = document.createElement('div');
            card.className = 'p-3.5 bg-gray-50 border-2 border-black';

            const qTitle = document.createElement('p');
            qTitle.className = 'text-xs font-black text-[#1A467C] uppercase';
            qTitle.textContent = `Pertanyaan #${index + 1}`;

            const qText = document.createElement('p');
            qText.className = 'text-xs sm:text-sm font-black text-black mt-0.5';
            qText.textContent = item.pertanyaan || '-';

            const aText = document.createElement('p');
            aText.className = 'text-xs sm:text-sm font-semibold text-gray-800 mt-2 pt-2 border-t border-gray-300 whitespace-pre-line';
            aText.textContent = item.jawaban || '-';

            card.appendChild(qTitle);
            card.appendChild(qText);
            card.appendChild(aText);
            body.appendChild(card);
        });

        const modal = document.getElementById('detailJawabanModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDetailJawabanModal() {
        const modal = document.getElementById('detailJawabanModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
