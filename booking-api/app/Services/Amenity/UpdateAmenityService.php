<?php

namespace App\Services\Amenity;

use App\Models\Amenity;
use Illuminate\Support\Str;

class UpdateAmenityService
{
    public function execute(Amenity $amenity, array $data): Amenity
    {
        // Генерация slug, если изменилось имя
        if (isset($data['name']) && $data['name'] !== $amenity->name && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $amenity->update($data);

        return $amenity->fresh();
    }
}
