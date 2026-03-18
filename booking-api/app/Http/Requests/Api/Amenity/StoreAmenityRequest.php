<?php

namespace App\Http\Requests\Api\Amenity;

use Illuminate\Foundation\Http\FormRequest;

class StoreAmenityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create-amenity') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:amenities,slug',
            'icon' => 'nullable|string|max:100',
            'category' => 'required|in:hotel,room',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0|max:999',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название удобства обязательно',
            'slug.required' => 'Slug обязателен',
            'slug.unique' => 'Такой slug уже существует',
            'category.required' => 'Категория обязательна',
            'category.in' => 'Категория должна быть hotel или room',
        ];
    }
}
