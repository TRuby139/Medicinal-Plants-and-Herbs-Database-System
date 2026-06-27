<?php
// config/db.php
// Database connection using procedural mysqli

$host = 'localhost';
$username = 'root';
$password = ''; // Default XAMPP password is empty
$database = 'medicinal_plants_db';

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    // Send a JSON error response if connection fails, as this will be included in API endpoints
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit();
}

// Set charset to utf8mb4 for full Unicode support
mysqli_set_charset($conn, "utf8mb4");
?>
