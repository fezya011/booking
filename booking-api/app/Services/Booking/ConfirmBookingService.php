<?php

namespace App\Services\Booking;

use App\Models\Booking;

class ConfirmBookingService
{
    public function execute(Booking $booking, array $data): Booking
    {
        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_id' => $data['payment_id'] ?? null,
            'payment_method' => $data['payment_method'] ?? 'card',
            'confirmed_at' => now(),
        ]);

        return $booking;
    }
}
