<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank_cash_deposit_m extends Public_Model
{
	public $table = 'SIMtrPenerimaanNonPasien';
	public $primary_key = 'NoBukti';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
			array(
					'field' => 'NoBukti',
					'label' => 'No Bukti',
					'rules' => 'required'
				),
			array(
					'field' => 'Tanggal',
					'label' => 'Tanggal',
					'rules' => 'required'
				),
			array(
					'field' => 'Jam',
					'label' => 'Jam',
					'rules' => 'required'
				),
			array(
					'field' => 'Keterangan',
					'label' => 'Keterangan',
					'rules' => 'required'
				),
			array(
					'field' => 'Nilai',
					'label' => 'Nilai',
					'rules' => 'required'
				),
			array(
				'field' => 'AkunID',
				'label' => 'Akun',
				'rules' => 'required'
			),
		));
		
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