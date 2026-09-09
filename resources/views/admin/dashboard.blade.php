@extends('admin.layouts.app')

@section('title', 'Data Pendaftar')
@section('header_title', 'Data Pendaftar POKJA')

@section('content')
<div class="space-y-6">

    <!-- 3 Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Card 1: Total Pendaftar -->
        <div class="bg-white border border-gray-300 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Pendaftar</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-bold text-gray-900">{{ number_format($totalPendaftar) }}</span>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 px-2 py-0.5">Mahasiswa</span>
            </div>
        </div>

        <!-- Card 2: Pendaftar Hari Ini -->
        <div class="bg-white border border-gray-300 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Pendaftar Hari Ini</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-bold text-gray-900">{{ number_format($pendaftarHariIni) }}</span>
                <span class="text-xs font-medium text-green-600 bg-green-50 border border-green-200 px-2 py-0.5">Hari Ini</span>
            </div>
        </div>

        <!-- Card 3: Total Kelas -->
        <div class="bg-white border border-gray-300 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Kelas Terdaftar</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-bold text-gray-900">{{ number_format($totalKelas) }}</span>
                <span class="text-xs font-medium text-gray-600 bg-gray-100 border border-gray-200 px-2 py-0.5">Variasi Kelas</span>
            </div>
        </div>
    </div>

    <!-- Action Bar: Search, Filter, Export -->
    <div class="bg-white border border-gray-300 p-4 sm:p-5 shadow-sm">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
            
            <!-- Search & Filter Fields -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
                <!-- Search Input -->
                <div class="flex-1 min-w-[200px]">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}"
                        placeholder="Cari Nama, NIM, No. Telp..."
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-sm px-3.5 py-2 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                    >
                </div>

                <!-- Filter Kelas Dropdown -->
                <div class="sm:w-48">
                    <select 
                        name="kelas" 
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-sm px-3.5 py-2 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                    >
                        <option value="">-- Semua Kelas --</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k }}" {{ $kelasFilter == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Filter & Reset -->
                <div class="flex items-center gap-2">
                    <button 
                        type="submit" 
                        class="flex-1 sm:flex-none px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-xs font-semibold uppercase tracking-wider transition cursor-pointer"
                    >
                        Filter
                    </button>
                    @if ($search || $kelasFilter)
                        <a 
                            href="{{ route('admin.dashboard') }}" 
                            class="px-3 py-2 border border-gray-300 text-gray-600 hover:bg-gray-100 text-xs font-semibold uppercase tracking-wider transition"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </div>

            <!-- Export CSV Button -->
            <div>
                <a 
                    href="{{ route('admin.pendaftar.export', ['search' => $search, 'kelas' => $kelasFilter]) }}" 
                    class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-xs font-semibold uppercase tracking-wider transition cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Export CSV / Excel</span>
                </a>
            </div>

        </form>
    </div>

    <!-- Tabel Data Pendaftar -->
    <div class="bg-white border border-gray-300 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700">Daftar Mahasiswa Pendaftar</h3>
            <span class="text-xs text-gray-500">Menampilkan {{ $pendaftarans->count() }} dari total {{ $pendaftarans->total() }} data</span>
        </div>

        <!-- Responsive Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-gray-300 bg-gray-100 text-gray-700 uppercase font-semibold text-[11px] sm:text-xs tracking-wider">
                        <th class="py-3 px-3.5 w-12 text-center">No</th>
                        <th class="py-3 px-3.5">Nama Lengkap</th>
                        <th class="py-3 px-3.5">NIM</th>
                        <th class="py-3 px-3.5">Kelas</th>
                        <th class="py-3 px-3.5">No. Telepon / WA</th>
                        <th class="py-3 px-3.5">Tanggal Daftar</th>
                        <th class="py-3 px-3.5 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pendaftarans as $index => $item)
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $item->no_telp);
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-3.5 text-center text-gray-500 font-mono">
                                {{ $pendaftarans->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-3.5 font-medium text-gray-900">
                                {{ $item->nama }}
                            </td>
                            <td class="py-3 px-3.5 text-gray-700 font-mono">
                                {{ $item->nim }}
                            </td>
                            <td class="py-3 px-3.5">
                                <span class="inline-block bg-gray-100 border border-gray-300 px-2 py-0.5 text-xs text-gray-800 font-medium">
                                    {{ $item->kelas }}
                                </span>
                            </td>
                            <td class="py-3 px-3.5">
                                <a 
                                    href="https://wa.me/{{ $cleanPhone }}" 
                                    target="_blank" 
                                    title="Kirim pesan WhatsApp"
                                    class="inline-flex items-center gap-1.5 text-green-700 hover:text-green-900 font-mono hover:underline"
                                >
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.969.586 1.761.882 2.796.883h.001c3.182 0 5.767-2.587 5.768-5.766.001-3.182-2.585-5.769-5.769-5.769zm3.364 8.163c-.147.417-.732.772-1.031.796-.285.023-.62.032-1.954-.525-1.583-.663-2.588-2.28-2.667-2.385-.078-.105-.639-.851-.639-1.624 0-.773.404-1.155.549-1.311.144-.156.315-.195.421-.195.105 0 .211.001.303.006.098.005.228-.037.357.273.132.315.45 1.096.489 1.176.039.078.065.171.013.275-.052.104-.078.17-.156.26-.078.092-.164.205-.234.275-.079.079-.161.164-.069.322.092.157.409.675.877 1.091.602.535 1.109.701 1.267.78.157.078.249.065.341-.039.092-.105.393-.457.498-.614.105-.157.21-.131.353-.078.144.052.915.431 1.072.509.157.079.262.118.301.184.039.066.039.381-.108.798z"/>
                                    </svg>
                                    <span>{{ $item->no_telp }}</span>
                                </a>
                            </td>
                            <td class="py-3 px-3.5 text-gray-500 text-xs font-mono">
                                {{ $item->created_at ? $item->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB' : '-' }}
                            </td>
                            <td class="py-3 px-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit Button -->
                                    <a 
                                        href="{{ route('admin.pendaftar.edit', $item->id) }}" 
                                        class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold uppercase tracking-wider transition"
                                        title="Edit Data"
                                    >
                                        Edit
                                    </a>

                                    <!-- Delete Button -->
                                    <form 
                                        action="{{ route('admin.pendaftar.destroy', $item->id) }}" 
                                        method="POST" 
                                        class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pendaftar atas nama {{ $item->nama }}?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold uppercase tracking-wider transition cursor-pointer"
                                            title="Hapus Data"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500 text-xs sm:text-sm">
                                @if ($search || $kelasFilter)
                                    Tidak ada data pendaftar yang cocok dengan pencarian / filter Anda.
                                @else
                                    Belum ada data pendaftar yang masuk.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if ($pendaftarans->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $pendaftarans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
