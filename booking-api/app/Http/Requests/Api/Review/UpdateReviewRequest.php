<?php

namespace App\Http\Requests\Api\Review;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        $review = $this->route('review');
        return $this->user()?->can('update-review', $review) ?? false;
    }

    public function rules(): array
    {
        return [
            'rating_cleanliness' => 'sometimes|integer|min:1|max:5',
            'rating_comfort' => 'sometimes|integer|min:1|max:5',
            'rating_location' => 'sometimes|integer|min:1|max:5',
            'rating_service' => 'sometimes|integer|min:1|max:5',
            'rating_value' => 'sometimes|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:2000',
            'pros' => 'nullable|string|max:1000',
            'cons' => 'nullable|string|max:1000',
        ];
    }
}
