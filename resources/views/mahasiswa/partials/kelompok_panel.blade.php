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

                        <span class="bg-amber-300 text-black border-2 border-black px-2.5 py-1 text-[10px] font-black uppercase shadow-[2px_2px_0px_0px_#000000]">
                            Menunggu Pembagian
                        </span>
                    </div>

                    <!-- Informasi Pembagian Kelompok oleh Super Admin -->
                    <div class="mhs-snap-2 card-tactile bg-white border-2 border-black p-6 sm:p-8 shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] text-center space-y-3">
                        <div class="w-12 h-12 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center mx-auto shadow-[3px_3px_0px_0px_#000000]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>

                        <span class="inline-block bg-amber-300 text-black border border-black px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider">
                            Dikelola Super Admin
                        </span>

                        <h4 class="text-sm sm:text-base font-black uppercase text-gray-950">
                            Kamu Belum Dimasukkan ke Kelompok
                        </h4>

                        <p class="max-w-lg mx-auto text-xs sm:text-sm font-semibold text-gray-600 leading-relaxed">
                            Pembentukan kelompok, penetapan Ketua, dan pembagian anggota diatur langsung oleh Super Admin / Panitia POKJA. Data tim kamu akan muncul otomatis di halaman ini setelah dibagikan.
                        </p>
                    </div>
                @else
                    @php
                        $kelompokFotoUrl = $myKelompok->fotoUrl();
                        $kelompokInitials = $myKelompok->initials();
                        $showEditFormOnLoad = isset($errors) && ($errors->has('judul_proposal') || $errors->has('foto'));
                        $jumlahAnggotaTim = $myKelompok->members->count();
                        $minAnggotaTim = \App\Http\Controllers\MahasiswaKelompokController::MIN_ANGGOTA;
                        $maxAnggotaTim = \App\Http\Controllers\MahasiswaKelompokController::MAX_ANGGOTA;
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
                                        @if ($jumlahAnggotaTim >= $maxAnggotaTim)
                                            <span class="bg-blue-100 text-[#1A467C] border border-black px-2 py-0.5 text-[10px] font-black uppercase">
                                                Penuh ({{ $jumlahAnggotaTim }}/{{ $maxAnggotaTim }} Anggota)
                                            </span>
                                        @elseif ($jumlahAnggotaTim >= $minAnggotaTim)
                                            <span class="bg-green-100 text-green-900 border border-black px-2 py-0.5 text-[10px] font-black uppercase">
                                                Memenuhi Syarat ({{ $jumlahAnggotaTim }}/{{ $maxAnggotaTim }} Anggota)
                                            </span>
                                        @else
                                            <span class="bg-amber-100 text-amber-950 border border-black px-2 py-0.5 text-[10px] font-black uppercase">
                                                Minimal {{ $minAnggotaTim }} Anggota ({{ $jumlahAnggotaTim }}/{{ $maxAnggotaTim }})
                                            </span>
                                        @endif
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
                                            Belum ada judul proposal. Klik Edit Proposal / Foto untuk menambahkan.
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
                                    Edit Proposal / Foto
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
                                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-700 mb-1">
                                                Nama Kelompok (Ditetapkan Super Admin)
                                            </span>
                                            <div class="w-full px-3 py-2 bg-gray-100 border-2 border-black text-xs font-black text-gray-700 uppercase">
                                                {{ $myKelompok->nama_kelompok }}
                                            </div>
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

                    <!-- Card Daftar Anggota Kelompok -->
                    <div class="mhs-snap-2 card-tactile bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000000] sm:shadow-[5px_5px_0px_0px_#000000] overflow-hidden">
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
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="px-4 sm:px-5 py-3 bg-gray-50 border-t-2 border-black">
                            <p class="text-[11px] font-semibold text-gray-600">
                                Susunan anggota, nama kelompok, dan penetapan Ketua dikelola oleh Super Admin POKJA. Hubungi panitia jika terdapat kendala tim.
                            </p>
                        </div>
                    </div>
                @endif
