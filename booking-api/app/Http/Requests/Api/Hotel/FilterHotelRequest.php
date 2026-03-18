<?php

namespace App\Http\Requests\Api\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class FilterHotelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'stars' => 'nullable|integer|min:1|max:5',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gt:min_price',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
            'check_in' => 'nullable|date|after:today',
            'check_out' => 'nullable|date|after:check_in',
            'guests' => 'nullable|integer|min:1',
            'sort_by' => 'nullable|in:price,rating,stars,name',
            'sort_order' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
