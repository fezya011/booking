<?php

namespace App\Http\Requests\Api\Booking;

use Illuminate\Foundation\Http\FormRequest;

class CancelBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $booking = $this->route('booking');
        return $this->user()?->can('cancel-booking', $booking) ?? false;
    }

    public function rules(): array
    {
        return [
            'reason' => 'nullable|string|max:255',
        ];
    }
}
