<?php
header("Access-Control-Allow-Origin: http://localhost:5173");

// Allow the following HTTP methods
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Allow the following headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}
header("Access-Control-Allow-Origin: http://localhost:5173");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Include database connection
include('db.php');

// Handle file upload and store information in the database
if (isset($_FILES['fontFile'])) {
    $groupName = $_POST['groupName'];
    $file = $_FILES['fontFile'];

    // Ensure the file is a .ttf file
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    if ($fileExtension !== 'ttf') {
        echo json_encode(['error' => 'Invalid file type. Only .ttf files are allowed.']);
        exit;
    }

    // Move uploaded font to the server
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filePath = $uploadDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Save the font info into the database
        $sql = "INSERT INTO font_groups (group_name, font_name, font_path) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$groupName, $file['name'], $filePath]);

        echo json_encode(['success' => true, 'message' => 'Font uploaded successfully!']);
    } else {
        echo json_encode(['error' => 'Error uploading font.']);
    }
}
?>