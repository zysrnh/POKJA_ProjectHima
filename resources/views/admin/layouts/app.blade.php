<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'Admin Panel') - POKJA HIMA IF</title>
    
    <!-- Favicon & Web Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Google Font: Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,600;1,700;1,800&display=swap" rel="stylesheet">

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

        function isDesktop() {
            return window.innerWidth >= 1024;
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (isDesktop()) {
                const isCollapsed = sidebar.classList.contains('lg:w-0');
                if (isCollapsed) {
                    sidebar.classList.remove('lg:w-0', 'lg:-translate-x-full', 'lg:overflow-hidden', 'lg:border-r-0');
                    sidebar.classList.add('lg:w-64', 'lg:translate-x-0');
                    localStorage.setItem('admin_sidebar_desktop', 'open');
                } else {
                    sidebar.classList.remove('lg:w-64', 'lg:translate-x-0');
                    sidebar.classList.add('lg:w-0', 'lg:-translate-x-full', 'lg:overflow-hidden', 'lg:border-r-0');
                    localStorage.setItem('admin_sidebar_desktop', 'closed');
                }
            } else {
                const isMobileOpen = !sidebar.classList.contains('-translate-x-full');
                if (isMobileOpen) {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                }
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (isDesktop()) {
                const savedState = localStorage.getItem('admin_sidebar_desktop');
                const sidebar = document.getElementById('admin-sidebar');
                if (savedState === 'closed') {
                    sidebar.classList.remove('lg:w-64', 'lg:translate-x-0');
                    sidebar.classList.add('lg:w-0', 'lg:-translate-x-full', 'lg:overflow-hidden', 'lg:border-r-0');
                }
            }
        });
    </script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
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
        }
    </style>
