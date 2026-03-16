<?php
// database/migrations/2024_01_01_000001_create_hotels_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();

            // Основная информация
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('short_description', 500)->nullable();

            // Адрес и локация
            $table->string('address');
            $table->string('city');
            $table->string('country');
            $table->string('postal_code', 20)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Контактная информация
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('check_in_time', 5)->default('14:00'); // Время заезда
            $table->string('check_out_time', 5)->default('12:00'); // Время выезда

            // Звезды и рейтинг (будет обновляться из отзывов)
            $table->unsignedTinyInteger('stars')->default(3); // 1-5 звезд отеля
            $table->decimal('rating', 3, 2)->default(0); // Средний рейтинг из отзывов
            $table->integer('review_count')->default(0); // Количество отзывов

            // Детальные рейтинги (будут обновляться из отзывов)
            $table->decimal('rating_cleanliness', 3, 2)->default(0);
            $table->decimal('rating_comfort', 3, 2)->default(0);
            $table->decimal('rating_location', 3, 2)->default(0);
            $table->decimal('rating_service', 3, 2)->default(0);
            $table->decimal('rating_value', 3, 2)->default(0);

            // Изображения
            $table->string('main_image')->nullable();
            $table->json('gallery')->nullable();

            // Минимальная и максимальная цена (будут обновляться из номеров)
            $table->decimal('min_price', 10, 2)->nullable();
            $table->decimal('max_price', 10, 2)->nullable();

            // Статус и настройки
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_approved')->default(true); // Прошел модерацию

            // Политики отеля
            $table->boolean('allows_pets')->default(false);
            $table->boolean('allows_children')->default(true);
            $table->boolean('allows_smoking')->default(false);
            $table->boolean('has_wheelchair_access')->default(false);

            // Языки обслуживания
            $table->json('languages')->nullable(); // ['ru', 'en', 'de']

            // Дополнительная информация
            $table->json('nearby_places')->nullable(); // Достопримечательности рядом
            $table->json('house_rules')->nullable(); // Правила отеля

            $table->timestamps();
            $table->softDeletes();

            // Индексы для поиска
            $table->index(['city', 'country']);
            $table->index('stars');
            $table->index('rating');
            $table->index(['is_active', 'is_approved']);
            $table->index(['min_price', 'max_price']);

            // Полнотекстовый поиск
            $table->fullText(['name', 'description', 'city', 'country']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
