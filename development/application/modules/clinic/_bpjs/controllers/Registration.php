<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Registration extends ADMIN_Controller
{
	protected $nameroutes = 'bpjs/registration';
	
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
		$this->data['lookup_registration_bpjs'] = base_url("{$this->nameroutes}/lookup_registration");
		
		$this->load->view("registration/form", $this->data);
	}
	
	public function register( $NoReg = NULL )
	{
		$this->data['reg'] = bpjs_helper::get_registration_outpatient( $NoReg );
		$this->data['add_url'] = config_item('bpjs_api_baseurl') ."/registrasi";
		$this->data['save_url'] = base_url("{$this->nameroutes}/save_registration");
		$this->data['remove_url'] = config_item('bpjs_api_baseurl') ."/registrasi";
		$this->data['delete_url'] = base_url("{$this->nameroutes}/delete_registration");
		$this->load->view("registration/register", $this->data);
	}
	
	public function save_registration()
	{
		if($this->input->post())
		{
			$post_data = $this->input->post('f');
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules('NoReg', 'Nomor Registrasi', "required");
			$this->form_validation->set_rules('SectionID', 'Section', "required");
			$this->form_validation->set_data($post_data);
			if($this->form_validation->run())
			{
				$response = bpjs_helper::save_registration($post_data);
			} else {
				$response = [
					'status' => 'success',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			
			response_json($response);
		}
	}
	
	public function delete_registration()
	{
		if($this->input->post())
		{
			$post_data = $this->input->post('f');
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules('NoReg', 'Nomor Registrasi', "required");
			$this->form_validation->set_rules('SectionID', 'Section', "required");
			$this->form_validation->set_data($post_data);
			if($this->form_validation->run())
			{
				$response = bpjs_helper::delete_registration($post_data);
			} else {
				$response = [
					'status' => 'success',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			
			response_json($response);
		}
	}
	
	public function lookup_registration( $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("registration/lookup/lookup_registration");
		}
	}
}

