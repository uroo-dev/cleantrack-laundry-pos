<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings->nama_app ?? 'LaundryKu' }} - Solusi Laundry Anda</title>
    <link rel="stylesheet" href="/css/app.css">
    @stack('styles')
</head>
<body class="bg-white">
    <nav class="bg-white shadow-sm border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <span class="text-xl font-bold text-primary">{{ $settings->nama_app ?? 'LaundryKu' }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('tracking') }}" class="text-primary hover:text-primary">Tracking</a>
                    <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-outline-primary">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="bg-gradient-to-br from-blue-800 via-blue-700 to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-primary leading-tight">{{ $settings->nama_app ?? 'LaundryKu' }} - Solusi Laundry Anda</h1>
                    <p class="mt-4 text-lg text-primary">Nikmati layanan laundry profesional dengan harga terjangkau. Kami siap membantu Anda!</p>
                    <div class="mt-8 flex gap-3">
                        <a href="{{ route('register') }}" class="btn bg-white text-primary hover:bg-blue-50 px-8 py-3 font-medium">Daftar Sekarang</a>
                        <a href="{{ route('tracking') }}" class="btn bg-primary border-2 border-white text-white hover:bg-white/10 px-8 py-3 font-medium">Cek Tracking</a>
                    </div>
                </div>
                <div class="hidden lg:flex justify-center">
                    <i data-lucide="shirt" class="w-48 h-48 text-white/20"></i>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold">Cek Tracking Laundry</h2>
            <p class="text-slate-500 mt-2">Masukkan kode transaksi Anda untuk melihat status laundry</p>
        </div>
        <div class="max-w-lg mx-auto">
            <form method="GET" action="{{ route('tracking') }}" class="flex gap-2">
                <input type="text" name="kode" class="form-control text-lg text-center" placeholder="Masukkan kode transaksi" required>
                <button type="submit" class="btn btn-primary px-6">Cari</button>
            </form>
        </div>
    </section>

    <section class="bg-slate-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Mengapa Memilih Kami?</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-primary/10 flex items-center justify-center mb-4">
                        <i data-lucide="clock" class="w-8 h-8 text-primary"></i>
                    </div>
                    <h3 class="font-semibold text-lg">Tepat Waktu</h3>
                    <p class="text-slate-500 text-sm mt-2">Kami mengembalikan laundry Anda tepat waktu sesuai janji.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-success/10 flex items-center justify-center mb-4">
                        <i data-lucide="sparkles" class="w-8 h-8 text-success"></i>
                    </div>
                    <h3 class="font-semibold text-lg">Bersih & Wangi</h3>
                    <p class="text-slate-500 text-sm mt-2">Hasil laundry bersih, wangi, dan rapi setrikaan.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-warning/10 flex items-center justify-center mb-4">
                        <i data-lucide="tag" class="w-8 h-8 text-warning"></i>
                    </div>
                    <h3 class="font-semibold text-lg">Harga Terjangkau</h3>
                    <p class="text-slate-500 text-sm mt-2">Harga bersahabat dengan kualitas terbaik.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-info/10 flex items-center justify-center mb-4">
                        <i data-lucide="map-pin" class="w-8 h-8 text-info"></i>
                    </div>
                    <h3 class="font-semibold text-lg">Lokasi Strategis</h3>
                    <p class="text-slate-500 text-sm mt-2">Mudah dijangkau dan tersedia antar-jemput.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-400 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-white font-semibold">{{ $settings->nama_app ?? 'LaundryKu' }}</p>
            <p class="text-sm mt-2">{{ $settings->alamat ?? '' }}</p>
            <p class="text-sm">Telp: {{ $settings->telepon ?? '' }} | Email: {{ $settings->email ?? '' }}</p>
            <hr class="my-4 border-slate-700">
            <p class="text-sm">&copy; {{ date('Y') }} {{ $settings->nama_app ?? 'LaundryKu' }}. All rights reserved.</p>
        </div>
    </footer>

    <script src="/js/app.js"></script>
    @stack('scripts')
</body>
</html>
