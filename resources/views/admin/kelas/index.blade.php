@extends('admin.layouts.app')

@section('title', 'Kelola Kelas')
@section('header_title', 'Kelola Daftar Kelas')

@section('content')
<div class="space-y-6">

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white border border-gray-300 p-4 sm:p-5 shadow-sm">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900">Daftar Pilihan Kelas Mahasiswa</h3>
            <p class="text-xs text-gray-500 mt-0.5">Kelas yang aktif di bawah ini akan otomatis muncul pada opsi dropdown di Form Pendaftaran.</p>
        </div>

        <a 
            href="{{ route('admin.kelas.create') }}" 
            class="inline-flex items-center gap-2 px-4 py-2 bg-[#2A82C6] hover:bg-[#1A467C] active:bg-[#1A467C] text-white text-xs font-semibold uppercase tracking-wider transition cursor-pointer"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="square" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Kelas Baru</span>
        </a>
    </div>

    <!-- Tabel Kelas -->
    <div class="bg-white border border-gray-300 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-gray-300 bg-gray-100 text-gray-700 uppercase font-semibold text-[11px] sm:text-xs tracking-wider">
                        <th class="py-3 px-3.5 w-12 text-center">No</th>
                        <th class="py-3 px-3.5">Nama Kelas</th>
                        <th class="py-3 px-3.5">Jumlah Pendaftar</th>
                        <th class="py-3 px-3.5">Status Tampil di Form</th>
                        <th class="py-3 px-3.5">Dibuat Pada</th>
                        <th class="py-3 px-3.5 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($kelasList as $index => $kelas)
                        @php
                            $pendaftarCount = $pendaftarCountPerClass[$kelas->nama_kelas] ?? 0;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-3.5 text-center text-gray-500 font-mono">
                                {{ $kelasList->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-3.5 font-bold text-gray-900 font-mono text-sm">
                                <span class="inline-block bg-blue-50 border border-blue-200 text-blue-900 px-2.5 py-0.5">
                                    {{ $kelas->nama_kelas }}
                                </span>
                            </td>
                            <td class="py-3 px-3.5 text-gray-700">
                                <span class="font-semibold">{{ $pendaftarCount }}</span> Mahasiswa
                            </td>
                            <td class="py-3 px-3.5">
                                @if ($kelas->is_active)
                                    <span class="inline-block bg-green-100 border border-green-300 text-green-800 text-xs px-2 py-0.5 font-medium">
                                        Aktif (Muncul)
                                    </span>
                                @else
                                    <span class="inline-block bg-gray-200 border border-gray-400 text-gray-700 text-xs px-2 py-0.5 font-medium">
                                        Nonaktif (Disembunyikan)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3.5 text-gray-500 text-xs font-mono">
                                {{ $kelas->created_at ? $kelas->created_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="py-3 px-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit Button -->
                                    <a 
                                        href="{{ route('admin.kelas.edit', $kelas->id) }}" 
                                        class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold uppercase tracking-wider transition"
                                        title="Edit Kelas"
                                    >
                                        Edit
                                    </a>

                                    <!-- Delete Button -->
                                    <form 
                                        action="{{ route('admin.kelas.destroy', $kelas->id) }}" 
                                        method="POST" 
                                        class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas {{ $kelas->nama_kelas }}?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold uppercase tracking-wider transition cursor-pointer"
                                            title="Hapus Kelas"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500 text-xs sm:text-sm">
                                Belum ada daftar kelas. Silakan tambahkan kelas baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kelasList->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $kelasList->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
