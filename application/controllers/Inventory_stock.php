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
			
			// Get currency information from system settings
			$app_details = $this->ad->get_details();
			$currency_code = $app_details->currency_code ?? 'USD';
			$data['currency_code'] = $currency_code;
			$data['currency_symbol'] = $this->get_currency_symbol($currency_code);
			
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

		if ($this->cf->module_permission("add", $this->module_permission)) {
			$product_id = intval($input_data['product_id']);
			$location_id = intval($input_data['location_id']);
			$qty = intval($input_data['qty']);
			$unit_cost = floatval($input_data['unit_cost']);
			$expiration_date = !empty($input_data['expiration_date']) ? $input_data['expiration_date'] : null;
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
						$notes,
						$expiration_date
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

		if ($this->cf->module_permission("add", $this->module_permission)) {
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

		if ($this->cf->module_permission("add", $this->module_permission)) {
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

		if ($this->cf->module_permission("add", $this->module_permission)) {
			$product_id = intval($input_data['product_id']);
			$location_id = intval($input_data['location_id']);
			$adjust_type = $input_data['adjust_type'];
			$adjust_reason = $input_data['adjust_reason'];
			$notes = $input_data['notes'] ?? '';

			if ($product_id && $location_id && $adjust_type && $adjust_reason) {
				try {
					// Get current stock
					$current_stock_data = $this->main_model->search_by_product_location($product_id, $location_id);
					$current_qty = $current_stock_data ? floatval($current_stock_data->qty_on_hand) : 0;

					$adjustment_qty = 0;
					$final_qty = 0;

					// Calculate adjustment based on type
					if ($adjust_type === 'ADD') {
						$adjustment_qty = floatval($input_data['adjust_qty']);
						if ($adjustment_qty <= 0) {
							echo "Error: Please enter a valid positive quantity to add!";
							return;
						}
						$final_qty = $current_qty + $adjustment_qty;
					} elseif ($adjust_type === 'SUBTRACT') {
						$adjustment_qty = -floatval($input_data['adjust_qty']);
						if (abs($adjustment_qty) <= 0) {
							echo "Error: Please enter a valid positive quantity to subtract!";
							return;
						}
						if (abs($adjustment_qty) > $current_qty) {
							echo "Error: Cannot subtract more than current stock (" . $current_qty . ")!";
							return;
						}
						$final_qty = $current_qty + $adjustment_qty; // adjustment_qty is negative
					} elseif ($adjust_type === 'SET') {
						$final_qty = floatval($input_data['new_qty']);
						if ($final_qty < 0) {
							echo "Error: New quantity cannot be negative!";
							return;
						}
						$adjustment_qty = $final_qty - $current_qty;
					} else {
						echo "Error: Invalid adjustment type!";
						return;
					}

					// Create movement record
					$movement_data = array(
						'date' => date('Y-m-d'),
						'product_id' => $product_id,
						'location_id' => $location_id,
						'movement_type' => 'ADJUSTMENT',
						'qty' => abs($adjustment_qty),
						'reference_type' => $adjust_reason,
						'reference_id' => null,
						'unit_cost' => 0, // Adjustments don't change cost
						'notes' => $notes . ' (Type: ' . $adjust_type . ', Reason: ' . $adjust_reason . ', Previous: ' . $current_qty . ', Final: ' . $final_qty . ')',
						'created_by' => $this->uid
					);

					// Save the movement using add_movement method
					$result = $this->stock_movements_model->add_movement($movement_data);

					if ($result) {
						// Update stock levels directly using stock model
						$this->load->model('stock_model');
						
						// Calculate the net change
						$net_change = $final_qty - $current_qty;
						$operation = $net_change >= 0 ? 'add' : 'subtract';
						$this->stock_model->update_stock($product_id, $location_id, abs($net_change), $operation);
						
						$action_desc = '';
						if ($adjust_type === 'ADD') {
							$action_desc = "Added " . abs($adjustment_qty) . " units";
						} elseif ($adjust_type === 'SUBTRACT') {
							$action_desc = "Subtracted " . abs($adjustment_qty) . " units";
						} else {
							$action_desc = "Set quantity to " . $final_qty . " units";
						}
						
						echo "Success: Stock adjustment completed! " . $action_desc . " (Previous: " . $current_qty . ", Current: " . $final_qty . ")";
					} else {
						echo "Error: Failed to create stock adjustment record!";
					}
				} catch (Exception $ex) {
					echo "Error: " . $ex->getMessage();
				}
			} else {
				echo "Error: Please provide all required fields (product, location, adjustment type, reason)!";
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

	function get_expiring_stock()
	{
		$location_id = intval($this->input->get('location_id'));
		$days_ahead = intval($this->input->get('days_ahead')) ?: 30;
		
		try {
			$result = $this->main_model->get_expiring_products($location_id, $days_ahead);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	function get_expired_stock()
	{
		$location_id = intval($this->input->get('location_id'));
		
		try {
			$result = $this->main_model->get_expired_products($location_id);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	function get_stock_valuation()
	{
		$location_id = intval($this->input->get('location_id'));
		
		try {
			$result = $this->main_model->get_stock_valuation($location_id);
			$total_value = 0;
			foreach ($result as $item) {
				$total_value += $item->stock_value;
			}
			
			echo json_encode(array(
				'items' => $result,
				'total_value' => $total_value
			));
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	function export_expiring_stock()
	{
		if ($this->cf->module_permission("view", $this->module_permission)) {
			$location_id = intval($this->input->get('location_id'));
			$days_ahead = intval($this->input->get('days_ahead')) ?: 30;
			
			try {
				$data = $this->main_model->get_expiring_products($location_id, $days_ahead);
				
				$this->load->library('excel');
				$this->excel->setActiveSheetIndex(0);
				$this->excel->getActiveSheet()->setTitle('Expiring Stock Report');
				
				// Headers
				$headers = array('Product Code', 'Product Name', 'Category', 'Location', 'Qty On Hand', 'Unit Cost', 'Total Value', 'Expiration Date', 'Days Until Expiry', 'Status');
				$col = 'A';
				foreach ($headers as $header) {
					$this->excel->getActiveSheet()->setCellValue($col . '1', $header);
					$col++;
				}
				
				// Data
				$row = 2;
				foreach ($data as $item) {
					$status = '';
					if ($item->days_until_expiry < 0) {
						$status = 'EXPIRED';
					} elseif ($item->days_until_expiry <= 7) {
						$status = 'CRITICAL';
					} elseif ($item->days_until_expiry <= 30) {
						$status = 'WARNING';
					}
					
					$this->excel->getActiveSheet()->setCellValue('A' . $row, $item->product_code);
					$this->excel->getActiveSheet()->setCellValue('B' . $row, $item->product_name);
					$this->excel->getActiveSheet()->setCellValue('C' . $row, $item->category);
					$this->excel->getActiveSheet()->setCellValue('D' . $row, $item->location);
					$this->excel->getActiveSheet()->setCellValue('E' . $row, $item->qty_on_hand);
					$this->excel->getActiveSheet()->setCellValue('F' . $row, $item->unit_cost);
					$this->excel->getActiveSheet()->setCellValue('G' . $row, $item->qty_on_hand * $item->unit_cost);
					$this->excel->getActiveSheet()->setCellValue('H' . $row, $item->expiration_date_formatted);
					$this->excel->getActiveSheet()->setCellValue('I' . $row, $item->days_until_expiry);
					$this->excel->getActiveSheet()->setCellValue('J' . $row, $status);
					$row++;
				}
				
				$filename = 'Expiring_Stock_Report_' . date('Y-m-d_H-i-s') . '.xlsx';
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="' . $filename . '"');
				header('Cache-Control: max-age=0');
				
				$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
				$objWriter->save('php://output');
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo "Error: You don't have permission to perform this action!";
		}
	}
	
	/**
	 * Export stock levels to PDF
	 */
	public function export_pdf()
	{
		if ($this->cf->module_permission("view", $this->module_permission)) {
			// Get filters from request
			$search = $this->input->get('search', TRUE);
			$location_id = $this->input->get('location_id', TRUE);
			
			// Get currency info from app details (already loaded in constructor)
			$app_details = $this->ad->get_details();
			$currency_code = $app_details->currency_code ?? 'USD';
			$currency_symbol = $this->get_currency_symbol($currency_code);
			
			// Get location name for filters
			$location_name = 'All Locations';
			if ($location_id) {
				$location = $this->data_location_model->get_by_id($location_id);
				if ($location) {
					$location_name = $location->location;
				}
			}
			
			// Get stock data using the same method as the search
			$stock_data = $this->main_model->search($search, $location_id, 1);
			
			// Prepare data for PDF
			$filters = array(
				'search_text' => $search,
				'location_name' => $location_name
			);
			
			// Load PDF library
			$this->load->library('Pdf');
			
			// Set data for PDF view
			$data = array(
				'stock_data' => $stock_data,
				'filters' => $filters,
				'currency_symbol' => $currency_symbol,
				'company_name' => $this->company_name,
				'company_address' => $this->company_address,
				'company_contact' => $this->company_contact,
				'page_name' => $this->page_name
			);
			
			// Extract variables for the PDF view
			extract($data);
			
			// Load PDF view
			$this->load->view('pdf/inventory_stock', $data);
		} else {
			echo "Error: You don't have permission to perform this action!";
		}
	}
	
	/**
	 * Get currency symbol based on currency code
	 */
	private function get_currency_symbol($currency_code)
	{
		$currency_symbols = array(
			'USD' => '$',
			'EUR' => '€',
			'GBP' => '£',
			'JPY' => '¥',
			'PHP' => '₱',
			'PGK' => 'K',  // Papua New Guinea Kina
			'AUD' => 'A$',
			'CAD' => 'C$',
			'SGD' => 'S$',
			'MYR' => 'RM',
			'THB' => '฿',
			'IDR' => 'Rp',
			'VND' => '₫',
			'KRW' => '₩',
			'CNY' => '¥',
			'INR' => '₹',
			'CHF' => 'CHF',
			'NZD' => 'NZ$',
			'ZAR' => 'R',
			'BRL' => 'R$'
		);
		
		return $currency_symbols[$currency_code] ?? $currency_code;
	}
}
