<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ $settings->nama_app ?? 'LaundryKu' }}</title>
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>
<body class="main">
    <div class="flex">
        <nav class="side-nav">
            <a href="{{ route('admin.dashboard') }}" class="intro-x flex items-center pl-5 pt-4">
                <span class="hidden xl:block text-white text-lg font-bold mx-auto">LaundryKu</span>
            </a>
            <div class="side-nav__devider my-6"></div>
            <ul>
                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="side-menu {{ request()->routeIs('admin.dashboard') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="home"></i></div>
                        <div class="side-menu__title">Dashboard</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pelanggan.index') }}" class="side-menu {{ request()->routeIs('admin.pelanggan.*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="users"></i></div>
                        <div class="side-menu__title">Pelanggan</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.layanan.index') }}" class="side-menu {{ request()->routeIs('admin.layanan.*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="package"></i></div>
                        <div class="side-menu__title">Layanan</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.transaksi.index') }}" class="side-menu {{ request()->routeIs('admin.transaksi.*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="file-text"></i></div>
                        <div class="side-menu__title">Transaksi</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pegawai.index') }}" class="side-menu {{ request()->routeIs('admin.pegawai.*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="briefcase"></i></div>
                        <div class="side-menu__title">Pegawai</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.laporan.index') }}" class="side-menu {{ request()->routeIs('admin.laporan.*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="bar-chart-2"></i></div>
                        <div class="side-menu__title">Laporan</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pengaturan.index') }}" class="side-menu {{ request()->routeIs('admin.pengaturan.*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="settings"></i></div>
                        <div class="side-menu__title">Pengaturan</div>
                    </a>
                </li>
                @endif
                @if(in_array(auth()->user()->role, ['super_admin', 'staff']))
                <li>
                    <a href="{{ route('staff.order.queue') }}" class="side-menu {{ request()->routeIs('staff.order.*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="list"></i></div>
                        <div class="side-menu__title">Antrian</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.order.index') }}" class="side-menu {{ request()->routeIs('staff.order.index') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="plus-circle"></i></div>
                        <div class="side-menu__title">Order Baru</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.tracking.index') }}" class="side-menu {{ request()->routeIs('staff.tracking.*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="search"></i></div>
                        <div class="side-menu__title">Tracking</div>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
        <div class="content flex-1">
            <div class="top-bar">
                <nav aria-label="breadcrumb" class="intro-x mr-auto">
                    <ol class="breadcrumb">
                        @yield('breadcrumb')
                    </ol>
                </nav>
                <div class="intro-x relative dropdown">
                    <button class="dropdown-toggle btn btn-sm btn-outline-secondary" data-tw-toggle="dropdown">
                        <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                        {{ auth()->user()->name }}
                    </button>
                    <div class="dropdown-menu w-40">
                        <div class="dropdown-menu__content box p-2">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-slate-200/50 rounded-md w-full text-left">
                                    <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-wrapper p-5">
                @yield('content')
            </div>
        </div>
    </div>
    @if(session('success'))
    <script>document.addEventListener('DOMContentLoaded', () => { const t = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true }); t.fire({ icon: 'success', title: '{{ session('success') }}' }); });</script>
    @endif
    @if(session('error'))
    <script>document.addEventListener('DOMContentLoaded', () => { const t = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true }); t.fire({ icon: 'error', title: '{{ session('error') }}' }); });</script>
    @endif
    @stack('scripts')
</body>
</html>
