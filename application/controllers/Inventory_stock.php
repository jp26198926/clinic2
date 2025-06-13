<?php
defined('BASEPATH') or die("No direct script allowed!");

class Inventory_stock extends CI_Controller
{
	protected $module_permission = array();
	protected $prefix; 
	protected $default_error_msg = "Error: Critical Error Encountered!";
	protected $role_id;
	protected $module = "inventory_stock";
	protected $module_description = "Inventory Stock";
	protected $page_name = "Stock Management";
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
					$this->load->model('stock_model', 'main_model');
					$this->load->model('stock_movements_model');
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
			
			$this->load->view('inventory_stock/index', $data);
		} else {
			$this->load->view('errors/html/error_403');
		}
	}

	function search()
	{
		$search = trim($this->input->get('search'));
		$location_id = intval($this->input->get('location_id'));
		
		try {
			$result = $this->main_model->search($search, $location_id, 1);
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

	function receive_stock()
	{
		$prefix = $this->prefix;
		$input_data = $this->input->post();

		if ($this->cf->module_permission("create", $this->module_permission)) {
			$product_id = intval($input_data['product_id']);
			$location_id = intval($input_data['location_id']);
			$qty = intval($input_data['qty']);
			$unit_cost = floatval($input_data['unit_cost']);
			$reference_type = $input_data['reference_type'];
			$reference_id = $input_data['reference_id'] ?? null;
			$notes = $input_data['notes'] ?? '';

			if ($product_id && $location_id && $qty > 0) {
				try {
					$result = $this->stock_movements_model->receive_stock(
						$product_id, 
						$location_id, 
						$qty, 
						$unit_cost, 
						$reference_type, 
						$reference_id, 
						$this->uid, 
						$notes
					);

					if ($result) {
						echo "Success: Stock received successfully!";
					} else {
						echo "Error: Failed to receive stock!";
					}
				} catch (Exception $ex) {
					echo $ex->getMessage();
				}
			} else {
				echo "Error: Please provide valid product, location, and quantity!";
			}
		} else {
			echo "Error: You don't have permission to perform this action!";
		}
	}

	function release_stock()
	{
		$prefix = $this->prefix;
		$input_data = $this->input->post();

		if ($this->cf->module_permission("create", $this->module_permission)) {
			$product_id = intval($input_data['product_id']);
			$location_id = intval($input_data['location_id']);
			$qty = intval($input_data['qty']);
			$reference_type = $input_data['reference_type'];
			$reference_id = $input_data['reference_id'] ?? null;
			$notes = $input_data['notes'] ?? '';

			if ($product_id && $location_id && $qty > 0) {
				try {
					$result = $this->stock_movements_model->release_stock(
						$product_id, 
						$location_id, 
						$qty, 
						$reference_type, 
						$reference_id, 
						$this->uid, 
						$notes
					);

					if ($result) {
						echo "Success: Stock released successfully!";
					} else {
						echo "Error: Failed to release stock!";
					}
				} catch (Exception $ex) {
					echo $ex->getMessage();
				}
			} else {
				echo "Error: Please provide valid product, location, and quantity!";
			}
		} else {
			echo "Error: You don't have permission to perform this action!";
		}
	}

	function transfer_stock()
	{
		$prefix = $this->prefix;
		$input_data = $this->input->post();

		if ($this->cf->module_permission("create", $this->module_permission)) {
			$product_id = intval($input_data['product_id']);
			$from_location_id = intval($input_data['from_location_id']);
			$to_location_id = intval($input_data['to_location_id']);
			$qty = intval($input_data['qty']);
			$notes = $input_data['notes'] ?? '';

			if ($product_id && $from_location_id && $to_location_id && $qty > 0 && $from_location_id != $to_location_id) {
				try {
					$result = $this->stock_movements_model->transfer_stock(
						$product_id, 
						$from_location_id, 
						$to_location_id, 
						$qty, 
						$this->uid, 
						$notes
					);

					if ($result) {
						echo "Success: Stock transferred successfully! Batch ID: " . $result;
					} else {
						echo "Error: Failed to transfer stock!";
					}
				} catch (Exception $ex) {
					echo $ex->getMessage();
				}
			} else {
				echo "Error: Please provide valid transfer details!";
			}
		} else {
			echo "Error: You don't have permission to perform this action!";
		}
	}

	function adjust_stock()
	{
		$prefix = $this->prefix;
		$input_data = $this->input->post();

		if ($this->cf->module_permission("update", $this->module_permission)) {
			$product_id = intval($input_data['product_id']);
			$location_id = intval($input_data['location_id']);
			$new_qty = intval($input_data['new_qty']);
			$notes = $input_data['notes'] ?? '';

			if ($product_id && $location_id && $new_qty >= 0) {
				try {
					$result = $this->stock_movements_model->adjust_stock(
						$product_id, 
						$location_id, 
						$new_qty, 
						$this->uid, 
						$notes
					);

					echo "Success: Stock adjusted successfully!";
				} catch (Exception $ex) {
					echo $ex->getMessage();
				}
			} else {
				echo "Error: Please provide valid adjustment details!";
			}
		} else {
			echo "Error: You don't have permission to perform this action!";
		}
	}

	function low_stock_report()
	{
		$location_id = intval($this->input->get('location_id'));
		
		try {
			$result = $this->main_model->get_low_stock_products($location_id);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	function get_product_stock_details()
	{
		$product_id = intval($this->input->get('product_id'));
		$location_id = intval($this->input->get('location_id'));
		
		if ($product_id && $location_id) {
			try {
				$stock = $this->main_model->search_by_product_location($product_id, $location_id);
				$movements = $this->stock_movements_model->get_product_movement_history($product_id, $location_id, 20);
				
				$result = array(
					'stock' => $stock,
					'movements' => $movements
				);
				
				echo json_encode($result);
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo "Error: Product ID and Location ID are required!";
		}
	}
}
