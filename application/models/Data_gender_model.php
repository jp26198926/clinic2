<?php
defined('BASEPATH') or die('No direct script access allowed');

class Data_gender_model extends CI_Model
{
	private $tablename = "genders";

	function search($search = "")
	{

		$this->db->select("id, gender");
		$this->db->from($this->tablename);
		$this->db->order_by('gender');

		if ($search) {
			$this->db->where("CONCAT_WS(' ',gender) LIKE '%{$search}%'");
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
		$this->db->select("id, gender");
		$this->db->from($this->tablename);
		$this->db->where('id', $id);
		$this->db->order_by('gender');

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function search_by_row($id)
	{
		$this->db->select("id, gender");
		$this->db->from($this->tablename);
		$this->db->where('id', $id);
		$this->db->order_by('gender');

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

	function advance_search($gender)
	{
		$this->db->select("gender");
		$this->db->from($this->tablename);
		$this->db->order_by("gender");

		if ($gender) {
			$this->db->like("gender", $gender);
		}

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function add($gender)
	{
		$data = array(
			'gender' => $gender
		);

		if ($this->db->insert($this->tablename, $data)) {
			return $this->db->insert_id();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function update($id, $gender)
	{
		$data = array(
			'gender' => $gender
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