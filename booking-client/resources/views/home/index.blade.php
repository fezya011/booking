@extends('layouts.app')

@section('title', 'Hotel Booking - Найдите идеальное место')

@section('content')
    <div class="min-h-screen bg-white">
        <!-- Hero секция -->
        <div class="border-b border-gray-100">
            <div class="container mx-auto px-4 lg:px-8 py-12 lg:py-16 max-w-6xl">
                <h1 class="text-4xl lg:text-5xl font-light text-gray-900 mb-4 text-center tracking-tight">
                    Остановитесь в отличном отеле
                </h1>

                <!-- Категории поиска -->
                <div class="flex justify-center space-x-8 mb-8">
                    <button class="pb-2 border-b-2 border-gray-900 text-gray-900 font-medium">Отели</button>
                    <button class="pb-2 text-gray-400 hover:text-gray-600 transition-colors">Развлечения</button>
                    <button class="pb-2 text-gray-400 hover:text-gray-600 transition-colors">Рестораны</button>
                </div>

                <!-- Поисковая строка -->
                <div class="max-w-3xl mx-auto relative" x-data="{ showDropdown: false, searchResults: [] }">
                    <div class="relative">
                        <input type="text"
                               x-ref="searchInput"
                               @focus="showDropdown = true"
                               @click.away="showDropdown = false"
                               @keyup="if ($refs.searchInput.value.length > 2) {
                                   showDropdown = true;
                                   // Имитация результатов поиска
                                   searchResults = ['Москва', 'Санкт-Петербург', 'Сочи', 'Казань'];
                               } else {
                                   searchResults = [];
                               }"
                               placeholder="Куда вы хотите поехать?"
                               class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent transition-all text-lg">

                        <!-- Выпадающий список с предложениями -->
                        <div x-show="showDropdown && searchResults.length > 0"
                             x-transition
                             class="absolute left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden z-10">
                            <div class="p-2">
                                <div class="px-4 py-2 text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Популярные направления
                                </div>
                                <template x-for="city in searchResults" :key="city">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 rounded-lg transition-colors">
                                        <div class="font-medium text-gray-900" x-text="city"></div>
                                        <div class="text-xs text-gray-400">Отели, развлечения, рестораны</div>
                                    </a>
                                </template>
                            </div>
                        </div>

                        <!-- Поблизости предложения -->
                        <div x-show="showDropdown && searchResults.length === 0"
                             x-transition
                             class="absolute left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden z-10">
                            <div class="p-2">
                                <div class="px-4 py-2 text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Поблизости
                                </div>
                                <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="text-gray-900">Отели рядом с вами</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-gray-900">Популярные развлечения</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Баннер с развлечениями -->
        <!-- Баннер с развлечениями -->
        <div class="border-b border-gray-100">
            <div class="container mx-auto px-4 lg:px-8 py-16 max-w-6xl">
                <!-- Баннер с фоновым изображением -->
                <div class="relative rounded-3xl overflow-hidden">
                    <!-- Фоновое изображение -->
                    <div class="absolute inset-0 bg-cover bg-center"
                         style="background-image: url('{{ asset('storage/work/b6351ceb-9988-486d.jpg') }}');">
                    </div>
                    <!-- Полупрозрачный overlay для читаемости текста -->
                    <div class="absolute inset-0 bg-black/30"></div>

                    <!-- Контент (сохраняем те же отступы и закругления) -->
                    <div class="relative p-12 text-center text-white">
                        <h2 class="text-3xl lg:text-4xl font-light mb-4">
                            Найдите развлечения по своему вкусу
                        </h2>
                        <p class="text-lg text-white/90 mb-8 max-w-2xl mx-auto">
                            Изучите более 400 000 развлечений и забронируйте у нас
                        </p>
                        <button class="px-8 py-3 bg-white text-gray-900 hover:bg-gray-100 rounded-xl font-medium transition-colors">
                            Забронировать
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Раздел с карточками по интересам -->
        <div class="border-b border-gray-100">
            <div class="container mx-auto px-4 lg:px-8 py-16 max-w-6xl">
                <h2 class="text-3xl font-light text-gray-900 mb-10 text-center">
                    Найдите развлечения по интересам
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Карточка 1 -->
                    <div class="group cursor-pointer">
                        <div class="aspect-w-16 aspect-h-9 rounded-2xl overflow-hidden mb-3 bg-gray-100">
                            <img src="https://via.placeholder.com/400x300/e5e7eb/9ca3af?text=Гастрономия"
                                 alt="Гастрономия"
                                 class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="font-medium text-gray-900">Гастрономия</h3>
                        <p class="text-sm text-gray-400">Рестораны и дегустации</p>
                    </div>

                    <!-- Карточка 2 -->
                    <div class="group cursor-pointer">
                        <div class="aspect-w-16 aspect-h-9 rounded-2xl overflow-hidden mb-3 bg-gray-100">
                            <img src="https://via.placeholder.com/400x300/e5e7eb/9ca3af?text=Экскурсии"
                                 alt="Экскурсии"
                                 class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="font-medium text-gray-900">Экскурсии</h3>
                        <p class="text-sm text-gray-400">Групповые и индивидуальные</p>
                    </div>

                    <!-- Карточка 3 -->
                    <div class="group cursor-pointer">
                        <div class="aspect-w-16 aspect-h-9 rounded-2xl overflow-hidden mb-3 bg-gray-100">
                            <img src="https://via.placeholder.com/400x300/e5e7eb/9ca3af?text=Спа+и+здоровье"
                                 alt="Спа и здоровье"
                                 class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="font-medium text-gray-900">Спа и здоровье</h3>
                        <p class="text-sm text-gray-400">Релакс и уход</p>
                    </div>

                    <!-- Карточка 4 -->
                    <div class="group cursor-pointer">
                        <div class="aspect-w-16 aspect-h-9 rounded-2xl overflow-hidden mb-3 bg-gray-100">
                            <img src="https://via.placeholder.com/400x300/e5e7eb/9ca3af?text=Активный+отдых"
                                 alt="Активный отдых"
                                 class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="font-medium text-gray-900">Активный отдых</h3>
                        <p class="text-sm text-gray-400">Туры и приключения</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Дополнительная информация -->
        <div class="container mx-auto px-4 lg:px-8 py-16 max-w-6xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="font-medium text-gray-900 mb-2">Гарантия лучшей цены</h4>
                    <p class="text-sm text-gray-400">Найдем дешевле — вернем разницу</p>
                </div>

                <div class="text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                    </div>
                    <h4 class="font-medium text-gray-900 mb-2">Более 400 000 вариантов</h4>
                    <p class="text-sm text-gray-400">Отели, развлечения и рестораны</p>
                </div>

                <div class="text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h4 class="font-medium text-gray-900 mb-2">Поддержка 24/7</h4>
                    <p class="text-sm text-gray-400">Поможем в любой ситуации</p>
                </div>
            </div>
        </div>
    </div>
@endsection
