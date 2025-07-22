<?php
require_once dirname(__DIR__) . '/config/db_connect.php';

// Check if admin_credentials table exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'admin_credentials'");
if ($tableCheck->num_rows == 0) {
    echo "Admin credentials table does not exist. Creating table...\n";
    
    // Create table
    $createTableSQL = "CREATE TABLE admin_credentials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100),
        email VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($createTableSQL) === TRUE) {
        echo "Table created successfully.\n";
    } else {
        echo "Error creating table: " . $conn->error . "\n";
    }
}

// Check if default admin user exists
$userCheck = $conn->prepare("SELECT * FROM admin_credentials WHERE username = ?");
$defaultUsername = 'admin';
$userCheck->bind_param("s", $defaultUsername);
$userCheck->execute();
$result = $userCheck->get_result();

if ($result->num_rows == 0) {
    echo "Default admin user does not exist. Creating user...\n";
    
    // Create default admin user
    $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $insertUserSQL = $conn->prepare("INSERT INTO admin_credentials (username, password, name, email) VALUES (?, ?, ?, ?)");
    $name = 'System Administrator';
    $email = 'admin@farmcs.com';
    $insertUserSQL->bind_param("ssss", $defaultUsername, $defaultPassword, $name, $email);
    
    if ($insertUserSQL->execute()) {
        echo "Default admin user created successfully.\n";
    } else {
        echo "Error creating admin user: " . $insertUserSQL->error . "\n";
    }
} else {
    echo "Default admin user already exists.\n";
}

$conn->close();
?>
