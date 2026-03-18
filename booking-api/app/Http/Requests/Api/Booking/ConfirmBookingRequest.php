<?php

namespace App\Http\Requests\Api\Booking;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'payment_id' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|in:cash,card,bank_transfer',
        ];
    }
}
