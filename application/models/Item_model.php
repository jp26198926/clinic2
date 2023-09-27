<?php
defined('BASEPATH') or die('No direct script access allowed');

class Item_model extends CI_Model
{
	private $tablename = "items";

	function add($data, $current_user = 0)
	{
		$this->db->trans_start();
		//store this data for transaction update
		$transaction_id = $data['transaction_id'];
		$total = $data['total'];

		//store this product name for logging
		$product = $data["product"];
		$uom = $data["uom"];

		//remove unnecessary fields
		unset($data["product"]);
		unset($data["uom"]);

		//add new field in the existing array
		$data["created_by"] = $current_user;

		if ($this->db->insert($this->tablename, $data)) {

			//get the current totals of all items in this transaction
			$this->db->reset_query();
			$this->db->select_sum('total');
			$this->db->where("status_id > 1");
			$this->db->where("transaction_id", $transaction_id);
			$query = $this->db->get('items');
			$total = $query->row()->total;

			//update transaction's total
			$this->db->reset_query();
			$this->db->set("subtotal", $total); //tax based amount
			$this->db->set("total", $total);
			$this->db->where("id", $transaction_id);
			$this->db->update("transactions");


			//write log - transaction
			$this->db->reset_query();
			$log_data = array(
				"action" => "Add Item",
				"created_by" => $current_user,
				"transaction_id" => $transaction_id,
				"item_id" => 0,
				"item_name" => "",
				"item_status" => "",
				"remarks" => $data["qty"] .  " " . $uom . " " . $product
			);
			$this->db->insert("trails", $log_data);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			$error = $this->db->error();
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			return true;
		}
	}

	function cancel($id, $reason, $transaction_id, $current_user = 0)
	{
		$this->db->trans_start();

		//get item details
		$this->db->select("items.qty");
		$this->db->select("products.code product_code, products.name as product_name");
		$this->db->select("uoms.code as uom_code, uoms.name as uom_name");
		$this->db->from($this->tablename);
		$this->db->join("products", "products.id = items.product_id");
		$this->db->join("uoms", "uoms.id = products.uom_id");
		$this->db->where("items.id", $id);

		if ($query = $this->db->get()) {
			$item = $query->row();

			//reset query
			$this->db->reset_query();
			//update item status
			$this->db->set("status_id", 1); //cancelled
			$this->db->set("deleted_by", $current_user);
			$this->db->set("deleted_at", date("Y-m-d H:i:s"));
			$this->db->set("deleted_reason", $reason);
			$this->db->where("id", $id);
			$this->db->update($this->tablename);

			//get the current totals of all items in this transaction
			$this->db->reset_query();
			$this->db->select_sum('total');
			$this->db->where("status_id > 1");
			$this->db->where("transaction_id", $transaction_id);
			$query = $this->db->get('items');
			$total = $query->row()->total;

			//update transaction's total
			$this->db->reset_query();
			$this->db->set("subtotal", $total); //tax based amount
			$this->db->set("total", $total);
			$this->db->where("id", $transaction_id);
			$this->db->update("transactions");

			//write log - transaction
			$this->db->reset_query();
			$log_data = array(
				"action" => "Cancel Item",
				"created_by" => $current_user,
				"transaction_id" => $transaction_id,
				"item_id" => $id,
				"item_name" => $item->qty . " " . $item->uom_code . " " . $item->product_code,
				"item_status" => "Cancelled",
				"remarks" => $reason
			);
			$this->db->insert("trails", $log_data);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			$error = $this->db->error();
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			return true;
		}
	}

	function search_by_transaction($transaction_id)
	{
		$this->db->select("items.*");
		$this->db->select("products.code product_code, products.name as product_name");
		$this->db->select("categories.category");
		$this->db->select("uoms.code as uom_code, uoms.name as uom_name");
		$this->db->select("item_status.status");
		$this->db->from($this->tablename);
		$this->db->join("products", "products.id = items.product_id");
		$this->db->join("categories", "categories.id = products.category_id");
		$this->db->join("uoms", "uoms.id = products.uom_id");
		$this->db->join("item_status", "item_status.id = items.status_id");
		$this->db->where("transaction_id", $transaction_id);
		$this->db->where("items.status_id >", 1); //exclude deleted items

		//throw error if query is invalid
		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}


}
