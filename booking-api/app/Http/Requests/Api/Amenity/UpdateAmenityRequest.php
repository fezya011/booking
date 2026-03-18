<?php

namespace App\Http\Requests\Api\Amenity;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAmenityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update-amenity', $this->route('amenity')) ?? false;
    }

    public function rules(): array
    {
        $amenity = $this->route('amenity');

        return [
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:amenities,slug,' . ($amenity?->id ?? 'NULL'),
            'icon' => 'nullable|string|max:100',
            'category' => 'sometimes|in:hotel,room',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0|max:999',
            'is_active' => 'nullable|boolean',
        ];
    }
}
