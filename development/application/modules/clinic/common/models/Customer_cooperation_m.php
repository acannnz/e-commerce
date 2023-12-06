<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_cooperation_m extends Public_Model
{
	public $table = 'SIMdCustomerKerjasama';
	public $primary_key = 'CustomerKerjasamaID';
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'code' => array(
						'field' => 'code',
						'label' => lang( 'code_label' ),
						'rules' => 'required'
					),
				'service_title' => array(
						'field' => 'service_title',
						'label' => lang( 'title_label' ),
						'rules' => 'required'
					),
				'service_description' => array(
						'field' => 'service_description',
						'label' => lang( 'description_label' ),
						'rules' => ''
					),
				'service_price' => array(
						'field' => 'service_price',
						'label' => lang( 'price_label' ),
						'rules' => ''
					),
				'state' => array(
						'field' => 'state',
						'label' => lang( 'status_label' ),
						'rules' => ''
					),
			));
		
		parent::__construct();
	}
}