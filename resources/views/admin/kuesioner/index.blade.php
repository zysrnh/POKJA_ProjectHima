@extends('admin.layouts.app')

@section('title', 'Kelola & Rekap Kuesioner')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="snap-in-1 bg-white border-2 sm:border-[3px] border-black shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-[#1A467C] border border-black inline-block"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-[#1A467C]">Evaluasi &amp; Masukan Anggota</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-black uppercase tracking-tight mt-1">
                Kuesioner Dinamis POKJA
            </h1>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mt-1">
                Kelola daftar pertanyaan dinamis (Teks, Paragraf, Pilihan Ganda) serta pantau &amp; ekspor jawaban anggota.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a
                href="{{ route('admin.kuesioner.export', ['search' => $search, 'kelas' => $kelasFilter, 'status' => $statusFilter]) }}"
                class="btn-smooth px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white border-2 border-black font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] inline-flex items-center gap-2"
            >
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Export CSV</span>
            </a>

            <button
                type="button"
                onclick="openPertanyaanModal('create')"
                class="btn-smooth px-4 py-2.5 bg-[#1A467C] hover:bg-[#12335C] text-white border-2 border-black font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] inline-flex items-center gap-2 cursor-pointer"
            >
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Pertanyaan</span>
            </button>
        </div>
    </div>

    <!-- Alert Validation Errors -->
    @if($errors->any())
        <div class="bg-red-100 border-2 border-black shadow-[4px_4px_0px_0px_#000000] p-4">
            <p class="text-xs font-black uppercase tracking-wider text-red-900 mb-1">Periksa Input Anda:</p>
            <ul class="list-disc list-inside text-xs font-bold text-red-800 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Ringkasan Statistik -->
    <div class="snap-in-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] p-4">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Total Responden</p>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl sm:text-3xl font-black text-[#1A467C]">{{ $totalResponden }}</span>
                <span class="text-xs font-bold text-gray-600">/ {{ $totalPeserta }} Anggota</span>
            </div>
        </div>

        <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] p-4">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Partisipasi Pengisian</p>
            <div class="flex items-center gap-3 mt-1.5">
                <span class="text-2xl sm:text-3xl font-black text-green-700">{{ $persenResponden }}%</span>
                <div class="flex-1 h-3 bg-gray-200 border-2 border-black overflow-hidden">
                    <div class="h-full bg-green-600" style="width: {{ min(100, (int) $persenResponden) }}%;"></div>
                </div>
            </div>
        </div>

        <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] p-4">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Pertanyaan Aktif</p>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl sm:text-3xl font-black text-black">{{ $pertanyaans->where('aktif', true)->count() }}</span>
                <span class="text-xs font-bold text-gray-600">dari {{ $pertanyaans->count() }} total pertanyaan</span>
            </div>
        </div>
    </div>

    <!-- Daftar Pertanyaan Dinamis -->
    <div class="snap-in-3 bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-5">
        <div class="flex items-center justify-between gap-2 pb-3 border-b-2 border-black">
            <div>
                <h2 class="text-sm sm:text-base font-black uppercase tracking-wider text-black">
                    Daftar Pertanyaan Dinamis
                </h2>
                <p class="text-xs font-medium text-gray-600">
                    Pertanyaan aktif akan langsung tampil di dashboard mahasiswa.
                </p>
            </div>
        </div>

        @if($pertanyaans->isEmpty())
            <div class="mt-4 p-6 bg-gray-50 border-2 border-dashed border-gray-400 text-center">
                <p class="text-xs sm:text-sm font-black uppercase text-gray-700">Belum Ada Pertanyaan Kuesioner</p>
                <p class="text-xs text-gray-500 mt-1">Klik tombol "Tambah Pertanyaan" di atas untuk membuat pertanyaan pertama.</p>
            </div>
        @else
            <div class="mt-4 space-y-3">
                @foreach($pertanyaans as $q)
                    <div class="p-3.5 sm:p-4 bg-[#F8FAFC] border-2 border-black flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                        <div class="space-y-1.5 min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="px-2 py-0.5 bg-[#1A467C] text-white border border-black text-[10px] font-black">
                                    #{{ $q->urutan }}
                                </span>
                                <span class="px-2 py-0.5 bg-white text-black border border-black text-[10px] font-black uppercase">
                                    {{ \App\Models\KuesionerPertanyaan::TIPE_LABELS[$q->tipe] ?? $q->tipe }}
                                </span>
                                @if($q->wajib)
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-900 border border-black text-[10px] font-black uppercase">
                                        Wajib
                                    </span>
                                @endif
                                @if($q->aktif)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-900 border border-black text-[10px] font-black uppercase">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-200 text-gray-700 border border-black text-[10px] font-black uppercase">
                                        Nonaktif
                                    </span>
                                @endif
                                <span class="px-2 py-0.5 bg-blue-50 text-[#1A467C] border border-black text-[10px] font-black">
                                    {{ $q->jawabans_count }} Jawaban
                                </span>
                            </div>

                            <p class="text-xs sm:text-sm font-black text-black leading-snug">
                                {{ $q->pertanyaan }}
                            </p>

                            @if($q->tipe === 'pilihan' && is_array($q->opsi) && count($q->opsi) > 0)
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    @foreach($q->opsi as $opt)
                                        <span class="px-2 py-0.5 bg-white border border-gray-400 text-[11px] font-bold text-gray-700">
                                            • {{ $opt }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button
                                type="button"
                                onclick='openPertanyaanModal("edit", @json($q))'
                                class="btn-smooth px-3 py-1.5 bg-white hover:bg-yellow-100 text-black border-2 border-black font-black text-[11px] uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
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
                                <button
                                    type="submit"
                                    class="btn-smooth px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white border-2 border-black font-black text-[11px] uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
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

    <!-- Statistik Pilihan Ganda (Jika Ada) -->
    @if(!empty($pilihanStats))
        <div class="snap-in-3 bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-5">
            <div class="pb-3 border-b-2 border-black">
                <h2 class="text-sm sm:text-base font-black uppercase tracking-wider text-black">
                    Rekap Statistik Pilihan Ganda
                </h2>
            </div>

            <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($pertanyaans->where('tipe', 'pilihan') as $q)
                    @php
                        $stat = $pilihanStats[$q->id] ?? ['total' => 0, 'options' => []];
                    @endphp
                    <div class="p-4 bg-[#F8FAFC] border-2 border-black">
                        <p class="text-xs sm:text-sm font-black text-black mb-1">{{ $q->pertanyaan }}</p>
                        <p class="text-[11px] font-bold text-gray-500 mb-3">Total jawaban: {{ $stat['total'] }}</p>
                        <div class="space-y-2.5">
                            @foreach($stat['options'] as $opt)
                                <div>
                                    <div class="flex items-center justify-between text-xs font-bold text-gray-800 mb-1">
                                        <span>{{ $opt['label'] }}</span>
                                        <span>{{ $opt['count'] }} ({{ $opt['pct'] }}%)</span>
                                    </div>
                                    <div class="w-full h-3 bg-white border border-black overflow-hidden">
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

    <!-- Rekap Jawaban Per Anggota -->
    <div class="snap-in-4 bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-5 space-y-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 pb-3 border-b-2 border-black">
            <div>
                <h2 class="text-sm sm:text-base font-black uppercase tracking-wider text-black">
                    Rekap Jawaban Anggota
                </h2>
                <p class="text-xs font-medium text-gray-600">
                    Klik "Lihat Jawaban" untuk membaca seluruh jawaban tiap anggota.
                </p>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.kuesioner.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
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
                        href="{{ route('admin.kuesioner.index') }}"
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
                                <div class="text-[11px] text-gray-600">{{ $mhs->nim }}</div>
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
                            <td class="px-3 py-2.5 border-r border-black text-[11px] text-gray-700">
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
                        <option value="teks">Teks Singkat</option>
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
            card.className = 'p-3.5 bg-[#F8FAFC] border-2 border-black';

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
