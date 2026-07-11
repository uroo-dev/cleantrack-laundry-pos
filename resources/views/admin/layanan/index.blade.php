@extends('layouts.admin')

@section('title', 'Data Layanan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Layanan</li>
@endsection

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-headline-sm">Data Layanan</h2>
        <a href="{{ route('admin.layanan.create') }}"
            class="bg-primary text-on-primary px-5 py-2.5 rounded-xl font-bold text-label-sm inline-flex items-center gap-2 hover:opacity-90 transition">
            <span class="material-symbols-outlined text-lg">add</span> Tambah Layanan
        </a>
    </div>

    <div class="glass-card rounded-2xl p-6 soft-shadow overflow-x-auto">
        <table class="w-full text-body-sm">
            <thead>
                <tr class="border-b border-outline-variant/30 text-label-sm text-outline">
                    <th class="text-left py-3 px-2 whitespace-nowrap">No</th>
                    <th class="text-left py-3 px-2 whitespace-nowrap">Nama Layanan</th>
                    <th class="text-left py-3 px-2 whitespace-nowrap">Harga/kg</th>
                    <th class="text-left py-3 px-2 whitespace-nowrap">Estimasi (Hari)</th>
                    <th class="text-left py-3 px-2 whitespace-nowrap">Status</th>
                    <th class="text-left py-3 px-2 whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($layanans as $l)
                <tr class="border-b border-outline-variant/10 hover:bg-surface-container/50 transition">
                    <td class="py-3 px-2">{{ $loop->iteration }}</td>
                    <td class="py-3 px-2 font-medium">{{ $l->nama }}</td>
                    <td class="py-3 px-2">Rp {{ number_format($l->harga_perkg, 0, ',', '.') }}</td>
                    <td class="py-3 px-2">{{ $l->estimasi_hari }} hari</td>
                    <td class="py-3 px-2">
                        @if($l->is_active)
                            <span class="inline-block px-3 py-1 rounded-lg text-label-sm font-medium bg-success/20 text-success">Aktif</span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-lg text-label-sm font-medium bg-error/20 text-error">Nonaktif</span>
                        @endif
                    </td>
                    <td class="py-3 px-2">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.layanan.edit', $l) }}"
                                class="bg-warning/20 text-warning px-3 py-2 rounded-xl inline-flex items-center gap-1 text-label-sm font-bold hover:bg-warning/30 transition">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <button type="button"
                                class="bg-error/20 text-error px-3 py-2 rounded-xl inline-flex items-center gap-1 text-label-sm font-bold hover:bg-error/30 transition btn-delete"
                                data-url="{{ route('admin.layanan.destroy', $l) }}">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-outline py-6">Tidak ada data layanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($layanans, 'links'))
    <div class="mt-6">
        {{ $layanans->links() }}
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
