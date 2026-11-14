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
    <!-- QRCode.js Library for Generating Group Join QR -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

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

        /* Podium Vertical Grow & Avatar Lift (Selaras Admin Leaderboard) */
        @keyframes podiumGrow {
            0% { transform: scaleY(0); }
            100% { transform: scaleY(1); }
        }
        .podium-bar {
            transform-origin: bottom;
            animation: podiumGrow 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .podium-bar-1 { animation-delay: 0.05s; }
        .podium-bar-2 { animation-delay: 0.14s; }
        .podium-bar-3 { animation-delay: 0.22s; }

        .podium-avatar {
            transition: transform 0.18s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .podium-col:hover .podium-avatar {
            transform: translateY(-4px) scale(1.06);
        }

        /* Leaderboard FLIP Row & Progress Bar Transitions */
        .lb-bar-fill {
            transition: width 0.55s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .lb-row {
            will-change: transform;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        /* Realtime Update Highlight & Stat Pop */
        @keyframes dataFlashPop {
            0% { background-color: #fef08a; }
            100% { background-color: transparent; }
        }
        .realtime-flash {
            animation: dataFlashPop 0.9s ease-out;
        }

        @keyframes statPop {
            0% { transform: scale(1); }
            40% { transform: scale(1.08); }
            100% { transform: scale(1); }
        }
        .stat-pop {
            display: inline-block;
            animation: statPop 0.32s cubic-bezier(0.18, 0.89, 0.32, 1.28);
        }

        @keyframes livePulseRing {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.45; transform: scale(1.25); }
        }
        .live-dot {
            animation: livePulseRing 2s ease-in-out infinite;
        }

        /* Modal Exit Snap Out (Kebalikan dari modalSpringSlam) */
        @keyframes modalSpringOut {
            0% { transform: scale(1) translateY(0); }
            100% { transform: scale(0.92) translateY(14px); }
        }
        .modal-spring-out {
            animation: modalSpringOut 0.16s cubic-bezier(0.4, 0, 1, 1) forwards;
        }

        /* Toggle Inline Box / Kamera Viewport Snap */
        @keyframes boxSnapIn {
            0% { opacity: 0; transform: translateY(-6px) scale(0.99); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        .box-snap-in {
            animation: boxSnapIn 0.2s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        /* Staggered Card Entrance Inside Active Tab */
        @keyframes mhsSnapIn {
            0% { opacity: 0; transform: translateY(10px) scale(0.985); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        .mhs-snap-1 { animation: mhsSnapIn 0.24s cubic-bezier(0.22, 1, 0.36, 1) 0.02s both; }
        .mhs-snap-2 { animation: mhsSnapIn 0.24s cubic-bezier(0.22, 1, 0.36, 1) 0.07s both; }
        .mhs-snap-3 { animation: mhsSnapIn 0.24s cubic-bezier(0.22, 1, 0.36, 1) 0.12s both; }
        .mhs-snap-4 { animation: mhsSnapIn 0.24s cubic-bezier(0.22, 1, 0.36, 1) 0.17s both; }
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
            <div class="card-tactile bg-gray-50 border-2 border-black p-3.5 shadow-[3px_3px_0px_0px_#000000] space-y-2">
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
                        <span id="rt-sidebar-point" class="bg-green-100 border border-black text-[#15803d] px-1.5 py-0.2 font-black text-[10px]">★ {{ (int) $mahasiswa->total_point }} PTS</span>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-dashed border-gray-300 text-[9px] font-mono font-black uppercase text-gray-500">
                    <span class="inline-flex items-center gap-1 text-[#15803d]">
                        <span class="live-dot w-1.5 h-1.5 bg-[#15803d] border border-black inline-block"></span>
                        LIVE SYNC
                    </span>
                    <span id="rt-sync-clock">3s</span>
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
                    class="btn-tactile w-full text-left px-3.5 py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black font-black text-xs uppercase tracking-wider flex items-center gap-2.5 shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <span>PointKu</span>
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
                    id="side-nav-kelompok"
                    onclick="switchTab('kelompok')" 
                    class="btn-tactile w-full text-left px-3.5 py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black font-black text-xs uppercase tracking-wider flex items-center gap-2.5 shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    <svg class="w-4 h-4 text-[#15803d] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="flex-1">Kelompok</span>
                    <span id="rt-side-inbox-badge" class="{{ (isset($kelompokInbox) && $kelompokInbox->count() > 0) ? '' : 'hidden ' }}bg-amber-300 text-black border border-black px-1.5 py-0.2 text-[10px] font-mono font-black leading-tight">
                        {{ isset($kelompokInbox) ? $kelompokInbox->count() : 0 }}
                    </span>
                </button>

                <button 
                    type="button" 
                    id="side-nav-kuesioner"
                    onclick="switchTab('kuesioner')" 
                    class="btn-tactile w-full text-left px-3.5 py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black font-black text-xs uppercase tracking-wider flex items-center justify-between gap-2 shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    <span class="flex items-center gap-2.5 min-w-0">
                        <svg class="w-4 h-4 text-[#1A467C] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span>Kuesioner</span>
                    </span>
                    @if(!empty($sudahIsiKuesioner))
                        <span class="px-1.5 py-0.5 bg-green-500 text-white border border-black text-[9px] font-black leading-none shrink-0">✓</span>
                    @endif
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

            <span class="inline-flex items-center gap-1 bg-green-100 border border-black px-2 py-0.5 text-[9px] font-mono font-black uppercase text-[#15803d]">
                <span class="live-dot w-1.5 h-1.5 bg-[#15803d] border border-black inline-block"></span>
                LIVE
            </span>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 p-3.5 sm:p-6 lg:p-8 max-w-5xl w-full mx-auto pb-24 md:pb-8 space-y-4 sm:space-y-6">

            <!-- Flash Alert Success -->
            @if (session('success'))
                <div class="tab-snap-in p-3 bg-green-50 border-2 border-black text-xs font-bold text-green-900 shadow-[3px_3px_0px_0px_#15803d]">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Flash Alert Error -->
            @if (session('error'))
                <div class="tab-snap-in p-3 bg-red-50 border-2 border-black text-xs font-bold text-red-900 shadow-[3px_3px_0px_0px_#CA2C2A]">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="tab-snap-in p-3 bg-red-50 border-2 border-black text-xs font-bold text-red-900 shadow-[3px_3px_0px_0px_#CA2C2A]">
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
                <div class="mhs-snap-1 card-tactile bg-white border-2 border-black p-4 sm:p-6 shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000]">
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
                <div class="mhs-snap-2 grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4">
                    <div class="card-tactile bg-white border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000]">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-[#1A467C] block truncate">Total Hadir</span>
                        <h3 class="text-xl sm:text-3xl font-black text-gray-950 font-mono mt-1 leading-none"><span id="rt-beranda-hadir">{{ $totalHadir }}</span><span class="text-xs sm:text-lg text-gray-500 font-bold">/7</span></h3>
                    </div>

                    <div class="card-tactile bg-white border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000]">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-green-800 block truncate">Persentase</span>
                        <h3 id="rt-beranda-persen" class="text-xl sm:text-3xl font-black text-green-700 font-mono mt-1 leading-none">{{ $persenHadir }}%</h3>
                    </div>

                    <div onclick="switchTab('poin')" class="card-tactile bg-amber-50 hover:bg-amber-100 border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000] cursor-pointer">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-[#15803d] block truncate">Point Saya</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <h3 id="rt-beranda-point" class="text-xl sm:text-3xl font-black text-[#15803d] font-mono leading-none">{{ (int) $mahasiswa->total_point }}</h3>
                            <span class="text-[10px] sm:text-xs font-black uppercase text-[#15803d]">Pts</span>
                        </div>
                    </div>

                    <div onclick="switchTab('leaderboard')" class="card-tactile bg-white hover:bg-blue-50 border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000] cursor-pointer">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-[#1A467C] block truncate">Peringkat</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <h3 id="rt-beranda-rank" class="text-xl sm:text-3xl font-black text-[#1A467C] font-mono leading-none">#{{ $myRank }}</h3>
                            <span id="rt-beranda-total-peserta" class="text-[10px] sm:text-xs font-bold text-gray-500 font-mono">/{{ $totalPeserta }}</span>
                        </div>
                    </div>
                </div>

            </section>

            <!-- ========================================================== -->
            <!-- TAB 2: SCAN PRESENSI KAMERA (Terpadu, Simple & Responsive) -->
            <!-- ========================================================== -->
            <section id="tab-panel-scan" class="tab-snap-in hidden space-y-4 sm:space-y-6">

                <div class="mhs-snap-1 card-tactile bg-white border-2 border-black p-4 sm:p-6 shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] space-y-4">
                    <div class="border-b-2 border-black pb-3 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm sm:text-base font-black uppercase text-[#1A467C]">
                                Scanner Presensi
                            </h3>
                            <p class="text-[11px] sm:text-xs text-gray-600 font-semibold mt-0.5">
                                Arahkan kamera ke QR Code di layar proyektor kelas.
                            </p>
                        </div>
                        <span id="rt-scan-sesi" class="text-xs font-black bg-[#1A467C] text-white border border-black px-2.5 py-1">
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
                    <div id="dash-camera-box" class="hidden box-snap-in space-y-3">
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
                    $maxPointAll = max(1, (int) ($leaderboardList->max('total_point') ?? 1));
                @endphp

                <!-- 1. 3 Kartu Statistik Bersih -->
                <div class="mhs-snap-1 grid grid-cols-3 gap-2.5 sm:gap-4">
                    <div class="card-tactile bg-white border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000]">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-[#15803d] block truncate">Total Point</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <h3 id="rt-poin-total" class="text-xl sm:text-3xl font-black text-[#15803d] font-mono leading-none">{{ (int) $mahasiswa->total_point }}</h3>
                            <span class="text-[10px] sm:text-xs font-black uppercase text-[#15803d]">Pts</span>
                        </div>
                    </div>

                    <div class="card-tactile bg-white border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000]">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-[#1A467C] block truncate">Peringkat</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <h3 id="rt-poin-rank" class="text-xl sm:text-3xl font-black text-[#1A467C] font-mono leading-none">#{{ $myRank }}</h3>
                            <span id="rt-poin-total-peserta" class="text-[10px] sm:text-xs font-bold text-gray-500 font-mono">/{{ $totalPeserta }}</span>
                        </div>
                    </div>

                    <div class="card-tactile bg-white border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000]">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-gray-700 block truncate">Riwayat</span>
                        <h3 class="text-xl sm:text-3xl font-black text-gray-950 font-mono mt-1 leading-none"><span id="rt-poin-history-count">{{ $mahasiswa->pointHistories->count() }}</span><span class="text-xs sm:text-base text-gray-500 font-bold">x</span></h3>
                    </div>
                </div>

                <!-- 2. Daftar Riwayat Point Saya -->
                <div class="mhs-snap-2 card-tactile bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] overflow-hidden">
                    <div class="px-4 sm:px-5 py-3 border-b-2 border-black bg-blue-50/70 flex items-center justify-between">
                        <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                            Riwayat Point
                        </h4>
                    </div>

                    <div id="rt-poin-history-list" class="divide-y divide-gray-200">
                        @forelse ($mahasiswa->pointHistories as $hist)
                            @php $isAdd = $hist->tipe === 'tambah'; @endphp
                            <div class="px-4 sm:px-5 py-3.5 hover:bg-blue-50/30 transition flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3 min-w-0">
                                    <span class="w-8 h-8 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_#000000] {{ $isAdd ? 'bg-[#15803d] text-white' : 'bg-[#CA2C2A] text-white' }}">
                                        {{ $isAdd ? '+' : '-' }}{{ (int) $hist->jumlah }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="font-black text-xs sm:text-sm uppercase text-gray-950 leading-tight">{{ $hist->kategori }}</p>
                                        @if ($hist->keterangan)
                                            <p class="text-xs font-semibold text-gray-600 mt-0.5 break-words">{{ $hist->keterangan }}</p>
                                        @endif
                                    </div>
                                </div>

                                <span class="shrink-0 font-mono text-[10px] font-bold text-gray-400 pt-0.5">
                                    {{ $hist->created_at ? $hist->created_at->timezone('Asia/Jakarta')->format('d/m H:i') : '-' }}
                                </span>
                            </div>
                        @empty
                            <div class="p-8 text-center text-xs font-bold text-gray-500">
                                Belum ada riwayat point.
                            </div>
                        @endforelse
                    </div>
                </div>

            </section>

            <!-- ========================================================== -->
            <!-- TAB 4: LEADERBOARD KELOMPOK POKJA (Individu di-hide)       -->
            <!-- ========================================================== -->
            <section id="tab-panel-leaderboard" class="tab-snap-in hidden space-y-5 sm:space-y-6">

                <!-- Header Mode Leaderboard (Fokus Kelompok; Peserta di-hide) -->
                <div class="mhs-snap-1 card-tactile bg-white border-2 border-black p-2.5 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            id="lb-btn-individu"
                            onclick="switchLeaderboardMode('individu')"
                            class="hidden"
                            aria-hidden="true"
                        >
                            Peserta ({{ $totalPeserta }})
                        </button>
                        <button
                            type="button"
                            id="lb-btn-kelompok"
                            onclick="switchLeaderboardMode('kelompok')"
                            class="btn-tactile px-4 py-2 bg-[#1A467C] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                        >
                            Kelompok ({{ $totalKelompok }})
                        </button>
                    </div>
                    <span class="font-mono text-[10px] font-black uppercase text-gray-500 px-2">
                        Klasemen Point Kelompok
                    </span>
                </div>

                <!-- MODE 1: LEADERBOARD INDIVIDU (Disembunyikan di Portal Mahasiswa) -->
                <div id="lb-view-individu" class="hidden space-y-5 sm:space-y-6">

                <!-- Podium Top 3 (Grafik Batang Podium Vertikal Ke Atas) -->
                @php
                    $first = $topThree->get(0);
                    $second = $topThree->get(1);
                    $third = $topThree->get(2);
                    $podiumBars = [
                        [
                            'rank' => 2,
                            'barBg' => 'bg-sky-200',
                            'barHeight' => 'h-28 sm:h-36',
                            'emptyHeight' => 'h-24 sm:h-32',
                            'mhs' => $second,
                        ],
                        [
                            'rank' => 1,
                            'barBg' => 'bg-amber-300',
                            'barHeight' => 'h-40 sm:h-48',
                            'emptyHeight' => 'h-32 sm:h-40',
                            'mhs' => $first,
                        ],
                        [
                            'rank' => 3,
                            'barBg' => 'bg-orange-200',
                            'barHeight' => 'h-20 sm:h-28',
                            'emptyHeight' => 'h-16 sm:h-24',
                            'mhs' => $third,
                        ],
                    ];
                @endphp

                <div id="lb-individu-podium-card" class="mhs-snap-2 card-tactile {{ $topThree->count() > 0 ? '' : 'hidden ' }}bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-6">
                    <div class="border-b-2 border-black pb-3 mb-5">
                        <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                            Podium Peringkat Teratas
                        </h4>
                    </div>

                    <div id="lb-individu-podium" class="grid grid-cols-3 gap-2.5 sm:gap-5 items-end max-w-2xl mx-auto pt-2 px-1">
                        @foreach ($podiumBars as $pod)
                            @php $mhs = $pod['mhs']; @endphp
                            <div class="flex flex-col items-center {{ $mhs ? 'podium-col' : '' }}">
                                @if ($mhs)
                                    @php $isMe = (int) $mhs->id === (int) $mahasiswa->id; @endphp
                                    <!-- Info Mahasiswa di Atas Batang -->
                                    <div class="flex flex-col items-center text-center mb-2.5 w-full">
                                        <div class="relative podium-avatar">
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
                                    <div class="podium-bar podium-bar-{{ $pod['rank'] }} w-full {{ $pod['barHeight'] }} {{ $pod['barBg'] }} border-t-2 border-x-2 border-black shadow-[3px_0px_0px_0px_#000000] flex flex-col items-center justify-center p-2 relative">
                                        <span class="font-mono font-black text-xl sm:text-3xl text-black/85 leading-none">
                                            #{{ $pod['rank'] }}
                                        </span>
                                    </div>
                                @else
                                    <div class="w-full {{ $pod['emptyHeight'] }} bg-gray-100/70 border-t-2 border-x-2 border-dashed border-gray-300 flex items-center justify-center">
                                        <span class="font-mono font-black text-base text-gray-400">#{{ $pod['rank'] }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="h-1.5 bg-black w-full max-w-2xl mx-auto"></div>
                </div>

                <!-- Klasemen Lengkap Peserta -->
                <div class="mhs-snap-3 card-tactile bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] overflow-hidden">
                    <div class="px-4 sm:px-5 py-3 border-b-2 border-black bg-blue-50/70 flex items-center justify-between">
                        <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                            Peringkat Peserta
                        </h4>
                        <span id="lb-individu-total-badge" class="bg-white border border-black px-2 py-0.5 font-mono font-black text-[10px] text-[#1A467C]">
                            {{ $totalPeserta }} Mahasiswa
                        </span>
                    </div>

                    <div id="lb-individu-list" class="divide-y divide-gray-200">
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
                            <div data-lb-key="mhs-{{ $item->id }}" data-rank="{{ $rankNum }}" data-pts="{{ $pts }}" data-foto-url="{{ $item->avatarUrl() ?? '' }}" class="lb-row px-4 sm:px-5 py-3.5 {{ $isCurrentUser ? 'bg-amber-50/90 border-l-4 border-l-[#1A467C]' : 'hover:bg-blue-50/40' }} flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <span data-role="rank-badge" class="w-8 h-8 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 {{ $rankNum === 1 ? 'bg-amber-300 text-black' : ($rankNum === 2 ? 'bg-sky-200 text-black' : ($rankNum === 3 ? 'bg-orange-200 text-black' : 'bg-gray-100 text-gray-800')) }}">
                                        #{{ $rankNum }}
                                    </span>

                                    <div data-role="avatar" class="w-9 h-9 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden">
                                        @if ($item->avatarUrl())
                                            <img src="{{ $item->avatarUrl() }}" alt="Avatar {{ $item->nama }}" class="w-full h-full object-cover">
                                        @else
                                            {{ $item->initials() }}
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div data-role="title-wrap" class="flex items-center gap-1.5">
                                            <p data-role="name" class="font-black text-xs sm:text-sm text-gray-950 truncate">{{ $item->nama }}</p>
                                            @if ($isCurrentUser)
                                                <span data-role="my-badge" class="bg-[#1A467C] text-white text-[9px] font-black uppercase px-1.5 py-0.2 border border-black shrink-0">
                                                    KAMU
                                                </span>
                                            @endif
                                        </div>
                                        <p data-role="submeta" class="font-mono text-[10px] sm:text-[11px] font-bold text-[#1A467C]">{{ $item->kelas }}</p>
                                        <div class="mt-1.5 w-full max-w-md h-2.5 bg-gray-100 border border-black overflow-hidden">
                                            <div data-role="bar-fill" class="lb-bar-fill h-full {{ $barColor }}" style="width: {{ $pctWidth }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-green-100 border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1 shrink-0">
                                    <span data-role="pts-text" class="font-mono font-black text-xs sm:text-sm text-[#15803d] leading-none">{{ $pts }}</span>
                                    <span class="text-[9px] sm:text-[10px] font-black uppercase text-[#15803d] leading-none">PTS</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                </div> <!-- End #lb-view-individu -->

                <!-- MODE 2: LEADERBOARD KELOMPOK -->
                <div id="lb-view-kelompok" class="space-y-5 sm:space-y-6">
                    @php
                        $maxKelompokPoint = max(1, (int) ($kelompokLeaderboardList->max('total_point') ?? 1));
                        $grpFirst = $kelompokTopThree->get(0);
                        $grpSecond = $kelompokTopThree->get(1);
                        $grpThird = $kelompokTopThree->get(2);
                        $kelompokPodiumBars = [
                            [
                                'rank' => 2,
                                'barBg' => 'bg-sky-200',
                                'barHeight' => 'h-28 sm:h-36',
                                'emptyHeight' => 'h-24 sm:h-32',
                                'grp' => $grpSecond,
                            ],
                            [
                                'rank' => 1,
                                'barBg' => 'bg-amber-300',
                                'barHeight' => 'h-40 sm:h-48',
                                'emptyHeight' => 'h-32 sm:h-40',
                                'grp' => $grpFirst,
                            ],
                            [
                                'rank' => 3,
                                'barBg' => 'bg-orange-200',
                                'barHeight' => 'h-20 sm:h-28',
                                'emptyHeight' => 'h-16 sm:h-24',
                                'grp' => $grpThird,
                            ],
                        ];
                    @endphp

                    <div id="lb-kelompok-empty" class="mhs-snap-2 card-tactile {{ $kelompokLeaderboardList->isEmpty() ? '' : 'hidden ' }}bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] p-8 text-center">
                        <p class="text-xs sm:text-sm font-black uppercase text-[#1A467C]">Belum Ada Kelompok</p>
                        <p class="text-xs font-semibold text-gray-600 mt-1">
                            Klasemen kelompok akan muncul otomatis setelah ada kelompok yang terbentuk.
                        </p>
                    </div>

                    <div id="lb-kelompok-content" class="{{ $kelompokLeaderboardList->isEmpty() ? 'hidden ' : '' }}space-y-5 sm:space-y-6">
                        <!-- Podium Top 3 Kelompok -->
                        <div class="mhs-snap-2 card-tactile bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] p-4 sm:p-6">
                            <div class="border-b-2 border-black pb-3 mb-5 flex items-center justify-between">
                                <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                                    Podium Kelompok Teratas
                                </h4>
                                <span class="font-mono text-[10px] font-black uppercase text-gray-500">
                                    Total Point Tim
                                </span>
                            </div>

                            <div id="lb-kelompok-podium" class="grid grid-cols-3 gap-2.5 sm:gap-5 items-end max-w-2xl mx-auto pt-2 px-1">
                                @foreach ($kelompokPodiumBars as $pod)
                                    @php $grp = $pod['grp']; @endphp
                                    <div class="flex flex-col items-center {{ $grp ? 'podium-col' : '' }}">
                                        @if ($grp)
                                            @php $isMyGroup = $myKelompok && (int) $grp->id === (int) $myKelompok->id; @endphp
                                            <div class="flex flex-col items-center text-center mb-2.5 w-full">
                                                <div class="relative podium-avatar">
                                                    <div class="{{ $pod['rank'] === 1 ? 'w-11 h-11 sm:w-14 sm:h-14 text-sm sm:text-base' : 'w-9 h-9 sm:w-12 sm:h-12 text-xs sm:text-sm' }} bg-[#1A467C] text-white border-2 border-black flex items-center justify-center font-black overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                                                        @if (!empty($grp->foto_url))
                                                            <img src="{{ $grp->foto_url }}" alt="Foto {{ $grp->nama_kelompok }}" class="w-full h-full object-cover">
                                                        @else
                                                            {{ $grp->initials }}
                                                        @endif
                                                    </div>
                                                    @if ($isMyGroup)
                                                        <span class="absolute -top-2 -right-3 bg-[#15803d] text-white text-[8px] font-black uppercase px-1 py-0.2 border border-black">
                                                            TIMMU
                                                        </span>
                                                    @endif
                                                </div>

                                                <h5 class="font-black text-[10px] sm:text-xs text-gray-950 uppercase truncate max-w-full mt-1.5 px-0.5" title="{{ $grp->nama_kelompok }}">
                                                    {{ $grp->nama_kelompok }}
                                                </h5>
                                                <span class="font-mono text-[9px] sm:text-[10px] font-bold text-[#1A467C]">
                                                    {{ $grp->jumlah_anggota }} Anggota
                                                </span>

                                                <div class="mt-1.5 bg-white border-2 border-black px-2 py-0.5 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1">
                                                    <span class="font-mono font-black text-xs sm:text-sm text-gray-950 leading-none">{{ $grp->total_point }}</span>
                                                    <span class="text-[8px] sm:text-[9px] font-black uppercase text-[#15803d] leading-none">PTS</span>
                                                </div>
                                            </div>

                                            <div class="podium-bar podium-bar-{{ $pod['rank'] }} w-full {{ $pod['barHeight'] }} {{ $pod['barBg'] }} border-t-2 border-x-2 border-black shadow-[3px_0px_0px_0px_#000000] flex flex-col items-center justify-center p-2">
                                                <span class="font-mono font-black text-xl sm:text-3xl text-black/85 leading-none">
                                                    #{{ $pod['rank'] }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="w-full {{ $pod['emptyHeight'] }} bg-gray-100/70 border-t-2 border-x-2 border-dashed border-gray-300 flex items-center justify-center">
                                                <span class="font-mono font-black text-base text-gray-400">#{{ $pod['rank'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="h-1.5 bg-black w-full max-w-2xl mx-auto"></div>
                        </div>

                        <!-- Klasemen Lengkap Kelompok -->
                        <div class="mhs-snap-3 card-tactile bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000000] overflow-hidden">
                            <div class="px-4 sm:px-5 py-3 border-b-2 border-black bg-blue-50/70 flex items-center justify-between">
                                <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                                    Peringkat Kelompok
                                </h4>
                                <span id="lb-kelompok-total-badge" class="bg-white border border-black px-2 py-0.5 font-mono font-black text-[10px] text-[#1A467C]">
                                    {{ $totalKelompok }} Kelompok
                                </span>
                            </div>

                            <div id="lb-kelompok-list" class="divide-y divide-gray-200">
                                @foreach ($kelompokLeaderboardList->slice(3) as $idx => $grp)
                                    @php
                                        $rankNum = $idx + 1;
                                        $pts = (int) $grp->total_point;
                                        $pctWidth = $pts > 0 ? max(8, round(($pts / $maxKelompokPoint) * 100)) : 0;
                                        $isMyTeam = $myKelompok && (int) $grp->id === (int) $myKelompok->id;
                                        $barColor = $rankNum === 1
                                            ? 'bg-amber-400'
                                            : ($rankNum === 2
                                                ? 'bg-[#2A82C6]'
                                                : ($rankNum === 3 ? 'bg-orange-400' : 'bg-[#15803d]'));
                                    @endphp
                                    <div data-lb-key="grp-{{ $grp->id }}" data-rank="{{ $rankNum }}" data-pts="{{ $pts }}" data-foto-url="{{ $grp->foto_url ?? '' }}" class="lb-row px-4 sm:px-5 py-3.5 {{ $isMyTeam ? 'bg-amber-50/90 border-l-4 border-l-[#1A467C]' : 'hover:bg-blue-50/40' }} flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0 flex-1">
                                            <span data-role="rank-badge" class="w-8 h-8 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 {{ $rankNum === 1 ? 'bg-amber-300 text-black' : ($rankNum === 2 ? 'bg-sky-200 text-black' : ($rankNum === 3 ? 'bg-orange-200 text-black' : 'bg-gray-100 text-gray-800')) }}">
                                                #{{ $rankNum }}
                                            </span>

                                            <div data-role="avatar" class="w-9 h-9 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden">
                                                @if (!empty($grp->foto_url))
                                                    <img src="{{ $grp->foto_url }}" alt="Foto {{ $grp->nama_kelompok }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ $grp->initials }}
                                                @endif
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <div data-role="title-wrap" class="flex items-center gap-1.5">
                                                    <p data-role="name" class="font-black text-xs sm:text-sm text-gray-950 truncate">{{ $grp->nama_kelompok }}</p>
                                                    @if ($isMyTeam)
                                                        <span data-role="my-badge" class="bg-[#15803d] text-white text-[9px] font-black uppercase px-1.5 py-0.2 border border-black shrink-0">
                                                            TIM KAMU
                                                        </span>
                                                    @endif
                                                </div>
                                                <p data-role="submeta" class="font-mono text-[10px] sm:text-[11px] font-bold text-gray-600">
                                                    Ketua: <span class="text-[#1A467C]">{{ $grp->ketua_nama }}</span> • {{ $grp->jumlah_anggota }} Anggota
                                                </p>
                                                <div class="mt-1.5 w-full max-w-md h-2.5 bg-gray-100 border border-black overflow-hidden">
                                                    <div data-role="bar-fill" class="lb-bar-fill h-full {{ $barColor }}" style="width: {{ $pctWidth }}%"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-green-100 border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1 shrink-0">
                                            <span data-role="pts-text" class="font-mono font-black text-xs sm:text-sm text-[#15803d] leading-none">{{ $pts }}</span>
                                            <span class="text-[9px] sm:text-[10px] font-black uppercase text-[#15803d] leading-none">PTS</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div> <!-- End #lb-view-kelompok -->

            </section>

            <!-- ========================================================== -->
            <!-- TAB 4.5: KELOMPOK & INBOX UNDANGAN MAHASISWA               -->
            <!-- ========================================================== -->
            <section id="tab-panel-kelompok" class="tab-snap-in hidden space-y-4 sm:space-y-6">
                @include('mahasiswa.partials.kelompok_panel')
            </section>

            <!-- ========================================================== -->
            <!-- TAB 3: PROFIL & PENGATURAN AKUN MAHASISWA                  -->
            <!-- ========================================================== -->
            <section id="tab-panel-profil" class="tab-snap-in hidden space-y-4 sm:space-y-6">

                <div class="mhs-snap-1 card-tactile bg-white border-2 border-black p-4 sm:p-6 shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] space-y-5">
                    
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

            <!-- ========================================================= -->
            <!-- TAB 7: KUESIONER DINAMIS (1x ISI & BISA DIEDIT)           -->
            <!-- ========================================================= -->
            @php
                $hasKuesionerValidationError = collect($errors->keys())->contains(fn ($k) => str_starts_with($k, 'jawaban'));
            @endphp
            <section id="tab-panel-kuesioner" class="tab-snap-in hidden space-y-4 sm:space-y-6">
                <div class="bg-white border-2 sm:border-[3px] border-black shadow-[4px_4px_0px_0px_#000000] sm:shadow-[6px_6px_0px_0px_#000000] p-4 sm:p-6">
                    <div class="pb-4 border-b-2 border-black">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 bg-[#1A467C] border border-black inline-block"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-[#1A467C]">Evaluasi &amp; Masukan Anggota</span>
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-black uppercase tracking-tight mt-1">
                            Kuesioner POKJA HIMA IF
                        </h2>
                        <p class="text-xs font-medium text-gray-600 mt-1">
                            Setiap anggota mengisi kuesioner 1 kali di tiap pertemuan dan dapat memperbarui jawabannya kapan saja.
                        </p>

                        <!-- Sub-Tab Pertemuan -->
                        <div class="flex flex-wrap items-center gap-2 mt-4">
                            @foreach($kuesionerPertemuanList as $pNum)
                                @php
                                    $isSelectedP = (int) $kuesionerPertemuanDipilih === (int) $pNum;
                                    $isFilledP = $sudahIsiKuesionerByPertemuan[$pNum] ?? false;
                                @endphp
                                <button
                                    type="button"
                                    id="kuesioner-ptab-{{ $pNum }}"
                                    onclick="switchKuesionerPertemuan({{ $pNum }})"
                                    class="btn-tactile px-3 py-1.5 border-2 border-black text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5 cursor-pointer {{ $isSelectedP ? 'bg-[#1A467C] text-white shadow-[2px_2px_0px_0px_#000000]' : 'bg-white hover:bg-gray-100 text-black' }}"
                                >
                                    <span>Pertemuan {{ $pNum }}</span>
                                    @if((int) $pNum === (int) $pertemuanAktifKuesioner)
                                        <span class="px-1 py-0.5 bg-amber-300 text-black border border-black text-[9px] leading-none">Aktif</span>
                                    @endif
                                    @if($isFilledP)
                                        <span class="px-1 py-0.5 bg-green-500 text-white border border-black text-[9px] leading-none">✓</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @foreach($kuesionerPertemuanList as $pNum)
                        @php
                            $pQuestions = $kuesionerPertanyaanByPertemuan[$pNum] ?? collect();
                            $pJawabanMap = $kuesionerJawabanByPertemuan->get($pNum, collect());
                            $pSudahIsi = $sudahIsiKuesionerByPertemuan[$pNum] ?? false;
                            $isSelectedPanel = (int) $kuesionerPertemuanDipilih === (int) $pNum;
                            $pHasValidationError = $hasKuesionerValidationError && (int) old('pertemuan', $kuesionerPertemuanDipilih) === (int) $pNum;
                        @endphp
                        <div id="kuesioner-pertemuan-panel-{{ $pNum }}" class="kuesioner-pertemuan-panel mt-5 {{ $isSelectedPanel ? '' : 'hidden' }}">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                                <span class="text-xs font-black uppercase tracking-wider text-gray-800">
                                    Kuesioner Pertemuan {{ $pNum }}
                                </span>

                                @if($pQuestions->isNotEmpty())
                                    @if($pSudahIsi)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 text-green-900 border-2 border-black text-[11px] font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000]">
                                            ✓ Sudah Mengisi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-100 text-amber-900 border-2 border-black text-[11px] font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000]">
                                            ● Belum Mengisi
                                        </span>
                                    @endif
                                @endif
                            </div>

                            @if($pQuestions->isEmpty())
                                <div class="p-6 bg-gray-50 border-2 border-dashed border-gray-400 text-center">
                                    <p class="text-xs sm:text-sm font-black uppercase tracking-wider text-gray-700">
                                        Belum Ada Pertanyaan untuk Pertemuan {{ $pNum }}
                                    </p>
                                    <p class="text-xs font-medium text-gray-500 mt-1">
                                        Panitia belum menambahkan atau mengaktifkan pertanyaan kuesioner untuk pertemuan ini.
                                    </p>
                                </div>
                            @else
                                @if($pSudahIsi && !$pHasValidationError)
                                    <!-- Ringkasan Jawaban Pertemuan {{ $pNum }} -->
                                    <div id="kuesioner-summary-box-{{ $pNum }}" class="space-y-4">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3.5 bg-green-50 border-2 border-black shadow-[3px_3px_0px_0px_#000000]">
                                            <div>
                                                <p class="text-xs font-black uppercase tracking-wider text-green-900">
                                                    Jawaban Pertemuan {{ $pNum }} Sudah Tercatat
                                                </p>
                                                <p class="text-[11px] font-semibold text-green-800 mt-0.5">
                                                    Masih ada yang ingin diubah? Klik tombol Edit Jawaban di sebelah kanan.
                                                </p>
                                            </div>
                                            <button
                                                type="button"
                                                onclick="toggleEditKuesioner({{ $pNum }}, true)"
                                                class="btn-tactile px-4 py-2 bg-[#1A467C] hover:bg-[#12335C] text-white border-2 border-black font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] cursor-pointer shrink-0"
                                            >
                                                Edit Jawaban
                                            </button>
                                        </div>

                                        <div class="space-y-3">
                                            @foreach($pQuestions as $idx => $q)
                                                @php
                                                    $savedAnswer = $pJawabanMap->get($q->id)?->jawaban;
                                                @endphp
                                                <div class="p-3.5 bg-gray-50 border-2 border-black">
                                                    <p class="text-[11px] font-black uppercase tracking-wider text-gray-500">
                                                        Pertanyaan {{ $idx + 1 }}
                                                    </p>
                                                    <p class="text-xs sm:text-sm font-black text-black mt-0.5">
                                                        {{ $q->pertanyaan }}
                                                    </p>
                                                    <div class="mt-2 pt-2 border-t border-gray-300 text-xs sm:text-sm font-semibold text-gray-800 whitespace-pre-line">
                                                        {{ $savedAnswer !== null && $savedAnswer !== '' ? $savedAnswer : '-' }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Form Pengisian / Edit Kuesioner Pertemuan {{ $pNum }} -->
                                <div id="kuesioner-form-box-{{ $pNum }}" class="{{ ($pSudahIsi && !$pHasValidationError) ? 'hidden' : '' }}">
                                    <form action="{{ route('mahasiswa.kuesioner.submit') }}" method="POST" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="pertemuan" value="{{ $pNum }}">
                                        @foreach($pQuestions as $idx => $q)
                                            @php
                                                $savedValue = $pJawabanMap->get($q->id)?->jawaban ?? '';
                                                $fieldValue = $pHasValidationError ? old('jawaban.' . $q->id, $savedValue) : $savedValue;
                                                $fieldError = $pHasValidationError ? $errors->first('jawaban.' . $q->id) : null;
                                            @endphp
                                            <div class="p-4 bg-[#F8FAFC] border-2 border-black shadow-[3px_3px_0px_0px_#000000]">
                                                <div class="flex items-start justify-between gap-2 mb-2">
                                                    <label class="block text-xs sm:text-sm font-black text-black leading-snug">
                                                        <span class="inline-block px-1.5 py-0.5 bg-[#1A467C] text-white border border-black text-[10px] mr-1.5">#{{ $idx + 1 }}</span>
                                                        {{ $q->pertanyaan }}
                                                        @if($q->wajib)
                                                            <span class="text-red-600 ml-0.5">*</span>
                                                        @endif
                                                    </label>
                                                    <span class="px-2 py-0.5 bg-white border border-black text-[9px] font-black uppercase tracking-wider text-gray-700 shrink-0">
                                                        {{ \App\Models\KuesionerPertanyaan::TIPE_LABELS[$q->tipe] ?? $q->tipe }}
                                                    </span>
                                                </div>

                                                @if($q->tipe === 'teks')
                                                    <input
                                                        type="text"
                                                        name="jawaban[{{ $q->id }}]"
                                                        value="{{ $fieldValue }}"
                                                        maxlength="500"
                                                        {{ $q->wajib ? 'required' : '' }}
                                                        placeholder="Tulis jawaban singkat Anda..."
                                                        class="w-full px-3 py-2.5 bg-white border-2 border-black text-xs sm:text-sm font-bold text-black focus:outline-none focus:bg-yellow-50"
                                                    >
                                                @elseif($q->tipe === 'paragraf')
                                                    <textarea
                                                        name="jawaban[{{ $q->id }}]"
                                                        rows="3"
                                                        maxlength="3000"
                                                        {{ $q->wajib ? 'required' : '' }}
                                                        placeholder="Tulis jawaban lengkap Anda..."
                                                        class="w-full px-3 py-2.5 bg-white border-2 border-black text-xs sm:text-sm font-bold text-black focus:outline-none focus:bg-yellow-50"
                                                    >{{ $fieldValue }}</textarea>
                                                @elseif($q->tipe === 'pilihan')
                                                    @php
                                                        $opsiList = is_array($q->opsi) ? $q->opsi : [];
                                                    @endphp
                                                    <div class="space-y-2 mt-2">
                                                        @foreach($opsiList as $optIdx => $optText)
                                                            <label class="flex items-center gap-2.5 p-2.5 bg-white border-2 border-black cursor-pointer hover:bg-blue-50 transition">
                                                                <input
                                                                    type="radio"
                                                                    name="jawaban[{{ $q->id }}]"
                                                                    value="{{ $optText }}"
                                                                    {{ (string) $fieldValue === (string) $optText ? 'checked' : '' }}
                                                                    {{ ($q->wajib && $optIdx === 0) ? 'required' : '' }}
                                                                    class="w-4 h-4 accent-[#1A467C] shrink-0"
                                                                >
                                                                <span class="text-xs sm:text-sm font-bold text-gray-900">{{ $optText }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                @if($fieldError)
                                                    <p class="text-[11px] font-black text-red-600 mt-1.5">{{ $fieldError }}</p>
                                                @endif
                                            </div>
                                        @endforeach

                                        <div class="flex flex-wrap items-center justify-end gap-2.5 pt-2">
                                            @if($pSudahIsi && !$pHasValidationError)
                                                <button
                                                    type="button"
                                                    onclick="toggleEditKuesioner({{ $pNum }}, false)"
                                                    class="btn-tactile px-4 py-2.5 bg-white hover:bg-gray-100 text-black border-2 border-black font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                                                >
                                                    Batal
                                                </button>
                                            @endif
                                            <button
                                                type="submit"
                                                class="btn-tactile px-5 py-2.5 bg-[#1A467C] hover:bg-[#12335C] text-white border-2 border-black font-black text-xs uppercase tracking-wider shadow-[4px_4px_0px_0px_#000000] cursor-pointer"
                                            >
                                                {{ $pSudahIsi ? "Perbarui Jawaban Pertemuan {$pNum}" : "Simpan Jawaban Pertemuan {$pNum}" }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>

        </main>

    </div>

    <!-- ============================================================== -->
    <!-- MOBILE BOTTOM NAVIGATION TOOLBAR (Khas Mobile Apps)            -->
    <!-- ============================================================== -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t-[3px] border-black flex justify-around items-center py-2 px-0.5 shadow-[0_-3px_0px_0px_#000000]">
        
        <!-- Tab 1: Beranda -->
        <button 
            type="button" 
            id="bot-nav-beranda"
            onclick="switchTab('beranda')" 
            class="bottom-nav-active btn-tactile flex flex-col items-center gap-1 py-1 px-1 text-gray-600 cursor-pointer"
        >
            <div class="bottom-nav-icon w-7 h-7 sm:w-8 sm:h-8 rounded-none border border-black flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-tight">Beranda</span>
        </button>

        <!-- Tab 2: Scan Presensi -->
        <button 
            type="button" 
            id="bot-nav-scan"
            onclick="switchTab('scan')" 
            class="btn-tactile flex flex-col items-center gap-1 py-1 px-1 text-gray-600 cursor-pointer"
        >
            <div class="bottom-nav-icon w-7 h-7 sm:w-8 sm:h-8 rounded-none border border-black flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="square" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-tight">Scan</span>
        </button>

        <!-- Tab 3: PointKu -->
        <button 
            type="button" 
            id="bot-nav-poin"
            onclick="switchTab('poin')" 
            class="btn-tactile flex flex-col items-center gap-1 py-1 px-1 text-gray-600 cursor-pointer"
        >
            <div class="bottom-nav-icon w-7 h-7 sm:w-8 sm:h-8 rounded-none border border-black flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
            <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-tight">PointKu</span>
        </button>

        <!-- Tab 4: Leaderboard -->
        <button 
            type="button" 
            id="bot-nav-leaderboard"
            onclick="switchTab('leaderboard')" 
            class="btn-tactile flex flex-col items-center gap-1 py-1 px-1 text-gray-600 cursor-pointer"
        >
            <div class="bottom-nav-icon w-7 h-7 sm:w-8 sm:h-8 rounded-none border border-black flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-tight">Rank</span>
        </button>

        <!-- Tab 5: Kelompok -->
        <button 
            type="button" 
            id="bot-nav-kelompok"
            onclick="switchTab('kelompok')" 
            class="btn-tactile flex flex-col items-center gap-1 py-1 px-1 text-gray-600 cursor-pointer"
        >
            <div class="bottom-nav-icon relative w-7 h-7 sm:w-8 sm:h-8 rounded-none border border-black flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span id="rt-bot-inbox-badge" class="{{ (isset($kelompokInbox) && $kelompokInbox->count() > 0) ? '' : 'hidden ' }}absolute -top-1.5 -right-1.5 min-w-[14px] h-[14px] px-0.5 bg-amber-300 text-black border border-black text-[8px] font-mono font-black flex items-center justify-center leading-none">
                    {{ isset($kelompokInbox) ? $kelompokInbox->count() : 0 }}
                </span>
            </div>
            <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-tight">Kelompok</span>
        </button>

        <!-- Tab 6: Kuesioner -->
        <button 
            type="button" 
            id="bot-nav-kuesioner"
            onclick="switchTab('kuesioner')" 
            class="btn-tactile relative flex flex-col items-center gap-1 py-1 px-1 text-gray-600 cursor-pointer"
        >
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="square" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-tight">Kuesioner</span>
            @if(!empty($sudahIsiKuesioner))
                <span class="absolute top-0.5 right-1 w-2 h-2 bg-green-500 border border-black rounded-full"></span>
            @endif
        </button>

        <!-- Tab 7: Profil -->
        <button 
            type="button" 
            id="bot-nav-profil"
            onclick="switchTab('profil')" 
            class="btn-tactile flex flex-col items-center gap-1 py-1 px-1 text-gray-600 cursor-pointer"
        >
            <div class="bottom-nav-icon w-7 h-7 sm:w-8 sm:h-8 rounded-none border border-black flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-tight">Profil</span>
        </button>

        <!-- Tab 7: Keluar dengan Konfirmasi -->
        <button 
            type="button" 
            onclick="openLogoutModal()"
            class="btn-tactile flex flex-col items-center gap-1 py-1 px-1 text-[#CA2C2A] cursor-pointer"
        >
            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-none border border-black flex items-center justify-center bg-red-50 text-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>
            <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-tight">Keluar</span>
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
                Selesai
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

    <!-- ============================================================== -->
    <!-- KELOMPOK CONFIRMATION MODAL POP-UP (Neubrutalism)              -->
    <!-- ============================================================== -->
    <div id="kelompok-confirm-modal" class="hidden fixed inset-0 z-50 bg-black/60 p-4 flex items-center justify-center">
        <div class="modal-spring-slam w-full max-w-sm bg-white border-[3px] border-black p-5 sm:p-6 shadow-[8px_8px_0px_0px_#000000] space-y-4 text-center">
            <div>
                <span class="inline-block text-[10px] font-black uppercase px-2 py-0.5 border border-black bg-amber-100 text-gray-950 shadow-[1px_1px_0px_0px_#000000] mb-1">
                    KONFIRMASI KELOMPOK
                </span>
                <h3 id="kelompok-confirm-title" class="text-base sm:text-lg font-black uppercase text-gray-950"></h3>
                <p id="kelompok-confirm-desc" class="text-xs font-semibold text-gray-600 mt-1"></p>
            </div>

            <div class="grid grid-cols-2 gap-2.5 pt-1">
                <button
                    type="button"
                    onclick="closeKelompokConfirmModal()"
                    class="btn-tactile py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 text-xs font-black uppercase tracking-wider border-2 border-black shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="button"
                    id="kelompok-confirm-btn"
                    onclick="submitKelompokConfirmModal()"
                    class="btn-tactile w-full py-2.5 bg-[#CA2C2A] text-white text-xs font-black uppercase tracking-wider border-2 border-black shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                >
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript: Multi-Tab Controller, Avatar Preview, Scanner, Kelompok & Logout Modal -->
    @php
        $hasKelompokValidationError = $errors->has('nama_kelompok')
            || $errors->has('judul_proposal')
            || $errors->has('foto')
            || $errors->has('kode_invite')
            || $errors->has('pendaftaran_id');
    @endphp
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let dashQrScanner = null;
        let isDashScanning = false;
        let isSubmittingScan = false;
        let kelompokQrScanner = null;
        let isKelompokQrSubmitted = false;
        let pendingKelompokConfirmFormId = null;

        let kelompokInviteUrl = @json($myKelompok ? route('mahasiswa.kelompok.joinLink', ['kode' => $myKelompok->kode_invite]) : '');
        let kelompokCandidates = @json($kandidatAnggotaList ?? []);
        const kelompokInviteAction = @json(route('mahasiswa.kelompok.invite'));
        const hasKelompokValidationError = @json($hasKelompokValidationError);
        const hasKuesionerValidationError = @json($hasKuesionerValidationError ?? false);
        const dashboardSyncUrl = @json(route('mahasiswa.dashboard.sync'));
        const myStudentId = {{ (int) $mahasiswa->id }};

        let currentRealtimeSig = @json($realtimeSignature ?? '');
        let pendingKelompokPayload = null;
        let currentLeaderboardMode = 'kelompok';
        let isRealtimeSyncInFlight = false;
        let realtimePollTimer = null;
        let lastIndPodiumSig = '';
        let lastGrpPodiumSig = '';

        // Shared Tactile Modal Open/Close Animation (Spring Slam In & Snap Out)
        function openModalAnimated(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            if (modal.dataset.closeTimer) {
                clearTimeout(Number(modal.dataset.closeTimer));
                delete modal.dataset.closeTimer;
            }
            const box = modal.querySelector('.modal-spring-slam, .modal-spring-out');
            modal.classList.remove('hidden');
            if (box) {
                box.classList.remove('modal-spring-out', 'modal-spring-slam');
                void box.offsetWidth;
                box.classList.add('modal-spring-slam');
            }
        }

        function closeModalAnimated(modalId, onClosed) {
            const modal = document.getElementById(modalId);
            if (!modal || modal.classList.contains('hidden')) {
                if (typeof onClosed === 'function') onClosed();
                return;
            }
            if (modal.dataset.closeTimer) {
                clearTimeout(Number(modal.dataset.closeTimer));
                delete modal.dataset.closeTimer;
            }
            const box = modal.querySelector('.modal-spring-slam, .modal-spring-out');
            if (!box) {
                modal.classList.add('hidden');
                if (typeof onClosed === 'function') onClosed();
                return;
            }
            box.classList.remove('modal-spring-slam', 'modal-spring-out');
            void box.offsetWidth;
            box.classList.add('modal-spring-out');

            modal.dataset.closeTimer = String(setTimeout(() => {
                delete modal.dataset.closeTimer;
                modal.classList.add('hidden');
                box.classList.remove('modal-spring-out');
                box.classList.add('modal-spring-slam');
                if (typeof onClosed === 'function') onClosed();
            }, 155));
        }

        function replayBoxSnap(el) {
            if (!el) return;
            el.classList.remove('box-snap-in');
            void el.offsetWidth;
            el.classList.add('box-snap-in');
        }

        // Open & Close Logout Confirmation Modal
        function openLogoutModal() {
            openModalAnimated('logout-confirm-modal');
        }

        function closeLogoutModal() {
            closeModalAnimated('logout-confirm-modal');
        }

        // Modal Konfirmasi Aksi Kelompok
        function openKelompokConfirmFromEl(btn) {
            pendingKelompokConfirmFormId = btn.dataset.form || null;
            const titleEl = document.getElementById('kelompok-confirm-title');
            const descEl = document.getElementById('kelompok-confirm-desc');
            const confirmBtn = document.getElementById('kelompok-confirm-btn');
            const isDanger = btn.dataset.danger !== '0';

            if (titleEl) titleEl.textContent = btn.dataset.title || 'Konfirmasi Aksi?';
            if (descEl) descEl.textContent = btn.dataset.message || '';
            if (confirmBtn) {
                confirmBtn.textContent = btn.dataset.confirm || 'Ya, Lanjutkan';
                confirmBtn.className = isDanger
                    ? 'btn-tactile w-full py-2.5 bg-[#CA2C2A] hover:bg-[#901C1A] text-white text-xs font-black uppercase tracking-wider border-2 border-black shadow-[3px_3px_0px_0px_#000000] cursor-pointer'
                    : 'btn-tactile w-full py-2.5 bg-amber-300 hover:bg-amber-400 text-black text-xs font-black uppercase tracking-wider border-2 border-black shadow-[3px_3px_0px_0px_#000000] cursor-pointer';
            }

            openModalAnimated('kelompok-confirm-modal');
        }

        function closeKelompokConfirmModal() {
            pendingKelompokConfirmFormId = null;
            closeModalAnimated('kelompok-confirm-modal', flushPendingKelompokUpdate);
        }

        function submitKelompokConfirmModal() {
            if (pendingKelompokConfirmFormId) {
                const targetForm = document.getElementById(pendingKelompokConfirmFormId);
                if (targetForm) {
                    targetForm.submit();
                }
            }
        }

        // Switch Tab Active State (Beranda, Scan, PointKu, Leaderboard, Kelompok, Profil)
        function switchTab(tabId) {
            const panels = ['beranda', 'scan', 'poin', 'leaderboard', 'kelompok', 'kuesioner', 'profil'];
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
                    if (p === tabId) {
                        btn.className = "nav-item-active btn-tactile w-full text-left px-3.5 py-2.5 border-2 border-black font-black text-xs uppercase tracking-wider flex items-center gap-2.5 cursor-pointer";
                    } else {
                        btn.className = "btn-tactile w-full text-left px-3.5 py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black font-black text-xs uppercase tracking-wider flex items-center gap-2.5 shadow-[2px_2px_0px_0px_#000000] cursor-pointer";
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

            // Jika meninggalkan tab scan/kelompok, matikan kamera masing-masing
            if (tabId !== 'scan') {
                stopDashScanner();
            }
            if (tabId !== 'kelompok') {
                stopKelompokQrScanner();
            } else {
                renderKelompokQr();
            }

            // Replay animasi tactile snap & podium ketika berpindah tab
            const activeSection = document.getElementById(`tab-panel-${tabId}`);
            replaySnapIn(activeSection);
            if (tabId === 'leaderboard') {
                replayPodiumAnimations(document.getElementById(
                    currentLeaderboardMode === 'kelompok' ? 'lb-kelompok-podium' : 'lb-individu-podium'
                ));
            }

            // Update URL hash
            window.location.hash = tabId;
        }

        // Mode Leaderboard Mahasiswa dikunci ke Kelompok (perorangan di-hide)
        function switchLeaderboardMode(mode) {
            currentLeaderboardMode = 'kelompok';
            const indView = document.getElementById('lb-view-individu');
            const grpView = document.getElementById('lb-view-kelompok');
            const grpBtn = document.getElementById('lb-btn-kelompok');

            const activeCls = 'btn-tactile px-4 py-2 bg-[#1A467C] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer';

            if (indView && grpView) {
                indView.classList.add('hidden');
                grpView.classList.remove('hidden');
                replaySnapIn(grpView);
                replayPodiumAnimations(document.getElementById('lb-kelompok-podium'));
            }
            if (grpBtn) {
                grpBtn.className = activeCls;
            }
        }

        // Live Preview Gambar Upload Bersama (Profil Mahasiswa & Profil Kelompok)
        function previewImageFile(input, previewId, placeholderId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    const placeholder = document.getElementById(placeholderId);
                    if (preview) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewAvatar(input) {
            previewImageFile(input, 'avatar-preview', 'avatar-placeholder');
        }

        function previewKelompokFoto(input) {
            previewImageFile(input, 'kelompok-foto-preview', 'kelompok-foto-placeholder');
            const hapusCb = document.getElementById('edit-hapus-foto');
            if (hapusCb && input.files && input.files[0]) {
                hapusCb.checked = false;
            }
        }

        // Render QR Code Undangan Kelompok untuk Ketua
        function renderKelompokQr() {
            const qrContainer = document.getElementById('kelompok-qr-canvas');
            if (!qrContainer || !kelompokInviteUrl || qrContainer.dataset.rendered === '1') {
                return;
            }
            if (typeof QRCode === 'undefined') {
                return;
            }
            qrContainer.innerHTML = '';
            new QRCode(qrContainer, {
                text: kelompokInviteUrl,
                width: 164,
                height: 164,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
            });
            qrContainer.dataset.rendered = '1';
        }

        // Toggle Inline Form Edit Info Kelompok
        function toggleEditKelompok() {
            const box = document.getElementById('kelompok-edit-box');
            if (!box) return;
            const opening = box.classList.contains('hidden');
            box.classList.toggle('hidden');
            if (opening) {
                replayBoxSnap(box);
            }
            flushPendingKelompokUpdate();
        }

        // Salin Link / Kode Undangan Kelompok
        async function copyKelompokText(inputId, btnId) {
            const input = document.getElementById(inputId);
            const btn = document.getElementById(btnId);
            if (!input) return;

            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(input.value);
                } else {
                    input.select();
                    document.execCommand('copy');
                }
            } catch (e) {
                input.select();
                document.execCommand('copy');
            }

            if (btn) {
                const orig = btn.textContent;
                btn.textContent = 'Tersalin!';
                setTimeout(() => {
                    btn.textContent = orig;
                }, 1800);
            }
        }

        // Filter & Render Kandidat Anggota untuk Diundang ke Inbox
        function filterKelompokKandidat() {
            const container = document.getElementById('invite-candidate-list');
            if (!container) return;

            const keyword = (document.getElementById('invite-search-input')?.value || '').trim().toLowerCase();
            const filtered = kelompokCandidates.filter(item => {
                if (!keyword) return true;
                return (item.nama || '').toLowerCase().includes(keyword)
                    || (item.nim || '').toLowerCase().includes(keyword)
                    || (item.kelas || '').toLowerCase().includes(keyword);
            }).slice(0, 25);

            container.innerHTML = '';

            if (filtered.length === 0) {
                const empty = document.createElement('div');
                empty.className = 'p-4 text-center text-xs font-bold text-gray-500';
                empty.textContent = kelompokCandidates.length === 0
                    ? 'Semua peserta sudah tergabung di kelompok.'
                    : 'Tidak ada mahasiswa yang cocok dengan pencarian.';
                container.appendChild(empty);
                return;
            }

            filtered.forEach(item => {
                const row = document.createElement('div');
                row.className = 'px-3 py-2.5 flex items-center justify-between gap-2 bg-white hover:bg-blue-50/40';

                const info = document.createElement('div');
                info.className = 'min-w-0 flex-1';

                const nameEl = document.createElement('p');
                nameEl.className = 'text-xs font-black text-gray-950 truncate';
                nameEl.textContent = item.nama;

                const metaEl = document.createElement('p');
                metaEl.className = 'text-[10px] font-mono font-bold text-gray-600';
                metaEl.textContent = `${item.nim} • ${item.kelas}`;

                info.appendChild(nameEl);
                info.appendChild(metaEl);
                row.appendChild(info);

                if (item.already_invited) {
                    const invitedBadge = document.createElement('span');
                    invitedBadge.className = 'px-2 py-1 bg-amber-100 border border-black text-[10px] font-black uppercase text-amber-900 shrink-0';
                    invitedBadge.textContent = 'Terundang';
                    row.appendChild(invitedBadge);
                } else {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = kelompokInviteAction;
                    form.className = 'shrink-0';

                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = csrfToken;

                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'pendaftaran_id';
                    idInput.value = String(item.id);

                    const submitBtn = document.createElement('button');
                    submitBtn.type = 'submit';
                    submitBtn.className = 'btn-tactile px-2.5 py-1 bg-[#1A467C] hover:bg-[#123259] text-white border border-black text-[10px] font-black uppercase cursor-pointer';
                    submitBtn.textContent = 'Undang';

                    form.appendChild(tokenInput);
                    form.appendChild(idInput);
                    form.appendChild(submitBtn);
                    row.appendChild(form);
                }

                container.appendChild(row);
            });
        }

        // Kamera Scanner Gabung Kelompok via QR Ketua
        async function startKelompokQrScanner() {
            isKelompokQrSubmitted = false;
            document.getElementById('kelompok-camera-trigger')?.classList.add('hidden');
            const camBox = document.getElementById('kelompok-camera-box');
            camBox?.classList.remove('hidden');
            replayBoxSnap(camBox);

            if (!kelompokQrScanner) {
                kelompokQrScanner = new Html5Qrcode("kelompok-qr-reader");
            }

            const config = {
                fps: 10,
                qrbox: { width: 200, height: 200 },
                aspectRatio: 1.0
            };

            try {
                await kelompokQrScanner.start(
                    { facingMode: "environment" },
                    config,
                    onKelompokQrSuccess,
                    () => {}
                );
            } catch (envErr) {
                try {
                    await kelompokQrScanner.start(
                        { facingMode: "user" },
                        config,
                        onKelompokQrSuccess,
                        () => {}
                    );
                } catch (userErr) {
                    alert("Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan di browser.");
                    stopKelompokQrScanner();
                }
            }
        }

        async function stopKelompokQrScanner() {
            if (kelompokQrScanner && kelompokQrScanner.isScanning) {
                try {
                    await kelompokQrScanner.stop();
                } catch (e) {}
            }
            document.getElementById('kelompok-camera-box')?.classList.add('hidden');
            document.getElementById('kelompok-camera-trigger')?.classList.remove('hidden');
            flushPendingKelompokUpdate();
        }

        async function onKelompokQrSuccess(decodedText) {
            if (isKelompokQrSubmitted) return;
            isKelompokQrSubmitted = true;

            await stopKelompokQrScanner();

            const joinInput = document.getElementById('kelompok-join-input');
            const joinForm = document.getElementById('kelompok-join-form');
            if (joinInput && joinForm) {
                joinInput.value = (decodedText || '').trim();
                joinForm.submit();
            }
        }

        // Cek URL Hash, Query Param, atau Validation Error saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            renderKelompokQr();
            filterKelompokKandidat();

            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            const hash = window.location.hash.replace('#', '');
            const validTabs = ['beranda', 'scan', 'poin', 'leaderboard', 'kelompok', 'kuesioner', 'profil'];

            if (validTabs.includes(tabParam)) {
                switchTab(tabParam);
            } else if (validTabs.includes(hash)) {
                switchTab(hash);
            } else if (hasKelompokValidationError) {
                switchTab('kelompok');
            } else if (hasKuesionerValidationError) {
                switchTab('kuesioner');
            }

            const kelompokSection = document.getElementById('tab-panel-kelompok');
            kelompokSection?.addEventListener('focusout', () => {
                setTimeout(flushPendingKelompokUpdate, 150);
            });

            startRealtimePolling();
        });

        function switchKuesionerPertemuan(pNum) {
            document.querySelectorAll('.kuesioner-pertemuan-panel').forEach(el => {
                el.classList.add('hidden');
            });
            document.getElementById('kuesioner-pertemuan-panel-' + pNum)?.classList.remove('hidden');

            document.querySelectorAll('[id^="kuesioner-ptab-"]').forEach(btn => {
                btn.classList.remove('bg-[#1A467C]', 'text-white', 'shadow-[2px_2px_0px_0px_#000000]');
                btn.classList.add('bg-white', 'hover:bg-gray-100', 'text-black');
            });
            const activeBtn = document.getElementById('kuesioner-ptab-' + pNum);
            if (activeBtn) {
                activeBtn.classList.remove('bg-white', 'hover:bg-gray-100', 'text-black');
                activeBtn.classList.add('bg-[#1A467C]', 'text-white', 'shadow-[2px_2px_0px_0px_#000000]');
            }
        }

        function toggleEditKuesioner(pNum, isEditing) {
            const summaryBox = document.getElementById('kuesioner-summary-box-' + pNum);
            const formBox = document.getElementById('kuesioner-form-box-' + pNum);
            if (!formBox) return;

            if (isEditing) {
                summaryBox?.classList.add('hidden');
                formBox.classList.remove('hidden');
            } else {
                formBox.classList.add('hidden');
                summaryBox?.classList.remove('hidden');
            }
        }

        // Buka Kamera Scanner di Tab Scan
        async function startDashScanner() {
            document.getElementById('dash-camera-trigger').classList.add('hidden');
            const dashCamBox = document.getElementById('dash-camera-box');
            dashCamBox.classList.remove('hidden');
            replayBoxSnap(dashCamBox);

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

            openModalAnimated('dash-result-modal');
        }

        function closeDashResultModal() {
            closeModalAnimated('dash-result-modal', syncDashboardNow);
        }

        // Replay animasi tab snap-in + stagger kartu di dalam tab secara halus saat pindah tab/mode
        function replaySnapIn(el) {
            if (!el) return;
            el.classList.remove('tab-snap-in');
            void el.offsetWidth;
            el.classList.add('tab-snap-in');
            el.querySelectorAll('.mhs-snap-1, .mhs-snap-2, .mhs-snap-3, .mhs-snap-4').forEach(node => {
                node.style.animation = 'none';
                void node.offsetWidth;
                node.style.animation = '';
            });
        }

        // Replay animasi batang podium tumbuh dari bawah
        function replayPodiumAnimations(containerEl) {
            if (!containerEl) return;
            const bars = containerEl.querySelectorAll('.podium-bar');
            bars.forEach(bar => {
                bar.style.animation = 'none';
                void bar.offsetHeight;
                bar.style.animation = '';
            });
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, ch => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
            }[ch]));
        }

        function updateStatText(id, nextText, flashElSelector = null) {
            const el = document.getElementById(id);
            if (!el) return;
            const target = String(nextText);
            if (el.textContent.trim() !== target.trim()) {
                el.textContent = target;
                el.classList.remove('stat-pop');
                void el.offsetWidth;
                el.classList.add('stat-pop');

                const flashTarget = flashElSelector ? el.closest(flashElSelector) : el.closest('.card-tactile');
                if (flashTarget) {
                    flashTarget.classList.remove('realtime-flash');
                    void flashTarget.offsetWidth;
                    flashTarget.classList.add('realtime-flash');
                }
            }
        }

        function updateInboxBadges(count) {
            const num = Number(count) || 0;
            const sideBadge = document.getElementById('rt-side-inbox-badge');
            const botBadge = document.getElementById('rt-bot-inbox-badge');
            [sideBadge, botBadge].forEach(badge => {
                if (!badge) return;
                badge.textContent = String(num);
                badge.classList.toggle('hidden', num <= 0);
            });
        }

        function renderPointHistories(histories) {
            const container = document.getElementById('rt-poin-history-list');
            if (!container) return;

            const items = Array.isArray(histories) ? histories : [];
            const nextSig = items.map(h => `${h.id}:${h.jumlah}`).join('|');
            if (container.dataset.histSig === nextSig) {
                return;
            }
            const hadInitial = container.dataset.histSig !== undefined;
            container.dataset.histSig = nextSig;

            if (items.length === 0) {
                container.innerHTML = '<div class="p-8 text-center text-xs font-bold text-gray-500">Belum ada riwayat point.</div>';
                return;
            }

            container.innerHTML = items.map(h => {
                const isAdd = h.tipe === 'tambah';
                const badgeCls = isAdd ? 'bg-[#15803d] text-white' : 'bg-[#CA2C2A] text-white';
                const prefix = isAdd ? '+' : '-';
                const keteranganHtml = h.keterangan
                    ? `<p class="text-xs font-semibold text-gray-600 mt-0.5 break-words">${escapeHtml(h.keterangan)}</p>`
                    : '';

                return `
                    <div class="px-4 sm:px-5 py-3.5 hover:bg-blue-50/30 transition flex items-start justify-between gap-3 ${hadInitial ? 'realtime-flash' : ''}">
                        <div class="flex items-start gap-3 min-w-0">
                            <span class="w-8 h-8 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_#000000] ${badgeCls}">
                                ${prefix}${Number(h.jumlah) || 0}
                            </span>
                            <div class="min-w-0">
                                <p class="font-black text-xs sm:text-sm uppercase text-gray-950 leading-tight">${escapeHtml(h.kategori)}</p>
                                ${keteranganHtml}
                            </div>
                        </div>
                        <span class="shrink-0 font-mono text-[10px] font-bold text-gray-400 pt-0.5">${escapeHtml(h.waktu || '-')}</span>
                    </div>
                `;
            }).join('');
        }

        function getRankBadgeCls(rankNum) {
            if (rankNum === 1) return 'w-8 h-8 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 bg-amber-300 text-black';
            if (rankNum === 2) return 'w-8 h-8 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 bg-sky-200 text-black';
            if (rankNum === 3) return 'w-8 h-8 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 bg-orange-200 text-black';
            return 'w-8 h-8 border-2 border-black font-mono font-black text-xs flex items-center justify-center shrink-0 bg-gray-100 text-gray-800';
        }

        function getBarColorCls(rankNum) {
            if (rankNum === 1) return 'lb-bar-fill h-full bg-amber-400';
            if (rankNum === 2) return 'lb-bar-fill h-full bg-[#2A82C6]';
            if (rankNum === 3) return 'lb-bar-fill h-full bg-orange-400';
            return 'lb-bar-fill h-full bg-[#15803d]';
        }

        function updateAvatarBox(avatarEl, imgUrl, altLabel, fallbackInitials) {
            if (!avatarEl) return;
            const wantImg = Boolean(imgUrl);
            const curImg = avatarEl.querySelector('img');
            if (wantImg) {
                if (!curImg || curImg.getAttribute('src') !== imgUrl) {
                    avatarEl.innerHTML = `<img src="${escapeHtml(imgUrl)}" alt="${escapeHtml(altLabel)}" class="w-full h-full object-cover">`;
                }
            } else if (curImg || avatarEl.textContent.trim() !== fallbackInitials) {
                avatarEl.textContent = fallbackInitials;
            }
        }

        function getDomTopThreeSig(listId) {
            const list = document.getElementById(listId);
            if (!list) return '';
            return Array.from(list.querySelectorAll('[data-lb-key]')).slice(0, 3)
                .map(el => `${el.dataset.lbKey}:${el.dataset.pts || 0}:${el.dataset.fotoUrl || ''}`)
                .join('|');
        }

        function getJsonTopThreeSig(items, prefix) {
            return (items || []).slice(0, 3)
                .map(item => `${prefix}-${item.id}:${Number(item.total_point) || 0}:${(prefix === 'mhs' ? item.avatar_url : item.foto_url) || ''}`)
                .join('|');
        }

        function renderPodiumSlots(containerId, items, type, forceAnimate = false) {
            const container = document.getElementById(containerId);
            if (!container) return;

            const list = Array.isArray(items) ? items : [];
            const slots = [
                { rank: 2, barBg: 'bg-sky-200', barHeight: 'h-28 sm:h-36', emptyHeight: 'h-24 sm:h-32', entry: list[1] || null },
                { rank: 1, barBg: 'bg-amber-300', barHeight: 'h-40 sm:h-48', emptyHeight: 'h-32 sm:h-40', entry: list[0] || null },
                { rank: 3, barBg: 'bg-orange-200', barHeight: 'h-20 sm:h-28', emptyHeight: 'h-16 sm:h-24', entry: list[2] || null },
            ];

            container.innerHTML = slots.map(pod => {
                const entry = pod.entry;
                if (!entry) {
                    return `
                        <div class="flex flex-col items-center">
                            <div class="w-full ${pod.emptyHeight} bg-gray-100/70 border-t-2 border-x-2 border-dashed border-gray-300 flex items-center justify-center">
                                <span class="font-mono font-black text-base text-gray-400">#${pod.rank}</span>
                            </div>
                        </div>
                    `;
                }

                const avatarSizeCls = pod.rank === 1
                    ? 'w-11 h-11 sm:w-14 sm:h-14 text-sm sm:text-base'
                    : 'w-9 h-9 sm:w-12 sm:h-12 text-xs sm:text-sm';

                if (type === 'individu') {
                    const avatarInner = entry.avatar_url
                        ? `<img src="${escapeHtml(entry.avatar_url)}" alt="Avatar ${escapeHtml(entry.nama)}" class="w-full h-full object-cover">`
                        : escapeHtml(entry.initials || 'IF');
                    const meBadge = entry.is_me
                        ? '<span class="absolute -top-2 -right-2 bg-[#15803d] text-white text-[8px] font-black uppercase px-1 py-0.2 border border-black">KAMU</span>'
                        : '';

                    return `
                        <div class="flex flex-col items-center podium-col">
                            <div class="flex flex-col items-center text-center mb-2.5 w-full">
                                <div class="relative podium-avatar">
                                    <div class="${avatarSizeCls} bg-white text-[#1A467C] border-2 border-black flex items-center justify-center font-black overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                                        ${avatarInner}
                                    </div>
                                    ${meBadge}
                                </div>
                                <h5 class="font-black text-[10px] sm:text-xs text-gray-950 uppercase truncate max-w-full mt-1.5 px-0.5" title="${escapeHtml(entry.nama)}">
                                    ${escapeHtml(entry.nama)}
                                </h5>
                                <span class="font-mono text-[9px] sm:text-[10px] font-bold text-[#1A467C]">
                                    ${escapeHtml(entry.kelas)}
                                </span>
                                <div class="mt-1.5 bg-white border-2 border-black px-2 py-0.5 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1">
                                    <span class="font-mono font-black text-xs sm:text-sm text-gray-950 leading-none">${Number(entry.total_point) || 0}</span>
                                    <span class="text-[8px] sm:text-[9px] font-black uppercase text-[#15803d] leading-none">PTS</span>
                                </div>
                            </div>
                            <div class="podium-bar podium-bar-${pod.rank} w-full ${pod.barHeight} ${pod.barBg} border-t-2 border-x-2 border-black shadow-[3px_0px_0px_0px_#000000] flex flex-col items-center justify-center p-2 relative">
                                <span class="font-mono font-black text-xl sm:text-3xl text-black/85 leading-none">#${pod.rank}</span>
                            </div>
                        </div>
                    `;
                }

                const myTeamBadge = entry.is_my_team
                    ? '<span class="absolute -top-2 -right-3 bg-[#15803d] text-white text-[8px] font-black uppercase px-1 py-0.2 border border-black">TIMMU</span>'
                    : '';
                const grpAvatarInner = entry.foto_url
                    ? `<img src="${escapeHtml(entry.foto_url)}" alt="Foto ${escapeHtml(entry.nama_kelompok)}" class="w-full h-full object-cover">`
                    : escapeHtml(entry.initials || 'KL');

                return `
                    <div class="flex flex-col items-center podium-col">
                        <div class="flex flex-col items-center text-center mb-2.5 w-full">
                            <div class="relative podium-avatar">
                                <div class="${avatarSizeCls} bg-[#1A467C] text-white border-2 border-black flex items-center justify-center font-black overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                                    ${grpAvatarInner}
                                </div>
                                ${myTeamBadge}
                            </div>
                            <h5 class="font-black text-[10px] sm:text-xs text-gray-950 uppercase truncate max-w-full mt-1.5 px-0.5" title="${escapeHtml(entry.nama_kelompok)}">
                                ${escapeHtml(entry.nama_kelompok)}
                            </h5>
                            <span class="font-mono text-[9px] sm:text-[10px] font-bold text-[#1A467C]">
                                ${Number(entry.jumlah_anggota) || 0} Anggota
                            </span>
                            <div class="mt-1.5 bg-white border-2 border-black px-2 py-0.5 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1">
                                <span class="font-mono font-black text-xs sm:text-sm text-gray-950 leading-none">${Number(entry.total_point) || 0}</span>
                                <span class="text-[8px] sm:text-[9px] font-black uppercase text-[#15803d] leading-none">PTS</span>
                            </div>
                        </div>
                        <div class="podium-bar podium-bar-${pod.rank} w-full ${pod.barHeight} ${pod.barBg} border-t-2 border-x-2 border-black shadow-[3px_0px_0px_0px_#000000] flex flex-col items-center justify-center p-2">
                            <span class="font-mono font-black text-xl sm:text-3xl text-black/85 leading-none">#${pod.rank}</span>
                        </div>
                    </div>
                `;
            }).join('');

            if (forceAnimate) {
                replayPodiumAnimations(container);
            }
        }

        function createLeaderboardRowEl(item, type, maxPoint) {
            const rankNum = Number(item.rank) || 1;
            const pts = Number(item.total_point) || 0;
            const pctWidth = pts > 0 ? Math.max(8, Math.round((pts / maxPoint) * 100)) : 0;
            const key = type === 'individu' ? `mhs-${item.id}` : `grp-${item.id}`;
            const highlightCls = (type === 'individu' ? item.is_me : item.is_my_team)
                ? 'bg-amber-50/90 border-l-4 border-l-[#1A467C]'
                : 'hover:bg-blue-50/40';

            const row = document.createElement('div');
            row.dataset.lbKey = key;
            row.dataset.rank = String(rankNum);
            row.dataset.pts = String(pts);
            row.dataset.fotoUrl = (type === 'individu' ? item.avatar_url : item.foto_url) || '';
            row.className = `lb-row px-4 sm:px-5 py-3.5 ${highlightCls} flex items-center justify-between gap-3 tab-snap-in`;

            if (type === 'individu') {
                const avatarHtml = item.avatar_url
                    ? `<img src="${escapeHtml(item.avatar_url)}" alt="Avatar ${escapeHtml(item.nama)}" class="w-full h-full object-cover">`
                    : escapeHtml(item.initials || 'IF');
                const meBadgeHtml = item.is_me
                    ? '<span data-role="my-badge" class="bg-[#1A467C] text-white text-[9px] font-black uppercase px-1.5 py-0.2 border border-black shrink-0">KAMU</span>'
                    : '';

                row.innerHTML = `
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <span data-role="rank-badge" class="${getRankBadgeCls(rankNum)}">#${rankNum}</span>
                        <div data-role="avatar" class="w-9 h-9 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden">
                            ${avatarHtml}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div data-role="title-wrap" class="flex items-center gap-1.5">
                                <p data-role="name" class="font-black text-xs sm:text-sm text-gray-950 truncate">${escapeHtml(item.nama)}</p>
                                ${meBadgeHtml}
                            </div>
                            <p data-role="submeta" class="font-mono text-[10px] sm:text-[11px] font-bold text-[#1A467C]">${escapeHtml(item.kelas)}</p>
                            <div class="mt-1.5 w-full max-w-md h-2.5 bg-gray-100 border border-black overflow-hidden">
                                <div data-role="bar-fill" class="${getBarColorCls(rankNum)}" style="width: ${pctWidth}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-100 border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1 shrink-0">
                        <span data-role="pts-text" class="font-mono font-black text-xs sm:text-sm text-[#15803d] leading-none">${pts}</span>
                        <span class="text-[9px] sm:text-[10px] font-black uppercase text-[#15803d] leading-none">PTS</span>
                    </div>
                `;
            } else {
                const teamBadgeHtml = item.is_my_team
                    ? '<span data-role="my-badge" class="bg-[#15803d] text-white text-[9px] font-black uppercase px-1.5 py-0.2 border border-black shrink-0">TIM KAMU</span>'
                    : '';
                const grpAvatarHtml = item.foto_url
                    ? `<img src="${escapeHtml(item.foto_url)}" alt="Foto ${escapeHtml(item.nama_kelompok)}" class="w-full h-full object-cover">`
                    : escapeHtml(item.initials || 'KL');

                row.innerHTML = `
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <span data-role="rank-badge" class="${getRankBadgeCls(rankNum)}">#${rankNum}</span>
                        <div data-role="avatar" class="w-9 h-9 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden">
                            ${grpAvatarHtml}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div data-role="title-wrap" class="flex items-center gap-1.5">
                                <p data-role="name" class="font-black text-xs sm:text-sm text-gray-950 truncate">${escapeHtml(item.nama_kelompok)}</p>
                                ${teamBadgeHtml}
                            </div>
                            <p data-role="submeta" class="font-mono text-[10px] sm:text-[11px] font-bold text-gray-600">
                                Ketua: <span class="text-[#1A467C]">${escapeHtml(item.ketua_nama)}</span> • ${Number(item.jumlah_anggota) || 0} Anggota
                            </p>
                            <div class="mt-1.5 w-full max-w-md h-2.5 bg-gray-100 border border-black overflow-hidden">
                                <div data-role="bar-fill" class="${getBarColorCls(rankNum)}" style="width: ${pctWidth}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-100 border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000000] inline-flex items-baseline gap-1 shrink-0">
                        <span data-role="pts-text" class="font-mono font-black text-xs sm:text-sm text-[#15803d] leading-none">${pts}</span>
                        <span class="text-[9px] sm:text-[10px] font-black uppercase text-[#15803d] leading-none">PTS</span>
                    </div>
                `;
            }

            return row;
        }

        function patchLeaderboardRowEl(row, item, type, maxPoint) {
            const rankNum = Number(item.rank) || 1;
            const pts = Number(item.total_point) || 0;
            const pctWidth = pts > 0 ? Math.max(8, Math.round((pts / maxPoint) * 100)) : 0;
            const nextFotoUrl = (type === 'individu' ? item.avatar_url : item.foto_url) || '';
            const prevRank = Number(row.dataset.rank || 0);
            const prevPts = Number(row.dataset.pts || 0);
            const changed = prevRank !== rankNum || prevPts !== pts || (row.dataset.fotoUrl || '') !== nextFotoUrl;

            row.dataset.rank = String(rankNum);
            row.dataset.pts = String(pts);
            row.dataset.fotoUrl = nextFotoUrl;

            const isHighlighted = type === 'individu' ? Boolean(item.is_me) : Boolean(item.is_my_team);
            row.className = `lb-row px-4 sm:px-5 py-3.5 ${isHighlighted ? 'bg-amber-50/90 border-l-4 border-l-[#1A467C]' : 'hover:bg-blue-50/40'} flex items-center justify-between gap-3`;

            const rankBadge = row.querySelector('[data-role="rank-badge"]');
            if (rankBadge) {
                rankBadge.className = getRankBadgeCls(rankNum);
                rankBadge.textContent = `#${rankNum}`;
            }

            const barFill = row.querySelector('[data-role="bar-fill"]');
            if (barFill) {
                barFill.className = getBarColorCls(rankNum);
                barFill.style.width = `${pctWidth}%`;
            }

            const ptsText = row.querySelector('[data-role="pts-text"]');
            if (ptsText && ptsText.textContent.trim() !== String(pts)) {
                ptsText.textContent = String(pts);
            }

            if (type === 'kelompok') {
                updateAvatarBox(
                    row.querySelector('[data-role="avatar"]'),
                    item.foto_url,
                    `Foto ${item.nama_kelompok || ''}`,
                    item.initials || 'KL'
                );

                const nameEl = row.querySelector('[data-role="name"]');
                if (nameEl) nameEl.textContent = item.nama_kelompok || '';

                const subEl = row.querySelector('[data-role="submeta"]');
                if (subEl) {
                    subEl.innerHTML = `Ketua: <span class="text-[#1A467C]">${escapeHtml(item.ketua_nama)}</span> • ${Number(item.jumlah_anggota) || 0} Anggota`;
                }

                const titleWrap = row.querySelector('[data-role="title-wrap"]');
                if (titleWrap) {
                    let badge = titleWrap.querySelector('[data-role="my-badge"]');
                    if (isHighlighted && !badge) {
                        badge = document.createElement('span');
                        badge.dataset.role = 'my-badge';
                        badge.className = 'bg-[#15803d] text-white text-[9px] font-black uppercase px-1.5 py-0.2 border border-black shrink-0';
                        badge.textContent = 'TIM KAMU';
                        titleWrap.appendChild(badge);
                    } else if (!isHighlighted && badge) {
                        badge.remove();
                    }
                }
            } else {
                const nameEl = row.querySelector('[data-role="name"]');
                if (nameEl) nameEl.textContent = item.nama || '';

                const subEl = row.querySelector('[data-role="submeta"]');
                if (subEl) subEl.textContent = item.kelas || '';

                updateAvatarBox(
                    row.querySelector('[data-role="avatar"]'),
                    item.avatar_url,
                    `Avatar ${item.nama || ''}`,
                    item.initials || 'IF'
                );
            }

            if (changed) {
                row.classList.remove('realtime-flash');
                void row.offsetWidth;
                row.classList.add('realtime-flash');
            }
        }

        // Animasi FLIP (First, Last, Invert, Play) saat urutan ranking leaderboard berubah
        function updateLeaderboardWithFlip(containerId, items, type) {
            const container = document.getElementById(containerId);
            if (!container) return;

            const list = Array.isArray(items) ? items : [];
            const belowPodium = list.slice(3);
            const isVisible = container.offsetParent !== null;
            const firstPositions = new Map();
            const existingMap = new Map();

            container.querySelectorAll('[data-lb-key]').forEach(el => {
                const key = el.dataset.lbKey;
                existingMap.set(key, el);
                if (isVisible) {
                    firstPositions.set(key, el.getBoundingClientRect().top);
                }
            });

            if (belowPodium.length === 0) {
                container.innerHTML = '';
                return;
            }

            const maxPoint = Math.max(1, ...list.map(i => Number(i.total_point) || 0));
            const nextKeys = new Set();
            const movedOrChanged = [];

            belowPodium.forEach(item => {
                const key = type === 'individu' ? `mhs-${item.id}` : `grp-${item.id}`;
                nextKeys.add(key);

                let rowEl = existingMap.get(key);
                if (rowEl) {
                    patchLeaderboardRowEl(rowEl, item, type, maxPoint);
                } else {
                    rowEl = createLeaderboardRowEl(item, type, maxPoint);
                }

                container.appendChild(rowEl);
                movedOrChanged.push({ key, rowEl });
            });

            existingMap.forEach((el, key) => {
                if (!nextKeys.has(key)) {
                    el.remove();
                }
            });

            if (!isVisible) return;

            const toAnimate = [];
            movedOrChanged.forEach(({ key, rowEl }) => {
                const firstTop = firstPositions.get(key);
                if (firstTop === undefined) return;
                const lastTop = rowEl.getBoundingClientRect().top;
                const deltaY = firstTop - lastTop;
                if (Math.abs(deltaY) > 1) {
                    rowEl.style.transition = 'none';
                    rowEl.style.transform = `translateY(${deltaY}px)`;
                    toAnimate.push(rowEl);
                }
            });

            if (toAnimate.length > 0) {
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        toAnimate.forEach(rowEl => {
                            rowEl.style.transition = 'transform 0.48s cubic-bezier(0.22, 1, 0.36, 1)';
                            rowEl.style.transform = '';
                            rowEl.addEventListener('transitionend', function cleanup() {
                                rowEl.style.transition = '';
                                rowEl.removeEventListener('transitionend', cleanup);
                            }, { once: true });
                        });
                    });
                });
            }
        }

        function isKelompokUiBusy() {
            const editBox = document.getElementById('kelompok-edit-box');
            if (editBox && !editBox.classList.contains('hidden')) {
                return true;
            }

            const cameraBox = document.getElementById('kelompok-camera-box');
            if (cameraBox && !cameraBox.classList.contains('hidden')) {
                return true;
            }

            const confirmModal = document.getElementById('kelompok-confirm-modal');
            if (confirmModal && !confirmModal.classList.contains('hidden')) {
                return true;
            }

            const section = document.getElementById('tab-panel-kelompok');
            const active = document.activeElement;
            if (section && active && section.contains(active) && ['INPUT', 'TEXTAREA', 'SELECT'].includes(active.tagName)) {
                return true;
            }

            return false;
        }

        function applyKelompokPayload(payload) {
            const section = document.getElementById('tab-panel-kelompok');
            if (!section || !payload || typeof payload.html !== 'string') return;

            const prevSearch = document.getElementById('invite-search-input')?.value || '';
            section.innerHTML = payload.html;

            kelompokInviteUrl = payload.invite_url || '';
            kelompokCandidates = Array.isArray(payload.candidates) ? payload.candidates : [];

            const nextSearchInput = document.getElementById('invite-search-input');
            if (nextSearchInput && prevSearch) {
                nextSearchInput.value = prevSearch;
            }

            filterKelompokKandidat();
            renderKelompokQr();

            if (!section.classList.contains('hidden')) {
                replaySnapIn(section);
            }
        }

        function flushPendingKelompokUpdate() {
            if (pendingKelompokPayload && !isKelompokUiBusy()) {
                const payload = pendingKelompokPayload;
                pendingKelompokPayload = null;
                applyKelompokPayload(payload);
            }
        }

        function applySyncPayload(data) {
            const stats = data.stats || {};

            updateStatText('rt-sidebar-point', `★ ${stats.total_point ?? 0} PTS`, '.card-tactile');
            updateStatText('rt-beranda-hadir', stats.total_hadir ?? 0);
            updateStatText('rt-beranda-persen', `${stats.persen_hadir ?? 0}%`);
            updateStatText('rt-beranda-point', stats.total_point ?? 0);
            updateStatText('rt-beranda-rank', `#${stats.my_rank ?? '-'}`);
            updateStatText('rt-beranda-total-peserta', `/${stats.total_peserta ?? 0}`);
            updateStatText('rt-scan-sesi', `Sesi ${stats.pertemuan_aktif ?? 1}`);
            updateStatText('rt-poin-total', stats.total_point ?? 0);
            updateStatText('rt-poin-rank', `#${stats.my_rank ?? '-'}`);
            updateStatText('rt-poin-total-peserta', `/${stats.total_peserta ?? 0}`);
            updateStatText('rt-poin-history-count', stats.history_count ?? 0);

            updateInboxBadges(stats.inbox_count ?? 0);

            const indBtn = document.getElementById('lb-btn-individu');
            const grpBtn = document.getElementById('lb-btn-kelompok');
            if (indBtn) indBtn.textContent = `Peserta (${stats.total_peserta ?? 0})`;
            if (grpBtn) grpBtn.textContent = `Kelompok (${stats.total_kelompok ?? 0})`;

            const indBadge = document.getElementById('lb-individu-total-badge');
            const grpBadge = document.getElementById('lb-kelompok-total-badge');
            if (indBadge) indBadge.textContent = `${stats.total_peserta ?? 0} Mahasiswa`;
            if (grpBadge) grpBadge.textContent = `${stats.total_kelompok ?? 0} Kelompok`;

            renderPointHistories(data.point_histories);

            // Update Leaderboard Individu (Podium + FLIP List)
            const indList = Array.isArray(data.individu_leaderboard) ? data.individu_leaderboard : [];
            const indPodiumCard = document.getElementById('lb-individu-podium-card');
            if (indPodiumCard) {
                indPodiumCard.classList.toggle('hidden', indList.length === 0);
            }
            const nextIndPodiumSig = getJsonTopThreeSig(indList, 'mhs');
            if (nextIndPodiumSig !== lastIndPodiumSig) {
                lastIndPodiumSig = nextIndPodiumSig;
                renderPodiumSlots('lb-individu-podium', indList, 'individu', true);
            }
            updateLeaderboardWithFlip('lb-individu-list', indList, 'individu');

            // Update Leaderboard Kelompok (Empty State + Podium + FLIP List)
            const grpList = Array.isArray(data.kelompok_leaderboard) ? data.kelompok_leaderboard : [];
            const grpEmpty = document.getElementById('lb-kelompok-empty');
            const grpContent = document.getElementById('lb-kelompok-content');
            if (grpEmpty && grpContent) {
                grpEmpty.classList.toggle('hidden', grpList.length > 0);
                grpContent.classList.toggle('hidden', grpList.length === 0);
            }
            const nextGrpPodiumSig = getJsonTopThreeSig(grpList, 'grp');
            if (nextGrpPodiumSig !== lastGrpPodiumSig) {
                lastGrpPodiumSig = nextGrpPodiumSig;
                renderPodiumSlots('lb-kelompok-podium', grpList, 'kelompok', true);
            }
            updateLeaderboardWithFlip('lb-kelompok-list', grpList, 'kelompok');

            // Update Tab Kelompok secara aman (antri jika sedang edit/ketik)
            if (data.kelompok) {
                if (isKelompokUiBusy()) {
                    pendingKelompokPayload = data.kelompok;
                } else {
                    pendingKelompokPayload = null;
                    applyKelompokPayload(data.kelompok);
                }
            }
        }

        async function syncDashboardNow() {
            if (isRealtimeSyncInFlight || isSubmittingScan) {
                return;
            }
            isRealtimeSyncInFlight = true;

            try {
                const url = `${dashboardSyncUrl}?sig=${encodeURIComponent(currentRealtimeSig)}`;
                const res = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    cache: 'no-store',
                });

                if (res.status === 401) {
                    const authErr = await res.json().catch(() => ({}));
                    if (realtimePollTimer) {
                        clearInterval(realtimePollTimer);
                    }
                    if (authErr.redirect) {
                        window.location.href = authErr.redirect;
                    }
                    return;
                }

                if (!res.ok) return;

                const payload = await res.json();
                const clockEl = document.getElementById('rt-sync-clock');
                if (clockEl) {
                    const now = new Date();
                    clockEl.textContent = now.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false,
                    });
                }

                if (payload && payload.changed) {
                    currentRealtimeSig = payload.signature || currentRealtimeSig;
                    applySyncPayload(payload);
                }
            } catch (e) {
                // Abaikan error jaringan sementara agar polling berikutnya tetap berjalan
            } finally {
                isRealtimeSyncInFlight = false;
            }
        }

        function startRealtimePolling() {
            lastIndPodiumSig = '';
            lastGrpPodiumSig = '';

            if (realtimePollTimer) {
                clearInterval(realtimePollTimer);
            }
            const pollIntervalMs = 10000 + Math.floor(Math.random() * 2000);
            realtimePollTimer = setInterval(() => {
                if (!document.hidden) {
                    syncDashboardNow();
                }
            }, pollIntervalMs);

            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    syncDashboardNow();
                }
            });
        }
    </script>

</body>
</html>
