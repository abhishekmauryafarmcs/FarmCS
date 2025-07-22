-- Migration Script for FarmCS Users Database
-- Adds support for profile images in existing users table

-- Add profile_picture column if not exists
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS profile_picture VARCHAR(255) NULL 
AFTER last_name;

-- Create user_profile_images table if not exists
CREATE TABLE IF NOT EXISTS user_profile_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    image_type ENUM('profile', 'cover', 'document') DEFAULT 'profile',
    is_active BOOLEAN DEFAULT TRUE,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_profile_image (user_id, image_type)
);

-- Migrate existing profile pictures from users table to user_profile_images
INSERT INTO user_profile_images (user_id, image_path, image_type)
SELECT user_id, profile_picture, 'profile'
FROM users
WHERE profile_picture IS NOT NULL
ON DUPLICATE KEY UPDATE image_path = profile_picture;

-- Optional: Create index for performance
CREATE INDEX idx_user_profile_images ON user_profile_images(user_id, is_active);
