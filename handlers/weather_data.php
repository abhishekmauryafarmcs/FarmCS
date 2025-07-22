<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

function fetchWeatherData($latitude, $longitude) {
    $params = [
        'latitude' => $latitude,
        'longitude' => $longitude,
        'current' => [
            'temperature_2m',
            'relative_humidity_2m',
            'apparent_temperature',
            'precipitation',
            'wind_speed_10m',
            'wind_direction_10m',
            'weather_code'
        ],
        'hourly' => [
            'temperature_2m',
            'relative_humidity_2m',
            'precipitation_probability',
            'wind_speed_10m',
            'weather_code'
        ],
        'daily' => [
            'weather_code',
            'temperature_2m_max',
            'temperature_2m_min',
            'sunrise',
            'sunset',
            'uv_index_max',
            'precipitation_sum',
            'precipitation_probability_max',
            'wind_speed_10m_max'
        ],
        'timezone' => 'auto',
        'forecast_days' => 7
    ];

    $url = "https://api.open-meteo.com/v1/forecast?" . http_build_query($params);

    // Initialize cURL
    $ch = curl_init();
    
    // Set cURL options
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
    ]);

    // Execute cURL request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    // Close cURL connection
    curl_close($ch);

    // Handle errors
    if ($error) {
        throw new Exception("API Connection Error: " . $error);
    }

    if ($httpCode !== 200) {
        throw new Exception("API Error: Received HTTP code " . $httpCode);
    }

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON response from API");
    }

    if (isset($data['error']) && $data['error'] === true) {
        throw new Exception("API Error: " . ($data['reason'] ?? 'Unknown error'));
    }

    return $data;
}

// Predefined location mapping for Indian states
$stateCoordinates = [
    'Andhra Pradesh' => ['lat' => 15.9129, 'lon' => 79.7400],
    'Karnataka' => ['lat' => 15.3173, 'lon' => 75.7139],
    'Maharashtra' => ['lat' => 19.7515, 'lon' => 75.7139],
    'Tamil Nadu' => ['lat' => 11.1271, 'lon' => 78.6569],
    'Kerala' => ['lat' => 10.8505, 'lon' => 76.2711],
    'Punjab' => ['lat' => 31.1471, 'lon' => 75.3412],
    'Uttar Pradesh' => ['lat' => 26.8467, 'lon' => 80.9462],
    'Gujarat' => ['lat' => 22.2587, 'lon' => 71.1924],
    'Rajasthan' => ['lat' => 27.0238, 'lon' => 74.2179],
    'Madhya Pradesh' => ['lat' => 22.9734, 'lon' => 78.6569],
    // Add more states as needed
];

// Weather code mapping
$weatherCodes = [
    0 => ['description' => 'Clear sky', 'icon' => 'fa-sun'],
    1 => ['description' => 'Mainly clear', 'icon' => 'fa-cloud-sun'],
    2 => ['description' => 'Partly cloudy', 'icon' => 'fa-cloud-sun'],
    3 => ['description' => 'Overcast', 'icon' => 'fa-cloud'],
    45 => ['description' => 'Foggy', 'icon' => 'fa-smog'],
    48 => ['description' => 'Depositing rime fog', 'icon' => 'fa-smog'],
    51 => ['description' => 'Light drizzle', 'icon' => 'fa-cloud-rain'],
    53 => ['description' => 'Moderate drizzle', 'icon' => 'fa-cloud-rain'],
    55 => ['description' => 'Dense drizzle', 'icon' => 'fa-cloud-rain'],
    61 => ['description' => 'Slight rain', 'icon' => 'fa-cloud-rain'],
    63 => ['description' => 'Moderate rain', 'icon' => 'fa-cloud-rain'],
    65 => ['description' => 'Heavy rain', 'icon' => 'fa-cloud-showers-heavy'],
    71 => ['description' => 'Slight snow fall', 'icon' => 'fa-snowflake'],
    73 => ['description' => 'Moderate snow fall', 'icon' => 'fa-snowflake'],
    75 => ['description' => 'Heavy snow fall', 'icon' => 'fa-snowflake'],
    80 => ['description' => 'Slight rain showers', 'icon' => 'fa-cloud-rain'],
    81 => ['description' => 'Moderate rain showers', 'icon' => 'fa-cloud-rain'],
    82 => ['description' => 'Violent rain showers', 'icon' => 'fa-cloud-showers-heavy'],
    95 => ['description' => 'Thunderstorm', 'icon' => 'fa-bolt'],
    96 => ['description' => 'Thunderstorm with light hail', 'icon' => 'fa-cloud-bolt'],
    99 => ['description' => 'Thunderstorm with heavy hail', 'icon' => 'fa-cloud-bolt']
];

try {
    // Debug logging
    error_log("Session data: " . print_r($_SESSION, true));
    
    // Check if we have the minimum required session data
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Please log in to view weather information', 401);
    }

    // Get user's state - first try state from session, then try to get it from database
    $state = null;
    if (isset($_SESSION['state'])) {
        $state = $_SESSION['state'];
    } else {
        // Try to get state from database using user_id
        require_once '../config/db_connect.php';
        $stmt = $conn->prepare("SELECT state FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $state = $row['state'];
            $_SESSION['state'] = $state; // Cache it in session
        }
    }

    if (!$state) {
        throw new Exception('State information not found. Please update your profile.', 404);
    }

    // Get coordinates based on user's state
    if (!isset($stateCoordinates[$state])) {
        throw new Exception("No coordinates found for state: $state");
    }
    
    $coordinates = $stateCoordinates[$state];
    $weatherData = fetchWeatherData($coordinates['lat'], $coordinates['lon']);

    echo json_encode([
        'success' => true,
        'data' => $weatherData,
        'weatherCodes' => $weatherCodes
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
