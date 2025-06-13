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
        if ($this->cf->module_permission("view", $this->module_permission)) {
            $data['role_id'] = $this->role_id;
            $data['uid'] = $this->uid;
            $data['uname'] = $this->uname;
            $data['page_name'] = $this->page_name;
            $data['parent_menu'] = $this->parent_menu;
            $data['module_description'] = $this->module_description;
            $data['app_code'] = $this->app_code;
            $data['app_name'] = $this->app_name;
            $data['app_title'] = $this->app_title;
            $data['app_version'] = $this->app_version;
            $data['company_code'] = $this->company_code;
            $data['company_name'] = $this->company_name;
            $data['company_address'] = $this->company_address;
            $data['company_contact'] = $this->company_contact;
            $data['module_permission'] = $this->module_permission;

            // Load dropdown data
            $data['locations'] = $this->data_location_model->search('', 0, 1);
            $data['products'] = $this->data_product_model->search('', '', 0, 1);

            $this->load->view('inventory_batch/index', $data);
        } else {
            echo "Error: You don't have permission to access this page!";
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
        if ($this->cf->module_permission("view", $this->module_permission)) {
            $data['role_id'] = $this->role_id;
            $data['uid'] = $this->uid;
            $data['uname'] = $this->uname;
            $data['page_name'] = "Manage Batch Items";
            $data['parent_menu'] = $this->parent_menu;
            $data['module_description'] = $this->module_description;
            $data['app_code'] = $this->app_code;
            $data['app_name'] = $this->app_name;
            $data['app_title'] = $this->app_title;
            $data['app_version'] = $this->app_version;
            $data['company_code'] = $this->company_code;
            $data['company_name'] = $this->company_name;
            $data['company_address'] = $this->company_address;
            $data['company_contact'] = $this->company_contact;
            $data['module_permission'] = $this->module_permission;

            // Load dropdown data
            $data['locations'] = $this->data_location_model->search('', 0, 1);
            $data['products'] = $this->data_product_model->search('', '', 0, 1);

            $this->load->view('inventory_batch/manage', $data);
        } else {
            echo "Error: You don't have permission to access this page!";
        }
    }
}
