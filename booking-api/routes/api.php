<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    HotelController,
    RoomController,
    BookingController,
    AmenityController,
    ReviewController
};

// Публичные маршруты
Route::prefix('v1')->group(function () {
    Route::get('/ping', function() {
        return response()->json([
            'success' => true,
            'message' => 'API is working!',
            'timestamp' => now()
        ]);
    });

    // Аутентификация
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Отели (публичное)
    Route::get('/hotels', [HotelController::class, 'index']);
    Route::get('/hotels/{hotel}', [HotelController::class, 'show']);
    Route::get('/hotels/{hotel}/rooms', [RoomController::class, 'index']);

    // Маршрут проверки доступности
    Route::get('/rooms/{room}/check-availability', [RoomController::class, 'checkAvailability']);

    // Удобства (публичное)
    Route::get('/amenities', [AmenityController::class, 'index']);

    // Отзывы (публичное)
    Route::get('/hotels/{hotel}/reviews', [ReviewController::class, 'index']);

    // Восстановление пароля (публичные)
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

    // Защищенные маршруты (требуют авторизации)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/user', [AuthController::class, 'user']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/stats', [AuthController::class, 'stats']);
        Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
        Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);
        Route::post('/auth/logout-all', [AuthController::class, 'logoutAll']);
        Route::post('/auth/avatar', [AuthController::class, 'uploadAvatar']);
        Route::delete('/auth/avatar', [AuthController::class, 'deleteAvatar']);


        // Бронирования
        Route::apiResource('bookings', BookingController::class)->except(['create', 'edit']);

        // Отзывы (создание/редактирование)
        Route::post('/hotels/{hotel}/reviews', [ReviewController::class, 'store']);
        Route::put('/reviews/{review}', [ReviewController::class, 'update']);
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

        // Админские маршруты
        Route::middleware('admin')->group(function () {
            Route::apiResource('hotels', HotelController::class)->except(['index', 'show']);
            Route::apiResource('rooms', RoomController::class)->except(['index', 'show']);
            Route::apiResource('amenities', AmenityController::class)->except(['index']);
        });
    });
});
