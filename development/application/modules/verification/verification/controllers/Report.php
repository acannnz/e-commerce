<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report extends Admin_Controller 
{ 
	protected $_translation = 'verification';	
	protected $nameroutes = 'verification/report';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('verification');
		
		$this->load->helper( "report" );
		$this->load->model("audit_model");
		$this->load->model("audit_detail_ap_model");
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
		
	public function honor()
	{
		if($this->input->post())
		{
			$date_start = $this->input->post('f[date_start]');
			$date_end = $this->input->post('f[date_end]');
			$doctor_id = $this->input->post('f[doctor_id]');

			report_helper::export_honor( $date_start, $date_end, $doctor_id );			
		}
		
		$data = [
			"datepicker" => true,
			"form" => true,
			"option_doctor" => option_doctor(),
		];
		
		$this->template
				->set( "heading", 'Laporan Rekap Honor Dokter' )
				->set_breadcrumb( lang("heading:reports") )
				->set_breadcrumb( 'Laporan Rekap Honor Dokter', base_url("{$this->nameroutes}/honor") )
				->build('reports/honor/dialog', $data );
	}
	
	public function lookup( $view, $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("general_payment/report/lookup/{$view}");
		} 
	}

}
