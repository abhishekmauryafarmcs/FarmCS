<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../config/Database.php');

try {
    $database = new Database();
    $db = $database->connect();

    // Drop users table if exists
    $db->exec("DROP TABLE IF EXISTS users");

    // Recreate users table
    $db->exec("CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        first_name VARCHAR(50),
        last_name VARCHAR(50),
        state VARCHAR(50),
        district VARCHAR(50),
        farm_type VARCHAR(20),
        farm_size DECIMAL(10,2),
        profile_image VARCHAR(255),
        role VARCHAR(20) DEFAULT 'user',
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    echo "Database reset successful. Users table recreated.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
