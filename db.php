<?php
// db.php
$host = 'localhost';
$db = 'font_system';
$user = 'root';  // Change this based on your MySQL username
$pass = '';  // Change this based on your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>