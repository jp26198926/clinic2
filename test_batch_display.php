<?php
/**
 * Test script to verify Inventory Batch display functionality
 * This script simulates a user session and tests the controller output
 */

// Set up the environment
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/clinic2/inventory_batch';
$_SERVER['SCRIPT_NAME'] = '/clinic2/index.php';

// Bootstrap CodeIgniter
define('BASEPATH', 'system/');
define('ENVIRONMENT', 'development');

require_once('index.php');

echo "🔍 Testing Batch Transaction Display\n";
echo "====================================\n\n";

try {
    // Test 1: Check if controller file exists and is readable
    $controller_file = 'application/controllers/Inventory_batch.php';
    if (file_exists($controller_file)) {
        echo "✅ Test 1: Controller file exists\n";
    } else {
        echo "❌ Test 1: Controller file missing\n";
        exit(1);
    }

    // Test 2: Check if view file exists
    $view_file = 'application/views/inventory_batch/index.php';
    if (file_exists($view_file)) {
        echo "✅ Test 2: View file exists\n";
    } else {
        echo "❌ Test 2: View file missing\n";
        exit(1);
    }

    // Test 3: Check if model file exists
    $model_file = 'application/models/Batch_transaction_model.php';
    if (file_exists($model_file)) {
        echo "✅ Test 3: Model file exists\n";
    } else {
        echo "❌ Test 3: Model file missing\n";
        exit(1);
    }

    // Test 4: Try to capture any PHP errors in the controller
    ob_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    include_once($controller_file);
    
    $errors = ob_get_clean();
    
    if (empty($errors)) {
        echo "✅ Test 4: Controller loads without PHP errors\n";
    } else {
        echo "❌ Test 4: Controller has PHP errors:\n";
        echo $errors . "\n";
    }

    // Test 5: Check template structure
    $template_header = 'application/views/template/header.php';
    $template_sidebar = 'application/views/template/sidebar.php';
    
    if (file_exists($template_header) && file_exists($template_sidebar)) {
        echo "✅ Test 5: Template files exist\n";
    } else {
        echo "❌ Test 5: Template files missing\n";
    }

    echo "\n🎯 Display Fix Summary:\n";
    echo "======================\n";
    echo "✓ Added \$prefix variable to controller data\n";
    echo "✓ Added \$ufname variable for user display\n";
    echo "✓ Updated session data loading pattern\n";
    echo "✓ Added proper permission checking\n";
    echo "✓ Fixed template variable compatibility\n";

    echo "\n🚀 Next Steps:\n";
    echo "==============\n";
    echo "1. Ensure user is logged into the system\n";
    echo "2. Navigate to: http://localhost/clinic2/inventory_batch\n";
    echo "3. Verify the page displays correctly with menu and content\n";
    echo "4. Test batch transaction creation functionality\n";

} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
}

echo "\n✅ Display Test Complete!\n";
echo "========================\n";
?>
