<?php

namespace App\Http\Requests\Api\Room;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create-room') ?? false;
    }

    public function rules(): array
    {
        return [
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'room_number' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'type' => 'required|in:standard,superior,deluxe,suite,family,studio,apartment',
            'capacity_adults' => 'required|integer|min:1|max:10',
            'capacity_children' => 'required|integer|min:0|max:5',
            'total_capacity' => 'nullable|integer|min:1|max:15',
            'size_sqm' => 'nullable|integer|min:5|max:500',
            'bed_type' => 'nullable|in:single,double,queen,king,twin,bunk,sofa_bed',
            'bed_count' => 'nullable|integer|min:1|max:10',
            'price_per_night' => 'required|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'price_is_per_person' => 'nullable|boolean',
            'quantity' => 'required|integer|min:1|max:100',
            'available_quantity' => 'nullable|integer|min:0',
            'main_image' => 'nullable|image|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
            'is_active' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'hotel_id.required' => 'Отель обязателен',
            'hotel_id.exists' => 'Указанный отель не существует',
            'name.required' => 'Название номера обязательно',
            'type.required' => 'Тип номера обязателен',
            'type.in' => 'Недопустимый тип номера',
            'capacity_adults.required' => 'Вместимость для взрослых обязательна',
            'capacity_adults.min' => 'Минимум 1 взрослый',
            'price_per_night.required' => 'Цена за ночь обязательна',
            'price_per_night.min' => 'Цена не может быть отрицательной',
            'quantity.required' => 'Количество номеров обязательно',
            'quantity.min' => 'Минимум 1 номер',
        ];
    }
}
