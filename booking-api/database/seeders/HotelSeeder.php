<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Amenity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        // Вместо TRUNCATE используем DELETE
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Hotel::query()->delete();
        Room::query()->delete();
        DB::table('hotel_amenities')->delete();
        DB::table('room_amenities')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Создаем отели
        $hotels = [
            [
                'name' => 'The Ritz-Carlton, Moscow',
                'description' => 'Роскошный отель в самом сердце Москвы, рядом с Красной площадью. Предлагает номера с панорамным видом на Кремль, спа-центр и рестораны высокой кухни.',
                'short_description' => '5-звездочный отель в центре Москвы с видом на Кремль',
                'address' => 'Тверская ул., 3',
                'city' => 'Москва',
                'country' => 'Россия',
                'postal_code' => '125009',
                'latitude' => 55.7558,
                'longitude' => 37.6176,
                'phone' => '+7 (495) 225-8888',
                'email' => 'reservations@ritzcarlton.ru',
                'website' => 'https://www.ritzcarlton.ru',
                'stars' => 5,
                'check_in_time' => '15:00',
                'check_out_time' => '12:00',
                'allows_pets' => false,
                'allows_children' => true,
                'has_wheelchair_access' => true,
                'is_active' => true,
                'is_featured' => true,
                'languages' => json_encode(['ru', 'en', 'de', 'fr']),
                'house_rules' => json_encode(['Без животных', 'Без курения', 'Тишина после 23:00']),
            ],
            [
                'name' => 'Four Seasons Hotel Moscow',
                'description' => 'Элегантный отель с историей, расположенный прямо у стен Кремля. Сочетает классический стиль и современный комфорт.',
                'short_description' => 'Исторический отель с видом на Красную площадь',
                'address' => 'Охотный Ряд, 2',
                'city' => 'Москва',
                'country' => 'Россия',
                'postal_code' => '125009',
                'latitude' => 55.7569,
                'longitude' => 37.6164,
                'phone' => '+7 (495) 660-7300',
                'email' => 'moscow@fourseasons.com',
                'website' => 'https://www.fourseasons.com/moscow',
                'stars' => 5,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'allows_pets' => true,
                'allows_children' => true,
                'has_wheelchair_access' => true,
                'is_active' => true,
                'is_featured' => true,
                'languages' => json_encode(['ru', 'en', 'fr', 'it']),
                'house_rules' => json_encode(['Домашние животные разрешены', 'VIP обслуживание']),
            ],
            [
                'name' => 'Ararat Park Hyatt Moscow',
                'description' => 'Отель в стиле ар-деко в центре Москвы. Известен своей крышей с панорамным видом и спа-центром мирового класса.',
                'short_description' => 'Отель в стиле ар-деко с видом на Москву',
                'address' => 'Неглинная ул., 4',
                'city' => 'Москва',
                'country' => 'Россия',
                'postal_code' => '109012',
                'latitude' => 55.7614,
                'longitude' => 37.6183,
                'phone' => '+7 (495) 783-1234',
                'email' => 'moscow.park@hyatt.com',
                'website' => 'https://www.hyatt.com',
                'stars' => 5,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'allows_pets' => false,
                'allows_children' => true,
                'has_wheelchair_access' => true,
                'is_active' => true,
                'is_featured' => false,
                'languages' => json_encode(['ru', 'en']),
                'house_rules' => json_encode(['Без домашних животных', 'Бизнес-класс обслуживание']),
            ],
            [
                'name' => 'St. Petersburg Hotel',
                'description' => 'Отель с видом на Неву и Петропавловскую крепость. Идеальное место для знакомства с культурной столицей.',
                'short_description' => 'Отель в центре Санкт-Петербурга с видом на Неву',
                'address' => 'Невский пр., 90',
                'city' => 'Санкт-Петербург',
                'country' => 'Россия',
                'postal_code' => '191025',
                'latitude' => 59.9343,
                'longitude' => 30.3351,
                'phone' => '+7 (812) 123-4567',
                'email' => 'info@spbhotel.ru',
                'website' => 'https://www.spbhotel.ru',
                'stars' => 4,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'allows_pets' => true,
                'allows_children' => true,
                'has_wheelchair_access' => false,
                'is_active' => true,
                'is_featured' => false,
                'languages' => json_encode(['ru', 'en', 'de']),
                'house_rules' => json_encode(['Домашние животные разрешены', 'Парковка платная']),
            ],
            [
                'name' => 'Sochi Marriott Krasnaya Polyana',
                'description' => 'Горнолыжный курорт в Красной Поляне. Современные номера, спа-центр и прямой доступ к подъемникам.',
                'short_description' => 'Горнолыжный курорт в Красной Поляне',
                'address' => 'ул. Защитников Кавказа, 85',
                'city' => 'Сочи',
                'country' => 'Россия',
                'postal_code' => '354392',
                'latitude' => 43.6789,
                'longitude' => 40.2056,
                'phone' => '+7 (862) 243-2000',
                'email' => 'reservations@marriott.com',
                'website' => 'https://www.marriott.com',
                'stars' => 5,
                'check_in_time' => '15:00',
                'check_out_time' => '12:00',
                'allows_pets' => false,
                'allows_children' => true,
                'has_wheelchair_access' => true,
                'is_active' => true,
                'is_featured' => true,
                'languages' => json_encode(['ru', 'en']),
                'house_rules' => json_encode(['Горнолыжное снаряжение можно арендовать']),
            ],
            [
                'name' => 'Kazan Palace by Tasigo',
                'description' => 'Роскошный отель в историческом центре Казани. Сочетает восточную архитектуру и европейский сервис.',
                'short_description' => 'Отель в центре Казани с восточным колоритом',
                'address' => 'ул. Баумана, 68',
                'city' => 'Казань',
                'country' => 'Россия',
                'postal_code' => '420111',
                'latitude' => 55.7887,
                'longitude' => 49.1221,
                'phone' => '+7 (843) 123-4567',
                'email' => 'info@kazanpalace.ru',
                'website' => 'https://www.kazanpalace.ru',
                'stars' => 5,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'allows_pets' => false,
                'allows_children' => true,
                'has_wheelchair_access' => true,
                'is_active' => true,
                'is_featured' => false,
                'languages' => json_encode(['ru', 'en', 'tt']),
                'house_rules' => json_encode(['Традиционная татарская кухня в ресторане']),
            ],
            [
                'name' => 'Cosmos Sochi Hotel',
                'description' => 'Отель на берегу Черного моря. Отличный выбор для семейного отдыха с бассейнами и анимацией.',
                'short_description' => 'Семейный отель на берегу моря',
                'address' => 'Курортный пр., 105',
                'city' => 'Сочи',
                'country' => 'Россия',
                'postal_code' => '354002',
                'latitude' => 43.5678,
                'longitude' => 39.7456,
                'phone' => '+7 (862) 234-5678',
                'email' => 'sochi@cosmos.ru',
                'website' => 'https://www.cosmos.ru',
                'stars' => 4,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'allows_pets' => true,
                'allows_children' => true,
                'has_wheelchair_access' => true,
                'is_active' => true,
                'is_featured' => false,
                'languages' => json_encode(['ru', 'en']),
                'house_rules' => json_encode(['Детская анимация', 'Пляж в 50 метрах']),
            ],
        ];

        // Сохраняем отели
        foreach ($hotels as $data) {
            Hotel::create($data);
        }

        // Создаем номера для отелей
        $this->createRooms();

        // Привязываем удобства
        $this->attachAmenities();
    }

    private function createRooms(): void
    {
        $hotels = Hotel::all();

        foreach ($hotels as $hotel) {
            // Стандартные номера
            Room::create([
                'hotel_id' => $hotel->id,
                'name' => 'Стандарт',
                'description' => 'Уютный номер с видом на город',
                'type' => 'standard',
                'capacity_adults' => 2,
                'capacity_children' => 1,
                'total_capacity' => 3,
                'size_sqm' => 25,
                'bed_type' => 'double',
                'bed_count' => 1,
                'price_per_night' => rand(5000, 15000),
                'quantity' => 10,
                'available_quantity' => 10,
                'is_active' => true,
                'is_available' => true,
            ]);

            // Люкс
            Room::create([
                'hotel_id' => $hotel->id,
                'name' => 'Люкс',
                'description' => 'Просторный номер с гостиной зоной',
                'type' => 'suite',
                'capacity_adults' => 4,
                'capacity_children' => 2,
                'total_capacity' => 6,
                'size_sqm' => 55,
                'bed_type' => 'king',
                'bed_count' => 2,
                'price_per_night' => rand(15000, 35000),
                'quantity' => 5,
                'available_quantity' => 5,
                'is_active' => true,
                'is_available' => true,
            ]);

            // Семейный номер
            Room::create([
                'hotel_id' => $hotel->id,
                'name' => 'Семейный',
                'description' => 'Идеально для отдыха с детьми',
                'type' => 'family',
                'capacity_adults' => 2,
                'capacity_children' => 3,
                'total_capacity' => 5,
                'size_sqm' => 40,
                'bed_type' => 'twin',
                'bed_count' => 2,
                'price_per_night' => rand(8000, 20000),
                'quantity' => 8,
                'available_quantity' => 8,
                'is_active' => true,
                'is_available' => true,
            ]);
        }
    }

    private function attachAmenities(): void
    {
        $amenities = Amenity::all();

        if ($amenities->isEmpty()) {
            return;
        }

        $hotels = Hotel::all();

        foreach ($hotels as $hotel) {
            $randomAmenities = $amenities->random(rand(5, 8));
            $hotel->amenities()->sync($randomAmenities->pluck('id'));
        }
    }
}
