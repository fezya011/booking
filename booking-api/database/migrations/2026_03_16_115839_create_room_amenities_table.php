<?php
// database/migrations/2024_01_01_000012_create_room_amenities_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['room_id', 'amenity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_amenities');
    }
};
