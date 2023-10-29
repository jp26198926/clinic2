<?php
defined('BASEPATH') or die('No direct script access allowed');

class Prescription_model extends CI_Model
{
	private $tablename = "prescriptions";

	function search_by_transaction($transaction_id, $only_active = true){
		$this->db->select("r.*");
		$this->db->select("p.code as product_code, p.name as product_name");
		$this->db->select("m.code as uom_code, m.name as uom_name");
		$this->db->select("CONCAT(u.fname,' ', u.mname,' ', u.lname) as created,");
		$this->db->from("prescriptions r");
		$this->db->join("products p", "p.id = r.product_id", "left");
		$this->db->join("uoms m", "m.id = p.uom_id", "left");
		$this->db->join("user u", "u.id=r.created_by", "left");
		$this->db->where("r.transaction_id", $transaction_id);

		if ($only_active){
			$this->db->where("r.status_id >", 1); //not deleted
		}

		if ($query = $this->db->get()){
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function add($data_input, $transaction_id, $current_user=0){
		$prescription_id = 0;
		$data = array(
			"created_by" => $current_user
		);
		$product_name = "";

		$this->db->trans_start();

		foreach ($data_input as $key => $val) {
			$col = str_replace("txt_prescription_", "", $key);
			if ($col !== "product_name"){ //product_name is not in the db fields so no need
				$data[$col] = $val;
			}else{
				$product_name = $val;
			}
		}

		$this->db->insert($this->tablename, $data);
		$prescription_id = $this->db->insert_id();

		//write log
		$log_data = array(
			"action" => "Add Prescription",
			"created_by" => $current_user,
			"transaction_id" => $transaction_id,
			"item_id" => 0,
			"item_name" => "",
			"item_status" => "",
			"remarks" => $data["qty"] . "x " . $product_name
		);
		$this->db->insert("trails", $log_data);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			//$error = $this->db->error();
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			//return true;
			return $prescription_id;
		}
	}

	function delete($prescription_id, $transaction_id, $reason, $current_user=0){
		$error = array();
		$data = array(
			"deleted_by" => $current_user,
			"deleted_at" => date("Y-m-d H:i:s"),
			"deleted_reason" => $reason,
			"status_id" => 1 //deleted
		);

		$this->db->trans_start();

		$this->db->where("id", $prescription_id);
		$this->db->update("prescriptions", $data);

		//get prescription's product name
		$this->db->reset_query();
		$this->db->select("x.qty, p.code as product_code, p.name as product_name, x.deleted_reason as reason");
		$this->db->select("u.code as uom_code");
		$this->db->from("prescriptions x");
		$this->db->join("products p" , "p.id = x.product_id", "left");
		$this->db->join("uoms u", "u.id = p.uom_id", "left");
		$this->db->where("x.id", $prescription_id);
		if ($query = $this->db->get()){
			$row = $query->row();

			//write log
			$this->db->reset_query();
			$log_data = array(
				"action" => "Delete Prescription",
				"created_by" => $current_user,
				"transaction_id" => $transaction_id,
				"item_id" => 0,
				"item_name" => "",
				"item_status" => "",
				"remarks" => $row->qty . "x " . $row->product_code . " - " . $row->product_name . " [" . $row->uom_code . "] | Reason: " . $row->reason
			);
			$this->db->insert("trails", $log_data);
		}else{
			$error = $this->db->error();
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			throw new Exception("Error: " . $error["message"]);
		} else {
			return true;
		}
	}

}
