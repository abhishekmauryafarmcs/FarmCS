<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['angle']) && is_numeric($data['angle'])) {
        $angle = max(0, min(180, intval($data['angle']))); // Ensure angle is between 0 and 180
        
        // Store the servo angle in a file
        file_put_contents('../logs/servo_angle.txt', $angle);
        
        $response['success'] = true;
        $response['message'] = 'Servo angle updated successfully';
        $response['angle'] = $angle;
    } else {
        $response['success'] = false;
        $response['message'] = 'Invalid angle parameter';
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Return current servo angle
    $angle = 0;
    if (file_exists('../logs/servo_angle.txt')) {
        $angle = intval(file_get_contents('../logs/servo_angle.txt'));
    }
    
    $response['success'] = true;
    $response['angle'] = $angle;
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
