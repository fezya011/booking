<?php

namespace App\Services\Amenity;

use App\Models\Amenity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BulkCreateAmenitiesService
{
    public function execute(array $items): array
    {
        $created = [];
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($items as $item) {
                try {
                    // Генерация slug, если не указан
                    if (!isset($item['slug']) && isset($item['name'])) {
                        $item['slug'] = Str::slug($item['name']);
                    }

                    // Проверка уникальности slug
                    if (Amenity::where('slug', $item['slug'])->exists()) {
                        throw new \Exception("Slug '{$item['slug']}' уже существует");
                    }

                    // Установка значений по умолчанию
                    $item['is_active'] = $item['is_active'] ?? true;
                    $item['sort_order'] = $item['sort_order'] ?? 0;

                    $created[] = Amenity::create($item);
                } catch (\Exception $e) {
                    $errors[] = [
                        'item' => $item,
                        'error' => $e->getMessage()
                    ];
                }
            }

            if (empty($errors)) {
                DB::commit();
            } else {
                DB::rollBack();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'created' => $created,
            'errors' => $errors
        ];
    }
}
