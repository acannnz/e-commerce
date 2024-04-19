<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier_sub_specialist_m extends Public_Model
{
	public $table = 'SIMmSubSpesialis';
	public $primary_key = 'SubSpesialisID';
	
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