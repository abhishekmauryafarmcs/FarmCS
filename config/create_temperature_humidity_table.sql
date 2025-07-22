-- Create Temperature and Humidity Logs Table
CREATE TABLE IF NOT EXISTS temperature_humidity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    temperature_celsius DECIMAL(5,2) NOT NULL,
    temperature_fahrenheit DECIMAL(5,2) NOT NULL,
    humidity DECIMAL(5,2) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Optional: Add index for performance
    INDEX idx_timestamp (timestamp)
);

-- Optional: Create a view for recent temperature and humidity data
CREATE OR REPLACE VIEW recent_temperature_humidity AS
SELECT 
    id,
    temperature_celsius,
    temperature_fahrenheit,
    humidity,
    timestamp,
    
    -- Calculate averages
    (SELECT AVG(temperature_celsius) FROM temperature_humidity_logs 
     WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 1 DAY)) AS avg_daily_celsius,
    
    (SELECT AVG(humidity) FROM temperature_humidity_logs 
     WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 1 DAY)) AS avg_daily_humidity,
    
    -- Calculate min and max
    (SELECT MIN(temperature_celsius) FROM temperature_humidity_logs 
     WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 1 DAY)) AS min_daily_celsius,
    
    (SELECT MAX(temperature_celsius) FROM temperature_humidity_logs 
     WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 1 DAY)) AS max_daily_celsius
    
FROM temperature_humidity_logs
ORDER BY timestamp DESC
LIMIT 50;
