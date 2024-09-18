<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

include('db.php');

$sql = "SELECT * FROM fonts ORDER BY created_at DESC";
$stmt = $pdo->query($sql);

$fonts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'fonts' => $fonts]);
?>