<?php
echo "Testing database connection...\n";

// Database configuration from CodeIgniter
$configs = [
    ['localhost:3308', 'root', 'astalavista', 'clinic2'],
];

foreach ($configs as $config) {
    list($host, $user, $pass, $db) = $config;
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "✅ Connected to database: $db on $host\n";
        
        // Check if required tables exist
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "Found " . count($tables) . " tables\n";
        
        // Check for key tables
        $key_tables = ['user', 'locations', 'products', 'stock_movements'];
        $found_tables = [];
        
        foreach ($key_tables as $table) {
            if (in_array($table, $tables)) {
                $found_tables[] = $table;
            }
        }
        
        echo "Key tables found: " . implode(', ', $found_tables) . "\n";
        
        if (count($found_tables) >= 3) {
            echo "✅ This appears to be the correct database!\n";
            echo "Database: $db\n";
            
            // Now try to create batch transaction tables
            echo "\nCreating batch transaction tables...\n";
            
            $sql_batch_table = "
            CREATE TABLE IF NOT EXISTS `batch_transactions` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `transaction_number` varchar(20) NOT NULL,
              `transaction_date` date NOT NULL,
              `transaction_type` enum('RECEIVE','RELEASE','TRANSFER') NOT NULL,
              `from_location_id` int(11) NULL,
              `to_location_id` int(11) NULL,
              `remarks` text NULL,
              `status` enum('DRAFT','COMPLETED','CANCELLED') NOT NULL DEFAULT 'DRAFT',
              `total_items` int(11) NOT NULL DEFAULT 0,
              `total_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
              `total_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
              `created_by` int(11) NOT NULL,
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `processed_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `transaction_number` (`transaction_number`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
            
            $sql_items_table = "
            CREATE TABLE IF NOT EXISTS `batch_transaction_items` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `batch_transaction_id` int(11) NOT NULL,
              `product_id` int(11) NOT NULL,
              `qty` decimal(10,2) NOT NULL,
              `unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
              `total_cost` decimal(15,2) GENERATED ALWAYS AS (`qty` * `unit_cost`) STORED,
              `notes` text NULL,
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
            
            try {
                $pdo->exec($sql_batch_table);
                echo "✅ Created batch_transactions table\n";
                
                $pdo->exec($sql_items_table);
                echo "✅ Created batch_transaction_items table\n";
                
                echo "\n🎉 Batch Transaction system is ready!\n";
                echo "You can now access: http://localhost/clinic2/inventory_batch\n";
                
            } catch (PDOException $e) {
                echo "⚠ Error creating tables: " . $e->getMessage() . "\n";
            }
            
            break;
        }
        
        echo "\n";
        
    } catch (PDOException $e) {
        echo "❌ Failed to connect to $db: " . $e->getMessage() . "\n";
    }
}
?>
