<?php
// Debug session and module access for inventory_reports
// This file will help us identify the access issue

// Check if we can access CodeIgniter
if (!defined('BASEPATH')) {
    echo "CodeIgniter not loaded. Accessing directly...\n";
    
    // Load CodeIgniter manually for debugging
    require_once(__DIR__ . '/index.php');
    exit();
}

// If this file is included in CI context, show session debug info
$CI =& get_instance();
$CI->load->model('app_details_m', 'ad');

try {
    $ad = $CI->ad->get_details();
    $prefix = $ad->session_prefix ?? 'clinic';
    
    echo "<h2>Session Debug Information</h2>";
    echo "<h3>Session Prefix: " . $prefix . "</h3>";
    
    echo "<h3>Session Data:</h3>";
    echo "<pre>";
    print_r($CI->session->userdata());
    echo "</pre>";
    
    echo "<h3>Logged In Status:</h3>";
    if (isset($CI->session->userdata[$prefix . '_logged_in'])) {
        echo "User is logged in.<br>";
        
        echo "<h4>User Info:</h4>";
        $logged_in = $CI->session->userdata[$prefix . '_logged_in'];
        foreach ($logged_in as $key => $value) {
            if ($key === $prefix . '_modules') {
                echo "<strong>$key:</strong><br>";
                if (is_array($value)) {
                    foreach ($value as $module) {
                        echo "  - " . (is_object($module) ? $module->module_name : print_r($module, true)) . "<br>";
                    }
                } else {
                    print_r($value);
                }
                echo "<br>";
            } else {
                echo "<strong>$key:</strong> " . (is_scalar($value) ? $value : gettype($value)) . "<br>";
            }
        }
        
        echo "<h4>Role ID:</h4>";
        $role_id = $CI->session->userdata[$prefix . '_logged_in'][$prefix . '_role'] ?? 'Not set';
        echo "Role ID: " . $role_id . "<br>";
        
        // Check database permissions
        echo "<h4>Database Module Permissions:</h4>";
        $CI->load->model('authentication_m', 'auth');
        try {
            $permissions = $CI->auth->allow_permission($role_id, 'inventory_reports');
            echo "Permissions for inventory_reports:<br>";
            foreach ($permissions as $perm) {
                echo "  - " . $perm['permission'] . "<br>";
            }
        } catch (Exception $e) {
            echo "Error getting permissions: " . $e->getMessage() . "<br>";
        }
        
        // Check allowed modules
        echo "<h4>Allowed Modules from Database:</h4>";
        try {
            $modules = $CI->auth->allow_module($role_id);
            echo "Modules for role $role_id:<br>";
            foreach ($modules as $module) {
                echo "  - " . $module->module_name . " (" . $module->module_description . ")<br>";
            }
        } catch (Exception $e) {
            echo "Error getting modules: " . $e->getMessage() . "<br>";
        }
        
    } else {
        echo "User is NOT logged in.<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
