<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laboratory extends Admin_Controller 
{ 
	protected $_translation = 'laboratory';	
	protected $_model = 'laboratory_m';
	protected $nameroutes = 'laboratory';
	  
	public function __construct() 
	{
		parent::__construct();
		$this->simple_login->check_user_role('laboratory');
		$this->simple_login->set_medics('laboratory');
		
		$this->page = "laboratory";
		$this->template->title( lang("laboratory:page") . ' - ' . $this->config->item('company_name') );
		
		$this->load->language('laboratory');
		$this->load->helper('laboratory');
		
		$this->load->model('laboratory_m');
		$this->load->model('mitra_type_m');
		$this->load->model("poly_nurse_model");
		$this->load->model("poly_transaction_detail_model");
		$this->load->model("poly_transaction_model");
		$this->load->model("poly_transaction_pop_model");
		$this->load->model("poly_destination_model");
		$this->load->model("poly_initial_diagnosis_model");
		$this->load->model("poly_m");
				
		$this->load->model("reservation_model");
		$this->load->model("registration_model");
		$this->load->model("registration_data_model");
		$this->load->model("laboratory_memo_model");

		$this->load->model("patient_model");
		$this->load->model("patient_type_model");
		$this->load->model("supplier_model");
		$this->load->model("section_model");
	}
	
	public function index()
	{
		$data = [
			'page' => $this->page,
			'nameroutes' => $this->nameroutes,
			'option_doctor' => option_doctor(),
			'form' => TRUE,
			'datatables' => TRUE,
		];

		$this->template
			->set( "heading", lang('laboratory:list_heading') )
			->set_breadcrumb( lang("laboratory:breadcrumb") )
			->build('datatable', (isset($data) ? $data : NULL));
			
	}
		
	public function create( $NoReg, $SectionID )
	{
		$examination = $this->laboratory_m->get_by(["RegNo" => $NoReg, "SectionID" => $SectionID, 'Batal' => 0]);		
		if( !empty($examination->NoBukti) ){
			redirect("laboratory/edit/{$examination->NoBukti}");
		}
		$item = laboratory_helper::get_registration_data( $NoReg, $SectionID);
		$item->NoBukti = laboratory_helper::gen_evidence_number($SectionID);
		$item->TindakLanjut_Pulang = 1;

		if( $this->input->post() ) 
		{
			$lab = $this->input->post("lab");
			$service = (array) $this->input->post("service");
			$service_component = (array) $this->input->post("service_component");
			$service_consumable = (array) $this->input->post("service_consumable");
			$checkout = $this->input->post("checkout");	

			//KHUSUS FKTP MATIKAN
			// if ($item->StatusBayar == "Sudah Bayar" || $item->ProsesPayment == 1)
			// {
			// 	response_json([
			// 		"status" => 'error',
			// 		"message" => 'Proses simpan data tidak dapat dilakukan, karena pasien sudah bayar dikasir',
			// 		"code" => 200
			// 	]);
			// }
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $lab );
			if( $this->form_validation->run() )
			{
				$response = laboratory_helper::create_examination($lab, $service, $service_component, $service_consumable, $checkout);

			} else
			{
				$response = [
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
			
			response_json($response);
		}

		$option_patient_type = $this->laboratory_m->get_option_patient_type();
		$option_section = $this->section_model->get_all(NULL, 0, ["TipePelayanan" => "RJ"], FALSE);

		$option_alasan_dirujuk = $this->laboratory_m->get_options("SIMmAlasanDirujuk");
		
		if( $this->input->is_ajax_request() )
		{
			$data = [
				'item' => $item,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
			];
			
			$this->load->view( 
					'modal/create_edit', 
					array('form_child' => $this->load->view('form', $data, true))
				);
		} else
		{
			$data = [
				"page" => $this->page."_".strtolower(__FUNCTION__),
				"item" => $item,
				"option_section" => $option_section,
				"user" => $this->simple_login->get_user(),
				"nameroutes" => $this->nameroutes,
				"lookup_doctor_sender" => base_url("{$this->nameroutes}/lookup_doctor_sender"),
				"lookup_supplier_sender" => base_url("{$this->nameroutes}/lookup_supplier_sender"),
				"lookup_supplier" => base_url("{$this->nameroutes}/lookup_supplier"),
				"lookup_supplier_analis" => base_url("{$this->nameroutes}/lookup_supplier_analis"),
				"lookup_supplier_transfer" => base_url("{$this->nameroutes}/lookup_supplier_transfer"),
				"lookup_vendor_dirujuk" => base_url("{$this->nameroutes}/lookup_vendor_dirujuk"),
				"form" => TRUE,
				"datatables" => TRUE,
			];
			
			$this->template
				->set( "heading", "Pemeriksaan Laboratorium" )
				->set_breadcrumb( "Penunjang" )
				->set_breadcrumb( "Laboratorium", base_url("{$this->nameroutes}") )
				->set_breadcrumb( "Pemeriksaan Laboratorium" )
				->build('form', $data);
		}
	}
	
	public function edit( $NoBukti = NULL )
	{
		$item = laboratory_helper::get_examination( $NoBukti );
		if( $this->input->post() ) 
		{
			$lab = $this->input->post("lab");
			$service = (array) $this->input->post("service");
			$service_component = (array) $this->input->post("service_component");
			$service_consumable = (array) $this->input->post("service_consumable");
				
			$this->load->library( 'form_validation' );		
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data(  $lab );
			if( $this->form_validation->run() )
			{
				$message = laboratory_helper::update_examination($lab, $service, $service_component, $service_consumable);

			} else
			{
				$message = [
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
			
			response_json($message);
		}
		
		$option_patient_type = $this->laboratory_m->get_option_patient_type();
		$option_section = $this->section_model->get_all(NULL, 0, ["TipePelayanan" => "RJ"], FALSE);
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'modal/create_edit', 
					array('form_child' => $this->load->view('form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
					"option_section" => $option_section,
					"user" => $this->simple_login->get_user(),
					"lookup_doctor_sender" => base_url("{$this->nameroutes}/lookup_doctor_sender"),
					"lookup_supplier" => base_url("{$this->nameroutes}/lookup_supplier"),
					"lookup_supplier_analis" => base_url("{$this->nameroutes}/lookup_supplier_analis"),
					"lookup_supplier_transfer" => base_url("{$this->nameroutes}/lookup_supplier_transfer"),
					"lookup_vendor_dirujuk" => base_url("{$this->nameroutes}/lookup_vendor_dirujuk"),
					"lookup_supplier_sender" => base_url("{$this->nameroutes}/lookup_supplier_sender"),
					"cancel_link" => base_url("{$this->nameroutes}/cancel/{$NoBukti}"),
					'nameroutes' => $this->nameroutes,
					"form" => TRUE,
					"datatables" => TRUE,
					"is_edit" => TRUE,
				);
		
			$this->template
				->set( "heading", "Pemeriksaan Laboratorium" )
				->set_breadcrumb( "Penunjang" )
				->set_breadcrumb( "Laboratorium", base_url("{$this->nameroutes}") )
				->set_breadcrumb( "Pemeriksaan Laboratorium" )
				->build("form", $data);
		}
	}
	
	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'lookup/suppliers', array("type" => "doctor" ) );
		} 
	}
	
	public function lookup_doctor_sender( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'lookup/doctor_sender', array("type" => "doctor" ) );
		} 
	}

	public function lookup_supplier_sender( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'lookup/supplier_sender', array("type" => "hospitals" ) );
		} 
	}

	public function lookup_supplier_analis( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'lookup/suppliers_analis', array("type" => "analys" ) );
		} 
	}

	public function lookup_supplier_transfer( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'lookup/suppliers_transfer', array("type" => "doctor" ) );
		} 
	}

	public function lookup_vendor_dirujuk( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'lookup/vendor_dirujuk', array("type" => "hospitals" ) );
		} 
	}
}