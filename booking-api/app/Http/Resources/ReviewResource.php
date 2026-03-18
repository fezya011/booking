<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar ? url('storage/' . $this->user->avatar) : null,
                'country' => $this->guest_country ?? $this->user->country,
            ],
            'hotel_id' => $this->hotel_id,
            'rating' => (float)$this->rating,
            'ratings' => [
                'cleanliness' => $this->rating_cleanliness,
                'comfort' => $this->rating_comfort,
                'location' => $this->rating_location,
                'service' => $this->rating_service,
                'value' => $this->rating_value,
            ],
            'title' => $this->title,
            'comment' => $this->comment,
            'pros' => $this->pros,
            'cons' => $this->cons,
            'travel_info' => [
                'date' => $this->travel_date?->format('Y-m-d'),
                'type' => $this->travel_type,
                'type_label' => $this->getTravelTypeLabel(),
            ],
            'images' => collect($this->images)->map(fn($img) => url('storage/' . $img)),
            'is_verified' => (bool)$this->is_verified,
            'helpful' => [
                'votes' => $this->helpful_votes,
                'user_voted' => $this->user_voted ?? false,
            ],
            'hotel_response' => $this->when($this->hotel_response, [
                'text' => $this->hotel_response,
                'responded_at' => $this->responded_at?->diffForHumans(),
                'responded_by' => $this->responder?->name,
            ]),
            'created_at' => $this->created_at?->diffForHumans(),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function getTravelTypeLabel(): ?string
    {
        return [
            'alone' => 'Один/одна',
            'couple' => 'Пара',
            'family' => 'С семьей',
            'friends' => 'С друзьями',
            'business' => 'В командировке',
        ][$this->travel_type] ?? null;
    }
}
