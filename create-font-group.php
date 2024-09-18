<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

include('db.php');

$data = json_decode(file_get_contents('php://input'), true);

$groupName = $data['groupName'];
$fontIds = $data['fontIds'];

if (count($fontIds) < 2) {
    echo json_encode(['error' => 'You must select at least two fonts.']);
    exit;
}

// Insert into font_groups
$sql = "INSERT INTO font_groups (group_name) VALUES (?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$groupName]);
$groupId = $pdo->lastInsertId();

// Insert into font_group_details
$sql = "INSERT INTO font_group_details (font_group_id, font_id) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);
foreach ($fontIds as $fontId) {
    $stmt->execute([$groupId, $fontId]);
}

echo json_encode(['success' => true, 'message' => 'Font group created successfully!']);
?>