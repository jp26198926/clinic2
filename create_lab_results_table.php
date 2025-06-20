<?php
// Lab Results Table Creation Script
// Run this script once to create the lab_results table

// Include CodeIgniter if running standalone
// require_once dirname(__FILE__) . '/index.php';

// Database configuration - update these with your actual database settings
$host = 'localhost:3308';
$username = 'root';  // Update with your DB username
$password = 'astalavista';      // Update with your DB password  
$database = 'clinic2'; // Update with your DB name

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute the SQL migration
    $sql = file_get_contents(__DIR__ . '/db_migration/2025-06-20_create_lab_results_table.sql');
    
    // Execute the SQL
    $pdo->exec($sql);
    
    echo "Lab results table created successfully!\n";
    echo "Migration completed: 2025-06-20_create_lab_results_table.sql\n";
    
} catch (PDOException $e) {
    echo "Error creating lab results table: " . $e->getMessage() . "\n";
    echo "Please check your database configuration and try again.\n";
}
?>
