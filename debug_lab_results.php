<?php
// Debug Lab Results
// This file helps debug lab results functionality

require_once 'application/config/database.php';

// Create database connection
$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

echo "<h2>Lab Results Debug Information</h2>";

// Check if lab_results table exists
$result = $mysqli->query("SHOW TABLES LIKE 'lab_results'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✓ lab_results table exists</p>";
    
    // Show table structure
    $structure = $mysqli->query("DESCRIBE lab_results");
    echo "<h3>Table Structure:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $structure->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Count total records
    $count = $mysqli->query("SELECT COUNT(*) as total FROM lab_results");
    $total = $count->fetch_assoc()['total'];
    echo "<h3>Total Records: " . $total . "</h3>";
    
    // Show recent records if any
    if ($total > 0) {
        $recent = $mysqli->query("SELECT * FROM lab_results ORDER BY created_at DESC LIMIT 10");
        echo "<h3>Recent Records:</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Transaction ID</th><th>Item ID</th><th>Entry Type</th><th>Test Name</th><th>Test Date</th><th>Created At</th><th>Created By</th></tr>";
        while ($row = $recent->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['transaction_id'] . "</td>";
            echo "<td>" . $row['item_id'] . "</td>";
            echo "<td>" . ($row['entry_type'] ?? 'NULL') . "</td>";
            echo "<td>" . $row['test_name'] . "</td>";
            echo "<td>" . $row['test_date'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "<td>" . $row['created_by'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} else {
    echo "<p style='color: red;'>✗ lab_results table does not exist</p>";
}

// Check logs directory
$log_dir = 'application/logs';
if (is_dir($log_dir)) {
    echo "<h3>Log Files:</h3>";
    $files = scandir($log_dir);
    foreach ($files as $file) {
        if (strpos($file, '.php') !== false) {
            $filepath = $log_dir . '/' . $file;
            echo "<p><strong>" . $file . "</strong> (Size: " . filesize($filepath) . " bytes, Modified: " . date('Y-m-d H:i:s', filemtime($filepath)) . ")</p>";
        }
    }
} else {
    echo "<p style='color: red;'>✗ Logs directory not found</p>";
}

$mysqli->close();
?>
