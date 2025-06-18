<?php
// Comprehensive session fix for inventory_reports access
session_start();

// Database connection
try {
    $pdo = new PDO("mysql:host=localhost:3308;dbname=clinic2", "root", "astalavista");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔧 Comprehensive Session Fix</h2>\n";
    
    // Get session prefix
    $stmt = $pdo->query("SELECT session_prefix FROM app_details LIMIT 1");
    $app_details = $stmt->fetch(PDO::FETCH_ASSOC);
    $prefix = $app_details['session_prefix'] ?? 'clinic';
    
    echo "<p><strong>Session Prefix:</strong> $prefix</p>\n";
    
    // Check if user is logged in
    if (!isset($_SESSION[$prefix . '_logged_in'])) {
        echo "<p>❌ <strong>No active session found.</strong> Please <a href='authentication'>login first</a>.</p>\n";
        exit;
    }
    
    $session_data = $_SESSION[$prefix . '_logged_in'];
    $role_id = $session_data[$prefix . '_role'] ?? 1;
    $user_id = $session_data[$prefix . '_uid'] ?? 'Unknown';
    
    echo "<p><strong>User ID:</strong> $user_id</p>\n";
    echo "<p><strong>Role ID:</strong> $role_id</p>\n";
    
    // Step 1: Verify module exists in database
    $sql_check = "SELECT COUNT(*) as count FROM admin_module WHERE module_name = 'inventory_reports'";
    $stmt_check = $pdo->query($sql_check);
    $module_exists = $stmt_check->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    
    if (!$module_exists) {
        echo "<p>❌ <strong>inventory_reports module not found in database!</strong></p>\n";
        echo "<p>Please run the database setup script first.</p>\n";
        exit;
    }
    
    echo "<p>✅ inventory_reports module exists in database</p>\n";
    
    // Step 2: Verify permissions exist
    $sql_perms = "
        SELECT COUNT(*) as count 
        FROM admin_mod_perm amp
        JOIN admin_module am ON am.id = amp.module_id
        WHERE am.module_name = 'inventory_reports' AND amp.role_id = ?
    ";
    $stmt_perms = $pdo->prepare($sql_perms);
    $stmt_perms->execute([$role_id]);
    $has_perms = $stmt_perms->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    
    if (!$has_perms) {
        echo "<p>❌ <strong>No permissions found for inventory_reports and role $role_id!</strong></p>\n";
        echo "<p>Please check the permissions setup.</p>\n";
        exit;
    }
    
    echo "<p>✅ Permissions exist for role $role_id</p>\n";
    
    // Step 3: Reload modules using exact same query as Authentication_m
    echo "<h3>🔄 Reloading Session Modules...</h3>\n";
    
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
    
    echo "<p><strong>Fresh modules from database:</strong> " . count($fresh_modules) . "</p>\n";
    
    // Check if inventory_reports is in fresh modules
    $inventory_reports_found = false;
    foreach ($fresh_modules as $module) {
        if ($module->module_name === 'inventory_reports') {
            $inventory_reports_found = true;
            echo "<p>✅ <strong>inventory_reports found in fresh module query!</strong></p>\n";
            break;
        }
    }
    
    if (!$inventory_reports_found) {
        echo "<p>❌ <strong>inventory_reports NOT found in fresh module query!</strong></p>\n";
        echo "<p>There may be an issue with the database query or permissions.</p>\n";
    }
    
    // Step 4: Update session with fresh modules
    $_SESSION[$prefix . '_logged_in'][$prefix . '_modules'] = $fresh_modules;
    
    // Step 5: Reload parents
    $sql_parents = "
        SELECT DISTINCT(m.parent_id), p.parent_name, p.parent_icon, p.parent_order
        FROM admin_mod_perm mp
        LEFT JOIN admin_module m ON m.id=mp.module_id
        LEFT JOIN admin_parent p ON p.parent_id = m.parent_id
        WHERE mp.role_id = ?
        GROUP BY mp.module_id
        ORDER BY p.parent_order
    ";
    
    $stmt_parents = $pdo->prepare($sql_parents);
    $stmt_parents->execute([$role_id]);
    $fresh_parents = $stmt_parents->fetchAll(PDO::FETCH_OBJ);
    
    $_SESSION[$prefix . '_logged_in'][$prefix . '_parents'] = $fresh_parents;
    
    echo "<p><strong>Fresh parents updated:</strong> " . count($fresh_parents) . "</p>\n";
    
    // Step 6: Test the Custom_function logic
    echo "<h3>🧪 Testing Module Access...</h3>\n";
    
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
        echo "<p>✅ <strong>SUCCESS! inventory_reports access is now working!</strong></p>\n";
        echo "<p>🎉 <strong>You can now access:</strong> <a href='inventory_reports' target='_blank' style='background: #28a745; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Go to Inventory Reports</a></p>\n";
    } else {
        echo "<p>❌ <strong>Still having access issues.</strong></p>\n";
        echo "<p>Debug info:</p>\n";
        echo "<ul>\n";
        echo "<li>Modules in session: " . (isset($_SESSION[$prefix . '_logged_in'][$prefix . '_modules']) ? count($_SESSION[$prefix . '_logged_in'][$prefix . '_modules']) : 0) . "</li>\n";
        echo "<li>Session key exists: " . (isset($_SESSION[$prefix . '_logged_in']) ? 'Yes' : 'No') . "</li>\n";
        echo "</ul>\n";
    }
    
    // Step 7: Show all inventory modules for verification
    echo "<h3>📋 All Inventory Modules in Session:</h3>\n";
    
    if (isset($_SESSION[$prefix . '_logged_in'][$prefix . '_modules'])) {
        $session_modules = $_SESSION[$prefix . '_logged_in'][$prefix . '_modules'];
        $inventory_count = 0;
        
        foreach ($session_modules as $module) {
            if (stripos($module->module_name, 'inventory') !== false) {
                $inventory_count++;
                $access_test = test_module_access($module->module_name, $prefix);
                $access_status = $access_test ? "✅" : "❌";
                echo "<p>$access_status <strong>{$module->module_name}</strong> - {$module->module_description}</p>\n";
            }
        }
        
        echo "<p><strong>Total inventory modules:</strong> $inventory_count</p>\n";
    }
    
    echo "<h3>✨ Summary</h3>\n";
    echo "<p>Session has been refreshed with the latest modules from the database.</p>\n";
    echo "<p>If you're still having issues, try:</p>\n";
    echo "<ol>\n";
    echo "<li>Clear your browser cache and cookies</li>\n";
    echo "<li><a href='authentication/logout'>Logout</a> and login again</li>\n";
    echo "<li>Check with the system administrator</li>\n";
    echo "</ol>\n";
    
} catch (PDOException $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>\n";
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>\n";
}
?>
