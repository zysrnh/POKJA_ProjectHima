@extends('admin.layouts.app')

@section('title', 'Log Aktivitas Point')
@section('header_title', 'Log Aktivitas Point Mahasiswa')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- ============================================================== -->
    <!-- 1. 2 RINGKASAN AKTIVITAS POINT                                 -->
    <!-- ============================================================== -->
    <div class="snap-in-1 grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
        <div class="bg-white border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between">
            <div>
                <span class="text-[11px] font-black uppercase tracking-wider text-[#15803d] block">Total Aktivitas Penambahan (+)</span>
                <span class="text-2xl font-black text-[#15803d] font-mono mt-1 block">{{ number_format($totalTambah) }}x</span>
            </div>
            <div class="w-10 h-10 bg-[#15803d] text-white border-2 border-black flex items-center justify-center font-black shadow-[2px_2px_0px_0px_#000000]">
                +
            </div>
        </div>

        <div class="bg-white border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between">
            <div>
                <span class="text-[11px] font-black uppercase tracking-wider text-[#CA2C2A] block">Total Aktivitas Pengurangan (-)</span>
                <span class="text-2xl font-black text-[#CA2C2A] font-mono mt-1 block">{{ number_format($totalKurang) }}x</span>
            </div>
            <div class="w-10 h-10 bg-[#CA2C2A] text-white border-2 border-black flex items-center justify-center font-black shadow-[2px_2px_0px_0px_#000000]">
                -
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 2. FILTER LOG AKTIVITAS                                        -->
    <!-- ============================================================== -->
    <div class="snap-in-2 bg-white border-2 border-black p-3 sm:p-4 shadow-[4px_4px_0px_0px_#000000]">
        <form action="{{ route('admin.poin.aktivitas') }}" method="GET" class="flex flex-col sm:flex-row items-stretch gap-2.5">
            <div class="flex-1 relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search }}"
                    placeholder="Cari Nama, NIM, Kategori, Keterangan, atau Admin..."
                    class="w-full bg-[#F8FBFE] border-2 border-black text-xs sm:text-sm font-semibold px-3 py-2 pl-8 focus:bg-white focus:outline-none"
                >
                <svg class="w-4 h-4 text-gray-500 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <div class="relative w-full sm:w-48">
                <select 
                    name="tipe" 
                    class="w-full bg-[#F8FBFE] border-2 border-black font-bold text-xs px-3 py-2.5 pr-8 appearance-none focus:bg-white focus:outline-none cursor-pointer"
                >
                    <option value="">-- SEMUA AKTIVITAS --</option>
                    <option value="tambah" {{ $tipeFilter === 'tambah' ? 'selected' : '' }}>Penambahan Point (+)</option>
                    <option value="kurang" {{ $tipeFilter === 'kurang' ? 'selected' : '' }}>Pengurangan Point (-)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-black">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <div class="flex items-center gap-1.5 shrink-0">
                <button 
                    type="submit" 
                    class="btn-smooth px-5 py-2 bg-[#1A467C] hover:bg-[#0F2A4A] text-white text-xs font-black uppercase tracking-wider border-2 border-black shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    Filter
                </button>
                @if ($search || $tipeFilter)
                    <a 
                        href="{{ route('admin.poin.aktivitas') }}" 
                        class="btn-smooth px-3 py-2 bg-white hover:bg-gray-100 text-gray-800 border-2 border-black text-xs font-black shadow-[2px_2px_0px_0px_#000000]"
                    >
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- ============================================================== -->
    <!-- 3. DAFTAR RIWAYAT AKTIVITAS POINT                              -->
    <!-- ============================================================== -->
    <div class="snap-in-3 bg-white border-2 border-black shadow-[6px_6px_0px_0px_#000000] overflow-hidden">
        <div class="px-5 py-3 border-b-2 border-black bg-blue-50/70 flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                Riwayat Aktivitas Point Mahasiswa
            </h3>
            <span class="text-xs font-bold text-[#1A467C] bg-white border-2 border-black px-2.5 py-0.5 shadow-[1px_1px_0px_0px_#000000]">
                Total: {{ $histories->total() }} Aktivitas
            </span>
        </div>

        <div class="divide-y-2 divide-gray-200">
            @forelse ($histories as $item)
                @php
                    $isAdd = $item->tipe === 'tambah';
                    $mhs = $item->pendaftaran;
                @endphp
                <div class="p-3.5 sm:px-5 sm:py-3.5 hover:bg-blue-50/30 transition flex items-start sm:items-center justify-between gap-3">
                    <div class="flex items-start sm:items-center gap-3 min-w-0">
                        <span class="w-9 h-9 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_#000000] {{ $isAdd ? 'bg-[#15803d] text-white' : 'bg-[#CA2C2A] text-white' }}">
                            {{ $isAdd ? '+' : '-' }}{{ (int) $item->jumlah }}
                        </span>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="font-black text-xs sm:text-sm text-gray-950">{{ $mhs->nama ?? 'Mahasiswa Terhapus' }}</span>
                                @if ($mhs)
                                    <span class="font-mono text-[11px] font-bold text-[#1A467C]">({{ $mhs->nim }} • {{ $mhs->kelas }})</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-1.5 mt-0.5">
                                <span class="inline-block px-1.5 py-0.2 text-[10px] font-black uppercase border border-black {{ $isAdd ? 'bg-green-100 text-green-900' : 'bg-red-100 text-red-900' }}">
                                    {{ $item->kategori }}
                                </span>
                                @if ($item->keterangan)
                                    <span class="text-xs font-semibold text-gray-700">— {{ $item->keterangan }}</span>
                                @endif
                            </div>
                            <p class="text-[10px] font-mono text-gray-500 mt-0.5">
                                {{ $item->created_at ? $item->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' }} WIB • Oleh: <strong>{{ $item->created_by ?? 'Admin' }}</strong>
                            </p>
                        </div>
                    </div>

                    <form 
                        action="{{ route('admin.poin.destroyHistory', $item->id) }}" 
                        method="POST" 
                        class="shrink-0"
                        onsubmit="return confirm('Hapus riwayat point ini? Total point mahasiswa akan dihitung ulang.')"
                    >
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="btn-smooth w-8 h-8 bg-white hover:bg-[#CA2C2A] text-gray-800 hover:text-white border-2 border-black flex items-center justify-center text-xs font-black shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
                            title="Hapus Riwayat Point Ini"
                        >
                            ✕
                        </button>
                    </form>
                </div>
            @empty
                <div class="p-8 text-center text-xs font-bold text-gray-500">
                    Belum ada riwayat aktivitas point.
                </div>
            @endforelse
        </div>

        @if ($histories->hasPages())
            <div class="px-5 py-3 border-t-2 border-black bg-white">
                {{ $histories->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
