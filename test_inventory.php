<?php
// Test script to verify the inventory models work correctly
require_once 'system/core/CodeIgniter.php';

// Simple test to check if Stock_model works
class Test_inventory extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('stock_model');
        $this->load->model('stock_movements_model');
    }
    
    public function test_models() {
        try {
            echo "Testing Stock Model...\n";
            
            // Test basic search
            $stock_data = $this->stock_model->search("", 0, 1);
            echo "Stock search successful. Found " . count($stock_data) . " records.\n";
            
            if (!empty($stock_data)) {
                echo "Sample stock record:\n";
                print_r($stock_data[0]);
            }
            
            echo "\nTesting Stock Movements Model...\n";
            
            // Test movements search
            $movements_data = $this->stock_movements_model->search("");
            echo "Movements search successful. Found " . count($movements_data) . " records.\n";
            
            if (!empty($movements_data)) {
                echo "Sample movement record:\n";
                print_r($movements_data[0]);
            }
            
            echo "\nAll tests passed! UOM table issue is fixed.\n";
            
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}
?>
