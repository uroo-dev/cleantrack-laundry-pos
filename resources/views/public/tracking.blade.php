<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracking - {{ $settings->nama_app ?? 'LaundryKu' }}</title>
    <link rel="stylesheet" href="/css/app.css">
    @stack('styles')
</head>
<body class="bg-slate-50">
    <nav class="bg-white shadow-sm border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="text-xl font-bold text-primary">{{ $settings->nama_app ?? 'LaundryKu' }}</span>
                </a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-slate-600 hover:text-primary">Beranda</a>
                    <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Masuk</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto px-4 py-12">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold">Tracking Laundry</h1>
            <p class="text-slate-500 mt-2">Masukkan kode transaksi untuk melihat status</p>
        </div>

        <div class="box p-8">
            <form method="GET" action="{{ route('tracking') }}" class="flex gap-2">
                <input type="text" name="kode" class="form-control flex-1 text-lg text-center" placeholder="Masukkan kode transaksi" value="{{ request('kode') }}" required>
                <button type="submit" class="btn btn-primary px-8">Cari</button>
            </form>
        </div>

        @if(isset($transaksi))
        <div class="box p-6 mt-6">
            <div class="text-center mb-4">
                <h2 class="text-xl font-bold">Order {{ $transaksi->kode_transaksi }}</h2>
            </div>

            @php
                $steps = ['Diterima', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];
                $statusMap = ['menunggu' => 0, 'dicuci' => 1, 'dikeringkan' => 2, 'disetrika' => 3, 'selesai' => 4, 'diambil' => 5];
                $current = $statusMap[$transaksi->status] ?? 0;
                $maxIdx = count($steps) - 1;
                $progressPercent = min(100, ($current / $maxIdx) * 100);
            @endphp

            <div class="w-full bg-slate-200 rounded-full h-3 mb-4">
                <div class="bg-primary h-3 rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
            </div>
            <p class="text-center text-sm text-slate-500 mb-6">{{ $progressPercent }}% Selesai</p>

            <div class="space-y-3 mb-6">
                @foreach($steps as $i => $step)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold {{ $i <= $current ? 'bg-primary text-white' : 'bg-slate-200 text-slate-400' }}">
                        {{ $i <= $current ? '✓' : ($i + 1) }}
                    </div>
                    <span class="{{ $i <= $current ? 'text-primary font-medium' : 'text-slate-400' }}">{{ $step }}</span>
                </div>
                @endforeach
            </div>

            <hr class="mb-4">
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><span class="text-slate-400">Tanggal:</span> {{ $transaksi->created_at->format('d/m/Y H:i') }}</div>
                <div><span class="text-slate-400">Layanan:</span> {{ $transaksi->layanan->nama ?? '-' }}</div>
                <div><span class="text-slate-400">Berat:</span> {{ $transaksi->berat }} kg</div>
                <div><span class="text-slate-400">Total:</span> Rp {{ number_format($transaksi->total, 0, ',', '.') }}</div>
                <div><span class="text-slate-400">Status:</span> {{ ucfirst($transaksi->status) }}</div>
            </div>
            @if($transaksi->catatan)
            <div class="mt-3 text-sm">
                <span class="text-slate-400">Catatan:</span> {{ $transaksi->catatan }}
            </div>
            @endif
        </div>
        @elseif(request('kode'))
        <div class="box p-8 mt-6 text-center">
            <i data-lucide="search-x" class="w-12 h-12 mx-auto text-slate-300"></i>
            <p class="text-slate-500 mt-3">Transaksi dengan kode "{{ request('kode') }}" tidak ditemukan.</p>
            <p class="text-sm text-slate-400 mt-1">Periksa kembali kode transaksi Anda.</p>
        </div>
        @endif
    </div>

    <footer class="text-center text-slate-400 text-sm py-8">
        <p>&copy; {{ date('Y') }} {{ $settings->nama_app ?? 'LaundryKu' }}. All rights reserved.</p>
    </footer>

    <script src="/js/app.js"></script>
    @stack('scripts')
</body>
</html>
