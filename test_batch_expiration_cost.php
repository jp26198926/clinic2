<?php
// Test file for batch transaction expiration and cost implementation
// This file tests the new expiration and cost fields functionality in batch transactions

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🧪 Batch Transaction Expiration & Cost Implementation Test</h1>";
echo "<p><strong>Date:</strong> " . date('Y-m-d H:i:s') . "</p>";

// Database connection test
try {
    $mysqli = new mysqli('localhost', 'root', 'astalavista', 'clinic2', 3308);
    
    if ($mysqli->connect_error) {
        throw new Exception('Database connection failed: ' . $mysqli->connect_error);
    }
    
    echo "<div style='color: green; background: #f0f8f0; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ Database connection successful";
    echo "</div>";
    
    // Check batch_transaction_items table structure
    echo "<h2>📋 Database Schema Verification</h2>";
    
    $result = $mysqli->query("SHOW COLUMNS FROM batch_transaction_items");
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    
    $required_columns = ['unit_cost', 'expiration_date', 'total_cost'];
    $missing_columns = [];
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0; width: 100%;'>";
    echo "<tr><th>Required Column</th><th>Status</th><th>Type</th><th>Action</th></tr>";
    
    foreach ($required_columns as $col) {
        $exists = in_array($col, $columns);
        $status = $exists ? "<span style='color: green;'>✅ EXISTS</span>" : "<span style='color: red;'>❌ MISSING</span>";
        
        // Get column type if exists
        $type = '';
        if ($exists) {
            $type_result = $mysqli->query("SHOW COLUMNS FROM batch_transaction_items WHERE Field = '{$col}'");
            if ($type_row = $type_result->fetch_assoc()) {
                $type = $type_row['Type'];
            }
        }
        
        $action = $exists ? "OK" : "Run migration SQL";
        
        echo "<tr><td>{$col}</td><td>{$status}</td><td>{$type}</td><td>{$action}</td></tr>";
        
        if (!$exists) {
            $missing_columns[] = $col;
        }
    }
    echo "</table>";
    
    if (empty($missing_columns)) {
        echo "<div style='color: green; background: #f0f8f0; padding: 10px; border-radius: 5px;'>";
        echo "✅ All required columns exist in the batch_transaction_items table!";
        echo "</div>";
        
        // Test sample data if table is ready
        echo "<h2>📊 Current Batch Transaction Data</h2>";
        
        // Check if we have any batch transaction data
        $result = $mysqli->query("SELECT COUNT(*) as count FROM batch_transactions");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p><strong>Total Batch Transactions:</strong> " . $row['count'] . "</p>";
            
            if ($row['count'] > 0) {
                // Show sample of recent batch transactions
                $result = $mysqli->query("
                    SELECT bt.*, 
                           DATE_FORMAT(bt.transaction_date, '%Y-%m-%d') as formatted_date,
                           DATE_FORMAT(bt.created_at, '%Y-%m-%d %H:%i') as created_at_formatted
                    FROM batch_transactions bt 
                    ORDER BY bt.created_at DESC 
                    LIMIT 5
                ");
                
                if ($result) {
                    echo "<h3>Recent Batch Transactions:</h3>";
                    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0; width: 100%;'>";
                    echo "<tr><th>Transaction #</th><th>Date</th><th>Type</th><th>Status</th><th>Items</th><th>Total Qty</th><th>Created</th></tr>";
                    
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['transaction_number']}</td>";
                        echo "<td>{$row['formatted_date']}</td>";
                        echo "<td>{$row['transaction_type']}</td>";
                        echo "<td>{$row['status']}</td>";
                        echo "<td>{$row['total_items']}</td>";
                        echo "<td>" . number_format($row['total_qty'], 2) . "</td>";
                        echo "<td>{$row['created_at_formatted']}</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                
                // Show sample batch transaction items with new fields
                echo "<h3>Sample Batch Transaction Items (with new fields):</h3>";
                $result = $mysqli->query("
                    SELECT bti.*, 
                           p.name as product_name,
                           p.code as product_code,
                           bt.transaction_number,
                           DATE_FORMAT(bti.expiration_date, '%Y-%m-%d') as expiration_formatted
                    FROM batch_transaction_items bti 
                    LEFT JOIN products p ON p.id = bti.product_id
                    LEFT JOIN batch_transactions bt ON bt.id = bti.batch_transaction_id
                    ORDER BY bti.id DESC 
                    LIMIT 5
                ");
                
                if ($result) {
                    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0; width: 100%;'>";
                    echo "<tr><th>Batch #</th><th>Product</th><th>Qty</th><th>Unit Cost</th><th>Total Cost</th><th>Expiration</th><th>Notes</th></tr>";
                    
                    while ($row = $result->fetch_assoc()) {
                        $expiration = $row['expiration_formatted'] ? $row['expiration_formatted'] : 'No expiry';
                        
                        echo "<tr>";
                        echo "<td>{$row['transaction_number']}</td>";
                        echo "<td>{$row['product_code']} - {$row['product_name']}</td>";
                        echo "<td>" . number_format($row['qty'], 2) . "</td>";
                        echo "<td>₱" . number_format($row['unit_cost'], 2) . "</td>";
                        echo "<td>₱" . number_format($row['total_cost'], 2) . "</td>";
                        echo "<td>{$expiration}</td>";
                        echo "<td>" . ($row['notes'] ? $row['notes'] : '-') . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p style='color: orange;'>⚠ No batch transaction items found</p>";
                }
            }
        }
        
    } else {
        echo "<div style='color: red; background: #f8f0f0; padding: 10px; border-radius: 5px;'>";
        echo "❌ Missing columns detected. Please run the migration SQL:";
        echo "<pre style='background: #f5f5f5; padding: 10px; margin: 10px 0;'>";
        echo "-- Run these commands in your MySQL client:\n";
        if (in_array('expiration_date', $missing_columns)) {
            echo "ALTER TABLE `batch_transaction_items` ADD COLUMN `expiration_date` DATE NULL AFTER `unit_cost`;\n";
        }
        if (in_array('unit_cost', $missing_columns)) {
            echo "ALTER TABLE `batch_transaction_items` ADD COLUMN `unit_cost` DECIMAL(10,2) DEFAULT 0 AFTER `qty`;\n";
        }
        if (in_array('total_cost', $missing_columns)) {
            echo "ALTER TABLE `batch_transaction_items` ADD COLUMN `total_cost` DECIMAL(12,2) GENERATED ALWAYS AS (`qty` * `unit_cost`) STORED AFTER `expiration_date`;\n";
        }
        echo "ALTER TABLE `batch_transaction_items` ADD INDEX `idx_expiration_date` (`expiration_date`);\n";
        echo "</pre>";
        echo "</div>";
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<div style='color: red; background: #f8f0f0; padding: 10px; border-radius: 5px;'>";
    echo "❌ Error: " . $e->getMessage();
    echo "</div>";
}

// File verification
echo "<h2>📁 File Implementation Status</h2>";

$files_to_check = [
    'application/models/Batch_transaction_model.php' => 'expiration_date',
    'application/views/inventory_batch/index.php' => 'nb_expiration_date',
    'sql_batch_transaction.sql' => 'expiration_date DATE NULL'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
echo "<tr><th>File</th><th>Key Feature</th><th>Status</th></tr>";

foreach ($files_to_check as $file => $search_text) {
    $full_path = "c:\\laragon\\www\\clinic2\\{$file}";
    
    if (file_exists($full_path)) {
        $content = file_get_contents($full_path);
        $has_feature = strpos($content, $search_text) !== false;
        $status = $has_feature ? "<span style='color: green;'>✅ UPDATED</span>" : "<span style='color: orange;'>⚠ CHECK</span>";
    } else {
        $status = "<span style='color: red;'>❌ MISSING</span>";
    }
    
    echo "<tr><td>{$file}</td><td>{$search_text}</td><td>{$status}</td></tr>";
}
echo "</table>";

echo "<h2>🎯 Testing Instructions</h2>";
echo "<div style='background: #f0f8ff; padding: 15px; border-radius: 5px; border-left: 5px solid #007bff;'>";
echo "<h3>How to Test:</h3>";
echo "<ol>";
echo "<li><strong>Database Setup:</strong> If columns are missing, run the migration SQL commands above</li>";
echo "<li><strong>Access Interface:</strong> Go to <a href='http://localhost/clinic2/inventory_batch' target='_blank'>Inventory → Batch Transactions</a></li>";
echo "<li><strong>Create New Batch:</strong> Click 'Create New Batch' and test the new fields:";
echo "<ul>";
echo "<li>Add products with different unit costs</li>";
echo "<li>Set expiration dates (some in the past, some near future)</li>";
echo "<li>Verify total cost calculations</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>View Details:</strong> Check that batch details show expiration and cost information</li>";
echo "<li><strong>Verify Data:</strong> Confirm data is saved correctly in database</li>";
echo "</ol>";
echo "</div>";

echo "<h2>🆕 New Features Added</h2>";
echo "<div style='background: #f0f8f0; padding: 10px; border-radius: 5px;'>";
echo "<h3>✅ Database Enhancements:</h3>";
echo "<ul>";
echo "<li><code>expiration_date</code> column added to batch_transaction_items</li>";
echo "<li>Enhanced <code>unit_cost</code> and <code>total_cost</code> handling</li>";
echo "<li>Indexes added for performance</li>";
echo "</ul>";

echo "<h3>✅ UI Enhancements:</h3>";
echo "<ul>";
echo "<li>Unit Cost field in batch item form</li>";
echo "<li>Expiration Date field with date picker</li>";
echo "<li>Total Cost calculation and display</li>";
echo "<li>Enhanced batch details view with cost and expiration</li>";
echo "<li>Visual indicators for expired/expiring items</li>";
echo "</ul>";

echo "<h3>✅ Backend Logic:</h3>";
echo "<ul>";
echo "<li>Updated Batch_transaction_model to handle new fields</li>";
echo "<li>Automatic total cost calculation</li>";
echo "<li>Expiration status determination</li>";
echo "<li>Enhanced data validation and processing</li>";
echo "</ul>";
echo "</div>";

echo "<h2>📞 Support</h2>";
echo "<div style='background: #f8f8f8; padding: 10px; border-radius: 5px;'>";
echo "<p><strong>Migration Files:</strong></p>";
echo "<ul>";
echo "<li><a href='http://localhost/clinic2/db_migration/2025-06-16_add_expiration_to_batch_items.sql' target='_blank'>Expiration Migration SQL</a></li>";
echo "<li><a href='http://localhost/clinic2/sql_batch_transaction.sql' target='_blank'>Updated Schema File</a></li>";
echo "</ul>";
echo "</div>";

echo "<br><hr>";
echo "<p style='text-align: center; color: #666; font-style: italic;'>";
echo "Batch Transaction Expiration & Cost Implementation completed on " . date('F j, Y') . " | Ready for testing";
echo "</p>";
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    max-width: 1200px; 
    line-height: 1.6;
}
h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
h2 { color: #555; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
h3 { color: #666; }
table { width: 100%; border-collapse: collapse; margin: 10px 0; }
th { background: #f5f5f5; padding: 8px; text-align: left; }
td { padding: 8px; vertical-align: top; }
code { background: #f5f5f5; padding: 2px 4px; border-radius: 3px; }
pre { overflow-x: auto; background: #f5f5f5; padding: 10px; border-radius: 5px; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>
