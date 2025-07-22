-- Create Database
CREATE DATABASE IF NOT EXISTS farmcs;

-- Use the database
USE farmcs;

-- Create Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    profile_picture VARCHAR(255),
    language VARCHAR(10) DEFAULT 'en',
    email_notifications TINYINT(1) DEFAULT 1,
    sms_notifications TINYINT(1) DEFAULT 0,
    theme VARCHAR(20) DEFAULT 'light',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create an initial admin user (replace with your own credentials)
INSERT INTO users (
    email, 
    password, 
    first_name, 
    last_name, 
    language, 
    email_notifications, 
    sms_notifications, 
    theme
) VALUES (
    'admin@farmcs.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- hashed 'password'
    'Farm',
    'Admin',
    'en',
    1,
    0,
    'light'
) ON DUPLICATE KEY UPDATE email = email;
