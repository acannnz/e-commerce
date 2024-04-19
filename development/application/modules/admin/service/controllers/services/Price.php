<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Price extends ADMIN_Controller
{
	protected $nameroutes = 'service/services/price';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('service');		
		$this->load->helper('service');
		$this->load->model('services/price_model');
		$this->load->model('class_model');
		$this->load->model('patient_type_model');
		$this->load->model('specialist_model');
		$this->load->model('operation_service_category_model');
	}
	
	/*
		@params
		(Object) $item -> Data Jasa
	*/
	public function index( $item )
	{
		$this->data['collection_price'] = service_helper::get_all_service_price( @$item->JasaID );
		$this->data['add_service_price'] = base_url("{$this->nameroutes}/form");
		$this->data['update_service_price'] = base_url("{$this->nameroutes}/form");
		$this->data['add_service_component'] = base_url("{$this->nameroutes}/form_component");
		$this->data['update_service_component'] = base_url("{$this->nameroutes}/form_component");
		
		//print_r($this->data['collection']);exit;
		
		$this->load->view('services/price/table', $this->data);
	}
	
	public function form( $row_index = NULL )
	{
		if( $this->input->is_ajax_request() )
		{
			$this->data['item'] = $item = (object) [
				'KelasID' => 'xx',
				'JenisPasienID' => 3,
				'PasienKTP' => 1,
				'KategoriOperasiID' => 1,
				'DokterID' => 'XX',
				'Harga_Lama' => NULL,
				'Harga_Baru' => NULL,
				'HargaHC_Lama' => NULL,
				'HargaHC_Baru' => NULL,
				'TglHargaBaru' => date('Y-m-d'),
				'SpesialisID' => '99',
				//'SubSpesialisID' => NULL,
				'Cyto' => NULL,
				//'HonorDokter' => NULL,
				//'Incentive' => NULL,
				'Lokasi' => 'RJ',
				'DiscHCUmum' => NULL,
				'SubSpesialis' => NULL,
				//'HargaIKS_Lama' => NULL,
				//'HargaIKS_Baru' => NULL,
				//'MulaiHarga_IKS' => NULL,
				//'Import' => NULL,
				'HargaBPJS' => NULL,
				'HargaBPJS_Lama' => NULL,
				'TglHargaBaruBPJS' => date('Y-m-d'),
				'InsentifKomponen' => NULL,
				'InsentifDetail' => NULL,
				//'TglHargaBaruHC' => NULL,
				//'MappingInHealth' => NULL,
			];
			
			$this->data['row_index'] = $row_index;
			$this->data['doctor'] = (object)['Nama_Supplier' => 'None'];
			$this->data['dropdown_class'] = $this->class_model->dropdown_data(['Active' => 1]);
			$this->data['dropdown_patient_type'] = $this->patient_type_model->dropdown_data(['Kerjasama' => 0]);
			$this->data['dropdown_specialist'] = $this->specialist_model->dropdown_data();
			$this->data['dropdown_operation_category'] = $this->operation_service_category_model->dropdown_data();
			$this->data['dropdown_location'] = $this->price_model->dropdown_static('Lokasi');
			
			$this->data['lookup_doctor'] = base_url("{$this->nameroutes}/lookup_data/doctor");
			
			$this->load->view("services/price/form", $this->data);
		}
	}
	
	public function form_component( $row_index = NULL )
	{
		if( $this->input->is_ajax_request() )
		{
			$this->data['item'] = $item = (object) [
				'KomponenBiayaID' => NULL,
				'Qty' => 1,
				'HargaLama' => NULL,
				'HargaBaru' => NULL,
				'HargaHCLama' => NULL,
				'HargaHCBaru' => NULL,
				'PersenInsentifHC' => NULL,
				//'HargaIKS_Lama' => NULL,
				//'HargaIKS_Baru' => NULL,
				'HargaBPJS' => NULL,
				'HargaBPJS_Lama' => NULL,
				'AkunNoLawan' => NULL,
				'AkunNo' => NULL,
				'PersenPajakTitipan' => 0,
				'PersenInsentif' => 0,
				'IncludeInsentif' => 0,
				'Prosentase' => 0,
				'NilaiPersen' => 0,
			];
			
			$this->data['row_index'] = $row_index;			
			$this->data['lookup_component'] = base_url("{$this->nameroutes}/lookup_data/component");
			$this->data['lookup_hpp'] = base_url("{$this->nameroutes}/lookup_data/lookup_hpp_account");
			$this->data['lookup_hpp_againts'] = base_url("{$this->nameroutes}/lookup_data/lookup_hpp_againts_account");
			
			$this->load->view("services/price/form_component", $this->data);
		}
	}
	
	public function lookup_data( $view, $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("services/price/lookup/{$view}");
		}
	}
}

