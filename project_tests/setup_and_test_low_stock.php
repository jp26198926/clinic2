<?php
// Test and setup Low Stock Report
include_once('application/config/database.php');

// Database connection
$host = $db['default']['hostname'];
$username = $db['default']['username'];
$password = $db['default']['password'];
$database = $db['default']['database'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Low Stock Report Setup and Test</h2>";
    
    // First, check current data
    echo "<h3>Current Database Status:</h3>";
    
    // Check products with reorder levels
    $reorder_sql = "SELECT COUNT(*) as total FROM products WHERE reorder_level > 0 AND status_id = 2";
    $reorder_stmt = $pdo->query($reorder_sql);
    $reorder_result = $reorder_stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Products with reorder level > 0: " . $reorder_result['total'] . "</p>";
    
    // Check stock records
    $stock_sql = "SELECT COUNT(*) as total FROM stock WHERE qty_on_hand >= 0";
    $stock_stmt = $pdo->query($stock_sql);
    $stock_result = $stock_stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Stock records: " . $stock_result['total'] . "</p>";
    
    // If no products have reorder levels, let's set some
    if ($reorder_result['total'] == 0) {
        echo "<h3>Setting up test reorder levels...</h3>";
        
        // Set reorder levels for first 5 active products
        $update_sql = "UPDATE products SET reorder_level = 10 WHERE status_id = 2 LIMIT 5";
        $pdo->exec($update_sql);
        echo "<p>Set reorder level of 10 for 5 products.</p>";
    }
    
    // Now test the low stock query
    echo "<h3>Testing Low Stock Query:</h3>";
    
    $sql = "SELECT s.id,
                   s.qty_on_hand,
                   p.code as product_code,
                   p.name as product_name,
                   p.reorder_level,
                   c.category,
                   u.name as uom,
                   l.location,
                   (p.reorder_level - s.qty_on_hand) as shortage_qty,
                   ROUND((s.qty_on_hand / NULLIF(p.reorder_level, 0)) * 100, 2) as stock_percentage
            FROM stock s
            LEFT JOIN products p ON p.id = s.product_id
            LEFT JOIN categories c ON c.id = p.category_id
            LEFT JOIN uoms u ON u.id = p.uom_id
            LEFT JOIN locations l ON l.id = s.location_id
            WHERE s.qty_on_hand < p.reorder_level
            AND p.reorder_level > 0
            AND p.status_id = 2
            ORDER BY stock_percentage ASC, shortage_qty DESC
            LIMIT 10";
    
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($results)) {
        echo "<p>No low stock items found. Let's create some test data...</p>";
        
        // Let's reduce stock for a few items to create low stock situations
        $reduce_sql = "UPDATE stock s 
                      JOIN products p ON p.id = s.product_id 
                      SET s.qty_on_hand = 5 
                      WHERE p.reorder_level = 10 
                      AND p.status_id = 2 
                      AND s.qty_on_hand > 5 
                      LIMIT 3";
        $pdo->exec($reduce_sql);
        echo "<p>Reduced stock for 3 products to create low stock situation.</p>";
        
        // Run query again
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    if (!empty($results)) {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr style='background-color: #f0f0f0;'>";
        echo "<th>Product Code</th><th>Product Name</th><th>Current Stock</th><th>Reorder Level</th><th>Shortage</th><th>Stock %</th>";
        echo "</tr>";
        foreach ($results as $row) {
            $row_class = ($row['stock_percentage'] < 50) ? 'style="background-color: #ffcccc;"' : '';
            echo "<tr $row_class>";
            echo "<td>" . htmlspecialchars($row['product_code']) . "</td>";
            echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
            echo "<td>" . $row['qty_on_hand'] . "</td>";
            echo "<td>" . $row['reorder_level'] . "</td>";
            echo "<td>" . $row['shortage_qty'] . "</td>";
            echo "<td>" . $row['stock_percentage'] . "%</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h3>Testing API Call:</h3>";
        
        // Test the API call
        $url = 'http://localhost:3308/clinic2/inventory_reports/low_stock_report?location_id=0';
        
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Content-Type: application/json\r\n",
                'timeout' => 10
            ]
        ]);
        
        $api_response = @file_get_contents($url, false, $context);
        
        if ($api_response !== false) {
            $api_data = json_decode($api_response, true);
            if ($api_data) {
                echo "<p style='color: green;'><strong>API Success!</strong> Found " . count($api_data) . " low stock items.</p>";
            } else {
                echo "<p style='color: red;'><strong>API Response:</strong> " . htmlspecialchars($api_response) . "</p>";
            }
        } else {
            echo "<p style='color: red;'><strong>API Error:</strong> Could not connect to API endpoint</p>";
        }
        
    } else {
        echo "<p>Still no low stock items found after test data creation.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
