<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set secure session parameters BEFORE session_start()
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
<<<<<<< HEAD
=======
ini_set('session.use_strict_mode', 1);
>>>>>>> 98c75b7 (updated code 6 th may)
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.gc_maxlifetime', 3600); // 1 hour
ini_set('session.cookie_lifetime', 3600); // 1 hour

// Start or resume session
session_start();

// Function to check if user is logged in
function isLoggedIn() {
<<<<<<< HEAD
    // Add additional session validation
=======
    // Check if user_id exists and is not empty
>>>>>>> 98c75b7 (updated code 6 th may)
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        return false;
    }

    // Check session timeout (1 hour)
<<<<<<< HEAD
    $max_lifetime = 3600; // 1 hour in seconds
    if (isset($_SESSION['last_activity']) && 
        (time() - $_SESSION['last_activity']) > $max_lifetime) {
=======
    if (isset($_SESSION['last_activity']) && 
        (time() - $_SESSION['last_activity']) > 3600) {
>>>>>>> 98c75b7 (updated code 6 th may)
        // Session expired, destroy it
        session_unset();
        session_destroy();
        return false;
    }

    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
    return true;
}

// Function to set user session
function setUserSession($userData) {
    $_SESSION['user_id'] = $userData['id'];
    $_SESSION['email'] = $userData['email'];
    $_SESSION['first_name'] = $userData['firstName'];
    $_SESSION['last_name'] = $userData['lastName'];
    $_SESSION['state'] = $userData['state'];
    $_SESSION['district'] = $userData['district'];
    $_SESSION['farm_type'] = $userData['farmType'];
    $_SESSION['farm_size'] = $userData['farmSize'];
    $_SESSION['last_activity'] = time();
}

// Function to get user session data
function getUserSession() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
<<<<<<< HEAD
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['email'],
        'firstName' => $_SESSION['first_name'],
        'lastName' => $_SESSION['last_name'],
        'state' => $_SESSION['state'],
        'district' => $_SESSION['district'],
        'farmType' => $_SESSION['farm_type'],
        'farmSize' => $_SESSION['farm_size']
=======
        'user_id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'first_name' => $_SESSION['first_name'] ?? '',
        'last_name' => $_SESSION['last_name'] ?? '',
        'mobile' => $_SESSION['mobile'] ?? '',
        'state' => $_SESSION['state'] ?? '',
        'district' => $_SESSION['district'] ?? ''
>>>>>>> 98c75b7 (updated code 6 th may)
    ];
}

// Function to clear user session
function clearUserSession() {
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
}

<<<<<<< HEAD
// Check session status endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
=======
// Handle AJAX requests
header('Content-Type: application/json');

// Get JSON data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data && isset($data['action'])) {
    switch ($data['action']) {
>>>>>>> 98c75b7 (updated code 6 th may)
        case 'check':
            echo json_encode([
                'loggedIn' => isLoggedIn(),
                'userData' => getUserSession()
            ]);
            break;
            
        case 'logout':
<<<<<<< HEAD
            clearUserSession();
=======
            session_unset();
            session_destroy();
>>>>>>> 98c75b7 (updated code 6 th may)
            echo json_encode(['success' => true]);
            break;
            
        case 'keepalive':
            if (isLoggedIn()) {
                $_SESSION['last_activity'] = time();
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            break;
<<<<<<< HEAD
    }
    exit;
}
=======
            
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
>>>>>>> 98c75b7 (updated code 6 th may)
