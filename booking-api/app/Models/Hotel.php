<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hotel extends Model
{
    use SoftDeletes;

    protected $table = 'hotels';

    protected $fillable = [
        'name', 'description', 'short_description', 'address', 'city',
        'country', 'postal_code', 'latitude', 'longitude', 'phone',
        'email', 'website', 'stars', 'rating', 'review_count',
        'rating_cleanliness', 'rating_comfort', 'rating_location',
        'rating_service', 'rating_value', 'main_image', 'gallery',
        'check_in_time', 'check_out_time', 'min_price', 'max_price',
        'is_active', 'is_featured', 'is_approved', 'allows_pets',
        'allows_children', 'allows_smoking', 'has_wheelchair_access',
        'languages', 'nearby_places', 'house_rules'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating' => 'decimal:2',
        'gallery' => 'array',
        'languages' => 'array',
        'nearby_places' => 'array',
        'house_rules' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_approved' => 'boolean',
        'allows_pets' => 'boolean',
        'allows_children' => 'boolean',
        'allows_smoking' => 'boolean',
        'has_wheelchair_access' => 'boolean',
    ];

    /**
     * Связь с номерами
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Связь с бронированиями
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Связь с удобствами (many-to-many)
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'hotel_amenities');
    }

    /**
     * Связь с отзывами
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
