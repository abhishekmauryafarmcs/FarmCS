-- Create database if not exists
CREATE DATABASE IF NOT EXISTS farmcs;

-- Use the database
USE farmcs;

-- Create users table if not exists
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
<<<<<<< HEAD
    email VARCHAR(100) NOT NULL UNIQUE,
=======
    user_id INT,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
>>>>>>> 98c75b7 (updated code 6 th may)
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    district VARCHAR(50) NOT NULL,
<<<<<<< HEAD
    farm_type VARCHAR(50) NOT NULL,
    farm_size DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
); 
=======
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Add mobile column if it doesn't exist (for existing installations)
ALTER TABLE users ADD COLUMN IF NOT EXISTS mobile VARCHAR(15) UNIQUE AFTER email;

-- Add username column if it doesn't exist (for existing installations)
ALTER TABLE users ADD COLUMN IF NOT EXISTS username VARCHAR(50) UNIQUE AFTER mobile;

-- Add password column if it doesn't exist (for existing installations)
ALTER TABLE users ADD COLUMN IF NOT EXISTS password VARCHAR(255) NOT NULL AFTER username;

-- Add is_active column if it doesn't exist (for existing installations)
ALTER TABLE users ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT TRUE AFTER district;

-- Add last_login column if it doesn't exist (for existing installations)
ALTER TABLE users ADD COLUMN IF NOT EXISTS last_login TIMESTAMP NULL AFTER is_active;

-- Add user_id column if it doesn't exist
ALTER TABLE users ADD COLUMN IF NOT EXISTS user_id INT AFTER id;

-- Update existing records to set user_id equal to id
UPDATE users SET user_id = id WHERE user_id IS NULL;

-- Create trigger to automatically set user_id to id for new records
DELIMITER //
CREATE TRIGGER IF NOT EXISTS before_insert_users 
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
    SET NEW.user_id = NEW.id;
END;//

-- Create trigger to keep user_id in sync with id on updates
CREATE TRIGGER IF NOT EXISTS before_update_users
BEFORE UPDATE ON users
FOR EACH ROW
BEGIN
    SET NEW.user_id = NEW.id;
END;//
DELIMITER ;

-- Create a view that aliases id as user_id for backward compatibility
CREATE OR REPLACE VIEW users_with_alias AS
SELECT 
    id as user_id,
    id,
    email,
    mobile,
    username,
    password,
    first_name,
    last_name,
    state,
    district,
    is_active,
    last_login,
    created_at,
    updated_at
FROM users; 
>>>>>>> 98c75b7 (updated code 6 th may)
