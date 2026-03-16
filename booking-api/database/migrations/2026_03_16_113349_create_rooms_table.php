<?php
// database/migrations/2024_01_01_000002_create_rooms_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->string('room_number')->nullable();
            $table->text('description')->nullable();

            // Тип номера
            $table->enum('type', [
                'standard',
                'superior',
                'deluxe',
                'suite',
                'family',
                'studio',
                'apartment'
            ])->default('standard');

            // Вместимость
            $table->integer('capacity_adults')->default(2);
            $table->integer('capacity_children')->default(0);
            $table->integer('total_capacity')->default(2);

            // Площадь
            $table->integer('size_sqm')->nullable();

            // Кровати
            $table->enum('bed_type', [
                'single',
                'double',
                'queen',
                'king',
                'twin',
                'bunk',
                'sofa_bed'
            ])->default('double');
            $table->integer('bed_count')->default(1);

            // Цены
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('weekend_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->boolean('price_is_per_person')->default(false);

            // Количество таких номеров
            $table->integer('quantity')->default(1);
            $table->integer('available_quantity')->default(1);

            // Изображения
            $table->string('main_image')->nullable();
            $table->json('gallery')->nullable();

            // Статус
            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Индексы
            $table->index(['hotel_id', 'type']);
            $table->index('price_per_night');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
