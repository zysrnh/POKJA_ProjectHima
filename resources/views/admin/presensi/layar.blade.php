<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layar Proyektor Presensi Kelas - POKJA HIMA IF</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- QRCode.js Library CDN for Instant Offline Canvas QR Generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #E8F2FA;
            background-image: radial-gradient(#1A467C 1.5px, transparent 1.5px);
            background-size: 28px 28px;
        }

        /* Tactile Drop Slam Notification Animation */
        @keyframes notifSlamIn {
            0% {
                transform: translateY(-140%) scale(0.92);
            }
            65% {
                transform: translateY(12px) scale(1.02);
            }
            85% {
                transform: translateY(-4px) scale(0.99);
            }
            100% {
                transform: translateY(0) scale(1);
            }
        }

        @keyframes notifSlamOut {
            0% {
                transform: translateY(0) scale(1);
            }
            100% {
                transform: translateY(-160%) scale(0.85);
            }
        }

        .notif-card-in {
            animation: notifSlamIn 0.38s cubic-bezier(0.18, 0.89, 0.32, 1.28) forwards;
        }

        .notif-card-out {
            animation: notifSlamOut 0.28s cubic-bezier(0.6, -0.28, 0.735, 0.045) forwards;
        }

        /* Progress Bar Animation 5s */
        .progress-bar-5s {
            animation: shrinkBar 5s linear infinite;
            transform-origin: left;
        }

        @keyframes shrinkBar {
            0% {
                width: 100%;
            }
            100% {
                width: 0%;
            }
        }
    </style>
