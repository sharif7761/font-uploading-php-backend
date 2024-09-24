<?php
include 'cors.php';

include('db.php');

$sql = "SELECT * FROM font_groups";
$stmt = $pdo->query($sql);
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($groups as &$group) {
    $sql = "SELECT f.* FROM fonts f
            JOIN font_group_details fd ON f.id = fd.font_id
            WHERE fd.font_group_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$group['id']]);
    $group['fonts'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode(['success' => true, 'groups' => $groups]);
?>