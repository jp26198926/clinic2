<?php
defined('BASEPATH') or die("No direct script allowed!");

class Maintenance_settings extends CI_Controller
{
	protected $module_permission = array();
	protected $prefix; //check or update app_details table in db for session_prefix field
	protected $default_error_msg = "Error: Critical Error Encountered!";
	protected $role_id;
	protected $module = "maintenance_settings";
	protected $module_description = "Settings";
	protected $page_name = "Settings";
	protected $parent_menu = "Maintenance";
	protected $uid = 0;
	protected $uname;
	protected $app_code;
	protected $app_name;
	protected $app_title;
	protected $app_version;
	protected $company_address;
	protected $company_contact;


	function __construct()
	{

		parent::__construct();
		//date_default_timezone_set("Pacific/Port_Moresby");

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
					$this->company_address = $this->session->userdata[$prefix . '_logged_in'][$prefix . '_company_address'];
					$this->company_contact = $this->session->userdata[$prefix . '_logged_in'][$prefix . '_company_contact'];

					$this->load->model('model_settings');

					$this->load->model('authentication_m', 'a');
					$this->role_id = $this->session->userdata[$this->prefix . '_logged_in'][$this->prefix . '_role'];
					$this->module_permission = $this->a->allow_permission($this->role_id, $this->module);

					$this->load->library('custom_function', NULL, 'cf');
				}
			}
		} catch (Exception $ex) {
			//echo $ex;
			$this->session->set_userdata($this->prefix . '_to_page', current_url());
			redirect(base_url() . 'authentication');
		}
		//end of getting session prefix
	}

	function index()
	{

		$data['prefix'] = $this->prefix;
		$data['app_title'] = $this->app_title;
		$data['app_version'] = $this->app_version;
		$data['page_name'] = $this->page_name;
		$data['parent_menu'] = $this->parent_menu;
		$data['module'] = $this->module;
		$data['module_description'] = $this->module_description;

		if ($this->cf->is_allowed_module($this->module, $this->prefix)) {
			$data['role_id'] = $this->role_id;
			$data['module_permission'] = $this->module_permission;
			$data['uid'] = $this->uid;
			$data['ufname'] = $this->uname;
			$data['timezone_list'] = $this->model_settings->timezone_list();
			$data['currency_list'] = $this->model_settings->currency_list();
			$data['app_details'] = $this->model_settings->app_details();

			$this->load->view('maintenance_settings/index', $data);
		} else {
			redirect(base_url() . 'authentication');
		}
	}

	function save()
	{
		$company_code = $this->input->post("company_code");
		$company_name = $this->input->post("company_name");
		$company_address = $this->input->post("company_address");
		$company_contact = $this->input->post("company_contact");
		$contact_person = $this->input->post("contact_person");

		$smtp_crypto = strtolower($this->input->post("smtp_crypt"));
		$smtp_host = $this->input->post("smtp_host");
		$smtp_user = $this->input->post("smtp_user");
		$smtp_pass = $this->input->post("smtp_pass");
		$smtp_port = $this->input->post("smtp_port");

		$timezone_id = intval($this->input->post("timezone_id"));
		$currency_id = intval($this->input->post("currency_id"));
		$gst_percent = $this->input->post("gst_percent");
		$countdown_timer = $this->input->post("countdown_timer");

		if ($company_code && $company_name) {
			try {
				$save = $this->model_settings->save(
					$company_code,
					$company_name,
					$company_address,
					$company_contact,
					$contact_person,
					$smtp_crypto,
					$smtp_host,
					$smtp_user,
					$smtp_pass,
					$smtp_port,
					$timezone_id,
					$currency_id,
					$gst_percent,
					$countdown_timer,
					$this->uid
				);

				if ($save) {
					echo "Successfully Saved!";
				} else {
					echo "Error: Problem saving the changes, please try again!";
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo "Error: Fields with red asterisk are required!";
		}
	}

	function app_save()
	{
		$data = $this->input->post();

		if ($data) {
			try {
				$save = $this->model_settings->app_save($data, $this->uid);
				if (!$save) {
					echo "Error: Unable to save!";
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	function company_save()
	{
		$data = $this->input->post();

		if ($data) {
			try {
				$save = $this->model_settings->company_save($data, $this->uid);
				if (!$save) {
					echo "Error: Unable to save!";
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	function email_save()
	{
		$data = $this->input->post();

		if ($data) {
			try {
				$save = $this->model_settings->email_save($data, $this->uid);
				if (!$save) {
					echo "Error: Unable to save!";
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	function email_send()
	{
		$mail_msg = $this->input->post();

		$send_error = mail_send($mail_msg);
		if ($send_error) {
			echo $send_error;
		} else {
			echo "Sent";
		}

		// if ($mail_msg) {
		// 	if ($mail_msg["to"] && $mail_msg["subject"] && $mail_msg["message"]) {

		// 		//get email server details
		// 		try {
		// 			$app_details = $this->model_settings->app_details();
		// 			if ($app_details) {
		// 				$company_code = $app_details->company_code;

		// 				$protocol = $app_details->email_protocol;
		// 				$smtp_host = $app_details->smtp_host;
		// 				$smtp_user = $app_details->smtp_user;
		// 				$smtp_pass = $app_details->smtp_pass;
		// 				$smtp_port = $app_details->smtp_port;
		// 				$smtp_crypto = $app_details->smtp_crypto;

		// 				$this->load->library('email');

		// 				$config['protocol'] = $protocol;
		// 				$config['smtp_host'] = $smtp_host;
		// 				$config['smtp_user'] = $smtp_user;
		// 				$config['smtp_pass'] = $smtp_pass;
		// 				$config['smtp_port'] = $smtp_port;
		// 				$config['smtp_crypto'] = $smtp_crypto;
		// 				$config['charset'] = 'iso-8859-1';
		// 				$config['wordwrap'] = TRUE;
		// 				$config['mailtype'] = 'html';

		// 				$this->email->initialize($config);
		// 				$this->email->set_newline("\r\n");

		// 				$this->email->from($smtp_user, $company_code);
		// 				$this->email->to($mail_msg["to"]);
		// 				$this->email->cc($mail_msg["cc"]);
		// 				$this->email->bcc($mail_msg["bcc"]);

		// 				$this->email->subject($mail_msg["subject"]);
		// 				$this->email->message($mail_msg["message"]);

		// 				//$this->email->send();

		// 				if (!$this->email->send()) {
		// 					// Generate error
		// 					//print_r($this->email);
		// 					echo "Error: Unable to send email!";
		// 				}
		// 			} else {
		// 				echo "Error: Unable to initialize email!";
		// 			}
		// 		} catch (Exception $ex) {
		// 			echo $ex->getMessage();
		// 		}
		// 	} else {
		// 		echo "Error: Fields with red asterisk (*) are required!";
		// 	}
		// } else {
		// 	echo $this->default_error_msg;
		// }
	}

	function sms_save()
	{
		$data = $this->input->post();

		if ($data) {
			try {
				$save = $this->model_settings->sms_save($data, $this->uid);
				if (!$save) {
					echo "Error: Unable to save!";
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	function sms_send()
	{
		$data_msg = $this->input->post();

		if ($data_msg) {
			if ($data_msg["to"] && $data_msg["message"]) {

				//get sms api details
				try {
					$app_details = $this->model_settings->app_details();
					if ($app_details) {
						$company_code = $app_details->company_code;
						$sms_api_code = $app_details->sms_api_code;

						$result = $this->cf->send_sms(
							$data_msg["to"],
							$company_code . "\n\n" . $data_msg["message"],
							$sms_api_code
						);

						echo $result;
					} else {
						echo "Error: Unable to initialize sms!";
					}
				} catch (Exception $ex) {
					echo $ex->getMessage();
				}
			} else {
				echo "Error: Fields with red asterisk (*) are required!";
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	function others_save()
	{
		$data = $this->input->post();

		if ($data) {
			try {
				$save = $this->model_settings->others_save($data, $this->uid);
				if (!$save) {
					echo "Error: Unable to save!";
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	public function change_logo()
	{
		if (isset($_FILES["file"])) {
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			$filename = explode(".", $_FILES["file"]["name"]);
			$extension = strtolower(end($filename));

			if (($_FILES["file"]["type"] == "image/gif")
				|| ($_FILES["file"]["type"] == "image/jpeg")
				|| ($_FILES["file"]["type"] == "image/jpg")
				|| ($_FILES["file"]["type"] == "image/pjpeg")
				|| ($_FILES["file"]["type"] == "image/x-png")
				|| ($_FILES["file"]["type"] == "image/png")
				&& ($_FILES["file"]["size"] < 200000)
				&& in_array($extension, $allowedExts)
			) {

				if ($_FILES["file"]["error"] > 0) {
					echo "Error: Upload Problem - " . $_FILES["file"]["error"];
				} else {

					$tempName = $_FILES["file"]["tmp_name"];
					$target_file = "./assets/images/logo" . "." . $extension; // . basename($_FILES["file"]["name"]);

					if (move_uploaded_file($tempName, $target_file)) {

						try {
							$update = $this->model_settings->update_logo($extension, $this->uid);

							if ($update) {
								echo base_url() . "assets/images/logo" . "." . $extension;
							} else {
								echo "Error: Unable to update the DB!";
							}
						} catch (Exception $ex) {
							echo $ex->getMessage();
						}
					} else {
						echo "Error: There was an error uploading your file.";
					}
				}
			} else {
				echo "Error: It's either not supported file or more than 200Kb in size!";
			}
		}
	}
}