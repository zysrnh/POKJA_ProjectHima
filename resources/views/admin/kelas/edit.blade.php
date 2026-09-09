@extends('admin.layouts.app')

@section('title', 'Edit Kelas')
@section('header_title', 'Edit Data Kelas')

@section('content')
<div class="max-w-xl mx-auto">

    <div class="bg-white border border-gray-300 p-6 sm:p-8 shadow-sm">
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h3 class="text-base font-bold uppercase tracking-wide text-gray-900">Perbarui Informasi Kelas</h3>
            <p class="text-xs text-gray-500 mt-1">Mengubah nama kelas akan otomatis menyinkronkan data pendaftar terkait.</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 p-3.5 bg-red-50 border border-red-600 text-red-800 text-xs sm:text-sm">
                <p class="font-semibold mb-1">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Field: Nama Kelas -->
            <div>
                <label for="nama_kelas" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                    Nama Kelas <span class="text-red-600">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama_kelas" 
                    name="nama_kelas" 
                    value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                    required
                    class="w-full bg-gray-50 border @error('nama_kelas') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-[#2A82C6] focus:ring-1 focus:ring-[#2A82C6] transition uppercase font-mono"
                >
                @error('nama_kelas')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: Status Aktif -->
            <div class="pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-gray-700">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1" 
                        {{ old('is_active', $kelas->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#2A82C6] border-gray-300 focus:ring-0"
                    >
                    <span>Aktifkan dan tampilkan kelas ini di Form Pendaftaran</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 pt-3">
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-[#2A82C6] hover:bg-[#1A467C] active:bg-[#1A467C] text-white text-xs sm:text-sm font-semibold uppercase tracking-wider transition cursor-pointer"
                >
                    Simpan Perubahan
                </button>
                <a 
                    href="{{ route('admin.kelas.index') }}" 
                    class="px-5 py-2.5 border border-gray-300 hover:bg-gray-100 text-gray-700 text-xs sm:text-sm font-semibold uppercase tracking-wider transition"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
