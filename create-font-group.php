<?php
include 'cors.php';
include('db.php');

$data = json_decode(file_get_contents('php://input'), true);

$groupName = $data['groupName'];
$fontIds = $data['fontIds'];

if (count($fontIds) < 2) {
    echo json_encode(['error' => 'You must select at least two fonts.']);
    exit;
}

$sql = "INSERT INTO font_groups (group_name) VALUES (?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$groupName]);
$groupId = $pdo->lastInsertId();

$sql = "INSERT INTO font_group_details (font_group_id, font_id) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);
foreach ($fontIds as $fontId) {
    $stmt->execute([$groupId, $fontId]);
}

echo json_encode(['success' => true, 'message' => 'Font group created successfully!']);
?>