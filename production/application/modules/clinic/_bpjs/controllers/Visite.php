<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Visite extends ADMIN_Controller
{
	protected $nameroutes = 'bpjs/visite';
	
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
		$this->data['lookup_visite_bpjs'] = base_url("{$this->nameroutes}/lookup_visite");
		
		$this->load->view("visite/form", $this->data);
	}
	
	public function add( $NoReg )
	{
		$this->data['process_url'] = base_url("{$this->nameroutes}/process/{$NoReg}");
		
		$this->load->view("visite/add", $this->data);
	}
	
	public function checkout( $NoReg )
	{
		$this->data['create_url'] = base_url("{$this->nameroutes}/save_checkout/{$NoReg}");
		$this->load->view("visite/referral", $this->data);
	}
	
	public function save_checkout( $NoReg )
	{
		if($this->input->post())
		{
			$post_data = $this->input->post('f');
			
			$this->load->model('integration_insurance_model');
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules('CheckoutState', 'Status Pulang', "required");
			$this->form_validation->set_data($post_data);
			if($this->form_validation->run())
			{
				$response = bpjs_helper::save_checkout_outpatient($NoReg, $post_data);
			} else {
				$response = [
					'status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			
			response_json($response);
		}
	}
	
	public function process( $NoReg )
	{
		$this->data['visite'] = bpjs_helper::get_visite_outpatient($NoReg);
		$this->data['service'] = bpjs_helper::get_service_outpatient($NoReg);
		$this->data['drug'] = bpjs_helper::get_drug_outpatient($NoReg);
		
		$this->data['add_visite_url'] = config_item('bpjs_api_baseurl') ."/visite";
		$this->data['save_visite_url'] = base_url("{$this->nameroutes}/save_visite_outpatient/{$NoReg}");
		$this->data['add_service_url'] = config_item('bpjs_api_baseurl') ."/tindakan";
		$this->data['save_service_url'] = base_url("bpjs/service_bpjs/save_service/{$NoReg}");
		$this->data['add_drug_url'] = config_item('bpjs_api_baseurl') ."/obat";
		$this->data['save_drug_url'] = base_url("bpjs/drug/save_drug/{$NoReg}");
		$this->data['get_visite_service_url'] = config_item('bpjs_api_baseurl') ."/tindakan/visite";
		$this->data['get_visite_drug_url'] = config_item('bpjs_api_baseurl') ."/obat/visite";

		$this->load->view("visite/process", $this->data);
	}
	
	public function save_visite_outpatient( $NoReg )
	{
		if($this->input->post())
		{
			$post_data = $this->input->post('f');
			
			$this->load->model('integration_insurance_model');
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules('NoBuktiIntegrasi', 'Nomor Kunjungan', "required|is_unique[{$this->integration_insurance_model->table}.NoBuktiIntegrasi]");
			$this->form_validation->set_data($post_data);
			if($this->form_validation->run())
			{
				$response = bpjs_helper::save_visite_outpatient($NoReg, $post_data['NoBuktiIntegrasi']);
			} else {
				$response = [
					'status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			
			response_json($response);
		}
	}
	
	public function lookup_visite( $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("visite/lookup/lookup_visite");
		}
	}
	
	public function lookup_referral( $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("visite/lookup/lookup_referral");
		}
	}
}

