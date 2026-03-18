<?php

namespace App\Services\Amenity;

use App\Models\Amenity;
use App\Http\Requests\Api\Amenity\FilterAmenityRequest;

class FilterAmenitiesService
{
    public function execute(FilterAmenityRequest $request)
    {
        $query = Amenity::query();

        // Фильтр по категории
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Фильтр по активности
        if ($request->filled('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Поиск по названию
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Сортировка
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');

        $query->orderBy($sortBy, $sortOrder)
            ->orderBy('name', 'asc');

        // Пагинация
        if ($request->filled('per_page')) {
            return $query->paginate($request->per_page);
        }

        return $query->get();
    }
}
