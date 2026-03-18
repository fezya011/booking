<?php

namespace App\Services\Room;

use App\Models\Room;
use Illuminate\Http\Request;

class FilterRoomsService
{
    public function execute(Request $request, $hotelId = null)
    {
        $query = Room::query()
            ->with(['amenities', 'hotel'])
            ->where('is_active', true);

        // Фильтр по отелю
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }

        // Фильтр по типу номера
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Фильтр по вместимости
        if ($request->filled('guests')) {
            $query->where('total_capacity', '>=', $request->guests);
        }

        // Фильтр по цене
        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        // Фильтр по удобствам
        if ($request->filled('amenities')) {
            $query->whereHas('amenities', function ($q) use ($request) {
                $q->whereIn('amenities.id', $request->amenities);
            }, '=', count($request->amenities));
        }

        // Фильтр по доступности на даты
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $query->whereDoesntHave('bookings', function ($q) use ($request) {
                $q->where('status', 'confirmed')
                    ->where(function ($query) use ($request) {
                        $query->whereBetween('check_in', [$request->check_in, $request->check_out])
                            ->orWhereBetween('check_out', [$request->check_in, $request->check_out])
                            ->orWhere(function ($q2) use ($request) {
                                $q2->where('check_in', '<=', $request->check_in)
                                    ->where('check_out', '>=', $request->check_out);
                            });
                    });
            });
        }

        return $query->get();
    }
}
