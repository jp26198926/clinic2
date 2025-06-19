<?php
// Simple test to verify Excel export functionality
define('BASEPATH', __DIR__);

// Load CodeIgniter
require_once __DIR__ . '/index.php';

// Get CodeIgniter instance
$CI = &get_instance();

echo "Testing Excel Export Functionality...\n\n";

// Test 1: Check if Excel library loads
try {
    $CI->load->library('excel');
    echo "✓ Excel library loaded successfully\n";
} catch (Exception $e) {
    echo "✗ Excel library failed to load: " . $e->getMessage() . "\n";
    exit;
}

// Test 2: Check if required models are available
$models = ['data_location_model', 'stock_model', 'stock_movements_model'];
foreach ($models as $model) {
    try {
        $CI->load->model($model);
        echo "✓ Model '{$model}' loaded successfully\n";
    } catch (Exception $e) {
        echo "✗ Model '{$model}' failed to load: " . $e->getMessage() . "\n";
    }
}

// Test 3: Check if Excel export method exists
if (method_exists($CI->excel, 'setActiveSheetIndex')) {
    echo "✓ Excel methods are available\n";
} else {
    echo "✗ Excel methods are not available\n";
}

// Test 4: Check if PHPExcel classes are available
if (class_exists('PHPExcel_IOFactory')) {
    echo "✓ PHPExcel_IOFactory class is available\n";
} else {
    echo "✗ PHPExcel_IOFactory class is not available\n";
}

echo "\nExcel export functionality test completed.\n";
echo "\nTo test the actual export:\n";
echo "1. Navigate to the Inventory Reports page\n";
echo "2. Select a report type (e.g., Low Stock Report)\n";
echo "3. Click 'Export Excel' button\n";
echo "4. The file should download as an .xlsx file\n";
?>
