<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Hotel Booking') - {{ config('app.name') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet"/>

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        @keyframes blink {
            0%   { color: #F53003; }
            40%  { color: #F53003; }
            60%  { color: #1b1b18; }
            100% { color: #1b1b18; }
        }
        .logo-cursor {
            color: #F53003;
            transition: color 0.3s ease;
        }
        .logo-wrap:hover .logo-cursor {
            animation: blink 1.4s ease-in-out infinite alternate;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-[#FDFDFC] text-[#1b1b18]">

@include('partials.header')

<main>
    @include('partials.alerts')
    @yield('content')
</main>

@include('partials.footer')

<!-- Alpine.js для интерактивности -->
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>

@stack('scripts')
</body>
</html>
