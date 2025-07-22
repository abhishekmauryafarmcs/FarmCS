<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once '../config/db_config.php';

// Initialize response array
$response = [
    'success' => false,
    'message' => ''
];

try {
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get raw POST data
    $json = file_get_contents('php://input');
    error_log("Raw POST data: " . $json);
    
    $data = json_decode($json, true);
    error_log("Decoded data: " . print_r($data, true));

    // Validate JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid request format: ' . json_last_error_msg());
    }

    // Validate required fields
    if (!isset($data['mobile']) || !isset($data['password'])) {
        throw new Exception('Mobile number and password are required');
    }

    // Sanitize inputs
    $mobile = trim($data['mobile']);
    $password = $data['password'];

    // Validate mobile
    if (!preg_match('/^[0-9]{10}$/', $mobile)) {
        throw new Exception('Invalid mobile number format');
    }

    // Get database connection
    $db = getDBConnection();
    error_log("Database connection established");

    // Check if user exists
    $stmt = $db->prepare('SELECT * FROM users WHERE mobile = ?');
    $stmt->bind_param('s', $mobile);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        throw new Exception('Invalid mobile number or password');
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        throw new Exception('Invalid mobile number or password');
    }

    // Check if account is active
    if ($user['is_active'] != 1) {
        throw new Exception('Your account is not active');
    }

    // Update last login time
    $updateStmt = $db->prepare('UPDATE users SET last_login = NOW() WHERE user_id = ?');
    $updateStmt->bind_param('i', $user['user_id']);
    $updateStmt->execute();
    $updateStmt->close();

    // Set session data
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['mobile'] = $user['mobile'];
    $_SESSION['state'] = $user['state'];
    $_SESSION['district'] = $user['district'];
    $_SESSION['is_logged_in'] = true;
    $_SESSION['last_activity'] = time();

    // Prepare response data
    $response['success'] = true;
    $response['message'] = 'Login successful';
    $response['data'] = [
        'user_id' => $user['user_id'],
        'username' => $user['username'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'mobile' => $user['mobile'],
        'state' => $user['state'],
        'district' => $user['district']
    ];

} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    $response['message'] = $e->getMessage();
}

// Send JSON response
echo json_encode($response);
exit;
?>
