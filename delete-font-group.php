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
$groupId = $data['groupId'];

// Delete from font_group_details
$sql = "DELETE FROM font_group_details WHERE font_group_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$groupId]);

// Delete from font_groups
$sql = "DELETE FROM font_groups WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$groupId]);

echo json_encode(['success' => true, 'message' => 'Font group deleted successfully!']);
?>