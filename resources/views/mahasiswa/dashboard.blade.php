<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - POKJA HIMA IF</title>

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
    <!-- QRCode.js Library CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

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

        .card-interactive {
            transition: transform 0.15s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.15s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .card-interactive:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px 0px #000000 !important;
        }
    </style>
</head>
<body class="min-h-screen text-[#000000] flex flex-col justify-between antialiased selection:bg-[#2A82C6] selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b-2 border-black sticky top-0 z-30 shadow-[0_2px_0px_0px_#000000]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img 
                    src="{{ asset('logo/logo.png') }}" 
                    alt="Logo HIMA IF" 
                    class="w-9 h-9 object-contain bg-white p-1 border-2 border-black shadow-[2px_2px_0px_0px_#000000]"
                >
                <div>
                    <span class="font-black text-xs uppercase tracking-wider text-[#1A467C] block">POKJA HIMA IF</span>
                    <span class="text-[10px] text-gray-600 font-bold uppercase tracking-wider block">Portal Mahasiswa</span>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <a 
                    href="{{ route('presensi.absen') }}" 
                    class="btn-smooth inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#15803d] hover:bg-green-800 text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000]"
                    title="Buka Kamera Scanner Presensi"
                >
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span class="hidden sm:inline">Scan Presensi</span>
                </a>

                <form action="{{ route('mahasiswa.logout') }}" method="POST" class="inline">
                    @csrf
                    <button 
                        type="submit" 
                        class="btn-smooth px-3 py-1.5 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                        title="Keluar dari Akun Mahasiswa"
                    >
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-8 w-full space-y-6">

        <!-- Banner Sambutan Mahasiswa -->
        <div class="card-interactive bg-white border-2 border-black p-5 sm:p-6 shadow-[5px_5px_0px_0px_#000000]">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="inline-block bg-[#1A467C] text-white text-[10px] font-black uppercase px-2.5 py-0.5 tracking-wider border border-black mb-1.5 shadow-[1px_1px_0px_0px_#000000]">
                        PESERTA POKJA
                    </span>
                    <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-gray-950">
                        {{ $mahasiswa->nama }}
                    </h2>
                    <p class="text-xs font-mono font-bold text-gray-600 mt-1">
                        NIM: <span class="text-[#1A467C] font-black">{{ $mahasiswa->nim }}</span> • Kelas: <span class="bg-blue-50 border border-[#2A82C6] text-[#1A467C] px-2 py-0.5 text-xs font-black">{{ $mahasiswa->kelas }}</span>
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    @if (!empty($pengaturan->link_grup_wa))
                        <a 
                            href="{{ $pengaturan->link_grup_wa }}" 
                            target="_blank"
                            class="btn-smooth inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#25D366] hover:bg-green-600 text-black border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000]"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.58 1.961.92 2.796.92 3.183 0 5.769-2.587 5.77-5.766.001-3.187-2.575-5.77-5.77-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.071.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z" />
                            </svg>
                            <span>Grup WA</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- 3 Stat Cards Ringkas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
            <div class="card-interactive bg-white border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-[#1A467C]">Total Kehadiran</span>
                    <h3 class="text-2xl font-black text-gray-950 font-mono mt-0.5">{{ $totalHadir }} / 7</h3>
                </div>
                <span class="text-[10px] font-black text-[#1A467C] bg-blue-50 border border-[#2A82C6] px-2 py-0.5 uppercase">Pertemuan</span>
            </div>

            <div class="card-interactive bg-white border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-green-800">Persentase Hadir</span>
                    <h3 class="text-2xl font-black text-green-700 font-mono mt-0.5">{{ $persenHadir }}%</h3>
                </div>
                <span class="text-[10px] font-black text-green-900 bg-green-50 border border-green-700 px-2 py-0.5 uppercase">Progres</span>
            </div>

            <div class="card-interactive bg-white border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-600">Sesi Berjalan</span>
                    <h3 class="text-2xl font-black text-[#1A467C] font-mono mt-0.5">P{{ $pengaturan->pertemuan_aktif }}</h3>
                </div>
                <span class="text-[10px] font-black {{ $pengaturan->is_scan_aktif ? 'text-green-900 bg-green-50 border border-green-700' : 'text-gray-700 bg-gray-100 border border-gray-400' }} px-2 py-0.5 uppercase">
                    {{ $pengaturan->is_scan_aktif ? 'Scan Aktif' : 'Tutup' }}
                </span>
            </div>
        </div>

        <!-- Rekapitulasi Presensi 7 Pertemuan -->
        <div class="bg-white border-2 border-black shadow-[6px_6px_0px_0px_#000000] p-5 sm:p-6 space-y-4">
            <div class="border-b-2 border-black pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black uppercase tracking-tight text-[#1A467C]">
                        Rekapitulasi Kehadiran 7 Pertemuan
                    </h3>
                    <p class="text-xs font-semibold text-gray-600 mt-0.5">
                        Daftar riwayat kehadiran Anda pada setiap pertemuan POKJA.
                    </p>
                </div>
                <span class="text-xs font-mono font-bold text-gray-700 bg-gray-100 border border-black px-2.5 py-1">
                    {{ $totalHadir }}/7 Hadir
                </span>
            </div>

            <!-- Grid 7 Pertemuan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                @for ($p = 1; $p <= 7; $p++)
                    @php
                        $presensi = $presensiMap->get($p);
                        $isHadir = ($presensi && $presensi->status === 'hadir');
                        $isCurrent = ($pengaturan->pertemuan_aktif === $p);
                    @endphp
                    <div class="card-interactive border-2 border-black p-3.5 flex flex-col justify-between shadow-[3px_3px_0px_0px_#000000] {{ $isHadir ? 'bg-green-50 border-green-900' : ($isCurrent ? 'bg-blue-50 border-[#2A82C6]' : 'bg-white') }}">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-black text-sm {{ $isHadir ? 'text-green-900' : 'text-gray-950' }}">
                                P{{ $p }}
                            </span>
                            @if ($isHadir)
                                <span class="w-5 h-5 bg-[#15803d] text-white border border-black flex items-center justify-center text-[10px] font-black">
                                    ✓
                                </span>
                            @elseif ($isCurrent && $pengaturan->is_scan_aktif)
                                <span class="w-2.5 h-2.5 bg-green-500 border border-black animate-ping"></span>
                            @else
                                <span class="text-[10px] font-mono text-gray-400">-</span>
                            @endif
                        </div>

                        <div class="mt-3 pt-2 border-t border-black/10">
                            @if ($isHadir)
                                <span class="text-[10px] font-black uppercase text-green-800 block">HADIR</span>
                                <span class="text-[9px] font-mono text-gray-500 block truncate">
                                    {{ $presensi->waktu_hadir ? $presensi->waktu_hadir->timezone('Asia/Jakarta')->format('H:i') . ' WIB' : '-' }}
                                </span>
                            @else
                                <span class="text-[10px] font-bold uppercase text-gray-400 block">BELUM</span>
                                <span class="text-[9px] font-mono text-gray-400 block">-</span>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Kartu Tiket & QR Code Peserta -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Detail Informasi Peserta -->
            <div class="md:col-span-2 bg-white border-2 border-black p-5 sm:p-6 shadow-[5px_5px_0px_0px_#000000] space-y-4">
                <h3 class="text-sm font-black uppercase tracking-wider text-[#1A467C] border-b-2 border-black pb-2">
                    Informasi Registrasi Peserta
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="bg-gray-50 border border-black p-3">
                        <span class="text-[10px] font-bold text-gray-500 uppercase block">Nama Lengkap</span>
                        <span class="font-black text-gray-950 block mt-0.5">{{ $mahasiswa->nama }}</span>
                    </div>

                    <div class="bg-gray-50 border border-black p-3">
                        <span class="text-[10px] font-bold text-gray-500 uppercase block">NIM Kampus</span>
                        <span class="font-mono font-black text-gray-950 block mt-0.5">{{ $mahasiswa->nim }}</span>
                    </div>

                    <div class="bg-gray-50 border border-black p-3">
                        <span class="text-[10px] font-bold text-gray-500 uppercase block">Kelas</span>
                        <span class="font-mono font-black text-gray-950 block mt-0.5">{{ $mahasiswa->kelas }}</span>
                    </div>

                    <div class="bg-gray-50 border border-black p-3">
                        <span class="text-[10px] font-bold text-gray-500 uppercase block">No. WhatsApp</span>
                        <span class="font-mono font-bold text-green-700 block mt-0.5">{{ $mahasiswa->no_telp }}</span>
                    </div>

                    <div class="sm:col-span-2 bg-gray-50 border border-black p-3">
                        <span class="text-[10px] font-bold text-gray-500 uppercase block">Email Kampus Resmi</span>
                        <span class="font-mono font-bold text-gray-900 block mt-0.5">{{ $mahasiswa->email ?? '-' }}</span>
                    </div>
                </div>

                <div class="pt-2 flex flex-wrap items-center gap-2">
                    <a 
                        href="{{ route('presensi.absen') }}" 
                        class="btn-smooth inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#2A82C6] hover:bg-[#1A467C] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000]"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        <span>Buka Scanner Presensi</span>
                    </a>
                </div>
            </div>

            <!-- Kartu QR Identitas Mahasiswa -->
            <div class="bg-white border-2 border-black p-5 shadow-[5px_5px_0px_0px_#000000] flex flex-col items-center justify-between text-center space-y-3">
                <span class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                    QR Identitas Peserta
                </span>

                <div class="p-3 bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000000]">
                    <div id="qrcode-peserta" class="w-36 h-36 flex items-center justify-center"></div>
                </div>

                <p class="text-[11px] font-mono font-bold text-gray-600">
                    ID: #{{ str_pad($mahasiswa->id, 4, '0', STR_PAD_LEFT) }} • {{ $mahasiswa->nim }}
                </p>
            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full text-center py-4 text-xs font-bold text-gray-500 border-t-2 border-black bg-white">
        &copy; {{ date('Y') }} POKJA HIMA IF • Sistem Terpadu Registrasi & Presensi
    </footer>

    <!-- Render QR Code Script & Auto Fill NIM on Scan Page -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Render QR Mahasiswa
            const qrTarget = document.getElementById('qrcode-peserta');
            if (qrTarget) {
                new QRCode(qrTarget, {
                    text: "POKJA:MHS:{{ $mahasiswa->nim }}",
                    width: 144,
                    height: 144,
                    colorDark: "#000000",
                    colorLight: "#FFFFFF",
                    correctLevel: QRCode.CorrectLevel.M
                });
            }

            // Simpan NIM otomatis ke localStorage agar pas buka scanner langsung terisi
            localStorage.setItem('pokja_presensi_nim', '{{ $mahasiswa->nim }}');
        });
    </script>

</body>
</html>
