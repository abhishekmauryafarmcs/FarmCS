-- Create sensor_data table in farmcs database
USE farmcs;

-- Drop table if exists to recreate
DROP TABLE IF EXISTS sensor_data;

CREATE TABLE sensor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT 1,  -- Default to first user
    soil_moisture FLOAT,
    temperature FLOAT,
    humidity FLOAT,
    light_intensity FLOAT,
    device_id VARCHAR(50),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes
CREATE INDEX idx_sensor_data_timestamp ON sensor_data(timestamp);
CREATE INDEX idx_sensor_data_user ON sensor_data(user_id);

-- Optional: Add a sample insert statement
INSERT INTO sensor_data 
(user_id, soil_moisture, temperature, humidity, light_intensity, device_id) 
VALUES 
(1, 65.5, 25.3, 45.2, 750.0, 'FARM_SENSOR_001');
