<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../config/Database.php');

try {
    $database = new Database();
    $db = $database->connect();

    // Get column information for users table
    $stmt = $db->prepare("SHOW COLUMNS FROM users");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Columns in users table:\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (Type: " . $column['Type'] . ")\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
