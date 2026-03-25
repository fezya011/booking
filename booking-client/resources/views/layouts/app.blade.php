<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Hotel Booking'))</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Кастомные стили --}}
    <style>
        /* Убеждаемся, что контент поверх 3D */
        main {
            position: relative;
            z-index: 150;
        }

        /* Стили для блюра */
        .backdrop-blur-sm {
            backdrop-filter: blur(8px);
        }

        @media (max-width: 768px) {
            .backdrop-blur-sm {
                backdrop-filter: blur(5px);
            }
        }

        /* Анимации для карточек */
        .interest-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .interest-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
        }

        /* Эффект для формы поиска */
        .search-form input:focus {
            transform: scale(1.01);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Тень для заголовка */
        .hero-title {
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Убеждаемся, что 3D контейнер не перекрывает клики */
        #earth-container {
            pointer-events: none;
        }

        #earth-canvas {
            pointer-events: auto;
        }
    </style>

    {{-- Дополнительные стили --}}
    @stack('styles')

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased">
{{-- Хедер --}}
@include('partials.header')

{{-- Основной контент --}}
<main>
    @yield('content')
</main>

{{-- Футер --}}
@include('partials.footer')

{{-- Дополнительные скрипты --}}
@stack('scripts')
</body>
</html>
