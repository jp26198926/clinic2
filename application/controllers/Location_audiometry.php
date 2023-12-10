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
class Location_audiometry extends Current_transaction
{
	protected $module_permission = array();
	protected $prefix; //check or update app_details table in db for session_prefix field
	protected $default_error_msg = "Error: Critical Error Encountered!";
	protected $role_id;
	protected $module = "location_audiometry";
	protected $module_description = "Audiometry";
	protected $page_name = "Audiometry";
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
	protected $location_id = 8;
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
			$result = $this->main_model->advance_search($form_data, [$this->location_id]);
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

			$result = $this->main_model->search("", $status_ids, [$this->location_id] );
			echo json_encode($result);
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	function view($transaction_id)
	{
		if ($transaction_id) {
			try {
				$record = $this->main_model->view($transaction_id, [$this->location_id]);
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

	

	// function search_active()
	// {
	// 	try {
	// 		$status_ids = array(2, 3);

	// 		$result = $this->main_model->search("", $status_ids, $this->uid);
	// 		echo json_encode($result);
	// 	} catch (Exception $ex) {
	// 		echo $ex->getMessage();
	// 	}
	// }

	// function create()
	// {
	// 	try {
	// 		$create = $this->main_model->create($this->uid);

	// 		if ($create) {
	// 			$this->session->set_flashdata("transaction_id", $create);

	// 			echo $create;

	// 			//write to log
	// 			$this->shared_model->write_to_log("Create", $this->uid, $create, 0, "", "", "Transaction has been created");
	// 		} else {
	// 			echo $this->default_error_msg;
	// 		}
	// 	} catch (Exception $ex) {
	// 		echo $ex->getMessage();
	// 	}
	// }

	// function new()
	// {
	// 	$prefix = $this->prefix;

	// 	$data["prefix"] = $prefix;
	// 	$data["app_title"] = $this->app_title;
	// 	$data["app_version"] = $this->app_version;
	// 	$data["page_name"] = $this->page_name;
	// 	$data["parent_menu"] = $this->parent_menu;
	// 	$data["module"] = $this->module;
	// 	$data["module_description"] = $this->module_description;

	// 	if ($this->cf->is_allowed_module($this->module, $prefix)) {
	// 		$data["role_id"] = $this->role_id;
	// 		$data["module_permission"] = $this->module_permission;
	// 		$data["uid"] = $this->uid;
	// 		$data["ufname"] = strtoupper($this->uname);

	// 		$data["uoms"] = $this->data_uom_model->search();
	// 		$data["trans_types"] = $this->data_trans_type_model->search();
	// 		$data["patients"] = $this->data_patient_model->search("");
	// 		$data["payment_methods"] = $this->data_payment_method_model->search();
	// 		$data["payment_types"] = $this->data_payment_type_model->search();
	// 		$data["clients"] = $this->data_client_model->search();
	// 		$data["charging_types"] = $this->data_charging_type_model->search();
	// 		$data["insurances"] = $this->data_insurance_model->search();
	// 		$data["doctors"] = $this->shared_model->doctors();
	// 		$data["locations"] = $this->data_location_model->search();
	// 		$data["queues"] = $this->data_queue_model->search();

	// 		$this->load->view("current_transaction/new", $data);
	// 	} else {
	// 		$this->default_error_msg;
	// 	}
	// }

	// function modify($transaction_id)
	// {
	// 	if ($transaction_id) {
	// 		try {
	// 			$record = $this->main_model->view($transaction_id, $this->uid);
	// 			if ($record) {
	// 				$prefix = $this->prefix;

	// 				$data["prefix"] = $prefix;
	// 				$data["app_title"] = $this->app_title;
	// 				$data["app_version"] = $this->app_version;
	// 				$data["page_name"] = $this->page_name;
	// 				$data["parent_menu"] = $this->parent_menu;
	// 				$data["module"] = $this->module;
	// 				$data["module_description"] = $this->module_description;

	// 				if ($this->cf->is_allowed_module($this->module, $prefix)) {
	// 					$data["role_id"] = $this->role_id;
	// 					$data["module_permission"] = $this->module_permission;
	// 					$data["uid"] = $this->uid;
	// 					$data["ufname"] = strtoupper($this->uname);


	// 					$data["uoms"] = $this->data_uom_model->search();
	// 					$data["transaction_id"] = $transaction_id;
	// 					$data["transaction_no"] = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 					$data["transaction_status_id"] = $record->status_id;
	// 					$data["record"] = $record;

	// 					$data["trans_types"] = $this->data_trans_type_model->search();
	// 					$data["patients"] = $this->data_patient_model->search("");
	// 					$data["payment_methods"] = $this->data_payment_method_model->search();
	// 					$data["payment_types"] = $this->data_payment_type_model->search();
	// 					$data["clients"] = $this->data_client_model->search();
	// 					$data["charging_types"] = $this->data_charging_type_model->search();
	// 					$data["insurances"] = $this->data_insurance_model->search();
	// 					$data["doctors"] = $this->shared_model->doctors();
	// 					$data["locations"] = $this->data_location_model->search();
	// 					$data["queues"] = $this->data_queue_model->search();

	// 					$data["trails"] = $this->shared_model->get_logs($transaction_id);

	// 					$this->load->view("current_transaction/modify", $data);
	// 				} else {
	// 					$this->default_error_msg;
	// 				}
	// 			} else {
	// 				$this->session->set_flashdata("error", "Error: Trans no. " . str_pad($transaction_id, 5, "0", STR_PAD_LEFT) . " is unavailable!");
	// 				redirect(base_url($this->module));
	// 			}
	// 		} catch (Exception $ex) {
	// 			$this->session->set_flashdata("error", $ex->getMessage());
	// 			redirect(base_url($this->module));
	// 		}
	// 	} else {
	// 		$this->session->set_flashdata("error", "Error: Critical Error Encountered!");
	// 	}
	// }

	// function save()
	// {
	// 	$result = array("success" => false);
	// 	$data = $this->input->post();
	// 	$dt = $this->input->post("date_new");
	// 	$trans_type_id = $this->input->post("trans_type_id_new");
	// 	$patient_id = $this->input->post("patient_id_new");
	// 	$payment_method_id = $this->input->post("payment_method_id_new");
	// 	$charging_type_id = $this->input->post("charging_type_id_new");

	// 	if ($dt && $trans_type_id && $patient_id && $payment_method_id && $charging_type_id) {
	// 		try {
	// 			$save = $this->main_model->save($data, $this->uid);
	// 			if ($save) {
	// 				$result["success"] = true;
	// 				$result["data"] = $save;

	// 				$this->session->set_flashdata("transaction_id", $save);
	// 			} else {
	// 				$result["error"] = "Unable to save, Please try again!";
	// 			}
	// 		} catch (Exception $ex) {
	// 			$result["error"] = $ex->getMessage();
	// 		}
	// 	} else {
	// 		$result["error"] = "Fields with red asterisk are required!";
	// 	}

	// 	echo json_encode($result);
	// }

	// function update()
	// {
	// 	$result = array("success" => false);
	// 	$data = $this->input->post();
	// 	$transaction_id = $this->input->post("id_update");
	// 	$dt = $this->input->post("date_update");
	// 	$trans_type_id = $this->input->post("trans_type_id_update");
	// 	$patient_id = $this->input->post("patient_id_update");
	// 	$payment_method_id = $this->input->post("payment_method_id_update");
	// 	$charging_type_id = $this->input->post("charging_type_id_update");

	// 	if ($transaction_id) {
	// 		if ($dt && $trans_type_id && $patient_id && $payment_method_id && $charging_type_id) {
	// 			try {
	// 				$save = $this->main_model->update($transaction_id, $data, $this->uid);
	// 				if ($save) {
	// 					$result["success"] = true;
	// 					$result["message"] = "Successfully Updated";
	// 				} else {
	// 					$result["error"] = "Unable to save, Please try again!";
	// 				}
	// 			} catch (Exception $ex) {
	// 				$result["error"] = $ex->getMessage();
	// 			}
	// 		} else {
	// 			$result["error"] = "Fields with red asterisk are required!";
	// 		}
	// 	} else {
	// 		$result["error"] = "Critical Error Encountered!";
	// 	}
	// 	echo json_encode($result);
	// }

	// function auto_save()
	// {
	// 	$result["success"] = false;

	// 	$id = $this->input->post("id");
	// 	$fieldname = $this->input->post("fieldname");
	// 	$fieldvalue = $this->input->post("fieldvalue");
	// 	$tag = $this->input->post("tag");
	// 	$fieldtext = $this->input->post("fieldtext");

	// 	$form_data[$fieldname] = $fieldvalue;

	// 	if ($id) {
	// 		try {
	// 			$this->main_model->update($id, $form_data, $this->uid);

	// 			//write to log
	// 			$fieldname = str_replace("_update", "", $fieldname);
	// 			$fieldname = str_replace("_id", "", $fieldname);
	// 			$fieldname = ucwords($fieldname);
	// 			$tag = strtolower($tag);

	// 			$log_remarks = str_replace("_update", "", $fieldname) . " : " . ($tag === "select" ? $fieldtext : $fieldvalue);
	// 			$save = $this->shared_model->write_to_log("Update Transaction", $this->uid, $id, 0, "", "", $log_remarks);

	// 			if ($save) {
	// 				$get_logs = $this->shared_model->get_logs($id);

	// 				$result["success"] = true;
	// 				$result["data"] = $get_logs;
	// 			}
	// 		} catch (Exception $ex) {
	// 			$result["error"] = $ex->getMessage();
	// 		}
	// 	}

	// 	echo json_encode($result);
	// }

	// function view($transaction_id)
	// {
	// 	if ($transaction_id) {
	// 		try {
	// 			$record = $this->main_model->view($transaction_id, $this->uid);
	// 			if ($record) {
	// 				$prefix = $this->prefix;

	// 				$data["prefix"] = $prefix;
	// 				$data["app_title"] = $this->app_title;
	// 				$data["app_version"] = $this->app_version;
	// 				$data["page_name"] = $this->page_name;
	// 				$data["parent_menu"] = $this->parent_menu;
	// 				$data["module"] = $this->module;
	// 				$data["module_description"] = $this->module_description;

	// 				if ($this->cf->is_allowed_module($this->module, $prefix)) {
	// 					$data["role_id"] = $this->role_id;
	// 					$data["module_permission"] = $this->module_permission;
	// 					$data["uid"] = $this->uid;
	// 					$data["ufname"] = strtoupper($this->uname);


	// 					$data["uoms"] = $this->data_uom_model->search();
	// 					$data["transaction_id"] = $transaction_id;
	// 					$data["transaction_no"] = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 					$data["transaction_status_id"] = $record->status_id;
	// 					$data["record"] = $record;
	// 					$data["disabled_field"] = $record->status_id == 2 ? "" : "disabled"; //if status not draft then disabled
	// 					$data["disabled_field2"] = ($record->status_id == 2 || $record->status_id == 3) ? "" : "disabled";

	// 					$data["trans_types"] = $this->data_trans_type_model->search();
	// 					$data["patients"] = $this->data_patient_model->search("");
	// 					$data["payment_methods"] = $this->data_payment_method_model->search();
	// 					$data["payment_types"] = $this->data_payment_type_model->search();
	// 					$data["clients"] = $this->data_client_model->search();
	// 					$data["charging_types"] = $this->data_charging_type_model->search();
	// 					$data["insurances"] = $this->data_insurance_model->search();
	// 					$data["doctors"] = $this->shared_model->doctors();
	// 					$data["locations"] = $this->data_location_model->search();
	// 					$data["queues"] = $this->data_queue_model->search();

	// 					$data["products"] = $this->data_product_model->search();
	// 					$data["pharmacy_products"] = $this->data_product_model->get_product_by_category(array(2)); //2-pharmacy
	// 					$data["packages"] = $this->data_package_model->search();

	// 					//get total charges
	// 					$data["total_charges"] = $this->main_model->get_total_charges($transaction_id);
	// 					//get total paid
	// 					$total_paid = $this->main_model->get_total_paid($transaction_id);
	// 					$data["total_paid"] = $total_paid ? $total_paid : 0;

	// 					$data["amount_due"] = $data["total_charges"] - $data["total_paid"];

	// 					$data["xray_attachments"] = $this->xray_attachment_list($transaction_id);
	// 					$data["trails"] = $this->shared_model->get_logs($transaction_id);

	// 					$items = $this->item_model->search_by_transaction($transaction_id);


	// 					$data["items"] = $items;
	// 					$this->load->view("current_transaction/view", $data);
	// 				} else {
	// 					$this->default_error_msg;
	// 				}
	// 			} else {
	// 				$this->session->set_flashdata("error", "Error: Trans no. " . str_pad($transaction_id, 5, "0", STR_PAD_LEFT) . " is unavailable!");
	// 				redirect(base_url($this->module));
	// 			}
	// 		} catch (Exception $ex) {
	// 			$this->session->set_flashdata("error", $ex->getMessage());
	// 			redirect(base_url($this->module));
	// 		}
	// 	} else {
	// 		$this->session->set_flashdata("error", "Error: Critical Error Encountered!");
	// 	}
	// }

	// //TRANS HISTORY
	// function patient_history()
	// {
	// 	$result["success"] = false;
	// 	$patient_id = $this->input->post("patient_id");
	// 	$transaction_id = $this->input->post("transaction_id");

	// 	if ($patient_id && $transaction_id) {
	// 		try {
	// 			$result["success"] = true;
	// 			$result["records"] = $this->data_patient_model->history($patient_id);
	// 		} catch (Exception $ex) {
	// 			$result["success"] = false;
	// 			$result["error"] = $ex->getMessage();
	// 		}
	// 	} else {
	// 		$result["error"] = $this->default_error_msg;
	// 	}

	// 	echo json_encode($result);
	// }

	// //TRANS CONFIRM
	// function confirm()
	// {
	// 	$transaction_id = $this->input->post("transaction_id");

	// 	if ($transaction_id) {
	// 		try {

	// 			$save = $this->main_model->confirm($transaction_id, $this->uid);

	// 			if ($save) {
	// 				$transaction_no = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 				$this->session->set_flashdata("message", $transaction_no . " has been confirmed!");

	// 				echo $transaction_no;
	// 			} else {
	// 				echo "Error: Failed! Please try again!";
	// 			}
	// 		} catch (Exception $ex) {
	// 			echo $ex->getMessage();
	// 		}
	// 	} else {
	// 		echo $this->default_error_msg;
	// 	}
	// }

	// //TRANS PRINT CHARGES
	// function print_charges($transaction_id)
	// {
	// 	if ($transaction_id) {
	// 		try {
	// 			$record = $this->main_model->view($transaction_id, $this->uid);

	// 			if ($record) {
	// 				$data["transaction_id"] = $transaction_id;
	// 				$data["transaction_no"] = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 				$data["app_name"] = $this->app_name;
	// 				$data["company_name"] = $this->company_name;
	// 				$data["company_address"] = $this->company_address;
	// 				$data["company_contact"] = $this->company_contact;
	// 				$data["record"] = $record;
	// 				$data["items"] = $this->item_model->search_by_transaction($transaction_id);
	// 				//get total paid
	// 				$total_paid = $this->main_model->get_total_paid($transaction_id);
	// 				$data["total_paid"] = $total_paid ? $total_paid : 0;

	// 				$log_remarks = "";
	// 				$this->shared_model->write_to_log("Print Charges", $this->uid, $transaction_id, 0, "", "", $log_remarks);

	// 				$this->load->library('Pdf');
	// 				$this->load->view('pdf/charges', $data);
	// 			} else {
	// 				$this->session->set_flashdata("error", "Error: Request no. REQ-" . str_pad($transaction_id, 5, "0", STR_PAD_LEFT) . " is unavailable!");
	// 				redirect(base_url($this->module));
	// 			}
	// 		} catch (Exception $ex) {
	// 			echo $ex->getMessage();
	// 		}
	// 	} else {
	// 		echo $this->default_error_msg;
	// 	}
	// }

	// //TRANS INSURANCE INVOICE
	// function print_insurance_invoice($transaction_id)
	// {
	// 	if ($transaction_id) {
	// 		try {
	// 			$record = $this->main_model->view($transaction_id, $this->uid);

	// 			if ($record) {
	// 				$data["transaction_id"] = $transaction_id;
	// 				$data["transaction_no"] = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 				$data["app_name"] = $this->app_name;
	// 				$data["company_name"] = $this->company_name;
	// 				$data["company_address"] = $this->company_address;
	// 				$data["company_contact"] = $this->company_contact;
	// 				$data["record"] = $record;
	// 				$data["items"] = $this->item_model->search_by_transaction($transaction_id);
	// 				//get total paid
	// 				$total_paid = $this->main_model->get_total_paid($transaction_id);
	// 				$data["total_paid"] = $total_paid ? $total_paid : 0;

	// 				$log_remarks = "";
	// 				$this->shared_model->write_to_log("Print Insurance Invoice", $this->uid, $transaction_id, 0, "", "", $log_remarks);

	// 				$this->load->library('Pdf');
	// 				$this->load->view('pdf/insurance_invoice', $data);
	// 			} else {
	// 				$this->session->set_flashdata("error", "Error: Request no. REQ-" . str_pad($transaction_id, 5, "0", STR_PAD_LEFT) . " is unavailable!");
	// 				redirect(base_url($this->module));
	// 			}
	// 		} catch (Exception $ex) {
	// 			echo $ex->getMessage();
	// 		}
	// 	} else {
	// 		echo $this->default_error_msg;
	// 	}
	// }

	// //TRANS CHECK PAYMENT
	// function check_payment()
	// {
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$result["success"] = false;

	// 	if ($transaction_id) {
	// 		try {
	// 			//get total charges
	// 			$total_charges = $this->main_model->get_total_charges($transaction_id);
	// 			//get total paid
	// 			$total_paid = $this->main_model->get_total_paid($transaction_id);
	// 			$total_paid = $total_paid ? $total_paid : 0;
	// 			//get payment history list
	// 			$payment_list = $this->payment_model->get_payment_list($transaction_id);

	// 			$amount_due = $total_charges - $total_paid;

	// 			$result["success"] = true;
	// 			$result["amount_due"] = $amount_due;
	// 			$result["payment_list"] = $payment_list;
	// 		} catch (Exception $ex) {
	// 			$result["error"] = $ex->getMessage();
	// 		}
	// 	} else {
	// 		$result["error"] = $this->default_error_msg;
	// 	}

	// 	echo json_encode($result);
	// }

	// //TRANS SAVE PAYMENT
	// function save_payment()
	// {
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$date = $this->input->post("date");
	// 	$amount_due = $this->input->post("amount_due");
	// 	$payment_type_id = $this->input->post("payment_type_id");
	// 	$pay_amount = $this->input->post("pay_amount");
	// 	$tender_amount = $this->input->post("tender_amount");
	// 	$change_amount = $this->input->post("change_amount");
	// 	$reference = $this->input->post("reference");

	// 	$result["success"] = false;


	// 	if ($transaction_id) {
	// 		if ($date && $payment_type_id && $pay_amount > 0.01 && $tender_amount > 0.01) {

	// 			if ($tender_amount >= $pay_amount) {
	// 				try {
	// 					$save = $this->payment_model->save_payment(
	// 						$transaction_id,
	// 						$date,
	// 						$payment_type_id,
	// 						$amount_due,
	// 						$pay_amount,
	// 						$tender_amount,
	// 						$change_amount,
	// 						$reference,
	// 						$this->uid
	// 					);
	// 					if ($save) {
	// 						//get total charges
	// 						$total_charges = $this->main_model->get_total_charges($transaction_id);
	// 						//get total paid
	// 						$total_paid = $this->main_model->get_total_paid($transaction_id);
	// 						$total_paid = $total_paid ? $total_paid : 0;

	// 						$amount_due = $total_charges - $total_paid;

	// 						$result["logs"] = $this->shared_model->get_logs($transaction_id);

	// 						$result["payment_id"] = $save;
	// 						$result["success"] = true;
	// 						$result["total_paid"] = $total_paid;
	// 						$result["amount_due"] = $amount_due;
	// 					}
	// 				} catch (Exception $ex) {
	// 					$result["error"] = $ex->getMessage();
	// 				}
	// 			} else {
	// 				$result["error"] = "Tender amount is less than the amount to be paid!";
	// 			}
	// 		} else {
	// 			$result["error"] = "All fields with asterisk are required!";
	// 		}
	// 	} else {
	// 		$result["error"] = $this->default_error_msg;
	// 	}

	// 	echo json_encode($result);
	// }

	// //TRANS CANCEL PAYMENT
	// function cancel_payment()
	// {
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$payment_id = $this->input->post("payment_id");
	// 	$result["success"] = false;

	// 	if ($transaction_id && $payment_id) {
	// 		try {
	// 			$cancel = $this->payment_model->cancel($payment_id, $transaction_id, $this->uid);

	// 			if ($cancel) {
	// 				//get total charges
	// 				$total_charges = $this->main_model->get_total_charges($transaction_id);
	// 				//get total paid
	// 				$total_paid = $this->main_model->get_total_paid($transaction_id);
	// 				$total_paid = $total_paid ? $total_paid : 0;

	// 				$payment_list = $this->payment_model->get_payment_list($transaction_id);

	// 				$amount_due = $total_charges - $total_paid;

	// 				$result["logs"] = $this->shared_model->get_logs($transaction_id);

	// 				$result["success"] = true;
	// 				$result["total_paid"] = $total_paid;
	// 				$result["amount_due"] = $amount_due;
	// 				$result["payment_list"] = $payment_list;
	// 			} else {
	// 				$result["error"] = "Unable to cancel. Please try again!";
	// 			}
	// 		} catch (Exception $ex) {
	// 			$result["error"] = $ex->getMessage();
	// 		}
	// 	} else {
	// 		$result["error"] = $this->default_error_msg;
	// 	}

	// 	echo json_encode($result);
	// }

	// //TRANS PRINT e-RECEIPT
	// function print_payment($payment_id)
	// {
	// 	if ($payment_id) {
	// 		try {
	// 			$record = $this->payment_model->search_by_id($payment_id);

	// 			if ($record) {
	// 				$data["payment_no"] = "PMT" . str_pad($payment_id, 3, "0", STR_PAD_LEFT);
	// 				$data["app_name"] = $this->app_name;
	// 				$data["company_name"] = $this->company_name;
	// 				$data["company_address"] = $this->company_address;
	// 				$data["company_contact"] = $this->company_contact;
	// 				$data["record"] = $record;
	// 				$data["items"] = $this->item_model->search_by_transaction($record->transaction_id);
	// 				//get total paid
	// 				$total_paid = $this->main_model->get_total_paid($record->transaction_id);
	// 				$data["total_paid"] = $total_paid ? $total_paid : 0;

	// 				$data["amount_paid"] = number_format($record->amount, 2, ".", ",");
	// 				$data["amount_paid_words"] = $this->cf->number_to_words($record->amount, "Kina", "Toea");

	// 				$log_remarks = "";
	// 				$this->shared_model->write_to_log("Print Payment", $this->uid, $record->transaction_id, 0, "", "", $log_remarks);

	// 				$this->load->library('Pdf');
	// 				$this->load->view('pdf/payment2', $data);
	// 			} else {
	// 				$this->session->set_flashdata("error", "Error: Critical Error Encountered!");
	// 				redirect(base_url($this->module));
	// 			}
	// 		} catch (Exception $ex) {
	// 			echo $ex->getMessage();
	// 		}
	// 	} else {
	// 		echo $this->default_error_msg;
	// 	}
	// }

	// //TRANS or SEND TO LOCATION
	// function transfer()
	// {
	// 	$result = array("success" => false);
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$location_id = $this->input->post("location_id");
	// 	$location = $this->input->post("location");

	// 	if ($transaction_id) {
	// 		try {

	// 			$save = $this->main_model->transfer($transaction_id, $location_id, $location, $this->uid);

	// 			if ($save) {
	// 				$transaction_no = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 				$this->session->set_flashdata("message", "Transaction No: " . $transaction_no . " has been transferred to " . $location);

	// 				$result["success"] = true;
	// 				$result["message"] = "Successfully transferred!";
	// 			} else {
	// 				$result["error"] = "Failed to transfer! Please try again!";
	// 			}
	// 		} catch (Exception $ex) {
	// 			$result["error"] = $ex->getMessage();
	// 		}
	// 	} else {
	// 		$result["error"] = "Critical Error Encountered!";
	// 	}

	// 	echo json_encode($result);
	// }

	// //TRANS PRESCRIPTION
	// function prescription_list()
	// {
	// 	$result["success"] = false;
	// 	$transaction_id = $this->input->post("transaction_id");

	// 	if ($transaction_id) {
	// 		try {
	// 			$result["data"] = $this->prescription_model->search_by_transaction($transaction_id);
	// 			$result["success"] = true;
	// 		} catch (Exception $ex) {
	// 			$result["error"] = $ex->getMessage();
	// 		}
	// 	} else {
	// 		$result["error"] = $this->default_error_msg;
	// 	}

	// 	echo json_encode($result);
	// }

	// function prescription_add()
	// {
	// 	$result["success"] = false;
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$product_id = $this->input->post("txt_prescription_product_id");
	// 	$qty = $this->input->post("txt_prescription_qty");
	// 	$instruction = $this->input->post("txt_prescription_instruction");
	// 	$data = $this->input->post();

	// 	if ($transaction_id) {
	// 		if ($product_id && floatval($qty) > 0 && $instruction) {
	// 			try {
	// 				$add = $this->prescription_model->add($data, $transaction_id, $this->uid);

	// 				if ($add) {
	// 					$result["data"] = $this->prescription_model->search_by_transaction($transaction_id);
	// 					$result["logs"] = $this->shared_model->get_logs($transaction_id);

	// 					$result["success"] = true;
	// 				} else {
	// 					$result["error"] = "Error: Adding failed, please try again!";
	// 				}
	// 			} catch (Exception $ex) {
	// 				$result["error"] = $ex->getMessage();
	// 			}
	// 		} else {
	// 			$result["error"] = "Error: All fields are required!";
	// 		}
	// 	} else {
	// 		$result["error"] = $this->default_error_msg;
	// 	}

	// 	echo json_encode($result);
	// }

	// function prescription_delete()
	// {
	// 	$result["success"] = false;
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$prescription_id = $this->input->post("prescription_id");
	// 	$reason = $this->input->post("reason");

	// 	if ($transaction_id && $prescription_id) {
	// 		if ($reason) {
	// 			try {
	// 				$delete = $this->prescription_model->delete($prescription_id, $transaction_id, $reason, $this->uid);

	// 				if ($delete) {
	// 					$result["data"] = $this->prescription_model->search_by_transaction($transaction_id);
	// 					$result["logs"] = $this->shared_model->get_logs($transaction_id);

	// 					$result["success"] = true;
	// 				} else {
	// 					$result["error"] = "Error: Unable to delete, please try again!";
	// 				}
	// 			} catch (Exception $ex) {
	// 				$result["error"] = $ex->getMessage();
	// 			}
	// 		} else {
	// 			$result["error"] = "Error: Please provide a reason!";
	// 		}
	// 	} else {
	// 		$result["error"] = $this->default_error_msg;
	// 	}

	// 	echo json_encode($result);
	// }

	// function prescription_print($transaction_id)
	// {
	// 	if ($transaction_id) {
	// 		try {
	// 			$record = $this->main_model->view($transaction_id, $this->uid);

	// 			if ($record) {
	// 				$data["transaction_id"] = $transaction_id;
	// 				$data["transaction_no"] = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 				$data["app_name"] = $this->app_name;
	// 				$data["company_name"] = $this->company_name;
	// 				$data["company_address"] = $this->company_address;
	// 				$data["company_contact"] = $this->company_contact;
	// 				$data["record"] = $record;
	// 				$data["items"] = $this->prescription_model->search_by_transaction($transaction_id);

	// 				$log_remarks = "";
	// 				$this->shared_model->write_to_log("Print Prescription", $this->uid, $transaction_id, 0, "", "", $log_remarks);

	// 				$this->load->library('Pdf');
	// 				$this->load->view('pdf/prescription', $data);
	// 			} else {
	// 				$this->session->set_flashdata("error", "Error: Request no. REQ-" . str_pad($transaction_id, 5, "0", STR_PAD_LEFT) . " is unavailable!");
	// 				redirect(base_url($this->module));
	// 			}
	// 		} catch (Exception $ex) {
	// 			echo $ex->getMessage();
	// 		}
	// 	} else {
	// 		echo $this->default_error_msg;
	// 	}
	// }

	// //TRANS COMPLETE
	// function complete()
	// {
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$result["success"] = false;

	// 	if ($transaction_id) {
	// 		try {
	// 			$complete = $this->main_model->complete($transaction_id, $this->uid);

	// 			if ($complete["proceed"] === true) {
	// 				$transaction_no = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 				$this->session->set_flashdata("message", $transaction_no . " has been mark as completed!");

	// 				$result["success"] = true;
	// 			} else {
	// 				$result["error"] = $complete["error"];
	// 			}
	// 		} catch (Exception $ex) {
	// 			$result["error"] = $ex->getMessage();
	// 		}
	// 	} else {
	// 		$result["error"] = $this->default_error_msg;
	// 	}

	// 	echo json_encode($result);
	// }

	// //TRANS CANCEL
	// function cancel()
	// {
	// 	$result["success"] = false;

	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$reason = $this->input->post("reason");

	// 	if ($transaction_id) {
	// 		if ($reason) {
	// 			try {

	// 				$save = $this->main_model->cancel($transaction_id, $reason, $this->uid);

	// 				if ($save["proceed"] === true) {
	// 					$transaction_no = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 					$this->session->set_flashdata("message", $transaction_no . " has been cancelled!");

	// 					$result["success"] = true;
	// 					$result["data"] = $transaction_no;
	// 				} else {
	// 					$result["error"] = $save["error"];
	// 				}
	// 			} catch (Exception $ex) {
	// 				$result["error"] = $ex->getMessage();
	// 			}
	// 		} else {
	// 			$result["error"] =  "Error: Please provide reason!";
	// 		}
	// 	} else {
	// 		$result["error"] =  $this->default_error_msg;
	// 	}

	// 	echo json_encode($result);
	// }

	// //SELECT PRODUCT
	// function select_product($transaction_id, $product_id)
	// {
	// 	if ($transaction_id && $product_id) {
	// 		try {
	// 			$result = $this->data_product_model->search_by_row($product_id);
	// 			echo json_encode($result);
	// 		} catch (Exception $ex) {
	// 			echo $ex->getMessage();
	// 		}
	// 	}
	// }

	// function xray_attachment_add()
	// {
	// 	$transaction_id = $this->input->post("transaction_id_new");
	// 	$result["success"] = false;

	// 	if ($transaction_id) {
	// 		//$filenames = $_FILES["xray_attachment_add"];
	// 		//if ($filename) {

	// 		$upload = $this->do_upload("xray_attachment_add", $transaction_id);

	// 		if (!$upload["error"]) {

	// 			//echo json_encode($this->xray_attachment_list($transaction_id));
	// 			$result["success"] = true;
	// 			$result["data"] = $this->xray_attachment_list($transaction_id);

	// 			// Retrieve and display the uploaded filenames
	// 			$file_info = $upload["upload_data"];

	// 			//checkArrayType: 0-not array, 1-1D array, 2-2D array
	// 			$array_type = $this->cf->checkArrayType($file_info);
	// 			$filenames = "";

	// 			if ($array_type == 1) {
	// 				$filenames = $file_info['file_name'];
	// 			} elseif ($array_type == 2) {
	// 				$i = 0;
	// 				foreach ($file_info as $filename) {
	// 					$i++;
	// 					$filenames .= ($i <= 1) ? $filename['file_name'] : ", " . $filename['file_name'];
	// 				}
	// 			}

	// 			//write to log
	// 			$log_remarks = "Attachment : {$filenames}";
	// 			$this->shared_model->write_to_log("Add X-Ray Attachment", $this->uid, $transaction_id, 0, "", "", $log_remarks);

	// 			//get logs from
	// 			$result["logs"] = $this->shared_model->get_logs($transaction_id);
	// 		} else {
	// 			$result["error"] = $upload["error_msg"];
	// 		}
	// 		//}
	// 	} else {
	// 		$result["error"] = $this->default_error_msg;
	// 	}

	// 	echo json_encode($result);
	// }

	// function xray_attachment_remove()
	// {
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$filename = $this->input->post("filename");
	// 	$file = "./upload/xray/" . $transaction_id . "/" . $filename;
	// 	$result["success"] = false;

	// 	if ($transaction_id && $filename) {
	// 		if (file_exists($file)) {
	// 			if (!unlink($file)) {
	// 				//echo "Error: Unable to delete attachment!";
	// 				$result["error"] = "Unable to delete attachment!";
	// 			} else {
	// 				//write to log
	// 				$log_remarks = "Attachment : {$filename}";
	// 				$this->shared_model->write_to_log("Removed X-Ray Attachment", $this->uid, $transaction_id, 0, "", "", $log_remarks);

	// 				$result["success"] = true;
	// 				//get logs from
	// 				$result["logs"] = $this->shared_model->get_logs($transaction_id);
	// 			}
	// 		} else {
	// 			//echo "Error: File does not exist!";
	// 			$result["error"] = "File does not exist!";
	// 		}
	// 	} else {
	// 		//echo $this->default_error_msg;
	// 		$result["error"] = $this->default_error_msg;
	// 	}

	// 	echo json_encode($result);
	// }

	// private function xray_attachment_list($transaction_id)
	// {
	// 	$path = "./upload/xray/" . $transaction_id;
	// 	$files = array();

	// 	if (file_exists($path)) {
	// 		$arrFiles = scandir($path);

	// 		foreach ($arrFiles as $file) {
	// 			//disregard if the result is a directory
	// 			if (!is_dir($path . "/" . $file)) {
	// 				array_push($files, $file);
	// 			}
	// 		}
	// 	}

	// 	return $files;
	// }

	// private function do_upload($file, $transaction_id)
	// {
	// 	$result = array("error" => false);

	// 	//prepare upload path
	// 	if (!file_exists("./upload/xray/" . $transaction_id)) {
	// 		mkdir("./upload/xray/" . $transaction_id, 0777, true);
	// 	}

	// 	$config["upload_path"]          = "./upload/xray/{$transaction_id}/";
	// 	$config["allowed_types"]        = "*";
	// 	//$config["max_size"]             = 10000;
	// 	// $config["max_width"]            = 1024;
	// 	// $config["max_height"]           = 768;


	// 	$this->load->library("upload", $config);

	// 	if (!$this->upload->do_upload($file)) {
	// 		$result["error"] = true;
	// 		$result["error_msg"] =  $this->upload->display_errors();
	// 	} else {
	// 		$result["upload_data"] = $this->upload->data();
	// 	}

	// 	return $result;
	// }


	// //ITEM ADD
	// function add_item()
	// {
	// 	$errors = [];
	// 	$data = [];

	// 	$form_data = $this->input->post();
	// 	$transaction_id = $form_data["transaction_id"];
	// 	$product_id = $form_data["product_id"];
	// 	$qty = $form_data["qty"];

	// 	if ($transaction_id && $product_id && $qty) {
	// 		try {
	// 			$add = $this->item_model->add($form_data, $this->uid);

	// 			if ($add) {
	// 				//display result
	// 				$result = $this->item_model->search_by_transaction($transaction_id);
	// 				$data["logs"] = $this->shared_model->get_logs($transaction_id);

	// 				$data["result"] = $result;
	// 			} else {
	// 				$errors["error"] = "Error: Saving Failed, Please Try Again!";
	// 			}
	// 		} catch (Exception $ex) {
	// 			$errors["error"] = $ex->getMessage();
	// 		}
	// 	} else {
	// 		$errors["error"] = "Error: All fields with * are required!";
	// 	}

	// 	if (!empty($errors)) {
	// 		$data["success"] = false;
	// 		$data["errors"] = $errors;
	// 	} else {
	// 		$data["success"] = true;
	// 	}

	// 	echo json_encode($data);
	// }

	// //ITEM INFO
	// function item_search_row()
	// {
	// 	$id = intval($this->input->post("id"));
	// 	if ($id) {
	// 		try {
	// 			$info = $this->item_model->search_by_row($id);
	// 			if ($info) {
	// 				echo json_encode($info);
	// 			} else {
	// 				echo "Error: No Record Found!";
	// 			}
	// 		} catch (Exception $ex) {
	// 			echo $ex->getMessage();
	// 		}
	// 	} else {
	// 		echo $this->default_error_msg;
	// 	}
	// }

	// //ITEM UPDATE
	// function item_update()
	// {
	// 	$result["success"] = false;
	// 	$errors = [];
	// 	$data = [];

	// 	$form_data = $this->input->post();
	// 	$transaction_id = !empty($form_data["transaction_id_item_update"]) ? intval($form_data["transaction_id_item_update"]) : 0;
	// 	$id = !empty($form_data["id_item_update"]) ? intval($form_data["id_item_update"]) : 0;

	// 	if ($transaction_id && $id) {
	// 		try {
	// 			$save = $this->item_model->update($id, $form_data, $this->uid);

	// 			if ($save) {
	// 				//display result
	// 				$result["records"] = $this->item_model->search_by_transaction($transaction_id);
	// 				$result["logs"] = $this->shared_model->get_logs($transaction_id);
	// 				$result["success"] = true;
	// 			} else {
	// 				$result["error"] = "Error: Saving Failed, Please Try Again!";
	// 			}
	// 		} catch (Exception $ex) {
	// 			$result["error"] = $ex->getMessage();
	// 		}
	// 	} else {
	// 		$result["error"] = "Error: Critical Error Encountered!";
	// 	}

	// 	echo json_encode($result);
	// }

	// //ITEM CANCEL
	// function item_cancel()
	// {
	// 	$errors = [];
	// 	$data = [];

	// 	$id = $this->input->post("id");
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$reason = $this->input->post("reason");

	// 	if ($id && $transaction_id) {
	// 		if ($reason) {
	// 			try {
	// 				$delete = $this->item_model->cancel($id, $reason, $transaction_id, $this->uid);

	// 				if ($delete) {
	// 					//display result
	// 					$result = $this->item_model->search_by_transaction($transaction_id);
	// 					$data["logs"] = $this->shared_model->get_logs($transaction_id);

	// 					$data["result"] = $result;
	// 				} else {
	// 					$errors["error"] = "Error: Saving Failed, Please Try Again!";
	// 				}
	// 			} catch (Exception $ex) {
	// 				$errors["error"] = $ex->getMessage();
	// 			}
	// 		} else {
	// 			$errors["error"] = "Error: All fields with * are required!";
	// 		}
	// 	} else {
	// 		$errors["error"] = "Error: Critical Error Encountered!";
	// 	}


	// 	if (!empty($errors)) {
	// 		$data["success"] = false;
	// 		$data["errors"] = $errors;
	// 	} else {
	// 		$data["success"] = true;
	// 	}

	// 	echo json_encode($data);
	// }

	// //ITEM COMPLETE
	// function item_complete()
	// {
	// 	$errors = [];
	// 	$data = [];

	// 	$id = $this->input->post("id");
	// 	$transaction_id = $this->input->post("transaction_id");

	// 	if ($id && $transaction_id) {

	// 		try {
	// 			$complete = $this->item_model->complete($id, $transaction_id, $this->uid);

	// 			if ($complete) {
	// 				//display result
	// 				$result = $this->item_model->search_by_transaction($transaction_id);
	// 				$data["logs"] = $this->shared_model->get_logs($transaction_id);

	// 				$data["result"] = $result;
	// 			} else {
	// 				$errors["error"] = "Error: Updating Failed, Please Try Again!";
	// 			}
	// 		} catch (Exception $ex) {
	// 			$errors["error"] = $ex->getMessage();
	// 		}
	// 	} else {
	// 		$errors["error"] = "Error: Critical Error Encountered!";
	// 	}


	// 	if (!empty($errors)) {
	// 		$data["success"] = false;
	// 		$data["errors"] = $errors;
	// 	} else {
	// 		$data["success"] = true;
	// 	}

	// 	echo json_encode($data);
	// }

	// //ITEM WORKING
	// function item_working()
	// {
	// 	$errors = [];
	// 	$data = [];

	// 	$id = $this->input->post("id");
	// 	$transaction_id = $this->input->post("transaction_id");

	// 	if ($id && $transaction_id) {

	// 		try {
	// 			$working = $this->item_model->working($id, $transaction_id, $this->uid);

	// 			if ($working) {
	// 				//display result
	// 				$result = $this->item_model->search_by_transaction($transaction_id);
	// 				$data["logs"] = $this->shared_model->get_logs($transaction_id);

	// 				$data["result"] = $result;
	// 			} else {
	// 				$errors["error"] = "Error: Updating Failed, Please Try Again!";
	// 			}
	// 		} catch (Exception $ex) {
	// 			$errors["error"] = $ex->getMessage();
	// 		}
	// 	} else {
	// 		$errors["error"] = "Error: Critical Error Encountered!";
	// 	}


	// 	if (!empty($errors)) {
	// 		$data["success"] = false;
	// 		$data["errors"] = $errors;
	// 	} else {
	// 		$data["success"] = true;
	// 	}

	// 	echo json_encode($data);
	// }
}
