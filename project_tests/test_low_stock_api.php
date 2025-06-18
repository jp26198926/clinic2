<?php
// Test Low Stock Report API
include_once('application/config/database.php');

// Database connection
$host = $db['default']['hostname'];
$username = $db['default']['username'];
$password = $db['default']['password'];
$database = $db['default']['database'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Testing Low Stock Report Query</h2>";
    
    // Test the query that should be in get_low_stock method
    $sql = "SELECT s.*,
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
    
    echo "<p><strong>Query Results:</strong></p>";
    if (empty($results)) {
        echo "<p>No low stock items found (this could be normal if all items are well-stocked).</p>";
        
        // Let's check if there's any stock data at all
        $check_sql = "SELECT COUNT(*) as total FROM stock WHERE qty_on_hand > 0";
        $check_stmt = $pdo->query($check_sql);
        $check_result = $check_stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>Total stock records with quantity > 0: " . $check_result['total'] . "</p>";
        
        // Check reorder levels
        $reorder_sql = "SELECT COUNT(*) as total FROM products WHERE reorder_level > 0 AND status_id = 2";
        $reorder_stmt = $pdo->query($reorder_sql);
        $reorder_result = $reorder_stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>Products with reorder level > 0: " . $reorder_result['total'] . "</p>";
        
    } else {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Product Code</th><th>Product Name</th><th>Current Stock</th><th>Reorder Level</th><th>Shortage</th><th>Percentage</th></tr>";
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['product_code']) . "</td>";
            echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
            echo "<td>" . $row['qty_on_hand'] . "</td>";
            echo "<td>" . $row['reorder_level'] . "</td>";
            echo "<td>" . $row['shortage_qty'] . "</td>";
            echo "<td>" . $row['stock_percentage'] . "%</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
