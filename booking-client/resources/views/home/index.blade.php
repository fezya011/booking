{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Hotel Booking - Найдите идеальное место')

@section('content')
    {{-- Основной контент с прозрачным фоном --}}
    <div style="position: relative; z-index: 10; background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(8px); min-height: 100vh;">
        <!-- Hero секция -->
        <div class="border-b border-gray-200/50">
            <div class="container mx-auto px-4 lg:px-8 py-12 lg:py-16 max-w-6xl">

                {{-- 3D Земля - размещаем над заголовком --}}
                <div class="flex justify-center mb-8">
                    <x-earth-background />
                </div>

                <h1 class="text-4xl lg:text-5xl font-light text-gray-900 mb-4 text-center tracking-tight">
                    Остановитесь в отличном отеле
                </h1>

                <!-- Категории поиска -->
                <div class="flex justify-center space-x-8 mb-8">
                    <a href="{{ route('hotels.search') }}" class="pb-2 border-b-2 border-gray-900 text-gray-900 font-medium">Отели</a>
                    <button class="pb-2 text-gray-400 hover:text-gray-600 transition-colors">Развлечения</button>
                    <button class="pb-2 text-gray-400 hover:text-gray-600 transition-colors">Рестораны</button>
                </div>

                <!-- Поисковая форма -->
                <form action="{{ route('hotels.search') }}" method="GET" class="max-w-3xl mx-auto relative">
                    <div class="flex gap-2">
                        <div class="flex-1 relative">
                            <input type="text"
                                   name="destination"
                                   value="{{ request('destination') }}"
                                   placeholder="Куда вы хотите поехать?"
                                   class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent transition-all text-lg shadow-lg">
                        </div>
                        <button type="submit"
                                class="px-8 py-4 bg-gray-900 text-white hover:bg-gray-800 rounded-2xl font-medium transition-all hover:scale-105 shadow-lg">
                            Найти
                        </button>
                    </div>
                </form>

                <!-- Популярные направления -->
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    @foreach(['Москва', 'Санкт-Петербург', 'Сочи', 'Казань', 'Калининград', 'Екатеринбург'] as $city)
                        <a href="{{ route('hotels.search', ['destination' => $city]) }}"
                           class="px-4 py-2 bg-white/80 hover:bg-white rounded-full text-sm text-gray-600 hover:text-gray-900 transition-all shadow-sm">
                            {{ $city }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Остальной контент без изменений -->
        <div class="border-b border-gray-200/50 mt-8">
            <div class="container mx-auto px-4 lg:px-8 py-16 max-w-6xl">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                    <div class="absolute inset-0 bg-cover bg-center"
                         style="background-image: url('{{ asset('storage/work/b6351ceb-9988-486d.jpg') }}');">
                    </div>
                    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
                    <div class="relative p-12 text-center text-white">
                        <h2 class="text-3xl lg:text-4xl font-light mb-4">
                            Найдите развлечения по своему вкусу
                        </h2>
                        <p class="text-lg text-white/90 mb-8 max-w-2xl mx-auto">
                            Изучите более 400 000 развлечений и забронируйте у нас
                        </p>
                        <button class="px-8 py-3 bg-white text-gray-900 hover:bg-gray-100 rounded-xl font-medium transition-all hover:scale-105 shadow-lg">
                            Забронировать
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-b border-gray-200/50">
            <div class="container mx-auto px-4 lg:px-8 py-16 max-w-6xl">
                <h2 class="text-3xl font-light text-gray-900 mb-10 text-center">
                    Найдите развлечения по интересам
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php
                        $interests = [
                            ['title' => 'Гастрономия', 'desc' => 'Рестораны и дегустации', 'img' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&h=300&fit=crop'],
                            ['title' => 'Экскурсии', 'desc' => 'Групповые и индивидуальные', 'img' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=400&h=300&fit=crop'],
                            ['title' => 'Спа и здоровье', 'desc' => 'Релакс и уход', 'img' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=400&h=300&fit=crop'],
                            ['title' => 'Активный отдых', 'desc' => 'Туры и приключения', 'img' => 'https://images.unsplash.com/photo-1530549387789-4c1017266635?w=400&h=300&fit=crop']
                        ];
                    @endphp

                    @foreach($interests as $interest)
                        <div class="group cursor-pointer bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all">
                            <div class="overflow-hidden bg-gray-100">
                                <img src="{{ $interest['img'] }}"
                                     alt="{{ $interest['title'] }}"
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium text-gray-900">{{ $interest['title'] }}</h3>
                                <p class="text-sm text-gray-500">{{ $interest['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 lg:px-8 py-16 max-w-6xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center bg-white/50 rounded-2xl p-6 backdrop-blur-sm">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="font-medium text-gray-900 mb-2">Гарантия лучшей цены</h4>
                    <p class="text-sm text-gray-500">Найдем дешевле — вернем разницу</p>
                </div>

                <div class="text-center bg-white/50 rounded-2xl p-6 backdrop-blur-sm">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                    </div>
                    <h4 class="font-medium text-gray-900 mb-2">Более 400 000 вариантов</h4>
                    <p class="text-sm text-gray-500">Отели, развлечения и рестораны</p>
                </div>

                <div class="text-center bg-white/50 rounded-2xl p-6 backdrop-blur-sm">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h4 class="font-medium text-gray-900 mb-2">Поддержка 24/7</h4>
                    <p class="text-sm text-gray-500">Поможем в любой ситуации</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.group');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
@endpush
