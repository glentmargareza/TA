<?php
// api/packages/index.php
// Endpoint GET: Mengambil daftar paket wisata resmi

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT id, name, slug, description, price_per_jeep, duration_minutes, category_badge, cover_image 
              FROM packages 
              WHERE is_active = 1 
              ORDER BY price_per_jeep ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $packages = $stmt->fetchAll();

    echo json_encode([
        "success" => true,
        "data" => $packages
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Gagal mengambil paket: " . $e->getMessage()
    ]);
}
