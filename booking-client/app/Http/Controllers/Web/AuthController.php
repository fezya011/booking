<?php
// app/Http/Controllers/Web/AuthController.php

namespace App\Http\Controllers\Web;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private ApiService $api;

    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }

    /**
     * Показать форму входа
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Обработка входа
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $response = $this->api->post('auth/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (isset($response['success']) && $response['success']) {
            $token = $response['data']['token'] ?? null;
            $user = $response['data']['user'] ?? $response['data'] ?? null;

            // 🔥 ОТЛАДКА
            Log::info('Token and user', [
                'token_exists' => !empty($token),
                'user_exists' => !empty($user)
            ]);

            if ($token && $user) {
                Session::put('api_token', $token);
                Session::put('user', $user);

                // 🔥 ОТЛАДКА - проверяем, что сохранилось
                Log::info('Session after login', [
                    'api_token' => Session::get('api_token'),
                    'user' => Session::get('user')
                ]);

                return redirect()->intended(route('home'))->with('success', 'Добро пожаловать!');
            }
        }

        // Обработка ошибок API
        $errors = $this->parseApiErrors($response);

        return back()->withErrors($errors)->withInput();
    }

    /**
     * Показать форму регистрации
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Обработка регистрации
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $response = $this->api->post('auth/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if (isset($response['success']) && $response['success']) {
            $token = $response['data']['token'] ?? null;
            $user = $response['data']['user'] ?? $response['data'] ?? null;

            if ($token && $user) {
                Session::put('api_token', $token);
                Session::put('user', $user);

                return redirect()->route('home')->with('success', 'Регистрация успешна! Добро пожаловать!');
            }
        }

        // Обработка ошибок API
        $errors = $this->parseApiErrors($response);

        return back()->withErrors($errors)->withInput();
    }

    /**
     * Выход из системы
     */
    public function logout()
    {
        if (Session::has('api_token')) {
            $this->api->post('auth/logout');
            Session::forget('api_token');
            Session::forget('user');
        }

        return redirect()->route('home')->with('success', 'Вы вышли из системы');
    }

    /**
     * Парсинг ошибок от API
     */
    private function parseApiErrors(array $response): array
    {
        $errors = [];

        // Сообщение об ошибке
        $message = $response['message'] ?? 'Произошла ошибка. Попробуйте еще раз.';

        // Ошибки валидации от API
        if (isset($response['errors']) && is_array($response['errors'])) {
            foreach ($response['errors'] as $field => $fieldErrors) {
                $errors[$field] = is_array($fieldErrors) ? $fieldErrors[0] : $fieldErrors;
            }
        }

        // Если нет специфичных ошибок, добавляем общее сообщение
        if (empty($errors)) {
            // Определяем поле для ошибки
            $field = $this->getErrorField($response);
            $errors[$field] = $message;
        }

        return $errors;
    }

    /**
     * Определение поля для общей ошибки
     */
    private function getErrorField(array $response): string
    {
        $message = strtolower($response['message'] ?? '');

        if (str_contains($message, 'email')) {
            return 'email';
        }

        if (str_contains($message, 'password')) {
            return 'password';
        }

        if (str_contains($message, 'name') || str_contains($message, 'имя')) {
            return 'name';
        }

        return 'email';
    }
}
