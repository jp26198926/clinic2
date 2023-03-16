<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Model_settings extends CI_Model
{
	private $tablename = "app_details";

	function timezone_list()
	{
		$this->db->order_by('zone');

		if ($query = $this->db->get('app_timezone')) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function currency_list()
	{
		if ($query = $this->db->get('app_currency')) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function app_details()
	{
		if ($query = $this->db->get('app_details')) {
			return $query->row();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function save(
		$company_code,
		$company_name,
		$company_address = "",
		$company_contact = "",
		$contact_person = "",
		$smtp_crypto = "ssl",
		$smtp_host = "smtp.gmail.com",
		$smtp_user = "",
		$smtp_pass = "",
		$smtp_port = 465,
		$timezone_id = 0,
		$currency_id = 0,
		$gst_percent = 0,
		$countdown_timer = 0,
		$current_user = 0
	) {
		$data = array(
			'company_code' => $company_code,
			'company_name' => $company_name,
			'company_address' => $company_address,
			'company_contact' => $company_contact,
			'contact_person' => $contact_person,
			'smtp_crypto' => $smtp_crypto,
			'smtp_host' => $smtp_host,
			'smtp_user' => $smtp_user,
			'smtp_pass' => $smtp_pass,
			'smtp_port' => $smtp_port,
			'timezone_id' => $timezone_id,
			'currency_id' => $currency_id,
			'gst_percent' => $gst_percent,
			'timer_countdown' => $countdown_timer,
			'dt_updated' => date('Y-m-d H:i:s'),
			'updated_by' => $current_user
		);

		if ($this->db->update('app_details', $data)) {
			return true;
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function app_save($data_input, $current_user = 0)
	{
		$data = array();
		$data["updated_by"] = $current_user;
		$data["dt_updated"] = date('Y-m-d H:i:s');

		foreach ($data_input as $key => $val) {
			$data[$key] = $val;
		}

		if ($this->db->update('app_details', $data)) {
			return true;
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function company_save($data_input, $current_user = 0)
	{
		$data = array();
		$data["updated_by"] = $current_user;
		$data["dt_updated"] = date('Y-m-d H:i:s');

		foreach ($data_input as $key => $val) {
			$data[$key] = $val;
		}

		if ($this->db->update('app_details', $data)) {
			return true;
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function email_save($data_input, $current_user = 0)
	{
		$data = array();
		$data["updated_by"] = $current_user;
		$data["dt_updated"] = date('Y-m-d H:i:s');

		foreach ($data_input as $key => $val) {
			$data[$key] = $val;
		}

		if ($this->db->update('app_details', $data)) {
			return true;
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function sms_save($data_input, $current_user = 0)
	{
		$data = array();
		$data["updated_by"] = $current_user;
		$data["dt_updated"] = date('Y-m-d H:i:s');

		foreach ($data_input as $key => $val) {
			$data[$key] = $val;
		}

		if ($this->db->update('app_details', $data)) {
			return true;
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function others_save($data_input, $current_user = 0)
	{
		$data = array();
		$data["updated_by"] = $current_user;
		$data["dt_updated"] = date('Y-m-d H:i:s');

		foreach ($data_input as $key => $val) {
			$data[$key] = $val;
		}

		if ($this->db->update('app_details', $data)) {
			return true;
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function update_logo($extension, $current_user = 0)
	{
		$data = array(
			'company_logo' => $extension,
			'updated_by' => $current_user,
			'dt_updated' => date('Y-m-d H:i:s')
		);

		if ($this->db->update('app_details', $data)) {
			return true;
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}
}