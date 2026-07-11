@extends('layouts.public')

@section('title', 'Lacak Status Cucian')

@section('content')
{{-- Desktop Top Bar --}}
<header class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-xl border-b border-white/30 shadow-sm">
    <div class="desktop-wrapper flex justify-between items-center px-margin-mobile lg:px-0 h-16">
        <a href="{{ route('home') }}" class="font-headline-md text-headline-md font-bold text-primary">{{ $settings->nama_app ?? 'LaundryHub' }}</a>
        <div class="flex items-center gap-4">
            @php $waTelp = $settings->telepon ?? ''; @endphp
            <a href="{{ $waTelp ? \App\Services\WhatsAppService::generateLink($waTelp) : '#' }}" target="_blank" class="material-symbols-outlined text-on-surface-variant p-2 hover:bg-primary/5 rounded-full transition-colors active:scale-95 duration-200">notifications</a>
            <a href="{{ route('login') }}" class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container overflow-hidden">
                <span class="material-symbols-outlined text-xl">person</span>
            </a>
        </div>
    </div>
</header>

{{-- Desktop Nav --}}
<div class="desktop-only fixed top-16 left-0 w-full z-40 bg-surface/60 backdrop-blur-md border-b border-outline-variant/20">
    <div class="desktop-wrapper flex items-center gap-8 px-margin-mobile lg:px-0 h-14">
        <a href="{{ route('home') }}" class="text-label-lg text-on-surface-variant hover:text-primary transition-colors">Beranda</a>
        <a href="{{ route('tracking') }}" class="text-label-lg text-primary font-bold">Lacak</a>
        <a href="{{ route('login') }}" class="text-label-lg text-on-surface-variant hover:text-primary transition-colors ml-auto">Login</a>
    </div>
</div>

