@extends('layouts.public')

@section('title', 'Lacak Status Cucian')

@section('content')
{{-- Top AppBar --}}
<header class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-xl border-b border-white/30 shadow-sm flex justify-between items-center px-margin-mobile h-16">
    <a href="{{ route('home') }}" class="font-headline-md text-headline-md font-bold text-primary">{{ $settings->nama_app ?? 'LaundryHub' }}</a>
    <div class="flex items-center gap-4">
        <span class="material-symbols-outlined text-primary hover:bg-primary/5 p-2 rounded-full transition-colors active:scale-95 duration-200">notifications</span>
        <a href="{{ route('login') }}" class="w-8 h-8 rounded-full bg-primary-fixed overflow-hidden flex items-center justify-center">
            <span class="material-symbols-outlined text-sm text-primary">person</span>
        </a>
    </div>
</header>

<main class="pt-24 px-margin-mobile space-y-6 max-w-md mx-auto pb-32">
    {{-- Hero: Search Section --}}
    <section class="space-y-4">
        <h1 class="font-headline-lg-mobile text-headline-lg-mobile text-primary">Lacak Status Cucian Anda</h1>
        <form method="GET" action="{{ route('tracking') }}" class="glass-card p-2 rounded-xl flex items-center gap-2 shadow-sm border border-outline-variant/30">
            <span class="material-symbols-outlined text-outline pl-2">receipt_long</span>
            <input name="kode" value="{{ request('kode') }}" class="flex-1 bg-transparent border-none focus:ring-0 text-body-md placeholder:text-outline" placeholder="Masukkan No. Invoice" type="text">
            <button type="submit" class="bg-primary text-on-primary p-3 rounded-lg flex items-center justify-center active:scale-95 transition-transform">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
    </section>

    @if(isset($transaksi))
    {{-- Summary Card --}}
    <div class="glass-card p-md rounded-xl shadow-sm space-y-4 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4">
            <span class="bg-secondary-container text-on-secondary-container text-label-sm px-3 py-1 rounded-full font-bold uppercase">{{ $transaksi->status }}</span>
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

    {{-- Live Progress Tracker --}}
    @php
        $steps = [
            ['status' => 'menunggu', 'label' => 'Diterima', 'icon' => 'inbox'],
            ['status' => 'dicuci', 'label' => 'Dicuci', 'icon' => 'local_laundry_service'],
            ['status' => 'dikeringkan', 'label' => 'Dikeringkan', 'icon' => 'dry_cleaning'],
            ['status' => 'disetrika', 'label' => 'Disetrika', 'icon' => 'iron'],
            ['status' => 'selesai', 'label' => 'QC', 'icon' => 'verified'],
        ];
        $statusOrder = array_column($steps, 'status');
        $currentIndex = array_search($transaksi->status, $statusOrder);
        if ($currentIndex === false) $currentIndex = 0;
    @endphp

    <section class="space-y-4">
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

    {{-- Notification / Reminder --}}
    @if(in_array($transaksi->status, ['dicuci', 'dikeringkan', 'disetrika']))
    <div class="bg-primary-container text-on-primary-container p-4 rounded-xl flex items-center gap-4 shadow-sm">
        <div class="bg-white/20 p-2 rounded-lg">
            <span class="material-symbols-outlined">schedule</span>
        </div>
        <div>
            <p class="text-label-lg">Pakaian Anda sedang dalam proses. Kami akan mengirimkan notifikasi saat sudah siap diambil.</p>
        </div>
    </div>
    @elseif($transaksi->status === 'selesai')
    <div class="bg-tertiary-fixed text-on-tertiary-fixed-variant p-4 rounded-xl flex items-center gap-4 shadow-sm">
        <div class="bg-white/20 p-2 rounded-lg">
            <span class="material-symbols-outlined">check_circle</span>
        </div>
        <div>
            <p class="text-label-lg font-bold">Pakaian Anda sudah siap diambil!</p>
        </div>
    </div>
    @endif

    {{-- Detail Card --}}
    <section class="space-y-3">
        <h2 class="text-label-lg text-on-surface-variant px-1 uppercase tracking-widest">Detail Pesanan</h2>
        <div class="glass-card p-md rounded-xl space-y-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-secondary/10 rounded-lg text-secondary">
                        <span class="material-symbols-outlined">inventory_2</span>
                    </div>
                    <div>
                        <p class="font-bold text-on-surface">{{ $transaksi->layanan->nama ?? 'N/A' }}</p>
                        <p class="text-body-sm text-on-surface-variant">Berat Total: {{ $transaksi->berat }} Kg</p>
                    </div>
                </div>
                <span class="bg-tertiary/10 text-tertiary text-[10px] font-bold px-2 py-1 rounded border border-tertiary/20 uppercase">{{ $transaksi->pembayaran?->status ?? 'Belum Bayar' }}</span>
            </div>
            <div class="space-y-2 border-t border-outline-variant/20 pt-4">
                <div class="flex justify-between text-body-sm text-on-surface-variant">
                    <span>Biaya Cuci (Rp {{ number_format($transaksi->harga / max($transaksi->berat, 1), 0, ',', '.') }}/Kg)</span>
                    <span>Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</span>
                </div>
                @if($transaksi->diskon > 0)
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
    @elseif(request('kode'))
    {{-- Not Found --}}
    <div class="glass-card p-md rounded-xl text-center py-12">
        <span class="material-symbols-outlined text-5xl text-outline">search_off</span>
        <p class="text-body-md text-on-surface-variant mt-4">Transaksi dengan kode "{{ request('kode') }}" tidak ditemukan.</p>
        <p class="text-label-sm text-outline mt-1">Periksa kembali kode invoice Anda.</p>
    </div>
    @endif
