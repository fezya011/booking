<?php

namespace App\Services\Review;

use App\Models\Review;
use App\Http\Requests\Api\Review\FilterReviewRequest;
use Illuminate\Contracts\Auth\Authenticatable;

class FilterReviewsService
{
    public function execute(FilterReviewRequest $request, int $hotelId, ?Authenticatable $user = null)
    {
        $query = Review::where('hotel_id', $hotelId)
            ->with('user')
            ->where('is_approved', true);

        // Сортировка
        $this->applySorting($query, $request->get('sort', 'recent'));

        // Фильтры
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->boolean('has_response')) {
            $query->whereNotNull('hotel_response');
        }

        if ($request->boolean('verified_only')) {
            $query->where('is_verified', true);
        }

        if ($request->boolean('with_photos')) {
            $query->whereNotNull('images')->where('images', '!=', '[]');
        }

        $reviews = $query->paginate($request->get('per_page', 10));

        // Добавляем информацию о голосовании пользователя
        if ($user) {
            foreach ($reviews as $review) {
                $review->user_voted = in_array(
                    $user->id,
                    $review->helpful_users ?? []
                );
            }
        }

        return $reviews;
    }

    private function applySorting($query, string $sort): void
    {
        match ($sort) {
            'rating_desc' => $query->orderBy('rating', 'desc'),
            'rating_asc' => $query->orderBy('rating', 'asc'),
            'helpful' => $query->orderBy('helpful_votes', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };
    }
}
