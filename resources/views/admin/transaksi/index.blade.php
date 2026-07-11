@extends('layouts.admin')

@section('title', 'Data Transaksi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Transaksi</li>
@endsection

@section('content')
    <h2 class="intro-x text-2xl font-medium">Data Transaksi</h2>
    <div class="intro-x flex flex-col sm:flex-row items-center mt-4 gap-3">
        <form method="GET" action="{{ route('admin.transaksi.index') }}" class="w-full sm:w-auto flex gap-2">
            <select name="status" class="form-control w-full sm:w-44">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="dicuci" {{ request('status') == 'dicuci' ? 'selected' : '' }}>Dicuci</option>
                <option value="dikeringkan" {{ request('status') == 'dikeringkan' ? 'selected' : '' }}>Dikeringkan</option>
                <option value="disetrika" {{ request('status') == 'disetrika' ? 'selected' : '' }}>Disetrika</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
    </div>
    <div class="box p-5 mt-5 intro-x overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">No</th>
                    <th class="whitespace-nowrap">Kode</th>
                    <th class="whitespace-nowrap">Pelanggan</th>
                    <th class="whitespace-nowrap">Layanan</th>
                    <th class="whitespace-nowrap">Berat</th>
                    <th class="whitespace-nowrap">Total</th>
                    <th class="whitespace-nowrap">Status</th>
                    <th class="whitespace-nowrap">Tanggal</th>
                    <th class="whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="font-mono">{{ $t->kode_transaksi }}</td>
                    <td>{{ $t->pelanggan->nama ?? '-' }}</td>
                    <td>{{ $t->layanan->nama ?? '-' }}</td>
                    <td>{{ $t->berat }} kg</td>
                    <td>Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $badge = match($t->status) {
                                'menunggu' => 'bg-warning/20 text-warning border-warning/20',
                                'dicuci' => 'bg-info/20 text-info border-info/20',
                                'dikeringkan' => 'bg-purple-200/20 text-purple-700 border-purple-200/20',
                                'disetrika' => 'bg-orange-200/20 text-orange-700 border-orange-200/20',
                                'selesai' => 'bg-success/20 text-success border-success/20',
                                'diambil' => 'bg-slate-300/20 text-slate-600 border-slate-300/20',
                                default => 'bg-slate-100 text-slate-600 border-slate-100'
                            };
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-medium border {{ $badge }}">{{ ucfirst($t->status) }}</span>
                    </td>
                    <td>{{ $t->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.transaksi.show', $t) }}" class="btn btn-sm btn-primary">
                            <i data-lucide="eye" class="w-3 h-3"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-slate-400 py-4">Tidak ada data transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5 intro-x">
        {{ $transaksis->links() }}
    </div>
@endsection
