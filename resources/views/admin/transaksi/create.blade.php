@extends('layouts.admin')

@section('title', 'Transaksi Baru')
@section('page-title', 'Transaksi Baru')
@section('page-description', 'Buat transaksi laundry baru')

@section('content')
<form action="{{ route('admin.transaksi.store') }}" method="POST" id="transaksiForm" class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
    @csrf
    {{-- Left - Main Form --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Customer Selection --}}
        <div class="glass-card rounded-2xl p-6 soft-shadow">
            <h3 class="text-headline-sm mb-4">Pilih Pelanggan</h3>
            <div class="flex items-center gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input type="text" id="customerSearch" placeholder="Cari nama atau telepon pelanggan..." class="w-full pl-10 pr-4 py-3 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                </div>
                <button type="button" onclick="openModal('addCustomerModal')" class="bg-primary text-on-primary px-4 py-3 rounded-xl font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">add</span>
                </button>
            </div>
            <div id="customerList" class="mt-4 max-h-48 overflow-y-auto custom-scrollbar space-y-2">
                @foreach($pelanggan as $p)
                <label class="flex items-center gap-3 p-3 rounded-xl border border-outline-variant/30 hover:bg-surface-container-low cursor-pointer transition-all has-[:checked]:bg-primary-container/30 has-[:checked]:border-primary">
                    <input type="radio" name="pelanggan_id" value="{{ $p->id }}" class="accent-primary" {{ $loop->first ? 'checked' : '' }}>
                    <div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-xs font-bold flex-shrink-0">{{ strtoupper(substr($p->nama, 0, 2)) }}</div>
                    <div>
                        <p class="text-label-lg font-bold">{{ $p->nama }}</p>
                        <p class="text-label-sm text-on-surface-variant">{{ $p->telepon ?? '-' }}</p>
                    </div>
                    <span class="ml-auto text-label-sm text-on-surface-variant">{{ $p->transaksi_count ?? 0 }}x</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Order Items --}}
        <div class="glass-card rounded-2xl p-6 soft-shadow">
            <h3 class="text-headline-sm mb-4">Item Laundry</h3>
            <div id="orderItems">
                <div class="order-item grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 rounded-xl bg-surface-container-low/50">
                    <div>
                        <label class="text-label-sm text-on-surface-variant mb-1 block">Layanan *</label>
                        <select name="layanan_id[]" required class="w-full px-3 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <option value="">Pilih Layanan</option>
                            @foreach($layanan as $l)
                            <option value="{{ $l->id }}" data-harga="{{ $l->harga_perkg }}" data-satuan="{{ $l->estimasi_hari }}">{{ $l->nama }} - Rp {{ number_format($l->harga_perkg, 0, ',', '.') }}/kg</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-label-sm text-on-surface-variant mb-1 block">Berat (Kg) *</label>
                        <input type="number" name="berat[]" step="0.1" min="0.1" required class="w-full px-3 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                    </div>
                    <div>
                        <label class="text-label-sm text-on-surface-variant mb-1 block">Harga</label>
                        <input type="number" name="harga[]" readonly class="w-full px-3 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm text-on-surface-variant">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="remove-item p-2 text-error hover:bg-error-container/30 rounded-lg transition-all">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" id="addItem" class="w-full border-2 border-dashed border-outline-variant/40 rounded-xl py-4 text-label-lg text-on-surface-variant hover:border-primary/40 hover:text-primary transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">add</span> Tambah Item
            </button>
        </div>

        {{-- Additional --}}
        <div class="glass-card rounded-2xl p-6 soft-shadow">
            <h3 class="text-headline-sm mb-4">Informasi Tambahan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-label-sm text-on-surface-variant mb-1 block">Estimasi Selesai</label>
                    <input type="datetime-local" name="estimasi_selesai" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                </div>
                <div>
                    <label class="text-label-sm text-on-surface-variant mb-1 block">Outlet</label>
                    <select name="outlet" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                        <option value="Utama">Utama</option>
                        <option value="Cabang 1">Cabang 1</option>
                        <option value="Cabang 2">Cabang 2</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label class="text-label-sm text-on-surface-variant mb-1 block">Catatan</label>
                <textarea name="catatan" rows="2" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40" placeholder="Catatan untuk order ini..."></textarea>
            </div>
        </div>
    </div>

    {{-- Right - Summary --}}
    <div class="space-y-6">
        <div class="glass-card rounded-2xl p-6 soft-shadow sticky top-24">
            <h3 class="text-headline-sm mb-4">Ringkasan</h3>
            <div id="summaryContent" class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-body-sm text-on-surface-variant">Items</span>
                    <span class="text-body-sm" id="summaryItems">0</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-body-sm text-on-surface-variant">Total Berat</span>
                    <span class="text-body-sm" id="summaryWeight">0 Kg</span>
                </div>
                <div class="border-t border-outline-variant/30 pt-3">
                    <div class="flex items-center justify-between">
                        <span class="text-body-sm text-on-surface-variant">Subtotal</span>
                        <span class="text-body-sm" id="summarySubtotal">Rp 0</span>
                    </div>
                </div>
                <div>
                    <label class="text-label-sm text-on-surface-variant mb-1 block">Diskon</label>
                    <input type="number" name="diskon" id="diskonInput" value="0" min="0" class="w-full px-3 py-2 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                </div>
                <div class="border-t border-outline-variant/30 pt-3">
                    <div class="flex items-center justify-between">
                        <span class="text-headline-sm">Total</span>
                        <span class="text-headline-sm font-bold text-primary" id="summaryTotal">Rp 0</span>
                    </div>
                </div>
            </div>

            {{-- Payment --}}
            <div class="mt-6 pt-4 border-t border-outline-variant/20">
                <h4 class="text-label-lg font-bold mb-3">Pembayaran</h4>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-outline-variant/30 cursor-pointer transition-all has-[:checked]:bg-primary-container/30 has-[:checked]:border-primary">
                        <input type="radio" name="status_pembayaran" value="lunas" checked class="accent-primary">
                        <span class="material-symbols-outlined text-tertiary">check_circle</span>
                        <span class="text-label-lg">Lunas</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-outline-variant/30 cursor-pointer transition-all has-[:checked]:bg-primary-container/30 has-[:checked]:border-primary">
                        <input type="radio" name="status_pembayaran" value="dp" class="accent-primary">
                        <span class="material-symbols-outlined text-secondary">payments</span>
                        <span class="text-label-lg">DP</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-outline-variant/30 cursor-pointer transition-all has-[:checked]:bg-primary-container/30 has-[:checked]:border-primary">
                        <input type="radio" name="status_pembayaran" value="belum" class="accent-primary">
                        <span class="material-symbols-outlined text-error">cancel</span>
                        <span class="text-label-lg">Belum Bayar</span>
                    </label>
                    <div id="dpField" class="hidden">
                        <label class="text-label-sm text-on-surface-variant mb-1 block">Jumlah DP</label>
                        <input type="number" name="dp" value="0" min="0" class="w-full px-3 py-2 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-primary text-on-primary py-3.5 rounded-xl font-bold flex items-center justify-center gap-2 mt-6 shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all">
                <span class="material-symbols-outlined">receipt_long</span> Simpan Transaksi
            </button>
        </div>
    </div>
