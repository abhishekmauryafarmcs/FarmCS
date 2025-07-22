<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['state'])) {
        $state = $data['state'];
        
        // Store the LED state in a file for persistence
        file_put_contents('../logs/led_state.txt', $state ? '1' : '0');
        
        $response['success'] = true;
        $response['message'] = 'LED state updated successfully';
        $response['state'] = $state;
    } else {
        $response['success'] = false;
        $response['message'] = 'State parameter is missing';
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Return current LED state
    $state = '0';
    if (file_exists('../logs/led_state.txt')) {
        $state = file_get_contents('../logs/led_state.txt');
    }
    
    $response['success'] = true;
    $response['state'] = $state === '1';
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
