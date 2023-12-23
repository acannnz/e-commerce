<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_m extends Public_Model
{
	public $table = 'Mst_Akun';
	public $primary_key = 'NoBukti';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
			
			array(
					'field' => 'FormatNo',
					'label' => lang('registrations:type_label'),
					'rules' => ''
				),
		));
		
		parent::__construct();
	}
}