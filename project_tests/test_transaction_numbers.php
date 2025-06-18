<?php
require_once 'index.php';

echo "Testing new transaction number generation with type-specific prefixes\n";
echo "======================================================================\n\n";

// Initialize CI instance
$CI =& get_instance();
$CI->load->model('Batch_transaction_model', 'batch_model');

// Test data arrays
$test_batches = array(
    array(
        'transaction_date' => date('Y-m-d'),
        'transaction_type' => 'RECEIVE',
        'to_location_id' => 1,
        'remarks' => 'Test RECEIVE transaction for new number format',
        'created_by' => 1
    ),
    array(
        'transaction_date' => date('Y-m-d'),
        'transaction_type' => 'RELEASE',
        'from_location_id' => 1,
        'remarks' => 'Test RELEASE transaction for new number format',
        'created_by' => 1
    ),
    array(
        'transaction_date' => date('Y-m-d'),
        'transaction_type' => 'TRANSFER',
        'from_location_id' => 1,
        'to_location_id' => 2,
        'remarks' => 'Test TRANSFER transaction for new number format',
        'created_by' => 1
    ),
    array(
        'transaction_date' => date('Y-m-d'),
        'transaction_type' => 'RECEIVE',
        'to_location_id' => 2,
        'remarks' => 'Second RECEIVE transaction to test incrementing',
        'created_by' => 1
    )
);

try {
    foreach ($test_batches as $index => $batch_data) {
        echo "Creating " . $batch_data['transaction_type'] . " batch...\n";
        
        $result = $CI->batch_model->create_batch($batch_data);
        
        if ($result) {
            echo "✅ Success! Transaction Number: " . $result['transaction_number'] . "\n";
            echo "   Batch ID: " . $result['batch_id'] . "\n";
        } else {
            echo "❌ Failed to create batch\n";
        }
        echo "\n";
    }
    
    echo "Transaction number generation test completed!\n";
    echo "Expected format: REC-0001, REL-0001, TRA-0001, REC-0002\n\n";
    
    // Test multiple of the same type to verify incrementing
    echo "Testing multiple RECEIVE transactions for proper incrementing:\n";
    echo "------------------------------------------------------------\n";
    
    for ($i = 1; $i <= 3; $i++) {
        $batch_data = array(
            'transaction_date' => date('Y-m-d'),
            'transaction_type' => 'RECEIVE',
            'to_location_id' => 1,
            'remarks' => "Test RECEIVE batch #$i for incrementing",
            'created_by' => 1
        );
        
        $result = $CI->batch_model->create_batch($batch_data);
        
        if ($result) {
            echo "✅ RECEIVE #{$i}: " . $result['transaction_number'] . "\n";
        } else {
            echo "❌ Failed to create RECEIVE batch #{$i}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\nTest completed!\n";
?>
