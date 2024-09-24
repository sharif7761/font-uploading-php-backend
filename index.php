<?php
include 'cors.php';
include('db.php');

if (isset($_FILES['fontFile'])) {
    $file = $_FILES['fontFile'];

    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    if ($fileExtension !== 'ttf') {
        echo json_encode(['error' => 'Invalid file type. Only .ttf files are allowed.']);
        exit;
    }

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filePath = $uploadDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        $sql = "INSERT INTO font_groups (font_name, font_path) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$file['name'], $filePath]);

        echo json_encode(['success' => true, 'message' => 'Font uploaded successfully!']);
    } else {
        echo json_encode(['error' => 'Error uploading font.']);
    }
}
?>