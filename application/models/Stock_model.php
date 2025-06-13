<?php
defined('BASEPATH') or die('No direct script access allowed');

class Stock_model extends CI_Model
{
    private $tablename = "stock";

    function search($search = "", $location_id = 0, $active_only = 0)
    {
        $this->db->select(
            "s.*,
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
}
