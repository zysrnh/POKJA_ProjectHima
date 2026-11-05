@extends('admin.layouts.app')

@section('title', 'Presensi & Kehadiran')
@section('header_title', 'Presensi 7 Pertemuan POKJA')

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
    <!-- CARD 1: BANNER UTAMA & KONTROL PROYEKTOR (Enters First)        -->
    <!-- ============================================================== -->
    <div class="snap-in-1 bg-white border-2 border-[#000000] p-4 sm:p-6 shadow-[5px_5px_0px_0px_#000000]">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-block bg-[#1A467C] text-white text-[10px] font-black uppercase px-2.5 py-0.5 tracking-wider border border-black shadow-[1px_1px_0px_0px_#000000]">
                        SISTEM PRESENSI TERPADU
                    </span>
                    <span class="inline-block {{ $pengaturan->is_scan_aktif ? 'bg-green-100 text-green-900 border-green-800' : 'bg-red-100 text-red-900 border-red-800' }} text-[10px] font-black uppercase px-2 py-0.5 border shadow-[1px_1px_0px_0px_#000000]">
                        SESI SCAN: {{ $pengaturan->is_scan_aktif ? 'DIBUKA' : 'DITUTUP' }} (P{{ $pengaturan->pertemuan_aktif }})
                    </span>
                </div>
                <h3 class="text-base sm:text-xl font-black uppercase tracking-tight text-[#1A467C]">
                    Presensi Kelas 7 Pertemuan & Rekapitulasi
                </h3>
                <p class="text-xs font-semibold text-gray-600 mt-0.5">
                    Tampilkan QR dinamis (refresh 5 detik) di layar proyektor kelas atau kelola rekap kehadiran mahasiswa.
                </p>
            </div>

            <!-- Tombol Aksi Cepat -->
            <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
                <!-- Buka Layar Proyektor (Tab Baru) -->
                <a 
                    href="{{ route('admin.presensi.layar') }}" 
                    target="_blank"
                    class="btn-smooth flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#2A82C6] hover:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-[#000000] shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                >
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>BUKA LAYAR PROYEKTOR</span>
                </a>

                <!-- Sinkron Spreadsheet -->
                <form action="{{ route('admin.presensi.sync') }}" method="POST" class="inline flex-1 sm:flex-none">
                    @csrf
                    <button 
                        type="submit" 
                        onclick="return confirm('Sinkronkan seluruh data pendaftar & kehadiran 7 pertemuan ke Google Sheets?')"
                        class="btn-smooth w-full inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 bg-white hover:bg-blue-50 text-[#1A467C] text-xs font-black uppercase tracking-wider border-2 border-[#000000] shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                        title="Sinkronkan Sheet 1: Peserta & Sheet 2: Kehadiran"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Sinkron Sheets</span>
                    </button>
                </form>

                <!-- Export Excel -->
                <a 
                    href="{{ route('admin.presensi.export') }}" 
                    class="btn-smooth flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 bg-green-700 hover:bg-green-800 text-white text-xs font-black uppercase tracking-wider border-2 border-[#000000] shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                    title="Download Rekap 7 Pertemuan format .xls"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Export (.xls)</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- CARD 2: KONTROL SESI PERTEMUAN AKTIF                           -->
    <!-- ============================================================== -->
    <div class="snap-in-2 bg-white border-2 border-[#000000] p-4 sm:p-5 shadow-[4px_4px_0px_0px_#000000]">
        <form action="{{ route('admin.presensi.sesi') }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            @csrf
            
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-black uppercase tracking-wider text-gray-800">
                    Pilih Pertemuan Aktif:
                </span>
                <select 
                    name="pertemuan_aktif" 
                    class="bg-[#F8FBFE] border-2 border-black font-black text-xs sm:text-sm px-3 py-1.5 focus:outline-none focus:shadow-[2px_2px_0px_0px_#000000]"
                >
                    @for ($p = 1; $p <= 7; $p++)
                        <option value="{{ $p }}" {{ (int) $pengaturan->pertemuan_aktif === $p ? 'selected' : '' }}>
                            Pertemuan {{ $p }}
                        </option>
                    @endfor
                </select>

                <select 
                    name="is_scan_aktif" 
                    class="bg-[#F8FBFE] border-2 border-black font-black text-xs sm:text-sm px-3 py-1.5 focus:outline-none focus:shadow-[2px_2px_0px_0px_#000000]"
                >
                    <option value="1" {{ $pengaturan->is_scan_aktif ? 'selected' : '' }}>BUKA SCAN (Aktif)</option>
                    <option value="0" {{ !$pengaturan->is_scan_aktif ? 'selected' : '' }}>TUTUP SCAN (Nonaktif)</option>
                </select>
            </div>

            <button 
                type="submit" 
                class="btn-smooth px-4 py-2 bg-[#1A467C] hover:bg-[#0F2A4A] text-white text-xs font-black uppercase tracking-wider border-2 border-black shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
            >
                Simpan Status Sesi
            </button>
        </form>
    </div>

    <!-- ============================================================== -->
    <!-- CARD 3: TAB SELECTOR PERTEMUAN 1 - 7 & STATS                   -->
    <!-- ============================================================== -->
    <div class="snap-in-3 space-y-3">
        <!-- 7 Pertemuan Pill Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1">
            @for ($p = 1; $p <= 7; $p++)
                <a 
                    href="{{ route('admin.presensi.index', ['pertemuan' => $p, 'search' => $search, 'kelas' => $kelasFilter]) }}"
                    class="btn-smooth shrink-0 px-3.5 py-1.5 border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] {{ $pertemuanDipilih === $p ? 'bg-[#2A82C6] text-white' : 'bg-white text-gray-800 hover:bg-gray-100' }}"
                >
                    P{{ $p }}
                </a>
            @endfor
        </div>

        <!-- 3 Stat Cards untuk Pertemuan yang Dipilih -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 sm:gap-4">
            <div class="card-interactive bg-white border-2 border-black p-3.5 sm:p-4 shadow-[3px_3px_0px_0px_#000000] flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-600">Total Mahasiswa</span>
                    <h4 class="text-xl sm:text-2xl font-black text-gray-950 font-mono mt-0.5">{{ $totalMahasiswa }}</h4>
                </div>
                <span class="text-[9px] font-black text-[#1A467C] bg-blue-50 border border-[#2A82C6] px-1.5 py-0.5 uppercase">Mhs</span>
            </div>

            <div class="card-interactive bg-white border-2 border-black p-3.5 sm:p-4 shadow-[3px_3px_0px_0px_#000000] flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-green-800">Hadir di Pertemuan {{ $pertemuanDipilih }}</span>
                    <h4 class="text-xl sm:text-2xl font-black text-green-700 font-mono mt-0.5">{{ $totalHadirPertemuan }}</h4>
                </div>
                <span class="text-[9px] font-black text-green-900 bg-green-50 border border-green-700 px-1.5 py-0.5 uppercase">Hadir</span>
            </div>

            <div class="card-interactive bg-white border-2 border-black p-3.5 sm:p-4 shadow-[3px_3px_0px_0px_#000000] flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-[#CA2C2A]">Belum Hadir (P{{ $pertemuanDipilih }})</span>
                    <h4 class="text-xl sm:text-2xl font-black text-[#CA2C2A] font-mono mt-0.5">{{ $totalBelumHadirPertemuan }}</h4>
                </div>
                <span class="text-[9px] font-black text-[#901C1A] bg-red-50 border border-[#CA2C2A] px-1.5 py-0.5 uppercase">Belum</span>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- CARD 4: FILTER & PENCARIAN                                     -->
    <!-- ============================================================== -->
    <div class="snap-in-4 bg-white border-2 border-black p-3 sm:p-4 shadow-[4px_4px_0px_0px_#000000]">
        <form action="{{ route('admin.presensi.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch gap-2.5">
            <input type="hidden" name="pertemuan" value="{{ $pertemuanDipilih }}">

            <div class="flex-1 relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search }}"
                    placeholder="Cari Nama, NIM, Email..."
                    class="w-full bg-[#F8FBFE] border-2 border-black text-xs sm:text-sm font-semibold px-3 py-2 pl-8 focus:bg-white focus:outline-none focus:shadow-[2px_2px_0px_0px_#000000] transition"
                >
                <svg class="w-4 h-4 text-gray-500 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select 
                name="kelas" 
                class="bg-[#F8FBFE] border-2 border-black font-bold text-xs sm:text-sm px-3 py-2 focus:bg-white focus:outline-none focus:shadow-[2px_2px_0px_0px_#000000]"
            >
                <option value="">-- SEMUA KELAS --</option>
                @foreach ($kelasList as $k)
                    <option value="{{ $k->nama_kelas }}" {{ $kelasFilter === $k->nama_kelas ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>

            <button 
                type="submit" 
                class="btn-smooth px-4 py-2 bg-[#1A467C] hover:bg-[#0F2A4A] text-white text-xs font-black uppercase tracking-wider border-2 border-black shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
            >
                FILTER
            </button>
        </form>
    </div>

    <!-- ============================================================== -->
    <!-- CARD 5: TABEL REKAP KEHADIRAN 7 PERTEMUAN (Desktop)            -->
    <!-- ============================================================== -->
    <div class="snap-in-5 hidden lg:block bg-white border-2 border-black shadow-[6px_6px_0px_0px_#000000] overflow-hidden">
        <div class="px-5 py-3 border-b-2 border-black bg-blue-50/70 flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                Rekapitulasi Kehadiran 7 Pertemuan Mahasiswa
            </h3>
            <span class="text-xs font-bold text-[#1A467C] bg-white border-2 border-black px-2.5 py-0.5 shadow-[1px_1px_0px_0px_#000000]">
                Total: {{ $pendaftarans->total() }} Mahasiswa
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b-2 border-black bg-[#1A467C] text-white uppercase font-black text-[11px] tracking-wider">
                        <th class="py-3 px-3 w-10 text-center">No</th>
                        <th class="py-3 px-3">NIM</th>
                        <th class="py-3 px-3">Nama Mahasiswa</th>
                        <th class="py-3 px-3 text-center">Kelas</th>
                        @for ($i = 1; $i <= 7; $i++)
                            <th class="py-3 px-2 text-center {{ $pertemuanDipilih === $i ? 'bg-[#2A82C6]' : '' }}">P{{ $i }}</th>
                        @endfor
                        <th class="py-3 px-3 text-center">Total</th>
                        <th class="py-3 px-3 text-center">Aksi (P{{ $pertemuanDipilih }})</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pendaftarans as $index => $item)
                        @php
                            $presensiMap = $item->presensis->keyBy('pertemuan');
                            $totalHadir = 0;
                            $isHadirDipilih = false;
                        @endphp
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="py-3 px-3 text-center font-bold text-gray-700 font-mono">
                                {{ $pendaftarans->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-3 font-black text-gray-950 font-mono">
                                {{ $item->nim }}
                            </td>
                            <td class="py-3 px-3 font-bold text-gray-900">
                                {{ $item->nama }}
                            </td>
                            <td class="py-3 px-3 text-center font-bold">
                                <span class="bg-blue-50 border border-[#2A82C6] px-1.5 py-0.5 text-[10px] font-mono">
                                    {{ $item->kelas }}
                                </span>
                            </td>

                            <!-- Status P1 s.d. P7 -->
                            @for ($i = 1; $i <= 7; $i++)
                                @php
                                    $p = $presensiMap->get($i);
                                    $hadir = ($p && $p->status === 'hadir');
                                    if ($hadir) $totalHadir++;
                                    if ($i === $pertemuanDipilih) $isHadirDipilih = $hadir;
                                @endphp
                                <td class="py-2 px-1 text-center font-mono font-bold {{ $pertemuanDipilih === $i ? 'bg-blue-50/60' : '' }}">
                                    @if ($hadir)
                                        <span class="inline-block bg-green-100 text-green-800 border border-green-700 px-1 py-0.2 text-[10px]" title="Hadir pada {{ $p->waktu_hadir ? $p->waktu_hadir->format('H:i') : '' }}">
                                            ✓
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-[10px]">-</span>
                                    @endif
                                </td>
                            @endfor

                            <!-- Total & Persentase -->
                            <td class="py-3 px-3 text-center font-black font-mono">
                                {{ $totalHadir }}/7 ({{ round(($totalHadir/7)*100) }}%)
                            </td>

                            <!-- Tombol Toggle Kehadiran Cepat (Pertemuan Terpilih) -->
                            <td class="py-3 px-3 text-center">
                                <form action="{{ route('admin.presensi.toggle', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="pertemuan" value="{{ $pertemuanDipilih }}">
                                    <button 
                                        type="submit" 
                                        class="btn-smooth px-2.5 py-1 text-[10px] font-black uppercase tracking-wider border border-black shadow-[1px_1px_0px_0px_#000000] cursor-pointer {{ $isHadirDipilih ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-800' }}"
                                    >
                                        {{ $isHadirDipilih ? '✓ HADIR' : '+ ABSENKAN' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="py-8 text-center text-gray-500 font-bold">
                                Tidak ada data mahasiswa yang cocok dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pendaftarans->hasPages())
            <div class="p-3 border-t-2 border-black bg-gray-50">
                {{ $pendaftarans->links() }}
            </div>
        @endif
    </div>

    <!-- ============================================================== -->
    <!-- CARD 5 (Mobile): Responsive Cards View                         -->
    <!-- ============================================================== -->
    <div class="snap-in-5 block lg:hidden space-y-3">
        @forelse ($pendaftarans as $index => $item)
            @php
                $presensiMap = $item->presensis->keyBy('pertemuan');
                $isHadirDipilih = ($presensiMap->get($pertemuanDipilih) && $presensiMap->get($pertemuanDipilih)->status === 'hadir');
                $totalHadir = $item->presensis->where('status', 'hadir')->count();
            @endphp
            <div class="card-interactive bg-white border-2 border-black p-3.5 shadow-[3px_3px_0px_0px_#000000] space-y-2.5">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h4 class="font-black text-sm text-gray-950 uppercase">{{ $item->nama }}</h4>
                        <p class="text-xs font-mono font-bold text-gray-600">{{ $item->nim }} • {{ $item->kelas }}</p>
                    </div>
                    <form action="{{ route('admin.presensi.toggle', $item->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="pertemuan" value="{{ $pertemuanDipilih }}">
                        <button 
                            type="submit" 
                            class="btn-smooth px-2.5 py-1 text-[10px] font-black uppercase tracking-wider border border-black shadow-[1px_1px_0px_0px_#000000] {{ $isHadirDipilih ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-800' }}"
                        >
                            {{ $isHadirDipilih ? '✓ HADIR P' . $pertemuanDipilih : '+ ABSENKAN' }}
                        </button>
                    </form>
                </div>

                <!-- Mini Dots P1 - P7 -->
                <div class="flex items-center justify-between border-t border-gray-200 pt-2 text-[10px] font-mono">
                    <div class="flex items-center gap-1">
                        @for ($i = 1; $i <= 7; $i++)
                            @php $h = ($presensiMap->get($i) && $presensiMap->get($i)->status === 'hadir'); @endphp
                            <span class="w-5 h-5 flex items-center justify-center font-bold border border-black {{ $h ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-400' }}">
                                {{ $i }}
                            </span>
                        @endfor
                    </div>
                    <span class="font-bold text-gray-700">Total: {{ $totalHadir }}/7</span>
                </div>
            </div>
        @empty
            <div class="bg-white border-2 border-black p-6 text-center text-xs font-bold text-gray-500 shadow-[3px_3px_0px_0px_#000000]">
                Belum ada data mahasiswa.
            </div>
        @endforelse

        @if ($pendaftarans->hasPages())
            <div class="pt-2">
                {{ $pendaftarans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
