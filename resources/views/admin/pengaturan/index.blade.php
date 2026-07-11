@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')
@section('page-description', 'Konfigurasi aplikasi laundry')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
    {{-- Profile Card --}}
    <div class="glass-card rounded-2xl p-6 soft-shadow text-center">
        <div class="w-20 h-20 mx-auto rounded-full bg-primary-fixed flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-4xl text-primary">store</span>
        </div>
        <h3 class="text-headline-sm font-bold">{{ $settings->nama_app ?? 'LaundryHub' }}</h3>
        <p class="text-body-sm text-on-surface-variant mt-1">{{ $settings->email ?? 'admin@laundryhub.com' }}</p>
        <div class="mt-4 flex justify-center gap-2">
            <span class="text-label-sm px-3 py-1 rounded-full bg-surface-container-high">Version 2.0</span>
        </div>
    </div>

    {{-- Settings Form --}}
    <div class="lg:col-span-2 space-y-6">
        <form action="{{ route('admin.pengaturan.update') }}" method="POST" class="glass-card rounded-2xl p-6 soft-shadow">
            @csrf
            <h3 class="text-headline-sm mb-6">Informasi Bisnis</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="text-label-sm text-on-surface-variant mb-1 block">Nama Aplikasi</label>
                    <input type="text" name="nama_app" value="{{ old('nama_app', $settings->nama_app ?? 'LaundryHub') }}" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                </div>
                <div>
                    <label class="text-label-sm text-on-surface-variant mb-1 block">Nomor Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $settings->telepon ?? '') }}" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40" placeholder="Contoh: 08123456789">
                </div>
                <div>
                    <label class="text-label-sm text-on-surface-variant mb-1 block">Email</label>
                    <input type="email" name="email" value="{{ old('email', $settings->email ?? '') }}" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                </div>
                <div>
                    <label class="text-label-sm text-on-surface-variant mb-1 block">WhatsApp Number</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $settings->whatsapp ?? $settings->telepon ?? '') }}" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40" placeholder="Nomor untuk WA notifikasi">
                </div>
            </div>
            <div class="mb-6">
                <label class="text-label-sm text-on-surface-variant mb-1 block">Alamat</label>
                <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">{{ old('alamat', $settings->alamat ?? '') }}</textarea>
            </div>
            <div class="mb-6">
                <label class="text-label-sm text-on-surface-variant mb-1 block">Deskripsi</label>
                <textarea name="deskripsi" rows="2" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">{{ old('deskripsi', $settings->deskripsi ?? '') }}</textarea>
            </div>
            <hr class="border-outline-variant/30 my-6">
            <h3 class="text-headline-sm mb-6">Halaman Beranda</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="text-label-sm text-on-surface-variant mb-1 block">Judul Hero</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $settings->hero_title ?? 'Laundry Kiloan & Satuan') }}" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                </div>
                <div>
                    <label class="text-label-sm text-on-surface-variant mb-1 block">Subjudul Hero</label>
                    <input type="text" name="hero_subtitle" value="{{ old('hero_subtitle', $settings->hero_subtitle ?? 'Nikmati layanan laundry') }}" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                </div>
            </div>
            <div class="mb-6">
                <label class="text-label-sm text-on-surface-variant mb-1 block">Tentang Kami</label>
                <textarea name="tentang" rows="3" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">{{ old('tentang', $settings->tentang ?? '') }}</textarea>
            </div>
            <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all">
                <span class="material-symbols-outlined">save</span> Simpan Pengaturan
            </button>
        </form>

        {{-- Danger Zone --}}
        <div class="glass-card rounded-2xl p-6 soft-shadow border border-error/20">
            <h3 class="text-headline-sm text-error mb-2">Zona Berbahaya</h3>
            <p class="text-body-sm text-on-surface-variant mb-4">Tindakan berikut tidak dapat dibatalkan.</p>
            <div class="flex items-center gap-4 flex-wrap">
                <button type="button" onclick="confirmReset()" class="bg-error/10 text-error px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-error/20 transition-all">
                    <span class="material-symbols-outlined">restart_alt</span> Reset Data
                </button>
                <button type="button" onclick="confirmExport()" class="bg-surface-container-high text-on-surface px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-surface-container-highest transition-all">
                    <span class="material-symbols-outlined">download</span> Export All Data
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmReset() {
    Swal.fire({
        title: 'Reset Semua Data?',
        text: 'Tindakan ini akan menghapus semua transaksi dan data!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ba1a1a',
        cancelButtonColor: '#e0e3e5',
        confirmButtonText: 'Ya, Reset!',
        cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            fetch('{{ route('admin.pengaturan.reset') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
            .then(() => Swal.fire({ icon: 'success', title: 'Data direset', timer: 2000, showConfirmButton: false }).then(() => location.reload()));
        }
    });
}
function confirmExport() {
    Swal.fire({
        title: 'Export Data?',
        text: 'Semua data akan di-download sebagai JSON',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#004ac6',
        confirmButtonText: 'Ya, Export',
        cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) window.location.href = '{{ route('admin.pengaturan.export') }}';
    });
}
</script>
@endpush
