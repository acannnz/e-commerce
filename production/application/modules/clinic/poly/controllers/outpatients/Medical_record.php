<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medical_record extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	protected $nameroutes = 'poly/outpatients/medical_record';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('outpatient');
		
		$this->load->model("emr_vital_signs_model");
		$this->load->model("emr_soap_notes_model");
		$this->load->model("supplier_model");
		$this->load->helper("poly");		
	}
	
	public function index( $NoReg, $NRM, $NoBukti = NULL, $is_edit = FALSE )
	{
		if($is_edit):
			$vital = $this->emr_vital_signs_model->get_by(['NoReg' => $NoReg, 'NoPemeriksaan' => $NoBukti]);
		elseif($get_vital = $this->emr_vital_signs_model->get_by(['NoReg' => $NoReg, 'NoPemeriksaan is NULL' => NULL , 'Parent' => 1])):
			$vital = $get_vital;
		else:
			$vital = (object) [
				'IdVitalSigns' => 0,
				'Height' => 0,
				'Weight' => 0,
				'Temperature' => 0,
				'Systolic' => 0,
				'Diastolic' => 0,
				'HeartRate' => 0,
				'RespiratoryRate' => 0,
				'OxygenSaturation' => 0,
				'Pain' => 0,
				'Parent' => 1
			];
		endif;
		
		if($is_edit):
			$soap = $this->emr_soap_notes_model->get_by(['NoPemeriksaan' => $NoBukti]);
		else:
			$soap = (object) [
				'IdSOAPNotes' => 0,
				'Subjective' => NULL,
				'Objective' => NULL,
				'Assessment' => NULL,
				'Plan' => NULL,
			];
		endif;
				
		$data = [
			"NoBukti" => $NoBukti,
			"vital" => $vital,
			"soap" => $soap,
			"lookup_soap_history" => base_url("{$this->nameroutes}/lookup_soap_history/{$NRM}"),
			"lookup_drug_history" => base_url("{$this->nameroutes}/lookup_drug_history/{$NRM}"),
			'nameroutes' => $this->nameroutes,
		];

		$this->load->view( 'outpatient/form/medical_record', $data );		
	}

	public function lookup_soap_history( $NRM )
	{
		if( $this->input->is_ajax_request() )
		{		
			$data = [
				'collection' => poly_helper::get_soap_history(['a.NRM' => $NRM]),
				'nameroutes' => $this->nameroutes,
			];
			
			$this->load->view( 'outpatient/medical_record/soap_history', $data );
		} 
	}
	public function lookup_drug_history( $NRM )
	{
		if( $this->input->is_ajax_request() )
		{		
			$data = [
				'collection' => poly_helper::get_drug_history(['d.NRM' => $NRM, 'd.Batal' => 0,'a.Batal' => 0, 'a.Retur' => 0, 'a.TipeTransaksi' => null]),
				'nameroutes' => $this->nameroutes,
			];
			
			$this->load->view( 'outpatient/medical_record/drug_history', $data );
		} 
	}

	public function drug_history_details( $NoBukti = NULL )
	{

		$NoBukti = ($NoBukti) ? $NoBukti : $this->input->post("NoBukti");

		if( ! $this->input->is_ajax_request() )
		{
			show_error( "Bad Request", 400 );
		}

		if( ($drug_details = poly_helper::get_drug_history_details($NoBukti)) === FALSE )
		{
			$response = array(
					'response_status' => 'error',
					'message' => lang( 'global:get_failed' )
				);
			
			print_r( json_encode($response) );
			exit;
			
		} 

		$data['item'] = $drug_details;
		$this->load->view( 'outpatient/medical_record/drug/details', $data );		
	}
}