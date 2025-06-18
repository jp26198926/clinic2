<?php
// Debug script to check PDF export data availability
defined('BASEPATH') or die("No direct script allowed!");

// Start session and load CI
require_once('index.php');

// Get CI instance
$CI =& get_instance();

// Load models
$CI->load->model('stock_model');
$CI->load->model('data_location_model');
$CI->load->model('app_details_m', 'ad');

echo "<h1>Debug: PDF Export Data Check</h1>";

// Test 1: Check if models are loaded
echo "<h2>Test 1: Model Loading</h2>";
if (isset($CI->stock_model)) {
    echo "<p style='color: green;'>✓ stock_model loaded successfully</p>";
} else {
    echo "<p style='color: red;'>✗ stock_model failed to load</p>";
}

if (isset($CI->data_location_model)) {
    echo "<p style='color: green;'>✓ data_location_model loaded successfully</p>";
} else {
    echo "<p style='color: red;'>✗ data_location_model failed to load</p>";
}

// Test 2: Check if data exists
echo "<h2>Test 2: Data Availability</h2>";

try {
    // Test low stock data
    $low_stock_data = $CI->stock_model->get_low_stock(0);
    echo "<p style='color: blue;'>Low Stock Records: " . count($low_stock_data) . "</p>";
    
    if (count($low_stock_data) > 0) {
        echo "<p>Sample Low Stock Record:</p>";
        echo "<pre>" . print_r($low_stock_data[0], true) . "</pre>";
    }
    
    // Test zero stock data
    $zero_stock_data = $CI->stock_model->get_zero_stock(0);
    echo "<p style='color: blue;'>Zero Stock Records: " . count($zero_stock_data) . "</p>";
    
    if (count($zero_stock_data) > 0) {
        echo "<p>Sample Zero Stock Record:</p>";
        echo "<pre>" . print_r($zero_stock_data[0], true) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error getting data: " . $e->getMessage() . "</p>";
}

// Test 3: Check PDF library
echo "<h2>Test 3: PDF Library Check</h2>";
try {
    $CI->load->library('pdf');
    echo "<p style='color: green;'>✓ PDF library loaded successfully</p>";
    
    // Check if Pdf class exists
    if (class_exists('Pdf')) {
        echo "<p style='color: green;'>✓ Pdf class is available</p>";
    } else {
        echo "<p style='color: red;'>✗ Pdf class not found</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ PDF library error: " . $e->getMessage() . "</p>";
}

// Test 4: Check app details
echo "<h2>Test 4: App Details</h2>";
try {
    $app_details = $CI->ad->get_details();
    if ($app_details) {
        echo "<p style='color: green;'>✓ App details loaded</p>";
        echo "<p>Currency Code: " . ($app_details->currency_code ?? 'N/A') . "</p>";
    } else {
        echo "<p style='color: red;'>✗ App details not found</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>App details error: " . $e->getMessage() . "</p>";
}

// Test 5: Check session data
echo "<h2>Test 5: Session Data</h2>";
if ($CI->session->userdata) {
    echo "<p style='color: green;'>✓ Session data available</p>";
    
    // Try to find session prefix
    $session_data = $CI->session->all_userdata();
    foreach ($session_data as $key => $value) {
        if (strpos($key, '_logged_in') !== false) {
            $prefix = str_replace('_logged_in', '', $key);
            echo "<p>Found session prefix: $prefix</p>";
            break;
        }
    }
} else {
    echo "<p style='color: red;'>✗ No session data available</p>";
}

echo "<hr>";
echo "<p><a href='inventory_reports'>← Back to Inventory Reports</a></p>";
?>
