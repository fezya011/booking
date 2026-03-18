<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'avatar' => $this->avatar ? url('storage/' . $this->avatar) : null,
            'bio' => $this->bio,
            'country' => $this->country,
            'city' => $this->city,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'is_active' => $this->is_active,
            'last_login_at' => $this->last_login_at?->diffForHumans(),
            'last_login_ip' => $this->last_login_ip,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),

            // Статистика (загружается при необходимости)
            'stats' => $this->when($request->has('with_stats'), [
                'bookings_count' => $this->bookings()->count(),
                'reviews_count' => $this->reviews()->count(),
            ]),
        ];
    }
}
