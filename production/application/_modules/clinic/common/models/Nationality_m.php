<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nationality_m extends Public_Model
{
	public $table = 'common_nationalities';
	public $primary_key = 'id';
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'alias' => array(
						'field' => 'alias',
						'label' => 'Alias',
						'rules' => 'required'
					),
				'nationality' => array(
						'field' => 'nationality',
						'label' => 'Nationality',
						'rules' => 'required'
					),
				'state' => array(
						'field' => 'state',
						'label' => 'Status',
						'rules' => ''
					),
			));
		
		parent::__construct();
	}
	
	public function options_nationality()
	{
		$options = array();
		
		$result = $this
			->as_dropdown( "nationality" )
			->order_by( "nationality" )
			->get_all()
			;
			
		if( $result )
		{
			foreach($result as $id => $nationality)
			{
				//array_push($options, $nationality);
				$options[$nationality] = $nationality;
			}
		}
			
		return $options;
	}
}