<?php
// Simple endpoint test - simulate the CodeIgniter environment
require_once __DIR__ . '/application/config/database.php';

// Output headers like CodeIgniter would
header('Content-Type: application/json');

try {
    // Connect using CodeIgniter database config
    $host = $db['default']['hostname'];
    $user = $db['default']['username'];
    $pass = $db['default']['password'];
    $database = $db['default']['database'];
    $port = $db['default']['port'] ?: 3306;
    
    $dsn = "mysql:host={$host};port={$port};dbname={$database}";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Execute the same query as the controller
    $sql = "
        SELECT x.id, x.code as product_code, x.name as product_name, x.cost, x.uom_id, m.name as uom
        FROM products x
        LEFT JOIN uoms m ON m.id = x.uom_id
        WHERE x.status_id = 2
        ORDER BY x.code ASC
        LIMIT 50
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    // Return JSON response like the controller should
    echo json_encode($products);
    
} catch (Exception $ex) {
    // Return error JSON like the controller should
    http_response_code(500);
    echo json_encode(array('error' => $ex->getMessage()));
}
?>
