<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateBookingService
{
    public function execute(User $user, array $data): Booking
    {
        return DB::transaction(function () use ($user, $data) {
            // Находим номер
            $room = Room::with('hotel')->findOrFail($data['room_id']);

            // Проверяем доступность
            if (!$room->isAvailable($data['check_in'], $data['check_out'])) {
                throw new \Exception('Номер недоступен на выбранные даты');
            }

            // Расчет стоимости
            $priceCalculation = $this->calculatePrice($room, $data);

            // Генерация уникального номера бронирования
            $bookingNumber = $this->generateBookingNumber();

            // Создание бронирования
            $booking = Booking::create([
                'booking_number' => $bookingNumber,
                'user_id' => $user->id,
                'hotel_id' => $room->hotel_id,
                'room_id' => $room->id,
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'nights' => $priceCalculation['nights'],
                'adults' => $data['adults'],
                'children' => $data['children'] ?? 0,
                'total_guests' => $data['adults'] + ($data['children'] ?? 0),
                'price_per_night' => $priceCalculation['price_per_night'],
                'subtotal' => $priceCalculation['subtotal'],
                'tax_amount' => $priceCalculation['tax'],
                'service_fee' => $priceCalculation['service_fee'],
                'total_price' => $priceCalculation['total'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'special_requests' => $data['special_requests'] ?? null,
                'guest_details' => $this->prepareGuestDetails($data),
            ]);

            return $booking->load(['hotel', 'room']);
        });
    }

    private function calculatePrice(Room $room, array $data): array
    {
        $nights = (strtotime($data['check_out']) - strtotime($data['check_in'])) / 86400;
        $pricePerNight = $room->price_per_night;
        $subtotal = $pricePerNight * $nights;
        $tax = $subtotal * 0.10; // 10% налог
        $serviceFee = $subtotal * 0.05; // 5% сервисный сбор
        $total = $subtotal + $tax + $serviceFee;

        return [
            'nights' => $nights,
            'price_per_night' => $pricePerNight,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'service_fee' => $serviceFee,
            'total' => $total,
        ];
    }

    private function generateBookingNumber(): string
    {
        return 'BKG-' . strtoupper(uniqid()) . '-' . rand(1000, 9999);
    }

    private function prepareGuestDetails(array $data): ?array
    {
        if (!isset($data['guest_names']) && !isset($data['guest_emails'])) {
            return null;
        }

        $details = [];
        for ($i = 0; $i < $data['adults']; $i++) {
            $details[] = [
                'name' => $data['guest_names'][$i] ?? null,
                'email' => $data['guest_emails'][$i] ?? null,
                'phone' => $data['guest_phones'][$i] ?? null,
            ];
        }

        return $details;
    }
}
