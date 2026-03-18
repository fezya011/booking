<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class UpdateBookingService
{
    public function execute(Booking $booking, array $data): Booking
    {
        return DB::transaction(function () use ($booking, $data) {
            // Нельзя обновить подтвержденное или отмененное бронирование
            if (in_array($booking->status, ['confirmed', 'cancelled', 'completed'])) {
                throw new \Exception("Нельзя обновить бронирование в статусе {$booking->status}");
            }

            // Если меняются даты, проверяем доступность и пересчитываем цену
            if (isset($data['check_in']) || isset($data['check_out'])) {
                $this->handleDateChange($booking, $data);
            }

            $booking->update($data);

            return $booking->fresh();
        });
    }

    private function handleDateChange(Booking $booking, array &$data): void
    {
        $checkIn = $data['check_in'] ?? $booking->check_in;
        $checkOut = $data['check_out'] ?? $booking->check_out;

        $room = Room::find($booking->room_id);

        // Проверяем доступность, исключая текущее бронирование
        if (!$room->isAvailable($checkIn, $checkOut, $booking->id)) {
            throw new \Exception('Номер недоступен на выбранные даты');
        }

        // Пересчет стоимости
        $nights = (strtotime($checkOut) - strtotime($checkIn)) / 86400;
        $subtotal = $room->price_per_night * $nights;

        $data['nights'] = $nights;
        $data['subtotal'] = $subtotal;
        $data['tax_amount'] = $subtotal * 0.10;
        $data['service_fee'] = $subtotal * 0.05;
        $data['total_price'] = $subtotal * 1.15;
    }
}
