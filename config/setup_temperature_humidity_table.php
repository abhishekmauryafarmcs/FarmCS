<?php
// Enable comprehensive error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once 'database.php';

try {
    // Create database connection
    $database = new Database();
    $conn = $database->connect();

    // Read SQL script
    $sqlScript = file_get_contents('create_temperature_humidity_table.sql');

    // Split the script into individual statements
    $statements = explode(';', $sqlScript);

    // Execute each statement
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $conn->exec($statement);
            echo "Executed: " . substr($statement, 0, 100) . "...<br>";
        }
    }

    echo "Database setup completed successfully!";
} catch (PDOException $e) {
    // Handle any database-related errors
    echo "Error: " . $e->getMessage();
}

// Close the connection
$database->closeConnection();
?>
