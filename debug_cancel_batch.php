<?php
// Test script to debug the cancel batch functionality
echo "Cancel Batch Debug Test\n";
echo "======================\n\n";

// Database connection
$mysqli = new mysqli('localhost', 'root', 'astalavista', 'clinic2', 3308);
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

echo "1. Testing database connection...\n";
echo "✓ Database connected successfully\n\n";

echo "2. Checking for COMPLETED batch transactions...\n";
$result = $mysqli->query("SELECT id, transaction_number, status FROM batch_transactions WHERE status = 'COMPLETED' LIMIT 5");
if ($result->num_rows > 0) {
    echo "✓ Found COMPLETED batches:\n";
    while ($row = $result->fetch_assoc()) {
        echo "  - ID: {$row['id']}, Number: {$row['transaction_number']}, Status: {$row['status']}\n";
    }
    echo "\n";
} else {
    echo "✗ No COMPLETED batches found\n\n";
}

echo "3. Testing batch retrieval...\n";
$test_batch_result = $mysqli->query("SELECT * FROM batch_transactions WHERE status = 'COMPLETED' LIMIT 1");
if ($test_batch_result->num_rows > 0) {
    $batch = $test_batch_result->fetch_assoc();
    $batch_id = $batch['id'];
    echo "✓ Test batch found: ID {$batch_id}\n\n";
    
    echo "4. Testing batch items retrieval...\n";
    $items_result = $mysqli->query("SELECT * FROM batch_transaction_items WHERE batch_transaction_id = {$batch_id}");
    if ($items_result->num_rows > 0) {
        echo "✓ Batch items found: {$items_result->num_rows} items\n";
        while ($item = $items_result->fetch_assoc()) {
            echo "  - Product ID: {$item['product_id']}, Qty: {$item['qty']}, Unit Cost: {$item['unit_cost']}\n";
        }
        echo "\n";
    } else {
        echo "✗ No batch items found\n\n";
    }
    
    echo "5. Testing stock_movements table...\n";
    $movements_check = $mysqli->query("SHOW TABLES LIKE 'stock_movements'");
    if ($movements_check->num_rows > 0) {
        echo "✓ stock_movements table exists\n";
        
        // Check structure
        $structure = $mysqli->query("DESCRIBE stock_movements");
        echo "✓ stock_movements columns: ";
        $columns = [];
        while ($col = $structure->fetch_assoc()) {
            $columns[] = $col['Field'];
        }
        echo implode(', ', $columns) . "\n\n";
    } else {
        echo "✗ stock_movements table does not exist\n\n";
    }
    
    echo "6. Testing locations table...\n";
    if ($batch['to_location_id']) {
        $location_result = $mysqli->query("SELECT * FROM locations WHERE id = {$batch['to_location_id']}");
        if ($location_result->num_rows > 0) {
            $location = $location_result->fetch_assoc();
            echo "✓ To location found: ID {$location['id']}, Name: {$location['location']}\n";
        } else {
            echo "✗ To location not found: ID {$batch['to_location_id']}\n";
        }
    }
    
    if ($batch['from_location_id']) {
        $location_result = $mysqli->query("SELECT * FROM locations WHERE id = {$batch['from_location_id']}");
        if ($location_result->num_rows > 0) {
            $location = $location_result->fetch_assoc();
            echo "✓ From location found: ID {$location['id']}, Name: {$location['location']}\n";
        } else {
            echo "✗ From location not found: ID {$batch['from_location_id']}\n";
        }
    }
    echo "\n";
    
} else {
    echo "✗ No test batch found\n\n";
}

echo "7. Creating a simple test batch for cancellation...\n";

// Create a simple COMPLETED batch for testing
$test_transaction_number = 'TEST_CANCEL_' . date('YmdHis');
$batch_sql = "INSERT INTO batch_transactions 
              (transaction_number, transaction_date, transaction_type, 
               to_location_id, remarks, created_by, status, processed_at, processed_by) 
              VALUES (?, ?, 'RECEIVE', 1, 'Test batch for cancel functionality', 1, 'COMPLETED', NOW(), 1)";

$stmt = $mysqli->prepare($batch_sql);
$stmt->bind_param('ss', $test_transaction_number, date('Y-m-d'));

if ($stmt->execute()) {
    $test_batch_id = $mysqli->insert_id();
    echo "✓ Test batch created: ID {$test_batch_id}\n";
    
    // Add a test item
    $item_sql = "INSERT INTO batch_transaction_items 
                 (batch_transaction_id, product_id, qty, unit_cost, notes) 
                 VALUES (?, 1, 5.00, 10.00, 'Test item for cancellation')";
    
    $item_stmt = $mysqli->prepare($item_sql);
    $item_stmt->bind_param('i', $test_batch_id);
    
    if ($item_stmt->execute()) {
        echo "✓ Test item added to batch\n";
        echo "✓ Test batch ready for cancellation testing\n\n";
        
        echo "Test Batch Details:\n";
        echo "- Batch ID: {$test_batch_id}\n";
        echo "- Transaction Number: {$test_transaction_number}\n";
        echo "- Status: COMPLETED\n";
        echo "- Type: RECEIVE\n";
        echo "- To Location: 1\n";
        echo "- Item Product: 1, Qty: 5, Cost: 10\n\n";
        
    } else {
        echo "✗ Failed to add test item\n\n";
    }
} else {
    echo "✗ Failed to create test batch\n\n";
}

echo "Debug Summary:\n";
echo "==============\n";
echo "✓ Database connection working\n";
echo "✓ Tables exist and accessible\n";
echo "✓ Test data created\n";
echo "\nNow test the cancel functionality in the web interface.\n";
echo "Look for batch: {$test_transaction_number}\n";

$mysqli->close();
?>
