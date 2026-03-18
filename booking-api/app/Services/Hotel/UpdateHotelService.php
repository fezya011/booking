<?php

namespace App\Services\Hotel;

use App\Models\Hotel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateHotelService
{
    public function execute(Hotel $hotel, array $data): Hotel
    {
        return DB::transaction(function () use ($hotel, $data) {
            // Обработка нового изображения
            if (isset($data['main_image'])) {
                // Удаляем старое
                if ($hotel->main_image) {
                    Storage::disk('public')->delete($hotel->main_image);
                }
                // Сохраняем новое
                $path = $data['main_image']->store('hotels', 'public');
                $data['main_image'] = $path;
            }

            // Обновление отеля
            $hotel->update($data);

            // Обновление удобств
            if (isset($data['amenities'])) {
                $hotel->amenities()->sync($data['amenities']);
            }

            return $hotel->fresh();
        });
    }
}
