<?php

namespace App\Http\Requests\Api\Review;

use Illuminate\Foundation\Http\FormRequest;

class FilterReviewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sort' => 'nullable|in:recent,rating_desc,rating_asc,helpful',
            'rating' => 'nullable|integer|min:1|max:5',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after:from_date',
            'has_response' => 'nullable|boolean',
            'verified_only' => 'nullable|boolean',
            'with_photos' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1|max:50',
        ];
    }
}
