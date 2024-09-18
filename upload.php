<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

include('db.php');

if (isset($_FILES['fontFile'])) {
    $groupName = $_POST['groupName'];
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
        $sql = "INSERT INTO fonts (group_name, font_name, font_path) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$groupName, $file['name'], $filePath]);

        echo json_encode(['success' => true, 'message' => 'Font uploaded successfully!']);
    } else {
        echo json_encode(['error' => 'Error moving uploaded font to server.']);
    }
} else {
    echo json_encode(['error' => 'No file uploaded.']);
}
?>