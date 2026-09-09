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
<body class="text-[#000000] min-h-screen flex items-center justify-center p-3.5 sm:p-6 antialiased selection:bg-[#2A82C6] selection:text-white relative overflow-x-hidden">

    <!-- Floating Background Decor -->
    <div class="hidden md:block absolute top-10 left-10 w-12 h-12 bg-[#2A82C6]/20 border-2 border-[#1A467C] shadow-[4px_4px_0px_0px_#1A467C] pointer-events-none"></div>
    <div class="hidden md:block absolute bottom-12 right-12 w-14 h-14 bg-[#CA2C2A]/15 border-2 border-[#901C1A] shadow-[4px_4px_0px_0px_#901C1A] pointer-events-none"></div>

    <div class="w-full max-w-md bg-white border-2 border-[#1A467C] p-6 sm:p-8 shadow-[8px_8px_0px_0px_#1A467C] z-10 relative">
        
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
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••"
                    required
                    class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] text-gray-950 font-semibold text-sm px-4 py-3 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                >
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

</body>
</html>
