<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Mahasiswa - POKJA HIMA IF</title>

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

    <!-- QRCode.js Library for Generating Ticket QR -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
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
            transform: translate(2px, 2px);
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

            <!-- Student Profile Badge -->
            <div class="bg-gray-50 border-2 border-black p-3.5 shadow-[3px_3px_0px_0px_#000000] space-y-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-[#2A82C6] text-white border border-black flex items-center justify-center font-black text-xs shadow-[1px_1px_0px_0px_#000000] shrink-0">
                        {{ strtoupper(substr($mahasiswa->nama, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <span class="text-[9px] font-black uppercase tracking-wider text-[#1A467C] block">MAHASISWA</span>
                        <h4 class="text-xs font-black text-gray-950 truncate uppercase leading-tight mt-0.5" title="{{ $mahasiswa->nama }}">
                            {{ $mahasiswa->nama }}
                        </h4>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-200 flex items-center justify-between font-mono text-[11px] font-bold text-gray-700">
                    <span>NIM: <strong class="text-gray-950">{{ $mahasiswa->nim }}</strong></span>
                    <span class="bg-blue-50 border border-black text-[#1A467C] px-1.5 py-0.2 font-black text-[10px]">{{ $mahasiswa->kelas }}</span>
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
                    <span>Ringkasan & Rekap</span>
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
                    id="side-nav-tiket"
                    onclick="switchTab('tiket')" 
                    class="btn-tactile w-full text-left px-3.5 py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black font-black text-xs uppercase tracking-wider flex items-center gap-2.5 shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    <svg class="w-4 h-4 text-[#1A467C] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span>Tiket & QR</span>
                </button>
            </nav>
        </div>

        <!-- Sidebar Bottom Logout -->
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

            <form action="{{ route('mahasiswa.logout') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="btn-tactile w-full py-2 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border-2 border-black font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] flex items-center justify-center gap-1.5 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar Akun</span>
                </button>
            </form>
        </div>

    </aside>

    <!-- ============================================================== -->
    <!-- MAIN CONTENT VIEWPORT                                          -->
    <!-- ============================================================== -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- Mobile Top Bar (Single clean header bar on mobile) -->
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

            <div class="bg-[#1A467C] border border-black px-2 py-0.5 text-white shadow-[1px_1px_0px_0px_#000000]">
                <span class="text-[9px] font-black uppercase tracking-wider">
                    Sesi {{ $pengaturan->pertemuan_aktif ?? 1 }}
                </span>
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

            <!-- ========================================================== -->
            <!-- TAB 1: BERANDA & REKAP KEHADIRAN                           -->
            <!-- ========================================================== -->
            <section id="tab-panel-beranda" class="tab-snap-in space-y-4 sm:space-y-6">

                <!-- Greeting Banner -->
                <div class="card-tactile bg-white border-2 border-black p-4 sm:p-6 shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000]">
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <span class="inline-block bg-[#1A467C] text-white text-[10px] font-black uppercase px-2 py-0.5 tracking-wider border border-black mb-1 shadow-[1px_1px_0px_0px_#000000]">
                                MAHASISWA
                            </span>
                            <h2 class="text-base sm:text-2xl font-black uppercase tracking-tight text-gray-950 truncate">
                                {{ $mahasiswa->nama }}
                            </h2>
                            <p class="text-xs font-mono font-bold text-gray-600 mt-0.5 sm:mt-1 truncate">
                                NIM: <span class="text-[#1A467C] font-black">{{ $mahasiswa->nim }}</span> • Kelas: <span class="bg-blue-50 border border-[#2A82C6] text-[#1A467C] px-1.5 py-0.2 text-xs font-black">{{ $mahasiswa->kelas }}</span>
                            </p>
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

                <!-- 3 Stat Cards Ringkas (Clean, Without Extra Badges) -->
                <div class="grid grid-cols-3 gap-2.5 sm:gap-4">
                    <div class="card-tactile bg-white border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000]">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-[#1A467C] block truncate">Total Hadir</span>
                        <h3 class="text-xl sm:text-3xl font-black text-gray-950 font-mono mt-1 leading-none">{{ $totalHadir }}<span class="text-xs sm:text-lg text-gray-500 font-bold">/7</span></h3>
                    </div>

                    <div class="card-tactile bg-white border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000]">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-green-800 block truncate">Persentase</span>
                        <h3 class="text-xl sm:text-3xl font-black text-green-700 font-mono mt-1 leading-none">{{ $persenHadir }}%</h3>
                    </div>

                    <div class="card-tactile bg-white border-2 border-black p-3 sm:p-4 shadow-[2px_2px_0px_0px_#000000] sm:shadow-[4px_4px_0px_0px_#000000]">
                        <span class="text-[9px] sm:text-xs font-black uppercase tracking-wider text-gray-700 block truncate">Sesi Aktif</span>
                        <h3 class="text-xl sm:text-3xl font-black text-[#1A467C] font-mono mt-1 leading-none">Sesi {{ $pengaturan->pertemuan_aktif ?? 1 }}</h3>
                    </div>
                </div>

                <!-- Rekapitulasi Kehadiran 7 Pertemuan -->
                <div class="card-tactile bg-white border-2 border-black p-4 sm:p-6 shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] space-y-3.5">
                    <div class="flex items-center justify-between border-b-2 border-black pb-2.5">
                        <div>
                            <h3 class="text-xs sm:text-base font-black uppercase text-[#1A467C]">
                                Rekapitulasi Kehadiran
                            </h3>
                            <p class="text-[10px] sm:text-xs text-gray-600 font-semibold mt-0.5">
                                Riwayat kehadiran Anda pada 7 pertemuan POKJA.
                            </p>
                        </div>
                        <span class="text-xs font-mono font-black bg-gray-100 border border-black px-2 py-0.5 shrink-0">
                            {{ $totalHadir }}/7 Hadir
                        </span>
                    </div>

                    <!-- Grid 7 Pertemuan (Responsive 2 / 4 / 7 Cols) -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2 sm:gap-3">
                        @for ($i = 1; $i <= 7; $i++)
                            @php
                                $itemPresensi = $presensiMap->get($i);
                                $isHadir = $itemPresensi && $itemPresensi->status === 'hadir';
                                $isCurrentSesi = (int) ($pengaturan->pertemuan_aktif ?? 1) === $i;
                            @endphp

                            <div class="p-2 sm:p-2.5 border-2 border-black text-center {{ $isHadir ? 'bg-green-50 shadow-[2px_2px_0px_0px_#15803d]' : 'bg-gray-50 shadow-[1px_1px_0px_0px_#000000]' }} {{ $isCurrentSesi ? 'ring-2 ring-[#2A82C6]' : '' }}">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[11px] font-black font-mono {{ $isCurrentSesi ? 'text-[#1A467C]' : 'text-gray-900' }}">
                                        P{{ $i }}
                                    </span>
                                    @if ($isHadir)
                                        <span class="text-[11px] font-black text-green-700">✓</span>
                                    @else
                                        <span class="text-[10px] font-mono text-gray-400">-</span>
                                    @endif
                                </div>

                                <div class="my-0.5">
                                    @if ($isHadir)
                                        <span class="inline-block bg-green-700 text-white text-[8px] sm:text-[9px] font-black uppercase px-1.5 py-0.2 border border-black">
                                            HADIR
                                        </span>
                                    @else
                                        <span class="inline-block bg-gray-200 text-gray-600 text-[8px] sm:text-[9px] font-bold uppercase px-1.5 py-0.2 border border-gray-400">
                                            BELUM
                                        </span>
                                    @endif
                                </div>

                                <span class="text-[9px] font-mono font-bold text-gray-500 block truncate mt-0.5">
                                    {{ $isHadir ? \Carbon\Carbon::parse($itemPresensi->waktu_presensi)->format('H:i') : '-' }}
                                </span>
                            </div>
                        @endfor
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

                    <!-- Locked Student Identity Card (Clean, No Extra Badges) -->
                    <div class="bg-blue-50 border-2 border-black p-3 space-y-0.5">
                        <span class="text-[9px] font-black uppercase text-gray-500 tracking-wider">Identitas Mahasiswa</span>
                        <h4 class="text-sm font-black uppercase text-gray-950">{{ $mahasiswa->nama }}</h4>
                        <p class="text-xs font-mono font-bold text-gray-700">
                            NIM: <span class="text-[#1A467C] font-black">{{ $mahasiswa->nim }}</span> • Kelas: <span class="font-black">{{ $mahasiswa->kelas }}</span>
                        </p>
                        <input type="hidden" id="dash-input-nim" value="{{ $mahasiswa->nim }}">
                    </div>

                    <!-- Status Sesi Panitia jika ditutup -->
                    @if (!($pengaturan->is_scan_aktif ?? false))
                        <div class="p-3 bg-red-50 border-2 border-black text-xs font-bold text-red-900 shadow-[2px_2px_0px_0px_#CA2C2A]">
                            Sesi presensi saat ini sedang DITUTUP oleh panitia di ruang kelas.
                        </div>
                    @endif

                    <!-- Tombol Buka Kamera (Clean Text: SCAN QR) -->
                    <div id="dash-camera-trigger">
                        <button 
                            type="button" 
                            id="dash-btn-start"
                            onclick="startDashScanner()"
                            class="btn-tactile w-full py-3.5 bg-[#2A82C6] hover:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="square" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>SCAN QR</span>
                        </button>
                    </div>

                    <!-- Viewport Kamera Scanner -->
                    <div id="dash-camera-box" class="hidden space-y-3">
                        <div class="relative bg-black border-2 border-black overflow-hidden shadow-[3px_3px_0px_0px_#000000]">
                            <div id="dash-qr-reader" class="w-full"></div>
                        </div>

                        <button 
                            type="button" 
                            onclick="stopDashScanner()"
                            class="btn-tactile w-full py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                        >
                            Tutup Kamera
                        </button>
                    </div>

                    <!-- Keterangan Minimalis Refresh 5 Detik -->
                    <p class="text-center text-xs font-semibold text-gray-500 pt-1">
                        QR di proyektor refresh otomatis tiap 5 detik.
                    </p>
                </div>

            </section>

            <!-- ========================================================== -->
            <!-- TAB 3: TIKET & QR IDENTITAS (Clean, Without Extra Headers) -->
            <!-- ========================================================== -->
            <section id="tab-panel-tiket" class="tab-snap-in hidden space-y-4 sm:space-y-6">

                <div class="card-tactile bg-white border-2 border-black p-4 sm:p-6 shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] space-y-4">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 items-center">
                        <!-- Data Rincian Mahasiswa -->
                        <div class="space-y-2.5">
                            <div class="border-b border-gray-200 pb-2">
                                <span class="text-[10px] font-bold text-gray-500 uppercase block">Nama Lengkap</span>
                                <h4 class="text-base font-black text-gray-950 uppercase">{{ $mahasiswa->nama }}</h4>
                            </div>

                            <div class="grid grid-cols-2 gap-2 border-b border-gray-200 pb-2">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase block">NIM Kampus</span>
                                    <span class="font-mono font-black text-sm text-[#1A467C]">{{ $mahasiswa->nim }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase block">Kelas</span>
                                    <span class="font-mono font-black text-sm text-gray-950">{{ $mahasiswa->kelas }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 border-b border-gray-200 pb-2">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase block">Tanggal Acara</span>
                                    <span class="font-bold text-xs text-gray-900">{{ $pengaturan->tanggal_acara ?? '13 Oktober 2026' }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase block">Ruangan</span>
                                    <span class="font-bold text-xs text-gray-900">{{ $pengaturan->lokasi_acara ?? 'Ruangan 105' }}</span>
                                </div>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold text-gray-500 uppercase block">No. WhatsApp</span>
                                <span class="font-mono font-bold text-xs text-gray-800">{{ $mahasiswa->no_telp }}</span>
                            </div>
                        </div>

                        <!-- QR Code Container -->
                        <div class="bg-gray-50 border-2 border-black p-4 text-center shadow-[2px_2px_0px_0px_#000000]">
                            <div id="qrcode-canvas" class="inline-block p-2 bg-white border-2 border-black shadow-[2px_2px_0px_0px_#000000]"></div>
                            <p class="font-mono text-xs font-black text-[#1A467C] tracking-wider mt-2">
                                {{ $mahasiswa->nim }}
                            </p>
                            <p class="text-[10px] font-semibold text-gray-500 mt-0.5">
                                Tunjukkan QR Code ini kepada panitia saat registrasi di lokasi.
                            </p>
                        </div>
                    </div>
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
            class="btn-tactile flex flex-col items-center gap-1 py-1 px-3 text-gray-600 cursor-pointer"
        >
            <div class="bottom-nav-icon w-8 h-8 rounded-none border border-black flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="square" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider">Scan</span>
        </button>

        <!-- Tab 3: Tiket -->
        <button 
            type="button" 
            id="bot-nav-tiket"
            onclick="switchTab('tiket')" 
            class="btn-tactile flex flex-col items-center gap-1 py-1 px-3 text-gray-600 cursor-pointer"
        >
            <div class="bottom-nav-icon w-8 h-8 rounded-none border border-black flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider">Tiket</span>
        </button>

        <!-- Tab 4: Keluar -->
        <form action="{{ route('mahasiswa.logout') }}" method="POST" class="inline">
            @csrf
            <button 
                type="submit" 
                class="btn-tactile flex flex-col items-center gap-1 py-1 px-3 text-[#CA2C2A] cursor-pointer"
            >
                <div class="w-8 h-8 rounded-none border border-black flex items-center justify-center bg-red-50 text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-wider">Keluar</span>
            </button>
        </form>

    </nav>

    <!-- ============================================================== -->
    <!-- RESULT MODAL POP-UP (Tactile Spring Feedback)                  -->
    <!-- ============================================================== -->
    <div id="dash-result-modal" class="hidden fixed inset-0 z-50 bg-black/60 p-4 flex items-center justify-center">
        <div class="modal-spring-slam w-full max-w-sm bg-white border-[3px] border-black p-5 sm:p-6 shadow-[8px_8px_0px_0px_#000000] space-y-4 text-center">
            
            <div id="dash-modal-icon-wrapper" class="w-14 h-14 mx-auto border-2 border-black flex items-center justify-center font-black text-2xl shadow-[3px_3px_0px_0px_#000000]"></div>

            <div>
                <span id="dash-modal-badge" class="inline-block text-[10px] font-black uppercase px-2 py-0.5 border border-black shadow-[1px_1px_0px_0px_#000000] mb-1"></span>
                <h3 id="dash-modal-title" class="text-base sm:text-lg font-black uppercase text-gray-950"></h3>
                <p id="dash-modal-desc" class="text-xs font-semibold text-gray-700 mt-1"></p>
            </div>

            <button 
                type="button" 
                onclick="closeDashResultModal()"
                class="btn-tactile w-full py-2.5 bg-[#2A82C6] hover:bg-[#1A467C] text-white text-xs font-black uppercase tracking-wider border-2 border-black shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
            >
                MENGERTI
            </button>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- JAVASCRIPT: TAB NAVIGATION, QR GENERATION, & LIVE SCANNER     -->
    <!-- ============================================================== -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let currentTab = 'beranda';
        let dashQrScanner = null;
        let isDashScanning = false;
        let isSubmittingScan = false;

        // Switch Tab Handler (Desktop Sidebar & Mobile Bottom Navigation)
        function switchTab(tabId) {
            currentTab = tabId;

            // Panels
            const panels = ['beranda', 'scan', 'tiket'];
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

            // Jika meninggalkan tab scan, matikan kamera
            if (tabId !== 'scan') {
                stopDashScanner();
            }

            // Update URL hash
            window.location.hash = tabId;
        }

        // Generate QR Tiket Resmi
        document.addEventListener('DOMContentLoaded', () => {
            const qrCanvas = document.getElementById('qrcode-canvas');
            if (qrCanvas) {
                new QRCode(qrCanvas, {
                    text: "{{ route('mahasiswa.aktivasi', ['token' => $mahasiswa->qr_token]) }}",
                    width: 140,
                    height: 140,
                    colorDark: "#000000",
                    colorLight: "#FFFFFF",
                    correctLevel: QRCode.CorrectLevel.M
                });
            }

            // Cek hash URL awal
            const hash = window.location.hash.replace('#', '');
            if (['beranda', 'scan', 'tiket'].includes(hash)) {
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

            // Coba environment dulu, fallback ke user webcam
            try {
                await dashQrScanner.start(
                    { facingMode: "environment" },
                    config,
                    onDashScanSuccess,
                    (err) => { /* abaikan frame errors */ }
                );
                isDashScanning = true;
            } catch (envErr) {
                try {
                    await dashQrScanner.start(
                        { facingMode: "user" },
                        config,
                        onDashScanSuccess,
                        (err) => { /* abaikan */ }
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

        // Modal Feedback Presensi Dashboard
        function showDashResultModal(data, isHttpOk) {
            const modal = document.getElementById('dash-result-modal');
            const iconWrapper = document.getElementById('dash-modal-icon-wrapper');
            const badge = document.getElementById('dash-modal-badge');
            const title = document.getElementById('dash-modal-title');
            const desc = document.getElementById('dash-modal-desc');

            iconWrapper.className = "w-14 h-14 mx-auto border-2 border-black flex items-center justify-center font-black text-2xl shadow-[3px_3px_0px_0px_#000000]";
            badge.className = "inline-block text-[10px] font-black uppercase px-2 py-0.5 border border-black shadow-[1px_1px_0px_0px_#000000] mb-1";

            if (isHttpOk && data.success) {
                iconWrapper.classList.add('bg-green-400', 'text-black');
                iconWrapper.innerHTML = '✓';
                badge.classList.add('bg-green-100', 'text-green-900');
                badge.innerText = 'BERHASIL HADIR';
                title.innerText = 'PRESENSI BERHASIL!';
                desc.innerText = data.message || 'Presensi kehadiran Anda telah tercatat di sistem.';
            } else {
                iconWrapper.classList.add('bg-red-400', 'text-black');
                iconWrapper.innerHTML = '✕';
                badge.classList.add('bg-red-100', 'text-red-900');
                badge.innerText = 'GAGAL';
                title.innerText = 'PRESENSI GAGAL';
                desc.innerText = data.message || 'Gagal memproses presensi.';
            }

            modal.classList.remove('hidden');
        }

        function closeDashResultModal() {
            document.getElementById('dash-result-modal').classList.add('hidden');
            // Refresh halaman agar rekap kehadiran langsung ter-update
            window.location.reload();
        }
    </script>

</body>
</html>
