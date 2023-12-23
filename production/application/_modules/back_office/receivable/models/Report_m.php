<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_m extends Public_Model
{
	public $table = 'ar_invoices';
	public $primary_key = 'id';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'house_id' => array(
						'field' => 'house_id',
						'label' => lang( 'reports:house_label' ),
						'rules' => 'required'
					),
				'type_id' => array(
						'field' => 'type_id',
						'label' => lang( 'types:type_label' ),
						'rules' => 'required'
					),
				'account_id' => array(
						'field' => 'account_id',
						'label' => lang( 'reports:account_label' ),
						'rules' => 'required'
					),
				'invoice_number' => array(
						'field' => 'invoice_number',
						'label' => lang( 'reports:invoice_number_label' ),
						'rules' => 'required'
					),
				'invoice_date' => array(
						'field' => 'invoice_date',
						'label' => lang( 'reports:invoice_date_label' ),
						'rules' => 'required'
					),
				'customer_id' => array(
						'field' => 'customer_id',
						'label' => lang( 'reports:customer_label' ),
						'rules' => 'required'
					),
				'due_date' => array(
						'field' => 'due_date',
						'label' => lang( 'reports:due_date_label' ),
						'rules' => ''
					),
				'value' => array(
						'field' => 'value',
						'label' => lang( 'reports:value_label' ),
						'rules' => 'required'
					),
				'remain' => array(
						'field' => 'remain',
						'label' => lang( 'reports:remain_label' ),
						'rules' => 'required'
					),
				'description' => array(
						'field' => 'description',
						'label' => lang( 'reports:description_label' ),
						'rules' => 'required'
					),
				'transaction_type_id' => array(
						'field' => 'transaction_type_id',
						'label' => 'Transaction Type',
						'rules' => 'required'
					),
			));
		
		parent::__construct();
	}
	
	public function find_report_list( $options = array())
	{
		$this->db->order_by( 'report_name', 'asc' );
		$this->db->where( $options );
		
		$query = $this->db->get( $this->table );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_object() as $row )
			{
				$data[ $row->id ] = $row->report_name;
			} //$query->result_array() as $row
		} //$query->num_rows() > 0
		
		return $data;
	}	

	public function find_currency_list( $options = array())
	{
		$this->db->order_by( 'code', 'asc' );
		$this->db->where( $options );
		
		$query = $this->db->get( "currencies" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_object() as $row )
			{
				$data[ $row->code ] = $row->symbol;
			} //$query->result_array() as $row
		} //$query->num_rows() > 0
		
		return $data;
	}	
	
	public function delete_report( $invoice_number )
	{
		if (empty($invoice_number))
		{
			return false;
		}
		
		$report = $this->db->where("invoice_number", $invoice_number)->get("ar_invoices")->row();
		$detail = $this->db->where("invoice_number", $invoice_number)->get("ar_invoice_details")->row();
		$invoice = $this->db->where("invoice_number", $detail->evidence_number)->get("ar_invoices")->row();
		
		// mengembalikan nilai sisa invoice
		$invoice_remain = $invoice->remain + $detail->debit - $detail->credit;
		
		$user_id = $this->tank_auth->get_user_id();
		$time_stamp = time();
		
		$this->db->trans_start();
		
		//hapus data nota debit credit
		$this->db->where( "invoice_number", $invoice_number )->delete("ar_invoices");
		$this->db->where( "invoice_number", $invoice_number )->delete("ar_invoice_details");
		$this->db->where( "evidence_number", $invoice_number )->delete("ar_invoice_details");
		$this->db->where( "evidence_number", $invoice_number )->delete("ar_card_receivables");

		// Update sisa invoice yg sebelum disesuaikan
		$this->db->where( "invoice_number", $invoice->invoice_number )->update("ar_invoices", array("remain" => $invoice_remain));		
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		
		return TRUE;
	
	}
	
	public function get_before_balance( $report_id = 0, $date )
	{
		$month = date('m', strtotime(" {$date} first day of -1 month")); 
		$year = date('Y', strtotime(" {$date} first day of -1 month")); 
		
		
		$query =  $this->db->where( array("report_id" => $report_id, "month" => $month, "year" => $year ) )
				->get("gl_monthly_posted")
				;
				
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}		
	
	}

	public function get_report( $where = NULL )
	{
		if ( !is_array( $where ) ) return false;
		
		$query = $this->db->where( $where )
						->get( $this->table )
						;
						
		if ($query->num_rows() > 0)
		{
			return $query->row();
		}
		
		return false;
	}

}