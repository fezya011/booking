<?php

namespace App\Http\Requests\Api\Amenity;

use Illuminate\Foundation\Http\FormRequest;

class BulkStoreAmenityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create-amenity') ?? false;
    }

    public function rules(): array
    {
        return [
            'amenities' => 'required|array|min:1|max:50',
            'amenities.*.name' => 'required|string|max:255',
            'amenities.*.slug' => 'required|string|max:255|distinct|unique:amenities,slug',
            'amenities.*.icon' => 'nullable|string|max:100',
            'amenities.*.category' => 'required|in:hotel,room',
            'amenities.*.description' => 'nullable|string|max:500',
            'amenities.*.sort_order' => 'nullable|integer|min:0|max:999',
        ];
    }
}
