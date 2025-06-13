<?php
echo "Simple PHP test\n";

try {
    $pdo = new PDO("mysql:host=localhost:3308;dbname=clinic2", 'root', 'astalavista');
    echo "Database connected\n";
    
    $result = $pdo->query("SELECT COUNT(*) as count FROM products WHERE status_id = 2");
    $row = $result->fetch();
    echo "Active products: " . $row['count'] . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
