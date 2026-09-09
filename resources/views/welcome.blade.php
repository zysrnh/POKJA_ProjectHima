<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Pendaftaran {{ $pengaturan->nama_acara ?? 'POKJA' }} - HIMA IF</title>
    
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
                            blue: '#2A82C6',
                            navy: '#1A467C',
                            red: '#CA2C2A',
                            maroon: '#901C1A',
                        }
                    },
                    boxShadow: {
                        'brutal-sm': '2px 2px 0px 0px #000000',
                        'brutal': '4px 4px 0px 0px #000000',
                        'brutal-lg': '6px 6px 0px 0px #000000',
                        'brutal-navy': '5px 5px 0px 0px #1A467C',
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
<body class="bg-[#F4F4F0] text-gray-900 min-h-screen flex items-center justify-center p-3.5 sm:p-6 lg:p-8 antialiased selection:bg-[#2A82C6] selection:text-white">

    <!-- Main Neubrutalism Card Container -->
    <div class="w-full max-w-xl bg-white border-2 sm:border-[2.5px] border-black p-5 sm:p-8 shadow-[6px_6px_0px_0px_#000000] sm:shadow-[8px_8px_0px_0px_#1A467C]">
        
        <!-- Header Top Badges -->
        <div class="flex items-center justify-between gap-2 mb-4">
            <div class="flex items-center gap-2">
                <span class="bg-[#CA2C2A] text-white border-2 border-black px-2.5 py-0.5 text-[10px] sm:text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000]">
                    POKJA 2026
                </span>
                <span class="bg-[#2A82C6] text-white border-2 border-black px-2.5 py-0.5 text-[10px] sm:text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000]">
                    HIMA IF
                </span>
            </div>
            <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-600 font-mono">
                REGISTRATION OPEN
            </span>
        </div>

        <!-- Header Title & Logo -->
        <div class="border-b-2 border-black pb-5 mb-5 flex items-center gap-4">
            <img 
                src="{{ asset('logo/logo.png') }}" 
                alt="Logo HIMA IF" 
                class="w-16 h-16 sm:w-20 sm:h-20 object-contain rounded-full border-2 border-black p-1 bg-white shrink-0 shadow-[3px_3px_0px_0px_#000000]"
            >
            <div>
                <h1 class="text-lg sm:text-2xl font-black text-black uppercase tracking-tight leading-tight">
                    FORMULIR PENDAFTARAN
                </h1>
                <p class="text-xs sm:text-sm font-semibold text-gray-700 mt-0.5">
                    Himpunan Mahasiswa Informatika
                </p>
            </div>
        </div>

        <!-- Theme Showcase Box (Neubrutalism Hero Accent) -->
        <div class="mb-5 bg-[#1A467C] text-white border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000]">
            <div class="flex items-center justify-between mb-1.5">
                <span class="bg-[#CA2C2A] text-white border border-black px-2 py-0.5 text-[9px] sm:text-[10px] font-black uppercase tracking-widest">
                    TEMA ACARA
                </span>
                <span class="text-[10px] font-mono text-blue-200">#SeminarProposal</span>
            </div>
            <p class="text-base sm:text-lg font-black tracking-wide text-yellow-300 italic uppercase">
                "{{ $pengaturan->deskripsi_acara ?? 'Innovative Idea to Great Proposal' }}"
            </p>
        </div>

        <!-- Event Details: 3-Card Neubrutalism Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
            <!-- Card 1: Tanggal -->
            <div class="bg-blue-50 border-2 border-black p-3 shadow-[3px_3px_0px_0px_#000000]">
                <div class="flex items-center gap-1.5 text-[#1A467C] mb-1">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-[10px] font-black uppercase tracking-wider">Tanggal</span>
                </div>
                <p class="text-xs sm:text-sm font-bold text-gray-900 leading-tight">
                    {{ $pengaturan->tanggal_acara ?? '13 Oktober 2026' }}
                </p>
            </div>

            <!-- Card 2: Waktu -->
            <div class="bg-amber-50 border-2 border-black p-3 shadow-[3px_3px_0px_0px_#000000]">
                <div class="flex items-center gap-1.5 text-amber-900 mb-1">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-[10px] font-black uppercase tracking-wider">Waktu</span>
                </div>
                <p class="text-xs sm:text-sm font-bold text-gray-900 leading-tight">
                    {{ $pengaturan->jam_acara ?? '08:00 WIB - Selesai' }}
                </p>
            </div>

            <!-- Card 3: Lokasi -->
            <div class="bg-red-50 border-2 border-black p-3 shadow-[3px_3px_0px_0px_#000000]">
                <div class="flex items-center gap-1.5 text-[#901C1A] mb-1">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="square" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-[10px] font-black uppercase tracking-wider">Tempat</span>
                </div>
                <p class="text-xs sm:text-sm font-bold text-gray-900 leading-tight">
                    {{ $pengaturan->lokasi_acara ?? 'Ruangan 105' }}
                </p>
            </div>
        </div>

        <!-- Flash Alert Sukses (Neubrutalism Style) -->
        @if (session('success'))
            <div id="alert-box" class="mb-6 p-4 bg-[#DCFCE7] border-2 border-black text-green-950 text-xs sm:text-sm shadow-[4px_4px_0px_0px_#000000]">
                <div class="flex items-start gap-2.5">
                    <span class="bg-green-600 text-white font-black text-xs px-2 py-0.5 border border-black">SUKSES</span>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Alert Error Global (Neubrutalism Style) -->
        @if ($errors->any())
            <div id="alert-box" class="mb-6 p-4 bg-[#FEE2E2] border-2 border-black text-red-950 text-xs sm:text-sm shadow-[4px_4px_0px_0px_#000000]">
                <div class="flex items-center gap-2 mb-2">
                    <span class="bg-[#CA2C2A] text-white font-black text-xs px-2 py-0.5 border border-black">ERROR</span>
                    <span class="font-bold">Terdapat kesalahan pada input Anda:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs font-semibold text-red-900">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Pendaftaran (Neubrutalism) -->
        <form 
            id="form-pendaftaran"
            action="{{ route('pendaftaran.store') }}" 
            method="POST" 
            class="space-y-4 sm:space-y-5"
            onsubmit="handleSubmit(event)"
        >
            @csrf

            <!-- Anti-bot Honeypot Field (Hidden from real users) -->
            <div class="hidden" aria-hidden="true">
                <input type="text" name="website_url" tabindex="-1" autocomplete="off">
            </div>

            <!-- Field: Nama -->
            <div>
                <label for="nama" class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                    Nama Lengkap <span class="text-[#CA2C2A]">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama" 
                    name="nama" 
                    value="{{ session('success') ? '' : old('nama') }}"
                    placeholder="Contoh: Fulan bin Fulan"
                    maxlength="100"
                    required
                    class="w-full bg-white border-2 border-black px-3.5 py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:bg-amber-50/20 focus:shadow-[3px_3px_0px_0px_#000000] transition duration-150 @error('nama') border-red-600 bg-red-50/50 @enderror"
                >
                @error('nama')
                    <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: NIM -->
            <div>
                <label for="nim" class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
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
                    class="w-full bg-white border-2 border-black px-3.5 py-2.5 text-sm font-semibold font-mono text-gray-900 focus:outline-none focus:bg-amber-50/20 focus:shadow-[3px_3px_0px_0px_#000000] transition duration-150 @error('nim') border-red-600 bg-red-50/50 @enderror"
                >
                @error('nim')
                    <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field: Kelas (Dropdown Dinamis) -->
            <div>
                <label for="kelas" class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                    Kelas <span class="text-[#CA2C2A]">*</span>
                </label>
                <select 
                    id="kelas" 
                    name="kelas" 
                    required
                    class="w-full bg-white border-2 border-black px-3.5 py-2.5 text-sm font-bold font-mono text-gray-900 focus:outline-none focus:bg-amber-50/20 focus:shadow-[3px_3px_0px_0px_#000000] transition duration-150 cursor-pointer @error('kelas') border-red-600 bg-red-50/50 @enderror"
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

            <!-- Field: No Telp -->
            <div>
                <label for="no_telp" class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
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
                    class="w-full bg-white border-2 border-black px-3.5 py-2.5 text-sm font-semibold font-mono text-gray-900 focus:outline-none focus:bg-amber-50/20 focus:shadow-[3px_3px_0px_0px_#000000] transition duration-150 @error('no_telp') border-red-600 bg-red-50/50 @enderror"
                >
                @error('no_telp')
                    <p class="mt-1 text-xs font-bold text-[#CA2C2A]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button (Neubrutalism Interactive Action) -->
            <div class="pt-3">
                <button 
                    type="submit" 
                    id="btn-submit"
                    class="w-full bg-[#2A82C6] hover:bg-[#1A467C] active:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-wider py-3.5 px-4 border-2 border-black shadow-[4px_4px_0px_0px_#000000] hover:shadow-[6px_6px_0px_0px_#000000] hover:-translate-x-0.5 hover:-translate-y-0.5 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none transition-all duration-150 cursor-pointer flex items-center justify-center gap-2"
                >
                    <span id="btn-text">KIRIM PENDAFTARAN</span>
                </button>
            </div>
        </form>

        <!-- Footer Info -->
        <div class="mt-6 pt-4 border-t-2 border-black text-center flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] font-semibold text-gray-600">
            <span>© {{ date('Y') }} HIMA IF - Pokja Division</span>
            <span>Pastikan data pendaftaran Anda valid</span>
        </div>
    </div>

    <script>
        // Anti-Double Submit Handler with Neubrutalism Spinner
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
