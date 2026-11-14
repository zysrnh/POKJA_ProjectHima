(function () {
    const MODAL_ID = 'pokja-lb-capture-modal';

    function drawHardShadowRect(ctx, x, y, w, h, fill, borderColor = '#000000', borderWidth = 3, shadowOffset = 6) {
        if (shadowOffset > 0) {
            ctx.fillStyle = '#000000';
            ctx.fillRect(x + shadowOffset, y + shadowOffset, w, h);
        }
        ctx.fillStyle = fill;
        ctx.fillRect(x, y, w, h);
        if (borderWidth > 0) {
            ctx.lineWidth = borderWidth;
            ctx.strokeStyle = borderColor;
            ctx.strokeRect(x + borderWidth / 2, y + borderWidth / 2, w - borderWidth, h - borderWidth);
        }
    }

    function truncateText(ctx, text, maxWidth) {
        const str = String(text ?? '').trim();
        if (!str) return '-';
        if (ctx.measureText(str).width <= maxWidth) return str;
        let out = str;
        while (out.length > 1 && ctx.measureText(out + '...').width > maxWidth) {
            out = out.slice(0, -1);
        }
        return out + '...';
    }

    function loadImageSafe(url) {
        return new Promise((resolve) => {
            if (!url) {
                resolve(null);
                return;
            }
            let settled = false;
            const img = new Image();
            img.crossOrigin = 'anonymous';
            const timer = setTimeout(() => {
                if (!settled) {
                    settled = true;
                    resolve(null);
                }
            }, 1500);
            img.onload = () => {
                if (!settled) {
                    settled = true;
                    clearTimeout(timer);
                    resolve(img);
                }
            };
            img.onerror = () => {
                if (!settled) {
                    settled = true;
                    clearTimeout(timer);
                    resolve(null);
                }
            };
            img.src = url;
        });
    }

    async function renderLeaderboardCanvas(options) {
        const mode = options.mode === 'kelompok' ? 'kelompok' : 'individu';
        const items = Array.isArray(options.items) ? options.items : [];
        const kelas = (options.kelas || '').trim();
        const topThree = items.slice(0, 3);
        const belowPodium = items.slice(3, 10);

        const W = 1080;
        const H = belowPodium.length > 0 ? 1350 : 880;
        const canvas = document.createElement('canvas');
        canvas.width = W;
        canvas.height = H;
        const ctx = canvas.getContext('2d');

        // Background + dot pattern
        ctx.fillStyle = '#F4F8FC';
        ctx.fillRect(0, 0, W, H);
        ctx.fillStyle = 'rgba(26, 70, 124, 0.14)';
        for (let gx = 24; gx < W; gx += 32) {
            for (let gy = 24; gy < H; gy += 32) {
                ctx.fillRect(gx, gy, 3, 3);
            }
        }

        // Main outer card
        const pad = 44;
        const cardW = W - pad * 2;
        const cardH = H - pad * 2;
        drawHardShadowRect(ctx, pad, pad, cardW, cardH, '#FFFFFF', '#000000', 4, 10);

        // Header bar inside card
        const hx = pad + 32;
        const hy = pad + 32;
        const hw = cardW - 64;

        drawHardShadowRect(ctx, hx, hy, 56, 56, '#FBBF24', '#000000', 3, 4);
        ctx.fillStyle = '#000000';
        ctx.font = '900 30px system-ui, -apple-system, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('★', hx + 28, hy + 30);

        ctx.textAlign = 'left';
        ctx.fillStyle = '#1A467C';
        ctx.font = '900 28px system-ui, -apple-system, sans-serif';
        ctx.fillText('LEADERBOARD POINT KEAKTIFAN', hx + 76, hy + 20);

        ctx.fillStyle = '#4B5563';
        ctx.font = '700 18px system-ui, -apple-system, sans-serif';
        const modeLabel = mode === 'kelompok' ? 'KLASEMEN KELOMPOK' : 'KLASEMEN PESERTA';
        const subHeader = kelas ? `${modeLabel} • KELAS ${kelas.toUpperCase()}` : `${modeLabel} • POKJA HIMA IF`;
        ctx.fillText(subHeader, hx + 76, hy + 46);

        // Mode pill on top right
        const badgeText = mode === 'kelompok' ? 'KELOMPOK' : 'PESERTA';
        ctx.font = '900 16px monospace';
        const badgeW = Math.max(120, ctx.measureText(badgeText).width + 32);
        drawHardShadowRect(ctx, hx + hw - badgeW, hy + 10, badgeW, 38, '#1A467C', '#000000', 3, 3);
        ctx.fillStyle = '#FFFFFF';
        ctx.textAlign = 'center';
        ctx.fillText(badgeText, hx + hw - badgeW / 2, hy + 30);

        // Divider line
        ctx.fillStyle = '#000000';
        ctx.fillRect(hx, hy + 78, hw, 3);

        // Preload top 3 & below avatars (best effort)
        const podiumOrder = [
            { rank: 2, entry: topThree[1] || null, barH: 150, barBg: '#BAE6FD', colX: hx + 16 },
            { rank: 1, entry: topThree[0] || null, barH: 215, barBg: '#FCD34D', colX: hx + hw / 3 + 8 },
            { rank: 3, entry: topThree[2] || null, barH: 110, barBg: '#FED7AA', colX: hx + (hw * 2) / 3 },
        ];
        const colW = hw / 3 - 16;

        const loadedPhotos = await Promise.all(
            items.slice(0, 10).map((item) => loadImageSafe(item.photoUrl))
        );
        const photoMap = new Map();
        items.slice(0, 10).forEach((item, idx) => {
            photoMap.set(idx, loadedPhotos[idx]);
        });

        // Draw podium section
        const podiumBottomY = hy + 520;
        ctx.fillStyle = '#000000';
        ctx.fillRect(hx + 16, podiumBottomY, hw - 32, 5);

        podiumOrder.forEach((slot) => {
            const cx = slot.colX + colW / 2;
            const barW = colW - 28;
            const barX = cx - barW / 2;
            const barY = podiumBottomY - slot.barH;

            if (!slot.entry) {
                ctx.save();
                ctx.setLineDash([8, 6]);
                ctx.strokeStyle = '#9CA3AF';
                ctx.lineWidth = 3;
                ctx.fillStyle = '#F9FAFB';
                ctx.fillRect(barX, barY, barW, slot.barH);
                ctx.strokeRect(barX, barY, barW, slot.barH);
                ctx.restore();
                ctx.fillStyle = '#9CA3AF';
                ctx.font = '900 32px monospace';
                ctx.textAlign = 'center';
                ctx.fillText(`#${slot.rank}`, cx, barY + slot.barH / 2);
                return;
            }

            const entry = slot.entry;
            const itemIndex = slot.rank - 1;
            const img = photoMap.get(itemIndex);

            // Podium bar
            drawHardShadowRect(ctx, barX, barY, barW, slot.barH, slot.barBg, '#000000', 4, 0);
            ctx.fillStyle = '#111827';
            ctx.font = slot.rank === 1 ? '900 44px monospace' : '900 36px monospace';
            ctx.textAlign = 'center';
            ctx.fillText(`#${slot.rank}`, cx, barY + slot.barH / 2 - 8);

            ctx.fillStyle = '#374151';
            ctx.font = '700 15px monospace';
            const barFooter = truncateText(ctx, entry.barFooter || '', barW - 20);
            ctx.fillText(barFooter, cx, barY + slot.barH / 2 + 24);

            // Point badge above bar
            const ptsText = `${entry.points} PTS`;
            ctx.font = '900 18px monospace';
            const ptsW = Math.max(92, ctx.measureText(ptsText).width + 24);
            const ptsY = barY - 46;
            drawHardShadowRect(ctx, cx - ptsW / 2, ptsY, ptsW, 34, '#FFFFFF', '#000000', 3, 3);
            ctx.fillStyle = '#15803D';
            ctx.fillText(ptsText, cx, ptsY + 18);

            // Subtitle & Name
            ctx.fillStyle = '#1A467C';
            ctx.font = '800 16px monospace';
            ctx.fillText(truncateText(ctx, entry.subtitle, colW - 12), cx, ptsY - 18);

            ctx.fillStyle = '#030712';
            ctx.font = '900 19px system-ui, -apple-system, sans-serif';
            ctx.fillText(truncateText(ctx, entry.title.toUpperCase(), colW - 8), cx, ptsY - 44);

            // Avatar box
            const avSize = slot.rank === 1 ? 82 : 70;
            const avX = cx - avSize / 2;
            const avY = ptsY - 64 - avSize;
            drawHardShadowRect(
                ctx,
                avX,
                avY,
                avSize,
                avSize,
                mode === 'kelompok' ? '#1A467C' : '#FFFFFF',
                '#000000',
                3,
                4
            );

            if (img) {
                ctx.save();
                ctx.beginPath();
                ctx.rect(avX + 3, avY + 3, avSize - 6, avSize - 6);
                ctx.clip();
                ctx.drawImage(img, avX + 3, avY + 3, avSize - 6, avSize - 6);
                ctx.restore();
            } else {
                ctx.fillStyle = mode === 'kelompok' ? '#FFFFFF' : '#1A467C';
                ctx.font = slot.rank === 1 ? '900 28px system-ui, sans-serif' : '900 24px system-ui, sans-serif';
                ctx.fillText(entry.initials || '--', cx, avY + avSize / 2 + 2);
            }
        });

        // Below podium list (#4 - #10)
        if (belowPodium.length > 0) {
            const listTopY = podiumBottomY + 36;
            ctx.fillStyle = '#1A467C';
            ctx.font = '900 20px system-ui, -apple-system, sans-serif';
            ctx.textAlign = 'left';
            ctx.fillText(
                mode === 'kelompok' ? 'PERINGKAT KELOMPOK (#4 - #10)' : 'PERINGKAT MAHASISWA (#4 - #10)',
                hx,
                listTopY
            );

            const rowStartY = listTopY + 24;
            const rowH = 78;
            const rowGap = 12;

            belowPodium.forEach((item, i) => {
                const ry = rowStartY + i * (rowH + rowGap);
                const rowBg = item.isMine ? '#ECFDF5' : '#FFFFFF';
                drawHardShadowRect(ctx, hx, ry, hw, rowH, rowBg, '#000000', 3, 4);

                // Rank square
                drawHardShadowRect(ctx, hx + 14, ry + 15, 48, 48, '#F3F4F6', '#000000', 2, 0);
                ctx.fillStyle = '#111827';
                ctx.font = '900 18px monospace';
                ctx.textAlign = 'center';
                ctx.fillText(`#${item.rank}`, hx + 38, ry + 40);

                // Avatar square
                const img = photoMap.get(i + 3);
                drawHardShadowRect(
                    ctx,
                    hx + 74,
                    ry + 15,
                    48,
                    48,
                    mode === 'kelompok' ? '#1A467C' : '#EFF6FF',
                    '#000000',
                    2,
                    0
                );
                if (img) {
                    ctx.save();
                    ctx.beginPath();
                    ctx.rect(hx + 76, ry + 17, 44, 44);
                    ctx.clip();
                    ctx.drawImage(img, hx + 76, ry + 17, 44, 44);
                    ctx.restore();
                } else {
                    ctx.fillStyle = mode === 'kelompok' ? '#FFFFFF' : '#1A467C';
                    ctx.font = '900 16px system-ui, sans-serif';
                    ctx.fillText(item.initials || '--', hx + 98, ry + 40);
                }

                // Name + subtitle
                ctx.textAlign = 'left';
                ctx.fillStyle = '#030712';
                ctx.font = '900 20px system-ui, -apple-system, sans-serif';
                const nameLabel = item.isMine ? `${item.title} (KAMU)` : item.title;
                ctx.fillText(truncateText(ctx, nameLabel, hw - 310), hx + 138, ry + 30);

                ctx.fillStyle = '#1A467C';
                ctx.font = '700 15px monospace';
                ctx.fillText(truncateText(ctx, item.subtitle, hw - 310), hx + 138, ry + 54);

                // Point badge
                const rowPts = `★ ${item.points} PTS`;
                ctx.font = '900 17px monospace';
                const rPtsW = Math.max(115, ctx.measureText(rowPts).width + 26);
                drawHardShadowRect(ctx, hx + hw - rPtsW - 14, ry + 19, rPtsW, 40, '#DCFCE7', '#000000', 2, 0);
                ctx.fillStyle = '#15803D';
                ctx.textAlign = 'center';
                ctx.fillText(rowPts, hx + hw - rPtsW / 2 - 14, ry + 40);
            });
        }

        // Footer
        const footY = pad + cardH - 36;
        ctx.fillStyle = '#E5E7EB';
        ctx.fillRect(hx, footY - 22, hw, 2);

        const nowStr = new Date().toLocaleString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
        });
        ctx.fillStyle = '#6B7280';
        ctx.font = '700 15px monospace';
        ctx.textAlign = 'left';
        ctx.fillText(`POKJA HIMA IF • Diambil ${nowStr} WIB`, hx, footY);

        if (options.myRank && options.myRank !== '-') {
            ctx.textAlign = 'right';
            ctx.fillStyle = '#1A467C';
            ctx.font = '900 16px monospace';
            ctx.fillText(`PERINGKAT KAMU: #${options.myRank}`, hx + hw, footY);
        }

        return new Promise((resolve) => {
            canvas.toBlob((blob) => {
                resolve({ canvas, blob, dataUrl: canvas.toDataURL('image/png') });
            }, 'image/png');
        });
    }

    function ensureModal() {
        let modal = document.getElementById(MODAL_ID);
        if (modal) return modal;

        modal = document.createElement('div');
        modal.id = MODAL_ID;
        modal.className = 'hidden fixed inset-0 z-50 bg-black/70 p-4 flex items-center justify-center';
        modal.innerHTML = `
            <div class="w-full max-w-lg bg-white border-[3px] border-black shadow-[8px_8px_0px_0px_#000000] p-4 sm:p-5 space-y-4">
                <div class="flex items-center justify-between border-b-2 border-black pb-3">
                    <div>
                        <span class="bg-amber-300 border border-black px-2 py-0.5 text-[10px] font-black uppercase">CAPTURE & SHARE</span>
                        <h4 class="text-sm sm:text-base font-black uppercase text-[#1A467C] mt-1">Preview Kartu Leaderboard</h4>
                    </div>
                    <button type="button" id="pokja-cap-close" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 border-2 border-black font-black text-sm cursor-pointer">✕</button>
                </div>

                <div class="bg-[#F4F8FC] border-2 border-black p-2 flex items-center justify-center min-h-[220px]">
                    <p id="pokja-cap-loading" class="text-xs font-mono font-bold text-gray-500">Menyiapkan gambar leaderboard...</p>
                    <img id="pokja-cap-preview" alt="Preview Leaderboard" class="hidden max-h-[55vh] w-auto object-contain border border-black">
                </div>

                <p id="pokja-cap-status" class="hidden text-[11px] font-bold text-[#15803d] bg-green-50 border border-black px-2.5 py-1.5 text-center"></p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                    <button type="button" id="pokja-cap-share" class="px-3 py-2.5 bg-[#15803d] hover:bg-green-800 text-white border-2 border-black text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer">
                        Share
                    </button>
                    <button type="button" id="pokja-cap-download" class="px-3 py-2.5 bg-[#1A467C] hover:bg-blue-900 text-white border-2 border-black text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer">
                        Download PNG
                    </button>
                    <button type="button" id="pokja-cap-copy" class="px-3 py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer">
                        Salin Gambar
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.add('hidden');
        });
        document.getElementById('pokja-cap-close').addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        return modal;
    }

    let currentCapture = null;

    function setStatus(msg, isError = false) {
        const el = document.getElementById('pokja-cap-status');
        if (!el) return;
        el.textContent = msg;
        el.className = isError
            ? 'text-[11px] font-bold text-[#CA2C2A] bg-red-50 border border-black px-2.5 py-1.5 text-center'
            : 'text-[11px] font-bold text-[#15803d] bg-green-50 border border-black px-2.5 py-1.5 text-center';
        el.classList.remove('hidden');
    }

    function triggerDownload(dataUrl, filename) {
        const a = document.createElement('a');
        a.href = dataUrl;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
    }

    async function openCaptureModal(options) {
        const modal = ensureModal();
        const loading = document.getElementById('pokja-cap-loading');
        const preview = document.getElementById('pokja-cap-preview');
        const status = document.getElementById('pokja-cap-status');
        const shareBtn = document.getElementById('pokja-cap-share');
        const dlBtn = document.getElementById('pokja-cap-download');
        const copyBtn = document.getElementById('pokja-cap-copy');

        if (status) status.classList.add('hidden');
        if (loading) loading.classList.remove('hidden');
        if (preview) preview.classList.add('hidden');
        modal.classList.remove('hidden');

        currentCapture = await renderLeaderboardCanvas(options);
        const filename = `leaderboard-pokja-${options.mode || 'individu'}-${Date.now()}.png`;

        if (preview) {
            preview.src = currentCapture.dataUrl;
            preview.classList.remove('hidden');
        }
        if (loading) loading.classList.add('hidden');

        dlBtn.onclick = () => {
            if (!currentCapture) return;
            triggerDownload(currentCapture.dataUrl, filename);
            setStatus('Gambar leaderboard berhasil didownload.');
        };

        copyBtn.onclick = async () => {
            if (!currentCapture || !currentCapture.blob) return;
            try {
                if (navigator.clipboard && window.ClipboardItem) {
                    await navigator.clipboard.write([
                        new ClipboardItem({ 'image/png': currentCapture.blob }),
                    ]);
                    setStatus('Gambar disalin ke clipboard! Tinggal paste di WhatsApp/chat.');
                } else {
                    triggerDownload(currentCapture.dataUrl, filename);
                    setStatus('Clipboard gambar tidak didukung browser ini — gambar otomatis didownload.');
                }
            } catch (err) {
                triggerDownload(currentCapture.dataUrl, filename);
                setStatus('Gagal menyalin ke clipboard — gambar otomatis didownload.');
            }
        };

        shareBtn.onclick = async () => {
            if (!currentCapture || !currentCapture.blob) return;
            const file = new File([currentCapture.blob], filename, { type: 'image/png' });
            const shareTitle = 'Leaderboard Point Keaktifan POKJA HIMA IF';
            const shareText = 'Update klasemen sementara Point Keaktifan POKJA HIMA IF!';

            try {
                if (navigator.share && navigator.canShare && navigator.canShare({ files: [file] })) {
                    await navigator.share({
                        files: [file],
                        title: shareTitle,
                        text: shareText,
                    });
                    setStatus('Leaderboard berhasil dibagikan!');
                    return;
                }
            } catch (err) {
                if (err && err.name === 'AbortError') return;
            }

            triggerDownload(currentCapture.dataUrl, filename);
            setStatus('Browser desktop tidak mendukung share file langsung — gambar otomatis didownload, siap di-share.');
        };
    }

    window.PokjaLeaderboardCapture = {
        open: openCaptureModal,
    };
})();

