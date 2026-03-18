<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    /**
     * Обновить профиль пользователя
     */
    public function updateProfile(User $user, array $data): User
    {
        // Обработка аватара
        if (isset($data['avatar'])) {
            // Удаляем старый аватар
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Сохраняем новый
            $path = $data['avatar']->store('avatars/' . $user->id, 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return $user->fresh();
    }

    /**
     * Получить статистику пользователя
     */
    public function getUserStats(User $user): array
    {
        return [
            'bookings_total' => $user->bookings()->count(),
            'bookings_completed' => $user->bookings()->where('status', 'completed')->count(),
            'bookings_cancelled' => $user->bookings()->where('status', 'cancelled')->count(),
            'reviews_total' => $user->reviews()->count(),
            'member_since' => $user->created_at->format('Y-m-d'),
            'last_login' => $user->last_login_at?->diffForHumans(),
        ];
    }
}
