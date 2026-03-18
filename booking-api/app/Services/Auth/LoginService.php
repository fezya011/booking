<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginService
{
    public function execute(string $email, string $password, ?string $ip = null): User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Предоставленные учетные данные неверны.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Ваш аккаунт деактивирован.'],
            ]);
        }

        // Обновляем время последнего входа
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);

        return $user;
    }
}
