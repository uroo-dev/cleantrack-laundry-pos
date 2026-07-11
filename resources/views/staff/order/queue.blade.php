@extends('layouts.admin')

@section('title', 'Progress Cucian')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('staff.order.queue') }}">Staff</a></li>
    <li class="breadcrumb-item active" aria-current="page">Progress Cucian</li>
@endsection

@php
$statusConfig = [
    'menunggu' => ['label' => 'Antre', 'icon' => 'schedule', 'color' => 'outline-variant', 'bg' => 'bg-surface-container', 'textColor' => 'text-on-surface-variant'],
    'dicuci' => ['label' => 'Dicuci', 'icon' => 'local_laundry_service', 'color' => 'primary', 'bg' => 'bg-primary/10', 'textColor' => 'text-primary'],
    'dikeringkan' => ['label' => 'Dikeringkan', 'icon' => 'air', 'color' => 'primary', 'bg' => 'bg-primary/10', 'textColor' => 'text-primary'],
    'disetrika' => ['label' => 'Disetrika', 'icon' => 'iron', 'color' => 'secondary', 'bg' => 'bg-secondary-fixed/30', 'textColor' => 'text-secondary'],
    'selesai' => ['label' => 'Selesai', 'icon' => 'check_circle', 'color' => 'tertiary', 'bg' => 'bg-tertiary-fixed-dim/30', 'textColor' => 'text-tertiary'],
    'diambil' => ['label' => 'Diambil', 'icon' => 'inventory_2', 'color' => 'surface-variant', 'bg' => 'bg-surface-container-high', 'textColor' => 'text-on-surface-variant'],
];

$filterTabs = [
    'semua' => 'Semua',
    'dicuci' => 'Sesi Cuci',
    'disetrika' => 'Sesi Setrika',
    'selesai' => 'Sesi Selesai',
];

$counts = [
    'semua' => $orders->count(),
    'dicuci' => ($groups['dicuci'] ?? collect())->count() + ($groups['dikeringkan'] ?? collect())->count(),
    'disetrika' => ($groups['disetrika'] ?? collect())->count(),
    'selesai' => ($groups['selesai'] ?? collect())->count() + ($groups['diambil'] ?? collect())->count(),
];

$nextStatusMap = [
    'menunggu' => 'dicuci',
    'dicuci' => 'dikeringkan',
    'dikeringkan' => 'disetrika',
    'disetrika' => 'selesai',
    'selesai' => 'diambil',
];

