<?php
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