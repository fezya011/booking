<?php
// database/migrations/2024_01_01_000011_create_hotel_amenities_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hotel_amenities', function (Blueprint $table) {
            $table->id();

            // Внешний ключ на таблицу hotels
            $table->foreignId('hotel_id')
                ->constrained()           // автоматически ссылается на таблицу hotels
                ->onDelete('cascade');    // при удалении отеля удаляются и его связи

            // Внешний ключ на таблицу amenities
            $table->foreignId('amenity_id')
                ->constrained()           // автоматически ссылается на таблицу amenities
                ->onDelete('cascade');    // при удалении удобства удаляются и его связи

            $table->timestamps();            // created_at и updated_at

            // Уникальность - чтобы не было дубликатов
            $table->unique(['hotel_id', 'amenity_id']);

            // Дополнительно: можно добавить индекс для ускорения поиска
            $table->index('hotel_id');
            $table->index('amenity_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_amenities');
    }
};
