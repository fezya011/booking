<?php

namespace App\Services\Review;

use App\Models\Review;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

class UpdateReviewService
{
    public function execute(Review $review, array $data): Review
    {
        return DB::transaction(function () use ($review, $data) {
            // Пересчет рейтинга если изменились оценки
            if ($this->ratingsChanged($review, $data)) {
                $data['rating'] = $this->calculateNewRating($review, $data);
            }

            $review->update($data);

            // Обновляем рейтинг отеля
            app(UpdateHotelRatingService::class)->execute($review->hotel);

            return $review->fresh();
        });
    }

    private function ratingsChanged(Review $review, array $data): bool
    {
        return isset($data['rating_cleanliness']) ||
            isset($data['rating_comfort']) ||
            isset($data['rating_location']) ||
            isset($data['rating_service']) ||
            isset($data['rating_value']);
    }

    private function calculateNewRating(Review $review, array $data): float
    {
        $sum = ($data['rating_cleanliness'] ?? $review->rating_cleanliness) +
            ($data['rating_comfort'] ?? $review->rating_comfort) +
            ($data['rating_location'] ?? $review->rating_location) +
            ($data['rating_service'] ?? $review->rating_service) +
            ($data['rating_value'] ?? $review->rating_value);

        return round($sum / 5, 2);
    }
}
