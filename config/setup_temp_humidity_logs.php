<?php
require_once 'db_config.php';

try {
    // Read SQL script
    $sqlScript = file_get_contents(__DIR__ . '/create_temperature_humidity_logs.sql');
    
    if ($sqlScript === false) {
        throw new Exception("Error reading the SQL file.");
    }

    // Execute the SQL command
    if ($conn->multi_query($sqlScript)) {
        echo "Temperature and humidity logs table created successfully\n";
    } else {
        throw new Exception("Error creating table: " . $conn->error);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

$conn->close();
?>
