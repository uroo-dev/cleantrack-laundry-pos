@extends('layouts.admin')

@section('title', 'Laporan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Laporan</li>
@endsection

@section('content')
    <h2 class="intro-x text-2xl font-medium">Laporan</h2>

    <div class="box p-5 mt-5 intro-x">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-col sm:flex-row gap-3 items-end">
            <div>
                <label class="form-label text-xs">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control" value="{{ request('dari', now()->startOfMonth()->format('Y-m-d')) }}">
            </div>
            <div>
                <label class="form-label text-xs">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control" value="{{ request('sampai', now()->format('Y-m-d')) }}">
            </div>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>
    </div>

    <div class="flex mt-5 gap-2 intro-x">
        <button type="button" class="btn {{ request('tab', 'pendapatan') === 'pendapatan' ? 'btn-primary' : 'btn-secondary' }}" data-tab="pendapatan">Pendapatan</button>
        <button type="button" class="btn {{ request('tab') === 'pelanggan' ? 'btn-primary' : 'btn-secondary' }}" data-tab="pelanggan">Pelanggan</button>
        <button type="button" class="btn {{ request('tab') === 'layanan' ? 'btn-primary' : 'btn-secondary' }}" data-tab="layanan">Layanan</button>
    </div>

    <div id="tab-pendapatan" class="tab-content {{ request('tab', 'pendapatan') === 'pendapatan' ? 'block' : 'hidden' }}">
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="box p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="dollar-sign" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-slate-500 text-xs uppercase truncate">Total Pendapatan</div>
                            <div class="text-lg xl:text-2xl font-bold mt-1 truncate">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="box p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-success/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="file-text" class="w-5 h-5 text-success"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-slate-500 text-xs uppercase truncate">Total Transaksi</div>
                            <div class="text-lg xl:text-2xl font-bold mt-1 truncate">{{ $totalTransaksi ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="box p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-warning/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="trending-up" class="w-5 h-5 text-warning"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-slate-500 text-xs uppercase truncate">Rata-rata per Transaksi</div>
                            <div class="text-lg xl:text-2xl font-bold mt-1 truncate">Rp {{ number_format($rataTransaksi ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="box p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-info/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="package" class="w-5 h-5 text-info"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-slate-500 text-xs uppercase truncate">Total Berat (kg)</div>
                            <div class="text-lg xl:text-2xl font-bold mt-1 truncate">{{ $totalBerat ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box p-5 mt-5">
            <canvas id="pendapatanChart" height="100"></canvas>
        </div>
    </div>

    <div id="tab-pelanggan" class="tab-content {{ request('tab') === 'pelanggan' ? 'block' : 'hidden' }}">
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-6">
                <div class="box p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="users" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-slate-500 text-xs uppercase truncate">Pelanggan Baru</div>
                            <div class="text-lg xl:text-2xl font-bold mt-1 truncate">{{ $pelangganBaru ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <div class="box p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-success/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="repeat" class="w-5 h-5 text-success"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-slate-500 text-xs uppercase truncate">Pelanggan Aktif</div>
                            <div class="text-lg xl:text-2xl font-bold mt-1 truncate">{{ $pelangganAktif ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box p-5 mt-5">
            <canvas id="pelangganChart" height="100"></canvas>
        </div>
    </div>

    <div id="tab-layanan" class="tab-content {{ request('tab') === 'layanan' ? 'block' : 'hidden' }}">
        <div class="box p-5 mt-5 overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Layanan</th>
                        <th>Jumlah Transaksi</th>
                        <th>Total Pendapatan</th>
                        <th>Total Berat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanLayanan ?? [] as $l)
                    <tr>
                        <td>{{ $l->nama }}</td>
                        <td>{{ $l->total_transaksi }}</td>
                        <td>Rp {{ number_format($l->total_pendapatan, 0, ',', '.') }}</td>
                        <td>{{ $l->total_berat }} kg</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-slate-400 py-4">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="box p-5 mt-5">
            <canvas id="layananChart" height="100"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-tab]').forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + tab)?.classList.remove('hidden');
            document.querySelectorAll('[data-tab]').forEach(b => {
                b.className = b.className.replace('btn-primary', 'btn-secondary');
            });
            this.className = this.className.replace('btn-secondary', 'btn-primary');
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.replaceState({}, '', url);
        });
    });
</script>
@endpush
