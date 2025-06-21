<?php
defined("BASEPATH") or die("No direct script allowed!");

class Current_transaction extends CI_Controller
{
	protected $module_permission = array();
	protected $prefix; //check or update app_details table in db for session_prefix field
	protected $default_error_msg = "Error: Critical Error Encountered!";
	protected $role_id;
	protected $module = "current_transaction";
	protected $module_description = "Transaction";
	protected $page_name = "Transaction";
	protected $parent_menu = "Current";
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
	protected $location_id = 0;
	// protected $timer_countdown;

	function __construct()
	{
		parent::__construct();
		//date_default_timezone_set("Pacific/Port_Moresby");

		//get session prefix from db
		$this->load->model("app_details_m", "ad");
		try {
			$ad = $this->ad->get_details();
			if ($ad) {
				$this->prefix = $ad->session_prefix;
				date_default_timezone_set($ad->timezone);

				if (!isset($this->session->userdata[$this->prefix . "_logged_in"])) {
					$this->session->set_userdata($this->prefix . '_to_page', current_url());
					redirect(base_url() . "authentication");
				} else {
					$prefix = $this->prefix;

					$this->uid = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_uid"];
					$this->uname = strtoupper($this->session->userdata[$prefix . "_logged_in"][$prefix . "_fname"]);
					$this->ufullname = strtoupper($this->session->userdata[$prefix . "_logged_in"][$prefix . "_fullname"]);
					$this->app_code = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_app_code"];
					$this->app_name = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_app_name"];
					$this->app_title = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_app_title"];
					$this->app_version = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_app_version"];
					$this->company_code = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_code"];
					$this->company_name = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_name"];
					$this->company_address = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_address"];
					$this->company_contact = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_contact"];
					// $this->timer_countdown = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_timer_countdown"];

					$this->load->model("authentication_m", "a");
					$this->role_id = $this->session->userdata[$this->prefix . "_logged_in"][$this->prefix . "_role"];
					$this->module_permission = $this->a->allow_permission($this->role_id, $this->module);

					$this->load->library("custom_function", NULL, "cf");

					//load needed models for this page
					$this->load->model("transaction_model", "main_model");
					$this->load->model("data_trans_type_model");
					$this->load->model("data_patient_model");
					$this->load->model("data_payment_method_model");
					$this->load->model("data_payment_type_model");
					$this->load->model("data_client_model");
					$this->load->model("data_charging_type_model");
					$this->load->model("data_insurance_model");
					$this->load->model("data_uom_model");
					$this->load->model("data_location_model");
					$this->load->model("data_queue_model");

					$this->load->model('data_gender_model');
					$this->load->model('data_civil_model');

					$this->load->model("data_product_model");
					$this->load->model("data_package_model");

					$this->load->model("item_model");
					$this->load->model("payment_model");
					$this->load->model("prescription_model");

					$this->load->model("shared_model");
				}
			}
		} catch (Exception $ex) {
			//echo $ex->getMessage();
			$this->session->set_userdata($this->prefix . '_to_page', current_url());
			redirect(base_url() . "authentication");
		}
		//end of getting session prefix
	}

	function index()
	{
		$prefix = $this->prefix;

		$data["prefix"] = $prefix;
		$data["app_title"] = $this->app_title;
		$data["app_version"] = $this->app_version;
		$data["page_name"] = $this->page_name;
		$data["parent_menu"] = $this->parent_menu;
		$data["module"] = $this->module;
		$data["module_description"] = $this->module_description;
		//$data["timer_countdown"] = $this->timer_countdown;

		if ($this->cf->is_allowed_module($this->module, $prefix)) {
			$data["role_id"] = $this->role_id;
			$data["module_permission"] = $this->module_permission;
			$data["uid"] = $this->uid;
			$data["ufname"] = strtoupper($this->uname);
			$data["search"] = $this->input->get("search");

			$this->load->view("current_transaction/index", $data);
		} else {
			$this->default_error_msg;
		}
	}

