<?php

namespace App\Http\Requests\Api\Review;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'rating_cleanliness' => 'required|integer|min:1|max:5',
            'rating_comfort' => 'required|integer|min:1|max:5',
            'rating_location' => 'required|integer|min:1|max:5',
            'rating_service' => 'required|integer|min:1|max:5',
            'rating_value' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:2000',
            'pros' => 'nullable|string|max:1000',
            'cons' => 'nullable|string|max:1000',
            'travel_date' => 'nullable|date|before:today',
            'travel_type' => 'nullable|in:alone,couple,family,friends,business',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:2048|mimes:jpeg,png,jpg,gif',
        ];
    }

    public function messages(): array
    {
        return [
            'rating_cleanliness.required' => 'Оцените чистоту',
            'rating_comfort.required' => 'Оцените комфорт',
            'rating_location.required' => 'Оцените расположение',
            'rating_service.required' => 'Оцените обслуживание',
            'rating_value.required' => 'Оцените соотношение цена/качество',
            'travel_date.before' => 'Дата поездки должна быть в прошлом',
        ];
    }
}
