<?php
// Detailed table schema inspection
session_start();
include 'config/connection.php';

// Check if user is an admin (recommended for security)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Unauthorized access. Admin rights required.");
}

// Fetch full table schema
$query = "SHOW FULL COLUMNS FROM users";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users Table Schema</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 1200px; 
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
    </style>
</head>
<body>
    <h1>Users Table Schema</h1>
    <table>
        <thead>
            <tr>
                <th>Field</th>
                <th>Type</th>
                <th>Null</th>
                <th>Key</th>
                <th>Default</th>
                <th>Extra</th>
                <th>Collation</th>
                <th>Privileges</th>
                <th>Comment</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['Field']); ?></td>
                <td><?php echo htmlspecialchars($row['Type']); ?></td>
                <td><?php echo htmlspecialchars($row['Null']); ?></td>
                <td><?php echo htmlspecialchars($row['Key']); ?></td>
                <td><?php echo htmlspecialchars($row['Default'] ?? 'NULL'); ?></td>
                <td><?php echo htmlspecialchars($row['Extra']); ?></td>
                <td><?php echo htmlspecialchars($row['Collation'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['Privileges']); ?></td>
                <td><?php echo htmlspecialchars($row['Comment'] ?? ''); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

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
