<?php
// app/Http/Controllers/Web/HomeController.php

namespace App\Http\Controllers\Web;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    private ApiService $api;

    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }

    public function index(Request $request)
    {
        // Получаем данные из API
        $hotelsResponse = $this->api->get('hotels', $request->all());

        // Преобразуем в объект пагинации Laravel
        $hotels = $this->createPaginator(
            $hotelsResponse['data'] ?? [],
            $hotelsResponse['meta']['total'] ?? 0,
            $hotelsResponse['meta']['per_page'] ?? 15,
            $hotelsResponse['meta']['current_page'] ?? 1
        );

        return view('home.index', compact('hotels'));
    }

    /**
     * Создает объект пагинации из данных API
     */
    private function createPaginator(array $items, int $total, int $perPage, int $currentPage): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
