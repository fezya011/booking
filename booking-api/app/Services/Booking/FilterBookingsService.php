<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\User;
use App\Http\Requests\Api\Booking\FilterBookingRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FilterBookingsService
{
    public function execute(FilterBookingRequest $request, User $user): LengthAwarePaginator
    {
        $query = Booking::with(['hotel', 'room'])
            ->where('user_id', $user->id);

        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('check_in', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('check_out', '<=', $request->input('to_date'));
        }

        // Фильтр по отелю
        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->input('hotel_id'));
        }

        // Сортировка
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Пагинация
        $perPage = $request->input('per_page', 15);

        return $query->paginate($perPage);
    }
}
