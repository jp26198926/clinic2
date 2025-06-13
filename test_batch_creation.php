<?php
// Test batch transaction creation
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Simulate a simple batch creation request
$test_data = array(
    'transaction_date' => '2025-06-13',
    'transaction_type' => 'RECEIVE',
    'from_location_id' => null,
    'to_location_id' => 11, // assuming location ID 11 exists
    'remarks' => 'Test batch transaction',
    'created_by' => 1
);

$test_items = array(
    array(
        'product_id' => 626, // L001 - Full Blood Count
        'qty' => 10.0,
        'unit_cost' => 0,
        'notes' => 'Test item'
    )
);

// Load CodeIgniter
require_once(BASEPATH.'core/CodeIgniter.php');

// Load the batch model
$CI =& get_instance();
$CI->load->model('batch_transaction_model');

try {
    echo "Testing batch transaction creation...\n";
    echo "Data: " . json_encode($test_data) . "\n";
    echo "Items: " . json_encode($test_items) . "\n";
    
    $result = $CI->batch_transaction_model->create_batch_with_items($test_data, $test_items, 1);
    
    echo "SUCCESS: " . json_encode($result) . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
