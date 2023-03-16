<?php
defined('BASEPATH') or die('No direct script access allowed');

class Data_package_model extends CI_Model
{
	private $tablename = "packages";

	function search($search = "", $active_only = 0, $limit=0)
	{

		$this->db->select(
			"x.*,
			 CONCAT(e.fname,' ', e.mname,' ', e.lname) as created,
             CONCAT(u.fname,' ', u.mname,' ', u.lname) as updated,
             CONCAT(d.fname,' ', d.mname,' ', d.lname) as deleted,
             s.status"
		);

		$this->db->from("packages x");
		$this->db->join("user e", "e.id=x.created_by", "left");
		$this->db->join("user u", "u.id=x.updated_by", "left");
		$this->db->join("user d", "d.id=x.deleted_by", "left");
		$this->db->join("package_status s", "s.id=x.status_id", "left");
		$this->db->order_by('x.package');

		if (intval($active_only) > 0) { //show only active
			$this->db->where("x.status_id", 2);
		}


		if (intval($limit) > 0){
			$this->db->limit($limit);
		}

		if ($search) {
			$this->db->where("CONCAT_WS(' ',x.package,s.status) LIKE '%{$search}%'");
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
			"x.*,
			 CONCAT(e.fname,' ', e.mname,' ', e.lname) as created,
             CONCAT(u.fname,' ', u.mname,' ', u.lname) as updated,
             CONCAT(d.fname,' ', d.mname,' ', d.lname) as deleted,
             s.status"
		);

		$this->db->from("packages x");
		$this->db->join("user e", "e.id=x.created_by", "left");
		$this->db->join("user u", "u.id=x.updated_by", "left");
		$this->db->join("user d", "d.id=x.deleted_by", "left");
		$this->db->join("package_status s", "s.id=x.status_id", "left");
		$this->db->where('x.id', $id);

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function search_by_row($id)
	{
		$this->db->select(
			"x.*,
			 CONCAT(e.fname,' ', e.mname,' ', e.lname) as created,
             CONCAT(u.fname,' ', u.mname,' ', u.lname) as updated,
             CONCAT(d.fname,' ', d.mname,' ', d.lname) as deleted,
             s.status"
		);

		$this->db->from("packages x");
		$this->db->join("user e", "e.id=x.created_by", "left");
		$this->db->join("user u", "u.id=x.updated_by", "left");
		$this->db->join("user d", "d.id=x.deleted_by", "left");
		$this->db->join("package_status s", "s.id=x.status_id", "left");
		$this->db->where('x.id', $id);

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

	function search_info($id)
	{
		$this->db->where('id', $id);

		if ($query = $this->db->get($this->tablename)) {

			if ($query->num_rows() > 0) {
				return $query->result();
			} else {
				return false;
			}
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function search_info_row($id)
	{
		$this->db->where('id', $id);

		if ($query = $this->db->get($this->tablename)) {

			if ($query->num_rows() > 0) {
				return $query->row();
			} else {
				return false;
			}
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function advance_search($input_data)
	{
		$this->db->select(
			"x.*,
			 CONCAT(e.fname,' ', e.mname,' ', e.lname) as created,
             CONCAT(u.fname,' ', u.mname,' ', u.lname) as updated,
             CONCAT(d.fname,' ', d.mname,' ', d.lname) as deleted,
             s.status"
		);

		$this->db->from("packages x");
		$this->db->join("user e", "e.id=x.created_by", "left");
		$this->db->join("user u", "u.id=x.updated_by", "left");
		$this->db->join("user d", "d.id=x.deleted_by", "left");
		$this->db->join("package_status s", "s.id=x.status_id", "left");
		$this->db->order_by('x.package');

		foreach ($input_data as $key => $val) {
			if ($val) {
				if ($key === "status_id") {
					$val = intval($val);
					$this->db->where("x." . $key, $val);
				} else {
					$this->db->like("x." . $key, $val);
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

	function add($data_input, $current_user = 0)
	{
		$data = array();
		$data["created_by"] = $current_user;

		foreach ($data_input as $key => $val) {
			$data[$key] = $val;
		}

		if ($this->db->insert($this->tablename, $data)) {
			return $this->db->insert_id();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function update($id, $data_input, $current_user = 0)
	{
		$data = array();
		$data["updated_by"] = $current_user;
		$data["updated_at"] = date("Y-m-d H:i:s");

		foreach ($data_input as $key => $val) {
			if ($key !== "id") {
				$data[$key] = $val;
			}
		}

		$this->db->where('id', $id);

		if ($this->db->update($this->tablename, $data)) {
			return true;
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function delete($id, $reason, $current_user = 0)
	{
		$data = array(
			'deleted_by' => $current_user,
			'deleted_at' => date('Y-m-d H:i:s'),
			'deleted_reason' => $reason,
			'status_id' => 1
		);
		$this->db->where("id", $id);

		if ($this->db->update($this->tablename, $data)) {
			return true;
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function activate($id)
	{
		$data = array(
			'deleted_by' => 0,
			'deleted_at' => NULL,
			'deleted_reason' => "",
			'status_id' => 2
		);
		$this->db->where("id", $id);

		if ($this->db->update($this->tablename, $data)) {
			return true;
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}
}