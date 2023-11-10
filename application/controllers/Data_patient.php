<?php
defined('BASEPATH') or die("No direct script allowed!");

class Data_patient extends CI_Controller
{
	protected $module_permission = array();
	protected $prefix; //check or update app_details table in db for session_prefix field
	protected $default_error_msg = "Error: Critical Error Encountered!";
	protected $role_id;
	protected $module = "data_patient";
	protected $module_description = "Patient";
	protected $page_name = "Patient";
	protected $parent_menu = "Data";
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
	//protected $timer_countdown;

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
					$this->company_code = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_code"];
					$this->company_name = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_name"];
					$this->company_address = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_address"];
					$this->company_contact = $this->session->userdata[$prefix . "_logged_in"][$prefix . "_company_contact"];
					//$this->timer_countdown = $this->session->userdata[$prefix . '_logged_in'][$prefix . '_timer_countdown'];

					$this->load->model('authentication_m', 'a');
					$this->role_id = $this->session->userdata[$this->prefix . '_logged_in'][$this->prefix . '_role'];
					$this->module_permission = $this->a->allow_permission($this->role_id, $this->module);

					$this->load->library('custom_function', NULL, 'cf');

					//load needed models for this page
					$this->load->model('data_patient_model', 'main_model');
					$this->load->model('data_patient_status_model');

					$this->load->model('data_gender_model');
					$this->load->model('data_civil_model');
				}
			}
		} catch (Exception $ex) {
			$this->session->set_userdata($this->prefix . '_to_page', current_url());
			redirect(base_url() . 'authentication');
		}
		//end of getting session prefix
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
		//$data['timer_countdown'] = $this->timer_countdown;

		if ($this->cf->is_allowed_module($this->module, $prefix)) {
			$data['role_id'] = $this->role_id;
			$data['module_permission'] = $this->module_permission;
			$data['uid'] = strtoupper($this->session->userdata[$prefix . '_logged_in'][$prefix . '_uid']);
			$data['ufname'] = strtoupper($this->session->userdata[$prefix . '_logged_in'][$prefix . '_fname']);

			$data['status'] = $this->data_patient_status_model->search("");
			$data['genders'] = $this->data_gender_model->search("");
			$data['civils'] = $this->data_civil_model->search("");
			$this->load->view('data_patient/index', $data);
		} else {
			$this->session->set_userdata($this->prefix . '_to_page', current_url());
			redirect(base_url() . 'authentication');
		}
	}

	function search()
	{
		$search = trim($this->input->get('search'));
		try {
			$result = $this->main_model->search($search);
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
				$info = $this->main_model->search_info_row($id);
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

	function advance_search()
	{
		$input_data = $this->input->post();

		try {
			$result = $this->main_model->advance_search($input_data);
			echo json_encode($result);
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	function add()
	{
		$data_input = $this->input->post();

		if ($data_input["lastname"] && $data_input["firstname"]) {
			try {
				$add = $this->main_model->add($data_input, $this->uid);

				if ($add) {
					$result = $this->main_model->search_by_id($add);
					echo json_encode($result);
				} else {
					echo "Error: Saving Failed, Please Try Again!";
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo "Error: All fields with * are required!";
		}
	}

	function update()
	{
		$data_input = $this->input->post();
		$id = $data_input["id"];

		if ($id) {
			if ($data_input["lastname"] && $data_input["firstname"]) {
				try {
					$update = $this->main_model->update($id, $data_input, $this->uid);

					if ($update) {
						$result = $this->main_model->search_by_row($id);
						echo json_encode($result);
					} else {
						echo "Error: Updating User Failed!";
					}
				} catch (Exception $ex) {
					echo $ex->getMessage();
				}
			} else {
				echo "Error: Critical Error Encountered!";
			}
		} else {
			echo "Error: All fields with * are required!";
		}
	}

	function delete()
	{
		$id = $this->input->post("id");
		$reason = $this->input->post("reason");

		if ($id) {
			if ($reason) {
				try {
					$delete = $this->main_model->delete($id, $reason, $this->uid);
					if ($delete) {
						try {
							$result = $this->main_model->search_by_row($id);
							echo json_encode($result);
						} catch (Exception $ex) {
							echo "Error: Successfully deleted but unable to reload the list. Please refresh the page manually";
						}
					} else {
						echo "Error: Unable to delete, Please Try Again!";
					}
				} catch (Exception $ex) {
					echo $ex->getMessage();
				}
			} else {
				echo "Error: Please provide a reason!";
			}
		} else {
			echo $this->default_error_msg;
		}
	}

	function activate()
	{
		$id = $this->input->post("id");

		if ($id) {
			try {
				$activate = $this->main_model->activate($id);
				if ($activate) {
					try {
						$result = $this->main_model->search_by_row($id);
						echo json_encode($result);
					} catch (Exception $ex) {
						echo "Error: Successfully Activated";
					}
				} else {
					echo "Error: Unable to activate, Please Try Again!";
				}
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}
		} else {
			echo $this->default_error_msg;
		}
	}


}
