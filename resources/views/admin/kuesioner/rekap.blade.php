@extends('admin.layouts.app')

@section('title', 'Rekap Jawaban Kuesioner')
@section('header_title', 'Rekap Jawaban Kuesioner')

@section('content')
<div class="space-y-6">

    @include('admin.kuesioner.partials.header_and_tabs', [
        'activeRoute' => 'admin.kuesioner.rekap',
        'subtitleTag' => 'REKAP JAWABAN',
        'pageHeading' => 'Rekap Jawaban Anggota',
        'pageDesc' => 'Filter berdasarkan nama, kelas, atau status pengisian serta lihat detail jawaban tiap peserta.',
        'showTambahPertanyaan' => false,
    ])

    <div class="snap-in-3 bg-white border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-5 space-y-4">
        <div class="pb-3 border-b-2 border-black flex items-center justify-between">
            <h4 class="text-sm font-black uppercase tracking-wider text-[#1A467C]">
                Daftar Anggota (Pertemuan {{ $pertemuanFilter }})
            </h4>
            <span class="text-[11px] font-bold text-gray-600">
                {{ $totalResponden }} / {{ $totalPeserta }} sudah mengisi
            </span>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.kuesioner.rekap') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-2.5">
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
                        href="{{ route('admin.kuesioner.rekap', ['pertemuan' => $pertemuanFilter]) }}"
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

@include('admin.kuesioner.partials.pertanyaan_modal')
@include('admin.kuesioner.partials.detail_jawaban_modal')
@endsection
