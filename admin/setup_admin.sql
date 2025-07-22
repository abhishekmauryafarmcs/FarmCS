-- Create admin_credentials table if it doesn't exist
CREATE TABLE IF NOT EXISTS admin_credentials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (username: admin, password: admin123)
-- Note: The password is hashed using PHP's password_hash function
INSERT INTO admin_credentials (username, password) 
VALUES ('admin', '$2y$10$8i5Oo58HaO.yhzXFu6o3.uRdYKbP.RAWz8yMp90090JfzQM6.oNi2')
ON DUPLICATE KEY UPDATE username = username; 