<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Pendaftaran {{ $pengaturan->nama_acara ?? 'POKJA' }} - HIMA IF</title>
    
    <!-- Google Font: Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800;1,900&display=swap" rel="stylesheet">

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

        .dot-card {
            background-color: #F8FBFE;
            background-image: radial-gradient(#1A467C 1px, transparent 1px);
            background-size: 16px 16px;
        }

        /* Tape effect on polaroid badge */
        .tape-badge {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(2px);
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body class="text-[#000000] min-h-screen flex items-center justify-center p-3.5 sm:p-6 lg:p-10 antialiased selection:bg-[#2A82C6] selection:text-white relative overflow-x-hidden">

    <!-- Floating Geometric Accents with Exact Palette -->
    <div class="hidden md:block absolute top-8 left-8 w-14 h-14 bg-[#2A82C6]/25 border-2 border-[#1A467C] shadow-[4px_4px_0px_0px_#1A467C] pointer-events-none"></div>
    <div class="hidden md:block absolute bottom-12 left-12 w-10 h-10 bg-[#CA2C2A]/20 border-2 border-[#901C1A] shadow-[3px_3px_0px_0px_#901C1A] pointer-events-none"></div>
    <div class="hidden md:block absolute top-16 right-12 w-12 h-12 bg-white border-2 border-[#1A467C] shadow-[4px_4px_0px_0px_#1A467C] pointer-events-none"></div>
    <div class="hidden md:block absolute bottom-8 right-16 w-16 h-16 bg-[#1A467C]/15 border-2 border-[#000000] shadow-[4px_4px_0px_0px_#000000] pointer-events-none"></div>

    <!-- Unified Single Card Container (Satu Form Utuh Satu Halaman) -->
    <div class="w-full max-w-4xl z-10 my-4 sm:my-8 bg-white border-2 border-[#1A467C] p-6 sm:p-9 lg:p-10 shadow-[8px_8px_0px_0px_#1A467C] sm:shadow-[10px_10px_0px_0px_#1A467C]">

        <!-- HERO HEADER: Polaroid + Big Highlighted Title -->
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6 mb-7 pb-7 border-b-2 border-[#1A467C]">
            
            <!-- Polaroid Logo Badge with Tape Header -->
            <div class="relative group shrink-0">
                <!-- Semi-transparent Tape Effect -->
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-14 h-5 tape-badge border border-gray-400 z-10"></div>
                
                <!-- Polaroid Box -->
                <div class="bg-white border-2 border-[#1A467C] p-2.5 shadow-[4px_4px_0px_0px_#1A467C] rotate-[-2deg] hover:rotate-0 transition-transform duration-200">
                    <img 
                        src="{{ asset('logo/logo.png') }}" 
                        alt="Logo HIMA IF" 
                        class="w-20 h-20 sm:w-24 sm:h-24 object-contain mx-auto bg-gray-50 border border-gray-200 p-1"
                    >
                    <p class="text-[10px] font-black font-mono text-center text-[#1A467C] mt-2 uppercase tracking-wider">
                        #HIMA_IF
                    </p>
                </div>
            </div>

            <!-- Big Highlighted Headline Section -->
            <div class="flex-1 text-center md:text-left">
                <span class="inline-block bg-[#1A467C] text-white text-[10px] sm:text-xs font-black uppercase px-2.5 py-0.5 tracking-widest mb-2 shadow-[2px_2px_0px_0px_#2A82C6]">
                    POKJA HIMA IF 2026
                </span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#1A467C] tracking-tight uppercase leading-none">
                    {{ $pengaturan->deskripsi_acara ?? 'INNOVATIVE IDEA TO GREAT PROPOSAL' }}
                </h1>
                <!-- Red Dual Accent Bar -->
                <div class="w-24 h-2 bg-[#CA2C2A] shadow-[2px_2px_0px_0px_#901C1A] mt-3 mx-auto md:mx-0"></div>
            </div>
        </div>

        <!-- Event Details Bar: 2 Blueprint Cards Grid -->
        <div class="dot-card border-2 border-[#1A467C] p-4 shadow-[4px_4px_0px_0px_#1A467C] mb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                <!-- Tanggal & Waktu -->
                <div class="bg-white border-2 border-[#1A467C] p-3.5 shadow-[3px_3px_0px_0px_#1A467C] flex items-start gap-3">
                    <span class="text-2xl mt-0.5">📅</span>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-[#2A82C6] block">
                            TANGGAL & WAKTU
                        </span>
                        <span class="font-black text-sm sm:text-base text-[#000000] block mt-0.5">
                            {{ $pengaturan->tanggal_acara ?? '13 Oktober 2026' }}
                        </span>
                        <span class="font-bold text-xs text-gray-600 block mt-0.5 font-mono">
                            {{ $pengaturan->jam_acara ?? '08:00 WIB - Selesai' }}
                        </span>
                    </div>
                </div>

                <!-- Lokasi / Ruangan -->
                <div class="bg-white border-2 border-[#901C1A] p-3.5 shadow-[3px_3px_0px_0px_#901C1A] flex items-start gap-3">
                    <span class="text-2xl mt-0.5">📍</span>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-[#CA2C2A] block">
                            TEMPAT / RUANGAN
                        </span>
                        <span class="font-black text-sm sm:text-base text-[#000000] block mt-0.5">
                            {{ $pengaturan->lokasi_acara ?? 'Ruangan 105' }}
                        </span>
                        <span class="font-bold text-xs text-gray-600 block mt-0.5">
                            Kampus Utama
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Alert Sukses -->
        @if (session('success'))
            <div id="alert-box" class="mb-6 p-4 bg-[#DCFCE7] border-2 border-[#1A467C] text-green-950 text-xs sm:text-sm shadow-[4px_4px_0px_0px_#1A467C]">
                <div class="flex items-start gap-2.5">
                    <span class="bg-green-700 text-white font-black text-xs px-2 py-0.5 border border-black shrink-0">BERHASIL</span>
                    <span class="font-bold pt-0.5">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Alert Error Global -->
        @if ($errors->any())
            <div id="alert-box" class="mb-6 p-4 bg-[#FEE2E2] border-2 border-[#901C1A] text-red-950 text-xs sm:text-sm shadow-[4px_4px_0px_0px_#901C1A]">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="bg-[#CA2C2A] text-white font-black text-xs px-2 py-0.5 border border-[#901C1A] shrink-0">PERIKSA</span>
                    <span class="font-bold">Terdapat kesalahan input:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs font-semibold text-red-900">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM PENDAFTARAN (Unified 2-Column Grid Layout) -->
        <form 
            id="form-pendaftaran"
            action="{{ route('pendaftaran.store') }}" 
            method="POST" 
            class="space-y-5"
            onsubmit="handleSubmit(event)"
        >
            @csrf

            <!-- Anti-bot Honeypot Field -->
            <div class="hidden" aria-hidden="true">
                <input type="text" name="website_url" tabindex="-1" autocomplete="off">
            </div>

            <!-- 2-Column Form Fields Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <!-- Field: Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                        Nama Lengkap <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nama" 
                        name="nama" 
                        value="{{ session('success') ? '' : old('nama') }}"
                        placeholder="Masukkan nama lengkap Anda..."
                        maxlength="100"
                        required
                        class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] px-3.5 py-3 text-sm font-semibold text-[#000000] focus:bg-white focus:outline-none focus:shadow-[4px_4px_0px_0px_#1A467C] transition-all duration-150 @error('nama') border-[#CA2C2A] bg-red-50 @enderror"
                    >
                    @error('nama')
                        <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: NIM -->
                <div>
                    <label for="nim" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                        NIM (Nomor Induk Mahasiswa) <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nim" 
                        name="nim" 
                        inputmode="numeric"
                        maxlength="20"
                        oninput="this.value = this.value.replace(/\s+/g, '')"
                        value="{{ session('success') ? '' : old('nim') }}"
                        placeholder="Contoh: 250414000"
                        required
                        class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] px-3.5 py-3 text-sm font-semibold font-mono text-[#000000] focus:bg-white focus:outline-none focus:shadow-[4px_4px_0px_0px_#1A467C] transition-all duration-150 @error('nim') border-[#CA2C2A] bg-red-50 @enderror"
                    >
                    @error('nim')
                        <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: Kelas (Dropdown Dinamis) -->
                <div>
                    <label for="kelas" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                        Kelas <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <select 
                        id="kelas" 
                        name="kelas" 
                        required
                        class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] px-3.5 py-3 text-sm font-bold font-mono text-[#000000] focus:bg-white focus:outline-none focus:shadow-[4px_4px_0px_0px_#1A467C] transition-all duration-150 cursor-pointer @error('kelas') border-[#CA2C2A] bg-red-50 @enderror"
                    >
                        <option value="">-- PILIH KELAS ANDA --</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->nama_kelas }}" {{ (session('success') ? '' : old('kelas')) == $k->nama_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas')
                        <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: No Telp / WhatsApp -->
                <div>
                    <label for="no_telp" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                        No. Telepon / WhatsApp <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <input 
                        type="tel" 
                        id="no_telp" 
                        name="no_telp" 
                        inputmode="tel"
                        maxlength="15"
                        oninput="this.value = this.value.replace(/\s+/g, '')"
                        value="{{ session('success') ? '' : old('no_telp') }}"
                        placeholder="Contoh: 081234567890"
                        required
                        class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] px-3.5 py-3 text-sm font-semibold font-mono text-[#000000] focus:bg-white focus:outline-none focus:shadow-[4px_4px_0px_0px_#1A467C] transition-all duration-150 @error('no_telp') border-[#CA2C2A] bg-red-50 @enderror"
                    >
                    @error('no_telp')
                        <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Submit Button: Full-width Biru Terang #2A82C6 dengan Bayangan Biru Gelap #1A467C -->
            <div class="pt-3">
                <button 
                    type="submit" 
                    id="btn-submit"
                    class="w-full bg-[#2A82C6] hover:bg-[#1A467C] active:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-widest py-4 px-6 border-2 border-[#1A467C] shadow-[6px_6px_0px_0px_#1A467C] hover:shadow-[8px_8px_0px_0px_#1A467C] hover:-translate-x-0.5 hover:-translate-y-0.5 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none transition-all duration-150 cursor-pointer flex items-center justify-center gap-2.5"
                >
                    <span id="btn-text">KIRIM PENDAFTARAN SEKARANG</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>

    </div>

    <script>
        // Anti-Double Submit Handler
        let isSubmitting = false;

        function handleSubmit(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }

            const btn = document.getElementById('btn-submit');
            const btnText = document.getElementById('btn-text');

            isSubmitting = true;
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');

            btnText.innerHTML = `
                <svg class="animate-spin h-4 w-4 text-white inline-block mr-1" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>SEDANG MENGIRIM...</span>
            `;

            return true;
        }

        // Auto Scroll to Alert if Present
        document.addEventListener('DOMContentLoaded', () => {
            const alertBox = document.getElementById('alert-box');
            if (alertBox) {
                alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>

</body>
</html>
