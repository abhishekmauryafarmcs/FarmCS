<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
require_once 'config/db_connect.php';

try {
    // Start transaction
    $db->beginTransaction();

    // 1. Add new mobile column
    $db->exec("ALTER TABLE users ADD COLUMN mobile VARCHAR(10) UNIQUE");
    echo "Added mobile column successfully\n";

    // 2. Create temporary column for mobile numbers (if needed)
    $db->exec("UPDATE users SET mobile = CONCAT('1234567', LPAD(user_id, 3, '0')) WHERE mobile IS NULL");
    echo "Added temporary mobile numbers\n";

    // 3. Make mobile column NOT NULL
    $db->exec("ALTER TABLE users MODIFY COLUMN mobile VARCHAR(10) NOT NULL");
    echo "Made mobile column NOT NULL\n";

    // 4. Drop email column
    $db->exec("ALTER TABLE users DROP COLUMN email");
    echo "Dropped email column successfully\n";

    // 5. Add indexes
    $db->exec("ALTER TABLE users ADD UNIQUE INDEX idx_mobile (mobile)");
    echo "Added index on mobile column\n";

    // Commit transaction
    $db->commit();
    echo "Migration completed successfully!\n";

} catch (Exception $e) {
    // Rollback on error
    $db->rollBack();
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Verify the changes
try {
    $result = $db->query("DESCRIBE users");
    echo "\nCurrent table structure:\n";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " - " . $row['Type'] . " - " . $row['Null'] . " - " . $row['Key'] . "\n";
    }
} catch (Exception $e) {
    echo "Error verifying table structure: " . $e->getMessage() . "\n";
}
?>
