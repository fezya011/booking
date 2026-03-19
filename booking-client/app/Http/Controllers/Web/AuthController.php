<?php
// app/Http/Controllers/Web/AuthController.php

namespace App\Http\Controllers\Web;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
            Session::put('api_token', $response['data']['token']);
            Session::put('user', $response['data']['user']);

            return redirect()->intended(route('home'))->with('success', 'Добро пожаловать!');
        }

        return back()->withErrors([
            'email' => $response['message'] ?? 'Неверные учетные данные',
        ])->withInput();
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
            Session::put('api_token', $response['data']['token']);
            Session::put('user', $response['data']['user']);

            return redirect()->route('home')->with('success', 'Регистрация успешна! Добро пожаловать!');
        }

        return back()->withErrors([
            'email' => $response['message'] ?? 'Ошибка регистрации',
        ])->withInput();
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
}
