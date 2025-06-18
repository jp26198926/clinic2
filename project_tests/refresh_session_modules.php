<?php
// Quick fix to refresh session modules for inventory_reports access
// This script will refresh the modules in the current session

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Load CodeIgniter
if (!defined('BASEPATH')) {
    require_once(__DIR__ . '/index.php');
    return;
}

$CI =& get_instance();
$CI->load->model('app_details_m', 'ad');
$CI->load->model('authentication_m', 'auth');

try {
    $ad = $CI->ad->get_details();
    $prefix = $ad->session_prefix ?? 'clinic';
    
    // Check if user is logged in
    if (isset($CI->session->userdata[$prefix . '_logged_in'])) {
        $logged_in = $CI->session->userdata[$prefix . '_logged_in'];
        $role_id = $logged_in[$prefix . '_role'];
        
        echo "<h2>Refreshing Session Modules</h2>";
        echo "<p>Current Role ID: $role_id</p>";
        
        // Get fresh modules from database
        $fresh_modules = $CI->auth->allow_module($role_id);
        
        echo "<h3>Fresh Modules from Database:</h3>";
        foreach ($fresh_modules as $module) {
            echo "- " . $module->module_name . " (" . $module->module_description . ")<br>";
        }
        
        // Update session data
        $logged_in[$prefix . '_modules'] = $fresh_modules;
        $logged_in[$prefix . '_parents'] = $CI->auth->allow_parent($role_id);
        
        // Save updated session
        $CI->session->set_userdata($prefix . '_logged_in', $logged_in);
        
        echo "<h3 style='color: green;'>✅ Session Updated Successfully!</h3>";
        echo "<p>You can now access the Inventory Reports module.</p>";
        echo "<p><a href='inventory_reports' target='_blank'>🔗 Test Inventory Reports Access</a></p>";
        echo "<p><a href='dashboard'>🏠 Return to Dashboard</a></p>";
        
    } else {
        echo "<h2 style='color: red;'>❌ User Not Logged In</h2>";
        echo "<p>Please <a href='authentication'>login</a> first.</p>";
    }
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
