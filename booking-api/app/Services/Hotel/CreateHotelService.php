<?php

namespace App\Services\Hotel;

use App\Models\Hotel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateHotelService
{
    public function execute(array $data): Hotel
    {
        return DB::transaction(function () use ($data) {
            // Обработка изображения, если есть
            if (isset($data['main_image'])) {
                $path = $data['main_image']->store('hotels', 'public');
                $data['main_image'] = $path;
            }

            // Создание отеля
            $hotel = Hotel::create($data);

            // Привязка удобств, если есть
            if (isset($data['amenities'])) {
                $hotel->amenities()->sync($data['amenities']);
            }

            return $hotel;
        });
    }
}
