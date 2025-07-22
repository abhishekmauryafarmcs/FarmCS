-- Create System Alerts Table
CREATE TABLE IF NOT EXISTS system_alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alert_type VARCHAR(50) NOT NULL COMMENT 'Type of alert (e.g., SOIL_MOISTURE, TEMPERATURE)',
    alert_message TEXT NOT NULL COMMENT 'Detailed alert message',
    severity ENUM('LOW', 'MEDIUM', 'HIGH') NOT NULL DEFAULT 'MEDIUM' COMMENT 'Severity of the alert',
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of alert generation',
    is_read BOOLEAN DEFAULT FALSE COMMENT 'Whether the alert has been read/acknowledged',
    
    INDEX idx_type (alert_type),
    INDEX idx_severity (severity),
    INDEX idx_timestamp (timestamp)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_unicode_ci 
  COMMENT='System-wide alerts and notifications';
