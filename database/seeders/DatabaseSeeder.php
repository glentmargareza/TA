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
        // 1. Seed Tepat 5 Paket Wisata Resmi Wukirsari
        $officialPackages = [
            [
                'slug' => 'short-trip',
                'name' => 'Short Trip',
                'description' => 'Paket petualangan rute pendek menyusuri kawasan lereng Merapi. Mengarungi jalur Susur Kali Gendol, saksi bisu Museum Kampung Hilang, Lorong Oksigen, Gerbang Tol Sokario, dan diakhiri dengan keseruan aksi basah Manuver Air.',
                'price_per_jeep' => 450000,
                'duration_minutes' => 105,
                'duration_text' => '1.5 - 2 jam',
                'destinations_count' => 5,
                'routes_list' => [
                    'Susur Kali Gendol',
                    'Museum Kampung Hilang',
                    'Lorong Oksigen',
                    'Gerbang Tol Sokario',
                    'Manuver Air',
                ],
                'gallery_images' => [
                    'img/7.jpeg',
                    'img/12.jpeg',
                    'img/13.jpeg',
                    'img/5.jpeg',
                ],
                'category_badge' => 'Paling Populer',
                'cover_image' => 'img/short_trip.jpeg',
                'is_active' => true,
            ],
            [
                'slug' => 'medium-trip-a',
                'name' => 'Medium Trip (A)',
                'description' => 'Petualangan seru dengan rute medium pilihan A. Jelajahi keindahan Volcano Park yang megah, susur sungai Kali Gendol, sejarah erupsi di Museum Kampung Hilang, kesejukan Lorong Oksigen, Gerbang Tol Sokario, dan cipratan seru Manuver Air.',
                'price_per_jeep' => 550000,
                'duration_minutes' => 135,
                'duration_text' => '2 - 2.5 jam',
                'destinations_count' => 6,
                'routes_list' => [
                    'Volcano Park',
                    'Susur Sungai Kali Gendol',
                    'Museum Kampung Hilang',
                    'Lorong Oksigen',
                    'Gerbang Tol Sokario',
                    'Manuver Air',
                ],
                'gallery_images' => [
                    'img/1.jpeg',
                    'img/6.jpeg',
                    'img/11.jpeg',
                    'img/10.jpeg',
                ],
                'category_badge' => 'Medium Rute A',
                'cover_image' => 'img/medium_trip_a.jpeg',
                'is_active' => true,
            ],
            [
                'slug' => 'medium-trip-b',
                'name' => 'Medium Trip (B)',
                'description' => 'Petualangan medium rute B dengan destinasi eksotis Selo Maruto. Rasakan ketangguhan jeep di Susur Sungai Gendol, Museum Kampung Hilang, formasi batuan Selo Maruto, Lorong Oksigen, Gerbang Tol Sokario, dan ditutup sensasi Manuver Air.',
                'price_per_jeep' => 550000,
                'duration_minutes' => 135,
                'duration_text' => '2 - 2.5 jam',
                'destinations_count' => 6,
                'routes_list' => [
                    'Susur Sungai Gendol',
                    'Museum Kampung Hilang',
                    'Selo Maruto',
                    'Lorong Oksigen',
                    'Gerbang Tol Sokario',
                    'Manuver Air',
                ],
                'gallery_images' => [
                    'img/3.jpeg',
                    'img/4.jpeg',
                    'img/6.jpeg',
                    'img/15.jpeg',
                ],
                'category_badge' => 'Medium Rute B',
                'cover_image' => 'img/medium_trip_b.jpeg',
                'is_active' => true,
            ],
            [
                'slug' => 'long-trip',
                'name' => 'Long Trip',
                'description' => 'Jelajahi keindahan terlengkap lereng Merapi dengan paket Long Trip. Menembus 8 destinasi sekaligus mulai dari Susur Sungai Watugede, Volcano Park, Susur Kali Gendol, Museum Kampung Hilang, Selo Maruto, Lorong Oksigen, Gerbang Tol Sokario, hingga Manuver Air.',
                'price_per_jeep' => 650000,
                'duration_minutes' => 195,
                'duration_text' => '2 - 3.5 jam',
                'destinations_count' => 8,
                'routes_list' => [
                    'Susur Sungai Watugede',
                    'Volcano Park',
                    'Susur Kali Gendol',
                    'Museum Kampung Hilang',
                    'Selo Maruto',
                    'Lorong Oksigen',
                    'Gerbang Tol Sokario',
                    'Manuver Air',
                ],
                'gallery_images' => [
                    'img/12.jpeg',
                    'img/8.jpeg',
                    'img/4.jpeg',
                    'img/manuver_air.png',
                ],
                'category_badge' => 'Jalur Terlengkap',
                'cover_image' => 'img/long_trip.jpeg',
                'is_active' => true,
            ],
            [
                'slug' => 'sunrise-trip',
                'name' => 'Sunrise Trip',
                'description' => 'Saksikan megahnya detik-detik matahari terbit di atas puncak Gunung Merapi dari Bunker Kaliadem mulai pukul 04.00 WIB dalam udara sejuk fajar. Rute berlanjut ke Tebing Kali Gendol, Lorong Oksigen, Gerbang Tol Sokario, Museum Kampung Hilang, Susur Sungai Kali Gendol, dan Manuver Air.',
                'price_per_jeep' => 650000,
                'duration_minutes' => 165,
                'duration_text' => 'start 04.00 WIB',
                'destinations_count' => 7,
                'routes_list' => [
                    'Bunker Kaliadem',
                    'Tebing Kali Gendol',
                    'Lorong Oksigen',
                    'Gerbang Tol Sokario',
                    'Museum Kampung Hilang',
                    'Susur Sungai Kali Gendol',
                    'Manuver Air',
                ],
                'gallery_images' => [
                    'img/16.jpeg',
                    'img/5.jpeg',
                    'img/13.jpeg',
                    'img/3.jpeg',
                ],
                'category_badge' => 'Spesial Sunrise',
                'cover_image' => 'img/sunrise_trip.jpeg',
                'is_active' => true,
            ],
        ];

        // Hapus paket di luar 5 paket resmi ini untuk menjaga integritas data
        $validSlugs = collect($officialPackages)->pluck('slug')->toArray();
        Package::whereNotIn('slug', $validSlugs)->delete();

        foreach ($officialPackages as $pkgData) {
            Package::updateOrCreate(
                ['slug' => $pkgData['slug']],
                $pkgData
            );
        }

        // 2. Seed Sesi Jam Operasional
        $slots = [
            ['slot_label' => 'Sunrise Tour (04:30 WIB)', 'start_time' => '04:30:00', 'end_time' => '08:00:00', 'max_jeeps' => 20],
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

        // 4. Seed 20 Armada Jeep Paguyuban (13 Ready, 5 On Trip, 2 Maintenance)
        $armadaOwners = [
            1 => 'Ruswantoro',
            2 => 'Joko Irianto',
            3 => 'Ganang',
            4 => 'Sriyatin',
            5 => 'Fendi H',
            6 => 'Yani Faturohman',
            7 => 'Pak Haryono',
            8 => 'Ariyanto',
            9 => 'Marjuki',
            10 => 'Ditiya',
            11 => 'Benyamin',
            12 => 'Yogel',
            13 => 'Suwanti',
            14 => 'Erni',
            15 => 'Rubiso',
            16 => 'Subagyo',
            17 => 'Triyono',
            18 => 'Bambang',
            19 => 'Supriyadi',
            20 => 'Wahyudi',
        ];
        $onTripIds = [2, 5, 15, 18, 19];
        $maintenanceIds = [13, 20];

        foreach ($armadaOwners as $no => $ownerName) {
            $numPad = str_pad($no, 4, '0', STR_PAD_LEFT);
            $plate = "AB {$numPad} WK";
            $status = 'READY';
            if (in_array($no, $onTripIds)) {
                $status = 'ON_TRIP';
            } elseif (in_array($no, $maintenanceIds)) {
                $status = 'MAINTENANCE';
            }

            Jeep::updateOrCreate(
                ['plate_number' => $plate],
                [
                    'model_name' => "Jeep #{$no} ({$ownerName})",
                    'status' => $status,
                ]
            );
        }

        // 5. Seed Driver Paguyuban (Jeep Wukirsari Adventure)
        $driversData = [
            ['full_name' => 'Joko', 'phone_number' => '081234567801'],
            ['full_name' => 'Sriyatin', 'phone_number' => '081234567802'],
            ['full_name' => 'Tringandiyanto', 'phone_number' => '081234567803'],
            ['full_name' => 'Arif Dwi', 'phone_number' => '081234567804'],
            ['full_name' => 'Fendi', 'phone_number' => '081234567805'],
            ['full_name' => 'Anjar', 'phone_number' => '081234567806'],
            ['full_name' => 'Gunardi', 'phone_number' => '081234567807'],
            ['full_name' => 'Ditiya', 'phone_number' => '081234567808'],
            ['full_name' => 'Bimo', 'phone_number' => '081234567809'],
            ['full_name' => 'Ariyanto', 'phone_number' => '081234567810'],
            ['full_name' => 'Marjuki', 'phone_number' => '081234567811'],
            ['full_name' => 'Koko', 'phone_number' => '081234567812'],
            ['full_name' => 'Edo', 'phone_number' => '081234567813'],
            ['full_name' => 'Benyamin', 'phone_number' => '081234567814'],
        ];
        foreach ($driversData as $d) {
            Driver::updateOrCreate(['full_name' => $d['full_name']], ['phone_number' => $d['phone_number'], 'is_active' => true]);
        }

        // 6. Seed Akun Default (Admin Paguyuban & Wisatawan)
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@wukirsari.com'],
            [
                'name' => 'Admin Jeep Wukirsari',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );

        $wisatawan = \App\Models\User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Budi Santoso (Wisatawan)',
                'password' => \Illuminate\Support\Facades\Hash::make('user123'),
                'role' => 'user',
                'phone' => '089876543210',
            ]
        );

        $userAndi = \App\Models\User::updateOrCreate(
            ['email' => 'andi.pratama@gmail.com'],
            ['name' => 'Andi Pratama', 'password' => \Illuminate\Support\Facades\Hash::make('user123'), 'role' => 'user', 'phone' => '081299887766']
        );

        $userSiti = \App\Models\User::updateOrCreate(
            ['email' => 'siti.nurhaliza@gmail.com'],
            ['name' => 'Siti Nurhaliza', 'password' => \Illuminate\Support\Facades\Hash::make('user123'), 'role' => 'user', 'phone' => '081322334455']
        );

        $userRizky = \App\Models\User::updateOrCreate(
            ['email' => 'rizky.maulana@gmail.com'],
            ['name' => 'Rizky Maulana', 'password' => \Illuminate\Support\Facades\Hash::make('user123'), 'role' => 'user', 'phone' => '081544556677']
        );

        $userDewi = \App\Models\User::updateOrCreate(
            ['email' => 'dewi.lestari@gmail.com'],
            ['name' => 'Dewi Lestari', 'password' => \Illuminate\Support\Facades\Hash::make('user123'), 'role' => 'user', 'phone' => '081788990011']
        );

        // 7. Seed Review Awal dari Berbagai Wisatawan
        $packagesList = Package::all();
        $pShort = $packagesList->where('slug', 'short-trip')->first();
        $pSunrise = $packagesList->where('slug', 'sunrise-trip')->first() ?? $packagesList->last();
        $pLong = $packagesList->where('slug', 'long-trip')->first();
        $pMedium = $packagesList->where('slug', 'medium-trip-a')->first();

        $sampleReviews = [
            [
                'user_id' => $wisatawan->id,
                'package_id' => $pShort?->id,
                'rating' => 5,
                'city' => 'Yogyakarta',
                'comment' => 'Pengalaman yang luar biasa! Driver-nya Mas Joko sangat ramah dan mahir mengemudi di jalur bebatuan kali kuning. Manuver airnya seru banget, basah-basahan tapi puas!',
            ],
            [
                'user_id' => $userAndi->id,
                'package_id' => $pMedium?->id,
                'rating' => 5,
                'city' => 'Semarang',
                'comment' => 'Medium Trip A rutenya sangat komplit. Museum Kampung Hilang bikin terharu melihat sisa erupsi, ditutup aksi seru di Kali Gendol. Armada jeep Toyota FJ40-nya gagah dan terawat.',
            ],
            [
                'user_id' => $userSiti->id,
                'package_id' => $pSunrise?->id,
                'rating' => 5,
                'city' => 'Surabaya',
                'comment' => 'Berangkat jam 04.30 pagi terbayar lunas dengan sunrise Merapi yang sangat magis! Driver bantu fotokan di spot terbaik. Sangat recommended buat liburan keluarga.',
            ],
            [
                'user_id' => $userRizky->id,
                'package_id' => $pLong?->id,
                'rating' => 5,
                'city' => 'Bandung',
                'comment' => 'Long Trip 8 destinasi sekaligus! Jalur Selo Maruto dan Lorong Oksigen sangat sejuk. Pemesanan di web cepat dan dapat e-tiket langsung.',
            ],
            [
                'user_id' => $userDewi->id,
                'package_id' => $pShort?->id,
                'rating' => 4,
                'city' => 'Jakarta Selatan',
                'comment' => 'Anak-anak suka sekali saat melewati genangan air. Driver mengutamakan keselamatan offroad. Pasti akan kembali lagi ajak keluarga besar!',
            ],
        ];

        foreach ($sampleReviews as $rev) {
            \App\Models\Review::updateOrCreate(
                ['user_id' => $rev['user_id'], 'comment' => $rev['comment']],
                $rev
            );
        }
    }
}