	function search()
	{
		$search = trim($this->input->get("search"));
		try {
			$result = $this->main_model->search($search, [], []);
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
			$result = $this->main_model->advance_search($form_data, []);
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

			$result = $this->main_model->search("", $status_ids, []);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	function create()
	{
		try {
			$create = $this->main_model->create($this->uid);

			if ($create) {
				$this->session->set_flashdata("transaction_id", $create);

				echo $create;

				//write to log
				$this->shared_model->write_to_log("Create", $this->uid, $create, 0, "", "", "Transaction has been created");
			} else {
				echo $this->default_error_msg;
			}
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	function new()
	{
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
			$data["location_id"] = $this->location_id;

			$data["uoms"] = $this->data_uom_model->search();
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

			$data['genders'] = $this->data_gender_model->search("");
			$data['civils'] = $this->data_civil_model->search("");

			$this->load->view("current_transaction/new", $data);
		} else {
			$this->default_error_msg;
		}
	}

	function modify($transaction_id)
	{
		if ($transaction_id) {
			try {
				$record = $this->main_model->view($transaction_id, $this->uid);
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

						$data["trails"] = $this->shared_model->get_logs($transaction_id);

						$this->load->view("current_transaction/modify", $data);
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

	function save(){
		$result = array("success" => false);
		$data = $this->input->post();
		$dt = $this->input->post("date_new");
		$trans_type_id = $this->input->post("trans_type_id_new");
		$patient_id = $this->input->post("patient_id_new");
		$payment_method_id = $this->input->post("payment_method_id_new");
		$charging_type_id = $this->input->post("charging_type_id_new");
		
		if ($dt && $trans_type_id && $patient_id && $payment_method_id && $charging_type_id){
			try {
				$save = $this->main_model->save($data, $this->uid, $this->location_id);
				if ($save){
					$result["success"] = true;
					$result["data"] = $save;

					//$this->session->set_flashdata("transaction_id", $save);
					$transaction_no = str_pad($save,5,"0",STR_PAD_LEFT);
					$this->session->set_flashdata("error", "Transaction #: {$transaction_no} has been created and transferred to Triage!");
				}else{
					$result["error"] = "Unable to save, Please try again!";
				}
			} catch (Exception $ex){
				$result["error"] = $ex->getMessage();
			}
		}else{
			$result["error"] = "Fields with red asterisk are required!";
		}

		echo json_encode($result);
	}

	function update(){
		$result = array("success" => false);
		$data = $this->input->post();
		$transaction_id = $this->input->post("id_update");
		$dt = $this->input->post("date_update");
		$trans_type_id = $this->input->post("trans_type_id_update");
		$patient_id = $this->input->post("patient_id_update");
		$payment_method_id = $this->input->post("payment_method_id_update");
		$charging_type_id = $this->input->post("charging_type_id_update");

		if ($transaction_id){
			if ($dt && $trans_type_id && $patient_id && $payment_method_id && $charging_type_id){
				try {
					$save = $this->main_model->update( $transaction_id, $data, $this->uid);
					if ($save){
						$result["success"] = true;
						$result["message"] = "Successfully Updated";
					}else{
						$result["error"] = "Unable to save, Please try again!";
					}
				} catch (Exception $ex){
					$result["error"] = $ex->getMessage();
				}
			}else{
				$result["error"] = "Fields with red asterisk are required!";
			}
		}else{
			$result["error"] = "Critical Error Encountered!";
		}
		echo json_encode($result);
	}

	function auto_save()
	{
		$result["success"] = false;

		$id = $this->input->post("id");
		$fieldname = $this->input->post("fieldname");
		$fieldvalue = $this->input->post("fieldvalue");
		$tag = $this->input->post("tag");
		$fieldtext = $this->input->post("fieldtext");

		$form_data[$fieldname] = $fieldvalue;

		if ($id) {
			try {
				$this->main_model->update($id, $form_data, $this->uid);

				//write to log
				$fieldname = str_replace("_update", "", $fieldname);
				$fieldname = str_replace("_id", "", $fieldname);
				$fieldname = ucwords($fieldname);
				$tag = strtolower($tag);

				$log_remarks = str_replace("_update", "", $fieldname) . " : " . ($tag === "select" ? $fieldtext : $fieldvalue);
				$save = $this->shared_model->write_to_log("Update Transaction", $this->uid, $id, 0, "", "", $log_remarks);

				if ($save){
					$get_logs = $this->shared_model->get_logs($id);

					$result["success"] = true;
					$result["data"] = $get_logs;
				}
			} catch (Exception $ex) {
				$result["error"] = $ex->getMessage();
			}
		}

		echo json_encode($result);
	}

	function view($transaction_id)
	{
		if ($transaction_id) {
			try {
				$record = $this->main_model->view($transaction_id, $this->uid);
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

	//TRANS HISTORY
	function patient_history(){
		$result["success"] = false;
		$patient_id = $this->input->post("patient_id");
		$transaction_id = $this->input->post("transaction_id");

		if ($patient_id && $transaction_id){
			try{
				$result["success"] = true;
				$result["records"] = $this->data_patient_model->history($patient_id);
			}catch(Exception $ex){
				$result["success"] = false;
				$result["error"] = $ex->getMessage();
			}
		}else{
			$result["error"] = $this->default_error_msg;
		}

		echo json_encode($result);
	}

	//TRANS CONFIRM
	function confirm()
	{
		$transaction_id = $this->input->post("transaction_id");

		if ($transaction_id) {
			try {

				$save = $this->main_model->confirm($transaction_id, $this->uid);

				if ($save) {
					$transaction_no = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
					$this->session->set_flashdata("message", $transaction_no . " has been confirmed!");

					echo $transaction_no;
				} else {
					echo "Error: Failed! Please try again!";
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	//TRANS PRINT CHARGES
	function print_charges($transaction_id)
	{
		if ($transaction_id) {
			try {
				$record = $this->main_model->view($transaction_id, $this->uid);

				if ($record) {
					$data["transaction_id"] = $transaction_id;
					$data["transaction_no"] = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
					$data["app_name"] = $this->app_name;
					$data["company_name"] = $this->company_name;
					$data["company_address"] = $this->company_address;
					$data["company_contact"] = $this->company_contact;
					$data["record"] = $record;
					$data["items"] = $this->item_model->search_by_transaction($transaction_id);
					//get total paid
					$total_paid = $this->main_model->get_total_paid($transaction_id);
					$data["total_paid"] = $total_paid ? $total_paid : 0;

					$log_remarks = "";
					$this->shared_model->write_to_log("Print Charges", $this->uid, $transaction_id, 0, "", "", $log_remarks);

					$this->load->library('Pdf');
					$this->load->view('pdf/charges', $data);
				} else {
					$this->session->set_flashdata("error", "Error: Request no. REQ-" . str_pad($transaction_id, 5, "0", STR_PAD_LEFT) . " is unavailable!");
					redirect(base_url($this->module));
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	//TRANS INSURANCE INVOICE
	function print_insurance_invoice($transaction_id)
	{
		if ($transaction_id) {
			try {
				$record = $this->main_model->view($transaction_id, $this->uid);

				if ($record) {
					$data["transaction_id"] = $transaction_id;
					$data["transaction_no"] = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
					$data["app_name"] = $this->app_name;
					$data["company_name"] = $this->company_name;
					$data["company_address"] = $this->company_address;
					$data["company_contact"] = $this->company_contact;
					$data["record"] = $record;
					$data["items"] = $this->item_model->search_by_transaction($transaction_id);
					//get total paid
					$total_paid = $this->main_model->get_total_paid($transaction_id);
					$data["total_paid"] = $total_paid ? $total_paid : 0;

					$log_remarks = "";
					$this->shared_model->write_to_log("Print Insurance Invoice", $this->uid, $transaction_id, 0, "", "", $log_remarks);

					$this->load->library('Pdf');
					$this->load->view('pdf/insurance_invoice', $data);
				} else {
					$this->session->set_flashdata("error", "Error: Request no. REQ-" . str_pad($transaction_id, 5, "0", STR_PAD_LEFT) . " is unavailable!");
					redirect(base_url($this->module));
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	//TRANS CHECK PAYMENT
	function check_payment() {
		$transaction_id = $this->input->post("transaction_id");
		$result["success"] = false;

		if ($transaction_id) {
			try {
				//get total charges
				$total_charges = $this->main_model->get_total_charges($transaction_id);
				//get total paid
				$total_paid = $this->main_model->get_total_paid($transaction_id);
				$total_paid = $total_paid ? $total_paid : 0;
				//get payment history list
				$payment_list = $this->payment_model->get_payment_list($transaction_id);

				$amount_due = $total_charges - $total_paid;

				$result["success"] = true;
				$result["amount_due"] = $amount_due;
				$result["payment_list"] = $payment_list;

			} catch(Exception $ex) {
				$result["error"] = $ex->getMessage();
			}
		}else{
			$result["error"] = $this->default_error_msg;
		}

		echo json_encode($result);
	}

	//TRANS SAVE PAYMENT
	function save_payment() {
		$transaction_id = $this->input->post("transaction_id");
		$date = $this->input->post("date");
		$amount_due = $this->input->post("amount_due");
        $payment_type_id = $this->input->post("payment_type_id");
		$pay_amount = $this->input->post("pay_amount");
        $tender_amount = $this->input->post("tender_amount");
        $change_amount = $this->input->post("change_amount");
        $reference = $this->input->post("reference");

		$result["success"] = false;


		if ($transaction_id){
			if ($date && $payment_type_id && $pay_amount > 0.01 && $tender_amount > 0.01){

				if ($tender_amount >= $pay_amount){
					try{
						$save = $this->payment_model->save_payment(
							$transaction_id,
							$date,
							$payment_type_id,
							$amount_due,
							$pay_amount,
							$tender_amount,
							$change_amount,
							$reference,
							$this->uid
						);
						if ($save){
							//get total charges
							$total_charges = $this->main_model->get_total_charges($transaction_id);
							//get total paid
							$total_paid = $this->main_model->get_total_paid($transaction_id);
							$total_paid = $total_paid ? $total_paid : 0;

							$amount_due = $total_charges - $total_paid;

							$result["logs"] = $this->shared_model->get_logs($transaction_id);

							$result["payment_id"] = $save;
							$result["success"] = true;
							$result["total_paid"] = $total_paid;
							$result["amount_due"] = $amount_due;
						}
					}catch(Exception $ex){
						$result["error"] = $ex->getMessage();
					}
				}else{
					$result["error"] = "Tender amount is less than the amount to be paid!";
				}

			}else{
				$result["error"] = "All fields with asterisk are required!";
			}
		}else{
			$result["error"] = $this->default_error_msg;
		}

		echo json_encode($result);
	}

	//TRANS CANCEL PAYMENT
	function cancel_payment(){
		$transaction_id = $this->input->post("transaction_id");
		$payment_id = $this->input->post("payment_id");
		$result["success"] = false;

		if ($transaction_id && $payment_id){
			try {
				$cancel = $this->payment_model->cancel($payment_id, $transaction_id, $this->uid);

				if ($cancel){
					//get total charges
					$total_charges = $this->main_model->get_total_charges($transaction_id);
					//get total paid
					$total_paid = $this->main_model->get_total_paid($transaction_id);
					$total_paid = $total_paid ? $total_paid : 0;

					$payment_list = $this->payment_model->get_payment_list($transaction_id);

					$amount_due = $total_charges - $total_paid;

					$result["logs"] = $this->shared_model->get_logs($transaction_id);

					$result["success"] = true;
					$result["total_paid"] = $total_paid;
					$result["amount_due"] = $amount_due;
					$result["payment_list"] = $payment_list;
				}else{
					$result["error"] = "Unable to cancel. Please try again!";
				}
			} catch(Exception $ex) {
				$result["error"] = $ex->getMessage();
			}
		}else{
			$result["error"] = $this->default_error_msg;
		}

		echo json_encode($result);
	}

	//TRANS PRINT e-RECEIPT
	function print_payment($payment_id)
	{
		if ($payment_id) {
			try {
				$record = $this->payment_model->search_by_id($payment_id);

				if ($record) {
					$data["payment_no"] = "PMT" . str_pad($payment_id, 3, "0", STR_PAD_LEFT);
					$data["app_name"] = $this->app_name;
					$data["company_name"] = $this->company_name;
					$data["company_address"] = $this->company_address;
					$data["company_contact"] = $this->company_contact;
					$data["record"] = $record;
					$data["items"] = $this->item_model->search_by_transaction($record->transaction_id);
					//get total paid
					$total_paid = $this->main_model->get_total_paid($record->transaction_id);
					$data["total_paid"] = $total_paid ? $total_paid : 0;

					$data["amount_paid"] = number_format($record->amount,2,".",",");
					$data["amount_paid_words"] = $this->cf->number_to_words($record->amount, "Kina", "Toea");

					$log_remarks = "";
					$this->shared_model->write_to_log("Print Payment", $this->uid, $record->transaction_id, 0, "", "", $log_remarks);

					$this->load->library('Pdf');
					$this->load->view('pdf/payment2', $data);
				} else {
					$this->session->set_flashdata("error", "Error: Critical Error Encountered!");
					redirect(base_url($this->module));
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	//TRANS or SEND TO LOCATION
	function transfer(){
		$result = array("success" => false);
		$transaction_id = $this->input->post("transaction_id");
		$location_id = $this->input->post("location_id");
		$location = $this->input->post("location");

		if ($transaction_id) {
			try {

				$save = $this->main_model->transfer($transaction_id, $location_id, $location, $this->uid);

				if ($save) {
					$transaction_no = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
					$this->session->set_flashdata("message", "Transaction No: " . $transaction_no . " has been transferred to " . $location);

					$result["success"] = true;
					$result["message"] = "Successfully transferred!";
				} else {
					$result["error"] = "Failed to transfer! Please try again!";
				}
			} catch (Exception $ex) {
				$result["error"] = $ex->getMessage();
			}
		} else {
			$result["error"] = "Critical Error Encountered!";
		}

		echo json_encode($result);
	}

	//TRANS PRESCRIPTION
	function prescription_list(){
		$result["success"] = false;
		$transaction_id = $this->input->post("transaction_id");

		if ($transaction_id){
			try{
				$result["data"] = $this->prescription_model->search_by_transaction($transaction_id);
				$result["success"] = true;
			}catch(Exception $ex){
				$result["error"] = $ex->getMessage();
			}
		}else{
			$result["error"] = $this->default_error_msg;
		}

		echo json_encode($result);
	}

	function prescription_add(){
		$result["success"] = false;
		$transaction_id = $this->input->post("transaction_id");
		$product_id = $this->input->post("txt_prescription_product_id");
		$qty = $this->input->post("txt_prescription_qty");
		$instruction = $this->input->post("txt_prescription_instruction");
		$data = $this->input->post();

		if ($transaction_id){
			if ($product_id && floatval($qty)>0 && $instruction){
				try{
					$add = $this->prescription_model->add($data, $transaction_id, $this->uid);

					if ($add){
						$result["data"] = $this->prescription_model->search_by_transaction($transaction_id);
						$result["logs"] = $this->shared_model->get_logs($transaction_id);

						$result["success"] = true;
					}else{
						$result["error"] = "Error: Adding failed, please try again!";
					}
				}catch(Exception $ex){
					$result["error"] = $ex->getMessage();
				}
			}else{
				$result["error"] = "Error: All fields are required!";
			}
		}else{
			$result["error"] = $this->default_error_msg;
		}

		echo json_encode($result);
	}

	function prescription_delete(){
		$result["success"] = false;
		$transaction_id = $this->input->post("transaction_id");
		$prescription_id = $this->input->post("prescription_id");
		$reason = $this->input->post("reason");

		if ($transaction_id && $prescription_id){
			if ($reason){
				try{
					$delete = $this->prescription_model->delete($prescription_id, $transaction_id, $reason, $this->uid);

					if ($delete){
						$result["data"] = $this->prescription_model->search_by_transaction($transaction_id);
						$result["logs"] = $this->shared_model->get_logs($transaction_id);

						$result["success"] = true;
					}else{
						$result["error"] = "Error: Unable to delete, please try again!";
					}
				}catch(Exception $ex){
					$result["error"] = $ex->getMessage();
				}
			}else{
				$result["error"] = "Error: Please provide a reason!";
			}
		}else{
			$result["error"] = $this->default_error_msg;
		}

		echo json_encode($result);
	}

	function prescription_print($transaction_id)
	{
		if ($transaction_id) {
			try {
				$record = $this->main_model->view($transaction_id, $this->uid);

				if ($record) {
					$data["transaction_id"] = $transaction_id;
					$data["transaction_no"] = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
					$data["app_name"] = $this->app_name;
					$data["company_name"] = $this->company_name;
					$data["company_address"] = $this->company_address;
					$data["company_contact"] = $this->company_contact;
					$data["record"] = $record;
					$data["items"] = $this->prescription_model->search_by_transaction($transaction_id);

					$log_remarks = "";
					$this->shared_model->write_to_log("Print Prescription", $this->uid, $transaction_id, 0, "", "", $log_remarks);

					$this->load->library('Pdf');
					$this->load->view('pdf/prescription', $data);
				} else {
					$this->session->set_flashdata("error", "Error: Request no. REQ-" . str_pad($transaction_id, 5, "0", STR_PAD_LEFT) . " is unavailable!");
					redirect(base_url($this->module));
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	//TRANS COMPLETE
	function complete(){
		$transaction_id = $this->input->post("transaction_id");
		$result["success"] = false;

		if ($transaction_id){
			try{
				$complete = $this->main_model->complete($transaction_id, $this->uid);

				if ($complete["proceed"] === true){
					$transaction_no = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
					$this->session->set_flashdata("message", $transaction_no . " has been mark as completed!");

					$result["success"] = true;
				}else{
					$result["error"] = $complete["error"];
				}
			}catch(Exception $ex){
				$result["error"] = $ex->getMessage();
			}
		} else {
			$result["error"] = $this->default_error_msg;
		}

		echo json_encode($result);
	}

	//TRANS CANCEL
	function cancel()
	{
		$result["success"] = false;

		$transaction_id = $this->input->post("transaction_id");
		$reason = $this->input->post("reason");

		if ($transaction_id) {
			if ($reason) {
				try {

					$save = $this->main_model->cancel($transaction_id, $reason, $this->uid);

					if ($save["proceed"] === true) {
						$transaction_no = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
						$this->session->set_flashdata("message", $transaction_no . " has been cancelled!");

						$result["success"] = true;
						$result["data"] = $transaction_no;
					} else {
						$result["error"] = $save["error"];
					}

				} catch (Exception $ex) {
					$result["error"] = $ex->getMessage();
				}
			} else {
				$result["error"] =  "Error: Please provide reason!";
			}
		} else {
			$result["error"] =  $this->default_error_msg;
		}

		echo json_encode($result);
	}

	//SELECT PRODUCT
	function select_product($transaction_id, $product_id)
	{
		if ($transaction_id && $product_id) {
			try {
				$result = $this->data_product_model->search_by_row($product_id);
				echo json_encode($result);
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		}
	}

	function xray_attachment_add()
	{
		$transaction_id = $this->input->post("transaction_id_new");
		$result["success"] = false;

		if ($transaction_id) {
			//$filenames = $_FILES["xray_attachment_add"];
			//if ($filename) {

			$upload = $this->do_upload("xray_attachment_add", $transaction_id);

			if (!$upload["error"]) {

				//echo json_encode($this->xray_attachment_list($transaction_id));
				$result["success"] = true;
				$result["data"] = $this->xray_attachment_list($transaction_id);

				// Retrieve and display the uploaded filenames
				$file_info = $upload["upload_data"];

				//checkArrayType: 0-not array, 1-1D array, 2-2D array
				$array_type = $this->cf->checkArrayType($file_info);
				$filenames = "";

				if ($array_type == 1){
					$filenames = $file_info['file_name'];
				}elseif ($array_type == 2){
					$i = 0;
					foreach($file_info as $filename){
						$i++;
						$filenames .= ($i <= 1) ? $filename['file_name'] : ", " . $filename['file_name'];
					}
				}

				//write to log
				$log_remarks = "Attachment : {$filenames}";
				$this->shared_model->write_to_log("Add X-Ray Attachment", $this->uid, $transaction_id, 0, "", "", $log_remarks);

				//get logs from
				$result["logs"] = $this->shared_model->get_logs($transaction_id);
			}else{
				$result["error"] = $upload["error_msg"];
			}
			//}
		}else{
			$result["error"] = $this->default_error_msg;
		}

		echo json_encode($result);
	}

	function xray_attachment_remove()
	{
		$transaction_id = $this->input->post("transaction_id");
		$filename = $this->input->post("filename");
		$file = "./upload/xray/" . $transaction_id . "/" . $filename;
		$result["success"] = false;

		if ($transaction_id && $filename) {
			if (file_exists($file)) {
				if (!unlink($file)) {
					//echo "Error: Unable to delete attachment!";
					$result["error"] = "Unable to delete attachment!";
				} else {
					//write to log
					$log_remarks = "Attachment : {$filename}";
					$this->shared_model->write_to_log("Removed X-Ray Attachment", $this->uid, $transaction_id, 0, "", "", $log_remarks);

					$result["success"] = true;
					//get logs from
					$result["logs"] = $this->shared_model->get_logs($transaction_id);
				}
			} else {
				//echo "Error: File does not exist!";
				$result["error"] = "File does not exist!";
			}
		} else {
			//echo $this->default_error_msg;
			$result["error"] = $this->default_error_msg;
		}

		echo json_encode($result);
	}

	function xray_attachment_list($transaction_id)
	{
		$path = "./upload/xray/" . $transaction_id;
		$files = array();

		if (file_exists($path)) {
			$arrFiles = scandir($path);

			foreach ($arrFiles as $file) {
				//disregard if the result is a directory
				if (!is_dir($path . "/" . $file)) {
					array_push($files, $file);
				}
			}
		}

		return $files;
	}

	function do_upload($file, $transaction_id)
	{
		$result = array("error" => false);

		//prepare upload path
		if (!file_exists("./upload/xray/" . $transaction_id)) {
			mkdir("./upload/xray/" . $transaction_id, 0777, true);
		}

		$config["upload_path"]          = "./upload/xray/{$transaction_id}/";
		$config["allowed_types"]        = "*";
		//$config["max_size"]             = 10000;
		// $config["max_width"]            = 1024;
		// $config["max_height"]           = 768;


		$this->load->library("upload", $config);

		if (!$this->upload->do_upload($file)) {
			$result["error"] = true;
			$result["error_msg"] =  $this->upload->display_errors();
		} else {
			$result["upload_data"] = $this->upload->data();
		}

		return $result;
	}


	//ITEM ADD
	function add_item()
	{
		$errors = [];
		$data = [];

		$form_data = $this->input->post();
		$transaction_id = $form_data["transaction_id"];
		$product_id = $form_data["product_id"];
		$qty = $form_data["qty"];

		if ($transaction_id && $product_id && $qty) {
			try {
				$add = $this->item_model->add($form_data, $this->uid);

				if ($add) {
					//display result
					$result = $this->item_model->search_by_transaction($transaction_id);
					$data["logs"] = $this->shared_model->get_logs($transaction_id);

					$data["result"] = $result;
				} else {
					$errors["error"] = "Error: Saving Failed, Please Try Again!";
				}
			} catch (Exception $ex) {
				$errors["error"] = $ex->getMessage();
			}
		} else {
			$errors["error"] = "Error: All fields with * are required!";
		}

		if (!empty($errors)) {
			$data["success"] = false;
			$data["errors"] = $errors;
		} else {
			$data["success"] = true;
		}

		echo json_encode($data);
	}

	//ITEM INFO
	function item_search_row()
	{
		$id = intval($this->input->post("id"));
		if ($id) {
			try {
				$info = $this->item_model->search_by_row($id);
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

	//ITEM UPDATE
	function item_update()
	{
		$result["success"] = false;
		$errors = [];
		$data = [];

		$form_data = $this->input->post();
		$transaction_id = !empty($form_data["transaction_id_item_update"]) ? intval($form_data["transaction_id_item_update"]) : 0;
		$id = !empty($form_data["id_item_update"]) ? intval($form_data["id_item_update"]) : 0;

		if ($transaction_id && $id) {
			try {
				$save = $this->item_model->update($id, $form_data, $this->uid);

				if ($save) {
					//display result
					$result["records"] = $this->item_model->search_by_transaction($transaction_id);
					$result["logs"] = $this->shared_model->get_logs($transaction_id);
					$result["success"] = true;
				} else {
					$result["error"] = "Error: Saving Failed, Please Try Again!";
				}
			} catch (Exception $ex) {
				$result["error"] = $ex->getMessage();
			}

		} else {
			$result["error"] = "Error: Critical Error Encountered!";
		}

		echo json_encode($result);
	}

	//ITEM CANCEL
	function item_cancel()
	{
		$errors = [];
		$data = [];

		$id = $this->input->post("id");
		$transaction_id = $this->input->post("transaction_id");
		$reason = $this->input->post("reason");

		if ($id && $transaction_id) {
			if ($reason) {
				try {
					$delete = $this->item_model->cancel($id, $reason, $transaction_id, $this->uid);

					if ($delete) {
						//display result
						$result = $this->item_model->search_by_transaction($transaction_id);
						$data["logs"] = $this->shared_model->get_logs($transaction_id);

						$data["result"] = $result;
					} else {
						$errors["error"] = "Error: Saving Failed, Please Try Again!";
					}
				} catch (Exception $ex) {
					$errors["error"] = $ex->getMessage();
				}
			} else {
				$errors["error"] = "Error: All fields with * are required!";
			}
		} else {
			$errors["error"] = "Error: Critical Error Encountered!";
		}


		if (!empty($errors)) {
			$data["success"] = false;
			$data["errors"] = $errors;
		} else {
			$data["success"] = true;
		}

		echo json_encode($data);
	}

	//ITEM COMPLETE
	function item_complete()
	{
		$errors = [];
		$data = [];

		$id = $this->input->post("id");
		$transaction_id = $this->input->post("transaction_id");

		if ($id && $transaction_id) {

				try {
					$complete = $this->item_model->complete($id, $transaction_id, $this->uid);

					if ($complete) {
						//display result
						$result = $this->item_model->search_by_transaction($transaction_id);
						$data["logs"] = $this->shared_model->get_logs($transaction_id);

						$data["result"] = $result;
					} else {
						$errors["error"] = "Error: Updating Failed, Please Try Again!";
					}
				} catch (Exception $ex) {
					$errors["error"] = $ex->getMessage();
				}
		} else {
			$errors["error"] = "Error: Critical Error Encountered!";
		}


		if (!empty($errors)) {
			$data["success"] = false;
			$data["errors"] = $errors;
		} else {
			$data["success"] = true;
		}

		echo json_encode($data);
	}

	//ITEM WORKING
	function item_working()
	{
		$errors = [];
		$data = [];

		$id = $this->input->post("id");
		$transaction_id = $this->input->post("transaction_id");

		if ($id && $transaction_id) {

				try {
					$working = $this->item_model->working($id, $transaction_id, $this->uid);

					if ($working) {
						//display result
						$result = $this->item_model->search_by_transaction($transaction_id);
						$data["logs"] = $this->shared_model->get_logs($transaction_id);

						$data["result"] = $result;
					} else {
						$errors["error"] = "Error: Updating Failed, Please Try Again!";
					}
				} catch (Exception $ex) {
					$errors["error"] = $ex->getMessage();
				}
		} else {
			$errors["error"] = "Error: Critical Error Encountered!";
		}


		if (!empty($errors)) {
			$data["success"] = false;
			$data["errors"] = $errors;
		} else {
			$data["success"] = true;
		}

		echo json_encode($data);
	}

	// Lab Result Methods
	function lab_upload()
	{
		$result = array("success" => false);
		
		try {
			$item_id = $this->input->post("item_id");
			$transaction_id = $this->input->post("transaction_id");
			$test_name = $this->input->post("test_name");
			$test_date = $this->input->post("test_date");
			$lab_provider = $this->input->post("lab_provider");
			$notes = $this->input->post("notes");
			
			if (!$item_id || !$transaction_id || !$test_name || !$test_date) {
				throw new Exception("Required fields are missing");
			}
			
			// Create upload directory if it doesn't exist
			$upload_path = "./upload/lab_results/" . $transaction_id . "/" . $item_id . "/";
			if (!is_dir($upload_path)) {
				mkdir($upload_path, 0755, true);
			}
			
			// Configure upload
			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = 'pdf|jpg|jpeg|png|doc|docx';
			$config['max_size'] = 10240; // 10MB
			$config['encrypt_name'] = true;
			
			$this->load->library('upload', $config);
			
			$uploaded_files = array();
			$files = $_FILES['lab_files'];
			
			// Handle multiple file upload
			for ($i = 0; $i < count($files['name']); $i++) {
				if ($files['error'][$i] == 0) {
					$_FILES['single_file']['name'] = $files['name'][$i];
					$_FILES['single_file']['type'] = $files['type'][$i];
					$_FILES['single_file']['tmp_name'] = $files['tmp_name'][$i];
					$_FILES['single_file']['error'] = $files['error'][$i];
					$_FILES['single_file']['size'] = $files['size'][$i];
					
					if ($this->upload->do_upload('single_file')) {
						$upload_data = $this->upload->data();
						$uploaded_files[] = array(
							'original_name' => $files['name'][$i],
							'file_name' => $upload_data['file_name'],
							'file_path' => $upload_path . $upload_data['file_name']
						);
					} else {
						throw new Exception("Upload error: " . $this->upload->display_errors());
					}
				}
			}
			
			if (empty($uploaded_files)) {
				throw new Exception("No files were uploaded successfully");
			}
			
			// Save to database
			$lab_data = array(
				'transaction_id' => $transaction_id,
				'item_id' => $item_id,
				'test_name' => $test_name,
				'test_date' => $test_date,
				'lab_provider' => $lab_provider,
				'notes' => $notes,
				'files' => json_encode($uploaded_files),
				'created_by' => $this->uid,
				'created_at' => date('Y-m-d H:i:s')
			);
			
			$this->db->insert('lab_results', $lab_data);
			
			if ($this->db->affected_rows() > 0) {
				$result["success"] = true;
				$result["message"] = "Laboratory results uploaded successfully";
				
				// Write to log
				$this->shared_model->write_to_log("Lab Upload", $this->uid, $transaction_id, 0, "", "", "Laboratory result uploaded: " . $test_name);
			} else {
				throw new Exception("Failed to save lab result to database");
			}
			
		} catch (Exception $ex) {
			$result["error"] = $ex->getMessage();
		}
		
		echo json_encode($result);
	}
	
	function lab_save_digital()
	{
		$result = array("success" => false);
		
		try {
			$item_id = $this->input->post("item_id");
			$transaction_id = $this->input->post("transaction_id");
			$test_name = $this->input->post("test_name");
			$test_date = $this->input->post("test_date");
			$lab_provider = $this->input->post("lab_provider");
			$performed_by = $this->input->post("performed_by");
			$interpretation = $this->input->post("interpretation");
			$notes = $this->input->post("notes");
			
			// Process lab parameters from result sets
			$result_set_ids = $this->input->post("result_set_id");
			$result_values = $this->input->post("result_value");
			
			if (!$item_id || !$transaction_id || !$test_name || !$test_date) {
				throw new Exception("Required fields are missing");
			}
			
			// Build lab parameters JSON from result sets
			$lab_parameters = array();
			if ($result_set_ids && is_array($result_set_ids)) {
				for ($i = 0; $i < count($result_set_ids); $i++) {
					if (!empty($result_set_ids[$i]) && isset($result_values[$i]) && $result_values[$i] !== '') {
						// Get result set details
						$this->db->select('result_label, unit, reference');
						$this->db->from('result_sets');
						$this->db->where('id', $result_set_ids[$i]);
						$result_set = $this->db->get()->row_array();
						
						if ($result_set) {
							$lab_parameters[] = array(
								'result_set_id' => $result_set_ids[$i],
								'name' => $result_set['result_label'],
								'value' => $result_values[$i],
								'unit' => $result_set['unit'],
								'reference_range' => $result_set['reference']
							);
						}
					}
				}
			}
			
			// Save to database
			$lab_data = array(
				'transaction_id' => $transaction_id,
				'item_id' => $item_id,
				'entry_type' => 'digital',
				'test_name' => $test_name,
				'test_date' => $test_date,
				'lab_provider' => $lab_provider,
				'performed_by' => $performed_by,
				'lab_parameters' => json_encode($lab_parameters),
				'interpretation' => $interpretation,
				'notes' => $notes,
				'created_by' => $this->uid,
				'created_at' => date('Y-m-d H:i:s')
			);
			
			$this->db->insert('lab_results', $lab_data);
			
			if ($this->db->affected_rows() > 0) {
				$result["success"] = true;
				$result["message"] = "Digital laboratory results saved successfully";
				$result["lab_id"] = $this->db->insert_id();
				
				// Write to log
				$this->shared_model->write_to_log("Lab Digital Entry", $this->uid, $transaction_id, 0, "", "", "Digital laboratory result entered: " . $test_name);
			} else {
				throw new Exception("Failed to save digital lab result to database");
			}
			
		} catch (Exception $ex) {
			$result["error"] = $ex->getMessage();
		}
		
		echo json_encode($result);
	}
	
	function lab_get_result_sets()
	{
		$result = array("success" => false);
		
		try {
			$product_id = $this->input->post("product_id");
			
			if (!$product_id) {
				throw new Exception("Product ID is required");
			}
			
			$this->db->select('id, result_label, reference, unit, description');
			$this->db->from('result_sets');
			$this->db->where('product_id', $product_id);
			$this->db->where('deleted_at IS NULL');
			$this->db->order_by('result_label', 'ASC');
			
			$query = $this->db->get();
			$result_sets = $query->result_array();
			
			$result["success"] = true;
			$result["result_sets"] = $result_sets;
			
		} catch (Exception $ex) {
			$result["error"] = $ex->getMessage();
		}
		
		echo json_encode($result);
	}
	
	function lab_save_result_set()
	{
		$result = array("success" => false);
		
		try {
			$product_id = $this->input->post("product_id");
			$result_label = trim($this->input->post("result_label"));
			$unit = trim($this->input->post("unit"));
			$reference = trim($this->input->post("reference"));
			$description = trim($this->input->post("description"));
			
			// Validation
			if (!$product_id) {
				throw new Exception("Product ID is required");
			}
			
			if (!$result_label) {
				throw new Exception("Parameter name is required");
			}
			
			// Check if product exists
			$this->db->select('id');
			$this->db->from('products');
			$this->db->where('id', $product_id);
			$this->db->where('deleted_at IS NULL');
			$product_query = $this->db->get();
			
			if (!$product_query) {
				throw new Exception("Database error: " . $this->db->error()['message']);
			}
			
			$product_exists = $product_query->row();
			
			if (!$product_exists) {
				throw new Exception("Product not found");
			}
			
			// Check for duplicate result label for the same product
			$this->db->select('id');
			$this->db->from('result_sets');
			$this->db->where('product_id', $product_id);
			$this->db->where('result_label', $result_label);
			$this->db->where('deleted_at IS NULL');
			$duplicate_query = $this->db->get();
			
			if (!$duplicate_query) {
				throw new Exception("Database error: " . $this->db->error()['message']);
			}
			
			$duplicate = $duplicate_query->row();
			
			if ($duplicate) {
				throw new Exception("A parameter with this name already exists for this product");
			}
			
			// Insert new result set
			$data = array(
				'product_id' => $product_id,
				'result_label' => $result_label,
				'unit' => $unit,
				'reference' => $reference,
				'description' => $description,
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => $this->uid
			);
			
			$insert_result = $this->db->insert('result_sets', $data);
			
			if (!$insert_result) {
				throw new Exception("Database error: " . $this->db->error()['message']);
			}
			
			if ($this->db->affected_rows() > 0) {
				$result["success"] = true;
				$result["result_set_id"] = $this->db->insert_id();
				$result["message"] = "Result parameter added successfully";
			} else {
				throw new Exception("Failed to save result parameter");
			}
			
		} catch (Exception $ex) {
			$result["error"] = $ex->getMessage();
		}
		
		echo json_encode($result);
	}
	
	function lab_delete_result_set()
	{
		$result = array("success" => false);
		
		try {
			$result_set_id = $this->input->post("result_set_id");
			
			if (!$result_set_id) {
				throw new Exception("Result set ID is required");
			}
			
			// Check if result set exists and user has permission
			$this->db->select('id, product_id, result_label');
			$this->db->from('result_sets');
			$this->db->where('id', $result_set_id);
			$this->db->where('deleted_at IS NULL');
			$result_set = $this->db->get()->row();
			
			if (!$result_set) {
				throw new Exception("Result parameter not found");
			}
			
			// Check if this result set is being used in any lab results
			$this->db->select('COUNT(*) as count');
			$this->db->from('lab_result_parameters');
			$this->db->where('result_set_id', $result_set_id);
			$usage = $this->db->get();
			$usage_count = $usage ? $usage->row()->count : 0;
			
			if ($usage_count > 0) {
				// Get additional details about the usage
				$this->db->select('lr.test_name, lr.test_date, CONCAT(p.fname, " ", p.lname) as patient_name');
				$this->db->from('lab_result_parameters lrp');
				$this->db->join('lab_results lr', 'lr.id = lrp.lab_result_id', 'left');
				$this->db->join('transaction_items ti', 'ti.id = lr.item_id', 'left');
				$this->db->join('transactions t', 't.id = ti.transaction_id', 'left');
				$this->db->join('patients p', 'p.id = t.patient_id', 'left');
				$this->db->where('lrp.result_set_id', $result_set_id);
				$this->db->limit(3); // Show only first 3 examples
				$usage_details = $this->db->get()->result();
				
				$error_message = "Cannot delete this parameter because it is used in " . $usage_count . " existing lab result" . ($usage_count > 1 ? "s" : "");
				
				if (!empty($usage_details)) {
					$error_message .= ". Examples: ";
					$examples = array();
					foreach ($usage_details as $detail) {
						$examples[] = $detail->test_name . " (" . $detail->patient_name . " - " . date('M d, Y', strtotime($detail->test_date)) . ")";
					}
					$error_message .= implode(", ", $examples);
					if ($usage_count > 3) {
						$error_message .= " and " . ($usage_count - 3) . " more";
					}
				}
				
				throw new Exception($error_message);
			}
			
			// Soft delete the result set
			$data = array(
				'deleted_at' => date('Y-m-d H:i:s'),
				'deleted_by' => $this->uid,
				'deleted_reason' => 'User deleted from lab modal'
			);
			
			$this->db->where('id', $result_set_id);
			$this->db->update('result_sets', $data);
			
			if ($this->db->affected_rows() > 0) {
				$result["success"] = true;
				$result["message"] = "Result parameter deleted successfully";
			} else {
				throw new Exception("Failed to delete result parameter");
			}
			
		} catch (Exception $ex) {
			$result["error"] = $ex->getMessage();
		}
		
		echo json_encode($result);
	}
	
	function lab_print_digital()
	{
		$result = array("success" => false);
		
		try {
			$lab_id = $this->input->post("lab_id");
			
			if (!$lab_id) {
				throw new Exception("Lab ID is required");
			}
			
			// Get lab result data
			$this->db->select('lr.*, t.patient_name, p.product_name');
			$this->db->from('lab_results lr');
			$this->db->join('transaction_items ti', 'ti.id = lr.item_id');
			$this->db->join('transactions t', 't.id = ti.transaction_id');
			$this->db->join('products p', 'p.id = ti.product_id');
			$this->db->where('lr.id', $lab_id);
			$this->db->where('lr.entry_type', 'digital');
			$this->db->where('lr.deleted_at IS NULL');
			
			$query = $this->db->get();
			$lab_result = $query->row_array();
			
			if (!$lab_result) {
				throw new Exception("Lab result not found");
			}
			
			// Decode lab parameters
			$lab_parameters = json_decode($lab_result['lab_parameters'], true);
			if (!$lab_parameters) {
				$lab_parameters = array();
			}
			
			// Get clinic information
			$clinic_name = $this->config->item('company_name') ?: 'Medical Clinic';
			$clinic_address = $this->config->item('company_address') ?: '';
			$clinic_phone = $this->config->item('company_phone') ?: '';
			
			// Also save lab parameters to the lab_result_parameters table if they don't exist
			foreach ($lab_parameters as $param) {
				if (isset($param['result_set_id'])) {
					// Check if parameter already exists
					$this->db->select('id');
					$this->db->from('lab_result_parameters');
					$this->db->where('lab_result_id', $lab_id);
					$this->db->where('result_set_id', $param['result_set_id']);
					$existing = $this->db->get()->row();
					
					if (!$existing) {
						// Insert parameter
						$param_data = array(
							'lab_result_id' => $lab_id,
							'result_set_id' => $param['result_set_id'],
							'result_value' => $param['value'],
							'created_at' => date('Y-m-d H:i:s'),
							'created_by' => $this->uid
						);
						$this->db->insert('lab_result_parameters', $param_data);
					}
				}
			}
			
			$data = array(
				'lab_data' => $lab_result,
				'lab_parameters' => $lab_parameters,
				'clinic_name' => $clinic_name,
				'clinic_address' => $clinic_address,
				'clinic_phone' => $clinic_phone,
				'app_title' => $this->app_title,
				'transaction_no' => str_pad($lab_result['transaction_id'], 5, "0", STR_PAD_LEFT)
			);
			
			$this->load->view('current_transaction/lab_print_template', $data);
			
		} catch (Exception $ex) {
			show_error("Error loading lab result: " . $ex->getMessage(), 500);
		}
	}
	
	function lab_list()
	{
		$result = array("success" => false);
		
		try {
			$item_id = $this->input->post("item_id");
			
			if (!$item_id) {
				throw new Exception("Item ID is required");
			}
			
			$this->db->select('*');
			$this->db->from('lab_results');
			$this->db->where('item_id', $item_id);
			$this->db->where('deleted_at IS NULL');
			$this->db->order_by('created_at', 'DESC');
			
			$query = $this->db->get();
			$results = $query->result_array();
			
			$result["success"] = true;
			$result["data"] = $results;
			
		} catch (Exception $ex) {
			$result["error"] = $ex->getMessage();
		}
		
		echo json_encode($result);
	}
}
