@extends('layouts.admin')

@section('title', 'Tracking Laundry')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('staff.order.queue') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tracking</li>
@endsection

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-5">
            <div class="box p-8 intro-x mt-4">
                <h2 class="text-lg font-medium mb-4">Cari Transaksi</h2>
                <form method="GET" action="{{ route('staff.tracking.index') }}">
                    <div class="flex gap-2">
                        <input type="text" name="kode" class="form-control" placeholder="Masukkan kode transaksi..." value="{{ request('kode') }}" required>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>
            </div>

            @if(request('kode'))
            <div class="box p-5 mt-4 intro-x">
                <form method="GET" action="{{ route('staff.tracking.index') }}" class="flex gap-2">
                    <input type="hidden" name="kode" value="{{ request('kode') }}">
                    <select name="status" class="form-control">
                        <option value="menunggu" {{ request('status', 'menunggu') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="dicuci" {{ request('status') == 'dicuci' ? 'selected' : '' }}>Dicuci</option>
                        <option value="dikeringkan" {{ request('status') == 'dikeringkan' ? 'selected' : '' }}>Dikeringkan</option>
                        <option value="disetrika" {{ request('status') == 'disetrika' ? 'selected' : '' }}>Disetrika</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </form>
            </div>
            @endif
        </div>

        <div class="col-span-12 lg:col-span-7">
            @if(isset($transaksi))
            <div class="box p-5 intro-x mt-4">
                <div class="flex items-center border-b border-slate-200 pb-3">
                    <h2 class="text-lg font-medium">Tracking</h2>
                    <span class="ml-auto font-mono text-primary">{{ $transaksi->kode_transaksi }}</span>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span>{{ $transaksi->pelanggan->nama ?? '-' }}</span>
                        <span class="text-slate-400">{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="mt-4">
                        @php
                            $steps = ['menunggu', 'dicuci', 'dikeringkan', 'disetrika', 'selesai', 'diambil'];
                            $currentIdx = array_search($transaksi->status, $steps);
                        @endphp
                        <div class="flex items-center gap-1">
                            @foreach($steps as $i => $step)
                                @php $done = $i <= $currentIdx; @endphp
                                <div class="flex-1 text-center">
                                    <div class="w-6 h-6 mx-auto rounded-full flex items-center justify-center text-xs font-bold {{ $done ? 'bg-primary text-white' : 'bg-slate-200 text-slate-400' }}">
                                        {{ $done ? '✓' : ($i + 1) }}
                                    </div>
                                    <p class="text-xs mt-1 {{ $done ? 'text-primary font-medium' : 'text-slate-400' }}">{{ ucfirst($step) }}</p>
                                </div>
                                @if($i < count($steps) - 1)
                                    <div class="flex-1 h-0.5 {{ $i < $currentIdx ? 'bg-primary' : 'bg-slate-200' }}"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div><span class="text-slate-400">Layanan:</span> {{ $transaksi->layanan->nama ?? '-' }}</div>
                        <div><span class="text-slate-400">Berat:</span> {{ $transaksi->berat }} kg</div>
                        <div><span class="text-slate-400">Total:</span> Rp {{ number_format($transaksi->total, 0, ',', '.') }}</div>
                        <div><span class="text-slate-400">Status:</span> {{ ucfirst($transaksi->status) }}</div>
                    </div>
                </div>
            </div>
            @elseif(request('kode'))
            <div class="box p-8 intro-x mt-4 text-center">
                <i data-lucide="search-x" class="w-12 h-12 mx-auto text-slate-300"></i>
                <p class="text-slate-400 mt-3">Transaksi dengan kode "{{ request('kode') }}" tidak ditemukan</p>
            </div>
            @endif
        </div>
    </div>

    <div class="box p-5 mt-5 intro-x">
        <h3 class="text-base font-medium border-b border-slate-200 pb-3">Order Aktif</h3>
        <div class="overflow-x-auto mt-3">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Berat</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                    <tr>
                        <td class="font-mono">{{ $o->kode_transaksi }}</td>
                        <td>{{ $o->pelanggan->nama ?? '-' }}</td>
                        <td>{{ $o->layanan->nama ?? '-' }}</td>
                        <td>{{ $o->berat }} kg</td>
                        <td>
                            @php
                                $badge = match($o->status) {
                                    'menunggu' => 'bg-warning/20 text-warning',
                                    'dicuci' => 'bg-info/20 text-info',
                                    'dikeringkan' => 'bg-purple-200/20 text-purple-700',
                                    'disetrika' => 'bg-orange-200/20 text-orange-700',
                                    'selesai' => 'bg-success/20 text-success',
                                    'diambil' => 'bg-slate-300/20 text-slate-600',
                                    default => 'bg-slate-100 text-slate-600'
                                };
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $badge }}">{{ ucfirst($o->status) }}</span>
                        </td>
                        <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-slate-400 py-4">Tidak ada order aktif</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
