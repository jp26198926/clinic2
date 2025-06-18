<?php
// Test file for expiration and cost implementation
// This file tests the new expiration and cost fields functionality

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the CodeIgniter index to bootstrap the framework
define('ENVIRONMENT', 'development');
$_SERVER['REQUEST_URI'] = '/test_expiration_cost.php';

// Simple test to check if we can connect to database
try {
    $mysqli = new mysqli('localhost', 'root', '', 'clinic2');
    
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }
    
    echo "<h1>Database Connection Test</h1>";
    echo "<p style='color: green;'>✓ Successfully connected to database</p>";
    
    // Check if stock table exists and has new columns
    echo "<h2>Table Structure Check</h2>";
    
    $result = $mysqli->query("DESCRIBE stock");
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        $has_expiration = false;
        $has_unit_cost = false;
        $has_total_cost = false;
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>{$row['Key']}</td>";
            echo "<td>{$row['Default']}</td>";
            echo "<td>{$row['Extra']}</td>";
            echo "</tr>";
            
            if ($row['Field'] == 'expiration_date') $has_expiration = true;
            if ($row['Field'] == 'unit_cost') $has_unit_cost = true;
            if ($row['Field'] == 'total_cost') $has_total_cost = true;
        }
        echo "</table>";
        
        echo "<h3>New Fields Status:</h3>";
        echo "<p style='color: " . ($has_expiration ? "green" : "red") . ";'>" . 
             ($has_expiration ? "✓" : "✗") . " expiration_date field</p>";
        echo "<p style='color: " . ($has_unit_cost ? "green" : "red") . ";'>" . 
             ($has_unit_cost ? "✓" : "✗") . " unit_cost field</p>";
        echo "<p style='color: " . ($has_total_cost ? "green" : "red") . ";'>" . 
             ($has_total_cost ? "✓" : "✗") . " total_cost field</p>";
             
        if (!$has_expiration || !$has_unit_cost || !$has_total_cost) {
            echo "<h3>SQL to run:</h3>";
            echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
            echo "-- Add missing columns to stock table\n";
            if (!$has_expiration) {
                echo "ALTER TABLE `stock` ADD COLUMN `expiration_date` DATE NULL AFTER `qty_reserved`;\n";
            }
            if (!$has_unit_cost) {
                echo "ALTER TABLE `stock` ADD COLUMN `unit_cost` DECIMAL(10,2) DEFAULT 0.00 AFTER `expiration_date`;\n";
            }
            if (!$has_total_cost) {
                echo "ALTER TABLE `stock` ADD COLUMN `total_cost` DECIMAL(12,2) GENERATED ALWAYS AS (`qty_on_hand` * `unit_cost`) STORED AFTER `unit_cost`;\n";
            }
            echo "ALTER TABLE `stock` ADD COLUMN `last_cost_update` TIMESTAMP NULL AFTER `total_cost`;\n";
            echo "ALTER TABLE `stock` ADD INDEX `idx_expiration_date` (`expiration_date`);\n";
            echo "ALTER TABLE `stock` ADD INDEX `idx_unit_cost` (`unit_cost`);\n";
            echo "</pre>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ Error getting table structure: " . $mysqli->error . "</p>";
    }
    
    // Test sample data if table is ready
    if ($has_expiration && $has_unit_cost && $has_total_cost) {
        echo "<h2>Sample Data Test</h2>";
        
        // Check if we have any stock data
        $result = $mysqli->query("SELECT COUNT(*) as count FROM stock");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p>Current stock records: " . $row['count'] . "</p>";
            
            if ($row['count'] > 0) {
                // Show sample of updated records
                $result = $mysqli->query("SELECT s.*, p.name as product_name, l.location FROM stock s 
                                        LEFT JOIN products p ON p.id = s.product_id 
                                        LEFT JOIN locations l ON l.id = s.location_id 
                                        LIMIT 5");
                if ($result) {
                    echo "<h3>Sample Stock Records:</h3>";
                    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
                    echo "<tr><th>Product</th><th>Location</th><th>Qty</th><th>Unit Cost</th><th>Total Cost</th><th>Expiration</th></tr>";
                    
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['product_name']}</td>";
                        echo "<td>{$row['location']}</td>";
                        echo "<td>{$row['qty_on_hand']}</td>";
                        echo "<td>₱" . number_format($row['unit_cost'], 2) . "</td>";
                        echo "<td>₱" . number_format($row['total_cost'], 2) . "</td>";
                        echo "<td>" . ($row['expiration_date'] ? $row['expiration_date'] : 'No expiry') . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            }
        }
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<h2>Implementation Status</h2>";
echo "<h3>✓ Completed:</h3>";
echo "<ul>";
echo "<li>Database migration script created</li>";
echo "<li>Stock_model updated with new methods for expiration and cost tracking</li>";
echo "<li>Stock_movements_model updated to handle expiration dates</li>";
echo "<li>Inventory_stock controller updated with new endpoints</li>";
echo "<li>UI updated with new columns and fields</li>";
echo "<li>JavaScript functions added for new reports</li>";
echo "</ul>";

echo "<h3>⚠ Next Steps:</h3>";
echo "<ul>";
echo "<li>Run the database migration manually if columns are missing</li>";
echo "<li>Test the new functionality in the inventory stock interface</li>";
echo "<li>Add sample data with expiration dates and costs</li>";
echo "<li>Test expiration alerts and cost calculations</li>";
echo "</ul>";

echo "<h3>🔗 Key Files Modified:</h3>";
echo "<ul>";
echo "<li><code>db_migration/2025-06-16_add_expiration_cost_to_stock.sql</code> - Database schema changes</li>";
echo "<li><code>application/models/Stock_model.php</code> - Added cost and expiration methods</li>";
echo "<li><code>application/models/Stock_movements_model.php</code> - Updated to handle expiration</li>";
echo "<li><code>application/controllers/Inventory_stock.php</code> - New report endpoints</li>";
echo "<li><code>application/views/inventory_stock/index.php</code> - UI enhancements</li>";
echo "</ul>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1 { color: #333; }
h2 { color: #666; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
table { width: 100%; max-width: 800px; }
th { background: #f5f5f5; padding: 8px; }
td { padding: 8px; }
code { background: #f5f5f5; padding: 2px 4px; border-radius: 3px; }
pre { overflow-x: auto; }
</style>
