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
			
			// Get currency information from database using currency_id
			$this->load->model('batch_transaction_model', 'batch_model');
			$currency_info = $this->batch_model->get_currency_info();
			$currency_code = $currency_info->code ?? 'USD';
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
				// Create basic table structure for empty data
				$html .= '<thead><tr>';
				$html .= '<th>#</th>';
				$html .= '<th>No Data</th>';
				$html .= '</tr></thead>';
				$html .= '<tbody>';
				// Don't add any rows - let DataTable handle the empty table
				$html .= '</tbody>';
			}
			
			$html .= '</table>';
			$html .= '</div>';
			
			echo json_encode(array('success' => true, 'html' => $html, 'title' => $title, 'record_count' => count($data)));
			
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
				$location = $this->data_location_model->search_by_row($location_id);
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

			// Get currency information from database using currency_id
			$this->load->model('batch_transaction_model', 'batch_model');
			$currency_info = $this->batch_model->get_currency_info();
			$currency_code = $currency_info->code ?? 'USD';
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
		// Check permission
		if (!$this->cf->module_permission("view", $this->module_permission)) {
			show_error('Access Denied', 403);
			return;
		}

		$report_type = trim($this->input->post('report_type'));
		$format = trim($this->input->post('format'));
		$location_id = intval($this->input->post('location_id'));
		$date_from = trim($this->input->post('date_from'));
		$date_to = trim($this->input->post('date_to'));
		
		// Validate inputs
		if (!$report_type || !$format) {
			show_error('Missing required parameters', 400);
			return;
		}
		
		if ($format === 'excel') {
			$this->export_excel($report_type, $location_id, $date_from, $date_to);
		} else {
			// Redirect to PDF export
			$params = array(
				'report_type' => $report_type,
				'location_id' => $location_id,
				'date_from' => $date_from,
				'date_to' => $date_to
			);
			
			$query_string = http_build_query($params);
			redirect("inventory_reports/export_pdf?{$query_string}");
		}
	}
	
	private function export_excel($report_type, $location_id, $date_from, $date_to)
	{
		try {
			// Load PhpSpreadsheet via Composer autoloader
			require_once FCPATH . 'vendor/autoload.php';
			
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
				$location = $this->data_location_model->search_by_row($location_id);
				$location_name = $location ? $location->location : 'Unknown Location';
			}

			// Create new PhpSpreadsheet object
			$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$worksheet = $spreadsheet->getActiveSheet();
			$worksheet->setTitle($report_title);

			// Set report header information
			$row = 1;
			$worksheet->setCellValue('A' . $row, $report_title);
			$worksheet->mergeCells('A' . $row . ':E' . $row);
			$worksheet->getStyle('A' . $row)->getFont()->setSize(16)->setBold(true);
			$row++;

			// Add filter information
			$worksheet->setCellValue('A' . $row, 'Generated: ' . date('Y-m-d H:i:s'));
			$row++;
			$worksheet->setCellValue('A' . $row, 'Location: ' . $location_name);
			$row++;
			if ($date_from) {
				$worksheet->setCellValue('A' . $row, 'Date From: ' . $date_from);
				$row++;
			}
			if ($date_to) {
				$worksheet->setCellValue('A' . $row, 'Date To: ' . $date_to);
				$row++;
			}
			$row++; // Empty row

			// Generate Excel content
			if (!empty($report_data)) {
				// Get headers from first row
				$first_row = (array) $report_data[0];
				$headers = array();
				$col = 'A';
				
				// Row number header
				$worksheet->setCellValue($col . $row, '#');
				$worksheet->getStyle($col . $row)->getFont()->setBold(true);
				$col++;
				
				foreach ($first_row as $key => $value) {
					if (in_array($key, ['id', 'product_id', 'location_id', 'created_by', 'updated_by', 'status_id'])) {
						continue; // Skip internal fields
					}
					$header = ucwords(str_replace('_', ' ', $key));
					$worksheet->setCellValue($col . $row, $header);
					$worksheet->getStyle($col . $row)->getFont()->setBold(true);
					$headers[] = $key;
					$col++;
				}
				$row++;

				// Data rows
				$record_num = 1;
				foreach ($report_data as $data_row) {
					$col = 'A';
					
					// Row number
					$worksheet->setCellValue($col . $row, $record_num);
					$col++;
					
					foreach ($headers as $header) {
						$value = isset($data_row->$header) ? $data_row->$header : '';
						
						// Format values
						if (strpos($header, 'date') !== false && $value) {
							$value = date('Y-m-d', strtotime($value));
						} elseif (strpos($header, 'qty') !== false || strpos($header, 'cost') !== false || strpos($header, 'value') !== false) {
							$value = (float)$value;
						}
						
						$worksheet->setCellValue($col . $row, $value);
						$col++;
					}
					$row++;
					$record_num++;
				}
				
				// Auto-size columns
				$lastCol = chr(65 + count($headers)); // Convert to letter
				for ($c = 'A'; $c <= $lastCol; $c++) {
					$worksheet->getColumnDimension($c)->setAutoSize(true);
				}
			} else {
				$worksheet->setCellValue('A' . $row, 'No data found for this report');
			}

			// Set filename and headers for download
			$filename = str_replace(' ', '_', $report_title) . '_' . date('Y-m-d_H-i-s') . '.xlsx';
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $filename . '"');
			header('Cache-Control: max-age=0');
			
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
			$writer->save('php://output');
			
		} catch (Exception $ex) {
			show_error('Error generating Excel file: ' . $ex->getMessage());
		}
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
			'COP' => '$', 'ARS' => '$', 'UYU' => '$', 'TWD' => 'NT$',
			'PGK' => 'K'  // Papua New Guinea Kina - Added for consistency
		);
		
		return $symbols[$currency_code] ?? $currency_code;
	}
}
