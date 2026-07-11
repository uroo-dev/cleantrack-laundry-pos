@extends('layouts.auth')

@section('title', 'Tracking Laundry')

@section('auth-content')
    <div class="w-full">
        @if(isset($transaksi))
        <div class="text-center mb-6">
            <span class="material-symbols-outlined text-5xl mb-2" style="color: #004ac6;">package_2</span>
            <h1 class="text-2xl font-bold" style="color: #191c1e;">Tracking Laundry</h1>
            <p class="font-mono text-lg font-semibold mt-1" style="color: #004ac6;">{{ $transaksi->kode_transaksi }}</p>
        </div>

        <div class="glass-card p-6 mb-6">
            @php
                $steps = ['Diterima', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];
                $statusMap = ['menunggu' => 0, 'dicuci' => 1, 'dikeringkan' => 2, 'disetrika' => 3, 'selesai' => 4, 'diambil' => 5];
                $current = $statusMap[$transaksi->status] ?? 0;
                $maxIdx = count($steps) - 1;
                $progressPercent = min(100, ($current / $maxIdx) * 100);
            @endphp

            <div class="w-full rounded-full h-3 mb-6" style="background: #eceef0;">
                <div class="h-3 rounded-full transition-all" style="width: {{ $progressPercent }}%; background: #004ac6;"></div>
            </div>
            <p class="text-center text-lg font-semibold mb-6" style="color: #191c1e;">{{ number_format($progressPercent, 0) }}% Selesai</p>

            <div class="space-y-4">
                @foreach($steps as $i => $step)
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold"
                        style="background: {{ $i <= $current ? '#004ac6' : '#eceef0' }}; color: {{ $i <= $current ? '#ffffff' : '#9ca3af' }};">
                        @if($i <= $current)
                            <span class="material-symbols-outlined" style="font-size: 20px;">check</span>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-sm" style="color: {{ $i <= $current ? '#004ac6' : '#9ca3af' }};">{{ $step }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="glass-card p-5 mb-6">
            <h3 class="font-semibold mb-4 flex items-center gap-2" style="color: #191c1e;">
                <span class="material-symbols-outlined" style="color: #004ac6;">description</span>
                Detail Transaksi
            </h3>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div style="color: #6b7280;">Tanggal</div>
                <div class="font-medium" style="color: #191c1e;">{{ $transaksi->created_at->format('d/m/Y H:i') }}</div>
                <div style="color: #6b7280;">Layanan</div>
                <div class="font-medium" style="color: #191c1e;">{{ $transaksi->layanan->nama ?? '-' }}</div>
                <div style="color: #6b7280;">Berat</div>
                <div class="font-medium" style="color: #191c1e;">{{ $transaksi->berat }} kg</div>
                <div style="color: #6b7280;">Harga</div>
                <div class="font-medium" style="color: #191c1e;">Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</div>
                @if($transaksi->diskon)
                <div style="color: #6b7280;">Diskon</div>
                <div class="font-medium" style="color: #dc2626;">-Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</div>
                @endif
                <div style="color: #6b7280;">Total</div>
                <div class="font-bold" style="color: #004ac6;">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</div>
                <div style="color: #6b7280;">Status</div>
                <div>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium"
                        style="background: rgba(0, 74, 198, 0.08); color: #004ac6;">
                        {{ ucfirst($transaksi->status) }}
                    </span>
                </div>
                @if($transaksi->catatan)
                <div style="color: #6b7280;">Catatan</div>
                <div class="font-medium" style="color: #191c1e;">{{ $transaksi->catatan }}</div>
                @endif
            </div>
        </div>

        @if($transaksi->detailLaundries->count())
        <div class="glass-card p-5 mb-6">
            <h3 class="font-semibold mb-3 flex items-center gap-2" style="color: #191c1e;">
                <span class="material-symbols-outlined" style="color: #004ac6;">checklist</span>
                Items Laundry
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left" style="color: #6b7280; border-bottom: 1px solid rgba(194, 199, 203, 0.3);">
                            <th class="pb-3 font-semibold">No</th>
                            <th class="pb-3 font-semibold">Nama Item</th>
                            <th class="pb-3 font-semibold">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->detailLaundries as $item)
                        <tr style="border-bottom: 1px solid rgba(194, 199, 203, 0.15);">
                            <td class="py-3">{{ $loop->iteration }}</td>
                            <td class="py-3">{{ $item->nama_barang }}</td>
                            <td class="py-3">{{ $item->jumlah }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="glass-card p-5 mb-6 text-center">
            <h3 class="font-semibold mb-3 flex items-center justify-center gap-2" style="color: #191c1e;">
                <span class="material-symbols-outlined" style="color: #004ac6;">star</span>
                Beri Rating
            </h3>
            <form method="POST" action="{{ route('customer.rate') }}">
                @csrf
                <input type="hidden" name="kode_transaksi" value="{{ $transaksi->kode_transaksi }}">
                <div class="flex justify-center gap-2" id="ratingStars">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" class="star text-3xl transition-all hover:scale-110" data-value="{{ $i }}" style="color: #d1d5db;">★</button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingValue" value="0">
                <p class="text-xs mt-2" style="color: #9ca3af;">Klik bintang untuk memberi rating</p>
                <button type="submit"
                    class="mt-3 px-6 py-2.5 rounded-xl font-semibold text-sm transition-all hover:brightness-110 active:scale-[0.98]"
                    style="background: #004ac6; color: #ffffff;">
                    <span class="material-symbols-outlined align-middle mr-1">send</span>
                    Kirim Rating
                </button>
            </form>
        </div>

        @else
        <div class="text-center mb-6">
            <span class="material-symbols-outlined text-5xl mb-2" style="color: #004ac6;">search</span>
            <h1 class="text-2xl font-bold" style="color: #191c1e;">Tracking Laundry</h1>
            <p class="text-sm mt-1" style="color: #6b7280;">Masukkan kode transaksi untuk melihat status</p>
        </div>

        <div class="glass-card p-8">
            <form method="POST" action="{{ route('customer.tracking.cek') }}" class="flex gap-2">
                @csrf
                <input type="text" name="kode_transaksi"
                    class="flex-1 px-4 py-2.5 rounded-xl border text-center"
                    style="background: #eceef0; border-color: rgba(194, 199, 203, 0.4); color: #191c1e;"
                    placeholder="Contoh: 000001" required>
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-1.5 transition-all hover:brightness-110 active:scale-[0.98]"
                    style="background: #004ac6; color: #ffffff;">
                    <span class="material-symbols-outlined">search</span>
                    CEK
                </button>
            </form>
        </div>

        @if(request('kode_transaksi'))
        <div class="glass-card p-6 mt-4 text-center">
            <span class="material-symbols-outlined text-3xl block mb-2" style="color: #f59e0b;">info</span>
            <p style="color: #6b7280;">Transaksi dengan kode "{{ request('kode_transaksi') }}" tidak ditemukan.</p>
        </div>
        @endif
        @endif

        <div class="text-center mt-6">
            <a href="{{ route('customer.dashboard') }}" class="text-sm font-medium inline-flex items-center gap-1 transition-all hover:brightness-110" style="color: #004ac6;">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function() {
            const value = this.dataset.value;
            document.querySelectorAll('.star').forEach(s => {
                s.style.color = s.dataset.value <= value ? '#f59e0b' : '#d1d5db';
            });
            document.getElementById('ratingValue').value = value;
        });
    });
</script>
@endpush