</head>
<body class="text-[#000000] min-h-screen flex flex-col antialiased selection:bg-[#2A82C6] selection:text-white">

    <div class="flex flex-1 min-h-screen relative overflow-x-hidden">
        
        <!-- Mobile Backdrop Overlay -->
        <div 
            id="sidebar-overlay" 
            onclick="closeMobileSidebar()" 
            class="fixed inset-0 bg-black/60 z-40 lg:hidden hidden transition-opacity duration-200"
        ></div>

        <!-- Sidebar Navigation (Desktop & Mobile Collapsible Neubrutalism) -->
        <aside 
            id="admin-sidebar" 
            class="fixed lg:static inset-y-0 left-0 z-50 w-64 lg:w-64 bg-[#1A467C] text-white flex flex-col justify-between -translate-x-full lg:translate-x-0 transition-all duration-200 ease-in-out border-r-2 border-[#000000] shrink-0"
        >
            <div class="w-64">
                <!-- Brand Header in Sidebar with Logo -->
                <div class="h-16 flex items-center justify-between px-4 bg-[#0F2A4A] border-b-2 border-[#000000]">
                    <div class="flex items-center gap-2.5">
                        <img 
                            src="{{ asset('logo/logo.png') }}" 
                            alt="Logo HIMA IF" 
                            class="w-9 h-9 object-contain bg-white p-1 border-2 border-white"
                        >
                        <div>
                            <span class="font-black text-xs uppercase tracking-wider text-white block">POKJA HIMA IF</span>
                            <span class="text-[10px] text-[#2A82C6] font-bold uppercase tracking-wider block">Admin Panel</span>
                        </div>
                    </div>
                    <!-- Close button on mobile -->
                    <button onclick="closeMobileSidebar()" class="lg:hidden text-white/70 hover:text-white p-1" title="Tutup Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="p-3.5 space-y-2 text-xs font-bold uppercase tracking-wider">
                    <!-- Menu 1: Data Pendaftar -->
                    <a 
                        href="{{ route('admin.dashboard') }}" 
                        class="flex items-center gap-3 px-3.5 py-3 border-2 transition {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.pendaftar.*') ? 'bg-[#2A82C6] border-white text-white shadow-[3px_3px_0px_0px_#000000]' : 'border-transparent text-gray-200 hover:bg-[#0F2A4A] hover:text-white' }}"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>Data Pendaftar</span>
                    </a>

                    <!-- Menu 2: Kelola Kelas -->
                    <a 
                        href="{{ route('admin.kelas.index') }}" 
                        class="flex items-center gap-3 px-3.5 py-3 border-2 transition {{ request()->routeIs('admin.kelas.*') ? 'bg-[#2A82C6] border-white text-white shadow-[3px_3px_0px_0px_#000000]' : 'border-transparent text-gray-200 hover:bg-[#0F2A4A] hover:text-white' }}"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Kelola Kelas</span>
                    </a>

                    <!-- Menu 3: Kelola Admin -->
                    <a 
                        href="{{ route('admin.users.index') }}" 
                        class="flex items-center gap-3 px-3.5 py-3 border-2 transition {{ request()->routeIs('admin.users.*') ? 'bg-[#2A82C6] border-white text-white shadow-[3px_3px_0px_0px_#000000]' : 'border-transparent text-gray-200 hover:bg-[#0F2A4A] hover:text-white' }}"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Kelola Admin</span>
                    </a>

                    <!-- Menu 4: Pengaturan Acara -->
                    <a 
                        href="{{ route('admin.pengaturan.index') }}" 
                        class="flex items-center gap-3 px-3.5 py-3 border-2 transition {{ request()->routeIs('admin.pengaturan.*') ? 'bg-[#2A82C6] border-white text-white shadow-[3px_3px_0px_0px_#000000]' : 'border-transparent text-gray-200 hover:bg-[#0F2A4A] hover:text-white' }}"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Pengaturan Acara</span>
                    </a>
                </nav>
            </div>

            <!-- Sidebar Footer -->
            <div class="w-64 p-4 border-t-2 border-[#000000] text-[11px] text-gray-300 bg-[#0F2A4A]">
                <p class="font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                <p class="truncate text-blue-200 font-mono">{{ Auth::user()->email ?? 'admin@pokja.com' }}</p>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-200">

            <!-- Top Header -->
            <header class="bg-white border-b-2 border-[#1A467C] h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30 shadow-[0_2px_0px_0px_#1A467C]">
                <!-- Left: Universal Sidebar Toggle Button -->
                <div class="flex items-center gap-3">
                    <button 
                        type="button" 
                        onclick="toggleSidebar()" 
                        class="btn-smooth p-2 bg-white border-2 border-[#1A467C] text-[#1A467C] shadow-[2px_2px_0px_0px_#1A467C] cursor-pointer"
                        title="Buka / Tutup Sidebar"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-xs sm:text-base font-black text-[#1A467C] uppercase tracking-wide">
                        @yield('header_title', 'Dashboard')
                    </h2>
                </div>

                <!-- Right: External Link & Logout -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <a 
                        href="{{ route('pendaftaran.index') }}" 
                        target="_blank" 
                        class="btn-smooth hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 border-2 border-[#1A467C] text-xs font-bold uppercase tracking-wider text-[#1A467C] bg-white shadow-[2px_2px_0px_0px_#1A467C]"
                    >
                        <span>Lihat Form</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>

                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button 
                            type="submit" 
                            class="btn-smooth px-3.5 py-1.5 bg-[#CA2C2A] hover:bg-[#901C1A] active:bg-[#901C1A] text-white text-xs font-bold uppercase tracking-wider border-2 border-[#901C1A] shadow-[2px_2px_0px_0px_#901C1A] cursor-pointer"
                        >
                            Keluar
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Body Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="p-3.5 bg-green-50 border-2 border-green-700 text-green-900 text-xs sm:text-sm font-semibold shadow-[4px_4px_0px_0px_#15803d]">
                        <div class="flex items-center gap-2">
                            <span class="bg-green-700 text-white font-black text-[10px] px-1.5 py-0.5 uppercase">SUKSES</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-3.5 bg-red-50 border-2 border-[#901C1A] text-red-950 text-xs sm:text-sm font-semibold shadow-[4px_4px_0px_0px_#901C1A]">
                        <div class="flex items-center gap-2">
                            <span class="bg-[#CA2C2A] text-white font-black text-[10px] px-1.5 py-0.5 uppercase">ERROR</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

        </div>

    </div>

</body>
</html>
