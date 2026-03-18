<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    protected $table = 'amenities';

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'category',
        'description',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Связь с отелями (many-to-many)
     */
    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(Hotel::class, 'hotel_amenities');
    }

    /**
     * Связь с номерами (many-to-many)
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_amenities');
    }

    /**
     * Получить иконку с Font Awesome классом
     */
    public function getIconClassAttribute(): string
    {
        return $this->icon ? 'fa fa-' . $this->icon : 'fa fa-tag';
    }

    /**
     * Название категории на русском
     */
    public function getCategoryNameAttribute(): string
    {
        return match($this->category) {
            'hotel' => 'Отельные удобства',
            'room' => 'Удобства в номере',
            default => $this->category
        };
    }
}
