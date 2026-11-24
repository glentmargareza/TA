<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_per_jeep',
        'duration_minutes',
        'duration_text',
        'destinations_count',
        'routes_list',
        'gallery_images',
        'category_badge',
        'cover_image',
        'is_active',
    ];

    protected $casts = [
        'price_per_jeep' => 'float',
        'duration_minutes' => 'integer',
        'destinations_count' => 'integer',
        'routes_list' => 'array',
        'gallery_images' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::saved(function () {
            \App\Services\SlotAvailabilityService::clearCache();
        });

        static::deleted(function () {
            \App\Services\SlotAvailabilityService::clearCache();
        });
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price_per_jeep, 0, ',', '.');
    }

    public function getEffectiveDurationTextAttribute(): string
    {
        if (!empty($this->duration_text)) {
            return $this->duration_text;
        }

        $defaultMap = [
            'short-trip' => '1.5 - 2 jam',
            'medium-trip-a' => '2 - 2.5 jam',
            'medium-trip-b' => '2 - 2.5 jam',
            'long-trip' => '2 - 3.5 jam',
            'sunrise-trip' => 'start 04.00 WIB',
        ];

        $slug = $this->attributes['slug'] ?? '';
        if (isset($defaultMap[$slug])) {
            return $defaultMap[$slug];
        }

        $hours = round($this->duration_minutes / 60, 1);
        return $hours . ' Jam';
    }

    public function getRoutesArrayAttribute()
    {
        if (is_array($this->routes_list) && count($this->routes_list) > 0) {
            return $this->routes_list;
        }
        return [];
    }

    public static function getDefaultCoverForSlug(?string $slug): string
    {
        $map = [
            'short-trip' => 'img/short_trip.jpeg',
            'medium-trip-a' => 'img/medium_trip_a.jpeg',
            'medium-trip-b' => 'img/medium_trip_b.jpeg',
            'long-trip' => 'img/long_trip.jpeg',
            'sunrise-trip' => 'img/sunrise_trip.jpeg',
        ];

        return $map[$slug] ?? 'img/beranda.jpeg';
    }

    public static function getDefaultGalleryForSlug(?string $slug): array
    {
        $defaults = [
            'short-trip' => ['img/7.jpeg', 'img/12.jpeg', 'img/13.jpeg', 'img/5.jpeg'],
            'medium-trip-a' => ['img/1.jpeg', 'img/6.jpeg', 'img/11.jpeg', 'img/10.jpeg'],
            'medium-trip-b' => ['img/3.jpeg', 'img/4.jpeg', 'img/6.jpeg', 'img/15.jpeg'],
            'long-trip' => ['img/12.jpeg', 'img/8.jpeg', 'img/4.jpeg', 'img/manuver_air.png'],
            'sunrise-trip' => ['img/16.jpeg', 'img/5.jpeg', 'img/13.jpeg', 'img/3.jpeg'],
        ];

        return $defaults[$slug] ?? ['img/7.jpeg', 'img/12.jpeg', 'img/13.jpeg', 'img/5.jpeg'];
    }

    public function getEffectiveGalleryAttribute()
    {
        $list = [];
        if (is_array($this->gallery_images) && count($this->gallery_images) > 0) {
            $list = $this->gallery_images;
        } else {
            $list = self::getDefaultGalleryForSlug($this->attributes['slug'] ?? '');
        }

        return array_map(function ($item) {
            if (empty($item)) return '';
            if (str_starts_with($item, 'http://') || str_starts_with($item, 'https://')) {
                return $item;
            }
            $cleaned = preg_replace('#^/?(public/)?#', '', $item);
            return asset($cleaned);
        }, $list);
    }

    public function getGalleryArrayAttribute()
    {
        return $this->effective_gallery;
    }

    /**
     * @param string|null $value
     * @return string
     */
    public function getCoverImageAttribute(?string $value = null): string
    {
        if (!empty($value)) {
            if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                return $value;
            }
            $cleanPath = ltrim(preg_replace('#^/?(public/)?#', '', $value), '/');
            return asset($cleanPath);
        }

        $slug = $this->attributes['slug'] ?? '';
        $defaultRel = self::getDefaultCoverForSlug($slug);
        if (file_exists(public_path($defaultRel))) {
            return asset($defaultRel);
        }

        return asset('img/beranda.jpeg');
    }

    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
