<?php
defined('BASEPATH') or die('No direct script access allowed');

class Stock_movements_model extends CI_Model
{
    private $tablename = "stock_movements";

    function search($search = "", $location_id = 0, $date_from = "", $date_to = "", $movement_type = "")
    {
        $this->db->select(
            "sm.*,
             DATE_FORMAT(sm.date, '%Y-%m-%d') as date,
             DATE_FORMAT(sm.created_at, '%Y-%m-%d %H:%i') as created_at,
             p.code as product_code,
             p.name as product_name,
             c.category,
             u.name as uom,
             l.location,
             lf.location as transfer_from_location,
             lt.location as transfer_to_location,
             CONCAT(e.fname,' ', e.mname,' ', e.lname) as created_by_name"
        );

        $this->db->from("stock_movements sm");
        $this->db->join("products p", "p.id=sm.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=sm.location_id", "left");
        $this->db->join("locations lf", "lf.id=sm.transfer_from_location_id", "left");
        $this->db->join("locations lt", "lt.id=sm.transfer_to_location_id", "left");
        $this->db->join("user e", "e.id=sm.created_by", "left");
        $this->db->order_by('sm.created_at DESC');

        if (intval($location_id) > 0) {
            $this->db->where("sm.location_id", $location_id);
        }

        if ($movement_type) {
            $this->db->where("sm.movement_type", $movement_type);
        }

        if ($date_from && $date_to) {
            $this->db->where("DATE(sm.created_at) >=", $date_from);
            $this->db->where("DATE(sm.created_at) <=", $date_to);
        }

        if ($search) {
            $this->db->where("(
                CONCAT_WS(' ',p.code, p.name, c.category, sm.movement_type, sm.reference_type, sm.notes) LIKE '%{$search}%'
            )");
        }

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function search_by_id($id)
    {
        $this->db->select(
            "sm.*,
             DATE_FORMAT(sm.date, '%Y-%m-%d') as date,
             DATE_FORMAT(sm.created_at, '%Y-%m-%d %H:%i') as created_at,
             p.code as product_code,
             p.name as product_name,
             l.location,
             lf.location as transfer_from_location,
             lt.location as transfer_to_location"
        );

        $this->db->from("stock_movements sm");
        $this->db->join("products p", "p.id=sm.product_id", "left");
        $this->db->join("locations l", "l.id=sm.location_id", "left");
        $this->db->join("locations lf", "lf.id=sm.transfer_from_location_id", "left");
        $this->db->join("locations lt", "lt.id=sm.transfer_to_location_id", "left");
        $this->db->where("sm.id", $id);

        if ($query = $this->db->get()) {
            if ($query->num_rows() == 1) {
                return $query->row();
            } else {
                return false;
            }
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function add_movement($data)
    {
        $movement_data = array(
            'date' => $data['date'] ?? date('Y-m-d'),
            'product_id' => $data['product_id'],
            'location_id' => $data['location_id'],
            'movement_type' => $data['movement_type'],
            'qty' => $data['qty'],
            'unit_cost' => $data['unit_cost'] ?? 0,
            'reference_type' => $data['reference_type'],
            'reference_id' => $data['reference_id'] ?? null,
            'transfer_from_location_id' => $data['transfer_from_location_id'] ?? null,
            'transfer_to_location_id' => $data['transfer_to_location_id'] ?? null,
            'transfer_batch_id' => $data['transfer_batch_id'] ?? null,
            'notes' => $data['notes'] ?? '',
            'created_by' => $data['created_by']
        );

        if ($this->db->insert($this->tablename, $movement_data)) {
            return $this->db->insert_id();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function receive_stock($product_id, $location_id, $qty, $unit_cost, $reference_type, $reference_id, $created_by, $notes = '', $expiration_date = null)
    {
        $this->db->trans_start();

        // Add movement record
        $movement_data = array(
            'date' => date('Y-m-d'),
            'product_id' => $product_id,
            'location_id' => $location_id,
            'movement_type' => 'RECEIVE',
            'qty' => $qty,
            'unit_cost' => $unit_cost,
            'reference_type' => $reference_type,
            'reference_id' => $reference_id,
            'notes' => $notes,
            'created_by' => $created_by
        );

        $movement_id = $this->add_movement($movement_data);

        // Update stock levels with cost and expiration
        $this->load->model('stock_model');
        $this->stock_model->update_stock_with_cost_expiration($product_id, $location_id, $qty, $unit_cost, $expiration_date, 'add');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception("Error: Failed to receive stock");
        }

        return $movement_id;
    }

    function release_stock($product_id, $location_id, $qty, $reference_type, $reference_id, $created_by, $notes = '')
    {
        $this->db->trans_start();

        // Check available stock
        $this->load->model('stock_model');
        $stock = $this->stock_model->search_by_product_location($product_id, $location_id);
        
        if (!$stock || $stock->qty_available < $qty) {
            throw new Exception("Error: Insufficient stock available");
        }

        // Add movement record
        $movement_data = array(
            'date' => date('Y-m-d'),
            'product_id' => $product_id,
            'location_id' => $location_id,
            'movement_type' => 'RELEASE',
            'qty' => $qty,
            'reference_type' => $reference_type,
            'reference_id' => $reference_id,
            'notes' => $notes,
            'created_by' => $created_by
        );

        $movement_id = $this->add_movement($movement_data);

        // Update stock levels
        $this->stock_model->update_stock($product_id, $location_id, $qty, 'subtract');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception("Error: Failed to release stock");
        }

        return $movement_id;
    }

    function transfer_stock($product_id, $from_location_id, $to_location_id, $qty, $created_by, $notes = '')
    {
        $this->db->trans_start();

        // Check available stock at source location
        $this->load->model('stock_model');
        $stock = $this->stock_model->search_by_product_location($product_id, $from_location_id);
        
        if (!$stock || $stock->qty_available < $qty) {
            throw new Exception("Error: Insufficient stock available for transfer");
        }

        $transfer_batch_id = 'TXN_' . date('YmdHis') . '_' . uniqid();

        // Release from source location
        $movement_out_data = array(
            'date' => date('Y-m-d'),
            'product_id' => $product_id,
            'location_id' => $from_location_id,
            'movement_type' => 'TRANSFER',
            'qty' => $qty,
            'reference_type' => 'TRANSFER',
            'transfer_from_location_id' => $from_location_id,
            'transfer_to_location_id' => $to_location_id,
            'transfer_batch_id' => $transfer_batch_id,
            'notes' => $notes,
            'created_by' => $created_by
        );

        $this->add_movement($movement_out_data);
        $this->stock_model->update_stock($product_id, $from_location_id, $qty, 'subtract');

        // Receive at destination location
        $movement_in_data = array(
            'date' => date('Y-m-d'),
            'product_id' => $product_id,
            'location_id' => $to_location_id,
            'movement_type' => 'RECEIVE',
            'qty' => $qty,
            'reference_type' => 'TRANSFER',
            'transfer_from_location_id' => $from_location_id,
            'transfer_to_location_id' => $to_location_id,
            'transfer_batch_id' => $transfer_batch_id,
            'notes' => $notes,
            'created_by' => $created_by
        );

        $this->add_movement($movement_in_data);
        $this->stock_model->update_stock($product_id, $to_location_id, $qty, 'add');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception("Error: Failed to transfer stock");
        }

        return $transfer_batch_id;
    }

    function adjust_stock($product_id, $location_id, $new_qty, $created_by, $notes = '')
    {
        $this->db->trans_start();

        // Get current stock
        $this->load->model('stock_model');
        $stock = $this->stock_model->search_by_product_location($product_id, $location_id);
        
        $current_qty = $stock ? $stock->qty_on_hand : 0;
        $adjustment = $new_qty - $current_qty;

        if ($adjustment != 0) {
            // Add movement record
            $movement_data = array(
                'date' => date('Y-m-d'),
                'product_id' => $product_id,
                'location_id' => $location_id,
                'movement_type' => 'ADJUSTMENT',
                'qty' => abs($adjustment),
                'reference_type' => 'ADJUSTMENT',
                'notes' => $notes . ' (Previous: ' . $current_qty . ', New: ' . $new_qty . ')',
                'created_by' => $created_by
            );

            $movement_id = $this->add_movement($movement_data);

            // Update stock to exact quantity
            if ($stock) {
                $this->db->set('qty_on_hand', $new_qty);
                $this->db->where('product_id', $product_id);
                $this->db->where('location_id', $location_id);
                $this->db->update('stock');
            } else {
                // Create new stock record
                $this->stock_model->update_stock($product_id, $location_id, $new_qty, 'add');
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception("Error: Failed to adjust stock");
        }

        return isset($movement_id) ? $movement_id : null;
    }

    function get_product_movement_history($product_id, $location_id = 0, $limit = 50)
    {
        $this->db->select(
            "sm.*,
             DATE_FORMAT(sm.date, '%Y-%m-%d') as date,
             DATE_FORMAT(sm.created_at, '%Y-%m-%d %H:%i') as created_at,
             l.location,
             lf.location as transfer_from_location,
             lt.location as transfer_to_location,
             CONCAT(e.fname,' ', e.mname,' ', e.lname) as created_by_name"
        );

        $this->db->from("stock_movements sm");
        $this->db->join("locations l", "l.id=sm.location_id", "left");
        $this->db->join("locations lf", "lf.id=sm.transfer_from_location_id", "left");
        $this->db->join("locations lt", "lt.id=sm.transfer_to_location_id", "left");
        $this->db->join("user e", "e.id=sm.created_by", "left");
        $this->db->where("sm.product_id", $product_id);

        if (intval($location_id) > 0) {
            $this->db->where("sm.location_id", $location_id);
        }

        $this->db->order_by('sm.created_at DESC');
        $this->db->limit($limit);

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function get_movement_summary($location_id = 0, $movement_type = "", $date_from = "", $date_to = "")
    {
        $this->db->select(
            "sm.movement_type,
             l.location,
             COUNT(*) as transaction_count,
             SUM(ABS(sm.qty)) as total_quantity,
             SUM(ABS(sm.qty) * COALESCE(s.unit_cost, 0)) as total_value,
             p.name as product_name,
             p.code as product_code,
             c.category,
             u.name as uom"
        );

        $this->db->from("stock_movements sm");
        $this->db->join("products p", "p.id=sm.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=sm.location_id", "left");
        $this->db->join("stock s", "s.product_id=sm.product_id AND s.location_id=sm.location_id", "left");

        if (intval($location_id) > 0) {
            $this->db->where("sm.location_id", $location_id);
        }

        if ($movement_type) {
            $this->db->where("sm.movement_type", $movement_type);
        }

        if ($date_from && $date_to) {
            $this->db->where("DATE(sm.created_at) >=", $date_from);
            $this->db->where("DATE(sm.created_at) <=", $date_to);
        } elseif ($date_from) {
            $this->db->where("DATE(sm.created_at) >=", $date_from);
        } elseif ($date_to) {
            $this->db->where("DATE(sm.created_at) <=", $date_to);
        }

        $this->db->group_by('sm.movement_type, sm.location_id, sm.product_id');
        $this->db->order_by('total_value DESC, total_quantity DESC');

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function get_movement_trends($location_id = 0, $date_from = null, $date_to = null)
    {
        $this->db->select(
            "DATE_FORMAT(sm.created_at, '%Y-%m-%d') as movement_date,
             sm.movement_type,
             COUNT(*) as transaction_count,
             SUM(ABS(sm.qty)) as total_quantity,
             SUM(ABS(sm.qty) * COALESCE(s.unit_cost, 0)) as total_value"
        );

        $this->db->from("stock_movements sm");
        $this->db->join("stock s", "s.product_id=sm.product_id AND s.location_id=sm.location_id", "left");
        
        // Support both date range and period parameters for backward compatibility
        if ($date_from && $date_to) {
            $this->db->where("DATE(sm.created_at) >=", $date_from);
            $this->db->where("DATE(sm.created_at) <=", $date_to);
        } elseif (is_numeric($date_from)) {
            // If date_from is numeric, treat it as period_months for backward compatibility
            $period_months = $date_from;
            $this->db->where("sm.created_at >= DATE_SUB(CURDATE(), INTERVAL {$period_months} MONTH)");
        } else {
            // Default to last 30 days
            $this->db->where("sm.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
        }

        if (intval($location_id) > 0) {
            $this->db->where("sm.location_id", $location_id);
        }

        $this->db->group_by('movement_date, sm.movement_type');
        $this->db->order_by('movement_date ASC, sm.movement_type');

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }
}
