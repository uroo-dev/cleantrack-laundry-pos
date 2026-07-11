@extends('layouts.admin')

@section('title', 'Detail Transaksi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.transaksi.index') }}">Transaksi</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-8">
            <div class="box p-5 intro-x">
                <div class="flex items-center border-b border-slate-200 pb-3">
                    <h2 class="text-lg font-medium">Detail Transaksi</h2>
                    <span class="ml-auto text-slate-500 font-mono">{{ $transaksi->kode_transaksi }}</span>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <span class="text-slate-500 text-xs">Pelanggan</span>
                        <p class="font-medium">{{ $transaksi->pelanggan->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 text-xs">Telepon</span>
                        <p class="font-medium">{{ $transaksi->pelanggan->telepon ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 text-xs">Layanan</span>
                        <p class="font-medium">{{ $transaksi->layanan->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 text-xs">Berat</span>
                        <p class="font-medium">{{ $transaksi->berat }} kg</p>
                    </div>
                    <div>
                        <span class="text-slate-500 text-xs">Harga</span>
                        <p class="font-medium">Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 text-xs">Diskon</span>
                        <p class="font-medium">Rp {{ number_format($transaksi->diskon ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 text-xs">Total</span>
                        <p class="font-medium text-lg text-primary">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 text-xs">Status</span>
                        @php
                            $badge = match($transaksi->status) {
                                'menunggu' => 'bg-warning/20 text-warning',
                                'dicuci' => 'bg-info/20 text-info',
                                'dikeringkan' => 'bg-purple-200/20 text-purple-700',
                                'disetrika' => 'bg-orange-200/20 text-orange-700',
                                'selesai' => 'bg-success/20 text-success',
                                'diambil' => 'bg-slate-300/20 text-slate-600',
                                default => 'bg-slate-100 text-slate-600'
                            };
                        @endphp
                        <p><span class="px-2 py-1 rounded text-xs font-medium {{ $badge }}">{{ ucfirst($transaksi->status) }}</span></p>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-500 text-xs">Tanggal Masuk</span>
                        <p class="font-medium">{{ $transaksi->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($transaksi->estimasi_selesai)
                    <div class="col-span-2">
                        <span class="text-slate-500 text-xs">Estimasi Selesai</span>
                        <p class="font-medium">{{ $transaksi->estimasi_selesai->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                    @if($transaksi->tanggal_ambil)
                    <div class="col-span-2">
                        <span class="text-slate-500 text-xs">Tanggal Diambil</span>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($transaksi->tanggal_ambil)->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                    @if($transaksi->catatan)
                    <div class="col-span-2">
                        <span class="text-slate-500 text-xs">Catatan</span>
                        <p class="font-medium">{{ $transaksi->catatan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($transaksi->detailLaundries->count())
            <div class="box p-5 mt-5 intro-x">
                <h3 class="text-base font-medium border-b border-slate-200 pb-3">Items Laundry</h3>
                <div class="overflow-x-auto mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Item</th>
                                <th>Jumlah</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi->detailLaundries as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>{{ $item->catatan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="box p-5 mt-5 intro-x">
                <h3 class="text-base font-medium border-b border-slate-200 pb-3">Tracking Timeline</h3>
                <div class="mt-4">
                    @php
                        $steps = ['menunggu', 'dicuci', 'dikeringkan', 'disetrika', 'selesai', 'diambil'];
                        $statusIndex = array_search($transaksi->status, $steps);
                    @endphp
                    <ul class="timeline">
                        @foreach($steps as $i => $step)
                        <li class="timeline-item {{ $i <= $statusIndex ? 'active' : '' }}">
                            <div class="timeline-item__node"></div>
                            <div class="timeline-item__content">
                                <span class="text-slate-500 text-xs">{{ $transaksi->updated_at->format('d/m/Y H:i') }}</span>
                                <p class="font-medium">{{ ucfirst($step) }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4">
            <div class="box p-5 intro-x">
                <h3 class="text-base font-medium border-b border-slate-200 pb-3">Pembayaran</h3>
                <div class="mt-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Subtotal</span>
                        <span>Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Diskon</span>
                        <span>Rp {{ number_format($transaksi->diskon ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span class="text-primary">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                    </div>
                    @if($transaksi->pembayaran)
                    <hr>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Status Bayar</span>
                        <span class="{{ $transaksi->pembayaran->status === 'lunas' ? 'text-success' : 'text-warning' }}">
                            {{ ucfirst($transaksi->pembayaran->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Metode</span>
                        <span>{{ $transaksi->pembayaran->metode_pembayaran ?? '-' }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="box p-5 mt-5 intro-x">
                <h3 class="text-base font-medium border-b border-slate-200 pb-3">Aksi</h3>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('staff.order.nota', $transaksi->id) }}" class="btn btn-secondary w-full" target="_blank">
                        <i data-lucide="printer" class="w-4 h-4 mr-1"></i> Cetak Nota
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
