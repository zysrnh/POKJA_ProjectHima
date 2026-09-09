<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Pendaftaran {{ $pengaturan->nama_acara ?? 'POKJA' }} - HIMA IF</title>
    
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
                            blue: '#2A82C6',
                            navy: '#1A467C',
                            red: '#CA2C2A',
                            maroon: '#901C1A',
                            bg: '#DFECF8',
                            dark: '#0F2642',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #DCEAF7;
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
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(2px);
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        }
    </style>
</head>
<body class="text-gray-900 min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-10 antialiased selection:bg-[#1A467C] selection:text-white relative overflow-x-hidden">

    <!-- Floating Geometric Accents (Neubrutalism Background Details) -->
    <div class="hidden md:block absolute top-8 left-8 w-14 h-14 bg-[#2A82C6]/30 border-2 border-[#1A467C] shadow-[4px_4px_0px_0px_#1A467C] pointer-events-none"></div>
    <div class="hidden md:block absolute bottom-12 left-12 w-10 h-10 bg-[#CA2C2A]/20 border-2 border-[#1A467C] shadow-[3px_3px_0px_0px_#1A467C] pointer-events-none"></div>
    <div class="hidden md:block absolute top-16 right-12 w-12 h-12 bg-white border-2 border-[#1A467C] shadow-[4px_4px_0px_0px_#1A467C] pointer-events-none"></div>
    <div class="hidden md:block absolute bottom-8 right-16 w-16 h-16 bg-[#1A467C]/20 border-2 border-[#1A467C] shadow-[4px_4px_0px_0px_#1A467C] pointer-events-none"></div>

    <!-- Main Container: 2-Column Graphic Design Grid -->
    <div class="w-full max-w-5xl z-10 my-4">

        <!-- Top Header Navigation Bar (Neubrutalism Title & Badges) -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4 sm:mb-6">
            <div class="flex items-center gap-2.5">
                <span class="bg-[#1A467C] text-white border-2 border-[#0F2642] px-3 py-1 text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#0F2642]">
                    POKJA HIMA IF
                </span>
                <span class="bg-[#CA2C2A] text-white border-2 border-[#0F2642] px-3 py-1 text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#0F2642]">
                    EDISI 2026
                </span>
            </div>
            <div class="flex items-center gap-2">
                <span class="bg-white text-[#1A467C] border-2 border-[#1A467C] px-3 py-1 text-xs font-black uppercase tracking-widest shadow-[3px_3px_0px_0px_#1A467C] font-mono">
                    ● REGISTRATION OPEN
                </span>
            </div>
        </div>

        <!-- 2-Column Layout Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            
            <!-- LEFT COLUMN: Event Hero Showcase (Poster Style) -->
            <div class="lg:col-span-6 flex flex-col gap-5">
                
                <!-- Hero Poster Card -->
                <div class="bg-white border-2 border-[#1A467C] p-6 sm:p-7 shadow-[6px_6px_0px_0px_#1A467C] relative">
                    
                    <!-- Top Polaroid + Title Layout -->
                    <div class="flex flex-col sm:flex-row items-start gap-5 mb-5">
                        
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

                        <!-- Main Big Headline (Without awkward "Tema Acara" labels) -->
                        <div class="flex-1">
                            <span class="inline-block bg-[#2A82C6] text-white border-2 border-[#1A467C] px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider mb-2 shadow-[2px_2px_0px_0px_#1A467C]">
                                OFFICIAL WORKSHOP & SEMINAR
                            </span>
                            <h1 class="text-2xl sm:text-3xl lg:text-3xl font-black text-[#1A467C] tracking-tight uppercase leading-tight">
                                {{ $pengaturan->deskripsi_acara ?? 'INNOVATIVE IDEA TO GREAT PROPOSAL' }}
                            </h1>
                            <div class="w-20 h-1.5 bg-[#CA2C2A] mt-2.5 mb-1"></div>
                        </div>
                    </div>

                    <!-- Event Metadata Cards (Schedule & Venue) -->
                    <div class="dot-card border-2 border-[#1A467C] p-4 shadow-[4px_4px_0px_0px_#1A467C] mb-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <!-- Tanggal & Waktu -->
                            <div class="bg-white border-2 border-[#1A467C] p-3 shadow-[2px_2px_0px_0px_#1A467C]">
                                <span class="text-[10px] font-black uppercase tracking-wider text-[#2A82C6] block mb-0.5">
                                    📅 TANGGAL & WAKTU
                                </span>
                                <span class="font-extrabold text-sm text-gray-900 block">
                                    {{ $pengaturan->tanggal_acara ?? '13 Oktober 2026' }}
                                </span>
                                <span class="font-semibold text-xs text-gray-600 block mt-0.5">
                                    {{ $pengaturan->jam_acara ?? '08:00 WIB - Selesai' }}
                                </span>
                            </div>

                            <!-- Lokasi / Ruangan -->
                            <div class="bg-white border-2 border-[#1A467C] p-3 shadow-[2px_2px_0px_0px_#1A467C]">
                                <span class="text-[10px] font-black uppercase tracking-wider text-[#CA2C2A] block mb-0.5">
                                    📍 TEMPAT / RUANGAN
                                </span>
                                <span class="font-extrabold text-sm text-gray-900 block">
                                    {{ $pengaturan->lokasi_acara ?? 'Ruangan 105' }}
                                </span>
                                <span class="font-semibold text-xs text-gray-600 block mt-0.5">
                                    Kampus Utama
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quote Accent Box (Similar to Graphic Design Reference) -->
                    <div class="bg-[#FCFBF7] border-2 border-[#1A467C] p-3.5 shadow-[3px_3px_0px_0px_#1A467C] relative">
                        <span class="text-2xl font-serif text-[#1A467C] absolute -top-3 left-3 bg-[#FCFBF7] px-1 font-black leading-none">“</span>
                        <p class="text-xs font-semibold text-gray-700 italic leading-relaxed pt-1">
                            Wadah pengasahan ide kreatif dan pematangan proposal ilmiah mahasiswa Teknik Informatika untuk kompetisi dan inovasi masa depan.
                        </p>
                        <span class="text-2xl font-serif text-[#1A467C] absolute -bottom-4 right-3 bg-[#FCFBF7] px-1 font-black leading-none">”</span>
                    </div>

                </div>

            </div>

            <!-- RIGHT COLUMN: Registration Form Card -->
            <div class="lg:col-span-6">
                
                <div class="bg-white border-2 border-[#1A467C] p-6 sm:p-8 shadow-[8px_8px_0px_0px_#1A467C] relative">
                    
                    <!-- Form Title Header -->
                    <div class="border-b-2 border-[#1A467C] pb-4 mb-5 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg sm:text-xl font-black text-[#1A467C] uppercase tracking-wide">
                                FORM PENDAFTARAN
                            </h2>
                            <p class="text-xs font-semibold text-gray-500 mt-0.5">
                                Lengkapi data diri Anda untuk konfirmasi kehadiran
                            </p>
                        </div>
                        <span class="bg-[#2A82C6] text-white text-xs font-black px-2 py-1 border-2 border-[#1A467C] shadow-[2px_2px_0px_0px_#1A467C]">
                            STEP 1/1
                        </span>
                    </div>

                    <!-- Flash Alert Sukses (Neubrutalism Style) -->
                    @if (session('success'))
                        <div id="alert-box" class="mb-5 p-3.5 bg-[#DCFCE7] border-2 border-[#1A467C] text-green-950 text-xs sm:text-sm shadow-[4px_4px_0px_0px_#1A467C]">
                            <div class="flex items-start gap-2.5">
                                <span class="bg-green-700 text-white font-black text-xs px-2 py-0.5 border border-black shrink-0">BERHASIL</span>
                                <span class="font-bold pt-0.5">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Alert Error Global (Neubrutalism Style) -->
                    @if ($errors->any())
                        <div id="alert-box" class="mb-5 p-3.5 bg-[#FEE2E2] border-2 border-[#1A467C] text-red-950 text-xs sm:text-sm shadow-[4px_4px_0px_0px_#1A467C]">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="bg-[#CA2C2A] text-white font-black text-xs px-2 py-0.5 border border-black shrink-0">PERIKSA</span>
                                <span class="font-bold">Terdapat kesalahan input:</span>
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 text-xs font-semibold text-red-900">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form Pendaftaran -->
                    <form 
                        id="form-pendaftaran"
                        action="{{ route('pendaftaran.store') }}" 
                        method="POST" 
                        class="space-y-4"
                        onsubmit="handleSubmit(event)"
                    >
                        @csrf

                        <!-- Anti-bot Honeypot Field -->
                        <div class="hidden" aria-hidden="true">
                            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                        </div>

                        <!-- Field: Nama Lengkap -->
                        <div>
                            <label for="nama" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
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
                                class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] px-3.5 py-2.5 text-sm font-semibold text-gray-900 focus:bg-white focus:outline-none focus:shadow-[4px_4px_0px_0px_#1A467C] transition-all duration-150 @error('nama') border-red-600 bg-red-50 @enderror"
                            >
                            @error('nama')
                                <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field: NIM -->
                        <div>
                            <label for="nim" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
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
                                class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] px-3.5 py-2.5 text-sm font-semibold font-mono text-gray-900 focus:bg-white focus:outline-none focus:shadow-[4px_4px_0px_0px_#1A467C] transition-all duration-150 @error('nim') border-red-600 bg-red-50 @enderror"
                            >
                            @error('nim')
                                <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field: Kelas (Dropdown Dinamis) -->
                        <div>
                            <label for="kelas" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                                Kelas <span class="text-[#CA2C2A]">*</span>
                            </label>
                            <select 
                                id="kelas" 
                                name="kelas" 
                                required
                                class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] px-3.5 py-2.5 text-sm font-bold font-mono text-gray-900 focus:bg-white focus:outline-none focus:shadow-[4px_4px_0px_0px_#1A467C] transition-all duration-150 cursor-pointer @error('kelas') border-red-600 bg-red-50 @enderror"
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
                            <label for="no_telp" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
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
                                class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] px-3.5 py-2.5 text-sm font-semibold font-mono text-gray-900 focus:bg-white focus:outline-none focus:shadow-[4px_4px_0px_0px_#1A467C] transition-all duration-150 @error('no_telp') border-red-600 bg-red-50 @enderror"
                            >
                            @error('no_telp')
                                <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button with Neubrutalism Action Effect -->
                        <div class="pt-3">
                            <button 
                                type="submit" 
                                id="btn-submit"
                                class="w-full bg-[#1A467C] hover:bg-[#0F2642] active:bg-[#0F2642] text-white text-xs sm:text-sm font-black uppercase tracking-widest py-3.5 px-4 border-2 border-[#0F2642] shadow-[5px_5px_0px_0px_#0F2642] hover:shadow-[7px_7px_0px_0px_#0F2642] hover:-translate-x-0.5 hover:-translate-y-0.5 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none transition-all duration-150 cursor-pointer flex items-center justify-center gap-2"
                            >
                                <span id="btn-text">KIRIM PENDAFTARAN SEKARANG</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="square" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>

                    <!-- Footer Info -->
                    <div class="mt-5 pt-3 border-t-2 border-[#1A467C] flex items-center justify-between text-[11px] font-bold text-[#1A467C]">
                        <span>✓ Sistem Pendaftaran Resmi</span>
                        <span>HIMA IF © {{ date('Y') }}</span>
                    </div>

                </div>

            </div>

        </div>

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
