@extends('layouts.public')

@section('title', 'Beranda')

@section('content')
{{-- Top Navigation Bar --}}
<header class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-xl border-b border-white/30 shadow-sm flex justify-between items-center px-margin-mobile h-16">
    <a href="{{ route('home') }}" class="font-headline-md text-headline-md font-bold text-primary">{{ $settings->nama_app ?? 'LaundryHub' }}</a>
    <div class="flex items-center gap-4">
        <a href="{{ route('tracking') }}" class="material-symbols-outlined text-on-surface-variant p-2 hover:bg-primary/5 rounded-full transition-colors active:scale-95 duration-200">local_shipping</a>
        <a href="{{ route('login') }}" class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container overflow-hidden">
            <span class="material-symbols-outlined text-xl">person</span>
        </a>
    </div>
</header>

<main class="pt-16 pb-24">
    {{-- Hero Section --}}
    <section class="relative px-margin-mobile pt-8 pb-12 overflow-hidden">
        <div class="relative z-10">
            <h1 class="font-headline-lg-mobile text-headline-lg-mobile text-primary mb-4 leading-tight">
                Laundry Bersih, Cepat, dan
                <span class="text-secondary">Tepat Waktu</span>
            </h1>
            <p class="font-body-md text-body-md text-on-surface-variant mb-8 max-w-[90%]">
                Nikmati kemudahan mencuci baju dengan layanan premium yang menjamin kebersihan dan ketepatan waktu untuk kenyamanan Anda.
            </p>

            <div class="flex flex-col gap-3 mb-10">
                <a href="{{ route('register') }}" class="bg-primary text-on-primary font-label-lg text-label-lg py-4 rounded-xl shadow-lg active:scale-95 transition-transform text-center">
                    Pesan Sekarang
                </a>
                <a href="{{ $settings->telepon ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', str_starts_with($settings->telepon, '0') ? '62' . substr($settings->telepon, 1) : $settings->telepon) : '#' }}" target="_blank" class="glass-card text-primary font-label-lg text-label-lg py-4 rounded-xl active:scale-95 transition-transform border border-primary/20 text-center">
                    Hubungi Kami
                </a>
            </div>

            <div class="flex gap-4">
                <div class="glass-card p-4 rounded-2xl flex-1 flex flex-col items-center shadow-sm">
                    <span class="text-secondary font-bold text-headline-sm">4.9</span>
                    <span class="text-label-sm text-on-surface-variant">Rating</span>
                </div>
                <div class="glass-card p-4 rounded-2xl flex-1 flex flex-col items-center shadow-sm">
                    <span class="text-primary font-bold text-headline-sm">{{ number_format($totalPelanggan ?? 1000) }}+</span>
                    <span class="text-label-sm text-on-surface-variant">Customers</span>
                </div>
            </div>
        </div>

        <div class="mt-12 rounded-3xl overflow-hidden shadow-2xl relative h-80">
            <img src="https://images.unsplash.com/photo-1545173168-9f1947eebb7f?w=800&auto=format" alt="Premium Laundry Service" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
        </div>
    </section>

    {{-- Services Section --}}
    <section class="px-margin-mobile py-8 bg-surface-container-low">
        <div class="flex justify-between items-end mb-6">
            <div>
                <h2 class="font-headline-sm text-headline-sm text-on-surface">Layanan Kami</h2>
                <p class="text-label-sm text-on-surface-variant">Pilih yang sesuai kebutuhanmu</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            @forelse($layanans as $layanan)
            <div class="glass-card p-5 rounded-3xl flex flex-col justify-between aspect-square active:scale-95 transition-transform">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-primary">{{ $loop->index % 4 === 0 ? 'local_laundry_service' : ($loop->index % 4 === 1 ? 'checkroom' : ($loop->index % 4 === 2 ? 'footprint' : 'layers')) }}</span>
                </div>
                <div>
                    <h3 class="font-label-lg text-label-lg text-on-surface">{{ $layanan->nama }}</h3>
                    <p class="text-[10px] text-on-surface-variant mb-2">Mulai Rp {{ number_format($layanan->harga_perkg, 0, ',', '.') }}/kg</p>
                    <span class="text-primary font-label-sm text-label-sm flex items-center gap-1">
                        {{ $layanan->estimasi_hari }} Hari
                        <span class="material-symbols-outlined text-xs">arrow_forward</span>
                    </span>
                </div>
            </div>
            @empty
            <div class="col-span-2 glass-card p-5 rounded-3xl text-center text-on-surface-variant">
                Belum ada layanan tersedia
            </div>
            @endforelse
        </div>

        <div class="mt-8 rounded-2xl overflow-hidden glass-card p-6 flex items-center gap-6">
            <div class="flex-1">
                <h4 class="font-label-lg text-label-lg text-primary mb-1">Hemat Waktu</h4>
                <p class="text-body-sm text-on-surface-variant">Layanan cuci ekspres selesai dalam 6 jam saja.</p>
            </div>
            <img src="https://images.unsplash.com/photo-1610557892470-55d9e80c0bce?w=200&auto=format" alt="Laundry Service" class="w-24 h-24 object-contain rounded-2xl">
        </div>
    </section>

    {{-- Pricing Section --}}
    <section class="px-margin-mobile py-12">
        <h2 class="font-headline-sm text-headline-sm text-on-surface mb-6">Estimasi Harga & Waktu</h2>
        <div class="overflow-hidden rounded-2xl border border-outline-variant glass-card">
            <table class="w-full text-left">
                <thead class="bg-primary/5">
                    <tr>
                        <th class="p-4 font-label-lg text-label-lg text-on-surface">Layanan</th>
                        <th class="p-4 font-label-lg text-label-lg text-on-surface">Waktu</th>
                        <th class="p-4 font-label-lg text-label-lg text-on-surface text-right">Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @foreach($layanans as $layanan)
                    <tr>
                        <td class="p-4 font-body-sm text-body-sm">{{ $layanan->nama }}</td>
                        <td class="p-4 font-body-sm text-body-sm">{{ $layanan->estimasi_hari > 0 ? $layanan->estimasi_hari . ' Hari' : '6 Jam' }}</td>
                        <td class="p-4 font-label-lg text-label-lg text-right">Rp {{ number_format($layanan->harga_perkg, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-center mt-4 text-label-sm text-outline">*Harga di atas adalah harga per Kilogram</p>
    </section>

    {{-- How It Works --}}
    <section class="px-margin-mobile py-12 bg-white">
        <h2 class="font-headline-sm text-headline-sm text-on-surface mb-8 text-center">Cara Kerja Kami</h2>
        <div class="relative pl-12 space-y-10">
            <div class="absolute left-[20px] top-0 bottom-0 w-[2px] bg-outline-variant"></div>

            <div class="relative">
                <div class="absolute -left-[42px] top-0 w-10 h-10 rounded-full bg-primary flex items-center justify-center text-on-primary z-10 shadow-md">
                    <span class="material-symbols-outlined text-sm">front_loader</span>
                </div>
                <div>
                    <h3 class="font-label-lg text-label-lg text-on-surface">Drop Off / Pickup</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Serahkan cucian ke outlet kami atau gunakan layanan antar jemput kami.</p>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -left-[42px] top-0 w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-on-primary z-10 shadow-md">
                    <span class="material-symbols-outlined text-sm">bubbles</span>
                </div>
                <div>
                    <h3 class="font-label-lg text-label-lg text-on-surface">Proses Cuci</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Pakaian dicuci dengan deterjen ramah lingkungan & mesin profesional.</p>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -left-[42px] top-0 w-10 h-10 rounded-full bg-tertiary flex items-center justify-center text-on-primary z-10 shadow-md">
                    <span class="material-symbols-outlined text-sm">verified</span>
                </div>
                <div>
                    <h3 class="font-label-lg text-label-lg text-on-surface">Quality Control</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Pemeriksaan ketat untuk memastikan noda hilang dan pakaian rapi.</p>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -left-[42px] top-0 w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container z-10 shadow-md">
                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                </div>
                <div>
                    <h3 class="font-label-lg text-label-lg text-on-surface">Siap Diambil</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Kami akan memberitahu Anda lewat WhatsApp saat pakaian sudah siap.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Us --}}
    <section class="px-margin-mobile py-12 bg-surface-container">
        <h2 class="font-headline-sm text-headline-sm text-on-surface mb-8">Mengapa {{ $settings->nama_app ?? 'LaundryHub' }}?</h2>
        <div class="space-y-4">
            <div class="glass-card p-6 rounded-3xl flex items-start gap-4">
                <div class="p-3 bg-primary/10 rounded-2xl">
                    <span class="material-symbols-outlined text-primary">location_on</span>
                </div>
                <div>
                    <h4 class="font-label-lg text-label-lg text-on-surface">Lacak Real-time</h4>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Cek status cucianmu kapan saja langsung dari aplikasi.</p>
                </div>
            </div>
            <div class="glass-card p-6 rounded-3xl flex items-start gap-4">
                <div class="p-3 bg-secondary/10 rounded-2xl">
                    <span class="material-symbols-outlined text-secondary">badge</span>
                </div>
                <div>
                    <h4 class="font-label-lg text-label-lg text-on-surface">Staf Profesional</h4>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Tim ahli yang terlatih menangani berbagai jenis bahan kain.</p>
                </div>
            </div>
            <div class="glass-card p-6 rounded-3xl flex items-start gap-4">
                <div class="p-3 bg-tertiary/10 rounded-2xl">
                    <span class="material-symbols-outlined text-tertiary">local_shipping</span>
                </div>
                <div>
                    <h4 class="font-label-lg text-label-lg text-on-surface">Antar Jemput</h4>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Malas keluar rumah? Biar kami yang jemput cucianmu.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="px-margin-mobile py-12">
        <h2 class="font-headline-sm text-headline-sm text-on-surface mb-6">Kata Pelanggan</h2>
        <div class="flex overflow-x-auto gap-4 pb-4 snap-x no-scrollbar">
            <div class="min-w-[280px] glass-card p-6 rounded-3xl snap-center">
                <div class="flex gap-1 mb-3">
                    @for($i = 0; $i < 5; $i++)
                    <span class="material-symbols-outlined text-secondary text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                    @endfor
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant mb-4 italic">"Baju selalu harum dan rapi banget. Layanan antar jemputnya bener-bener ngebantu buat aku yang sibuk kerja."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-primary">SW</div>
                    <div class="font-label-sm text-label-sm">Sari Widjaya</div>
                </div>
            </div>
            <div class="min-w-[280px] glass-card p-6 rounded-3xl snap-center">
                <div class="flex gap-1 mb-3">
                    @for($i = 0; $i < 5; $i++)
                    <span class="material-symbols-outlined text-secondary text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                    @endfor
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant mb-4 italic">"Harga bersahabat tapi kualitas premium. Sudah jadi langganan lebih dari setahun di sini."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-secondary-fixed flex items-center justify-center font-bold text-secondary">BP</div>
                    <div class="font-label-sm text-label-sm">Budi Pratama</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact & Maps --}}
    <section class="px-margin-mobile py-12 mb-8">
        <h2 class="font-headline-sm text-headline-sm text-on-surface mb-6">Hubungi Kami</h2>
        <div class="glass-card rounded-3xl overflow-hidden p-6 mb-4">
            <div class="flex flex-col gap-6">
                <a href="{{ $settings->telepon ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', str_starts_with($settings->telepon, '0') ? '62' . substr($settings->telepon, 1) : $settings->telepon) : '#' }}" target="_blank" class="flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <span class="material-symbols-outlined">chat</span>
                    </div>
                    <div>
                        <p class="text-label-sm text-outline">WhatsApp</p>
                        <p class="font-label-lg text-label-lg">{{ $settings->telepon ?? '+62 812 3456 789' }}</p>
                    </div>
                </a>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">location_on</span>
                    </div>
                    <div>
                        <p class="text-label-sm text-outline">Alamat</p>
                        <p class="font-label-lg text-label-lg">{{ $settings->alamat ?? 'Jl. Kebersihan No. 45, Jakarta Selatan' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl overflow-hidden border border-outline-variant h-48 bg-surface-container relative">
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="material-symbols-outlined text-outline text-4xl">map</span>
            </div>
            <div class="absolute bottom-4 left-4 right-4 glass-card p-3 rounded-xl flex items-center justify-between">
                <span class="text-label-sm">{{ $settings->alamat ?? 'Jl. Kebersihan No. 45, Jakarta Selatan' }}</span>
                <a href="https://maps.google.com/?q={{ urlencode($settings->alamat ?? 'Jakarta') }}" target="_blank" class="material-symbols-outlined text-primary">open_in_new</a>
            </div>
        </div>
    </section>
</main>

{{-- Bottom Navigation --}}
<nav class="fixed bottom-0 w-full z-50 rounded-t-xl bg-surface/80 backdrop-blur-xl border-t border-white/30 shadow-lg flex items-center h-20 pb-safe px-2 justify-center gap-12">
    <a href="{{ route('home') }}" class="flex flex-col items-center justify-center bg-secondary-container text-on-secondary-container rounded-full px-4 py-1 active:scale-90 duration-200">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">home</span>
        <span class="font-label-sm text-label-sm">Beranda</span>
    </a>
    <a href="{{ route('tracking') }}" class="flex flex-col items-center justify-center text-on-surface-variant hover:opacity-80 transition-opacity active:scale-90 duration-200">
        <span class="material-symbols-outlined">local_shipping</span>
        <span class="font-label-sm text-label-sm">Lacak</span>
    </a>
</nav>

{{-- Floating Action Button --}}
<a href="{{ route('register') }}" class="fixed bottom-24 right-6 w-14 h-14 bg-primary text-on-primary rounded-full shadow-2xl flex items-center justify-center z-40 active:scale-90 transition-transform">
    <span class="material-symbols-outlined">add</span>
</a>
@endsection

@push('scripts')
<script>
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        const fab = document.querySelector('a.fixed.bottom-24');
        if (currentScroll > lastScroll && currentScroll > 100) {
            fab.style.transform = 'translateY(100px)';
        } else {
            fab.style.transform = 'translateY(0)';
        }
        lastScroll = currentScroll;
    });
</script>
@endpush
