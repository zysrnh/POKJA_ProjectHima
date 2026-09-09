<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'Admin Panel') - POKJA HIMA IF</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col antialiased">

    <div class="flex flex-1 min-h-screen relative">
        
        <!-- Mobile Sidebar Overlay Backdrop -->
        <div 
            id="sidebar-overlay" 
            onclick="toggleSidebar()" 
            class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden transition-opacity"
        ></div>

        <!-- Sidebar Navigation -->
        <aside 
            id="admin-sidebar" 
            class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white flex flex-col justify-between transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out border-r border-gray-800"
        >
            <div>
                <!-- Brand Header in Sidebar -->
                <div class="h-16 flex items-center justify-between px-6 bg-gray-950 border-b border-gray-800">
                    <div class="flex items-center gap-3">
                        <span class="bg-blue-600 text-white text-[11px] font-bold px-2 py-0.5 uppercase tracking-wider">Admin</span>
                        <span class="font-bold text-sm uppercase tracking-wider text-white">POKJA HIMA IF</span>
                    </div>
                    <!-- Close button on mobile -->
                    <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1.5 text-xs font-semibold uppercase tracking-wider">
                    <!-- Menu 1: Data Pendaftar -->
                    <a 
                        href="{{ route('admin.dashboard') }}" 
                        class="flex items-center gap-3 px-3.5 py-3 transition {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.pendaftar.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>Data Pendaftar</span>
                    </a>

                    <!-- Menu 2: Kelola Admin -->
                    <a 
                        href="{{ route('admin.users.index') }}" 
                        class="flex items-center gap-3 px-3.5 py-3 transition {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Kelola Admin</span>
                    </a>
                </nav>
            </div>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-gray-800 text-[11px] text-gray-400 bg-gray-950">
                <p class="font-medium text-gray-300 truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                <p class="truncate text-gray-500">{{ Auth::user()->email ?? 'admin@pokja.com' }}</p>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Top Header -->
            <header class="bg-white border-b border-gray-300 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30">
                <!-- Left: Hamburger toggle button for Mobile -->
                <div class="flex items-center gap-3">
                    <button 
                        type="button" 
                        onclick="toggleSidebar()" 
                        class="lg:hidden p-2 text-gray-700 hover:bg-gray-100 transition"
                        title="Buka Menu"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-sm sm:text-base font-bold text-gray-900 uppercase tracking-wide">
                        @yield('header_title', 'Dashboard')
                    </h2>
                </div>

                <!-- Right: External Link & Logout -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <a 
                        href="{{ route('pendaftaran.index') }}" 
                        target="_blank" 
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-300 text-xs font-semibold uppercase tracking-wider text-gray-700 bg-white hover:bg-gray-50 transition"
                    >
                        <span>Lihat Form Pendaftaran</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>

                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button 
                            type="submit" 
                            class="px-3.5 py-1.5 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white text-xs font-semibold uppercase tracking-wider transition cursor-pointer"
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
                    <div class="p-3.5 bg-green-50 border border-green-600 text-green-800 text-xs sm:text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-700 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-3.5 bg-red-50 border border-red-600 text-red-800 text-xs sm:text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-700 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

        </div>

    </div>

</body>
</html>
