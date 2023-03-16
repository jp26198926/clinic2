<?php
defined('BASEPATH') or die('No direct script access allowed');

class Enduser_my_request_model extends CI_Model
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
			 CONCAT('REQ-', LPAD(x.id,5,'0')) as request_no,
			 t.code as department_code, t.name as department_name,
			 v.code as division_code, v.name as division_name,
			 CONCAT(n.fname,' ', n.mname,' ', n.lname) as noted,
			 CONCAT(g.fname,' ', g.mname,' ', g.lname) as approved,
			 CONCAT(p.fname,' ', p.mname,' ', p.lname) as purchaser,
			 CONCAT(e.fname,' ', e.mname,' ', e.lname) as created,
             CONCAT(u.fname,' ', u.mname,' ', u.lname) as updated,
             CONCAT(d.fname,' ', d.mname,' ', d.lname) as deleted,
             s.status"
		);

		$this->db->from("transactions x");
		$this->db->join("departments t", "t.id=x.department_id", "left");
		$this->db->join("divisions v", "v.id=x.division_id", "left");
		$this->db->join("user n", "n.id=x.noted_by", "left");
		$this->db->join("user g", "g.id=x.approved_by", "left");
		$this->db->join("user p", "p.id=x.purchaser_id", "left");
		$this->db->join("user e", "e.id=x.created_by", "left");
		$this->db->join("user u", "u.id=x.updated_by", "left");
		$this->db->join("user d", "d.id=x.deleted_by", "left");
		$this->db->join("transaction_status s", "s.id=x.status_id", "left");
		$this->db->where("x.id", $id);
		$this->db->where("x.created_by", $current_user); //only the request owner can see

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
			 CONCAT('REQ-', LPAD(x.id,5,'0')) as request_no,
			 t.code as department_code, t.name as department_name,
			 v.code as division_code, v.name as division_name,
			 CONCAT(n.fname,' ', n.mname,' ', n.lname) as noted,
			 CONCAT(g.fname,' ', g.mname,' ', g.lname) as approved,
			 CONCAT(p.fname,' ', p.mname,' ', p.lname) as purchaser,
			 CONCAT(e.fname,' ', e.mname,' ', e.lname) as created,
             CONCAT(u.fname,' ', u.mname,' ', u.lname) as updated,
             CONCAT(d.fname,' ', d.mname,' ', d.lname) as deleted,
             s.status"
		);

		$this->db->from("transactions x");
		$this->db->join("departments t", "t.id=x.department_id", "left");
		$this->db->join("divisions v", "v.id=x.division_id", "left");
		$this->db->join("user n", "n.id=x.noted_by", "left");
		$this->db->join("user g", "g.id=x.approved_by", "left");
		$this->db->join("user p", "p.id=x.purchaser_id", "left");
		$this->db->join("user e", "e.id=x.created_by", "left");
		$this->db->join("user u", "u.id=x.updated_by", "left");
		$this->db->join("user d", "d.id=x.deleted_by", "left");
		$this->db->join("transaction_status s", "s.id=x.status_id", "left");
		$this->db->where("x.created_by", $current_user);
		$this->db->order_by('x.id');

		if ($search) {
			$this->db->where(
				"CONCAT_WS(
					' ',
					CONCAT('REQ-', LPAD(x.id,5,'0')),
					x.date,
					t.code, t.name,
					v.code, v.name,
					e.fname, e.mname, e.lname,
					n.fname, n.mname, n.lname,
					g.fname, g.mname, g.lname,
					p.fname, p.mname, p.lname,
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
			 CONCAT('REQ-', LPAD(x.id,5,'0')) as request_no,
			 t.code as department_code, t.name as department_name,
			 v.code as division_code, v.name as division_name,
			 CONCAT(n.fname,' ', n.mname,' ', n.lname) as noted,
			 CONCAT(g.fname,' ', g.mname,' ', g.lname) as approved,
			 CONCAT(p.fname,' ', p.mname,' ', p.lname) as purchaser,
			 CONCAT(e.fname,' ', e.mname,' ', e.lname) as created,
             CONCAT(u.fname,' ', u.mname,' ', u.lname) as updated,
             CONCAT(d.fname,' ', d.mname,' ', d.lname) as deleted,
             s.status"
		);

		$this->db->from("transactions x");
		$this->db->join("departments t", "t.id=x.department_id", "left");
		$this->db->join("divisions v", "v.id=x.division_id", "left");
		$this->db->join("user n", "n.id=x.noted_by", "left");
		$this->db->join("user g", "g.id=x.approved_by", "left");
		$this->db->join("user p", "p.id=x.purchaser_id", "left");
		$this->db->join("user e", "e.id=x.created_by", "left");
		$this->db->join("user u", "u.id=x.updated_by", "left");
		$this->db->join("user d", "d.id=x.deleted_by", "left");
		$this->db->join("transaction_status s", "s.id=x.status_id", "left");
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
				} else if ($col === "requested") {
					$this->db->like("CONCAT(e.fname,' ', e.mname,' ', e.lname)", $val);
				} else if ($col === "dept_approved") {
					$this->db->like("CONCAT(n.fname,' ', n.mname,' ', n.lname)", $val);
				} else if ($col === "gm_approved") {
					$this->db->like("CONCAT(g.fname,' ', g.mname,' ', g.lname)", $val);
				} else if ($col === "purchaser") {
					$this->db->like("CONCAT(p.fname,' ', p.mname,' ', p.lname)", $val);
				} else if ($col === "request_no") {
					$this->db->like("CONCAT('REQ-', LPAD(x.id,5,'0'))", $val);
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
	function cancel_check($transaction_id)
	{
		//check if some item status has a value of more than 4 then it return false and cannot be cancelled
		$this->db->where("transaction_id", $transaction_id);
		$this->db->where("status_id >", 7); //for gm approval and above

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
		$this->db->trans_start();

		$data = array(
			'deleted_by' => $current_user,
			'deleted_at' => date('Y-m-d H:i:s'),
			'deleted_reason' => $reason,
			'status_id' => 1 //cancel
		);

		//update transaction
		$this->db->where("id", $transaction_id);
		$this->db->update("transactions", $data);

		$this->db->reset_query();

		//write log - transaction
		$log_data = array(
			"action" => "Cancel Request",
			"created_by" => $current_user,
			"transaction_id" => $transaction_id,
			"item_id" => 0,
			"item_name" => "",
			"item_status" => "",
			"remarks" => $reason
		);
		$this->db->insert("trails", $log_data);

		$this->db->reset_query();

		//get all items in the transaction
		$this->db->select("x.id, x.qty, x.description, s.status");
		$this->db->from("items x");
		$this->db->join("item_status s", "s.id = x.status_id", "left");
		$this->db->where("x.transaction_id", $transaction_id);
		$this->db->where("x.status_id <=", 7); //for gm approval or below

		$query = $this->db->get();
		$results = $query->result();

		$this->db->reset_query();

		foreach ($results as $key => $value) {
			//update item status
			$this->db->reset_query();

			$this->db->where("id", $value->id);
			$this->db->update("items", $data);

			//write log
			$this->db->reset_query();

			$series_no = str_pad($value->id, 5, "0", STR_PAD_LEFT);

			$log_data = array(
				"action" => "Cancel Item",
				"created_by" => $current_user,
				"transaction_id" => $transaction_id,
				"item_id" => $value->id,
				"item_name" => $value->description,
				"item_status" => $value->status,
				"remarks" => $series_no . " : " . $value->qty . " x " . $value->description . " -> " . $reason
			);

			$this->db->insert("trails", $log_data);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			//$error = $this->db->error();
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			return true;
		}
	}

	function for_dept_approval($transaction_id, $approver_id, $approver_name = "", $current_user = 0)
	{
		$this->db->trans_start();

		$data = array(
			'noted_by' => $approver_id,
			'updated_by' => $current_user,
			'updated_at' => date('Y-m-d H:i:s'),
			'status_id' => 5 //for dept approval
		);

		//update transaction
		$this->db->where("id", $transaction_id);
		$this->db->update("transactions", $data);

		//write log - transaction
		$log_data = array(
			"action" => "Send for Dept Approval",
			"created_by" => $current_user,
			"transaction_id" => $transaction_id,
			"item_id" => 0,
			"item_name" => "",
			"item_status" => "",
			"remarks" => "Send to: " . $approver_name
		);
		$this->db->insert("trails", $log_data);

		$this->db->reset_query();

		//get all items in the transaction
		$this->db->select("x.id, x.qty, x.description, s.status");
		$this->db->from("items x");
		$this->db->join("item_status s", "s.id = x.status_id", "left");
		$this->db->where("x.transaction_id", $transaction_id);
		$this->db->where("x.status_id", 3); //draft

		$query = $this->db->get();
		$results = $query->result();

		$this->db->reset_query();

		foreach ($results as $key => $value) {
			//update item status
			$this->db->reset_query();

			$this->db->where("id", $value->id);
			$this->db->update("items", $data);

			//write log
			$this->db->reset_query();

			$series_no = str_pad($value->id, 5, "0", STR_PAD_LEFT);

			$log_data = array(
				"action" => "Send for Dept Approval - Item",
				"created_by" => $current_user,
				"transaction_id" => $transaction_id,
				"item_id" => $value->id,
				"item_name" => $value->description,
				"item_status" => $value->status,
				"remarks" => "Send to: " . $approver_name . " -> " . $series_no . " : " . $value->qty . " x " . $value->description
			);

			$this->db->insert("trails", $log_data);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			//$error = $this->db->error();
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			return true;
		}
	}

	function transfer_dept_approver($transaction_id, $approver_id, $approver_name = "", $current_user = 0)
	{
		$this->db->trans_start();

		$data = array(
			'noted_by' => $approver_id,
			'updated_by' => $current_user,
			'updated_at' => date('Y-m-d H:i:s')
		);

		//update transaction
		$this->db->where("id", $transaction_id);
		$this->db->update("transactions", $data);

		//write log - transaction
		$log_data = array(
			"action" => "Transfer Dept Approver",
			"created_by" => $current_user,
			"transaction_id" => $transaction_id,
			"item_id" => 0,
			"item_name" => "",
			"item_status" => "",
			"remarks" => "Transferred to: " . $approver_name
		);
		$this->db->insert("trails", $log_data);

		$this->db->reset_query();

		//get all items in the transaction
		$this->db->select("x.id, x.qty, x.description, s.status");
		$this->db->from("items x");
		$this->db->join("item_status s", "s.id = x.status_id", "left");
		$this->db->where("x.transaction_id", $transaction_id);
		$this->db->where("x.status_id", 5); //for dept approval

		$query = $this->db->get();
		$results = $query->result();

		$this->db->reset_query();

		foreach ($results as $key => $value) {
			//update item status
			$this->db->reset_query();

			$this->db->where("id", $value->id);
			$this->db->update("items", $data);

			//write log
			$this->db->reset_query();

			$series_no = str_pad($value->id, 5, "0", STR_PAD_LEFT);

			$log_data = array(
				"action" => "Transfer Dept Approver - Item",
				"created_by" => $current_user,
				"transaction_id" => $transaction_id,
				"item_id" => $value->id,
				"item_name" => $value->description,
				"item_status" => $value->status,
				"remarks" => "Transferred to: " . $approver_name . " -> " . $series_no . " : " . $value->qty . " x " . $value->description
			);

			$this->db->insert("trails", $log_data);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			//$error = $this->db->error();
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			return true;
		}
	}

	function for_gm_approval($transaction_id, $approver_id, $approver_name = "", $current_user = 0)
	{
		$this->db->trans_start();

		$data = array(
			'approved_by' => $approver_id,
			'updated_by' => $current_user,
			'updated_at' => date('Y-m-d H:i:s'),
			'status_id' => 7 //for gm approval
		);

		//update transaction
		$this->db->where("id", $transaction_id);
		$this->db->update("transactions", $data);

		//write log - transaction
		$log_data = array(
			"action" => "Send for GM Approval",
			"created_by" => $current_user,
			"transaction_id" => $transaction_id,
			"item_id" => 0,
			"item_name" => "",
			"item_status" => "",
			"remarks" => "Send to: " . $approver_name
		);
		$this->db->insert("trails", $log_data);

		$this->db->reset_query();

		//get all items in the transaction
		$this->db->select("x.id, x.qty, x.description, s.status");
		$this->db->from("items x");
		$this->db->join("item_status s", "s.id = x.status_id", "left");
		$this->db->where("x.transaction_id", $transaction_id);
		$this->db->where("x.status_id", 6); //dept approved

		$query = $this->db->get();
		$results = $query->result();

		$this->db->reset_query();

		foreach ($results as $key => $value) {
			//update item status
			$this->db->reset_query();

			$this->db->where("id", $value->id);
			$this->db->update("items", $data);

			//write log
			$this->db->reset_query();

			$series_no = str_pad($value->id, 5, "0", STR_PAD_LEFT);

			$log_data = array(
				"action" => "Send for GM Approval - Item",
				"created_by" => $current_user,
				"transaction_id" => $transaction_id,
				"item_id" => $value->id,
				"item_name" => $value->description,
				"item_status" => $value->status,
				"remarks" => "Send to: " . $approver_name . " -> " . $series_no . " : " . $value->qty . " x " . $value->description
			);

			$this->db->insert("trails", $log_data);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			//$error = $this->db->error();
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			return true;
		}
	}

	function transfer_gm_approver($transaction_id, $approver_id, $approver_name = "", $current_user = 0)
	{
		$this->db->trans_start();

		$data = array(
			'approved_by' => $approver_id,
			'updated_by' => $current_user,
			'updated_at' => date('Y-m-d H:i:s')
		);

		//update transaction
		$this->db->where("id", $transaction_id);
		$this->db->update("transactions", $data);

		//write log - transaction
		$log_data = array(
			"action" => "Transfer GM Approver",
			"created_by" => $current_user,
			"transaction_id" => $transaction_id,
			"item_id" => 0,
			"item_name" => "",
			"item_status" => "",
			"remarks" => "Transferred to: " . $approver_name
		);
		$this->db->insert("trails", $log_data);

		$this->db->reset_query();

		//get all items in the transaction
		$this->db->select("x.id, x.qty, x.description, s.status");
		$this->db->from("items x");
		$this->db->join("item_status s", "s.id = x.status_id", "left");
		$this->db->where("x.transaction_id", $transaction_id);
		$this->db->where("x.status_id", 7); //for GM approval

		$query = $this->db->get();
		$results = $query->result();

		$this->db->reset_query();

		foreach ($results as $key => $value) {
			//update item status
			$this->db->reset_query();

			$this->db->where("id", $value->id);
			$this->db->update("items", $data);

			//write log
			$this->db->reset_query();

			$series_no = str_pad($value->id, 5, "0", STR_PAD_LEFT);

			$log_data = array(
				"action" => "Transfer GM Approver - Item",
				"created_by" => $current_user,
				"transaction_id" => $transaction_id,
				"item_id" => $value->id,
				"item_name" => $value->description,
				"item_status" => $value->status,
				"remarks" => "Transferred to: " . $approver_name . " -> " . $series_no . " : " . $value->qty . " x " . $value->description
			);

			$this->db->insert("trails", $log_data);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			//$error = $this->db->error();
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			return true;
		}
	}
}