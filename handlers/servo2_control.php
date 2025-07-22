<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

$response = array();

// File to store servo angle
$angleFile = '../logs/servo2_angle.txt';

// Handle POST request to update angle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['angle']) && is_numeric($data['angle'])) {
        $angle = max(0, min(180, intval($data['angle']))); // Ensure angle is between 0 and 180
        
        // Store the servo angle in a file
        file_put_contents($angleFile, $angle);
        
        $response['success'] = true;
        $response['message'] = 'Servo 2 angle updated successfully';
        $response['angle'] = $angle;
    } else {
        $response['success'] = false;
        $response['message'] = 'Invalid angle value';
    }
}
// Handle GET request to retrieve current angle
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($angleFile)) {
        $angle = intval(file_get_contents($angleFile));
    } else {
        $angle = 0; // Default angle
        file_put_contents($angleFile, $angle);
    }
    
    $response['success'] = true;
    $response['angle'] = $angle;
}
// Invalid request method
else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?> 