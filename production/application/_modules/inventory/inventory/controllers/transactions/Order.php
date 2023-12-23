<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Order extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/transactions/order'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('order_model');
		$this->load->model('order_detail_model');
		$this->load->model('supplier_model');
		$this->load->model('section_model');
		$this->load->model('item_model');
		$this->load->model('item_category_model');
		$this->load->model('item_location_model');
		$this->load->model('item_unit_model');
	}
	
	public function get_detail_collection( $id = 0, $is_mod = FALSE)
	{
		if ( ( $this->input->is_ajax_request() || $is_mod ) && $id !== 0  )
		{
			$collection = $this->order_detail_model->get_all_by( NULL, FALSE, $id);
			
			return $is_mod ? $collection : response_json( $collection );
		}
	}
	
}

