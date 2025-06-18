<?php
// Simple test script to run the batch transaction SQL migration

// Database configuration (adjust as needed)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinic_db";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully to database: $dbname\n\n";
    
    // Read and execute the SQL file
    $sql_file = __DIR__ . '/sql_batch_transaction.sql';
    
    if (file_exists($sql_file)) {
        $sql = file_get_contents($sql_file);
        
        // Split SQL statements (simple split by semicolon)
        $statements = explode(';', $sql);
        
        echo "Executing batch transaction migration...\n";
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && !preg_match('/^--/', $statement)) {
                try {
                    $pdo->exec($statement);
                    echo "✓ Executed: " . substr($statement, 0, 50) . "...\n";
                } catch (PDOException $e) {
                    echo "⚠ Warning: " . $e->getMessage() . "\n";
                }
            }
        }
        
        echo "\n✅ Migration completed!\n";
        
        // Test if tables were created
        $tables = ['batch_transactions', 'batch_transaction_items'];
        echo "\nChecking created tables:\n";
        
        foreach ($tables as $table) {
            try {
                $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
                if ($stmt->rowCount() > 0) {
                    echo "✓ Table '$table' exists\n";
                } else {
                    echo "✗ Table '$table' not found\n";
                }
            } catch (PDOException $e) {
                echo "✗ Error checking table '$table': " . $e->getMessage() . "\n";
            }
        }
        
    } else {
        echo "SQL file not found: $sql_file\n";
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    echo "Please check your database configuration.\n";
}
?>
