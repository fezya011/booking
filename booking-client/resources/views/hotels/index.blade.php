@extends('layouts.app')

@section('title', 'Все отели - Hotel Booking')

@push('styles')
    <style>
        .hotel-card {
            transition: all 0.3s ease;
        }
        .hotel-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }

        /* Стили для фильтров */
        .filter-section {
            transition: all 0.3s ease;
        }
        .filter-section:hover {
            background-color: #f9fafb;
        }

        /* Анимация загрузки */
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        /* Стили для чекбоксов */
        .amenity-checkbox {
            accent-color: #1b1b18;
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 lg:px-8 py-8 max-w-7xl">
            <!-- Заголовок -->
            <div class="mb-8">
                <h1 class="text-3xl lg:text-4xl font-light text-gray-900 mb-2">
                    Все отели
                </h1>
                <p class="text-gray-500">
                    {{ $hotels->total() }} отелей найдено
                </p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Левая колонка - Фильтры -->
                <div class="lg:w-80 flex-shrink-0">
                    <div class="bg-white rounded-xl border border-gray-100 p-6 sticky top-24">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Фильтры</h3>

                        <form action="{{ route('hotels.index') }}" method="GET" id="filter-form">
                            <!-- Скрытые поля для сохранения параметров поиска -->
                            @if(request()->has('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <!-- Цена -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Цена за ночь</label>
                                <div class="flex gap-3">
                                    <div class="flex-1">
                                        <input type="number"
                                               name="min_price"
                                               value="{{ request('min_price') }}"
                                               placeholder="от"
                                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-400">
                                    </div>
                                    <div class="flex-1">
                                        <input type="number"
                                               name="max_price"
                                               value="{{ request('max_price') }}"
                                               placeholder="до"
                                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-400">
                                    </div>
                                </div>
                            </div>

                            <!-- Звезды -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Звездность</label>
                                <div class="space-y-2">
                                    @for($i = 5; $i >= 1; $i--)
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox"
                                                   name="stars[]"
                                                   value="{{ $i }}"
                                                   {{ in_array($i, (array)request('stars', [])) ? 'checked' : '' }}
                                                   class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-500">
                                            <span class="ml-3 text-sm text-gray-600 flex items-center">
                                            @for($j = 1; $j <= $i; $j++)
                                                    <svg class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                @endfor
                                            <span class="ml-1">{{ $i }} звезд{{ $i == 1 ? 'а' : '' }}</span>
                                        </span>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Удобства -->
                            @if(isset($amenities) && count($amenities) > 0)
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Удобства</label>
                                    <div class="space-y-2 max-h-48 overflow-y-auto">
                                        @foreach($amenities as $amenity)
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox"
                                                       name="amenities[]"
                                                       value="{{ $amenity->id }}"
                                                       {{ in_array($amenity->id, (array)request('amenities', [])) ? 'checked' : '' }}
                                                       class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-500">
                                                <span class="ml-3 text-sm text-gray-600">{{ $amenity->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Сортировка -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Сортировка</label>
                                <select name="sort_by" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-400">
                                    <option value="rating" {{ request('sort_by', 'rating') == 'rating' ? 'selected' : '' }}>По рейтингу</option>
                                    <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>По цене</option>
                                    <option value="stars" {{ request('sort_by') == 'stars' ? 'selected' : '' }}>По звездности</option>
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Новинки</option>
                                </select>
                                <select name="sort_order" class="w-full mt-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-400">
                                    <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>По убыванию</option>
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>По возрастанию</option>
                                </select>
                            </div>

                            <!-- Кнопки -->
                            <div class="flex gap-3">
                                <button type="submit" class="flex-1 px-4 py-2 bg-gray-900 text-white hover:bg-gray-800 rounded-lg font-medium transition-colors">
                                    Применить
                                </button>
                                @if(request()->anyFilled(['min_price', 'max_price', 'stars', 'amenities', 'sort_by', 'sort_order']))
                                    <a href="{{ route('hotels.index') }}" class="px-4 py-2 text-gray-500 hover:text-gray-700 transition-colors">
                                        Сбросить
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Правая колонка - Список отелей -->
                <div class="flex-1">
                    <!-- Результатов на странице -->
                    <div class="flex justify-end mb-4">
                        <select name="per_page" form="filter-form" class="px-3 py-1.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none">
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 на странице</option>
                            <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30 на странице</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 на странице</option>
                        </select>
                    </div>

                    <!-- Список отелей -->
                    <div class="space-y-4">
                        @forelse($hotels as $hotel)
                            <div class="hotel-card bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all border border-gray-100">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Изображение -->
                                    <div class="md:w-56 h-48 md:h-auto bg-gray-100 relative overflow-hidden">
                                        @if($hotel->main_image)
                                            <img src="{{ $hotel->main_image }}"
                                                 alt="{{ $hotel->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center">
                                                <span class="text-white text-3xl font-bold">{{ substr($hotel->name, 0, 1) }}</span>
                                            </div>
                                        @endif

                                        @if($hotel->is_featured)
                                            <span class="absolute top-3 left-3 px-2 py-1 bg-gray-900 text-white text-xs font-medium rounded-full">
                                            Рекомендуем
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Информация -->
                                    <div class="flex-1 p-5">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-xl font-semibold text-gray-900 hover:text-gray-600">
                                                    <a href="{{ route('hotels.show', $hotel->id) }}">
                                                        {{ $hotel->name }}
                                                    </a>
                                                </h3>
                                                <p class="text-sm text-gray-500 mt-1 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    </svg>
                                                    {{ $hotel->address }}, {{ $hotel->city }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <div class="flex items-center gap-1">
                                                <span class="bg-gray-900 text-white text-sm font-medium px-2 py-0.5 rounded">
                                                    {{ number_format($hotel->rating, 1) }}
                                                </span>
                                                    <span class="text-xs text-gray-500">/5</span>
                                                </div>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $hotel->review_count ?? 0 }} отзывов</p>
                                            </div>
                                        </div>

                                        <!-- Звезды -->
                                        <div class="flex items-center gap-1 mt-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $hotel->stars ? 'text-gray-700' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>

                                        <!-- Описание -->
                                        <p class="text-gray-600 text-sm mt-3 line-clamp-2">
                                            {{ $hotel->short_description ?? substr(strip_tags($hotel->description ?? ''), 0, 120) }}...
                                        </p>

                                        <!-- Удобства и цена -->
                                        <div class="flex flex-wrap justify-between items-center mt-4 pt-4 border-t border-gray-100">
                                            <div class="flex flex-wrap gap-1">
                                                @if(isset($hotel->amenities) && count($hotel->amenities) > 0)
                                                    @php
                                                        $amenitiesArray = is_array($hotel->amenities) ? $hotel->amenities : $hotel->amenities->toArray();
                                                        $displayAmenities = array_slice($amenitiesArray, 0, 3);
                                                    @endphp
                                                    @foreach($displayAmenities as $amenity)
                                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                    {{ is_array($amenity) ? $amenity['name'] : $amenity->name }}
                </span>
                                                    @endforeach
                                                    @if(count($amenitiesArray) > 3)
                                                        <span class="text-xs text-gray-400 px-2 py-1">
                    +{{ count($amenitiesArray) - 3 }}
                </span>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="text-right">
        <span class="text-2xl font-bold text-gray-900">
            {{ number_format($hotel->min_price ?? 0, 0, '.', ' ') }} ₽
        </span>
                                                <span class="text-xs text-gray-500">/ночь</span>
                                            </div>
                                        </div>
                        @empty
                            <!-- Нет результатов -->
                            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                                <div class="text-6xl mb-4">🏨</div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Отели не найдены</h3>
                                <p class="text-gray-500 mb-6">Попробуйте изменить параметры фильтрации</p>
                                <a href="{{ route('hotels.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-900 text-white hover:bg-gray-800 rounded-lg font-medium transition-colors">
                                    Сбросить фильтры
                                </a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Пагинация -->
                    @if($hotels->hasPages())
                        <div class="mt-8">
                            {{ $hotels->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
