@extends('layouts.admin')

@section('title', 'Input Order Baru')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('staff.order.queue') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Order Baru</li>
@endsection

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-headline-sm font-bold">Input Order Baru</h2>
            <p class="text-body-sm text-on-surface-variant">Buat pesanan laundry baru untuk pelanggan</p>
        </div>
        <a href="{{ route('staff.order.queue') }}" class="text-label-sm text-primary font-bold hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">chevron_left</span> Kembali
        </a>
    </div>

    @if($errors->any())
    <div class="mb-6 glass-card rounded-2xl p-4 border border-error/20 bg-error-container/10">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-error">error</span>
            <div>
                <p class="text-label-lg text-error font-bold">Terjadi kesalahan</p>
                <ul class="text-body-sm text-error mt-1 list-disc list-inside">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('staff.order.store') }}" id="orderForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-7 space-y-6">
                {{-- Customer Data --}}
                <div class="glass-card rounded-2xl p-6 soft-shadow">
                    <h3 class="text-label-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Data Pelanggan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-label-sm text-on-surface-variant mb-1.5 block">Nama Pelanggan <span class="text-error">*</span></label>
                            <select name="pelanggan_id" class="w-full rounded-xl border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" required>
                                <option value="">Cari pelanggan...</option>
                                @foreach($pelanggan as $p)
                                <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }} - {{ $p->telepon }}</option>
                                @endforeach
                            </select>
                            @error('pelanggan_id') <p class="text-body-sm text-error mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-label-sm text-on-surface-variant mb-1.5 block">No. HP</label>
                            <input type="text" id="no_hp" class="w-full rounded-xl border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" readonly placeholder="Otomatis terisi">
                        </div>
                    </div>
                </div>

                {{-- Laundry Detail --}}
                <div class="glass-card rounded-2xl p-6 soft-shadow">
                    <h3 class="text-label-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">local_laundry_service</span>
                        Detail Laundry
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-label-sm text-on-surface-variant mb-1.5 block">Layanan <span class="text-error">*</span></label>
                            <select name="layanan_id" class="w-full rounded-xl border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" id="layanan_id" required>
                                <option value="">Pilih layanan...</option>
                                @foreach($layanan as $l)
                                <option value="{{ $l->id }}" data-harga="{{ $l->harga_perkg }}" {{ old('layanan_id') == $l->id ? 'selected' : '' }}>{{ $l->nama }} - Rp {{ number_format($l->harga_perkg, 0, ',', '.') }}/kg</option>
                                @endforeach
                            </select>
                            @error('layanan_id') <p class="text-body-sm text-error mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-label-sm text-on-surface-variant mb-1.5 block">Berat (kg) <span class="text-error">*</span></label>
                            <input type="number" name="berat" id="berat" class="w-full rounded-xl border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all {{ $errors->has('berat') ? 'border-error' : '' }}" value="{{ old('berat') }}" step="0.5" min="0" required>
                            @error('berat') <p class="text-body-sm text-error mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-label-sm text-on-surface-variant mb-1.5 block">Catatan</label>
                            <textarea name="catatan" class="w-full rounded-xl border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" rows="2">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Items --}}
                <div class="glass-card rounded-2xl p-6 soft-shadow">
                    <h3 class="text-label-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">checklist</span>
                        Items Laundry
                    </h3>
                    <div id="items-container" class="space-y-2">
                        <div class="flex gap-2 items-center">
                            <input type="text" name="items[0][nama_barang]" class="flex-1 rounded-xl border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" placeholder="Nama item (misal: Kemeja)">
                            <input type="number" name="items[0][jumlah]" class="w-24 rounded-xl border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" placeholder="Jml" min="1" value="1">
                        </div>
                    </div>
                    <button type="button" id="addItem" class="mt-3 inline-flex items-center gap-1.5 text-label-sm font-bold text-primary hover:bg-primary/10 px-4 py-2 rounded-xl transition-all">
                        <span class="material-symbols-outlined text-lg">add</span> Tambah Item
                    </button>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="glass-card rounded-2xl p-6 soft-shadow sticky top-28">
                    <h3 class="text-label-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">receipt</span>
                        Ringkasan
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-body-sm">
                            <span class="text-on-surface-variant">Layanan</span>
                            <span id="ringkasan-layanan" class="font-medium">-</span>
                        </div>
                        <div class="flex justify-between text-body-sm">
                            <span class="text-on-surface-variant">Harga/kg</span>
                            <span id="ringkasan-harga" class="font-medium">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-body-sm">
                            <span class="text-on-surface-variant">Berat</span>
                            <span id="ringkasan-berat" class="font-medium">0 kg</span>
                        </div>
                        <hr class="border-outline-variant">
                        <div class="flex justify-between text-body-sm">
                            <span class="text-on-surface-variant">Diskon</span>
                            <div class="flex items-center gap-2">
                                <span class="text-label-sm">Rp</span>
                                <input type="number" name="diskon" id="diskon" value="0" min="0" class="w-24 text-right rounded-lg border border-outline-variant bg-surface-container-lowest px-2 py-1 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                            </div>
                        </div>
                        <hr class="border-outline-variant">
                        <div class="flex justify-between text-headline-sm font-bold">
                            <span>Total</span>
                            <span id="ringkasan-total" class="text-primary">Rp 0</span>
                        </div>
                        <input type="hidden" name="total" id="total_hidden">
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="flex-1 bg-primary text-on-primary px-5 py-2.5 rounded-xl font-bold inline-flex items-center justify-center gap-2 hover:bg-primary-container hover:text-on-primary-fixed-variant transition-all">
                            <span class="material-symbols-outlined">save</span> Simpan
                        </button>
                        <button type="button" id="btnCetakNota" class="bg-surface-container-high text-on-surface-variant px-5 py-2.5 rounded-xl font-bold inline-flex items-center gap-2 hover:bg-surface-container-highest transition-all">
                            <span class="material-symbols-outlined">print</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    let itemIndex = 1;

    document.getElementById('addItem')?.addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const div = document.createElement('div');
        div.className = 'flex gap-2 items-center';
        div.innerHTML = `
            <input type="text" name="items[${itemIndex}][nama_barang]" class="flex-1 rounded-xl border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" placeholder="Nama item">
            <input type="number" name="items[${itemIndex}][jumlah]" class="w-24 rounded-xl border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" placeholder="Jml" min="1" value="1">
            <button type="button" class="bg-error/10 text-error p-2 rounded-xl hover:bg-error/20 transition-all remove-item"><span class="material-symbols-outlined">close</span></button>
        `;
        container.appendChild(div);
        itemIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.flex').remove();
        }
    });

    function hitungTotal() {
        const harga = parseFloat(document.getElementById('layanan_id')?.selectedOptions[0]?.dataset?.harga || 0);
        const berat = parseFloat(document.getElementById('berat')?.value || 0);
        const diskon = parseFloat(document.getElementById('diskon')?.value || 0);
        const total = Math.max(0, (harga * berat) - diskon);
        document.getElementById('ringkasan-layanan').textContent = document.getElementById('layanan_id')?.selectedOptions[0]?.text?.split(' - ')[0] || '-';
        document.getElementById('ringkasan-harga').textContent = 'Rp ' + harga.toLocaleString('id-ID');
        document.getElementById('ringkasan-berat').textContent = berat + ' kg';
        document.getElementById('ringkasan-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('total_hidden').value = total;
    }

    document.getElementById('layanan_id')?.addEventListener('change', hitungTotal);
    document.getElementById('berat')?.addEventListener('input', hitungTotal);
    document.getElementById('diskon')?.addEventListener('input', hitungTotal);

    document.getElementById('btnCetakNota')?.addEventListener('click', function() {
        const form = document.getElementById('orderForm');
        const action = form.action;
        form.action = action + '?cetak=1';
        form.submit();
        form.action = action;
    });
</script>
@endpush
