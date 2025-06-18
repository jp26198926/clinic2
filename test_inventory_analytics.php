<?php
// Test script for Inventory Analytics API endpoints
echo "<h1>🔍 Inventory Analytics API Testing</h1>";
echo "<p>Testing all analytics endpoints to ensure functionality...</p>";

// Base URL for the analytics module
$base_url = "http://localhost/clinic2/inventory_analytics/";

// List of API endpoints to test
$endpoints = [
    'get_dashboard_stats' => 'Dashboard Statistics',
    'get_movement_trends' => 'Movement Trends (30 days)',
    'get_top_products' => 'Top Products Analysis',
    'get_abc_analysis' => 'ABC Classification',
    'get_stock_alerts' => 'Stock Alerts'
];

echo "<h2>📊 API Endpoint Tests</h2>";

foreach ($endpoints as $endpoint => $description) {
    echo "<div style='border: 1px solid #ddd; margin: 10px 0; padding: 15px; border-radius: 5px;'>";
    echo "<h3 style='color: #3498db; margin: 0 0 10px 0;'>🔗 {$description}</h3>";
    echo "<p><strong>Endpoint:</strong> <code>{$endpoint}</code></p>";
    
    // Test the endpoint
    $url = $base_url . $endpoint;
    if ($endpoint === 'get_movement_trends') {
        $url .= '?period=30&location_id=0';
    } elseif ($endpoint === 'get_top_products') {
        $url .= '?limit=10&location_id=0';
    } else {
        $url .= '?location_id=0';
    }
    
    echo "<p><strong>Test URL:</strong> <a href='{$url}' target='_blank'>{$url}</a></p>";
    
    // Try to fetch the data
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'method' => 'GET'
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        echo "<p style='color: red;'>❌ <strong>Failed to fetch data</strong> - Check if the endpoint is accessible</p>";
    } else {
        $data = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            if (isset($data['success']) && $data['success']) {
                echo "<p style='color: green;'>✅ <strong>Success!</strong> - Endpoint returned valid data</p>";
                echo "<details style='margin-top: 10px;'>";
                echo "<summary style='cursor: pointer; color: #3498db;'>📋 View Response Data</summary>";
                echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto; font-size: 12px;'>";
                echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT));
                echo "</pre>";
                echo "</details>";
            } else {
                echo "<p style='color: orange;'>⚠️ <strong>API Error:</strong> " . ($data['message'] ?? 'Unknown error') . "</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ <strong>Invalid JSON Response</strong></p>";
            echo "<details style='margin-top: 10px;'>";
            echo "<summary style='cursor: pointer; color: #e74c3c;'>🔍 View Raw Response</summary>";
            echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto; font-size: 12px;'>";
            echo htmlspecialchars($response);
            echo "</pre>";
            echo "</details>";
        }
    }
    
    echo "</div>";
}

// Test the main dashboard page
echo "<h2>🎨 Dashboard Page Test</h2>";
echo "<div style='border: 1px solid #ddd; margin: 10px 0; padding: 15px; border-radius: 5px;'>";
echo "<h3 style='color: #3498db; margin: 0 0 10px 0;'>📊 Main Dashboard</h3>";
echo "<p><strong>URL:</strong> <a href='http://localhost/clinic2/inventory_analytics' target='_blank'>http://localhost/clinic2/inventory_analytics</a></p>";
echo "<p>Click the link above to test the main analytics dashboard interface.</p>";
echo "</div>";

// Database verification
echo "<h2>🗄️ Database Verification</h2>";
echo "<div style='border: 1px solid #ddd; margin: 10px 0; padding: 15px; border-radius: 5px;'>";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=clinic2', 'root', 'astalavista');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ <strong>Database connection successful</strong></p>";
    
    // Check if module exists
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admin_module WHERE module_name = 'inventory_analytics'");
    $module_exists = $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    
    if ($module_exists) {
        echo "<p style='color: green;'>✅ <strong>inventory_analytics module found in database</strong></p>";
        
        // Check permissions
        $stmt = $pdo->query("
            SELECT r.role_name, p.permission 
            FROM admin_mod_perm mp 
            JOIN admin_module m ON mp.module_id = m.id 
            JOIN admin_role r ON mp.role_id = r.id 
            JOIN admin_permission p ON mp.permission_id = p.id 
            WHERE m.module_name = 'inventory_analytics' 
            ORDER BY r.id, p.id
        ");
        $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($permissions)) {
            echo "<p style='color: green;'>✅ <strong>Permissions configured:</strong></p>";
            echo "<ul>";
            foreach ($permissions as $perm) {
                echo "<li>{$perm['role_name']}: {$perm['permission']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p style='color: red;'>❌ <strong>No permissions found</strong></p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ <strong>inventory_analytics module not found in database</strong></p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ <strong>Database connection failed:</strong> " . $e->getMessage() . "</p>";
}

echo "</div>";

// File verification
echo "<h2>📁 File Structure Verification</h2>";
echo "<div style='border: 1px solid #ddd; margin: 10px 0; padding: 15px; border-radius: 5px;'>";

$required_files = [
    'application/controllers/Inventory_analytics.php' => 'Analytics Controller',
    'application/views/inventory_analytics/index.php' => 'Dashboard View',
    'project_sql/add_inventory_analytics.sql' => 'Database Setup Script'
];

foreach ($required_files as $file => $description) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ <strong>{$description}:</strong> {$file}</p>";
    } else {
        echo "<p style='color: red;'>❌ <strong>{$description}:</strong> {$file} (NOT FOUND)</p>";
    }
}

echo "</div>";

echo "<h2>🚀 Next Steps</h2>";
echo "<div style='border: 1px solid #27ae60; margin: 10px 0; padding: 15px; border-radius: 5px; background-color: #f8fff8;'>";
echo "<ol>";
echo "<li><strong>Access the Dashboard:</strong> <a href='http://localhost/clinic2/inventory_analytics' target='_blank'>Open Analytics Dashboard</a></li>";
echo "<li><strong>Check Menu:</strong> Login to your system and look for 'Analytics Dashboard' in the Inventory menu</li>";
echo "<li><strong>Test Features:</strong> Try the filters, charts, and export functions</li>";
echo "<li><strong>Review Data:</strong> Ensure the analytics display meaningful data from your inventory</li>";
echo "</ol>";
echo "</div>";

echo "<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 20px; background-color: #f8f9fa; }
h1 { color: #2c3e50; text-align: center; }
h2 { color: #34495e; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
h3 { margin-top: 0; }
code { background: #ecf0f1; padding: 3px 6px; border-radius: 4px; font-family: 'Courier New', monospace; }
a { color: #3498db; text-decoration: none; }
a:hover { text-decoration: underline; }
details { margin-top: 10px; }
summary { font-weight: bold; padding: 5px 0; }
pre { max-height: 300px; overflow-y: auto; }
ul { margin: 10px 0; }
li { margin: 5px 0; }
</style>";
?>