</form>

{{-- Add Customer Modal --}}
<div id="addCustomerModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" style="background: rgba(0,0,0,0.3); backdrop-filter: blur(4px);">
    <div class="glass-card rounded-2xl w-full max-w-lg soft-shadow" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-outline-variant/20 flex items-center justify-between">
            <h3 class="text-headline-sm">Pelanggan Baru</h3>
            <button onclick="closeModal('addCustomerModal')" class="p-2 hover:bg-surface-container-high rounded-lg"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form action="{{ route('admin.pelanggan.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="redirect" value="transaksi">
            <div>
                <label class="text-label-sm text-on-surface-variant mb-1 block">Nama Pelanggan *</label>
                <input type="text" name="nama" required class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="text-label-sm text-on-surface-variant mb-1 block">Nomor Telepon *</label>
                <input type="text" name="telepon" required class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="text-label-sm text-on-surface-variant mb-1 block">Alamat</label>
                <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40"></textarea>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="flex-1 bg-primary text-on-primary py-2.5 rounded-xl font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all">Simpan</button>
                <button type="button" onclick="closeModal('addCustomerModal')" class="flex-1 bg-surface-container-high text-on-surface py-2.5 rounded-xl font-bold hover:scale-[1.02] transition-all">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
document.getElementById('addCustomerModal')?.addEventListener('click', () => closeModal('addCustomerModal'));

