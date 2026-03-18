<?php

namespace App\Services\Review;

use App\Models\Hotel;
use App\Models\Review;

class UpdateHotelRatingService
{
    public function execute(Hotel $hotel): void
    {
        $averages = Review::where('hotel_id', $hotel->id)
            ->where('is_approved', true)
            ->selectRaw('
                AVG(rating_cleanliness) as avg_cleanliness,
                AVG(rating_comfort) as avg_comfort,
                AVG(rating_location) as avg_location,
                AVG(rating_service) as avg_service,
                AVG(rating_value) as avg_value,
                AVG(rating) as avg_total,
                COUNT(*) as count
            ')
            ->first();

        $hotel->update([
            'rating_cleanliness' => round($averages->avg_cleanliness ?? 0, 2),
            'rating_comfort' => round($averages->avg_comfort ?? 0, 2),
            'rating_location' => round($averages->avg_location ?? 0, 2),
            'rating_service' => round($averages->avg_service ?? 0, 2),
            'rating_value' => round($averages->avg_value ?? 0, 2),
            'rating' => round($averages->avg_total ?? 0, 2),
            'review_count' => $averages->count ?? 0,
        ]);
    }
}
