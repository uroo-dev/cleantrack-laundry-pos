@extends('layouts.admin')

@section('title', 'Tracking Laundry')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('staff.order.queue') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tracking</li>
@endsection

@section('content')
    <div class="mb-6">
        <h2 class="text-headline-sm font-bold">Tracking Laundry</h2>
        <p class="text-body-sm text-on-surface-variant">Cari dan update status pesanan laundry</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-5 space-y-6">
            {{-- Search --}}
            <div class="glass-card rounded-2xl p-6 soft-shadow">
                <h3 class="text-label-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">search</span>
                    Cari Transaksi
                </h3>
                <form method="GET" action="{{ route('staff.tracking.index') }}">
                    <div class="flex gap-3">
                        <input type="text" name="kode" class="flex-1 rounded-xl border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" placeholder="Masukkan kode transaksi..." value="{{ request('kode') }}" required>
                        <button type="submit" class="bg-primary text-on-primary px-4 py-2.5 rounded-xl font-bold inline-flex items-center justify-center hover:bg-primary-container hover:text-on-primary-fixed-variant transition-all">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Status Update --}}
            @if(request('kode'))
            <div class="glass-card rounded-2xl p-6 soft-shadow">
                <h3 class="text-label-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">sync_alt</span>
                    Update Status
                </h3>
                <form method="GET" action="{{ route('staff.tracking.index') }}" class="flex gap-3">
                    <input type="hidden" name="kode" value="{{ request('kode') }}">
                    <select name="status" class="flex-1 rounded-xl border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                        <option value="menunggu" {{ request('status', 'menunggu') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="dicuci" {{ request('status') == 'dicuci' ? 'selected' : '' }}>Dicuci</option>
                        <option value="dikeringkan" {{ request('status') == 'dikeringkan' ? 'selected' : '' }}>Dikeringkan</option>
                        <option value="disetrika" {{ request('status') == 'disetrika' ? 'selected' : '' }}>Disetrika</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                    </select>
                    <button type="submit" class="bg-primary text-on-primary px-5 py-2.5 rounded-xl font-bold text-label-sm hover:bg-primary-container hover:text-on-primary-fixed-variant transition-all">Update</button>
                </form>
            </div>
            @endif
        </div>

        <div class="lg:col-span-7">
            @if(isset($transaksi))
            <div class="glass-card rounded-2xl p-6 soft-shadow">
                <div class="flex items-center gap-2 pb-4 border-b border-outline-variant/50">
                    <span class="material-symbols-outlined text-primary">track_changes</span>
                    <h3 class="text-label-lg font-bold flex-1">Tracking</h3>
                    <span class="font-mono text-label-sm bg-primary/10 text-primary px-3 py-1 rounded-lg">{{ $transaksi->kode_transaksi }}</span>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-body-sm">
                        <span class="font-medium">{{ $transaksi->pelanggan->nama ?? '-' }}</span>
                        <span class="text-on-surface-variant">{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    {{-- Progress Steps --}}
                    <div class="mt-6">
                        @php
                            $steps = ['menunggu', 'dicuci', 'dikeringkan', 'disetrika', 'selesai', 'diambil'];
                            $currentIdx = array_search($transaksi->status, $steps);
                        @endphp
                        <div class="flex items-center gap-1">
                            @foreach($steps as $i => $step)
                                @php $done = $i <= $currentIdx; @endphp
                                <div class="flex-1 text-center">
                                    <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center text-label-sm font-bold {{ $done ? 'bg-primary text-on-primary' : 'bg-surface-container-highest text-on-surface-variant' }}">
                                        {{ $done ? '✓' : ($i + 1) }}
                                    </div>
                                    <p class="text-label-sm mt-1 {{ $done ? 'text-primary font-bold' : 'text-on-surface-variant' }}">{{ ucfirst($step) }}</p>
                                </div>
                                @if($i < count($steps) - 1)
                                    <div class="flex-1 h-1 rounded-full {{ $i < $currentIdx ? 'bg-primary' : 'bg-surface-container-highest' }}"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-6 grid grid-cols-2 gap-4 text-body-sm bg-surface-container-low/50 rounded-xl p-4">
                        <div><span class="text-on-surface-variant">Layanan:</span> <span class="font-medium">{{ $transaksi->layanan->nama ?? '-' }}</span></div>
                        <div><span class="text-on-surface-variant">Berat:</span> <span class="font-medium">{{ $transaksi->berat }} kg</span></div>
                        <div><span class="text-on-surface-variant">Total:</span> <span class="font-medium">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span></div>
                        <div><span class="text-on-surface-variant">Status:</span> <span class="font-medium capitalize">{{ $transaksi->status }}</span></div>
                    </div>
                </div>
            </div>
            @elseif(request('kode'))
            <div class="glass-card rounded-2xl p-8 soft-shadow text-center">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant opacity-30">search_off</span>
                <p class="text-body-sm text-on-surface-variant mt-3">Transaksi dengan kode "{{ request('kode') }}" tidak ditemukan</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Active Orders Table --}}
    <div class="glass-card rounded-2xl p-6 soft-shadow mt-6">
        <h3 class="text-label-lg font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">list_alt</span>
            Order Aktif
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-body-sm">
                <thead>
                    <tr class="border-b border-outline-variant/50">
                        <th class="text-left text-label-sm text-on-surface-variant font-medium pb-3 px-3">Kode</th>
                        <th class="text-left text-label-sm text-on-surface-variant font-medium pb-3 px-3">Pelanggan</th>
                        <th class="text-left text-label-sm text-on-surface-variant font-medium pb-3 px-3">Layanan</th>
                        <th class="text-left text-label-sm text-on-surface-variant font-medium pb-3 px-3">Berat</th>
                        <th class="text-left text-label-sm text-on-surface-variant font-medium pb-3 px-3">Status</th>
                        <th class="text-left text-label-sm text-on-surface-variant font-medium pb-3 px-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                    <tr class="border-b border-outline-variant/20 hover:bg-surface-container-low/50 transition-all">
                        <td class="py-3 px-3 font-mono text-label-sm">{{ $o->kode_transaksi }}</td>
                        <td class="py-3 px-3 font-medium">{{ $o->pelanggan->nama ?? '-' }}</td>
                        <td class="py-3 px-3 text-on-surface-variant">{{ $o->layanan->nama ?? '-' }}</td>
                        <td class="py-3 px-3 text-on-surface-variant">{{ $o->berat }} kg</td>
                        <td class="py-3 px-3">
                            @php
                                $badge = match($o->status) {
                                    'menunggu' => 'bg-amber-50 text-amber-700',
                                    'dicuci' => 'bg-sky-50 text-sky-700',
                                    'dikeringkan' => 'bg-violet-50 text-violet-700',
                                    'disetrika' => 'bg-orange-50 text-orange-700',
                                    'selesai' => 'bg-green-50 text-green-700',
                                    'diambil' => 'bg-slate-50 text-slate-600',
                                    default => 'bg-surface-container-high text-on-surface-variant'
                                };
                            @endphp
                            <span class="text-label-sm font-medium px-3 py-1 rounded-lg {{ $badge }}">{{ ucfirst($o->status) }}</span>
                        </td>
                        <td class="py-3 px-3 text-on-surface-variant">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-body-sm text-on-surface-variant py-8">
                            <span class="material-symbols-outlined text-2xl opacity-40 mb-1 block">inbox</span>
                            Tidak ada order aktif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
