<?php
defined('BASEPATH') or die('No direct script access allowed');

class Stock_model extends CI_Model
{
    private $tablename = "stock";

    function search($search = "", $location_id = 0, $active_only = 0)
    {
        $this->db->select(
            "s.*,
             DATE_FORMAT(s.expiration_date, '%Y-%m-%d') as expiration_date_formatted,
             CASE 
                WHEN s.expiration_date IS NOT NULL AND s.expiration_date <= CURDATE() THEN 'expired'
                WHEN s.expiration_date IS NOT NULL AND s.expiration_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'expiring_soon'
                ELSE 'normal'
             END as expiration_status,
             p.code as product_code,
             p.name as product_name,
             c.category,
             u.name as uom,
             l.location,
             CONCAT(e.fname,' ', e.mname,' ', e.lname) as created,
             CONCAT(up.fname,' ', up.mname,' ', up.lname) as updated"
        );

        $this->db->from("stock s");
        $this->db->join("products p", "p.id=s.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=s.location_id", "left");
        $this->db->join("user e", "e.id=p.created_by", "left");
        $this->db->join("user up", "up.id=p.updated_by", "left");
        $this->db->order_by('p.name, l.location');

        if (intval($location_id) > 0) {
            $this->db->where("s.location_id", $location_id);
        }

        if (intval($active_only) > 0) {
            $this->db->where("p.status_id", 2);
        }

        if ($search) {
            $this->db->where("(
                CONCAT_WS(' ',p.code, p.name, c.category, u.name, l.location) LIKE '%{$search}%'
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
            "s.*,
             DATE_FORMAT(s.expiration_date, '%Y-%m-%d') as expiration_date_formatted,
             CASE 
                WHEN s.expiration_date IS NOT NULL AND s.expiration_date <= CURDATE() THEN 'expired'
                WHEN s.expiration_date IS NOT NULL AND s.expiration_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'expiring_soon'
                ELSE 'normal'
             END as expiration_status,
             p.code as product_code,
             p.name as product_name,
             c.category,
             u.name as uom,
             l.location"
        );

        $this->db->from("stock s");
        $this->db->join("products p", "p.id=s.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=s.location_id", "left");
        $this->db->where("s.id", $id);

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

    function search_by_product_location($product_id, $location_id)
    {
        $this->db->where('product_id', $product_id);
        $this->db->where('location_id', $location_id);
        
        if ($query = $this->db->get($this->tablename)) {
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

    function update_stock($product_id, $location_id, $qty_change, $operation = 'add')
    {
        // First check if stock record exists
        $existing = $this->search_by_product_location($product_id, $location_id);
        
        if ($existing) {
            // Update existing stock
            if ($operation == 'add') {
                $this->db->set('qty_on_hand', 'qty_on_hand + ' . $qty_change, FALSE);
            } else {
                $this->db->set('qty_on_hand', 'qty_on_hand - ' . $qty_change, FALSE);
            }
            
            $this->db->where('product_id', $product_id);
            $this->db->where('location_id', $location_id);
            
            if ($this->db->update($this->tablename)) {
                return true;
            } else {
                $error = $this->db->error();
                throw new Exception("Error: " . $error['message']);
            }
        } else {
            // Create new stock record
            $data = array(
                'product_id' => $product_id,
                'location_id' => $location_id,
                'qty_on_hand' => $operation == 'add' ? $qty_change : 0
            );
            
            if ($this->db->insert($this->tablename, $data)) {
                return $this->db->insert_id();
            } else {
                $error = $this->db->error();
                throw new Exception("Error: " . $error['message']);
            }
        }
    }

    function reserve_stock($product_id, $location_id, $qty_reserve)
    {
        $this->db->set('qty_reserved', 'qty_reserved + ' . $qty_reserve, FALSE);
        $this->db->where('product_id', $product_id);
        $this->db->where('location_id', $location_id);
        
        if ($this->db->update($this->tablename)) {
            return true;
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function unreserve_stock($product_id, $location_id, $qty_unreserve)
    {
        $this->db->set('qty_reserved', 'qty_reserved - ' . $qty_unreserve, FALSE);
        $this->db->where('product_id', $product_id);
        $this->db->where('location_id', $location_id);
        $this->db->where('qty_reserved >=', $qty_unreserve);
        
        if ($this->db->update($this->tablename)) {
            return true;
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function get_low_stock_products($location_id = 0)
    {
        $this->db->select(
            "s.*,
             p.code as product_code,
             p.name as product_name,
             p.reorder_level,
             c.category,
             u.name as uom,
             l.location,
             CONCAT(e.fname,' ', e.mname,' ', e.lname) as created,
             CONCAT(up.fname,' ', up.mname,' ', up.lname) as updated"
        );

        $this->db->from("stock s");
        $this->db->join("products p", "p.id=s.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=s.location_id", "left");
        $this->db->join("user e", "e.id=p.created_by", "left");
        $this->db->join("user up", "up.id=p.updated_by", "left");
        $this->db->where("s.qty_available <= p.reorder_level");
        $this->db->where("p.status_id", 2);

        if (intval($location_id) > 0) {
            $this->db->where("s.location_id", $location_id);
        }

        $this->db->order_by('s.qty_available ASC');

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function update_stock_with_cost_expiration($product_id, $location_id, $qty_change, $unit_cost = null, $expiration_date = null, $operation = 'add')
    {
        // First check if stock record exists
        $existing = $this->search_by_product_location($product_id, $location_id);
        
        if ($existing) {
            // Update existing stock
            $update_data = array();
            
            if ($operation == 'add') {
                $this->db->set('qty_on_hand', 'qty_on_hand + ' . $qty_change, FALSE);
                
                // Update cost using weighted average if new cost provided
                if ($unit_cost !== null && $unit_cost > 0) {
                    $current_value = $existing->qty_on_hand * $existing->unit_cost;
                    $new_value = $qty_change * $unit_cost;
                    $total_qty = $existing->qty_on_hand + $qty_change;
                    
                    if ($total_qty > 0) {
                        $weighted_avg_cost = ($current_value + $new_value) / $total_qty;
                        $this->db->set('unit_cost', $weighted_avg_cost);
                        $this->db->set('last_cost_update', 'NOW()', FALSE);
                    }
                }
                
                // Update expiration date if provided (use earliest date)
                if ($expiration_date) {
                    if (!$existing->expiration_date || $expiration_date < $existing->expiration_date) {
                        $this->db->set('expiration_date', $expiration_date);
                    }
                }
            } else {
                $this->db->set('qty_on_hand', 'qty_on_hand - ' . $qty_change, FALSE);
            }
            
            $this->db->where('product_id', $product_id);
            $this->db->where('location_id', $location_id);
            
            if ($this->db->update($this->tablename)) {
                return true;
            } else {
                $error = $this->db->error();
                throw new Exception("Error: " . $error['message']);
            }
        } else {
            // Create new stock record
            $data = array(
                'product_id' => $product_id,
                'location_id' => $location_id,
                'qty_on_hand' => $operation == 'add' ? $qty_change : 0,
                'unit_cost' => $unit_cost ?? 0,
                'expiration_date' => $expiration_date,
                'last_cost_update' => $unit_cost ? date('Y-m-d H:i:s') : null
            );
            
            if ($this->db->insert($this->tablename, $data)) {
                return $this->db->insert_id();
            } else {
                $error = $this->db->error();
                throw new Exception("Error: " . $error['message']);
            }
        }
    }

    function get_expiring_products($location_id = 0, $days_ahead = 30)
    {
        $this->db->select(
            "s.*,
             DATE_FORMAT(s.expiration_date, '%Y-%m-%d') as expiration_date_formatted,
             DATEDIFF(s.expiration_date, CURDATE()) as days_until_expiry,
             p.code as product_code,
             p.name as product_name,
             c.category,
             u.name as uom,
             l.location"
        );

        $this->db->from("stock s");
        $this->db->join("products p", "p.id=s.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=s.location_id", "left");
        $this->db->where("s.expiration_date IS NOT NULL");
        $this->db->where("s.expiration_date <=", date('Y-m-d', strtotime("+{$days_ahead} days")));
        $this->db->where("s.qty_on_hand >", 0);
        $this->db->where("p.status_id", 2);

        if (intval($location_id) > 0) {
            $this->db->where("s.location_id", $location_id);
        }

        $this->db->order_by('s.expiration_date ASC');

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function get_expired_products($location_id = 0)
    {
        $this->db->select(
            "s.*,
             DATE_FORMAT(s.expiration_date, '%Y-%m-%d') as expiration_date_formatted,
             DATEDIFF(CURDATE(), s.expiration_date) as days_expired,
             p.code as product_code,
             p.name as product_name,
             c.category,
             u.name as uom,
             l.location"
        );

        $this->db->from("stock s");
        $this->db->join("products p", "p.id=s.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=s.location_id", "left");
        $this->db->where("s.expiration_date IS NOT NULL");
        $this->db->where("s.expiration_date <", date('Y-m-d'));
        $this->db->where("s.qty_on_hand >", 0);
        $this->db->where("p.status_id", 2);

        if (intval($location_id) > 0) {
            $this->db->where("s.location_id", $location_id);
        }

        $this->db->order_by('s.expiration_date DESC');

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function get_stock_valuation($location_id = 0)
    {
        $this->db->select(
            "s.*,
             p.code as product_code,
             p.name as product_name,
             c.category,
             u.name as uom,
             l.location,
             ROUND(s.qty_on_hand * s.unit_cost, 2) as total_value,
             s.unit_cost as cost_per_unit"
        );

        $this->db->from("stock s");
        $this->db->join("products p", "p.id=s.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=s.location_id", "left");
        
        $this->db->where("p.status_id", 2); // Active products only
        $this->db->where("s.qty_on_hand > 0"); // Only products with stock

        if (intval($location_id) > 0) {
            $this->db->where("s.location_id", $location_id);
        }

        $this->db->order_by('total_value DESC');

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function get_low_stock($location_id = 0)
    {
        $this->db->select(
            "s.*,
             p.code as product_code,
             p.name as product_name,
             p.reorder_level,
             c.category,
             u.name as uom,
             l.location,
             (p.reorder_level - s.qty_on_hand) as shortage_qty,
             ROUND((s.qty_on_hand / NULLIF(p.reorder_level, 0)) * 100, 2) as stock_percentage"
        );

        $this->db->from("stock s");
        $this->db->join("products p", "p.id=s.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=s.location_id", "left");
        
        // Only products below reorder level
        $this->db->where("s.qty_on_hand < p.reorder_level");
        $this->db->where("p.reorder_level > 0"); // Only products with defined reorder levels
        $this->db->where("p.status_id", 2); // Active products only

        if (intval($location_id) > 0) {
            $this->db->where("s.location_id", $location_id);
        }

        $this->db->order_by('stock_percentage ASC, shortage_qty DESC');

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function get_expiring_stock($location_id = 0, $days = 30)
    {
        $this->db->select(
            "s.*,
             p.code as product_code,
             p.name as product_name,
             c.category,
             u.name as uom,
             l.location,
             DATE_FORMAT(s.expiration_date, '%Y-%m-%d') as expiration_date_formatted,
             DATEDIFF(s.expiration_date, CURDATE()) as days_to_expiry,
             ROUND(s.qty_on_hand * s.unit_cost, 2) as total_value"
        );

        $this->db->from("stock s");
        $this->db->join("products p", "p.id=s.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=s.location_id", "left");
        
        $this->db->where("s.expiration_date IS NOT NULL");
        $this->db->where("s.expiration_date > CURDATE()"); // Not yet expired
        $this->db->where("s.expiration_date <= DATE_ADD(CURDATE(), INTERVAL {$days} DAY)"); // Expiring within specified days
        $this->db->where("p.status_id", 2); // Active products only
        $this->db->where("s.qty_on_hand > 0"); // Only products with stock

        if (intval($location_id) > 0) {
            $this->db->where("s.location_id", $location_id);
        }

        $this->db->order_by('s.expiration_date ASC');

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function get_expired_stock($location_id = 0)
    {
        $this->db->select(
            "s.*,
             p.code as product_code,
             p.name as product_name,
             c.category,
             u.name as uom,
             l.location,
             DATE_FORMAT(s.expiration_date, '%Y-%m-%d') as expiration_date_formatted,
             DATEDIFF(CURDATE(), s.expiration_date) as days_expired,
             ROUND(s.qty_on_hand * s.unit_cost, 2) as total_value"
        );

        $this->db->from("stock s");
        $this->db->join("products p", "p.id=s.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=s.location_id", "left");
        
        $this->db->where("s.expiration_date IS NOT NULL");
        $this->db->where("s.expiration_date <= CURDATE()"); // Already expired
        $this->db->where("p.status_id", 2); // Active products only
        $this->db->where("s.qty_on_hand > 0"); // Only products with stock

        if (intval($location_id) > 0) {
            $this->db->where("s.location_id", $location_id);
        }

        $this->db->order_by('s.expiration_date DESC'); // Most recently expired first

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function get_zero_stock($location_id = 0)
    {
        $this->db->select(
            "s.*,
             p.code as product_code,
             p.name as product_name,
             c.category,
             u.name as uom,
             l.location"
        );

        $this->db->from("stock s");
        $this->db->join("products p", "p.id=s.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=s.location_id", "left");
        
        $this->db->where("s.qty_on_hand", 0);
        $this->db->where("p.status_id", 2); // Active products only

        if (intval($location_id) > 0) {
            $this->db->where("s.location_id", $location_id);
        }

        $this->db->order_by('p.name, l.location');

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function get_negative_stock($location_id = 0)
    {
        $this->db->select(
            "s.*,
             p.code as product_code,
             p.name as product_name,
             c.category,
             u.name as uom,
             l.location"
        );

        $this->db->from("stock s");
        $this->db->join("products p", "p.id=s.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=s.location_id", "left");
        
        $this->db->where("s.qty_on_hand <", 0);
        $this->db->where("p.status_id", 2); // Active products only

        if (intval($location_id) > 0) {
            $this->db->where("s.location_id", $location_id);
        }

        $this->db->order_by('s.qty_on_hand ASC, p.name');

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function get_abc_analysis($location_id = 0)
    {
        $this->db->select(
            "s.*,
             p.code as product_code,
             p.name as product_name,
             c.category,
             u.name as uom,
             l.location,
             ROUND(s.qty_on_hand * s.unit_cost, 2) as total_value"
        );

        $this->db->from("stock s");
        $this->db->join("products p", "p.id=s.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=s.location_id", "left");
        
        $this->db->where("p.status_id", 2); // Active products only
        $this->db->where("s.qty_on_hand > 0"); // Only products with stock

        if (intval($location_id) > 0) {
            $this->db->where("s.location_id", $location_id);
        }

        $this->db->order_by('total_value DESC');

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }

    function get_turnover_analysis($location_id = 0, $period_months = 12)
    {
        $this->db->select(
            "s.*,
             p.code as product_code,
             p.name as product_name,
             c.category,
             u.name as uom,
             l.location,
             ROUND(s.qty_on_hand * s.unit_cost, 2) as total_value"
        );

        $this->db->from("stock s");
        $this->db->join("products p", "p.id=s.product_id", "left");
        $this->db->join("categories c", "c.id=p.category_id", "left");
        $this->db->join("uoms u", "u.id=p.uom_id", "left");
        $this->db->join("locations l", "l.id=s.location_id", "left");
        
        $this->db->where("p.status_id", 2); // Active products only
        $this->db->where("s.qty_on_hand > 0"); // Only products with stock

        if (intval($location_id) > 0) {
            $this->db->where("s.location_id", $location_id);
        }

        $this->db->order_by('p.name, l.location');

        if ($query = $this->db->get()) {
            return $query->result();
        } else {
            $error = $this->db->error();
            throw new Exception("Error: " . $error['message']);
        }
    }
}
