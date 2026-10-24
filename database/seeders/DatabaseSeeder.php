<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\ScheduleSlot;
use App\Models\PickupZone;
use App\Models\Jeep;
use App\Models\Driver;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Paket Wisata Resmi
        Package::updateOrCreate(
            ['slug' => 'adventure'],
            [
                'name' => 'Adventure',
                'description' => 'Jelajahi jalur lava dan pemandangan menakjubkan Gunung Merapi dengan sensasi petualangan yang seru.',
                'price_per_jeep' => 450000,
                'duration_minutes' => 105,
                'category_badge' => 'Paling Populer',
                'cover_image' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ]
        );

        Package::updateOrCreate(
            ['slug' => 'lavature'],
            [
                'name' => 'Lavature',
                'description' => 'Nikmati keindahan alam Merapi dengan jalur yang lebih menantang dan pemandangan yang memukau.',
                'price_per_jeep' => 550000,
                'duration_minutes' => 135,
                'category_badge' => 'Paket Sedang',
                'cover_image' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ]
        );

        Package::updateOrCreate(
            ['slug' => 'sunrise-tour'],
            [
                'name' => 'Sunrise Tour',
                'description' => 'Saksikan matahari terbit di atas Merapi dengan suasana yang sejuk dan menenangkan.',
                'price_per_jeep' => 700000,
                'duration_minutes' => 210,
                'category_badge' => 'Sunrise Spesial',
                'cover_image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ]
        );

        // 2. Seed Sesi Jam Operasional
        $slots = [
            ['slot_label' => 'Sunrise Tour (04:30 WIB)', 'start_time' => '04:30:00', 'end_time' => '08:00:00', 'max_jeeps' => 15],
            ['slot_label' => 'Sesi Pagi 1 (08:00 WIB)', 'start_time' => '08:00:00', 'end_time' => '10:30:00', 'max_jeeps' => 20],
            ['slot_label' => 'Sesi Pagi 2 (10:30 WIB)', 'start_time' => '10:30:00', 'end_time' => '13:00:00', 'max_jeeps' => 20],
            ['slot_label' => 'Sesi Siang / Sore (13:30 WIB)', 'start_time' => '13:30:00', 'end_time' => '16:00:00', 'max_jeeps' => 20],
        ];
        foreach ($slots as $slot) {
            ScheduleSlot::updateOrCreate(['slot_label' => $slot['slot_label']], $slot);
        }

        // 3. Seed Zona Penjemputan
        $zones = [
            ['zone_name' => 'Datang Sendiri ke Basecamp Wukirsari', 'additional_fee' => 0, 'description' => 'Langsung berkumpul di pangkalan Jeep Wukirsari Cangkringan.'],
            ['zone_name' => 'Area Cangkringan & Pakem (Lokal)', 'additional_fee' => 50000, 'description' => 'Penjemputan hotel/villa area Kaliurang & Cangkringan.'],
            ['zone_name' => 'Area Sleman & Kota Yogyakarta / Stasiun', 'additional_fee' => 150000, 'description' => 'Shuttle penjemputan dari pusat Kota Jogja / Stasiun Tugu & Lempuyangan.'],
            ['zone_name' => 'Area Bandara YIA Kulon Progo', 'additional_fee' => 300000, 'description' => 'Layanan jemputan khusus Bandara YIA langsung ke pangkalan.'],
        ];
        foreach ($zones as $zone) {
            PickupZone::updateOrCreate(['zone_name' => $zone['zone_name']], $zone);
        }

        // 4. Seed Armada Jeep
        Jeep::updateOrCreate(['plate_number' => 'AB 1024 WK'], ['model_name' => 'Toyota Hardtop FJ40 (Hijau)', 'status' => 'READY']);
        Jeep::updateOrCreate(['plate_number' => 'AB 2048 WK'], ['model_name' => 'Suzuki Jimny Katana (Hitam)', 'status' => 'READY']);
        Jeep::updateOrCreate(['plate_number' => 'AB 3072 WK'], ['model_name' => 'Mitsubishi Willys 4x4', 'status' => 'READY']);

        // 5. Seed Driver Paguyuban
        Driver::updateOrCreate(['full_name' => 'Mas Joko Wukirsari'], ['phone_number' => '081234567801', 'is_active' => true]);
        Driver::updateOrCreate(['full_name' => 'Pak Slamet Merapi'], ['phone_number' => '081234567802', 'is_active' => true]);
        Driver::updateOrCreate(['full_name' => 'Bambang Kalikuning'], ['phone_number' => '081234567803', 'is_active' => true]);
    }
}
