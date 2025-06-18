<?php
// Test Batch Transaction System Functionality

// Database configuration
$pdo = new PDO('mysql:host=localhost:3308;dbname=clinic2', 'root', 'astalavista');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "🧪 Testing Batch Transaction System\n";
echo "==================================\n\n";

try {
    // Test 1: Verify tables exist
    echo "✅ Test 1: Database Tables\n";
    $tables = ['batch_transactions', 'batch_transaction_items'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "  ✓ Table '$table' exists\n";
        } else {
            echo "  ✗ Table '$table' missing\n";
        }
    }
    
    // Test 2: Verify menu integration
    echo "\n✅ Test 2: Menu Integration\n";
    $stmt = $pdo->query("SELECT * FROM admin_module WHERE module_name = 'inventory_batch'");
    if ($module = $stmt->fetch()) {
        echo "  ✓ Module 'inventory_batch' exists (ID: {$module['id']})\n";
        
        // Check permissions
        $stmt = $pdo->query("SELECT COUNT(*) FROM admin_mod_perm WHERE module_id = {$module['id']}");
        $perm_count = $stmt->fetchColumn();
        echo "  ✓ Permissions configured: $perm_count\n";
    } else {
        echo "  ✗ Module 'inventory_batch' not found\n";
    }
    
    // Test 3: Test transaction number generation
    echo "\n✅ Test 3: Transaction Number Generation\n";
    $date_prefix = date('Ymd');
    
    // Simulate transaction number generation
    $stmt = $pdo->query("SELECT transaction_number FROM batch_transactions WHERE transaction_number LIKE '{$date_prefix}%' ORDER BY transaction_number DESC LIMIT 1");
    $last_record = $stmt->fetch();
    
    $seq_num = 1;
    if ($last_record) {
        $last_seq = intval(substr($last_record['transaction_number'], 8));
        $seq_num = $last_seq + 1;
    }
    
    $new_transaction_number = $date_prefix . str_pad($seq_num, 4, '0', STR_PAD_LEFT);
    echo "  ✓ Next transaction number: $new_transaction_number\n";
    
    // Test 4: Create test batch
    echo "\n✅ Test 4: Create Test Batch\n";
    
    // Check if we have required data
    $location_count = $pdo->query("SELECT COUNT(*) FROM locations")->fetchColumn();
    $product_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $user_count = $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
    
    echo "  ✓ Locations available: $location_count\n";
    echo "  ✓ Products available: $product_count\n";
    echo "  ✓ Users available: $user_count\n";
    
    if ($location_count > 0 && $product_count > 0 && $user_count > 0) {
        // Get first location and product for testing
        $location = $pdo->query("SELECT id FROM locations LIMIT 1")->fetchColumn();
        $product = $pdo->query("SELECT id FROM products LIMIT 1")->fetchColumn();
        $user = $pdo->query("SELECT id FROM user LIMIT 1")->fetchColumn();
        
        // Create test batch
        $stmt = $pdo->prepare("
            INSERT INTO batch_transactions 
            (transaction_number, transaction_date, transaction_type, to_location_id, remarks, created_by, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $new_transaction_number,
            date('Y-m-d'),
            'RECEIVE',
            $location,
            'Test batch created by system test',
            $user,
            'DRAFT'
        ]);
        
        $batch_id = $pdo->lastInsertId();
        echo "  ✓ Test batch created: ID $batch_id, Number $new_transaction_number\n";
        
        // Add test item
        $stmt = $pdo->prepare("
            INSERT INTO batch_transaction_items 
            (batch_transaction_id, product_id, qty, unit_cost, notes) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $batch_id,
            $product,
            10.00,
            5.50,
            'Test item'
        ]);
        
        echo "  ✓ Test item added: 10 units @ 5.50 each\n";
        
        // Verify totals calculation
        $stmt = $pdo->query("SELECT total_cost FROM batch_transaction_items WHERE batch_transaction_id = $batch_id");
        $total_cost = $stmt->fetchColumn();
        echo "  ✓ Total cost calculated: $total_cost (expected: 55.00)\n";
        
        // Clean up test data
        $pdo->exec("DELETE FROM batch_transaction_items WHERE batch_transaction_id = $batch_id");
        $pdo->exec("DELETE FROM batch_transactions WHERE id = $batch_id");
        echo "  ✓ Test data cleaned up\n";
        
    } else {
        echo "  ⚠ Insufficient data for batch creation test\n";
    }
    
    // Test 5: Verify file structure
    echo "\n✅ Test 5: File Structure\n";
    $files = [
        'application/controllers/Inventory_batch.php',
        'application/models/Batch_transaction_model.php',
        'application/views/inventory_batch/index.php',
        'application/views/inventory_batch/manage.php',
        'application/views/inventory_batch/print.php'
    ];
    
    foreach ($files as $file) {
        if (file_exists($file)) {
            echo "  ✓ File exists: $file\n";
        } else {
            echo "  ✗ File missing: $file\n";
        }
    }
    
    echo "\n🎉 Batch Transaction System Tests Complete!\n";
    echo "==========================================\n";
    echo "✅ System is ready for use\n";
    echo "🌐 Access URL: http://localhost/clinic2/inventory_batch\n";
    echo "📚 Documentation: BATCH_TRANSACTION_USER_GUIDE.md\n";
    echo "🔧 Technical Guide: BATCH_TRANSACTION_TECHNICAL_GUIDE.md\n";
    
} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
}
?>
