<?php

namespace App\Http\Requests\Api\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $booking = $this->route('booking');
        return $this->user()?->can('update-booking', $booking) ?? false;
    }

    public function rules(): array
    {
        return [
            'check_in' => 'sometimes|date|after:today',
            'check_out' => 'sometimes|date|after:check_in',
            'adults' => 'sometimes|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:5',
            'special_requests' => 'nullable|string|max:500',
        ];
    }
}
