                @if (!$myKelompok)
                    <!-- Header Overview (Belum Punya Kelompok) -->
                    <div class="mhs-snap-1 card-tactile bg-white border-2 border-black p-4 sm:p-5 shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="bg-[#15803d] text-white border border-black px-2 py-0.5 text-[10px] font-black uppercase shadow-[1px_1px_0px_0px_#000000]">
                                KELOMPOK
                            </span>
                            <h3 class="text-sm sm:text-base font-black uppercase text-[#1A467C]">
                                Overview Kelompok
                            </h3>
                        </div>

                        @if ($kelompokInbox->count() > 0)
                            <span class="bg-amber-300 text-black border-2 border-black px-2.5 py-1 text-[10px] font-black uppercase shadow-[2px_2px_0px_0px_#000000]">
                                {{ $kelompokInbox->count() }} Undangan Masuk
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-800 border-2 border-black px-2.5 py-1 text-[10px] font-black uppercase">
                                Belum Bergabung
                            </span>
                        @endif
                    </div>

                    <!-- Inbox Undangan Kelompok -->
                    <div class="mhs-snap-2 card-tactile bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
                        <div class="px-4 sm:px-5 py-3 border-b-2 border-black {{ $kelompokInbox->count() > 0 ? 'bg-amber-50' : 'bg-blue-50/70' }} flex items-center justify-between">
                            <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                                Inbox Undangan Kelompok
                            </h4>
                            <span class="bg-white border border-black px-2 py-0.5 font-mono font-black text-[10px] text-[#1A467C]">
                                {{ $kelompokInbox->count() }} Masuk
                            </span>
                        </div>

                        @if ($kelompokInbox->isEmpty())
                            <div class="p-6 text-center text-xs font-bold text-gray-500">
                                Belum ada undangan kelompok di inbox kamu. Kamu bisa tunggu undangan dari Ketua tim, buat kelompok baru, atau gabung lewat kode/QR.
                            </div>
                        @else
                            <div class="divide-y-2 divide-black">
                                @foreach ($kelompokInbox as $inv)
                                    @if ($inv->kelompok)
                                        @php $invFotoUrl = $inv->kelompok->fotoUrl(); @endphp
                                        <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white hover:bg-amber-50/40 transition">
                                            <div class="flex items-start gap-3 min-w-0 flex-1">
                                                <div class="w-11 h-11 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                                                    @if ($invFotoUrl)
                                                        <img src="{{ $invFotoUrl }}" alt="Foto {{ $inv->kelompok->nama_kelompok }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ $inv->kelompok->initials() }}
                                                    @endif
                                                </div>

                                                <div class="min-w-0 flex-1 space-y-1">
                                                    <div class="flex flex-wrap items-center gap-1.5">
                                                        <span class="font-black text-sm text-gray-950 uppercase">
                                                            {{ $inv->kelompok->nama_kelompok }}
                                                        </span>
                                                        <span class="bg-blue-50 border border-black text-[#1A467C] px-1.5 py-0.2 text-[10px] font-mono font-black">
                                                            {{ $inv->kelompok->members->count() }} Anggota
                                                        </span>
                                                    </div>
                                                    <p class="text-xs font-semibold text-gray-700">
                                                        Ketua: <strong class="text-gray-950">{{ $inv->kelompok->ketua->nama ?? '-' }}</strong>
                                                        @if (!empty($inv->kelompok->ketua?->kelas))
                                                            <span class="font-mono text-[11px] text-[#1A467C]">({{ $inv->kelompok->ketua->kelas }})</span>
                                                        @endif
                                                    </p>
                                                    @if (!empty($inv->kelompok->judul_proposal))
                                                        <p class="text-[11px] font-medium text-gray-600 truncate">
                                                            Ide Proposal: {{ $inv->kelompok->judul_proposal }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2 shrink-0">
                                                <form action="{{ route('mahasiswa.kelompok.respondInvite', $inv->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="accept">
                                                    <button
                                                        type="submit"
                                                        class="btn-tactile px-3.5 py-2 bg-[#15803d] hover:bg-[#166534] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                                                    >
                                                        Terima
                                                    </button>
                                                </form>
                                                <form action="{{ route('mahasiswa.kelompok.respondInvite', $inv->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="decline">
                                                    <button
                                                        type="submit"
                                                        class="btn-tactile px-3.5 py-2 bg-white hover:bg-red-50 text-[#CA2C2A] border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                                                    >
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Buat Kelompok Sendiri & Gabung via Kode / Link / QR -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Form Buat Kelompok Sendiri (Auto Ketua) -->
                        <div class="mhs-snap-3 card-tactile bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
                            <div class="px-4 sm:px-5 py-3 border-b-2 border-black bg-blue-50/70 flex items-center justify-between">
                                <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                                    Buat Kelompok Sendiri
                                </h4>
                                <span class="bg-amber-300 border border-black px-2 py-0.5 text-[10px] font-black uppercase">
                                    Jadi Ketua
                                </span>
                            </div>

                            <form action="{{ route('mahasiswa.kelompok.store') }}" method="POST" class="p-4 sm:p-5 space-y-3.5">
                                @csrf
                                <div>
                                    <label for="create-nama-kelompok" class="block text-[11px] font-black uppercase tracking-wider text-gray-800 mb-1">
                                        Nama Kelompok <span class="text-[#CA2C2A]">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="create-nama-kelompok"
                                        name="nama_kelompok"
                                        value="{{ old('nama_kelompok') }}"
                                        required
                                        maxlength="80"
                                        placeholder="Contoh: Tim Garuda IF"
                                        class="w-full px-3 py-2 bg-white border-2 border-black text-xs sm:text-sm font-bold text-gray-950 focus:outline-none focus:bg-blue-50/40"
                                    >
                                </div>

                                <div>
                                    <label for="create-judul-proposal" class="block text-[11px] font-black uppercase tracking-wider text-gray-800 mb-1">
                                        Ide Judul Proposal <span class="text-gray-400 font-semibold">(Opsional)</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="create-judul-proposal"
                                        name="judul_proposal"
                                        value="{{ old('judul_proposal') }}"
                                        maxlength="200"
                                        placeholder="Bisa diisi atau diubah nanti"
                                        class="w-full px-3 py-2 bg-white border-2 border-black text-xs sm:text-sm font-semibold text-gray-900 focus:outline-none focus:bg-blue-50/40"
                                    >
                                </div>

                                <button
                                    type="submit"
                                    class="btn-tactile w-full py-2.5 bg-[#1A467C] hover:bg-[#123259] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] cursor-pointer"
                                >
                                    Buat Kelompok &amp; Jadi Ketua
                                </button>
                            </form>
                        </div>

                        <!-- Form Gabung via Kode, Link, atau Scan QR -->
                        <div class="mhs-snap-4 card-tactile bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
                            <div class="px-4 sm:px-5 py-3 border-b-2 border-black bg-green-50/80 flex items-center justify-between">
                                <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#15803d]">
                                    Gabung via Link / Kode QR
                                </h4>
                                <span class="bg-white border border-black px-2 py-0.5 text-[10px] font-black uppercase text-[#15803d]">
                                    Anggota
                                </span>
                            </div>

                            <div class="p-4 sm:p-5 space-y-4">
                                <form id="kelompok-join-form" action="{{ route('mahasiswa.kelompok.joinCode') }}" method="POST" class="space-y-2.5">
                                    @csrf
                                    <label for="kelompok-join-input" class="block text-[11px] font-black uppercase tracking-wider text-gray-800">
                                        Kode atau Link Undangan
                                    </label>
                                    <div class="flex gap-2">
                                        <input
                                            type="text"
                                            id="kelompok-join-input"
                                            name="kode_invite"
                                            value="{{ old('kode_invite') }}"
                                            required
                                            placeholder="PKJ-XXXXXX atau tempel link..."
                                            class="flex-1 min-w-0 px-3 py-2 bg-white border-2 border-black text-xs sm:text-sm font-mono font-bold text-gray-950 focus:outline-none focus:bg-green-50/40"
                                        >
                                        <button
                                            type="submit"
                                            class="btn-tactile px-4 py-2 bg-[#15803d] hover:bg-[#166534] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] shrink-0 cursor-pointer"
                                        >
                                            Gabung
                                        </button>
                                    </div>
                                </form>

                                <div class="border-t-2 border-dashed border-gray-300 pt-3.5 space-y-3">
                                    <button
                                        type="button"
                                        id="kelompok-camera-trigger"
                                        onclick="startKelompokQrScanner()"
                                        class="btn-tactile w-full py-2.5 bg-amber-300 hover:bg-amber-400 text-black border-2 border-black text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] flex items-center justify-center gap-2 cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="square" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="square" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Scan Kode QR Ketua</span>
                                    </button>

                                    <div id="kelompok-camera-box" class="hidden box-snap-in space-y-2.5">
                                        <div id="kelompok-qr-reader" class="w-full max-w-xs mx-auto border-2 border-black bg-black overflow-hidden"></div>
                                        <button
                                            type="button"
                                            onclick="stopKelompokQrScanner()"
                                            class="btn-tactile w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 border-2 border-black text-xs font-black uppercase tracking-wider cursor-pointer"
                                        >
                                            Tutup Kamera
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @php
                        $joinUrl = route('mahasiswa.kelompok.joinLink', ['kode' => $myKelompok->kode_invite]);
                        $kelompokFotoUrl = $myKelompok->fotoUrl();
                        $kelompokInitials = $myKelompok->initials();
                        $showEditFormOnLoad = isset($errors) && ($errors->has('nama_kelompok') || $errors->has('judul_proposal') || $errors->has('foto'));
                    @endphp

                    <!-- Header Ringkasan Kelompok Saya -->
                    <div class="mhs-snap-1 card-tactile bg-white border-2 border-black p-4 sm:p-6 shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] space-y-4">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="flex items-start gap-3 sm:gap-4 min-w-0 flex-1">
                                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center font-black text-base sm:text-lg shadow-[3px_3px_0px_0px_#000000] shrink-0 overflow-hidden">
                                    @if ($kelompokFotoUrl)
                                        <img src="{{ $kelompokFotoUrl }}" alt="Foto {{ $myKelompok->nama_kelompok }}" class="w-full h-full object-cover">
                                    @else
                                        {{ $kelompokInitials }}
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1 space-y-1">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="bg-[#1A467C] text-white border border-black px-2 py-0.5 text-[10px] font-black uppercase">
                                            KELOMPOK SAYA
                                        </span>
                                        <span class="{{ $isKetua ? 'bg-amber-300 text-black' : 'bg-sky-200 text-black' }} border border-black px-2 py-0.5 text-[10px] font-black uppercase">
                                            {{ $isKetua ? 'Ketua Kelompok' : 'Anggota' }}
                                        </span>
                                        <span class="bg-gray-100 text-gray-900 border border-black px-2 py-0.5 text-[10px] font-mono font-black">
                                            {{ $myKelompok->kode_invite }}
                                        </span>
                                    </div>

                                    <h3 class="text-base sm:text-xl font-black uppercase text-gray-950 tracking-tight break-words">
                                        {{ $myKelompok->nama_kelompok }}
                                    </h3>

                                    @if (!empty($myKelompok->judul_proposal))
                                        <p class="text-xs font-semibold text-gray-700">
                                            Judul Proposal: <span class="text-gray-950 font-bold">{{ $myKelompok->judul_proposal }}</span>
                                        </p>
                                    @elseif ($isKetua)
                                        <p class="text-xs font-medium text-gray-500">
                                            Belum ada judul proposal. Klik Edit Info untuk menambahkan.
                                        </p>
                                    @endif
                                </div>
                            </div>

                            @if ($isKetua)
                                <button
                                    type="button"
                                    onclick="toggleEditKelompok()"
                                    class="btn-tactile px-3 py-1.5 bg-white hover:bg-gray-100 text-gray-950 border-2 border-black text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000000] shrink-0 cursor-pointer"
                                >
                                    Edit Info
                                </button>
                            @endif
                        </div>

                        @if ($isKetua)
                            <div id="kelompok-edit-box" class="{{ $showEditFormOnLoad ? '' : 'hidden' }} box-snap-in border-t-2 border-black pt-4">
                                <form action="{{ route('mahasiswa.kelompok.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-gray-50 border-2 border-black p-3.5 sm:p-4">
                                    @csrf
                                    @method('PUT')

                                    <!-- Upload Foto Profil Kelompok -->
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 pb-3 border-b-2 border-dashed border-gray-300">
                                        <div class="w-16 h-16 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center font-black text-base shadow-[2px_2px_0px_0px_#000000] shrink-0 overflow-hidden">
                                            <img
                                                id="kelompok-foto-preview"
                                                src="{{ $kelompokFotoUrl ?? '' }}"
                                                alt="Preview Foto Kelompok"
                                                class="{{ $kelompokFotoUrl ? '' : 'hidden ' }}w-full h-full object-cover"
                                            >
                                            <span id="kelompok-foto-placeholder" class="{{ $kelompokFotoUrl ? 'hidden' : '' }}">
                                                {{ $kelompokInitials }}
                                            </span>
                                        </div>

                                        <div class="min-w-0 flex-1 space-y-1.5">
                                            <label for="edit-foto-kelompok" class="block text-[10px] font-black uppercase tracking-wider text-gray-700">
                                                Foto Profil Kelompok <span class="text-gray-400 font-semibold">(Opsional)</span>
                                            </label>
                                            <input
                                                type="file"
                                                id="edit-foto-kelompok"
                                                name="foto"
                                                accept="image/jpeg,image/png,image/webp"
                                                onchange="previewKelompokFoto(this)"
                                                class="block w-full text-xs font-bold text-gray-800 file:mr-2.5 file:py-1.5 file:px-3 file:border-2 file:border-black file:bg-amber-300 file:hover:bg-amber-400 file:text-black file:text-[10px] file:font-black file:uppercase file:cursor-pointer cursor-pointer"
                                            >
                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                <span class="text-[10px] font-semibold text-gray-500">
                                                    JPG, PNG, WEBP • Maks. 2 MB (Min. 50x50 px)
                                                </span>
                                                @if ($kelompokFotoUrl)
                                                    <label class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase text-[#CA2C2A] cursor-pointer">
                                                        <input type="checkbox" id="edit-hapus-foto" name="hapus_foto" value="1" class="w-3.5 h-3.5 border border-black accent-[#CA2C2A]">
                                                        Hapus foto saat ini
                                                    </label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label for="edit-nama-kelompok" class="block text-[10px] font-black uppercase tracking-wider text-gray-700 mb-1">
                                                Nama Kelompok
                                            </label>
                                            <input
                                                type="text"
                                                id="edit-nama-kelompok"
                                                name="nama_kelompok"
                                                value="{{ old('nama_kelompok', $myKelompok->nama_kelompok) }}"
                                                required
                                                maxlength="80"
                                                class="w-full px-3 py-2 bg-white border-2 border-black text-xs font-bold text-gray-950"
                                            >
                                        </div>
                                        <div>
                                            <label for="edit-judul-proposal" class="block text-[10px] font-black uppercase tracking-wider text-gray-700 mb-1">
                                                Ide Judul Proposal
                                            </label>
                                            <input
                                                type="text"
                                                id="edit-judul-proposal"
                                                name="judul_proposal"
                                                value="{{ old('judul_proposal', $myKelompok->judul_proposal) }}"
                                                maxlength="200"
                                                placeholder="Opsional"
                                                class="w-full px-3 py-2 bg-white border-2 border-black text-xs font-semibold text-gray-900"
                                            >
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            type="button"
                                            onclick="toggleEditKelompok()"
                                            class="btn-tactile px-3 py-1.5 bg-white border-2 border-black text-xs font-black uppercase cursor-pointer"
                                        >
                                            Batal
                                        </button>
                                        <button
                                            type="submit"
                                            class="btn-tactile px-4 py-1.5 bg-[#1A467C] text-white border-2 border-black text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                                        >
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <!-- Metrik Ringkas Kelompok -->
                        <div class="grid grid-cols-3 gap-2.5 sm:gap-4 border-t-2 border-black pt-4">
                            <div class="bg-blue-50 border-2 border-black p-3 text-center transition hover:-translate-y-0.5">
                                <span class="block text-[10px] font-black uppercase text-[#1A467C]">Total Anggota</span>
                                <span class="block font-mono text-base sm:text-xl font-black text-gray-950 mt-0.5">{{ $kelompokStats['jumlah_anggota'] }}</span>
                            </div>
                            <div class="bg-green-50 border-2 border-black p-3 text-center transition hover:-translate-y-0.5">
                                <span class="block text-[10px] font-black uppercase text-[#15803d]">Total Point Tim</span>
                                <span class="block font-mono text-base sm:text-xl font-black text-[#15803d] mt-0.5">{{ $kelompokStats['total_point'] }} PTS</span>
                            </div>
                            <div class="bg-amber-50 border-2 border-black p-3 text-center transition hover:-translate-y-0.5">
                                <span class="block text-[10px] font-black uppercase text-amber-800">Kehadiran Tim</span>
                                <span class="block font-mono text-base sm:text-xl font-black text-gray-950 mt-0.5">{{ $kelompokStats['persen_hadir'] }}%</span>
                            </div>
                        </div>
                    </div>

                    @if ($isKetua)
                        <!-- Panel Khusus Ketua: Share Link/QR & Undang ke Inbox Teman -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            <!-- Card Link & Kode QR Undangan -->
                            <div class="mhs-snap-2 card-tactile bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
                                <div class="px-4 sm:px-5 py-3 border-b-2 border-black bg-green-50/80 flex items-center justify-between">
                                    <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#15803d]">
                                        Undang via Link &amp; Kode QR
                                    </h4>
                                    <span class="bg-white border border-black px-2 py-0.5 font-mono font-black text-[10px] text-[#15803d]">
                                        {{ $myKelompok->kode_invite }}
                                    </span>
                                </div>

                                <div class="p-4 sm:p-5 space-y-4">
                                    <div class="flex flex-col items-center justify-center bg-gray-50 border-2 border-black p-4">
                                        <div id="kelompok-qr-canvas" class="bg-white p-2.5 border-2 border-black shadow-[3px_3px_0px_0px_#000000]"></div>
                                        <span class="mt-2 font-mono text-xs font-black tracking-widest text-gray-950">
                                            {{ $myKelompok->kode_invite }}
                                        </span>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black uppercase tracking-wider text-gray-700">
                                            Link Gabung Otomatis
                                        </label>
                                        <div class="flex gap-2">
                                            <input
                                                type="text"
                                                id="kelompok-share-link"
                                                value="{{ $joinUrl }}"
                                                readonly
                                                class="flex-1 min-w-0 px-2.5 py-2 bg-gray-50 border-2 border-black text-[11px] font-mono font-bold text-gray-800 select-all"
                                            >
                                            <button
                                                type="button"
                                                id="btn-copy-kelompok-link"
                                                onclick="copyKelompokText('kelompok-share-link', 'btn-copy-kelompok-link')"
                                                class="btn-tactile px-3.5 py-2 bg-[#1A467C] hover:bg-[#123259] text-white border-2 border-black text-xs font-black uppercase shadow-[2px_2px_0px_0px_#000000] shrink-0 cursor-pointer"
                                            >
                                                Salin Link
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Undang Langsung ke Inbox Teman -->
                            <div class="mhs-snap-3 card-tactile bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] overflow-hidden flex flex-col">
                                <div class="px-4 sm:px-5 py-3 border-b-2 border-black bg-amber-50/80 flex items-center justify-between">
                                    <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                                        Undang ke Inbox Teman
                                    </h4>
                                    <span class="bg-white border border-black px-2 py-0.5 font-mono font-black text-[10px] text-[#1A467C]">
                                        {{ $kandidatAnggotaList->where('already_invited', false)->count() }} Tersedia
                                    </span>
                                </div>

                                <div class="p-4 sm:p-5 space-y-4 flex-1">
                                    <div>
                                        <input
                                            type="text"
                                            id="invite-search-input"
                                            oninput="filterKelompokKandidat()"
                                            placeholder="Cari nama, NIM, atau kelas teman..."
                                            class="w-full px-3 py-2 bg-white border-2 border-black text-xs font-bold text-gray-950 focus:outline-none focus:bg-amber-50/40"
                                        >
                                    </div>

                                    <div id="invite-candidate-list" class="max-h-56 overflow-y-auto divide-y divide-gray-200 border-2 border-black bg-white"></div>

                                    @if ($myKelompok->invitations->isNotEmpty())
                                        <div class="border-t-2 border-black pt-3.5 space-y-2">
                                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-700">
                                                Menunggu Konfirmasi ({{ $myKelompok->invitations->count() }})
                                            </span>
                                            <div class="divide-y divide-gray-200 border-2 border-black bg-gray-50 max-h-36 overflow-y-auto">
                                                @foreach ($myKelompok->invitations as $sentInv)
                                                    @if ($sentInv->pendaftaran)
                                                        <div class="px-3 py-2 flex items-center justify-between gap-2 text-xs">
                                                            <div class="min-w-0">
                                                                <p class="font-bold text-gray-950 truncate">{{ $sentInv->pendaftaran->nama }}</p>
                                                                <p class="font-mono text-[10px] text-gray-600">{{ $sentInv->pendaftaran->nim }} • {{ $sentInv->pendaftaran->kelas }}</p>
                                                            </div>
                                                            <form action="{{ route('mahasiswa.kelompok.cancelInvite', $sentInv->id) }}" method="POST" class="shrink-0">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button
                                                                    type="submit"
                                                                    class="btn-tactile px-2.5 py-1 bg-white hover:bg-red-50 text-[#CA2C2A] border border-black text-[10px] font-black uppercase cursor-pointer"
                                                                >
                                                                    Batalkan
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Card Daftar Anggota Kelompok -->
                    <div class="mhs-snap-4 card-tactile bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
                        <div class="px-4 sm:px-5 py-3 border-b-2 border-black bg-blue-50/70 flex items-center justify-between">
                            <h4 class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#1A467C]">
                                Daftar Anggota Kelompok
                            </h4>
                            <span class="bg-white border border-black px-2 py-0.5 font-mono font-black text-[10px] text-[#1A467C]">
                                {{ $kelompokStats['jumlah_anggota'] }} Orang
                            </span>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach ($myKelompok->members as $member)
                                @php
                                    $mhs = $member->pendaftaran;
                                    $isSelf = (int) $member->pendaftaran_id === (int) $mahasiswa->id;
                                    $isMemberKetua = (int) $member->pendaftaran_id === (int) $myKelompok->ketua_id;
                                @endphp
                                @if ($mhs)
                                    <div class="px-4 sm:px-5 py-3.5 {{ $isSelf ? 'bg-amber-50/70' : 'bg-white hover:bg-blue-50/30' }} transition flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0 flex-1">
                                            <div class="w-10 h-10 bg-[#2A82C6] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden">
                                                @if ($mhs->avatarUrl())
                                                    <img src="{{ $mhs->avatarUrl() }}" alt="Foto {{ $mhs->nama }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ $mhs->initials() }}
                                                @endif
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-wrap items-center gap-1.5">
                                                    <p class="font-black text-xs sm:text-sm text-gray-950 truncate">{{ $mhs->nama }}</p>
                                                    <span class="{{ $isMemberKetua ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-800' }} border border-black text-[9px] font-black uppercase px-1.5 py-0.2 shrink-0">
                                                        {{ $isMemberKetua ? 'KETUA' : 'ANGGOTA' }}
                                                    </span>
                                                    @if ($isSelf)
                                                        <span class="bg-[#1A467C] text-white border border-black text-[9px] font-black uppercase px-1.5 py-0.2 shrink-0">
                                                            KAMU
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="font-mono text-[10px] sm:text-[11px] font-bold text-gray-600 mt-0.5">
                                                    {{ $mhs->nim }} • <span class="text-[#1A467C]">{{ $mhs->kelas }}</span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap items-center justify-between sm:justify-end gap-2 shrink-0">
                                            <span class="bg-green-100 border-2 border-black px-2.5 py-1 font-mono font-black text-xs text-[#15803d]">
                                                ★ {{ (int) ($mhs->total_point ?? 0) }} PTS
                                            </span>

                                            @if ($isKetua && !$isSelf)
                                                <form id="transfer-ketua-form-{{ $member->id }}" action="{{ route('mahasiswa.kelompok.transferKetua', $member->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button
                                                        type="button"
                                                        data-form="transfer-ketua-form-{{ $member->id }}"
                                                        data-title="Serahkan Jabatan Ketua?"
                                                        data-message="Jabatan Ketua kelompok akan dipindahkan ke {{ $mhs->nama }}."
                                                        data-confirm="Ya, Serahkan"
                                                        data-danger="0"
                                                        onclick="openKelompokConfirmFromEl(this)"
                                                        class="btn-tactile px-2.5 py-1.5 bg-amber-300 hover:bg-amber-400 text-black border-2 border-black text-[10px] font-black uppercase cursor-pointer"
                                                    >
                                                        Jadikan Ketua
                                                    </button>
                                                </form>

                                                <form id="remove-member-form-{{ $member->id }}" action="{{ route('mahasiswa.kelompok.removeMember', $member->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="button"
                                                        data-form="remove-member-form-{{ $member->id }}"
                                                        data-title="Keluarkan Anggota?"
                                                        data-message="{{ $mhs->nama }} akan dikeluarkan dari kelompok ini."
                                                        data-confirm="Ya, Keluarkan"
                                                        data-danger="1"
                                                        onclick="openKelompokConfirmFromEl(this)"
                                                        class="btn-tactile px-2.5 py-1.5 bg-white hover:bg-red-50 text-[#CA2C2A] border-2 border-black text-[10px] font-black uppercase cursor-pointer"
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

                        <div class="px-4 sm:px-5 py-3.5 bg-gray-50 border-t-2 border-black flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <p class="text-[11px] font-semibold text-gray-600">
                                @if ($isKetua)
                                    Serahkan jabatan ke anggota lain terlebih dahulu jika hanya ingin keluar tanpa membubarkan kelompok.
                                @else
                                    Keluar akan melepas keanggotaanmu dari kelompok ini.
                                @endif
                            </p>

                            <form id="leave-disband-form" action="{{ route('mahasiswa.kelompok.leave') }}" method="POST" class="shrink-0">
                                @csrf
                                <button
                                    type="button"
                                    data-form="leave-disband-form"
                                    data-title="{{ $isKetua ? 'Bubarkan Kelompok?' : 'Keluar dari Kelompok?' }}"
                                    data-message="{{ $isKetua ? 'Seluruh anggota akan otomatis dikeluarkan dan kode undangan kelompok tidak berlaku lagi.' : 'Kamu tidak akan lagi tergabung di kelompok ' . $myKelompok->nama_kelompok . '.' }}"
                                    data-confirm="{{ $isKetua ? 'Ya, Bubarkan' : 'Ya, Keluar' }}"
                                    data-danger="1"
                                    onclick="openKelompokConfirmFromEl(this)"
                                    class="btn-tactile px-4 py-2 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                                >
                                    {{ $isKetua ? 'Bubarkan Kelompok' : 'Keluar Kelompok' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

