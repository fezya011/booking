<?php
// database/migrations/2024_01_01_000003_create_bookings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique(); // Уникальный номер бронирования

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');

            // Даты бронирования
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('nights'); // Количество ночей

            // Информация о гостях
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            $table->json('guest_details')->nullable(); // Имена гостей и т.д.

            // Цены
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('subtotal', 10, 2); // Цена за все ночи
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2); // Итоговая цена

            // Статус бронирования
            $table->enum('status', [
                'pending',      // Ожидает подтверждения
                'confirmed',    // Подтверждено
                'cancelled',    // Отменено
                'completed',    // Завершено (гости уехали)
                'no_show',      // Гость не приехал
                'refunded'      // Возврат средств
            ])->default('pending');

            // Оплата
            $table->enum('payment_status', [
                'pending',      // Ожидает оплаты
                'paid',         // Оплачено
                'partially_paid', // Частично оплачено
                'refunded',     // Возвращено
                'failed'        // Ошибка оплаты
            ])->default('pending');

            $table->string('payment_method')->nullable(); // cash, card, bank_transfer
            $table->string('payment_id')->nullable(); // ID транзакции
            $table->json('payment_details')->nullable(); // Детали платежа

            // Дополнительные услуги
            $table->json('extras')->nullable(); // Доп. услуги (завтрак, трансфер)
            $table->decimal('extras_total', 10, 2)->default(0);

            // Пожелания и заметки
            $table->text('special_requests')->nullable();
            $table->text('admin_notes')->nullable(); // Заметки администратора

            // Время заезда/выезда
            $table->time('estimated_arrival_time')->nullable();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();

            // Отмена
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->decimal('cancellation_fee', 10, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Индексы
            $table->index(['user_id', 'status']);
            $table->index(['hotel_id', 'check_in', 'check_out']);
            $table->index('booking_number');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