$quickActions = [
    'menunggu' => ['label' => 'Mulai Kerjakan', 'icon' => 'play_arrow', 'class' => 'bg-primary text-on-primary shadow-md hover:bg-primary/90 w-full'],
    'dicuci' => [
        ['label' => 'Mulai Keringkan', 'icon' => 'dry', 'status' => 'dikeringkan', 'class' => 'bg-surface-container-high hover:bg-surface-container-highest text-on-surface'],
        ['label' => 'Selesai Cuci', 'icon' => 'check_circle', 'status' => 'disetrika', 'class' => 'bg-primary text-on-primary shadow-sm hover:opacity-90'],
    ],
    'dikeringkan' => [
        ['label' => 'Mulai Setrika', 'icon' => 'iron', 'status' => 'disetrika', 'class' => 'bg-surface-container-high hover:bg-surface-container-highest text-on-surface'],
        ['label' => 'Selesai Kering', 'icon' => 'check_circle', 'status' => 'disetrika', 'class' => 'bg-primary text-on-primary shadow-sm hover:opacity-90'],
    ],
    'disetrika' => [
        ['label' => 'Packing', 'icon' => 'inventory_2', 'status' => 'selesai', 'class' => 'bg-surface-container-high hover:bg-surface-container-highest text-on-surface'],
        ['label' => 'Selesai', 'icon' => 'task_alt', 'status' => 'selesai', 'class' => 'bg-secondary text-on-secondary shadow-sm hover:opacity-90'],
    ],
    'selesai' => ['label' => 'Konfirmasi Pengambilan', 'icon' => 'person_pin_circle', 'class' => 'bg-tertiary text-on-tertiary shadow-md hover:bg-tertiary/90 w-full'],
];
@endphp

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <h1 class="font-headline-md text-headline-md text-on-surface">Update Progress Cucian</h1>
            <p class="text-body-md font-body-md text-on-surface-variant">Kelola status pesanan aktif dengan cepat dan akurat.</p>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-2 bg-surface-container p-1 rounded-xl w-fit" id="filterTabs">
        @foreach($filterTabs as $key => $label)
        <button class="filter-btn px-4 py-2 rounded-lg text-label-lg font-label-lg transition-all flex items-center gap-2
            {{ $loop->first ? 'bg-surface-container-lowest shadow-sm text-primary' : 'text-on-surface-variant hover:bg-surface-container-high' }}"
            data-filter="{{ $key }}">
            {{ $label }}
            <span class="bg-primary/10 px-2 py-0.5 rounded-full text-label-sm {{ $loop->first ? '' : 'bg-surface-container-highest' }}">{{ $counts[$key] }}</span>
        </button>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-gutter" id="orderGrid">
    @forelse($orders as $trx)
    @php
        $cfg = $statusConfig[$trx->status] ?? $statusConfig['menunggu'];
        $next = $nextStatusMap[$trx->status] ?? null;
        $actions = $quickActions[$trx->status] ?? [];
        $isSingleAction = isset($actions['label']);
        $actionList = $isSingleAction ? [$actions] : $actions;
        $borderColor = 'border-l-' . $cfg['color'];
        $isPriority = $trx->status === 'menunggu' && $trx->created_at->diffInHours(now()) > 4;
    @endphp
    <div class="glass-card rounded-xl p-md flex flex-col shadow-sm hover:shadow-md transition-all group border-l-4 {{ $borderColor }} order-card"
         data-status="{{ $trx->status }}">
        <div class="flex justify-between items-start mb-4">
            <div>
                <span class="text-label-sm font-label-sm {{ $cfg['textColor'] }} {{ $cfg['bg'] }} px-2 py-1 rounded-full uppercase tracking-wider mb-2 inline-block">
                    {{ $trx->kode_transaksi }}
                </span>
                <h3 class="font-headline-sm text-headline-sm text-on-surface">{{ $trx->pelanggan->nama ?? '-' }}</h3>
            </div>
            @if($isPriority)
            <div class="bg-error-container text-on-error-container px-3 py-1 rounded-lg flex items-center gap-2 whitespace-nowrap">
                <span class="material-symbols-outlined text-[18px]">priority_high</span>
                <span class="font-label-lg text-label-lg">Prioritas</span>
            </div>
            @elseif($trx->estimasi_selesai)
            @php $sisaJam = now()->diffInHours($trx->estimasi_selesai, false); @endphp
            <div class="bg-secondary-container/20 text-secondary px-3 py-1 rounded-lg flex items-center gap-2 whitespace-nowrap">
                <span class="material-symbols-outlined text-[18px]">timer</span>
                <span class="font-label-lg text-label-lg">{{ $sisaJam > 0 ? $sisaJam . ' Jam' : 'Selesai' }}</span>
            </div>
            @endif
        </div>

        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-xl bg-surface-container flex items-center justify-center {{ $trx->status === 'dicuci' || $trx->status === 'dikeringkan' ? 'text-primary' : ($trx->status === 'disetrika' ? 'text-secondary' : ($trx->status === 'selesai' || $trx->status === 'diambil' ? 'text-tertiary' : 'text-on-surface-variant')) }}">
                <span class="material-symbols-outlined text-[32px]">{{ $cfg['icon'] }}</span>
            </div>
            <div>
                <p class="text-label-sm font-label-sm text-on-surface-variant uppercase">Layanan</p>
                <p class="font-body-md text-on-surface">{{ $trx->layanan->nama ?? '-' }} ({{ $trx->berat }} kg)</p>
                <p class="text-label-sm font-label-sm {{ $cfg['textColor'] }}">Status: {{ $cfg['label'] }}</p>
            </div>
        </div>

        <div class="mt-auto space-y-3">
            <p class="text-label-sm font-label-sm text-on-surface-variant uppercase">Update Cepat</p>
            @if(count($actionList) > 0)
            <div class="{{ $isSingleAction ? '' : 'grid grid-cols-2 gap-2' }}">
                @foreach($actionList as $action)
                <form method="POST" action="{{ route('staff.order.update-status', $trx->id) }}" class="inline status-form">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="{{ $action['status'] ?? $next }}">
                    <button type="submit"
                        class="flex items-center justify-center gap-2 py-3 px-4 rounded-lg transition-all active:scale-[0.98] w-full {{ $action['class'] ?? 'bg-primary text-on-primary' }}">
                        <span class="material-symbols-outlined text-[18px]">{{ $action['icon'] ?? 'arrow_forward' }}</span>
                        <span class="text-label-lg font-label-lg whitespace-nowrap">{{ $action['label'] ?? 'Update' }}</span>
                    </button>
                </form>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-full flex flex-col items-center justify-center py-16 text-on-surface-variant">
        <span class="material-symbols-outlined text-6xl opacity-30 mb-4">local_laundry_service</span>
        <p class="text-headline-sm font-headline-sm">Tidak ada pesanan aktif</p>
        <p class="text-body-md font-body-md mt-1">Semua pesanan sudah selesai hari ini.</p>
        <a href="{{ route('staff.order.index') }}" class="mt-6 bg-primary text-on-primary px-6 py-3 rounded-xl flex items-center gap-2 hover:bg-primary/90 transition-all">
            <span class="material-symbols-outlined">add</span> Buat Pesanan Baru
        </a>
    </div>
    @endforelse

    <a href="{{ route('staff.order.index') }}" class="glass-card rounded-xl p-md flex flex-col items-center justify-center border-2 border-dashed border-outline-variant hover:border-primary hover:bg-primary/5 transition-all text-on-surface-variant hover:text-primary group">
        <div class="w-16 h-16 rounded-full bg-surface-container group-hover:bg-primary-fixed flex items-center justify-center mb-4 transition-all">
            <span class="material-symbols-outlined text-[40px]">add_task</span>
        </div>
        <span class="font-headline-sm text-headline-sm">Tambah Antrean Baru</span>
        <span class="text-body-md font-body-md">Buat pesanan laundry baru</span>
    </a>
