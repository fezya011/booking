<?php

namespace App\Providers;

use App\Models\Hotel;
use App\Models\Amenity;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Администратор (полный доступ)
        Gate::define('admin', fn(User $user) => $user->role === 'admin');

        // Владелец отеля (если добавлять такую роль)
        Gate::define('hotel-owner', fn(User $user) => $user->role === 'hotel_owner' || $user->role === 'admin');

        // Управление отелями
        Gate::define('create-hotel', fn(User $user) => $user->role === 'admin');
        Gate::define('update-hotel', function (User $user, Hotel $hotel) {return $user->role === 'admin';});
        Gate::define('delete-hotel', fn(User $user, Hotel $hotel) => $user->role === 'admin');

        // Управление номерами
        Gate::define('create-room', fn(User $user) => $user->role === 'admin');
        Gate::define('update-room', function (User $user, Room $room) { return $user->role === 'admin'; });
        Gate::define('delete-room', fn(User $user, Room $room) => $user->role === 'admin');

        // Управление бронированиями
        Gate::define('view-bookings', fn(User $user) => true);
        Gate::define('create-booking', fn(User $user) => true);
        Gate::define('view-booking', function (User $user, Booking $booking) { return $user->id === $booking->user_id || $user->role === 'admin'; });
        Gate::define('update-booking', function (User $user, Booking $booking) { return $user->role === 'admin' || $user->id === $booking->user_id; });
        Gate::define('cancel-booking', function (User $user, Booking $booking) { return $user->role === 'admin' || $user->id === $booking->user_id; });

        //Управление отзывами
        Gate::define('create-review', function (User $user, Booking $booking) { return true; });
        Gate::define('update-review', function (User $user, Review $review) { return $user->id === $review->user_id || $user->role === 'admin'; });

        Gate::define('delete-review', function (User $user, Review $review) { return $user->id === $review->user_id || $user->role === 'admin'; });

        // Дашборд и статистика (только админ)
        Gate::define('view-dashboard', fn(User $user) => $user->role === 'admin');
        Gate::define('view-statistics', fn(User $user) => $user->role === 'admin');

        // Управление пользователями (только админ)
        Gate::define('view-users', fn(User $user) => $user->role === 'admin');
        Gate::define('update-user', function (User $user, User $targetUser) { return $user->id === $targetUser->id || $user->role === 'admin'; });
        Gate::define('delete-user', fn(User $user, User $targetUser) => $user->role === 'admin');

        // Управление удобствами
        Gate::define('create-amenity', fn(User $user) => $user->role === 'admin');
        Gate::define('update-amenity', fn(User $user, Amenity $amenity) => $user->role === 'admin');
        Gate::define('delete-amenity', fn(User $user, Amenity $amenity) => $user->role === 'admin');
    }
}
