<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Examination_result extends Admin_Controller 
{ 
	protected $_translation = 'laboratory';	
	protected $_model = 'lis_test_sample_model';
	protected $nameroutes = 'laboratory/laboratories/examination_result';
	  
	public function __construct() 
	{
		parent::__construct();
		$this->simple_login->check_user_role('laboratory');
						
		$this->load->language('laboratory');
		$this->load->helper('laboratory');
		
		$this->load->model('laboratory_m');
		$this->load->model('lis_test_sample_model');
		$this->load->model('lis_test_type_model');
		$this->load->model('service_model');
		$this->load->model('service_test_model');
		$this->load->model('test_type_detail_m');
		$this->load->model('test_type_m');
		$this->load->model('test_category_m');
		
		$this->load->model("registration_model");
		$this->load->model("registration_data_model");

		$this->load->model("patient_model");
		$this->load->model("patient_type_model");
		$this->load->model("supplier_model");
		$this->load->model("section_model");
	}
	
	public function process($NoBukti)
	{
		$item = $this->lis_test_sample_model->get_one( $NoBukti );
		if(empty($item)){
			$this->create($NoBukti);
		} else {
			$this->edit($NoBukti);
		}
	}
		
	public function create( $NoBukti )
	{				
		$item = laboratory_helper::get_examination_test($NoBukti);
		if(empty($item)){
			return false;
		}
		
		if(empty($item->TglLahir))
		{
			$dIntJmlHari = (int) @$item->UmurThn * 365 + (int) @$item->UmurBln * 30 + $item->UmurHr;
			$dIntJmlHariSisa = $dIntJmlHari % 365;
			$item->Pasien_UmurTh = ($dIntJmlHari / 365) < 0 ? 0 : (int) $dIntJmlHari / 365;
			$item->Pasien_UmurBln = ($dIntJmlHariSisa / 30) < 0 ? 0 : (int) $dIntJmlHariSisa / 30;
			$item->Pasien_UmurHr = $dIntJmlHariSisa % 30;
		} else {
			$datediff = time() - strtotime($item->TglLahir);
			$dIntJmlHari = round($datediff / (60 * 60 * 24));
			$dIntJmlHariSisa = $dIntJmlHari % 365;
			$item->Pasien_UmurTh = ($dIntJmlHari / 365) < 0 ? 0 : (int) $dIntJmlHari / 365;
			$item->Pasien_UmurBln = ($dIntJmlHariSisa / 30) < 0 ? 0 : (int) $dIntJmlHariSisa / 30;
			$item->Pasien_UmurHr = $dIntJmlHariSisa % 30;
		}
		
		$_get_sample = $this->get_model()->get_max('SampleID', ['NoSystem' => $NoBukti]);
		$dStrNoBill = !empty($_get_sample) ? $_get_sample++ : 'Z01';
		$item->SampleID = $dStrNoBill;
		
		$collection = laboratory_helper::get_examination_test_result($NoBukti);
		
		foreach($collection as $key => $row):
			if(!empty($item->NRM) && !empty($row->NamaTest)):
				$params = [
					'pStrNoBill' => $NoBukti, 
					'pStrJenisTest' => $row->TestID, 
					'pStrSex' => $item->JenisKelamin, 
					'pIntUmur_Th' => $item->Pasien_UmurTh, 
					'pIntUmur_Bln' => $item->Pasien_UmurBln, 
					'pIntUmur_Hr' => $item->Pasien_UmurHr, 
					'pStrTypeKelahiran' => "", 
				];
				$get_reference_value = laboratory_helper::get_reference_value( $params );
				
				$collection[$key]->NilaiRujukan = $get_reference_value->NilaiRujukan;
				if( $get_reference_value->NilaiRujukanKeterangan == "") $NilaiRujukanKeterangan = ".";						
				$collection[$key]->Keterangan = $get_reference_value->NilaiRujukanKeterangan;
					
			else:
				$collection[$key]->NilaiRujukan = "";
				$collection[$key]->Keterangan = "-";
			endif;
		endforeach;
		
		if( $this->input->post() ) 
		{
			$header = (object) $this->input->post("f");
			$results = $this->input->post("results");
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( (array) $header );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
			
			if( empty($results) )
			{
				$response = [
					"status" => "error",
					"message" => "Tidak ada Hasil Test yang dimasukan.",
					"code" => 200
				];					
				response_json($response);
			}

			if( $this->form_validation->run() )
			{
				$response = laboratory_helper::create_examination_test( $item, $header, $results);
				
			} else
			{
				$response = [
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
			
			response_json( $response );
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"collection" => $collection,
					"verify_result_link" => base_url("{$this->nameroutes}/verify_result/{$NoBukti}/{$item->SampleID}"),
				);
			
			$this->load->view( 
					'modal/examination_result', 
					array('form_child' => $this->load->view('examination_result/form', $data, true))
				);
		} else {
			show_404();
		}
	}
	
	public function edit( $NoBukti )
	{	
		$item = laboratory_helper::get_examination_test($NoBukti, TRUE);
		if(empty($item)){
			return false;
		}
						
		$collection = laboratory_helper::get_examination_test_result($NoBukti, TRUE);
		
		if( $this->input->post() ) 
		{
			$header = (object) $this->input->post("f");
			$results = (object) $this->input->post("results");
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( (array) $header );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
			
			if( empty($results) )
			{
				$response = [
					"status" => "error",
					"message" => "Tidak ada Hasil Test yang dimasukan.",
					"code" => 200
				];					
				response_json($response);
			}

			if( $this->form_validation->run() )
			{
				$response = laboratory_helper::update_examination_test( $item, $header, $results);
			} else
			{
				$response = [
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
			
			response_json( $response );
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"collection" => $collection,
					"verify_result_link" => base_url("{$this->nameroutes}/verify_result/{$NoBukti}/{$item->SampleID}"),
					"is_edit" => TRUE,
				);
			
			$this->load->view( 
					'modal/examination_result', 
					array('form_child' => $this->load->view('examination_result/form', $data, true))
				);
		} else {
			show_404();
		}
	}
	
	public function verify_result( $NoBukti, $SampleID)
	{
		$item = laboratory_helper::get_examination_test($NoBukti, TRUE);
		if(empty($item)){
			return false;
		}
				
		$collection = laboratory_helper::get_examination_test_result($NoBukti, TRUE);
		$doctor = $this->supplier_model->get_by(['Kode_Supplier' => $item->DokterID]);
		$analysis = $this->supplier_model->get_by(['Kode_Supplier' => $item->AnalisID]);
		
		if($this->input->post())
		{
			$header = (object) $this->input->post("f");
			$results = $this->input->post("results");
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( (array) $header );
			if( $this->form_validation->run() )
			{
				$response = laboratory_helper::verify_result( $item, $header, $results);
			} else
			{
				$response = [
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
			
			response_json( $response );
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"collection" => $collection,
					"doctor" => $doctor,
					"analysis" => $analysis,
					"form_action" => base_url("{$this->nameroutes}/edit/{$NoBukti}"),
					"print_url" => base_url("laboratory/reports/examination_test_result/{$NoBukti}"),
					"is_edit" => TRUE,
				);
			
			$this->load->view( 
					'modal/examination_result', 
					array('form_child' => $this->load->view('examination_result/verify_result', $data, true))
				);			
				
		} else {
			show_404();
		}
	}
	
	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'lookup/suppliers', array("type" => "doctor" ) );
		} 
	}
	
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'registrations/lookup/datatable' );
		} else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( "Lookup Box" )
				->build('registrations/lookup', (isset($data) ? $data : NULL));
		}
	}	
}