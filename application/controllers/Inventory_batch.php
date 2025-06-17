<?php
defined('BASEPATH') or die("No direct script allowed!");

class Inventory_batch extends CI_Controller
{
    protected $module_permission = array();
    protected $prefix; 
    protected $default_error_msg = "Error: Critical Error Encountered!";
    protected $role_id;
    protected $module = "inventory_batch";
    protected $module_description = "Batch Transactions";
    protected $page_name = "Batch Transaction Management";
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
                    $this->load->model('batch_transaction_model', 'main_model');
                    $this->load->model('data_product_model');
                    $this->load->model('data_location_model');
                }
            }
        } catch (Exception $ex) {
            show_error('Database Error: ' . $ex->getMessage());
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

            // Load dropdown data
            $data['locations'] = $this->data_location_model->search('', 0, 1);
            $data['products'] = $this->data_product_model->search('', '', 0, 1);
            
            // Get currency information from system settings
            $currency_info = $this->main_model->get_currency_info();
            $data['currency_code'] = $currency_info->code ?? 'USD';
            $data['currency_symbol'] = $this->get_currency_symbol($currency_info->code ?? 'USD');

            $this->load->view('inventory_batch/index', $data);
        } else {
            $this->session->set_userdata($this->prefix . '_to_page', current_url());
            redirect(base_url() . 'authentication');
        }
    }

    function search()
    {
        $search = $this->input->get('search') ?? '';
        $transaction_type = $this->input->get('transaction_type') ?? '';
        $status = $this->input->get('status') ?? '';
        $date_from = $this->input->get('date_from') ?? '';
        $date_to = $this->input->get('date_to') ?? '';
        $location_id = intval($this->input->get('location_id') ?? 0);

        try {
            $data = $this->main_model->search($search, $transaction_type, $status, $date_from, $date_to, $location_id);
            echo json_encode($data);
        } catch (Exception $ex) {
            echo json_encode(array('error' => $ex->getMessage()));
        }
    }

    function create_batch()
    {
        $prefix = $this->prefix;
        $input_data = $this->input->post();

        if ($this->cf->module_permission("add", $this->module_permission)) {
            $transaction_date = $input_data['transaction_date'];
            $transaction_type = $input_data['transaction_type'];
            $from_location_id = intval($input_data['from_location_id'] ?? 0);
            $to_location_id = intval($input_data['to_location_id'] ?? 0);
            $remarks = $input_data['remarks'] ?? '';

            // Validation
            if (!$transaction_date || !$transaction_type) {
                echo "Error: Transaction date and type are required!";
                return;
            }

            // Validate locations based on transaction type
            if ($transaction_type === 'RECEIVE' && !$to_location_id) {
                echo "Error: Destination location is required for RECEIVE transactions!";
                return;
            }

            if ($transaction_type === 'RELEASE' && !$from_location_id) {
                echo "Error: Source location is required for RELEASE transactions!";
                return;
            }

            if ($transaction_type === 'TRANSFER' && (!$from_location_id || !$to_location_id || $from_location_id === $to_location_id)) {
                echo "Error: Valid source and destination locations are required for TRANSFER transactions!";
                return;
            }

            try {
                $batch_data = array(
                    'transaction_date' => $transaction_date,
                    'transaction_type' => $transaction_type,
                    'from_location_id' => $from_location_id ?: null,
                    'to_location_id' => $to_location_id ?: null,
                    'remarks' => $remarks,
                    'created_by' => $this->uid
                );

                $result = $this->main_model->create_batch($batch_data);

                if ($result) {
                    echo "Success: Batch transaction created! Transaction Number: " . $result['transaction_number'];
                } else {
                    echo "Error: Failed to create batch transaction!";
                }
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        } else {
            echo "Error: You don't have permission to perform this action!";
        }
    }

    function add_item()
    {
        $prefix = $this->prefix;
        $input_data = $this->input->post();

        if ($this->cf->module_permission("add", $this->module_permission)) {
            $batch_id = intval($input_data['batch_id']);
            $product_id = intval($input_data['product_id']);
            $qty = floatval($input_data['qty']);
            $unit_cost = floatval($input_data['unit_cost'] ?? 0);
            $notes = $input_data['notes'] ?? '';

            if (!$batch_id || !$product_id || $qty <= 0) {
                echo "Error: Valid batch, product, and quantity are required!";
                return;
            }

            try {
                $item_data = array(
                    'product_id' => $product_id,
                    'qty' => $qty,
                    'unit_cost' => $unit_cost,
                    'notes' => $notes
                );

                $result = $this->main_model->add_item($batch_id, $item_data);

                if ($result) {
                    echo "Success: Item added to batch successfully!";
                } else {
                    echo "Error: Failed to add item to batch!";
                }
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        } else {
            echo "Error: You don't have permission to perform this action!";
        }
    }

    function update_item()
    {
        $prefix = $this->prefix;
        $input_data = $this->input->post();

        if ($this->cf->module_permission("modify", $this->module_permission)) {
            $item_id = intval($input_data['item_id']);
            $qty = floatval($input_data['qty']);
            $unit_cost = floatval($input_data['unit_cost'] ?? 0);
            $notes = $input_data['notes'] ?? '';

            if (!$item_id || $qty <= 0) {
                echo "Error: Valid item ID and quantity are required!";
                return;
            }

            try {
                $item_data = array(
                    'qty' => $qty,
                    'unit_cost' => $unit_cost,
                    'notes' => $notes
                );

                $result = $this->main_model->update_item($item_id, $item_data);

                if ($result) {
                    echo "Success: Item updated successfully!";
                } else {
                    echo "Error: Failed to update item!";
                }
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        } else {
            echo "Error: You don't have permission to perform this action!";
        }
    }

    function delete_item()
    {
        $prefix = $this->prefix;
        $item_id = intval($this->input->post('item_id'));

        if ($this->cf->module_permission("delete", $this->module_permission)) {
            if (!$item_id) {
                echo "Error: Valid item ID is required!";
                return;
            }

            try {
                $result = $this->main_model->delete_item($item_id);

                if ($result) {
                    echo "Success: Item deleted successfully!";
                } else {
                    echo "Error: Failed to delete item!";
                }
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        } else {
            echo "Error: You don't have permission to perform this action!";
        }
    }

    function get_batch_details()
    {
        $batch_id = intval($this->input->get('batch_id'));

        if (!$batch_id) {
            echo json_encode(array('error' => 'Invalid batch ID'));
            return;
        }

        try {
            $batch = $this->main_model->get_by_id($batch_id);
            $items = $this->main_model->get_items($batch_id);

            echo json_encode(array(
                'batch' => $batch,
                'items' => $items
            ));
        } catch (Exception $ex) {
            echo json_encode(array('error' => $ex->getMessage()));
        }
    }

    function process_batch()
    {
        $prefix = $this->prefix;
        $batch_id = intval($this->input->post('batch_id'));

        if ($this->cf->module_permission("modify", $this->module_permission)) {
            if (!$batch_id) {
                echo "Error: Valid batch ID is required!";
                return;
            }

            try {
                $result = $this->main_model->process_batch($batch_id, $this->uid);

                if ($result) {
                    echo "Success: Batch transaction processed successfully!";
                } else {
                    echo "Error: Failed to process batch transaction!";
                }
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        } else {
            echo "Error: You don't have permission to perform this action!";
        }
    }

    function cancel_batch()
    {
        $prefix = $this->prefix;
        $batch_id = intval($this->input->post('batch_id'));
        $reason = $this->input->post('reason') ?? '';

        if ($this->cf->module_permission("modify", $this->module_permission)) {
            if (!$batch_id) {
                echo "Error: Valid batch ID is required!";
                return;
            }

            try {
                $result = $this->main_model->cancel_batch($batch_id, $reason);

                if ($result) {
                    echo "Success: Batch transaction cancelled successfully!";
                } else {
                    echo "Error: Failed to cancel batch transaction!";
                }
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        } else {
            echo "Error: You don't have permission to perform this action!";
        }
    }

    function print_batch()
    {
        $batch_id = intval($this->input->get('batch_id'));

        if ($this->cf->module_permission("print", $this->module_permission)) {
            if (!$batch_id) {
                echo "Error: Valid batch ID is required!";
                return;
            }

            try {
                $data = $this->main_model->get_batch_print_data($batch_id);
                
                $data['company_name'] = $this->company_name;
                $data['company_address'] = $this->company_address;
                $data['company_contact'] = $this->company_contact;
                $data['page_name'] = $this->page_name;

                $this->load->view('inventory_batch/print', $data);
            } catch (Exception $ex) {
                echo "Error: " . $ex->getMessage();
            }
        } else {
            echo "Error: You don't have permission to print!";
        }
    }

    function manage()
    {
        $prefix = $this->prefix;

        $data['prefix'] = $prefix;
        $data['app_title'] = $this->session->userdata[$prefix . '_logged_in'][$prefix . '_app_title'];
        $data['app_version'] = $this->session->userdata[$prefix . '_logged_in'][$prefix . '_app_version'];
        $data['page_name'] = "Manage Batch Items";
        $data['parent_menu'] = $this->parent_menu;
        $data['module'] = $this->module;
        $data['module_description'] = $this->module_description;

        if ($this->cf->is_allowed_module($this->module, $prefix)) {
            $data['role_id'] = $this->role_id;
            $data['module_permission'] = $this->module_permission;
            $data['uid'] = strtoupper($this->session->userdata[$prefix . '_logged_in'][$prefix . '_uid']);
            $data['ufname'] = strtoupper($this->session->userdata[$prefix . '_logged_in'][$prefix . '_fname']);

            // Load dropdown data
            $data['locations'] = $this->data_location_model->search('', 0, 1);
            $data['products'] = $this->data_product_model->search('', '', 0, 1);
            
            // Get currency information from system settings
            $currency_info = $this->main_model->get_currency_info();
            $data['currency_code'] = $currency_info->code ?? 'USD';
            $data['currency_symbol'] = $this->get_currency_symbol($currency_info->code ?? 'USD');

            $this->load->view('inventory_batch/manage', $data);
        } else {
            $this->session->set_userdata($this->prefix . '_to_page', current_url());
            redirect(base_url() . 'authentication');
        }
    }

    function get_batch_list()
    {
        if ($this->cf->module_permission("view", $this->module_permission)) {
            $search = $this->input->post('search') ?? '';
            $transaction_type = $this->input->post('transaction_type') ?? '';
            $status = $this->input->post('status') ?? '';
            $date_from = $this->input->post('date_from') ?? '';
            $date_to = $this->input->post('date_to') ?? '';
            $location_id = intval($this->input->post('location_id') ?? 0);

            try {
                $data = $this->main_model->search($search, $transaction_type, $status, $date_from, $date_to, $location_id);
                echo json_encode(array('success' => true, 'data' => $data));
            } catch (Exception $ex) {
                echo json_encode(array('success' => false, 'message' => $ex->getMessage()));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'Permission denied'));
        }
    }

    function create_batch_with_items()
    {
        $prefix = $this->prefix;
        
        // Set JSON content type header
        header('Content-Type: application/json');
        
        // Get JSON input
        $json_input = $this->input->raw_input_stream;
        $input_data = json_decode($json_input, true);
        
        // Log the input for debugging
        error_log('Batch creation input: ' . print_r($input_data, true));

        if ($this->cf->module_permission("add", $this->module_permission)) {
            $transaction_date = $input_data['transaction_date'];
            $transaction_type = $input_data['transaction_type'];
            $from_location_id = intval($input_data['from_location_id'] ?? 0);
            $to_location_id = intval($input_data['to_location_id'] ?? 0);
            $remarks = $input_data['remarks'] ?? '';
            $items = $input_data['items'] ?? [];

            // Validation
            if (!$transaction_date || !$transaction_type) {
                echo json_encode(['success' => false, 'message' => 'Transaction date and type are required!']);
                return;
            }

            if (empty($items)) {
                echo json_encode(['success' => false, 'message' => 'At least one item is required!']);
                return;
            }

            // Validate locations based on transaction type
            if ($transaction_type === 'RECEIVE' && !$to_location_id) {
                echo json_encode(['success' => false, 'message' => 'Destination location is required for RECEIVE transactions!']);
                return;
            }

            if ($transaction_type === 'RELEASE' && !$from_location_id) {
                echo json_encode(['success' => false, 'message' => 'Source location is required for RELEASE transactions!']);
                return;
            }

            if ($transaction_type === 'TRANSFER' && (!$from_location_id || !$to_location_id || $from_location_id === $to_location_id)) {
                echo json_encode(['success' => false, 'message' => 'Valid source and destination locations are required for TRANSFER transactions!']);
                return;
            }

            try {
                $batch_data = array(
                    'transaction_date' => $transaction_date,
                    'transaction_type' => $transaction_type,
                    'from_location_id' => $from_location_id ?: null,
                    'to_location_id' => $to_location_id ?: null,
                    'remarks' => $remarks,
                    'created_by' => $this->uid,
                    'status' => 'COMPLETED' // Create as completed directly
                );

                $result = $this->main_model->create_batch_with_items($batch_data, $items, $this->uid);

                if ($result) {
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Batch transaction created and completed successfully!',
                        'batch_id' => $result['batch_id'],
                        'transaction_number' => $result['transaction_number']
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create batch transaction!']);
                }
            } catch (Exception $ex) {
                error_log('Batch creation error: ' . $ex->getMessage());
                echo json_encode(['success' => false, 'message' => $ex->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'You don\'t have permission to perform this action!']);
        }
    }

    function get_products()
    {
        // Set JSON content type header
        header('Content-Type: application/json');
        
        try {
            if ($this->cf->module_permission("view", $this->module_permission)) {
                // Get active products with basic info using correct table structure
                $this->db->select('x.id, x.code as product_code, x.name as product_name, x.amount as cost, x.uom_id, m.name as uom');
                $this->db->from('products x');
                $this->db->join('uoms m', 'm.id = x.uom_id', 'left');
                $this->db->where('x.status_id', 2); // Active products only (status_id = 2)
                $this->db->order_by('x.code', 'ASC');
                
                $query = $this->db->get();
                
                if ($query) {
                    $products = $query->result();
                    echo json_encode($products);
                } else {
                    $error = $this->db->error();
                    http_response_code(500);
                    echo json_encode(array('error' => 'Database query failed: ' . $error['message']));
                }
            } else {
                http_response_code(403);
                echo json_encode(array('error' => 'Permission denied'));
            }
        } catch (Exception $ex) {
            error_log('get_products error: ' . $ex->getMessage());
            http_response_code(500);
            echo json_encode(array('error' => 'Server error: ' . $ex->getMessage()));
        }
    }
    
    // Diagnostic endpoint to test basic functionality
    function test_products()
    {
        header('Content-Type: application/json');
        
        try {
            // Simple test without authentication
            $this->db->select('COUNT(*) as count');
            $this->db->from('products');
            $this->db->where('status_id', 2);
            $query = $this->db->get();
            
            if ($query) {
                $result = $query->row();
                echo json_encode(array(
                    'success' => true,
                    'message' => 'Database connection working',
                    'active_products' => $result->count,
                    'timestamp' => date('Y-m-d H:i:s')
                ));
            } else {
                $error = $this->db->error();
                echo json_encode(array('error' => 'Database error: ' . $error['message']));
            }
        } catch (Exception $ex) {
            echo json_encode(array('error' => 'Exception: ' . $ex->getMessage()));
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
