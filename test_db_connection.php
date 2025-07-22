<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Test</h2>";

// Test 1: Basic Connection
echo "<h3>Test 1: Basic Connection</h3>";
try {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'farmcs';

    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    echo "✅ Database connection successful!<br>";
    echo "Server info: " . $conn->server_info . "<br>";
    echo "Server version: " . $conn->server_version . "<br>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    exit();
}

// Test 2: Check Database Existence
echo "<h3>Test 2: Database Check</h3>";
try {
    $result = $conn->query("SELECT DATABASE()");
    $row = $result->fetch_row();
    echo "✅ Current database: " . $row[0] . "<br>";
} catch (Exception $e) {
    echo "❌ Error checking database: " . $e->getMessage() . "<br>";
}

// Test 3: Check Users Table
echo "<h3>Test 3: Users Table Check</h3>";
try {
    $result = $conn->query("DESCRIBE users");
    if ($result) {
        echo "✅ Users table exists<br>";
        echo "Table structure:<br><pre>";
        while ($row = $result->fetch_assoc()) {
            echo $row['Field'] . " - " . $row['Type'] . " - " . $row['Null'] . " - " . $row['Key'] . "<br>";
        }
        echo "</pre>";
    } else {
        echo "❌ Users table not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Error checking users table: " . $e->getMessage() . "<br>";
}

// Test 4: Check Table Permissions
echo "<h3>Test 4: Permission Check</h3>";
try {
    // Test SELECT
    $conn->query("SELECT 1 FROM users LIMIT 1");
    echo "✅ SELECT permission OK<br>";
    
    // Test INSERT (with rollback)
    $conn->begin_transaction();
    $conn->query("INSERT INTO users (mobile, password_hash, first_name, last_name, state, district, farm_type, farm_size) 
                 VALUES ('9999999999', 'test', 'Test', 'User', 'Test', 'Test', 'Test', 1)");
    echo "✅ INSERT permission OK<br>";
    $conn->rollback();
    
    // Test UPDATE (with rollback)
    $conn->begin_transaction();
    $conn->query("UPDATE users SET last_login = NOW() WHERE mobile = '9999999999'");
    echo "✅ UPDATE permission OK<br>";
    $conn->rollback();
    
    // Test DELETE (with rollback)
    $conn->begin_transaction();
    $conn->query("DELETE FROM users WHERE mobile = '9999999999'");
    echo "✅ DELETE permission OK<br>";
    $conn->rollback();
    
} catch (Exception $e) {
    echo "❌ Permission error: " . $e->getMessage() . "<br>";
}

// Test 5: Check Connection Speed
echo "<h3>Test 5: Performance Check</h3>";
try {
    $start = microtime(true);
    for($i = 0; $i < 100; $i++) {
        $conn->query("SELECT 1");
    }
    $time = number_format((microtime(true) - $start) * 1000, 2);
    echo "✅ 100 queries completed in {$time}ms<br>";
    echo "Average query time: " . number_format($time / 100, 2) . "ms<br>";
} catch (Exception $e) {
    echo "❌ Performance test error: " . $e->getMessage() . "<br>";
}

// Close connection
$conn->close();
echo "<br>Connection closed successfully";

// Add some basic styling
?>
<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        line-height: 1.6;
    }
    h2 {
        color: #2c3e50;
    }
    h3 {
        color: #34495e;
        margin-top: 20px;
    }
    pre {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
    }
</style> 