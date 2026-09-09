<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran POKJA - HIMA IF</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-lg bg-white border border-gray-300 p-6 md:p-8 shadow-sm">
        <!-- Header -->
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h1 class="text-xl font-bold text-gray-900 uppercase tracking-wide">Formulir Pendaftaran POKJA</h1>
            <p class="text-xs text-gray-600 mt-1">Himpunan Mahasiswa Informatika (HIMA IF)</p>
        </div>

        <!-- Form Mentahan -->
        <form action="#" method="POST" class="space-y-5" onsubmit="event.preventDefault();">
            @csrf

            <!-- Field: Nama -->
            <div>
                <label for="nama" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                    Nama Lengkap <span class="text-red-600">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama" 
                    name="nama" 
                    placeholder="Contoh: Fulan bin Fulan"
                    required
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                >
            </div>

            <!-- Field: NIM -->
            <div>
                <label for="nim" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                    NIM (Nomor Induk Mahasiswa) <span class="text-red-600">*</span>
                </label>
                <input 
                    type="text" 
                    id="nim" 
                    name="nim" 
                    placeholder="Contoh: 2111521001"
                    required
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                >
            </div>

            <!-- Field: Kelas -->
            <div>
                <label for="kelas" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                    Kelas <span class="text-red-600">*</span>
                </label>
                <input 
                    type="text" 
                    id="kelas" 
                    name="kelas" 
                    placeholder="Contoh: IF-A / IF 2024"
                    required
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                >
            </div>

            <!-- Field: No Telp -->
            <div>
                <label for="no_telp" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                    No. Telepon / WhatsApp <span class="text-red-600">*</span>
                </label>
                <input 
                    type="tel" 
                    id="no_telp" 
                    name="no_telp" 
                    placeholder="Contoh: 081234567890"
                    required
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                >
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold uppercase tracking-wider py-3 px-4 transition cursor-pointer"
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
