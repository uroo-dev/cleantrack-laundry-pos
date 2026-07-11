@extends('layouts.admin')

@section('title', 'Data Pegawai')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pegawai</li>
@endsection

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-headline-sm">Data Pegawai</h2>
        <a href="{{ route('admin.pegawai.create') }}"
            class="bg-primary text-on-primary px-5 py-2.5 rounded-xl font-bold text-label-sm inline-flex items-center gap-2 hover:opacity-90 transition">
            <span class="material-symbols-outlined text-lg">add</span> Tambah Pegawai
        </a>
    </div>

    <div class="glass-card rounded-2xl p-6 soft-shadow overflow-x-auto">
        <table class="w-full text-body-sm">
            <thead>
                <tr class="border-b border-outline-variant/30 text-label-sm text-outline">
                    <th class="text-left py-3 px-2 whitespace-nowrap">No</th>
                    <th class="text-left py-3 px-2 whitespace-nowrap">Nama</th>
                    <th class="text-left py-3 px-2 whitespace-nowrap">Email</th>
                    <th class="text-left py-3 px-2 whitespace-nowrap">Telepon</th>
                    <th class="text-left py-3 px-2 whitespace-nowrap">Role</th>
                    <th class="text-left py-3 px-2 whitespace-nowrap">Status</th>
                    <th class="text-left py-3 px-2 whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawais as $p)
                <tr class="border-b border-outline-variant/10 hover:bg-surface-container/50 transition">
                    <td class="py-3 px-2">{{ $loop->iteration }}</td>
                    <td class="py-3 px-2 font-medium">{{ $p->name }}</td>
                    <td class="py-3 px-2">{{ $p->email }}</td>
                    <td class="py-3 px-2">{{ $p->phone ?? '-' }}</td>
                    <td class="py-3 px-2">
                        @if($p->role === 'admin')
                            <span class="inline-block px-3 py-1 rounded-lg text-label-sm font-medium bg-primary/20 text-primary">Admin</span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-lg text-label-sm font-medium bg-info/20 text-info">Staff</span>
                        @endif
                    </td>
                    <td class="py-3 px-2">
                        @if($p->is_active)
                            <span class="inline-block px-3 py-1 rounded-lg text-label-sm font-medium bg-success/20 text-success">Aktif</span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-lg text-label-sm font-medium bg-error/20 text-error">Nonaktif</span>
                        @endif
                    </td>
                    <td class="py-3 px-2">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.pegawai.edit', $p) }}"
                                class="bg-warning/20 text-warning px-3 py-2 rounded-xl inline-flex items-center gap-1 text-label-sm font-bold hover:bg-warning/30 transition">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <button type="button"
                                class="bg-error/20 text-error px-3 py-2 rounded-xl inline-flex items-center gap-1 text-label-sm font-bold hover:bg-error/30 transition btn-delete"
                                data-url="{{ route('admin.pegawai.destroy', $p) }}">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-outline py-6">Tidak ada data pegawai</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($pegawais, 'links'))
    <div class="mt-6">
        {{ $pegawais->links() }}
    </div>
    @endif

    <form id="deleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = url;
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
