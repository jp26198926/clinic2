<?php
defined('BASEPATH') or die('No direct script access allowed');

class Data_package_status_model extends CI_Model
{
	private $tablename = "package_status";

	function search($search = "")
	{

		$this->db->select("id, status");
		$this->db->from($this->tablename);
		$this->db->order_by('status');

		if ($search) {
			$this->db->where("CONCAT_WS(' ',status) LIKE '%{$search}%'");
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
		$this->db->select("id, status");
		$this->db->from($this->tablename);
		$this->db->where('id', $id);
		$this->db->order_by('status');

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function search_by_row($id)
	{
		$this->db->select("id, status");
		$this->db->from($this->tablename);
		$this->db->where('id', $id);
		$this->db->order_by('status');

		if ($query = $this->db->get()) {
			return $query->row();
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

	function advance_search($status)
	{
		$this->db->select("status");
		$this->db->from($this->tablename);
		$this->db->order_by("status");

		if ($status) {
			$this->db->like("status", $status);
		}

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function add($status)
	{
		$data = array(
			'status' => $status
		);

		if ($this->db->insert($this->tablename, $data)) {
			return $this->db->insert_id();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function update($id, $status)
	{
		$data = array(
			'status' => $status
		);

		$this->db->where('id', $id);

		if ($this->db->update($this->tablename, $data)) {
			return true;
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}
}