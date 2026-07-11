@extends('layouts.admin')

@section('title', 'Data Pegawai')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pegawai</li>
@endsection

@section('content')
    <h2 class="intro-x text-2xl font-medium">Data Pegawai</h2>
    <div class="intro-x mt-4">
        <a href="{{ route('admin.pegawai.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tambah Pegawai
        </a>
    </div>
    <div class="box p-5 mt-5 intro-x overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">No</th>
                    <th class="whitespace-nowrap">Nama</th>
                    <th class="whitespace-nowrap">Email</th>
                    <th class="whitespace-nowrap">Telepon</th>
                    <th class="whitespace-nowrap">Role</th>
                    <th class="whitespace-nowrap">Status</th>
                    <th class="whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawais as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->email }}</td>
                    <td>{{ $p->phone ?? '-' }}</td>
                    <td>
                        @if($p->role === 'admin')
                            <span class="px-2 py-1 rounded text-xs font-medium bg-primary/20 text-primary">Admin</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs font-medium bg-info/20 text-info">Staff</span>
                        @endif
                    </td>
                    <td>
                        @if($p->is_active)
                            <span class="px-2 py-1 rounded text-xs font-medium bg-success/20 text-success">Aktif</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs font-medium bg-danger/20 text-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td class="flex gap-1">
                        <a href="{{ route('admin.pegawai.edit', $p) }}" class="btn btn-sm btn-warning">
                            <i data-lucide="edit" class="w-3 h-3"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.pegawai.destroy', $p) }}" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                    <td colspan="7" class="text-center text-slate-400 py-4">Tidak ada data pegawai</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5 intro-x">
        {{ $pegawais->links() }}
    </div>
@endsection
