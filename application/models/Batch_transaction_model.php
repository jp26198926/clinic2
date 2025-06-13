<?php
defined('BASEPATH') or die('No direct script access allowed');

class Batch_transaction_model extends CI_Model
{
    private $batch_table = "batch_transactions";
    private $items_table = "batch_transaction_items";

    function search($search = "", $transaction_type = "", $status = "", $date_from = "", $date_to = "", $location_id = 0)
    {
        $this->db->select(
            "bt.*,
             DATE_FORMAT(bt.transaction_date, '%Y-%m-%d') as transaction_date,
             DATE_FORMAT(bt.created_at, '%Y-%m-%d %H:%i') as created_at,
             lf.location as from_location,
             lt.location as to_location,
             CONCAT(u.fname,' ', u.mname,' ', u.lname) as created_by_name"
        );

        $this->db->from("$this->batch_table bt");
        $this->db->join("locations lf", "lf.id=bt.from_location_id", "left");
        $this->db->join("locations lt", "lt.id=bt.to_location_id", "left");
        $this->db->join("user u", "u.id=bt.created_by", "left");
        $this->db->order_by('bt.created_at', 'DESC');

        if ($search) {
            $this->db->where("(
                bt.transaction_number LIKE '%{$search}%' OR
                bt.remarks LIKE '%{$search}%'
            )");
        }

        if ($transaction_type) {
            $this->db->where("bt.transaction_type", $transaction_type);
        }

        if ($status) {
            $this->db->where("bt.status", $status);
        }

        if ($location_id > 0) {
            $this->db->where("(bt.from_location_id = {$location_id} OR bt.to_location_id = {$location_id})");
        }

        if ($date_from && $date_to) {
            $this->db->where("bt.transaction_date >=", $date_from);
            $this->db->where("bt.transaction_date <=", $date_to);
        }

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function get_by_id($id)
    {
        $this->db->select(
            "bt.*,
             DATE_FORMAT(bt.transaction_date, '%Y-%m-%d') as transaction_date,
             DATE_FORMAT(bt.created_at, '%Y-%m-%d %H:%i') as created_at,
             lf.location as from_location,
             lt.location as to_location,
             CONCAT(u.fname,' ', u.mname,' ', u.lname) as created_by_name"
        );

        $this->db->from("$this->batch_table bt");
        $this->db->join("locations lf", "lf.id=bt.from_location_id", "left");
        $this->db->join("locations lt", "lt.id=bt.to_location_id", "left");
        $this->db->join("user u", "u.id=bt.created_by", "left");
        $this->db->where("bt.id", $id);

        if ($query = $this->db->get()) {
            return $query->row();
        } else {
            return false;
        }
    }

    function get_items($batch_id)
    {
        $this->db->select(
            "bti.*,
             p.code as product_code,
             p.name as product_name,
             c.category,
             u.name as uom"
        );

        $this->db->from("$this->items_table bti");
        $this->db->join("products p", "p.id=bti.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->where("bti.batch_transaction_id", $batch_id);
        $this->db->order_by("p.name", "ASC");

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            return array();
        }
    }

    function create_batch($data)
    {
        $this->db->trans_start();

        // Generate transaction number
        $transaction_number = $this->generate_transaction_number();

        $batch_data = array(
            'transaction_number' => $transaction_number,
            'transaction_date' => $data['transaction_date'],
            'transaction_type' => $data['transaction_type'],
            'from_location_id' => $data['from_location_id'] ?? null,
            'to_location_id' => $data['to_location_id'] ?? null,
            'remarks' => $data['remarks'] ?? '',
            'created_by' => $data['created_by'],
            'status' => 'DRAFT'
        );

        $this->db->insert($this->batch_table, $batch_data);
        $batch_id = $this->db->insert_id();

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception("Error: Failed to create batch transaction");
        }

        return array(
            'batch_id' => $batch_id,
            'transaction_number' => $transaction_number
        );
    }

    function add_item($batch_id, $item_data)
    {
        $item_insert = array(
            'batch_transaction_id' => $batch_id,
            'product_id' => $item_data['product_id'],
            'qty' => $item_data['qty'],
            'unit_cost' => $item_data['unit_cost'] ?? 0,
            'notes' => $item_data['notes'] ?? ''
        );

        if ($this->db->insert($this->items_table, $item_insert)) {
            // Update batch totals
            $this->update_batch_totals($batch_id);
            return $this->db->insert_id();
        } else {
            throw new Exception("Error: Failed to add item to batch");
        }
    }

