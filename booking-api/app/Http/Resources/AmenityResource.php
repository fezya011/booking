<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AmenityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'category' => $this->category,
            'category_label' => $this->category === 'hotel' ? 'Отельные' : 'В номере',
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'is_active' => (bool)$this->is_active,
            'usage' => [
                'hotels_count' => $this->whenCounted('hotels', $this->hotels_count ?? 0),
                'rooms_count' => $this->whenCounted('rooms', $this->rooms_count ?? 0),
            ],
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
