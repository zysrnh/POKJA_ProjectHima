<!-- Modal Tambah / Edit Pertanyaan -->
<div id="pertanyaanModal" class="fixed inset-0 z-50 hidden bg-black/60 items-center justify-center p-4">
    <div class="bg-white border-[3px] border-black shadow-[8px_8px_0px_0px_#000000] w-full max-w-lg p-5 sm:p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b-2 border-black">
            <h3 id="pertanyaanModalTitle" class="text-base sm:text-lg font-black uppercase tracking-tight text-black">
                Tambah Pertanyaan
            </h3>
            <button
                type="button"
                onclick="closePertanyaanModal()"
                class="w-8 h-8 bg-white hover:bg-red-100 border-2 border-black font-black text-sm flex items-center justify-center cursor-pointer"
            >
                ✕
            </button>
        </div>

        <form id="pertanyaanForm" method="POST" action="{{ route('admin.kuesioner.pertanyaan.store') }}" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="pertanyaanFormMethod" value="POST">
            <input type="hidden" name="current_pertemuan" value="{{ $pertemuanFilter }}">

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">
                    Teks Pertanyaan <span class="text-red-600">*</span>
                </label>
                <textarea
                    id="inputPertanyaan"
                    name="pertanyaan"
                    rows="3"
                    required
                    maxlength="1000"
                    placeholder="Contoh: Apa masukan kamu untuk kegiatan POKJA berikutnya?"
                    class="w-full px-3 py-2 bg-white border-2 border-black text-xs sm:text-sm font-bold text-black focus:outline-none focus:bg-yellow-50"
                ></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">
                        Tipe Jawaban <span class="text-red-600">*</span>
                    </label>
                    <select
                        id="inputTipe"
                        name="tipe"
                        onchange="toggleOpsiField()"
                        class="w-full px-3 py-2 bg-white border-2 border-black text-xs sm:text-sm font-bold text-black focus:outline-none"
                    >
                        <option value="teks">Isian Singkat</option>
                        <option value="paragraf">Paragraf / Uraian</option>
                        <option value="pilihan">Pilihan Ganda</option>
                        <option value="tanggal">Pilihan Tanggal</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">
                        Urutan Tampil
                    </label>
                    <input
                        id="inputUrutan"
                        type="number"
                        name="urutan"
                        min="0"
                        max="9999"
                        placeholder="Otomatis"
                        class="w-full px-3 py-2 bg-white border-2 border-black text-xs sm:text-sm font-bold text-black focus:outline-none focus:bg-yellow-50"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">
                    Berlaku Untuk Pertemuan
                </label>
                <select
                    id="inputPertemuan"
                    name="pertemuan"
                    class="w-full px-3 py-2 bg-white border-2 border-black text-xs sm:text-sm font-bold text-black focus:outline-none"
                >
                    <option value="">Semua Pertemuan (P1 - P6)</option>
                    @foreach($pertemuanList as $pNum)
                        <option value="{{ $pNum }}">Pertemuan {{ $pNum }}</option>
                    @endforeach
                </select>
            </div>

            <div id="opsiFieldWrapper" class="hidden">
                <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">
                    Opsi Pilihan Ganda (1 baris = 1 pilihan) <span class="text-red-600">*</span>
                </label>
                <textarea
                    id="inputOpsiText"
                    name="opsi_text"
                    rows="4"
                    placeholder="Sangat Setuju&#10;Setuju&#10;Netral&#10;Tidak Setuju"
                    class="w-full px-3 py-2 bg-white border-2 border-black text-xs sm:text-sm font-bold text-black focus:outline-none focus:bg-yellow-50"
                ></textarea>
            </div>

            <div class="flex flex-wrap items-center gap-4 pt-1">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input id="inputWajib" type="checkbox" name="wajib" value="1" checked class="w-4 h-4 accent-[#1A467C]">
                    <span class="text-xs font-black uppercase text-black">Wajib Diisi</span>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input id="inputAktif" type="checkbox" name="aktif" value="1" checked class="w-4 h-4 accent-[#1A467C]">
                    <span class="text-xs font-black uppercase text-black">Aktifkan Pertanyaan</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t-2 border-black">
                <button
                    type="button"
                    onclick="closePertanyaanModal()"
                    class="btn-smooth px-4 py-2 bg-white hover:bg-gray-100 text-black border-2 border-black font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="btn-smooth px-5 py-2 bg-[#1A467C] hover:bg-[#12335C] text-white border-2 border-black font-black text-xs uppercase shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                >
                    Simpan Pertanyaan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const storePertanyaanUrl = @json(route('admin.kuesioner.pertanyaan.store'));
    const updatePertanyaanUrlTpl = @json(route('admin.kuesioner.pertanyaan.update', ['id' => '__ID__']));
    const defaultAdminPertemuan = @json((string) $pertemuanFilter);

    function toggleOpsiField() {
        const tipe = document.getElementById('inputTipe').value;
        const wrapper = document.getElementById('opsiFieldWrapper');
        if (tipe === 'pilihan') {
            wrapper.classList.remove('hidden');
        } else {
            wrapper.classList.add('hidden');
        }
    }

    function openPertanyaanModal(mode, data = null) {
        const modal = document.getElementById('pertanyaanModal');
        const title = document.getElementById('pertanyaanModalTitle');
        const form = document.getElementById('pertanyaanForm');
        const methodInput = document.getElementById('pertanyaanFormMethod');

        if (mode === 'edit' && data) {
            title.textContent = 'Edit Pertanyaan';
            form.action = updatePertanyaanUrlTpl.replace('__ID__', data.id);
            methodInput.value = 'PUT';

            document.getElementById('inputPertanyaan').value = data.pertanyaan || '';
            document.getElementById('inputTipe').value = data.tipe || 'teks';
            document.getElementById('inputUrutan').value = data.urutan ?? '';
            document.getElementById('inputPertemuan').value = data.pertemuan ? String(data.pertemuan) : '';
            document.getElementById('inputOpsiText').value = Array.isArray(data.opsi) ? data.opsi.join('\n') : '';
            document.getElementById('inputWajib').checked = Boolean(data.wajib);
            document.getElementById('inputAktif').checked = Boolean(data.aktif);
        } else {
            title.textContent = 'Tambah Pertanyaan';
            form.action = storePertanyaanUrl;
            methodInput.value = 'POST';

            document.getElementById('inputPertanyaan').value = '';
            document.getElementById('inputTipe').value = 'teks';
            document.getElementById('inputUrutan').value = '';
            document.getElementById('inputPertemuan').value = defaultAdminPertemuan;
            document.getElementById('inputOpsiText').value = '';
            document.getElementById('inputWajib').checked = true;
            document.getElementById('inputAktif').checked = true;
        }

        toggleOpsiField();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePertanyaanModal() {
        const modal = document.getElementById('pertanyaanModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function copyKuesionerLink() {
        const url = @json(route('mahasiswa.dashboard', ['tab' => 'kuesioner', 'pertemuan' => $pertemuanFilter]));
        const label = document.getElementById('copyKuesionerLinkText');
        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(url);
            } else {
                const tmp = document.createElement('textarea');
                tmp.value = url;
                tmp.style.position = 'fixed';
                tmp.style.left = '-9999px';
                document.body.appendChild(tmp);
                tmp.select();
                document.execCommand('copy');
                tmp.remove();
            }
            if (label) {
                label.textContent = 'Link Tersalin!';
                setTimeout(() => {
                    label.textContent = 'Salin Link';
                }, 2000);
            }
        } catch (e) {
            window.prompt('Salin link kuesioner berikut:', url);
        }
    }
</script>
