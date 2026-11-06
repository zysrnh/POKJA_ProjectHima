<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Pendaftaran Berhasil - {{ $pengaturan->nama_acara ?? 'POKJA HIMA IF 2026' }}</title>

    <!-- Favicon & Web Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Google Font: Plus Jakarta Sans -->
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
            box-shadow: 7px 7px 0px 0px #1A467C !important;
        }

        @keyframes snapIn {
            0% { transform: translateY(-16px) scale(0.96); }
            70% { transform: translateY(2px) scale(1.01); }
            100% { transform: translateY(0) scale(1); }
        }
        .snap-in {
            animation: snapIn 0.28s cubic-bezier(0.18, 0.89, 0.32, 1.28) forwards;
        }
    </style>
</head>
<body class="min-h-screen text-[#000000] flex flex-col justify-between p-4 sm:p-6 lg:p-10 antialiased selection:bg-[#2A82C6] selection:text-white">

    <div></div>

    <!-- Main Card Container -->
    <main class="my-auto py-6 flex flex-col items-center justify-center">

        <div class="snap-in w-full max-w-lg bg-white border-[3px] border-[#1A467C] p-5 sm:p-8 shadow-[8px_8px_0px_0px_#1A467C] space-y-6">

            <!-- Logo & Success Header -->
            <div class="text-center space-y-3 border-b-2 border-[#1A467C]/20 pb-5">
                <div class="w-16 h-16 mx-auto bg-green-50 border-2 border-black flex items-center justify-center shadow-[3px_3px_0px_0px_#15803d]">
                    <svg class="w-9 h-9 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-[#1A467C] uppercase tracking-tight">
                        Selamat Bergabung!
                    </h1>
                    @if ($pendaftaran)
                        <p class="text-xs sm:text-sm font-semibold text-gray-700 mt-1">
                            Halo, <strong class="text-black">{{ $pendaftaran->nama }}</strong> (<span class="font-mono font-bold">{{ $pendaftaran->nim }}</span> / {{ $pendaftaran->kelas }}). Data Anda telah resmi tersimpan.
                        </p>
                    @else
                        <p class="text-xs sm:text-sm font-semibold text-gray-700 mt-1">
                            Data pendaftaran Anda telah resmi tersimpan di sistem POKJA HIMA IF.
                        </p>
                    @endif
                </div>
            </div>

            <!-- 2 INSTRUKSI PENTING: CEK EMAIL & GABUNG GRUP WA -->
            <div class="space-y-4">
                
                <!-- 1. Instruksi Cek Email & QR Aktivasi Akun -->
                <div class="card-tactile bg-[#F8FBFE] border-2 border-[#1A467C] p-4 shadow-[3px_3px_0px_0px_#1A467C] space-y-2">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-blue-100 border border-[#1A467C] text-[#1A467C] flex items-center justify-center shrink-0 shadow-[1px_1px_0px_0px_#1A467C]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-xs sm:text-sm font-black uppercase text-[#1A467C] tracking-wide">
                            1. Cek Email & Unduh QR Pendaftaran
                        </h2>
                    </div>
                    <p class="text-xs font-semibold text-gray-700 leading-relaxed pl-10">
                        <strong>QR Code pendaftaran</strong> telah kami kirimkan ke email Anda:
                        <span class="font-mono font-bold text-[#1A467C] bg-white px-1.5 py-0.5 border border-gray-300 block sm:inline mt-1 sm:mt-0">
                            {{ $pendaftaran ? $pendaftaran->email : 'email terdaftar Anda' }}
                        </span>
                    </p>
                    <div class="pl-10 text-[11px] font-medium text-amber-900 bg-amber-50 border border-amber-300 p-2">
                        💡 <strong>Tips:</strong> Jika tidak ada di Kotak Masuk (Inbox), silakan periksa folder <strong>Spam / Junk</strong> Anda.
                    </div>
                </div>

                <!-- 2. Instruksi Gabung Grup WhatsApp -->
                @php
                    $linkGrupWa = $pengaturan->link_grup_wa ?? 'https://chat.whatsapp.com/';
                @endphp
                <div class="card-tactile bg-green-50/60 border-2 border-green-800 p-4 shadow-[3px_3px_0px_0px_#15803d] space-y-2.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-green-600 border border-black text-white flex items-center justify-center shrink-0 shadow-[1px_1px_0px_0px_#000000]">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                        </div>
                        <h2 class="text-xs sm:text-sm font-black uppercase text-green-950 tracking-wide">
                            2. Masuk Grup WhatsApp Peserta
                        </h2>
                    </div>
                    <p class="text-xs font-semibold text-gray-700 leading-relaxed pl-10">
                        Untuk koordinasi jadwal, materi kegiatan, dan instruksi penting dari panitia POKJA.
                    </p>
                    <div class="pl-10 pt-1">
                        <a 
                            href="{{ $linkGrupWa }}" 
                            target="_blank"
                            class="btn-tactile w-full inline-flex items-center justify-center gap-2 bg-green-700 hover:bg-green-800 text-white font-black text-xs uppercase tracking-wider py-3 px-4 border-2 border-black shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                        >
                            <span>Gabung Grup WhatsApp Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Tombol Aksi Menuju Halaman Login / Aktivasi Akun -->
            <div class="pt-2 border-t-2 border-[#1A467C]/20 space-y-2">
                <a 
                    href="{{ route('mahasiswa.login') }}" 
                    class="btn-tactile w-full inline-flex items-center justify-center gap-2 bg-[#1A467C] hover:bg-[#153660] text-white font-black text-xs sm:text-sm uppercase tracking-wider py-3.5 px-4 border-2 border-black shadow-[4px_4px_0px_0px_#000000] cursor-pointer"
                >
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>Lanjut ke Aktivasi Akun / Login</span>
                </a>

                <div class="text-center pt-1">
                    <a 
                        href="{{ route('pendaftaran.index') }}" 
                        class="text-xs font-bold text-gray-500 hover:text-black hover:underline"
                    >
                        ← Kembali ke Beranda Formulir
                    </a>
                </div>
            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full text-center py-2 text-xs font-bold text-gray-500">
        &copy; {{ date('Y') }} POKJA HIMA IF • Sistem Terpadu Registrasi & Presensi
    </footer>

</body>
</html>

