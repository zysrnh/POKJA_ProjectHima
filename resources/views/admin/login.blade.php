<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Login Admin - POKJA HIMA IF</title>
    
    <!-- Google Font: Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex items-center justify-center p-3 sm:p-6 antialiased">

    <div class="w-full max-w-md bg-white border border-gray-300 p-6 sm:p-8 shadow-sm">
        <!-- Header -->
        <div class="border-b border-gray-200 pb-4 mb-6 text-center">
            <h1 class="text-xl font-bold uppercase tracking-wide text-gray-900">Panel Administrator</h1>
            <p class="text-xs text-gray-600 mt-1">POKJA HIMA IF - Silakan masuk untuk mengelola data</p>
        </div>

        <!-- Flash Alert Sukses / Info -->
        @if (session('success'))
            <div class="mb-5 p-3.5 bg-green-50 border border-green-600 text-green-800 text-xs sm:text-sm">
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Flash Alert Error -->
        @if ($errors->any())
            <div class="mb-5 p-3.5 bg-red-50 border border-red-600 text-red-800 text-xs sm:text-sm">
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
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
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-base md:text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                >
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                    Password
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••"
                    required
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-base md:text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                >
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-gray-700">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-0">
                    <span>Ingat saya di perangkat ini</span>
                </label>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs sm:text-sm font-semibold uppercase tracking-wider py-3 px-4 transition cursor-pointer"
                >
                    Masuk ke Panel Admin
                </button>
            </div>
        </form>

        <!-- Footer / Back to Form -->
        <div class="mt-6 pt-4 border-t border-gray-200 text-center">
            <a href="{{ route('pendaftaran.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline">
                &larr; Kembali ke Form Pendaftaran
            </a>
        </div>
    </div>

</body>
</html>
