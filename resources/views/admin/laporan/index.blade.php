@extends('layouts.admin')

@section('title', 'Laporan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Laporan</li>
@endsection

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-headline-sm">Laporan</h2>
    </div>

    <div class="glass-card rounded-2xl p-6 soft-shadow mb-6">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="w-full sm:w-auto">
                <label class="text-label-sm block mb-1.5">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ request('dari', now()->startOfMonth()->format('Y-m-d')) }}"
                    class="bg-surface-container rounded-xl border border-outline-variant/40 px-4 py-2.5 w-full sm:w-56 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition">
            </div>
            <div class="w-full sm:w-auto">
                <label class="text-label-sm block mb-1.5">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ request('sampai', now()->format('Y-m-d')) }}"
                    class="bg-surface-container rounded-xl border border-outline-variant/40 px-4 py-2.5 w-full sm:w-56 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition">
            </div>
            <button type="submit"
                class="bg-primary text-on-primary px-5 py-2.5 rounded-xl font-bold text-label-sm hover:opacity-90 transition w-full sm:w-auto">
                Tampilkan
            </button>
        </form>
    </div>

    <div class="flex gap-2 mb-6">
        <button type="button"
            class="tab-btn px-5 py-2.5 rounded-xl font-bold text-label-sm transition {{ request('tab', 'pendapatan') === 'pendapatan' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}"
            data-tab="pendapatan">Pendapatan</button>
        <button type="button"
            class="tab-btn px-5 py-2.5 rounded-xl font-bold text-label-sm transition {{ request('tab') === 'pelanggan' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}"
            data-tab="pelanggan">Pelanggan</button>
        <button type="button"
            class="tab-btn px-5 py-2.5 rounded-xl font-bold text-label-sm transition {{ request('tab') === 'layanan' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}"
            data-tab="layanan">Layanan</button>
    </div>

    <div id="tab-pendapatan" class="tab-content {{ request('tab', 'pendapatan') === 'pendapatan' ? 'block' : 'hidden' }}">
        <div class="grid grid-cols-12 gap-4 mb-6">
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="glass-card rounded-2xl p-5 soft-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-primary/20 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-2xl">payments</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-label-sm text-outline truncate">Total Pendapatan</div>
                            <div class="text-headline-sm font-bold mt-0.5 truncate">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="glass-card rounded-2xl p-5 soft-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-success/20 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-success text-2xl">description</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-label-sm text-outline truncate">Total Transaksi</div>
                            <div class="text-headline-sm font-bold mt-0.5 truncate">{{ $totalTransaksi ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="glass-card rounded-2xl p-5 soft-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-warning/20 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-warning text-2xl">trending_up</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-label-sm text-outline truncate">Rata-rata per Transaksi</div>
                            <div class="text-headline-sm font-bold mt-0.5 truncate">Rp {{ number_format($rataTransaksi ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="glass-card rounded-2xl p-5 soft-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-info/20 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-info text-2xl">inventory_2</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-label-sm text-outline truncate">Total Berat (kg)</div>
                            <div class="text-headline-sm font-bold mt-0.5 truncate">{{ $totalBerat ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6 soft-shadow">
            <canvas id="pendapatanChart" height="100"></canvas>
        </div>
    </div>

    <div id="tab-pelanggan" class="tab-content {{ request('tab') === 'pelanggan' ? 'block' : 'hidden' }}">
        <div class="grid grid-cols-12 gap-4 mb-6">
            <div class="col-span-12 sm:col-span-6">
                <div class="glass-card rounded-2xl p-5 soft-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-primary/20 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-2xl">group</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-label-sm text-outline truncate">Pelanggan Baru</div>
                            <div class="text-headline-sm font-bold mt-0.5 truncate">{{ $pelangganBaru ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <div class="glass-card rounded-2xl p-5 soft-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-success/20 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-success text-2xl">repeat</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-label-sm text-outline truncate">Pelanggan Aktif</div>
                            <div class="text-headline-sm font-bold mt-0.5 truncate">{{ $pelangganAktif ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6 soft-shadow">
            <canvas id="pelangganChart" height="100"></canvas>
        </div>
    </div>

    <div id="tab-layanan" class="tab-content {{ request('tab') === 'layanan' ? 'block' : 'hidden' }}">
        <div class="glass-card rounded-2xl p-6 soft-shadow mb-6 overflow-x-auto">
            <table class="w-full text-body-sm">
                <thead>
                    <tr class="border-b border-outline-variant/30 text-label-sm text-outline">
                        <th class="text-left py-3 px-2">Layanan</th>
                        <th class="text-left py-3 px-2">Jumlah Transaksi</th>
                        <th class="text-left py-3 px-2">Total Pendapatan</th>
                        <th class="text-left py-3 px-2">Total Berat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanLayanan ?? [] as $l)
                    <tr class="border-b border-outline-variant/10 hover:bg-surface-container/50 transition">
                        <td class="py-3 px-2 font-medium">{{ $l->nama }}</td>
                        <td class="py-3 px-2">{{ $l->total_transaksi }}</td>
                        <td class="py-3 px-2">Rp {{ number_format($l->total_pendapatan, 0, ',', '.') }}</td>
                        <td class="py-3 px-2">{{ $l->total_berat }} kg</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-outline py-6">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="glass-card rounded-2xl p-6 soft-shadow">
            <canvas id="layananChart" height="100"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + tab)?.classList.remove('hidden');
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.className = b.className.replace('bg-primary text-on-primary', 'bg-surface-container text-on-surface hover:bg-surface-container-high');
            });
            this.className = this.className.replace('bg-surface-container text-on-surface hover:bg-surface-container-high', 'bg-primary text-on-primary');
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.replaceState({}, '', url);
        });
    });
</script>
@endpush
