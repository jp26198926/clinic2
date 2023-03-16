<?php
defined('BASEPATH') or die('No direct script access allowed');

class Data_civil_model extends CI_Model
{
	private $tablename = "civils";

	function search($search = "")
	{

		$this->db->select("id, civil");
		$this->db->from($this->tablename);
		$this->db->order_by('civil');

		if ($search) {
			$this->db->where("CONCAT_WS(' ',civil) LIKE '%{$search}%'");
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
		$this->db->select("id, civil");
		$this->db->from($this->tablename);
		$this->db->where('id', $id);
		$this->db->order_by('civil');

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function search_by_row($id)
	{
		$this->db->select("id, civil");
		$this->db->from($this->tablename);
		$this->db->where('id', $id);
		$this->db->order_by('civil');

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

	function advance_search($civil)
	{
		$this->db->select("civil");
		$this->db->from($this->tablename);
		$this->db->order_by("civil");

		if ($civil) {
			$this->db->like("civil", $civil);
		}

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function add($civil)
	{
		$data = array(
			'civil' => $civil
		);

		if ($this->db->insert($this->tablename, $data)) {
			return $this->db->insert_id();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function update($id, $civil)
	{
		$data = array(
			'civil' => $civil
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