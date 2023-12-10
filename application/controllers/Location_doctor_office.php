<?php
defined("BASEPATH") or die("No direct script allowed!");
require_once(APPPATH . "controllers/Current_transaction.php");

/**
 * **location IDS**
 * 1 - Reception
 * 2 - Triage
 * 3 - Cashier
 * 4 - Pre-Emp
 * 5 - Doc Office
 * 6 - Laboratory
 * 7 - X-ray
 * 8 - Audiometry
 * 9 - Spiro
 * 10 - ECG
 * 11 - Pharmacy
 * 12 - ER
 */
class Location_doctor_office extends Current_transaction
{
	protected $module_permission = array();
	protected $prefix; //check or update app_details table in db for session_prefix field
	protected $default_error_msg = "Error: Critical Error Encountered!";
	protected $role_id;
	protected $module = "location_doctor_office";
	protected $module_description = "Doctor's Office";
	protected $page_name = "Doctor's Office";
	protected $parent_menu = "Location";
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
	protected $location_id = 5;
	// protected $timer_countdown;

	function __construct()
	{
		parent::__construct();
	}


	function search()
	{
		$search = trim($this->input->get("search"));
		try {
			$result = $this->main_model->search($search, [], [$this->location_id]);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	function advance_search()
	{
		$errors = [];
		$data = [];

		$form_data = $this->input->post();

		try {
			$result = $this->main_model->advance_search($form_data, [$this->location_id], $this->uid);
			$data["result"] = $result;
		} catch (Exception $ex) {
			$errors["error"] = $ex->getMessage();
		}

		if (!empty($errors)) {
			$data["success"] = false;
			$data["errors"] = $errors;
		} else {
			$data["success"] = true;
		}

		echo json_encode($data);
	}

	function search_active()
	{
		try {
			$status_ids = array(2, 3);

			$result = $this->main_model->search("", $status_ids, [$this->location_id], $this->uid);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	function view($transaction_id)
	{
		if ($transaction_id) {
			try {
				$record = $this->main_model->view($transaction_id, [$this->location_id], $this->uid);
				if ($record) {
					$prefix = $this->prefix;

					$data["prefix"] = $prefix;
					$data["app_title"] = $this->app_title;
					$data["app_version"] = $this->app_version;
					$data["page_name"] = $this->page_name;
					$data["parent_menu"] = $this->parent_menu;
					$data["module"] = $this->module;
					$data["module_description"] = $this->module_description;

					if ($this->cf->is_allowed_module($this->module, $prefix)) {
						$data["role_id"] = $this->role_id;
						$data["module_permission"] = $this->module_permission;
						$data["uid"] = $this->uid;
						$data["ufname"] = strtoupper($this->uname);


						$data["uoms"] = $this->data_uom_model->search();
						$data["transaction_id"] = $transaction_id;
						$data["transaction_no"] = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
						$data["transaction_status_id"] = $record->status_id;
						$data["record"] = $record;
						$data["disabled_field"] = $record->status_id == 2 ? "" : "disabled"; //if status not draft then disabled
						$data["disabled_field2"] = ($record->status_id == 2 || $record->status_id == 3) ? "" : "disabled";

						$data["trans_types"] = $this->data_trans_type_model->search();
						$data["patients"] = $this->data_patient_model->search("");
						$data["payment_methods"] = $this->data_payment_method_model->search();
						$data["payment_types"] = $this->data_payment_type_model->search();
						$data["clients"] = $this->data_client_model->search();
						$data["charging_types"] = $this->data_charging_type_model->search();
						$data["insurances"] = $this->data_insurance_model->search();
						$data["doctors"] = $this->shared_model->doctors();
						$data["locations"] = $this->data_location_model->search();
						$data["queues"] = $this->data_queue_model->search();

						$data["products"] = $this->data_product_model->search();
						$data["pharmacy_products"] = $this->data_product_model->get_product_by_category(array(2)); //2-pharmacy
						$data["packages"] = $this->data_package_model->search();

						//get total charges
						$data["total_charges"] = $this->main_model->get_total_charges($transaction_id);
						//get total paid
						$total_paid = $this->main_model->get_total_paid($transaction_id);
						$data["total_paid"] = $total_paid ? $total_paid : 0;

						$data["amount_due"] = $data["total_charges"] - $data["total_paid"];

						$data["xray_attachments"] = $this->xray_attachment_list($transaction_id);
						$data["trails"] = $this->shared_model->get_logs($transaction_id);

						$items = $this->item_model->search_by_transaction($transaction_id);


						$data["items"] = $items;
						$this->load->view("current_transaction/view", $data);
					} else {
						$this->default_error_msg;
					}
				} else {
					$this->session->set_flashdata("error", "Error: Trans no. " . str_pad($transaction_id, 5, "0", STR_PAD_LEFT) . " is unavailable!");
					redirect(base_url($this->module));
				}
			} catch (Exception $ex) {
				$this->session->set_flashdata("error", $ex->getMessage());
				redirect(base_url($this->module));
			}
		} else {
			$this->session->set_flashdata("error", "Error: Critical Error Encountered!");
		}
	}
}
