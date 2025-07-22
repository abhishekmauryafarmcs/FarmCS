CREATE TABLE IF NOT EXISTS soil_moisture_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    moisture_value FLOAT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_timestamp (timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
