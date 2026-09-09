<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Pendaftaran POKJA - HIMA IF</title>
    
    <!-- Google Font: Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex items-center justify-center p-3 sm:p-6 antialiased">

    <div class="w-full max-w-lg bg-white border border-gray-300 p-5 sm:p-8 shadow-sm">
        <!-- Header with Logo -->
        <div class="border-b border-gray-200 pb-5 mb-5 flex items-center gap-4">
            <img 
                src="{{ asset('logo/logo.png') }}" 
                alt="Logo HIMA IF" 
                class="w-14 h-14 sm:w-16 sm:h-16 object-contain rounded-full border border-gray-200 p-1 bg-white shrink-0 shadow-xs"
            >
            <div>
                <h1 class="text-base sm:text-lg font-bold text-gray-900 uppercase tracking-wide">Formulir Pendaftaran POKJA</h1>
                <p class="text-xs text-gray-600 mt-0.5">Himpunan Mahasiswa Informatika (HIMA IF)</p>
            </div>
        </div>

        <!-- Flash Alert Sukses -->
        @if (session('success'))
            <div class="mb-5 p-3.5 bg-green-50 border border-green-600 text-green-800 text-xs sm:text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-700 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Alert Error Global jika ada kegagalan validasi -->
        @if ($errors->any())
            <div class="mb-5 p-3.5 bg-red-50 border border-red-600 text-red-800 text-xs sm:text-sm">
                <p class="font-semibold mb-1">Terdapat kesalahan pada input Anda:</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Pendaftaran -->
        <form action="{{ route('pendaftaran.store') }}" method="POST" class="space-y-4 sm:space-y-5">
            @csrf

            <!-- Field: Nama -->
            <div>
                <label for="nama" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                    Nama Lengkap <span class="text-red-600">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama" 
                    name="nama" 
                    value="{{ old('nama') }}"
                    placeholder="Contoh: Fulan bin Fulan"
                    required
                    class="w-full bg-gray-50 border @error('nama') border-red-500 bg-red-50/20 @else border-gray-300 @enderror text-gray-900 text-base md:text-sm px-3.5 py-2.5 sm:py-2 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                >
                @error('nama')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: NIM -->
            <div>
                <label for="nim" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                    NIM (Nomor Induk Mahasiswa) <span class="text-red-600">*</span>
                </label>
                <input 
                    type="text" 
                    id="nim" 
                    name="nim" 
                    value="{{ old('nim') }}"
                    placeholder="Contoh: 2111521001"
                    required
                    class="w-full bg-gray-50 border @error('nim') border-red-500 bg-red-50/20 @else border-gray-300 @enderror text-gray-900 text-base md:text-sm px-3.5 py-2.5 sm:py-2 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
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
                    value="{{ old('kelas') }}"
                    placeholder="Contoh: IF-A / IF 2024"
                    required
                    class="w-full bg-gray-50 border @error('kelas') border-red-500 bg-red-50/20 @else border-gray-300 @enderror text-gray-900 text-base md:text-sm px-3.5 py-2.5 sm:py-2 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
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
                    value="{{ old('no_telp') }}"
                    placeholder="Contoh: 081234567890"
                    required
                    class="w-full bg-gray-50 border @error('no_telp') border-red-500 bg-red-50/20 @else border-gray-300 @enderror text-gray-900 text-base md:text-sm px-3.5 py-2.5 sm:py-2 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                >
                @error('no_telp')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs sm:text-sm font-semibold uppercase tracking-wider py-3 px-4 transition cursor-pointer"
                >
                    Kirim Pendaftaran
                </button>
            </div>
        </form>

        <!-- Footer Info -->
        <div class="mt-6 pt-4 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">Pastikan seluruh data yang dimasukkan sudah benar dan valid.</p>
        </div>
    </div>

</body>
</html>
