<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_number' => $this->booking_number,
            'hotel' => new HotelResource($this->whenLoaded('hotel')),
            'room' => new RoomResource($this->whenLoaded('room')),
            'dates' => [
                'check_in' => $this->check_in->format('Y-m-d'),
                'check_out' => $this->check_out->format('Y-m-d'),
                'check_in_time' => $this->hotel?->check_in_time ?? '14:00',
                'check_out_time' => $this->hotel?->check_out_time ?? '12:00',
                'nights' => $this->nights,
            ],
            'guests' => [
                'adults' => $this->adults,
                'children' => $this->children,
                'total' => $this->total_guests,
                'details' => $this->guest_details,
            ],
            'price' => [
                'per_night' => (float)$this->price_per_night,
                'subtotal' => (float)$this->subtotal,
                'tax' => (float)$this->tax_amount,
                'service_fee' => (float)$this->service_fee,
                'total' => (float)$this->total_price,
            ],
            'status' => [
                'current' => $this->status,
                'label' => $this->getStatusLabel(),
                'color' => $this->getStatusColor(),
            ],
            'payment' => [
                'status' => $this->payment_status,
                'method' => $this->payment_method,
                'id' => $this->payment_id,
            ],
            'special_requests' => $this->special_requests,
            'cancellation' => $this->when($this->cancelled_at, [
                'cancelled_at' => $this->cancelled_at?->format('Y-m-d H:i:s'),
                'reason' => $this->cancellation_reason,
            ]),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Ожидает подтверждения',
            'confirmed' => 'Подтверждено',
            'cancelled' => 'Отменено',
            'completed' => 'Завершено',
            default => $this->status,
        };
    }

    private function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'green',
            'cancelled' => 'red',
            'completed' => 'blue',
            default => 'gray',
        };
    }
}
