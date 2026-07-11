@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Ikhtisar bisnis laundry Anda')

@php
    $role = auth()->user()->role;
@endphp

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-8">
    <div class="glass-card rounded-2xl p-6 soft-shadow relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="flex items-start justify-between mb-2 relative">
            <div>
                <p class="text-label-sm text-on-surface-variant">Omzet Hari Ini</p>
                <p class="text-headline-sm font-bold mt-1">Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-primary-container/40 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">payments</span>
            </div>
        </div>
        <div class="flex items-center gap-1 mt-6 relative">
            <span class="material-symbols-outlined text-sm {{ ($omzetGrowth ?? 0) >= 0 ? 'text-tertiary' : 'text-error' }}">trending_up</span>
            <span class="text-label-sm {{ ($omzetGrowth ?? 0) >= 0 ? 'text-tertiary' : 'text-error' }}">{{ ($omzetGrowth ?? 0) >= 0 ? '+' : '' }}{{ number_format($omzetGrowth ?? 0) }}%</span>
            <span class="text-label-sm text-on-surface-variant ml-1">vs kemarin</span>
        </div>
    </div>

    <div class="glass-card rounded-2xl p-6 soft-shadow relative overflow-hidden group">
        <div class="flex items-start justify-between mb-2 relative">
            <div>
                <p class="text-label-sm text-on-surface-variant">Cucian Aktif</p>
                <p class="text-headline-sm font-bold mt-1">{{ number_format($orderAktif ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-secondary-container/40 flex items-center justify-center">
                <span class="material-symbols-outlined text-secondary">local_laundry_service</span>
            </div>
        </div>
        <div class="flex items-center gap-1 mt-6">
            <span class="material-symbols-outlined text-sm text-secondary-container">schedule</span>
            <span class="text-label-sm text-on-surface-variant">Dalam proses</span>
        </div>
    </div>

    <div class="glass-card rounded-2xl p-6 soft-shadow relative overflow-hidden group">
        <div class="flex items-start justify-between mb-2 relative">
            <div>
                <p class="text-label-sm text-on-surface-variant">Selesai Hari Ini</p>
                <p class="text-headline-sm font-bold mt-1">{{ number_format($laundrySelesai ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-tertiary-container/40 flex items-center justify-center">
                <span class="material-symbols-outlined text-tertiary">check_circle</span>
            </div>
        </div>
        <div class="flex items-center gap-1 mt-6">
            <span class="material-symbols-outlined text-sm text-tertiary">task_alt</span>
            <span class="text-label-sm text-on-surface-variant">Siap diambil</span>
        </div>
    </div>

    <div class="glass-card rounded-2xl p-6 soft-shadow relative overflow-hidden group">
        <div class="flex items-start justify-between mb-2 relative">
            <div>
                <p class="text-label-sm text-on-surface-variant">Total Pelanggan</p>
                <p class="text-headline-sm font-bold mt-1">{{ number_format($totalPelanggan ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-primary-container/40 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">group</span>
            </div>
        </div>
        <div class="flex items-center gap-1 mt-6">
            <span class="material-symbols-outlined text-sm text-primary">person_add</span>
            <span class="text-label-sm text-on-surface-variant">+{{ number_format($pelangganBaru ?? 0) }} bulan ini</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter mb-8">
    <div class="lg:col-span-2 glass-card rounded-2xl p-6 soft-shadow">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-headline-sm">Tren Volume Cucian</h3>
            <div class="flex items-center gap-3">
                <select id="chartRange" class="text-label-sm bg-surface-container border border-outline-variant rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="7">7 Hari</option>
                    <option value="30" selected>30 Hari</option>
                    <option value="90">90 Hari</option>
                </select>
            </div>
        </div>
        <div class="relative h-64">
            <canvas id="volumeChart"></canvas>
        </div>
    </div>

    <div class="glass-card rounded-2xl p-6 soft-shadow">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-headline-sm">Cucian Jatuh Tempo</h3>
            <span class="text-label-sm text-on-surface-variant">Hari ini</span>
        </div>
        <div class="space-y-4">
            @forelse($cucianJatuhTempo ?? [] as $item)
            <div class="flex items-center gap-4 p-3 rounded-xl bg-error-container/20 border border-error/10">
                <div class="w-10 h-10 rounded-full bg-error-container flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-error text-sm">schedule</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-label-lg truncate">{{ $item->pelanggan->nama ?? 'N/A' }}</p>
                    <p class="text-label-sm text-on-surface-variant">{{ $item->invoice ?? 'INV-001' }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-label-sm font-bold text-error">{{ \Carbon\Carbon::parse($item->estimasi_selesai)->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant opacity-30 mb-2">check_circle</span>
                <p class="text-body-sm text-on-surface-variant">Tidak ada cucian jatuh tempo</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<div class="glass-card rounded-2xl p-6 soft-shadow">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-headline-sm">Aktivitas Terbaru</h3>
        <a href="{{ route('admin.transaksi.index') }}" class="text-label-sm text-primary font-bold hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-label-sm text-on-surface-variant border-b border-outline-variant/30">
                    <th class="text-left py-3 px-2">Invoice</th>
                    <th class="text-left py-3 px-2">Pelanggan</th>
                    <th class="text-left py-3 px-2">Status</th>
                    <th class="text-left py-3 px-2">Total</th>
                    <th class="text-left py-3 px-2">Tanggal</th>
                    <th class="text-right py-3 px-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiTerbaru ?? [] as $trx)
                <tr class="border-b border-outline-variant/20 hover:bg-surface-container-low transition-all">
                    <td class="py-3 px-2">
                        <a href="{{ route('admin.transaksi.show', $trx->id) }}" class="text-label-lg text-primary font-bold">{{ $trx->invoice }}</a>
                    </td>
                    <td class="py-3 px-2 text-body-sm">{{ $trx->pelanggan->nama ?? 'N/A' }}</td>
                    <td class="py-3 px-2">
                        @php
                            $statusColors = [
                                'pending' => 'bg-warning/10 text-warning',
                                'proses' => 'bg-info/10 text-info',
                                'selesai' => 'bg-tertiary-container/40 text-tertiary',
                                'diambil' => 'bg-surface-container-high text-on-surface-variant',
                            ];
                            $color = $statusColors[$trx->status] ?? 'bg-surface-container-high text-on-surface-variant';
                        @endphp
                        <span class="text-label-sm px-3 py-1 rounded-full {{ $color }}">{{ ucfirst($trx->status) }}</span>
                    </td>
                    <td class="py-3 px-2 text-body-sm font-bold">Rp {{ number_format($trx->total ?? 0, 0, ',', '.') }}</td>
                    <td class="py-3 px-2 text-body-sm text-on-surface-variant">{{ $trx->created_at->format('d M Y') }}</td>
                    <td class="py-3 px-2 text-right">
                        <a href="{{ route('admin.transaksi.show', $trx->id) }}" class="inline-flex items-center gap-1 text-label-sm text-primary hover:underline">
                            Detail <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-on-surface-variant">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('volumeChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!},
            datasets: [{
                label: 'Volume',
                data: {!! json_encode($chartData ?? [12, 19, 15, 22, 18, 25, 20]) !!},
                borderColor: '#004ac6',
                backgroundColor: 'rgba(0,74,198,0.1)',
                fill: 'start',
                tension: 0.4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#004ac6',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { family: 'Plus Jakarta Sans' } } },
                x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans' } } }
            }
        }
    });
});
</script>
@endpush
