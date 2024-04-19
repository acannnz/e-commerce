<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Section extends ADMIN_Controller
{
	protected $nameroutes = 'service/services/section';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('service');		
		$this->load->helper('service');
		$this->load->model('services/service_section_model');
	}
	
	/*
		@params
		(Object) $item -> Data Jasa
	*/
	public function index( $item )
	{
		$this->data['collection_section'] = service_helper::get_all_service_section( @$item->JasaID );
		$this->data['add_service_section'] = base_url("{$this->nameroutes}/lookup");
	
		$this->load->view('services/section/table', $this->data);
	}
	
	public function lookup()
	{
		if( $this->input->is_ajax_request() )
		{
			$this->load->view("services/section/lookup/section", $this->data);
		}
	}
}

