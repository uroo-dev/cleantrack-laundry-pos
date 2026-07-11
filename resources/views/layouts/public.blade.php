<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $settings->nama_app ?? 'LaundryHub') - {{ $settings->nama_app ?? 'LaundryHub' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="overflow-x-hidden bg-background text-on-background">
    @yield('content')

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Toast.fire({ icon: 'error', title: '{{ session('error') }}' });
        });
    </script>
    @endif
    @stack('scripts')
</body>
</html>
