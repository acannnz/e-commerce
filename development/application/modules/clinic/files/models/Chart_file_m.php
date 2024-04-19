<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chart_file_m extends Public_Model
{
	public $table = 'transaction_chart_files';
	public $primary_key = 'id';
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				array(
						'field' => 'id',
						'label' => lang('chart_file:id_label'),
						'rules' => ''
					),
			));
		
		parent::__construct();
	}
}


