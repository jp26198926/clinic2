<?php
// Direct diagnostic test for inventory_reports controller
session_start();

echo "<h2>🔬 Inventory Reports Controller Diagnostic</h2>\n";

try {
    $pdo = new PDO("mysql:host=localhost:3308;dbname=clinic2", "root", "astalavista");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get session prefix
    $stmt = $pdo->query("SELECT session_prefix FROM app_details LIMIT 1");
    $app_details = $stmt->fetch(PDO::FETCH_ASSOC);
    $prefix = $app_details['session_prefix'] ?? 'clinic';
    
    echo "<p><strong>Session Prefix:</strong> $prefix</p>\n";
    
    // Check session
    if (!isset($_SESSION[$prefix . '_logged_in'])) {
        echo "<p>❌ <strong>No session found.</strong> Please <a href='authentication'>login first</a>.</p>\n";
        exit;
    }
    
    echo "<p>✅ Session exists</p>\n";
    
    // Check if files exist
    echo "<h3>📁 File Existence Check:</h3>\n";
    
    $files_to_check = [
        'Controller' => 'application/controllers/Inventory_reports.php',
        'View' => 'application/views/inventory_reports/index.php',
        'Stock Model' => 'application/models/Stock_model.php',
        'Movements Model' => 'application/models/Stock_movements_model.php',
        'Location Model' => 'application/models/Data_location_model.php',
        'Product Model' => 'application/models/Data_product_model.php',
        'Custom Function' => 'application/libraries/Custom_function.php'
    ];
    
    foreach ($files_to_check as $name => $path) {
        if (file_exists($path)) {
            echo "<p>✅ <strong>{$name}:</strong> {$path}</p>\n";
        } else {
            echo "<p>❌ <strong>{$name} MISSING:</strong> {$path}</p>\n";
        }
    }
    
    // Check model methods exist
    echo "<h3>🔍 Model Methods Check:</h3>\n";
    
    // Load the Stock_model to check methods
    if (file_exists('application/models/Stock_model.php')) {
        require_once('application/models/Stock_model.php');
        
        $required_methods = [
            'get_low_stock',
            'get_stock_valuation', 
            'get_expiring_stock',
            'get_expired_stock',
            'get_zero_stock'
        ];
        
        $reflection = new ReflectionClass('Stock_model');
        $existing_methods = array_map(function($method) {
            return $method->getName();
        }, $reflection->getMethods());
        
        foreach ($required_methods as $method) {
            if (in_array($method, $existing_methods)) {
                echo "<p>✅ <strong>Stock_model::{$method}()</strong> exists</p>\n";
            } else {
                echo "<p>❌ <strong>Stock_model::{$method}()</strong> missing</p>\n";
            }
        }
    }
    
    // Test actual controller loading simulation
    echo "<h3>🧪 Controller Loading Simulation:</h3>\n";
    
    // Simulate what happens when inventory_reports is accessed
    $controller_path = 'application/controllers/Inventory_reports.php';
    if (file_exists($controller_path)) {
        $content = file_get_contents($controller_path);
        
        // Check for main_model
        if (strpos($content, "'main_model'") !== false) {
            echo "<p>❌ <strong>Controller still loads main_model</strong> - This will cause failure</p>\n";
        } else {
            echo "<p>✅ <strong>main_model reference removed</strong></p>\n";
        }
        
        // Check for required methods
        $required_controller_methods = ['index', 'generate_report', 'export_report', 'get_currency_symbol'];
        
        foreach ($required_controller_methods as $method) {
            if (strpos($content, "function {$method}") !== false) {
                echo "<p>✅ <strong>Controller::{$method}()</strong> exists</p>\n";
            } else {
                echo "<p>❌ <strong>Controller::{$method}()</strong> missing</p>\n";
            }
        }
    }
    
    // Test direct access attempt
    echo "<h3>🌐 Direct Access Test:</h3>\n";
    
    echo "<div style='background: #e7f3ff; border: 1px solid #b8daff; padding: 15px; border-radius: 5px; margin: 15px 0;'>\n";
    echo "<h4>🚀 Test Links:</h4>\n";
    echo "<p><strong>Primary:</strong> <a href='inventory_reports' target='_blank' style='background: #007bff; color: white; padding: 8px 15px; text-decoration: none; border-radius: 3px;'>inventory_reports</a></p>\n";
    echo "<p><strong>With index.php:</strong> <a href='index.php/inventory_reports' target='_blank' style='background: #28a745; color: white; padding: 8px 15px; text-decoration: none; border-radius: 3px;'>index.php/inventory_reports</a></p>\n";
    echo "<p><strong>With index method:</strong> <a href='inventory_reports/index' target='_blank' style='background: #17a2b8; color: white; padding: 8px 15px; text-decoration: none; border-radius: 3px;'>inventory_reports/index</a></p>\n";
    echo "</div>\n";
    
    // Show what to expect
    echo "<h3>📋 What Should Happen:</h3>\n";
    echo "<ol>\n";
    echo "<li><strong>Click any test link above</strong></li>\n";
    echo "<li><strong>Expected Result:</strong> Inventory Reports page loads with 8 report cards</li>\n";
    echo "<li><strong>If Access Denied:</strong> Session module issue (run fix scripts)</li>\n";
    echo "<li><strong>If 404 Error:</strong> URL rewriting issue (try index.php version)</li>\n";
    echo "<li><strong>If 500 Error:</strong> Controller/model loading issue (check file paths)</li>\n";
    echo "<li><strong>If Blank Page:</strong> PHP error (check error logs)</li>\n";
    echo "</ol>\n";
    
    // Final troubleshooting
    echo "<h3>🔧 Troubleshooting Commands:</h3>\n";
    echo "<div style='background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; border-radius: 5px; font-family: monospace;'>\n";
    echo "<p><strong>1. Check PHP Error Log:</strong><br>";
    echo "tail -f /c/laragon/logs/apache_error.log</p>\n";
    echo "<p><strong>2. Enable CI Debugging:</strong><br>";
    echo "Set \$config['log_threshold'] = 4; in application/config/config.php</p>\n";
    echo "<p><strong>3. Check .htaccess:</strong><br>";
    echo "Verify URL rewriting is working</p>\n";
    echo "</div>\n";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>\n";
}
?>
