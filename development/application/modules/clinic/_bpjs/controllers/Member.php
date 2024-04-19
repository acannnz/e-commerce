<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Member extends ADMIN_Controller
{
	protected $nameroutes = 'bpjs/member';
	
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
		$this->data['lookup_member_bpjs'] = base_url("{$this->nameroutes}/lookup_member");
		
		$this->load->view("member/form", $this->data);
	}
	
	public function lookup_member( $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("member/lookup/lookup_member");
		}
	}
}

