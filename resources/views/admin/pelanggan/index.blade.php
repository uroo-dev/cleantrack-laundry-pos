@extends('layouts.admin')

@section('title', 'Data Pelanggan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pelanggan</li>
@endsection

@section('content')
    <h2 class="intro-x text-2xl font-medium">Data Pelanggan</h2>
    <div class="intro-x flex flex-col sm:flex-row items-center mt-4 gap-3">
        <div class="w-full sm:w-auto mr-auto">
            <a href="{{ route('admin.pelanggan.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tambah Pelanggan
            </a>
        </div>
        <form method="GET" action="{{ route('admin.pelanggan.index') }}" class="w-full sm:w-auto flex gap-2">
            <input type="text" name="search" class="form-control w-full sm:w-56" placeholder="Cari pelanggan..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
    </div>
    <div class="box p-5 mt-5 intro-x overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">No</th>
                    <th class="whitespace-nowrap">Kode</th>
                    <th class="whitespace-nowrap">Nama</th>
                    <th class="whitespace-nowrap">Telepon</th>
                    <th class="whitespace-nowrap">Total Transaksi</th>
                    <th class="whitespace-nowrap">Poin</th>
                    <th class="whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggans as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="font-mono">{{ $p->kode_pelanggan }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->telepon ?? '-' }}</td>
                    <td>{{ $p->transaksi_count ?? $p->transaksis_count ?? 0 }}</td>
                    <td>{{ $p->poin ?? 0 }}</td>
                    <td class="flex gap-1">
                        <a href="{{ route('admin.pelanggan.edit', $p) }}" class="btn btn-sm btn-warning">
                            <i data-lucide="edit" class="w-3 h-3"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.pelanggan.destroy', $p) }}" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-slate-400 py-4">Tidak ada data pelanggan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5 intro-x">
        {{ $pelanggans->links() }}
    </div>
@endsection
