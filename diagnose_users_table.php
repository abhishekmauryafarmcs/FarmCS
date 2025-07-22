<?php
// Comprehensive database schema diagnostic script
session_start();
include 'config/connection.php';

// Function to check if a column exists
function columnExists($conn, $table, $column) {
    $query = "SHOW COLUMNS FROM `$table` LIKE '$column'";
    $result = $conn->query($query);
    return $result->num_rows > 0;
}

// Columns to add
$columnsToAdd = [
    'profile_picture' => "VARCHAR(255) DEFAULT NULL",
    'first_name' => "VARCHAR(50) DEFAULT NULL",
    'last_name' => "VARCHAR(50) DEFAULT NULL",
    'language' => "VARCHAR(10) DEFAULT 'en'",
    'email_notifications' => "TINYINT(1) DEFAULT 1",
    'sms_notifications' => "TINYINT(1) DEFAULT 0",
    'theme' => "VARCHAR(20) DEFAULT 'light'"
];

// Results array
$results = [];

// Check and add missing columns
foreach ($columnsToAdd as $column => $definition) {
    if (!columnExists($conn, 'users', $column)) {
        $addColumnQuery = "ALTER TABLE `users` ADD COLUMN `$column` $definition";
        
        try {
            if ($conn->query($addColumnQuery) === TRUE) {
                $results[] = [
                    'column' => $column,
                    'status' => 'Added Successfully',
                    'query' => $addColumnQuery
                ];
            } else {
                $results[] = [
                    'column' => $column,
                    'status' => 'Failed to Add',
                    'error' => $conn->error,
                    'query' => $addColumnQuery
                ];
            }
        } catch (Exception $e) {
            $results[] = [
                'column' => $column,
                'status' => 'Exception',
                'error' => $e->getMessage(),
                'query' => $addColumnQuery
            ];
        }
    } else {
        $results[] = [
            'column' => $column,
            'status' => 'Already Exists'
        ];
    }
}

// Update existing users with default values
$updateQuery = "
    UPDATE `users` 
    SET 
        `first_name` = COALESCE(`first_name`, SUBSTRING_INDEX(`email`, '@', 1)),
        `last_name` = COALESCE(`last_name`, ''),
        `language` = COALESCE(`language`, 'en'),
        `email_notifications` = COALESCE(`email_notifications`, 1),
        `sms_notifications` = COALESCE(`sms_notifications`, 0),
        `theme` = COALESCE(`theme`, 'light')
";

try {
    if ($conn->query($updateQuery) === TRUE) {
        $results[] = [
            'column' => 'Default Values',
            'status' => 'Updated Successfully',
            'query' => $updateQuery
        ];
    } else {
        $results[] = [
            'column' => 'Default Values',
            'status' => 'Update Failed',
            'error' => $conn->error,
            'query' => $updateQuery
        ];
    }
} catch (Exception $e) {
    $results[] = [
        'column' => 'Default Values',
        'status' => 'Update Exception',
        'error' => $e->getMessage(),
        'query' => $updateQuery
    ];
}

// Create profile pictures directory
$profile_pic_dir = 'uploads/profile_pictures';
if (!file_exists($profile_pic_dir)) {
    mkdir($profile_pic_dir, 0777, true);
}

// Display results
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Schema Diagnostic</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 1000px; 
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
        th { 
            background-color: #f2f2f2; 
        }
        .success { 
            color: green; 
            font-weight: bold; 
        }
        .error { 
            color: red; 
            font-weight: bold; 
        }
        .warning { 
            color: orange; 
            font-weight: bold; 
        }
    </style>
</head>
<body>
    <h1>Database Schema Diagnostic</h1>
    
    <table>
        <thead>
            <tr>
                <th>Column</th>
                <th>Status</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $result): ?>
            <tr>
                <td><?php echo htmlspecialchars($result['column']); ?></td>
                <td class="<?php 
                    echo strtolower(str_replace(' ', '-', $result['status'])); 
                ?>">
                    <?php echo htmlspecialchars($result['status']); ?>
                </td>
                <td>
                    <?php 
                    if (isset($result['query'])) {
                        echo "Query: " . htmlspecialchars($result['query']);
                    }
                    if (isset($result['error'])) {
                        echo "<br>Error: " . htmlspecialchars($result['error']);
                    }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Troubleshooting Tips</h2>
    <ul>
        <li>Ensure your database user has ALTER TABLE permissions</li>
        <li>Check for any database-level constraints</li>
        <li>Verify the table name is exactly 'users'</li>
        <li>Make sure you're connected to the correct database</li>
    </ul>
</body>
</html>
<?php
// Close database connection
$conn->close();
?>
