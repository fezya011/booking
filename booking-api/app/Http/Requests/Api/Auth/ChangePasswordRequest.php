<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null; // Только авторизованные
    }

    public function rules(): array
    {
        return [
            'current_password' => 'required|string',
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Текущий пароль обязателен',
            'new_password.required' => 'Новый пароль обязателен',
            'new_password.confirmed' => 'Пароли не совпадают',
        ];
    }
}
