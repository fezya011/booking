<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ChangePasswordService
{
    public function execute(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Текущий пароль неверен.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($newPassword)
        ]);
    }
}
