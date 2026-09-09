@extends('admin.layouts.app')

@section('title', 'Edit Data Pendaftar')
@section('header_title', 'Edit Data Pendaftar')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="bg-white border-2 border-[#1A467C] p-6 sm:p-8 shadow-[6px_6px_0px_0px_#1A467C]">
        <div class="border-b-2 border-[#1A467C] pb-4 mb-6">
            <span class="inline-block bg-[#1A467C] text-white text-[10px] font-black uppercase px-2.5 py-0.5 tracking-wider mb-1">
                DATA MAHASISWA #{{ $pendaftaran->id }}
            </span>
            <h3 class="text-base font-black uppercase tracking-tight text-[#1A467C]">Perbarui Informasi Pendaftar</h3>
            <p class="text-xs font-semibold text-gray-600 mt-1">Terdaftar sejak: {{ $pendaftaran->created_at ? $pendaftaran->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB' : '-' }}</p>
        </div>

        <!-- Flash Alert Error -->
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

        <!-- Form Edit (Neubrutalism) -->
        <form action="{{ route('admin.pendaftar.update', $pendaftaran->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Field: Nama -->
            <div>
                <label for="nama" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    Nama Lengkap <span class="text-[#CA2C2A]">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama" 
                    name="nama" 
                    value="{{ old('nama', $pendaftaran->nama) }}"
                    required
                    class="w-full bg-[#F8FBFE] border-2 @error('nama') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold text-sm px-4 py-3 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                >
                @error('nama')
                    <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: NIM -->
            <div>
                <label for="nim" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    NIM (Nomor Induk Mahasiswa) <span class="text-[#CA2C2A]">*</span>
                </label>
                <input 
                    type="text" 
                    id="nim" 
                    name="nim" 
                    value="{{ old('nim', $pendaftaran->nim) }}"
                    required
                    class="w-full bg-[#F8FBFE] border-2 @error('nim') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold font-mono text-sm px-4 py-3 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                >
                @error('nim')
                    <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: Kelas (Dropdown Dinamis) -->
            <div>
                <label for="kelas" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    Kelas <span class="text-[#CA2C2A]">*</span>
                </label>
                <select 
                    id="kelas" 
                    name="kelas" 
                    required
                    class="w-full bg-[#F8FBFE] border-2 @error('kelas') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold font-mono text-sm px-4 py-3 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition cursor-pointer"
                >
                    <option value="">-- PILIH KELAS --</option>
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->nama_kelas }}" {{ old('kelas', $pendaftaran->kelas) == $k->nama_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} {{ !$k->is_active ? '(Nonaktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('kelas')
                    <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: No Telp -->
            <div>
                <label for="no_telp" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    No. Telepon / WhatsApp <span class="text-[#CA2C2A]">*</span>
                </label>
                <input 
                    type="tel" 
                    id="no_telp" 
                    name="no_telp" 
                    value="{{ old('no_telp', $pendaftaran->no_telp) }}"
                    required
                    class="w-full bg-[#F8FBFE] border-2 @error('no_telp') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold font-mono text-sm px-4 py-3 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                >
                @error('no_telp')
                    <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: Status Kehadiran -->
            <div>
                <label for="status_kehadiran" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    Status Kehadiran <span class="text-[#CA2C2A]">*</span>
                </label>
                <select 
                    id="status_kehadiran" 
                    name="status_kehadiran" 
                    required
                    class="w-full bg-[#F8FBFE] border-2 @error('status_kehadiran') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold text-sm px-4 py-3 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition cursor-pointer"
                >
                    <option value="belum_hadir" {{ old('status_kehadiran', $pendaftaran->status_kehadiran) == 'belum_hadir' ? 'selected' : '' }}>BELUM HADIR</option>
                    <option value="hadir" {{ old('status_kehadiran', $pendaftaran->status_kehadiran) == 'hadir' ? 'selected' : '' }}>HADIR</option>
                    <option value="tidak_hadir" {{ old('status_kehadiran', $pendaftaran->status_kehadiran) == 'tidak_hadir' ? 'selected' : '' }}>TIDAK HADIR</option>
                </select>
                @error('status_kehadiran')
                    <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                @enderror
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
                    href="{{ route('admin.dashboard') }}" 
                    class="btn-smooth px-5 py-3 border-2 border-gray-400 bg-white hover:bg-gray-100 text-gray-800 text-xs sm:text-sm font-bold uppercase tracking-wider shadow-[2px_2px_0px_0px_#9ca3af]"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
