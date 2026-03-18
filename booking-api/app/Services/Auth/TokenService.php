<?php

namespace App\Services\Auth;

use App\Models\User;

class TokenService
{
    /**
     * Создать новый токен для пользователя
     */
    public function createToken(User $user, string $device = 'auth_token'): string
    {
        return $user->createToken($device)->plainTextToken;
    }

    /**
     * Удалить текущий токен
     */
    public function revokeCurrentToken(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Удалить все токены пользователя
     */
    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Удалить все токены кроме текущего
     */
    public function revokeAllExceptCurrent(User $user): void
    {
        $user->tokens()
            ->where('id', '!=', $user->currentAccessToken()->id)
            ->delete();
    }
}
