<?php
// Test script for Inventory Analytics functionality
echo "<h1>Inventory Analytics Test Page</h1>";
echo "<p>Testing analytics functionality before database integration...</p>";

// Check if all necessary files exist
$files_to_check = [
    'application/controllers/Inventory_analytics.php',
    'application/views/inventory_analytics/index.php',
    'project_sql/add_inventory_analytics.sql'
];

echo "<h2>File Verification</h2>";
foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $file exists</p>";
    } else {
        echo "<p style='color: red;'>❌ $file NOT found</p>";
    }
}

// Check CodeIgniter setup
echo "<h2>CodeIgniter Check</h2>";
if (file_exists('application/config/config.php')) {
    echo "<p style='color: green;'>✅ CodeIgniter application found</p>";
    
    // Load minimal CI environment for testing
    try {
        define('BASEPATH', 'system/');
        require_once 'application/config/config.php';
        echo "<p style='color: green;'>✅ Config loaded successfully</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error loading config: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ CodeIgniter application not found</p>";
}

// Analytics API endpoints to test
$analytics_endpoints = [
    'get_dashboard_stats',
    'get_movement_trends', 
    'get_top_products',
    'get_abc_analysis',
    'get_stock_alerts'
];

echo "<h2>Analytics Endpoints</h2>";
echo "<p>The following endpoints will be available after database setup:</p>";
foreach ($analytics_endpoints as $endpoint) {
    echo "<p>📊 <code>inventory_analytics/{$endpoint}</code></p>";
}

echo "<h2>Next Steps</h2>";
echo "<ol>";
echo "<li>Run the SQL script: <code>project_sql/add_inventory_analytics.sql</code></li>";
echo "<li>Access the analytics dashboard at: <code>http://your-site/inventory_analytics</code></li>";
echo "<li>Check the Inventory menu for the new Analytics item</li>";
echo "</ol>";

echo "<h2>Database Setup Commands</h2>";
echo "<p>Run this SQL script in your database:</p>";
echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
echo htmlentities(file_get_contents('project_sql/add_inventory_analytics.sql'));
echo "</pre>";

// Show some sample data structure for the analytics
echo "<h2>Sample Analytics Data Structure</h2>";
echo "<h3>Dashboard Stats</h3>";
echo "<pre style='background: #f5f5f5; padding: 10px;'>";
echo json_encode([
    'total_products' => 150,
    'total_stock_value' => 25750.00,
    'low_stock_count' => 12,
    'expired_stock_count' => 3,
    'recent_movements_count' => 45
], JSON_PRETTY_PRINT);
echo "</pre>";

echo "<h3>Movement Trends</h3>";
echo "<pre style='background: #f5f5f5; padding: 10px;'>";
echo json_encode([
    [
        'movement_date' => '2025-06-17',
        'movement_type' => 'RECEIVE',
        'transaction_count' => 5,
        'total_quantity' => 120.00,
        'total_value' => 1850.00
    ],
    [
        'movement_date' => '2025-06-17',
        'movement_type' => 'RELEASE',
        'transaction_count' => 3,
        'total_quantity' => 75.00,
        'total_value' => 1125.00
    ]
], JSON_PRETTY_PRINT);
echo "</pre>";

echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1 { color: #2c3e50; }
h2 { color: #3498db; border-bottom: 2px solid #3498db; padding-bottom: 5px; }
code { background: #ecf0f1; padding: 2px 5px; border-radius: 3px; }
</style>";
?>
