<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Drug extends ADMIN_Controller
{
	protected $nameroutes = 'bpjs/drug';
	
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
		$this->data['lookup_drug_bpjs'] = base_url("{$this->nameroutes}/lookup_drug");
		
		$this->load->view("drug/form", $this->data);
	}
	
	public function save_drug()
	{
		if($this->input->post())
		{
			$post_data = $this->input->post('f');
			
			$this->load->model('registration_model');
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules('NoBuktiIntegrasi', 'Nomor Kunjungan', "required");
			$this->form_validation->set_rules('NoBuktiObatIntegrasi', 'Kode Obat SK', "required");
			$this->form_validation->set_rules('NoReg', 'Nomor Registrasi', "required");
			$this->form_validation->set_rules('NoBukti', 'Nomor Bukti', "required");
			$this->form_validation->set_rules('BarangID', 'BarangID', "required");
			$this->form_validation->set_data($post_data);
			if($this->form_validation->run())
			{
				$response = bpjs_helper::save_drug($post_data);
			} else {
				$response = [
					'status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			
			response_json($response);
		}
	}
	
	public function lookup_drug( $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("drug/lookup/lookup_drug");
		}
	}
}

