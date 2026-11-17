<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BasecampService
{
    // Koordinat dasar resmi Basecamp Paguyuban Jeep Wukirsari Adventure
    // Titik: Bedoyo / Sidorejo, Wukirsari, Cangkringan, Sleman, D.I. Yogyakarta 55583
    public const DEFAULT_NAME    = 'Basecamp Jeep Wukirsari';
    public const DEFAULT_ADDRESS = 'Jl. Bebeng - Cangkringan, Wukirsari, Kapanewon Cangkringan, Kabupaten Sleman, D.I. Yogyakarta 55583';
    public const DEFAULT_LAT     = -7.6437244;
    public const DEFAULT_LNG     = 110.4338597;
    public const FREE_KM         = 5.0;     // Batas jarak penjemputan gratis (km)
    public const FEE_PER_JEEP    = 50000;   // Biaya tambahan per unit jeep jika jarak > FREE_KM

    /**
     * Path file penyimpanan konfigurasi dinamis basecamp
     */
    public static function settingsFilePath(): string
    {
        return storage_path('app/basecamp_settings.json');
    }

    /**
     * Ambil seluruh konfigurasi operasional basecamp
     */
    public static function getSettings(): array
    {
        $default = [
            'name'                 => self::DEFAULT_NAME,
            'basecamp_address'     => self::DEFAULT_ADDRESS,
            'basecamp_lat'         => self::DEFAULT_LAT,
            'basecamp_lng'         => self::DEFAULT_LNG,
            'cs_phone'             => '+62 812-3456-7890',
            'operational_hours'    => '06:00 - 18:00 WIB',
            'dp_percentage'        => 30,
            'max_pax_per_jeep'     => 4,
            'checkin_lead_minutes' => 30,
            'google_maps_url'      => 'https://www.google.com/maps?q=' . self::DEFAULT_LAT . ',' . self::DEFAULT_LNG,
            'hero_badge'           => 'Offroad Lereng Merapi',
            'hero_headline'        => 'Jelajahi Keindahan Alam Merapi Bersama Jeep Wukirsari',
            'hero_subtitle'        => 'Rasakan petualangan seru menyusuri jalur lava, menikmati panorama Merapi, dan menciptakan cerita perjalanan yang tak terlupakan.',
        ];

        // Override dari file JSON jika ada
        $file = self::settingsFilePath();
        if (file_exists($file)) {
            try {
                $saved = json_decode(file_get_contents($file), true);
                if (is_array($saved)) {
                    $default = array_merge($default, $saved);
                }
            } catch (\Throwable $e) {
                Log::warning('BasecampService: Gagal membaca settings file: ' . $e->getMessage());
            }
        }

        // Override dari environment jika diset eksplisit oleh DevOps
        if (env('BASECAMP_LAT')) {
            $default['basecamp_lat'] = (float) env('BASECAMP_LAT');
        }
        if (env('BASECAMP_LNG')) {
            $default['basecamp_lng'] = (float) env('BASECAMP_LNG');
        }
        if (env('BASECAMP_NAME')) {
            $default['name'] = (string) env('BASECAMP_NAME');
        }

        // Normalisasi format angka
        $default['basecamp_lat'] = (float) $default['basecamp_lat'];
        $default['basecamp_lng'] = (float) $default['basecamp_lng'];

        return $default;
    }

    /**
     * Simpan konfigurasi basecamp dari panel admin
     */
    public static function saveSettings(array $data): bool
    {
        try {
            $current = self::getSettings();
            $merged = array_merge($current, $data);

            // Selalu perbarui URL Google Maps jika koordinat berubah dan URL lama kosong/default
            if (isset($data['basecamp_lat'], $data['basecamp_lng'])) {
                $merged['basecamp_lat'] = (float) $data['basecamp_lat'];
                $merged['basecamp_lng'] = (float) $data['basecamp_lng'];
                if (empty($merged['google_maps_url']) || str_contains($merged['google_maps_url'], 'maps.google.com/?q=')) {
                    $merged['google_maps_url'] = 'https://www.google.com/maps?q=' . $merged['basecamp_lat'] . ',' . $merged['basecamp_lng'];
                }
            }

            $dir = dirname(self::settingsFilePath());
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            file_put_contents(self::settingsFilePath(), json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return true;
        } catch (\Throwable $e) {
            Log::error('BasecampService: Gagal menyimpan settings: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Dapatkan representasi data Basecamp ringkas untuk perhitungan dan peta
     */
    public static function getBasecamp(): array
    {
        $settings = self::getSettings();
        return [
            'name'            => $settings['name'] ?? self::DEFAULT_NAME,
            'address'         => $settings['basecamp_address'] ?? self::DEFAULT_ADDRESS,
            'lat'             => (float) ($settings['basecamp_lat'] ?? self::DEFAULT_LAT),
            'lng'             => (float) ($settings['basecamp_lng'] ?? self::DEFAULT_LNG),
            'google_maps_url' => $settings['google_maps_url'] ?? ('https://www.google.com/maps?q=' . self::DEFAULT_LAT . ',' . self::DEFAULT_LNG),
        ];
    }

    public static function lat(): float
    {
        return self::getBasecamp()['lat'];
    }

    public static function lng(): float
    {
        return self::getBasecamp()['lng'];
    }

    public static function name(): string
    {
        return self::getBasecamp()['name'];
    }

    public static function address(): string
    {
        return self::getBasecamp()['address'];
    }
}

