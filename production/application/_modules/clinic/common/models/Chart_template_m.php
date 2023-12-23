<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chart_template_m extends Public_Model
{
	public $table = 'chart_templates';
	public $primary_key = 'id';
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'chief_complaint' => array(
						'field' => 'chief_complaint',
						'label' => lang( 'chart_template:complaint_label' ),
						'rules' => 'required'
					),
				'subjective' => array(
						'field' => 'subjective',
						'label' => lang( 'chart_template:subjective_label' ),
						'rules' => ''
					),
				'objective' => array(
						'field' => 'objective',
						'label' => lang( 'chart_template:objective_label' ),
						'rules' => ''
					),
				'assessment' => array(
						'field' => 'assessment',
						'label' => lang( 'chart_template:assessment_label' ),
						'rules' => ''
					),
				'plan' => array(
						'field' => 'plan',
						'label' => lang( 'chart_template:plan_label' ),
						'rules' => ''
					),
				'service_component_id' => array(
						'field' => 'service_component_id',
						'label' => lang( 'chart_template:service_comp_label' ),
						'rules' => ''
					),
				'product_component_id' => array(
						'field' => 'product_component_id',
						'label' => lang( 'chart_template:product_comp_label' ),
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