    function update_item($item_id, $item_data)
    {
        $item_update = array(
            'qty' => $item_data['qty'],
            'unit_cost' => $item_data['unit_cost'] ?? 0,
            'notes' => $item_data['notes'] ?? ''
        );

        // Get batch_id before update
        $item = $this->db->get_where($this->items_table, array('id' => $item_id))->row();
        if (!$item) {
            throw new Exception("Error: Item not found");
        }

        $this->db->where('id', $item_id);
        if ($this->db->update($this->items_table, $item_update)) {
            // Update batch totals
            $this->update_batch_totals($item->batch_transaction_id);
            return true;
        } else {
            throw new Exception("Error: Failed to update item");
        }
    }

    function delete_item($item_id)
    {
        // Get batch_id before delete
        $item = $this->db->get_where($this->items_table, array('id' => $item_id))->row();
        if (!$item) {
            throw new Exception("Error: Item not found");
        }

        $this->db->where('id', $item_id);
        if ($this->db->delete($this->items_table)) {
            // Update batch totals
            $this->update_batch_totals($item->batch_transaction_id);
            return true;
        } else {
            throw new Exception("Error: Failed to delete item");
        }
    }

    private function update_batch_totals($batch_id)
    {
        // Calculate totals
        $this->db->select('COUNT(*) as total_items, SUM(qty) as total_qty, SUM(total_cost) as total_cost');
        $this->db->from($this->items_table);
        $this->db->where('batch_transaction_id', $batch_id);
        $totals = $this->db->get()->row();

        // Update batch record
        $update_data = array(
            'total_items' => $totals->total_items,
            'total_qty' => $totals->total_qty ?? 0,
            'total_cost' => $totals->total_cost ?? 0
        );

        $this->db->where('id', $batch_id);
        $this->db->update($this->batch_table, $update_data);
    }

    function process_batch($batch_id, $user_id)
    {
        $this->db->trans_start();

        // Get batch details
        $batch = $this->get_by_id($batch_id);
        if (!$batch || $batch->status !== 'DRAFT') {
            throw new Exception("Error: Invalid batch or batch already processed");
        }

        // Get batch items
        $items = $this->get_items($batch_id);
        if (empty($items)) {
            throw new Exception("Error: No items in batch to process");
        }

        $this->load->model('stock_movements_model');

        foreach ($items as $item) {
            try {
                switch ($batch->transaction_type) {
                    case 'RECEIVE':
                        $this->stock_movements_model->receive_stock(
                            $item->product_id,
                            $batch->to_location_id,
                            $item->qty,
                            $item->unit_cost,
                            'BATCH_RECEIVE',
                            $batch_id,
                            $user_id,
                            "Batch: {$batch->transaction_number} - {$item->notes}"
                        );
                        break;

                    case 'RELEASE':
                        $this->stock_movements_model->release_stock(
                            $item->product_id,
                            $batch->from_location_id,
                            $item->qty,
                            'BATCH_RELEASE',
                            $batch_id,
                            $user_id,
                            "Batch: {$batch->transaction_number} - {$item->notes}"
                        );
                        break;

                    case 'TRANSFER':
                        $this->stock_movements_model->transfer_stock(
                            $item->product_id,
                            $batch->from_location_id,
                            $batch->to_location_id,
                            $item->qty,
                            $user_id,
                            "Batch: {$batch->transaction_number} - {$item->notes}"
                        );
                        break;
                }
            } catch (Exception $e) {
                // Mark batch as failed and throw exception
                $this->db->where('id', $batch_id);
                $this->db->update($this->batch_table, array(
                    'status' => 'CANCELLED',
                    'remarks' => $batch->remarks . "\n\nProcessing failed: " . $e->getMessage()
                ));
                throw new Exception("Error processing item {$item->product_code}: " . $e->getMessage());
            }
        }

        // Mark batch as completed
        $this->db->where('id', $batch_id);
        $this->db->update($this->batch_table, array(
            'status' => 'COMPLETED',
            'processed_at' => date('Y-m-d H:i:s')
        ));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception("Error: Failed to process batch transaction");
        }

