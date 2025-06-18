<?php
defined('BASEPATH') or die("No direct script allowed!");

class Inventory_analytics extends CI_Controller
{
	protected $module_permission = array();
	protected $prefix; 
	protected $default_error_msg = "Error: Critical Error Encountered!";
	protected $role_id;
	protected $module = "inventory_analytics";
	protected $module_description = "Inventory Analytics";
	protected $page_name = "Analytics Dashboard";
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
					$this->load->model('stock_model');
					$this->load->model('stock_movements_model');
					$this->load->model('data_product_model');
					$this->load->model('data_location_model');
					$this->load->model('batch_transaction_model', 'batch_model');
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
			
			// Get currency information from database using currency_id
			$currency_info = $this->batch_model->get_currency_info();
			$currency_code = $currency_info->code ?? 'USD';
			$data['currency_code'] = $currency_code;
			$data['currency_symbol'] = $this->get_currency_symbol($currency_code);
			
			$this->load->view('inventory_analytics/index', $data);
		} else {
			$this->load->view('errors/html/error_403');
		}
	}

	// Analytics API Methods
	
	/**
	 * Get dashboard summary statistics
	 */
	function get_dashboard_stats()
	{
		// Check permission
		if (!$this->cf->module_permission("view", $this->module_permission)) {
			echo json_encode(array('success' => false, 'message' => 'Access Denied'));
			return;
		}

		try {
			$location_id = intval($this->input->get('location_id') ?? 0);
			
			// Get various statistics
			$stats = array();
			
			// Total products
			$products = $this->data_product_model->search('', '', 0, 1);
			$stats['total_products'] = count($products);
			
			// Total stock value
			$stock_valuation = $this->stock_model->get_stock_valuation($location_id);
			$total_value = 0;
			foreach ($stock_valuation as $item) {
				$total_value += floatval($item->total_value ?? 0);
			}
			$stats['total_stock_value'] = $total_value;
			
			// Low stock count
			$low_stock = $this->stock_model->get_low_stock($location_id);
			$stats['low_stock_count'] = count($low_stock);
			
			// Expired stock count
			$expired_stock = $this->stock_model->get_expired_stock($location_id);
			$stats['expired_stock_count'] = count($expired_stock);
			
			// Recent movements count (last 7 days)
			$date_from = date('Y-m-d', strtotime('-7 days'));
			$date_to = date('Y-m-d');
			$recent_movements = $this->stock_movements_model->get_movement_summary($location_id, '', $date_from, $date_to);
			$stats['recent_movements_count'] = count($recent_movements);
			
			echo json_encode(array('success' => true, 'data' => $stats));
			
		} catch (Exception $ex) {
			echo json_encode(array('success' => false, 'message' => $ex->getMessage()));
		}
	}

	/**
	 * Get stock movement trends for charts
	 */
	function get_movement_trends()
	{
		// Check permission
		if (!$this->cf->module_permission("view", $this->module_permission)) {
			echo json_encode(array('success' => false, 'message' => 'Access Denied'));
			return;
		}

		try {
			$location_id = intval($this->input->get('location_id') ?? 0);
			$period = $this->input->get('period') ?? '30'; // days
			
			$date_from = date('Y-m-d', strtotime("-{$period} days"));
			$date_to = date('Y-m-d');
			
			// Get movement data
			$movements = $this->stock_movements_model->get_movement_trends($location_id, $date_from, $date_to);
			
			echo json_encode(array('success' => true, 'data' => $movements));
			
		} catch (Exception $ex) {
			echo json_encode(array('success' => false, 'message' => $ex->getMessage()));
		}
	}

	/**
	 * Get top products by movement
	 */
	function get_top_products()
	{
		// Check permission
		if (!$this->cf->module_permission("view", $this->module_permission)) {
			echo json_encode(array('success' => false, 'message' => 'Access Denied'));
			return;
		}

		try {
			$location_id = intval($this->input->get('location_id') ?? 0);
			$movement_type = $this->input->get('movement_type') ?? '';
			$period = intval($this->input->get('period') ?? 30);
			$limit = intval($this->input->get('limit') ?? 10);
			
			$date_from = date('Y-m-d', strtotime("-{$period} days"));
			$date_to = date('Y-m-d');
			
			$top_products = $this->stock_movements_model->get_top_products($location_id, $movement_type, $date_from, $date_to, $limit);
			
			echo json_encode(array('success' => true, 'data' => $top_products));
			
		} catch (Exception $ex) {
			echo json_encode(array('success' => false, 'message' => $ex->getMessage()));
		}
	}

	/**
	 * Get ABC analysis data
	 */
	function get_abc_analysis()
	{
		// Check permission
		if (!$this->cf->module_permission("view", $this->module_permission)) {
			echo json_encode(array('success' => false, 'message' => 'Access Denied'));
			return;
		}

		try {
			$location_id = intval($this->input->get('location_id') ?? 0);
			
			$abc_data = $this->stock_model->get_abc_analysis($location_id);
			
			echo json_encode(array('success' => true, 'data' => $abc_data));
			
		} catch (Exception $ex) {
			echo json_encode(array('success' => false, 'message' => $ex->getMessage()));
		}
	}

	/**
	 * Get stock alerts (low stock, expired, expiring)
	 */
	function get_stock_alerts()
	{
		// Check permission
		if (!$this->cf->module_permission("view", $this->module_permission)) {
			echo json_encode(array('success' => false, 'message' => 'Access Denied'));
			return;
		}

		try {
			$location_id = intval($this->input->get('location_id') ?? 0);
			
			$alerts = array();
			
			// Low stock alerts
			$low_stock = $this->stock_model->get_low_stock($location_id);
			foreach ($low_stock as $item) {
				$alerts[] = array(
					'type' => 'low_stock',
					'severity' => 'warning',
					'message' => "Low stock: {$item->product_name} ({$item->qty_on_hand} units remaining)",
					'product_id' => $item->product_id,
					'location_id' => $item->location_id,
					'details' => $item
				);
			}
			
			// Expired stock alerts
			$expired_stock = $this->stock_model->get_expired_stock($location_id);
			foreach ($expired_stock as $item) {
				$alerts[] = array(
					'type' => 'expired',
					'severity' => 'danger',
					'message' => "Expired stock: {$item->product_name} (expired {$item->expiration_date})",
					'product_id' => $item->product_id,
					'location_id' => $item->location_id,
					'details' => $item
				);
			}
			
			// Expiring stock alerts (next 30 days)
			$expiring_stock = $this->stock_model->get_expiring_stock($location_id, 30);
			foreach ($expiring_stock as $item) {
				$alerts[] = array(
					'type' => 'expiring',
					'severity' => 'info',
					'message' => "Stock expiring soon: {$item->product_name} (expires {$item->expiration_date})",
					'product_id' => $item->product_id,
					'location_id' => $item->location_id,
					'details' => $item
				);
			}
			
			// Sort alerts by severity (danger, warning, info)
			usort($alerts, function($a, $b) {
				$severity_order = array('danger' => 1, 'warning' => 2, 'info' => 3);
				return $severity_order[$a['severity']] - $severity_order[$b['severity']];
			});
			
			echo json_encode(array('success' => true, 'data' => $alerts));
			
		} catch (Exception $ex) {
			echo json_encode(array('success' => false, 'message' => $ex->getMessage()));
		}
	}

	/**
	 * Helper function to get currency symbol
	 */
	private function get_currency_symbol($currency_code)
	{
		$symbols = array(
			'USD' => '$',
			'EUR' => '€',
			'GBP' => '£',
			'JPY' => '¥',
			'AUD' => 'A$',
			'CAD' => 'C$',
			'CHF' => 'CHF',
			'CNY' => '¥',
			'SEK' => 'kr',
			'NZD' => 'NZ$',
			'PGK' => 'K'  // Papua New Guinea Kina
		);
		
		return isset($symbols[$currency_code]) ? $symbols[$currency_code] : $currency_code;
	}
}
