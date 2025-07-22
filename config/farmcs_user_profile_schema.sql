-- FarmCS User Profile Database Schema
-- Version: 1.0
-- Created: 2024-12-08

-- Drop existing tables if they exist (be careful in production!)
DROP TABLE IF EXISTS user_profiles;
DROP TABLE IF EXISTS user_profile_images;

-- Create User Profiles Table
CREATE TABLE user_profiles (
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
);

-- Create User Profile Images Table
CREATE TABLE user_profile_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    image_type ENUM('profile', 'cover', 'document') DEFAULT 'profile',
    is_active BOOLEAN DEFAULT TRUE,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user_profiles(user_id) ON DELETE CASCADE
);

-- Create Indexes for Performance
CREATE INDEX idx_user_email ON user_profiles(email);
CREATE INDEX idx_user_profile_images ON user_profile_images(user_id, is_active);

-- Sample Trigger to Update Timestamp
DELIMITER //
CREATE TRIGGER update_user_profile_timestamp 
BEFORE UPDATE ON user_profiles
FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END;//
DELIMITER ;

-- Insert Sample Data (Optional, remove in production)
INSERT INTO user_profiles 
(user_id, first_name, last_name, email, phone_number, language, theme) 
VALUES 
(1, 'John', 'Doe', 'john.doe@example.com', '+911234567890', 'en', 'light');

INSERT INTO user_profile_images 
(user_id, image_path, image_type) 
VALUES 
(1, 'uploads/profile_pictures/default_profile.png', 'profile');

-- Permissions (Adjust as per your database user)
GRANT SELECT, INSERT, UPDATE, DELETE 
ON farmcs.user_profiles 
TO 'farmcs_user'@'localhost';

GRANT SELECT, INSERT, UPDATE, DELETE 
ON farmcs.user_profile_images 
TO 'farmcs_user'@'localhost';
