<?php

namespace App\Services\Room;

use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateRoomService
{
    public function execute(Room $room, array $data): Room
    {
        return DB::transaction(function () use ($room, $data) {
            // Обработка нового основного изображения
            if (isset($data['main_image'])) {
                // Удаляем старое
                if ($room->main_image) {
                    Storage::disk('public')->delete($room->main_image);
                }
                $path = $data['main_image']->store('rooms', 'public');
                $data['main_image'] = $path;
            }

            // Обработка новой галереи
            if (isset($data['gallery']) && is_array($data['gallery'])) {
                // Удаляем старую галерею
                if ($room->gallery) {
                    foreach ($room->gallery as $oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
                $gallery = [];
                foreach ($data['gallery'] as $image) {
                    $gallery[] = $image->store('rooms/gallery', 'public');
                }
                $data['gallery'] = $gallery;
            }

            // Пересчет общей вместимости, если изменилось
            if (isset($data['capacity_adults']) || isset($data['capacity_children'])) {
                $data['total_capacity'] = ($data['capacity_adults'] ?? $room->capacity_adults) +
                    ($data['capacity_children'] ?? $room->capacity_children);
            }

            // Обновление номера
            $room->update($data);

            // Обновление удобств
            if (isset($data['amenities'])) {
                $room->amenities()->sync($data['amenities']);
            }

            return $room->fresh('amenities');
        });
    }
}
