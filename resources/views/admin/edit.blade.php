<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Edit Data Pendaftar - POKJA HIMA IF</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen">

    <!-- Top Navbar -->
    <header class="bg-white border-b border-gray-300 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <span class="bg-blue-600 text-white font-bold text-xs px-2.5 py-1 tracking-wider uppercase">Admin</span>
                    <h1 class="text-base sm:text-lg font-bold text-gray-900 uppercase tracking-wide">Edit Data Pendaftar</h1>
                </div>

                <div>
                    <a 
                        href="{{ route('admin.dashboard') }}" 
                        class="px-3.5 py-1.5 border border-gray-300 text-xs font-semibold uppercase tracking-wider text-gray-700 bg-white hover:bg-gray-50 transition"
                    >
                        &larr; Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-8">

        <div class="bg-white border border-gray-300 p-6 sm:p-8 shadow-sm">
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h2 class="text-base font-bold uppercase tracking-wide text-gray-900">Perbarui Informasi Pendaftar</h2>
                <p class="text-xs text-gray-500 mt-1">ID Pendaftaran: #{{ $pendaftaran->id }} | Terdaftar sejak: {{ $pendaftaran->created_at ? $pendaftaran->created_at->format('d/m/Y H:i') : '-' }}</p>
            </div>

            <!-- Flash Alert Error -->
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

            <!-- Form Edit -->
            <form action="{{ route('admin.pendaftar.update', $pendaftaran->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Field: Nama -->
                <div>
                    <label for="nama" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nama" 
                        name="nama" 
                        value="{{ old('nama', $pendaftaran->nama) }}"
                        required
                        class="w-full bg-gray-50 border @error('nama') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                    >
                    @error('nama')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: NIM -->
                <div>
                    <label for="nim" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                        NIM <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nim" 
                        name="nim" 
                        value="{{ old('nim', $pendaftaran->nim) }}"
                        required
                        class="w-full bg-gray-50 border @error('nim') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                    >
                    @error('nim')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: Kelas -->
                <div>
                    <label for="kelas" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                        Kelas <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="kelas" 
                        name="kelas" 
                        value="{{ old('kelas', $pendaftaran->kelas) }}"
                        required
                        class="w-full bg-gray-50 border @error('kelas') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                    >
                    @error('kelas')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: No Telp -->
                <div>
                    <label for="no_telp" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                        No. Telepon / WhatsApp <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="tel" 
                        id="no_telp" 
                        name="no_telp" 
                        value="{{ old('no_telp', $pendaftaran->no_telp) }}"
                        required
                        class="w-full bg-gray-50 border @error('no_telp') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                    >
                    @error('no_telp')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-3">
                    <button 
                        type="submit" 
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold uppercase tracking-wider transition cursor-pointer"
                    >
                        Simpan Perubahan
                    </button>
                    <a 
                        href="{{ route('admin.dashboard') }}" 
                        class="px-5 py-2.5 border border-gray-300 hover:bg-gray-100 text-gray-700 text-xs sm:text-sm font-semibold uppercase tracking-wider transition"
                    >
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </main>

</body>
</html>
