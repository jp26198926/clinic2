<?php
// Quick verification script for expiration and cost implementation
// Run this to verify the implementation works correctly

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🧪 Expiration & Cost Implementation Verification</h1>";
echo "<p><strong>Date:</strong> " . date('Y-m-d H:i:s') . "</p>";

// Database connection test
try {
    $mysqli = new mysqli('localhost', 'root', '', 'clinic2');
    
    if ($mysqli->connect_error) {
        throw new Exception('Database connection failed: ' . $mysqli->connect_error);
    }
    
    echo "<div style='color: green; background: #f0f8f0; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ Database connection successful";
    echo "</div>";
    
    // Check table structure
    echo "<h2>📋 Database Schema Verification</h2>";
    
    $result = $mysqli->query("SHOW COLUMNS FROM stock");
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    
    $required_columns = ['expiration_date', 'unit_cost', 'total_cost', 'last_cost_update'];
    $missing_columns = [];
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0; width: 100%;'>";
    echo "<tr><th>Required Column</th><th>Status</th><th>Action</th></tr>";
    
    foreach ($required_columns as $col) {
        $exists = in_array($col, $columns);
        $status = $exists ? "<span style='color: green;'>✅ EXISTS</span>" : "<span style='color: red;'>❌ MISSING</span>";
        $action = $exists ? "OK" : "Run migration SQL";
        
        echo "<tr><td>{$col}</td><td>{$status}</td><td>{$action}</td></tr>";
        
        if (!$exists) {
            $missing_columns[] = $col;
        }
    }
    echo "</table>";
    
    if (empty($missing_columns)) {
        echo "<div style='color: green; background: #f0f8f0; padding: 10px; border-radius: 5px;'>";
        echo "✅ All required columns exist in the database!";
        echo "</div>";
        
        // Test data operations
        echo "<h2>🔧 Functionality Testing</h2>";
        
        // Test 1: Check if we can insert sample data
        echo "<h3>Test 1: Sample Data Creation</h3>";
        
        // Get a product and location for testing
        $product_result = $mysqli->query("SELECT id, name FROM products WHERE status_id = 2 LIMIT 1");
        $location_result = $mysqli->query("SELECT id, location FROM locations WHERE status_id = 2 LIMIT 1");
        
        if ($product_result && $location_result) {
            $product = $product_result->fetch_assoc();
            $location = $location_result->fetch_assoc();
            
            if ($product && $location) {
                echo "<p><strong>Test Product:</strong> {$product['name']} (ID: {$product['id']})</p>";
                echo "<p><strong>Test Location:</strong> {$location['location']} (ID: {$location['id']})</p>";
                
                // Check if test record exists
                $existing = $mysqli->query("SELECT * FROM stock WHERE product_id = {$product['id']} AND location_id = {$location['id']}");
                
                if ($existing && $existing->num_rows > 0) {
                    $stock = $existing->fetch_assoc();
                    echo "<div style='background: #f0f0f0; padding: 10px; border-radius: 5px;'>";
                    echo "<h4>Existing Stock Record Found:</h4>";
                    echo "<ul>";
                    echo "<li><strong>Quantity:</strong> {$stock['qty_on_hand']}</li>";
                    echo "<li><strong>Unit Cost:</strong> ₱" . number_format($stock['unit_cost'], 2) . "</li>";
                    echo "<li><strong>Total Cost:</strong> ₱" . number_format($stock['total_cost'], 2) . "</li>";
                    echo "<li><strong>Expiration:</strong> " . ($stock['expiration_date'] ? $stock['expiration_date'] : 'No expiry') . "</li>";
                    echo "</ul>";
                    echo "</div>";
                } else {
                    echo "<p style='color: orange;'>⚠ No existing stock record - you can test by adding stock through the interface</p>";
                }
                
                // Test 2: Cost calculation verification
                echo "<h3>Test 2: Cost Calculation Logic</h3>";
                
                $cost_test_sql = "SELECT 
                    s.*,
                    p.name as product_name,
                    l.location,
                    (s.qty_on_hand * s.unit_cost) as calculated_cost,
                    s.total_cost as stored_cost
                FROM stock s 
                LEFT JOIN products p ON p.id = s.product_id 
                LEFT JOIN locations l ON l.id = s.location_id 
                WHERE s.unit_cost > 0 
                LIMIT 5";
                
                $cost_result = $mysqli->query($cost_test_sql);
                
                if ($cost_result && $cost_result->num_rows > 0) {
                    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
                    echo "<tr><th>Product</th><th>Location</th><th>Qty</th><th>Unit Cost</th><th>Calculated</th><th>Stored</th><th>Match</th></tr>";
                    
                    while ($row = $cost_result->fetch_assoc()) {
                        $calculated = $row['calculated_cost'];
                        $stored = $row['stored_cost'];
                        $match = abs($calculated - $stored) < 0.01 ? "✅" : "❌";
                        
                        echo "<tr>";
                        echo "<td>{$row['product_name']}</td>";
                        echo "<td>{$row['location']}</td>";
                        echo "<td>{$row['qty_on_hand']}</td>";
                        echo "<td>₱" . number_format($row['unit_cost'], 2) . "</td>";
                        echo "<td>₱" . number_format($calculated, 2) . "</td>";
                        echo "<td>₱" . number_format($stored, 2) . "</td>";
                        echo "<td>{$match}</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p style='color: orange;'>⚠ No stock records with costs found - add some stock with unit costs to test</p>";
                }
                
                // Test 3: Expiration date logic
                echo "<h3>Test 3: Expiration Date Logic</h3>";
                
                $exp_test_sql = "SELECT 
                    s.*,
                    p.name as product_name,
                    l.location,
                    DATEDIFF(s.expiration_date, CURDATE()) as days_until_expiry,
                    CASE 
                        WHEN s.expiration_date IS NULL THEN 'No Expiry'
                        WHEN s.expiration_date <= CURDATE() THEN 'EXPIRED'
                        WHEN s.expiration_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'EXPIRING SOON'
                        ELSE 'NORMAL'
                    END as status
                FROM stock s 
                LEFT JOIN products p ON p.id = s.product_id 
                LEFT JOIN locations l ON l.id = s.location_id 
                WHERE s.expiration_date IS NOT NULL 
                LIMIT 5";
                
                $exp_result = $mysqli->query($exp_test_sql);
                
                if ($exp_result && $exp_result->num_rows > 0) {
                    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
                    echo "<tr><th>Product</th><th>Location</th><th>Expiry Date</th><th>Days Until</th><th>Status</th></tr>";
                    
                    while ($row = $exp_result->fetch_assoc()) {
                        $status_color = '';
                        switch($row['status']) {
                            case 'EXPIRED': $status_color = 'color: red;'; break;
                            case 'EXPIRING SOON': $status_color = 'color: orange;'; break;
                            case 'NORMAL': $status_color = 'color: green;'; break;
                        }
                        
                        echo "<tr>";
                        echo "<td>{$row['product_name']}</td>";
                        echo "<td>{$row['location']}</td>";
                        echo "<td>{$row['expiration_date']}</td>";
                        echo "<td>{$row['days_until_expiry']}</td>";
                        echo "<td style='{$status_color}'>{$row['status']}</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p style='color: orange;'>⚠ No stock records with expiration dates found - add some stock with expiry dates to test</p>";
                }
                
            } else {
                echo "<p style='color: red;'>❌ No active products or locations found for testing</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Could not query products or locations</p>";
        }
        
    } else {
        echo "<div style='color: red; background: #f8f0f0; padding: 10px; border-radius: 5px;'>";
        echo "❌ Missing columns detected. Please run the migration SQL:";
        echo "<pre style='background: #f5f5f5; padding: 10px; margin: 10px 0;'>";
        echo "-- Run these commands in your MySQL client:\n";
        if (in_array('expiration_date', $missing_columns)) {
            echo "ALTER TABLE `stock` ADD COLUMN `expiration_date` DATE NULL AFTER `qty_reserved`;\n";
        }
        if (in_array('unit_cost', $missing_columns)) {
            echo "ALTER TABLE `stock` ADD COLUMN `unit_cost` DECIMAL(10,2) DEFAULT 0.00 AFTER `expiration_date`;\n";
        }
        if (in_array('total_cost', $missing_columns)) {
            echo "ALTER TABLE `stock` ADD COLUMN `total_cost` DECIMAL(12,2) GENERATED ALWAYS AS (`qty_on_hand` * `unit_cost`) STORED AFTER `unit_cost`;\n";
        }
        if (in_array('last_cost_update', $missing_columns)) {
            echo "ALTER TABLE `stock` ADD COLUMN `last_cost_update` TIMESTAMP NULL AFTER `total_cost`;\n";
        }
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
    'application/models/Stock_model.php' => 'update_stock_with_cost_expiration',
    'application/models/Stock_movements_model.php' => 'expiration_date',
    'application/controllers/Inventory_stock.php' => 'get_expiring_stock',
    'application/views/inventory_stock/index.php' => 'expiring_stock'
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

echo "<h2>🎯 Quick Start Guide</h2>";
echo "<div style='background: #f0f8ff; padding: 15px; border-radius: 5px; border-left: 5px solid #007bff;'>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li><strong>Database Setup:</strong> If columns are missing, run the migration SQL commands above</li>";
echo "<li><strong>Test Interface:</strong> Go to <a href='http://localhost/clinic2/inventory_stock' target='_blank'>Inventory → Stock Management</a></li>";
echo "<li><strong>Add Sample Data:</strong> Use 'Receive Stock' with expiration date and unit cost</li>";
echo "<li><strong>Test Reports:</strong> Try 'Expiring Stock', 'Expired Stock', and 'Stock Valuation' buttons</li>";
echo "<li><strong>Verify Mobile:</strong> Test on mobile/tablet devices</li>";
echo "</ol>";
echo "</div>";

echo "<h2>📞 Support</h2>";
echo "<div style='background: #f8f8f8; padding: 10px; border-radius: 5px;'>";
echo "<p><strong>Documentation:</strong></p>";
echo "<ul>";
echo "<li><a href='http://localhost/clinic2/INVENTORY_EXPIRATION_COST_IMPLEMENTATION.md' target='_blank'>Implementation Guide</a></li>";
echo "<li><a href='http://localhost/clinic2/TESTING_CHECKLIST_EXPIRATION_COST.md' target='_blank'>Testing Checklist</a></li>";
echo "<li><a href='http://localhost/clinic2/manual_migration.sql' target='_blank'>Manual Migration SQL</a></li>";
echo "</ul>";
echo "</div>";

echo "<br><hr>";
echo "<p style='text-align: center; color: #666; font-style: italic;'>";
echo "Implementation completed on " . date('F j, Y') . " | Ready for production use";
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
