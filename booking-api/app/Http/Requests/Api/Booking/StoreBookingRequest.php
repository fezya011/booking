<?php

namespace App\Http\Requests\Api\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:5',
            'special_requests' => 'nullable|string|max:500',
            'guest_names' => 'nullable|array',
            'guest_emails' => 'nullable|array',
            'guest_phones' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'Выберите номер',
            'room_id.exists' => 'Указанный номер не существует',
            'check_in.required' => 'Укажите дату заезда',
            'check_in.after' => 'Дата заезда не может быть раньше сегодня',
            'check_out.required' => 'Укажите дату выезда',
            'check_out.after' => 'Дата выезда должна быть позже даты заезда',
            'adults.required' => 'Укажите количество взрослых',
            'adults.min' => 'Минимум 1 взрослый',
        ];
    }
}
