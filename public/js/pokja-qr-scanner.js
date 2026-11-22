(function () {
    function buildScanConfig() {
        return {
            fps: 15,
            qrbox: function (viewfinderWidth, viewfinderHeight) {
                const edge = Math.max(200, Math.floor(Math.min(viewfinderWidth, viewfinderHeight) * 0.82));
                return { width: edge, height: edge };
            },
            aspectRatio: 1.0,
            videoConstraints: {
                facingMode: { ideal: 'environment' },
                width: { ideal: 1280 },
                height: { ideal: 720 },
            },
        };
    }

    function createScanner(containerId) {
        return new Html5Qrcode(containerId, {
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true,
            },
            verbose: false,
        });
    }

    function setupCameraControls(containerId, zoomBarId) {
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
    }

    async function startScanner(existingInstance, containerId, zoomBarId, onSuccess) {
        const scanner = existingInstance || createScanner(containerId);
        const config = buildScanConfig();

        try {
            await scanner.start(
                { facingMode: 'environment' },
                config,
                onSuccess,
                () => {}
            );
            setTimeout(() => setupCameraControls(containerId, zoomBarId), 350);
            return scanner;
        } catch (envErr) {
            await scanner.start(
                { facingMode: 'user' },
                {
                    fps: config.fps,
                    qrbox: config.qrbox,
                    aspectRatio: config.aspectRatio,
                },
                onSuccess,
                () => {}
            );
            return scanner;
        }
    }

    function hideZoomBar(zoomBarId) {
        const zoomBar = zoomBarId ? document.getElementById(zoomBarId) : null;
        if (zoomBar) {
            zoomBar.classList.add('hidden');
            zoomBar.innerHTML = '';
        }
    }

    window.PokjaQrScanner = {
        start: startScanner,
        hideZoomBar,
    };
})();
