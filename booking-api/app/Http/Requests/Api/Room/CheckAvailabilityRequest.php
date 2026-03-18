<?php

namespace App\Http\Requests\Api\Room;

use Illuminate\Foundation\Http\FormRequest;

class CheckAvailabilityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'nullable|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:5',
        ];
    }

    public function messages(): array
    {
        return [
            'check_in.required' => 'Дата заезда обязательна',
            'check_in.after' => 'Дата заезда не может быть раньше сегодня',
            'check_out.required' => 'Дата выезда обязательна',
            'check_out.after' => 'Дата выезда должна быть позже даты заезда',
        ];
    }
}
