<?php

namespace App\Http\Requests\Api\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create-hotel') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'stars' => 'required|integer|min:1|max:5',
            'check_in_time' => 'nullable|string|size:5',
            'check_out_time' => 'nullable|string|size:5',
            'allows_pets' => 'nullable|boolean',
            'allows_children' => 'nullable|boolean',
            'has_wheelchair_access' => 'nullable|boolean',
            'main_image' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название отеля обязательно',
            'address.required' => 'Адрес обязателен',
            'city.required' => 'Город обязателен',
            'country.required' => 'Страна обязательна',
            'stars.required' => 'Количество звезд обязательно',
            'stars.min' => 'Минимум 1 звезда',
            'stars.max' => 'Максимум 5 звезд',
        ];
    }
}
