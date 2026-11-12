<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Login Admin - POKJA HIMA IF</title>
    
    <!-- Favicon & Web Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
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
        }

        @keyframes cardSnapIn {
            0% { opacity: 0; transform: translateY(12px) scale(0.985); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        .card-snap-in {
            animation: cardSnapIn 0.28s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
    </style>
</head>
<body class="text-[#000000] min-h-screen flex items-center justify-center p-3.5 sm:p-6 antialiased selection:bg-[#2A82C6] selection:text-white relative overflow-x-hidden">

    <!-- Floating Background Decor -->
    <div class="hidden md:block absolute top-10 left-10 w-12 h-12 bg-[#2A82C6]/20 border-2 border-[#1A467C] shadow-[4px_4px_0px_0px_#1A467C] pointer-events-none"></div>
    <div class="hidden md:block absolute bottom-12 right-12 w-14 h-14 bg-[#CA2C2A]/15 border-2 border-[#901C1A] shadow-[4px_4px_0px_0px_#901C1A] pointer-events-none"></div>

    <div class="card-snap-in w-full max-w-md bg-white border-2 border-[#1A467C] p-6 sm:p-8 shadow-[8px_8px_0px_0px_#1A467C] z-10 relative">
        
        <!-- Header with Logo -->
        <div class="border-b-2 border-[#1A467C] pb-5 mb-6 text-center">
            <div class="inline-block bg-white border-2 border-[#1A467C] p-2.5 shadow-[4px_4px_0px_0px_#1A467C]">
                <img 
                    src="{{ asset('logo/logo.png') }}" 
                    alt="Logo HIMA IF" 
                    class="w-16 h-16 object-contain mx-auto"
                >
            </div>
        </div>

        <!-- Flash Alert Sukses / Info -->
        @if (session('success'))
            <div class="mb-5 p-3.5 bg-green-50 border-2 border-green-700 text-green-900 text-xs sm:text-sm font-semibold shadow-[3px_3px_0px_0px_#15803d]">
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Flash Alert Error -->
        @if ($errors->any())
            <div class="mb-5 p-3.5 bg-red-50 border-2 border-[#901C1A] text-red-950 text-xs sm:text-sm font-semibold shadow-[3px_3px_0px_0px_#901C1A]">
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-900">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Login (Neubrutalism) -->
        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    Email Admin
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="admin@pokja.com"
                    required
                    autofocus
                    class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] text-gray-950 font-semibold text-sm px-4 py-3 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                >
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                    Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        required
                        class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] text-gray-950 font-semibold text-sm px-4 py-3 pr-11 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                    >
                    <button 
                        type="button" 
                        onclick="toggleAdminPassword()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#1A467C] hover:text-[#2A82C6] focus:outline-none cursor-pointer p-1"
                        title="Lihat / Sembunyikan Password"
                    >
                        <svg id="eye-icon-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="square" stroke-width="2.2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eye-icon-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-[#1A467C]">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-[#2A82C6] border-2 border-[#1A467C] focus:ring-0">
                    <span>Ingat saya di perangkat ini</span>
                </label>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="btn-smooth w-full bg-[#2A82C6] hover:bg-[#1A467C] active:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-widest py-3.5 px-4 border-2 border-[#1A467C] shadow-[4px_4px_0px_0px_#1A467C] cursor-pointer"
                >
                    Masuk ke Panel Admin
                </button>
            </div>
        </form>

        <!-- Footer / Back to Form -->
        <div class="mt-6 pt-4 border-t-2 border-[#1A467C] text-center">
            <a href="{{ route('pendaftaran.index') }}" class="text-xs font-bold text-[#1A467C] hover:text-[#2A82C6] hover:underline uppercase tracking-wider">
                &larr; Kembali ke Form Pendaftaran
            </a>
        </div>
    </div>

    <script>
        function toggleAdminPassword() {
            const input = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-icon-open');
            const eyeClosed = document.getElementById('eye-icon-closed');

            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
