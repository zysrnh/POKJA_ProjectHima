<!-- Modal Detail Jawaban Anggota -->
<div id="detailJawabanModal" class="fixed inset-0 z-50 hidden bg-black/60 items-center justify-center p-4">
    <div class="bg-white border-[3px] border-black shadow-[8px_8px_0px_0px_#000000] w-full max-w-2xl p-5 sm:p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-3 pb-3 border-b-2 border-black">
            <div>
                <h3 id="detailMhsNama" class="text-base sm:text-lg font-black uppercase tracking-tight text-black">-</h3>
                <p id="detailMhsMeta" class="text-xs font-bold text-gray-600 mt-0.5">-</p>
            </div>
            <button
                type="button"
                onclick="closeDetailJawabanModal()"
                class="w-8 h-8 bg-white hover:bg-red-100 border-2 border-black font-black text-sm flex items-center justify-center cursor-pointer shrink-0"
            >
                ✕
            </button>
        </div>

        <div id="detailJawabanBody" class="mt-4 space-y-3"></div>

        <div class="flex justify-end pt-4 mt-4 border-t-2 border-black">
            <button
                type="button"
                onclick="closeDetailJawabanModal()"
                class="btn-smooth px-4 py-2 bg-[#1A467C] text-white border-2 border-black font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
            >
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openDetailJawabanModal(payload) {
        document.getElementById('detailMhsNama').textContent = payload.nama || '-';
        document.getElementById('detailMhsMeta').textContent = `${payload.nim || '-'} • ${payload.kelas || '-'}`;

        const body = document.getElementById('detailJawabanBody');
        body.innerHTML = '';

        const items = Array.isArray(payload.items) ? payload.items : [];
        items.forEach((item, index) => {
            const card = document.createElement('div');
            card.className = 'p-3.5 bg-gray-50 border-2 border-black';

            const qTitle = document.createElement('p');
            qTitle.className = 'text-xs font-black text-[#1A467C] uppercase';
            qTitle.textContent = `Pertanyaan #${index + 1}`;

            const qText = document.createElement('p');
            qText.className = 'text-xs sm:text-sm font-black text-black mt-0.5';
            qText.textContent = item.pertanyaan || '-';

            const aText = document.createElement('p');
            aText.className = 'text-xs sm:text-sm font-semibold text-gray-800 mt-2 pt-2 border-t border-gray-300 whitespace-pre-line';
            aText.textContent = item.jawaban || '-';

            card.appendChild(qTitle);
            card.appendChild(qText);
            card.appendChild(aText);
            body.appendChild(card);
        });

        const modal = document.getElementById('detailJawabanModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDetailJawabanModal() {
        const modal = document.getElementById('detailJawabanModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
