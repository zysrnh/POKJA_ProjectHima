@extends('admin.layouts.app')

@section('title', 'Edit & Kelola Kelompok')
@section('header_title', 'Edit & Kelola Anggota Kelompok')

@section('content')
@php
    $minAnggota = \App\Http\Controllers\MahasiswaKelompokController::MIN_ANGGOTA;
    $maxAnggota = \App\Http\Controllers\MahasiswaKelompokController::MAX_ANGGOTA;
    $fotoUrl = $kelompok->fotoUrl();
    $jumlahAnggota = $kelompok->members->count();
    $totalPointTim = $kelompok->members->sum(fn ($m) => (int) ($m->pendaftaran->total_point ?? 0));
    $kelasOptions = $kandidatMahasiswa->pluck('kelas')->filter()->unique()->sort()->values();
@endphp
<div class="max-w-5xl mx-auto space-y-5">

    <!-- Header Summary Card -->
    <div class="snap-in-1 bg-white border-2 border-black p-4 sm:p-5 shadow-[5px_5px_0px_0px_#000000] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5 min-w-0">
            <div class="w-14 h-14 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center font-black text-base shadow-[2px_2px_0px_0px_#000000] shrink-0 overflow-hidden">
                @if ($fotoUrl)
                    <img src="{{ $fotoUrl }}" alt="Foto {{ $kelompok->nama_kelompok }}" class="w-full h-full object-cover">
                @else
                    {{ $kelompok->initials() }}
                @endif
            </div>

            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="bg-amber-400 text-black border border-black px-2 py-0.5 text-[10px] font-black uppercase">
                        KELOLA SUPER ADMIN
                    </span>
                    <span class="bg-gray-100 border border-black px-2 py-0.5 font-mono font-black text-[10px] text-gray-800">
                        {{ $kelompok->kode_invite }}
                    </span>
                    <span class="{{ $jumlahAnggota >= $maxAnggota ? 'bg-blue-100 text-[#1A467C]' : ($jumlahAnggota >= $minAnggota ? 'bg-green-100 text-green-900' : 'bg-amber-100 text-amber-950') }} border border-black px-2 py-0.5 text-[10px] font-black uppercase">
                        {{ $jumlahAnggota }} Anggota
                    </span>
                    <span class="bg-green-50 border border-black px-2 py-0.5 font-mono font-black text-[10px] text-[#15803d]">
                        ★ {{ $totalPointTim }} PTS Tim
                    </span>
                </div>
                <h3 class="text-base sm:text-lg font-black uppercase text-[#1A467C] mt-1 break-words">
                    {{ $kelompok->nama_kelompok }}
                </h3>
                <p class="text-xs font-semibold text-gray-600">
                    Ketua saat ini: <strong class="text-gray-950">{{ $kelompok->ketua->nama ?? '-' }}</strong>
                    @if (!empty($kelompok->ketua?->kelas))
                        <span class="font-mono text-[#1A467C]">({{ $kelompok->ketua->kelas }})</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a
                href="{{ route('admin.kelompok.index') }}"
                class="btn-smooth px-3.5 py-2 bg-white hover:bg-gray-100 text-[#1A467C] border-2 border-[#1A467C] text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#1A467C]"
            >
                ← Daftar Kelompok
            </a>

            <form
                action="{{ route('admin.kelompok.destroy', $kelompok->id) }}"
                method="POST"
                data-confirm="Bubarkan kelompok {{ $kelompok->nama_kelompok }} secara permanen?"
                onsubmit="return confirm(this.dataset.confirm);"
            >
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="btn-smooth px-3.5 py-2 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    Bubarkan Tim
                </button>
            </form>
        </div>
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        <!-- Left Column: Edit Info Kelompok -->
        <div class="lg:col-span-5 space-y-5">
            <div class="snap-in-2 bg-white border-2 border-[#1A467C] p-5 shadow-[6px_6px_0px_0px_#1A467C]">
                <h4 class="text-xs font-black uppercase tracking-wider text-[#1A467C] border-b-2 border-[#1A467C] pb-2 mb-4">
                    Edit Info &amp; Foto Kelompok
                </h4>

                <form action="{{ route('admin.kelompok.update', $kelompok->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center gap-3.5 bg-[#F8FBFE] border-2 border-[#1A467C] p-3">
                        <div class="w-16 h-16 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center font-black text-base shadow-[2px_2px_0px_0px_#000000] shrink-0 overflow-hidden">
                            <img
                                id="edit-kelompok-foto-preview"
                                src="{{ $fotoUrl ?? '' }}"
                                alt="Foto {{ $kelompok->nama_kelompok }}"
                                class="{{ $fotoUrl ? '' : 'hidden ' }}w-full h-full object-cover"
                            >
                            <span id="edit-kelompok-foto-placeholder" class="{{ $fotoUrl ? 'hidden' : '' }}">
                                {{ $kelompok->initials() }}
                            </span>
                        </div>

                        <div class="min-w-0 flex-1 space-y-1.5">
                            <input
                                type="file"
                                id="foto_kelompok_edit"
                                name="foto"
                                accept="image/jpeg,image/png,image/webp"
                                onchange="previewEditKelompokFoto(this)"
                                class="block w-full text-xs font-bold text-gray-800 file:mr-2 file:py-1 file:px-2.5 file:border-2 file:border-black file:bg-amber-300 file:hover:bg-amber-400 file:text-black file:text-[10px] file:font-black file:uppercase file:cursor-pointer cursor-pointer"
                            >
                            @if ($fotoUrl)
                                <label class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase text-[#CA2C2A] cursor-pointer">
                                    <input type="checkbox" id="edit-hapus-foto" name="hapus_foto" value="1" class="w-3.5 h-3.5 accent-[#CA2C2A]">
                                    <span>Hapus foto saat ini</span>
                                </label>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label for="nama_kelompok" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                            Nama Kelompok <span class="text-[#CA2C2A]">*</span>
                        </label>
                        <input
                            type="text"
                            id="nama_kelompok"
                            name="nama_kelompok"
                            value="{{ old('nama_kelompok', $kelompok->nama_kelompok) }}"
                            maxlength="80"
                            required
                            class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] text-gray-950 font-bold text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="judul_proposal" class="block text-xs font-black uppercase tracking-wider text-[#1A467C] mb-1">
                            Ide / Judul Proposal
                        </label>
                        <input
                            type="text"
                            id="judul_proposal"
                            name="judul_proposal"
                            value="{{ old('judul_proposal', $kelompok->judul_proposal) }}"
                            maxlength="200"
                            placeholder="Opsional"
                            class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] text-gray-950 font-semibold text-sm px-3.5 py-2.5 focus:bg-white focus:outline-none"
                        >
                    </div>

                    <button
                        type="submit"
                        class="btn-smooth w-full py-2.5 bg-[#2A82C6] hover:bg-[#1A467C] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                    >
                        Simpan Perubahan Info
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Susunan Anggota & Tambah Anggota -->
        <div class="lg:col-span-7 space-y-5">

            <!-- Susunan Anggota Saat Ini -->
            <div class="snap-in-2 bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
                <div class="px-4 sm:px-5 py-3 border-b-2 border-black bg-blue-50/80 flex items-center justify-between">
                    <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                        Susunan Anggota Tim
                    </h4>
                    <span class="bg-white border border-black px-2 py-0.5 font-mono font-black text-[10px] text-[#1A467C]">
                        {{ $jumlahAnggota }} Orang
                    </span>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach ($kelompok->members as $member)
                        @php
                            $mhs = $member->pendaftaran;
                            $isKetuaMember = (int) $member->pendaftaran_id === (int) $kelompok->ketua_id;
                        @endphp
                        @if ($mhs)
                            <div class="px-4 sm:px-5 py-3.5 {{ $isKetuaMember ? 'bg-amber-50/60' : 'bg-white' }} flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div class="w-10 h-10 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden">
                                        @if ($mhs->avatarUrl())
                                            <img src="{{ $mhs->avatarUrl() }}" alt="Avatar {{ $mhs->nama }}" class="w-full h-full object-cover">
                                        @else
                                            {{ $mhs->initials() }}
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            <p class="font-black text-xs sm:text-sm text-gray-950 truncate">{{ $mhs->nama }}</p>
                                            <span class="{{ $isKetuaMember ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-800' }} border border-black text-[9px] font-black uppercase px-1.5 py-0.2">
                                                {{ $isKetuaMember ? 'KETUA' : 'ANGGOTA' }}
                                            </span>
                                        </div>
                                        <p class="font-mono text-[11px] font-bold text-gray-600 mt-0.5">
                                            {{ $mhs->nim }} • <span class="text-[#1A467C]">{{ $mhs->kelas }}</span> • <span class="text-[#15803d]">★ {{ (int) $mhs->total_point }} PTS</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-1.5 shrink-0">
                                    @if ($isKetuaMember)
                                        <span class="px-2.5 py-1 bg-amber-100 border border-black text-[10px] font-black uppercase text-amber-950">
                                            Ketua Aktif
                                        </span>
                                    @else
                                        <form action="{{ route('admin.kelompok.ketua.set', [$kelompok->id, $member->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="btn-smooth px-2.5 py-1.5 bg-amber-300 hover:bg-amber-400 text-black border border-black text-[10px] font-black uppercase cursor-pointer"
                                            >
                                                Jadikan Ketua
                                            </button>
                                        </form>

                                        <form
                                            action="{{ route('admin.kelompok.members.remove', [$kelompok->id, $member->id]) }}"
                                            method="POST"
                                            class="inline"
                                            data-confirm="Keluarkan {{ $mhs->nama }} dari kelompok {{ $kelompok->nama_kelompok }}?"
                                            onsubmit="return confirm(this.dataset.confirm);"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="btn-smooth px-2.5 py-1.5 bg-white hover:bg-red-50 text-[#CA2C2A] border border-black text-[10px] font-black uppercase cursor-pointer"
                                            >
                                                Keluarkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Tambah Anggota Baru -->
            <div class="snap-in-3 bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
                <div class="px-4 sm:px-5 py-3 border-b-2 border-black bg-green-50/80 flex items-center justify-between gap-2">
                    <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#15803d]">
                        Tambah Anggota Baru
                    </h4>
                    <span class="bg-white border border-black px-2 py-0.5 font-mono font-black text-[10px] text-[#15803d]">
                        Tersedia: {{ $kandidatMahasiswa->count() }} Kandidat
                    </span>
                </div>

                <div class="p-4 sm:p-5 space-y-3.5">
                    @if ($kandidatMahasiswa->isEmpty())
                        <div class="p-4 bg-amber-50 border-2 border-black text-xs font-bold text-amber-950">
                            Semua mahasiswa pendaftar sudah tergabung di kelompok lain.
                        </div>
                    @else
                        <form action="{{ route('admin.kelompok.members.add', $kelompok->id) }}" method="POST" class="space-y-3.5">
                            @csrf

                            <div class="flex flex-col sm:flex-row gap-2">
                                <input
                                    type="text"
                                    id="edit-kandidat-search"
                                    oninput="filterEditCandidates()"
                                    placeholder="Cari nama, NIM, atau kelas mahasiswa..."
                                    class="flex-1 px-3 py-2 bg-[#F8FBFE] border-2 border-[#1A467C] text-xs font-bold text-gray-950 focus:bg-white focus:outline-none"
                                >
                                <select
                                    id="edit-kandidat-kelas-filter"
                                    onchange="filterEditCandidates()"
                                    class="w-full sm:w-40 px-3 py-2 bg-[#F8FBFE] border-2 border-[#1A467C] text-xs font-bold text-gray-950 cursor-pointer"
                                >
                                    <option value="">Semua Kelas</option>
                                    @foreach ($kelasOptions as $kls)
                                        <option value="{{ strtoupper($kls) }}">{{ $kls }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="edit-candidate-list" class="max-h-72 overflow-y-auto border-2 border-black divide-y divide-gray-200 bg-white">
                                @foreach ($kandidatMahasiswa as $kandidat)
                                    <label
                                        class="edit-candidate-row px-3.5 py-2.5 flex items-center justify-between gap-2.5 hover:bg-green-50/40 cursor-pointer transition"
                                        data-search="{{ strtolower($kandidat->nama . ' ' . $kandidat->nim . ' ' . $kandidat->kelas) }}"
                                        data-kelas="{{ strtoupper($kandidat->kelas) }}"
                                    >
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <input
                                                type="checkbox"
                                                name="pendaftaran_ids[]"
                                                value="{{ $kandidat->id }}"
                                                onchange="updateEditSelectedCount()"
                                                class="edit-add-checkbox w-4 h-4 accent-[#15803d] shrink-0 cursor-pointer"
                                            >
                                            <div class="min-w-0">
                                                <p class="font-black text-xs text-gray-950 truncate">{{ $kandidat->nama }}</p>
                                                <p class="font-mono text-[10px] font-bold text-gray-600">
                                                    {{ $kandidat->nim }} • <span class="text-[#1A467C]">{{ $kandidat->kelas }}</span>
                                                </p>
                                            </div>
                                        </div>

                                        <span class="bg-gray-100 border border-black px-2 py-0.5 font-mono font-black text-[10px] text-gray-800 shrink-0">
                                            ★ {{ (int) $kandidat->total_point }} PTS
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                            <div id="edit-candidate-empty" class="hidden p-4 border-2 border-black bg-gray-50 text-center text-xs font-bold text-gray-500">
                                Tidak ada mahasiswa yang cocok dengan pencarian.
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                                <span id="edit-selected-count" class="text-xs font-mono font-black text-gray-700">
                                    Dipilih: 0 Mahasiswa
                                </span>
                                <button
                                    type="submit"
                                    class="btn-smooth px-5 py-2.5 bg-[#15803d] hover:bg-[#166534] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                                >
                                    Tambahkan Anggota Terpilih
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>

<script>
    function previewEditKelompokFoto(input) {
        const preview = document.getElementById('edit-kelompok-foto-preview');
        const placeholder = document.getElementById('edit-kelompok-foto-placeholder');
        const hapusCb = document.getElementById('edit-hapus-foto');

        if (input.files && input.files[0]) {
            if (hapusCb) hapusCb.checked = false;
            const reader = new FileReader();
            reader.onload = function (e) {
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function filterEditCandidates() {
        const keyword = (document.getElementById('edit-kandidat-search')?.value || '').trim().toLowerCase();
        const kelasFilter = (document.getElementById('edit-kandidat-kelas-filter')?.value || '').trim().toUpperCase();
        const rows = document.querySelectorAll('#edit-candidate-list .edit-candidate-row');
        const emptyBox = document.getElementById('edit-candidate-empty');
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

    function updateEditSelectedCount() {
        const checked = Array.from(document.querySelectorAll('.edit-add-checkbox:checked'));
        const countEl = document.getElementById('edit-selected-count');
        if (countEl) {
            countEl.textContent = `Dipilih: ${checked.length} Mahasiswa`;
        }
    }
</script>
@endsection
