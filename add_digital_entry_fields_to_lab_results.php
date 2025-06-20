<?php
// Add Digital Entry Fields to Lab Results Table
// Run this script to add fields for digital laboratory result entries

// Database configuration
$host = 'localhost:3308';
$username = 'root';
$password = 'astalavista';
$database = 'clinic2';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute the SQL migration
    $sql = file_get_contents(__DIR__ . '/db_migration/2025-06-20_add_digital_entry_fields_to_lab_results.sql');
    
    // Execute the SQL
    $pdo->exec($sql);
    
    echo "Digital entry fields added to lab_results table successfully!\n";
    echo "Migration completed: 2025-06-20_add_digital_entry_fields_to_lab_results.sql\n";
    
} catch (PDOException $e) {
    echo "Error adding digital entry fields: " . $e->getMessage() . "\n";
    echo "Please check your database configuration and try again.\n";
}
?>
