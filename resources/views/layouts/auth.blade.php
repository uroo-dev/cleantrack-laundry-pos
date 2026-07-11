<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ $settings->nama_app ?? 'LaundryKu' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#004ac6',
                        secondary: '#00687a',
                        tertiary: '#006242',
                        background: '#f7f9fb',
                        'surface-container': '#eceef0',
                        'on-primary': '#ffffff',
                        'on-background': '#191c1e',
                        'outline-variant': '#c2c7cb',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet" />

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f7f9fb;
            color: #191c1e;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(194, 199, 203, 0.3);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.04);
        }
        .glass-card-strong {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(194, 199, 203, 0.25);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        input:focus {
            outline: none;
            border-color: #004ac6 !important;
            box-shadow: 0 0 0 3px rgba(0, 74, 198, 0.12);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md mx-auto">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-2xl font-extrabold tracking-tight" style="color: #004ac6;">
                <span class="material-symbols-outlined" style="font-size: 32px;">local_laundry_service</span>
                {{ $settings->nama_app ?? 'LaundryKu' }}
            </a>
        </div>

        <div class="glass-card-strong p-8">
            @yield('auth-content')
        </div>
    </div>

    @if(session('success'))
    <script>document.addEventListener('DOMContentLoaded', () => { const t = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true }); t.fire({ icon: 'success', title: '{{ session('success') }}' }); });</script>
    @endif
    @if(session('error'))
    <script>document.addEventListener('DOMContentLoaded', () => { const t = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true }); t.fire({ icon: 'error', title: '{{ session('error') }}' }); });</script>
    @endif
    @if(session('info'))
    <script>document.addEventListener('DOMContentLoaded', () => { const t = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true }); t.fire({ icon: 'info', title: '{{ session('info') }}' }); });</script>
    @endif
    @if(session('warning'))
    <script>document.addEventListener('DOMContentLoaded', () => { const t = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true }); t.fire({ icon: 'warning', title: '{{ session('warning') }}' }); });</script>
    @endif

    @stack('scripts')
</body>
</html>
