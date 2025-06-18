<?php
defined('BASEPATH') or die("No direct script allowed!");

class Inventory_reports extends CI_Controller
{
	protected $module_permission = array();
	protected $prefix; 
	protected $default_error_msg = "Error: Critical Error Encountered!";
	protected $role_id;
	protected $module = "inventory_reports";
	protected $module_description = "Inventory Reports";
	protected $page_name = "Inventory Reports";
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
					$this->module_permission = $this->a->allow_permission($this->role_id, $this->module);					$this->load->library('custom_function', NULL, 'cf');

					//load needed models for this page
					$this->load->model('data_location_model');
					$this->load->model('data_product_model');
					$this->load->model('stock_model');
					$this->load->model('stock_movements_model');
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
			
			// Get currency information from system settings
			$app_details = $this->ad->get_details();
			$currency_code = $app_details->currency_code ?? 'USD';
			$data['currency_code'] = $currency_code;
			$data['currency_symbol'] = $this->get_currency_symbol($currency_code);
			
			$this->load->view('inventory_reports/index', $data);
		} else {
			$this->load->view('errors/html/error_403');
		}
	}

	// Report Generation Methods
	
	function low_stock_report()
	{
		$location_id = intval($this->input->get('location_id'));
		
		try {
			$result = $this->stock_model->get_low_stock($location_id);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}

	function stock_valuation_report()
	{
		$location_id = intval($this->input->get('location_id'));
		
		try {
			$result = $this->stock_model->get_stock_valuation($location_id);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}

	function expiring_stock_report()
	{
		$location_id = intval($this->input->get('location_id'));
		$days_ahead = intval($this->input->get('days_ahead'));
		
		if ($days_ahead <= 0) $days_ahead = 30;
		
		try {
			$result = $this->stock_model->get_expiring_stock($location_id, $days_ahead);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}

	function expired_stock_report()
	{
		$location_id = intval($this->input->get('location_id'));
		
		try {
			$result = $this->stock_model->get_expired_stock($location_id);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}

	function zero_stock_report()
	{
		$location_id = intval($this->input->get('location_id'));
		
		try {
			$result = $this->stock_model->get_zero_stock($location_id);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}

	function negative_stock_report()
	{
		$location_id = intval($this->input->get('location_id'));
		
		try {
			$result = $this->stock_model->get_negative_stock($location_id);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}

	function movement_summary_report()
	{
		$location_id = intval($this->input->get('location_id'));
		$movement_type = trim($this->input->get('movement_type'));
		$date_from = trim($this->input->get('date_from'));
		$date_to = trim($this->input->get('date_to'));
		
		try {
			$result = $this->stock_movements_model->get_movement_summary($location_id, $movement_type, $date_from, $date_to);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}

	function top_products_report()
	{
		$location_id = intval($this->input->get('location_id'));
		$movement_type = trim($this->input->get('movement_type'));
		$date_from = trim($this->input->get('date_from'));
		$date_to = trim($this->input->get('date_to'));
		$limit = intval($this->input->get('limit'));
		
		if ($limit <= 0) $limit = 10;
		
		try {
			$result = $this->stock_movements_model->get_top_products($location_id, $movement_type, $date_from, $date_to, $limit);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}

	function abc_analysis_report()
	{
		$location_id = intval($this->input->get('location_id'));
		
		try {
			$result = $this->stock_model->get_abc_analysis($location_id);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}

	function turnover_analysis_report()
	{
		$location_id = intval($this->input->get('location_id'));
		$period_months = intval($this->input->get('period_months'));
		
		if ($period_months <= 0) $period_months = 12;
		
		try {
			$result = $this->stock_model->get_turnover_analysis($location_id, $period_months);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}

	// Generate Report for Modal Display
	function generate_report()
	{
		$report_type = trim($this->input->post('report_type'));
		$location_id = intval($this->input->post('location_id'));
		$date_from = trim($this->input->post('date_from'));
		$date_to = trim($this->input->post('date_to'));
		
		try {
			$data = array();
			$title = '';
			
			// Get data based on report type
			switch($report_type) {
				case 'low_stock':
					$data = $this->stock_model->get_low_stock($location_id);
					$title = 'Low Stock Report';
					break;
					
				case 'stock_valuation':
					$data = $this->stock_model->get_stock_valuation($location_id);
					$title = 'Stock Valuation Report';
					break;
					
				case 'expiring_stock':
					$data = $this->stock_model->get_expiring_stock($location_id, 30);
					$title = 'Expiring Stock Report (30 days)';
					break;
					
				case 'expired_stock':
					$data = $this->stock_model->get_expired_stock($location_id);
					$title = 'Expired Stock Report';
					break;
					
				case 'zero_stock':
					$data = $this->stock_model->get_zero_stock($location_id);
					$title = 'Zero Stock Report';
					break;
					
				case 'movement_summary':
					$data = $this->stock_movements_model->get_movement_summary($location_id, '', $date_from, $date_to);
					$title = 'Movement Summary Report';
					break;
					
				case 'abc_analysis':
					$data = $this->stock_model->get_abc_analysis($location_id);
					$title = 'ABC Analysis Report';
					break;
					
				case 'turnover_analysis':
					$data = $this->stock_model->get_turnover_analysis($location_id);
					$title = 'Turnover Analysis Report';
					break;
					
				default:
					echo json_encode(array('success' => false, 'message' => 'Invalid report type'));
					return;
			}
			
			// Generate HTML table
			$html = '<div class="table-responsive">';
			$html .= '<table class="table table-striped table-bordered">';
			
			if (!empty($data)) {
				// Get first row to build headers
				$first_row = (array) $data[0];
				$html .= '<thead><tr>';
				$html .= '<th>#</th>'; // Row number
				foreach ($first_row as $key => $value) {
					if (in_array($key, ['id', 'product_id', 'location_id', 'created_by', 'updated_by', 'status_id'])) {
						continue; // Skip internal fields
					}
					$html .= '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
				}
				$html .= '</tr></thead>';
				
				// Data rows
				$html .= '<tbody>';
				$row_num = 1;
				foreach ($data as $row) {
					$html .= '<tr>';
					$html .= '<td>' . $row_num . '</td>';
					foreach ((array) $row as $key => $value) {
						if (in_array($key, ['id', 'product_id', 'location_id', 'created_by', 'updated_by', 'status_id'])) {
							continue; // Skip internal fields
						}
						
						// Format values
						if (strpos($key, 'date') !== false && $value) {
							$value = date('Y-m-d', strtotime($value));
						} elseif (strpos($key, 'qty') !== false || strpos($key, 'cost') !== false || strpos($key, 'value') !== false) {
							$value = number_format((float)$value, 2);
						}
						
						$html .= '<td>' . htmlspecialchars($value ?: '-') . '</td>';
					}
					$html .= '</tr>';
					$row_num++;
				}
				$html .= '</tbody>';
			} else {
				$html .= '<tr><td colspan="100%" class="text-center">No data found for this report</td></tr>';
			}
			
			$html .= '</table>';
			$html .= '</div>';
			
			echo json_encode(array('success' => true, 'html' => $html, 'title' => $title));
			
		} catch (Exception $ex) {
			echo json_encode(array('success' => false, 'message' => $ex->getMessage()));
		}
	}

	// PDF Export Methods
	
	/**
	 * Export inventory reports to PDF
	 */
	public function export_pdf()
	{
		// Check permission
		if (!$this->cf->module_permission("view", $this->module_permission)) {
			show_error('Access Denied', 403);
			return;
		}

		try {
			// Get filter parameters from GET or POST
			$report_type = trim($this->input->get('report_type')) ?: trim($this->input->post('report_type'));
			$location_id = intval($this->input->get('location_id')) ?: intval($this->input->post('location_id'));
			$date_from = trim($this->input->get('date_from')) ?: trim($this->input->post('date_from'));
			$date_to = trim($this->input->get('date_to')) ?: trim($this->input->post('date_to'));

			// Validate report type
			if (!$report_type) {
				show_error('Report type is required', 400);
				return;
			}

			// Get data based on report type
			$report_data = array();
			$report_title = '';
			
			switch($report_type) {
				case 'low_stock':
					$report_data = $this->stock_model->get_low_stock($location_id);
					$report_title = 'Low Stock Report';
					break;
					
				case 'stock_valuation':
					$report_data = $this->stock_model->get_stock_valuation($location_id);
					$report_title = 'Stock Valuation Report';
					break;
					
				case 'expiring_stock':
					$report_data = $this->stock_model->get_expiring_stock($location_id, 30);
					$report_title = 'Expiring Stock Report (30 days)';
					break;
					
				case 'expired_stock':
					$report_data = $this->stock_model->get_expired_stock($location_id);
					$report_title = 'Expired Stock Report';
					break;
					
				case 'zero_stock':
					$report_data = $this->stock_model->get_zero_stock($location_id);
					$report_title = 'Zero Stock Report';
					break;
					
				case 'movement_summary':
					$report_data = $this->stock_movements_model->get_movement_summary($location_id, '', $date_from, $date_to);
					$report_title = 'Movement Summary Report';
					break;
					
				case 'abc_analysis':
					$report_data = $this->stock_model->get_abc_analysis($location_id);
					$report_title = 'ABC Analysis Report';
					break;
					
				case 'turnover_analysis':
					$report_data = $this->stock_model->get_turnover_analysis($location_id);
					$report_title = 'Turnover Analysis Report';
					break;
					
				default:
					show_error('Invalid report type: ' . $report_type, 400);
					return;
			}

			// Get location name for filter display
			$location_name = 'All Locations';
			if ($location_id) {
				$location = $this->data_location_model->search_by_id($location_id);
				$location_name = $location ? $location->location : 'Unknown Location';
			}

			// Prepare filter information for the PDF
			$filters = array(
				'location_name' => $location_name,
				'date_from' => $date_from,
				'date_to' => $date_to,
				'total_records' => count($report_data)
			);

			// Get company information
			$prefix = $this->prefix;
			$company_name = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_name"];
			$company_address = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_address"];
			$company_contact = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_contact"];
			$page_name = $this->page_name;

			// Get currency information
			$app_details = $this->ad->get_details();
			$currency_code = $app_details->currency_code ?? 'USD';
			$currency_symbol = $this->get_currency_symbol($currency_code);
		// Load PDF library
		$this->load->library('pdf');

		// Set data for the PDF view
		$data = array(
			'report_data' => $report_data,
			'report_title' => $report_title,
			'report_type' => $report_type,
			'filters' => $filters,
			'currency_symbol' => $currency_symbol,
			'company_name' => $company_name,
			'company_address' => $company_address,
			'company_contact' => $company_contact,
			'page_name' => $page_name,
			'app_name' => $this->app_name  // Add missing app_name variable
		);

		// Load the PDF view with data
		$this->load->view('pdf/inventory_reports', $data);

		} catch (Exception $ex) {
			show_error('Error generating PDF: ' . $ex->getMessage());
		}
	}
	
	function export_report()
	{
		$report_type = trim($this->input->post('report_type'));
		$format = trim($this->input->post('format'));
		$location_id = intval($this->input->post('location_id'));
		$date_from = trim($this->input->post('date_from'));
		$date_to = trim($this->input->post('date_to'));
		
		// For now, redirect to PDF export (Excel can be implemented later)
		if ($format === 'excel') {
			$format = 'pdf'; // Fallback to PDF for now
		}
		
		// Redirect to existing export_pdf method with parameters
		$params = array(
			'report_type' => $report_type,
			'location_id' => $location_id,
			'date_from' => $date_from,
			'date_to' => $date_to
		);
		
		$query_string = http_build_query($params);
		
		redirect("inventory_reports/export_pdf?{$query_string}");
	}
	
	// Utility Methods
	
	private function get_currency_symbol($currency_code)
	{
		$symbols = array(
			'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'JPY' => '¥',
			'CNY' => '¥', 'INR' => '₹', 'KRW' => '₩', 'RUB' => '₽',
			'BRL' => 'R$', 'CAD' => 'C$', 'AUD' => 'A$', 'ZAR' => 'R',
			'MXN' => 'Mex$', 'SGD' => 'S$', 'HKD' => 'HK$', 'NZD' => 'NZ$',
			'SEK' => 'kr', 'NOK' => 'kr', 'DKK' => 'kr', 'PLN' => 'zł',
			'CHF' => 'CHF', 'TRY' => '₺', 'THB' => '฿', 'MYR' => 'RM',
			'PHP' => '₱', 'IDR' => 'Rp', 'VND' => '₫', 'CZK' => 'Kč',
			'HUF' => 'Ft', 'ILS' => '₪', 'CLP' => '$', 'PEN' => 'S/',
			'COP' => '$', 'ARS' => '$', 'UYU' => '$', 'TWD' => 'NT$'
		);
		
		return $symbols[$currency_code] ?? $currency_code;
	}
}
