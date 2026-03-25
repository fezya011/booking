<?php

namespace App\Http\Controllers\Web;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HotelController extends Controller
{
    private ApiService $api;

    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }

    /**
     * Страница со всеми отелями (с фильтрами)
     */
    public function index(Request $request)
    {
        // Параметры для API
        $params = [];

        if ($request->filled('search')) {
            $params['city'] = $request->search;
        }
        if ($request->filled('min_price')) {
            $params['min_price'] = $request->min_price;
        }
        if ($request->filled('max_price')) {
            $params['max_price'] = $request->max_price;
        }
        if ($request->filled('stars')) {
            $params['stars'] = $request->stars;
        }
        if ($request->filled('amenities')) {
            $params['amenities'] = $request->amenities;
        }
        if ($request->filled('sort_by')) {
            $params['sort_by'] = $request->sort_by;
        }
        if ($request->filled('sort_order')) {
            $params['sort_order'] = $request->sort_order;
        }
        $params['per_page'] = $request->get('per_page', 15);

        // Запрос к API
        $response = $this->api->get('hotels', $params);

        // 🔥 ИСПРАВЛЕНО: данные могут быть в response['data']['data']
        $hotelsData = [];
        $meta = [];

        if (isset($response['data']['data'])) {
            // Структура: { success: true, data: { data: [...] }, meta: {...} }
            $hotelsData = $response['data']['data'];
            $meta = $response['meta'] ?? [];
        } elseif (isset($response['data'])) {
            // Альтернативная структура
            $hotelsData = $response['data'];
            $meta = $response['meta'] ?? [];
        } else {
            $hotelsData = [];
        }

        // Преобразуем в коллекцию объектов
        $hotels = collect($hotelsData)->map(function ($hotel) {
            return (object) $hotel;
        });

        // Создаем объект пагинации
        $paginator = new LengthAwarePaginator(
            $hotels,
            $meta['total'] ?? $hotels->count(),
            $meta['per_page'] ?? 15,
            $meta['current_page'] ?? 1,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Получаем список удобств для фильтров
        $amenitiesResponse = $this->api->get('amenities', ['category' => 'hotel']);
        $amenitiesData = $amenitiesResponse['data']['data'] ?? $amenitiesResponse['data'] ?? [];
        $amenities = collect($amenitiesData)->map(fn($a) => (object) $a);

        return view('hotels.index', [
            'hotels' => $paginator,
            'amenities' => $amenities,
        ]);
    }

    /**
     * Страница поиска отелей с картой
     */
    public function search(Request $request)
    {
        // Параметры для API
        $params = [];

        if ($request->filled('destination')) {
            $params['city'] = $request->destination;
        }

        if ($request->filled('check_in')) {
            $params['check_in'] = $request->check_in;
        }

        if ($request->filled('check_out')) {
            $params['check_out'] = $request->check_out;
        }

        if ($request->filled('adults')) {
            $params['adults'] = $request->adults;
        }

        if ($request->filled('sort')) {
            $params['sort_by'] = $request->sort;
            $params['sort_order'] = $request->sort === 'price_asc' ? 'asc' : 'desc';
        }

        $params['per_page'] = 20;

        // Запрос к API
        $response = $this->api->get('hotels', $params);

        // 🔥 ИСПРАВЛЕНО
        $hotelsData = [];
        $meta = [];

        if (isset($response['data']['data'])) {
            $hotelsData = $response['data']['data'];
            $meta = $response['meta'] ?? [];
        } elseif (isset($response['data'])) {
            $hotelsData = $response['data'];
            $meta = $response['meta'] ?? [];
        }

        $hotels = collect($hotelsData)->map(function ($hotel) {
            return (object) $hotel;
        });

        $paginator = new LengthAwarePaginator(
            $hotels,
            $meta['total'] ?? $hotels->count(),
            $meta['per_page'] ?? 15,
            $meta['current_page'] ?? 1,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('hotels.search', [
            'hotels' => $paginator,
        ]);
    }

    /**
     * Детальная страница отеля
     */
    public function show($id)
    {
        $response = $this->api->get("hotels/{$id}");

        if (!($response['success'] ?? false)) {
            abort(404);
        }

        // 🔥 ИСПРАВЛЕНО: данные отеля могут быть в response['data']
        $hotelData = $response['data'] ?? [];

        // Если отель вложен в data.data
        if (isset($response['data']['data'])) {
            $hotelData = $response['data']['data'];
        }

        return view('hotels.show', [
            'hotel' => (object) $hotelData
        ]);
    }
}
