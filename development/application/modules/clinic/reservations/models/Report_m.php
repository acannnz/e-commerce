<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_m extends Public_Model
{
	public $table = 'patient_types';
	public $primary_key = 'id';
	
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
		$options = $this
			->as_dropdown( "type_name" )
			->order_by( "type_name" )
			->get_all(array("state" => 1))
			;
			
		return (is_array($options) ? $options : array());
	}	
}


