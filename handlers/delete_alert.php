<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);
$alert_id = $data['alert_id'] ?? null;

if (!$alert_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Alert ID is required']);
    exit();
}

// Delete the alert
$query = "DELETE FROM alerts WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $alert_id, $_SESSION['user_id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to delete alert']);
}

$stmt->close();
$conn->close();
?>
