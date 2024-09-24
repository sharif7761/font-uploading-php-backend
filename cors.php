<?php
// Allow requests from your frontend domain (e.g., http://localhost:5173)
header("Access-Control-Allow-Origin: *"); // Use '*' to allow all origins or specify your frontend URL

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
?>