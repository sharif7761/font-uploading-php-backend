<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'db.php'; // Include database connection

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!empty($data->font_id)) {
        $font_id = intval($data->font_id);

        try {
            // Start a transaction
            $pdo->beginTransaction();

            // Step 1: Delete from `font_group_details` where the font_id matches
            $deleteGroupDetailsStmt = $pdo->prepare("DELETE FROM font_group_details WHERE font_id = :font_id");
            $deleteGroupDetailsStmt->bindParam(':font_id', $font_id);
            $deleteGroupDetailsStmt->execute();

            // Step 2: Delete the font entry from the `fonts` table
            $stmt = $pdo->prepare("SELECT font_path FROM fonts WHERE id = :font_id");
            $stmt->bindParam(':font_id', $font_id);
            $stmt->execute();
            $font = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($font) {
                $fontFilePath = 'uploads/fonts/' . $font['font_path'];
                if (file_exists($fontFilePath)) {
                    unlink($fontFilePath); // Remove the font file
                }

                // Step 3: Delete the font itself
                $deleteFontStmt = $pdo->prepare("DELETE FROM fonts WHERE id = :font_id");
                $deleteFontStmt->bindParam(':font_id', $font_id);
                $deleteFontStmt->execute();

                // Commit the transaction
                $pdo->commit();
                echo json_encode(['message' => 'Font and related data deleted successfully']);
            } else {
                echo json_encode(['message' => 'Font not found']);
            }
        } catch (PDOException $e) {
            // Rollback in case of error
            $pdo->rollBack();
            echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Font ID is required']);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
}
?>