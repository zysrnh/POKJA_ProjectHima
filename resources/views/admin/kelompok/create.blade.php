@extends('admin.layouts.app')

@section('title', 'Buat Kelompok Baru')
@section('header_title', 'Buat Kelompok Mahasiswa')

@section('content')
@php
    $minAnggota = \App\Http\Controllers\MahasiswaKelompokController::MIN_ANGGOTA;
    $maxAnggota = \App\Http\Controllers\MahasiswaKelompokController::MAX_ANGGOTA;
    $oldKetuaId = (int) old('ketua_id', 0);
    $oldAnggotaIds = collect(old('anggota_ids', []))->map(fn ($v) => (int) $v)->all();
    $kelasOptions = $kandidatMahasiswa->pluck('kelas')->filter()->unique()->sort()->values();
@endphp
<div class="max-w-4xl mx-auto space-y-5">

    <div class="snap-in-1 flex items-center justify-between gap-3 bg-white border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000]">
        <div>
            <span class="inline-block bg-amber-400 text-black border border-black text-[10px] font-black uppercase px-2 py-0.5 tracking-wider mb-1">
                KHUSUS SUPER ADMIN
            </span>
            <h3 class="text-sm sm:text-base font-black uppercase text-[#1A467C]">
                Formulir Pembentukan Kelompok Baru
            </h3>
        </div>
        <a
            href="{{ route('admin.kelompok.index') }}"
            class="btn-smooth px-3.5 py-2 bg-white hover:bg-gray-100 text-[#1A467C] border-2 border-[#1A467C] text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#1A467C]"
        >
            ← Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="snap-in-1 p-4 bg-red-50 border-2 border-[#901C1A] text-red-950 text-xs sm:text-sm font-semibold shadow-[3px_3px_0px_0px_#901C1A]">
            <p class="font-black uppercase mb-1">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-red-900">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kelompok.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <!-- Card 1: Informasi Kelompok -->
        <div class="snap-in-2 bg-white border-2 border-[#1A467C] p-5 sm:p-6 shadow-[6px_6px_0px_0px_#1A467C] space-y-4">
            <h4 class="text-xs font-black uppercase tracking-wider text-[#1A467C] border-b-2 border-[#1A467C] pb-2">
                1. Identitas &amp; Foto Kelompok
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nama_kelompok" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                        Nama Kelompok <span class="text-[#CA2C2A]">*</span>
                    </label>
                    <input
                        type="text"
                        id="nama_kelompok"
                        name="nama_kelompok"
                        value="{{ old('nama_kelompok') }}"
                        maxlength="80"
                        required
                        placeholder="Contoh: Tim Garuda IF"
                        class="w-full bg-[#F8FBFE] border-2 @error('nama_kelompok') border-[#CA2C2A] bg-red-50 @else border-[#1A467C] @enderror text-gray-950 font-bold text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C]"
                    >
                </div>

                <div>
                    <label for="judul_proposal" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1.5">
                        Ide / Judul Proposal <span class="text-gray-400 font-semibold">(Opsional)</span>
                    </label>
                    <input
                        type="text"
                        id="judul_proposal"
                        name="judul_proposal"
                        value="{{ old('judul_proposal') }}"
                        maxlength="200"
                        placeholder="Bisa dikosongkan dan diisi nanti"
                        class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] text-gray-950 font-semibold text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C]"
                    >
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-4 bg-[#F8FBFE] border-2 border-[#1A467C] p-3.5">
                <div class="w-16 h-16 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center font-black text-base shadow-[2px_2px_0px_0px_#000000] shrink-0 overflow-hidden">
                    <img id="create-kelompok-foto-preview" src="" alt="Preview Foto" class="hidden w-full h-full object-cover">
                    <span id="create-kelompok-foto-placeholder">KL</span>
                </div>

                <div class="flex-1 min-w-0 space-y-1.5">
                    <label for="foto_kelompok_create" class="block text-xs font-black uppercase tracking-wider text-[#1A467C]">
                        Foto Profil Kelompok <span class="text-gray-400 font-semibold">(Opsional)</span>
                    </label>
                    <input
                        type="file"
                        id="foto_kelompok_create"
                        name="foto"
                        accept="image/jpeg,image/png,image/webp"
                        onchange="previewCreateKelompokFoto(this)"
                        class="block w-full text-xs font-bold text-gray-800 file:mr-3 file:py-1.5 file:px-3 file:border-2 file:border-black file:bg-amber-300 file:hover:bg-amber-400 file:text-black file:text-[10px] file:font-black file:uppercase file:cursor-pointer cursor-pointer"
                    >
                    <p class="text-[11px] font-semibold text-gray-500">JPG, PNG, WEBP • Maks. 2 MB (Min. 50x50 px)</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Penunjukan Ketua & Anggota -->
        <div class="snap-in-3 bg-white border-2 border-[#1A467C] p-5 sm:p-6 shadow-[6px_6px_0px_0px_#1A467C] space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b-2 border-[#1A467C] pb-2.5">
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-[#1A467C]">
                        2. Pilih Ketua &amp; Anggota Tim
                    </h4>
                    <p class="text-[11px] font-semibold text-gray-600 mt-0.5">
                        Wajib pilih 1 Ketua, lalu centang anggota tambahan (standar {{ $minAnggota }}-{{ $maxAnggota }} orang, Super Admin bebas menyesuaikan jumlah anggota tim). Hanya menampilkan mahasiswa yang belum punya kelompok.
                    </p>
                </div>

                <span id="create-selection-badge" class="bg-amber-300 text-black border-2 border-black px-2.5 py-1 font-mono font-black text-xs shadow-[2px_2px_0px_0px_#000000]">
                    Terpilih: 0 Orang
                </span>
            </div>

            @if ($kandidatMahasiswa->isEmpty())
                <div class="p-5 bg-amber-50 border-2 border-black text-xs font-bold text-amber-950 text-center">
                    Semua mahasiswa pendaftar sudah tergabung di kelompok. Keluarkan mahasiswa dari kelompok lain atau bubarkan kelompok lama jika ingin membuat tim baru.
                </div>
            @else
                <div class="flex flex-col sm:flex-row gap-2.5">
                    <input
                        type="text"
                        id="kandidat-search"
                        oninput="filterCreateCandidates()"
                        placeholder="Cari nama, NIM, atau kelas mahasiswa..."
                        class="flex-1 px-3 py-2 bg-[#F8FBFE] border-2 border-[#1A467C] text-xs font-bold text-gray-950 focus:bg-white focus:outline-none"
                    >
                    <select
                        id="kandidat-kelas-filter"
                        onchange="filterCreateCandidates()"
                        class="w-full sm:w-44 px-3 py-2 bg-[#F8FBFE] border-2 border-[#1A467C] text-xs font-bold text-gray-950 cursor-pointer"
                    >
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasOptions as $kls)
                            <option value="{{ strtoupper($kls) }}">{{ $kls }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="create-candidate-list" class="max-h-96 overflow-y-auto border-2 border-black divide-y divide-gray-200 bg-white">
                    @foreach ($kandidatMahasiswa as $mhs)
                        @php
                            $isKetuaOld = $oldKetuaId === (int) $mhs->id;
                            $isAnggotaOld = !$isKetuaOld && in_array((int) $mhs->id, $oldAnggotaIds, true);
                        @endphp
                        <div
                            class="candidate-row px-3.5 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 hover:bg-blue-50/40 transition"
                            data-search="{{ strtolower($mhs->nama . ' ' . $mhs->nim . ' ' . $mhs->kelas) }}"
                            data-kelas="{{ strtoupper($mhs->kelas) }}"
                            data-id="{{ $mhs->id }}"
                        >
                            <div class="min-w-0">
                                <p class="font-black text-xs sm:text-sm text-gray-950 truncate">{{ $mhs->nama }}</p>
                                <p class="font-mono text-[11px] font-bold text-gray-600">
                                    {{ $mhs->nim }} • <span class="text-[#1A467C]">{{ $mhs->kelas }}</span> • ★ {{ (int) $mhs->total_point }} PTS
                                </p>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <label class="inline-flex items-center gap-1.5 px-2.5 py-1 border border-black bg-amber-100 hover:bg-amber-200 text-[10px] font-black uppercase text-black cursor-pointer">
                                    <input
                                        type="radio"
                                        name="ketua_id"
                                        value="{{ $mhs->id }}"
                                        {{ $isKetuaOld ? 'checked' : '' }}
                                        onchange="syncCreateMemberSelection()"
                                        class="w-3.5 h-3.5 accent-black cursor-pointer"
                                    >
                                    <span>Ketua</span>
                                </label>

                                <label class="anggota-label inline-flex items-center gap-1.5 px-2.5 py-1 border border-black bg-blue-50 hover:bg-blue-100 text-[10px] font-black uppercase text-[#1A467C] cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="anggota_ids[]"
                                        value="{{ $mhs->id }}"
                                        {{ $isAnggotaOld ? 'checked' : '' }}
                                        onchange="syncCreateMemberSelection(this)"
                                        class="anggota-checkbox w-3.5 h-3.5 accent-[#1A467C] cursor-pointer"
                                    >
                                    <span>Anggota</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="create-candidate-empty" class="hidden p-4 border-2 border-black bg-gray-50 text-center text-xs font-bold text-gray-500">
                    Tidak ada mahasiswa yang cocok dengan filter pencarian.
                </div>
            @endif

            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    {{ $kandidatMahasiswa->isEmpty() ? 'disabled' : '' }}
                    class="btn-smooth px-6 py-3 bg-[#2A82C6] hover:bg-[#1A467C] disabled:bg-gray-300 disabled:text-gray-600 disabled:cursor-not-allowed text-white text-xs sm:text-sm font-black uppercase tracking-wider border-2 border-black shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                >
                    Simpan &amp; Buat Kelompok
                </button>
                <a
                    href="{{ route('admin.kelompok.index') }}"
                    class="btn-smooth px-5 py-3 border-2 border-gray-400 bg-white hover:bg-gray-100 text-gray-800 text-xs sm:text-sm font-bold uppercase tracking-wider shadow-[2px_2px_0px_0px_#9ca3af]"
                >
                    Batal
                </a>
            </div>
        </div>
    </form>

</div>

<script>
    const CREATE_MIN_ANGGOTA = {{ $minAnggota }};

    function previewCreateKelompokFoto(input) {
        const preview = document.getElementById('create-kelompok-foto-preview');
        const placeholder = document.getElementById('create-kelompok-foto-placeholder');
        if (!preview || !placeholder) return;

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function filterCreateCandidates() {
        const keyword = (document.getElementById('kandidat-search')?.value || '').trim().toLowerCase();
        const kelasFilter = (document.getElementById('kandidat-kelas-filter')?.value || '').trim().toUpperCase();
        const rows = document.querySelectorAll('#create-candidate-list .candidate-row');
        const emptyBox = document.getElementById('create-candidate-empty');
        let visibleCount = 0;

        rows.forEach(row => {
            const haystack = row.dataset.search || '';
            const rowKelas = row.dataset.kelas || '';
            const matchKeyword = !keyword || haystack.includes(keyword);
            const matchKelas = !kelasFilter || rowKelas === kelasFilter;
            const visible = matchKeyword && matchKelas;
            row.classList.toggle('hidden', !visible);
            if (visible) visibleCount++;
        });

        if (emptyBox) {
            emptyBox.classList.toggle('hidden', visibleCount > 0);
        }
    }

    function syncCreateMemberSelection() {
        const selectedKetua = document.querySelector('input[name="ketua_id"]:checked');
        const ketuaId = selectedKetua ? selectedKetua.value : null;

        document.querySelectorAll('#create-candidate-list .candidate-row').forEach(row => {
            const cb = row.querySelector('.anggota-checkbox');
            const label = row.querySelector('.anggota-label');
            if (!cb || !label) return;

            if (ketuaId && row.dataset.id === ketuaId) {
                cb.checked = false;
                cb.disabled = true;
                label.classList.add('opacity-40', 'cursor-not-allowed');
            } else {
                cb.disabled = false;
                label.classList.remove('opacity-40', 'cursor-not-allowed');
            }
        });

        const checkedAnggota = Array.from(document.querySelectorAll('.anggota-checkbox:checked'));
        const totalSelected = (ketuaId ? 1 : 0) + checkedAnggota.length;

        const badge = document.getElementById('create-selection-badge');
        if (badge) {
            badge.textContent = `Terpilih: ${totalSelected} Orang (${ketuaId ? '1 Ketua + ' : 'Belum pilih Ketua, '}${checkedAnggota.length} Anggota)`;
            const isReady = Boolean(ketuaId && totalSelected >= CREATE_MIN_ANGGOTA);
            badge.classList.toggle('bg-green-200', isReady);
            badge.classList.toggle('bg-amber-300', !isReady);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        syncCreateMemberSelection();
    });
</script>
@endsection
