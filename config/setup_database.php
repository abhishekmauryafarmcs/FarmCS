<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root';  // Default XAMPP MySQL username
$db_pass = '';      // Default XAMPP MySQL password

// Create connection without database
$conn = new mysqli($db_host, $db_user, $db_pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

try {
    // Read and execute the SQL file
    $sql = file_get_contents('create_database.sql');
    
    if ($conn->multi_query($sql)) {
        do {
            // Store first result set
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        
        echo "Database setup completed successfully!<br>";
        echo "<a href='../admin/setup_admin.php'>Continue to Admin Setup</a>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?> 