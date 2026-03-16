<?php
// database/migrations/2024_01_01_000010_create_amenities_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // Название удобства
            $table->string('slug')->unique();           // Уникальный идентификатор
            $table->string('icon')->nullable();         // Иконка (FontAwesome, Bootstrap Icons)
            $table->string('category');                 // 'hotel' или 'room'
            $table->text('description')->nullable();    // Описание
            $table->integer('sort_order')->default(0);  // Для сортировки
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
