<?php

namespace App\Http\Requests\Api\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update-hotel', $this->route('hotel')) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:100',
            'country' => 'sometimes|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'stars' => 'sometimes|integer|min:1|max:5',
            'check_in_time' => 'nullable|string|size:5',
            'check_out_time' => 'nullable|string|size:5',
            'allows_pets' => 'nullable|boolean',
            'allows_children' => 'nullable|boolean',
            'has_wheelchair_access' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ];
    }
}
