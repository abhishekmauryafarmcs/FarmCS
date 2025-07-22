<?php
// Comprehensive database schema diagnostic script
session_start();
include 'config/connection.php';

// Function to get table columns
function getTableColumns($conn, $table) {
    $columns = [];
    $result = $conn->query("SHOW COLUMNS FROM `$table`");
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
    }
    
    return $columns;
}

// Get table columns
$table_columns = getTableColumns($conn, 'users');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users Table Schema</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 800px; 
            margin: 0 auto; 
            padding: 20px; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        .column-list {
            background-color: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Users Table Schema</h1>
    
    <div class="column-list">
        <h2>Existing Columns:</h2>
        <ul>
            <?php 
            if (!empty($table_columns)) {
                foreach ($table_columns as $column) {
                    echo "<li>" . htmlspecialchars($column) . "</li>";
                }
            } else {
                echo "<li>No columns found. Table might be empty or not exist.</li>";
            }
            ?>
        </ul>
    </div>

    <h2>Recommended Actions</h2>
    <ol>
        <li>Verify the table name is correct</li>
        <li>Check database connection settings</li>
        <li>Ensure the users table exists</li>
    </ol>

    <h2>Database Connection Details</h2>
    <pre>
    <?php
    print_r([
        'Host' => $conn->host_info,
        'Database' => $conn->query("SELECT DATABASE()")->fetch_array()[0],
        'Server Version' => $conn->server_info
    ]);
    ?>
    </pre>
</body>
</html>
<?php
$conn->close();
?>
