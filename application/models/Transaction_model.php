<?php
defined('BASEPATH') or die('No direct script access allowed');

class Transaction_model extends CI_Model
{
	private $tablename = "transactions";

	function create($created_by)
	{
		$data["created_by"] = $created_by;

		if ($this->db->insert($this->tablename, $data)) {
			if ($this->db->affected_rows() > 0) {
				return $this->db->insert_id();
			} else {
				return false;
			}
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function save($data_input, $current_user = 0)
	{
		$this->db->trans_start();

		$data = array();
		$transaction_id = 0;

		$data["created_by"] = $current_user;
		$data["status_id"] = 3; //confirmed
		$data["location_id"] = 2; //Triage

		foreach ($data_input as $key => $val) {
			$col = str_replace("_new", "", $key);
			$data[$col] = $val;
		}

		if (!$this->db->insert($this->tablename, $data)) {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		} else {
			$transaction_id = $this->db->insert_id();
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			$error = $this->db->error();
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			return $transaction_id;
		}
	}


	function update($id, $data_input, $current_user = 0)
	{
		$this->db->trans_start();

		$data = array();
		$data["updated_by"] = $current_user;
		$data["updated_at"] = date("Y-m-d H:i:s");

		foreach ($data_input as $key => $val) {
			$col = str_replace("_update", "", $key);
			$data[$col] = $val;
		}

		$this->db->where("id", $id);

		if (!$this->db->update($this->tablename, $data)) {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
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

	function view($id, $current_user = 0)
	{
		$this->db->select(
			"x.*,
			 LPAD(x.id,5,'0') as transaction_no,
			 tt.trans_type,
			 CONCAT(p.lastname, ', ', p.firstname,' ', p.middlename) as patient,
			 c.name as client, c.name as company,
			 ct.charging_type,
			 pm.payment_method,
			 i.name as insurance,
			 CONCAT(dr.fname,' ', dr.mname,' ', dr.lname) as doctor,
			 l.location,
			 CONCAT(e.fname,' ', e.mname,' ', e.lname) as created,
             CONCAT(u.fname,' ', u.mname,' ', u.lname) as updated,
             CONCAT(d.fname,' ', d.mname,' ', d.lname) as deleted,
			 q.queue,
             s.status"
		);

		$this->db->from("transactions x");
		$this->db->join("trans_types tt", "tt.id=x.trans_type_id", "left");
		$this->db->join("patients p", "p.id=x.patient_id", "left");
		$this->db->join("clients c", "c.id=x.client_id", "left");
		$this->db->join("charging_types ct", "ct.id=x.charging_type_id", "left");
		$this->db->join("payment_methods pm", "pm.id=x.payment_method_id", "left");
		$this->db->join("insurances i", "i.id=x.insurance_id", "left");
		$this->db->join("user dr", "dr.id=x.doctor_id", "left");
		$this->db->join("locations l", "l.id=x.location_id", "left");
		$this->db->join("user e", "e.id=x.created_by", "left");
		$this->db->join("user u", "u.id=x.updated_by", "left");
		$this->db->join("user d", "d.id=x.deleted_by", "left");
		$this->db->join("transaction_status s", "s.id=x.status_id", "left");
		$this->db->join("queues q", "q.id=x.queue_id", "left");
		$this->db->where("x.id", $id);

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

	function search($search = "", $status_ids = [], $current_user = 0)
	{
		$this->db->select(
			"x.*,
			 LPAD(x.id,5,'0') as transaction_no,
			 tt.trans_type,
			 CONCAT(p.firstname,' ',p.middlename,' ',p.lastname) as patient,
			 c.name as client,
			 ct.charging_type,
			 pm.payment_method,
			 i.name as insurance,
			 CONCAT(dr.fname,' ', dr.mname,' ', dr.lname) as doctor,
			 l.location,
			 CONCAT(e.fname,' ', e.mname,' ', e.lname) as created,
             CONCAT(u.fname,' ', u.mname,' ', u.lname) as updated,
             CONCAT(d.fname,' ', d.mname,' ', d.lname) as deleted,
			 q.queue,
             s.status"
		);

		$this->db->from("transactions x");
		$this->db->join("trans_types tt", "tt.id=x.trans_type_id", "left");
		$this->db->join("patients p", "p.id=x.patient_id", "left");
		$this->db->join("clients c", "c.id=x.client_id", "left");
		$this->db->join("charging_types ct", "ct.id=x.charging_type_id", "left");
		$this->db->join("payment_methods pm", "pm.id=x.payment_method_id", "left");
		$this->db->join("insurances i", "i.id=x.insurance_id", "left");
		$this->db->join("user dr", "dr.id=x.doctor_id", "left");
		$this->db->join("locations l", "l.id=x.location_id", "left");
		$this->db->join("user e", "e.id=x.created_by", "left");
		$this->db->join("user u", "u.id=x.updated_by", "left");
		$this->db->join("user d", "d.id=x.deleted_by", "left");
		$this->db->join("transaction_status s", "s.id=x.status_id", "left");
		$this->db->join("queues q", "q.id=x.queue_id", "left");
		$this->db->where("x.created_by", $current_user);
		$this->db->order_by('x.id');

		if ($search) {
			$this->db->where("CONCAT_WS(
					' ',
					LPAD(x.id,5,'0'),
					x.date,
					e.fname, e.mname, e.lname,
					u.fname, u.mname, u.lname,
					d.fname, d.mname, d.lname,
					s.status
				)
				LIKE '%{$search}%'"
			);
		}

		if (count($status_ids) > 0) { //if status id specified
			$this->db->where_in("x.status_id", $status_ids);
		}

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function advance_search($data_input, $current_user = 0)
	{
		$this->db->select(
			"x.*,
			 LPAD(x.id,5,'0') as transaction_no,
			 tt.trans_type,
			 CONCAT(p.firstname,' ',p.middlename,' ',p.lastname) as patient,
			 c.name as client,
			 ct.charging_type,
			 pm.payment_method,
			 i.name as insurance,
			 CONCAT(dr.fname,' ', dr.mname,' ', dr.lname) as doctor,
			 l.location,
			 CONCAT(e.fname,' ', e.mname,' ', e.lname) as created,
             CONCAT(u.fname,' ', u.mname,' ', u.lname) as updated,
             CONCAT(d.fname,' ', d.mname,' ', d.lname) as deleted,
			 q.queue,
             s.status"
		);

		$this->db->from("transactions x");
		$this->db->join("trans_types tt", "tt.id=x.trans_type_id", "left");
		$this->db->join("patients p", "p.id=x.patient_id", "left");
		$this->db->join("clients c", "c.id=x.client_id", "left");
		$this->db->join("charging_types ct", "ct.id=x.charging_type_id", "left");
		$this->db->join("payment_methods pm", "pm.id=x.payment_method_id", "left");
		$this->db->join("insurances i", "i.id=x.insurance_id", "left");
		$this->db->join("user dr", "dr.id=x.doctor_id", "left");
		$this->db->join("locations l", "l.id=x.location_id", "left");
		$this->db->join("user e", "e.id=x.created_by", "left");
		$this->db->join("user u", "u.id=x.updated_by", "left");
		$this->db->join("user d", "d.id=x.deleted_by", "left");
		$this->db->join("transaction_status s", "s.id=x.status_id", "left");
		$this->db->join("queues q", "q.id=x.queue_id", "left");
		$this->db->where("x.created_by", $current_user);


		foreach ($data_input as $key => $val) {
			$col = str_replace("_asearch", "", $key);

			if ($val) {
				if ($col === "date_from") {
					$this->db->where("x.date >=", $val);
				} else if ($col === "date_to") {
					$this->db->where("x.date <=", $val);
				} else if ($col === "division") {
					$this->db->like("CONCAT(v.code,' ',v.name)", $val);
				} else if ($col === "dept") {
					$this->db->like("CONCAT(t.code,' ',t.name)", $val);
				} else if ($col === "transactioned") {
					$this->db->like("CONCAT(e.fname,' ', e.mname,' ', e.lname)", $val);
				} else if ($col === "dept_approved") {
					$this->db->like("CONCAT(n.fname,' ', n.mname,' ', n.lname)", $val);
				} else if ($col === "gm_approved") {
					$this->db->like("CONCAT(g.fname,' ', g.mname,' ', g.lname)", $val);
				} else if ($col === "purchaser") {
					$this->db->like("CONCAT(p.fname,' ', p.mname,' ', p.lname)", $val);
				} else if ($col === "transaction_no") {
					$this->db->like("LPAD(x.id,5,'0')", $val);
				} else if ($col === "status") {
					$this->db->like("s.status", $val);
				} else {
					$this->db->like("x." . $col, $val);
				}
			}
		}

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	//transactions
	function complete($transaction_id, $current_user = 0){
		$result["proceed"] = false;

		$this->db->trans_start();

		//check if all items status are completed
		$this->db->select("id");
		$this->db->from("items");
		$this->db->where("transaction_id", $transaction_id);
		$this->db->where("status_id >", 1); //not deleted
		$this->db->where("status_id <", 4); //not completed
		$this->db->get();
		if ($this->db->affected_rows() > 0){
			$result["error"] = "Some items are not yet completed!";
		}else{
			//get payment method
			//reset query
			$this->db->reset_query();
			$this->db->select("t.payment_method_id, (t.total - t.total_paid) as balance, pm.payment_method");
			$this->db->from("transactions t");
			$this->db->join("payment_methods pm", "pm.id = t.payment_method_id", "left");
			$this->db->where("t.id", $transaction_id);

			if ($query = $this->db->get()){
				$row = $query->row();
				$payment_method = $row->payment_method;
				$balance = $row->balance;

				//check the method is cash
				if (stripos($payment_method, "cash") !== false){
					//check if there is balance
					if ($balance > 0) {
						$result["error"] = "There is {$balance} balance that need to be settled!";
					}else{
						$result["proceed"] = true;
					}
				}else{
					$result["proceed"] = true;
				}

				if ($result["proceed"] === true){
					//mark the transaction status as complete
					$this->db->reset_query();
					$this->db->set("status_id", 4); //completed
					$this->db->set("queue_id", 0);
					$this->db->where("id", $transaction_id);
					$this->db->update("transactions");

					//write log
					$this->db->reset_query();
					$log_data = array(
						"action" => "Complete Transaction",
						"created_by" => $current_user,
						"transaction_id" => $transaction_id,
						"item_id" => 0,
						"item_name" => "",
						"item_status" => "",
						"remarks" => "Transaction has been completed"
					);
					$this->db->insert("trails", $log_data);
				}
			}else{
				$result["error"] = $this->db->error();
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			return $result;
		}
	}

	function cancel_check($transaction_id)
	{
		//check if some item status has a value of more than 4 then it return false and cannot be cancelled
		$this->db->where("transaction_id", $transaction_id);
		$this->db->where("status_id", 2); //completed

		if ($this->db->get("items")) {
			if ($this->db->affected_rows() > 0) {
				return false;
			} else {
				return true;
			}
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function cancel($transaction_id, $reason, $current_user = 0)
	{
		$result["proceed"] = false;

		$this->db->trans_start();

		$data = array(
			'deleted_by' => $current_user,
			'deleted_at' => date('Y-m-d H:i:s'),
			'deleted_reason' => $reason,
			'status_id' => 1 //cancel
		);

		//check if total paid is greater than 0
		$this->db->select("total_paid");
		$this->db->where("id", $transaction_id);
		$query = $this->db->get("transactions");
		$total_paid = $query->row()->total_paid;

		if (floatval($total_paid) > 0){
			$result["error"] = "There is already a payment, please cancel the payment first before you can cancel this transaction!";
		}else{
			$result["proceed"] = true;

			$this->db->reset_query();

			//update transaction
			$this->db->where("id", $transaction_id);
			$this->db->update("transactions", $data);

			$this->db->reset_query();

			//write log - transaction
			$log_data = array(
				"action" => "Cancel Transaction",
				"created_by" => $current_user,
				"transaction_id" => $transaction_id,
				"item_id" => 0,
				"item_name" => "",
				"item_status" => "",
				"remarks" => $reason
			);
			$this->db->insert("trails", $log_data);

			// $this->db->reset_query();

			// //get all items in the transaction
			// $this->db->select("x.id, x.qty, p.name as description, s.status");
			// $this->db->from("items x");
			// $this->db->join("products p","p.id = x.product_id", "left");
			// $this->db->join("item_status s", "s.id = x.status_id", "left");
			// $this->db->where("x.transaction_id", $transaction_id);
			// $this->db->where("x.status_id >", 1);

			// if ($query = $this->db->get()){
			// 	$results = $query->result();

			// 	$this->db->reset_query();

			// 	foreach ($results as $key => $value) {
			// 		//update item status
			// 		$this->db->reset_query();

			// 		$this->db->where("id", $value->id);
			// 		$this->db->update("items", $data);

			// 		//write log
			// 		$this->db->reset_query();

			// 		$series_no = str_pad($value->id, 5, "0", STR_PAD_LEFT);

			// 		$log_data = array(
			// 			"action" => "Cancel Item",
			// 			"created_by" => $current_user,
			// 			"transaction_id" => $transaction_id,
			// 			"item_id" => $value->id,
			// 			"item_name" => $value->description,
			// 			"item_status" => $value->status,
			// 			"remarks" => $series_no . " : " . $value->qty . " x " . $value->description . " -> " . $reason
			// 		);

			// 		$this->db->insert("trails", $log_data);
			// 	}
			// }
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			//$error = $this->db->error();
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			return $result;
		}
	}

	function confirm($transaction_id, $current_user = 0)
	{
		$this->db->trans_start();

		$data = array(
			'status_id' => 3 //confirmed
		);

		//update transaction
		$this->db->where("id", $transaction_id);
		$this->db->update("transactions", $data);

		$this->db->reset_query();

		//write log - transaction
		$log_data = array(
			"action" => "Confirmed Transaction",
			"created_by" => $current_user,
			"transaction_id" => $transaction_id,
			"item_id" => 0,
			"item_name" => "",
			"item_status" => "",
			"remarks" => "Transaction has been confirmed!"
		);
		$this->db->insert("trails", $log_data);

		// $this->db->reset_query();

		// //get all items in the transaction
		// $this->db->select("x.id, x.qty, x.description, s.status");
		// $this->db->from("items x");
		// $this->db->join("item_status s", "s.id = x.status_id", "left");
		// $this->db->where("x.transaction_id", $transaction_id);
		// $this->db->where("x.status_id <=", 7); //for gm approval or below

		// if ($query = $this->db->get()){
		// 	$results = $query->result();

		// 	$this->db->reset_query();

		// 	foreach ($results as $key => $value) {
		// 		//update item status
		// 		$this->db->reset_query();

		// 		$this->db->where("id", $value->id);
		// 		$this->db->update("items", $data);

		// 		//write log
		// 		$this->db->reset_query();

		// 		$series_no = str_pad($value->id, 5, "0", STR_PAD_LEFT);

		// 		$log_data = array(
		// 			"action" => "Cancel Item",
		// 			"created_by" => $current_user,
		// 			"transaction_id" => $transaction_id,
		// 			"item_id" => $value->id,
		// 			"item_name" => $value->description,
		// 			"item_status" => $value->status,
		// 			"remarks" => $series_no . " : " . $value->qty . " x " . $value->description . " -> " . $reason
		// 		);

		// 		$this->db->insert("trails", $log_data);
		// 	}
		// }

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			//$error = $this->db->error();
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			return true;
		}
	}

	function get_total_charges($transaction_id) {
		$this->db->select_sum('total');
		$this->db->where("status_id > 1");
		$this->db->where("transaction_id", $transaction_id);
		$query = $this->db->get('items');
		$total = $query->row()->total;

		return $total;
	}

	function get_total_paid($transaction_id){
		$this->db->select_sum('amount');
		$this->db->where("status_id > 1");
		$this->db->where("transaction_id", $transaction_id);
		$query = $this->db->get('payments');
		$total = $query->row()->amount;

		return $total;
	}



	// function for_dept_approval($transaction_id, $approver_id, $approver_name = "", $current_user = 0)
	// {
	// 	$this->db->trans_start();

	// 	$data = array(
	// 		'noted_by' => $approver_id,
	// 		'updated_by' => $current_user,
	// 		'updated_at' => date('Y-m-d H:i:s'),
	// 		'status_id' => 5 //for dept approval
	// 	);

	// 	//update transaction
	// 	$this->db->where("id", $transaction_id);
	// 	$this->db->update("transactions", $data);

	// 	//write log - transaction
	// 	$log_data = array(
	// 		"action" => "Send for Dept Approval",
	// 		"created_by" => $current_user,
	// 		"transaction_id" => $transaction_id,
	// 		"item_id" => 0,
	// 		"item_name" => "",
	// 		"item_status" => "",
	// 		"remarks" => "Send to: " . $approver_name
	// 	);
	// 	$this->db->insert("trails", $log_data);

	// 	$this->db->reset_query();

	// 	//get all items in the transaction
	// 	$this->db->select("x.id, x.qty, x.description, s.status");
	// 	$this->db->from("items x");
	// 	$this->db->join("item_status s", "s.id = x.status_id", "left");
	// 	$this->db->where("x.transaction_id", $transaction_id);
	// 	$this->db->where("x.status_id", 3); //draft

	// 	$query = $this->db->get();
	// 	$results = $query->result();

	// 	$this->db->reset_query();

	// 	foreach ($results as $key => $value) {
	// 		//update item status
	// 		$this->db->reset_query();

	// 		$this->db->where("id", $value->id);
	// 		$this->db->update("items", $data);

	// 		//write log
	// 		$this->db->reset_query();

	// 		$series_no = str_pad($value->id, 5, "0", STR_PAD_LEFT);

	// 		$log_data = array(
	// 			"action" => "Send for Dept Approval - Item",
	// 			"created_by" => $current_user,
	// 			"transaction_id" => $transaction_id,
	// 			"item_id" => $value->id,
	// 			"item_name" => $value->description,
	// 			"item_status" => $value->status,
	// 			"remarks" => "Send to: " . $approver_name . " -> " . $series_no . " : " . $value->qty . " x " . $value->description
	// 		);

	// 		$this->db->insert("trails", $log_data);
	// 	}

	// 	$this->db->trans_complete();

	// 	if ($this->db->trans_status() === FALSE) {
	// 		// generate an error... or use the log_message() function to log your error
	// 		//$error = $this->db->error();
	// 		throw new Exception("Error: Database problem, Please contact your System Administrator!");
	// 	} else {
	// 		return true;
	// 	}
	// }

	// function transfer_dept_approver($transaction_id, $approver_id, $approver_name = "", $current_user = 0)
	// {
	// 	$this->db->trans_start();

	// 	$data = array(
	// 		'noted_by' => $approver_id,
	// 		'updated_by' => $current_user,
	// 		'updated_at' => date('Y-m-d H:i:s')
	// 	);

	// 	//update transaction
	// 	$this->db->where("id", $transaction_id);
	// 	$this->db->update("transactions", $data);

	// 	//write log - transaction
	// 	$log_data = array(
	// 		"action" => "Transfer Dept Approver",
	// 		"created_by" => $current_user,
	// 		"transaction_id" => $transaction_id,
	// 		"item_id" => 0,
	// 		"item_name" => "",
	// 		"item_status" => "",
	// 		"remarks" => "Transferred to: " . $approver_name
	// 	);
	// 	$this->db->insert("trails", $log_data);

	// 	$this->db->reset_query();

	// 	//get all items in the transaction
	// 	$this->db->select("x.id, x.qty, x.description, s.status");
	// 	$this->db->from("items x");
	// 	$this->db->join("item_status s", "s.id = x.status_id", "left");
	// 	$this->db->where("x.transaction_id", $transaction_id);
	// 	$this->db->where("x.status_id", 5); //for dept approval

	// 	$query = $this->db->get();
	// 	$results = $query->result();

	// 	$this->db->reset_query();

	// 	foreach ($results as $key => $value) {
	// 		//update item status
	// 		$this->db->reset_query();

	// 		$this->db->where("id", $value->id);
	// 		$this->db->update("items", $data);

	// 		//write log
	// 		$this->db->reset_query();

	// 		$series_no = str_pad($value->id, 5, "0", STR_PAD_LEFT);

	// 		$log_data = array(
	// 			"action" => "Transfer Dept Approver - Item",
	// 			"created_by" => $current_user,
	// 			"transaction_id" => $transaction_id,
	// 			"item_id" => $value->id,
	// 			"item_name" => $value->description,
	// 			"item_status" => $value->status,
	// 			"remarks" => "Transferred to: " . $approver_name . " -> " . $series_no . " : " . $value->qty . " x " . $value->description
	// 		);

	// 		$this->db->insert("trails", $log_data);
	// 	}

	// 	$this->db->trans_complete();

	// 	if ($this->db->trans_status() === FALSE) {
	// 		// generate an error... or use the log_message() function to log your error
	// 		//$error = $this->db->error();
	// 		throw new Exception("Error: Database problem, Please contact your System Administrator!");
	// 	} else {
	// 		return true;
	// 	}
	// }

	// function for_gm_approval($transaction_id, $approver_id, $approver_name = "", $current_user = 0)
	// {
	// 	$this->db->trans_start();

	// 	$data = array(
	// 		'approved_by' => $approver_id,
	// 		'updated_by' => $current_user,
	// 		'updated_at' => date('Y-m-d H:i:s'),
	// 		'status_id' => 7 //for gm approval
	// 	);

	// 	//update transaction
	// 	$this->db->where("id", $transaction_id);
	// 	$this->db->update("transactions", $data);

	// 	//write log - transaction
	// 	$log_data = array(
	// 		"action" => "Send for GM Approval",
	// 		"created_by" => $current_user,
	// 		"transaction_id" => $transaction_id,
	// 		"item_id" => 0,
	// 		"item_name" => "",
	// 		"item_status" => "",
	// 		"remarks" => "Send to: " . $approver_name
	// 	);
	// 	$this->db->insert("trails", $log_data);

	// 	$this->db->reset_query();

	// 	//get all items in the transaction
	// 	$this->db->select("x.id, x.qty, x.description, s.status");
	// 	$this->db->from("items x");
	// 	$this->db->join("item_status s", "s.id = x.status_id", "left");
	// 	$this->db->where("x.transaction_id", $transaction_id);
	// 	$this->db->where("x.status_id", 6); //dept approved

	// 	$query = $this->db->get();
	// 	$results = $query->result();

	// 	$this->db->reset_query();

	// 	foreach ($results as $key => $value) {
	// 		//update item status
	// 		$this->db->reset_query();

	// 		$this->db->where("id", $value->id);
	// 		$this->db->update("items", $data);

	// 		//write log
	// 		$this->db->reset_query();

	// 		$series_no = str_pad($value->id, 5, "0", STR_PAD_LEFT);

	// 		$log_data = array(
	// 			"action" => "Send for GM Approval - Item",
	// 			"created_by" => $current_user,
	// 			"transaction_id" => $transaction_id,
	// 			"item_id" => $value->id,
	// 			"item_name" => $value->description,
	// 			"item_status" => $value->status,
	// 			"remarks" => "Send to: " . $approver_name . " -> " . $series_no . " : " . $value->qty . " x " . $value->description
	// 		);

	// 		$this->db->insert("trails", $log_data);
	// 	}

	// 	$this->db->trans_complete();

	// 	if ($this->db->trans_status() === FALSE) {
	// 		// generate an error... or use the log_message() function to log your error
	// 		//$error = $this->db->error();
	// 		throw new Exception("Error: Database problem, Please contact your System Administrator!");
	// 	} else {
	// 		return true;
	// 	}
	// }

	// function transfer_gm_approver($transaction_id, $approver_id, $approver_name = "", $current_user = 0)
	// {
	// 	$this->db->trans_start();

	// 	$data = array(
	// 		'approved_by' => $approver_id,
	// 		'updated_by' => $current_user,
	// 		'updated_at' => date('Y-m-d H:i:s')
	// 	);

	// 	//update transaction
	// 	$this->db->where("id", $transaction_id);
	// 	$this->db->update("transactions", $data);

	// 	//write log - transaction
	// 	$log_data = array(
	// 		"action" => "Transfer GM Approver",
	// 		"created_by" => $current_user,
	// 		"transaction_id" => $transaction_id,
	// 		"item_id" => 0,
	// 		"item_name" => "",
	// 		"item_status" => "",
	// 		"remarks" => "Transferred to: " . $approver_name
	// 	);
	// 	$this->db->insert("trails", $log_data);

	// 	$this->db->reset_query();

	// 	//get all items in the transaction
	// 	$this->db->select("x.id, x.qty, x.description, s.status");
	// 	$this->db->from("items x");
	// 	$this->db->join("item_status s", "s.id = x.status_id", "left");
	// 	$this->db->where("x.transaction_id", $transaction_id);
	// 	$this->db->where("x.status_id", 7); //for GM approval

	// 	$query = $this->db->get();
	// 	$results = $query->result();

	// 	$this->db->reset_query();

	// 	foreach ($results as $key => $value) {
	// 		//update item status
	// 		$this->db->reset_query();

	// 		$this->db->where("id", $value->id);
	// 		$this->db->update("items", $data);

	// 		//write log
	// 		$this->db->reset_query();

	// 		$series_no = str_pad($value->id, 5, "0", STR_PAD_LEFT);

	// 		$log_data = array(
	// 			"action" => "Transfer GM Approver - Item",
	// 			"created_by" => $current_user,
	// 			"transaction_id" => $transaction_id,
	// 			"item_id" => $value->id,
	// 			"item_name" => $value->description,
	// 			"item_status" => $value->status,
	// 			"remarks" => "Transferred to: " . $approver_name . " -> " . $series_no . " : " . $value->qty . " x " . $value->description
	// 		);

	// 		$this->db->insert("trails", $log_data);
	// 	}

	// 	$this->db->trans_complete();

	// 	if ($this->db->trans_status() === FALSE) {
	// 		// generate an error... or use the log_message() function to log your error
	// 		//$error = $this->db->error();
	// 		throw new Exception("Error: Database problem, Please contact your System Administrator!");
	// 	} else {
	// 		return true;
	// 	}
	// }
}
