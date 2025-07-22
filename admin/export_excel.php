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

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="FarmCS_Users_' . date('Y-m-d') . '.xls"');
header('Cache-Control: max-age=0');

// Create Excel content
$excel_content = "Name\tEmail\tState\tDistrict\tFarm Type\tFarm Size (acres)\tRegistered On\n";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = $row['first_name'] . ' ' . $row['last_name'];
        $email = $row['email'];
        $state = $row['state'];
        $district = $row['district'];
        $farm_type = $row['farm_type'];
        $farm_size = $row['farm_size'];
        $created_at = date('M d, Y', strtotime($row['created_at']));

        // Add row to Excel content
        $excel_content .= "$name\t$email\t$state\t$district\t$farm_type\t$farm_size\t$created_at\n";
    }
}

// Output the Excel content
echo $excel_content;
exit;
?>
