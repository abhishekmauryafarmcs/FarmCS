<?php
// Recreate users table with correct schema
session_start();
include 'config/connection.php';

// Check if user is an admin (recommended for security)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Unauthorized access. Admin rights required.");
}

// Drop existing users table if it exists
$drop_table_query = "DROP TABLE IF EXISTS users";
if ($conn->query($drop_table_query) === FALSE) {
    die("Error dropping existing table: " . $conn->error);
}

// Create new users table with comprehensive schema
$create_table_query = "
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    profile_picture VARCHAR(255),
    language VARCHAR(10) DEFAULT 'en',
    email_notifications TINYINT(1) DEFAULT 1,
    sms_notifications TINYINT(1) DEFAULT 0,
    theme VARCHAR(20) DEFAULT 'light',
    role ENUM('admin', 'user', 'guest') DEFAULT 'user',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($create_table_query) === FALSE) {
    die("Error creating table: " . $conn->error);
}

// Create an initial admin user
$admin_email = 'admin@farmcs.com';
$admin_password = password_hash('password', PASSWORD_DEFAULT);

$insert_admin_query = "
INSERT INTO users 
(email, password, first_name, last_name, role) 
VALUES 
(?, ?, 'Farm', 'Admin', 'admin')
";

$stmt = $conn->prepare($insert_admin_query);
$stmt->bind_param("ss", $admin_email, $admin_password);

if ($stmt->execute() === FALSE) {
    die("Error inserting admin user: " . $stmt->error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users Table Recreated</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 600px; 
            margin: 0 auto; 
            padding: 20px; 
            text-align: center; 
        }
        .success {
            color: green;
            background-color: #e6f3e6;
            padding: 20px;
            border-radius: 5px;
        }
        .warning {
            color: orange;
            background-color: #fff4e6;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Users Table Recreated Successfully</h1>
    
    <div class="success">
        <h2>Table Created</h2>
        <p>A new users table has been created with the correct schema.</p>
    </div>
    
    <div class="warning">
        <h2>Important</h2>
        <p>Initial admin user created:</p>
        <p>Email: admin@farmcs.com</p>
        <p>Password: password</p>
        <p><strong>Please change this password immediately!</strong></p>
    </div>
</body>
</html>
<?php
$conn->close();
?>
