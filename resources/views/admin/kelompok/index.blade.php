@extends('admin.layouts.app')

@section('title', 'Kelola Kelompok')
@section('header_title', 'Kelola Kelompok Mahasiswa')

@section('content')
@php
    $minAnggota = \App\Http\Controllers\MahasiswaKelompokController::MIN_ANGGOTA;
    $maxAnggota = \App\Http\Controllers\MahasiswaKelompokController::MAX_ANGGOTA;
@endphp
<div class="space-y-5 sm:space-y-6">

    <!-- Header Action Bar -->
    <div class="snap-in-1 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border-2 border-[#000000] p-4 sm:p-5 shadow-[5px_5px_0px_0px_#000000]">
        <div>
            <div class="flex flex-wrap items-center gap-2 mb-1">
                <span class="bg-amber-400 text-black border border-black text-[10px] font-black uppercase px-2 py-0.5 tracking-wider">
                    KHUSUS SUPER ADMIN
                </span>
                <span class="bg-[#1A467C] text-white border border-black text-[10px] font-mono font-black uppercase px-2 py-0.5">
                    STANDAR {{ $minAnggota }}-{{ $maxAnggota }} ORANG / TIM
                </span>
            </div>
            <h3 class="text-sm sm:text-base font-black uppercase tracking-tight text-[#1A467C]">
                Daftar &amp; Pembentukan Kelompok POKJA
            </h3>
            <p class="text-xs font-semibold text-gray-600 mt-0.5">
                Pembagian kelompok, penunjukan Ketua, dan penempatan anggota dikelola sepenuhnya oleh Super Admin.
            </p>
        </div>

        <a
            href="{{ route('admin.kelompok.create') }}"
            class="btn-smooth inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#2A82C6] hover:bg-[#1A467C] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_0px_#000000] shrink-0"
        >
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="square" stroke-width="2.8" d="M12 4v16m8-8H4" />
            </svg>
            <span>Buat Kelompok</span>
        </a>
    </div>

    <!-- Summary Metrics -->
    <div class="snap-in-2 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <div class="bg-white border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000]">
            <span class="block text-[10px] font-black uppercase tracking-wider text-[#1A467C]">Total Kelompok</span>
            <span class="block font-mono text-2xl font-black text-gray-950 mt-1">{{ $totalKelompok }}</span>
            <span class="block text-[11px] font-semibold text-gray-500 mt-0.5">Tim terbentuk saat ini</span>
        </div>

        <div class="bg-blue-50 border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000]">
            <span class="block text-[10px] font-black uppercase tracking-wider text-[#1A467C]">Sudah Berkelompok</span>
            <span class="block font-mono text-2xl font-black text-[#1A467C] mt-1">
                {{ $totalMahasiswaBerkelompok }} <span class="text-xs font-bold text-gray-600">/ {{ $totalPendaftar }}</span>
            </span>
            <span class="block text-[11px] font-semibold text-gray-600 mt-0.5">Mahasiswa sudah masuk tim</span>
        </div>

        <div class="{{ $belumPunyaKelompok > 0 ? 'bg-amber-50' : 'bg-green-50' }} border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000]">
            <span class="block text-[10px] font-black uppercase tracking-wider {{ $belumPunyaKelompok > 0 ? 'text-amber-900' : 'text-[#15803d]' }}">
                Belum Dapat Kelompok
            </span>
            <span class="block font-mono text-2xl font-black {{ $belumPunyaKelompok > 0 ? 'text-amber-950' : 'text-[#15803d]' }} mt-1">
                {{ $belumPunyaKelompok }}
            </span>
            <span class="block text-[11px] font-semibold text-gray-700 mt-0.5">
                {{ $belumPunyaKelompok > 0 ? 'Mahasiswa siap dialokasikan' : 'Semua pendaftar sudah punya tim' }}
            </span>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="snap-in-2 bg-white border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000]">
        <form action="{{ route('admin.kelompok.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama kelompok, kode PKJ, judul proposal, nama/NIM/kelas anggota..."
                    class="w-full px-3.5 py-2.5 bg-[#F8FBFE] border-2 border-[#1A467C] text-xs sm:text-sm font-bold text-gray-950 focus:bg-white focus:outline-none focus:shadow-[2px_2px_0px_0px_#1A467C]"
                >
            </div>

            <div class="w-full md:w-52">
                <select
                    name="status"
                    onchange="this.form.submit()"
                    class="w-full px-3 py-2.5 bg-[#F8FBFE] border-2 border-[#1A467C] text-xs sm:text-sm font-bold text-gray-950 focus:bg-white focus:outline-none cursor-pointer"
                >
                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Jumlah Anggota</option>
                    <option value="kurang" {{ $statusFilter === 'kurang' ? 'selected' : '' }}>Kurang (&lt; {{ $minAnggota }} Anggota)</option>
                    <option value="memenuhi" {{ $statusFilter === 'memenuhi' ? 'selected' : '' }}>Standar ({{ $minAnggota }}-{{ $maxAnggota }} Anggota)</option>
                    <option value="penuh" {{ $statusFilter === 'penuh' ? 'selected' : '' }}>{{ $maxAnggota }}+ Anggota</option>
                </select>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <button
                    type="submit"
                    class="btn-smooth px-4 py-2.5 bg-[#1A467C] hover:bg-[#0F2A4A] text-white border-2 border-black text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                >
                    Cari
                </button>
                @if ($search !== '' || $statusFilter !== 'all')
                    <a
                        href="{{ route('admin.kelompok.index') }}"
                        class="btn-smooth px-3.5 py-2.5 bg-white hover:bg-gray-100 text-gray-900 border-2 border-black text-xs font-black uppercase tracking-wider"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Mobile Cards (block lg:hidden) -->
    <div class="snap-in-3 block lg:hidden space-y-3">
        @forelse ($kelompoks as $kelompok)
            @php
                $fotoUrl = $kelompok->fotoUrl();
                $memberCount = $kelompok->members->count();
                $totalTeamPoint = $kelompok->members->sum(fn ($m) => (int) ($m->pendaftaran->total_point ?? 0));
            @endphp
            <div class="bg-white border-2 border-black p-4 shadow-[4px_4px_0px_0px_#000000] space-y-3">
                <div class="flex items-start justify-between gap-2.5">
                    <div class="flex items-start gap-2.5 min-w-0">
                        <div class="w-11 h-11 bg-[#1A467C] text-white border-2 border-black flex items-center justify-center font-black text-xs shrink-0 overflow-hidden shadow-[2px_2px_0px_0px_#000000]">
                            @if ($fotoUrl)
                                <img src="{{ $fotoUrl }}" alt="Foto {{ $kelompok->nama_kelompok }}" class="w-full h-full object-cover">
                            @else
                                {{ $kelompok->initials() }}
                            @endif
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="bg-gray-100 border border-black px-1.5 py-0.2 font-mono font-black text-[10px] text-gray-800">
                                    {{ $kelompok->kode_invite }}
                                </span>
                                @if ($memberCount > $maxAnggota)
                                    <span class="bg-blue-100 border border-black text-[#1A467C] px-1.5 py-0.2 text-[10px] font-black uppercase">
                                        {{ $memberCount }} Anggota
                                    </span>
                                @elseif ($memberCount === $maxAnggota)
                                    <span class="bg-blue-100 border border-black text-[#1A467C] px-1.5 py-0.2 text-[10px] font-black uppercase">
                                        Lengkap ({{ $memberCount }}/{{ $maxAnggota }})
                                    </span>
                                @elseif ($memberCount >= $minAnggota)
                                    <span class="bg-green-100 border border-black text-green-900 px-1.5 py-0.2 text-[10px] font-black uppercase">
                                        Siap ({{ $memberCount }}/{{ $maxAnggota }})
                                    </span>
                                @else
                                    <span class="bg-amber-100 border border-black text-amber-950 px-1.5 py-0.2 text-[10px] font-black uppercase">
                                        Kurang ({{ $memberCount }}/{{ $maxAnggota }})
                                    </span>
                                @endif
                            </div>

                            <h4 class="font-black text-sm text-gray-950 uppercase mt-1 break-words">
                                {{ $kelompok->nama_kelompok }}
                            </h4>

                            @if (!empty($kelompok->judul_proposal))
                                <p class="text-[11px] font-semibold text-gray-600 mt-0.5 break-words">
                                    {{ $kelompok->judul_proposal }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <span class="bg-green-100 border border-black px-2 py-0.5 font-mono font-black text-[10px] text-[#15803d] shrink-0">
                        ★ {{ $totalTeamPoint }} PTS
                    </span>
                </div>

                <div class="border-t border-gray-200 pt-2.5 space-y-1.5">
                    <span class="block text-[10px] font-black uppercase text-gray-600">Daftar Anggota ({{ $memberCount }})</span>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($kelompok->members as $member)
                            @if ($member->pendaftaran)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 border border-black text-[10px] font-bold {{ (int) $member->pendaftaran_id === (int) $kelompok->ketua_id ? 'bg-amber-200 text-black' : 'bg-gray-50 text-gray-800' }}">
                                    @if ((int) $member->pendaftaran_id === (int) $kelompok->ketua_id)
                                        <span class="font-black">[KETUA]</span>
                                    @endif
                                    <span>{{ $member->pendaftaran->nama }}</span>
                                    <span class="font-mono text-gray-600">({{ $member->pendaftaran->kelas }})</span>
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-200">
                    <a
                        href="{{ route('admin.kelompok.edit', $kelompok->id) }}"
                        class="btn-smooth px-3 py-1.5 bg-amber-400 hover:bg-amber-500 text-black border border-black text-[11px] font-black uppercase shadow-[2px_2px_0px_0px_#000000]"
                    >
                        Edit / Anggota
                    </a>

                    <form
                        action="{{ route('admin.kelompok.destroy', $kelompok->id) }}"
                        method="POST"
                        class="inline"
                        data-confirm="Bubarkan kelompok {{ $kelompok->nama_kelompok }}? Semua anggota akan dilepas dari kelompok ini."
                        onsubmit="return confirm(this.dataset.confirm);"
                    >
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="btn-smooth px-3 py-1.5 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border border-black text-[11px] font-black uppercase shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                        >
                            Bubarkan
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white border-2 border-black p-6 text-center text-gray-500 font-bold text-xs shadow-[4px_4px_0px_0px_#000000]">
                Belum ada kelompok yang cocok atau terbentuk. Klik tombol "Buat Kelompok" untuk memulai.
            </div>
        @endforelse

        @if ($kelompoks->hasPages())
            <div class="pt-2">
                {{ $kelompoks->links() }}
            </div>
        @endif
    </div>

    <!-- Desktop Table (hidden lg:block) -->
    <div class="snap-in-3 hidden lg:block bg-white border-2 border-[#1A467C] shadow-[6px_6px_0px_0px_#1A467C] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="border-b-2 border-[#1A467C] bg-[#1A467C] text-white uppercase font-black text-[11px] tracking-wider">
                        <th class="py-3.5 px-3.5 w-12 text-center">No</th>
                        <th class="py-3.5 px-3.5">Kelompok</th>
                        <th class="py-3.5 px-3.5">Ketua &amp; Anggota</th>
                        <th class="py-3.5 px-3.5 text-center w-32">Kuota</th>
                        <th class="py-3.5 px-3.5 text-center w-28">Point Tim</th>
                        <th class="py-3.5 px-3.5 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($kelompoks as $index => $kelompok)
                        @php
                            $fotoUrl = $kelompok->fotoUrl();
                            $memberCount = $kelompok->members->count();
                            $totalTeamPoint = $kelompok->members->sum(fn ($m) => (int) ($m->pendaftaran->total_point ?? 0));
                        @endphp
                        <tr class="hover:bg-blue-50/40 transition align-top">
                            <td class="py-3.5 px-3.5 text-center text-gray-700 font-mono font-bold">
                                {{ $kelompoks->firstItem() + $index }}
                            </td>

                            <td class="py-3.5 px-3.5">
                                <div class="flex items-start gap-2.5">
                                    <div class="w-10 h-10 bg-[#1A467C] text-white border border-black flex items-center justify-center font-black text-xs shadow-[2px_2px_0px_0px_#000000] shrink-0 overflow-hidden">
                                        @if ($fotoUrl)
                                            <img src="{{ $fotoUrl }}" alt="Foto {{ $kelompok->nama_kelompok }}" class="w-full h-full object-cover">
                                        @else
                                            {{ $kelompok->initials() }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-black text-sm text-gray-950 uppercase">{{ $kelompok->nama_kelompok }}</p>
                                        <span class="inline-block mt-0.5 bg-gray-100 border border-black px-1.5 py-0.2 font-mono font-black text-[10px] text-gray-800">
                                            {{ $kelompok->kode_invite }}
                                        </span>
                                        @if (!empty($kelompok->judul_proposal))
                                            <p class="text-[11px] font-semibold text-gray-600 mt-1 max-w-xs truncate" title="{{ $kelompok->judul_proposal }}">
                                                Proposal: {{ $kelompok->judul_proposal }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-3.5">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($kelompok->members as $member)
                                        @if ($member->pendaftaran)
                                            @php $isKetuaRow = (int) $member->pendaftaran_id === (int) $kelompok->ketua_id; @endphp
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 border border-black text-[11px] font-bold {{ $isKetuaRow ? 'bg-amber-300 text-black' : 'bg-white text-gray-800' }}">
                                                @if ($isKetuaRow)
                                                    <span class="text-[9px] font-black uppercase bg-black text-amber-300 px-1">KETUA</span>
                                                @endif
                                                <span>{{ $member->pendaftaran->nama }}</span>
                                                <span class="font-mono text-[10px] text-gray-600">{{ $member->pendaftaran->nim }} • {{ $member->pendaftaran->kelas }}</span>
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>

                            <td class="py-3.5 px-3.5 text-center">
                                @if ($memberCount > $maxAnggota)
                                    <span class="inline-block bg-blue-100 border border-black text-[#1A467C] text-[10px] px-2 py-0.5 font-black uppercase">
                                        {{ $memberCount }} Anggota
                                    </span>
                                @elseif ($memberCount === $maxAnggota)
                                    <span class="inline-block bg-blue-100 border border-black text-[#1A467C] text-[10px] px-2 py-0.5 font-black uppercase">
                                        Lengkap ({{ $memberCount }}/{{ $maxAnggota }})
                                    </span>
                                @elseif ($memberCount >= $minAnggota)
                                    <span class="inline-block bg-green-100 border border-black text-green-900 text-[10px] px-2 py-0.5 font-black uppercase">
                                        Memenuhi ({{ $memberCount }}/{{ $maxAnggota }})
                                    </span>
                                @else
                                    <span class="inline-block bg-amber-100 border border-black text-amber-950 text-[10px] px-2 py-0.5 font-black uppercase">
                                        Kurang ({{ $memberCount }}/{{ $maxAnggota }})
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-3.5 text-center">
                                <span class="inline-block bg-green-50 border border-black px-2 py-0.5 font-mono font-black text-xs text-[#15803d]">
                                    ★ {{ $totalTeamPoint }}
                                </span>
                            </td>

                            <td class="py-3.5 px-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a
                                        href="{{ route('admin.kelompok.edit', $kelompok->id) }}"
                                        class="btn-smooth w-8 h-8 bg-amber-400 hover:bg-amber-500 text-black border border-black flex items-center justify-center shadow-[1px_1px_0px_0px_#000000]"
                                        title="Edit &amp; Kelola Anggota {{ $kelompok->nama_kelompok }}"
                                    >
                                        <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="square" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <form
                                        action="{{ route('admin.kelompok.destroy', $kelompok->id) }}"
                                        method="POST"
                                        class="inline"
                                        data-confirm="Bubarkan kelompok {{ $kelompok->nama_kelompok }}? Semua anggota akan dilepas dari kelompok ini."
                                        onsubmit="return confirm(this.dataset.confirm);"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn-smooth w-8 h-8 bg-[#CA2C2A] hover:bg-[#901C1A] text-white border border-[#901C1A] flex items-center justify-center shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
                                            title="Bubarkan Kelompok {{ $kelompok->nama_kelompok }}"
                                        >
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="square" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-gray-500 font-semibold text-xs sm:text-sm">
                                Belum ada kelompok yang terbentuk. Klik tombol "Buat Kelompok" di kanan atas untuk membuat kelompok pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kelompoks->hasPages())
            <div class="px-5 py-3.5 border-t-2 border-[#1A467C] bg-white">
                {{ $kelompoks->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
