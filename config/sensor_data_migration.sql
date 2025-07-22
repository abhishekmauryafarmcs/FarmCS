-- Create sensor_data table
CREATE TABLE IF NOT EXISTS sensor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    soil_moisture FLOAT,
    temperature FLOAT,
    humidity FLOAT,
    light_intensity FLOAT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Optional: Create an index for faster queries
CREATE INDEX idx_sensor_data_timestamp ON sensor_data(timestamp);

-- Optional: Add a sample insert statement
INSERT INTO sensor_data 
(user_id, soil_moisture, temperature, humidity, light_intensity) 
VALUES 
(1, 65.5, 25.3, 45.2, 750.0);
