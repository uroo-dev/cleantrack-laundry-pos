@extends('layouts.admin')

@section('title', 'Pelanggan')
@section('page-title', 'Pelanggan')
@section('page-description', 'Kelola data pelanggan laundry')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-8">
    <div class="glass-card rounded-2xl p-6 soft-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-label-sm text-on-surface-variant">Total Pelanggan</p>
                <p class="text-headline-sm font-bold mt-1">{{ number_format($totalPelanggan) }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-primary-container/40 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">group</span>
            </div>
        </div>
    </div>
    <div class="glass-card rounded-2xl p-6 soft-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-label-sm text-on-surface-variant">Pelanggan Aktif</p>
                <p class="text-headline-sm font-bold mt-1">{{ number_format($pelangganAktif) }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-secondary-container/40 flex items-center justify-center">
                <span class="material-symbols-outlined text-secondary">person_check</span>
            </div>
        </div>
    </div>
    <div class="glass-card rounded-2xl p-6 soft-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-label-sm text-on-surface-variant">Rata-rata Order</p>
                <p class="text-headline-sm font-bold mt-1">{{ number_format($rataOrder) }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-tertiary-container/40 flex items-center justify-center">
                <span class="material-symbols-outlined text-tertiary">order_approve</span>
            </div>
        </div>
    </div>
    <div class="glass-card rounded-2xl p-6 soft-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-label-sm text-on-surface-variant">Loyalitas Tinggi</p>
                <p class="text-headline-sm font-bold mt-1">{{ number_format($loyalitasTinggi) }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-error-container/40 flex items-center justify-center">
                <span class="material-symbols-outlined text-error">workspace_premium</span>
            </div>
        </div>
    </div>
</div>

<div class="glass-card rounded-2xl overflow-hidden soft-shadow">
    <div class="p-6 border-b border-outline-variant/20">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3 flex-1 max-w-md">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input type="text" id="searchPelanggan" placeholder="Cari nama atau telepon..." class="w-full pl-10 pr-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40 transition-all">
                </div>
            </div>
            <button onclick="openModal('addPelangganModal')" class="bg-primary text-on-primary px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all">
                <span class="material-symbols-outlined">add</span> Tambah Pelanggan
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full" id="pelangganTable">
            <thead>
                <tr class="text-label-sm text-on-surface-variant border-b border-outline-variant/30 bg-surface-container-low/50">
                    <th class="text-left py-4 px-6">Pelanggan</th>
                    <th class="text-left py-4 px-6">Telepon</th>
                    <th class="text-left py-4 px-6">Total Order</th>
                    <th class="text-left py-4 px-6">Total Spending</th>
                    <th class="text-left py-4 px-6">Terakhir Order</th>
                    <th class="text-right py-4 px-6">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggan as $p)
                <tr class="border-b border-outline-variant/20 hover:bg-surface-container-low transition-all pelanggan-row">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-on-primary-fixed-variant flex-shrink-0">
                                {{ strtoupper(substr($p->nama, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-label-lg font-bold">{{ $p->nama }}</p>
                                <p class="text-label-sm text-on-surface-variant">{{ $p->alamat ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <a href="{{ \App\Services\WhatsAppService::generateLink($p->telepon ?? '') }}" target="_blank" class="flex items-center gap-2 text-label-sm text-primary hover:underline">
                            <span class="material-symbols-outlined text-sm">chat</span>
                            {{ $p->telepon ?? '-' }}
                        </a>
                    </td>
                    <td class="py-4 px-6 text-body-sm font-bold">{{ $p->transaksi_count ?? $p->transaksis_count ?? 0 }}</td>
                    <td class="py-4 px-6 text-body-sm">Rp {{ number_format($p->total_spending ?? 0, 0, ',', '.') }}</td>
                    <td class="py-4 px-6 text-body-sm text-on-surface-variant">{{ $p->transaksi_terakhir ? \Carbon\Carbon::parse($p->transaksi_terakhir)->format('d M Y') : '-' }}</td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ \App\Services\WhatsAppService::generateLink($p->telepon ?? '', 'Halo ' . $p->nama . ', ini dari Laundry!') }}" target="_blank" class="p-2 hover:bg-surface-container-high rounded-lg transition-all" title="Chat WhatsApp">
                                <span class="material-symbols-outlined text-tertiary">chat</span>
                            </a>
                            <a href="{{ route('admin.pelanggan.edit', $p->id) }}" class="p-2 hover:bg-surface-container-high rounded-lg transition-all" title="Edit">
                                <span class="material-symbols-outlined text-primary">edit</span>
                            </a>
                            <form action="{{ route('admin.pelanggan.destroy', $p->id) }}" method="POST" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, '{{ $p->nama }}')" class="p-2 hover:bg-surface-container-high rounded-lg transition-all" title="Hapus">
                                    <span class="material-symbols-outlined text-error">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-16">
                        <span class="material-symbols-outlined text-5xl text-on-surface-variant opacity-20 mb-3">group_off</span>
                        <p class="text-body-sm text-on-surface-variant">Belum ada pelanggan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-outline-variant/20">
        {{ $pelanggan->links() }}
    </div>
</div>

{{-- Add Customer Modal --}}
<div id="addPelangganModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" style="background: rgba(0,0,0,0.3); backdrop-filter: blur(4px);">
    <div class="glass-card rounded-2xl w-full max-w-lg soft-shadow max-h-[90vh] overflow-y-auto custom-scrollbar" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-outline-variant/20 flex items-center justify-between">
            <h3 class="text-headline-sm">Tambah Pelanggan</h3>
            <button onclick="closeModal('addPelangganModal')" class="p-2 hover:bg-surface-container-high rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.pelanggan.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="text-label-sm text-on-surface-variant mb-1 block">Nama Pelanggan *</label>
                <input type="text" name="nama" required class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="text-label-sm text-on-surface-variant mb-1 block">Nomor Telepon *</label>
                <input type="text" name="telepon" required class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                <p class="text-label-sm text-on-surface-variant mt-1">Contoh: 08123456789</p>
            </div>
            <div>
                <label class="text-label-sm text-on-surface-variant mb-1 block">Alamat</label>
                <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40"></textarea>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="flex-1 bg-primary text-on-primary py-2.5 rounded-xl font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all">Simpan</button>
                <button type="button" onclick="closeModal('addPelangganModal')" class="flex-1 bg-surface-container-high text-on-surface py-2.5 rounded-xl font-bold hover:scale-[1.02] transition-all">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
document.querySelectorAll('#addPelangganModal, #editPelangganModal').forEach(m => {
    m.addEventListener('click', () => m.classList.add('hidden'));
});

function confirmDelete(e, nama) {
    Swal.fire({
        title: 'Hapus ' + nama + '?',
        text: "Data pelanggan akan dihapus permanen",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ba1a1a',
        cancelButtonColor: '#e0e3e5',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(r => { if (r.isConfirmed) e.target.closest('form').submit(); });
}

document.getElementById('searchPelanggan')?.addEventListener('keyup', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.pelanggan-row').forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endpush
