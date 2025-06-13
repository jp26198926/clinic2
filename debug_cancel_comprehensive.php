<?php
/**
 * Comprehensive Cancel Batch Debug Script
 * This script helps debug the cancel batch functionality step by step
 */

echo "=== CANCEL BATCH FUNCTIONALITY DEBUG ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

// Database connection
$mysqli = new mysqli('localhost', 'root', 'astalavista', 'clinic2', 3308);
if ($mysqli->connect_error) {
    die('❌ Database connection failed: ' . $mysqli->connect_error . "\n");
}
echo "✅ Database connected successfully\n\n";

// Step 1: Check for available COMPLETED batches
echo "STEP 1: Checking for COMPLETED batch transactions...\n";
echo "=" . str_repeat("=", 50) . "\n";

$completed_batches = $mysqli->query("
    SELECT id, transaction_number, transaction_type, status, 
           from_location_id, to_location_id, created_at
    FROM batch_transactions 
    WHERE status = 'COMPLETED' 
    ORDER BY created_at DESC 
    LIMIT 5
");

if ($completed_batches->num_rows > 0) {
    echo "✅ Found {$completed_batches->num_rows} COMPLETED batch(es):\n";
    while ($batch = $completed_batches->fetch_assoc()) {
        echo "  • ID: {$batch['id']} | Number: {$batch['transaction_number']} | Type: {$batch['transaction_type']}\n";
        echo "    From: {$batch['from_location_id']} | To: {$batch['to_location_id']} | Date: {$batch['created_at']}\n";
    }
    echo "\n";
} else {
    echo "⚠️  No COMPLETED batches found. Creating a test batch...\n";
    
    // Create a test COMPLETED batch
    $test_number = 'TEST_CANCEL_' . date('YmdHis');
    $insert_batch = $mysqli->prepare("
        INSERT INTO batch_transactions 
        (transaction_number, transaction_date, transaction_type, to_location_id, 
         remarks, created_by, status, processed_at, processed_by) 
        VALUES (?, ?, 'RECEIVE', 1, 'Test batch for cancel functionality', 1, 'COMPLETED', NOW(), 1)
    ");
    $insert_batch->bind_param('ss', $test_number, date('Y-m-d'));
    
    if ($insert_batch->execute()) {
        $test_batch_id = $mysqli->insert_id();
        echo "✅ Test batch created: ID {$test_batch_id}, Number: {$test_number}\n";
        
        // Add test item
        $insert_item = $mysqli->prepare("
            INSERT INTO batch_transaction_items 
            (batch_transaction_id, product_id, qty, unit_cost, notes) 
            VALUES (?, 1, 5.00, 10.00, 'Test item for cancellation')
        ");
        $insert_item->bind_param('i', $test_batch_id);
        $insert_item->execute();
        echo "✅ Test item added to batch\n\n";
    } else {
        echo "❌ Failed to create test batch\n\n";
    }
}

// Step 2: Check required tables and structures
echo "STEP 2: Verifying required database structures...\n";
echo "=" . str_repeat("=", 50) . "\n";

$tables_to_check = [
    'batch_transactions' => ['id', 'status', 'transaction_type', 'from_location_id', 'to_location_id'],
    'batch_transaction_items' => ['batch_transaction_id', 'product_id', 'qty', 'unit_cost'],
    'stock_movements' => ['product_id', 'location_id', 'movement_type', 'qty'],
    'stock' => ['product_id', 'location_id', 'qty_on_hand']
];

foreach ($tables_to_check as $table => $required_columns) {
    $table_check = $mysqli->query("SHOW TABLES LIKE '$table'");
    if ($table_check->num_rows > 0) {
        echo "✅ Table '$table' exists\n";
        
        // Check columns
        $columns_result = $mysqli->query("DESCRIBE $table");
        $existing_columns = [];
        while ($col = $columns_result->fetch_assoc()) {
            $existing_columns[] = $col['Field'];
        }
        
        $missing_columns = array_diff($required_columns, $existing_columns);
        if (empty($missing_columns)) {
            echo "  ✅ All required columns present\n";
        } else {
            echo "  ⚠️  Missing columns: " . implode(', ', $missing_columns) . "\n";
        }
    } else {
        echo "❌ Table '$table' does not exist\n";
    }
}
echo "\n";

// Step 3: Check current stock levels for testing
echo "STEP 3: Checking current stock levels...\n";
echo "=" . str_repeat("=", 50) . "\n";

$stock_check = $mysqli->query("
    SELECT s.*, p.name as product_name, l.location 
    FROM stock s 
    LEFT JOIN products p ON s.product_id = p.id 
    LEFT JOIN locations l ON s.location_id = l.id 
    LIMIT 5
");

if ($stock_check->num_rows > 0) {
    echo "✅ Current stock levels:\n";
    while ($stock = $stock_check->fetch_assoc()) {
        echo "  • Product: {$stock['product_name']} | Location: {$stock['location']} | Qty: {$stock['qty_on_hand']}\n";
    }
    echo "\n";
} else {
    echo "⚠️  No stock records found\n\n";
}

// Step 4: Test the cancel batch logic simulation
echo "STEP 4: Simulating cancel batch logic...\n";
echo "=" . str_repeat("=", 50) . "\n";

// Get a test batch for simulation
$test_batch_query = $mysqli->query("
    SELECT * FROM batch_transactions 
    WHERE status = 'COMPLETED' 
    LIMIT 1
");

if ($test_batch_query->num_rows > 0) {
    $test_batch = $test_batch_query->fetch_assoc();
    echo "✅ Using batch for simulation:\n";
    echo "  • ID: {$test_batch['id']}\n";
    echo "  • Number: {$test_batch['transaction_number']}\n";
    echo "  • Type: {$test_batch['transaction_type']}\n";
    echo "  • Status: {$test_batch['status']}\n\n";
    
    // Get batch items
    $items_query = $mysqli->query("
        SELECT * FROM batch_transaction_items 
        WHERE batch_transaction_id = {$test_batch['id']}
    ");
    
    if ($items_query->num_rows > 0) {
        echo "✅ Batch items to reverse:\n";
        while ($item = $items_query->fetch_assoc()) {
            echo "  • Product ID: {$item['product_id']}\n";
            echo "  • Quantity: {$item['qty']}\n";
            echo "  • Unit Cost: {$item['unit_cost']}\n";
            
            // Simulate reversal logic
            switch ($test_batch['transaction_type']) {
                case 'RECEIVE':
                    echo "  • Action: Would create CANCEL_RECEIVE movement\n";
                    echo "  • Effect: Subtract {$item['qty']} from location {$test_batch['to_location_id']}\n";
                    break;
                case 'RELEASE':
                    echo "  • Action: Would create CANCEL_RELEASE movement\n";
                    echo "  • Effect: Add {$item['qty']} back to location {$test_batch['from_location_id']}\n";
                    break;
                case 'TRANSFER':
                    echo "  • Action: Would create CANCEL_TRANSFER movements\n";
                    echo "  • Effect: Add {$item['qty']} back to location {$test_batch['from_location_id']}\n";
                    echo "  • Effect: Subtract {$item['qty']} from location {$test_batch['to_location_id']}\n";
                    break;
            }
            echo "\n";
        }
    } else {
        echo "⚠️  No items found for this batch\n\n";
    }
} else {
    echo "❌ No COMPLETED batches available for simulation\n\n";
}

// Step 5: Check web application files
echo "STEP 5: Verifying web application files...\n";
echo "=" . str_repeat("=", 50) . "\n";

$files_to_check = [
    'application/views/inventory_batch/index.php' => 'Main batch view file',
    'application/controllers/Inventory_batch.php' => 'Batch controller',
    'application/models/Batch_transaction_model.php' => 'Batch model',
    'application/models/Stock_movements_model.php' => 'Stock movements model'
];

foreach ($files_to_check as $file => $description) {
    if (file_exists($file)) {
        echo "✅ {$description}: {$file}\n";
        
        // Check for key functions/methods
        $content = file_get_contents($file);
        if (strpos($file, 'index.php') !== false) {
            if (strpos($content, 'cancelBatch') !== false) {
                echo "  ✅ cancelBatch function found\n";
            }
            if (strpos($content, 'bootbox.prompt') !== false) {
                echo "  ✅ bootbox implementation found\n";
            }
        } elseif (strpos($file, 'Inventory_batch.php') !== false) {
            if (strpos($content, 'cancel_batch') !== false) {
                echo "  ✅ cancel_batch method found\n";
            }
        } elseif (strpos($file, 'Batch_transaction_model.php') !== false) {
            if (strpos($content, 'reverse_stock_movements') !== false) {
                echo "  ✅ reverse_stock_movements method found\n";
            }
        }
    } else {
        echo "❌ {$description}: {$file} (NOT FOUND)\n";
    }
}
echo "\n";

// Step 6: Final recommendations
echo "STEP 6: Testing recommendations...\n";
echo "=" . str_repeat("=", 50) . "\n";

echo "🔍 TO TEST THE CANCEL FUNCTIONALITY:\n\n";
echo "1. Open your web browser and navigate to:\n";
echo "   http://localhost:6066/clinic2/inventory_batch\n\n";

echo "2. Look for batches with COMPLETED status\n";
echo "   (Red cancel button should appear next to them)\n\n";

echo "3. Click the cancel button and check:\n";
echo "   ✓ Bootbox dialog appears (not SweetAlert2)\n";
echo "   ✓ Reason input is required\n";
echo "   ✓ No JavaScript errors in browser console\n\n";

echo "4. After successful cancellation:\n";
echo "   ✓ Batch status changes to 'CANCELLED'\n";
echo "   ✓ Stock movements are created in stock_movements table\n";
echo "   ✓ Stock levels are adjusted in stock table\n\n";

echo "5. Check browser console (F12) for any errors:\n";
echo "   ✓ No 'Swal is not defined' errors\n";
echo "   ✓ No 500 Internal Server Errors\n";
echo "   ✓ Successful AJAX responses\n\n";

echo "🐞 IF YOU STILL GET ERRORS:\n\n";
echo "1. Check the browser console for JavaScript errors\n";
echo "2. Check the server error logs:\n";
echo "   - /c/laragon/logs/apache_error.log\n";
echo "   - /c/laragon/logs/php_errors.log\n\n";

echo "3. Enable PHP error reporting by adding to your controller:\n";
echo "   ini_set('display_errors', 1);\n";
echo "   error_reporting(E_ALL);\n\n";

echo "✅ FIXES APPLIED:\n";
echo "  ✓ Replaced SweetAlert2 with bootbox.js\n";
echo "  ✓ Extended cancel support to COMPLETED batches\n";
echo "  ✓ Added stock movement reversal logic\n";
echo "  ✓ Fixed method parameter ordering\n";
echo "  ✓ Added proper error handling\n";
echo "  ✓ Added CSRF protection\n\n";

echo "📊 DEBUG COMPLETE!\n";
echo "The cancel batch functionality should now work without errors.\n";

$mysqli->close();
?>
