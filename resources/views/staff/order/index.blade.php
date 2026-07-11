@extends('layouts.admin')

@section('title', 'Input Order Baru')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('staff.order.queue') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Order Baru</li>
@endsection

@section('content')
    <h2 class="intro-x text-2xl font-medium">Input Order Baru</h2>

    <form method="POST" action="{{ route('staff.order.store') }}" id="orderForm">
        @csrf
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 lg:col-span-7">
                <div class="box p-5 intro-x">
                    <h3 class="text-base font-medium border-b border-slate-200 pb-3">Data Pelanggan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                            <select name="pelanggan_id" class="form-control tom-select" required>
                                <option value="">Cari pelanggan...</option>
                                @foreach($pelanggan as $p)
                                <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }} - {{ $p->telepon }}</option>
                                @endforeach
                            </select>
                            @error('pelanggan_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div>
                            <label class="form-label">No. HP</label>
                            <input type="text" id="no_hp" class="form-control" readonly placeholder="Otomatis terisi">
                        </div>
                    </div>
                </div>

                <div class="box p-5 mt-5 intro-x">
                    <h3 class="text-base font-medium border-b border-slate-200 pb-3">Detail Laundry</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="form-label">Layanan <span class="text-danger">*</span></label>
                            <select name="layanan_id" class="form-control tom-select" id="layanan_id" required>
                                <option value="">Pilih layanan...</option>
                                @foreach($layanan as $l)
                                <option value="{{ $l->id }}" data-harga="{{ $l->harga_perkg }}" {{ old('layanan_id') == $l->id ? 'selected' : '' }}>{{ $l->nama }} - Rp {{ number_format($l->harga_perkg, 0, ',', '.') }}/kg</option>
                                @endforeach
                            </select>
                            @error('layanan_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div>
                            <label class="form-label">Berat (kg) <span class="text-danger">*</span></label>
                            <input type="number" name="berat" id="berat" class="form-control {{ $errors->has('berat') ? 'border-danger' : '' }}" value="{{ old('berat') }}" step="0.5" min="0" required>
                            @error('berat') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="2">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="box p-5 mt-5 intro-x">
                    <h3 class="text-base font-medium border-b border-slate-200 pb-3">Items Laundry</h3>
                    <div id="items-container">
                        <div class="flex gap-2 items-center mb-2">
                            <input type="text" name="items[0][nama]" class="form-control flex-1" placeholder="Nama item (misal: Kemeja)">
                            <input type="number" name="items[0][jumlah]" class="form-control w-20" placeholder="Jml" min="1" value="1">
                        </div>
                    </div>
                    <button type="button" id="addItem" class="btn btn-sm btn-outline-secondary mt-2">
                        <i data-lucide="plus" class="w-3 h-3 mr-1"></i> Tambah Item
                    </button>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-5">
                <div class="box p-5 intro-x sticky top-5">
                    <h3 class="text-base font-medium border-b border-slate-200 pb-3">Ringkasan</h3>
                    <div class="mt-4 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Layanan</span>
                            <span id="ringkasan-layanan">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Harga/kg</span>
                            <span id="ringkasan-harga">Rp 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Berat</span>
                            <span id="ringkasan-berat">0 kg</span>
                        </div>
                        <hr>
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span id="ringkasan-total" class="text-primary">Rp 0</span>
                        </div>
                        <input type="hidden" name="total" id="total_hidden">
                    </div>
                    <div class="mt-6 flex gap-2">
                        <button type="submit" class="btn btn-primary flex-1">
                            <i data-lucide="save" class="w-4 h-4 mr-1"></i> Simpan
                        </button>
                        <button type="button" id="btnCetakNota" class="btn btn-secondary">
                            <i data-lucide="printer" class="w-4 h-4 mr-1"></i> Cetak Nota
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
        div.className = 'flex gap-2 items-center mb-2';
        div.innerHTML = `
            <input type="text" name="items[${itemIndex}][nama]" class="form-control flex-1" placeholder="Nama item">
            <input type="number" name="items[${itemIndex}][jumlah]" class="form-control w-20" placeholder="Jml" min="1" value="1">
            <button type="button" class="btn btn-sm btn-danger remove-item"><i data-lucide="x" class="w-3 h-3"></i></button>
        `;
        container.appendChild(div);
        lucide.createIcons();
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
        const total = harga * berat;
        document.getElementById('ringkasan-layanan').textContent = document.getElementById('layanan_id')?.selectedOptions[0]?.text?.split(' - ')[0] || '-';
        document.getElementById('ringkasan-harga').textContent = 'Rp ' + harga.toLocaleString('id-ID');
        document.getElementById('ringkasan-berat').textContent = berat + ' kg';
        document.getElementById('ringkasan-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('total_hidden').value = total;
    }

    document.getElementById('layanan_id')?.addEventListener('change', hitungTotal);
    document.getElementById('berat')?.addEventListener('input', hitungTotal);

    document.getElementById('btnCetakNota')?.addEventListener('click', function() {
        const form = document.getElementById('orderForm');
        const action = form.action;
        form.action = action + '?cetak=1';
        form.submit();
        form.action = action;
    });
</script>
@endpush
