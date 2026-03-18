<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'stars' => $this->stars,
            'rating' => round($this->rating, 1),
            'review_count' => $this->review_count,
            'rating_breakdown' => [
                'cleanliness' => round($this->rating_cleanliness, 1),
                'comfort' => round($this->rating_comfort, 1),
                'location' => round($this->rating_location, 1),
                'service' => round($this->rating_service, 1),
                'value' => round($this->rating_value, 1),
            ],
            'main_image' => $this->main_image ? url('storage/' . $this->main_image) : null,
            'gallery' => collect($this->gallery)->map(fn($img) => url('storage/' . $img)),
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'amenities' => AmenityResource::collection($this->whenLoaded('amenities')),
            'rooms' => RoomResource::collection($this->whenLoaded('rooms')),
            'recent_reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'policies' => [
                'allows_pets' => $this->allows_pets,
                'allows_children' => $this->allows_children,
                'allows_smoking' => $this->allows_smoking,
                'has_wheelchair_access' => $this->has_wheelchair_access,
            ],
            'languages' => $this->languages,
            'nearby_places' => $this->nearby_places,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
