<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Transaksi - {{ $settings->nama_app ?? 'LaundryHub' }}</title>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; padding: 24px; color: #191c1e; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 8px 12px; text-align: left; border: 1px solid #c3c6d7; font-size: 13px; }
        th { background: #eef0f2; font-weight: 600; }
        h1 { font-size: 20px; color: #004ac6; margin: 0; }
        .sub { color: #434655; font-size: 13px; margin-top: 4px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>{{ $settings->nama_app ?? 'LaundryHub' }}</h1>
    <p class="sub">Laporan Transaksi</p>
    <p class="sub">Dicetak: {{ now()->format('d M Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Berat (Kg)</th>
                <th>Harga/Kg</th>
                <th>Diskon</th>
                <th>Total</th>
                <th>Status</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $trx)
            <tr>
                <td>{{ $trx->invoice }}</td>
                <td>{{ $trx->created_at->format('d/m/Y') }}</td>
                <td>{{ $trx->pelanggan->nama ?? '-' }}</td>
                <td>{{ $trx->layanan->nama ?? '-' }}</td>
                <td class="text-right">{{ number_format($trx->berat, 1) }}</td>
                <td class="text-right">{{ number_format($trx->harga, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($trx->diskon, 0, ',', '.') }}</td>
                <td class="text-right"><strong>{{ number_format($trx->total, 0, ',', '.') }}</strong></td>
                <td>{{ ucfirst($trx->status) }}</td>
                <td>{{ $trx->status_pembayaran ?? 'belum' }}</td>
            </tr>
            @empty
            <tr><td colspan="10" style="text-align:center;padding:40px;color:#737686;">Tidak ada data transaksi</td></tr>
            @endforelse
        </tbody>
    </table>
    <script>window.print();</script>
</body>
</html>
