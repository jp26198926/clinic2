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
				$product_name = $col;
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
		$data = array(
			"deleted_by" => $current_user,
			"deleted_at" => date("Y-m-d H:i:s"),
			"deleted_reason" => $reason,
			"status_id" => 1 //deleted
		);

		$this->db->trans_start();

		$this->db->where("id", $prescription_id);
		$this->db->update("prescriptions", $data);

		//write log
		$this->db->reset_query();
		$log_data = array(
			"action" => "Delete Prescription",
			"created_by" => $current_user,
			"transaction_id" => $transaction_id,
			"item_id" => 0,
			"item_name" => "",
			"item_status" => "",
			"remarks" => "Prescription ID: " . $prescription_id . " - " . $reason
		);
		$this->db->insert("trails", $log_data);

		if ($this->db->trans_status() === FALSE) {
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			return true;
		}
	}







	function search_by_id($id){
		$this->db->select("p.*");
		$this->db->select("CONCAT('PMT', LPAD(p.id,3,'0')) as prescription_no");
		$this->db->select("t.id as transaction_id, LPAD(t.id,5,'0') as transaction_no");
		$this->db->select("tt.trans_type");
		$this->db->select("pt.prescription_type");
		$this->db->select("CONCAT(e.lastname, ', ', e.firstname,' ', e.middlename) as patient");
		$this->db->select("c.name as client, c.name as company");
		$this->db->select("CONCAT(u.fname,' ', u.mname,' ', u.lname) as created,");
		$this->db->from("prescriptions p");
		$this->db->join("prescription_types pt", "pt.id = p.prescription_type_id", "left");
		$this->db->join("transactions t", "t.id = p.transaction_id", "left");
		$this->db->join("trans_types tt", "tt.id=t.trans_type_id", "left");
		$this->db->join("patients e", "e.id=t.patient_id", "left");
		$this->db->join("clients c", "c.id=t.client_id", "left");
		$this->db->join("user u", "u.id=p.created_by", "left");
		$this->db->where("p.id", $id);

		if ($query = $this->db->get()) {
			if ($this->db->affected_rows() > 0) {
				return $query->row();
			} else {
				return false;
			}
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function get_prescription_list($transaction_id){
		$this->db->select("p.id, p.date, p.amount, p.reference, p.status_id");
		$this->db->select("CONCAT('PMT',LPAD(p.id,3,'0')) as prescription_no");
		$this->db->select("t.prescription_type");
		$this->db->select("s.status");
		$this->db->select("CONCAT(e.lname,', ', e.fname,' ', e.mname) as created");
		$this->db->from("prescriptions p");
		$this->db->join("user e", "e.id=p.created_by", "left");
		$this->db->join("prescription_types t", "t.id=p.prescription_type_id", "left");
		$this->db->join("prescription_status s", "s.id=p.status_id", "left");
		$this->db->where("p.transaction_id", $transaction_id);

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function save_prescription(
		$transaction_id,
		$date,
		$prescription_type_id,
		$amount_due,
		$pay_amount,
		$tender_amount,
		$change_amount,
		$reference,
		$current_user=0
	){
		$prescription_id = 0;

		$data = array(
			"transaction_id" => $transaction_id,
			"date" => $date,
			"prescription_type_id" => $prescription_type_id,
			"amount_due" => $amount_due,
			"amount" => $pay_amount,
			"tender_amount" => $tender_amount,
			"change_amount" => $change_amount,
			"reference" => $reference,
			"created_by" => $current_user
		);

		$this->db->trans_start();

		//save prescription
		$this->db->insert("prescriptions", $data);
		$prescription_id = $this->db->insert_id();

		//get the total paid
		$this->db->reset_query();
		$this->db->select_sum('amount');
		$this->db->where("status_id > 1");
		$this->db->where("transaction_id", $transaction_id);
		$query = $this->db->get('prescriptions');
		$total_paid = $query->row()->amount;

		//update transaction's total
		$this->db->reset_query();
		$this->db->set("total_paid", $total_paid);
		$this->db->where("id", $transaction_id);
		$this->db->update("transactions");

		// 	//write log - transaction
		$log_data = array(
			"action" => "Add Payment",
			"created_by" => $current_user,
			"transaction_id" => $transaction_id,
			"item_id" => 0,
			"item_name" => "",
			"item_status" => "",
			"remarks" => "Amount: " . $pay_amount
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

	function cancel($id, $transaction_id, $current_user = 0){
		$data = array(
			"deleted_at" => date('Y-m-d H:i:s'),
			"deleted_by" => $current_user,
			"status_id" => 1
		);

		$this->db->trans_start();

		//update record
		$this->db->where("id", $id);
		$this->db->update("prescriptions", $data);

		//get the total paid
		$this->db->reset_query();
		$this->db->select_sum('amount');
		$this->db->where("status_id > 1");
		$this->db->where("transaction_id", $transaction_id);
		$query = $this->db->get('prescriptions');
		$total_paid = $query->row()->amount;

		//update transaction's total
		$this->db->reset_query();
		$this->db->set("total_paid", $total_paid);
		$this->db->where("id", $transaction_id);
		$this->db->update("transactions");


		//write log - transaction
		$log_data = array(
			"action" => "Cancel Payment",
			"created_by" => $current_user,
			"transaction_id" => $transaction_id,
			"item_id" => 0,
			"item_name" => "",
			"item_status" => "",
			"remarks" => "Payment No: PMT" . str_pad($id, 3, '0', STR_PAD_LEFT)
		);
		$this->db->insert("trails", $log_data);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			//$error = $this->db->error();
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			//return true;
			return true;
		}
	}


}
