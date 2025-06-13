<?php
defined('BASEPATH') or die('No direct script access allowed');

class Inventory_stock extends CI_Controller
{
	private $prefix;
	private $page_name;
	private $parent_menu;
	private $module;
	private $module_description;
	private $default_error_msg;
	private $role_id;
	private $module_permission;
	private $uid;
	private $fname;
	private $lname;
	private $company_code;
	private $company_name;
	private $company_address;
	private $company_contact;

	function __construct()
	{
		parent::__construct();

		// Define controller-specific properties
		$this->page_name = 'Inventory Stock';
		$this->parent_menu = 'Inventory';
		$this->module = 'inventory_stock';
		$this->module_description = 'Inventory stock levels and locations';
		$this->default_error_msg = "Error: Invalid Access!";
		// $this->prefix will be set dynamically

		// Load the model to get application details (like session prefix and timezone)
		$this->load->model('app_details_m', 'ad');
		try {
			$app_details = $this->ad->get_details();

			if ($app_details && isset($app_details->session_prefix) && isset($app_details->timezone)) {
				$this->prefix = $app_details->session_prefix;
				date_default_timezone_set($app_details->timezone);

				// Check if user is logged in
				if (!$this->session->userdata($this->prefix . '_logged_in')) {
					$this->session->set_userdata($this->prefix . '_to_page', current_url());
					redirect(base_url() . 'authentication');
				} else {
					// User is logged in, set user-specific properties
					$this->uid = $this->session->userdata[$this->prefix . '_logged_in'][$this->prefix . '_uid'];
					$this->fname = $this->session->userdata[$this->prefix . '_logged_in'][$this->prefix . '_fname'];
					$this->lname = $this->session->userdata[$this->prefix . '_logged_in'][$this->prefix . '_lname'];

					$this->company_code = $this->session->userdata[$this->prefix . "_logged_in"][$this->prefix . "_company_code"];
					$this->company_name = $this->session->userdata[$this->prefix . "_logged_in"][$this->prefix . "_company_name"];
					$this->company_address = $this->session->userdata[$this->prefix . "_logged_in"][$this->prefix . "_company_address"];
					$this->company_contact = $this->session->userdata[$this->prefix . "_logged_in"][$this->prefix . "_company_contact"];

					// Load authentication model and check permissions
					$this->load->model('authentication_m', 'a');
					$this->role_id = $this->session->userdata[$this->prefix . '_logged_in'][$this->prefix . '_role'];
					$this->module_permission = $this->a->allow_permission($this->role_id, $this->module);

					// Load libraries and other necessary models
					$this->load->library('custom_function', NULL, 'cf');
					$this->load->model('inventory_stock_model', 'main_model');
					$this->load->model('inventory_stock_status_model');
					$this->load->model('inventory_products_model');
					$this->load->model('data_location_model');
				}
			} else {
				// Critical error: Application details (prefix/timezone) not found or incomplete
				log_message('error', 'Critical error: Application details not found or incomplete in ' . __CLASS__ . ' constructor.');
				// Set a generic flash message if possible, though prefix might be unknown
				$this->session->set_flashdata('error_message', 'System configuration error. Please contact administrator.');
				redirect(base_url() . 'authentication'); // Redirect to login or an error page
			}
		} catch (Exception $ex) {
			log_message('error', 'Exception in ' . __CLASS__ . ' constructor: ' . $ex->getMessage());
			// Use a generic session key if prefix is not set or an error occurred before it was set
			$session_key_for_redirect = isset($this->prefix) ? $this->prefix . '_to_page' : 'unknown_prefix_redirect_to_page';
			$this->session->set_userdata($session_key_for_redirect, current_url());
			redirect(base_url() . 'authentication');
		}
		//end of getting session prefix
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

			$data['status'] = $this->inventory_stock_status_model->search("");
			$data['products'] = $this->inventory_products_model->search("");
			$data['locations'] = $this->data_location_model->search("");
			$this->load->view('inventory_stock/index', $data);
		} else {
			$this->session->set_userdata($this->prefix . '_to_page', current_url());
			redirect(base_url() . 'authentication');
		}
	}

	function search()
	{
		$search = trim($this->input->get('search'));
		try {
			$result = $this->main_model->search($search);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	function search_info_row()
	{
		$id = $this->input->post('id');
		$result = array('status' => 'error', 'message' => 'Invalid request');
		
		if ($id) {
			try {
				$data = $this->main_model->search_by_row($id);
				if ($data) {
					$result = array('status' => 'success', 'data' => $data);
				} else {
					$result = array('status' => 'error', 'message' => 'Stock record not found');
				}
			} catch (Exception $ex) {
				$result = array('status' => 'error', 'message' => $ex->getMessage());
			}
		}
		
		echo json_encode($result);
	}

	function add()
	{
		$result = array('status' => 'error', 'message' => 'Invalid request');
		
		$product_id = $this->input->post('product_id');
		$location_id = $this->input->post('location_id');
		$quantity = $this->input->post('quantity');
		$min_stock = $this->input->post('min_stock');
		$max_stock = $this->input->post('max_stock');
        // movement_date is optional for add, model defaults to current date if not provided
        $movement_date = $this->input->post('movement_date'); 

		if ($product_id && $location_id && is_numeric($quantity)) {
			try {
				// Check if stock record already exists for this product and location
                // Model method name is get_stock_by_product_location
				$existing = $this->main_model->get_stock_by_product_location($product_id, $location_id);
				
				if ($existing) {
					$result = array('status' => 'error', 'message' => 'Stock record already exists for this product and location');
				} else {
                    $data_input = array(
                        'product_id' => $product_id,
                        'location_id' => $location_id,
                        'quantity' => $quantity,
                        'min_stock_level' => $min_stock ?: 0, // Default to 0 if empty
                        'max_stock_level' => $max_stock ?: 0, // Default to 0 if empty
                    );
                    if ($movement_date) {
                        $data_input['movement_date'] = $movement_date;
                    }

					$add_result = $this->main_model->add($data_input, $this->uid);
					
					if ($add_result && $add_result['status'] == 'success') { // Check status from model
						$result = array('status' => 'success', 'message' => 'Stock record added successfully', 'stock_id' => $add_result['stock_id']);
					} else {
						$result = array('status' => 'error', 'message' => isset($add_result['message']) ? $add_result['message'] : 'Failed to add stock record');
					}
				}
			} catch (Exception $ex) {
				$result = array('status' => 'error', 'message' => $ex->getMessage());
			}
		} else {
			$result = array('status' => 'error', 'message' => 'Product ID, Location ID, and Quantity must be filled');
		}
		
		echo json_encode($result);
	}

	function update()
	{
		$result = array('status' => 'error', 'message' => 'Invalid request');
		
		$id = $this->input->post('id');
		$product_id = $this->input->post('product_id');
		$location_id = $this->input->post('location_id');
		$quantity = $this->input->post('quantity');
		$min_stock = $this->input->post('min_stock');
		$max_stock = $this->input->post('max_stock');
        // movement_date is optional for update, model defaults to current date if not provided for quantity changes
        $movement_date = $this->input->post('movement_date');

		if ($id && $product_id && $location_id && is_numeric($quantity)) {
			try {
                $data_input = array(
                    'product_id' => $product_id,
                    'location_id' => $location_id,
                    'quantity' => $quantity,
                    'min_stock_level' => $min_stock ?: 0, // Default to 0 if empty
                    'max_stock_level' => $max_stock ?: 0, // Default to 0 if empty
                );
                if ($movement_date) {
                    $data_input['movement_date'] = $movement_date;
                }

				$update_result = $this->main_model->update($id, $data_input, $this->uid);
				
				if ($update_result && $update_result['status'] == 'success') { // Check status from model
					$result = array('status' => 'success', 'message' => 'Stock record updated successfully');
                } else if ($update_result && $update_result['status'] == 'info') { // Handle no changes case
                    $result = array('status' => 'info', 'message' => isset($update_result['message']) ? $update_result['message'] : 'No changes were made.');
				} else {
					$result = array('status' => 'error', 'message' => isset($update_result['message']) ? $update_result['message'] : 'Failed to update stock record');
				}
			} catch (Exception $ex) {
				$result = array('status' => 'error', 'message' => $ex->getMessage());
			}
		} else {
			$result = array('status' => 'error', 'message' => 'ID, Product ID, Location ID, and Quantity must be filled');
		}
		
		echo json_encode($result);
	}

	function delete()
	{
		$result = array('status' => 'error', 'message' => 'Invalid request');
		
		$id = $this->input->post('id');

		if ($id) {
			try {
				$delete_result = $this->main_model->delete($id, $this->uid);
				
				if ($delete_result) {
					$result = array('status' => 'success', 'message' => 'Stock record deleted successfully');
				} else {
					$result = array('status' => 'error', 'message' => 'Failed to delete stock record');
				}
			} catch (Exception $ex) {
				$result = array('status' => 'error', 'message' => $ex->getMessage());
			}
		}
		
		echo json_encode($result);
	}

	function activate()
	{
		$result = array('status' => 'error', 'message' => 'Invalid request');
		
		$id = $this->input->post('id');

		if ($id) {
			try {
				$activate_result = $this->main_model->activate($id, $this->uid);
				
				if ($activate_result) {
					$result = array('status' => 'success', 'message' => 'Stock record activated successfully');
				} else {
					$result = array('status' => 'error', 'message' => 'Failed to activate stock record');
				}
			} catch (Exception $ex) {
				$result = array('status' => 'error', 'message' => $ex->getMessage());
			}
		}
		
		echo json_encode($result);
	}

	function advance_search()
	{
		$result = array('status' => 'error', 'message' => 'Invalid request');
		
		$product_id = $this->input->post('product_id');
		$location_id = $this->input->post('location_id');
		$status_id = $this->input->post('status_id');
		$low_stock = $this->input->post('low_stock');

		$search_data = array(
			'product_id' => $product_id,
			'location_id' => $location_id,
			'status_id' => $status_id,
			'low_stock' => $low_stock
		);

		try {
			$data = $this->main_model->advance_search($search_data);
			$result = array('status' => 'success', 'data' => $data);
		} catch (Exception $ex) {
			$result = array('status' => 'error', 'message' => $ex->getMessage());
		}
		
		echo json_encode($result);
	}

	function adjust_stock()
	{
		$result = array('status' => 'error', 'message' => 'Invalid request');
		
		$id = $this->input->post('id');
		$adjustment_type = $this->input->post('adjustment_type'); // 'add' or 'subtract'
		$adjustment_quantity = $this->input->post('adjustment_quantity');
		$reason = $this->input->post('reason');
		$movement_date = $this->input->post('movement_date'); // Added movement_date

		if ($id && $adjustment_type && is_numeric($adjustment_quantity) && $adjustment_quantity > 0 && $reason && $movement_date) { // Added movement_date validation
			try {
                // Pass $this->uid to the model method
				$adjust_result = $this->main_model->adjust_stock($id, $adjustment_type, $adjustment_quantity, $reason, $movement_date, $this->uid);
				
				if ($adjust_result && $adjust_result['status'] == 'success') { // Check status from model response
					$result = array('status' => 'success', 'message' => 'Stock adjusted successfully');
				} else {
					$result = array('status' => 'error', 'message' => isset($adjust_result['message']) ? $adjust_result['message'] : 'Failed to adjust stock');
				}
			} catch (Exception $ex) {
				$result = array('status' => 'error', 'message' => $ex->getMessage());
			}
		} else {
			$result = array('status' => 'error', 'message' => 'All fields are required, quantity must be positive, and movement date must be set');
		}
		
		echo json_encode($result);
	}

	// New function to handle fetching stock movements
	function get_stock_movements_ajax()
	{
		$stock_id = $this->input->post('stock_id');
		$result = array('status' => 'error', 'message' => 'Invalid request');

		if ($stock_id) {
			try {
				$movements = $this->main_model->get_stock_movements($stock_id);
				$result = array('status' => 'success', 'data' => $movements);
			} catch (Exception $ex) {
				$result = array('status' => 'error', 'message' => $ex->getMessage());
			}
		}
		echo json_encode($result);
	}

	function receive_stock_ajax() {
		$result = array('status' => 'error', 'message' => 'Invalid request');

		$product_id = $this->input->post('product_id');
		$location_id = $this->input->post('location_id');
		$quantity = $this->input->post('quantity');
		$movement_date = $this->input->post('movement_date');
		$notes = $this->input->post('notes'); // Optional notes

		if ($product_id && $location_id && is_numeric($quantity) && $quantity > 0 && $movement_date) {
			try {
				// Corrected parameter order: $this->uid before $notes
				$receive_result = $this->main_model->receive_stock($product_id, $location_id, $quantity, $movement_date, $this->uid, $notes);
				if ($receive_result && $receive_result['status'] == 'success') {
					$result = array('status' => 'success', 'message' => 'Stock received successfully.');
				} else {
					$result = array('status' => 'error', 'message' => isset($receive_result['message']) ? $receive_result['message'] : 'Failed to receive stock.');
				}
			} catch (Exception $ex) {
				$result = array('status' => 'error', 'message' => $ex->getMessage());
			}
		} else {
			$result = array('status' => 'error', 'message' => 'Product, location, quantity, and movement date are required. Quantity must be positive.');
		}
		echo json_encode($result);
	}

	function release_stock_ajax() {
		$result = array('status' => 'error', 'message' => 'Invalid request');

		$stock_id = $this->input->post('stock_id');
		$quantity = $this->input->post('quantity');
		$movement_date = $this->input->post('movement_date');
		$notes = $this->input->post('notes'); // Optional notes

		if ($stock_id && is_numeric($quantity) && $quantity > 0 && $movement_date) {
			try {
				// Corrected parameter order: $this->uid before $notes
				$release_result = $this->main_model->release_stock($stock_id, $quantity, $movement_date, $this->uid, $notes);
				if ($release_result && $release_result['status'] == 'success') {
					$result = array('status' => 'success', 'message' => 'Stock released successfully.');
				} else {
					$result = array('status' => 'error', 'message' => isset($release_result['message']) ? $release_result['message'] : 'Failed to release stock.');
				}
			} catch (Exception $ex) {
				$result = array('status' => 'error', 'message' => $ex->getMessage());
			}
		} else {
			$result = array('status' => 'error', 'message' => 'Stock ID, quantity, and movement date are required. Quantity must be positive.');
		}
		echo json_encode($result);
	}

	function stock_transfer_ajax() {
		$result = array('status' => 'error', 'message' => 'Invalid request');

		$stock_id = $this->input->post('stock_id');
		$quantity = $this->input->post('quantity');
		$to_location_id = $this->input->post('to_location_id');
		$movement_date = $this->input->post('movement_date');
		$notes = $this->input->post('notes'); // Optional notes

		if ($stock_id && is_numeric($quantity) && $quantity > 0 && $to_location_id && $movement_date) {
			try {
				// Corrected parameter order: $this->uid before $notes
				$transfer_result = $this->main_model->transfer_stock($stock_id, $quantity, $to_location_id, $movement_date, $this->uid, $notes);
				if ($transfer_result && $transfer_result['status'] == 'success') {
					$result = array('status' => 'success', 'message' => 'Stock transferred successfully.');
				} else {
					$result = array('status' => 'error', 'message' => isset($transfer_result['message']) ? $transfer_result['message'] : 'Failed to transfer stock.');
				}
			} catch (Exception $ex) {
				$result = array('status' => 'error', 'message' => $ex->getMessage());
			}
		} else {
			$result = array('status' => 'error', 'message' => 'Stock ID, quantity, target location, and movement date are required. Quantity must be positive.');
		}
		echo json_encode($result);
	}
}
