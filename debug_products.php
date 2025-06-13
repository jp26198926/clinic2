<?php
// Test the get_products endpoint directly
define('BASEPATH', 'test');

// Simulate basic database connection
try {
    $pdo = new PDO("mysql:host=localhost:3308;dbname=clinic2", 'root', 'astalavista');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Testing get_products query directly...\n";
    
    // Test the exact query from the controller
    $sql = "
        SELECT x.id, x.code as product_code, x.name as product_name, x.cost, x.uom_id, m.name as uom
        FROM products x
        LEFT JOIN uoms m ON m.id = x.uom_id
        WHERE x.status_id = 2
        ORDER BY x.code ASC
        LIMIT 10
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    echo "✅ Query executed successfully\n";
    echo "✅ Found " . count($products) . " products\n";
    
    if (count($products) > 0) {
        echo "\n📋 Sample Products:\n";
        $json_data = [];
        foreach ($products as $product) {
            $json_data[] = $product;
            echo sprintf(
                "  - ID: %s | Code: %s | Name: %s | UOM: %s\n",
                $product->id,
                $product->product_code,
                substr($product->product_name, 0, 30),
                $product->uom ?: 'N/A'
            );
        }
        
        // Test JSON output
        $json = json_encode($json_data);
        if ($json !== false) {
            echo "\n✅ JSON encoding successful\n";
            echo "JSON length: " . strlen($json) . " characters\n";
        } else {
            echo "\n❌ JSON encoding failed\n";
        }
    } else {
        echo "\n⚠️  No products found\n";
        
        // Debug: Check what's in the products table
        echo "\nDebugging products table...\n";
        
        $debug_sql = "SELECT COUNT(*) as total, COUNT(CASE WHEN status_id = 2 THEN 1 END) as active FROM products";
        $debug_result = $pdo->query($debug_sql)->fetch();
        echo "Total products: {$debug_result['total']}, Active (status_id=2): {$debug_result['active']}\n";
        
        // Check status_id distribution
        $status_sql = "SELECT status_id, COUNT(*) as count FROM products GROUP BY status_id";
        $status_results = $pdo->query($status_sql)->fetchAll();
        echo "Status distribution:\n";
        foreach ($status_results as $status) {
            echo "  Status {$status['status_id']}: {$status['count']} products\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Full trace:\n" . $e->getTraceAsString() . "\n";
}
?>
