<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Presensi Kehadiran Kelas - POKJA HIMA IF</title>

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
    <!-- HTML5-QRCode Library for Fast In-Browser Mobile Camera Scanning -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

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

        @keyframes modalSlam {
            0% {
                transform: translateY(-40px) scale(0.92);
            }
            65% {
                transform: translateY(8px) scale(1.02);
            }
            100% {
                transform: translateY(0) scale(1);
            }
        }
        .modal-slam {
            animation: modalSlam 0.32s cubic-bezier(0.18, 0.89, 0.32, 1.28) forwards;
        }
    </style>
</head>
<body class="min-h-screen text-[#000000] flex flex-col justify-between p-3 sm:p-6 select-none antialiased">

    <!-- ============================================================== -->
    <!-- HEADER BAR                                                     -->
    <!-- ============================================================== -->
    <header class="w-full max-w-md mx-auto bg-white border-2 border-black p-3.5 sm:p-4 shadow-[4px_4px_0px_0px_#000000] flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <img 
                src="{{ asset('logo/logo.png') }}" 
                alt="Logo HIMA IF" 
                class="w-8 h-8 object-contain bg-white p-0.5 border border-black shadow-[1px_1px_0px_0px_#000000]"
            >
            <div>
                <span class="font-black text-xs uppercase tracking-wider text-[#1A467C] block leading-none">POKJA HIMA IF</span>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tight block mt-0.5">Presensi Kehadiran</span>
            </div>
        </div>

        <div class="bg-[#1A467C] border border-black px-2.5 py-1 text-white shadow-[1px_1px_0px_0px_#000000]">
            <span class="text-[10px] font-black uppercase tracking-wider">
                Sesi {{ $pengaturan->pertemuan_aktif ?? 1 }}
            </span>
        </div>
    </header>

    <!-- ============================================================== -->
    <!-- MAIN CONTAINER                                                 -->
    <!-- ============================================================== -->
    <main class="w-full max-w-md mx-auto my-4 space-y-4">

        <!-- Card Identitas Mahasiswa -->
        <div class="bg-white border-2 border-black p-4 sm:p-5 shadow-[4px_4px_0px_0px_#000000] space-y-3">
            <div class="flex items-center justify-between border-b-2 border-black pb-2">
                <span class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                    1. Identitas Mahasiswa
                </span>
                @if ($mahasiswaLogin)
                    <span class="text-[9px] font-black uppercase tracking-wider bg-green-100 text-green-900 border border-green-800 px-2 py-0.5 shadow-[1px_1px_0px_0px_#000000]">
                        ✓ AKUN TERHUBUNG
                    </span>
                @else
                    <span id="nim-badge-saved" class="hidden text-[9px] font-black uppercase tracking-wider bg-green-100 text-green-900 border border-green-800 px-1.5 py-0.5">
                        NIM VALID
                    </span>
                @endif
            </div>

            @if ($mahasiswaLogin)
                <!-- Identitas Terkunci dari Login Mahasiswa -->
                <div class="pt-1">
                    <input type="hidden" id="input-nim" value="{{ $mahasiswaLogin->nim }}">
                    <h3 class="text-base font-black text-gray-950 uppercase">
                        {{ $mahasiswaLogin->nama }}
                    </h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="font-mono font-bold text-xs text-[#1A467C] bg-blue-50 border border-black px-2 py-0.5">
                            NIM: {{ $mahasiswaLogin->nim }}
                        </span>
                        <span class="font-bold text-xs text-gray-800 bg-gray-100 border border-black px-2 py-0.5">
                            Kelas: {{ $mahasiswaLogin->kelas }}
                        </span>
                    </div>
                </div>
            @else
                <!-- Input Manual NIM untuk Publik non-login -->
                <div>
                    <label for="input-nim" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        Nomor Induk Mahasiswa (NIM) <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="input-nim" 
                            inputmode="numeric"
                            pattern="[0-9]*"
                            maxlength="9" 
                            placeholder="Contoh: 250414010" 
                            class="w-full bg-[#F8FBFE] border-2 border-black text-gray-950 font-mono font-black text-base px-3.5 py-2.5 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#000000] transition tracking-widest"
                        >
                        <span id="nim-check" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-green-700 font-black text-lg">
                            ✓
                        </span>
                    </div>
                    <p id="nim-feedback" class="text-[11px] font-semibold text-gray-500 mt-1">
                        Masukkan 9 digit NIM resmi Anda yang terdaftar di POKJA.
                    </p>
                </div>
            @endif
        </div>

        <!-- Card Kamera Scanner QR Proyektor -->
        <div class="bg-white border-2 border-black p-4 sm:p-5 shadow-[4px_4px_0px_0px_#000000] space-y-3">
            <div class="border-b-2 border-black pb-2 flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                    2. Scan QR di Layar Proyektor
                </span>
                <span class="text-[10px] font-mono font-bold text-gray-500">LIVE SCANNER</span>
            </div>

            <!-- Tombol Buka Kamera -->
            <div id="btn-camera-wrapper">
                <button 
                    type="button" 
                    id="btn-start-camera"
                    onclick="startCameraScanner()"
                    class="btn-smooth w-full py-3.5 bg-[#2A82C6] hover:bg-[#1A467C] active:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center gap-2 cursor-pointer"
                >
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="square" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Scan QR</span>
                </button>
            </div>

            <!-- Area Video Scanner Kamera -->
            <div id="camera-container" class="hidden space-y-3">
                <div class="relative bg-black border-2 border-black overflow-hidden shadow-[3px_3px_0px_0px_#000000]">
                    <div id="qr-reader" class="w-full"></div>
                </div>

                <button 
                    type="button" 
                    onclick="stopCameraScanner()"
                    class="btn-smooth w-full py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    Tutup Kamera
                </button>
            </div>

            <!-- Keterangan Minimalis Refresh 5 Detik -->
            <p class="text-center text-xs font-semibold text-gray-500 pt-1">
                QR di proyektor refresh otomatis tiap 5 detik.
            </p>
        </div>

        @if ($mahasiswaLogin)
            <div class="text-center">
                <a 
                    href="{{ route('mahasiswa.dashboard') }}" 
                    class="btn-smooth inline-flex items-center gap-1.5 px-4 py-2 bg-white text-gray-900 border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000]"
                >
                    ← Kembali ke Dashboard Mahasiswa
                </a>
            </div>
        @endif

    </main>

    <!-- ============================================================== -->
    <!-- RESULT MODAL POP-UP (Solid Tactile Feedback)                  -->
    <!-- ============================================================== -->
    <div id="result-modal" class="hidden fixed inset-0 z-50 bg-black/60 p-4 flex items-center justify-center">
        <div class="modal-slam w-full max-w-sm bg-white border-[3px] border-black p-5 sm:p-6 shadow-[8px_8px_0px_0px_#000000] space-y-4 text-center">
            
            <div id="modal-icon-wrapper" class="w-14 h-14 mx-auto border-2 border-black flex items-center justify-center font-black text-2xl shadow-[3px_3px_0px_0px_#000000]">
                <!-- Icon via JS -->
            </div>

            <div>
                <span id="modal-badge" class="inline-block text-[10px] font-black uppercase px-2 py-0.5 border border-black shadow-[1px_1px_0px_0px_#000000] mb-1">
                    STATUS
                </span>
                <h3 id="modal-title" class="text-base sm:text-lg font-black uppercase text-gray-950">
                    HASIL PRESENSI
                </h3>
                <p id="modal-desc" class="text-xs font-semibold text-gray-700 mt-1">
                    Keterangan
                </p>
            </div>

            <!-- Student Detail Info Card -->
            <div id="modal-student-box" class="hidden bg-blue-50 border-2 border-black p-3 text-left space-y-1 text-xs">
                <div class="flex justify-between">
                    <span class="font-bold text-gray-600">NAMA:</span>
                    <span id="detail-nama" class="font-black text-gray-950 truncate"></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold text-gray-600">NIM / KELAS:</span>
                    <span id="detail-nim-kelas" class="font-black text-gray-950 font-mono"></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold text-gray-600">WAKTU SCAN:</span>
                    <span id="detail-waktu" class="font-black text-green-800 font-mono"></span>
                </div>
            </div>

            <button 
                type="button" 
                onclick="closeResultModal()"
                class="btn-smooth w-full py-2.5 bg-[#2A82C6] hover:bg-[#1A467C] text-white text-xs font-black uppercase tracking-wider border-2 border-black shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
            >
                MENGERTI
            </button>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- FOOTER                                                         -->
    <!-- ============================================================== -->
    <footer class="w-full max-w-md mx-auto text-center text-[10px] font-bold text-gray-500 py-2">
        POKJA HIMA IF • Institut Digital Ekonomi LPKIA
    </footer>

    <!-- ============================================================== -->
    <!-- JAVASCRIPT: HTML5 QR Scanner & AJAX Submission                 -->
    <!-- ============================================================== -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let html5QrCode = null;
        let isProcessing = false;
        let isNimValid = {{ $mahasiswaLogin ? 'true' : 'false' }};

        document.addEventListener('DOMContentLoaded', () => {
            const inputNIM = document.getElementById('input-nim');

            @if (!$mahasiswaLogin)
                // Cek LocalStorage untuk NIM tersimpan
                const savedNIM = localStorage.getItem('pokja_mhs_nim');
                if (savedNIM && savedNIM.length === 9) {
                    inputNIM.value = savedNIM;
                    verifikasiNimServer(savedNIM);
                }

                inputNIM.addEventListener('input', (e) => {
                    const val = e.target.value.replace(/[^0-9]/g, '');
                    e.target.value = val;

                    if (val.length === 9) {
                        verifikasiNimServer(val);
                    } else {
                        isNimValid = false;
                        document.getElementById('nim-check').classList.add('hidden');
                        const fb = document.getElementById('nim-feedback');
                        fb.className = "text-[11px] font-semibold text-gray-500 mt-1";
                        fb.innerText = "Masukkan 9 digit NIM resmi Anda yang terdaftar di POKJA.";
                    }
                });
            @endif
        });

        // Verifikasi ke server apakah NIM benar-benar terdaftar
        async function verifikasiNimServer(nim) {
            const checkEl = document.getElementById('nim-check');
            const fb = document.getElementById('nim-feedback');
            fb.className = "text-[11px] font-semibold text-blue-700 mt-1";
            fb.innerText = "Memeriksa NIM ke database...";

            try {
                const res = await fetch("{{ route('presensi.cekNim') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ nim: nim })
                });

                const data = await res.json();

                if (res.ok && data.valid) {
                    isNimValid = true;
                    localStorage.setItem('pokja_mhs_nim', nim);
                    checkEl.classList.remove('hidden');
                    fb.className = "text-[11px] font-bold text-green-700 mt-1";
                    fb.innerText = `✓ Terdaftar: ${data.nama} (${data.kelas})`;
                } else {
                    isNimValid = false;
                    checkEl.classList.add('hidden');
                    fb.className = "text-[11px] font-bold text-red-600 mt-1";
                    fb.innerText = `✗ ${data.message || 'NIM tidak terdaftar di sistem POKJA'}`;
                }
            } catch (err) {
                isNimValid = false;
                checkEl.classList.add('hidden');
                fb.className = "text-[11px] font-semibold text-red-600 mt-1";
                fb.innerText = "Gagal memverifikasi NIM. Periksa koneksi internet.";
            }
        }

        // Buka Kamera Scanner dengan Fallback Kompatibilitas Tinggi
        async function startCameraScanner() {
            const nim = document.getElementById('input-nim').value.trim();

            if (!nim || nim.length !== 9) {
                alert("Harap masukkan 9 digit NIM terdaftar Anda terlebih dahulu!");
                document.getElementById('input-nim')?.focus();
                return;
            }

            if (!isNimValid) {
                alert("NIM Anda tidak valid atau tidak terdaftar di sistem. Harap periksa kembali.");
                return;
            }

            document.getElementById('btn-camera-wrapper').classList.add('hidden');
            document.getElementById('camera-container').classList.remove('hidden');

            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("qr-reader");
            }

            const config = {
                fps: 10,
                qrbox: { width: 220, height: 220 },
                aspectRatio: 1.0
            };

            // 1. Coba kamera belakang (environment)
            try {
                await html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    onScanSuccess,
                    (err) => { /* abaikan error frame scanner */ }
                );
            } catch (envErr) {
                // 2. Fallback jika device tidak mendukung facingMode environment (misal laptop webcam)
                try {
                    await html5QrCode.start(
                        { facingMode: "user" },
                        config,
                        onScanSuccess,
                        (err) => { /* abaikan */ }
                    );
                } catch (userErr) {
                    alert("Tidak dapat mengakses kamera. Pastikan Anda telah memberikan izin akses kamera di browser.");
                    stopCameraScanner();
                }
            }
        }

        // Tutup Kamera Scanner
        async function stopCameraScanner() {
            if (html5QrCode && html5QrCode.isScanning) {
                try {
                    await html5QrCode.stop();
                } catch (e) {
                    // Ignore
                }
            }
            document.getElementById('camera-container').classList.add('hidden');
            document.getElementById('btn-camera-wrapper').classList.remove('hidden');
        }

        // Callback saat QR Berhasil Terbaca Kamera
        async function onScanSuccess(decodedText) {
            if (isProcessing) return;
            isProcessing = true;

            const nim = document.getElementById('input-nim').value.trim();

            // Getar HP jika didukung
            if (navigator.vibrate) {
                navigator.vibrate([100, 50, 100]);
            }

            await stopCameraScanner();

            // Submit ke Backend
            try {
                const response = await fetch("{{ route('presensi.scan') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nim: nim,
                        qr_payload: decodedText
                    })
                });

                const data = await response.json();
                showResultModal(data, response.ok);

            } catch (err) {
                showResultModal({
                    success: false,
                    message: "Gagal terhubung ke server. Periksa koneksi internet Anda."
                }, false);
            } finally {
                isProcessing = false;
            }
        }

        // Tampilkan Modal Hasil Presensi
        function showResultModal(data, isHttpOk) {
            const modal = document.getElementById('result-modal');
            const iconWrapper = document.getElementById('modal-icon-wrapper');
            const badge = document.getElementById('modal-badge');
            const title = document.getElementById('modal-title');
            const desc = document.getElementById('modal-desc');
            const studentBox = document.getElementById('modal-student-box');

            iconWrapper.className = "w-16 h-16 mx-auto border-2 border-black flex items-center justify-center font-black text-3xl shadow-[3px_3px_0px_0px_#000000] transition-transform duration-300 scale-105";
            badge.className = "inline-block text-[10px] font-black uppercase px-2 py-0.5 border border-black shadow-[1px_1px_0px_0px_#000000] mb-1";

            if (isHttpOk && data.success) {
                if (navigator.vibrate) navigator.vibrate([80, 40, 120]);
                iconWrapper.classList.add('bg-green-400', 'text-black');
                iconWrapper.innerHTML = '✓';
                badge.classList.add('bg-green-100', 'text-green-900');
                badge.innerText = data.already ? 'SUDAH TERCATAT HADIR' : 'BERHASIL HADIR';
                title.innerText = 'PRESENSI BERHASIL!';
                desc.innerText = data.message || 'Kehadiran Anda pada sesi ini telah berhasil dicatat.';

                const info = data.data || data;
                if (info && info.nama) {
                    document.getElementById('detail-nama').innerText = info.nama || '-';
                    document.getElementById('detail-nim-kelas').innerText = `${info.nim || '-'} (${info.kelas || '-'})`;
                    document.getElementById('detail-waktu').innerText = info.waktu || 'Baru Saja';
                    studentBox.classList.remove('hidden');
                } else {
                    studentBox.classList.add('hidden');
                }
            } else {
                iconWrapper.classList.add('bg-red-400', 'text-black');
                iconWrapper.innerHTML = '✕';
                badge.classList.add('bg-red-100', 'text-red-900');
                badge.innerText = 'PRESENSI GAGAL';
                title.innerText = 'Gagal Melakukan Presensi';
                desc.innerText = data.message || 'Terjadi kesalahan sistem saat memproses presensi.';
                studentBox.classList.add('hidden');
            }

            modal.classList.remove('hidden');
        }

        function closeResultModal() {
            document.getElementById('result-modal').classList.add('hidden');
        }
    </script>

</body>
</html>
