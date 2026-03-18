<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use SoftDeletes;

    protected $table = 'reviews';

    protected $fillable = [
        'user_id',
        'hotel_id',
        'booking_id',
        'rating_cleanliness',
        'rating_comfort',
        'rating_location',
        'rating_service',
        'rating_value',
        'rating',
        'title',
        'comment',
        'pros',
        'cons',
        'guest_name',
        'guest_email',
        'guest_country',
        'travel_date',
        'travel_type',
        'is_verified',
        'is_approved',
        'is_recommended',
        'hotel_response',
        'responded_at',
        'responded_by',
        'helpful_votes',
        'helpful_users',
        'images'
    ];

    protected $casts = [
        'rating_cleanliness' => 'integer',
        'rating_comfort' => 'integer',
        'rating_location' => 'integer',
        'rating_service' => 'integer',
        'rating_value' => 'integer',
        'rating' => 'decimal:2',
        'travel_date' => 'date',
        'responded_at' => 'datetime',
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
        'is_recommended' => 'boolean',
        'helpful_users' => 'array',
        'images' => 'array'
    ];

    /**
     * Связь с пользователем
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с отелем
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Связь с бронированием
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Кто ответил на отзыв
     */
    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * Получить текст типа путешествия
     */
    public function getTravelTypeTextAttribute(): string
    {
        return match($this->travel_type) {
            'alone' => 'Один',
            'couple' => 'Пара',
            'family' => 'Семья',
            'friends' => 'С друзьями',
            'business' => 'Бизнес',
            default => 'Не указано'
        };
    }

    /**
     * Проверка, есть ли ответ отеля
     */
    public function getHasResponseAttribute(): bool
    {
        return !is_null($this->hotel_response);
    }

    /**
     * Получить инициалы пользователя для аватарки
     */
    public function getUserInitialsAttribute(): string
    {
        $name = $this->user->name ?? $this->guest_name ?? 'Аноним';
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(mb_substr($word, 0, 1));
            }
        }
        return $initials;
    }
}
