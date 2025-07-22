<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers for JSON response
header('Content-Type: application/json');

// Include database connection
require_once '../config/db_connect.php';

// Function to validate and sanitize input
function sanitizeInput($input) {
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false, 
        'message' => 'Method Not Allowed'
    ]);
    exit();
}

// Get raw POST data
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

// Validate input
if (!$data) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid JSON data'
    ]);
    exit();
}

// Required fields
$requiredFields = [
    'soil_moisture', 
    'temperature', 
    'humidity', 
    'light_intensity', 
    'device_id'
];

// Check for missing fields
foreach ($requiredFields as $field) {
    if (!isset($data[$field])) {
        http_response_code(400);
        echo json_encode([
            'success' => false, 
            'message' => "Missing required field: $field"
        ]);
        exit();
    }
}

// Sanitize and validate inputs
$soilMoisture = sanitizeInput($data['soil_moisture']);
$temperature = sanitizeInput($data['temperature']);
$humidity = sanitizeInput($data['humidity']);
$lightIntensity = sanitizeInput($data['light_intensity']);

// Replace deprecated filter_var with htmlspecialchars for device_id
$deviceId = htmlspecialchars(trim($data['device_id']), ENT_QUOTES, 'UTF-8');

// Validate and sanitize device_id
if (empty($deviceId) || !preg_match('/^[A-Za-z0-9_-]+$/', $deviceId)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => "Invalid device ID format"
    ]);
    exit();
}

// Get user_id based on device_id (you might need to create a devices table)
// For now, we'll use a default user_id
$userId = 1; // Default user ID, replace with actual logic to map device to user

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO sensor_data 
    (user_id, soil_moisture, temperature, humidity, light_intensity, device_id) 
    VALUES (?, ?, ?, ?, ?, ?)");

// Bind parameters
$stmt->bind_param(
    "idddds", 
    $userId, 
    $soilMoisture, 
    $temperature, 
    $humidity, 
    $lightIntensity, 
    $deviceId
);

// Execute the statement
try {
    if ($stmt->execute()) {
        // Success response
        http_response_code(200);
        echo json_encode([
            'success' => true, 
            'message' => 'Sensor data received and stored successfully',
            'sensor_data_id' => $stmt->insert_id
        ]);
    } else {
        // Database insertion error
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to store sensor data: ' . $stmt->error
        ]);
    }
} catch (Exception $e) {
    // Catch any unexpected errors
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Server error: ' . $e->getMessage()
    ]);
} finally {
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
