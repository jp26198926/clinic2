<?php
defined('BASEPATH') or die("No direct script allowed!");

class Inventory_movements extends CI_Controller
{
	protected $module_permission = array();
	protected $prefix; 
	protected $default_error_msg = "Error: Critical Error Encountered!";
	protected $role_id;
	protected $module = "inventory_movements";
	protected $module_description = "Stock Movements";
	protected $page_name = "Stock Movements";
	protected $parent_menu = "Inventory";
	protected $uid = 0;
	protected $uname;
	protected $ufullname;
	protected $app_code;
	protected $app_name;
	protected $app_title;
	protected $app_version;
	protected $company_code;
	protected $company_name;
	protected $company_address;
	protected $company_contact;

	function __construct()
	{
		parent::__construct();

		//get session prefix from db
		$this->load->model('app_details_m', 'ad');
		try {
			$ad = $this->ad->get_details();
			if ($ad) {
				$this->prefix = $ad->session_prefix;
				date_default_timezone_set($ad->timezone);

				if (!isset($this->session->userdata[$this->prefix . '_logged_in'])) {
					$this->session->set_userdata($this->prefix . '_to_page', current_url());
					redirect(base_url() . 'authentication');
				} else {
					$prefix = $this->prefix;

					$this->uid = $this->session->userdata[$prefix . '_logged_in'][$prefix . '_uid'];
					$this->uname = strtoupper($this->session->userdata[$prefix . '_logged_in'][$prefix . '_fname']);
					$this->app_code = $this->session->userdata[$prefix . '_logged_in'][$prefix . '_app_code'];
					$this->app_name = $this->session->userdata[$prefix . '_logged_in'][$prefix . '_app_name'];
					$this->app_title = $this->session->userdata[$prefix . '_logged_in'][$prefix . '_app_title'];
					$this->app_version = $this->session->userdata[$prefix . '_logged_in'][$prefix . '_app_version'];
					$this->company_code = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_code"];
					$this->company_name = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_name"];
					$this->company_address = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_address"];
					$this->company_contact = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_contact"];

					$this->load->model('authentication_m', 'a');
					$this->role_id = $this->session->userdata[$this->prefix . '_logged_in'][$this->prefix . '_role'];
					$this->module_permission = $this->a->allow_permission($this->role_id, $this->module);

					$this->load->library('custom_function', NULL, 'cf');

					//load needed models for this page
					$this->load->model('stock_movements_model', 'main_model');
					$this->load->model('data_product_model');
					$this->load->model('data_location_model');
				}
			}
		} catch (Exception $ex) {
			$this->session->set_userdata($this->prefix . '_to_page', current_url());
			redirect(base_url() . 'authentication');
		}
	}

	function index()
	{
		$prefix = $this->prefix;

		$data['prefix'] = $prefix;
		$data['app_title'] = $this->session->userdata[$prefix . '_logged_in'][$prefix . '_app_title'];
		$data['app_version'] = $this->session->userdata[$prefix . '_logged_in'][$prefix . '_app_version'];
		$data['page_name'] = $this->page_name;
		$data['parent_menu'] = $this->parent_menu;
		$data['module'] = $this->module;
		$data['module_description'] = $this->module_description;

		if ($this->cf->is_allowed_module($this->module, $prefix)) {
			$data['role_id'] = $this->role_id;
			$data['module_permission'] = $this->module_permission;
			$data['uid'] = strtoupper($this->session->userdata[$prefix . '_logged_in'][$prefix . '_uid']);
			$data['ufname'] = strtoupper($this->session->userdata[$prefix . '_logged_in'][$prefix . '_fname']);

			$data['locations'] = $this->data_location_model->search("", 1);
			
			$this->load->view('inventory_movements/index', $data);
		} else {
			$this->load->view('errors/html/error_403');
		}
	}

	function search()
	{
		$search = trim($this->input->get('search'));
		$location_id = intval($this->input->get('location_id'));
		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$movement_type = $this->input->get('movement_type');
		
		try {
			$result = $this->main_model->search($search, $location_id, $date_from, $date_to, $movement_type);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	function search_info_row()
	{
		$id = intval($this->input->post('id'));
		if ($id) {
			try {
				$info = $this->main_model->search_by_id($id);
				if ($info) {
					echo json_encode($info);
				} else {
					echo "Error: No Record Found!";
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	function get_product_history()
	{
		$product_id = intval($this->input->get('product_id'));
		$location_id = intval($this->input->get('location_id'));
		$limit = intval($this->input->get('limit')) ?: 50;
		
		if ($product_id) {
			try {
				$result = $this->main_model->get_product_movement_history($product_id, $location_id, $limit);
				echo json_encode($result);
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo "Error: Product ID is required!";
		}
	}
}
