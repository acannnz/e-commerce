<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vital_reading_m extends Public_Model
{
	public $table = 'common_vital_readings';
	public $primary_key = 'id';
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'role' => array(
						'field' => 'role',
						'label' => lang( 'vr:role_label' ),
						'rules' => 'required'
					),
				'title' => array(
						'field' => 'title',
						'label' => lang( 'vr:title_label' ),
						'rules' => 'required'
					),
				'description' => array(
						'field' => 'description',
						'label' => lang( 'vr:long_desc_label' ),
						'rules' => ''
					),
				'input_type' => array(
						'field' => 'input_type',
						'label' => lang( 'vr:input_type_label' ),
						'rules' => ''
					),
				'input_validation' => array(
						'field' => 'input_validation',
						'label' => lang( 'vr:input_validation_label' ),
						'rules' => ''
					),
				'input_mask' => array(
						'field' => 'input_mask',
						'label' => lang( 'vr:input_mask_label' ),
						'rules' => ''
					),
				'value_min' => array(
						'field' => 'value_min',
						'label' => lang( 'vr:value_min_label' ),
						'rules' => ''
					),
				'value_max' => array(
						'field' => 'value_max',
						'label' => lang( 'vr:value_max_label' ),
						'rules' => ''
					),
				'value_default' => array(
						'field' => 'value_default',
						'label' => lang( 'vr:value_default_label' ),
						'rules' => ''
					),
				'unit_options' => array(
						'field' => 'unit_options',
						'label' => lang( 'vr:unit_options_label' ),
						'rules' => ''
					),
				'unit_default' => array(
						'field' => 'unit_default',
						'label' => lang( 'vr:unit_default_label' ),
						'rules' => ''
					),
				'is_recommended' => array(
						'field' => 'is_recommended',
						'label' => lang( 'vr:is_recommended_label' ),
						'rules' => 'integer'
					),
				'ordering' => array(
						'field' => 'ordering',
						'label' => lang( 'vr:ordering_label' ),
						'rules' => 'integer'
					),
				'state' => array(
						'field' => 'state',
						'label' => lang( 'vr:state_label' ),
						'rules' => 'integer'
					),
			));
		
		parent::__construct();
	}
}

