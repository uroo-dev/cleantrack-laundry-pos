@extends('layouts.admin')

@section('title', 'Transaksi')
@section('page-title', 'Transaksi')
@section('page-description', 'Kelola semua transaksi laundry')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-8">
    <div class="glass-card rounded-2xl p-6 soft-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-label-sm text-on-surface-variant">Total Invoices</p>
                <p class="text-headline-sm font-bold mt-1">{{ number_format($totalInvoice) }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-primary-container/40 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">receipt_long</span>
            </div>
        </div>
    </div>
    <div class="glass-card rounded-2xl p-6 soft-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-label-sm text-on-surface-variant">Belum Lunas</p>
                <p class="text-headline-sm font-bold mt-1 text-error">{{ number_format($belumLunas) }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-error-container/40 flex items-center justify-center">
                <span class="material-symbols-outlined text-error">payments</span>
            </div>
        </div>
    </div>
    <div class="glass-card rounded-2xl p-6 soft-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-label-sm text-on-surface-variant">Total Pendapatan</p>
                <p class="text-headline-sm font-bold mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-tertiary-container/40 flex items-center justify-center">
                <span class="material-symbols-outlined text-tertiary">savings</span>
            </div>
        </div>
    </div>
</div>

<div class="glass-card rounded-2xl overflow-hidden soft-shadow">
    <div class="p-6 border-b border-outline-variant/20">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3 flex-1 max-w-md">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input type="text" id="searchTransaksi" placeholder="Cari invoice atau pelanggan..." class="w-full pl-10 pr-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40 transition-all">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <select id="statusFilter" class="text-label-sm bg-surface-container border border-outline-variant/40 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                    <option value="diambil">Diambil</option>
                </select>
                <button onclick="openModal('exportModal')" class="bg-surface-container-high px-4 py-2.5 rounded-xl text-label-sm flex items-center gap-2 hover:bg-surface-container-highest transition-all">
                    <span class="material-symbols-outlined text-sm">download</span> Export
                </button>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-label-sm text-on-surface-variant border-b border-outline-variant/30 bg-surface-container-low/50">
                    <th class="text-left py-4 px-6">Invoice</th>
                    <th class="text-left py-4 px-6">Pelanggan</th>
                    <th class="text-left py-4 px-6">Status</th>
                    <th class="text-left py-4 px-6">Pembayaran</th>
                    <th class="text-left py-4 px-6">Total</th>
                    <th class="text-left py-4 px-6">Estimasi Selesai</th>
                    <th class="text-right py-4 px-6">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $trx)
                <tr class="border-b border-outline-variant/20 hover:bg-surface-container-low transition-all">
                    <td class="py-4 px-6">
                        <a href="{{ route('admin.transaksi.show', $trx->id) }}" class="text-label-lg text-primary font-bold">{{ $trx->invoice }}</a>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-xs font-bold text-on-primary-fixed-variant flex-shrink-0">
                                {{ strtoupper(substr($trx->pelanggan->nama ?? '?', 0, 2)) }}
                            </div>
                            <span class="text-body-sm">{{ $trx->pelanggan->nama ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        @php
                            $colors = ['pending' => 'bg-warning/10 text-yellow-700', 'proses' => 'bg-blue-50 text-blue-700', 'selesai' => 'bg-green-50 text-green-700', 'diambil' => 'bg-surface-container-high text-on-surface-variant'];
                        @endphp
                        <span class="text-label-sm px-3 py-1 rounded-full {{ $colors[$trx->status] ?? 'bg-surface-container-high text-on-surface-variant' }}">{{ ucfirst($trx->status) }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <span class="text-label-sm px-3 py-1 rounded-full {{ $trx->status_pembayaran === 'lunas' ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                            {{ ucfirst($trx->status_pembayaran ?? 'belum') }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-body-sm font-bold">Rp {{ number_format($trx->total ?? 0, 0, ',', '.') }}</td>
                    <td class="py-4 px-6 text-body-sm text-on-surface-variant">{{ $trx->estimasi_selesai ? \Carbon\Carbon::parse($trx->estimasi_selesai)->format('d M Y') : '-' }}</td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.transaksi.show', $trx->id) }}" class="p-2 hover:bg-surface-container-high rounded-lg transition-all" title="Detail">
                                <span class="material-symbols-outlined text-primary">visibility</span>
                            </a>
                            @if($trx->status !== 'diambil')
                            <form action="{{ route('admin.transaksi.update-status', $trx->id) }}" method="POST" class="inline-block">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $trx->status === 'pending' ? 'proses' : ($trx->status === 'proses' ? 'selesai' : 'diambil') }}">
                                <button type="submit" class="p-2 hover:bg-surface-container-high rounded-lg transition-all" title="Lanjutkan Status">
                                    <span class="material-symbols-outlined text-secondary">arrow_forward</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-16">
                        <span class="material-symbols-outlined text-5xl text-on-surface-variant opacity-20 mb-3">receipt_long</span>
                        <p class="text-body-sm text-on-surface-variant">Belum ada transaksi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-outline-variant/20">
        {{ $transaksi->links() }}
    </div>
</div>

{{-- Export Modal --}}
<div id="exportModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" style="background: rgba(0,0,0,0.3); backdrop-filter: blur(4px);">
    <div class="glass-card rounded-2xl w-full max-w-md soft-shadow" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-outline-variant/20 flex items-center justify-between">
            <h3 class="text-headline-sm">Export Transaksi</h3>
            <button onclick="closeModal('exportModal')" class="p-2 hover:bg-surface-container-high rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form action="{{ route('admin.transaksi.export') }}" method="GET" class="p-6 space-y-4">
            <div>
                <label class="text-label-sm text-on-surface-variant mb-1 block">Dari Tanggal</label>
                <input type="date" name="dari" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="text-label-sm text-on-surface-variant mb-1 block">Sampai Tanggal</label>
                <input type="date" name="sampai" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="text-label-sm text-on-surface-variant mb-1 block">Status</label>
                <select name="status" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="">Semua</option>
                    <option value="pending">Pending</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                    <option value="diambil">Diambil</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-primary text-on-primary py-2.5 rounded-xl font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all">
                <span class="material-symbols-outlined text-sm">download</span> Export Excel
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
document.getElementById('exportModal')?.addEventListener('click', () => closeModal('exportModal'));

document.getElementById('searchTransaksi')?.addEventListener('keyup', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('tbody tr').forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
document.getElementById('statusFilter')?.addEventListener('change', function() {
    const v = this.value.toLowerCase();
    document.querySelectorAll('tbody tr').forEach(r => {
        if (!v) { r.style.display = ''; return; }
        const status = r.querySelector('td:nth-child(3) span')?.textContent.trim().toLowerCase() || '';
        r.style.display = status.includes(v) ? '' : 'none';
    });
});
</script>
@endpush
