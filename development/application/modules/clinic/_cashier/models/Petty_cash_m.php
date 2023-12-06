<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Petty_cash_m extends Public_Model
{
	public $table = 'SIMtrPettyCashKasir';
	public $primary_key = 'NoBukti';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
			array(
					'field' => 'KategoriTestID',
					'label' => lang('registrations:registration_number_label'),
					'rules' => ''
				),
			array(
					'field' => 'KategoriTestNama',
					'label' => lang('registrations:date_label'),
					'rules' => ''
				),
			array(
					'field' => 'FormatDefault',
					'label' => lang('registrations:time_label'),
					'rules' => ''
				),
			array(
					'field' => 'NoUrut',
					'label' => lang('registrations:mr_number_label'),
					'rules' => ''
				),
			array(
					'field' => 'FormatNo',
					'label' => lang('registrations:type_label'),
					'rules' => ''
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