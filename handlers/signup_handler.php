<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    $requiredFields = ['firstName', 'lastName', 'mobile', 'password', 'state', 'district'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            throw new Exception(ucfirst($field) . " is required");
        }
    }

    // Sanitize inputs
    $firstName = trim($data['firstName']);
    $lastName = trim($data['lastName']);
    $mobile = trim($data['mobile']);
    $password = $data['password'];
    $state = trim($data['state']);
    $district = trim($data['district']);

    error_log("Sanitized data - Name: $firstName $lastName, Mobile: $mobile, State: $state, District: $district");

    // Validate mobile
    if (!preg_match('/^[0-9]{10}$/', $mobile)) {
        throw new Exception('Invalid mobile number format');
    }

    // Password validation
    if (strlen($password) < 8) {
        throw new Exception('Password must be at least 8 characters long');
    }

    // Generate username and email
    $username = strtolower($firstName . $lastName);
    $email = strtolower($firstName . '.' . $lastName . '@farmcs.com');
    error_log("Generated username: $username, email: $email");

    // Get database connection
    $db = getDBConnection();
    error_log("Database connection established");

    // Check if mobile already exists
    $stmt = $db->prepare('SELECT COUNT(*) FROM users WHERE mobile = ?');
    $stmt->bind_param('s', $mobile);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        throw new Exception('Mobile number already registered');
    }

    // Check if username exists and append number if needed
    $baseUsername = $username;
    $counter = 1;
    while (true) {
        $stmt = $db->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        if ($count === 0) break;
        $username = $baseUsername . $counter;
        $counter++;
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $db->prepare('
        INSERT INTO users (
            username, email, mobile, password,
            first_name, last_name, state, district
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ');

    $stmt->bind_param(
        'ssssssss',
        $username,
        $email,
        $mobile,
        $passwordHash,
        $firstName,
        $lastName,
        $state,
        $district
    );

    error_log("Attempting to insert new user");
    if (!$stmt->execute()) {
        throw new Exception('Failed to create account: ' . $stmt->error);
    }

    $userId = $stmt->insert_id;
    error_log("User created successfully with ID: $userId");

    $response['success'] = true;
    $response['message'] = 'Account created successfully! Please login to continue.';
    $response['userId'] = $userId;

} catch (Exception $e) {
    error_log("Signup error: " . $e->getMessage());
    $response['message'] = $e->getMessage();
}

error_log("Final response: " . json_encode($response));
echo json_encode($response);
exit;
?>
