<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Mahasiswa - POKJA HIMA IF</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E8F2FA;
            background-image: radial-gradient(#1A467C 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }

        .btn-smooth {
            transition: transform 0.15s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.15s cubic-bezier(0.2, 0.8, 0.2, 1), background-color 0.15s ease;
        }
        .btn-smooth:hover {
            transform: translate(-2px, -2px);
        }
        .btn-smooth:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px 0px #000000 !important;
        }

        @keyframes cardSnapIn {
            0% {
                transform: translateY(-20px) scale(0.97);
                opacity: 0;
            }
            100% {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }
        .snap-card {
            animation: cardSnapIn 0.35s cubic-bezier(0.18, 0.89, 0.32, 1.28) forwards;
        }
    </style>
</head>
<body class="min-h-screen text-[#000000] flex flex-col justify-between p-4 sm:p-6 antialiased selection:bg-[#2A82C6] selection:text-white">

    <!-- Top Simple Bar -->
    <header class="w-full max-w-md mx-auto flex items-center justify-between py-2">
        <a href="{{ route('pendaftaran.index') }}" class="btn-smooth inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000]">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="square" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Form Pendaftaran</span>
        </a>

        <a href="{{ route('presensi.absen') }}" class="btn-smooth inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#15803d] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000]">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="square" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
            </svg>
            <span>Scan Presensi</span>
        </a>
    </header>

    <!-- Main Container -->
    <main class="my-auto py-6 flex flex-col items-center justify-center">

        <div class="snap-card w-full max-w-md bg-white border-[3px] border-[#000000] p-6 sm:p-8 shadow-[8px_8px_0px_0px_#000000] space-y-6">

            <!-- Logo & Title -->
            <div class="text-center space-y-2 border-b-2 border-black pb-5">
                <img 
                    src="{{ asset('logo/logo.png') }}" 
                    alt="Logo HIMA IF" 
                    class="w-14 h-14 mx-auto object-contain bg-white p-1.5 border-2 border-black shadow-[3px_3px_0px_0px_#000000]"
                >
                <div>
                    <h1 class="text-lg sm:text-xl font-black uppercase tracking-tight text-[#1A467C]">
                        Portal Mahasiswa POKJA
                    </h1>
                    <p class="text-xs font-semibold text-gray-600 mt-0.5">
                        Masukkan NIM untuk melihat tiket & rekap kehadiran 7 pertemuan.
                    </p>
                </div>
            </div>

            <!-- Flash Alert Error -->
            @if (session('error') || $errors->any())
                <div class="p-3.5 bg-red-50 border-2 border-black text-xs font-bold text-red-900 shadow-[3px_3px_0px_0px_#CA2C2A]">
                    {{ session('error') ?? $errors->first() }}
                </div>
            @endif

            <!-- Flash Alert Success -->
            @if (session('success'))
                <div class="p-3.5 bg-green-50 border-2 border-black text-xs font-bold text-green-900 shadow-[3px_3px_0px_0px_#15803d]">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form Login -->
            <form action="{{ route('mahasiswa.login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="nim" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                        NIM Mahasiswa (9 Digit Angka) <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="nim" 
                            name="nim" 
                            value="{{ old('nim') }}"
                            required
                            autofocus
                            inputmode="numeric"
                            maxlength="9"
                            placeholder="Contoh: 250414010"
                            oninput="this.value = this.value.replace(/\D/g, '')"
                            class="w-full bg-[#F8FBFE] border-2 border-black text-gray-950 font-mono font-black text-base px-3.5 py-3 pl-10 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#000000] transition"
                        >
                        <svg class="w-5 h-5 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <p class="text-[11px] font-semibold text-gray-500 mt-1">
                        Gunakan NIM resmi yang Anda daftarkan pada form registrasi POKJA.
                    </p>
                </div>

                <button 
                    type="submit" 
                    class="btn-smooth w-full py-3 sm:py-3.5 bg-[#2A82C6] hover:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center gap-2 cursor-pointer"
                >
                    <span>Masuk ke Dashboard</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- Bottom Helpers -->
            <div class="pt-4 border-t-2 border-black text-center space-y-2">
                <p class="text-xs font-semibold text-gray-600">
                    Belum mendaftar sebagai peserta?
                </p>
                <a 
                    href="{{ route('pendaftaran.index') }}" 
                    class="btn-smooth inline-block px-4 py-2 bg-white hover:bg-gray-100 text-black border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000]"
                >
                    Daftar Sekarang
                </a>
            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full text-center py-2 text-xs font-bold text-gray-500">
        &copy; {{ date('Y') }} POKJA HIMA IF • Sistem Terpadu Registrasi & Presensi
    </footer>

</body>
</html>
