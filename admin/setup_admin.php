<?php
require_once '../config/db_config.php';

try {
    // Read the SQL file
    $sql = file_get_contents('setup_admin.sql');

    // Execute the SQL commands
    if ($conn->multi_query($sql)) {
        do {
            // Store first result set
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
    }

    echo "Admin setup completed successfully!<br>";
    echo "You can now login with:<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "<br><a href='admin_login.php'>Go to Admin Login</a>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?> 