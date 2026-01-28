<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Bhp extends ADMIN_Controller
{
	protected $nameroutes = 'service/services/bhp';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('service');		
		$this->load->helper('service');
		$this->load->model('services/bhp_model');
	}
	
	/*
		@params
		(Object) $item -> Data Jasa
	*/
	public function index( $item )
	{
		$this->data['collection_bhp'] = service_helper::get_all_service_bhp( @$item->JasaID );
		$this->data['add_service_bhp'] = base_url("{$this->nameroutes}/lookup");
	
		$this->load->view('services/bhp/table', $this->data);
	}
	
	public function lookup()
	{
		if( $this->input->is_ajax_request() )
		{
			$this->load->view("services/bhp/lookup/item", $this->data);
		}
	}
}

