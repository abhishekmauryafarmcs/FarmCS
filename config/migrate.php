<?php
require_once 'database.php';

function createUsersTable() {
    $database = new Database();
    $conn = $database->connect();

    try {
        // Drop table if exists
        $dropTable = "DROP TABLE IF EXISTS users";
        $conn->exec($dropTable);

        // Create users table
        $createTable = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            state VARCHAR(100) NOT NULL,
            district VARCHAR(100) NOT NULL,
            farm_type VARCHAR(100) NOT NULL,
            farm_size DECIMAL(10, 2) NULL,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            last_login TIMESTAMP NULL,
            INDEX idx_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $conn->exec($createTable);
        echo "Users table created successfully\n";

    } catch(PDOException $e) {
        die("Error creating users table: " . $e->getMessage());
    }

    $database->closeConnection();
}

// Run the migration
createUsersTable();
?>
