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
					$this->load->model("data_client_model");
					$this->load->model("data_charging_type_model");
					$this->load->model("data_insurance_model");
					$this->load->model("data_uom_model");
					$this->load->model("data_location_model");
					$this->load->model("data_queue_model");

					$this->load->model("data_product_model");
					$this->load->model("data_package_model");

					$this->load->model("item_model");

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
			$result = $this->main_model->search($search, [], $this->uid);
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
			$result = $this->main_model->advance_search($form_data, $this->uid);
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
			$status_ids = array(2, 3, 4);

			$result = $this->main_model->search("", $status_ids, $this->uid);
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
		$transaction_id = $this->session->flashdata("transaction_id");

		if ($transaction_id) {
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

				$data["divisions"] = $this->data_division_model->search();
				$data["departments"] = $this->data_department_model->search();
				$data["uoms"] = $this->data_uom_model->search();
				$data["types"] = $this->data_type_model->search();
				$data["transaction_id"] = $transaction_id;
				$data["transaction_no"] = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
				$data["trails"] = $this->shared_model->get_logs($transaction_id);

				$this->load->view("current_transaction/new", $data);
			} else {
				$this->default_error_msg;
			}
		} else {
			redirect(base_url() . "current_transaction");
		}
	}

	function auto_save()
	{
		$errors = [];

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
				$this->shared_model->write_to_log("Update", $this->uid, $id, 0, "", "", $log_remarks);
			} catch (Exception $ex) {
				$errors["error"] = $ex->getMessage();
			}
		}
	}

	// function attachment_add()
	// {
	// 	$transaction_id = $this->input->post("transaction_id_new");

	// 	if ($transaction_id) {
	// 		$filename = $_FILES["attachment_update"]["name"];

	// 		if ($filename) {

	// 			$upload = $this->do_upload("attachment_update", $transaction_id);

	// 			if (!$upload["error"]) {
	// 				echo json_encode($this->attachment_list($transaction_id));

	// 				//write to log
	// 				$log_remarks = "Attachment : {$filename}";
	// 				$this->shared_model->write_to_log("Add Attachment", $this->uid, $transaction_id, 0, "", "", $log_remarks);
	// 			}
	// 		}
	// 	}
	// }

	// function attachment_remove()
	// {
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$filename = $this->input->post("filename");
	// 	$file = "./upload/" . $transaction_id . "/" . $filename;

	// 	if ($transaction_id && $filename) {
	// 		if (file_exists($file)) {
	// 			if (!unlink($file)) {
	// 				echo "Error: Unable to delete attachment!";
	// 			} else {
	// 				//write to log
	// 				$log_remarks = "Attachment : {$filename}";
	// 				$this->shared_model->write_to_log("Removed Attachment", $this->uid, $transaction_id, 0, "", "", $log_remarks);
	// 			}
	// 		} else {
	// 			echo "Error: File does not exist!";
	// 		}
	// 	} else {
	// 		echo $this->default_error_msg;
	// 	}
	// }

	// private function attachment_list($transaction_id)
	// {
	// 	$path = "./upload/" . $transaction_id;
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

						$data["trans_types"] = $this->data_trans_type_model->search();
						$data["patients"] = $this->data_patient_model->search("");
						$data["payment_methods"] = $this->data_payment_method_model->search();
						$data["clients"] = $this->data_client_model->search();
						$data["charging_types"] = $this->data_charging_type_model->search();
						$data["insurances"] = $this->data_insurance_model->search();
						$data["doctors"] = $this->shared_model->doctors();
						$data["locations"] = $this->data_location_model->search();
						$data["queues"] = $this->data_queue_model->search();

						$data["products"] = $this->data_product_model->search();
						$data["packages"] = $this->data_package_model->search();

						$data["trails"] = $this->shared_model->get_logs($transaction_id);

						$items = $this->item_model->search_by_transaction($transaction_id);

						$data["items"] = $items;
						$this->load->view("current_transaction/view", $data);
					} else {
						$this->default_error_msg;
					}
				} else {
					$this->session->set_flashdata("error", "Error: Trans no. " . str_pad($transaction_id, 5, "0", STR_PAD_LEFT) . " is unavailable!");
					redirect(base_url() . "current_transaction");
				}
			} catch (Exception $ex) {
				$this->session->set_flashdata("error", $ex->getMessage());
				redirect(base_url() . "current_transaction");
			}
		} else {
			$this->session->set_flashdata("error", "Error: Critical Error Encountered!");
		}
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

	function item_update()
	{
		$errors = [];
		$data = [];

		$form_data = $this->input->post();
		$transaction_id = !empty($form_data["transaction_id_item_update"]) ? intval($form_data["transaction_id_item_update"]) : 0;
		$id = !empty($form_data["id_item_update"]) ? intval($form_data["id_item_update"]) : 0;
		$qty = !empty($form_data["qty_item_update"]) ? intval($form_data["qty_item_update"]) : 0;
		$uom_id = !empty($form_data["uom_id_item_update"]) ? intval($form_data["uom_id_item_update"]) : 0;
		$type_id = !empty($form_data["type_id_item_update"]) ? intval($form_data["type_id_item_update"]) : 0;
		$description = !empty($form_data["description_item_update"]) ? $form_data["description_item_update"] : "";

		if ($transaction_id && $id) {
			if ($qty && $uom_id && $type_id && $description) {
				try {
					$save = $this->item_model->update($id, $form_data, $this->uid);

					if ($save) {
						//write to log
						$log_remarks = str_pad($id, 5, "0", STR_PAD_LEFT) . " : {$qty} x {$description}";
						$this->shared_model->write_to_log("Update Item", $this->uid, $transaction_id, 0, "", "", $log_remarks);

						//display result
						$result = $this->item_model->search_by_transaction($transaction_id);
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

	// private function do_upload($file, $transaction_id)
	// {
	// 	$result = array("error" => false);

	// 	//prepare upload path
	// 	if (!file_exists("./upload/" . $transaction_id)) {
	// 		mkdir("./upload/" . $transaction_id, 0777, true);
	// 	}

	// 	$config["upload_path"]          = "./upload/{$transaction_id}/";
	// 	$config["allowed_types"]        = "txt|pdf|gif|jpg|png|doc|docx|xls|xlsx|ppt|pptx";
	// 	$config["max_size"]             = 10000;
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

	//transactions
	function cancel()
	{
		$transaction_id = $this->input->post("transaction_id");
		$reason = $this->input->post("reason");

		if ($transaction_id) {
			if ($reason) {
				try {
					//$check = $this->main_model->cancel_check($transaction_id);

					//if ($check) {

					$save = $this->main_model->cancel($transaction_id, $reason, $this->uid);

					if ($save) {
						$transaction_no = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
						$this->session->set_flashdata("message", $transaction_no . " has been cancelled!");

						echo $transaction_no;
					} else {
						echo "Error: Failed! Please try again!";
					}
					//} else {
					//	echo "Error: Some items are not cancellable, you need to cancel the item one by one!";
					//}
				} catch (Exception $ex) {
					echo $ex->getMessage();
				}
			} else {
				echo "Error: Please provide reason!";
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	// function for_dept_approval()
	// {
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$approver_id = $this->input->post("approver_id");
	// 	$approver_name = $this->input->post("approver_name");

	// 	if ($transaction_id) {
	// 		if ($approver_id) {
	// 			try {
	// 				$save = $this->main_model->for_dept_approval($transaction_id, $approver_id, $approver_name, $this->uid);
	// 				if ($save) {
	// 					$transaction_no = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 					$link = base_url() . "approval_dept_approval/view/" . $transaction_id;

	// 					//get approver details
	// 					$approver_details = $this->shared_model->get_user_details($approver_id);

	// 					if ($approver_details) {
	// 						//send mail
	// 						$mail_msg = [];
	// 						$mail_msg["to"] = $approver_details->email;
	// 						$mail_msg["subject"] = "[RMS] {$transaction_no} FOR DEPT APPROVAL";

	// 						$msg = "Dear " . ucwords($approver_details->fname) . ",\n\n";
	// 						$msg .= "<a href='{$link}'>{$transaction_no}</a> has been sent to you for approval. Please login to your account to review the request. \n\n";
	// 						$msg .= "Link: <a href='{$link}'>{$link}</a>\n\n\n";
	// 						$msg .= "This is system auto-generated message. Please DO NOT reply.";

	// 						$mail_msg["message"] = nl2br($msg);

	// 						$send_error = mail_send($mail_msg);
	// 						if ($send_error) {
	// 							echo $send_error;
	// 						}
	// 					}

	// 					$this->session->set_flashdata("message", $transaction_no . " has been sent for Dept Approval! - {$approver_name}");

	// 					echo $transaction_no;
	// 				} else {
	// 					echo "Error: Send failed! Try again later!";
	// 				}
	// 			} catch (Exception $ex) {
	// 				echo $ex->getMessage();
	// 			}
	// 		} else {
	// 			echo "Error: Please select approver!";
	// 		}
	// 	} else {
	// 		echo $this->default_error_msg;
	// 	}
	// }

	// function transfer_dept_approver()
	// {
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$approver_id = $this->input->post("approver_id");
	// 	$approver_name = $this->input->post("approver_name");

	// 	if ($transaction_id) {
	// 		if ($approver_id) {
	// 			try {
	// 				$save = $this->main_model->transfer_dept_approver($transaction_id, $approver_id, $approver_name, $this->uid);
	// 				if ($save) {
	// 					$transaction_no = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 					$link = base_url() . "approval_dept_approval/view/" . $transaction_id;

	// 					//get approver details
	// 					$approver_details = $this->shared_model->get_user_details($approver_id);

	// 					if ($approver_details) {
	// 						//send mail
	// 						$mail_msg = [];
	// 						$mail_msg["to"] = $approver_details->email;
	// 						$mail_msg["subject"] = "[RMS] {$transaction_no} FOR DEPT APPROVAL";

	// 						$msg = "Dear " . ucwords($approver_details->fname) . "\n\n";
	// 						$msg .= "<a href='{$link}'>{$transaction_no}</a> has been sent to you for approval. Please login to your account to review the request. \n\n";
	// 						$msg .= "Link: <a href='{$link}'>{$link}</a>\n\n\n";
	// 						$msg .= "This is system auto-generated message. Please DO NOT reply.";

	// 						$mail_msg["message"] = nl2br($msg);

	// 						$send_error = mail_send($mail_msg);
	// 						if ($send_error) {
	// 							echo $send_error;
	// 						}
	// 					}

	// 					$this->session->set_flashdata("message", $transaction_no . " has been transferred to new Dept Approver - {$approver_name}");

	// 					echo $transaction_no;
	// 				} else {
	// 					echo "Error: Send failed! Try again later!";
	// 				}
	// 			} catch (Exception $ex) {
	// 				echo $ex->getMessage();
	// 			}
	// 		} else {
	// 			echo "Error: Please select approver!";
	// 		}
	// 	} else {
	// 		echo $this->default_error_msg;
	// 	}
	// }

	// function for_gm_approval()
	// {
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$approver_id = $this->input->post("approver_id");
	// 	$approver_name = $this->input->post("approver_name");

	// 	if ($transaction_id) {
	// 		if ($approver_id) {
	// 			try {
	// 				$save = $this->main_model->for_gm_approval($transaction_id, $approver_id, $approver_name, $this->uid);
	// 				if ($save) {
	// 					$transaction_no = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 					$link = base_url() . "approval_gm_approval/view/" . $transaction_id;

	// 					//get approver details
	// 					$approver_details = $this->shared_model->get_user_details($approver_id);

	// 					if ($approver_details) {
	// 						//send mail
	// 						$mail_msg = [];
	// 						$mail_msg["to"] = $approver_details->email;
	// 						$mail_msg["subject"] = "[RMS] {$transaction_no} FOR GM APPROVAL";

	// 						$msg = "Dear " . ucwords($approver_details->fname) . "\n\n";
	// 						$msg .= "<a href='{$link}'>{$transaction_no}</a> has been sent to you for approval. Please login to your account to review the request. \n\n";
	// 						$msg .= "Link: <a href='{$link}'>{$link}</a>\n\n\n";
	// 						$msg .= "This is system auto-generated message. Please DO NOT reply.";

	// 						$mail_msg["message"] = nl2br($msg);

	// 						$send_error = mail_send($mail_msg);
	// 						if ($send_error) {
	// 							echo $send_error;
	// 						}
	// 					}

	// 					$this->session->set_flashdata("message", $transaction_no . " has been sent for GM Approval! - {$approver_name}");

	// 					echo $transaction_no;
	// 				} else {
	// 					echo "Error: Send failed! Try again later!";
	// 				}
	// 			} catch (Exception $ex) {
	// 				echo $ex->getMessage();
	// 			}
	// 		} else {
	// 			echo "Error: Please select approver!";
	// 		}
	// 	} else {
	// 		echo $this->default_error_msg;
	// 	}
	// }

	// function transfer_gm_approver()
	// {
	// 	$transaction_id = $this->input->post("transaction_id");
	// 	$approver_id = $this->input->post("approver_id");
	// 	$approver_name = $this->input->post("approver_name");

	// 	if ($transaction_id) {
	// 		if ($approver_id) {
	// 			try {
	// 				$save = $this->main_model->transfer_gm_approver($transaction_id, $approver_id, $approver_name, $this->uid);
	// 				if ($save) {
	// 					$transaction_no = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 					$link = base_url() . "approval_gm_approval/view/" . $transaction_id;

	// 					//get approver details
	// 					$approver_details = $this->shared_model->get_user_details($approver_id);

	// 					if ($approver_details) {
	// 						//send mail
	// 						$mail_msg = [];
	// 						$mail_msg["to"] = $approver_details->email;
	// 						$mail_msg["subject"] = "[RMS] {$transaction_no} FOR GM APPROVAL";

	// 						$msg = "Dear " . ucwords($approver_details->fname) . "\n\n";
	// 						$msg .= "<a href='{$link}'>{$transaction_no}</a> has been sent to you for approval. Please login to your account to review the request. \n\n";
	// 						$msg .= "Link: <a href='{$link}'>{$link}</a>\n\n\n";
	// 						$msg .= "This is system auto-generated message. Please DO NOT reply.";

	// 						$mail_msg["message"] = nl2br($msg);

	// 						$send_error = mail_send($mail_msg);
	// 						if ($send_error) {
	// 							echo $send_error;
	// 						}
	// 					}

	// 					$this->session->set_flashdata("message", $transaction_no . " has been transferred to GM Approver - {$approver_name}");

	// 					echo $transaction_no;
	// 				} else {
	// 					echo "Error: Send failed! Try again later!";
	// 				}
	// 			} catch (Exception $ex) {
	// 				echo $ex->getMessage();
	// 			}
	// 		} else {
	// 			echo "Error: Please select approver!";
	// 		}
	// 	} else {
	// 		echo $this->default_error_msg;
	// 	}
	// }

	// function export_pdf($transaction_id)
	// {
	// 	if ($transaction_id) {
	// 		try {
	// 			$record = $this->main_model->view($transaction_id, $this->uid);

	// 			if ($record) {
	// 				$data["transaction_no"] = str_pad($transaction_id, 5, "0", STR_PAD_LEFT);
	// 				$data["app_name"] = $this->app_name;
	// 				$data["company_name"] = $this->company_name;
	// 				$data["company_address"] = $this->company_address;
	// 				$data["company_contact"] = $this->company_contact;
	// 				$data["record"] = $record;
	// 				$data["items"] = $this->item_model->search_by_transaction($transaction_id);

	// 				$this->load->library('Pdf');
	// 				$this->load->view('pdf/export_to_pdf', $data);
	// 			} else {
	// 				$this->session->set_flashdata("error", "Error: Request no. REQ-" . str_pad($transaction_id, 5, "0", STR_PAD_LEFT) . " is unavailable!");
	// 				redirect(base_url() . "current_transaction");
	// 			}
	// 		} catch (Exception $ex) {
	// 			echo $ex->getMessage();
	// 		}
	// 	} else {
	// 		echo $this->default_error_msg;
	// 	}
	// }
}
