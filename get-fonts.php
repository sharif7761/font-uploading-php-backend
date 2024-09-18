<?php
include('db.php');

$sql = "SELECT * FROM font_groups ORDER BY created_at DESC";
$stmt = $pdo->query($sql);

$fonts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'fonts' => $fonts]);
?>