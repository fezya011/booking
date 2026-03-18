<?php

namespace App\Services\Room;

use App\Models\Room;
use App\Http\Requests\Api\Room\CheckAvailabilityRequest;

class CheckRoomAvailabilityService
{
    public function execute(Room $room, CheckAvailabilityRequest $request): array
    {
        // Проверка вместимости
        $capacityCheck = $this->checkCapacity($room, $request);

        // Проверка доступности на даты
        $availabilityCheck = $this->checkDates($room, $request);

        // Расчет цены
        $price = $this->calculatePrice($room, $request);

        return [
            'room_id' => $room->id,
            'room_name' => $room->name,
            'is_available' => $capacityCheck && $availabilityCheck,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'nights' => $this->calculateNights($request),
            'price_per_night' => $room->price_per_night,
            'total_price' => $price,
            'capacity_check' => [
                'required_adults' => (int)($request->adults ?? 2),
                'required_children' => (int)($request->children ?? 0),
                'max_adults' => $room->capacity_adults,
                'max_children' => $room->capacity_children,
                'is_sufficient' => $capacityCheck,
            ],
        ];
    }

    private function checkCapacity(Room $room, CheckAvailabilityRequest $request): bool
    {
        $adults = (int)($request->adults ?? 2);
        $children = (int)($request->children ?? 0);

        return $adults <= $room->capacity_adults &&
            $children <= $room->capacity_children &&
            ($adults + $children) <= ($room->total_capacity ?? $adults + $children);
    }

    private function checkDates(Room $room, CheckAvailabilityRequest $request): bool
    {
        return $room->isAvailable(
            $request->check_in,
            $request->check_out
        );
    }

    private function calculatePrice(Room $room, CheckAvailabilityRequest $request): float
    {
        return $room->calculatePrice(
            $request->check_in,
            $request->check_out
        );
    }

    private function calculateNights(CheckAvailabilityRequest $request): int
    {
        return (strtotime($request->check_out) - strtotime($request->check_in)) / 86400;
    }
}
