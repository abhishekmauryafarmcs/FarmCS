CREATE TABLE IF NOT EXISTS weather_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    temperature_metric FLOAT,
    temperature_imperial FLOAT,
    weather_text VARCHAR(100),
    weather_code INT,
    wind_speed_metric FLOAT,
    wind_speed_imperial FLOAT,
    humidity INT,
    precipitation_probability INT,
    is_day_time BOOLEAN,
    timestamp DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
