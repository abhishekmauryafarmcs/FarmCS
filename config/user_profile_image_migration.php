<?php
// User Profile Image Migration Script

require_once 'connection.php';

class UserProfileImageMigration {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function migrateProfileImages() {
        try {
            // Begin transaction
            $this->conn->begin_transaction();

            // Add profile_picture column if not exists
            $alter_users_query = "ALTER TABLE users 
                ADD COLUMN IF NOT EXISTS profile_picture VARCHAR(255) NULL 
                AFTER last_name";
            $this->conn->query($alter_users_query);

            // Create user_profile_images table if not exists
            $create_table_query = "CREATE TABLE IF NOT EXISTS user_profile_images (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                image_path VARCHAR(255) NOT NULL,
                image_type ENUM('profile', 'cover', 'document') DEFAULT 'profile',
                is_active BOOLEAN DEFAULT TRUE,
                uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_user_profile_image (user_id, image_type)
            )";
            $this->conn->query($create_table_query);

            // Migrate existing profile pictures
            $migrate_query = "INSERT INTO user_profile_images (user_id, image_path, image_type)
                SELECT user_id, profile_picture, 'profile'
                FROM users
                WHERE profile_picture IS NOT NULL
                ON DUPLICATE KEY UPDATE image_path = profile_picture";
            $this->conn->query($migrate_query);

            // Create performance index
            $create_index_query = "CREATE INDEX IF NOT EXISTS idx_user_profile_images 
                ON user_profile_images(user_id, is_active)";
            $this->conn->query($create_index_query);

            // Commit transaction
            $this->conn->commit();

            echo "Profile image migration completed successfully.";
            return true;
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->conn->rollback();
            
            error_log("Profile Image Migration Error: " . $e->getMessage());
            echo "Migration failed: " . $e->getMessage();
            return false;
        }
    }

    public function verifyMigration() {
        // Verify migration by checking table structures and data
        $checks = [
            'users_column' => $this->checkUsersColumn(),
            'profile_images_table' => $this->checkProfileImagesTable(),
            'migrated_data' => $this->checkMigratedData()
        ];

        return $checks;
    }

    private function checkUsersColumn() {
        $query = "SHOW COLUMNS FROM users LIKE 'profile_picture'";
        $result = $this->conn->query($query);
        return $result->num_rows > 0;
    }

    private function checkProfileImagesTable() {
        $query = "SHOW TABLES LIKE 'user_profile_images'";
        $result = $this->conn->query($query);
        return $result->num_rows > 0;
    }

    private function checkMigratedData() {
        $query = "SELECT COUNT(*) as migrated_count FROM user_profile_images";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['migrated_count'] > 0;
    }
}

// Run migration if script is directly accessed
if (basename($_SERVER['PHP_SELF']) === 'user_profile_image_migration.php') {
    $migration = new UserProfileImageMigration($conn);
    $migration->migrateProfileImages();
    
    // Optional: Verify migration
    $verification_results = $migration->verifyMigration();
    print_r($verification_results);
}
?>
