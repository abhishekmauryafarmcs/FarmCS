<?php
// Database Configuration and Setup Script

// Include main connection file
require_once 'connection.php';

class DatabaseSetup {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function createUserProfileTables() {
        $sql_queries = [
            "CREATE TABLE IF NOT EXISTS user_profiles (
                user_id INT PRIMARY KEY,
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                phone_number VARCHAR(20),
                language ENUM('en', 'hi') DEFAULT 'en',
                theme ENUM('light', 'dark') DEFAULT 'light',
                email_notifications BOOLEAN DEFAULT TRUE,
                sms_notifications BOOLEAN DEFAULT FALSE,
                account_type ENUM('farmer', 'admin', 'staff') DEFAULT 'farmer',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                last_login TIMESTAMP NULL
            )",
            "CREATE TABLE IF NOT EXISTS user_profile_images (
                image_id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                image_path VARCHAR(255) NOT NULL,
                image_type ENUM('profile', 'cover', 'document') DEFAULT 'profile',
                is_active BOOLEAN DEFAULT TRUE,
                uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES user_profiles(user_id) ON DELETE CASCADE
            )",
            "CREATE INDEX IF NOT EXISTS idx_user_email ON user_profiles(email)",
            "CREATE INDEX IF NOT EXISTS idx_user_profile_images ON user_profile_images(user_id, is_active)"
        ];

        foreach ($sql_queries as $query) {
            if ($this->conn->query($query) === FALSE) {
                throw new Exception("Error creating table: " . $this->conn->error);
            }
        }

        return true;
    }

    public function setupDefaultData() {
        // Check if default data already exists
        $check_query = "SELECT COUNT(*) as count FROM user_profiles WHERE user_id = 1";
        $result = $this->conn->query($check_query);
        $row = $result->fetch_assoc();

        if ($row['count'] == 0) {
            $insert_queries = [
                "INSERT INTO user_profiles 
                (user_id, first_name, last_name, email, phone_number, language, theme) 
                VALUES 
                (1, 'John', 'Doe', 'john.doe@example.com', '+911234567890', 'en', 'light')",
                
                "INSERT INTO user_profile_images 
                (user_id, image_path, image_type) 
                VALUES 
                (1, 'uploads/profile_pictures/default_profile.png', 'profile')"
            ];

            foreach ($insert_queries as $query) {
                if ($this->conn->query($query) === FALSE) {
                    throw new Exception("Error inserting default data: " . $this->conn->error);
                }
            }
        }

        return true;
    }

    public function runSetup() {
        try {
            $this->createUserProfileTables();
            $this->setupDefaultData();
            echo "Database setup completed successfully.";
            return true;
        } catch (Exception $e) {
            error_log("Database Setup Error: " . $e->getMessage());
            echo "Database setup failed: " . $e->getMessage();
            return false;
        }
    }
}

// Run setup if this script is directly accessed
if (basename($_SERVER['PHP_SELF']) === 'database_setup.php') {
    $setup = new DatabaseSetup($conn);
    $setup->runSetup();
}
?>
