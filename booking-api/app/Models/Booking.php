<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use SoftDeletes;

    protected $table = 'bookings';

    protected $fillable = [
        'booking_number',
        'user_id',
        'hotel_id',
        'room_id',
        'check_in',
        'check_out',
        'nights',
        'adults',
        'children',
        'infants',
        'total_guests',
        'guest_details',
        'price_per_night',
        'subtotal',
        'tax_amount',
        'service_fee',
        'discount_amount',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'payment_id',
        'payment_details',
        'extras',
        'extras_total',
        'special_requests',
        'admin_notes',
        'estimated_arrival_time',
        'check_in_time',
        'check_out_time',
        'cancelled_at',
        'cancellation_reason',
        'cancellation_fee'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'cancelled_at' => 'datetime',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'guest_details' => 'array',
        'payment_details' => 'array',
        'extras' => 'array',
        'nights' => 'integer',
        'adults' => 'integer',
        'children' => 'integer',
        'infants' => 'integer',
        'total_guests' => 'integer',
        'price_per_night' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'extras_total' => 'decimal:2',
        'total_price' => 'decimal:2',
        'cancellation_fee' => 'decimal:2'
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
     * Связь с номером
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Получить статус на русском
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Ожидает подтверждения',
            'confirmed' => 'Подтверждено',
            'cancelled' => 'Отменено',
            'completed' => 'Завершено',
            'no_show' => 'Гость не приехал',
            'refunded' => 'Возврат средств',
            default => $this->status
        };
    }

    /**
     * Получить цвет статуса для UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'completed' => 'info',
            'no_show' => 'dark',
            'refunded' => 'secondary',
            default => 'light'
        };
    }

    /**
     * Статус оплаты на русском
     */
    public function getPaymentStatusTextAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'Ожидает оплаты',
            'paid' => 'Оплачено',
            'partially_paid' => 'Частично оплачено',
            'refunded' => 'Возвращено',
            'failed' => 'Ошибка оплаты',
            default => $this->payment_status
        };
    }

    /**
     * Проверка возможности отмены
     */
    public function canBeCancelled(): bool
    {
        // Нельзя отменить если уже отменено или завершено
        if (in_array($this->status, ['cancelled', 'completed'])) {
            return false;
        }

        // Нельзя отменить менее чем за 24 часа до заезда
        $checkIn = strtotime($this->check_in);
        $now = time();

        return ($checkIn - $now) > 86400; // 24 часа в секундах
    }

    /**
     * Получить полную стоимость с учетом всех сборов
     */
    public function getGrandTotalAttribute(): float
    {
        return $this->subtotal
            + $this->tax_amount
            + $this->service_fee
            + $this->extras_total
            - $this->discount_amount;
    }

    /**
     * Форматированный номер бронирования
     */
    public function getFormattedBookingNumberAttribute(): string
    {
        return $this->booking_number ?? 'BKG-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
