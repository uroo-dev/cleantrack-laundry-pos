@extends('layouts.auth')

@section('title', 'Tracking Laundry')

@section('auth-content')
    <div class="w-full max-w-2xl mx-auto">
        @if(isset($transaksi))
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">Tracking Laundry</h1>
            <p class="font-mono text-lg text-primary mt-1">{{ $transaksi->kode_transaksi }}</p>
        </div>

        <div class="box p-6 intro-x mb-6">
            @php
                $steps = ['Diterima', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];
                $statusMap = ['menunggu' => 0, 'dicuci' => 1, 'dikeringkan' => 2, 'disetrika' => 3, 'selesai' => 4, 'diambil' => 5];
                $current = $statusMap[$transaksi->status] ?? 0;
                $maxIdx = count($steps) - 1;
                $progressPercent = min(100, ($current / $maxIdx) * 100);
            @endphp

            <div class="w-full bg-slate-200 rounded-full h-3 mb-6">
                <div class="bg-primary h-3 rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
            </div>
            <p class="text-center text-lg font-medium mb-6">{{ $progressPercent }}% Selesai</p>

            <div class="space-y-4">
                @foreach($steps as $i => $step)
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg {{ $i <= $current ? 'bg-primary text-white' : 'bg-slate-100' }}">
                        {{ $i <= $current ? '✓' : $emojis[$i] }}
                    </div>
                    <div>
                        <p class="font-medium {{ $i <= $current ? 'text-primary' : 'text-slate-400' }}">{{ $step }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="box p-5 intro-x mb-6">
            <h3 class="font-medium mb-3">Detail Transaksi</h3>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><span class="text-slate-400">Tanggal:</span> {{ $transaksi->created_at->format('d/m/Y H:i') }}</div>
                <div><span class="text-slate-400">Layanan:</span> {{ $transaksi->layanan->nama ?? '-' }}</div>
                <div><span class="text-slate-400">Berat:</span> {{ $transaksi->berat }} kg</div>
                <div><span class="text-slate-400">Harga:</span> Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</div>
                @if($transaksi->diskon)
                <div><span class="text-slate-400">Diskon:</span> -Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</div>
                @endif
                <div><span class="text-slate-400 font-bold">Total:</span> <span class="font-bold text-primary">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span></div>
                <div><span class="text-slate-400">Status:</span> {{ ucfirst($transaksi->status) }}</div>
                @if($transaksi->catatan)
                <div class="col-span-2"><span class="text-slate-400">Catatan:</span> {{ $transaksi->catatan }}</div>
                @endif
            </div>
        </div>

        @if($transaksi->detailLaundries->count())
        <div class="box p-5 intro-x mb-6">
            <h3 class="font-medium mb-3">Items Laundry</h3>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Item</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->detailLaundries as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->jumlah }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="box p-5 intro-x text-center">
            <h3 class="font-medium mb-3">Beri Rating</h3>
            <form method="POST" action="{{ route('customer.rate') }}">
                @csrf
                <input type="hidden" name="kode_transaksi" value="{{ $transaksi->kode_transaksi }}">
                <div class="flex justify-center gap-1 text-2xl" id="ratingStars">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" class="star" data-value="{{ $i }}" style="color: #ddd; transition: color 0.2s;">★</button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingValue" value="0">
                <p class="text-xs text-slate-400 mt-2">Klik bintang untuk memberi rating</p>
                <button type="submit" class="btn btn-sm btn-primary mt-2">Kirim Rating</button>
            </form>
        </div>

        @else
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">Tracking Laundry</h1>
            <p class="text-slate-400 mt-1">Masukkan kode transaksi untuk melihat status</p>
        </div>

        <div class="box p-8 intro-x">
            <form method="POST" action="{{ route('customer.tracking.cek') }}" class="flex gap-2">
                @csrf
                <input type="text" name="kode_transaksi" class="form-control flex-1 text-lg text-center" placeholder="Contoh: 000001" required>
                <button type="submit" class="btn btn-primary px-6">CEK</button>
            </form>
        </div>

        @if(request('kode_transaksi'))
        <div class="box p-8 intro-x mt-4 text-center">
            <p class="text-slate-400">Transaksi dengan kode "{{ request('kode_transaksi') }}" tidak ditemukan.</p>
        </div>
        @endif
        @endif

        <div class="text-center mt-6">
            <a href="{{ route('customer.dashboard') }}" class="text-primary text-sm">← Kembali ke Dashboard</a>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function() {
            const value = this.dataset.value;
            document.querySelectorAll('.star').forEach(s => {
                s.style.color = s.dataset.value <= value ? '#f59e0b' : '#ddd';
            });
            document.getElementById('ratingValue').value = value;
        });
    });
</script>
@endpush
