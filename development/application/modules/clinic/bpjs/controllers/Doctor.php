<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Doctor extends ADMIN_Controller
{
	protected $nameroutes = 'bpjs/doctor';
	
	public function __construct()
	{
		parent::__construct();		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('bpjs');		
		$this->load->helper('bpjs');
	}
	
	public function index()
	{
		show_404();
	}
	
	public function form_mapping( $code = NULL )
	{
		$this->data['mapping'] = $mapping = (object) [
			'code' => $code,
		];
		$this->data['lookup_doctor_bpjs'] = base_url("{$this->nameroutes}/lookup_doctor");
		
		$this->load->view("doctor/form", $this->data);
	}
	
	public function lookup_doctor( $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("doctor/lookup/lookup_doctor");
		}
	}
}

