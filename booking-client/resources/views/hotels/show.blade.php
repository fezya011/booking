@extends('layouts.app')

@section('title', $hotel->name . ' - Hotel Booking')

@push('styles')
    <style>
        .gallery-thumb {
            transition: all 0.3s ease;
        }
        .gallery-thumb:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }
        .active-thumb {
            border: 2px solid #1b1b18;
            opacity: 1;
        }
        .amenity-badge {
            transition: all 0.2s ease;
        }
        .amenity-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .review-card {
            transition: all 0.2s ease;
        }
        .review-card:hover {
            transform: translateX(4px);
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 lg:px-8 py-8 max-w-7xl">

            <!-- Хлебные крошки -->
            <nav class="flex mb-6 text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Главная</a>
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('hotels.index') }}" class="text-gray-500 hover:text-gray-700">Отели</a>
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-900">{{ $hotel->name }}</span>
            </nav>

            <!-- Название отеля -->
            <h1 class="text-3xl lg:text-4xl font-light text-gray-900 mb-2">{{ $hotel->name }}</h1>

            <!-- Адрес -->
            <div class="flex items-center text-gray-500 mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>{{ $hotel->address }}, {{ $hotel->city }}, {{ $hotel->country }}</span>
            </div>

            <!-- Звезды и рейтинг -->
            <div class="flex items-center gap-4 mb-6">
                <div class="flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $hotel->stars ? 'text-gray-700' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <div class="flex items-center gap-2">
                    <span class="bg-gray-900 text-white text-lg font-bold px-3 py-1 rounded">{{ number_format($hotel->rating, 1) }}</span>
                    <span class="text-gray-500">{{ $hotel->review_count ?? 0 }} отзывов</span>
                </div>
                @if($hotel->is_featured)
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">⭐ Рекомендуемый отель</span>
                @endif
            </div>

            <!-- Галерея изображений -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-8">
                <!-- Главное изображение -->
                <div class="lg:col-span-3 h-96 bg-gray-100 rounded-xl overflow-hidden">
                    <img id="mainImage"
                         src="{{ $hotel->main_image ?? asset('images/placeholders/hotel-large.jpg') }}"
                         alt="{{ $hotel->name }}"
                         class="w-full h-full object-cover">
                </div>

                <!-- Миниатюры (если есть галерея) -->
                <div class="grid grid-cols-2 gap-4">
                    @php
                        $gallery = $hotel->gallery ?? [];
                        $thumbnails = array_slice($gallery, 0, 4);
                    @endphp
                    @foreach($thumbnails as $index => $thumb)
                        <div class="h-44 bg-gray-100 rounded-xl overflow-hidden cursor-pointer gallery-thumb"
                             onclick="changeImage('{{ $thumb }}')">
                            <img src="{{ $thumb }}"
                                 alt="Фото отеля"
                                 class="w-full h-full object-cover">
                        </div>
                    @endforeach
                    @if(count($thumbnails) < 4)
                        @for($i = count($thumbnails); $i < 4; $i++)
                            <div class="h-44 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>

            <!-- Основное содержание: описание + боковая панель -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Левая колонка: описание и удобства -->
                <div class="lg:col-span-2">
                    <!-- Описание отеля -->
                    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-6">
                        <h2 class="text-xl font-medium text-gray-900 mb-4">Об отеле</h2>
                        <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                            {{ $hotel->description ?? 'Описание отсутствует' }}
                        </p>
                    </div>

                    <!-- Удобства отеля -->
                    @if(isset($hotel->amenities) && count($hotel->amenities) > 0)
                        <div class="bg-white rounded-xl border border-gray-100 p-6 mb-6">
                            <h2 class="text-xl font-medium text-gray-900 mb-4">Удобства и услуги</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @php
                                    $amenitiesArray = is_array($hotel->amenities) ? $hotel->amenities : ($hotel->amenities ? $hotel->amenities->toArray() : []);
                                @endphp
                                @foreach($amenitiesArray as $amenity)
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg amenity-badge">
                                        @if(is_array($amenity) && isset($amenity['icon']))
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                        <span class="text-sm text-gray-700">{{ is_array($amenity) ? $amenity['name'] : $amenity->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Правила отеля -->
                    @if(isset($hotel->house_rules) && count($hotel->house_rules) > 0)
                        <div class="bg-white rounded-xl border border-gray-100 p-6">
                            <h2 class="text-xl font-medium text-gray-900 mb-4">Правила проживания</h2>
                            <ul class="space-y-2">
                                @php
                                    $rules = is_array($hotel->house_rules) ? $hotel->house_rules : (is_string($hotel->house_rules) ? json_decode($hotel->house_rules, true) : []);
                                @endphp
                                @foreach($rules as $rule)
                                    <li class="flex items-start gap-2 text-gray-600">
                                        <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>{{ $rule }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Правая колонка: бронирование и информация -->
                <div class="lg:col-span-1">
                    <!-- Карточка бронирования -->
                    <div class="bg-white rounded-xl border border-gray-100 p-6 sticky top-24">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Забронировать номер</h3>

                        <form action="" method="GET">
                            <!-- Даты -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Дата заезда</label>
                                <input type="date"
                                       name="check_in"
                                       value="{{ request('check_in', now()->format('Y-m-d')) }}"
                                       min="{{ now()->format('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Дата выезда</label>
                                <input type="date"
                                       name="check_out"
                                       value="{{ request('check_out', now()->addDays(3)->format('Y-m-d')) }}"
                                       min="{{ now()->addDay()->format('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
                            </div>

                            <!-- Гости -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Гости</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <select name="adults" class="px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ request('adults', 2) == $i ? 'selected' : '' }}>
                                                {{ $i }} взросл{{ $i == 1 ? 'ый' : 'ых' }}
                                            </option>
                                        @endfor
                                    </select>
                                    <select name="children" class="px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
                                        @for($i = 0; $i <= 4; $i++)
                                            <option value="{{ $i }}" {{ request('children', 0) == $i ? 'selected' : '' }}>
                                                {{ $i > 0 ? $i . ' детей' : 'Без детей' }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <button type="submit"
                                    class="w-full py-3 bg-gray-900 text-white hover:bg-gray-800 rounded-lg font-medium transition-colors mb-4">
                                Выбрать номер
                            </button>
                        </form>

                        <!-- Цены -->
                        <div class="border-t border-gray-100 pt-4">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-500">Цена от</span>
                                <span class="text-gray-900 font-medium">{{ number_format($hotel->min_price ?? 0, 0, '.', ' ') }} ₽ / ночь</span>
                            </div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-500">Заезд</span>
                                <span class="text-gray-900">{{ $hotel->check_in_time ?? '14:00' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Выезд</span>
                                <span class="text-gray-900">{{ $hotel->check_out_time ?? '12:00' }}</span>
                            </div>
                        </div>

                        <!-- Политики -->
                        <div class="border-t border-gray-100 mt-4 pt-4 space-y-2">
                            @if(isset($hotel->allows_pets) && $hotel->allows_pets)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Разрешены домашние животные</span>
                                </div>
                            @endif

                            @if(isset($hotel->allows_children) && $hotel->allows_children)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Приветствуются дети</span>
                                </div>
                            @endif

                            @if(isset($hotel->has_wheelchair_access) && $hotel->has_wheelchair_access)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Доступно для колясок</span>
                                </div>
                            @endif

                            @if(isset($hotel->allows_smoking) && $hotel->allows_smoking)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span>Разрешено курение</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Отзывы -->
            @if(isset($hotel->recent_reviews) && count($hotel->recent_reviews) > 0)
                <div class="mt-8">
                    <h2 class="text-2xl font-light text-gray-900 mb-6">Отзывы гостей</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($hotel->recent_reviews as $review)
                            <div class="bg-white rounded-xl border border-gray-100 p-5 review-card">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 font-medium">
                                            {{ substr($review->guest_name ?? $review->user_name ?? 'А', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $review->guest_name ?? $review->user_name ?? 'Гость' }}</p>
                                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="bg-gray-900 text-white text-sm font-medium px-2 py-0.5 rounded">{{ $review->rating }}</span>
                                        <span class="text-xs text-gray-500">/5</span>
                                    </div>
                                </div>
                                @if($review->title)
                                    <h4 class="font-medium text-gray-800 mb-2">{{ $review->title }}</h4>
                                @endif
                                <p class="text-gray-600 text-sm">{{ Str::limit($review->comment ?? '', 200) }}</p>
                                @if($review->hotel_response)
                                    <div class="mt-3 p-3 bg-gray-50 rounded-lg text-sm">
                                        <p class="font-medium text-gray-700 mb-1">Ответ отеля:</p>
                                        <p class="text-gray-600">{{ $review->hotel_response }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @if(($hotel->review_count ?? 0) > 2)
                        <div class="text-center mt-6">
                            <a href="{{ route('hotels.reviews', $hotel->id) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                                <span>Посмотреть все {{ $hotel->review_count }} отзывов</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Номера -->
            @if(isset($hotel->rooms) && count($hotel->rooms) > 0)
                <div class="mt-8">
                    <h2 class="text-2xl font-light text-gray-900 mb-6">Доступные номера</h2>
                    <div class="space-y-4">
                        @foreach($hotel->rooms as $room)
                            @php
                                // Преобразуем массив в объект для удобства (опционально)
                                $roomObj = is_array($room) ? (object) $room : $room;
                            @endphp
                            <div class="bg-white rounded-xl border border-gray-100 p-5 flex flex-col md:flex-row gap-4">
                                <div class="md:w-48 h-32 bg-gray-100 rounded-lg overflow-hidden">
                                    @if(isset($roomObj->main_image) && $roomObj->main_image)
                                        <img src="{{ $roomObj->main_image }}"
                                             alt="{{ $roomObj->name ?? 'Номер' }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-wrap justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">{{ $roomObj->name ?? 'Номер' }}</h3>
                                            <p class="text-sm text-gray-500">{{ $roomObj->description ?? 'Комфортный номер' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-2xl font-bold text-gray-900">{{ number_format($roomObj->price_per_night ?? 0, 0, '.', ' ') }} ₽</span>
                                            <span class="text-xs text-gray-500">/ночь</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            {{ $roomObj->capacity_adults ?? 2 }} взрослых
                        </span>
                                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            {{ $roomObj->size_sqm ?? '—' }} м²
                        </span>
                                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                            </svg>
                            {{ $roomObj->bed_type ?? 'double' }}
                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function changeImage(imageUrl) {
            document.getElementById('mainImage').src = imageUrl;
            // Добавляем активный класс к миниатюре
            document.querySelectorAll('.gallery-thumb').forEach(thumb => {
                thumb.classList.remove('active-thumb');
            });
            event.currentTarget.classList.add('active-thumb');
        }
    </script>
@endpush
