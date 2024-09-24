<?php
include 'cors.php';
include('db.php');

$sql = "SELECT * FROM fonts ORDER BY created_at DESC";
$stmt = $pdo->query($sql);

$fonts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'fonts' => $fonts]);
?>