<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Mahasiswa - POKJA HIMA IF</title>

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
    <!-- QR Code Scanning Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E8F2FA;
            background-image: radial-gradient(#1A467C 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }

        /* Tactile Button Press (Tanpa Opacity / Tanpa Fade) */
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

        /* Card Scale Snap-in (Solid Tanpa Opacity) */
        @keyframes cardSnapIn {
            0% {
                transform: translateY(-16px) scale(0.96);
            }
            70% {
                transform: translateY(2px) scale(1.01);
            }
            100% {
                transform: translateY(0) scale(1);
            }
        }
        .snap-card {
            animation: cardSnapIn 0.28s cubic-bezier(0.18, 0.89, 0.32, 1.28) forwards;
        }

        /* Tab Switch Snap Animation */
        @keyframes tabSnap {
            0% { transform: translateY(4px) scale(0.99); }
            100% { transform: translateY(0) scale(1); }
        }
        .tab-snap {
            animation: tabSnap 0.18s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }
    </style>
</head>
<body class="min-h-screen text-[#000000] flex flex-col justify-between p-4 sm:p-6 antialiased selection:bg-[#2A82C6] selection:text-white">

    <div></div>

    <!-- Main Login Card Container -->
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
                        Portal Mahasiswa POKJA
                    </h1>
                    <p class="text-xs font-semibold text-gray-600 mt-0.5">
                        Masuk untuk melihat tiket, kartu peserta & rekap presensi.
                    </p>
                </div>
            </div>

            <!-- Flash Alert Error -->
            @if (session('error') || $errors->any())
                <div class="p-3.5 bg-red-50 border-2 border-black text-xs font-bold text-red-900 shadow-[3px_3px_0px_0px_#CA2C2A]">
                    {{ session('error') ?? $errors->first() }}
                </div>
            @endif

            <!-- Flash Alert Success -->
            @if (session('success'))
                <div class="p-3.5 bg-green-50 border-2 border-black text-xs font-bold text-green-900 shadow-[3px_3px_0px_0px_#15803d]">
                    {{ session('success') }}
                </div>
            @endif

            <!-- 2 TABS SELECTION: LOGIN & AKTIVASI AKUN -->
            <div class="grid grid-cols-2 gap-2 border-2 border-black p-1 bg-gray-100 shadow-[2px_2px_0px_0px_#000000]">
                <button 
                    type="button" 
                    id="tab-btn-biasa" 
                    onclick="switchTab('biasa')"
                    class="btn-tactile py-2 text-xs font-black uppercase tracking-wider border-2 border-black bg-white text-[#1A467C] shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    Login
                </button>
                <button 
                    type="button" 
                    id="tab-btn-qr" 
                    onclick="switchTab('qr')"
                    class="btn-tactile py-2 text-xs font-black uppercase tracking-wider border-2 border-transparent text-gray-600 hover:text-black cursor-pointer"
                >
                    Aktivasi Akun
                </button>
            </div>

            <!-- ============================================================== -->
            <!-- TAB 1: FORM LOGIN BIASA (Email / NIM + Password)               -->
            <!-- ============================================================== -->
            <div id="panel-biasa" class="tab-snap space-y-4">
                <form action="{{ route('mahasiswa.login.post') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Field: Identifier (Email Kampus atau NIM) -->
                    <div>
                        <label for="identifier" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                            Email Kampus / NIM <span class="text-[#CA2C2A]">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="identifier" 
                                name="identifier" 
                                value="{{ old('identifier') }}"
                                required
                                autofocus
                                placeholder="Contoh: 250414010 atau email kamu"
                                class="w-full bg-[#F8FBFE] border-2 border-black text-gray-950 font-bold text-xs sm:text-sm px-3.5 py-2.5 pl-10 focus:bg-white focus:outline-none focus:translate-x-[-1px] focus:translate-y-[-1px] focus:shadow-[4px_4px_0px_0px_#000000] transition"
                            >
                            <svg class="w-4 h-4 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Field: Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-black uppercase tracking-wider text-[#1A467C]">
                                Password <span class="text-[#CA2C2A]">*</span>
                            </label>
                            <button 
                                type="button" 
                                onclick="switchTab('qr')" 
                                class="text-[10px] font-bold text-[#2A82C6] hover:underline cursor-pointer"
                            >
                                Belum punya password?
                            </button>
                        </div>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                placeholder="Masukkan password akun Anda..."
                                class="w-full bg-[#F8FBFE] border-2 border-black text-gray-950 font-bold text-xs sm:text-sm px-3.5 py-2.5 pl-10 pr-10 focus:bg-white focus:outline-none focus:translate-x-[-1px] focus:translate-y-[-1px] focus:shadow-[4px_4px_0px_0px_#000000] transition"
                            >
                            <svg class="w-4 h-4 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility('password', 'eye-login')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-black cursor-pointer"
                            >
                                <svg id="eye-login" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="square" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="square" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Login -->
                    <button 
                        type="submit" 
                        class="btn-tactile w-full py-3 bg-[#2A82C6] hover:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-black shadow-[4px_4px_0px_0px_#000000] flex items-center justify-center gap-2 cursor-pointer mt-2"
                    >
                        <span>Masuk</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- ============================================================== -->
            <!-- TAB 2: AKTIVASI QR TIKET PENDAFTARAN (Pertama Kali Login)       -->
            <!-- ============================================================== -->
            <div id="panel-qr" class="hidden tab-snap space-y-4">
                <div class="bg-blue-50 border-2 border-black p-3 text-xs text-blue-950 font-medium shadow-[2px_2px_0px_0px_#1A467C]">
                    <p class="font-black text-[#1A467C] uppercase mb-0.5">Aktivasi Akun Mahasiswa</p>
                    <p>Upload file gambar QR tiket dari email Anda atau scan menggunakan kamera untuk membuat password baru.</p>
                </div>

                <!-- Input File QR Upload -->
                <div class="space-y-2">
                    <input 
                        type="file" 
                        id="qr-file-input" 
                        accept="image/*" 
                        onchange="scanQrFromFile(this)"
                        class="hidden"
                    >
                    <button 
                        type="button" 
                        onclick="document.getElementById('qr-file-input').click()"
                        class="btn-tactile w-full py-3 bg-[#1A467C] hover:bg-[#153660] text-white border-2 border-black font-black text-xs sm:text-sm uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Pilih Gambar QR Tiket</span>
                    </button>
                </div>

                <!-- Atau Buka Kamera HP -->
                <div class="space-y-2">
                    <button 
                        type="button" 
                        id="btn-scan-camera"
                        onclick="toggleCameraScanner()"
                        class="btn-tactile w-full py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Scan Menggunakan Kamera</span>
                    </button>

                    <!-- Camera Viewport Target -->
                    <div id="camera-reader-box" class="hidden border-2 border-black overflow-hidden shadow-[3px_3px_0px_0px_#000000]">
                        <div id="qr-camera-reader" class="w-full"></div>
                    </div>
                </div>

                <!-- Status Feedback Box -->
                <div id="qr-status-msg" class="hidden p-3 text-xs font-bold border-2 border-black shadow-[2px_2px_0px_0px_#000000]"></div>
            </div>

            <!-- Bottom Register Link -->
            <div class="pt-4 border-t-2 border-black text-center space-y-1.5">
                <p class="text-xs font-semibold text-gray-600">
                    Belum mendaftar sebagai peserta?
                </p>
                <a 
                    href="{{ route('pendaftaran.index') }}" 
                    class="btn-tactile inline-block px-4 py-1.5 bg-white hover:bg-gray-100 text-black border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000]"
                >
                    Formulir Registrasi
                </a>
            </div>

        </div>

    </main>

    <!-- Offscreen Scanner Container untuk Html5Qrcode scanFile (mencegah error layout container) -->
    <div id="qr-file-scan-worker" style="position: fixed; left: -9999px; top: 0; width: 320px; height: 320px; pointer-events: none; z-index: -999;"></div>

    <!-- Footer -->
    <footer class="w-full text-center py-2 text-xs font-bold text-gray-500">
        &copy; {{ date('Y') }} POKJA HIMA IF • Sistem Terpadu Registrasi & Presensi
    </footer>

    <!-- JavaScript: Tab Switching, Robust QR Decoder (Multi-Resolution Canvas + Fallbacks), and Camera Scanner -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let html5QrScanner = null;
        let isCameraActive = false;

        function switchTab(mode) {
            const btnBiasa = document.getElementById('tab-btn-biasa');
            const btnQr = document.getElementById('tab-btn-qr');
            const panelBiasa = document.getElementById('panel-biasa');
            const panelQr = document.getElementById('panel-qr');

            if (mode === 'biasa') {
                btnBiasa.className = "btn-tactile py-2 text-xs font-black uppercase tracking-wider border-2 border-black bg-white text-[#1A467C] shadow-[2px_2px_0px_0px_#000000] cursor-pointer";
                btnQr.className = "btn-tactile py-2 text-xs font-black uppercase tracking-wider border-2 border-transparent text-gray-600 hover:text-black cursor-pointer";
                panelBiasa.classList.remove('hidden');
                panelQr.classList.add('hidden');
                stopCameraScanner();
            } else {
                btnQr.className = "btn-tactile py-2 text-xs font-black uppercase tracking-wider border-2 border-black bg-white text-[#1A467C] shadow-[2px_2px_0px_0px_#000000] cursor-pointer";
                btnBiasa.className = "btn-tactile py-2 text-xs font-black uppercase tracking-wider border-2 border-transparent text-gray-600 hover:text-black cursor-pointer";
                panelQr.classList.remove('hidden');
                panelBiasa.classList.add('hidden');
            }
        }

        function togglePasswordVisibility(inputId, eyeId) {
            const input = document.getElementById(inputId);
            if (!input) return;
            input.type = (input.type === 'password') ? 'text' : 'password';
        }

        // Verifikasi QR string ke Backend via AJAX
        async function verifyQrToken(scannedText) {
            showStatus('Memverifikasi QR tiket...', 'info');
            try {
                const response = await fetch("{{ route('mahasiswa.verifikasiQr') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ token: scannedText })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showStatus(`Tiket ${data.nama} (${data.nim}) valid! Mengarahkan ke form password...`, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 800);
                } else {
                    showStatus(data.message || 'QR Tiket tidak valid atau tidak terdaftar.', 'error');
                }
            } catch (err) {
                showStatus('Terjadi kesalahan jaringan saat verifikasi QR.', 'error');
            }
        }

        // Decode QR dari file gambar yang diupload (Multi-Resolution Canvas jsQR + Html5Qrcode Fallback)
        async function scanQrFromFile(input) {
            if (!input.files || input.files.length === 0) return;
            const file = input.files[0];
            showStatus('Membaca QR tiket dari file...', 'info');

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = async function() {
                    let decodedText = null;

                    // 1. Coba decode via jsQR pada resolusi asli
                    if (window.jsQR) {
                        try {
                            const canvas = document.createElement('canvas');
                            canvas.width = img.naturalWidth || img.width;
                            canvas.height = img.naturalHeight || img.height;
                            const ctx = canvas.getContext('2d', { willReadFrequently: true });
                            ctx.drawImage(img, 0, 0);
                            const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                            const code = jsQR(imgData.data, imgData.width, imgData.height, {
                                inversionAttempts: "attemptBoth",
                            });
                            if (code && code.data) {
                                decodedText = code.data;
                            }
                        } catch (err) {
                            console.warn('jsQR original resolution pass error:', err);
                        }

                        // 2. Jika belum terbaca dan resolusi gambar besar (>600px), coba resize ke 500px (sweet-spot QR)
                        if (!decodedText && (img.naturalWidth > 600 || img.naturalHeight > 600)) {
                            try {
                                const targetW = 500;
                                const scale = targetW / img.naturalWidth;
                                const targetH = Math.round(img.naturalHeight * scale);
                                const canvas = document.createElement('canvas');
                                canvas.width = targetW;
                                canvas.height = targetH;
                                const ctx = canvas.getContext('2d', { willReadFrequently: true });
                                ctx.drawImage(img, 0, 0, targetW, targetH);
                                const imgData = ctx.getImageData(0, 0, targetW, targetH);
                                const code = jsQR(imgData.data, targetW, targetH, {
                                    inversionAttempts: "attemptBoth",
                                });
                                if (code && code.data) {
                                    decodedText = code.data;
                                }
                            } catch (err) {
                                console.warn('jsQR resize pass error:', err);
                            }
                        }
                    }

                    if (decodedText) {
                        verifyQrToken(decodedText);
                        return;
                    }

                    // 3. Fallback: Html5Qrcode scanFile via dedicated offscreen container
                    try {
                        const html5Qr = new Html5Qrcode("qr-file-scan-worker");
                        const fallbackText = await html5Qr.scanFile(file, false);
                        await html5Qr.clear();
                        if (fallbackText) {
                            verifyQrToken(fallbackText);
                            return;
                        }
                    } catch (err) {
                        console.warn('Html5Qrcode scanFile fallback error:', err);
                    }

                    showStatus('Tidak ditemukan QR code valid pada gambar tersebut. Pastikan Anda mengupload file gambar QR tiket dari email.', 'error');
                };

                img.onerror = function() {
                    showStatus('Gagal membaca gambar. Pastikan format file PNG atau JPG.', 'error');
                };

                img.src = e.target.result;
            };

            reader.readAsDataURL(file);
        }

        // Kamera Scanner QR
        async function toggleCameraScanner() {
            const box = document.getElementById('camera-reader-box');
            const btn = document.getElementById('btn-scan-camera');

            if (isCameraActive) {
                stopCameraScanner();
            } else {
                box.classList.remove('hidden');
                btn.innerHTML = '<span>Tutup Kamera</span>';
                isCameraActive = true;

                html5QrScanner = new Html5Qrcode("qr-camera-reader");
                try {
                    await html5QrScanner.start(
                        { facingMode: "environment" },
                        { fps: 10, qrbox: { width: 220, height: 220 } },
                        (decodedText) => {
                            stopCameraScanner();
                            verifyQrToken(decodedText);
                        },
                        (errorMessage) => {}
                    );
                } catch (err) {
                    // Fallback to front camera if environment fails
                    try {
                        await html5QrScanner.start(
                            { facingMode: "user" },
                            { fps: 10, qrbox: { width: 220, height: 220 } },
                            (decodedText) => {
                                stopCameraScanner();
                                verifyQrToken(decodedText);
                            },
                            (errorMessage) => {}
                        );
                    } catch (err2) {
                        showStatus('Gagal mengakses kamera. Izinkan akses kamera atau gunakan opsi upload file gambar.', 'error');
                        stopCameraScanner();
                    }
                }
            }
        }

        function stopCameraScanner() {
            if (html5QrScanner && isCameraActive) {
                html5QrScanner.stop().then(() => {
                    html5QrScanner.clear();
                    const box = document.getElementById('camera-reader-box');
                    if (box) box.classList.add('hidden');
                    const btn = document.getElementById('btn-scan-camera');
                    if (btn) btn.innerHTML = '<span>Scan Menggunakan Kamera</span>';
                    isCameraActive = false;
                }).catch(() => {
                    isCameraActive = false;
                });
            }
        }

        function showStatus(msg, type) {
            const box = document.getElementById('qr-status-msg');
            box.classList.remove('hidden', 'bg-red-50', 'text-red-900', 'bg-green-50', 'text-green-900', 'bg-blue-50', 'text-blue-900');
            if (type === 'error') {
                box.classList.add('bg-red-50', 'text-red-900');
            } else if (type === 'success') {
                box.classList.add('bg-green-50', 'text-green-900');
            } else {
                box.classList.add('bg-blue-50', 'text-blue-900');
            }
            box.innerText = msg;
        }
    </script>

</body>
</html>
