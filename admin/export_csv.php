<?php
session_start();
require_once '../config/db_config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Get all users
$query = "SELECT first_name, last_name, email, state, district, farm_type, farm_size, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($query);

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="FarmCS_Users_' . date('Y-m-d') . '.csv"');

// Create output stream
$output = fopen('php://output', 'w');

// Add UTF-8 BOM for proper Excel encoding
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Add headers
fputcsv($output, ['Name', 'Email', 'State', 'District', 'Farm Type', 'Farm Size (acres)', 'Registered On']);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = $row['first_name'] . ' ' . $row['last_name'];
        $created_at = date('M d, Y', strtotime($row['created_at']));
        
        // Prepare row data
        $csv_row = [
            $name,
            $row['email'],
            $row['state'],
            $row['district'],
            $row['farm_type'],
            $row['farm_size'],
            $created_at
        ];
        
        // Add row to CSV
        fputcsv($output, $csv_row);
    }
}

// Close the output stream
fclose($output);
exit;
?>
