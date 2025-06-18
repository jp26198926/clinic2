<?php
// Test inventory_reports access after session reload
session_start();

// Database connection
try {
    $pdo = new PDO("mysql:host=localhost:3308;dbname=clinic2", "root", "astalavista");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🧪 Testing Inventory Reports Access</h2>\n";
    
    // Get session prefix
    $stmt = $pdo->query("SELECT session_prefix FROM app_details LIMIT 1");
    $app_details = $stmt->fetch(PDO::FETCH_ASSOC);
    $prefix = $app_details['session_prefix'] ?? 'clinic';
    
    echo "<p><strong>Session Prefix:</strong> $prefix</p>\n";
    
    // Simulate the Custom_function->is_allowed_module check
    function test_is_allowed_module($module_name, $prefix = "") {
        $result = false;
        
        if (isset($_SESSION[$prefix . '_logged_in'][$prefix . '_modules'])) {
            $allowed_module = $_SESSION[$prefix . '_logged_in'][$prefix . '_modules'];
            
            foreach ($allowed_module as $key => $value) {
                $my_module = strtolower($value->module_name);
                
                if (trim($my_module) === trim(strtolower($module_name))) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }
    
    // Test various inventory modules
    $modules_to_test = [
        'inventory_stock',
        'inventory_batch', 
        'inventory_movements',
        'inventory_reports'
    ];
    
    echo "<h3>🔍 Module Access Test Results:</h3>\n";
    
    foreach ($modules_to_test as $module) {
        $allowed = test_is_allowed_module($module, $prefix);
        $status = $allowed ? "✅ ALLOWED" : "❌ DENIED";
        echo "<p><strong>$module:</strong> $status</p>\n";
    }
    
    // Show session modules for debugging
    if (isset($_SESSION[$prefix . '_logged_in'][$prefix . '_modules'])) {
        $session_modules = $_SESSION[$prefix . '_logged_in'][$prefix . '_modules'];
        echo "<h3>📋 Current Session Modules:</h3>\n";
        echo "<p><strong>Total modules in session:</strong> " . count($session_modules) . "</p>\n";
        
        echo "<h4>Inventory-related modules in session:</h4>\n";
        foreach ($session_modules as $module) {
            if (stripos($module->module_name, 'inventory') !== false) {
                echo "<p>- {$module->module_name} ({$module->module_description})</p>\n";
            }
        }
    } else {
        echo "<p>❌ No modules found in session</p>\n";
    }
    
    // Provide next steps
    echo "<h3>🎯 Next Steps:</h3>\n";
    
    $inventory_reports_allowed = test_is_allowed_module('inventory_reports', $prefix);
    
    if ($inventory_reports_allowed) {
        echo "<p>✅ <strong>SUCCESS!</strong> inventory_reports access is now working.</p>\n";
        echo "<p>🎉 <strong>Try accessing the page:</strong> <a href='inventory_reports' target='_blank'>Go to Inventory Reports</a></p>\n";
    } else {
        echo "<p>❌ <strong>Still having issues.</strong> Try these steps:</p>\n";
        echo "<ol>\n";
        echo "<li><a href='force_session_reload.php'>Run force session reload again</a></li>\n";
        echo "<li><a href='authentication/logout'>Logout completely</a> and login again</li>\n";
        echo "<li>Clear browser cache and cookies</li>\n";
        echo "</ol>\n";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>\n";
}
?>
