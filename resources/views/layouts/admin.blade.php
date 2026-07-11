<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ $settings->nama_app ?? 'LaundryHub' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface": "#f7f9fb", "surface-dim": "#d8dadc", "surface-bright": "#f7f9fb",
                        "surface-container-lowest": "#ffffff", "surface-container-low": "#f2f4f6",
                        "surface-container": "#eceef0", "surface-container-high": "#e6e8ea",
                        "surface-container-highest": "#e0e3e5", "on-surface": "#191c1e",
                        "on-surface-variant": "#434655", "inverse-surface": "#2d3133",
                        "inverse-on-surface": "#eff1f3", "inverse-primary": "#b4c5ff",
                        "background": "#f7f9fb", "on-background": "#191c1e",
                        "primary": "#004ac6", "primary-container": "#2563eb",
                        "primary-fixed": "#dbe1ff", "primary-fixed-dim": "#b4c5ff",
                        "on-primary": "#ffffff", "on-primary-container": "#eeefff",
                        "on-primary-fixed": "#00174b", "on-primary-fixed-variant": "#003ea8",
                        "secondary": "#00687a", "secondary-container": "#57dffe",
                        "secondary-fixed": "#acedff", "secondary-fixed-dim": "#4cd7f6",
                        "on-secondary": "#ffffff", "on-secondary-container": "#006172",
                        "on-secondary-fixed": "#001f26", "on-secondary-fixed-variant": "#004e5c",
                        "tertiary": "#006242", "tertiary-container": "#007d55",
                        "tertiary-fixed": "#6ffbbe", "tertiary-fixed-dim": "#4edea3",
                        "on-tertiary": "#ffffff", "on-tertiary-container": "#bdffdb",
                        "on-tertiary-fixed": "#002113", "on-tertiary-fixed-variant": "#005236",
                        "error": "#ba1a1a", "error-container": "#ffdad6",
                        "on-error": "#ffffff", "on-error-container": "#93000a",
                        "outline": "#737686", "outline-variant": "#c3c6d7", "surface-tint": "#0053db",
                        "surface-variant": "#e0e3e5"
                    },
                    borderRadius: { DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", "2xl": "1rem", "3xl": "1.5rem", full: "9999px" },
                    spacing: { xs: "4px", sm: "12px", base: "8px", md: "24px", lg: "40px", xl: "64px", gutter: "24px", "margin-desktop": "48px", "margin-mobile": "16px" },
                    fontFamily: { "label-lg": ["Plus Jakarta Sans"], "label-sm": ["Plus Jakarta Sans"], "body-lg": ["Plus Jakarta Sans"], "headline-sm": ["Plus Jakarta Sans"], "body-md": ["Plus Jakarta Sans"], "headline-lg": ["Plus Jakarta Sans"], "body-sm": ["Plus Jakarta Sans"], "headline-md": ["Plus Jakarta Sans"] },
                    fontSize: {
                        "label-lg": ["14px", { lineHeight: "20px", letterSpacing: "0.1px", fontWeight: "600" }],
                        "headline-sm": ["20px", { lineHeight: "28px", fontWeight: "600" }],
                        "body-sm": ["14px", { lineHeight: "20px", fontWeight: "400" }],
                        "body-lg": ["18px", { lineHeight: "28px", fontWeight: "400" }],
                        "headline-md": ["28px", { lineHeight: "36px", fontWeight: "600" }],
                        "label-sm": ["12px", { lineHeight: "16px", letterSpacing: "0.5px", fontWeight: "500" }],
                        "headline-lg": ["40px", { lineHeight: "48px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "body-md": ["16px", { lineHeight: "24px", fontWeight: "400" }]
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255,255,255,0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .soft-shadow { box-shadow: 0 20px 40px -15px rgba(0,74,198,0.08); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c3c6d7; border-radius: 10px; }
        @media print { .no-print { display: none !important; } .print-padding { padding: 0 !important; } body { background-color: white; } .glass-card { backdrop-filter: none; border: 1px solid #eceef0; } }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>
<body class="bg-background text-on-background min-h-screen">
    @php $role = auth()->user()->role; @endphp
    {{-- Sidebar --}}
    <aside class="h-screen w-64 fixed left-0 top-0 bg-surface-container-low/80 backdrop-blur-xl border-r border-outline-variant/30 shadow-sm flex flex-col p-gutter z-50">
        <div class="mb-lg">
            <a href="{{ route('admin.dashboard') }}" class="text-headline-sm font-bold text-primary tracking-tight">{{ $settings->nama_app ?? 'LaundryHub' }}</a>
            <p class="text-label-sm text-on-surface-variant opacity-70">{{ ucfirst($role) }} Portal</p>
        </div>
        <nav class="flex-1 space-y-2 overflow-y-auto custom-scrollbar">
            @if($role === 'super_admin' || $role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-highest' }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="text-label-lg">Dashboard</span>
            </a>
            <a href="{{ route('admin.transaksi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.transaksi.*') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-highest' }}">
                <span class="material-symbols-outlined">receipt_long</span>
                <span class="text-label-lg">Transactions</span>
            </a>
            <a href="{{ route('admin.pelanggan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.pelanggan.*') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-highest' }}">
                <span class="material-symbols-outlined">group</span>
                <span class="text-label-lg">Customers</span>
            </a>
            <a href="{{ route('admin.layanan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.layanan.*') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-highest' }}">
                <span class="material-symbols-outlined">local_laundry_service</span>
                <span class="text-label-lg">Layanan</span>
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.laporan.*') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-highest' }}">
                <span class="material-symbols-outlined">bar_chart</span>
                <span class="text-label-lg">Laporan</span>
            </a>
            @endif
            @if($role === 'super_admin')
            <a href="{{ route('admin.pegawai.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.pegawai.*') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-highest' }}">
                <span class="material-symbols-outlined">badge</span>
                <span class="text-label-lg">Pegawai</span>
            </a>
            <a href="{{ route('admin.pengaturan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.pengaturan.*') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-highest' }}">
                <span class="material-symbols-outlined">settings</span>
                <span class="text-label-lg">Settings</span>
            </a>
            @endif
            @if($role === 'staff')
            <a href="{{ route('staff.order.queue') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('staff.order.*') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-highest' }}">
                <span class="material-symbols-outlined">local_laundry_service</span>
                <span class="text-label-lg">Progress</span>
            </a>
            <a href="{{ route('staff.tracking.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('staff.tracking.*') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-highest' }}">
                <span class="material-symbols-outlined">search</span>
                <span class="text-label-lg">Tracking</span>
            </a>
            @endif
        </nav>
        <div class="mt-auto space-y-2">
            @if($role === 'staff')
            <a href="{{ route('staff.order.index') }}" class="w-full bg-primary text-on-primary py-3 rounded-xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform">
                <span class="material-symbols-outlined">add</span> New Order
            </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-2 w-full text-error hover:bg-error-container/30 rounded-xl transition-all">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="text-label-lg">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Top Bar --}}
    <header class="fixed top-0 right-0 w-[calc(100%-256px)] h-20 bg-surface/80 backdrop-blur-md border-b border-outline-variant/20 flex justify-between items-center px-margin-desktop z-40">
        <div class="flex items-center gap-4 flex-1">
            <h2 class="text-headline-md font-bold text-on-surface">@yield('page-title', 'Dashboard')</h2>
            @hasSection('page-description')
            <span class="text-body-sm text-on-surface-variant hidden lg:block ml-2">— @yield('page-description')</span>
            @endif
        </div>
        <div class="flex items-center gap-4">
            @php $waLink = $settings->telepon ? \App\Services\WhatsAppService::generateLink($settings->telepon) : '#'; @endphp
            <a href="{{ $waLink }}" target="_blank" class="hover:bg-surface-container-high rounded-full p-2 transition-all" title="WhatsApp">
                <span class="material-symbols-outlined text-on-surface-variant">chat</span>
            </a>
            <div class="flex items-center gap-3 ml-2 pl-4 border-l border-outline-variant">
                <div class="text-right">
                    <p class="text-label-lg font-bold">{{ auth()->user()->name }}</p>
                    <p class="text-label-sm text-on-surface-variant capitalize">{{ auth()->user()->role }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-on-primary-fixed-variant">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="ml-64 pt-20 p-margin-desktop min-h-screen">
        @yield('content')
    </main>

    {{-- Background Decoration --}}
    <div class="fixed top-0 left-0 w-full h-full -z-10 pointer-events-none opacity-50">
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-primary-container/20 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[400px] h-[400px] bg-secondary-container/20 blur-[100px] rounded-full"></div>
    </div>

    <script>
        document.querySelectorAll('button, a').forEach(el => {
            el.addEventListener('mousedown', () => el.style.transform = 'scale(0.97)');
            el.addEventListener('mouseup', () => el.style.transform = '');
            el.addEventListener('mouseleave', () => el.style.transform = '');
        });
        document.querySelectorAll('.glass-card').forEach(card => {
            card.addEventListener('mouseenter', () => { card.style.transform = 'translateY(-4px)'; card.style.transition = 'transform 0.3s cubic-bezier(0.34,1.56,0.64,1)'; });
            card.addEventListener('mouseleave', () => { card.style.transform = 'translateY(0)'; });
        });
    </script>
    @if(session('success'))
    <script>document.addEventListener('DOMContentLoaded', () => { const t = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true }); t.fire({ icon: 'success', title: '{{ session('success') }}' }); });</script>
    @endif
    @if(session('error'))
    <script>document.addEventListener('DOMContentLoaded', () => { const t = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true }); t.fire({ icon: 'error', title: '{{ session('error') }}' }); });</script>
    @endif
    @stack('scripts')
</body>
</html>
