@extends('layouts.print')

@section('title', 'Nota Laundry')

@section('content')
    <div class="max-w-lg mx-auto p-8 bg-white" id="nota">
        <div class="text-center border-b border-gray-300 pb-4 mb-4">
            <h1 class="text-2xl font-bold">{{ $settings->nama_app ?? 'LaundryKu' }}</h1>
            <p class="text-sm text-gray-500">{{ $settings->alamat ?? '' }}</p>
            <p class="text-sm text-gray-500">Telp: {{ $settings->telepon ?? '' }}</p>
        </div>

        <div class="flex justify-between text-sm mb-4">
            <div>
                <p><strong>Kode:</strong> {{ $transaksi->kode_transaksi }}</p>
                <p><strong>Tanggal:</strong> {{ $transaksi->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="text-right">
                <p><strong>Pelanggan:</strong> {{ $transaksi->pelanggan->nama ?? '-' }}</p>
                <p><strong>Telepon:</strong> {{ $transaksi->pelanggan->telepon ?? '-' }}</p>
            </div>
        </div>

        <table class="w-full text-sm border-collapse mb-4">
            <thead>
                <tr class="border-b border-gray-300">
                    <th class="text-left py-2">Item</th>
                    <th class="text-center py-2">Jml</th>
                    <th class="text-right py-2">Harga</th>
                    <th class="text-right py-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @if($transaksi->detailLaundries->count())
                    @foreach($transaksi->detailLaundries as $item)
                    <tr>
                        <td class="py-1">{{ $item->nama_barang }}</td>
                        <td class="text-center py-1">{{ $item->jumlah }}</td>
                        <td class="text-right py-1">-</td>
                        <td class="text-right py-1">-</td>
                    </tr>
                    @endforeach
                @endif
                <tr class="border-t border-gray-200">
                    <td colspan="2">{{ $transaksi->layanan->nama ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($transaksi->layanan->harga_perkg ?? 0, 0, ',', '.') }}/kg</td>
                    <td class="text-right">Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="border-t border-gray-300 font-bold">
                    <td colspan="2" class="py-2">Berat: {{ $transaksi->berat }} kg</td>
                    <td class="text-right py-2">Total</td>
                    <td class="text-right py-2">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                </tr>
                @if($transaksi->diskon)
                <tr>
                    <td colspan="2"></td>
                    <td class="text-right text-red-500">Diskon</td>
                    <td class="text-right text-red-500">-Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="border-t-2 border-gray-300 font-bold text-lg">
                    <td colspan="3" class="py-2">Grand Total</td>
                    <td class="text-right py-2">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        @if($transaksi->catatan)
        <div class="text-sm mb-4">
            <p><strong>Catatan:</strong> {{ $transaksi->catatan }}</p>
        </div>
        @endif

        <div class="text-sm mb-4">
            <p><strong>Status:</strong> {{ ucfirst($transaksi->status) }}</p>
        </div>

        <div class="text-center text-sm text-gray-500 border-t border-gray-300 pt-4 mt-4">
            <p>Terima kasih telah menggunakan jasa {{ $settings->nama_app ?? 'LaundryKu' }}!</p>
            <p class="text-xs mt-1">Barang yang sudah dicuci tidak dapat ditukar. Kami tidak bertanggung jawab atas barang yang tertinggal.</p>
        </div>

        <div class="text-center mt-8 no-print">
            <button onclick="window.print()" class="btn btn-primary">Cetak</button>
            <button onclick="window.close()" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
@endsection
