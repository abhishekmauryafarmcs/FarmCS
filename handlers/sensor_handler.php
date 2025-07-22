<?php
header('Content-Type: application/json');

// Database connection
require_once '../config/db_connect.php';

try {
    // Optional: Allow filtering by device_id
    $deviceId = isset($_GET['device_id']) ? mysqli_real_escape_string($conn, $_GET['device_id']) : null;

    // Construct the query with optional device filtering
    $query = "SELECT 
                soil_moisture, 
                temperature, 
                humidity, 
                light_intensity, 
                device_id,
                timestamp 
              FROM sensor_data";
    
    // Add device filter if provided
    if ($deviceId) {
        $query .= " WHERE device_id = '$deviceId'";
    }

    // Order by most recent and limit to 1 record
    $query .= " ORDER BY timestamp DESC LIMIT 1";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception("Database query failed: " . mysqli_error($conn));
    }
    
    $sensorData = mysqli_fetch_assoc($result);
    
    if (!$sensorData) {
        // Return a valid response with null/defaults if no data is found
        echo json_encode([
            'success' => true,
            'data' => [
                'soilMoisture' => null,
                'temperature' => null,
                'humidity' => null,
                'lightIntensity' => null,
                'deviceId' => null,
                'timestamp' => null
            ]
        ]);
        exit;
    }
    
    // Return sensor data as JSON
    echo json_encode([
        'success' => true,
        'data' => [
            'soilMoisture' => round($sensorData['soil_moisture'], 1),
            'temperature' => round($sensorData['temperature'], 1),
            'humidity' => round($sensorData['humidity'], 1),
            'lightIntensity' => round($sensorData['light_intensity'], 1),
            'deviceId' => $sensorData['device_id'],
            'timestamp' => $sensorData['timestamp']
        ]
    ]);
} catch (Exception $e) {
    // Error handling
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Close database connection
mysqli_close($conn);
?>
