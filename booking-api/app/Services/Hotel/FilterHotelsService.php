<?php

namespace App\Services\Hotel;

use App\Models\Hotel;
use App\Http\Requests\Api\Hotel\FilterHotelRequest;

class FilterHotelsService
{
    public function execute(FilterHotelRequest $request)
    {
        $query = Hotel::query()
            ->with(['amenities', 'rooms'])
            ->withAvg('reviews as rating', 'rating')
            ->withCount('reviews as review_count');

        // Фильтр по городу
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Фильтр по стране
        if ($request->filled('country')) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        // Фильтр по звездам
        if ($request->filled('stars')) {
            $query->where('stars', $request->stars);
        }

        // Фильтр по цене
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('rooms', function ($q) use ($request) {
                if ($request->filled('min_price')) {
                    $q->where('price_per_night', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $q->where('price_per_night', '<=', $request->max_price);
                }
            });
        }

        // Фильтр по удобствам
        if ($request->filled('amenities')) {
            $query->whereHas('amenities', function ($q) use ($request) {
                $q->whereIn('amenities.id', $request->amenities);
            }, '=', count($request->amenities));
        }

        // Сортировка
        $sortField = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortField === 'price') {
            $query->withMin('rooms as min_price', 'price_per_night')
                ->orderBy('min_price', $sortOrder);
        } else {
            $query->orderBy($sortField, $sortOrder);
        }

        return $query->paginate($request->get('per_page', 15));
    }
}
