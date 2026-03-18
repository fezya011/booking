<?php

namespace App\Services\Review;

use App\Models\Review;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateReviewService
{
    public function execute(User $user, Hotel $hotel, array $data): Review
    {
        return DB::transaction(function () use ($user, $hotel, $data) {
            // Проверка на существующий отзыв
            if ($this->userAlreadyReviewed($user, $hotel)) {
                throw new \Exception('Вы уже оставляли отзыв на этот отель');
            }

            // Проверка завершенного бронирования
            $hasCompletedBooking = $this->hasCompletedBooking($user, $hotel);

            // Вычисляем общий рейтинг
            $averageRating = $this->calculateAverageRating($data);

            // Обработка изображений
            $images = $this->handleImages($data['images'] ?? []);

            // Создание отзыва
            $review = Review::create([
                'user_id' => $user->id,
                'hotel_id' => $hotel->id,
                'rating_cleanliness' => $data['rating_cleanliness'],
                'rating_comfort' => $data['rating_comfort'],
                'rating_location' => $data['rating_location'],
                'rating_service' => $data['rating_service'],
                'rating_value' => $data['rating_value'],
                'rating' => $averageRating,
                'title' => $data['title'] ?? null,
                'comment' => $data['comment'] ?? null,
                'pros' => $data['pros'] ?? null,
                'cons' => $data['cons'] ?? null,
                'travel_date' => $data['travel_date'] ?? null,
                'travel_type' => $data['travel_type'] ?? null,
                'images' => $images,
                'is_verified' => $hasCompletedBooking,
                'guest_name' => $user->name,
                'guest_country' => $user->country,
            ]);

            // Обновляем рейтинг отеля
            app(UpdateHotelRatingService::class)->execute($hotel);

            return $review->load('user');
        });
    }

    private function userAlreadyReviewed(User $user, Hotel $hotel): bool
    {
        return Review::where('user_id', $user->id)
            ->where('hotel_id', $hotel->id)
            ->exists();
    }

    private function hasCompletedBooking(User $user, Hotel $hotel): bool
    {
        return $hotel->bookings()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->exists();
    }

    private function calculateAverageRating(array $data): float
    {
        $sum = $data['rating_cleanliness'] +
            $data['rating_comfort'] +
            $data['rating_location'] +
            $data['rating_service'] +
            $data['rating_value'];

        return round($sum / 5, 2);
    }

    private function handleImages(array $images): array
    {
        $paths = [];
        foreach ($images as $image) {
            $paths[] = $image->store('reviews', 'public');
        }
        return $paths;
    }
}
