<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Pendaftaran {{ $pengaturan->nama_acara ?? 'POKJA' }} - HIMA IF</title>
    
    <!-- Favicon & Web Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Google Font: Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                    },
                    colors: {
                        hima: {
                            'blue-light': '#2A82C6',
                            'blue-dark': '#1A467C',
                            'red-light': '#CA2C2A',
                            'red-dark': '#901C1A',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #E8F2FA;
            background-image: radial-gradient(#1A467C 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }

        .dot-card {
            background-color: #F8FBFE;
            background-image: radial-gradient(#1A467C 1px, transparent 1px);
            background-size: 16px 16px;
        }

        /* Tape effect on polaroid badge */
        .tape-badge {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(2px);
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        }

        /* Smooth Floating Ambient Accents */
        @keyframes gentleFloat1 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(2deg); }
        }
        @keyframes gentleFloat2 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(6px) rotate(-3deg); }
        }

        .anim-float-1 {
            animation: gentleFloat1 6s ease-in-out infinite;
        }
        .anim-float-2 {
            animation: gentleFloat2 7s ease-in-out infinite;
        }

        /* Polaroid Playful Wobble on Click */
        @keyframes polaroidWobble {
            0% { transform: rotate(-2deg) scale(1); }
            20% { transform: rotate(12deg) scale(1.12); }
            40% { transform: rotate(-10deg) scale(1.08); }
            60% { transform: rotate(6deg) scale(1.04); }
            80% { transform: rotate(-4deg) scale(1.01); }
            100% { transform: rotate(-2deg) scale(1); }
        }
        .wobble-active {
            animation: polaroidWobble 0.65s cubic-bezier(0.36, 0.07, 0.19, 0.97) both !important;
        }

        /* Interactive Shape Bounce & Spin */
        @keyframes shapePopSpin {
            0% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.35) rotate(180deg); }
            100% { transform: scale(1) rotate(360deg); }
        }
        .shape-popping {
            animation: shapePopSpin 0.55s cubic-bezier(0.34, 1.56, 0.64, 1) both !important;
        }

        /* Interactive 3D Perspective Card Container */
        .card-perspective {
            perspective: 1000px;
        }
        .tilt-card {
            transform-style: preserve-3d;
            transition: transform 0.15s ease-out, box-shadow 0.15s ease-out;
            will-change: transform;
        }

        /* Live Validation Pop-in Checkmark */
        @keyframes fieldCheckPop {
            0% { opacity: 0; transform: scale(0.4) rotate(-20deg); }
            70% { transform: scale(1.2) rotate(5deg); }
            100% { opacity: 1; transform: scale(1) rotate(0deg); }
        }
        .field-valid-badge {
            animation: fieldCheckPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        /* Smooth Page Enter Animations */
        @keyframes slideFadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Smooth Floating Card Keyframes */
        @keyframes floatCard1 {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-4px); }
        }
        @keyframes floatCard2 {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(3.5px); }
        }
        @keyframes floatCard3 {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-3.5px); }
        }

        .anim-fade-1 {
            animation: slideFadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards, floatCard1 5.5s ease-in-out 0.5s infinite;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .anim-fade-1:hover {
            animation-play-state: paused;
            transform: translateY(-6px);
        }

        .anim-fade-2 {
            animation: slideFadeUp 0.55s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both, floatCard2 6.2s ease-in-out 0.65s infinite;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .anim-fade-2:hover {
            animation-play-state: paused;
            transform: translateY(-5px);
        }

        .anim-fade-3 {
            animation: slideFadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both, floatCard3 7s ease-in-out 0.8s infinite;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .anim-fade-3:focus-within,
        .anim-fade-3:hover {
            animation-play-state: paused;
            transform: translateY(-3px);
        }

        /* Modal Smooth Pop-in with Tactile Spring */
        @keyframes modalSpringIn {
            0% {
                opacity: 0;
                transform: scale(0.85) translateY(20px);
            }
            70% {
                transform: scale(1.02) translateY(-2px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes modalSpringOut {
            0% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            100% {
                opacity: 0;
                transform: scale(0.85) translateY(16px);
            }
        }

        @keyframes backdropFade {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes backdropFadeOut {
            0% { opacity: 1; }
            100% { opacity: 0; }
        }

        .modal-spring-in {
            animation: modalSpringIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .modal-spring-out {
            animation: modalSpringOut 0.22s cubic-bezier(0.4, 0, 1, 1) forwards !important;
        }

        .backdrop-fade-in {
            animation: backdropFade 0.25s ease-out forwards;
        }

        .backdrop-fade-out {
            animation: backdropFadeOut 0.22s ease-in forwards !important;
        }

        .checkmark-pop {
            animation: checkmarkPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) 0.15s both;
        }

        /* Smooth Interactive Controls */
        .btn-smooth {
            transition: transform 0.18s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.18s cubic-bezier(0.2, 0.8, 0.2, 1), background-color 0.15s ease;
        }
        .btn-smooth:hover {
            transform: translate(-2px, -2px);
        }
        .btn-smooth:active {
            transform: translate(2px, 2px);
        }
    </style>
</head>
<body class="text-[#000000] min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-10 antialiased selection:bg-[#2A82C6] selection:text-white relative overflow-x-hidden">

    <!-- Floating Interactive Geometric Accents (Click/Tap for fun tactile pop) -->
    <div onclick="popShape(this)" title="Klik aku!" class="cursor-pointer select-none absolute top-6 sm:top-8 left-4 sm:left-8 w-10 h-10 sm:w-14 sm:h-14 bg-[#2A82C6]/30 border-2 border-[#1A467C] shadow-[3px_3px_0px_0px_#1A467C] sm:shadow-[4px_4px_0px_0px_#1A467C] hover:scale-110 active:scale-90 transition-transform anim-float-1 z-0"></div>
    <div onclick="popShape(this)" title="Klik aku!" class="cursor-pointer select-none absolute bottom-8 sm:bottom-12 left-4 sm:left-12 w-8 h-8 sm:w-10 sm:h-10 bg-[#CA2C2A]/25 border-2 border-[#901C1A] shadow-[2px_2px_0px_0px_#901C1A] sm:shadow-[3px_3px_0px_0px_#901C1A] hover:scale-110 active:scale-90 transition-transform anim-float-2 z-0"></div>
    <div onclick="popShape(this)" title="Klik aku!" class="cursor-pointer select-none absolute top-12 sm:top-16 right-4 sm:right-12 w-9 h-9 sm:w-12 sm:h-12 bg-white/90 border-2 border-[#1A467C] shadow-[3px_3px_0px_0px_#1A467C] sm:shadow-[4px_4px_0px_0px_#1A467C] hover:scale-110 active:scale-90 transition-transform anim-float-2 z-0"></div>
    <div onclick="popShape(this)" title="Klik aku!" class="cursor-pointer select-none absolute bottom-6 sm:bottom-8 right-4 sm:right-16 w-11 h-11 sm:w-16 sm:h-16 bg-[#1A467C]/20 border-2 border-[#000000] shadow-[3px_3px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000] hover:scale-110 active:scale-90 transition-transform anim-float-1 z-0"></div>

    <!-- Main Wrapper: Separated Sequential Cards (Judul -> Waktu & Tempat -> Form) -->
    <div class="card-perspective w-full max-w-4xl z-10 my-2 sm:my-8 space-y-3.5 sm:space-y-6">

        <!-- 1. BAGIAN ATAS: CARD JUDUL & LOGO POLAROID -->
        <div class="anim-fade-1 tilt-card bg-white border-2 border-[#1A467C] p-4 sm:p-8 shadow-[5px_5px_0px_0px_#1A467C] sm:shadow-[8px_8px_0px_0px_#1A467C] transition-all duration-200">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-4 sm:gap-6 text-center md:text-left">
                
                <!-- Polaroid Logo Badge with Tape Header (Click to Wobble) -->
                <div class="relative group shrink-0">
                    <div class="absolute -top-2.5 left-1/2 -translate-x-1/2 w-12 sm:w-14 h-4 sm:h-5 tape-badge border border-gray-400 z-10"></div>
                    
                    <div 
                        id="polaroid-badge"
                        onclick="wobblePolaroid(this)"
                        title="Klik untuk efek goyang!"
                        class="bg-white border-2 border-[#1A467C] p-2 sm:p-2.5 shadow-[3px_3px_0px_0px_#1A467C] sm:shadow-[4px_4px_0px_0px_#1A467C] rotate-[-2deg] hover:rotate-0 transition-transform duration-300 ease-out cursor-pointer active:scale-95 select-none"
                    >
                        <img 
                            src="{{ asset('logo/logo.png') }}" 
                            alt="Logo HIMA IF" 
                            class="w-14 h-14 sm:w-24 sm:h-24 object-contain mx-auto bg-gray-50 border border-gray-200 p-0.5 sm:p-1 pointer-events-none"
                        >
                        <p class="text-[9px] sm:text-[10px] font-black font-mono text-center text-[#1A467C] mt-1 sm:mt-2 uppercase tracking-wider">
                            #HIMA_IF
                        </p>
                    </div>
                </div>

                <!-- Big Headline Section -->
                <div class="flex-1">
                    <span class="inline-block bg-[#1A467C] text-white text-[10px] sm:text-xs font-black uppercase px-2.5 py-0.5 tracking-widest mb-1.5 sm:mb-2 shadow-[2px_2px_0px_0px_#2A82C6]">
                        POKJA HIMA IF 2026
                    </span>
                    <h1 class="text-xl sm:text-4xl lg:text-5xl font-black text-[#1A467C] tracking-tight uppercase leading-snug sm:leading-tight">
                        {{ $pengaturan->deskripsi_acara ?? 'INNOVATIVE IDEA TO GREAT PROPOSAL' }}
                    </h1>
                    <div class="w-16 sm:w-24 h-1.5 sm:h-2 bg-[#CA2C2A] shadow-[2px_2px_0px_0px_#901C1A] mt-2 sm:mt-3 mx-auto md:mx-0"></div>
                </div>
            </div>
        </div>

        <!-- 2. BAGIAN TENGAH: CARD INFO ACARA (3 SEKAT ELEGAN) -->
        <div class="anim-fade-2 tilt-card bg-white border-2 border-[#1A467C] shadow-[5px_5px_0px_0px_#1A467C] sm:shadow-[6px_6px_0px_0px_#1A467C] grid grid-cols-3 divide-x-2 divide-[#1A467C]/20 p-2 sm:p-3">
            
            <!-- Sekat 1: Tanggal Pelaksanaan -->
            <div class="px-1.5 sm:px-4 py-1 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-1 sm:gap-3">
                <div class="p-1 sm:p-2 bg-blue-50 border sm:border-2 border-[#2A82C6] text-[#2A82C6] shadow-[1px_1px_0px_0px_#1A467C] sm:shadow-[2px_2px_0px_0px_#1A467C] shrink-0">
                    <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="min-w-0 w-full">
                    <span class="text-[8px] sm:text-[10px] font-black uppercase tracking-wider text-[#2A82C6] block leading-tight">
                        TANGGAL
                    </span>
                    <span class="font-extrabold text-[11px] sm:text-sm text-gray-900 block leading-tight truncate mt-0.5" title="{{ $pengaturan->tanggal_acara ?? '13 Oktober 2026' }}">
                        {{ $pengaturan->tanggal_acara ?? '13 Oktober 2026' }}
                    </span>
                </div>
            </div>

            <!-- Sekat 2: Waktu Pelaksanaan -->
            <div class="px-1.5 sm:px-4 py-1 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-1 sm:gap-3">
                <div class="p-1 sm:p-2 bg-blue-50 border sm:border-2 border-[#1A467C] text-[#1A467C] shadow-[1px_1px_0px_0px_#1A467C] sm:shadow-[2px_2px_0px_0px_#1A467C] shrink-0">
                    <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0 w-full">
                    <span class="text-[8px] sm:text-[10px] font-black uppercase tracking-wider text-[#1A467C] block leading-tight">
                        WAKTU
                    </span>
                    <span class="font-extrabold text-[11px] sm:text-sm text-gray-900 block leading-tight truncate mt-0.5" title="{{ $pengaturan->jam_acara ?? '08:00 WIB - Selesai' }}">
                        {{ $pengaturan->jam_acara ?? '08:00 WIB - Selesai' }}
                    </span>
                </div>
            </div>

            <!-- Sekat 3: Tempat / Ruangan -->
            <div class="px-1.5 sm:px-4 py-1 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-1 sm:gap-3">
                <div class="p-1 sm:p-2 bg-red-50 border sm:border-2 border-[#CA2C2A] text-[#CA2C2A] shadow-[1px_1px_0px_0px_#901C1A] sm:shadow-[2px_2px_0px_0px_#901C1A] shrink-0">
                    <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="square" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="min-w-0 w-full">
                    <span class="text-[8px] sm:text-[10px] font-black uppercase tracking-wider text-[#CA2C2A] block leading-tight">
                        LOKASI
                    </span>
                    <span class="font-extrabold text-[11px] sm:text-sm text-gray-900 block leading-tight truncate mt-0.5" title="{{ $pengaturan->lokasi_acara ?? 'Ruangan 105' }}">
                        {{ $pengaturan->lokasi_acara ?? 'Ruangan 105' }}
                    </span>
                </div>
            </div>

        </div>

        <!-- 3. BAGIAN BAWAH: CARD FORMULIR PENDAFTARAN -->
        <div class="anim-fade-3 tilt-card bg-white border-2 border-[#1A467C] p-4 sm:p-8 shadow-[5px_5px_0px_0px_#1A467C] sm:shadow-[8px_8px_0px_0px_#1A467C]">

            <!-- Alert Error Global jika ada validasi gagal -->
            @if ($errors->any())
                <div id="alert-box" class="mb-3 sm:mb-6 p-2.5 sm:p-4 bg-[#FEE2E2] border-2 border-[#901C1A] text-red-950 text-xs sm:text-sm shadow-[3px_3px_0px_0px_#901C1A] sm:shadow-[4px_4px_0px_0px_#901C1A] animate-pulse">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="bg-[#CA2C2A] text-white font-black text-xs px-2 py-0.5 border border-[#901C1A] shrink-0">PERIKSA</span>
                        <span class="font-bold">Terdapat kesalahan input:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs font-semibold text-red-900">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Pendaftaran (2-Column Fields Grid on Desktop, 1-Column on Mobile) -->
            <form 
                id="form-pendaftaran"
                action="{{ route('pendaftaran.store') }}" 
                method="POST" 
                class="space-y-3 sm:space-y-5"
                onsubmit="handleSubmit(event)"
            >
                @csrf

                <!-- Anti-bot Honeypot Field -->
                <div class="hidden" aria-hidden="true">
                    <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                </div>

                <!-- Fields Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-5">
                    
                    <!-- Field: Nama Lengkap -->
                    <div>
                        <label for="nama" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1 sm:mb-1.5 flex items-center justify-between">
                            <span>Nama Lengkap <span class="text-[#CA2C2A]">*</span></span>
                            <span id="hint-nama" class="text-[10px] font-mono text-gray-400">min. 3 huruf</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="nama" 
                                name="nama" 
                                value="{{ session('success') ? '' : old('nama') }}"
                                placeholder="Masukkan nama lengkap Anda..."
                                maxlength="100"
                                required
                                oninput="validateLive('nama')"
                                class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] pl-3.5 pr-8 py-2 sm:pl-4 sm:pr-9 sm:py-3 text-xs sm:text-sm font-semibold text-[#000000] focus:bg-white focus:outline-none focus:shadow-[4px_4px_0px_0px_#1A467C] transition-all duration-200 @error('nama') border-[#CA2C2A] bg-red-50 @enderror"
                            >
                            <!-- Live Checkmark Badge -->
                            <div id="check-nama" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 bg-green-600 text-white border border-[#1A467C] text-[9px] sm:text-[11px] font-black flex items-center justify-center shadow-[1px_1px_0px_0px_#1A467C] field-valid-badge">
                                ✓
                            </div>
                        </div>
                        @error('nama')
                            <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Field: NIM -->
                    <div>
                        <label for="nim" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1 sm:mb-1.5 flex items-center justify-between">
                            <span>NIM <span class="text-[#CA2C2A]">*</span></span>
                            <span id="hint-nim" class="text-[10px] font-mono text-gray-400">angka</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="nim" 
                                name="nim" 
                                inputmode="numeric"
                                maxlength="20"
                                oninput="this.value = this.value.replace(/\s+/g, ''); validateLive('nim')"
                                value="{{ session('success') ? '' : old('nim') }}"
                                placeholder="Contoh: 250414000"
                                required
                                class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] pl-3.5 pr-8 py-2 sm:pl-4 sm:pr-9 sm:py-3 text-xs sm:text-sm font-semibold font-mono text-[#000000] focus:bg-white focus:outline-none focus:shadow-[4px_4px_0px_0px_#1A467C] transition-all duration-200 @error('nim') border-[#CA2C2A] bg-red-50 @enderror"
                            >
                            <!-- Live Checkmark Badge -->
                            <div id="check-nim" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 bg-green-600 text-white border border-[#1A467C] text-[9px] sm:text-[11px] font-black flex items-center justify-center shadow-[1px_1px_0px_0px_#1A467C] field-valid-badge">
                                ✓
                            </div>
                        </div>
                        @error('nim')
                            <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Field: Kelas (Custom Neubrutalism Dropdown Pop-over) -->
                    <div class="relative" id="custom-select-container">
                        <label for="input-kelas-value" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1 sm:mb-1.5 flex items-center justify-between">
                            <span>Kelas <span class="text-[#CA2C2A]">*</span></span>
                            <span id="check-kelas" class="hidden text-green-700 font-bold text-xs">✓ Terpilih</span>
                        </label>
                        
                        @php
                            $selectedKelas = session('success') ? '' : old('kelas');
                        @endphp
                        <!-- Hidden input to submit form data -->
                        <input 
                            type="hidden" 
                            id="input-kelas-value" 
                            name="kelas" 
                            value="{{ $selectedKelas }}"
                            required
                        >

                        <!-- Custom Trigger Button (Works identically across desktop and mobile) -->
                        <button 
                            type="button" 
                            id="dropdown-kelas-trigger"
                            onclick="toggleKelasDropdown()"
                            class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] px-3.5 py-2 sm:px-4 sm:py-3 text-left flex items-center justify-between text-xs sm:text-sm font-bold font-mono focus:bg-white focus:outline-none focus:shadow-[4px_4px_0px_0px_#1A467C] transition-all duration-150 cursor-pointer @error('kelas') border-[#CA2C2A] bg-red-50 @enderror"
                        >
                            <span id="dropdown-kelas-label" class="{{ empty($selectedKelas) ? 'text-gray-400 font-normal' : 'text-gray-900 font-bold' }}">
                                {{ !empty($selectedKelas) ? $selectedKelas : '-- PILIH KELAS ANDA --' }}
                            </span>
                            <svg id="dropdown-chevron-icon" class="w-5 h-5 text-[#1A467C] border-l-2 border-[#1A467C] pl-1 transform transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu List (Neubrutalism Popover) -->
                        <div 
                            id="dropdown-kelas-menu" 
                            class="hidden absolute z-50 left-0 right-0 bg-white border-2 border-[#1A467C] shadow-[5px_5px_0px_0px_#1A467C] sm:shadow-[6px_6px_0px_0px_#1A467C] max-h-48 sm:max-h-56 overflow-y-auto divide-y divide-gray-100"
                        >
                            @foreach ($kelasList as $k)
                                <div 
                                    onclick="pilihKelas('{{ $k->nama_kelas }}')"
                                    class="dropdown-kelas-item px-4 py-2 sm:px-4 sm:py-2.5 text-xs sm:text-sm font-bold font-mono text-gray-900 hover:bg-[#2A82C6] hover:text-white cursor-pointer transition-colors flex items-center justify-between {{ $selectedKelas === $k->nama_kelas ? 'bg-blue-50 text-[#1A467C]' : '' }}"
                                    data-value="{{ $k->nama_kelas }}"
                                >
                                    <span>{{ $k->nama_kelas }}</span>
                                    <span class="item-check {{ $selectedKelas === $k->nama_kelas ? '' : 'hidden' }} text-xs font-black">✓</span>
                                </div>
                            @endforeach
                        </div>

                        @error('kelas')
                            <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Field: No Telp / WhatsApp -->
                    <div>
                        <label for="no_telp" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1 sm:mb-1.5 flex items-center justify-between">
                            <span>No. WhatsApp <span class="text-[#CA2C2A]">*</span></span>
                            <span id="hint-wa" class="text-[10px] font-mono text-gray-400">08xx / 62xx</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="tel" 
                                id="no_telp" 
                                name="no_telp" 
                                inputmode="tel"
                                maxlength="15"
                                oninput="this.value = this.value.replace(/\s+/g, ''); validateLive('no_telp')"
                                value="{{ session('success') ? '' : old('no_telp') }}"
                                placeholder="Contoh: 081234567890"
                                required
                                class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] pl-3.5 pr-8 py-2 sm:pl-4 sm:pr-9 sm:py-3 text-xs sm:text-sm font-semibold font-mono text-[#000000] focus:bg-white focus:outline-none focus:shadow-[4px_4px_0px_0px_#1A467C] transition-all duration-200 @error('no_telp') border-[#CA2C2A] bg-red-50 @enderror"
                            >
                            <!-- Live Checkmark Badge -->
                            <div id="check-no_telp" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 bg-green-600 text-white border border-[#1A467C] text-[9px] sm:text-[11px] font-black flex items-center justify-center shadow-[1px_1px_0px_0px_#1A467C] field-valid-badge">
                                ✓
                            </div>
                        </div>
                        @error('no_telp')
                            <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="pt-2 sm:pt-3">
                    <button 
                        type="submit" 
                        id="btn-submit"
                        class="btn-smooth w-full bg-[#2A82C6] hover:bg-[#1A467C] active:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-widest py-3 sm:py-4 px-6 border-2 border-[#1A467C] shadow-[5px_5px_0px_0px_#1A467C] hover:shadow-[7px_7px_0px_0px_#1A467C] cursor-pointer flex items-center justify-center gap-2.5"
                    >
                        <span id="btn-text">KIRIM PENDAFTARAN SEKARANG</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>

        </div>

    </div>    <!-- POP-UP MODAL SUKSES (Clean Minimalist Tactile Animated Neubrutalism) -->
    @if (session('success'))
        @php
            $linkGrupWa = $pengaturan->link_grup_wa ?? 'https://chat.whatsapp.com/';
        @endphp

        <div id="modal-sukses" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs backdrop-fade-in transition-opacity duration-200">
            <!-- Modal Card Content (Smooth Spring Pop-in Animated) -->
            <div id="modal-card" class="modal-spring-in w-full max-w-md bg-white border-2 border-[#1A467C] p-6 sm:p-7 shadow-[10px_10px_0px_0px_#1A467C] relative text-center">
                
                <!-- Close Button (Pojok Kanan Atas) -->
                <button 
                    type="button" 
                    onclick="tutupModal()" 
                    class="btn-smooth absolute top-3 right-3 p-1.5 border border-[#1A467C] text-[#1A467C] hover:bg-[#CA2C2A] hover:text-white hover:border-[#901C1A] cursor-pointer"
                    title="Tutup Modal"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Clean Animated Checkmark Icon -->
                <div class="checkmark-pop w-14 h-14 mx-auto mb-4 bg-green-50 border-2 border-green-700 text-green-700 flex items-center justify-center shadow-[3px_3px_0px_0px_#15803d]">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <!-- Headline Title -->
                <h3 class="text-xl sm:text-2xl font-black text-[#1A467C] uppercase tracking-tight mb-2">
                    Pendaftaran Berhasil!
                </h3>
                
                <!-- Confirmation Message -->
                <p class="text-xs sm:text-sm font-semibold text-gray-800 leading-relaxed max-w-xs mx-auto mb-3">
                    Data pendaftaran kamu telah berhasil tersimpan di sistem.
                </p>

                <!-- Note Join Grup WA -->
                <div class="bg-green-50/90 border border-green-700 p-2.5 max-w-sm mx-auto mb-5 text-[11px] sm:text-xs font-semibold text-green-950">
                    Silakan bergabung ke <strong>Grup WhatsApp Peserta</strong> untuk mendapatkan informasi teknis dan jadwal acara:
                </div>

                <!-- Action Button 1: Join WhatsApp Group Button -->
                <a 
                    href="{{ $linkGrupWa }}" 
                    target="_blank"
                    class="btn-smooth w-full inline-flex items-center justify-center gap-2 bg-green-700 hover:bg-green-800 text-white font-black text-xs uppercase tracking-wider py-3.5 px-4 border-2 border-green-950 shadow-[4px_4px_0px_0px_#14532d] hover:shadow-[6px_6px_0px_0px_#14532d] cursor-pointer mb-2.5"
                >
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    <span>GABUNG GRUP WHATSAPP PESERTA</span>
                </a>

                <!-- Action Button 2: Simple Close Link -->
                <button 
                    type="button" 
                    onclick="tutupModal()" 
                    class="w-full text-xs font-bold text-gray-500 hover:text-black py-1.5 transition cursor-pointer"
                >
                    Tutup Jendela
                </button>

            </div>
        </div>
    @endif

    <script>
        // Custom Dropdown Kelas Functions (Smart Auto Drop-up / Drop-down)
        function toggleKelasDropdown() {
            const menu = document.getElementById('dropdown-kelas-menu');
            const trigger = document.getElementById('dropdown-kelas-trigger');
            const chevron = document.getElementById('dropdown-chevron-icon');
            if (menu && trigger) {
                const isHidden = menu.classList.contains('hidden');
                if (isHidden) {
                    // Cek sisa ruang layar di bawah tombol trigger
                    const rect = trigger.getBoundingClientRect();
                    const spaceBelow = window.innerHeight - rect.bottom;
                    const spaceAbove = rect.top;

                    // Jika ruang di bawah kurang dari 230px, buka ke ATAS (drop-up) agar tidak terpotong layar
                    if (spaceBelow < 230 && spaceAbove > spaceBelow) {
                        menu.classList.remove('top-full', 'mt-1.5');
                        menu.classList.add('bottom-full', 'mb-1.5');
                    } else {
                        menu.classList.remove('bottom-full', 'mb-1.5');
                        menu.classList.add('top-full', 'mt-1.5');
                    }

                    menu.classList.remove('hidden');
                    if (chevron) chevron.classList.add('rotate-180');
                } else {
                    menu.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                }
            }
        }

        function pilihKelas(val) {
            const input = document.getElementById('input-kelas-value');
            const label = document.getElementById('dropdown-kelas-label');
            const menu = document.getElementById('dropdown-kelas-menu');
            const chevron = document.getElementById('dropdown-chevron-icon');
            const trigger = document.getElementById('dropdown-kelas-trigger');

            if (input) input.value = val;
            if (label) {
                label.textContent = val;
                label.classList.remove('text-gray-400', 'font-normal');
                label.classList.add('text-gray-900', 'font-bold');
            }

            // Update item highlight & check icon
            document.querySelectorAll('.dropdown-kelas-item').forEach(el => {
                const check = el.querySelector('.item-check');
                if (el.getAttribute('data-value') === val) {
                    el.classList.add('bg-blue-50', 'text-[#1A467C]');
                    if (check) check.classList.remove('hidden');
                } else {
                    el.classList.remove('bg-blue-50', 'text-[#1A467C]');
                    if (check) check.classList.add('hidden');
                }
            });

            if (menu) menu.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
            if (trigger) trigger.classList.remove('border-[#CA2C2A]', 'bg-red-50');

            const checkKelas = document.getElementById('check-kelas');
            if (checkKelas) checkKelas.classList.remove('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const container = document.getElementById('custom-select-container');
            const menu = document.getElementById('dropdown-kelas-menu');
            const chevron = document.getElementById('dropdown-chevron-icon');
            if (container && menu && !container.contains(e.target)) {
                menu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        });

        // Anti-Double Submit & Client Validation Handler
        let isSubmitting = false;

        function handleSubmit(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }

            // Validate custom dropdown
            const inputKelas = document.getElementById('input-kelas-value');
            const triggerKelas = document.getElementById('dropdown-kelas-trigger');
            if (!inputKelas || !inputKelas.value.trim()) {
                e.preventDefault();
                if (triggerKelas) {
                    triggerKelas.classList.add('border-[#CA2C2A]', 'bg-red-50');
                    toggleKelasDropdown();
                    triggerKelas.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }

            const btn = document.getElementById('btn-submit');
            const btnText = document.getElementById('btn-text');

            isSubmitting = true;
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');

            btnText.innerHTML = `
                <svg class="animate-spin h-4 w-4 text-white inline-block mr-1" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>SEDANG MENGIRIM...</span>
            `;

            return true;
        }

        // Close Modal Handler with smooth exit transition
        function tutupModal() {
            const modal = document.getElementById('modal-sukses');
            const card = document.getElementById('modal-card');
            if (modal && card) {
                card.classList.remove('modal-spring-in');
                modal.classList.remove('backdrop-fade-in');
                card.classList.add('modal-spring-out');
                modal.classList.add('backdrop-fade-out');
                setTimeout(() => {
                    modal.remove();
                }, 220);
            }
        }

        // Close on Escape Key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                tutupModal();
                const menu = document.getElementById('dropdown-kelas-menu');
                const chevron = document.getElementById('dropdown-chevron-icon');
                if (menu) menu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        });

        // 1. Polaroid Wobble Click Animation
        function wobblePolaroid(el) {
            if (!el) return;
            el.classList.remove('wobble-active');
            void el.offsetWidth;
            el.classList.add('wobble-active');
            el.addEventListener('animationend', () => {
                el.classList.remove('wobble-active');
            }, { once: true });
        }

        // 2. Interactive Floating Background Shapes (Pop & Spin)
        function popShape(el) {
            if (!el) return;
            el.classList.remove('shape-popping');
            void el.offsetWidth;
            el.classList.add('shape-popping');
            el.addEventListener('animationend', () => {
                el.classList.remove('shape-popping');
            }, { once: true });
        }

        // 3. Tactile 3D Tilt Card Parallax (Smooth Neubrutalism Angle)
        function initTiltCards() {
            const cards = document.querySelectorAll('.tilt-card');
            cards.forEach(card => {
                card.addEventListener('mousemove', (e) => {
                    if (window.innerWidth < 768) return;
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    const rotateX = ((y - centerY) / centerY) * -2.5;
                    const rotateY = ((x - centerX) / centerX) * 2.5;
                    card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) translateY(-2px)`;
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = '';
                });
            });
        }

        // 4. Live Form Input Validation Indicators
        function validateLive(type) {
            if (type === 'nama') {
                const val = document.getElementById('nama')?.value.trim() || '';
                const check = document.getElementById('check-nama');
                if (check) {
                    if (val.length >= 3) {
                        check.classList.remove('hidden');
                    } else {
                        check.classList.add('hidden');
                    }
                }
            } else if (type === 'nim') {
                const val = document.getElementById('nim')?.value.trim() || '';
                const check = document.getElementById('check-nim');
                if (check) {
                    if (/^\d{6,20}$/.test(val)) {
                        check.classList.remove('hidden');
                    } else {
                        check.classList.add('hidden');
                    }
                }
            } else if (type === 'no_telp') {
                const val = document.getElementById('no_telp')?.value.trim() || '';
                const check = document.getElementById('check-no_telp');
                if (check) {
                    if (/^(08|62|\+62)\d{8,13}$/.test(val)) {
                        check.classList.remove('hidden');
                    } else {
                        check.classList.add('hidden');
                    }
                }
            }
        }

        // Initialize On Load
        document.addEventListener('DOMContentLoaded', () => {
            initTiltCards();
            validateLive('nama');
            validateLive('nim');
            validateLive('no_telp');

            const kelasVal = document.getElementById('input-kelas-value')?.value.trim();
            if (kelasVal) {
                const checkKelas = document.getElementById('check-kelas');
                if (checkKelas) checkKelas.classList.remove('hidden');
            }
        });
    </script>

</body>
</html>
