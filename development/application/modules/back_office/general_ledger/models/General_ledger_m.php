<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Journal_m extends Public_Model
{
	public $table = 'TBJ_Tranaska';
	public $primary_key = 'id';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'house_id' => array(
						'field' => 'house_id',
						'label' => lang( 'accounts:house_label' ),
						'rules' => ''
					),
				'journal_number' => array(
						'field' => 'journal_number',
						'label' => lang( 'accounts:journal_number_label' ),
						'rules' => ''
					),
				'journal_date' => array(
						'field' => 'journal_date',
						'label' => lang( 'accounts:journal_date_label' ),
						'rules' => 'required'
					),
				'journal_currency_code' => array(
						'field' => 'journal_currency_code',
						'label' => lang( 'accounts:currency_label' ),
						'rules' => 'required'
					),
				'debit' => array(
						'field' => 'debit',
						'label' => lang( 'accounts:debit_label' ),
						'rules' => 'required'
					),
				'credit' => array(
						'field' => 'credit',
						'label' => lang( 'accounts:credit_label' ),
						'rules' => 'required'
					),
				'balance' => array(
						'field' => 'balance',
						'label' => lang( 'accounts:balance_label' ),
						'rules' => 'required'
					),
				'notes' => array(
						'field' => 'notes',
						'label' => lang( 'accounts:notes_label' ),
						'rules' => ''
					),
				'state' => array(
						'field' => 'state',
						'label' => lang( 'accounting:state_label' ),
						'rules' => ''
					),
			));
		
		parent::__construct();
	}
	
	public function find_account_list( $options = array())
	{
		$this->db->order_by( 'account_name', 'asc' );
		$this->db->where( $options );
		
		$query = $this->db->get( $this->table );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_object() as $row )
			{
				$data[ $row->id ] = $row->account_name;
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
	
	public function delete_transaction_journal( $journal_number )
	{
		if (empty($journal_number))
		{
			return false;
		}
		
		$this->db->trans_start();
		$this->db->query("DELETE FROM tab_gl_journals WHERE journal_number = '". $journal_number ."' ");
		$this->db->query("DELETE FROM tab_gl_journal_details WHERE journal_number = '". $journal_number ."' ");
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		
		return TRUE;
	
	}
	
	public function get_before_balance( $account_id = 0, $date )
	{
		$month = date('m', strtotime(" {$date} first day of -1 month")); 
		$year = date('Y', strtotime(" {$date} first day of -1 month")); 
		
		
		$query =  $this->db->where( array("account_id" => $account_id, "month" => $month, "year" => $year ) )
				->get("gl_monthly_posted")
				;
				
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}		
	
	}
	
	public function check_journal_closing( $journal_number = NULL )
	{
		if( empty($journal_number) ){ return false; }
		
		$query = $this->db->where("journal_number", $journal_number)
						->get("gl_journals")
						;
		
		if ( $query->num_rows() > 0)
		{
			return $query->row()->state;
		}
		
		return false;
	}
	
}