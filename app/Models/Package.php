<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_per_jeep',
        'duration_minutes',
        'category_badge',
        'cover_image',
        'is_active',
    ];

    protected $casts = [
        'price_per_jeep' => 'float',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