</head>
<body class="min-h-screen text-[#000000] flex flex-col justify-between p-4 sm:p-8 select-none overflow-x-hidden">

    <!-- ============================================================== -->
    <!-- FLOATING SLAM NOTIFICATIONS CONTAINER (Drops from Top)         -->
    <!-- ============================================================== -->
    <div id="notif-container" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-full max-w-xl px-4 space-y-2.5 pointer-events-none">
        <!-- Notifikasi card dimasukkan secara dinamis via JS -->
    </div>

    <!-- ============================================================== -->
    <!-- TOP BAR: Brand & Acara & Fullscreen Button                     -->
    <!-- ============================================================== -->
    <header class="flex items-center justify-between bg-white border-[3px] border-[#000000] p-4 sm:p-5 shadow-[6px_6px_0px_0px_#000000]">
        <div class="flex items-center gap-3.5">
            <img 
                src="{{ asset('logo/logo.png') }}" 
                alt="Logo HIMA IF" 
                class="w-12 h-12 object-contain bg-white p-1 border-2 border-black shadow-[2px_2px_0px_0px_#000000]"
            >
            <div>
                <span class="inline-block bg-[#1A467C] text-white text-[11px] font-black uppercase px-2.5 py-0.5 tracking-wider border border-black shadow-[1px_1px_0px_0px_#000000] mb-0.5">
                    LAYAR PROYEKTOR KELAS
                </span>
                <h1 class="text-lg sm:text-2xl font-black uppercase tracking-tight text-[#1A467C]">
                    {{ $pengaturan->nama_acara }}
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Badge Pertemuan Aktif -->
            <div class="bg-[#2A82C6] border-2 border-black px-4 py-2 text-white shadow-[3px_3px_0px_0px_#000000]">
                <span class="text-[10px] font-black uppercase tracking-wider block leading-none">SESI KELAS</span>
                <span class="text-base sm:text-xl font-black uppercase leading-tight" id="display-pertemuan">
                    PERTEMUAN {{ $pengaturan->pertemuan_aktif }}
                </span>
            </div>

            <!-- Tombol Fullscreen -->
            <button 
                type="button" 
                onclick="toggleFullscreen()"
                class="bg-white hover:bg-gray-100 border-2 border-black p-2.5 sm:px-4 sm:py-2 text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] active:translate-x-0.5 active:translate-y-0.5 cursor-pointer flex items-center gap-1.5"
                title="Layar Penuh"
            >
                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                </svg>
                <span class="hidden sm:inline">FULLSCREEN</span>
            </button>

            <!-- Tombol Kembali ke Admin -->
            <a 
                href="{{ route('admin.presensi.index') }}" 
                class="bg-[#CA2C2A] hover:bg-[#901C1A] border-2 border-black px-3.5 py-2 text-white text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] active:translate-x-0.5 active:translate-y-0.5 cursor-pointer"
            >
                TUTUP LAYAR
            </a>
        </div>
    </header>

    <!-- ============================================================== -->
    <!-- MAIN PROJECTION DISPLAY: Big Live QR & Counter                 -->
    <!-- ============================================================== -->
    <main class="my-auto py-6 sm:py-10 flex flex-col items-center justify-center">

        <div class="w-full max-w-xl bg-white border-[3px] border-[#000000] p-6 sm:p-8 shadow-[10px_10px_0px_0px_#000000] flex flex-col items-center text-center relative">
            
            <!-- Live Status & Counter Header -->
            <div class="w-full flex items-center justify-between border-b-2 border-black pb-4 mb-6">
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 bg-green-500 border border-black animate-pulse inline-block"></span>
                    <span class="text-xs sm:text-sm font-black uppercase tracking-wider text-green-700" id="status-scan-label">
                        {{ $pengaturan->is_scan_aktif ? 'SESI SCAN DIBUKA' : 'SESI SCAN DITUTUP' }}
                    </span>
                </div>

                <div class="bg-blue-50 border-2 border-black px-3 py-1 shadow-[2px_2px_0px_0px_#000000]">
                    <span class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                        HADIR: <span id="counter-hadir" class="text-sm font-mono text-[#2A82C6] font-black">{{ $totalHadir }}</span> / {{ $totalMahasiswa }}
                    </span>
                </div>
            </div>

            <!-- QR Code Canvas Container -->
            <div class="relative bg-white border-4 border-black p-4 sm:p-5 shadow-[6px_6px_0px_0px_#000000]">
                <!-- QR Box Render Target -->
                <div id="qrcode-box" class="flex items-center justify-center min-w-[240px] min-h-[240px] sm:min-w-[280px] sm:min-h-[280px]">
                    <!-- Canvas QR dimasukkan oleh QRCode.js -->
                </div>

                <!-- Overlay jika sesi ditutup -->
                <div id="closed-overlay" class="{{ $pengaturan->is_scan_aktif ? 'hidden' : 'flex' }} absolute inset-0 bg-red-600/95 border-2 border-black flex-col items-center justify-center p-6 text-white text-center">
                    <svg class="w-16 h-16 mb-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span class="text-lg font-black uppercase tracking-tight">SESI SCAN DITUTUP</span>
                    <p class="text-xs font-semibold mt-1">Silakan tunggu instruksi dari panitia di kelas.</p>
                </div>
            </div>

            <!-- 5-Second Animated Progress Bar -->
            <div class="w-full mt-6 space-y-1.5">
                <div class="flex items-center justify-between text-[11px] font-black uppercase tracking-wider text-gray-700">
                    <span>Refresh QR Dinamis</span>
                    <span id="countdown-text" class="font-mono text-[#CA2C2A]">5 Detik</span>
                </div>
                <div class="w-full h-3 bg-gray-200 border-2 border-black overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                    <div id="progress-bar" class="h-full bg-[#2A82C6] progress-bar-5s"></div>
                </div>
            </div>

            <!-- Instruksi Mahasiswa di Proyektor -->
            <div class="mt-6 pt-4 border-t-2 border-black w-full text-center">
                <p class="text-xs sm:text-sm font-bold text-gray-800">
                    Buka di browser HP Anda: 
                    <span class="bg-[#1A467C] text-white px-2.5 py-1 font-mono font-black border border-black shadow-[2px_2px_0px_0px_#000000] inline-block mt-1">
                        {{ url('/absen') }}
                    </span>
                </p>
                <p class="text-[11px] font-semibold text-gray-500 mt-2">
                    Masukkan NIM Anda lalu arahkan kamera ke QR Code di atas.
                </p>
            </div>

        </div>

    </main>

    <!-- ============================================================== -->
    <!-- FOOTER: Info & Copyright                                       -->
    <!-- ============================================================== -->
    <footer class="text-center py-2 text-xs font-bold text-gray-700">
        POKJA HIMA IF • Sistem Presensi Dinamis Otomatis
    </footer>

    <!-- ============================================================== -->
    <!-- JAVASCRIPT: Dynamic 5s QR Refresh & Drop Notif Live Polling    -->
    <!-- ============================================================== -->
    <script>
        let qrcode = null;
        let lastPayload = '';
        let lastSeenScanTime = '{{ now()->toIso8601String() }}';
        let countdownSeconds = 5;

        // Inisialisasi QRCode.js pada #qrcode-box
        function initQR() {
            const qrBox = document.getElementById('qrcode-box');
            qrBox.innerHTML = '';
            qrcode = new QRCode(qrBox, {
                text: "POKJA:INIT",
                width: 280,
                height: 280,
                colorDark: "#000000",
                colorLight: "#FFFFFF",
                correctLevel: QRCode.CorrectLevel.M
            });
        }

        // Fetch Token QR Baru dari Backend
        async function fetchNewToken() {
            try {
                const response = await fetch("{{ route('admin.presensi.token') }}");
                if (!response.ok) return;

                const data = await response.json();

                // Update info pertemuan
                document.getElementById('display-pertemuan').innerText = `PERTEMUAN ${data.pertemuan}`;
                document.getElementById('counter-hadir').innerText = data.total_hadir;

                // Overlay status scan
                const overlay = document.getElementById('closed-overlay');
                const statusLabel = document.getElementById('status-scan-label');
                if (data.is_scan_aktif) {
                    overlay.classList.add('hidden');
                    overlay.classList.remove('flex');
                    statusLabel.innerText = "SESI SCAN DIBUKA";
                    statusLabel.className = "text-xs sm:text-sm font-black uppercase tracking-wider text-green-700";
                } else {
                    overlay.classList.remove('hidden');
                    overlay.classList.add('flex');
                    statusLabel.innerText = "SESI SCAN DITUTUP";
                    statusLabel.className = "text-xs sm:text-sm font-black uppercase tracking-wider text-red-700";
                }

                // Update QR HANYA jika payload baru
                if (data.payload && data.payload !== lastPayload) {
                    lastPayload = data.payload;
                    qrcode.clear();
                    qrcode.makeCode(data.payload);
                }

                // Reset progress bar animasi
                resetProgressBar();

            } catch (err) {
                console.error("Gagal memperbarui token QR:", err);
            }
        }

        function resetProgressBar() {
            const bar = document.getElementById('progress-bar');
            bar.classList.remove('progress-bar-5s');
            void bar.offsetWidth; // Trigger reflow
            bar.classList.add('progress-bar-5s');
        }

        // Suara Tactile Beep Sintesis Web Audio API (Tanpa file mp3 luar)
        function playSuccessChime() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();

                osc.type = 'triangle';
                osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                osc.frequency.exponentialRampToValueAtTime(880, audioCtx.currentTime + 0.15); // A5

                gain.gain.setValueAtTime(0.25, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.35);

                osc.connect(gain);
                gain.connect(audioCtx.destination);

                osc.start();
                osc.stop(audioCtx.currentTime + 0.35);
            } catch (e) {
                // Ignore audio restriction
            }
        }

        // Tampilkan Notifikasi Fisik Jatuh dari Atas (Drop Slam)
        function showDropNotification(scan) {
            playSuccessChime();

            const container = document.getElementById('notif-container');
            const notif = document.createElement('div');
            notif.className = "notif-card-in bg-white border-[3px] border-[#000000] p-4 shadow-[6px_6px_0px_0px_#000000] flex items-center justify-between gap-3 text-black";

            notif.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 bg-[#15803d] border-2 border-black flex items-center justify-center text-white font-black text-lg shadow-[2px_2px_0px_0px_#000000] shrink-0">
                        ✓
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="bg-[#1A467C] text-white text-[9px] font-black uppercase px-1.5 py-0.2 tracking-wider border border-black">
                                HADIR
                            </span>
                            <span class="text-[11px] font-bold text-gray-500 font-mono">${scan.waktu} WIB</span>
                        </div>
                        <h4 class="text-sm sm:text-base font-black uppercase text-gray-950 truncate mt-0.5">
                            ${scan.nama}
                        </h4>
                        <p class="text-xs font-bold text-gray-600 font-mono">
                            ${scan.nim} • ${scan.kelas}
                        </p>
                    </div>
                </div>
                <div class="w-3 h-3 bg-green-500 border border-black shrink-0"></div>
            `;

            container.prepend(notif);

            // Auto dismiss setelah 3.5 detik
            setTimeout(() => {
                notif.classList.remove('notif-card-in');
                notif.classList.add('notif-card-out');
                setTimeout(() => notif.remove(), 300);
            }, 3500);
        }

        // Polling Cek Mahasiswa Baru yang Berhasil Scan
        async function pollRecentScans() {
            try {
                const response = await fetch(`{{ route('admin.presensi.recent-scans') }}?since=${encodeURIComponent(lastSeenScanTime)}`);
                if (!response.ok) return;

                const data = await response.json();
                if (data.scans && data.scans.length > 0) {
                    // Update counter
                    document.getElementById('counter-hadir').innerText = data.total_hadir;

                    // Tampilkan notifikasi tiap scan yang baru
                    data.scans.reverse().forEach(scan => {
                        showDropNotification(scan);
                    });
                }

                if (data.current_time) {
                    lastSeenScanTime = data.current_time;
                }
            } catch (err) {
                console.error("Gagal polling recent scans:", err);
            }
        }

        // Toggle Fullscreen Layar Proyektor
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    alert(`Gagal masuk mode layar penuh: ${err.message}`);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        // Jalankan saat load
        document.addEventListener('DOMContentLoaded', () => {
            initQR();
            fetchNewToken();

            // Refresh token QR tiap 5 detik tepat
            setInterval(() => {
                fetchNewToken();
            }, 5000);

            // Polling cek siapa yang baru absen tiap 1.5 detik
            setInterval(() => {
                pollRecentScans();
            }, 1500);
        });
    </script>
</body>
</html>
