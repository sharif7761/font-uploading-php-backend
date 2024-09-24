<?php
// Allow requests from your frontend domain (e.g., http://localhost:5173)
header("Access-Control-Allow-Origin: http://localhost:5173");

// Allow specific HTTP methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow specific headers to be sent in requests
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // If it's an OPTIONS request, stop execution and return success status
    http_response_code(200);
    exit();
}

  // Handle CORS
include 'db.php';    // Database connection



// Check if the group_id is provided
if (isset($_GET['group_id'])) {
    $groupId = $_GET['group_id'];

    try {
        // Fetch the specific font group by ID
        $sql = "SELECT * FROM font_groups WHERE id = :group_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['group_id' => $groupId]);

        $group = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($group) {
            // Fetch the fonts associated with this group
            $sqlFonts = "SELECT fonts.id, fonts.font_name
                         FROM font_group_details
                         INNER JOIN fonts ON font_group_details.font_id = fonts.id
                         WHERE font_group_details.font_group_id = :group_id";
            $stmtFonts = $pdo->prepare($sqlFonts);
            $stmtFonts->execute(['group_id' => $groupId]);

            $fonts = $stmtFonts->fetchAll(PDO::FETCH_ASSOC);

            // Return specific group and its fonts
            echo json_encode([
                'group' => $group,
                'font_ids' => array_column($fonts, 'id'),  // List of font IDs
                'fonts' => $fonts                         // Full font details
            ]);
        } else {
            http_response_code(404); // Font group not found
            echo json_encode(['error' => 'Font group not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500); // Internal server error
        echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
    }
} else {
    http_response_code(400); // Bad request
    echo json_encode(['error' => 'group_id parameter is required']);
}
?>