@extends('admin.layouts.app')

@section('title', 'Kelola Admin')
@section('header_title', 'Kelola Akun Admin')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar (Neubrutalism) -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white border-2 border-[#1A467C] p-5 sm:p-6 shadow-[6px_6px_0px_0px_#1A467C]">
        <div>
            <h3 class="text-sm font-black uppercase tracking-wider text-[#1A467C]">Daftar Administrator Sistem</h3>
            <p class="text-xs font-semibold text-gray-600 mt-1">Seluruh akun yang memiliki hak akses login ke panel admin POKJA.</p>
        </div>

        <a 
            href="{{ route('admin.users.create') }}" 
            class="btn-smooth inline-flex items-center gap-2 px-5 py-3 bg-[#2A82C6] hover:bg-[#1A467C] text-white text-xs font-black uppercase tracking-wider border-2 border-[#1A467C] shadow-[3px_3px_0px_0px_#1A467C] cursor-pointer"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="square" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Admin Baru</span>
        </a>
    </div>

    <!-- Tabel Data Admin (Neubrutalism) -->
    <div class="bg-white border-2 border-[#1A467C] shadow-[6px_6px_0px_0px_#1A467C] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="border-b-2 border-[#1A467C] bg-[#1A467C] text-white uppercase font-black text-[11px] tracking-wider">
                        <th class="py-3.5 px-3.5 w-12 text-center">No</th>
                        <th class="py-3.5 px-3.5">Nama Administrator</th>
                        <th class="py-3.5 px-3.5">Alamat Email</th>
                        <th class="py-3.5 px-3.5">Status Akun</th>
                        <th class="py-3.5 px-3.5">Dibuat Pada</th>
                        <th class="py-3.5 px-3.5 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($admins as $index => $admin)
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="py-3.5 px-3.5 text-center text-gray-700 font-mono font-bold">
                                {{ $admins->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-3.5 font-bold text-gray-950">
                                <div class="flex items-center gap-2">
                                    <span>{{ $admin->name }}</span>
                                    @if ($admin->id === Auth::id())
                                        <span class="bg-blue-50 text-[#1A467C] border border-[#2A82C6] text-[10px] font-black px-2 py-0.5 uppercase">AKUN ANDA</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-3.5 text-gray-900 font-mono font-bold">
                                {{ $admin->email }}
                            </td>
                            <td class="py-3.5 px-3.5">
                                <span class="inline-block bg-green-50 border border-green-700 text-green-900 text-[11px] px-2 py-0.5 font-bold uppercase">
                                    Aktif
                                </span>
                            </td>
                            <td class="py-3.5 px-3.5 text-gray-700 text-xs font-mono font-medium">
                                {{ $admin->created_at ? $admin->created_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit Button -->
                                    <a 
                                        href="{{ route('admin.users.edit', $admin->id) }}" 
                                        class="btn-smooth px-2.5 py-1 bg-amber-400 hover:bg-amber-500 text-black text-[11px] font-black uppercase tracking-wider border border-black shadow-[1px_1px_0px_0px_#000000]"
                                        title="Edit Admin"
                                    >
                                        Edit
                                    </a>

                                    <!-- Delete Button -->
                                    @if ($admin->id !== Auth::id())
                                        <form 
                                            action="{{ route('admin.users.destroy', $admin->id) }}" 
                                            method="POST" 
                                            class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin {{ $admin->name }}?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="btn-smooth px-2.5 py-1 bg-[#CA2C2A] hover:bg-[#901C1A] text-white text-[11px] font-black uppercase tracking-wider border border-[#901C1A] shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
                                                title="Hapus Admin"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span 
                                            class="px-2.5 py-1 bg-gray-200 border border-gray-400 text-gray-500 text-[11px] font-bold uppercase tracking-wider cursor-not-allowed"
                                            title="Tidak dapat menghapus akun sendiri"
                                        >
                                            Hapus
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-gray-500 font-semibold text-xs sm:text-sm">
                                Belum ada akun admin tambahan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if ($admins->hasPages())
            <div class="px-5 py-3.5 border-t-2 border-[#1A467C] bg-white">
                {{ $admins->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
