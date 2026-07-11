@extends('layouts.admin')

@section('title', 'Detail Transaksi')
@section('page-title', $transaksi->invoice)
@section('page-description', 'Detail transaksi laundry')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
    {{-- Left Column --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Invoice Header --}}
        <div class="glass-card rounded-2xl p-6 soft-shadow">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-headline-lg font-bold text-primary">{{ $transaksi->invoice }}</h1>
                    <p class="text-body-sm text-on-surface-variant mt-1">Dibuat {{ $transaksi->created_at->format('d M Y H:i') }}</p>
                    <p class="text-body-sm text-on-surface-variant">Oleh {{ $transaksi->user->name ?? $transaksi->created_by ?? 'N/A' }}</p>
                </div>
                <div class="text-right">
                    @php
                        $statusColors = ['pending' => 'bg-warning/10 text-yellow-700', 'proses' => 'bg-blue-50 text-blue-700', 'selesai' => 'bg-green-50 text-green-700', 'diambil' => 'bg-surface-container-high text-on-surface-variant'];
                        $payColors = ['lunas' => 'bg-green-50 text-green-700', 'belum' => 'bg-yellow-50 text-yellow-700', 'dp' => 'bg-blue-50 text-blue-700'];
                    @endphp
                    <span class="text-label-sm px-4 py-1.5 rounded-full {{ $statusColors[$transaksi->status] ?? 'bg-surface-container-high' }}">{{ ucfirst($transaksi->status) }}</span>
                    <br>
                    <span class="text-label-sm px-4 py-1.5 rounded-full inline-block mt-2 {{ $payColors[$transaksi->status_pembayaran] ?? 'bg-surface-container-high' }}">
                        {{ $transaksi->status_pembayaran === 'lunas' ? 'Lunas' : ($transaksi->status_pembayaran === 'dp' ? 'DP ('.number_format($transaksi->dp ?? 0,0,',','.').')' : 'Belum Lunas') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Customer Info & Outlet --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="glass-card rounded-2xl p-6 soft-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <span class="material-symbols-outlined text-primary">person</span>
                    <h3 class="text-headline-sm">Informasi Pelanggan</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-label-sm text-on-surface-variant">Nama</p>
                        <p class="text-body-sm font-bold">{{ $transaksi->pelanggan->nama ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant">Telepon</p>
                        @php $customerWa = $transaksi->pelanggan->telepon ? \App\Services\WhatsAppService::generateLink($transaksi->pelanggan->telepon) : '#'; @endphp
                        <a href="{{ $customerWa }}" target="_blank" class="text-body-sm text-primary font-bold flex items-center gap-1 hover:underline">
                            <span class="material-symbols-outlined text-sm">chat</span>
                            {{ $transaksi->pelanggan->telepon ?? '-' }}
                        </a>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant">Alamat</p>
                        <p class="text-body-sm text-on-surface-variant">{{ $transaksi->pelanggan->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="glass-card rounded-2xl p-6 soft-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <span class="material-symbols-outlined text-secondary">store</span>
                    <h3 class="text-headline-sm">Informasi Outlet</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-label-sm text-on-surface-variant">Outlet</p>
                        <p class="text-body-sm font-bold">{{ $transaksi->outlet ?? 'Utama' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant">Estimasi Selesai</p>
                        <p class="text-body-sm font-bold">{{ $transaksi->estimasi_selesai ? \Carbon\Carbon::parse($transaksi->estimasi_selesai)->format('d M Y H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant">Catatan</p>
                        <p class="text-body-sm text-on-surface-variant">{{ $transaksi->catatan ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Items --}}
        <div class="glass-card rounded-2xl p-6 soft-shadow">
            <h3 class="text-headline-sm mb-4">Detail Order</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-label-sm text-on-surface-variant border-b border-outline-variant/30">
                            <th class="text-left py-3 px-2">Layanan</th>
                            <th class="text-left py-3 px-2">Berat (Kg)</th>
                            <th class="text-left py-3 px-2">Harga/Kg</th>
                            <th class="text-right py-3 px-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $subtotalItems = ($transaksi->berat ?? 0) * ($transaksi->harga ?? 0); @endphp
                        <tr class="border-b border-outline-variant/20">
                            <td class="py-3 px-2 text-body-sm font-bold">{{ $transaksi->layanan->nama ?? 'N/A' }}</td>
                            <td class="py-3 px-2 text-body-sm">{{ number_format($transaksi->berat ?? 0, 1) }}</td>
                            <td class="py-3 px-2 text-body-sm">Rp {{ number_format($transaksi->harga ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3 px-2 text-body-sm font-bold text-right">Rp {{ number_format($subtotalItems, 0, ',', '.') }}</td>
                        </tr>
                        @if(($transaksi->detailLaundries ?? collect())->isNotEmpty())
                        <tr><td colspan="4" class="py-3 px-2">
                            <div class="border-t border-outline-variant/20 pt-3">
                                <p class="text-label-sm text-on-surface-variant mb-2">Detail Item:</p>
                                @foreach($transaksi->detailLaundries as $d)
                                <div class="flex items-center justify-between py-1">
                                    <span class="text-body-sm">{{ $d->nama_barang }} <span class="text-on-surface-variant">x{{ $d->jumlah }}</span></span>
                                    @if($d->catatan)<span class="text-label-sm text-on-surface-variant">{{ $d->catatan }}</span>@endif
                                </div>
                                @endforeach
                            </div>
                        </td></tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Summary --}}
            <div class="mt-4 pt-4 border-t border-outline-variant/20 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-body-sm text-on-surface-variant">Subtotal</span>
                    <span class="text-body-sm">Rp {{ number_format($subtotalItems, 0, ',', '.') }}</span>
                </div>
                @if($transaksi->diskon ?? false)
                <div class="flex items-center justify-between">
                    <span class="text-body-sm text-on-surface-variant">Diskon</span>
                    <span class="text-body-sm text-error">-Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($transaksi->pajak ?? false)
                <div class="flex items-center justify-between">
                    <span class="text-body-sm text-on-surface-variant">Pajak</span>
                    <span class="text-body-sm">Rp {{ number_format($transaksi->pajak, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex items-center justify-between pt-2 border-t border-outline-variant/30">
                    <span class="text-headline-sm">Total</span>
                    <span class="text-headline-sm font-bold text-primary">Rp {{ number_format($transaksi->total ?? $subtotalItems, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="space-y-6">
        {{-- QR Code --}}
        <div class="glass-card rounded-2xl p-6 soft-shadow text-center">
            <div class="inline-flex items-center justify-center gap-2 mb-4">
                <span class="material-symbols-outlined text-primary">qr_code_scanner</span>
                <h3 class="text-headline-sm">QR Code</h3>
            </div>
            <div class="w-40 h-40 mx-auto bg-surface-container-high rounded-2xl flex items-center justify-center">
                {!! $transaksi->qr_code ?? '<span class="material-symbols-outlined text-5xl text-on-surface-variant opacity-30">qr_code</span>' !!}
            </div>
            <p class="text-label-sm text-on-surface-variant mt-3">Scan untuk tracking</p>
        </div>

        {{-- Actions --}}
        <div class="glass-card rounded-2xl p-6 soft-shadow">
            <h3 class="text-headline-sm mb-4">Aksi</h3>
            <div class="space-y-3">
                @if($transaksi->status === 'pending')
                <form action="{{ route('admin.transaksi.update-status', $transaksi->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="proses">
                    <button type="submit" class="w-full bg-secondary text-white py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:scale-[1.02] transition-all">
                        <span class="material-symbols-outlined text-sm">play_arrow</span> Proses Cucian
                    </button>
                </form>
                @endif
                @if($transaksi->status === 'proses')
                <form action="{{ route('admin.transaksi.update-status', $transaksi->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="selesai">
                    <button type="submit" class="w-full bg-tertiary text-white py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:scale-[1.02] transition-all">
                        <span class="material-symbols-outlined text-sm">check_circle</span> Tandai Selesai
                    </button>
                </form>
                @endif
                @if($transaksi->status === 'selesai')
                <form action="{{ route('admin.transaksi.update-status', $transaksi->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="diambil">
                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:scale-[1.02] transition-all">
                        <span class="material-symbols-outlined text-sm">handshake</span> Tandai Diambil
                    </button>
                </form>
                @endif
                @if($transaksi->status_pembayaran !== 'lunas')
                <form action="{{ route('admin.transaksi.update-payment', $transaksi->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status_pembayaran" value="lunas">
                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:scale-[1.02] transition-all">
                        <span class="material-symbols-outlined text-sm">payments</span> Tandai Lunas
                    </button>
                </form>
                @endif
                <button onclick="window.print()" class="w-full bg-surface-container-high py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-surface-container-highest transition-all">
                    <span class="material-symbols-outlined text-sm">print</span> Print
                </button>
                @php $notifWa = $transaksi->pelanggan->telepon ? \App\Services\WhatsAppService::generateLink($transaksi->pelanggan->telepon, 'Halo ' . ($transaksi->pelanggan->nama ?? '') . ', laundry Anda dengan invoice ' . $transaksi->invoice . ' sudah selesai. Silahkan diambil di outlet kami. Terima kasih!') : '#'; @endphp
                <a href="{{ $notifWa }}" target="_blank" class="w-full bg-tertiary text-white py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:scale-[1.02] transition-all">
                    <span class="material-symbols-outlined text-sm">chat</span> Notifikasi WA
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
@if($transaksi->status_pembayaran !== 'lunas' && session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}'
});
@endif
</script>
@endpush
