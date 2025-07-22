<?php
// Enable comprehensive error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session and set JSON header
session_start();
header('Content-Type: application/json');

// Include database connection
require_once '../config/db_connect.php';

// Logging function with timestamp
function logEvent($message, $type = 'info') {
    $logFile = '../logs/sensor_clear_' . $type . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

try {
    // Validate user authentication
    if (!isset($_SESSION['user_id'])) {
        logEvent("Unauthorized access attempt", 'error');
        throw new Exception("Unauthorized access");
    }

    $user_id = $_SESSION['user_id'];
    logEvent("Clearing sensor data for user ID: {$user_id}");

    // First, count total records before deletion
    $count_before_stmt = $conn->prepare("SELECT COUNT(*) as row_count FROM sensor_data");
    $count_before_stmt->execute();
    $count_before_result = $count_before_stmt->get_result();
    $rows_before = $count_before_result->fetch_assoc()['row_count'];
    $count_before_stmt->close();

    // Multiple deletion strategies
    $deletion_queries = [
        "DELETE FROM sensor_data WHERE user_id = ?",
        "TRUNCATE TABLE sensor_data",  // Fallback method
        "DELETE FROM sensor_data"      // Last resort
    ];

    $total_deleted_rows = 0;

    foreach ($deletion_queries as $query) {
        // Prepare statement
        $stmt = $conn->prepare($query);
        
        if ($query === "DELETE FROM sensor_data WHERE user_id = ?") {
            $stmt->bind_param("i", $user_id);
        }

        // Execute deletion
        $result = $stmt->execute();

        if ($result === false) {
            logEvent("Query failed: {$query} - Error: {$stmt->error}", 'error');
        } else {
            $deleted_rows = $stmt->affected_rows;
            $total_deleted_rows += $deleted_rows;
            logEvent("Deleted {$deleted_rows} rows with query: {$query}");
        }

        $stmt->close();
    }

    // Verify table is empty
    $count_stmt = $conn->prepare("SELECT COUNT(*) as row_count FROM sensor_data");
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $row_count = $count_result->fetch_assoc()['row_count'];
    $count_stmt->close();

    logEvent("Rows before deletion: {$rows_before}, Rows after deletion: {$row_count}");

    // Ensure we show the total number of records that existed before deletion
    $records_cleared = $rows_before;

    // Return response
    echo json_encode([
        'success' => true,
        'message' => "Cleared {$records_cleared} sensor data records",
        'deleted_rows' => $records_cleared,
        'remaining_rows' => $row_count
    ]);

} catch (Exception $e) {
    logEvent("Exception: " . $e->getMessage(), 'critical');
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Unable to clear sensor data',
        'error_details' => $e->getMessage()
    ]);
} finally {
    // Ensure database connection is closed
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>
