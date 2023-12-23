<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Factur_detail_m extends Public_Model
{
	public $table = 'ap_factur_details';
	public $primary_key = 'id';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'factur_number' => array(
						'field' => 'factur_number',
						'label' => lang( 'accounts:parent_label' ),
						'rules' => 'required'
					),
				'account_id' => array(
						'field' => 'account_id',
						'label' => lang( 'accounts:parent_ids_label' ),
						'rules' => 'required'
					),
				'debit' => array(
						'field' => 'debit',
						'label' => lang( 'accounts:level_label' ),
						'rules' => 'required'
					),
				'credit' => array(
						'field' => 'credit',
						'label' => lang( 'accounts:account_number_label' ),
						'rules' => 'required'
					),
				'notes' => array(
						'field' => 'notes',
						'label' => lang( 'accounts:account_description_label' ),
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
}