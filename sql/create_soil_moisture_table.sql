-- Create Soil Moisture Sensor Data Table
CREATE TABLE IF NOT EXISTS soil_moisture_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    moisture_value FLOAT NOT NULL COMMENT 'Soil moisture percentage',
    temperature FLOAT NULL COMMENT 'Optional: Temperature reading with moisture',
    humidity FLOAT NULL COMMENT 'Optional: Humidity reading',
    sensor_location VARCHAR(100) NULL COMMENT 'Location of the soil moisture sensor',
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of data recording',
    status ENUM('NORMAL', 'LOW', 'CRITICAL') GENERATED ALWAYS AS 
        (CASE 
            WHEN moisture_value >= 60 THEN 'NORMAL'
            WHEN moisture_value >= 30 AND moisture_value < 60 THEN 'LOW'
            ELSE 'CRITICAL'
        END) STORED COMMENT 'Automated status based on moisture level',
    
    INDEX idx_timestamp (timestamp),
    INDEX idx_status (status)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_unicode_ci 
  COMMENT='Real-time soil moisture sensor data tracking';

-- Optional: Create a view for recent soil moisture data
CREATE OR REPLACE VIEW recent_soil_moisture_data AS
SELECT 
    id, 
    moisture_value, 
    temperature, 
    humidity, 
    sensor_location, 
    timestamp, 
    status,
    TIMESTAMPDIFF(MINUTE, timestamp, NOW()) AS minutes_ago
FROM 
    soil_moisture_data
WHERE 
    timestamp >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
ORDER BY 
    timestamp DESC;

-- Optional: Create a trigger to log significant moisture changes
DELIMITER //
CREATE TRIGGER trg_soil_moisture_change 
AFTER INSERT ON soil_moisture_data
FOR EACH ROW
BEGIN
    IF NEW.moisture_value <= 20 THEN
        INSERT INTO system_alerts (
            alert_type, 
            alert_message, 
            severity, 
            timestamp
        ) VALUES (
            'SOIL_MOISTURE', 
            CONCAT('Critical low moisture detected: ', NEW.moisture_value, '% at ', NEW.sensor_location), 
            'HIGH', 
            NOW()
        );
    END IF;
END;//
DELIMITER ;

-- Optional: Create a procedure to get moisture trend
DELIMITER //
CREATE PROCEDURE get_soil_moisture_trend(IN hours INT)
BEGIN
    SELECT 
        AVG(moisture_value) as avg_moisture,
        MIN(moisture_value) as min_moisture,
        MAX(moisture_value) as max_moisture,
        COUNT(*) as total_readings
    FROM 
        soil_moisture_data
    WHERE 
        timestamp >= DATE_SUB(NOW(), INTERVAL hours HOUR);
END;//
DELIMITER ;
