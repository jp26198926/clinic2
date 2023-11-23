<?php
defined('BASEPATH') or die('No direct script access allowed');

class Accounting_invoice_charges_model extends CI_Model
{
	private $tablename = "transactions";

	function search($search = "", $status_ids = [])
	{
		$this->db->select(
			"x.id, x.date, x.total, x.total_paid, (x.total - x.total_paid) as amount_due,
			 x.payment_status_id as status_id,
			 LPAD(x.id,5,'0') as invoice_no,
			 c.name as company,
			 CONCAT(p.firstname,' ',p.middlename,' ',p.lastname) as patient,
             s.status"
		);

		$this->db->from("transactions x");
		$this->db->join("patients p", "p.id=x.patient_id", "left");
		$this->db->join("clients c", "c.id=x.client_id", "left");
		$this->db->join("payment_status s", "s.id=x.payment_status_id", "left");
		$this->db->where("x.status_id", 4); //4 - completed
		$this->db->where_in("x.payment_method_id", array(2,4)); //2- po clinic hour, 4- po after office hour

		if ($search) {
			$this->db->where("CONCAT_WS(
					' ',
					LPAD(x.id,5,'0'),
					x.date,
					c.name,
					p.firstname, p.middlename, p.lastname,
					s.status
				)
				LIKE '%{$search}%'"
			);
		}

		if (count($status_ids) > 0) { //if status id specified
			$this->db->where_in("x.payment_status_id", $status_ids);
		}

		$this->db->order_by('x.id');

		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function search_row($transaction_id)
	{
		$this->db->select(
			"x.id, x.date, x.total, x.total_paid, (x.total - x.total_paid) as amount_due,
			 x.payment_status_id as status_id,
			 LPAD(x.id,5,'0') as invoice_no,
			 c.name as company,
			 CONCAT(p.firstname,' ',p.middlename,' ',p.lastname) as patient,
             s.status"
		);

		$this->db->from("transactions x");
		$this->db->join("patients p", "p.id=x.patient_id", "left");
		$this->db->join("clients c", "c.id=x.client_id", "left");
		$this->db->join("payment_status s", "s.id=x.payment_status_id", "left");
		$this->db->where("x.status_id", 4); //4 - completed
		$this->db->where_in("x.payment_method_id", array(2,4)); //2- po clinic hour, 4- po after office hour

		$this->db->where("x.id", $transaction_id);

		if ($query = $this->db->get()) {
			return $query->row();
		} else {
			$error = $this->db->error();
			throw new Exception("Error: " . $error['message']);
		}
	}

	function complete($transaction_id){
		$result["proceed"] = false;

		$this->db->trans_start();

		$this->db->select("(t.total - t.total_paid) as balance");
		$this->db->from("transactions t");
		$this->db->where("t.id", $transaction_id);

		if ($query = $this->db->get()) {
			$row = $query->row();
			$balance = $row->balance;

			if ($balance > 0) {
				$result["error"] = "There is {$balance} balance that needs to be settled!";
			} else {
				$result["proceed"] = true;
				//mark the transaction status as complete
				$this->db->reset_query();
				$this->db->set("payment_status_id", 4); //completed
				$this->db->where("id", $transaction_id);
				$this->db->update("transactions");
			}

		}else{
			$error = $this->db->error();
			$result["error"] = $error['message'];
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			throw new Exception("Error: Database problem, Please contact your System Administrator!");
		} else {
			return $result;
		}
	}

}
