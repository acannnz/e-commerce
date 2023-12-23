<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Patient_type_m extends Public_Model
{
	public $table = 'SIMmJenisKerjasama';
	public $primary_key = 'JenisKerjasamaID';
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'code' => array(
						'field' => 'code',
						'label' => lang( 'patient_types:code_label' ),
						'rules' => 'required'
					),
				'type_name' => array(
						'field' => 'type_name',
						'label' => lang( 'patient_types:type_label' ),
						'rules' => ''
					),
				'type_description' => array(
						'field' => 'type_description',
						'label' => lang( 'patient_types:description_label' ),
						'rules' => ''
					),
				'state' => array(
						'field' => 'state',
						'label' => lang( 'patient_types:state_label' ),
						'rules' => ''
					),
			));
		
		parent::__construct();
	}
	
	public function options_type()
	{
		$result = array();
		$this->db->select('JenisKerjasamaID,JenisKerjasama');
		$this->db->from('SIMmJenisKerjasama');
		$query = $this->db->get();
		foreach($query->result_array() as $row)
		{
			$result[] = $row;
		}
		return $result;

	}	
}


