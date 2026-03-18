<?php

namespace App\Http\Requests\Api\Amenity;

use Illuminate\Foundation\Http\FormRequest;

class FilterAmenityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category' => 'nullable|in:hotel,room',
            'active' => 'nullable|boolean',
            'search' => 'nullable|string|max:100',
            'sort_by' => 'nullable|in:name,category,sort_order,created_at',
            'sort_order' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
