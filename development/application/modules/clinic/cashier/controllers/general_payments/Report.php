<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report extends Admin_Controller 
{ 
	protected $_translation = 'reports';	
	protected $_model = 'general_payment_m';  
	protected $nameroutes = 'cashier/general_payments/report';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('cashier');
		
		$this->load->helper( "general_payment_report" );
		$this->load->model("section_model");
		$this->load->model("supplier_model");
		
		$this->page = "Laporan Keuangan";
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("cahier/general-payment")."\";</script>";
			exit();
		} else
		{
			redirect( "cahier/general-payment" );
		}
	}
	
	public function transaction_recap_by_section_doctor()
	{
		if($this->input->post())
		{
			$date_start = $this->input->post('f[date_start]');
			$date_end = $this->input->post('f[date_end]');
			$section_id = $this->input->post('f[section_id]');
			$doctor_id = $this->input->post('f[doctor_id]');
			
			general_payment_report_helper::export_transaction_recap_by_section_doctor( $date_start, $date_end, $section_id, $doctor_id );			
		}
		
		$data = [
			"datepicker" => true,
			"form" => true,
			"option_section" => $this->section_model->dropdown_data(['TipePelayanan' => 'RJ', 'StatusAktif' => 1]),
			"option_doctor" => option_doctor(),
		];
		
		$this->template
				->set( "heading", 'Laporan Rekap Pendapatan' )
				->set_breadcrumb( lang("reports:page") )
				->set_breadcrumb( 'Laporan Rekap Pendapatan', base_url("{$this->nameroutes}/transaction_recap_by_section_doctor") )
				->build('general_payment/report/transaction_recap_by_section_doctor/dialog', $data );
	}
	
	public function transaction_recap_by_service_group()
	{
		if($this->input->post())
		{
			$date_start = $this->input->post('f[date_start]');
			$date_end = $this->input->post('f[date_end]');
			$section_id = $this->input->post('f[section_id]');
			$doctor_id = $this->input->post('f[doctor_id]');
			
			general_payment_report_helper::export_transaction_recap_by_service_group( $date_start, $date_end, $section_id, $doctor_id );			
		}
		
		$data = [
			"datepicker" => true,
			"form" => true,
			"option_section" => $this->section_model->dropdown_data(['TipePelayanan' => 'RJ', 'StatusAktif' => 1]),
			"option_doctor" => option_doctor(),
		];
		
		$this->template
				->set( "heading", 'Laporan Rekap Pendapatan Per Grup Jasa' )
				->set_breadcrumb( lang("reports:page") )
				->set_breadcrumb( 'Laporan Rekap Pendapatan Per Grup Jasa', base_url("{$this->nameroutes}/transaction_recap_by_service_group") )
				->build('general_payment/report/transaction_recap_by_service_group/dialog', $data );
	}
	
	public function lookup( $view, $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("general_payment/report/lookup/{$view}");
		} 
	}

}
