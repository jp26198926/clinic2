<?php
// Force session reload for inventory_reports access issue
session_start();

// Database connection
try {
    $pdo = new PDO("mysql:host=localhost:3308;dbname=clinic2", "root", "astalavista");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔄 Force Session Reload</h2>\n";
    
    // Get session prefix
    $stmt = $pdo->query("SELECT session_prefix FROM app_details LIMIT 1");
    $app_details = $stmt->fetch(PDO::FETCH_ASSOC);
    $prefix = $app_details['session_prefix'] ?? 'clinic';
    
    echo "<p><strong>Session Prefix:</strong> $prefix</p>\n";
    
    // Check if user is logged in
    if (isset($_SESSION[$prefix . '_logged_in'])) {
        $session_data = $_SESSION[$prefix . '_logged_in'];
        $role_id = $session_data[$prefix . '_role'] ?? 1;
        
        echo "<p><strong>Current Role ID:</strong> $role_id</p>\n";
        
        // Reload modules from database (same query as Authentication_m->allow_module)
        $sql = "
            SELECT m.module_name, m.module_description, m.parent_id
            FROM admin_mod_perm mp
            LEFT JOIN admin_module m ON m.id=mp.module_id
            WHERE mp.role_id = ?
            GROUP BY mp.module_id
            ORDER BY m.parent_id, m.module_description
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$role_id]);
        $modules = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        echo "<h3>📋 Modules from Database:</h3>\n";
        echo "<p><strong>Total modules found:</strong> " . count($modules) . "</p>\n";
        
        // Check for inventory_reports specifically
        $found_inventory_reports = false;
        $inventory_count = 0;
        
        foreach ($modules as $module) {
            if (stripos($module->module_name, 'inventory') !== false) {
                $inventory_count++;
                if ($module->module_name === 'inventory_reports') {
                    $found_inventory_reports = true;
                    echo "<p>✅ <strong>Found inventory_reports in database query!</strong></p>\n";
                }
            }
        }
        
        echo "<p><strong>Inventory modules found:</strong> $inventory_count</p>\n";
        
        if (!$found_inventory_reports) {
            echo "<p>❌ <strong>inventory_reports NOT found in database query</strong></p>\n";
        }
        
        // Update session with fresh modules
        $_SESSION[$prefix . '_logged_in'][$prefix . '_modules'] = $modules;
        
        // Also reload parents
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
        $parents = $stmt_parents->fetchAll(PDO::FETCH_OBJ);
        
        $_SESSION[$prefix . '_logged_in'][$prefix . '_parents'] = $parents;
        
        echo "<h3>✅ Session Updated Successfully!</h3>\n";
        echo "<p>Updated " . count($modules) . " modules and " . count($parents) . " parents in session.</p>\n";
        
        // Show current session modules
        echo "<h3>📋 Updated Session Modules:</h3>\n";
        $updated_modules = $_SESSION[$prefix . '_logged_in'][$prefix . '_modules'];
        $inventory_modules_in_session = 0;
        
        foreach ($updated_modules as $module) {
            if (stripos($module->module_name, 'inventory') !== false) {
                $inventory_modules_in_session++;
                echo "<p>- {$module->module_name} ({$module->module_description})</p>\n";
            }
        }
        
        echo "<p><strong>Inventory modules now in session:</strong> $inventory_modules_in_session</p>\n";
        
        // Check specifically for inventory_reports
        $reports_in_session = false;
        foreach ($updated_modules as $module) {
            if ($module->module_name === 'inventory_reports') {
                $reports_in_session = true;
                break;
            }
        }
        
        if ($reports_in_session) {
            echo "<p>✅ <strong>inventory_reports is now in session!</strong></p>\n";
            echo "<p>🎉 <strong>Try accessing the inventory reports page now:</strong> <a href='inventory_reports' target='_blank'>Go to Inventory Reports</a></p>\n";
        } else {
            echo "<p>❌ <strong>inventory_reports still not in session after update</strong></p>\n";
        }
        
    } else {
        echo "<p>❌ No user session found. Please login first.</p>\n";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>\n";
}
?>