        return true;
    }

    function cancel_batch($batch_id, $reason = '')
    {
        $batch = $this->get_by_id($batch_id);
        if (!$batch) {
            throw new Exception("Error: Batch not found");
        }
        
        if ($batch->status !== 'DRAFT' && $batch->status !== 'COMPLETED') {
            throw new Exception("Error: Cannot cancel this batch - invalid status");
        }

        // If batch is COMPLETED, we need to reverse stock movements
        if ($batch->status === 'COMPLETED') {
            $this->reverse_stock_movements($batch_id, $reason);
        }

        $update_data = array(
            'status' => 'CANCELLED',
            'remarks' => $batch->remarks . "\n\nCancelled: " . $reason . " (Date: " . date('Y-m-d H:i:s') . ")"
        );

        $this->db->where('id', $batch_id);
        if ($this->db->update($this->batch_table, $update_data)) {
            return true;
        } else {
            throw new Exception("Error: Failed to cancel batch");
        }
    }

    private function reverse_stock_movements($batch_id, $reason = '')
    {
        // Load stock movement model and stock model
        $this->load->model('stock_movements_model', 'stock_movements');
        $this->load->model('stock_model');
        
        // Get batch details
        $batch = $this->get_by_id($batch_id);
        $items = $this->get_items($batch_id);
        
        foreach ($items as $item) {
            // Create reverse stock movement based on transaction type
            switch ($batch->transaction_type) {
                case 'RECEIVE':
                    // Reverse RECEIVE - subtract from stock (negative movement)
                    $movement_data = array(
                        'date' => date('Y-m-d'),
                        'product_id' => $item->product_id,
                        'location_id' => $batch->to_location_id,
                        'movement_type' => 'ADJUSTMENT',
                        'qty' => -$item->qty, // Negative to reverse
                        'unit_cost' => $item->unit_cost,
                        'reference_type' => 'BATCH_CANCEL',
                        'reference_id' => $batch_id,
                        'notes' => "Cancelled batch: " . $batch->transaction_number . " - " . $reason,
                        'created_by' => 1 // TODO: Use actual user ID
                    );
                    
                    $this->stock_movements->add_movement($movement_data);
                    $this->stock_model->update_stock($item->product_id, $batch->to_location_id, $item->qty, 'subtract');
                    break;
                    
                case 'RELEASE':
                    // Reverse RELEASE - add back to stock (positive movement)
                    $movement_data = array(
                        'date' => date('Y-m-d'),
                        'product_id' => $item->product_id,
                        'location_id' => $batch->from_location_id,
                        'movement_type' => 'ADJUSTMENT',
                        'qty' => $item->qty, // Positive to add back
                        'unit_cost' => $item->unit_cost,
                        'reference_type' => 'BATCH_CANCEL',
                        'reference_id' => $batch_id,
                        'notes' => "Cancelled batch: " . $batch->transaction_number . " - " . $reason,
                        'created_by' => 1 // TODO: Use actual user ID
                    );
                    
                    $this->stock_movements->add_movement($movement_data);
                    $this->stock_model->update_stock($item->product_id, $batch->from_location_id, $item->qty, 'add');
                    break;
                    
                case 'TRANSFER':
                    // Reverse TRANSFER - add back to source
                    $movement_data_source = array(
                        'date' => date('Y-m-d'),
                        'product_id' => $item->product_id,
                        'location_id' => $batch->from_location_id,
                        'movement_type' => 'ADJUSTMENT',
                        'qty' => $item->qty, // Positive to add back to source
                        'unit_cost' => $item->unit_cost,
                        'reference_type' => 'BATCH_CANCEL',
                        'reference_id' => $batch_id,
                        'transfer_from_location_id' => $batch->to_location_id,
                        'transfer_to_location_id' => $batch->from_location_id,
                        'notes' => "Cancelled batch: " . $batch->transaction_number . " - " . $reason,
                        'created_by' => 1 // TODO: Use actual user ID
                    );
                    
                    $this->stock_movements->add_movement($movement_data_source);
                    $this->stock_model->update_stock($item->product_id, $batch->from_location_id, $item->qty, 'add');
                    
                    // Reverse TRANSFER - subtract from destination
                    $movement_data_dest = array(
                        'date' => date('Y-m-d'),
                        'product_id' => $item->product_id,
                        'location_id' => $batch->to_location_id,
                        'movement_type' => 'ADJUSTMENT',
                        'qty' => -$item->qty, // Negative to subtract from destination
                        'unit_cost' => $item->unit_cost,
                        'reference_type' => 'BATCH_CANCEL',
                        'reference_id' => $batch_id,
                        'transfer_from_location_id' => $batch->to_location_id,
                        'transfer_to_location_id' => $batch->from_location_id,
                        'notes' => "Cancelled batch: " . $batch->transaction_number . " - " . $reason,
                        'created_by' => 1 // TODO: Use actual user ID
                    );
                    
                    $this->stock_movements->add_movement($movement_data_dest);
                    $this->stock_model->update_stock($item->product_id, $batch->to_location_id, $item->qty, 'subtract');
                    break;
            }
        }
    }

    private function generate_transaction_number()
    {
        $date_prefix = date('Ymd');
        
        // Get the next sequence number for today
        $this->db->select('transaction_number');
        $this->db->from($this->batch_table);
        $this->db->like('transaction_number', $date_prefix, 'after');
        $this->db->order_by('transaction_number', 'DESC');
        $this->db->limit(1);
        
        $last_record = $this->db->get()->row();
        
        $seq_num = 1;
        if ($last_record) {
            $last_seq = intval(substr($last_record->transaction_number, 8));
            $seq_num = $last_seq + 1;
        }
        
        return $date_prefix . str_pad($seq_num, 4, '0', STR_PAD_LEFT);
    }

    function get_batch_print_data($batch_id)
    {
        $batch = $this->get_by_id($batch_id);
        if (!$batch) {
            throw new Exception("Error: Batch not found");
        }

        $items = $this->get_items($batch_id);
        
        return array(
            'batch' => $batch,
            'items' => $items
        );
    }

    function create_batch_with_items($batch_data, $items, $user_id)
    {
        $this->db->trans_start();

        // Generate transaction number
        $transaction_number = $this->generate_transaction_number();

        // Prepare batch data
        $batch_insert = array(
            'transaction_number' => $transaction_number,
            'transaction_date' => $batch_data['transaction_date'],
            'transaction_type' => $batch_data['transaction_type'],
            'from_location_id' => $batch_data['from_location_id'],
            'to_location_id' => $batch_data['to_location_id'],
            'remarks' => $batch_data['remarks'],
            'created_by' => $batch_data['created_by'],
            'status' => 'COMPLETED',
            'processed_at' => date('Y-m-d H:i:s'),
            'processed_by' => $user_id
        );

        $this->db->insert($this->batch_table, $batch_insert);
        $batch_id = $this->db->insert_id();

        if (!$batch_id) {
            throw new Exception("Error: Failed to create batch transaction");
        }

        // Add items
        $total_items = 0;
        $total_qty = 0;
        $total_cost = 0;

        foreach ($items as $item) {
            $item_insert = array(
                'batch_transaction_id' => $batch_id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'unit_cost' => $item['unit_cost'],
                'notes' => $item['notes']
            );

            $this->db->insert($this->items_table, $item_insert);
            
            if (!$this->db->insert_id()) {
                throw new Exception("Error: Failed to add item to batch");
            }

            $total_items++;
            $total_qty += $item['qty'];
            $total_cost += ($item['qty'] * $item['unit_cost']);
        }

        // Update batch totals
        $this->db->where('id', $batch_id);
        $this->db->update($this->batch_table, array(
            'total_items' => $total_items,
            'total_qty' => $total_qty,
            'total_cost' => $total_cost
        ));

        // Process stock movements immediately
        $this->process_stock_movements($batch_id, $user_id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception("Error: Failed to create and process batch transaction");
        }

        return array(
            'batch_id' => $batch_id,
            'transaction_number' => $transaction_number
        );
    }

    function process_stock_movements($batch_id, $user_id)
    {
        // Get batch details
        $batch = $this->get_by_id($batch_id);
        if (!$batch) {
            throw new Exception("Error: Batch not found");
        }

        // Get batch items
        $items = $this->get_items($batch_id);
        if (empty($items)) {
            throw new Exception("Error: No items found in batch");
        }

        // Load stock movements model
        $this->load->model('stock_movements_model');

        foreach ($items as $item) {
            try {
                switch ($batch->transaction_type) {
                    case 'RECEIVE':
                        $this->stock_movements_model->receive_stock(
                            $item->product_id,
                            $batch->to_location_id,
                            $item->qty,
                            $item->unit_cost,
                            'BATCH_RECEIVE',
                            $batch_id,
                            $user_id,
                            "Batch: {$batch->transaction_number} - {$item->notes}"
                        );
                        break;

                    case 'RELEASE':
                        $this->stock_movements_model->release_stock(
                            $item->product_id,
                            $batch->from_location_id,
                            $item->qty,
                            'BATCH_RELEASE',
                            $batch_id,
                            $user_id,
                            "Batch: {$batch->transaction_number} - {$item->notes}"
                        );
                        break;

                    case 'TRANSFER':
                        $this->stock_movements_model->transfer_stock(
                            $item->product_id,
                            $batch->from_location_id,
                            $batch->to_location_id,
                            $item->qty,
                            $user_id,
                            "Batch: {$batch->transaction_number} - {$item->notes}"
                        );
                        break;

                    default:
                        throw new Exception("Error: Invalid transaction type: {$batch->transaction_type}");
                }
            } catch (Exception $ex) {
                throw new Exception("Error processing item {$item->product_code}: " . $ex->getMessage());
            }
        }

        return true;
    }
}
