<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Test extends ADMIN_Controller
{
	protected $nameroutes = 'service/services/test';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('service');		
		$this->load->helper('service');
		$this->load->model('services/service_test_model');
	}
	
	/*
		@params
		(Object) $item -> Data Jasa
	*/
	public function index( $item )
	{
		$this->data['collection_test'] = service_helper::get_all_service_test( @$item->JasaID );
		$this->data['add_service_test'] = base_url("{$this->nameroutes}/lookup");
		
		$this->load->view('services/test/table', $this->data);
	}
	
	public function lookup()
	{
		if( $this->input->is_ajax_request() )
		{
			$this->load->view("services/test/lookup/test", $this->data);
		}
	}
}

