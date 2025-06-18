<?php
// Final comprehensive test for inventory_reports access
session_start();

echo "<h2>🔧 Final Inventory Reports Access Test</h2>\n";

try {
    $pdo = new PDO("mysql:host=localhost:3308;dbname=clinic2", "root", "astalavista");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get session prefix
    $stmt = $pdo->query("SELECT session_prefix FROM app_details LIMIT 1");
    $app_details = $stmt->fetch(PDO::FETCH_ASSOC);
    $prefix = $app_details['session_prefix'] ?? 'clinic';
    
    echo "<p><strong>Session Prefix:</strong> $prefix</p>\n";
    
    // Check session status
    if (!isset($_SESSION[$prefix . '_logged_in'])) {
        echo "<p>❌ <strong>No session found.</strong> Please <a href='authentication'>login first</a>.</p>\n";
        exit;
    }
    
    echo "<p>✅ Session found</p>\n";
    
    $session_data = $_SESSION[$prefix . '_logged_in'];
    $role_id = $session_data[$prefix . '_role'] ?? 1;
    
    echo "<p><strong>Role ID:</strong> $role_id</p>\n";
    
    // Check if main_model issue is fixed
    echo "<h3>📁 Controller File Check:</h3>\n";
    
    $controller_path = 'application/controllers/Inventory_reports.php';
    if (file_exists($controller_path)) {
        $controller_content = file_get_contents($controller_path);
        
        if (strpos($controller_content, "load->model('main_model')") !== false) {
            echo "<p>❌ <strong>main_model still being loaded</strong> - This will cause errors</p>\n";
        } else {
            echo "<p>✅ <strong>main_model reference removed</strong> - Controller should work now</p>\n";
        }
        
        // Check other required models
        $required_models = ['data_location_model', 'data_product_model', 'stock_model', 'stock_movements_model'];
        foreach ($required_models as $model) {
            $model_file = "application/models/" . ucfirst($model) . ".php";
            if (file_exists($model_file)) {
                echo "<p>✅ {$model} file exists</p>\n";
            } else {
                echo "<p>❌ {$model} file missing</p>\n";
            }
        }
    }
    
    // Test module access
    echo "<h3>🔍 Module Access Test:</h3>\n";
    
    function test_module_access($module_name, $prefix) {
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
    
    $inventory_reports_access = test_module_access('inventory_reports', $prefix);
    
    if ($inventory_reports_access) {
        echo "<p>✅ <strong>inventory_reports access: ALLOWED</strong></p>\n";
    } else {
        echo "<p>❌ <strong>inventory_reports access: DENIED</strong></p>\n";
        
        // Force refresh session if still denied
        echo "<p>🔄 Attempting to refresh session...</p>\n";
        
        $sql_modules = "
            SELECT m.module_name, m.module_description, m.parent_id
            FROM admin_mod_perm mp
            LEFT JOIN admin_module m ON m.id=mp.module_id
            WHERE mp.role_id = ?
            GROUP BY mp.module_id
            ORDER BY m.parent_id, m.module_description
        ";
        
        $stmt_modules = $pdo->prepare($sql_modules);
        $stmt_modules->execute([$role_id]);
        $fresh_modules = $stmt_modules->fetchAll(PDO::FETCH_OBJ);
        
        $_SESSION[$prefix . '_logged_in'][$prefix . '_modules'] = $fresh_modules;
        
        // Test again
        $inventory_reports_access = test_module_access('inventory_reports', $prefix);
        if ($inventory_reports_access) {
            echo "<p>✅ <strong>After refresh - inventory_reports access: ALLOWED</strong></p>\n";
        } else {
            echo "<p>❌ <strong>After refresh - inventory_reports access: STILL DENIED</strong></p>\n";
        }
    }
    
    // Test direct URL access
    echo "<h3>🚀 Direct Access Test:</h3>\n";
    echo "<p><a href='inventory_reports' target='_blank' style='background: #28a745; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;'>🎯 Test Inventory Reports Page</a></p>\n";
    
    // Show all inventory modules
    echo "<h3>📋 All Inventory Modules in Session:</h3>\n";
    
    if (isset($_SESSION[$prefix . '_logged_in'][$prefix . '_modules'])) {
        $session_modules = $_SESSION[$prefix . '_logged_in'][$prefix . '_modules'];
        
        echo "<p><strong>Total modules in session:</strong> " . count($session_modules) . "</p>\n";
        
        $inventory_modules = [];
        foreach ($session_modules as $module) {
            if (stripos($module->module_name, 'inventory') !== false) {
                $inventory_modules[] = $module;
            }
        }
        
        if (!empty($inventory_modules)) {
            echo "<p><strong>Inventory modules found:</strong></p>\n";
            echo "<ul>\n";
            foreach ($inventory_modules as $module) {
                $access_test = test_module_access($module->module_name, $prefix);
                $icon = $access_test ? "✅" : "❌";
                echo "<li>{$icon} <strong>{$module->module_name}</strong> - {$module->module_description}</li>\n";
            }
            echo "</ul>\n";
        } else {
            echo "<p>❌ No inventory modules found in session</p>\n";
        }
    }
    
    // Troubleshooting steps
    echo "<h3>🛠️ If Still Having Issues:</h3>\n";
    echo "<ol>\n";
    echo "<li><strong>Clear Browser Cache:</strong> Ctrl+Shift+Delete and clear all</li>\n";
    echo "<li><strong>Hard Refresh:</strong> Ctrl+F5 on the page</li>\n";
    echo "<li><strong>Complete Logout:</strong> <a href='authentication/logout'>Logout here</a> and login again</li>\n";
    echo "<li><strong>Try Different Browser:</strong> Use incognito/private mode</li>\n";
    echo "<li><strong>Check Browser Console:</strong> F12 → Console for JavaScript errors</li>\n";
    echo "</ol>\n";
    
    // Show final status
    echo "<h3>🎯 Final Status:</h3>\n";
    
    if ($inventory_reports_access) {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 15px 0;'>\n";
        echo "<h4>✅ SUCCESS!</h4>\n";
        echo "<p>The inventory_reports module is now accessible. You should be able to access the page.</p>\n";
        echo "</div>\n";
    } else {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 15px 0;'>\n";
        echo "<h4>❌ Still Having Issues</h4>\n";
        echo "<p>The module access is still denied. Please contact the system administrator or check the database permissions manually.</p>\n";
        echo "</div>\n";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>\n";
}
?>
