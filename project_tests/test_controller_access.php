<?php
// Direct test for inventory_reports controller access
session_start();

echo "<h2>🔍 Direct Inventory Reports Access Test</h2>\n";

// Database connection
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
    
    echo "<p>✅ Session found</p>\n";
    
    // Load CodeIgniter to test the controller
    $GLOBALS['EXT'] = '.php';
    $GLOBALS['CFG'] =& load_class('Config', 'core');
    $GLOBALS['UNI'] =& load_class('Utf8', 'core');
    $GLOBALS['SEC'] =& load_class('Security', 'core');
    
    function &load_class($class, $directory = 'libraries', $param = NULL) {
        static $_classes = array();
        
        if (isset($_classes[$class])) {
            return $_classes[$class];
        }
        
        $name = FALSE;
        
        foreach (array(APPPATH, BASEPATH) as $path) {
            if (file_exists($path.$directory.'/'.$class.'.php')) {
                $name = $class;
                
                if (class_exists($name, FALSE) === FALSE) {
                    require_once($path.$directory.'/'.$class.'.php');
                }
                
                break;
            }
        }
        
        if ($name === FALSE) {
            exit('Unable to locate the specified class: '.$class.'.php');
        }
        
        $_classes[$class] = new $name($param);
        return $_classes[$class];
    }
    
    echo "<p>🧪 <strong>Testing Controller Access:</strong></p>\n";
    
    // Test if we can access the inventory reports URL
    echo "<p><a href='inventory_reports' target='_blank' style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0;'>🚀 Test Inventory Reports Access</a></p>\n";
    
    echo "<p><strong>Alternative test links:</strong></p>\n";
    echo "<ul>\n";
    echo "<li><a href='inventory_reports/index' target='_blank'>inventory_reports/index</a></li>\n";
    echo "<li><a href='index.php/inventory_reports' target='_blank'>index.php/inventory_reports</a></li>\n";
    echo "<li><a href='index.php/inventory_reports/index' target='_blank'>index.php/inventory_reports/index</a></li>\n";
    echo "</ul>\n";
    
    // Check if controller file exists
    $controller_path = 'application/controllers/Inventory_reports.php';
    if (file_exists($controller_path)) {
        echo "<p>✅ Controller file exists: $controller_path</p>\n";
    } else {
        echo "<p>❌ Controller file missing: $controller_path</p>\n";
    }
    
    // Check if view file exists
    $view_path = 'application/views/inventory_reports/index.php';
    if (file_exists($view_path)) {
        echo "<p>✅ View file exists: $view_path</p>\n";
    } else {
        echo "<p>❌ View file missing: $view_path</p>\n";
    }
    
    // Show current session modules for debugging
    if (isset($_SESSION[$prefix . '_logged_in'][$prefix . '_modules'])) {
        $modules = $_SESSION[$prefix . '_logged_in'][$prefix . '_modules'];
        echo "<h3>📋 Current Session Modules:</h3>\n";
        
        $inventory_modules = [];
        foreach ($modules as $module) {
            if (stripos($module->module_name, 'inventory') !== false) {
                $inventory_modules[] = $module;
            }
        }
        
        if (!empty($inventory_modules)) {
            echo "<p><strong>Inventory modules in session:</strong></p>\n";
            echo "<ul>\n";
            foreach ($inventory_modules as $module) {
                echo "<li><strong>{$module->module_name}</strong> - {$module->module_description}</li>\n";
            }
            echo "</ul>\n";
        } else {
            echo "<p>❌ No inventory modules found in session</p>\n";
        }
    }
    
    echo "<h3>🛠️ Troubleshooting Steps:</h3>\n";
    echo "<ol>\n";
    echo "<li><a href='fix_inventory_reports_access.php'>Run the comprehensive session fix</a></li>\n";
    echo "<li>Clear browser cache and cookies</li>\n";
    echo "<li><a href='authentication/logout'>Logout</a> and login again</li>\n";
    echo "<li>Check browser console for JavaScript errors</li>\n";
    echo "<li>Check if any .htaccess rules are blocking the request</li>\n";
    echo "</ol>\n";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>\n";
}
?>
