<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Drug_payment_m extends Public_Model
{
	public $table = 'SIMtrPembayaranObatBebas';
	public $primary_key = 'NoBukti';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = ['insert' => []];
		
		parent::__construct();
	}

	public function get_option_patient_type ()
	{
		$query = $this->db
					->order_by("JenisKerjasama", "ASC")
					->get("SIMmJenisKerjasama");
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}
	
	public function get_options( $table, $where = NULL, $order_by = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		if( !empty( $order_by ) && is_array( $order_by ))
		{
			$this->db->order_by( $order_by['field'], $order_by['order'] );
		}

		$query = $this->db->get( $table );
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}
}