@extends('admin.layouts.app')

@section('title', 'Data Pendaftar')
@section('header_title', 'Data Pendaftar POKJA')

@section('content')
<div class="space-y-6">

    <!-- 4 Stat Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Card 1: Total Pendaftar -->
        <div class="bg-white border border-gray-300 p-4 sm:p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Pendaftar</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-bold text-gray-900" id="stat-total-pendaftar">{{ number_format($totalPendaftar) }}</span>
                <span class="text-[11px] font-medium text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5">Mahasiswa</span>
            </div>
        </div>

        <!-- Card 2: Total Hadir -->
        <div class="bg-white border border-gray-300 p-4 sm:p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-green-700">Mahasiswa Hadir</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-bold text-green-700" id="stat-total-hadir">{{ number_format($totalHadir) }}</span>
                <span class="text-[11px] font-medium text-green-800 bg-green-50 border border-green-300 px-2 py-0.5">Hadir</span>
            </div>
        </div>

        <!-- Card 3: Belum Hadir -->
        <div class="bg-white border border-gray-300 p-4 sm:p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Belum Hadir</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-bold text-gray-700" id="stat-total-belum-hadir">{{ number_format($totalBelumHadir) }}</span>
                <span class="text-[11px] font-medium text-gray-700 bg-gray-100 border border-gray-300 px-2 py-0.5">Belum / Absen</span>
            </div>
        </div>

        <!-- Card 4: Pendaftar Hari Ini -->
        <div class="bg-white border border-gray-300 p-4 sm:p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Pendaftar Hari Ini</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($pendaftarHariIni) }}</span>
                <span class="text-[11px] font-medium text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5">Hari Ini</span>
            </div>
        </div>
    </div>

    <!-- Action Bar: Search, Filter, Export -->
    <div class="bg-white border border-gray-300 p-4 sm:p-5 shadow-sm">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
            
            <!-- Search & Filter Fields -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 flex-1">
                <!-- Search Input -->
                <div class="flex-1 min-w-[180px]">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}"
                        placeholder="Cari Nama, NIM, No. Telp..."
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-sm px-3.5 py-2 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition"
                    >
                </div>

                <!-- Filter Kelas Dropdown -->
                <div class="sm:w-44">
                    <select 
                        name="kelas" 
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-sm px-3 py-2 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition cursor-pointer font-mono"
                    >
                        <option value="">-- Semua Kelas --</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k }}" {{ $kelasFilter == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status Kehadiran Dropdown -->
                <div class="sm:w-44">
                    <select 
                        name="status_kehadiran" 
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-sm px-3 py-2 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition cursor-pointer"
                    >
                        <option value="">-- Semua Kehadiran --</option>
                        <option value="hadir" {{ $statusFilter == 'hadir' ? 'selected' : '' }}>🟢 Hadir</option>
                        <option value="belum_hadir" {{ $statusFilter == 'belum_hadir' ? 'selected' : '' }}>⚪ Belum Hadir</option>
                        <option value="tidak_hadir" {{ $statusFilter == 'tidak_hadir' ? 'selected' : '' }}>🔴 Tidak Hadir</option>
                    </select>
                </div>

                <!-- Tombol Filter & Reset -->
                <div class="flex items-center gap-2">
                    <button 
                        type="submit" 
                        class="flex-1 sm:flex-none px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-xs font-semibold uppercase tracking-wider transition cursor-pointer"
                    >
                        Filter
                    </button>
                    @if ($search || $kelasFilter || $statusFilter)
                        <a 
                            href="{{ route('admin.dashboard') }}" 
                            class="px-3 py-2 border border-gray-300 text-gray-600 hover:bg-gray-100 text-xs font-semibold uppercase tracking-wider transition text-center"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </div>

            <!-- Export CSV Button -->
            <div>
                <a 
                    href="{{ route('admin.pendaftar.export', ['search' => $search, 'kelas' => $kelasFilter, 'status_kehadiran' => $statusFilter]) }}" 
                    class="w-full lg:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-xs font-semibold uppercase tracking-wider transition cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Export CSV / Excel</span>
                </a>
            </div>

        </form>
    </div>

    <!-- Tabel Data Pendaftar -->
    <div class="bg-white border border-gray-300 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700">Daftar Mahasiswa Pendaftar</h3>
            <span class="text-xs text-gray-500">Menampilkan {{ $pendaftarans->count() }} dari total {{ $pendaftarans->total() }} data</span>
        </div>

        <!-- Responsive Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-gray-300 bg-gray-100 text-gray-700 uppercase font-semibold text-[11px] sm:text-xs tracking-wider">
                        <th class="py-3 px-3 w-10 text-center">No</th>
                        <th class="py-3 px-3.5">Nama Lengkap</th>
                        <th class="py-3 px-3.5">NIM</th>
                        <th class="py-3 px-3.5">Kelas</th>
                        <th class="py-3 px-3.5">No. Telepon / WA</th>
                        <th class="py-3 px-3.5 text-center min-w-[140px]">Kehadiran</th>
                        <th class="py-3 px-3.5">Tanggal Daftar</th>
                        <th class="py-3 px-3.5 text-center w-28">Aksi</th>
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
                        <tr class="hover:bg-gray-50 transition" id="row-pendaftar-{{ $item->id }}">
                            <td class="py-3 px-3 text-center text-gray-500 font-mono">
                                {{ $pendaftarans->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-3.5 font-medium text-gray-900">
                                {{ $item->nama }}
                            </td>
                            <td class="py-3 px-3.5 text-gray-700 font-mono">
                                {{ $item->nim }}
                            </td>
                            <td class="py-3 px-3.5">
                                <span class="inline-block bg-gray-100 border border-gray-300 px-2 py-0.5 text-xs text-gray-800 font-medium">
                                    {{ $item->kelas }}
                                </span>
                            </td>
                            <td class="py-3 px-3.5">
                                <a 
                                    href="https://wa.me/{{ $cleanPhone }}" 
                                    target="_blank" 
                                    title="Kirim pesan WhatsApp"
                                    class="inline-flex items-center gap-1.5 text-green-700 hover:text-green-900 font-mono hover:underline"
                                >
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.969.586 1.761.882 2.796.883h.001c3.182 0 5.767-2.587 5.768-5.766.001-3.182-2.585-5.769-5.769-5.769zm3.364 8.163c-.147.417-.732.772-1.031.796-.285.023-.62.032-1.954-.525-1.583-.663-2.588-2.28-2.667-2.385-.078-.105-.639-.851-.639-1.624 0-.773.404-1.155.549-1.311.144-.156.315-.195.421-.195.105 0 .211.001.303.006.098.005.228-.037.357.273.132.315.45 1.096.489 1.176.039.078.065.171.013.275-.052.104-.078.17-.156.26-.078.092-.164.205-.234.275-.079.079-.161.164-.069.322.092.157.409.675.877 1.091.602.535 1.109.701 1.267.78.157.078.249.065.341-.039.092-.105.393-.457.498-.614.105-.157.21-.131.353-.078.144.052.915.431 1.072.509.157.079.262.118.301.184.039.066.039.381-.108.798z"/>
                                    </svg>
                                    <span>{{ $item->no_telp }}</span>
                                </a>
                            </td>

                            <!-- Kolom: Toggle Kehadiran Interaktif -->
                            <td class="py-3 px-3.5 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <button 
                                        type="button" 
                                        role="switch" 
                                        aria-checked="{{ $isHadir ? 'true' : 'false' }}"
                                        onclick="toggleKehadiran({{ $item->id }}, this)"
                                        id="toggle-btn-{{ $item->id }}"
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $isHadir ? 'bg-green-600' : 'bg-gray-300' }}"
                                        title="Klik untuk ubah kehadiran"
                                    >
                                        <span class="sr-only">Toggle Kehadiran</span>
                                        <span 
                                            aria-hidden="true" 
                                            id="toggle-knob-{{ $item->id }}"
                                            class="pointer-events-none inline-block h-5 w-5 transform bg-white shadow ring-0 transition duration-200 ease-in-out {{ $isHadir ? 'translate-x-5' : 'translate-x-0' }}"
                                        ></span>
                                    </button>
                                    <span 
                                        id="label-status-{{ $item->id }}" 
                                        class="text-xs font-semibold uppercase tracking-wider {{ $isHadir ? 'text-green-700' : 'text-gray-500' }}"
                                    >
                                        {{ $isHadir ? 'Hadir' : 'Belum' }}
                                    </span>
                                </div>
                            </td>

                            <td class="py-3 px-3.5 text-gray-500 text-xs font-mono">
                                {{ $item->created_at ? $item->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB' : '-' }}
                            </td>
                            <td class="py-3 px-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit Button -->
                                    <a 
                                        href="{{ route('admin.pendaftar.edit', $item->id) }}" 
                                        class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold uppercase tracking-wider transition"
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
                                            class="px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold uppercase tracking-wider transition cursor-pointer"
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
                            <td colspan="8" class="py-8 text-center text-gray-500 text-xs sm:text-sm">
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
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $pendaftarans->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Floating Toast Notification -->
<div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

<script>
    const csrfToken = '{{ csrf_token() }}';

    function showToast(message, isSuccess = true) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `pointer-events-auto px-4 py-2.5 shadow-md border text-xs font-semibold tracking-wide uppercase transition-all duration-300 transform translate-y-2 opacity-0 flex items-center gap-2 ${
            isSuccess 
                ? 'bg-green-800 text-white border-green-900' 
                : 'bg-red-800 text-white border-red-900'
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
        btnElement.style.opacity = '0.7';

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

                // Update switch state
                btnElement.setAttribute('aria-checked', isHadir ? 'true' : 'false');
                if (isHadir) {
                    btnElement.classList.remove('bg-gray-300');
                    btnElement.classList.add('bg-green-600');
                    knob.classList.remove('translate-x-0');
                    knob.classList.add('translate-x-5');
                    label.textContent = 'Hadir';
                    label.classList.remove('text-gray-500');
                    label.classList.add('text-green-700');
                } else {
                    btnElement.classList.remove('bg-green-600');
                    btnElement.classList.add('bg-gray-300');
                    knob.classList.remove('translate-x-5');
                    knob.classList.add('translate-x-0');
                    label.textContent = 'Belum';
                    label.classList.remove('text-green-700');
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
            btnElement.style.opacity = '1';
        }
    }
</script>
@endsection
