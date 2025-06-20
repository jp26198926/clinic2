<?php
// Add is_allow_upload field to products table
// Run this script once to add the new field

// Database configuration - update these with your actual database settings
$host = 'localhost';
$username = 'root';  // Update with your DB username
$password = 'astalavista';      // Update with your DB password  
$database = 'clinic2'; // Update with your DB name
$port = 3308;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute the SQL migration
    $sql = file_get_contents(__DIR__ . '/db_migration/2025-06-20_add_is_allow_upload_to_products.sql');
    
    // Execute the SQL
    $pdo->exec($sql);
    
    echo "Products table updated successfully!\n";
    echo "Added 'is_allow_upload' field to products table.\n";
    echo "Migration completed: 2025-06-20_add_is_allow_upload_to_products.sql\n";
    
} catch (PDOException $e) {
    echo "Error updating products table: " . $e->getMessage() . "\n";
    echo "Please check your database configuration and try again.\n";
}
?>
