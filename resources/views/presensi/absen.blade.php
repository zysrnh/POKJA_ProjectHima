<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Presensi Kehadiran Kelas - POKJA HIMA IF</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- HTML5-QRCode Library for Fast In-Browser Mobile Camera Scanning -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
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

        /* Solid Spring Modal Slam Animation */
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
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tight block mt-0.5">PRESENSI KELAS</span>
            </div>
        </div>

        <div class="bg-[#2A82C6] border border-black px-2.5 py-1 text-white shadow-[1px_1px_0px_0px_#000000]">
            <span class="text-[10px] font-black uppercase tracking-wider">
                SESI P{{ $pengaturan->pertemuan_aktif ?? 1 }}
            </span>
        </div>
    </header>

    <!-- ============================================================== -->
    <!-- MAIN CONTAINER                                                 -->
    <!-- ============================================================== -->
    <main class="w-full max-w-md mx-auto my-4 space-y-4">

        <!-- Card Input NIM Mahasiswa -->
        <div class="bg-white border-2 border-black p-4 sm:p-5 shadow-[4px_4px_0px_0px_#000000] space-y-3">
            <div class="flex items-center justify-between border-b-2 border-black pb-2">
                <span class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                    1. Identitas Mahasiswa
                </span>
                <span id="nim-badge-saved" class="hidden text-[9px] font-black uppercase tracking-wider bg-green-100 text-green-900 border border-green-800 px-1.5 py-0.2">
                    NIM TERSIMPAN
                </span>
            </div>

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
                <p class="text-[11px] font-semibold text-gray-500 mt-1">
                    Pastikan NIM sesuai dengan pendaftaran (9 digit angka).
                </p>
            </div>
        </div>

        <!-- Card Kamera Scanner QR Proyektor -->
        <div class="bg-white border-2 border-black p-4 sm:p-5 shadow-[4px_4px_0px_0px_#000000] space-y-3">
            <div class="border-b-2 border-black pb-2 flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                    2. Scan QR di Layar Proyektor
                </span>
                <span class="text-[10px] font-mono font-bold text-gray-500">HTML5 CAMERA</span>
            </div>

            <!-- Tombol Buka Kamera -->
            <div id="btn-camera-wrapper">
                <button 
                    type="button" 
                    id="btn-start-camera"
                    onclick="startCameraScanner()"
                    class="btn-smooth w-full py-3 bg-[#2A82C6] hover:bg-[#1A467C] active:bg-[#1A467C] text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-black shadow-[3px_3px_0px_0px_#000000] flex items-center justify-center gap-2 cursor-pointer"
                >
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="square" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>BUKA KAMERA & SCAN QR</span>
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
                    TUTUP KAMERA
                </button>
            </div>

            <!-- Tips & Peringatan QR 5 Detik -->
            <div class="p-3 bg-amber-50 border-2 border-black text-[11px] font-bold text-amber-950 shadow-[2px_2px_0px_0px_#000000] space-y-1">
                <p class="font-black uppercase flex items-center gap-1.5 text-amber-900">
                    <svg class="w-3.5 h-3.5 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>PENTING (ANTI-TITIP ABSEN):</span>
                </p>
                <p>QR Code di proyektor berganti otomatis tiap 5 detik. Pastikan scan langsung ke layar proyektor di kelas, bukan dari kiriman foto chat.</p>
            </div>
        </div>

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
        let html5QrCode = null;
        let isProcessing = false;

        // Cek LocalStorage untuk NIM tersimpan
        document.addEventListener('DOMContentLoaded', () => {
            const savedNIM = localStorage.getItem('pokja_mhs_nim');
            const inputNIM = document.getElementById('input-nim');

            if (savedNIM && savedNIM.length === 9) {
                inputNIM.value = savedNIM;
                document.getElementById('nim-badge-saved').classList.remove('hidden');
                document.getElementById('nim-check').classList.remove('hidden');
            }

            inputNIM.addEventListener('input', (e) => {
                const val = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = val;

                if (val.length === 9) {
                    localStorage.setItem('pokja_mhs_nim', val);
                    document.getElementById('nim-check').classList.remove('hidden');
                } else {
                    document.getElementById('nim-check').classList.add('hidden');
                }
            });
        });

        // Buka Kamera Scanner
        async function startCameraScanner() {
            const nim = document.getElementById('input-nim').value.trim();
            if (nim.length !== 9) {
                alert("Harap masukkan 9 digit NIM Anda terlebih dahulu!");
                document.getElementById('input-nim').focus();
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

            try {
                await html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    onScanSuccess,
                    onScanFailure
                );
            } catch (err) {
                alert(`Gagal mengakses kamera: ${err.message || 'Izin kamera ditolak'}`);
                stopCameraScanner();
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
        async function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;

            const nim = document.getElementById('input-nim').value.trim();
            if (nim.length !== 9) {
                alert("NIM belum diisi dengan benar!");
                isProcessing = false;
                return;
            }

            // Getar HP jika browser mendukung
            if (navigator.vibrate) {
                navigator.vibrate([100, 50, 100]);
            }

            // Hentikan kamera sementara
            await stopCameraScanner();

            // Submit ke Backend via AJAX
            try {
                const response = await fetch("{{ route('presensi.scan') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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

        function onScanFailure(error) {
            // Abaikan frame kamera yang belum menemukan QR
        }

        // Tampilkan Modal Hasil Absen
        function showResultModal(data, isHttpOk) {
            const modal = document.getElementById('result-modal');
            const iconWrapper = document.getElementById('modal-icon-wrapper');
            const badge = document.getElementById('modal-badge');
            const title = document.getElementById('modal-title');
            const desc = document.getElementById('modal-desc');
            const studentBox = document.getElementById('modal-student-box');

            modal.classList.remove('hidden');

            if (data.success && !data.already) {
                // SUKSES HADIR PERTAMA KALI
                iconWrapper.className = "w-14 h-14 mx-auto border-2 border-black flex items-center justify-center font-black text-2xl shadow-[3px_3px_0px_0px_#000000] bg-green-500 text-white";
                iconWrapper.innerText = "✓";
                badge.className = "inline-block text-[10px] font-black uppercase px-2 py-0.5 border border-black shadow-[1px_1px_0px_0px_#000000] mb-1 bg-green-100 text-green-900";
                badge.innerText = "BERHASIL HADIR";
                title.innerText = `PERTEMUAN ${data.pertemuan}`;
                desc.innerText = "Presensi Anda telah berhasil dicatat dan masuk ke layar proyektor!";

                studentBox.classList.remove('hidden');
                document.getElementById('detail-nama').innerText = data.nama;
                document.getElementById('detail-nim-kelas').innerText = `${data.nim} • ${data.kelas}`;
                document.getElementById('detail-waktu').innerText = `${data.waktu} WIB`;

            } else if (data.success && data.already) {
                // SUDAH PERNAH HADIR
                iconWrapper.className = "w-14 h-14 mx-auto border-2 border-black flex items-center justify-center font-black text-2xl shadow-[3px_3px_0px_0px_#000000] bg-amber-400 text-black";
                iconWrapper.innerText = "ℹ";
                badge.className = "inline-block text-[10px] font-black uppercase px-2 py-0.5 border border-black shadow-[1px_1px_0px_0px_#000000] mb-1 bg-amber-100 text-amber-900";
                badge.innerText = "SUDAH TERCATAT";
                title.innerText = `SUDAH ABSEN (P${data.pertemuan})`;
                desc.innerText = data.message;

                studentBox.classList.remove('hidden');
                document.getElementById('detail-nama').innerText = data.nama;
                document.getElementById('detail-nim-kelas').innerText = `${data.nim} • ${data.kelas}`;
                document.getElementById('detail-waktu').innerText = `${data.waktu} WIB`;

            } else {
                // ERROR (KEDALUWARSA / NIM SALAH / SESI TUTUP)
                iconWrapper.className = "w-14 h-14 mx-auto border-2 border-black flex items-center justify-center font-black text-2xl shadow-[3px_3px_0px_0px_#000000] bg-red-600 text-white";
                iconWrapper.innerText = "✕";
                badge.className = "inline-block text-[10px] font-black uppercase px-2 py-0.5 border border-black shadow-[1px_1px_0px_0px_#000000] mb-1 bg-red-100 text-red-900";
                badge.innerText = "PRESENSI GAGAL";
                title.innerText = "TIDAK VALID";
                desc.innerText = data.message || "Gagal memproses presensi.";
                studentBox.classList.add('hidden');
            }
        }

        function closeResultModal() {
            document.getElementById('result-modal').classList.add('hidden');
        }
    </script>
</body>
</html>
