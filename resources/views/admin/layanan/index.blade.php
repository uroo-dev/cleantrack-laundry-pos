@extends('layouts.admin')

@section('title', 'Data Layanan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Layanan</li>
@endsection

@section('content')
    <h2 class="intro-x text-2xl font-medium">Data Layanan</h2>
    <div class="intro-x mt-4">
        <a href="{{ route('admin.layanan.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tambah Layanan
        </a>
    </div>
    <div class="box p-5 mt-5 intro-x overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">No</th>
                    <th class="whitespace-nowrap">Nama Layanan</th>
                    <th class="whitespace-nowrap">Harga/kg</th>
                    <th class="whitespace-nowrap">Estimasi (Hari)</th>
                    <th class="whitespace-nowrap">Status</th>
                    <th class="whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($layanans as $l)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $l->nama }}</td>
                    <td>Rp {{ number_format($l->harga_perkg, 0, ',', '.') }}</td>
                    <td>{{ $l->estimasi_hari }} hari</td>
                    <td>
                        @if($l->is_active)
                            <span class="px-2 py-1 rounded text-xs font-medium bg-success/20 text-success">Aktif</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs font-medium bg-danger/20 text-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td class="flex gap-1">
                        <a href="{{ route('admin.layanan.edit', $l) }}" class="btn btn-sm btn-warning">
                            <i data-lucide="edit" class="w-3 h-3"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.layanan.destroy', $l) }}" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                    <td colspan="6" class="text-center text-slate-400 py-4">Tidak ada data layanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5 intro-x">
        {{ $layanans->links() }}
    </div>
@endsection
