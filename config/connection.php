<?php
// Database Connection Configuration

// Database credentials
$db_host = 'localhost';     // Database host (usually localhost)
$db_username = 'root';      // Your database username
$db_password = '';          // Your database password (empty for XAMPP default)
$db_name = 'farmcs';        // Your database name

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    // Detailed error logging
    error_log("Database Connection Failed: " . $conn->connect_error);
    
    // User-friendly error display (comment out in production)
    die("Connection failed: " . $conn->connect_error . 
        "<br>Please check your database settings in config/connection.php");
}

// Optional: Set character set
$conn->set_charset("utf8mb4");

// Optional: Error reporting for development
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>
