<?php
session_start();

// Include database connection
require_once 'config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Unauthorized access. Please log in.'
    ]);
    exit();
}

// Function to handle file upload
function uploadProfilePicture($file, $user_id) {
    // Define upload directory
    $upload_dir = 'uploads/profile_pictures/';
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = $user_id . '_profile_' . uniqid() . '.' . $file_extension;
    $upload_path = $upload_dir . $new_filename;

    // Allowed file types
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    
    // Validate file type
    if (!in_array($file['type'], $allowed_types)) {
        return [
            'success' => false, 
            'message' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.'
        ];
    }

    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return [
            'success' => false, 
            'message' => 'File size exceeds 5MB limit.'
        ];
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        return [
            'success' => true, 
            'path' => $upload_path
        ];
    }

    return [
        'success' => false, 
        'message' => 'File upload failed.'
    ];
}

// Function to get existing profile image
function getCurrentProfileImage($conn, $user_id) {
    $stmt = $conn->prepare("SELECT image_path FROM user_profile_images WHERE user_id = ? AND image_type = 'profile' AND is_active = 1 LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['image_path'];
    }
    
    return null;
}

// Prepare response
$response = [
    'success' => false,
    'message' => 'Unknown error occurred.'
];

try {
    // Get user ID from session
    $user_id = $_SESSION['user_id'];

    // Get current profile image if exists
    $current_profile_image = getCurrentProfileImage($conn, $user_id);

    // Prepare update query
    $update_query = "UPDATE users SET first_name = ?, last_name = ?";
    $params = [
        $_POST['first_name'] ?? '',
        $_POST['last_name'] ?? ''
    ];

    // Handle profile picture upload
    $profile_picture_path = $current_profile_image;
    if (!empty($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $upload_result = uploadProfilePicture($_FILES['profile_picture'], $user_id);
        
        if ($upload_result['success']) {
            // Add profile picture to update query
            $profile_picture_path = $upload_result['path'];
            
            // Insert or update profile image in user_profile_images table
            $image_stmt = $conn->prepare("INSERT INTO user_profile_images (user_id, image_path, image_type) VALUES (?, ?, 'profile') ON DUPLICATE KEY UPDATE image_path = ?");
            $image_stmt->bind_param("iss", $user_id, $profile_picture_path, $profile_picture_path);
            $image_stmt->execute();
            $image_stmt->close();
        } else {
            // Log upload error but continue with other updates
            error_log("Profile picture upload failed: " . $upload_result['message']);
        }
    }

    // Always update profile_picture in users table
    $update_query .= ", profile_picture = ?";
    $params[] = $profile_picture_path;

    // Add WHERE clause
    $update_query .= " WHERE user_id = ?";
    $params[] = $user_id;

    // Prepare and execute statement
    $stmt = $conn->prepare($update_query);
    
    // Dynamically bind parameters based on number of params
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Profile updated successfully',
            'profile_picture' => $profile_picture_path
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Failed to update profile: ' . $stmt->error
        ];
    }

    $stmt->close();
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ];
}

// Close database connection
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>
