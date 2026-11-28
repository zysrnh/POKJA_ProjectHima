(function () {
    const activeOpsByContainer = new Map();
    const zoomCleanupByContainer = new Map();

    function delay(ms) {
        return new Promise((resolve) => setTimeout(resolve, ms));
    }

    function extractErrorText(err) {
        if (!err) return '';
        if (typeof err === 'string') return err;
        const parts = [err.name, err.message].filter(Boolean);
        return parts.length > 0 ? parts.join(': ') : String(err);
    }

    async function ensureHtml5QrcodeLoaded() {
        if (typeof Html5Qrcode !== 'undefined') {
            return true;
        }

        const cdnFallbacks = [
            'https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js',
        ];

        for (const src of cdnFallbacks) {
            try {
                await new Promise((resolve, reject) => {
                    const s = document.createElement('script');
                    s.src = src;
                    s.async = true;
                    s.onload = () => resolve();
                    s.onerror = () => reject(new Error('CDN load failed'));
                    document.head.appendChild(s);
                });
                if (typeof Html5Qrcode !== 'undefined') {
                    return true;
                }
            } catch (_) {}
        }

        return typeof Html5Qrcode !== 'undefined';
    }

    function safeQrBox(viewfinderWidth, viewfinderHeight) {
        const w = Number(viewfinderWidth) || 0;
        const h = Number(viewfinderHeight) || 0;
        const minDim = Math.min(w, h);
        if (minDim < 70) {
            return { width: 50, height: 50 };
        }
        const maxAllowed = Math.max(50, minDim - 12);
        const target = Math.floor(minDim * 0.78);
        const edge = Math.min(maxAllowed, Math.max(50, Math.min(300, target)));
        return { width: edge, height: edge };
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

    function cleanupCameraControls(containerId) {
        const cleanup = containerId ? zoomCleanupByContainer.get(containerId) : null;
        if (typeof cleanup === 'function') {
            try { cleanup(); } catch (_) {}
            zoomCleanupByContainer.delete(containerId);
        }
    }

    async function resetScannerInstance(scanner, containerId) {
        cleanupCameraControls(containerId);

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
            container.style.touchAction = '';
            container.innerHTML = '';
        }
    }

    function clampAndSnapZoom(val, minZoom, maxZoom, rawStep) {
        let z = Math.min(maxZoom, Math.max(minZoom, Number(val) || minZoom));
        if (rawStep > 0 && rawStep < (maxZoom - minZoom)) {
            const steps = Math.round((z - minZoom) / rawStep);
            z = Math.min(maxZoom, Math.max(minZoom, minZoom + steps * rawStep));
        }
        return Number(z.toFixed(2));
    }

    function setupCameraControls(containerId, zoomBarId) {
        try {
            cleanupCameraControls(containerId);

            const zoomBar = zoomBarId ? document.getElementById(zoomBarId) : null;
            if (zoomBar) {
                zoomBar.classList.add('hidden');
                zoomBar.innerHTML = '';
            }

            const container = document.getElementById(containerId);
            const videoEl = container ? container.querySelector('video') : null;
            const stream = videoEl && videoEl.srcObject ? videoEl.srcObject : null;
            const track = stream && stream.getVideoTracks ? stream.getVideoTracks()[0] : null;
            if (!container || !track || typeof track.getCapabilities !== 'function') {
                return;
            }

            const caps = track.getCapabilities();

            // Tingkatkan resolusi & autofocus secara halus setelah stream berhasil dibuka
            if (typeof track.applyConstraints === 'function') {
                const enhanceConstraints = {};
                if (caps.width && caps.width.max >= 1280 && caps.height && caps.height.max >= 720) {
                    enhanceConstraints.width = { ideal: 1280 };
                    enhanceConstraints.height = { ideal: 720 };
                }
                if (Array.isArray(caps.focusMode) && caps.focusMode.includes('continuous')) {
                    enhanceConstraints.advanced = [{ focusMode: 'continuous' }];
                }
                if (Object.keys(enhanceConstraints).length > 0) {
                    track.applyConstraints(enhanceConstraints).catch(() => {});
                }
            }

            if (!caps.zoom || typeof caps.zoom.max !== 'number') {
                return;
            }

            const minZoom = Number(caps.zoom.min) || 1;
            const maxZoom = Number(caps.zoom.max);
            if (!(maxZoom > minZoom)) {
                return;
            }

            const rawStep = Number(caps.zoom.step) || 0.1;
            const sliderStep = Math.max(0.05, Math.min(0.25, rawStep));
            const btnDelta = Math.max(0.5, Number(((maxZoom - minZoom) / 8).toFixed(1)));
            const currentSetting = typeof track.getSettings === 'function' ? Number(track.getSettings().zoom) : NaN;
            let activeZoom = clampAndSnapZoom(
                Number.isFinite(currentSetting) ? currentSetting : Math.min(maxZoom, Math.max(minZoom, 1)),
                minZoom,
                maxZoom,
                rawStep
            );

            // Overlay badge sementara saat mencubit layar kamera
            const viewportWrapper = container.parentElement;
            let overlayBadge = null;
            let hideBadgeTimer = null;

            if (viewportWrapper) {
                overlayBadge = document.createElement('div');
                overlayBadge.className = 'hidden pointer-events-none absolute top-2.5 right-2.5 z-20 bg-amber-300 text-black border-2 border-black px-2 py-0.5 font-mono font-black text-xs shadow-[2px_2px_0px_0px_#000000]';
                viewportWrapper.appendChild(overlayBadge);
            }

            const flashZoomBadge = (zoomVal) => {
                if (!overlayBadge) return;
                overlayBadge.textContent = `${zoomVal.toFixed(1)}x`;
                overlayBadge.classList.remove('hidden');
                if (hideBadgeTimer) clearTimeout(hideBadgeTimer);
                hideBadgeTimer = setTimeout(() => {
                    overlayBadge.classList.add('hidden');
                }, 1000);
            };

            let readoutEl = null;
            let sliderEl = null;
            let resetBtn = null;

            const syncZoomUi = (zoomVal) => {
                if (readoutEl) {
                    readoutEl.textContent = `${zoomVal.toFixed(1)}x / ${maxZoom.toFixed(1)}x`;
                }
                if (sliderEl) {
                    sliderEl.value = String(zoomVal);
                }
                if (resetBtn) {
                    const isDefault = Math.abs(zoomVal - minZoom) < 0.08;
                    resetBtn.disabled = isDefault;
                    resetBtn.classList.toggle('opacity-40', isDefault);
                    resetBtn.classList.toggle('cursor-not-allowed', isDefault);
                }
            };

            let pendingZoom = null;
            let isApplyingZoom = false;
            let lastApplyTs = 0;

            const applyHardwareZoom = (nextZoom, showOverlay = false) => {
                activeZoom = clampAndSnapZoom(nextZoom, minZoom, maxZoom, rawStep);
                syncZoomUi(activeZoom);
                if (showOverlay) {
                    flashZoomBadge(activeZoom);
                }

                pendingZoom = activeZoom;
                if (isApplyingZoom) return;

                const pump = async () => {
                    if (pendingZoom === null) return;
                    const elapsed = Date.now() - lastApplyTs;
                    if (elapsed < 55) {
                        await delay(55 - elapsed);
                    }
                    if (pendingZoom === null) return;
                    const target = pendingZoom;
                    pendingZoom = null;
                    isApplyingZoom = true;
                    lastApplyTs = Date.now();
                    try {
                        await track.applyConstraints({ advanced: [{ zoom: target }] });
                    } catch (_) {
                        // Abaikan reject driver sementara saat gesture cepat
                    } finally {
                        isApplyingZoom = false;
                        if (pendingZoom !== null) {
                            pump();
                        }
                    }
                };
                pump();
            };

            if (zoomBar) {
                const topRow = document.createElement('div');
                topRow.className = 'flex items-center justify-between gap-2';

                const hintEl = document.createElement('span');
                hintEl.className = 'text-[10px] font-black uppercase tracking-wider text-[#1A467C]';
                hintEl.textContent = 'Cubit Kamera / Geser Zoom';

                const rightGroup = document.createElement('div');
                rightGroup.className = 'flex items-center gap-1.5';

                readoutEl = document.createElement('span');
                readoutEl.className = 'bg-amber-300 text-black border border-black px-2 py-0.5 text-[11px] font-mono font-black';

                resetBtn = document.createElement('button');
                resetBtn.type = 'button';
                resetBtn.textContent = 'Reset 1x';
                resetBtn.className = 'px-2 py-0.5 bg-white hover:bg-gray-100 text-black border border-black text-[10px] font-black uppercase cursor-pointer';
                resetBtn.addEventListener('click', () => applyHardwareZoom(minZoom, true));

                rightGroup.appendChild(readoutEl);
                rightGroup.appendChild(resetBtn);
                topRow.appendChild(hintEl);
                topRow.appendChild(rightGroup);

                const controlRow = document.createElement('div');
                controlRow.className = 'flex items-center gap-2';

                const zoomOutBtn = document.createElement('button');
                zoomOutBtn.type = 'button';
                zoomOutBtn.textContent = '-';
                zoomOutBtn.setAttribute('aria-label', 'Perkecil Zoom');
                zoomOutBtn.className = 'w-8 h-8 shrink-0 bg-white hover:bg-gray-100 text-black border-2 border-black font-black text-base leading-none shadow-[1px_1px_0px_0px_#000000] cursor-pointer';
                zoomOutBtn.addEventListener('click', () => applyHardwareZoom(activeZoom - btnDelta, true));

                sliderEl = document.createElement('input');
                sliderEl.type = 'range';
                sliderEl.min = String(minZoom);
                sliderEl.max = String(maxZoom);
                sliderEl.step = String(sliderStep);
                sliderEl.value = String(activeZoom);
                sliderEl.setAttribute('aria-label', 'Atur Zoom Kamera');
                sliderEl.className = 'flex-1 h-2.5 accent-[#1A467C] cursor-pointer';
                sliderEl.addEventListener('input', () => {
                    applyHardwareZoom(parseFloat(sliderEl.value), true);
                });

                const zoomInBtn = document.createElement('button');
                zoomInBtn.type = 'button';
                zoomInBtn.textContent = '+';
                zoomInBtn.setAttribute('aria-label', 'Perbesar Zoom');
                zoomInBtn.className = 'w-8 h-8 shrink-0 bg-white hover:bg-gray-100 text-black border-2 border-black font-black text-base leading-none shadow-[1px_1px_0px_0px_#000000] cursor-pointer';
                zoomInBtn.addEventListener('click', () => applyHardwareZoom(activeZoom + btnDelta, true));

                controlRow.appendChild(zoomOutBtn);
                controlRow.appendChild(sliderEl);
                controlRow.appendChild(zoomInBtn);

                zoomBar.appendChild(topRow);
                zoomBar.appendChild(controlRow);
                zoomBar.classList.remove('hidden');
            }

            syncZoomUi(activeZoom);

            // Pinch-to-Zoom 2 Jari langsung di layar kamera
            container.style.touchAction = 'pan-y';
            let pinchStartDist = 0;
            let pinchStartZoom = activeZoom;

            const getTouchDist = (touches) => {
                const dx = touches[0].clientX - touches[1].clientX;
                const dy = touches[0].clientY - touches[1].clientY;
                return Math.hypot(dx, dy);
            };

            const onTouchStart = (e) => {
                if (e.touches && e.touches.length === 2) {
                    pinchStartDist = getTouchDist(e.touches);
                    pinchStartZoom = activeZoom;
                    flashZoomBadge(activeZoom);
                }
            };

            const onTouchMove = (e) => {
                if (e.touches && e.touches.length === 2 && pinchStartDist > 0) {
                    e.preventDefault();
                    const dist = getTouchDist(e.touches);
                    const ratio = dist / pinchStartDist;
                    const nextZoom = pinchStartZoom * Math.pow(ratio, 1.2);
                    applyHardwareZoom(nextZoom, true);
                }
            };

            const onTouchEnd = (e) => {
                if (!e.touches || e.touches.length < 2) {
                    pinchStartDist = 0;
                }
            };

            container.addEventListener('touchstart', onTouchStart, { passive: true });
            container.addEventListener('touchmove', onTouchMove, { passive: false });
            container.addEventListener('touchend', onTouchEnd, { passive: true });
            container.addEventListener('touchcancel', onTouchEnd, { passive: true });

            zoomCleanupByContainer.set(containerId, () => {
                container.removeEventListener('touchstart', onTouchStart);
                container.removeEventListener('touchmove', onTouchMove);
                container.removeEventListener('touchend', onTouchEnd);
                container.removeEventListener('touchcancel', onTouchEnd);
                container.style.touchAction = '';
                if (hideBadgeTimer) clearTimeout(hideBadgeTimer);
                if (overlayBadge && overlayBadge.parentNode) {
                    overlayBadge.parentNode.removeChild(overlayBadge);
                }
            });
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

    async function tryStartAttempt(containerId, cameraConfig, scanConfig, onSuccess) {
        const scanner = new Html5Qrcode(containerId, { verbose: false });
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
        const loaded = await ensureHtml5QrcodeLoaded();
        if (!loaded) {
            throw new Error('Library QR scanner gagal dimuat. Periksa koneksi internet lalu muat ulang halaman.');
        }

        if (!window.isSecureContext || !navigator.mediaDevices || typeof navigator.mediaDevices.getUserMedia !== 'function') {
            throw new Error('Akses kamera membutuhkan halaman HTTPS yang aman dan browser yang mendukung kamera.');
        }

        if (await isCameraPermissionDenied()) {
            throw new Error('NotAllowedError: Izin kamera diblokir di pengaturan browser.');
        }

        await resetScannerInstance(existingInstance, containerId);
        await new Promise((resolve) => requestAnimationFrame(() => resolve()));

        const compatConfig = { fps: 12, qrbox: safeQrBox };
        const fullFrameConfig = { fps: 10 };

        const attempts = [
            { camera: { facingMode: 'environment' }, config: compatConfig },
            { camera: { facingMode: 'user' }, config: compatConfig },
            { camera: { facingMode: 'environment' }, config: fullFrameConfig },
        ];

        let lastError = null;

        for (let i = 0; i < attempts.length; i++) {
            if (i > 0) {
                await delay(250);
            }

            const attempt = attempts[i];
            const res = await tryStartAttempt(containerId, attempt.camera, attempt.config, onSuccess);
            if (res.ok) {
                setTimeout(() => setupCameraControls(containerId, zoomBarId), 350);
                return res.scanner;
            }

            lastError = res.error;
            if (await isCameraPermissionDenied()) {
                throw lastError;
            }
        }

        try {
            await delay(250);
            const devices = await Html5Qrcode.getCameras();
            const ordered = sortCamerasPreferBack(devices);
            for (let i = 0; i < ordered.length; i++) {
                const cam = ordered[i];
                if (!cam || !cam.id) continue;
                if (i > 0) {
                    await delay(250);
                }

                const res = await tryStartAttempt(containerId, cam.id, fullFrameConfig, onSuccess);
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
            return 'Izin kamera diblokir oleh browser atau perangkat. Cek pengaturan izin situs di address bar, setel Kamera ke Izinkan, lalu muat ulang halaman.';
        }
        if (/NotReadableError|TrackStartError|AbortError|Could not start video source/i.test(raw)) {
            return 'Browser menerima izin, tetapi hardware kamera gagal dibuka (kemungkinan tab/aplikasi lain masih menahan kamera atau perlu refresh halaman). Detail: ' + raw;
        }
        if (/NotFoundError|DevicesNotFoundError|Requested device not found|no camera/i.test(raw)) {
            return 'Kamera tidak ditemukan pada perangkat ini.';
        }
        if (/OverconstrainedError|ConstraintNotSatisfiedError/i.test(raw)) {
            return 'Resolusi/mode kamera tidak didukung browser ini. Detail: ' + raw;
        }

        return raw
            ? `Tidak dapat membuka kamera (${raw}). Silakan muat ulang halaman dan coba lagi.`
            : 'Tidak dapat membuka kamera. Silakan muat ulang halaman dan coba lagi.';
    }

    window.PokjaQrScanner = {
        start: startScanner,
        stop: stopScanner,
        hideZoomBar,
        formatError,
    };
})();
