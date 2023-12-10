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

		$error = array();
		$data = array();
		$transaction_id = 0;

		$data["created_by"] = $current_user;
		$data["status_id"] = 3; //confirmed
		$data["location_id"] = 2; //Triage

		foreach ($data_input as $key => $val) {
			$col = str_replace("_new", "", $key);
			if ($col !== "logs"){ //exclude logs
				$data[$col] = $val;
			}
		}

		if (!$this->db->insert($this->tablename, $data)) {
			$error = $this->db->error();
			//throw new Exception("Error: " . $error['message']);
		} else {
			$transaction_id = $this->db->insert_id();
			$transaction_no = str_pad($transaction_id,5,"0",STR_PAD_LEFT);

			//write log
			$this->db->reset_query();
			$log_data = array(
				"action" => "Create Transaction",
				"created_by" => $current_user,
				"transaction_id" => $transaction_id,
				"item_id" => 0,
				"item_name" => "",
				"item_status" => "",
				"remarks" => "Transaction No: " . $transaction_no
			);
			$this->db->insert("trails", $log_data);

			//write to log other fields
			// Loop through the array and access each item
			foreach ($data_input["logs"] as $key => $value) {
				// $key is the specific key for each item
				// $value is the corresponding value

				$this->db->reset_query();
				$log_data = array(
					"action" => "Create Transaction",
					"created_by" => $current_user,
					"transaction_id" => $transaction_id,
					"item_id" => 0,
					"item_name" => "",
					"item_status" => "",
					"remarks" => $key . " : " .  $value
				);
				$this->db->insert("trails", $log_data);
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			//$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		} else {
			return $transaction_id;
		}
	}

	function update($id, $data_input, $current_user = 0)
	{
		$this->db->trans_start();

		$error = array();

		$data = array();
		$data["updated_by"] = $current_user;
		$data["updated_at"] = date("Y-m-d H:i:s");

		foreach ($data_input as $key => $val) {
			$col = str_replace("_update", "", $key);
			if ($col !== "logs"){ //exclude logs
				$data[$col] = $val;
			}
		}

		$this->db->where("id", $id);

		if (!$this->db->update($this->tablename, $data)) {
			$error = $this->db->error();
			//throw new Exception("Error: " . $error['message']);
		}else{

			//write to log other fields
			// Loop through the array and access each item
			if (array_key_exists("logs", $data_input)){
				foreach ($data_input["logs"] as $key => $value) {
					// $key is the specific key for each item
					// $value is the corresponding value

					$this->db->reset_query();
					$log_data = array(
						"action" => "Update Transaction",
						"created_by" => $current_user,
						"transaction_id" => $id,
						"item_id" => 0,
						"item_name" => "",
						"item_status" => "",
						"remarks" => $key . " : " .  $value
					);
					$this->db->insert("trails", $log_data);
				}
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			//$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		} else {
			return true;
		}
	}

	function view($id, $location_ids = [])
	{
		$this->db->select(
			"x.*,
			 LPAD(x.id,5,'0') as transaction_no,
			 tt.trans_type,
			 CONCAT(p.lastname, ', ', p.firstname,' ', p.middlename) as patient,
			 c.name as client, c.name as company,
			 ct.charging_type,
			 pm.payment_method,
			 i.name as insurance, i.company_name as insurance_company_name,
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

		if (is_array($location_ids) && count($location_ids) > 0) { //if location id specified
			$this->db->where_in("x.location_id", $location_ids);
		}

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

	function search($search = "", $status_ids = [], $location_ids = [])
	{
		$this->db->select(
			"x.*,
			 LPAD(x.id,5,'0') as transaction_no,
			 tt.trans_type,
			 CONCAT(p.firstname,' ',p.middlename,' ',p.lastname) as patient,
			 c.name as client,
			 ct.charging_type,
			 pm.payment_method,
			 i.name as insurance, i.company_name as insurance_company_name,
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
		$this->db->order_by('x.id');

		if ($search) {
			$this->db->where("CONCAT_WS(
					' ',
					LPAD(x.id,5,'0'),
					x.date,
					tt.trans_type,
					c.name,
					ct.charging_type,
					pm.payment_method,
					l.location,
					dr.fname, dr.mname, dr.lname,
					p.firstname, p.middlename, p.lastname,
					s.status
				)
				LIKE '%{$search}%'"
			);
		}

		if (is_array($status_ids) && count($status_ids) > 0) { //if status id specified
			$this->db->where_in("x.status_id", $status_ids);
		}

		if (is_array($location_ids) && count($location_ids) > 0) { //if location id specified
			$this->db->where_in("x.location_id", $location_ids);
		}

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function advance_search($data_input, $location_ids=[])
	{
		$this->db->select(
			"x.*,
			 LPAD(x.id,5,'0') as transaction_no,
			 tt.trans_type,
			 CONCAT(p.firstname,' ',p.middlename,' ',p.lastname) as patient,
			 c.name as client,
			 ct.charging_type,
			 pm.payment_method,
			 i.name as insurance, i.company_name as insurance_company_name,
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

		if (is_array($location_ids) && count($location_ids) > 0) { //if location id specified
			$this->db->where_in("x.location_id", $location_ids);
		}

		foreach ($data_input as $key => $val) {
			$col = str_replace("_asearch", "", $key);

			if ($val) {
				if ($col === "date_from") {
					$this->db->where("x.date >=", $val);
				} else if ($col === "date_to") {
					$this->db->where("x.date <=", $val);	
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
	function transfer($transaction_id, $location_id, $location, $current_user = 0){
		$this->db->trans_start();

		$error = array();


		$this->db->set("location_id", $location_id);
		$this->db->where("id", $transaction_id);

		if (!$this->db->update($this->tablename)) {
			$error = $this->db->error();
		}else{
			$this->db->reset_query();
			$log_data = array(
				"action" => "Transfer Location",
				"created_by" => $current_user,
				"transaction_id" => $transaction_id,
				"item_id" => 0,
				"item_name" => "",
				"item_status" => "",
				"remarks" => "Sent To : " . $location
			);
			$this->db->insert("trails", $log_data);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			//$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		} else {
			return true;
		}
	}

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

	function get_summary($dt_from = "", $dt_to = "")
	{
		$this->db->select("CASE
								WHEN status_id = 1 THEN 'CANCELLED'
								WHEN status_id = 3 THEN 'PENDING'
								WHEN status_id = 4 THEN 'COMPLETED'
							END AS label");
		$this->db->select("COUNT(*) AS data");
		$this->db->select("CASE
								WHEN status_id = 1 THEN '#DA5430'
        						WHEN status_id = 3 THEN '#2091CF'
        						WHEN status_id = 4 THEN '#68BC31'
        						ELSE '#000000'
    						END AS color");
		$this->db->from("transactions");

		//if both dt from and to are empty then set today's date
		if ($dt_from === "" && $dt_to === "") {
			$dt_from = $dt_from ? $dt_from : date("Y-m-d");
			$dt_to = $dt_to ? $dt_to : date("Y-m-d");
		}

		if ($dt_from) {
			$this->db->where("date >=", $dt_from);
		}

		if ($dt_to) {
			$this->db->where("date <=", $dt_to);
		}

		$this->db->group_by('status_id');

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}
}
