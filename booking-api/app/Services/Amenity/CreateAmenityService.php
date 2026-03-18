<?php

namespace App\Services\Amenity;

use App\Models\Amenity;
use Illuminate\Support\Str;

class CreateAmenityService
{
    public function execute(array $data): Amenity
    {
        // Генерация slug, если не указан
        if (!isset($data['slug']) && isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Установка значений по умолчанию
        $data['is_active'] = $data['is_active'] ?? true;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return Amenity::create($data);
    }
}
