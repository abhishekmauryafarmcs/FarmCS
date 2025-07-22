<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
require_once 'config/db_connect.php';

try {
    // Create users table with mobile number instead of email
    $sql = "CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE,
        mobile VARCHAR(10) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        state VARCHAR(50) NOT NULL,
        district VARCHAR(50) NOT NULL,
        farm_type VARCHAR(50) NOT NULL,
        farm_size DECIMAL(10,2) NOT NULL,
        profile_image VARCHAR(255),
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL,
        INDEX idx_mobile (mobile)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $db->exec($sql);
    echo "Users table created or already exists\n";

    // Check if table structure is correct
    $result = $db->query("DESCRIBE users");
    echo "\nCurrent table structure:\n";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " - " . $row['Type'] . " - " . $row['Null'] . " - " . $row['Key'] . "\n";
    }

    echo "\nDatabase setup completed successfully!\n";

} catch (Exception $e) {
    echo "Setup failed: " . $e->getMessage() . "\n";
    exit(1);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database Setup</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 600px; 
            margin: 0 auto; 
            padding: 20px; 
            text-align: center; 
        }
    </style>
</head>
<body>
    <h1>FarmCS Database Setup</h1>
    <p>Initial admin credentials:<br>
    Email: admin@farmcs.com<br>
    Password: password</p>
    <p><strong>IMPORTANT:</strong> Change this password immediately after first login!</p>
</body>
</html>
