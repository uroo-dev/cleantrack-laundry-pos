@extends('layouts.auth')

@section('title', 'Dashboard Pelanggan')

@section('auth-content')
    <div class="w-full max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Halo {{ auth()->user()->name }} 👋</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary">
                    <i data-lucide="log-out" class="w-4 h-4 mr-1"></i> Keluar
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="box p-4 intro-x">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                        <i data-lucide="activity" class="w-5 h-5 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Laundry Aktif</p>
                        <p class="text-xl font-bold">{{ $laundryAktif ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="box p-4 intro-x">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-success/20 flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-5 h-5 text-success"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Total Riwayat</p>
                        <p class="text-xl font-bold">{{ $totalRiwayat ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="box p-4 intro-x">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-warning/20 flex items-center justify-center">
                        <i data-lucide="award" class="w-5 h-5 text-warning"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Poin</p>
                        <p class="text-xl font-bold">{{ $pelanggan->poin ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="box p-5 intro-x mb-6">
            <h2 class="font-medium mb-3">Cek Tracking Laundry</h2>
            <form method="POST" action="{{ route('customer.tracking.cek') }}" class="flex gap-2">
                @csrf
                <input type="text" name="kode_transaksi" class="form-control flex-1" placeholder="Masukkan kode transaksi" required>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="search" class="w-4 h-4 mr-1"></i> CEK
                </button>
            </form>
        </div>

        <div class="box p-5 intro-x">
            <h2 class="font-medium mb-3">Riwayat Transaksi</h2>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Layanan</th>
                            <th>Berat</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat ?? [] as $r)
                        <tr>
                            <td class="font-mono">{{ $r->kode_transaksi }}</td>
                            <td>{{ $r->layanan->nama ?? '-' }}</td>
                            <td>{{ $r->berat }} kg</td>
                            <td>Rp {{ number_format($r->total, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($r->status) }}</td>
                            <td>{{ $r->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-400">Belum ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
