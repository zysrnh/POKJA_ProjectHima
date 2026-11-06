<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Password Baru - POKJA HIMA IF</title>

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

        .btn-smooth {
            transition: transform 0.15s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.15s cubic-bezier(0.2, 0.8, 0.2, 1), background-color 0.15s ease;
        }
        .btn-smooth:hover {
            transform: translate(-2px, -2px);
        }
        .btn-smooth:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px 0px #000000 !important;
        }

        @keyframes cardSnapIn {
            0% {
                transform: translateY(-20px) scale(0.97);
            }
            100% {
                transform: translateY(0) scale(1);
            }
        }
        .snap-card {
            animation: cardSnapIn 0.35s cubic-bezier(0.18, 0.89, 0.32, 1.28) forwards;
        }
    </style>
</head>
<body class="min-h-screen text-[#000000] flex flex-col justify-between p-4 sm:p-6 antialiased selection:bg-[#2A82C6] selection:text-white">

    <div></div>

    <!-- Main Container -->
    <main class="my-auto py-6 flex flex-col items-center justify-center">

        <div class="snap-card w-full max-w-md bg-white border-[3px] border-[#000000] p-6 sm:p-8 shadow-[8px_8px_0px_0px_#000000] space-y-6">

            <!-- Logo & Title -->
            <div class="text-center space-y-2 border-b-2 border-black pb-5">
                <img 
                    src="{{ asset('logo/logo.png') }}" 
                    alt="Logo HIMA IF" 
                    class="w-14 h-14 mx-auto object-contain bg-white p-1.5 border-2 border-black shadow-[3px_3px_0px_0px_#000000]"
                >
                <div>
                    <h1 class="text-lg sm:text-xl font-black uppercase tracking-tight text-[#1A467C]">
                        Atur Password Baru
                    </h1>
                    <p class="text-xs font-semibold text-gray-600 mt-0.5">
                        Halo, <span class="text-gray-950 font-bold">{{ $mahasiswa->nama }}</span>! Masukkan password baru untuk akun portal mahasiswa Anda.
                    </p>
                </div>
            </div>

            <!-- Identity Pill -->
            <div class="bg-gray-50 border-2 border-black p-3 text-xs flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-gray-500 uppercase block">NIM Terdaftar</span>
                    <span class="font-mono font-black text-gray-950">{{ $mahasiswa->nim }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-gray-500 uppercase block">Kelas</span>
                    <span class="font-mono font-black text-[#1A467C]">{{ $mahasiswa->kelas }}</span>
                </div>
            </div>

            <!-- Flash Alert Error -->
            @if ($errors->any())
                <div class="p-3.5 bg-red-50 border-2 border-black text-xs font-bold text-red-900 shadow-[3px_3px_0px_0px_#CA2C2A]">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Buat Password Baru -->
            <form action="{{ request()->fullUrl() }}" method="POST" class="space-y-4">
                @csrf

                <!-- Field: Password Baru -->
                <div>
                    <label for="password" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                        Password Baru <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            minlength="6"
                            placeholder="Minimal 6 karakter..."
                            class="w-full bg-[#F8FBFE] border-2 border-black text-gray-950 font-bold text-sm px-3.5 py-2.5 pr-10 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#000000] transition"
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility('password', 'eye-1')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 hover:text-black cursor-pointer"
                        >
                            <svg id="eye-1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="square" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Field: Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                        Ulangi Password Baru <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required
                            minlength="6"
                            placeholder="Ketik ulang password baru..."
                            class="w-full bg-[#F8FBFE] border-2 border-black text-gray-950 font-bold text-sm px-3.5 py-2.5 pr-10 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#000000] transition"
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility('password_confirmation', 'eye-2')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 hover:text-black cursor-pointer"
                        >
                            <svg id="eye-2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="square" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Security Checklist -->
                <div class="p-3 bg-blue-50 border border-black text-[11px] font-semibold text-blue-950 space-y-1">
                    <p class="font-bold text-[#1A467C]">Petunjuk Keamanan Password:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-gray-700">
                        <li>Minimal terdiri dari 6 karakter.</li>
                        <li>Pastikan konfirmasi password sama persis.</li>
                        <li>Gunakan kombinasi angka dan huruf agar lebih aman.</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="btn-smooth w-full py-3 bg-[#1A467C] hover:bg-[#153660] text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center gap-2 cursor-pointer mt-2"
                >
                    <span>Simpan Password & Masuk</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </form>

            <div class="text-center pt-2">
                <a 
                    href="{{ route('mahasiswa.login') }}" 
                    class="text-xs font-bold text-gray-600 hover:text-black hover:underline"
                >
                    ← Kembali ke Halaman Login
                </a>
            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full text-center py-2 text-xs font-bold text-gray-500">
        &copy; {{ date('Y') }} POKJA HIMA IF • Sistem Terpadu Registrasi & Presensi
    </footer>

    <script>
        function togglePasswordVisibility(inputId, eyeIconId) {
            const input = document.getElementById(inputId);
            const eyeIcon = document.getElementById(eyeIconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="square" stroke-width="2.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                input.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="square" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="square" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>

</body>
</html>
