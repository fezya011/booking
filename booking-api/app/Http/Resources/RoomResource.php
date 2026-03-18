<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hotel_id' => $this->hotel_id,
            'hotel' => new HotelResource($this->whenLoaded('hotel')),
            'name' => $this->name,
            'room_number' => $this->room_number,
            'description' => $this->description,
            'type' => $this->type,
            'type_label' => $this->getTypeLabel(),
            'capacity' => [
                'adults' => $this->capacity_adults,
                'children' => $this->capacity_children,
                'total' => $this->total_capacity,
            ],
            'size_sqm' => $this->size_sqm,
            'beds' => [
                'type' => $this->bed_type,
                'type_label' => $this->getBedTypeLabel(),
                'count' => $this->bed_count,
            ],
            'prices' => [
                'per_night' => (float)$this->price_per_night,
                'weekend' => (float)$this->weekend_price,
                'sale' => (float)$this->sale_price,
                'is_per_person' => (bool)$this->price_is_per_person,
            ],
            'current_price' => $this->getCurrentPrice(),
            'availability' => [
                'total' => $this->quantity,
                'available' => $this->available_quantity,
                'is_available' => (bool)$this->is_available,
            ],
            'main_image' => $this->main_image ? url('storage/' . $this->main_image) : null,
            'gallery' => collect($this->gallery)->map(fn($img) => url('storage/' . $img)),
            'amenities' => AmenityResource::collection($this->whenLoaded('amenities')),
            'is_active' => (bool)$this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function getTypeLabel(): string
    {
        return [
            'standard' => 'Стандарт',
            'superior' => 'Улучшенный',
            'deluxe' => 'Делюкс',
            'suite' => 'Люкс',
            'family' => 'Семейный',
            'studio' => 'Студия',
            'apartment' => 'Апартаменты',
        ][$this->type] ?? $this->type;
    }

    private function getBedTypeLabel(): string
    {
        return [
            'single' => 'Односпальная',
            'double' => 'Двуспальная',
            'queen' => 'Queen size',
            'king' => 'King size',
            'twin' => 'Twin',
            'bunk' => 'Двухъярусная',
            'sofa_bed' => 'Диван-кровать',
        ][$this->bed_type] ?? $this->bed_type;
    }

    private function getCurrentPrice(): float
    {
        if ($this->sale_price && $this->sale_price < $this->price_per_night) {
            return (float)$this->sale_price;
        }
        return (float)$this->price_per_night;
    }
}
