<?php
defined('BASEPATH') or die('No direct script access allowed');

class Shared_model extends CI_Model
{
	function write_to_log($action, $created_by, $transaction_id, $item_id = 0, $item_name = "", $item_status = "", $remarks = "")
	{
		$data = array(
			"action" => $action,
			"created_by" => $created_by,
			"transaction_id" => $transaction_id,
			"item_id" => $item_id,
			"item_name" => $item_name,
			"item_status" => $item_status,
			"remarks" => $remarks
		);

		if ($this->db->insert("trails", $data)) {
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

	function get_logs($request_id)
	{
		$this->db->select("t.*,
							CONCAT(u.fname, ' ', u.mname, ' ', u.lname) as created");
		$this->db->from("trails t");
		$this->db->join("user u", "u.id=t.created_by", "left");
		$this->db->where("transaction_id", $request_id);
		$this->db->order_by("t.id", "DESC");

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function doctors()
	{
		$this->db->select("`id`, CONCAT(`lname`,', ',`fname`,' ',`mname`) as name");
		$this->db->where("status_id", 1); //active
		$this->db->where_in("role_id", array(4));
		$this->db->order_by("lname, fname, mname");

		if ($query = $this->db->get("user")) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function get_user_details($id)
	{
		$this->db->where("id", $id);

		if ($query = $this->db->get("user")) {
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
}