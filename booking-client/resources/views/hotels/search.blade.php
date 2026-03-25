@extends('layouts.app')

@section('title', 'Поиск отелей - Hotel Booking')

@push('styles')
    <style>
        /* Анимации для карточек */
        .hotel-card {
            transition: all 0.3s ease;
        }
        .hotel-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }

        /* Стили для скролла списка */
        .hotels-list {
            height: calc(100vh - 180px);
            overflow-y: auto;
            scrollbar-width: thin;
        }
        .hotels-list::-webkit-scrollbar {
            width: 6px;
        }
        .hotels-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        .hotels-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .hotels-list::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
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
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Фильтры поиска (сверху) -->
        <div class="bg-white border-b border-gray-100 sticky top-0 z-20 shadow-sm">
            <div class="container mx-auto px-4 lg:px-8 max-w-7xl py-4">
                <form action="{{ route('hotels.search') }}" method="GET" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Город/Отель</label>
                        <input type="text"
                               name="destination"
                               value="{{ request('destination') }}"
                               placeholder="Куда едем?"
                               class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent">
                    </div>

                    <div class="w-40">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Заезд</label>
                        <input type="date"
                               name="check_in"
                               value="{{ request('check_in', now()->format('Y-m-d')) }}"
                               min="{{ now()->format('Y-m-d') }}"
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
                    </div>

                    <div class="w-40">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Выезд</label>
                        <input type="date"
                               name="check_out"
                               value="{{ request('check_out', now()->addDays(3)->format('Y-m-d')) }}"
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
                    </div>

                    <div class="w-32">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Гости</label>
                        <select name="adults" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ request('adults', 2) == $i ? 'selected' : '' }}>
                                    {{ $i }} взросл{{ $i == 1 ? 'ый' : 'ых' }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit" class="px-6 py-2 bg-gray-900 text-white hover:bg-gray-800 rounded-lg transition-colors">
                        Найти
                    </button>

                    @if(request()->has('destination'))
                        <a href="{{ route('hotels.search') }}" class="px-4 py-2 text-gray-400 hover:text-gray-600 transition-colors">
                            Сбросить
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Основной контент: список слева, карта справа -->
        <div class="flex flex-col lg:flex-row">
            <!-- Левая колонка - список отелей -->
            <div class="w-full lg:w-1/2 xl:w-2/5 border-r border-gray-100 bg-white">
                <div class="p-4 border-b border-gray-100 bg-white sticky top-[72px] z-10">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-900">
                            Найдено отелей: <span class="font-bold">{{ $hotels->total() ?? 0 }}</span>
                        </h2>
                        <select name="sort" form="search-form" class="px-3 py-1.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none">
                            <option value="recommended">Рекомендованные</option>
                            <option value="price_asc">Сначала дешевые</option>
                            <option value="price_desc">Сначала дорогие</option>
                            <option value="rating">По рейтингу</option>
                        </select>
                    </div>
                </div>

                <div class="hotels-list">
                    @forelse($hotels as $hotel)
                        <div class="hotel-card border-b border-gray-100 p-4 hover:bg-gray-50 cursor-pointer transition-all"
                             data-lat="{{ $hotel->latitude }}"
                             data-lng="{{ $hotel->longitude }}"
                             data-id="{{ $hotel->id }}"
                             data-name="{{ $hotel->name }}"
                             onclick="focusOnHotel({{ $hotel->latitude }}, {{ $hotel->longitude }}, {{ $hotel->id }})">
                            <div class="flex gap-4">
                                <!-- Изображение отеля -->
                                <div class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden">
                                    @if($hotel->main_image)
                                        <img src="{{ $hotel->main_image }}"
                                             alt="{{ $hotel->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-white text-2xl font-bold">
                                            {{ substr($hotel->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Информация -->
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-semibold text-gray-900 hover:text-gray-600">
                                                <a href="{{ route('hotels.show', $hotel->id) }}">
                                                    {{ $hotel->name }}
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-0.5">
                                                {{ $hotel->address }}, {{ $hotel->city }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <div class="flex items-center gap-1">
                                                <span class="bg-gray-900 text-white text-sm font-medium px-2 py-0.5 rounded">
                                                    {{ number_format($hotel->rating, 1) }}
                                                </span>
                                                <span class="text-xs text-gray-500">/ 5</span>
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

                                    <!-- Краткое описание -->
                                    <p class="text-sm text-gray-600 mt-2 line-clamp-2">
                                        {{ $hotel->short_description ?? substr(strip_tags($hotel->description ?? ''), 0, 100) }}
                                    </p>

                                    <!-- 🔥 ИСПРАВЛЕННЫЙ БЛОК: Удобства и цена -->
                                    <div class="flex justify-between items-center mt-3">
                                        <div class="flex flex-wrap gap-1">
                                            @php
                                                $amenitiesArray = is_array($hotel->amenities) ? $hotel->amenities : ($hotel->amenities ? $hotel->amenities->toArray() : []);
                                                $displayAmenities = array_slice($amenitiesArray, 0, 3);
                                            @endphp
                                            @foreach($displayAmenities as $amenity)
                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                                    {{ is_array($amenity) ? $amenity['name'] : $amenity->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xl font-bold text-gray-900">
                                                {{ number_format($hotel->min_price ?? 0, 0, '.', ' ') }} ₽
                                            </span>
                                            <span class="text-xs text-gray-500">/ночь</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <div class="text-5xl mb-4">🏨</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Отели не найдены</h3>
                            <p class="text-gray-500">Попробуйте изменить параметры поиска</p>
                        </div>
                    @endforelse

                    <!-- Пагинация -->
                    @if($hotels->hasPages())
                        <div class="p-4 border-t border-gray-100">
                            {{ $hotels->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Правая колонка - карта -->
            <div class="w-full lg:w-1/2 xl:w-3/5 h-[calc(100vh-72px)] sticky top-[72px] bg-gray-100">
                <div id="map" class="w-full h-full"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let map;
        let markers = [];
        let currentMarker = null;

        // Данные отелей из PHP
        const hotels = @json($hotels->items());

        // Инициализация карты
        function initMap() {
            // Центр карты (по умолчанию Москва)
            let center = [55.7558, 37.6176];

            // Если есть отели, центрируем на первом
            if (hotels.length > 0 && hotels[0].latitude && hotels[0].longitude) {
                center = [hotels[0].latitude, hotels[0].longitude];
            }

            // Создаем карту
            map = L.map('map').setView(center, 12);

            // Добавляем тайлы (бесплатные OpenStreetMap)
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 19,
                minZoom: 3
            }).addTo(map);

            // Добавляем маркеры
            addMarkers();
        }

        // Добавление маркеров на карту
        function addMarkers() {
            hotels.forEach(hotel => {
                if (hotel.latitude && hotel.longitude) {
                    // Создаем кастомную иконку
                    const customIcon = L.divIcon({
                        html: `<div class="w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center border-2 border-gray-900">
                            <span class="text-xs font-bold text-gray-900">🏨</span>
                           </div>`,
                        className: 'custom-marker',
                        iconSize: [32, 32],
                        popupAnchor: [0, -16]
                    });

                    const marker = L.marker([hotel.latitude, hotel.longitude], { icon: customIcon })
                        .addTo(map)
                        .bindPopup(`
                        <div class="p-2 max-w-xs">
                            <h3 class="font-semibold text-gray-900">${escapeHtml(hotel.name)}</h3>
                            <p class="text-sm text-gray-600 mt-1">${escapeHtml(hotel.address)}, ${escapeHtml(hotel.city)}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="bg-gray-900 text-white text-xs px-2 py-0.5 rounded">${hotel.rating || 0}</span>
                                <span class="text-xs text-gray-500">${hotel.review_count || 0} отзывов</span>
                            </div>
                            <div class="mt-2">
                                <span class="text-lg font-bold text-gray-900">${formatPrice(hotel.min_price)} ₽</span>
                                <span class="text-xs text-gray-500">/ночь</span>
                            </div>
                            <a href="/hotels/${hotel.id}" class="inline-block mt-3 text-sm text-gray-900 font-medium hover:underline">Подробнее →</a>
                        </div>
                    `);

                    // Сохраняем маркер с ID отеля
                    marker.hotelId = hotel.id;
                    markers.push(marker);

                    // Добавляем событие клика по маркеру
                    marker.on('click', () => {
                        highlightHotelCard(hotel.id);
                    });
                }
            });
        }

        // Функция экранирования HTML
        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        // Фокусировка на отеле (центрирование карты и выделение маркера)
        function focusOnHotel(lat, lng, hotelId) {
            if (!map) return;

            // Центрируем карту
            map.setView([lat, lng], 15);

            // Находим и анимируем маркер
            const marker = markers.find(m => m.hotelId === hotelId);
            if (marker) {
                marker.openPopup();

                // Визуальное выделение маркера
                if (currentMarker) {
                    currentMarker._icon.classList.remove('marker-active');
                }
                if (marker._icon) {
                    marker._icon.classList.add('marker-active');
                    currentMarker = marker;
                }
            }

            // Выделяем карточку в списке
            highlightHotelCard(hotelId);
        }

        // Подсветка карточки отеля в списке
        function highlightHotelCard(hotelId) {
            // Убираем подсветку со всех карточек
            document.querySelectorAll('.hotel-card').forEach(card => {
                card.classList.remove('bg-gray-50', 'border-l-4', 'border-l-gray-900');
            });

            // Подсвечиваем выбранную
            const selectedCard = document.querySelector(`.hotel-card[data-id="${hotelId}"]`);
            if (selectedCard) {
                selectedCard.classList.add('bg-gray-50', 'border-l-4', 'border-l-gray-900');
                selectedCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        // Форматирование цены
        function formatPrice(price) {
            if (!price) return '0';
            return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
        }

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', () => {
            initMap();
        });

        // Обработка изменения размера окна
        window.addEventListener('resize', () => {
            if (map) {
                setTimeout(() => map.invalidateSize(), 100);
            }
        });
    </script>

    <style>
        .custom-marker div {
            transition: all 0.2s ease;
        }
        .marker-active div {
            transform: scale(1.2);
            border-color: #F53003 !important;
            border-width: 3px !important;
        }
    </style>
@endpush
