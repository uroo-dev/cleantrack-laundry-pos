@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
    <div class="grid grid-cols-12 gap-6 mt-2">
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="box p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="dollar-sign" class="w-5 h-5 text-primary"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-slate-500 text-xs uppercase tracking-wide truncate">Pendapatan Hari Ini</div>
                        <div class="text-lg xl:text-2xl font-bold mt-1 truncate">Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="box p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-success/20 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="users" class="w-5 h-5 text-success"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-slate-500 text-xs uppercase tracking-wide truncate">Total Pelanggan</div>
                        <div class="text-lg xl:text-2xl font-bold mt-1 truncate">{{ $totalPelanggan ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="box p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-warning/20 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="activity" class="w-5 h-5 text-warning"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-slate-500 text-xs uppercase tracking-wide truncate">Order Aktif</div>
                        <div class="text-lg xl:text-2xl font-bold mt-1 truncate">{{ $orderAktif ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="box p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-info/20 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="check-circle" class="w-5 h-5 text-info"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-slate-500 text-xs uppercase tracking-wide truncate">Laundry Selesai</div>
                        <div class="text-lg xl:text-2xl font-bold mt-1 truncate">{{ $laundrySelesai ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-8">
        <div class="col-span-12">
            <div class="box p-5 intro-x">
                <div class="flex items-center pb-3 border-b border-slate-200">
                    <h2 class="text-lg font-medium">Transaksi Terbaru</h2>
                </div>
                <div class="overflow-x-auto mt-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">#</th>
                                <th class="whitespace-nowrap">Kode</th>
                                <th class="whitespace-nowrap">Pelanggan</th>
                                <th class="whitespace-nowrap">Layanan</th>
                                <th class="whitespace-nowrap">Berat</th>
                                <th class="whitespace-nowrap">Total</th>
                                <th class="whitespace-nowrap">Status</th>
                                <th class="whitespace-nowrap">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksiTerbaru ?? [] as $trx)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="font-mono">{{ $trx->kode_transaksi }}</td>
                                <td>{{ $trx->pelanggan->nama ?? '-' }}</td>
                                <td>{{ $trx->layanan->nama ?? '-' }}</td>
                                <td>{{ $trx->berat }} kg</td>
                                <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $badge = match($trx->status) {
                                            'menunggu' => 'bg-warning/20 text-warning',
                                            'dicuci' => 'bg-info/20 text-info',
                                            'dikeringkan' => 'bg-purple-200/20 text-purple-700',
                                            'disetrika' => 'bg-orange-200/20 text-orange-700',
                                            'selesai' => 'bg-success/20 text-success',
                                            'diambil' => 'bg-slate-300/20 text-slate-600',
                                            default => 'bg-slate-100 text-slate-600'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs font-medium {{ $badge }}">{{ ucfirst($trx->status) }}</span>
                                </td>
                                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-slate-400 py-4">Belum ada transaksi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
