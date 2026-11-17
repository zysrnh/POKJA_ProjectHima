<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Mahasiswa - {{ $pengaturan->nama_acara ?? 'POKJA HIMA IF' }}</title>

    <!-- Favicon & Web Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Fonts: Bricolage Grotesque (display) + Plus Jakarta Sans (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Bricolage Grotesque"', '"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- QR Code Scanning Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E8F2FA;
            background-image: radial-gradient(rgba(26, 70, 124, 0.28) 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }

        /* Fokus keyboard yang jelas */
        :focus-visible {
            outline: 3px solid #000000;
            outline-offset: 3px;
        }
        input:focus-visible {
            outline: none; /* input sudah punya efek shadow sendiri */
        }

        .btn-tactile {
            transition: transform 0.12s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.12s cubic-bezier(0.2, 0.8, 0.2, 1), background-color 0.12s ease;
        }
        .btn-tactile:hover {
            transform: translate(-2px, -2px);
        }
        .btn-tactile:active {
            transform: translate(2px, 2px) !important;
            box-shadow: 1px 1px 0px 0px #000000 !important;
        }

        @keyframes cardSnapIn {
            0% { transform: translateY(-16px) scale(0.97); }
            70% { transform: translateY(2px) scale(1.005); }
            100% { transform: translateY(0) scale(1); }
        }
        .snap-card {
            animation: cardSnapIn 0.28s cubic-bezier(0.18, 0.89, 0.32, 1.28) forwards;
        }

        @keyframes tabSnap {
            0% { transform: translateY(4px) scale(0.99); }
            100% { transform: translateY(0) scale(1); }
        }
        .tab-snap {
            animation: tabSnap 0.18s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        /* Satu momen utama: stempel "Pendaftaran ditutup" */
        @keyframes stampIn {
            0%   { transform: rotate(-2deg) scale(1.9); opacity: 0; }
            60%  { transform: rotate(-2deg) scale(0.94); opacity: 1; }
            100% { transform: rotate(-2deg) scale(1); opacity: 1; }
        }
        .stamp {
            transform: rotate(-2deg);
            animation: stampIn 0.45s cubic-bezier(0.2, 0.8, 0.2, 1) 0.35s both;
        }

        .grid-paper {
            background-color: #FFFFFF;
            background-image:
                linear-gradient(to right, rgba(26, 70, 124, 0.08) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(26, 70, 124, 0.08) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        @media (prefers-reduced-motion: reduce) {
            .snap-card, .tab-snap, .stamp { animation: none !important; }
            .btn-tactile { transition: none; }
        }
    </style>
</head>
<body class="min-h-screen text-[#000000] flex flex-col p-4 sm:p-6 lg:p-8 antialiased selection:bg-[#2A82C6] selection:text-white">

    <main class="flex-1 py-4 flex items-center justify-center">
        <div class="snap-card w-full max-w-6xl bg-white border-[3px] border-[#000000] shadow-[8px_8px_0px_0px_#000000] grid grid-cols-1 lg:grid-cols-12 overflow-hidden">

            <!-- ============================================================== -->
            <!-- PANEL KIRI: HERO + INFO KEGIATAN                               -->
            <!-- ============================================================== -->
            <section class="lg:col-span-7 grid-paper p-6 sm:p-10 lg:p-12 flex flex-col gap-8 lg:gap-10">

                <!-- Brand -->
                <div class="flex items-center gap-4">
                    <img
                        src="{{ asset('logo/logo.png') }}"
                        alt="Logo HIMA IF"
                        class="w-14 h-14 object-contain bg-white p-2 border-[3px] border-black shadow-[4px_4px_0px_0px_#000000] shrink-0"
                    >
                    <div>
                        <p class="text-sm font-bold text-[#2A82C6] leading-none">HIMA Informatika</p>
                        <p class="font-display text-2xl sm:text-[1.7rem] font-extrabold tracking-tight text-[#1A467C] leading-none mt-1.5">
                            {{ $pengaturan->nama_acara ?? 'POKJA HIMA IF' }}
                        </p>
                    </div>
                </div>

                <!-- Hero -->
                <div class="flex flex-col items-start gap-5">
                    <span class="stamp inline-flex items-center gap-2 bg-[#CA2C2A] text-white border-[3px] border-black px-3.5 py-1.5 text-sm font-extrabold shadow-[4px_4px_0px_0px_#000000]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="square" stroke-width="2.6" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pendaftaran ditutup
                    </span>

                    <h1 class="font-display text-4xl sm:text-5xl xl:text-[3.5rem] font-extrabold leading-[1.02] tracking-tight text-[#1A467C] break-words [text-wrap:balance] [text-shadow:3px_3px_0_#FCD34D]">
                        {{ $pengaturan->deskripsi_acara ?? 'Portal peserta POKJA HIMA IF' }}
                    </h1>

                    <p class="text-sm sm:text-base font-medium text-gray-700 max-w-md leading-relaxed">
                        Presensi, point keaktifan, kelompok, dan kuesioner peserta ada di satu portal ini.
                    </p>
                </div>

                <!-- Info Jadwal & Lokasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Tanggal -->
                    <div class="bg-[#FDE68A] border-[3px] border-black p-4 shadow-[5px_5px_0px_0px_#000000] flex flex-col gap-3">
                        <div class="w-10 h-10 bg-white border-2 border-black text-[#1A467C] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="square" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-black/70">Tanggal</p>
                            <p class="text-base font-extrabold text-black leading-snug">{{ $pengaturan->tanggal_acara ?? '14 November 2026' }}</p>
                        </div>
                    </div>

                    <!-- Waktu -->
                    <div class="bg-[#BDE0F7] border-[3px] border-black p-4 shadow-[5px_5px_0px_0px_#000000] flex flex-col gap-3">
                        <div class="w-10 h-10 bg-white border-2 border-black text-[#1A467C] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="square" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-black/70">Waktu</p>
                            <p class="text-base font-extrabold text-black leading-snug">{{ $pengaturan->jam_acara ?? '15:30 WIB – 17:30 WIB' }}</p>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div class="bg-[#F9C9C6] border-[3px] border-black p-4 shadow-[5px_5px_0px_0px_#000000] flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 sm:col-span-2">
                        <div class="w-10 h-10 bg-white border-2 border-black text-[#CA2C2A] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="square" stroke-width="2.2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="square" stroke-width="2.2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-black/70">Lokasi</p>
                            <p class="text-base font-extrabold text-black leading-snug">{{ $pengaturan->lokasi_acara ?? 'Ruangan 105' }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============================================================== -->
            <!-- PANEL KANAN: SOLID BLUE + FLOATING LOGIN CARD                  -->
            <!-- ============================================================== -->
            <section class="lg:col-span-5 bg-[#2A82C6] border-t-[3px] lg:border-t-0 lg:border-l-[3px] border-black p-6 sm:p-10 flex items-center justify-center">
                <div class="relative w-full max-w-md">
                    <!-- Offset Accent Layer -->
                    <div class="hidden sm:block absolute inset-0 translate-x-3 translate-y-3 bg-[#FCD34D] border-[3px] border-black pointer-events-none" aria-hidden="true"></div>

                    <!-- Floating White Login Card -->
                    <div class="relative bg-white border-[3px] border-black p-6 sm:p-8 shadow-[5px_5px_0px_0px_#000000] sm:shadow-none space-y-6">

                        <div class="border-b-[3px] border-black pb-5">
                            <h2 class="font-display text-2xl sm:text-[1.75rem] font-extrabold tracking-tight text-[#1A467C] leading-tight">
                                Masuk akun mahasiswa
                            </h2>
                            <p class="text-sm font-medium text-gray-600 mt-2 leading-relaxed">
                                Login pakai NIM atau email kampus. Belum punya password? Aktivasi dulu lewat QR tiket.
                            </p>
                        </div>

                        <!-- Flash Alert Error -->
                        @if (session('error') || $errors->any())
                            <div class="p-4 bg-red-50 border-2 border-black text-sm font-bold text-red-900 shadow-[4px_4px_0px_0px_#CA2C2A]">
                                {{ session('error') ?? $errors->first() }}
                            </div>
                        @endif

                        <!-- Flash Alert Success -->
                        @if (session('success'))
                            <div class="p-4 bg-green-50 border-2 border-black text-sm font-bold text-green-900 shadow-[4px_4px_0px_0px_#15803d]">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- TABS: LOGIN & AKTIVASI AKUN -->
                        <div class="grid grid-cols-2 gap-2 border-2 border-black p-1.5 bg-gray-100">
                            <button
                                type="button"
                                id="tab-btn-biasa"
                                onclick="switchTab('biasa')"
                                class="btn-tactile py-2.5 text-sm font-extrabold border-2 cursor-pointer border-black bg-[#1A467C] text-white shadow-[3px_3px_0px_0px_#000000]"
                            >
                                Login
                            </button>
                            <button
                                type="button"
                                id="tab-btn-qr"
                                onclick="switchTab('qr')"
                                class="btn-tactile py-2.5 text-sm font-extrabold border-2 cursor-pointer border-transparent text-gray-600 hover:text-black"
                            >
                                Aktivasi akun
                            </button>
                        </div>

                        <!-- TAB 1: FORM LOGIN BIASA -->
                        <div id="panel-biasa" class="tab-snap">
                            <form action="{{ route('mahasiswa.login.post') }}" method="POST" class="space-y-5">
                                @csrf

                                <div>
                                    <label for="identifier" class="block text-sm font-bold text-[#1A467C] mb-2">
                                        Email kampus / NIM <span class="text-[#CA2C2A]">*</span>
                                    </label>
                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="identifier"
                                            name="identifier"
                                            value="{{ old('identifier') }}"
                                            required
                                            autofocus
                                            autocomplete="username"
                                            placeholder="250414010 atau email kamu"
                                            class="w-full bg-[#F8FBFE] border-2 border-black text-gray-950 font-semibold text-sm px-4 py-3 pl-11 placeholder:text-gray-400 placeholder:font-medium focus:bg-white focus:-translate-x-px focus:-translate-y-px focus:shadow-[4px_4px_0px_0px_#000000] transition"
                                        >
                                        <svg class="w-4 h-4 text-gray-500 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="square" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label for="password" class="block text-sm font-bold text-[#1A467C]">
                                            Password <span class="text-[#CA2C2A]">*</span>
                                        </label>
                                        <button
                                            type="button"
                                            onclick="switchTab('lupa')"
                                            class="text-xs font-bold text-[#CA2C2A] hover:underline cursor-pointer"
                                        >
                                            Lupa password?
                                        </button>
                                    </div>
                                    <div class="relative">
                                        <input
                                            type="password"
                                            id="password"
                                            name="password"
                                            required
                                            autocomplete="current-password"
                                            placeholder="Masukkan password kamu"
                                            class="w-full bg-[#F8FBFE] border-2 border-black text-gray-950 font-semibold text-sm px-4 py-3 pl-11 pr-11 placeholder:text-gray-400 placeholder:font-medium focus:bg-white focus:-translate-x-px focus:-translate-y-px focus:shadow-[4px_4px_0px_0px_#000000] transition"
                                        >
                                        <svg class="w-4 h-4 text-gray-500 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="square" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <button
                                            type="button"
                                            onclick="togglePasswordVisibility('password')"
                                            aria-label="Tampilkan atau sembunyikan password"
                                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-500 hover:text-black cursor-pointer"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="square" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="square" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    class="btn-tactile w-full py-3.5 bg-[#2A82C6] hover:bg-[#1A467C] text-white text-base font-extrabold border-2 border-black shadow-[4px_4px_0px_0px_#000000] hover:shadow-[6px_6px_0px_0px_#000000] cursor-pointer mt-1"
                                >
                                    Masuk
                                </button>
                            </form>
                        </div>

                        <!-- TAB 2: AKTIVASI AKUN DENGAN QR TIKET -->
                        <div id="panel-qr" class="hidden tab-snap space-y-5">
                            <div class="bg-blue-50 border-2 border-black p-4 text-sm text-blue-950 font-medium leading-relaxed shadow-[3px_3px_0px_0px_#1A467C]">
                                <p class="font-extrabold text-[#1A467C] mb-1">Aktivasi akun mahasiswa</p>
                                <p>Upload gambar QR tiket dari email kamu, atau scan pakai kamera untuk bikin password baru.</p>
                            </div>

                            <div>
                                <input
                                    type="file"
                                    id="qr-file-input"
                                    accept="image/*"
                                    onchange="scanQrFromFile(this)"
                                    class="hidden"
                                >
                                <button
                                    type="button"
                                    onclick="document.getElementById('qr-file-input').click()"
                                    class="btn-tactile w-full py-3.5 bg-[#1A467C] hover:bg-[#153660] text-white border-2 border-black font-extrabold text-sm shadow-[4px_4px_0px_0px_#000000] hover:shadow-[6px_6px_0px_0px_#000000] flex items-center justify-center gap-2.5 cursor-pointer"
                                >
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="square" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Pilih gambar QR tiket</span>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <button
                                    type="button"
                                    id="btn-scan-camera"
                                    onclick="toggleCameraScanner()"
                                    class="btn-tactile w-full py-3 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black font-extrabold text-sm shadow-[4px_4px_0px_0px_#000000] hover:shadow-[6px_6px_0px_0px_#000000] flex items-center justify-center gap-2.5 cursor-pointer"
                                >
                                    <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Scan pakai kamera</span>
                                </button>

                                <div id="camera-reader-box" class="hidden border-2 border-black overflow-hidden shadow-[4px_4px_0px_0px_#000000]">
                                    <div id="qr-camera-reader" class="w-full"></div>
                                </div>
                            </div>

                            <div id="qr-status-msg" class="hidden p-3.5 text-sm font-bold border-2 border-black shadow-[3px_3px_0px_0px_#000000]"></div>
                        </div>

                        <!-- TAB 3: LUPA PASSWORD -->
                        <div id="panel-lupa" class="hidden tab-snap space-y-5">
                            <div class="bg-amber-50 border-2 border-black p-4 text-sm text-amber-950 font-medium leading-relaxed shadow-[3px_3px_0px_0px_#d97706]">
                                <p class="font-extrabold text-amber-900 mb-1">Lupa password</p>
                                <p>Masukkan email kampus atau NIM yang terdaftar. Kami kirim tautan untuk mengatur ulang password kamu.</p>
                            </div>

                            @if (session('error_reset'))
                                <div class="p-4 bg-red-50 border-2 border-black text-sm font-bold text-red-900 shadow-[4px_4px_0px_0px_#CA2C2A]">
                                    {{ session('error_reset') }}
                                </div>
                            @endif

                            @if (session('success_reset'))
                                <div class="p-4 bg-green-50 border-2 border-black text-sm font-bold text-green-900 shadow-[4px_4px_0px_0px_#15803d]">
                                    {{ session('success_reset') }}
                                </div>
                            @endif

                            <form action="{{ route('mahasiswa.lupaPassword.post') }}" method="POST" class="space-y-5">
                                @csrf

                                <div>
                                    <label for="reset_identifier" class="block text-sm font-bold text-[#1A467C] mb-2">
                                        Email kampus / NIM <span class="text-[#CA2C2A]">*</span>
                                    </label>
                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="reset_identifier"
                                            name="reset_identifier"
                                            value="{{ old('reset_identifier') }}"
                                            required
                                            placeholder="250414010 atau email kamu"
                                            class="w-full bg-[#F8FBFE] border-2 border-black text-gray-950 font-semibold text-sm px-4 py-3 pl-11 placeholder:text-gray-400 placeholder:font-medium focus:bg-white focus:-translate-x-px focus:-translate-y-px focus:shadow-[4px_4px_0px_0px_#000000] transition"
                                        >
                                        <svg class="w-4 h-4 text-gray-500 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="square" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    class="btn-tactile w-full py-3.5 bg-[#1A467C] hover:bg-[#153660] text-white text-base font-extrabold border-2 border-black shadow-[4px_4px_0px_0px_#000000] hover:shadow-[6px_6px_0px_0px_#000000] cursor-pointer mt-1"
                                >
                                    Kirim tautan reset password
                                </button>

                                <div class="text-center pt-1">
                                    <button
                                        type="button"
                                        onclick="switchTab('biasa')"
                                        class="text-sm font-bold text-gray-600 hover:text-black hover:underline cursor-pointer"
                                    >
                                        ← Kembali ke login
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </section>

        </div>
    </main>

    <!-- Offscreen Scanner Container untuk Html5Qrcode scanFile -->
    <div id="qr-file-scan-worker" style="position: fixed; left: -9999px; top: 0; width: 320px; height: 320px; pointer-events: none; z-index: -999;"></div>

    <!-- Footer -->
    <footer class="w-full text-center py-3 text-xs font-bold text-gray-600">
        &copy; {{ date('Y') }} POKJA HIMA IF • Sistem terpadu presensi &amp; keaktifan
    </footer>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let html5QrScanner = null;
        let isCameraActive = false;

        const TAB_BASE = 'btn-tactile py-2.5 text-sm font-extrabold border-2 cursor-pointer';
        const TAB_ON   = TAB_BASE + ' border-black bg-[#1A467C] text-white shadow-[3px_3px_0px_0px_#000000]';
        const TAB_OFF  = TAB_BASE + ' border-transparent text-gray-600 hover:text-black';

        function switchTab(mode) {
            const btnBiasa = document.getElementById('tab-btn-biasa');
            const btnQr = document.getElementById('tab-btn-qr');
            const panelBiasa = document.getElementById('panel-biasa');
            const panelQr = document.getElementById('panel-qr');
            const panelLupa = document.getElementById('panel-lupa');

            if (mode === 'biasa') {
                btnBiasa.className = TAB_ON;
                btnQr.className = TAB_OFF;
                panelBiasa.classList.remove('hidden');
                panelQr.classList.add('hidden');
                panelLupa.classList.add('hidden');
                stopCameraScanner();
            } else if (mode === 'qr') {
                btnQr.className = TAB_ON;
                btnBiasa.className = TAB_OFF;
                panelQr.classList.remove('hidden');
                panelBiasa.classList.add('hidden');
                panelLupa.classList.add('hidden');
            } else if (mode === 'lupa') {
                btnBiasa.className = TAB_OFF;
                btnQr.className = TAB_OFF;
                panelLupa.classList.remove('hidden');
                panelBiasa.classList.add('hidden');
                panelQr.classList.add('hidden');
                stopCameraScanner();
            }
        }

        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            if (!input) return;
            input.type = (input.type === 'password') ? 'text' : 'password';
        }

        async function verifyQrToken(scannedText) {
            showStatus('Memverifikasi QR tiket...', 'info');
            try {
                const response = await fetch("{{ route('mahasiswa.verifikasiQr') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ token: scannedText })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showStatus(`Tiket ${data.nama} (${data.nim}) valid! Mengarahkan ke form password...`, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 800);
                } else {
                    showStatus(data.message || 'QR Tiket tidak valid atau tidak terdaftar.', 'error');
                }
            } catch (err) {
                showStatus('Terjadi kesalahan jaringan saat verifikasi QR.', 'error');
            }
        }

        async function scanQrFromFile(input) {
            if (!input.files || input.files.length === 0) return;
            const file = input.files[0];
            showStatus('Membaca QR tiket dari file...', 'info');

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = async function() {
                    let decodedText = null;

                    if (window.jsQR) {
                        try {
                            const canvas = document.createElement('canvas');
                            canvas.width = img.naturalWidth || img.width;
                            canvas.height = img.naturalHeight || img.height;
                            const ctx = canvas.getContext('2d', { willReadFrequently: true });
                            ctx.drawImage(img, 0, 0);
                            const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                            const code = jsQR(imgData.data, imgData.width, imgData.height, {
                                inversionAttempts: "attemptBoth",
                            });
                            if (code && code.data) {
                                decodedText = code.data;
                            }
                        } catch (err) {
                            console.warn('jsQR original resolution pass error:', err);
                        }

                        if (!decodedText && (img.naturalWidth > 600 || img.naturalHeight > 600)) {
                            try {
                                const targetW = 500;
                                const scale = targetW / img.naturalWidth;
                                const targetH = Math.round(img.naturalHeight * scale);
                                const canvas = document.createElement('canvas');
                                canvas.width = targetW;
                                canvas.height = targetH;
                                const ctx = canvas.getContext('2d', { willReadFrequently: true });
                                ctx.drawImage(img, 0, 0, targetW, targetH);
                                const imgData = ctx.getImageData(0, 0, targetW, targetH);
                                const code = jsQR(imgData.data, targetW, targetH, {
                                    inversionAttempts: "attemptBoth",
                                });
                                if (code && code.data) {
                                    decodedText = code.data;
                                }
                            } catch (err) {
                                console.warn('jsQR resize pass error:', err);
                            }
                        }
                    }

                    if (decodedText) {
                        verifyQrToken(decodedText);
                        return;
                    }

                    try {
                        const html5Qr = new Html5Qrcode("qr-file-scan-worker");
                        const fallbackText = await html5Qr.scanFile(file, false);
                        await html5Qr.clear();
                        if (fallbackText) {
                            verifyQrToken(fallbackText);
                            return;
                        }
                    } catch (err) {
                        console.warn('Html5Qrcode scanFile fallback error:', err);
                    }

                    showStatus('Tidak ditemukan QR code valid pada gambar tersebut. Pastikan kamu upload gambar QR tiket dari email.', 'error');
                };

                img.onerror = function() {
                    showStatus('Gagal membaca gambar. Pastikan format file PNG atau JPG.', 'error');
                };

                img.src = e.target.result;
            };

            reader.readAsDataURL(file);
        }

        async function toggleCameraScanner() {
            const box = document.getElementById('camera-reader-box');
            const btn = document.getElementById('btn-scan-camera');

            if (isCameraActive) {
                stopCameraScanner();
            } else {
                box.classList.remove('hidden');
                btn.innerHTML = '<span>Tutup kamera</span>';
                isCameraActive = true;

                html5QrScanner = new Html5Qrcode("qr-camera-reader");
                try {
                    await html5QrScanner.start(
                        { facingMode: "environment" },
                        { fps: 10, qrbox: { width: 220, height: 220 } },
                        (decodedText) => {
                            stopCameraScanner();
                            verifyQrToken(decodedText);
                        },
                        () => {}
                    );
                } catch (err) {
                    try {
                        await html5QrScanner.start(
                            { facingMode: "user" },
                            { fps: 10, qrbox: { width: 220, height: 220 } },
                            (decodedText) => {
                                stopCameraScanner();
                                verifyQrToken(decodedText);
                            },
                            () => {}
                        );
                    } catch (err2) {
                        showStatus('Gagal mengakses kamera. Izinkan akses kamera atau pakai opsi upload gambar.', 'error');
                        stopCameraScanner();
                    }
                }
            }
        }

        function stopCameraScanner() {
            if (html5QrScanner && isCameraActive) {
                html5QrScanner.stop().then(() => {
                    html5QrScanner.clear();
                    const box = document.getElementById('camera-reader-box');
                    if (box) box.classList.add('hidden');
                    const btn = document.getElementById('btn-scan-camera');
                    if (btn) btn.innerHTML = '<span>Scan pakai kamera</span>';
                    isCameraActive = false;
                }).catch(() => {
                    isCameraActive = false;
                });
            }
        }

        function showStatus(msg, type) {
            const box = document.getElementById('qr-status-msg');
            box.classList.remove('hidden', 'bg-red-50', 'text-red-900', 'bg-green-50', 'text-green-900', 'bg-blue-50', 'text-blue-900');
            if (type === 'error') {
                box.classList.add('bg-red-50', 'text-red-900');
            } else if (type === 'success') {
                box.classList.add('bg-green-50', 'text-green-900');
            } else {
                box.classList.add('bg-blue-50', 'text-blue-900');
            }
            box.innerText = msg;
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('error_reset') || session('success_reset'))
                switchTab('lupa');
            @endif
        });
    </script>

</body>
</html>