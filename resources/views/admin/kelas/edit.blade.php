@extends('admin.layouts.app')

@section('title', 'Edit Kelas')
@section('header_title', 'Edit Data Kelas')

@section('content')
<div class="max-w-xl mx-auto">

    <div class="bg-white border-2 border-[#1A467C] p-6 sm:p-8 shadow-[6px_6px_0px_0px_#1A467C]">
        <div class="border-b-2 border-[#1A467C] pb-4 mb-6">
            <span class="inline-block bg-[#1A467C] text-white text-[10px] font-black uppercase px-2.5 py-0.5 tracking-wider mb-1">
                KELOLA DATA KELAS
            </span>
            <h3 class="text-base font-black uppercase tracking-tight text-[#1A467C]">Perbarui Informasi Kelas</h3>
            <p class="text-xs font-semibold text-gray-600 mt-1">Mengubah nama kelas akan otomatis menyinkronkan data pendaftar terkait.</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 p-4 bg-red-50 border-2 border-[#901C1A] text-red-950 text-xs sm:text-sm font-semibold shadow-[3px_3px_0px_0px_#901C1A]">
                <p class="font-black uppercase mb-1">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-900">
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
                <label for="nama_kelas" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    Nama Kelas <span class="text-[#CA2C2A]">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama_kelas" 
                    name="nama_kelas" 
                    value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                    required
                    class="w-full bg-[#F8FBFE] border-2 @error('nama_kelas') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold font-mono text-sm px-4 py-3 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition uppercase"
                >
                @error('nama_kelas')
                    <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: Status Aktif -->
            <div class="pt-2">
                <label class="flex items-center gap-2.5 cursor-pointer text-xs font-bold text-[#1A467C]">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1" 
                        {{ old('is_active', $kelas->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#2A82C6] border-2 border-[#1A467C] focus:ring-0"
                    >
                    <span>Aktifkan dan tampilkan kelas ini di Form Pendaftaran</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 pt-3">
                <button 
                    type="submit" 
                    class="btn-smooth px-6 py-3 bg-[#2A82C6] hover:bg-[#1A467C] active:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-[#1A467C] shadow-[3px_3px_0px_0px_#1A467C] cursor-pointer"
                >
                    Simpan Perubahan
                </button>
                <a 
                    href="{{ route('admin.kelas.index') }}" 
                    class="btn-smooth px-5 py-3 border-2 border-gray-400 bg-white hover:bg-gray-100 text-gray-800 text-xs sm:text-sm font-bold uppercase tracking-wider shadow-[2px_2px_0px_0px_#9ca3af]"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
