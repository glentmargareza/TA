-- Database: db_jeep_wukirsari
-- Platform Pemesanan Jeep Wukirsari Adventure Tour (Tugas Akhir)

CREATE DATABASE IF NOT EXISTS `db_jeep_wukirsari` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_jeep_wukirsari`;

-- 1. Tabel Paket Wisata
CREATE TABLE IF NOT EXISTS `packages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT NOT NULL,
  `price_per_jeep` DECIMAL(12,2) NOT NULL,
  `duration_minutes` INT NOT NULL,
  `category_badge` VARCHAR(50) DEFAULT NULL,
  `cover_image` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Tabel Sesi Jam Operasional (Slots)
CREATE TABLE IF NOT EXISTS `schedule_slots` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `slot_label` VARCHAR(50) NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `max_jeeps` INT DEFAULT 15,
  `is_active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- 3. Tabel Zona Penjemputan (Pickup Zones)
CREATE TABLE IF NOT EXISTS `pickup_zones` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `zone_name` VARCHAR(100) NOT NULL,
  `additional_fee` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `description` TEXT DEFAULT NULL
) ENGINE=InnoDB;

-- 4. Tabel Pemesanan (Bookings)
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_code` VARCHAR(50) NOT NULL UNIQUE,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `customer_email` VARCHAR(100) DEFAULT NULL,
  `package_id` INT NOT NULL,
  `slot_id` INT NOT NULL,
  `trip_date` DATE NOT NULL,
  `jeep_quantity` INT NOT NULL DEFAULT 1,
  `passenger_count` INT NOT NULL,
  `arrival_type` ENUM('self_drive', 'pickup') DEFAULT 'self_drive',
  `pickup_zone_id` INT DEFAULT NULL,
  `pickup_address` TEXT DEFAULT NULL,
  `package_subtotal` DECIMAL(12,2) NOT NULL,
  `pickup_fee` DECIMAL(12,2) DEFAULT 0.00,
  `total_amount` DECIMAL(12,2) NOT NULL,
  `paid_amount` DECIMAL(12,2) DEFAULT 0.00,
  `remaining_amount` DECIMAL(12,2) DEFAULT 0.00,
  `payment_mode` ENUM('DP', 'FULL') DEFAULT 'FULL',
  `booking_status` ENUM('PENDING', 'DP_PAID', 'FULL_PAID', 'CANCELLED', 'COMPLETED') DEFAULT 'PENDING',
  `lock_expires_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`package_id`) REFERENCES `packages`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`slot_id`) REFERENCES `schedule_slots`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`pickup_zone_id`) REFERENCES `pickup_zones`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 5. Tabel Pembayaran (Payments)
CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NOT NULL,
  `transaction_ref` VARCHAR(100) DEFAULT NULL,
  `payment_stage` ENUM('DOWN_PAYMENT', 'SETTLEMENT', 'FULL_PAYMENT') NOT NULL,
  `payment_type` VARCHAR(50) DEFAULT NULL,
  `gross_amount` DECIMAL(12,2) NOT NULL,
  `payment_status` ENUM('PENDING', 'SETTLED', 'EXPIRED', 'FAILED') DEFAULT 'PENDING',
  `snap_token` VARCHAR(255) DEFAULT NULL,
  `paid_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Tabel Armada Jeep
CREATE TABLE IF NOT EXISTS `jeeps` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `plate_number` VARCHAR(20) NOT NULL UNIQUE,
  `model_name` VARCHAR(50) NOT NULL,
  `status` ENUM('READY', 'MAINTENANCE', 'ON_TRIP') DEFAULT 'READY'
) ENGINE=InnoDB;

-- 7. Tabel Driver Paguyuban
CREATE TABLE IF NOT EXISTS `drivers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(100) NOT NULL,
  `phone_number` VARCHAR(20) NOT NULL,
  `license_number` VARCHAR(50) DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- 8. Tabel Penugasan Armada (Assignments)
CREATE TABLE IF NOT EXISTS `booking_assignments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NOT NULL,
  `jeep_id` INT NOT NULL,
  `driver_id` INT NOT NULL,
  `assigned_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`jeep_id`) REFERENCES `jeeps`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`driver_id`) REFERENCES `drivers`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 9. DATA AWAL (SEED DATA RESMI)
INSERT INTO `packages` (`id`, `name`, `slug`, `description`, `price_per_jeep`, `duration_minutes`, `category_badge`, `cover_image`) VALUES
(1, 'Adventure', 'adventure', 'Jelajahi jalur lava dan pemandangan menakjubkan Gunung Merapi dengan sensasi petualangan yang seru.', 450000.00, 105, 'Paling Populer', 'adventure.jpg'),
(2, 'Lavature', 'lavature', 'Nikmati keindahan alam Merapi dengan jalur yang lebih menantang dan pemandangan yang memukau.', 550000.00, 135, 'Paket Sedang', 'lavature.jpg'),
(3, 'Sunrise Tour', 'sunrise-tour', 'Saksikan matahari terbit di atas Merapi dengan suasana yang sejuk dan menenangkan.', 700000.00, 210, 'Sunrise Spesial', 'sunrise.jpg')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

INSERT INTO `schedule_slots` (`id`, `slot_label`, `start_time`, `end_time`, `max_jeeps`) VALUES
(1, 'Sunrise Tour', '04:30:00', '08:00:00', 15),
(2, 'Sesi Pagi 1', '08:00:00', '10:30:00', 20),
(3, 'Sesi Pagi 2', '10:30:00', '13:00:00', 20),
(4, 'Sesi Siang / Sore', '13:30:00', '16:00:00', 20)
ON DUPLICATE KEY UPDATE `slot_label`=VALUES(`slot_label`);

INSERT INTO `pickup_zones` (`id`, `zone_name`, `additional_fee`, `description`) VALUES
(1, 'Datang Sendiri ke Basecamp Wukirsari', 0.00, 'Langsung berkumpul di pangkalan Jeep Wukirsari Cangkringan.'),
(2, 'Area Cangkringan & Pakem', 50000.00, 'Penjemputan langsung di penginapan / hotel sekitar Cangkringan & Kaliurang.'),
(3, 'Area Sleman & Kota Yogyakarta', 150000.00, 'Penjemputan di hotel pusat kota / Stasiun Tugu & Lempuyangan via shuttle.'),
(4, 'Area Bandara YIA Kulon Progo', 300000.00, 'Layanan shuttle khusus bandara YIA langsung menuju pangkalan Wukirsari.')
ON DUPLICATE KEY UPDATE `zone_name`=VALUES(`zone_name`);

INSERT INTO `jeeps` (`plate_number`, `model_name`, `status`) VALUES
('AB 1024 WK', 'Toyota Hardtop FJ40 (Hijau)', 'READY'),
('AB 2048 WK', 'Suzuki Jimny Katana (Hitam)', 'READY'),
('AB 3072 WK', 'Mitsubishi Willys 4x4', 'READY')
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);

INSERT INTO `drivers` (`full_name`, `phone_number`, `is_active`) VALUES
('Mas Joko Wukirsari', '081234567801', 1),
('Pak Slamet Merapi', '081234567802', 1),
('Bambang Kalikuning', '081234567803', 1)
ON DUPLICATE KEY UPDATE `is_active`=VALUES(`is_active`);
