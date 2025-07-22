-- Migration script to add settings-related columns to users table

-- Check and add profile_picture column
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS profile_picture VARCHAR(255) DEFAULT NULL;

-- Check and add first_name column
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS first_name VARCHAR(50) DEFAULT NULL;

-- Check and add last_name column
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS last_name VARCHAR(50) DEFAULT NULL;

-- Check and add language column
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS language VARCHAR(10) DEFAULT 'en';

-- Check and add email_notifications column
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS email_notifications TINYINT(1) DEFAULT 1;

-- Check and add sms_notifications column
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS sms_notifications TINYINT(1) DEFAULT 0;

-- Check and add theme column
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS theme VARCHAR(20) DEFAULT 'light';

-- Update existing users with default values if needed
UPDATE users 
SET 
    first_name = COALESCE(first_name, SUBSTRING_INDEX(email, '@', 1)),
    last_name = COALESCE(last_name, ''),
    language = COALESCE(language, 'en'),
    email_notifications = COALESCE(email_notifications, 1),
    sms_notifications = COALESCE(sms_notifications, 0),
    theme = COALESCE(theme, 'light'),
    profile_picture = COALESCE(profile_picture, NULL);

-- Create uploads directory for profile pictures if not exists
-- This is a PHP script to ensure directory exists
<?php
$profile_pic_dir = 'uploads/profile_pictures';
if (!file_exists($profile_pic_dir)) {
    mkdir($profile_pic_dir, 0777, true);
}
?>

-- Add mobile column if it doesn't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS mobile VARCHAR(10) UNIQUE;

-- Update existing users with temporary mobile numbers
UPDATE users 
SET mobile = CONCAT('1234567', LPAD(user_id, 3, '0')) 
WHERE mobile IS NULL;

-- Make mobile column NOT NULL
ALTER TABLE users 
MODIFY COLUMN mobile VARCHAR(10) NOT NULL;

-- Drop email column if it exists
ALTER TABLE users 
DROP COLUMN IF EXISTS email;

-- Add index on mobile column
ALTER TABLE users 
ADD UNIQUE INDEX IF NOT EXISTS idx_mobile (mobile);
