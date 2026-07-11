@extends('layouts.admin')

@section('title', 'Antrian Laundry')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('staff.order.queue') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Antrian</li>
@endsection

@section('content')
    <h2 class="intro-x text-2xl font-medium">Antrian Laundry</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        @php
            $statuses = [
                'menunggu' => ['label' => 'Menunggu', 'color' => 'warning', 'icon' => 'clock'],
                'dicuci' => ['label' => 'Dicuci', 'color' => 'info', 'icon' => 'droplet'],
                'dikeringkan' => ['label' => 'Dikeringkan', 'color' => 'purple', 'icon' => 'wind'],
                'disetrika' => ['label' => 'Disetrika', 'color' => 'orange', 'icon' => 'zap'],
                'selesai' => ['label' => 'Selesai', 'color' => 'success', 'icon' => 'check-circle'],
                'diambil' => ['label' => 'Diambil', 'color' => 'slate', 'icon' => 'package'],
            ];
        @endphp

        @foreach($statuses as $key => $status)
        <div class="col-span-12 sm:col-span-6 xl:col-span-4">
            <div class="box p-4 intro-x h-full">
                <div class="flex items-center border-b border-slate-200 pb-3 mb-3">
                    <i data-lucide="{{ $status['icon'] }}" class="w-5 h-5 mr-2 text-{{ $status['color'] }}"></i>
                    <h3 class="font-medium">{{ $status['label'] }}</h3>
                    <span class="ml-auto bg-{{ $status['color'] }}/20 text-{{ $status['color'] }} text-xs px-2 py-1 rounded">{{ ($groups[$key] ?? collect())->count() }}</span>
                </div>
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @forelse(($groups[$key] ?? collect()) as $trx)
                    <div class="border border-slate-200 rounded p-3 hover:bg-slate-50 transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-mono text-xs text-slate-400">{{ $trx->kode_transaksi }}</span>
                                <p class="font-medium text-sm">{{ $trx->pelanggan->nama ?? '-' }}</p>
                                <p class="text-xs text-slate-400">{{ $trx->layanan->nama ?? '-' }} - {{ $trx->berat }} kg</p>
                                <p class="text-xs text-slate-400">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                            </div>
                            <span class="text-xs text-slate-400">{{ $trx->created_at->format('H:i') }}</span>
                        </div>
                        <div class="mt-2 flex gap-1 flex-wrap">
                            @php
                                $nextStatus = match($trx->status) {
                                    'menunggu' => 'dicuci',
                                    'dicuci' => 'dikeringkan',
                                    'dikeringkan' => 'disetrika',
                                    'disetrika' => 'selesai',
                                    'selesai' => 'diambil',
                                    default => null
                                };
                                $btnLabels = [
                                    'menunggu' => 'Mulai Cuci',
                                    'dicuci' => 'Keringkan',
                                    'dikeringkan' => 'Setrika',
                                    'disetrika' => 'Selesai',
                                    'selesai' => 'Diambil',
                                ];
                            @endphp
                            @if($nextStatus && isset($btnLabels[$trx->status]))
                            <form method="POST" action="{{ route('staff.order.update-status', $trx->id) }}" class="inline">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="{{ $nextStatus }}">
                                <button type="submit" class="btn btn-sm btn-primary text-xs">{{ $btnLabels[$trx->status] }}</button>
                            </form>
                            @endif
                            <a href="{{ route('staff.order.nota', $trx->id) }}" class="btn btn-sm btn-outline-secondary text-xs">Detail</a>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-slate-400 text-sm py-4">Tidak ada antrian</p>
                    @endforelse
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection
