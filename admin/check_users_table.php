<?php
require_once dirname(__DIR__) . '/config/db_connect.php';

// Check users table structure
$result = $conn->query("DESCRIBE users");

if ($result) {
    echo "Users Table Columns:\n";
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error describing users table: " . $conn->error . "\n";
}

$conn->close();
?>
