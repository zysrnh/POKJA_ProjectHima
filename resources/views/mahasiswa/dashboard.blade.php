<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Mahasiswa - POKJA HIMA IF</title>

    <!-- Favicon & Web Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

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

    <!-- HTML5-QRCode Library for In-Browser Camera Scanner -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E8F2FA;
            background-image: radial-gradient(#1A467C 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }

        /* Tactile Neubrutalism Button & Card Transitions */
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

        .card-tactile {
            transition: transform 0.15s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.15s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .card-tactile:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px 0px #000000 !important;
        }

        .nav-item-active {
            background-color: #1A467C !important;
            color: #FFFFFF !important;
            box-shadow: 3px 3px 0px 0px #000000 !important;
        }

        .bottom-nav-active {
            color: #1A467C !important;
            font-weight: 800 !important;
            transform: scale(1.05);
        }
        .bottom-nav-active .bottom-nav-icon {
            background-color: #1A467C !important;
            color: #FFFFFF !important;
            box-shadow: 2px 2px 0px 0px #000000 !important;
        }

        /* Spring Slam Modal Animation (Without Opacity Transitions) */
        @keyframes modalSpringSlam {
            0% { transform: translateY(-30px) scale(0.92); }
            70% { transform: translateY(4px) scale(1.02); }
            100% { transform: translateY(0) scale(1); }
        }
        .modal-spring-slam {
            animation: modalSpringSlam 0.28s cubic-bezier(0.18, 0.89, 0.32, 1.28) forwards;
        }

        @keyframes iconBouncePop {
            0% { transform: scale(0.3) rotate(-12deg); }
            60% { transform: scale(1.18) rotate(4deg); }
            80% { transform: scale(0.95) rotate(-2deg); }
            100% { transform: scale(1) rotate(0deg); }
        }
        .icon-bounce-pop {
            animation: iconBouncePop 0.45s cubic-bezier(0.18, 0.89, 0.32, 1.28) forwards;
        }

        /* Tactile Tab Snap Pop Animation (No Opacity) */
        @keyframes tabSnapIn {
            0% { transform: translateY(6px) scale(0.99); }
            100% { transform: translateY(0) scale(1); }
        }
        .tab-snap-in {
            animation: tabSnapIn 0.2s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }
    </style>
</head>
<body class="min-h-screen text-[#000000] flex flex-col md:flex-row antialiased selection:bg-[#2A82C6] selection:text-white overflow-x-hidden">

    <!-- ============================================================== -->
    <!-- DESKTOP SIDEBAR (Visible on md: and up)                         -->
    <!-- ============================================================== -->
    <aside class="hidden md:flex md:w-64 lg:w-72 bg-white border-r-[3px] border-black flex-col justify-between shrink-0 h-screen sticky top-0 z-30 shadow-[4px_0px_0px_0px_#000000]">
        
        <div class="p-5 space-y-6">
            <!-- Brand & Logo -->
            <div class="flex items-center gap-3 border-b-2 border-black pb-4">
                <img 
                    src="{{ asset('logo/logo.png') }}" 
                    alt="Logo HIMA IF" 
                    class="w-10 h-10 object-contain bg-white p-1 border-2 border-black shadow-[2px_2px_0px_0px_#000000]"
                >
                <div>
                    <span class="font-black text-xs uppercase tracking-wider text-[#1A467C] block leading-tight">POKJA HIMA IF</span>
                    <span class="text-[10px] text-gray-600 font-bold uppercase tracking-wider block mt-0.5">Portal Mahasiswa</span>
                </div>
            </div>

            <!-- Student Profile Badge (Tanpa Label Mahasiswa) -->
            <div class="bg-gray-50 border-2 border-black p-3.5 shadow-[3px_3px_0px_0px_#000000] space-y-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center font-black text-xs shadow-[1px_1px_0px_0px_#000000] shrink-0 overflow-hidden">
                        @if (!empty($mahasiswa->foto) && file_exists(public_path('uploads/avatars/' . $mahasiswa->foto)))
                            <img src="{{ route('avatar.serve', ['filename' => basename($mahasiswa->foto)]) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($mahasiswa->nama, 0, 2)) }}
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs font-black text-gray-950 truncate uppercase leading-tight" title="{{ $mahasiswa->nama }}">
                            {{ $mahasiswa->nama }}
                        </h4>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-200 flex items-center justify-between font-mono text-[11px] font-bold text-gray-700">
                    <span>NIM: <strong class="text-gray-950">{{ $mahasiswa->nim }}</strong></span>
                    <div class="flex items-center gap-1">
                        <span class="bg-blue-50 border border-black text-[#1A467C] px-1.5 py-0.2 font-black text-[10px]">{{ $mahasiswa->kelas }}</span>
                        <span class="bg-green-100 border border-black text-[#15803d] px-1.5 py-0.2 font-black text-[10px]">★ {{ (int) $mahasiswa->total_point }} PTS</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar Navigation Links -->
            <nav class="space-y-2">
                <button 
                    type="button" 
                    id="side-nav-beranda"
                    onclick="switchTab('beranda')" 
                    class="nav-item-active btn-tactile w-full text-left px-3.5 py-2.5 border-2 border-black font-black text-xs uppercase tracking-wider flex items-center gap-2.5 cursor-pointer"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Ringkasan Presensi</span>
                </button>

                <button 
                    type="button" 
                    id="side-nav-scan"
                    onclick="switchTab('scan')" 
                    class="btn-tactile w-full text-left px-3.5 py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black font-black text-xs uppercase tracking-wider flex items-center gap-2.5 shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    <svg class="w-4 h-4 text-[#15803d] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="square" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Scan Presensi</span>
                </button>

                <button 
                    type="button" 
                    id="side-nav-poin"
                    onclick="switchTab('poin')" 
                    class="btn-tactile w-full text-left px-3.5 py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black font-black text-xs uppercase tracking-wider flex items-center justify-between gap-2 shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    <span class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <span>PointKu</span>
                    </span>
                    <span class="bg-amber-300 text-black border border-black px-2 py-0.5 font-mono font-black text-[10px] leading-none whitespace-nowrap shrink-0">
                        {{ (int) $mahasiswa->total_point }} PTS
                    </span>
                </button>

                <button 
                    type="button" 
                    id="side-nav-leaderboard"
                    onclick="switchTab('leaderboard')" 
                    class="btn-tactile w-full text-left px-3.5 py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black font-black text-xs uppercase tracking-wider flex items-center gap-2.5 shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    <svg class="w-4 h-4 text-[#2A82C6] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Leaderboard</span>
                </button>

                <button 
                    type="button" 
                    id="side-nav-profil"
                    onclick="switchTab('profil')" 
                    class="btn-tactile w-full text-left px-3.5 py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black font-black text-xs uppercase tracking-wider flex items-center gap-2.5 shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    <svg class="w-4 h-4 text-[#1A467C] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profil Saya</span>
                </button>
            </nav>
        </div>

        <!-- Sidebar Bottom Logout with Confirmation Modal Trigger -->
        <div class="p-5 border-t-2 border-black space-y-3">
            @if (!empty($pengaturan->link_grup_wa))
                <a 
                    href="{{ $pengaturan->link_grup_wa }}" 
                    target="_blank" 
                    class="btn-tactile w-full inline-flex items-center justify-center gap-2 py-2 bg-[#25D366] text-black border-2 border-black font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000]"
                >
                    <span>Grup WhatsApp</span>
                </a>
            @endif

            <button 
                type="button" 
                onclick="openLogoutModal()"
                class="btn-tactile w-full py-2 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border-2 border-black font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] flex items-center justify-center gap-1.5 cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Keluar Akun</span>
            </button>
        </div>

    </aside>

    <!-- ============================================================== -->
    <!-- MAIN CONTENT VIEWPORT                                          -->
    <!-- ============================================================== -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- Mobile Top Bar (Clean Brand Bar, Badge Sesi 1 Di-remove sesuai revisi) -->
        <header class="md:hidden bg-white border-b-2 border-black sticky top-0 z-30 p-3 flex items-center justify-between shadow-[0_2px_0px_0px_#000000]">
            <div class="flex items-center gap-2">
                <img 
                    src="{{ asset('logo/logo.png') }}" 
                    alt="Logo HIMA IF" 
                    class="w-7 h-7 object-contain bg-white p-0.5 border border-black shadow-[1px_1px_0px_0px_#000000]"
                >
                <div>
                    <h1 class="text-xs font-black uppercase text-[#1A467C] leading-none">POKJA HIMA IF</h1>
                    <span class="text-[9px] font-bold text-gray-500 uppercase mt-0.5 block">Portal Mahasiswa</span>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 p-3.5 sm:p-6 lg:p-8 max-w-5xl w-full mx-auto pb-24 md:pb-8 space-y-4 sm:space-y-6">

            <!-- Flash Alert Success -->
            @if (session('success'))
                <div class="p-3 bg-green-50 border-2 border-black text-xs font-bold text-green-900 shadow-[3px_3px_0px_0px_#15803d]">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Flash Alert Error -->
            @if ($errors->any())
                <div class="p-3 bg-red-50 border-2 border-black text-xs font-bold text-red-900 shadow-[3px_3px_0px_0px_#CA2C2A]">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ========================================================== -->
            <!-- TAB 1: BERANDA RINGKASAN                                   -->
            <!-- ========================================================== -->
            <section id="tab-panel-beranda" class="tab-snap-in space-y-4 sm:space-y-6">

                <!-- Greeting Banner with Avatar (Tanpa Label Mahasiswa) -->
                <div class="card-tactile bg-white border-2 border-black p-4 sm:p-6 shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000]">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <!-- Avatar Display -->
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center font-black text-base sm:text-lg shadow-[2px_2px_0px_0px_#000000] shrink-0 overflow-hidden">
                                @if (!empty($mahasiswa->foto) && file_exists(public_path('uploads/avatars/' . $mahasiswa->foto)))
                                    <img src="{{ route('avatar.serve', ['filename' => basename($mahasiswa->foto)]) }}" alt="Foto {{ $mahasiswa->nama }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($mahasiswa->nama, 0, 2)) }}
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <h2 class="text-sm sm:text-xl font-black text-gray-950 uppercase tracking-tight truncate">
                                    {{ $mahasiswa->nama }}
                                </h2>
                                <p class="text-[11px] sm:text-xs text-gray-600 font-semibold font-mono mt-0.5">
                                    NIM: <span class="font-bold text-gray-950">{{ $mahasiswa->nim }}</span> • Kelas: <span class="bg-gray-100 border border-gray-400 px-1 py-0.2 text-[#1A467C] font-bold">{{ $mahasiswa->kelas }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Scan Button: Clean Camera Icon on Mobile / Compact with Label on Desktop -->
                        <button 
                            type="button" 
                            onclick="switchTab('scan')" 
                            title="Buka Scanner Presensi"
                            class="btn-tactile p-2.5 sm:px-4 sm:py-2.5 bg-[#15803d] hover:bg-[#166534] text-white border-2 border-black shadow-[3px_3px_0px_0px_#000000] flex items-center justify-center gap-1.5 cursor-pointer shrink-0"
                        >
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="square" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="hidden sm:inline text-xs font-black uppercase">Scan</span>
                        </button>
                    </div>
                </div>

                <!-- 4 Stat Cards Ringkas (Presensi + PointKu) -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4">
                    <div class="card-tactile bg-white border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000]">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-[#1A467C] block truncate">Total Hadir</span>
                        <h3 class="text-xl sm:text-3xl font-black text-gray-950 font-mono mt-1 leading-none">{{ $totalHadir }}<span class="text-xs sm:text-lg text-gray-500 font-bold">/7</span></h3>
                    </div>

                    <div class="card-tactile bg-white border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000]">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-green-800 block truncate">Persentase</span>
                        <h3 class="text-xl sm:text-3xl font-black text-green-700 font-mono mt-1 leading-none">{{ $persenHadir }}%</h3>
                    </div>

                    <div onclick="switchTab('poin')" class="card-tactile bg-amber-50 hover:bg-amber-100 border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000] cursor-pointer">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-[#15803d] block truncate">Point Saya</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <h3 class="text-xl sm:text-3xl font-black text-[#15803d] font-mono leading-none">{{ (int) $mahasiswa->total_point }}</h3>
                            <span class="text-[10px] sm:text-xs font-black uppercase text-[#15803d]">Pts</span>
                        </div>
                    </div>

                    <div onclick="switchTab('leaderboard')" class="card-tactile bg-white hover:bg-blue-50 border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000] cursor-pointer">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-[#1A467C] block truncate">Peringkat</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <h3 class="text-xl sm:text-3xl font-black text-[#1A467C] font-mono leading-none">#{{ $myRank }}</h3>
                            <span class="text-[10px] sm:text-xs font-bold text-gray-500 font-mono">/{{ $totalPeserta }}</span>
                        </div>
                    </div>
                </div>

                <!-- Banner Cepat Menuju PointKu & Leaderboard -->
                <div class="card-tactile bg-white border-2 border-black p-4 sm:p-5 shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-300 text-black border-2 border-black flex items-center justify-center font-black text-base shadow-[2px_2px_0px_0px_#000000] shrink-0">
                            ★
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="bg-[#1A467C] text-white text-[9px] font-black uppercase px-1.5 py-0.5 border border-black">POINTKU</span>
                                <h3 class="text-xs sm:text-sm font-black uppercase text-gray-950">Point Keaktifan & Peringkat</h3>
                            </div>
                            <p class="text-[11px] sm:text-xs text-gray-600 font-semibold mt-0.5">
                                Kamu punya <strong class="text-[#15803d] font-mono">{{ (int) $mahasiswa->total_point }} Pts</strong> (Peringkat <strong class="text-[#1A467C] font-mono">#{{ $myRank }}</strong>).
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button 
                            type="button" 
                            onclick="switchTab('poin')" 
                            class="btn-tactile px-3.5 py-2 bg-white hover:bg-gray-100 text-gray-900 text-xs font-black uppercase tracking-wider border-2 border-black shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                        >
                            PointKu
                        </button>
                        <button 
                            type="button" 
                            onclick="switchTab('leaderboard')" 
                            class="btn-tactile px-3.5 py-2 bg-[#1A467C] hover:bg-[#2A82C6] text-white text-xs font-black uppercase tracking-wider border-2 border-black shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                        >
                            Leaderboard →
                        </button>
                    </div>
                </div>

            </section>

            <!-- ========================================================== -->
            <!-- TAB 2: SCAN PRESENSI KAMERA (Terpadu, Simple & Responsive) -->
            <!-- ========================================================== -->
            <section id="tab-panel-scan" class="tab-snap-in hidden space-y-4 sm:space-y-6">

                <div class="card-tactile bg-white border-2 border-black p-4 sm:p-6 shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] space-y-4">
                    <div class="border-b-2 border-black pb-3 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm sm:text-base font-black uppercase text-[#1A467C]">
                                Scanner Presensi
                            </h3>
                            <p class="text-[11px] sm:text-xs text-gray-600 font-semibold mt-0.5">
                                Arahkan kamera ke QR Code di layar proyektor kelas.
                            </p>
                        </div>
                        <span class="text-xs font-black bg-[#1A467C] text-white border border-black px-2.5 py-1">
                            Sesi {{ $pengaturan->pertemuan_aktif ?? 1 }}
                        </span>
                    </div>

                    <!-- Locked Student Identity Card -->
                    <div class="bg-blue-50 border-2 border-black p-3 space-y-0.5">
                        <span class="text-[9px] font-black uppercase text-gray-500 tracking-wider">Identitas Mahasiswa</span>
                        <h4 class="text-sm font-black uppercase text-gray-950">{{ $mahasiswa->nama }}</h4>
                        <p class="text-xs font-mono font-bold text-gray-700">
                            NIM: <span class="text-[#1A467C] font-black">{{ $mahasiswa->nim }}</span> • Kelas: <span class="font-black">{{ $mahasiswa->kelas }}</span>
                        </p>
                        <input type="hidden" id="dash-input-nim" value="{{ $mahasiswa->nim }}">
                    </div>

                    <!-- Scanner Camera Trigger Box -->
                    <div id="dash-camera-trigger" class="space-y-2">
                        <button 
                            type="button" 
                            onclick="startDashScanner()" 
                            class="btn-tactile w-full py-4 bg-[#2A82C6] hover:bg-[#1A467C] text-white font-black text-xs sm:text-sm uppercase tracking-wider border-2 border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="square" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Buka Kamera & Scan QR</span>
                        </button>
                    </div>

                    <!-- Live Scanner Camera Viewport Container -->
                    <div id="dash-camera-box" class="hidden space-y-3">
                        <div class="relative bg-black border-2 border-black overflow-hidden shadow-[4px_4px_0px_0px_#000000]">
                            <div id="dash-qr-reader" class="w-full"></div>
                        </div>

                        <button 
                            type="button" 
                            onclick="stopDashScanner()" 
                            class="btn-tactile w-full py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-900 border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                        >
                            Tutup Kamera
                        </button>
                    </div>

                    <p class="text-center text-[11px] font-semibold text-gray-500 pt-1">
                        QR di proyektor kelas diperbarui otomatis secara dinamis tiap 5 detik.
                    </p>
                </div>

            </section>

            <!-- ========================================================== -->
            <!-- TAB 3: POINTKU (STATUS & RIWAYAT POINT SAYA)               -->
            <!-- ========================================================== -->
            <section id="tab-panel-poin" class="tab-snap-in hidden space-y-5 sm:space-y-6">

                @php
                    $totalTambahCount = $mahasiswa->pointHistories->where('tipe', 'tambah')->count();
                    $totalKurangCount = $mahasiswa->pointHistories->where('tipe', 'kurang')->count();
                    $maxPointAll = max(1, (int) ($leaderboardList->max('total_point') ?? 1));
                @endphp

                <!-- 1. Kartu Ringkasan Point & Peringkat Saya -->
                <div class="card-tactile bg-white border-2 border-black p-4 sm:p-6 shadow-[4px_4px_0px_0px_#000000] space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b-2 border-black pb-3.5">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="bg-amber-400 text-black text-[10px] font-black uppercase px-2 py-0.5 border border-black">POINTKU</span>
                                <h3 class="text-sm sm:text-lg font-black uppercase text-[#1A467C]">
                                    Point Keaktifan Saya
                                </h3>
                            </div>
                            <p class="text-[11px] sm:text-xs text-gray-600 font-semibold mt-0.5">
                                Rincian total perolehan point keaktifan dan riwayat aktivitasmu.
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <div class="bg-amber-300 border-2 border-black px-3 py-1.5 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1">
                                <span class="text-[10px] font-black uppercase text-black">RANK</span>
                                <span class="font-mono font-black text-sm sm:text-base text-black">#{{ $myRank }}</span>
                            </div>
                            <div class="bg-green-100 border-2 border-black px-3 py-1.5 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1">
                                <span class="font-mono font-black text-sm sm:text-base text-[#15803d]">{{ (int) $mahasiswa->total_point }}</span>
                                <span class="text-[10px] font-black uppercase text-[#15803d]">PTS</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2.5 sm:gap-4 text-center">
                        <div class="bg-green-50 border-2 border-black p-2.5 sm:p-3">
                            <span class="text-[9px] sm:text-[10px] font-black uppercase text-[#15803d] block">Point Masuk (+)</span>
                            <span class="font-mono font-black text-base sm:text-xl text-[#15803d] mt-0.5 block">{{ $totalTambahCount }}x</span>
                        </div>
                        <div class="bg-red-50 border-2 border-black p-2.5 sm:p-3">
                            <span class="text-[9px] sm:text-[10px] font-black uppercase text-[#CA2C2A] block">Evaluasi (-)</span>
                            <span class="font-mono font-black text-base sm:text-xl text-[#CA2C2A] mt-0.5 block">{{ $totalKurangCount }}x</span>
                        </div>
                        <div class="bg-blue-50 border-2 border-black p-2.5 sm:p-3">
                            <span class="text-[9px] sm:text-[10px] font-black uppercase text-[#1A467C] block">Total Aktivitas</span>
                            <span class="font-mono font-black text-base sm:text-xl text-[#1A467C] mt-0.5 block">{{ $mahasiswa->pointHistories->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Daftar Lengkap Riwayat Point Saya -->
                <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] overflow-hidden">
                    <div class="px-4 sm:px-5 py-3.5 border-b-2 border-black bg-blue-50/70 flex items-center justify-between gap-2">
                        <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                            Riwayat Aktivitas Point Saya
                        </h4>
                        <button 
                            type="button" 
                            onclick="switchTab('leaderboard')" 
                            class="btn-tactile px-3 py-1 bg-[#1A467C] text-white border border-black font-black text-[10px] uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                        >
                            Cek Leaderboard →
                        </button>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @forelse ($mahasiswa->pointHistories as $hist)
                            @php $isAdd = $hist->tipe === 'tambah'; @endphp
                            <div class="p-4 hover:bg-blue-50/30 transition flex items-center justify-between gap-3">
                                <div class="flex items-start gap-3 min-w-0">
                                    <span class="w-9 h-9 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_#000000] {{ $isAdd ? 'bg-[#15803d] text-white' : 'bg-[#CA2C2A] text-white' }}">
                                        {{ $isAdd ? '+' : '-' }}{{ (int) $hist->jumlah }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="font-black text-xs sm:text-sm uppercase text-gray-950 leading-tight">{{ $hist->kategori }}</p>
                                        @if ($hist->keterangan)
                                            <p class="text-xs font-semibold text-gray-600 mt-0.5 break-words">{{ $hist->keterangan }}</p>
                                        @endif
                                        <p class="text-[10px] font-mono font-bold text-gray-400 mt-1">
                                            {{ $hist->created_at ? $hist->created_at->timezone('Asia/Jakarta')->format('d M Y • H:i') : '-' }} WIB
                                        </p>
                                    </div>
                                </div>

                                <span class="shrink-0 px-2.5 py-1 border-2 border-black font-mono font-black text-xs {{ $isAdd ? 'bg-green-100 text-[#15803d]' : 'bg-red-100 text-[#CA2C2A]' }}">
                                    {{ $isAdd ? '+' : '-' }}{{ (int) $hist->jumlah }} PTS
                                </span>
                            </div>
                        @empty
                            <div class="p-8 text-center text-xs font-bold text-gray-500">
                                Belum ada riwayat perubahan point untuk akunmu.
                            </div>
                        @endforelse
                    </div>
                </div>

            </section>

            <!-- ========================================================== -->
            <!-- TAB 4: LEADERBOARD PESERTA POKJA                           -->
            <!-- ========================================================== -->
            <section id="tab-panel-leaderboard" class="tab-snap-in hidden space-y-5 sm:space-y-6">

                <!-- Header Banner Leaderboard -->
                <div class="card-tactile bg-white border-2 border-black p-4 sm:p-5 shadow-[4px_4px_0px_0px_#000000] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="bg-amber-400 text-black text-[10px] font-black uppercase px-2 py-0.5 border border-black">LEADERBOARD</span>
                            <h3 class="text-sm sm:text-lg font-black uppercase text-[#1A467C]">
                                Klasemen Point Keaktifan
                            </h3>
                        </div>
                        <p class="text-[11px] sm:text-xs text-gray-600 font-semibold mt-0.5">
                            Posisi kamu saat ini: <strong class="text-[#1A467C] font-mono">Peringkat #{{ $myRank }}</strong> dari {{ $totalPeserta }} mahasiswa (<strong class="text-[#15803d] font-mono">{{ (int) $mahasiswa->total_point }} PTS</strong>).
                        </p>
                    </div>
                    <button 
                        type="button" 
                        onclick="switchTab('poin')" 
                        class="btn-tactile px-3.5 py-2 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] shrink-0 cursor-pointer"
                    >
                        Riwayat PointKu →
                    </button>
                </div>

                <!-- Podium Top 3 (Grafik Batang Podium Vertikal Ke Atas) -->
                @if ($topThree->count() > 0)
                    @php
                        $first = $topThree->get(0);
                        $second = $topThree->get(1);
                        $third = $topThree->get(2);
                        $podiumBars = [
                            [
                                'rank' => 2,
                                'barBg' => 'bg-sky-200',
                                'barHeight' => 'h-28 sm:h-36',
                                'mhs' => $second,
                            ],
                            [
                                'rank' => 1,
                                'barBg' => 'bg-amber-300',
                                'barHeight' => 'h-40 sm:h-48',
                                'mhs' => $first,
                            ],
                            [
                                'rank' => 3,
                                'barBg' => 'bg-orange-200',
                                'barHeight' => 'h-20 sm:h-28',
                                'mhs' => $third,
                            ],
                        ];
                    @endphp

                    <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-6">
                        <div class="flex items-center justify-between gap-2 border-b-2 border-black pb-3 mb-5">
                            <div class="flex items-center gap-2">
                                <span class="bg-amber-400 text-black text-[10px] font-black px-2 py-0.5 border border-black">TOP 3</span>
                                <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                                    Podium Peringkat Teratas
                                </h4>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2.5 sm:gap-5 items-end max-w-2xl mx-auto pt-2 px-1">
                            @foreach ($podiumBars as $pod)
                                @php $mhs = $pod['mhs']; @endphp
                                <div class="flex flex-col items-center">
                                    @if ($mhs)
                                        @php $isMe = (int) $mhs->id === (int) $mahasiswa->id; @endphp
                                        <!-- Info Mahasiswa di Atas Batang -->
                                        <div class="flex flex-col items-center text-center mb-2.5 w-full">
                                            <div class="relative">
                                                <div class="{{ $pod['rank'] === 1 ? 'w-11 h-11 sm:w-14 sm:h-14 text-sm sm:text-base' : 'w-9 h-9 sm:w-12 sm:h-12 text-xs sm:text-sm' }} bg-white text-[#1A467C] border-2 border-black flex items-center justify-center font-black overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                                                    @if ($mhs->avatarUrl())
                                                        <img src="{{ $mhs->avatarUrl() }}" alt="Avatar {{ $mhs->nama }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ $mhs->initials() }}
                                                    @endif
                                                </div>
                                                @if ($isMe)
                                                    <span class="absolute -top-2 -right-2 bg-[#15803d] text-white text-[8px] font-black uppercase px-1 py-0.2 border border-black">
                                                        KAMU
                                                    </span>
                                                @endif
                                            </div>

                                            <h5 class="font-black text-[10px] sm:text-xs text-gray-950 uppercase truncate max-w-full mt-1.5 px-0.5" title="{{ $mhs->nama }}">
                                                {{ $mhs->nama }}
                                            </h5>
                                            <span class="font-mono text-[9px] sm:text-[10px] font-bold text-[#1A467C]">
                                                {{ $mhs->kelas }}
                                            </span>

                                            <div class="mt-1.5 bg-white border-2 border-black px-2 py-0.5 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1">
                                                <span class="font-mono font-black text-xs sm:text-sm text-gray-950 leading-none">{{ (int) $mhs->total_point }}</span>
                                                <span class="text-[8px] sm:text-[9px] font-black uppercase text-[#15803d] leading-none">PTS</span>
                                            </div>
                                        </div>

                                        <!-- Batang Vertikal Podium -->
                                        <div class="w-full {{ $pod['barHeight'] }} {{ $pod['barBg'] }} border-t-2 border-x-2 border-black shadow-[3px_0px_0px_0px_#000000] flex flex-col items-center justify-center p-2 relative">
                                            <span class="font-mono font-black text-xl sm:text-3xl text-black/85 leading-none">
                                                #{{ $pod['rank'] }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="h-1.5 bg-black w-full max-w-2xl mx-auto"></div>
                    </div>
                @endif

                <!-- Klasemen Lengkap Peserta -->
                <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] overflow-hidden">
                    <div class="px-4 sm:px-5 py-3 border-b-2 border-black bg-blue-50/70 flex items-center justify-between">
                        <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                            Peringkat Lengkap Peserta
                        </h4>
                        <span class="bg-white border border-black px-2 py-0.5 font-mono font-black text-[10px] text-[#1A467C]">
                            {{ $totalPeserta }} Mahasiswa
                        </span>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @foreach ($leaderboardList as $idx => $item)
                            @php
                                $rankNum = $idx + 1;
                                $pts = (int) $item->total_point;
                                $pctWidth = $pts > 0 ? max(8, round(($pts / $maxPointAll) * 100)) : 0;
                                $isCurrentUser = (int) $item->id === (int) $mahasiswa->id;
                                $barColor = $rankNum === 1
                                    ? 'bg-amber-400'
                                    : ($rankNum === 2
                                        ? 'bg-[#2A82C6]'
                                        : ($rankNum === 3 ? 'bg-orange-400' : 'bg-[#15803d]'));
                            @endphp
                            <div class="px-4 sm:px-5 py-3.5 {{ $isCurrentUser ? 'bg-amber-50/90 border-l-4 border-l-[#1A467C]' : 'hover:bg-blue-50/40' }} transition flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <span class="w-8 h-8 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 {{ $rankNum === 1 ? 'bg-amber-300 text-black' : ($rankNum === 2 ? 'bg-sky-200 text-black' : ($rankNum === 3 ? 'bg-orange-200 text-black' : 'bg-gray-100 text-gray-800')) }}">
                                        #{{ $rankNum }}
                                    </span>

                                    <div class="w-9 h-9 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden">
                                        @if ($item->avatarUrl())
                                            <img src="{{ $item->avatarUrl() }}" alt="Avatar {{ $item->nama }}" class="w-full h-full object-cover">
                                        @else
                                            {{ $item->initials() }}
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1.5">
                                            <p class="font-black text-xs sm:text-sm text-gray-950 truncate">{{ $item->nama }}</p>
                                            @if ($isCurrentUser)
                                                <span class="bg-[#1A467C] text-white text-[9px] font-black uppercase px-1.5 py-0.2 border border-black shrink-0">
                                                    KAMU
                                                </span>
                                            @endif
                                        </div>
                                        <p class="font-mono text-[10px] sm:text-[11px] font-bold text-[#1A467C]">{{ $item->kelas }}</p>
                                        <div class="mt-1.5 w-full max-w-md h-2.5 bg-gray-100 border border-black overflow-hidden">
                                            <div class="h-full {{ $barColor }}" style="width: {{ $pctWidth }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-green-100 border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1 shrink-0">
                                    <span class="font-mono font-black text-xs sm:text-sm text-[#15803d] leading-none">{{ $pts }}</span>
                                    <span class="text-[9px] sm:text-[10px] font-black uppercase text-[#15803d] leading-none">PTS</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </section>

            <!-- ========================================================== -->
            <!-- TAB 3: PROFIL & PENGATURAN AKUN MAHASISWA                  -->
            <!-- ========================================================== -->
            <section id="tab-panel-profil" class="tab-snap-in hidden space-y-4 sm:space-y-6">

                <div class="card-tactile bg-white border-2 border-black p-4 sm:p-6 shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] space-y-5">
                    
                    <div class="border-b-2 border-black pb-3 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm sm:text-base font-black uppercase text-[#1A467C]">
                                Profil Mahasiswa
                            </h3>
                            <p class="text-[11px] sm:text-xs text-gray-600 font-semibold mt-0.5">
                                Kelola foto profil dan perbarui alamat email Anda.
                            </p>
                        </div>
                        <span class="text-[10px] font-mono font-black bg-blue-50 border border-black text-[#1A467C] px-2 py-1 uppercase shadow-[1px_1px_0px_0px_#000000]">
                            AKUN AKTIF
                        </span>
                    </div>

                    <!-- Form Update Profil -->
                    <form action="{{ route('mahasiswa.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <!-- Foto Profil Section -->
                        <div class="bg-gray-50 border-2 border-black p-4 space-y-3">
                            <span class="block text-xs font-black uppercase tracking-wider text-[#1A467C]">
                                Foto Profil
                            </span>
                            
                            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
                                <!-- Avatar Preview Box -->
                                <div class="relative shrink-0">
                                    <div class="w-24 h-24 bg-[#2A82C6] text-white border-2 border-black shadow-[3px_3px_0px_0px_#000000] flex items-center justify-center font-black text-2xl overflow-hidden">
                                        @if (!empty($mahasiswa->foto) && file_exists(public_path('uploads/avatars/' . $mahasiswa->foto)))
                                            <img id="avatar-preview" src="{{ route('avatar.serve', ['filename' => basename($mahasiswa->foto)]) }}" alt="Foto {{ $mahasiswa->nama }}" class="w-full h-full object-cover">
                                        @else
                                            <img id="avatar-preview" class="hidden w-full h-full object-cover" alt="Preview Foto">
                                            <span id="avatar-placeholder">{{ strtoupper(substr($mahasiswa->nama, 0, 2)) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Upload Input & Helper -->
                                <div class="space-y-2 flex-1 text-center sm:text-left">
                                    <input 
                                        type="file" 
                                        id="foto-input" 
                                        name="foto" 
                                        accept="image/png,image/jpeg,image/jpg,image/webp" 
                                        onchange="previewAvatar(this)" 
                                        class="hidden"
                                    >
                                    <button 
                                        type="button" 
                                        onclick="document.getElementById('foto-input').click()" 
                                        class="btn-tactile inline-flex items-center gap-2 py-2 px-3.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                                    >
                                        <svg class="w-4 h-4 text-[#1A467C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="square" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Pilih Foto Baru</span>
                                    </button>
                                    <p class="text-[11px] font-semibold text-gray-500">
                                        Mendukung format JPG, PNG, atau WEBP. Maksimal ukuran 2 MB.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Setting Email -->
                        <div>
                            <label for="email" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                                Alamat Email <span class="text-[#CA2C2A]">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $mahasiswa->email) }}" 
                                required
                                placeholder="email@kamu.com" 
                                class="w-full bg-[#F8FBFE] border-2 border-black text-gray-950 font-bold text-xs sm:text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:translate-x-[-1px] focus:translate-y-[-1px] focus:shadow-[4px_4px_0px_0px_#000000] transition"
                            >
                            <p class="text-[11px] font-semibold text-gray-500 mt-1">
                                Digunakan untuk login portal serta menerima pemberitahuan acara.
                            </p>
                        </div>

                        <!-- Data Diri Mahasiswa (Read-Only) -->
                        <div class="border-t-2 border-black pt-3.5 space-y-2.5">
                            <span class="text-[11px] font-black uppercase tracking-wider text-gray-500 block">
                                Informasi Mahasiswa (Terkunci)
                            </span>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                                <div class="bg-gray-100 border border-black p-2.5">
                                    <span class="text-[10px] font-bold text-gray-500 uppercase block">Nama Lengkap</span>
                                    <span class="font-black text-gray-950 uppercase">{{ $mahasiswa->nama }}</span>
                                </div>

                                <div class="bg-gray-100 border border-black p-2.5">
                                    <span class="text-[10px] font-bold text-gray-500 uppercase block">NIM Kampus</span>
                                    <span class="font-mono font-black text-[#1A467C]">{{ $mahasiswa->nim }}</span>
                                </div>

                                <div class="bg-gray-100 border border-black p-2.5">
                                    <span class="text-[10px] font-bold text-gray-500 uppercase block">Kelas</span>
                                    <span class="font-black text-gray-950">{{ $mahasiswa->kelas }}</span>
                                </div>

                                <div class="bg-gray-100 border border-black p-2.5">
                                    <span class="text-[10px] font-bold text-gray-500 uppercase block">No. WhatsApp</span>
                                    <span class="font-mono font-bold text-gray-950">{{ $mahasiswa->no_telp }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Simpan -->
                        <button 
                            type="submit" 
                            class="btn-tactile w-full py-3 bg-[#1A467C] hover:bg-[#153660] text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center gap-2 cursor-pointer mt-2"
                        >
                            <span>Simpan Perubahan Profil</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>

                    </form>

                </div>

            </section>

        </main>

    </div>

    <!-- ============================================================== -->
    <!-- MOBILE BOTTOM NAVIGATION TOOLBAR (Khas Mobile Apps)            -->
    <!-- ============================================================== -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t-[3px] border-black flex justify-around items-center py-2 px-1 shadow-[0_-3px_0px_0px_#000000]">
        
        <!-- Tab 1: Beranda -->
        <button 
            type="button" 
            id="bot-nav-beranda"
            onclick="switchTab('beranda')" 
            class="bottom-nav-active btn-tactile flex flex-col items-center gap-1 py-1 px-3 text-gray-600 cursor-pointer"
        >
            <div class="bottom-nav-icon w-8 h-8 rounded-none border border-black flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider">Beranda</span>
        </button>

        <!-- Tab 2: Scan Presensi -->
        <button 
            type="button" 
            id="bot-nav-scan"
            onclick="switchTab('scan')" 
            class="btn-tactile flex flex-col items-center gap-1 py-1 px-2.5 text-gray-600 cursor-pointer"
        >
            <div class="bottom-nav-icon w-8 h-8 rounded-none border border-black flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="square" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider">Scan</span>
        </button>

        <!-- Tab 3: PointKu -->
        <button 
            type="button" 
            id="bot-nav-poin"
            onclick="switchTab('poin')" 
            class="btn-tactile flex flex-col items-center gap-1 py-1 px-2 text-gray-600 cursor-pointer"
        >
            <div class="bottom-nav-icon w-8 h-8 rounded-none border border-black flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
            <span class="text-[9px] font-black uppercase tracking-wider">PointKu</span>
        </button>

        <!-- Tab 4: Leaderboard -->
        <button 
            type="button" 
            id="bot-nav-leaderboard"
            onclick="switchTab('leaderboard')" 
            class="btn-tactile flex flex-col items-center gap-1 py-1 px-2 text-gray-600 cursor-pointer"
        >
            <div class="bottom-nav-icon w-8 h-8 rounded-none border border-black flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <span class="text-[9px] font-black uppercase tracking-wider">Rank</span>
        </button>

        <!-- Tab 5: Profil -->
        <button 
            type="button" 
            id="bot-nav-profil"
            onclick="switchTab('profil')" 
            class="btn-tactile flex flex-col items-center gap-1 py-1 px-2 text-gray-600 cursor-pointer"
        >
            <div class="bottom-nav-icon w-8 h-8 rounded-none border border-black flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider">Profil</span>
        </button>

        <!-- Tab 4: Keluar dengan Konfirmasi -->
        <button 
            type="button" 
            onclick="openLogoutModal()"
            class="btn-tactile flex flex-col items-center gap-1 py-1 px-3 text-[#CA2C2A] cursor-pointer"
        >
            <div class="w-8 h-8 rounded-none border border-black flex items-center justify-center bg-red-50 text-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider">Keluar</span>
        </button>

    </nav>

    <!-- ============================================================== -->
    <!-- RESULT MODAL POP-UP (Tactile Spring Feedback + Receipt Box)    -->
    <!-- ============================================================== -->
    <div id="dash-result-modal" class="hidden fixed inset-0 z-50 bg-black/60 p-4 flex items-center justify-center">
        <div class="modal-spring-slam w-full max-w-sm bg-white border-[3px] border-black p-5 sm:p-6 shadow-[8px_8px_0px_0px_#000000] space-y-4 text-center">
            
            <div class="relative w-16 h-16 mx-auto flex items-center justify-center">
                <div id="dash-modal-pulse" class="hidden absolute inset-0 bg-green-400 border-2 border-black animate-ping"></div>
                <div id="dash-modal-icon-wrapper" class="relative w-16 h-16 border-2 border-black flex items-center justify-center font-black text-3xl shadow-[3px_3px_0px_0px_#000000]"></div>
            </div>

            <div>
                <span id="dash-modal-badge" class="inline-block text-[10px] font-black uppercase px-2.5 py-0.5 border border-black shadow-[1px_1px_0px_0px_#000000] mb-1"></span>
                <h3 id="dash-modal-title" class="text-base sm:text-lg font-black uppercase text-gray-950"></h3>
                <p id="dash-modal-desc" class="text-xs font-semibold text-gray-700 mt-1"></p>
            </div>

            <!-- Kartu Bukti Presensi Mahasiswa -->
            <div id="dash-modal-student-box" class="hidden bg-blue-50 border-2 border-black p-3 text-left space-y-1.5 text-xs shadow-[2px_2px_0px_0px_#000000]">
                <div class="flex justify-between gap-2">
                    <span class="font-bold text-gray-600">NAMA:</span>
                    <span id="dash-detail-nama" class="font-black text-gray-950 uppercase truncate"></span>
                </div>
                <div class="flex justify-between gap-2">
                    <span class="font-bold text-gray-600">NIM / KELAS:</span>
                    <span id="dash-detail-nim-kelas" class="font-black text-[#1A467C] font-mono"></span>
                </div>
                <div class="flex justify-between gap-2">
                    <span class="font-bold text-gray-600">WAKTU SCAN:</span>
                    <span id="dash-detail-waktu" class="font-black text-green-800 font-mono"></span>
                </div>
            </div>

            <button 
                type="button" 
                onclick="closeDashResultModal()"
                class="btn-tactile w-full py-2.5 bg-[#2A82C6] hover:bg-[#1A467C] text-white text-xs font-black uppercase tracking-wider border-2 border-black shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
            >
                Selesai & Muat Ulang
            </button>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- LOGOUT CONFIRMATION MODAL POP-UP (Neubrutalism)                -->
    <!-- ============================================================== -->
    <div id="logout-confirm-modal" class="hidden fixed inset-0 z-50 bg-black/60 p-4 flex items-center justify-center">
        <div class="modal-spring-slam w-full max-w-sm bg-white border-[3px] border-black p-5 sm:p-6 shadow-[8px_8px_0px_0px_#000000] space-y-4 text-center">
            
            <div class="w-14 h-14 mx-auto bg-red-100 text-[#CA2C2A] border-2 border-black flex items-center justify-center font-black text-2xl shadow-[3px_3px_0px_0px_#000000]">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>

            <div>
                <span class="inline-block text-[10px] font-black uppercase px-2 py-0.5 border border-black bg-red-100 text-red-900 shadow-[1px_1px_0px_0px_#000000] mb-1">
                    KONFIRMASI
                </span>
                <h3 class="text-base sm:text-lg font-black uppercase text-gray-950">
                    Keluar dari Akun?
                </h3>
                <p class="text-xs font-semibold text-gray-600 mt-1">
                    Apakah Anda yakin ingin keluar dari sesi portal mahasiswa?
                </p>
            </div>

            <div class="grid grid-cols-2 gap-2.5 pt-1">
                <button 
                    type="button" 
                    onclick="closeLogoutModal()"
                    class="btn-tactile py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 text-xs font-black uppercase tracking-wider border-2 border-black shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                >
                    Batal
                </button>
                <form action="{{ route('mahasiswa.logout') }}" method="POST" class="w-full">
                    @csrf
                    <button 
                        type="submit" 
                        class="btn-tactile w-full py-2.5 bg-[#CA2C2A] hover:bg-[#901C1A] text-white text-xs font-black uppercase tracking-wider border-2 border-black shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                    >
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript: Multi-Tab Controller, Avatar Preview, Scanner, and Logout Modal -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let dashQrScanner = null;
        let isDashScanning = false;
        let isSubmittingScan = false;

        // Open & Close Logout Confirmation Modal
        function openLogoutModal() {
            document.getElementById('logout-confirm-modal').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logout-confirm-modal').classList.add('hidden');
        }

        // Switch Tab Active State (Beranda, Scan, PointKu, Leaderboard, Profil)
        function switchTab(tabId) {
            const panels = ['beranda', 'scan', 'poin', 'leaderboard', 'profil'];
            if (!panels.includes(tabId)) {
                tabId = 'beranda';
            }

            // Panels Visibility
            panels.forEach(p => {
                const el = document.getElementById(`tab-panel-${p}`);
                if (el) {
                    if (p === tabId) {
                        el.classList.remove('hidden');
                    } else {
                        el.classList.add('hidden');
                    }
                }
            });

            // Desktop Sidebar Highlights
            panels.forEach(p => {
                const btn = document.getElementById(`side-nav-${p}`);
                if (btn) {
                    const flexLayout = p === 'poin' ? 'flex items-center justify-between gap-2' : 'flex items-center gap-2.5';
                    if (p === tabId) {
                        btn.className = `nav-item-active btn-tactile w-full text-left px-3.5 py-2.5 border-2 border-black font-black text-xs uppercase tracking-wider ${flexLayout} cursor-pointer`;
                    } else {
                        btn.className = `btn-tactile w-full text-left px-3.5 py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black font-black text-xs uppercase tracking-wider ${flexLayout} shadow-[2px_2px_0px_0px_#000000] cursor-pointer`;
                    }
                }
            });

            // Mobile Bottom Navigation Highlights
            panels.forEach(p => {
                const btn = document.getElementById(`bot-nav-${p}`);
                if (btn) {
                    if (p === tabId) {
                        btn.classList.add('bottom-nav-active');
                    } else {
                        btn.classList.remove('bottom-nav-active');
                    }
                }
            });

            // Jika meninggalkan tab scan, matikan kamera
            if (tabId !== 'scan') {
                stopDashScanner();
            }

            // Update URL hash
            window.location.hash = tabId;
        }

        // Live Preview Foto Profil saat dipilih
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    const placeholder = document.getElementById('avatar-placeholder');
                    if (preview) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        // Cek URL Hash atau Query Param awal saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            const hash = window.location.hash.replace('#', '');
            
            if (['beranda', 'scan', 'poin', 'leaderboard', 'profil'].includes(tabParam)) {
                switchTab(tabParam);
            } else if (['beranda', 'scan', 'poin', 'leaderboard', 'profil'].includes(hash)) {
                switchTab(hash);
            }
        });

        // Buka Kamera Scanner di Tab Scan
        async function startDashScanner() {
            document.getElementById('dash-camera-trigger').classList.add('hidden');
            document.getElementById('dash-camera-box').classList.remove('hidden');

            if (!dashQrScanner) {
                dashQrScanner = new Html5Qrcode("dash-qr-reader");
            }

            const config = {
                fps: 10,
                qrbox: { width: 220, height: 220 },
                aspectRatio: 1.0
            };

            try {
                await dashQrScanner.start(
                    { facingMode: "environment" },
                    config,
                    onDashScanSuccess,
                    (err) => {}
                );
                isDashScanning = true;
            } catch (envErr) {
                try {
                    await dashQrScanner.start(
                        { facingMode: "user" },
                        config,
                        onDashScanSuccess,
                        (err) => {}
                    );
                    isDashScanning = true;
                } catch (userErr) {
                    alert("Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan di browser.");
                    stopDashScanner();
                }
            }
        }

        // Tutup Kamera Scanner
        async function stopDashScanner() {
            if (dashQrScanner && dashQrScanner.isScanning) {
                try {
                    await dashQrScanner.stop();
                } catch (e) {}
            }
            isDashScanning = false;
            document.getElementById('dash-camera-box')?.classList.add('hidden');
            document.getElementById('dash-camera-trigger')?.classList.remove('hidden');
        }

        // Callback saat QR Proyektor Terdeteksi
        async function onDashScanSuccess(decodedText) {
            if (isSubmittingScan) return;
            isSubmittingScan = true;

            const nim = document.getElementById('dash-input-nim').value.trim();

            if (navigator.vibrate) {
                navigator.vibrate([100, 50, 100]);
            }

            await stopDashScanner();

            try {
                const response = await fetch("{{ route('presensi.scan') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nim: nim,
                        qr_payload: decodedText
                    })
                });

                const data = await response.json();
                showDashResultModal(data, response.ok);

            } catch (err) {
                showDashResultModal({
                    success: false,
                    message: "Terjadi kesalahan jaringan saat mengirim presensi."
                }, false);
            } finally {
                isSubmittingScan = false;
            }
        }

        // Suara Feedback Scan di HP
        function playDashScanSound(isSuccess) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = isSuccess ? 'triangle' : 'sawtooth';
                osc.frequency.setValueAtTime(isSuccess ? 523.25 : 240, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(isSuccess ? 783.99 : 160, ctx.currentTime + 0.18);
                gain.gain.setValueAtTime(0.25, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.32);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.32);
            } catch (e) {}
        }

        // Modal Feedback Presensi Dashboard dengan Animasi Pop
        function showDashResultModal(data, isHttpOk) {
            const modal = document.getElementById('dash-result-modal');
            const iconWrapper = document.getElementById('dash-modal-icon-wrapper');
            const pulseRing = document.getElementById('dash-modal-pulse');
            const badge = document.getElementById('dash-modal-badge');
            const title = document.getElementById('dash-modal-title');
            const desc = document.getElementById('dash-modal-desc');
            const studentBox = document.getElementById('dash-modal-student-box');

            iconWrapper.className = "icon-bounce-pop relative w-16 h-16 border-2 border-black flex items-center justify-center font-black text-3xl shadow-[3px_3px_0px_0px_#000000]";
            badge.className = "inline-block text-[10px] font-black uppercase px-2.5 py-0.5 border border-black shadow-[1px_1px_0px_0px_#000000] mb-1";

            if (isHttpOk && data.success) {
                playDashScanSound(true);
                if (navigator.vibrate) navigator.vibrate([80, 40, 120]);
                pulseRing?.classList.remove('hidden');
                setTimeout(() => pulseRing?.classList.add('hidden'), 900);

                iconWrapper.classList.add('bg-green-400', 'text-black');
                iconWrapper.innerHTML = '✓';
                badge.classList.add('bg-green-100', 'text-green-900');
                badge.innerText = data.already ? 'SUDAH TERCATAT HADIR' : 'BERHASIL HADIR';
                title.innerText = 'PRESENSI BERHASIL!';
                desc.innerText = data.message || 'Presensi kehadiran Anda telah tercatat di sistem.';

                if (data.data || data.nama) {
                    const info = data.data || data;
                    document.getElementById('dash-detail-nama').innerText = info.nama || '-';
                    document.getElementById('dash-detail-nim-kelas').innerText = `${info.nim || '-'} (${info.kelas || '-'})`;
                    document.getElementById('dash-detail-waktu').innerText = info.waktu || 'Baru Saja';
                    studentBox?.classList.remove('hidden');
                }
            } else {
                playDashScanSound(false);
                if (navigator.vibrate) navigator.vibrate([200, 80, 200]);
                pulseRing?.classList.add('hidden');

                iconWrapper.classList.add('bg-red-400', 'text-black');
                iconWrapper.innerHTML = '✕';
                badge.classList.add('bg-red-100', 'text-red-900');
                badge.innerText = 'GAGAL';
                title.innerText = 'PRESENSI GAGAL';
                desc.innerText = data.message || 'Gagal memproses presensi.';
                studentBox?.classList.add('hidden');
            }

            modal.classList.remove('hidden');
        }

        function closeDashResultModal() {
            document.getElementById('dash-result-modal').classList.add('hidden');
            window.location.reload();
        }
    </script>

</body>
</html>
