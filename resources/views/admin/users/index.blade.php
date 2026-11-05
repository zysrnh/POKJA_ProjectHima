@extends('admin.layouts.app')

@section('title', 'Kelola Admin')
@section('header_title', 'Kelola Akun Admin')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar (Neubrutalism Minimalist) -->
    <div class="snap-in-1 flex items-center justify-between gap-4 bg-white border-2 border-[#000000] p-4 sm:p-5 shadow-[5px_5px_0px_0px_#000000]">
        <div>
            <h3 class="text-sm sm:text-base font-black uppercase tracking-tight text-[#1A467C]">Daftar Administrator Sistem</h3>
            <p class="text-xs font-semibold text-gray-600 mt-0.5">Seluruh akun yang memiliki hak akses login ke panel admin POKJA.</p>
        </div>

        <a 
            href="{{ route('admin.users.create') }}" 
            class="btn-smooth w-10 h-10 sm:w-11 sm:h-11 bg-[#2A82C6] hover:bg-[#1A467C] text-white border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000000] cursor-pointer shrink-0"
            title="Tambah Admin Baru"
        >
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="square" stroke-width="2.8" d="M12 4v16m8-8H4" />
            </svg>
        </a>
    </div>

    <!-- Tabel Data Admin (Neubrutalism) -->
    <div class="snap-in-2 bg-white border-2 border-[#1A467C] shadow-[6px_6px_0px_0px_#1A467C] overflow-hidden">
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
                                    <!-- Edit Button (Icon Only Neubrutalism) -->
                                    <a 
                                        href="{{ route('admin.users.edit', $admin->id) }}" 
                                        class="btn-smooth w-7 h-7 sm:w-8 sm:h-8 bg-amber-400 hover:bg-amber-500 text-black border border-black flex items-center justify-center shadow-[1px_1px_0px_0px_#000000]"
                                        title="Edit Admin {{ $admin->name }}"
                                    >
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="square" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <!-- Delete Button (Icon Only Neubrutalism) -->
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
                                                class="btn-smooth w-7 h-7 sm:w-8 sm:h-8 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border border-[#901C1A] flex items-center justify-center shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
                                                title="Hapus Admin {{ $admin->name }}"
                                            >
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="square" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span 
                                            class="w-7 h-7 sm:w-8 sm:h-8 bg-gray-200 border border-gray-400 text-gray-400 flex items-center justify-center cursor-not-allowed"
                                            title="Tidak dapat menghapus akun sendiri"
                                        >
                                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="square" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
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
