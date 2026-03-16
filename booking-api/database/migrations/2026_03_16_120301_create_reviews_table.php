<?php
// database/migrations/2024_XX_XX_XXXXXX_create_reviews_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Кто оставил отзыв
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // К какому отелю отзыв
            $table->foreignId('hotel_id')
                ->constrained()
                ->onDelete('cascade');

            // Связь с бронированием (опционально - чтобы только гости могли писать)
            $table->foreignId('booking_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            // Оценки по разным критериям
            $table->unsignedTinyInteger('rating_cleanliness')->default(5); // Чистота
            $table->unsignedTinyInteger('rating_comfort')->default(5);     // Комфорт
            $table->unsignedTinyInteger('rating_location')->default(5);    // Расположение
            $table->unsignedTinyInteger('rating_service')->default(5);     // Обслуживание
            $table->unsignedTinyInteger('rating_value')->default(5);       // Соотношение цена/качество

            // Общий рейтинг (будет вычисляться автоматически)
            $table->decimal('rating', 3, 2)->default(5.00);

            // Текст отзыва
            $table->text('title')->nullable();        // Заголовок отзыва
            $table->text('comment')->nullable();      // Текст отзыва
            $table->text('pros')->nullable();         // Что понравилось
            $table->text('cons')->nullable();         // Что не понравилось

            // Информация о госте (на случай если user_id удален)
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_country')->nullable();

            // Даты поездки
            $table->date('travel_date')->nullable();  // Когда путешествовали
            $table->string('travel_type')->nullable(); // С кем путешествовали (alone, couple, family, friends, business)

            // Подтверждение и модерация
            $table->boolean('is_verified')->default(false); // Подтвержденное бронирование
            $table->boolean('is_approved')->default(true);  // Прошел модерацию
            $table->boolean('is_recommended')->default(true); // Рекомендует ли отель

            // Ответ отеля на отзыв
            $table->text('hotel_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users');

            // Полезность отзыва
            $table->integer('helpful_votes')->default(0);
            $table->json('helpful_users')->nullable(); // Кто отметил как полезный

            // Фото к отзыву
            $table->json('images')->nullable();

            $table->timestamps();
            $table->softDeletes(); // Возможность скрыть отзыв, но не удалять

            // Индексы для быстрого поиска
            $table->index(['hotel_id', 'rating']);
            $table->index(['hotel_id', 'created_at']);
            $table->index('user_id');
            $table->index('is_approved');
            $table->index('is_verified');

            // Уникальность - один пользователь может оставить только один отзыв на отель
            $table->unique(['user_id', 'hotel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