</div>
@endsection

<button class="fixed bottom-margin-desktop right-margin-desktop w-16 h-16 bg-primary text-on-primary rounded-full shadow-lg flex items-center justify-center hover:scale-110 active:scale-95 transition-all z-50 no-print" onclick="Swal.fire({title:'Scan QR',text:'Fitur scan QR akan segera hadir',icon:'info',confirmButtonColor:'#004ac6'})">
    <span class="material-symbols-outlined text-[32px]">qr_code_scanner</span>
</button>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.order-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => {
                b.classList.remove('bg-surface-container-lowest', 'shadow-sm', 'text-primary');
                b.classList.add('text-on-surface-variant', 'hover:bg-surface-container-high');
                const badge = b.querySelector('span:last-child');
                if (badge) badge.classList.remove('bg-primary/10');
                if (badge) badge.classList.add('bg-surface-container-highest');
            });
            this.classList.remove('text-on-surface-variant', 'hover:bg-surface-container-high');
            this.classList.add('bg-surface-container-lowest', 'shadow-sm', 'text-primary');
            const badge = this.querySelector('span:last-child');
            if (badge) badge.classList.remove('bg-surface-container-highest');
            if (badge) badge.classList.add('bg-primary/10');

            const filter = this.dataset.filter;
            cards.forEach(card => {
                if (filter === 'semua') {
                    card.style.display = '';
                    return;
                }
                const status = card.dataset.status;
                if ((filter === 'dicuci' && (status === 'dicuci' || status === 'dikeringkan')) ||
                    (filter === 'disetrika' && status === 'disetrika') ||
                    (filter === 'selesai' && (status === 'selesai' || status === 'diambil'))) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    document.querySelectorAll('.status-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const label = btn ? btn.querySelector('span:last-child')?.textContent || 'update' : 'update';
            Swal.fire({
                title: 'Konfirmasi',
                text: `Ubah status ke "${label}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#004ac6',
            }).then((result) => {
                if (result.isConfirmed) {
                    const loadingBtn = btn || this.querySelector('button');
                    if (loadingBtn) loadingBtn.disabled = true;
                    this.submit();
                }
            });
        });
    });

    document.querySelectorAll('.glass-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            if (!card.querySelector('button[disabled]')) {
                card.classList.add('shadow-xl', '-translate-y-1');
            }
        });
        card.addEventListener('mouseleave', () => {
            card.classList.remove('shadow-xl', '-translate-y-1');
        });
    });
});
</script>
@endpush
