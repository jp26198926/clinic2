<?php
// Simple test to create one more RECEIVE transaction to test auto-increment
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include CodeIgniter bootstrap
define('BASEPATH', TRUE);
define('ENVIRONMENT', 'development');

// Set paths
$system_path = './system';
$application_folder = './application';

// Bootstrap CodeIgniter
require_once $system_path.'/core/CodeIgniter.php';

// Get CI instance
$CI =& get_instance();

try {
    // Load required models
    $CI->load->model('Batch_transaction_model', 'batch_model');
    
    // Test data for a new RECEIVE transaction (should get REC-0002)
    $batch_data = array(
        'transaction_date' => date('Y-m-d'),
        'transaction_type' => 'RECEIVE',
        'to_location_id' => 1,
        'remarks' => 'Testing auto-increment - should be REC-0002',
        'created_by' => 1
    );
    
    echo "Creating RECEIVE transaction (should auto-increment to REC-0002)...\n";
    
    $result = $CI->batch_model->create_batch($batch_data);
    
    if ($result) {
        echo "✅ SUCCESS!\n";
        echo "Transaction Number: " . $result['transaction_number'] . "\n";
        echo "Batch ID: " . $result['batch_id'] . "\n";
        
        if ($result['transaction_number'] === 'REC-0002') {
            echo "✅ Auto-increment working correctly!\n";
        } else {
            echo "⚠️  Expected REC-0002, got " . $result['transaction_number'] . "\n";
        }
    } else {
        echo "❌ Failed to create batch transaction\n";
    }
    
    // Also test RELEASE increment (should get REL-0002)
    $batch_data2 = array(
        'transaction_date' => date('Y-m-d'),
        'transaction_type' => 'RELEASE',
        'from_location_id' => 1,
        'remarks' => 'Testing RELEASE auto-increment - should be REL-0002',
        'created_by' => 1
    );
    
    echo "\nCreating RELEASE transaction (should auto-increment to REL-0002)...\n";
    
    $result2 = $CI->batch_model->create_batch($batch_data2);
    
    if ($result2) {
        echo "✅ SUCCESS!\n";
        echo "Transaction Number: " . $result2['transaction_number'] . "\n";
        echo "Batch ID: " . $result2['batch_id'] . "\n";
        
        if ($result2['transaction_number'] === 'REL-0002') {
            echo "✅ Auto-increment working correctly for RELEASE!\n";
        } else {
            echo "⚠️  Expected REL-0002, got " . $result2['transaction_number'] . "\n";
        }
    } else {
        echo "❌ Failed to create RELEASE batch transaction\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\nTest completed!\n";
?>
