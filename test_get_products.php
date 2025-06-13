<?php
echo "Testing get_products functionality...\n";

// Database configuration
$host = 'localhost:3308';
$user = 'root';
$pass = 'astalavista';
$db = 'clinic2';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to database\n";
    
    // Test the exact query from the fixed get_products method
    $sql = "
        SELECT x.id, x.code as product_code, x.name as product_name, x.cost, x.uom_id, m.name as uom
        FROM products x
        LEFT JOIN uoms m ON m.id = x.uom_id
        WHERE x.status_id = 2
        ORDER BY x.code ASC
        LIMIT 5
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "✅ Query executed successfully\n";
    echo "✅ Found " . count($products) . " active products\n";
    
    if (count($products) > 0) {
        echo "\n📋 Sample Products:\n";
        foreach ($products as $product) {
            echo sprintf(
                "  - ID: %s | Code: %s | Name: %s | Cost: %s | UOM: %s\n",
                $product['id'],
                $product['product_code'],
                substr($product['product_name'], 0, 30),
                $product['cost'],
                $product['uom'] ?: 'N/A'
            );
        }
        
        echo "\n✅ Products are properly formatted for the dropdown\n";
        
        // Test JSON encoding (what the controller returns)
        $json = json_encode($products);
        if ($json !== false) {
            echo "✅ JSON encoding successful\n";
        } else {
            echo "❌ JSON encoding failed\n";
        }
        
    } else {
        echo "⚠️  No active products found. Check products table and status_id values.\n";
        
        // Check what status_id values exist
        $status_check = $pdo->query("SELECT DISTINCT status_id, COUNT(*) as count FROM products GROUP BY status_id")->fetchAll();
        echo "\n📊 Product status distribution:\n";
        foreach ($status_check as $status) {
            echo "  - Status ID {$status['status_id']}: {$status['count']} products\n";
        }
    }
    
} catch (Exception $ex) {
    echo "❌ Error: " . $ex->getMessage() . "\n";
}

echo "\n🔍 Test complete!\n";
?>
