@extends('admin.layouts.app')

@section('title', 'Data Pendaftar')
@section('header_title', 'Data Pendaftar POKJA')

@section('content')
<div class="space-y-6">

    <!-- 4 Stat Cards (Neubrutalism) -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Card 1: Total Pendaftar -->
        <div class="bg-white border-2 border-[#1A467C] p-4 sm:p-5 shadow-[4px_4px_0px_0px_#1A467C]">
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#1A467C]">Total Pendaftar</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-black text-gray-950" id="stat-total-pendaftar">{{ number_format($totalPendaftar) }}</span>
                <span class="text-[10px] font-bold text-[#1A467C] bg-blue-50 border border-[#2A82C6] px-2 py-0.5 uppercase">Mahasiswa</span>
            </div>
        </div>

        <!-- Card 2: Total Hadir -->
        <div class="bg-white border-2 border-green-800 p-4 sm:p-5 shadow-[4px_4px_0px_0px_#166534]">
            <p class="text-[11px] font-bold uppercase tracking-wider text-green-800">Mahasiswa Hadir</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-black text-green-800" id="stat-total-hadir">{{ number_format($totalHadir) }}</span>
                <span class="text-[10px] font-bold text-green-900 bg-green-50 border border-green-700 px-2 py-0.5 uppercase">Hadir</span>
            </div>
        </div>

        <!-- Card 3: Belum Hadir -->
        <div class="bg-white border-2 border-[#901C1A] p-4 sm:p-5 shadow-[4px_4px_0px_0px_#901C1A]">
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#901C1A]">Belum Hadir</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-black text-[#901C1A]" id="stat-total-belum-hadir">{{ number_format($totalBelumHadir) }}</span>
                <span class="text-[10px] font-bold text-[#901C1A] bg-red-50 border border-[#CA2C2A] px-2 py-0.5 uppercase">Belum</span>
            </div>
        </div>

        <!-- Card 4: Pendaftar Hari Ini -->
        <div class="bg-white border-2 border-[#1A467C] p-4 sm:p-5 shadow-[4px_4px_0px_0px_#1A467C]">
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#1A467C]">Pendaftar Hari Ini</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-black text-gray-950">{{ number_format($pendaftarHariIni) }}</span>
                <span class="text-[10px] font-bold text-[#2A82C6] bg-blue-50 border border-[#2A82C6] px-2 py-0.5 uppercase">Hari Ini</span>
            </div>
        </div>
    </div>

    <!-- Action Bar: Search, Custom Styled Dropdown Filter, Export Excel (Neubrutalism) -->
    <div class="bg-white border-2 border-[#1A467C] p-4 sm:p-5 shadow-[6px_6px_0px_0px_#1A467C]">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3.5">
            
            <!-- Search & Filter Fields Grid -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1 flex-wrap">
                <!-- Search Input -->
                <div class="flex-1 min-w-[200px]">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}"
                        placeholder="Cari Nama, NIM, No. Telp..."
                        class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] text-gray-900 text-xs sm:text-sm font-semibold px-3.5 py-2.5 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition"
                    >
                </div>

                <!-- Custom Styled Filter: Kelas Dropdown -->
                <div class="w-full sm:w-48 relative">
                    <select 
                        name="kelas" 
                        class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] text-gray-950 text-xs sm:text-sm font-bold font-mono px-3.5 py-2.5 pr-9 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition cursor-pointer appearance-none"
                    >
                        <option value="">-- SEMUA KELAS --</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k }}" {{ $kelasFilter == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                    <!-- Custom Chevron Arrow -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-[#1A467C]">
                        <svg class="w-4 h-4 border-l-2 border-[#1A467C] pl-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Custom Styled Filter: Status Kehadiran Dropdown -->
                <div class="w-full sm:w-56 relative">
                    <select 
                        name="status_kehadiran" 
                        class="w-full bg-[#F8FBFE] border-2 border-[#1A467C] text-gray-950 text-xs sm:text-sm font-bold px-3.5 py-2.5 pr-9 focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_0px_#1A467C] transition cursor-pointer appearance-none"
                    >
                        <option value="">-- SEMUA KEHADIRAN --</option>
                        <option value="hadir" {{ $statusFilter == 'hadir' ? 'selected' : '' }}>HADIR</option>
                        <option value="belum_hadir" {{ $statusFilter == 'belum_hadir' ? 'selected' : '' }}>BELUM HADIR</option>
                        <option value="tidak_hadir" {{ $statusFilter == 'tidak_hadir' ? 'selected' : '' }}>TIDAK HADIR</option>
                    </select>
                    <!-- Custom Chevron Arrow -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-[#1A467C]">
                        <svg class="w-4 h-4 border-l-2 border-[#1A467C] pl-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="square" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Tombol Filter & Reset -->
                <div class="flex items-center gap-2">
                    <button 
                        type="submit" 
                        class="btn-smooth flex-1 sm:flex-none px-4 py-2.5 bg-[#1A467C] hover:bg-[#0F2A4A] text-white text-xs font-black uppercase tracking-wider border-2 border-[#000000] shadow-[2px_2px_0px_0px_#000000] cursor-pointer"
                    >
                        Filter
                    </button>
                    @if ($search || $kelasFilter || $statusFilter)
                        <a 
                            href="{{ route('admin.dashboard') }}" 
                            class="btn-smooth px-3.5 py-2.5 border-2 border-gray-400 bg-white text-gray-700 hover:bg-gray-100 text-xs font-bold uppercase tracking-wider shadow-[2px_2px_0px_0px_#9ca3af] text-center"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </div>

            <!-- Export Excel Button -->
            <div>
                <a 
                    href="{{ route('admin.pendaftar.export', ['search' => $search, 'kelas' => $kelasFilter, 'status_kehadiran' => $statusFilter]) }}" 
                    class="btn-smooth w-full lg:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-green-700 hover:bg-green-800 text-white text-xs font-black uppercase tracking-wider border-2 border-green-950 shadow-[3px_3px_0px_0px_#14532d] cursor-pointer"
                    title="Export data ke file Excel berformat styling rapi"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Export Excel (.xls)</span>
                </a>
            </div>

        </form>
    </div>

    <!-- Tabel Data Pendaftar (Neubrutalism) -->
    <div class="bg-white border-2 border-[#1A467C] shadow-[6px_6px_0px_0px_#1A467C] overflow-hidden">
        <div class="px-5 py-3.5 border-b-2 border-[#1A467C] bg-blue-50/60 flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-wider text-[#1A467C]">Daftar Mahasiswa Pendaftar</h3>
            <span class="text-xs font-bold text-[#1A467C] bg-white border border-[#1A467C] px-2.5 py-0.5">
                Total: {{ $pendaftarans->total() }} Mahasiswa
            </span>
        </div>

        <!-- Responsive Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="border-b-2 border-[#1A467C] bg-[#1A467C] text-white uppercase font-black text-[11px] tracking-wider">
                        <th class="py-3.5 px-3.5 w-12 text-center">No</th>
                        <th class="py-3.5 px-3.5">Nama Lengkap</th>
                        <th class="py-3.5 px-3.5">NIM</th>
                        <th class="py-3.5 px-3.5">Kelas</th>
                        <th class="py-3.5 px-3.5">No. Telepon / WA</th>
                        <th class="py-3.5 px-3.5 text-center min-w-[150px]">Kehadiran</th>
                        <th class="py-3.5 px-3.5">Tanggal Daftar</th>
                        <th class="py-3.5 px-3.5 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pendaftarans as $index => $item)
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $item->no_telp);
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                            $isHadir = ($item->status_kehadiran === 'hadir');
                        @endphp
                        <tr class="hover:bg-blue-50/40 transition" id="row-pendaftar-{{ $item->id }}">
                            <td class="py-3.5 px-3.5 text-center text-gray-700 font-mono font-bold">
                                {{ $pendaftarans->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-3.5 font-bold text-gray-950">
                                {{ $item->nama }}
                            </td>
                            <td class="py-3.5 px-3.5 text-gray-900 font-mono font-bold">
                                {{ $item->nim }}
                            </td>
                            <td class="py-3.5 px-3.5">
                                <span class="inline-block bg-blue-50 border border-[#2A82C6] px-2 py-0.5 text-xs text-[#1A467C] font-bold font-mono">
                                    {{ $item->kelas }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3.5">
                                <a 
                                    href="https://wa.me/{{ $cleanPhone }}" 
                                    target="_blank" 
                                    title="Kirim pesan WhatsApp"
                                    class="inline-flex items-center gap-1.5 text-[#1A467C] hover:text-[#2A82C6] font-mono font-bold hover:underline"
                                >
                                    <svg class="w-3.5 h-3.5 fill-current text-green-600" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.969.586 1.761.882 2.796.883h.001c3.182 0 5.767-2.587 5.768-5.766.001-3.182-2.585-5.769-5.769-5.769zm3.364 8.163c-.147.417-.732.772-1.031.796-.285.023-.62.032-1.954-.525-1.583-.663-2.588-2.28-2.667-2.385-.078-.105-.639-.851-.639-1.624 0-.773.404-1.155.549-1.311.144-.156.315-.195.421-.195.105 0 .211.001.303.006.098.005.228-.037.357.273.132.315.45 1.096.489 1.176.039.078.065.171.013.275-.052.104-.078.17-.156.26-.078.092-.164.205-.234.275-.079.079-.161.164-.069.322.092.157.409.675.877 1.091.602.535 1.109.701 1.267.78.157.078.249.065.341-.039.092-.105.393-.457.498-.614.105-.157.21-.131.353-.078.144.052.915.431 1.072.509.157.079.262.118.301.184.039.066.039.381-.108.798z"/>
                                    </svg>
                                    <span>{{ $item->no_telp }}</span>
                                </a>
                            </td>

                            <!-- Kolom: Toggle Kehadiran Super Smooth (Neubrutalism) -->
                            <td class="py-3.5 px-3.5 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <button 
                                        type="button" 
                                        role="switch" 
                                        aria-checked="{{ $isHadir ? 'true' : 'false' }}"
                                        onclick="toggleKehadiran({{ $item->id }}, this)"
                                        id="toggle-btn-{{ $item->id }}"
                                        class="relative inline-flex h-7 w-12 flex-shrink-0 cursor-pointer p-0.5 border-2 border-[#1A467C] transition-colors duration-300 ease-in-out focus:outline-none {{ $isHadir ? 'bg-green-700' : 'bg-gray-300' }}"
                                        title="Klik untuk ubah kehadiran"
                                    >
                                        <span class="sr-only">Toggle Kehadiran</span>
                                        <span 
                                            aria-hidden="true" 
                                            id="toggle-knob-{{ $item->id }}"
                                            class="pointer-events-none inline-block h-5 w-5 transform bg-white border border-[#1A467C] shadow-xs transition-transform duration-300 ease-out {{ $isHadir ? 'translate-x-5' : 'translate-x-0' }}"
                                        ></span>
                                    </button>
                                    <span 
                                        id="label-status-{{ $item->id }}" 
                                        class="text-xs font-black uppercase tracking-wider {{ $isHadir ? 'text-green-800' : 'text-gray-500' }}"
                                    >
                                        {{ $isHadir ? 'HADIR' : 'BELUM' }}
                                    </span>
                                </div>
                            </td>

                            <td class="py-3.5 px-3.5 text-gray-700 text-xs font-mono font-medium">
                                {{ $item->created_at ? $item->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB' : '-' }}
                            </td>
                            <td class="py-3.5 px-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit Button -->
                                    <a 
                                        href="{{ route('admin.pendaftar.edit', $item->id) }}" 
                                        class="btn-smooth px-2.5 py-1 bg-amber-400 hover:bg-amber-500 text-black text-[11px] font-black uppercase tracking-wider border border-black shadow-[1px_1px_0px_0px_#000000]"
                                        title="Edit Data"
                                    >
                                        Edit
                                    </a>

                                    <!-- Delete Button -->
                                    <form 
                                        action="{{ route('admin.pendaftar.destroy', $item->id) }}" 
                                        method="POST" 
                                        class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pendaftar atas nama {{ $item->nama }}?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="btn-smooth px-2.5 py-1 bg-[#CA2C2A] hover:bg-[#901C1A] text-white text-[11px] font-black uppercase tracking-wider border border-[#901C1A] shadow-[1px_1px_0px_0px_#000000] cursor-pointer"
                                            title="Hapus Data"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-gray-500 font-semibold text-xs sm:text-sm">
                                @if ($search || $kelasFilter || $statusFilter)
                                    Tidak ada data pendaftar yang cocok dengan pencarian / filter Anda.
                                @else
                                    Belum ada data pendaftar yang masuk.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if ($pendaftarans->hasPages())
            <div class="px-5 py-3.5 border-t-2 border-[#1A467C] bg-white">
                {{ $pendaftarans->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Floating Toast Notification (Neubrutalism) -->
<div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

<script>
    const csrfToken = '{{ csrf_token() }}';

    function showToast(message, isSuccess = true) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `pointer-events-auto px-4 py-2.5 border-2 shadow-[4px_4px_0px_0px_#000000] text-xs font-black tracking-wide uppercase transition-all duration-300 transform translate-y-2 opacity-0 flex items-center gap-2 ${
            isSuccess 
                ? 'bg-green-700 text-white border-green-950' 
                : 'bg-[#CA2C2A] text-white border-[#901C1A]'
        }`;
        
        toast.innerHTML = `
            <span>${isSuccess ? '✓' : '✕'}</span>
            <span>${message}</span>
        `;
        
        container.appendChild(toast);
        
        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-2', 'opacity-0');
        });

        // Remove after 2.5 seconds
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    async function toggleKehadiran(id, btnElement) {
        const knob = document.getElementById(`toggle-knob-${id}`);
        const label = document.getElementById(`label-status-${id}`);
        const statHadir = document.getElementById('stat-total-hadir');
        const statBelum = document.getElementById('stat-total-belum-hadir');

        btnElement.disabled = true;

        try {
            const url = `{{ url('/admin/pendaftar') }}/${id}/toggle-kehadiran`;
            const response = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({})
            });

            const data = await response.json();

            if (response.ok && data.success) {
                const isHadir = data.is_hadir;

                // Smooth update switch state
                btnElement.setAttribute('aria-checked', isHadir ? 'true' : 'false');
                if (isHadir) {
                    btnElement.classList.remove('bg-gray-300');
                    btnElement.classList.add('bg-green-700');
                    knob.classList.remove('translate-x-0');
                    knob.classList.add('translate-x-5');
                    label.textContent = 'HADIR';
                    label.classList.remove('text-gray-500');
                    label.classList.add('text-green-800');
                } else {
                    btnElement.classList.remove('bg-green-700');
                    btnElement.classList.add('bg-gray-300');
                    knob.classList.remove('translate-x-5');
                    knob.classList.add('translate-x-0');
                    label.textContent = 'BELUM';
                    label.classList.remove('text-green-800');
                    label.classList.add('text-gray-500');
                }

                // Update stats counter dynamically if exists
                if (statHadir && statBelum) {
                    let hadirCount = parseInt(statHadir.textContent.replace(/[^0-9]/g, '')) || 0;
                    let belumCount = parseInt(statBelum.textContent.replace(/[^0-9]/g, '')) || 0;

                    if (isHadir) {
                        hadirCount++;
                        belumCount = Math.max(0, belumCount - 1);
                    } else {
                        hadirCount = Math.max(0, hadirCount - 1);
                        belumCount++;
                    }

                    statHadir.textContent = hadirCount.toLocaleString();
                    statBelum.textContent = belumCount.toLocaleString();
                }

                showToast(data.message, true);
            } else {
                showToast(data.message || 'Gagal mengubah status kehadiran.', false);
            }
        } catch (error) {
            console.error(error);
            showToast('Terjadi kesalahan jaringan.', false);
        } finally {
            btnElement.disabled = false;
        }
    }
</script>
@endsection
