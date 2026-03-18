<?php

namespace App\Services\Booking;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class CancelBookingService
{
    public function execute(Booking $booking, ?string $reason = null): Booking
    {
        return DB::transaction(function () use ($booking, $reason) {
            // Нельзя отменить завершенное или уже отмененное
            if (in_array($booking->status, ['completed', 'cancelled'])) {
                throw new \Exception("Нельзя отменить бронирование в статусе {$booking->status}");
            }

            // Проверка на дату заезда (нельзя отменить за 24 часа)
            if (strtotime($booking->check_in) < strtotime('+24 hours')) {
                throw new \Exception('Отмена возможна не менее чем за 24 часа до заезда');
            }

            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $reason ?? 'Отменено пользователем',
            ]);

            return $booking;
        });
    }
}
