<?php

namespace App\Http\Requests\Api\Booking;

use Illuminate\Foundation\Http\FormRequest;

class FilterBookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'nullable|in:pending,confirmed,cancelled,completed',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after:from_date',
            'hotel_id' => 'nullable|exists:hotels,id',
            'sort_by' => 'nullable|in:created_at,check_in,check_out,total_price',
            'sort_order' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
