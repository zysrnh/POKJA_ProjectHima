(function () {
    const activeOpsByContainer = new Map();

    function extractErrorText(err) {
        if (!err) return '';
        if (typeof err === 'string') return err;
        const parts = [err.name, err.message].filter(Boolean);
        return parts.length > 0 ? parts.join(': ') : String(err);
    }

    function safeQrBox(viewfinderWidth, viewfinderHeight) {
        const minDim = Math.min(viewfinderWidth || 0, viewfinderHeight || 0);
        if (!minDim || minDim < 80) {
            return { width: 50, height: 50 };
        }
        const maxAllowed = Math.max(50, minDim - 12);
        const target = Math.floor(minDim * 0.8);
        const edge = Math.min(maxAllowed, Math.max(50, Math.min(320, target)));
        return { width: edge, height: edge };
    }

    function buildHdConfig() {
        return {
            fps: 15,
            qrbox: safeQrBox,
            videoConstraints: {
                facingMode: { ideal: 'environment' },
                width: { ideal: 1280 },
                height: { ideal: 720 },
            },
        };
    }

    function buildCompatConfig() {
        return {
            fps: 12,
            qrbox: safeQrBox,
        };
    }

    function createScanner(containerId, useNativeDetector) {
        if (useNativeDetector) {
            return new Html5Qrcode(containerId, {
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true,
                },
                verbose: false,
            });
        }

        return new Html5Qrcode(containerId, { verbose: false });
    }

    function releaseContainerVideoTracks(containerId) {
        const container = containerId ? document.getElementById(containerId) : null;
        if (!container) return;

        const videos = container.querySelectorAll('video');
        videos.forEach((video) => {
            try {
                if (video.srcObject && typeof video.srcObject.getTracks === 'function') {
                    video.srcObject.getTracks().forEach((track) => {
                        try { track.stop(); } catch (_) {}
                    });
                    video.srcObject = null;
                }
            } catch (_) {}
        });
    }

    async function resetScannerInstance(scanner, containerId) {
        if (scanner) {
            try {
                if (scanner.isScanning) {
                    await scanner.stop();
                }
            } catch (_) {}
            try {
                scanner.clear();
            } catch (_) {}
        }

        releaseContainerVideoTracks(containerId);

        const container = containerId ? document.getElementById(containerId) : null;
        if (container) {
            container.innerHTML = '';
        }
    }

    function setupCameraControls(containerId, zoomBarId) {
        try {
            const zoomBar = zoomBarId ? document.getElementById(zoomBarId) : null;
            if (zoomBar) {
                zoomBar.classList.add('hidden');
                zoomBar.innerHTML = '';
            }

            const container = document.getElementById(containerId);
            const videoEl = container ? container.querySelector('video') : null;
            const stream = videoEl && videoEl.srcObject ? videoEl.srcObject : null;
            const track = stream && stream.getVideoTracks ? stream.getVideoTracks()[0] : null;
            if (!track || typeof track.getCapabilities !== 'function') {
                return;
            }

            const caps = track.getCapabilities();

            if (Array.isArray(caps.focusMode) && caps.focusMode.includes('continuous')) {
                track.applyConstraints({ advanced: [{ focusMode: 'continuous' }] }).catch(() => {});
            }

            if (zoomBar && caps.zoom && typeof caps.zoom.max === 'number' && caps.zoom.max > caps.zoom.min) {
                const minZoom = Number(caps.zoom.min) || 1;
                const maxZoom = Number(caps.zoom.max);
                const presets = [1, 2, 3, 4].filter((z, idx) => idx === 0 || (z >= minZoom && z <= maxZoom + 0.01));
                if (presets.length <= 1) return;

                const label = document.createElement('span');
                label.className = 'text-[10px] font-black uppercase tracking-wider text-gray-600 mr-1';
                label.textContent = 'Zoom:';
                zoomBar.appendChild(label);

                let activeZoom = Math.min(maxZoom, Math.max(minZoom, 1));
                const buttons = [];

                const renderButtons = () => {
                    buttons.forEach(({ btn, val }) => {
                        const isActive = Math.abs(activeZoom - val) < 0.05;
                        btn.className = isActive
                            ? 'px-2.5 py-1 bg-[#1A467C] text-white border-2 border-black text-[11px] font-mono font-black cursor-pointer'
                            : 'px-2.5 py-1 bg-white hover:bg-gray-100 text-black border-2 border-black text-[11px] font-mono font-black cursor-pointer';
                    });
                };

                presets.forEach((rawVal) => {
                    const targetVal = Math.min(maxZoom, Math.max(minZoom, rawVal));
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = `${rawVal}x`;
                    btn.addEventListener('click', () => {
                        activeZoom = targetVal;
                        renderButtons();
                        track.applyConstraints({ advanced: [{ zoom: targetVal }] }).catch(() => {});
                    });
                    buttons.push({ btn, val: targetVal });
                    zoomBar.appendChild(btn);
                });

                renderButtons();
                zoomBar.classList.remove('hidden');
            }
        } catch (_) {}
    }

    async function isCameraPermissionDenied() {
        try {
            if (!navigator.permissions || typeof navigator.permissions.query !== 'function') {
                return false;
            }
            const status = await navigator.permissions.query({ name: 'camera' });
            return status && status.state === 'denied';
        } catch (_) {
            return false;
        }
    }

    async function tryStartAttempt(containerId, cameraConfig, scanConfig, useNativeDetector, onSuccess) {
        const scanner = createScanner(containerId, useNativeDetector);
        try {
            await scanner.start(cameraConfig, scanConfig, onSuccess, () => {});
            return { ok: true, scanner };
        } catch (err) {
            await resetScannerInstance(scanner, containerId);
            return { ok: false, error: err };
        }
    }

    function sortCamerasPreferBack(devices) {
        if (!Array.isArray(devices) || devices.length <= 1) {
            return Array.isArray(devices) ? devices : [];
        }
        const score = (cam) => {
            const label = String((cam && cam.label) || '').toLowerCase();
            if (/back|rear|environment|belakang/.test(label)) return 0;
            if (/front|user|depan/.test(label)) return 2;
            return 1;
        };
        return [...devices].sort((a, b) => score(a) - score(b));
    }

    async function runStart(existingInstance, containerId, zoomBarId, onSuccess) {
        if (typeof Html5Qrcode === 'undefined') {
            throw new Error('Library QR scanner gagal dimuat. Periksa koneksi internet lalu muat ulang halaman.');
        }

        if (!window.isSecureContext || !navigator.mediaDevices || typeof navigator.mediaDevices.getUserMedia !== 'function') {
            throw new Error('Akses kamera membutuhkan halaman HTTPS yang aman dan browser yang mendukung kamera.');
        }

        if (await isCameraPermissionDenied()) {
            throw new Error('NotAllowedError: Izin kamera diblokir di pengaturan browser.');
        }

        await resetScannerInstance(existingInstance, containerId);

        // Tunggu 1 frame agar ukuran container (#qr-reader / #dash-qr-reader) sudah terhitung
        await new Promise((resolve) => requestAnimationFrame(() => resolve()));

        const hdConfig = buildHdConfig();
        const compatConfig = buildCompatConfig();

        const attempts = [
            { camera: { facingMode: 'environment' }, config: hdConfig, nativeDetector: true },
            { camera: { facingMode: 'environment' }, config: compatConfig, nativeDetector: false },
            { camera: { facingMode: 'user' }, config: compatConfig, nativeDetector: false },
        ];

        let lastError = null;

        for (const attempt of attempts) {
            const res = await tryStartAttempt(
                containerId,
                attempt.camera,
                attempt.config,
                attempt.nativeDetector,
                onSuccess
            );

            if (res.ok) {
                setTimeout(() => setupCameraControls(containerId, zoomBarId), 350);
                return res.scanner;
            }

            lastError = res.error;
            if (await isCameraPermissionDenied()) {
                throw lastError;
            }
        }

        // Fallback terakhir: enumerasi camera deviceId langsung (mengatasi device yang menolak constraint facingMode)
        try {
            const devices = await Html5Qrcode.getCameras();
            const ordered = sortCamerasPreferBack(devices);
            for (const cam of ordered) {
                if (!cam || !cam.id) continue;
                const res = await tryStartAttempt(
                    containerId,
                    cam.id,
                    compatConfig,
                    false,
                    onSuccess
                );
                if (res.ok) {
                    setTimeout(() => setupCameraControls(containerId, zoomBarId), 350);
                    return res.scanner;
                }
                lastError = res.error;
            }
        } catch (enumErr) {
            lastError = lastError || enumErr;
        }

        throw lastError || new Error('Kamera gagal dibuka.');
    }

    async function startScanner(existingInstance, containerId, zoomBarId, onSuccess) {
        const prev = activeOpsByContainer.get(containerId) || Promise.resolve();
        const op = prev.catch(() => {}).then(() => runStart(existingInstance, containerId, zoomBarId, onSuccess));
        activeOpsByContainer.set(containerId, op);
        try {
            return await op;
        } finally {
            if (activeOpsByContainer.get(containerId) === op) {
                activeOpsByContainer.delete(containerId);
            }
        }
    }

    async function stopScanner(instance, containerId, zoomBarId) {
        const prev = activeOpsByContainer.get(containerId) || Promise.resolve();
        const op = prev.catch(() => {}).then(async () => {
            await resetScannerInstance(instance, containerId);
            hideZoomBar(zoomBarId);
        });
        activeOpsByContainer.set(containerId, op);
        try {
            await op;
        } finally {
            if (activeOpsByContainer.get(containerId) === op) {
                activeOpsByContainer.delete(containerId);
            }
        }
    }

    function hideZoomBar(zoomBarId) {
        const zoomBar = zoomBarId ? document.getElementById(zoomBarId) : null;
        if (zoomBar) {
            zoomBar.classList.add('hidden');
            zoomBar.innerHTML = '';
        }
    }

    function formatError(err) {
        const raw = extractErrorText(err);

        if (!window.isSecureContext) {
            return 'Akses kamera membutuhkan halaman HTTPS yang aman.';
        }
        if (/Library QR scanner gagal dimuat/i.test(raw)) {
            return raw;
        }
        if (/NotAllowedError|PermissionDeniedError|Permission denied|Permission dismissed/i.test(raw)) {
            return 'Izin kamera diblokir oleh browser atau perangkat. Buka pengaturan izin situs di address bar (ikon gembok/kamera), setel Kamera ke Izinkan, lalu muat ulang halaman.';
        }
        if (/NotReadableError|TrackStartError|AbortError|Could not start video source/i.test(raw)) {
            return 'Kamera sudah diizinkan, tetapi sedang dipakai oleh aplikasi/tab lain atau hardware kamera belum siap. Tutup aplikasi kamera lain lalu coba lagi.';
        }
        if (/NotFoundError|DevicesNotFoundError|Requested device not found|no camera/i.test(raw)) {
            return 'Kamera tidak ditemukan pada perangkat ini.';
        }
        if (/OverconstrainedError|ConstraintNotSatisfiedError/i.test(raw)) {
            return 'Pengaturan kamera tidak cocok dengan perangkat ini. Silakan muat ulang halaman dan coba lagi.';
        }

        return raw
            ? `Tidak dapat membuka kamera (${raw}). Pastikan kamera tidak sedang dipakai aplikasi lain lalu muat ulang halaman.`
            : 'Tidak dapat membuka kamera. Pastikan kamera tidak sedang dipakai aplikasi lain lalu muat ulang halaman.';
    }

    window.PokjaQrScanner = {
        start: startScanner,
        stop: stopScanner,
        hideZoomBar,
        formatError,
    };
})();
