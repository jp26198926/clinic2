<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_stock_model extends CI_Model {

    private $stock_table_name = 'stock';
    private $stock_movements_table_name = 'stock_movements';

    public function search($code, $location, $product_name, $category, $status, $sort_column, $sort_order, $limit, $offset)
    {
        $this->db->select('
            s.stock_id,
            s.product_id,
            s.location_id,
            s.quantity,
            s.min_stock_level,
            s.max_stock_level,
            s.last_updated,
            s.status_id,
            p.code as product_code,
            p.name as product_name,
            p.uom_id,
            c.name as category_name,
            u.name as uom_name,
            l.name as location_name,
            ss.name as status_name,
            CASE 
                WHEN s.quantity <= s.min_stock_level THEN "Low Stock"
                WHEN s.quantity >= s.max_stock_level THEN "Overstock"
                ELSE "Normal"
            END as stock_level_status
        ');
        $this->db->from('stock s');
        $this->db->join('products p', 's.product_id = p.product_id', 'left');
        $this->db->join('categories c', 'p.category_id = c.category_id', 'left');
        $this->db->join('uom u', 'p.uom_id = u.uom_id', 'left');
        $this->db->join('locations l', 's.location_id = l.location_id', 'left');
        $this->db->join('stock_status ss', 's.status_id = ss.status_id', 'left');

        // Apply filters
        if (!empty($code)) {
            $this->db->like('p.code', $code);
        }
        if (!empty($location)) {
            $this->db->where('s.location_id', $location);
        }
        if (!empty($product_name)) {
            $this->db->like('p.name', $product_name);
        }
        if (!empty($category)) {
            $this->db->where('p.category_id', $category);
        }
        if (!empty($status)) {
            $this->db->where('s.status_id', $status);
        }

        // Count total records before applying limit
        $total_query = clone $this->db;
        $total_records = $total_query->count_all_results();

        // Apply sorting
        if (!empty($sort_column) && !empty($sort_order)) {
            $this->db->order_by($sort_column, $sort_order);
        } else {
            $this->db->order_by('p.name', 'ASC');
        }

        // Apply limit and offset
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();
        
        return array(
            'data' => $query->result_array(),
            'total_records' => $total_records
        );
    }

    public function search_info_row($stock_id)
    {
        $this->db->select('
            s.stock_id,
            s.product_id,
            s.location_id,
            s.quantity,
            s.min_stock_level,
            s.max_stock_level,
            s.last_updated,
            s.status_id,
            p.code as product_code,
            p.name as product_name,
            p.uom_id,
            c.name as category_name,
            u.name as uom_name,
            l.name as location_name,
            ss.name as status_name,
            CASE 
                WHEN s.quantity <= s.min_stock_level THEN "Low Stock"
                WHEN s.quantity >= s.max_stock_level THEN "Overstock"
                ELSE "Normal"
            END as stock_level_status
        ');
        $this->db->from('stock s');
        $this->db->join('products p', 's.product_id = p.product_id', 'left');
        $this->db->join('categories c', 'p.category_id = c.category_id', 'left');
        $this->db->join('uom u', 'p.uom_id = u.uom_id', 'left');
        $this->db->join('locations l', 's.location_id = l.location_id', 'left');
        $this->db->join('stock_status ss', 's.status_id = ss.status_id', 'left');
        $this->db->where('s.stock_id', $stock_id);
        
        return $this->db->get()->row_array();
    }

    // public function add($product_id, $location_id, $quantity, $min_stock, $max_stock, $user_id_from_controller) // Old signature
    public function add($data_input, $user_id_from_controller)
    {
        try {
            // Check if stock record already exists for this product and location
            $existing = $this->get_stock_by_product_location($data_input['product_id'], $data_input['location_id']);
            
            if ($existing) {
                return array('status' => 'error', 'message' => 'Stock record already exists for this product and location.');
            }
            
            $db_payload = array();
            // Set model-specific default values
            $db_payload['status_id'] = 1; // Defaulting to active status
            $db_payload['last_updated'] = date('Y-m-d H:i:s');

            // Iterate through $data_input and add relevant fields to $db_payload
            $allowed_keys_for_stock_table = ['product_id', 'location_id', 'quantity', 'min_stock_level', 'max_stock_level'];
            foreach ($data_input as $key => $val) {
                if (in_array($key, $allowed_keys_for_stock_table)) {
                    $db_payload[$key] = $val;
                }
            }
            
            $this->db->insert($this->stock_table_name, $db_payload);
            
            if ($this->db->affected_rows() > 0) {
                $stock_id = $this->db->insert_id();
                
                // Movement_date for initial stock log
                $movement_date = isset($data_input['movement_date']) ? $data_input['movement_date'] : date('Y-m-d');
                
                // Log stock movement - Pass $user_id_from_controller
                // Ensure quantity is from the actual inserted data if there's any transformation
                $quantity_inserted = $db_payload['quantity']; 
                $this->log_stock_movement($stock_id, 'Initial Stock', $quantity_inserted, 0, $quantity_inserted, $movement_date, $user_id_from_controller, 'Initial stock setup');
                
                return array('status' => 'success', 'message' => 'Stock record added successfully.', 'stock_id' => $stock_id);
            } else {
                return array('status' => 'error', 'message' => 'Failed to add stock record.');
            }
        } catch (Exception $e) {
            return array('status' => 'error', 'message' => 'Database error: ' . $e->getMessage());
        }
    }

    // public function update($stock_id, $product_id, $location_id, $quantity, $min_stock, $max_stock, $user_id_from_controller) // Old signature
    public function update($stock_id, $data_input, $user_id_from_controller)
    {
        try {
            // Get current stock data
            $current_stock = $this->get_by_id($stock_id);
            if (!$current_stock) {
                return array('status' => 'error', 'message' => 'Stock record not found.');
            }
            
            // Check if product_id and location_id combination already exists (excluding current record)
            // This check should use values from $data_input if they are being changed.
            $check_product_id = isset($data_input['product_id']) ? $data_input['product_id'] : $current_stock['product_id'];
            $check_location_id = isset($data_input['location_id']) ? $data_input['location_id'] : $current_stock['location_id'];

            if ( (isset($data_input['product_id']) && $data_input['product_id'] != $current_stock['product_id']) || 
                 (isset($data_input['location_id']) && $data_input['location_id'] != $current_stock['location_id']) ) {
                
                $this->db->where('product_id', $check_product_id);
                $this->db->where('location_id', $check_location_id);
                $this->db->where('stock_id !=', $stock_id);
                $existing = $this->db->get($this->stock_table_name)->row();
            
                if ($existing) {
                    return array('status' => 'error', 'message' => 'Stock record already exists for the new product and location combination.');
                }
            }
            
            $db_payload = array();
            // Set model-specific values
            $db_payload['last_updated'] = date('Y-m-d H:i:s');

            // Iterate through $data_input and add relevant fields to $db_payload
            $allowed_keys_for_stock_table = ['product_id', 'location_id', 'quantity', 'min_stock_level', 'max_stock_level'];
            foreach ($data_input as $key => $val) {
                if (in_array($key, $allowed_keys_for_stock_table)) {
                    $db_payload[$key] = $val;
                }
            }
            
            // If $db_payload is empty (no valid keys from $data_input), no update needed for these fields.
            // However, last_updated will still be set. If only last_updated is changed, affected_rows might be 0.
            // The controller sends specific fields, so $db_payload should contain them.

            $this->db->where('stock_id', $stock_id);
            $this->db->update($this->stock_table_name, $db_payload);
            
            $affected_rows = $this->db->affected_rows();

            if ($affected_rows > 0) {
                // Check if quantity was part of the update and changed
                $new_quantity = isset($db_payload['quantity']) ? $db_payload['quantity'] : $current_stock['quantity'];
                
                if (isset($db_payload['quantity']) && $new_quantity != $current_stock['quantity']) {
                    $old_quantity = $current_stock['quantity'];
                    $movement_type = $new_quantity > $old_quantity ? 'Stock Adjustment (+)' : 'Stock Adjustment (-)';
                    $quantity_change = abs($new_quantity - $old_quantity);
                    
                    $movement_date = isset($data_input['movement_date']) ? $data_input['movement_date'] : date('Y-m-d'); 
                    
                    $this->log_stock_movement($stock_id, $movement_type, $quantity_change, $old_quantity, $new_quantity, $movement_date, $user_id_from_controller, 'Manual stock adjustment via update');
                }
                return array('status' => 'success', 'message' => 'Stock record updated successfully.');
            } else {
                // Check if data was actually different from current_stock for the keys in $db_payload
                $no_real_change = true;
                foreach ($db_payload as $key => $value) {
                    if ($key === 'last_updated') continue; // Ignore last_updated for this check
                    if (array_key_exists($key, $current_stock) && $current_stock[$key] != $value) {
                        $no_real_change = false;
                        break;
                    }
                }
                if ($no_real_change && $affected_rows === 0) { // affected_rows can be 0 if data is same
                    return array('status' => 'info', 'message' => 'No changes were made to the stock details.');
                }
                // If affected_rows is 0 but data was different, it might be an update failure not caught by exception
                return array('status' => 'error', 'message' => 'Failed to update stock record or no effective changes made.');
            }
        } catch (Exception $e) {
            return array('status' => 'error', 'message' => 'Database error: ' . $e->getMessage());
        }
    }

    public function delete($stock_id)
    {
        try {
            $this->db->where('stock_id', $stock_id);
            $this->db->delete($this->stock_table_name);
            
            if ($this->db->affected_rows() > 0) {
                // Also delete related stock movements
                $this->db->where('stock_id', $stock_id);
                $this->db->delete($this->stock_movements_table_name);
                
                return array('status' => 'success', 'message' => 'Stock record deleted successfully.');
            } else {
                return array('status' => 'error', 'message' => 'Stock record not found or already deleted.');
            }
        } catch (Exception $e) {
            return array('status' => 'error', 'message' => 'Database error: ' . $e->getMessage());
        }
    }

    public function activate($stock_id, $status_id)
    {
        try {
            $data = array(
                'status_id' => $status_id,
                'last_updated' => date('Y-m-d H:i:s')
            );
            
            $this->db->where('stock_id', $stock_id);
            $this->db->update('stock', $data);
            
            if ($this->db->affected_rows() > 0) {
                return array('status' => 'success', 'message' => 'Stock status updated successfully.');
            } else {
                return array('status' => 'error', 'message' => 'Stock record not found or no changes made.');
            }
        } catch (Exception $e) {
            return array('status' => 'error', 'message' => 'Database error: ' . $e->getMessage());
        }
    }

    public function get_by_id($stock_id)
    {
        $this->db->where('stock_id', $stock_id);
        return $this->db->get($this->stock_table_name)->row_array();
    }

    // Parameter order changed: $movement_date before $reason, added $user_id
    private function log_stock_movement($stock_id, $movement_type, $quantity, $old_quantity, $new_quantity, $movement_date, $user_id, $reason = '')
    {
        $movement_data = array(
            'stock_id' => $stock_id,
            'movement_type' => $movement_type,
            'quantity' => $quantity,
            'old_quantity' => $old_quantity,
            'new_quantity' => $new_quantity,
            'reason' => $reason,
            'date' => $movement_date, // Added movement_date
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user_id // Use passed user_id
        );
        
        $this->db->insert($this->stock_movements_table_name, $movement_data);
    }

    public function get_low_stock($location_id = null)
    {
        $this->db->select('
            s.stock_id,
            s.product_id,
            s.location_id,
            s.quantity,
            s.min_stock_level,
            s.max_stock_level,
            s.last_updated,
            s.status_id,
            p.code as product_code,
            p.name as product_name,
            p.uom_id,
            c.name as category_name,
            u.name as uom_name,
            l.name as location_name,
            ss.name as status_name,
            CASE 
                WHEN s.quantity <= s.min_stock_level THEN "Low Stock"
                WHEN s.quantity >= s.max_stock_level THEN "Overstock"
                ELSE "Normal"
            END as stock_level_status
        ');
        $this->db->from('stock s');
        $this->db->join('products p', 's.product_id = p.product_id', 'left');
        $this->db->join('categories c', 'p.category_id = c.category_id', 'left');
        $this->db->join('uom u', 'p.uom_id = u.uom_id', 'left');
        $this->db->join('locations l', 's.location_id = l.location_id', 'left');
        $this->db->join('stock_status ss', 's.status_id = ss.status_id', 'left');
        $this->db->where('s.quantity <= s.min_stock_level');

        if ($location_id) {
            $this->db->where('s.location_id', $location_id);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    // Add this new function to fetch stock movements
    public function get_stock_movements($stock_id)
    {
        $this->db->select('sm.*, p.name as product_name, l.name as location_name, u.username as user_fullname'); // Added user_fullname
        $this->db->from($this->stock_movements_table_name . ' sm');
        $this->db->join($this->stock_table_name . ' s', 'sm.stock_id = s.stock_id', 'left');
        $this->db->join('products p', 's.product_id = p.product_id', 'left');
        $this->db->join('locations l', 's.location_id = l.location_id', 'left');
        $this->db->join('users u', 'sm.created_by = u.uid', 'left'); // Assuming 'users' table and 'uid' for user ID, 'username' for full name
        $this->db->where('sm.stock_id', $stock_id);
        $this->db->order_by('sm.date', 'DESC'); // Order by movement date
        $this->db->order_by('sm.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Parameter order changed: $user_id before $notes
    public function receive_stock($product_id, $location_id, $quantity, $movement_date, $user_id, $notes = '')
    {
        $this->db->trans_start();

        // Check if stock record exists, if not create it
        $stock = $this->get_stock_by_product_location($product_id, $location_id);
        $stock_id = 0;
        $old_quantity = 0;

        if ($stock) {
            $stock_id = $stock['stock_id'];
            $old_quantity = $stock['quantity'];
            $new_stock_qty = $old_quantity + $quantity;
            $this->db->where('stock_id', $stock_id);
            $this->db->update($this->stock_table_name, array('quantity' => $new_stock_qty, 'last_updated' => date('Y-m-d H:i:s')));
        } else {
            // Create new stock record
            $new_stock_data = array(
                'product_id' => $product_id,
                'location_id' => $location_id,
                'quantity' => $quantity,
                'min_stock_level' => 0, // Default or fetch from product settings
                'max_stock_level' => 0, // Default or fetch from product settings
                'status_id' => 1, // Assuming 1 is 'Active'
                'last_updated' => date('Y-m-d H:i:s')
            );
            $this->db->insert($this->stock_table_name, $new_stock_data);
            $stock_id = $this->db->insert_id();
            $new_stock_qty = $quantity; // old_quantity is 0 for new stock
        }

        // Log stock movement - Parameter order changed, pass $user_id
        $this->log_stock_movement($stock_id, 'Receive Stock', $quantity, $old_quantity, $new_stock_qty, $movement_date, $user_id, $notes);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return array('status' => 'error', 'message' => 'Failed to receive stock due to a transaction error.');
        }
        return array('status' => 'success', 'message' => 'Stock received successfully.', 'stock_id' => $stock_id);
    }


    // Parameter order changed: $user_id before $notes
    public function release_stock($stock_id, $quantity, $movement_date, $user_id, $notes = '')
    {
        $this->db->trans_start();

        $current_stock = $this->get_by_id($stock_id);

        if (!$current_stock) {
            $this->db->trans_rollback();
            return array('status' => 'error', 'message' => 'Stock item not found.');
        }

        if ($current_stock['quantity'] < $quantity) {
            $this->db->trans_rollback();
            return array('status' => 'error', 'message' => 'Insufficient stock to release.');
        }

        $new_quantity = $current_stock['quantity'] - $quantity;
        $this->db->where('stock_id', $stock_id);
        $this->db->update($this->stock_table_name, array('quantity' => $new_quantity, 'last_updated' => date('Y-m-d H:i:s')));

        // Log stock movement - Parameter order changed, pass $user_id
        $this->log_stock_movement($stock_id, 'Release Stock', $quantity, $current_stock['quantity'], $new_quantity, $movement_date, $user_id, $notes);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return array('status' => 'error', 'message' => 'Failed to release stock due to a transaction error.');
        }
        return array('status' => 'success', 'message' => 'Stock released successfully.');
    }

    // Parameter order changed: $user_id before $notes
    public function transfer_stock($from_stock_id, $quantity, $to_location_id, $movement_date, $user_id, $notes = '')
    {
        $this->db->trans_start();

        // Get 'from' stock details
        $from_stock = $this->get_by_id($from_stock_id);
        if (!$from_stock) {
            $this->db->trans_rollback();
            return array('status' => 'error', 'message' => 'Source stock item not found.');
        }

        if ($from_stock['quantity'] < $quantity) {
            $this->db->trans_rollback();
            return array('status' => 'error', 'message' => 'Insufficient stock at source location.');
        }
        
        if ($from_stock['location_id'] == $to_location_id) {
            $this->db->trans_rollback();
            return array('status' => 'error', 'message' => 'Source and destination locations cannot be the same.');
        }

        // Update 'from' stock
        $from_stock_current_qty = $from_stock['quantity'];
        $from_stock_new_qty = $from_stock_current_qty - $quantity;
        $this->db->where('stock_id', $from_stock_id);
        $this->db->update($this->stock_table_name, array('quantity' => $from_stock_new_qty, 'last_updated' => date('Y-m-d H:i:s')));
        
        $product_id = $from_stock['product_id'];
        $notes_out = 'Transfer Out to ' . $this->get_location_name($to_location_id) . ($notes ? ' - ' . $notes : '');
        // Parameter order changed, pass $user_id
        $this->log_stock_movement($from_stock_id, 'Transfer Out', $quantity, $from_stock_current_qty, $from_stock_new_qty, $movement_date, $user_id, $notes_out);

        // Check/Update 'to' stock
        $to_stock = $this->get_stock_by_product_location($product_id, $to_location_id);
        $to_stock_id = 0;
        $to_stock_old_qty = 0;

        if ($to_stock) {
            $to_stock_id = $to_stock['stock_id'];
            $to_stock_old_qty = $to_stock['quantity'];
            $to_stock_new_qty = $to_stock_old_qty + $quantity;
            $this->db->where('stock_id', $to_stock_id);
            $this->db->update($this->stock_table_name, array('quantity' => $to_stock_new_qty, 'last_updated' => date('Y-m-d H:i:s')));
        } else {
            // Create new stock record at destination
            $new_stock_data = array(
                'product_id' => $product_id,
                'location_id' => $to_location_id,
                'quantity' => $quantity,
                'min_stock_level' => $from_stock['min_stock_level'], // Copy from source or use default
                'max_stock_level' => $from_stock['max_stock_level'], // Copy from source or use default
                'status_id' => 1, // Assuming 1 is 'Active'
                'last_updated' => date('Y-m-d H:i:s')
            );
            $this->db->insert($this->stock_table_name, $new_stock_data);
            $to_stock_id = $this->db->insert_id();
            $to_stock_new_qty = $quantity; // old_quantity is 0 for new stock
        }
        
        $notes_in = 'Transfer In from ' . $this->get_location_name($from_stock['location_id']) . ($notes ? ' - ' . $notes : '');
        // Parameter order changed, pass $user_id
        $this->log_stock_movement($to_stock_id, 'Transfer In', $quantity, $to_stock_old_qty, $to_stock_new_qty, $movement_date, $user_id, $notes_in);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return array('status' => 'error', 'message' => 'Stock transfer failed due to a transaction error.');
        }
        return array('status' => 'success', 'message' => 'Stock transferred successfully.');
    }

    // Helper function to get location name (you might have this in a location model)
    private function get_location_name($location_id) {
        $this->db->select('name');
        $this->db->where('location_id', $location_id);
        $query = $this->db->get('locations');
        if ($query->num_rows() > 0) {
            return $query->row()->name;
        }
        return 'Unknown Location';
    }

    public function get_stock_by_product_location($product_id, $location_id)
    {
        $this->db->where('product_id', $product_id);
        $this->db->where('location_id', $location_id);
        return $this->db->get($this->stock_table_name)->row_array();
    }

    // Parameter order changed: $user_id before $notes
    public function adjust_stock($stock_id, $adjustment_type, $adjustment_quantity, $reason, $movement_date, $user_id)
    {
        $this->db->trans_start();

        $current_stock = $this->get_by_id($stock_id);

        if (!$current_stock) {
            $this->db->trans_rollback();
            return array('status' => 'error', 'message' => 'Stock item not found.');
        }

        if (!is_numeric($adjustment_quantity) || $adjustment_quantity <= 0) {
            $this->db->trans_rollback();
            return array('status' => 'error', 'message' => 'Adjustment quantity must be a positive number.');
        }

        $old_quantity = $current_stock['quantity'];
        $new_quantity = $old_quantity;

        if ($adjustment_type == 'add') {
            $new_quantity = $old_quantity + $adjustment_quantity;
            $movement_description = 'Stock Adjustment (+)';
        } elseif ($adjustment_type == 'subtract') {
            if ($old_quantity < $adjustment_quantity) {
                $this->db->trans_rollback();
                return array('status' => 'error', 'message' => 'Insufficient stock to subtract.');
            }
            $new_quantity = $old_quantity - $adjustment_quantity;
            $movement_description = 'Stock Adjustment (-)';
        } else {
            $this->db->trans_rollback();
            return array('status' => 'error', 'message' => 'Invalid adjustment type.');
        }

        $this->db->where('stock_id', $stock_id);
        $this->db->update($this->stock_table_name, array('quantity' => $new_quantity, 'last_updated' => date('Y-m-d H:i:s')));

        // Log stock movement
        $this->log_stock_movement($stock_id, $movement_description, $adjustment_quantity, $old_quantity, $new_quantity, $movement_date, $user_id, $reason);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return array('status' => 'error', 'message' => 'Failed to adjust stock due to a transaction error.');
        }
        return array('status' => 'success', 'message' => 'Stock adjusted successfully.');
    }
}
