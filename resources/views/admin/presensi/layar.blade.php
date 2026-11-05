<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layar Proyektor Presensi Kelas - POKJA HIMA IF</title>

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
    <!-- QRCode.js Library CDN for Instant Offline Canvas QR Generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E8F2FA;
            background-image: radial-gradient(#1A467C 1.5px, transparent 1.5px);
            background-size: 28px 28px;
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
    <!-- TOP BAR: Brand Acara & Icon Actions Only                       -->
    <!-- ============================================================== -->
    <header class="flex items-center justify-between bg-white border-[3px] border-[#000000] p-4 sm:p-5 shadow-[6px_6px_0px_0px_#000000]">
        <div class="flex items-center gap-3.5">
            <img 
                src="{{ asset('logo/logo.png') }}" 
                alt="Logo HIMA IF" 
                class="w-10 h-10 object-contain bg-white p-1 border-2 border-black shadow-[2px_2px_0px_0px_#000000]"
            >
            <h1 class="text-base sm:text-xl font-black uppercase tracking-tight text-[#1A467C]">
                {{ $pengaturan->nama_acara }}
            </h1>
        </div>

        <div class="flex items-center gap-2.5 sm:gap-3">
            <!-- Badge P1 Saja -->
            <div class="w-10 h-10 sm:w-11 sm:h-11 bg-[#2A82C6] border-2 border-black flex items-center justify-center text-white font-black text-sm sm:text-base shadow-[3px_3px_0px_0px_#000000]" id="display-pertemuan" title="Pertemuan {{ $pengaturan->pertemuan_aktif }}">
                P{{ $pengaturan->pertemuan_aktif }}
            </div>

            <!-- Tombol Fullscreen (Icon Only) -->
            <button 
                type="button" 
                onclick="toggleFullscreen()"
                class="btn-smooth w-10 h-10 sm:w-11 sm:h-11 bg-white hover:bg-gray-100 border-2 border-black flex items-center justify-center text-black shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                title="Layar Penuh (Fullscreen)"
            >
                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.5" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                </svg>
            </button>

            <!-- Tombol Tutup Layar (Icon Only) -->
            <a 
                href="{{ route('admin.presensi.index') }}" 
                class="btn-smooth w-10 h-10 sm:w-11 sm:h-11 bg-[#CA2C2A] hover:bg-[#901C1A] border-2 border-black flex items-center justify-center text-white shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                title="Tutup Layar Proyektor"
            >
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-width="2.8" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </div>
    </header>

    <!-- ============================================================== -->
    <!-- MAIN PROJECTION DISPLAY: Pure Clean Big QR Only                -->
    <!-- ============================================================== -->
    <main class="my-auto py-6 sm:py-10 flex flex-col items-center justify-center">

        <div class="w-full max-w-md bg-white border-[3px] border-[#000000] p-6 sm:p-8 shadow-[10px_10px_0px_0px_#000000] flex flex-col items-center text-center relative">
            
            <!-- QR Code Canvas Container -->
            <div class="relative bg-white border-4 border-black p-4 sm:p-5 shadow-[6px_6px_0px_0px_#000000]">
                <!-- QR Box Render Target -->
                <div id="qrcode-box" class="flex items-center justify-center min-w-[240px] min-h-[240px] sm:min-w-[280px] sm:min-h-[280px]">
                    <!-- Canvas QR dimasukkan oleh QRCode.js -->
                </div>

                <!-- Overlay jika sesi ditutup -->
                <div id="closed-overlay" class="{{ $pengaturan->is_scan_aktif ? 'hidden' : 'flex' }} absolute inset-0 bg-red-600/95 border-2 border-black flex-col items-center justify-center p-6 text-white text-center">
                    <svg class="w-14 h-14 mb-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span class="text-base font-black uppercase tracking-tight">SESI SCAN DITUTUP</span>
                </div>
            </div>

        </div>

    </main>

    <!-- ============================================================== -->
    <!-- FLOATING HADIR COUNTER (Ujung Layar)                           -->
    <!-- ============================================================== -->
    <div class="fixed bottom-6 right-6 z-40 bg-white border-2 border-black px-4 py-2.5 shadow-[4px_4px_0px_0px_#000000] flex items-center gap-2">
        <span class="w-2.5 h-2.5 bg-green-500 border border-black animate-pulse inline-block"></span>
        <span class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
            HADIR: <span id="counter-hadir" class="font-mono text-sm text-[#2A82C6] font-black">{{ $totalHadir }}</span> / {{ $totalMahasiswa }}
        </span>
    </div>

    <!-- ============================================================== -->
    <!-- JAVASCRIPT: Dynamic 5s QR Refresh & Drop Notif Live Polling    -->
    <!-- ============================================================== -->
    <script>
        let qrcode = null;
        let lastPayload = '';
        let lastSeenScanTime = '{{ now()->toIso8601String() }}';

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
                const displayPertemuan = document.getElementById('display-pertemuan');
                if (displayPertemuan) {
                    displayPertemuan.innerText = `P${data.pertemuan}`;
                }

                // Update floating counter
                const counterHadir = document.getElementById('counter-hadir');
                if (counterHadir) {
                    counterHadir.innerText = data.total_hadir;
                }

                // Overlay status scan
                const overlay = document.getElementById('closed-overlay');
                if (overlay) {
                    if (data.is_scan_aktif) {
                        overlay.classList.add('hidden');
                        overlay.classList.remove('flex');
                    } else {
                        overlay.classList.remove('hidden');
                        overlay.classList.add('flex');
                    }
                }

                // Update QR HANYA jika payload baru
                if (data.payload && data.payload !== lastPayload) {
                    lastPayload = data.payload;
                    qrcode.clear();
                    qrcode.makeCode(data.payload);
                }

            } catch (err) {
                console.error("Gagal memperbarui token QR:", err);
            }
        }

        // Suara Tactile Beep Sintesis Web Audio API (Tanpa file mp3 luar)
        function playSuccessChime() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();

                osc.type = 'triangle';
                osc.frequency.setValueAtTime(587.33, audioCtx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(880, audioCtx.currentTime + 0.15);

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
                    const counterHadir = document.getElementById('counter-hadir');
                    if (counterHadir) {
                        counterHadir.innerText = data.total_hadir;
                    }

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
