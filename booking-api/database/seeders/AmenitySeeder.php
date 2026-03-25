<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        // Вместо TRUNCATE используем DELETE
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Amenity::query()->delete();
        DB::table('hotel_amenities')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $amenities = [
            // Отельные удобства
            ['name' => 'Бесплатный Wi-Fi', 'slug' => 'free-wifi', 'icon' => 'wifi', 'category' => 'hotel', 'sort_order' => 10, 'is_active' => true],
            ['name' => 'Бассейн', 'slug' => 'pool', 'icon' => 'pool', 'category' => 'hotel', 'sort_order' => 20, 'is_active' => true],
            ['name' => 'Парковка', 'slug' => 'parking', 'icon' => 'parking', 'category' => 'hotel', 'sort_order' => 30, 'is_active' => true],
            ['name' => 'Ресторан', 'slug' => 'restaurant', 'icon' => 'restaurant', 'category' => 'hotel', 'sort_order' => 40, 'is_active' => true],
            ['name' => 'Спа-центр', 'slug' => 'spa', 'icon' => 'spa', 'category' => 'hotel', 'sort_order' => 50, 'is_active' => true],
            ['name' => 'Тренажерный зал', 'slug' => 'gym', 'icon' => 'gym', 'category' => 'hotel', 'sort_order' => 60, 'is_active' => true],
            ['name' => 'Конференц-зал', 'slug' => 'conference', 'icon' => 'conference', 'category' => 'hotel', 'sort_order' => 70, 'is_active' => true],
            ['name' => 'Трансфер', 'slug' => 'transfer', 'icon' => 'car', 'category' => 'hotel', 'sort_order' => 80, 'is_active' => true],

            // Удобства в номере
            ['name' => 'Кондиционер', 'slug' => 'ac', 'icon' => 'ac', 'category' => 'room', 'sort_order' => 10, 'is_active' => true],
            ['name' => 'Телевизор', 'slug' => 'tv', 'icon' => 'tv', 'category' => 'room', 'sort_order' => 20, 'is_active' => true],
            ['name' => 'Мини-бар', 'slug' => 'minibar', 'icon' => 'minibar', 'category' => 'room', 'sort_order' => 30, 'is_active' => true],
            ['name' => 'Сейф', 'slug' => 'safe', 'icon' => 'safe', 'category' => 'room', 'sort_order' => 40, 'is_active' => true],
            ['name' => 'Балкон', 'slug' => 'balcony', 'icon' => 'balcony', 'category' => 'room', 'sort_order' => 50, 'is_active' => true],
            ['name' => 'Вид на море', 'slug' => 'sea-view', 'icon' => 'sea-view', 'category' => 'room', 'sort_order' => 60, 'is_active' => true],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}
