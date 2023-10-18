<?php
defined('BASEPATH') or die('No direct script access allowed');

class Payment_model extends CI_Model
{
	private $tablename = "payments";

	function search_by_id($id){
		$this->db->select("p.*");
		$this->db->select("CONCAT('PMT', LPAD(p.id,3,'0')) as payment_no");
		$this->db->select("t.id as transaction_id, LPAD(t.id,5,'0') as transaction_no");
		$this->db->select("tt.trans_type");
		$this->db->select("pt.payment_type");
		$this->db->select("CONCAT(e.lastname, ', ', e.firstname,' ', e.middlename) as patient");
		$this->db->select("c.name as client, c.name as company");
		$this->db->select("CONCAT(u.fname,' ', u.mname,' ', u.lname) as created,");
		$this->db->from("payments p");
		$this->db->join("payment_types pt", "pt.id = p.payment_type_id", "left");
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

	function get_payment_list($transaction_id){
		$this->db->select("p.id, p.date, p.amount, p.reference, p.status_id");
		$this->db->select("CONCAT('PMT',LPAD(p.id,3,'0')) as payment_no");
		$this->db->select("t.payment_type");
		$this->db->select("s.status");
		$this->db->select("CONCAT(e.lname,', ', e.fname,' ', e.mname) as created");
		$this->db->from("payments p");
		$this->db->join("user e", "e.id=p.created_by", "left");
		$this->db->join("payment_types t", "t.id=p.payment_type_id", "left");
		$this->db->join("payment_status s", "s.id=p.status_id", "left");
		$this->db->where("p.transaction_id", $transaction_id);

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function save_payment(
		$transaction_id,
		$date,
		$payment_type_id,
		$amount_due,
		$pay_amount,
		$tender_amount,
		$change_amount,
		$reference,
		$current_user=0
	){
		$payment_id = 0;

		$data = array(
			"transaction_id" => $transaction_id,
			"date" => $date,
			"payment_type_id" => $payment_type_id,
			"amount_due" => $amount_due,
			"amount" => $pay_amount,
			"tender_amount" => $tender_amount,
			"change_amount" => $change_amount,
			"reference" => $reference,
			"created_by" => $current_user
		);

		$this->db->trans_start();

		//save payment
		$this->db->insert("payments", $data);
		$payment_id = $this->db->insert_id();

		//get the total paid
		$this->db->reset_query();
		$this->db->select_sum('amount');
		$this->db->where("status_id > 1");
		$this->db->where("transaction_id", $transaction_id);
		$query = $this->db->get('payments');
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
			return $payment_id;
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
		$this->db->update("payments", $data);

		//get the total paid
		$this->db->reset_query();
		$this->db->select_sum('amount');
		$this->db->where("status_id > 1");
		$this->db->where("transaction_id", $transaction_id);
		$query = $this->db->get('payments');
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