function calcSummary() {
    let items = 0, weight = 0, subtotal = 0;
    document.querySelectorAll('.order-item').forEach((row, i) => {
        const select = row.querySelector('[name="layanan_id[]"]');
        const berat = parseFloat(row.querySelector('[name="berat[]"]').value) || 0;
        const harga = select ? parseFloat(select.options[select.selectedIndex]?.dataset?.harga || 0) : 0;
        const sub = berat * harga;
        items++;
        weight += berat;
        subtotal += sub;
        const hargaInput = row.querySelector('[name="harga[]"]');
        if (hargaInput) hargaInput.value = sub;
    });
    document.getElementById('summaryItems').textContent = items;
    document.getElementById('summaryWeight').textContent = weight.toFixed(1) + ' Kg';
    document.getElementById('summarySubtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    const diskon = parseFloat(document.getElementById('diskonInput').value) || 0;
    const total = Math.max(0, subtotal - diskon);
    document.getElementById('summaryTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function addOrderItem() {
    const template = document.querySelector('.order-item').cloneNode(true);
    template.querySelectorAll('input, select').forEach(el => el.value = '');
    template.querySelectorAll('[name="harga[]"]').forEach(el => el.value = '');
    template.querySelectorAll('input, select').forEach(el => el.addEventListener('change', calcSummary));
    template.querySelectorAll('input').forEach(el => el.addEventListener('keyup', calcSummary));
    template.querySelector('.remove-item').addEventListener('click', function() {
        if (document.querySelectorAll('.order-item').length > 1) {
            template.remove();
            calcSummary();
        } else {
            Swal.fire({ icon: 'warning', title: 'Minimal 1 item', timer: 1500, showConfirmButton: false });
        }
    });
    document.getElementById('orderItems').appendChild(template);
    calcSummary();
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.order-item input, .order-item select').forEach(el => el.addEventListener('change', calcSummary));
    document.querySelectorAll('.order-item input').forEach(el => el.addEventListener('keyup', calcSummary));
    document.querySelector('.remove-item')?.addEventListener('click', function() {
        if (document.querySelectorAll('.order-item').length > 1) {
            this.closest('.order-item').remove();
            calcSummary();
        } else {
            Swal.fire({ icon: 'warning', title: 'Minimal 1 item', timer: 1500, showConfirmButton: false });
        }
    });
    document.getElementById('addItem').addEventListener('click', addOrderItem);
    document.getElementById('diskonInput').addEventListener('keyup', calcSummary);
    document.getElementById('diskonInput').addEventListener('change', calcSummary);
    document.querySelectorAll('[name="status_pembayaran"]').forEach(r => r.addEventListener('change', function() {
        document.getElementById('dpField').classList.toggle('hidden', this.value !== 'dp');
    }));
});

document.getElementById('transaksiForm').addEventListener('submit', function(e) {
    const customer = document.querySelector('[name="pelanggan_id"]:checked');
    if (!customer) { e.preventDefault(); Swal.fire({ icon: 'error', title: 'Pilih pelanggan', timer: 2000, showConfirmButton: false }); }
});
</script>
@endpush
