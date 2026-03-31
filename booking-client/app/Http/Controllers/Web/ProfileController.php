<?php

namespace App\Http\Controllers\Web;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    private ApiService $api;

    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }

    /**
     * Показать профиль
     */
    public function show()
    {
        $user = Session::get('user');

        if (!$user) {
            return redirect()->route('login')->with('error', 'Войдите в систему');
        }

        return view('profile.show', compact('user'));
    }

    /**
     * Форма редактирования профиля
     */
    public function edit()
    {
        $user = Session::get('user');

        if (!$user) {
            return redirect()->route('login')->with('error', 'Войдите в систему');
        }

        return view('profile.edit', compact('user'));
    }

    /**
     * Обновление профиля (без аватара)
     */
    public function update(Request $request)
    {
        $user = Session::get('user');

        if (!$user) {
            return redirect()->route('login')->with('error', 'Войдите в систему');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif',
        ]);

        // 🔥 ЗАГРУЖАЕМ АВАТАР
        if ($request->hasFile('avatar')) {
            $avatarResponse = $this->api->upload('auth/avatar', $request->file('avatar'));

            // Сохраняем полный URL аватара в сессию
            if (isset($avatarResponse['success']) && $avatarResponse['success']) {
                $user['avatar'] = $avatarResponse['data']['avatar_url'];  // ← полный URL
                Session::put('user', $user);

                Log::info('Avatar saved to session', ['avatar_url' => $user['avatar']]);
            } else {
                return back()->withErrors(['avatar' => $avatarResponse['message'] ?? 'Ошибка загрузки фото']);
            }
        }

        // Обновляем остальные данные профиля
        $response = $this->api->put('auth/profile', [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        if (isset($response['success']) && $response['success']) {
            // Обновляем сессию
            $user['name'] = $request->name;
            $user['email'] = $request->email;
            $user['phone'] = $request->phone;
            Session::put('user', $user);

            return redirect()->route('profile.show')
                ->with('success', 'Профиль обновлен');
        }

        return back()->withErrors(['error' => $response['message'] ?? 'Ошибка обновления']);
    }
    /**
     * Смена пароля
     */
    public function updatePassword(Request $request)
    {
        $user = Session::get('user');

        if (!$user) {
            return redirect()->route('login')->with('error', 'Войдите в систему');
        }

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $response = $this->api->post('auth/change-password', [
            'current_password' => $request->current_password,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if (isset($response['success']) && $response['success']) {
            return back()->with('success', 'Пароль изменен');
        }

        return back()->withErrors(['current_password' => $response['message'] ?? 'Ошибка смены пароля']);
    }
}
