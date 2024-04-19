<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Icd_m extends Public_Model
{
	public $table = 'common_icd';
	public $primary_key = 'id';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'code' => array(
						'field' => 'code',
						'label' => lang( 'icd:code_label' ),
						'rules' => 'required'
					),
				'version' => array(
						'field' => 'version',
						'label' => lang( 'icd:version_label' ),
						'rules' => 'required'
					),
				'long_desc' => array(
						'field' => 'long_desc',
						'label' => lang( 'icd:long_desc_label' ),
						'rules' => 'required'
					),
				'short_desc' => array(
						'field' => 'short_desc',
						'label' => lang( 'icd:short_desc_label' ),
						'rules' => ''
					),
				'state' => array(
						'field' => 'state',
						'label' => lang( 'icd:state_label' ),
						'rules' => ''
					),
			));
		
		parent::__construct();
	}
}