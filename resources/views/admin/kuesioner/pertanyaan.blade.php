@extends('admin.layouts.app')

@section('title', 'Kelola Pertanyaan Kuesioner')
@section('header_title', 'Kelola Pertanyaan Kuesioner')

@section('content')
<div class="space-y-6">

    @include('admin.kuesioner.partials.header_and_tabs', [
        'activeRoute' => 'admin.kuesioner.pertanyaan',
        'subtitleTag' => 'KELOLA PERTANYAAN',
        'pageHeading' => 'Daftar Pertanyaan Kuesioner',
        'pageDesc' => 'Tambah, ubah, atur urutan, atau nonaktifkan pertanyaan kuesioner per pertemuan.',
        'showTambahPertanyaan' => true,
    ])

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

</div>

@include('admin.kuesioner.partials.pertanyaan_modal')
@endsection