<main class="pt-16 pb-24 lg:pb-16">
    {{-- Mobile Header --}}
    <div class="mobile-only px-margin-mobile pt-6 pb-4">
        <h1 class="font-headline-lg-mobile text-headline-lg-mobile text-primary">Lacak Status Cucian Anda</h1>
    </div>

    {{-- Search Section --}}
    <section class="px-margin-mobile lg:pt-10">
        <div class="desktop-wrapper">
            <div class="max-w-2xl mx-auto">
                <h1 class="desktop-only font-headline-lg text-headline-lg text-primary mb-2 text-center">Lacak Status Cucian Anda</h1>
                <p class="desktop-only text-body-md text-on-surface-variant mb-8 text-center">Masukkan nomor invoice untuk mengetahui posisi cucian Anda</p>
                <form method="GET" action="{{ route('tracking') }}" class="glass-card p-2 rounded-xl flex items-center gap-2 shadow-sm border border-outline-variant/30">
                    <span class="material-symbols-outlined text-outline pl-2 flex-shrink-0">receipt_long</span>
                    <input name="kode" value="{{ request('kode') }}" class="flex-1 bg-transparent border-none focus:ring-0 text-body-md placeholder:text-outline min-w-0" placeholder="Masukkan No. Invoice (cth: INV-20260711-0001)" type="text">
                    <button type="submit" class="bg-primary text-on-primary p-3 rounded-lg flex items-center justify-center active:scale-95 transition-transform flex-shrink-0">
                        <span class="material-symbols-outlined">search</span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    @if(isset($transaksi))
    {{-- Desktop: Two Column Layout --}}
    <div class="desktop-wrapper px-margin-mobile lg:px-0 mt-8">
        <div class="lg:grid lg:grid-cols-5 lg:gap-8">

            {{-- Left Column: Summary + Progress Timeline --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Summary Card --}}
                <div class="glass-card p-md rounded-xl shadow-sm space-y-4 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4">
                        @php
                            $statusBadge = match($transaksi->status) {
                                'menunggu' => 'bg-amber-50 text-amber-700',
                                'dicuci', 'dikeringkan', 'disetrika' => 'bg-sky-50 text-sky-700',
                                'selesai' => 'bg-green-50 text-green-700',
                                'diambil' => 'bg-slate-100 text-slate-600',
                                default => 'bg-surface-container text-on-surface-variant'
                            };
                        @endphp
                        <span class="{{ $statusBadge }} text-label-sm px-3 py-1 rounded-full font-bold uppercase">{{ $transaksi->status }}</span>
                    </div>
                    <div class="space-y-1">
                        <p class="text-on-surface-variant text-label-sm uppercase tracking-wider">Nama Pelanggan</p>
                        <p class="font-headline-sm text-headline-sm text-on-surface">{{ $transaksi->pelanggan->nama ?? 'N/A' }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4 border-t border-outline-variant/20 pt-4">
                        <div>
                            <p class="text-on-surface-variant text-label-sm">No. Invoice</p>
                            <p class="font-bold text-primary">{{ $transaksi->kode_transaksi }}</p>
                        </div>
                        <div>
                            <p class="text-on-surface-variant text-label-sm">Estimasi Selesai</p>
                            <p class="font-bold text-on-surface">{{ $transaksi->estimasi_selesai ? $transaksi->estimasi_selesai->format('d M, H:i') : '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Mobile: Horizontal Progress Tracker --}}
                @php
                    $steps = [
                        ['status' => 'menunggu', 'label' => 'Diterima', 'icon' => 'inbox'],
                        ['status' => 'dicuci', 'label' => 'Dicuci', 'icon' => 'local_laundry_service'],
                        ['status' => 'dikeringkan', 'label' => 'Dikeringkan', 'icon' => 'air'],
                        ['status' => 'disetrika', 'label' => 'Disetrika', 'icon' => 'iron'],
                        ['status' => 'selesai', 'label' => 'QC', 'icon' => 'verified'],
                    ];
                    $statusOrder = array_column($steps, 'status');
                    $currentIndex = array_search($transaksi->status, $statusOrder);
                    if ($currentIndex === false) $currentIndex = 0;
                @endphp

                {{-- Mobile Progress --}}
                <section class="mobile-only space-y-4">
                    <h2 class="text-label-lg text-on-surface-variant px-1 uppercase tracking-widest">Progress Pengerjaan</h2>
                    <div class="glass-card p-md rounded-xl overflow-hidden">
                        <div class="relative flex justify-between items-start mb-2 pt-2 overflow-x-auto no-scrollbar pb-6">
                            <div class="absolute top-6 left-0 w-full h-[2px] bg-outline-variant/30"></div>
                            @foreach($steps as $i => $step)
                            <div class="flex flex-col items-center gap-3 relative min-w-[80px]">
                                @if($i < $currentIndex)
                                <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center z-10 shadow-md">
                                    <span class="material-symbols-outlined text-[18px]">check</span>
                                </div>
                                @elseif($i == $currentIndex)
                                <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center z-10 shadow-lg ring-4 ring-primary/20">
                                    <span class="material-symbols-outlined text-[20px]">{{ $step['icon'] }}</span>
                                </div>
                                @else
                                <div class="w-8 h-8 rounded-full bg-surface-container-highest text-outline flex items-center justify-center z-10">
                                    <span class="material-symbols-outlined text-[18px]">{{ $step['icon'] }}</span>
                                </div>
                                @endif
                                <span class="text-label-sm {{ $i <= $currentIndex ? 'text-on-surface font-semibold' : 'text-outline' }}">{{ $step['label'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                {{-- Desktop: Vertical Timeline --}}
                <section class="desktop-only">
                    <h2 class="font-headline-sm text-primary mb-6">Progress Pengerjaan</h2>
                    <div class="relative pl-10">
                        <div class="absolute left-[19px] top-2 bottom-2 w-[2px] bg-outline-variant/40"></div>
                        @foreach($steps as $i => $step)
                        @php
                            $isCompleted = $i < $currentIndex;
                            $isCurrent = $i == $currentIndex;
                            $isPending = $i > $currentIndex;
                        @endphp
                        <div class="relative pb-8 last:pb-0">
                            <div class="absolute left-[-26px] top-0 w-10 h-10 rounded-full flex items-center justify-center z-10
                                {{ $isCompleted ? 'bg-primary text-on-primary shadow-md' : ($isCurrent ? 'bg-primary text-on-primary shadow-lg ring-4 ring-primary/20' : 'bg-surface-container-highest text-outline') }}">
                                @if($isCompleted)
                                <span class="material-symbols-outlined text-[20px]">check</span>
                                @else
                                <span class="material-symbols-outlined text-[20px]">{{ $step['icon'] }}</span>
                                @endif
                            </div>
                            <div class="glass-card rounded-xl p-4 {{ $isCurrent ? 'border-l-4 border-l-primary shadow-md' : 'border border-outline-variant/20' }}">
                                <p class="font-bold text-on-surface {{ $isPending ? 'opacity-50' : '' }}">{{ $step['label'] }}</p>
                                @if($isCurrent)
                                <p class="text-body-sm text-primary mt-1">Sedang dalam proses ini</p>
                                @elseif($isCompleted)
                                <p class="text-body-sm text-tertiary mt-1">Selesai</p>
                                @else
                                <p class="text-body-sm text-outline mt-1">Menunggu</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>

                {{-- Timeline History (Desktop) --}}
                @if($transaksi->relationLoaded('trackings') && $transaksi->trackings->count() > 1)
                <section class="desktop-only glass-card rounded-xl p-md space-y-4">
                    <h3 class="font-label-lg text-on-surface font-bold">Riwayat Status</h3>
                    <div class="space-y-3">
                        @foreach($transaksi->trackings->sortByDesc('waktu') as $log)
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 rounded-full bg-primary mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-label-sm font-bold text-on-surface capitalize">{{ $log->status }}</p>
                                <p class="text-body-sm text-on-surface-variant">{{ $log->keterangan }}</p>
                                <p class="text-label-sm text-outline">{{ $log->waktu ? $log->waktu->format('d M Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif
            </div>

            {{-- Right Column: Details + Notification --}}
            <div class="lg:col-span-2 space-y-6 mt-6 lg:mt-0">
                {{-- Notification --}}
                @if(in_array($transaksi->status, ['dicuci', 'dikeringkan', 'disetrika']))
                <div class="bg-primary-container text-on-primary-container p-4 rounded-xl flex items-center gap-4 shadow-sm">
                    <div class="bg-white/20 p-2 rounded-lg flex-shrink-0">
                        <span class="material-symbols-outlined">schedule</span>
                    </div>
                    <div>
                        <p class="text-label-lg">Pakaian Anda sedang dalam proses. Kami akan mengirimkan notifikasi saat sudah siap diambil.</p>
                    </div>
                </div>
                @elseif($transaksi->status === 'selesai')
                <div class="bg-tertiary-fixed text-on-tertiary-fixed-variant p-4 rounded-xl flex items-center gap-4 shadow-sm">
                    <div class="bg-white/20 p-2 rounded-lg flex-shrink-0">
                        <span class="material-symbols-outlined">check_circle</span>
                    </div>
                    <div>
                        <p class="text-label-lg font-bold">Pakaian Anda sudah siap diambil!</p>
                        @if($transaksi->pelanggan->telepon ?? false)
                        <a href="{{ \App\Services\WhatsAppService::generateLink($transaksi->pelanggan->telepon, 'Halo, saya ingin mengambil cucian dengan invoice ' . $transaksi->kode_transaksi) }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 mt-2 text-label-sm font-bold text-on-primary-container bg-white/20 px-3 py-1.5 rounded-lg hover:bg-white/30 transition-all">
                            <span class="material-symbols-outlined text-[16px]">chat</span> Konfirmasi via WA
                        </a>
                        @endif
                    </div>
                </div>
                @elseif($transaksi->status === 'menunggu' && $transaksi->pelanggan->telepon ?? false)
                <div class="bg-amber-50 text-amber-800 p-4 rounded-xl flex items-center gap-4 shadow-sm">
                    <div class="bg-white/20 p-2 rounded-lg flex-shrink-0">
                        <span class="material-symbols-outlined">hourglass_empty</span>
                    </div>
                    <div>
                        <p class="text-label-lg font-bold">Pesanan diterima, menunggu antrian</p>
                        <p class="text-body-sm mt-1">Kami akan segera memproses pesanan Anda.</p>
                    </div>
                </div>
                @endif

                {{-- Order Details --}}
                <section class="space-y-3">
                    <h2 class="text-label-lg text-on-surface-variant uppercase tracking-widest desktop-only">Detail Pesanan</h2>
                    <div class="glass-card p-md rounded-xl space-y-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="p-2 bg-secondary/10 rounded-lg text-secondary flex-shrink-0">
                                    <span class="material-symbols-outlined">inventory_2</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-on-surface truncate">{{ $transaksi->layanan->nama ?? 'N/A' }}</p>
                                    <p class="text-body-sm text-on-surface-variant">Berat: {{ $transaksi->berat }} Kg</p>
                                </div>
                            </div>
                            @php
                                $payStatus = $transaksi->status_pembayaran ?? ($transaksi->pembayaran?->status ?? 'belum');
                                $payBadge = match($payStatus) {
                                    'lunas' => 'bg-green-50 text-green-700 border-green-200',
                                    'dp' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    default => 'bg-red-50 text-red-700 border-red-200'
                                };
                            @endphp
                            <span class="text-[10px] font-bold px-2 py-1 rounded border uppercase flex-shrink-0 ml-2 {{ $payBadge }}">{{ $payStatus }}</span>
                        </div>

                        @if($transaksi->relationLoaded('detailLaundries') && $transaksi->detailLaundries->count() > 0)
                        <div class="border-t border-outline-variant/20 pt-4">
                            <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Item Laundry</p>
                            <div class="space-y-2">
                                @foreach($transaksi->detailLaundries as $item)
                                <div class="flex justify-between text-body-sm">
                                    <span class="text-on-surface">{{ $item->nama_barang }} <span class="text-outline">x{{ $item->jumlah }}</span></span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="space-y-2 border-t border-outline-variant/20 pt-4">
                            <div class="flex justify-between text-body-sm text-on-surface-variant">
                                <span>Biaya Cuci</span>
                                <span>Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</span>
                            </div>
                            @if(($transaksi->diskon ?? 0) > 0)
                            <div class="flex justify-between text-body-sm text-on-surface-variant">
                                <span>Diskon</span>
                                <span class="text-error">-Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between font-bold text-on-surface pt-2 border-t border-dashed border-outline-variant/30">
                                <span>Total Pembayaran</span>
                                <span class="text-primary">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Action Buttons --}}
                @if($transaksi->pelanggan->telepon ?? false)
                <div class="flex gap-3">
                    <a href="{{ \App\Services\WhatsAppService::generateLink($transaksi->pelanggan->telepon, 'Halo, saya mau tanya soal cucian dengan invoice ' . $transaksi->kode_transaksi) }}"
                       target="_blank"
                       class="flex-1 flex items-center justify-center gap-2 py-3 bg-[#25D366] text-white rounded-xl font-bold hover:opacity-90 transition-all active:scale-[0.98]">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.539 2.016 2.041-.536c1.047.58 1.954.922 3.247.923 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766"/></svg>
                        <span class="text-label-lg">Chat WA</span>
                    </a>
                    <a href="tel:{{ preg_replace('/[^0-9]/', '', $settings->telepon ?? '') }}"
                       class="flex-1 flex items-center justify-center gap-2 py-3 bg-primary text-on-primary rounded-xl font-bold hover:opacity-90 transition-all active:scale-[0.98]">
                        <span class="material-symbols-outlined">call</span>
                        <span class="text-label-lg">Telepon</span>
                    </a>
                </div>
                @endif

                {{-- Tracking History (Mobile) --}}
                @if($transaksi->relationLoaded('trackings') && $transaksi->trackings->count() > 1)
                <section class="mobile-only glass-card rounded-xl p-md space-y-4">
                    <h3 class="font-label-lg text-on-surface font-bold">Riwayat Status</h3>
                    <div class="space-y-3">
                        @foreach($transaksi->trackings->sortByDesc('waktu') as $log)
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 rounded-full bg-primary mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-label-sm font-bold text-on-surface capitalize">{{ $log->status }}</p>
                                <p class="text-body-sm text-on-surface-variant">{{ $log->keterangan }}</p>
                                <p class="text-label-sm text-outline">{{ $log->waktu ? $log->waktu->format('d M Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif
            </div>

        </div>
    </div>

    @elseif(request('kode'))
    {{-- Not Found --}}
    <div class="desktop-wrapper px-margin-mobile lg:px-0 mt-8">
        <div class="max-w-md mx-auto">
            <div class="glass-card p-md rounded-xl text-center py-12">
                <span class="material-symbols-outlined text-5xl text-outline">search_off</span>
                <p class="text-body-md text-on-surface-variant mt-4">Transaksi dengan kode "{{ request('kode') }}" tidak ditemukan.</p>
                <p class="text-label-sm text-outline mt-1">Periksa kembali kode invoice Anda.</p>
            </div>
        </div>
    </div>
    @endif
</main>

{{-- Mobile Bottom Navigation --}}
<nav class="mobile-only fixed bottom-0 w-full z-50 rounded-t-xl bg-surface/80 backdrop-blur-xl border-t border-white/30 shadow-lg flex items-center h-20 pb-safe px-2 justify-center gap-12">
    <a href="{{ route('home') }}" class="flex flex-col items-center justify-center text-on-surface-variant hover:opacity-80 transition-opacity active:scale-90 duration-200">
        <span class="material-symbols-outlined">home</span>
        <span class="font-label-sm text-label-sm">Beranda</span>
    </a>
    <a href="{{ route('tracking') }}" class="flex flex-col items-center justify-center bg-secondary-container text-on-secondary-container rounded-full px-4 py-1 active:scale-90 duration-200">
        <span class="material-symbols-outlined">local_shipping</span>
        <span class="font-label-sm text-label-sm">Lacak</span>
    </a>
</nav>

{{-- Desktop Footer --}}
<footer class="desktop-only bg-surface-container-low border-t border-outline-variant/20 py-12">
    <div class="desktop-wrapper px-margin-mobile lg:px-0">
        <div class="grid grid-cols-4 gap-8">
            <div class="col-span-2">
                <h3 class="font-headline-sm text-primary font-bold mb-3">{{ $settings->nama_app ?? 'LaundryHub' }}</h3>
                <p class="text-body-sm text-on-surface-variant max-w-sm">{{ $settings->deskripsi ?? 'Nikmati kemudahan mencuci baju dengan layanan premium.' }}</p>
            </div>
            <div>
                <h4 class="font-label-lg text-on-surface font-bold mb-3">Menu</h4>
                <div class="space-y-2">
                    <a href="{{ route('home') }}" class="block text-body-sm text-on-surface-variant hover:text-primary">Beranda</a>
                    <a href="{{ route('tracking') }}" class="block text-body-sm text-on-surface-variant hover:text-primary">Lacak</a>
                    <a href="{{ route('login') }}" class="block text-body-sm text-on-surface-variant hover:text-primary">Login</a>
                </div>
            </div>
            <div>
                <h4 class="font-label-lg text-on-surface font-bold mb-3">Kontak</h4>
                <div class="space-y-2">
                    <p class="text-body-sm text-on-surface-variant">{{ $settings->telepon ?? '-' }}</p>
                    <p class="text-body-sm text-on-surface-variant">{{ $settings->email ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="border-t border-outline-variant/20 mt-8 pt-8 text-center text-label-sm text-on-surface-variant">
            &copy; {{ date('Y') }} {{ $settings->nama_app ?? 'LaundryHub' }}. All rights reserved.
        </div>
    </div>
</footer>

{{-- Mobile Floating WA (only with results) --}}
@if(isset($transaksi) && ($settings->telepon ?? false))
<div class="mobile-only fixed bottom-24 right-margin-mobile flex flex-col gap-3">
    <a href="{{ \App\Services\WhatsAppService::generateLink($settings->telepon) }}" target="_blank" class="w-14 h-14 bg-[#25D366] text-white rounded-full shadow-lg flex items-center justify-center active:scale-95 transition-transform">
        <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.539 2.016 2.041-.536c1.047.58 1.954.922 3.247.923 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766"/></svg>
    </a>
    <a href="tel:{{ preg_replace('/[^0-9]/', '', $settings->telepon ?? '') }}" class="w-14 h-14 bg-primary text-white rounded-full shadow-lg flex items-center justify-center active:scale-95 transition-transform">
        <span class="material-symbols-outlined">call</span>
    </a>
</div>
@endif
@endsection
