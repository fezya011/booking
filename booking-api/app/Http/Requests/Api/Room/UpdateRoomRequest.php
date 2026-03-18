<?php

namespace App\Http\Requests\Api\Room;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update-room', $this->route('room')) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'room_number' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'type' => 'sometimes|in:standard,superior,deluxe,suite,family,studio,apartment',
            'capacity_adults' => 'sometimes|integer|min:1|max:10',
            'capacity_children' => 'sometimes|integer|min:0|max:5',
            'total_capacity' => 'nullable|integer|min:1|max:15',
            'size_sqm' => 'nullable|integer|min:5|max:500',
            'bed_type' => 'nullable|in:single,double,queen,king,twin,bunk,sofa_bed',
            'bed_count' => 'nullable|integer|min:1|max:10',
            'price_per_night' => 'sometimes|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'price_is_per_person' => 'nullable|boolean',
            'quantity' => 'sometimes|integer|min:1|max:100',
            'available_quantity' => 'nullable|integer|min:0',
            'main_image' => 'nullable|image|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
            'is_active' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
        ];
    }
}
