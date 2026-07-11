<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ $settings->nama_app ?? 'LaundryKu' }}</title>
    <link rel="stylesheet" href="/css/app.css">
    @stack('styles')
</head>
<body class="login">
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="{{ route('home') }}" class="-intro-x flex items-center pt-5">
                    <span class="text-white text-lg font-bold ml-3">LaundryKu</span>
                </a>
                <div class="my-auto">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                        Kelola Laundry Anda
                        <br>
                        dengan Mudah.
                    </div>
                    <div class="-intro-x mt-5 text-white text-opacity-70 text-lg">
                        Sistem manajemen laundry terpercaya untuk bisnis Anda.
                    </div>
                </div>
            </div>
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    @yield('auth-content')
                </div>
            </div>
        </div>
    </div>
    <div class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 right-0 box dark:bg-darkmode-800 dark:border-darkmode-200 border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10">
        <span class="mr-4 text-gray-700 dark:text-white">Mode Gelap</span>
        <input type="checkbox" class="dark-mode-switcher__checkbox" id="dark-mode-switcher">
        <label for="dark-mode-switcher" class="dark-mode-switcher__label"></label>
    </div>
    <script src="/js/app.js"></script>
    @stack('scripts')
</body>
</html>