</main>

{{-- Bottom Navigation --}}
<nav class="fixed bottom-0 w-full z-50 rounded-t-xl bg-surface/80 backdrop-blur-xl border-t border-white/30 shadow-lg flex justify-around items-center h-20 pb-safe px-2 max-w-md mx-auto left-0 right-0">
    <a href="{{ route('home') }}" class="flex flex-col items-center justify-center text-on-surface-variant hover:opacity-80 transition-opacity active:scale-90 duration-200">
        <span class="material-symbols-outlined">home</span>
        <span class="font-label-sm text-label-sm">Beranda</span>
    </a>
    <a href="{{ route('tracking') }}" class="flex flex-col items-center justify-center bg-secondary-container text-on-secondary-container rounded-full px-4 py-1 active:scale-90 duration-200">
        <span class="material-symbols-outlined">local_shipping</span>
        <span class="font-label-sm text-label-sm">Lacak</span>
    </a>
</nav>

{{-- Support Buttons --}}
<div class="fixed bottom-24 right-margin-mobile flex flex-col gap-3">
    @if($settings->telepon)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', str_starts_with($settings->telepon, '0') ? '62' . substr($settings->telepon, 1) : $settings->telepon) }}" target="_blank" class="w-14 h-14 bg-[#25D366] text-white rounded-full shadow-lg flex items-center justify-center active:scale-95 transition-transform">
        <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.539 2.016 2.041-.536c1.047.58 1.954.922 3.247.923 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766m6.69 8.391c-.114.34-.544.577-.872.662-.189.051-.435.06-.693.058-.152-.001-.314-.012-.47-.034-1.066-.152-2.1-.611-2.989-1.295-.086-.066-.17-.133-.254-.202-1.021-.83-1.852-1.841-2.39-3.025-.133-.293-.245-.595-.335-.905-.09-.311-.133-.625-.13-.941.004-.337.156-.633.302-.8.058-.066.124-.126.19-.181.066-.055.135-.105.204-.15.152-.1.302-.121.45-.121.147 0 .202.001.303.007.101.006.19.014.27.182.013.027.027.054.042.081.067.12.135.241.204.361.069.12.138.241.202.355.064.114.129.228.182.332.053.104.093.185.02.32-.073.135-.11.202-.185.291-.075.09-.15.18-.225.263-.075.083-.157.171-.073.315.084.144.374.616.804 1.002.086.077.173.15.264.22.43.337.932.583 1.48.74.147.042.27.042.373-.086.103-.128.441-.52.559-.697.118-.177.235-.147.397-.088.162.059 1.029.485 1.206.573.177.088.294.132.338.207.044.075.044.432-.07.772"></path></svg>
    </a>
    @endif
    <a href="tel:{{ preg_replace('/[^0-9]/', '', $settings->telepon ?? '') }}" class="w-14 h-14 bg-primary text-white rounded-full shadow-lg flex items-center justify-center active:scale-95 transition-transform">
        <span class="material-symbols-outlined">call</span>
    </a>
</div>
@endsection
