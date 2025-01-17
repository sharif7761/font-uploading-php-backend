<?php
include 'cors.php';
include('db.php');

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!empty($data->font_id)) {
        $font_id = intval($data->font_id);

        try {
            $pdo->beginTransaction();

            $deleteGroupDetailsStmt = $pdo->prepare("DELETE FROM font_group_details WHERE font_id = :font_id");
            $deleteGroupDetailsStmt->bindParam(':font_id', $font_id);
            $deleteGroupDetailsStmt->execute();

            $stmt = $pdo->prepare("SELECT font_path FROM fonts WHERE id = :font_id");
            $stmt->bindParam(':font_id', $font_id);
            $stmt->execute();
            $font = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($font) {
                $fontFilePath = 'uploads/fonts/' . $font['font_path'];
                if (file_exists($fontFilePath)) {
                    unlink($fontFilePath);
                }

                $deleteFontStmt = $pdo->prepare("DELETE FROM fonts WHERE id = :font_id");
                $deleteFontStmt->bindParam(':font_id', $font_id);
                $deleteFontStmt->execute();

                $pdo->commit();
                echo json_encode(['message' => 'Font and related data deleted successfully', 'status' => true]);
            } else {
                echo json_encode(['message' => 'Font not found', 'status' => false]);
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo json_encode(['message' => 'Error: ' . $e->getMessage(), 'status' => false]);
        }
    } else {
        echo json_encode(['message' => 'Font ID is required', 'status' => false]);
    }
} else {
    echo json_encode(['message' => 'Invalid request method', 'status' => false]);
}
?>