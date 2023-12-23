<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Merchan_m extends Public_Model
{
	public $table = 'SIMmMerchan'; 
	public $primary_key = 'ID';
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'ID' => array(
						'field' => 'ID',
						'label' => lang( 'code_label' ),
						'rules' => ''
					),
				'NamaBank' => array(
						'field' => 'NamaBank',
						'label' => lang( 'title_label' ),
						'rules' => ''
					),
			));
		
		parent::__construct();
	}
}