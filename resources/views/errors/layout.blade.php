<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') - @yield('title') | POKJA HIMA IF</title>

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

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E8F2FA;
            background-image: radial-gradient(#1A467C 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }

        /* Tactile Button Press (Tanpa Opacity / Tanpa Fade) */
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

        /* Card Scale Snap-in (Solid Tanpa Opacity) */
        @keyframes cardSnapIn {
            0% {
                transform: translateY(-18px) scale(0.95);
            }
            70% {
                transform: translateY(3px) scale(1.01);
            }
            100% {
                transform: translateY(0) scale(1);
            }
        }
        .snap-card {
            animation: cardSnapIn 0.28s cubic-bezier(0.18, 0.89, 0.32, 1.28) forwards;
        }
    </style>
</head>
<body class="min-h-screen text-[#000000] flex flex-col justify-between p-4 sm:p-6 antialiased selection:bg-[#2A82C6] selection:text-white">

    <div></div>

    <main class="my-auto py-6 flex flex-col items-center justify-center">

        <div class="snap-card w-full max-w-md bg-white border-[3px] border-[#000000] p-6 sm:p-8 shadow-[8px_8px_0px_0px_#000000] space-y-6 text-center">

            <!-- Brand Header -->
            <div class="flex items-center justify-between border-b-2 border-black pb-4">
                <div class="flex items-center gap-2.5 text-left">
                    <img 
                        src="{{ asset('logo/logo.png') }}" 
                        alt="Logo HIMA IF" 
                        class="w-9 h-9 object-contain bg-white p-1 border-2 border-black shadow-[2px_2px_0px_0px_#000000]"
                    >
                    <div>
                        <span class="font-black text-xs uppercase tracking-wider text-[#1A467C] block leading-none">POKJA HIMA IF</span>
                        <span class="text-[10px] font-bold text-gray-600 uppercase tracking-wider block mt-0.5">Security & System Guard</span>
                    </div>
                </div>

                <span class="font-mono font-black text-xs px-2.5 py-1 border-2 border-black shadow-[2px_2px_0px_0px_#000000] @yield('badge_class', 'bg-red-100 text-red-900')">
                    HTTP @yield('code')
                </span>
            </div>

            <!-- Big Error Code Box -->
            <div class="py-4 px-4 border-2 border-black shadow-[4px_4px_0px_0px_#000000] @yield('box_bg', 'bg-[#F8FBFE]')">
                <h1 class="text-5xl sm:text-6xl font-black font-mono tracking-tighter @yield('code_color', 'text-[#1A467C]')">
                    @yield('code')
                </h1>
                <span class="inline-block mt-1 text-xs font-black uppercase tracking-widest text-gray-900 bg-white border border-black px-2.5 py-0.5">
                    @yield('label')
                </span>
            </div>

            <!-- Message & Explanation -->
            <div class="space-y-2">
                <h2 class="text-base sm:text-lg font-black uppercase text-gray-950">
                    @yield('title')
                </h2>
                <p class="text-xs sm:text-sm font-semibold text-gray-600 leading-relaxed">
                    @yield('message')
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="pt-2 space-y-2.5">
                <a 
                    href="{{ url('/') }}" 
                    class="btn-tactile w-full py-3 bg-[#1A467C] hover:bg-[#153660] text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center gap-2 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Kembali ke Beranda Utama</span>
                </a>

                <button 
                    type="button" 
                    onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ url('/') }}'"
                    class="btn-tactile w-full py-2.5 bg-white hover:bg-gray-100 text-gray-900 text-xs font-black uppercase tracking-wider border-2 border-black shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    ← Kembali ke Halaman Sebelumnya
                </button>
            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full text-center py-2 text-xs font-bold text-gray-500">
        &copy; {{ date('Y') }} POKJA HIMA IF • Sistem Terproteksi Keamanan Terpadu
    </footer>

</body>
</html>

