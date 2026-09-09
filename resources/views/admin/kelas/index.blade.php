@extends('admin.layouts.app')

@section('title', 'Kelola Kelas')
@section('header_title', 'Kelola Daftar Kelas')

@section('content')
<div class="space-y-6">

    <!-- Action Bar (Neubrutalism) -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white border-2 border-[#1A467C] p-5 sm:p-6 shadow-[6px_6px_0px_0px_#1A467C]">
        <div>
            <h3 class="text-sm font-black uppercase tracking-wider text-[#1A467C]">Daftar Pilihan Kelas Mahasiswa</h3>
            <p class="text-xs font-semibold text-gray-600 mt-1">Kelas yang aktif di bawah ini akan otomatis muncul pada opsi dropdown di Form Pendaftaran.</p>
        </div>

        <a 
            href="{{ route('admin.kelas.create') }}" 
            class="btn-smooth inline-flex items-center gap-2 px-5 py-3 bg-[#2A82C6] hover:bg-[#1A467C] text-white text-xs font-black uppercase tracking-wider border-2 border-[#1A467C] shadow-[3px_3px_0px_0px_#1A467C] cursor-pointer"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="square" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Kelas Baru</span>
        </a>
    </div>

    <!-- Tabel Kelas (Neubrutalism) -->
    <div class="bg-white border-2 border-[#1A467C] shadow-[6px_6px_0px_0px_#1A467C] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="border-b-2 border-[#1A467C] bg-[#1A467C] text-white uppercase font-black text-[11px] tracking-wider">
                        <th class="py-3.5 px-3.5 w-12 text-center">No</th>
                        <th class="py-3.5 px-3.5">Nama Kelas</th>
                        <th class="py-3.5 px-3.5">Jumlah Pendaftar</th>
                        <th class="py-3.5 px-3.5">Status Tampil</th>
                        <th class="py-3.5 px-3.5">Dibuat Pada</th>
                        <th class="py-3.5 px-3.5 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($kelasList as $index => $kelas)
                        @php
                            $pendaftarCount = $pendaftarCountPerClass[$kelas->nama_kelas] ?? 0;
                        @endphp
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="py-3.5 px-3.5 text-center text-gray-700 font-mono font-bold">
                                {{ $kelasList->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-3.5 font-black text-gray-950 font-mono text-sm">
                                <span class="inline-block bg-blue-50 border border-[#2A82C6] text-[#1A467C] px-2.5 py-1">
                                    {{ $kelas->nama_kelas }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3.5 text-gray-900 font-bold">
                                <span class="text-[#1A467C] font-black">{{ $pendaftarCount }}</span> Mahasiswa
                            </td>
                            <td class="py-3.5 px-3.5">
                                @if ($kelas->is_active)
                                    <span class="inline-block bg-green-50 border border-green-700 text-green-900 text-[11px] px-2 py-0.5 font-bold uppercase">
                                        Aktif (Muncul)
                                    </span>
                                @else
                                    <span class="inline-block bg-gray-100 border border-gray-400 text-gray-700 text-[11px] px-2 py-0.5 font-bold uppercase">
                                        Nonaktif (Sembunyi)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3.5 text-gray-700 text-xs font-mono font-medium">
                                {{ $kelas->created_at ? $kelas->created_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit Button -->
                                    <a 
                                        href="{{ route('admin.kelas.edit', $kelas->id) }}" 
                                        class="btn-smooth px-2.5 py-1 bg-amber-400 hover:bg-amber-500 text-black text-[11px] font-black uppercase tracking-wider border border-black shadow-[1px_1px_0px_0px_#000000]"
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
                                            class="btn-smooth px-2.5 py-1 bg-[#CA2C2A] hover:bg-[#901C1A] text-white text-[11px] font-black uppercase tracking-wider border border-[#901C1A] shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
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
                            <td colspan="6" class="py-10 text-center text-gray-500 font-semibold text-xs sm:text-sm">
                                Belum ada kelas yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if ($kelasList->hasPages())
            <div class="px-5 py-3.5 border-t-2 border-[#1A467C] bg-white">
                {{ $kelasList->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
