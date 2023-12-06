<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Satuan_m extends Public_Model
{
	public $table = 'LISmSatuan'; 
	public $primary_key = 'SatuanID';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
			array(
					'field' => 'SatuanID',
					'label' => lang('registrations:registration_number_label'),
					'rules' => ''
				),
			array(
					'field' => 'NamaSatuan',
					'label' => lang('registrations:registration_number_label'),
					'rules' => ''
				),
			)
							 );
		
		parent::__construct();
	}
	
	public function options_type()
	{
		$result = array();
		$this->db->select('SatuanID');
		$this->db->from('LISmSatuan');
		//$this->db->where('code',$code);
		$query = $this->db->get();
		foreach($query->result_array() as $row)
		{
			$result[] = $row;
		}
		return $result;

	}	

	
	

}