<?php

namespace App\Services\Room;

use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateRoomService
{
    public function execute(array $data): Room
    {
        return DB::transaction(function () use ($data) {
            // Обработка основного изображения
            if (isset($data['main_image'])) {
                $path = $data['main_image']->store('rooms', 'public');
                $data['main_image'] = $path;
            }

            // Обработка галереи
            if (isset($data['gallery']) && is_array($data['gallery'])) {
                $gallery = [];
                foreach ($data['gallery'] as $image) {
                    $gallery[] = $image->store('rooms/gallery', 'public');
                }
                $data['gallery'] = $gallery;
            }

            // Расчет общей вместимости
            $data['total_capacity'] = ($data['capacity_adults'] ?? 0) + ($data['capacity_children'] ?? 0);

            // Количество доступных номеров
            $data['available_quantity'] = $data['quantity'] ?? 1;

            // Создание номера
            $room = Room::create($data);

            // Привязка удобств
            if (isset($data['amenities'])) {
                $room->amenities()->sync($data['amenities']);
            }

            return $room->load('amenities');
        });
    }
}
