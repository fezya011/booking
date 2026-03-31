<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\ChangePasswordRequest;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Auth\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Auth\RegisterService;
use App\Services\Auth\LoginService;
use App\Services\Auth\TokenService;
use App\Services\Auth\ChangePasswordService;
use App\Services\Auth\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(
        private TokenService $tokenService
    ) {}

    public function register( RegisterRequest $request, RegisterService $action): JsonResponse
    {
        $user = $action->execute($request->validated());
        $token = $this->tokenService->createToken($user);

        return response()->json([
            'success' => true,
            'message' => 'Пользователь успешно зарегистрирован',
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    public function login(LoginRequest $request, LoginService $action): JsonResponse
    {
        $user = $action->execute(
            $request->email,
            $request->password,
            $request->ip()
        );

        $token = $this->tokenService->createToken($user);

        return response()->json([
            'success' => true,
            'message' => 'Успешный вход в систему',
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    public function logout(): JsonResponse
    {
        /** @var User $user */ // Подсказочка для IDE PHPDoc
        $user = auth()->user();

        $this->tokenService->revokeCurrentToken($user);

        return response()->json([
            'success' => true,
            'message' => 'Успешный выход из системы'
        ]);
    }

    public function user(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'data' => new UserResource($user)
        ]);
    }

    public function refresh(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $this->tokenService->revokeCurrentToken($user);
        $token = $this->tokenService->createToken($user);

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    public function changePassword(ChangePasswordRequest $request, ChangePasswordService $action): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $action->execute(
            $user,
            $request->current_password,
            $request->new_password
        );

        return response()->json([
            'success' => true,
            'message' => 'Пароль успешно изменен'
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Инструкции по сбросу пароля отправлены на ваш email'
        ]);
    }

    /**
     * 🔥 НОВЫЙ МЕТОД: Загрузка аватара пользователя
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars/' . $user->id, 'public');
        $user->update(['avatar' => $path]);

        // 🔥 Полный URL для отладки
        $fullUrl = Storage::disk('public')->url($path);

        Log::info('Avatar uploaded', [
            'path' => $path,
            'full_url' => $fullUrl,
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Аватар загружен',
            'data' => [
                'avatar_url' => $fullUrl,  // ← возвращаем полный URL
                'path' => $path
            ]
        ]);
    }

    /**
     * 🔥 НОВЫЙ МЕТОД: Удаление аватара
     */
    public function deleteAvatar(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Аватар удален',
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Пароль успешно сброшен'
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request, ProfileService $action): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user = $action->updateProfile($user, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Профиль успешно обновлен',
            'data' => new UserResource($user)
        ]);
    }

    public function stats(ProfileService $action): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $stats = $action->getUserStats($user);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function logoutAll(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $this->tokenService->revokeAllTokens($user);

        return response()->json([
            'success' => true,
            'message' => 'Вы вышли из всех устройств'
        ]);
    }
}
