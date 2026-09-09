@extends('admin.layouts.app')

@section('title', 'Kelola Admin')
@section('header_title', 'Kelola Akun Admin')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white border border-gray-300 p-4 sm:p-5 shadow-sm">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900">Daftar Administrator Sistem</h3>
            <p class="text-xs text-gray-500 mt-0.5">Seluruh akun yang memiliki akses login ke panel admin POKJA.</p>
        </div>

        <a 
            href="{{ route('admin.users.create') }}" 
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-semibold uppercase tracking-wider transition cursor-pointer"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="square" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Admin Baru</span>
        </a>
    </div>

    <!-- Tabel Data Admin -->
    <div class="bg-white border border-gray-300 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-gray-300 bg-gray-100 text-gray-700 uppercase font-semibold text-[11px] sm:text-xs tracking-wider">
                        <th class="py-3 px-3.5 w-12 text-center">No</th>
                        <th class="py-3 px-3.5">Nama Administrator</th>
                        <th class="py-3 px-3.5">Alamat Email</th>
                        <th class="py-3 px-3.5">Status Akun</th>
                        <th class="py-3 px-3.5">Dibuat Pada</th>
                        <th class="py-3 px-3.5 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($admins as $index => $admin)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-3.5 text-center text-gray-500 font-mono">
                                {{ $admins->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-3.5 font-medium text-gray-900">
                                <div class="flex items-center gap-2">
                                    <span>{{ $admin->name }}</span>
                                    @if ($admin->id === Auth::id())
                                        <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-1.5 py-0.5 border border-blue-200">AKUN ANDA</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-3.5 text-gray-700 font-mono">
                                {{ $admin->email }}
                            </td>
                            <td class="py-3 px-3.5">
                                <span class="inline-block bg-green-100 border border-green-300 text-green-800 text-xs px-2 py-0.5 font-medium">
                                    Aktif
                                </span>
                            </td>
                            <td class="py-3 px-3.5 text-gray-500 text-xs font-mono">
                                {{ $admin->created_at ? $admin->created_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="py-3 px-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit Button -->
                                    <a 
                                        href="{{ route('admin.users.edit', $admin->id) }}" 
                                        class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold uppercase tracking-wider transition"
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
                                                class="px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold uppercase tracking-wider transition cursor-pointer"
                                                title="Hapus Admin"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <button 
                                            disabled 
                                            class="px-2.5 py-1 bg-gray-300 text-gray-500 text-xs font-semibold uppercase tracking-wider cursor-not-allowed"
                                            title="Tidak dapat menghapus akun sendiri"
                                        >
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500 text-xs sm:text-sm">
                                Belum ada data administrator.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($admins->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $admins->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
