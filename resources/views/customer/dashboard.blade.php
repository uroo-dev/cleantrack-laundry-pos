@extends('layouts.auth')

@section('title', 'Dashboard Pelanggan')

@section('auth-content')
    <div class="w-full">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold" style="background: #004ac6; color: #ffffff;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold" style="color: #191c1e;">Halo, {{ auth()->user()->name }}</h2>
                    <p class="text-xs" style="color: #6b7280;">Selamat datang kembali!</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium transition-all hover:brightness-95"
                    style="background: #eceef0; color: #191c1e;">
                    <span class="material-symbols-outlined text-lg">logout</span>
                    Keluar
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="glass-card p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(0, 74, 198, 0.1);">
                        <span class="material-symbols-outlined" style="color: #004ac6; font-size: 28px;">laundry</span>
                    </div>
                    <div>
                        <p class="text-xs font-medium" style="color: #6b7280;">Laundry Aktif</p>
                        <p class="text-2xl font-extrabold" style="color: #191c1e;">{{ $laundryAktif ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(0, 104, 122, 0.1);">
                        <span class="material-symbols-outlined" style="color: #00687a; font-size: 28px;">checklist</span>
                    </div>
                    <div>
                        <p class="text-xs font-medium" style="color: #6b7280;">Total Riwayat</p>
                        <p class="text-2xl font-extrabold" style="color: #191c1e;">{{ $totalRiwayat ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(0, 98, 66, 0.1);">
                        <span class="material-symbols-outlined" style="color: #006242; font-size: 28px;">redeem</span>
                    </div>
                    <div>
                        <p class="text-xs font-medium" style="color: #6b7280;">Poin</p>
                        <p class="text-2xl font-extrabold" style="color: #191c1e;">{{ $pelanggan->poin ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card p-5 mb-6">
            <h3 class="font-semibold mb-3 flex items-center gap-2" style="color: #191c1e;">
                <span class="material-symbols-outlined" style="color: #004ac6;">search</span>
                Cek Tracking Laundry
            </h3>
            <form method="POST" action="{{ route('customer.tracking.cek') }}" class="flex gap-2">
                @csrf
                <input type="text" name="kode_transaksi"
                    class="flex-1 px-4 py-2.5 rounded-xl border"
                    style="background: #eceef0; border-color: rgba(194, 199, 203, 0.4); color: #191c1e;"
                    placeholder="Masukkan kode transaksi" required>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-1.5 transition-all hover:brightness-110 active:scale-[0.98]"
                    style="background: #004ac6; color: #ffffff;">
                    <span class="material-symbols-outlined text-lg">search</span>
                    CEK
                </button>
            </form>
        </div>

        <div class="glass-card p-5">
            <h3 class="font-semibold mb-3 flex items-center gap-2" style="color: #191c1e;">
                <span class="material-symbols-outlined" style="color: #004ac6;">receipt_long</span>
                Riwayat Transaksi
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left" style="color: #6b7280; border-bottom: 1px solid rgba(194, 199, 203, 0.3);">
                            <th class="pb-3 font-semibold">Kode</th>
                            <th class="pb-3 font-semibold">Layanan</th>
                            <th class="pb-3 font-semibold">Berat</th>
                            <th class="pb-3 font-semibold">Total</th>
                            <th class="pb-3 font-semibold">Status</th>
                            <th class="pb-3 font-semibold">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat ?? [] as $r)
                        <tr style="border-bottom: 1px solid rgba(194, 199, 203, 0.15);">
                            <td class="py-3 font-mono text-xs" style="color: #004ac6;">{{ $r->kode_transaksi }}</td>
                            <td class="py-3">{{ $r->layanan->nama ?? '-' }}</td>
                            <td class="py-3">{{ $r->berat }} kg</td>
                            <td class="py-3 font-semibold">Rp {{ number_format($r->total, 0, ',', '.') }}</td>
                            <td class="py-3">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium"
                                    style="background: rgba(0, 74, 198, 0.08); color: #004ac6;">
                                    {{ ucfirst($r->status) }}
                                </span>
                            </td>
                            <td class="py-3" style="color: #6b7280;">{{ $r->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center" style="color: #9ca3af;">
                                <span class="material-symbols-outlined text-3xl block mb-2">inbox</span>
                                Belum ada transaksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
