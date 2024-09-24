<?php
include 'cors.php';
include('db.php');

$data = json_decode(file_get_contents('php://input'), true);
$groupId = $data['groupId'];

$sql = "DELETE FROM font_group_details WHERE font_group_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$groupId]);

$sql = "DELETE FROM font_groups WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$groupId]);

echo json_encode(['success' => true, 'message' => 'Font group deleted successfully!']);
?>