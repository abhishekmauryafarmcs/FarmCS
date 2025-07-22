CREATE TABLE IF NOT EXISTS light_sensor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lux_value FLOAT NOT NULL,
    timestamp DATETIME NOT NULL,
    INDEX idx_timestamp (timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
