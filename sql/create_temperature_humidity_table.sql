-- Create temperature_humidity_logs table if not exists
CREATE TABLE IF NOT EXISTS temperature_humidity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    temperature_celsius DECIMAL(5,2) NOT NULL,
    temperature_fahrenheit DECIMAL(5,2) NOT NULL,
    humidity DECIMAL(5,2) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add index for performance
CREATE INDEX idx_timestamp ON temperature_humidity_logs (timestamp);
