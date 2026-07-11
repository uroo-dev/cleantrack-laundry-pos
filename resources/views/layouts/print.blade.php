<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ $settings->nama_app ?? 'LaundryKu' }}</title>
    <link rel="stylesheet" href="/css/app.css">
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')
    <script src="/js/app.js"></script>
    @stack('scripts')
</body>
</html>
