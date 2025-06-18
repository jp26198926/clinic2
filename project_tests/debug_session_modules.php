<?php
// Debug session modules for inventory_reports access issue
session_start();

// Load CodeIgniter to get session data
define('BASEPATH', __DIR__ . '/system/');
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['SCRIPT_NAME'] = '/clinic2/debug_session_modules.php';

require_once 'application/config/database.php';

// Connect to database
try {
    $pdo = new PDO("mysql:host=localhost:3308;dbname=clinic2", "root", "astalavista");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔍 Session Modules Debug</h2>\n";
    
    // Get session prefix from database
    $stmt = $pdo->query("SELECT session_prefix FROM app_details LIMIT 1");
    $app_details = $stmt->fetch(PDO::FETCH_ASSOC);
    $prefix = $app_details['session_prefix'] ?? 'clinic';
    
    echo "<p><strong>Session Prefix:</strong> $prefix</p>\n";
    
    // Check if session exists
    if (isset($_SESSION[$prefix . '_logged_in'])) {
        echo "<p>✅ User session found</p>\n";
        
        $session_data = $_SESSION[$prefix . '_logged_in'];
        $user_id = $session_data[$prefix . '_uid'] ?? 'N/A';
        $role_id = $session_data[$prefix . '_role'] ?? 'N/A';
        
        echo "<p><strong>User ID:</strong> $user_id</p>\n";
        echo "<p><strong>Role ID:</strong> $role_id</p>\n";
        
        // Check modules in session
        if (isset($session_data[$prefix . '_modules'])) {
            echo "<h3>📋 Modules in Session:</h3>\n";
            $modules = $session_data[$prefix . '_modules'];
            $inventory_modules = [];
            
            foreach ($modules as $module) {
                if (isset($module->parent_module) && stripos($module->parent_module, 'inventory') !== false) {
                    $inventory_modules[] = $module;
                }
                if (isset($module->module_name) && stripos($module->module_name, 'inventory') !== false) {
                    $inventory_modules[] = $module;
                }
            }
            
            echo "<p><strong>Total modules in session:</strong> " . count($modules) . "</p>\n";
            echo "<p><strong>Inventory-related modules found:</strong> " . count($inventory_modules) . "</p>\n";
            
            if (!empty($inventory_modules)) {
                echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>\n";
                echo "<tr><th>Module Name</th><th>Description</th><th>Parent</th></tr>\n";
                foreach ($inventory_modules as $mod) {
                    $module_name = $mod->module_name ?? 'N/A';
                    $description = $mod->module_description ?? 'N/A';
                    $parent = $mod->parent_module ?? 'N/A';
                    echo "<tr><td>$module_name</td><td>$description</td><td>$parent</td></tr>\n";
                }
                echo "</table>\n";
                
                // Check specifically for inventory_reports
                $found_reports = false;
                foreach ($inventory_modules as $mod) {
                    if (isset($mod->module_name) && $mod->module_name === 'inventory_reports') {
                        $found_reports = true;
                        break;
                    }
                }
                
                if ($found_reports) {
                    echo "<p>✅ <strong>inventory_reports found in session!</strong></p>\n";
                } else {
                    echo "<p>❌ <strong>inventory_reports NOT found in session</strong></p>\n";
                }
            }
        } else {
            echo "<p>❌ No modules found in session</p>\n";
        }
        
        // Check parent modules in session
        if (isset($session_data[$prefix . '_parents'])) {
            echo "<h3>📁 Parent Modules in Session:</h3>\n";
            $parents = $session_data[$prefix . '_parents'];
            echo "<p><strong>Total parents in session:</strong> " . count($parents) . "</p>\n";
            
            foreach ($parents as $parent) {
                if (isset($parent->parent_name) && stripos($parent->parent_name, 'inventory') !== false) {
                    echo "<p>✅ <strong>Inventory parent found:</strong> {$parent->parent_name} (ID: {$parent->parent_id})</p>\n";
                }
            }
        }
        
    } else {
        echo "<p>❌ No user session found</p>\n";
        echo "<p>Available session keys: " . implode(', ', array_keys($_SESSION)) . "</p>\n";
    }
    
    // Check database permissions for inventory_reports
    echo "<h3>🔐 Database Permissions Check:</h3>\n";
    
    // Get user role (assume role 1 for admin)
    $role_id = isset($session_data[$prefix . '_role']) ? $session_data[$prefix . '_role'] : 1;
    
    $sql = "
        SELECT 
            am.module_name, 
            am.module_description,
            ap.parent_name,
            amp.permission_id
        FROM admin_module am
        LEFT JOIN admin_parent ap ON am.parent_id = ap.parent_id  
        LEFT JOIN admin_mod_perm amp ON am.id = amp.module_id AND amp.role_id = ?
        WHERE am.module_name = 'inventory_reports'
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$role_id]);
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($permissions)) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>\n";
        echo "<tr><th>Module</th><th>Parent</th><th>Permission ID</th></tr>\n";
        foreach ($permissions as $perm) {
            $permission_id = $perm['permission_id'] ?? 'No permission';
            echo "<tr><td>{$perm['module_name']}</td><td>{$perm['parent_name']}</td><td>$permission_id</td></tr>\n";
        }
        echo "</table>\n";
    } else {
        echo "<p>❌ No permissions found for inventory_reports and role $role_id</p>\n";
    }
    
    // Show how to force session refresh
    echo "<h3>🔄 Session Refresh Options:</h3>\n";
    echo "<p>1. <a href='?refresh_session=1'>Force refresh session modules</a></p>\n";
    echo "<p>2. Clear browser session and login again</p>\n";
    echo "<p>3. <a href='authentication/logout'>Logout and login again</a></p>\n";
    
    // Handle session refresh request
    if (isset($_GET['refresh_session'])) {
        echo "<h3>🔄 Refreshing Session...</h3>\n";
        
        // Clear modules from session
        unset($_SESSION[$prefix . '_logged_in'][$prefix . '_modules']);
        unset($_SESSION[$prefix . '_logged_in'][$prefix . '_parents']);
        
        echo "<p>✅ Session modules cleared. Please <a href='authentication/logout'>logout and login again</a> for changes to take effect.</p>\n";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>\n";
}
?>
