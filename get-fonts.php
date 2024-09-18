<?php
// Allow from any origin (for development only, restrict this in production)
header("Access-Control-Allow-Origin: *");

// Allow the following HTTP methods
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Allow the following headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Include database connection (if required)
include('db.php');

// Fetch data
$sql = "SELECT * FROM font_groups ORDER BY created_at DESC";
$stmt = $pdo->query($sql);

$fonts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'fonts' => $fonts]);
?>
