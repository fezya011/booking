<?php

namespace App\Services\Review;

use App\Models\Review;
use App\Models\User;

class HelpfulVoteService
{
    public function toggle(Review $review, User $user): array
    {
        $helpfulUsers = $review->helpful_users ?? [];
        $userId = $user->id;

        if (in_array($userId, $helpfulUsers)) {
            // Убираем голос
            $helpfulUsers = array_diff($helpfulUsers, [$userId]);
            $review->helpful_votes--;
            $message = 'Голос убран';
            $voted = false;
        } else {
            // Добавляем голос
            $helpfulUsers[] = $userId;
            $review->helpful_votes++;
            $message = 'Отзыв отмечен как полезный';
            $voted = true;
        }

        $review->helpful_users = array_values($helpfulUsers);
        $review->save();

        return [
            'message' => $message,
            'voted' => $voted,
            'votes_count' => $review->helpful_votes,
        ];
    }
}
