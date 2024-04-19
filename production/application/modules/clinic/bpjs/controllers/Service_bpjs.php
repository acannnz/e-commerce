<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Service_bpjs extends ADMIN_Controller
{
	protected $nameroutes = 'bpjs/service_bpjs';
	
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
		$this->data['lookup_service_bpjs'] = base_url("{$this->nameroutes}/lookup_service");
		
		$this->load->view("bpjs/service/form", $this->data);
	}
	
	public function save_service()
	{
		if($this->input->post())
		{
			$post_data = $this->input->post('f');
			
			$this->load->model('registration_model');
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules('NoBuktiIntegrasi', 'Nomor Kunjungan', "required");
			$this->form_validation->set_rules('NoBuktiTindakanIntegrasi', 'Kode Tindakan SK', "required");
			$this->form_validation->set_rules('NoReg', 'Nomor Registrasi', "required");
			$this->form_validation->set_rules('NoPemeriksaan', 'Nomor Pemeriksaan', "required");
			$this->form_validation->set_rules('JasaID', 'JasaID', "required");
			$this->form_validation->set_data($post_data);
			if($this->form_validation->run())
			{
				$response = bpjs_helper::save_service($post_data);
			} else {
				$response = [
					'status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			
			response_json($response);
		}
	}
	
	public function lookup_service( $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("bpjs/service/lookup/lookup_service");
		}
	}
}

