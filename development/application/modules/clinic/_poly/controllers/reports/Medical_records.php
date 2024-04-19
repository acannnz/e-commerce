<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medical_records extends Admin_Controller
{ 
	protected $_translation = 'reports';	
	protected $nameroutes = 'poly/reports';	
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role(['inpatient', 'outpatient']);
		
		$this->page = 'Medical Reports';
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("{$this->nameroutes}/medical-records")."\";</script>";
			exit();
		} else
		{
			redirect( "{$this->nameroutes}/medical-records/dialog" );
		}
	}
	
	public function export()
	{
		if ( $this->input->post() )
		{
			$this->load->helper( "export" );
			$this->load->helper( "report" );

			switch ( $this->input->post("export_to") ) :
				case "spog_pdf" :
					$this->export_spog_pdf();
				break;	
				case "spog_excel" :
					$this->export_spog_excel();
				break;
				case "spa_pdf" :
					$this->export_spa_pdf();
				break;	
				case "spa_excel" :
					$this->export_spa_excel();
				break;
			endswitch;
		}
	}

	public function spog( $is_ajax = FALSE)
	{
		$data = [
			"datepicker" => true,
			"form" => true,
			"export_url" => base_url("{$this->nameroutes}/medical-records/export"),
		];
			
		if( $this->input->is_ajax_request())
		{
			$this->load->view(
				"polies/reports/medical_reports/modal/dialog",
				["form_child" => $this->load->view("polies/reports/medical_reports/dialog_spog", $data, true)]
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:page") )
				->set_breadcrumb( 'Rekam Medik SPOG', base_url("{$this->nameroutes}/medical-records") )
				->build('polies/reports/medical_reports/dialog_spog', (isset($data) ? $data : NULL));
		}
	}
	
	private function export_spog_pdf( $type )
	{
		$location = $this->session->userdata($type);
		if(!$this->input->post())
		{
			$post_data = (object) $this->input->post("f") ;

			$collection = report_helper::get_recap_stocks($post_data->date_start, $post_data->date_end, $post_data->Lokasi_ID );	
			$section = $this->db->where("SectionID", $location['section_id'])->get("SIMmSection")->row();						
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
							"section" => $section,
						);
						
			$html_content =  $this->load->view( "polies/reports/medical_reports/export/pdf", $data, TRUE ); 
			$footer = lang('reports:recap_stock_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:recap_stock_label');		
			
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		$this->index();

	}

	private function export_spog_excel()
	{
		if ($this->input->post())
		{
			$date_start = $this->input->post("f[date_start]") ;
			$date_end = $this->input->post("f[date_end]") ;
			$doctor_id = $this->input->post("f[doctor_id]") ;
			report_helper::export_excel_spog_medical_record($date_start, $date_end, $doctor_id);
		}
		
		$this->index();

	}
	
	
	public function spa( $is_ajax = FALSE)
	{
		$data = [
			"datepicker" => true,
			"form" => true,
			"export_url" => base_url("{$this->nameroutes}/medical-records/export/{$type}"),
		];
			
		if( $this->input->is_ajax_request())
		{
			$this->load->view(
				"polies/reports/medical_reports/modal/dialog",
				["form_child" => $this->load->view("polies/reports/medical_reports/dialog_spa", $data, true)]
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:page") )
				->set_breadcrumb( 'Rekam Medik SPA', base_url("{$this->nameroutes}/medical-records") )
				->build('polies/reports/medical_reports/dialog_spa', (isset($data) ? $data : NULL));
		}
	}
	
	private function export_spa_pdf( $type )
	{
		$location = $this->session->userdata($type);
		if(!$this->input->post())
		{
			$post_data = (object) $this->input->post("f") ;

			$collection = report_helper::get_recap_stocks($post_data->date_start, $post_data->date_end, $post_data->Lokasi_ID );	
			$section = $this->db->where("SectionID", $location['section_id'])->get("SIMmSection")->row();						
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
							"section" => $section,
						);
						
			$html_content =  $this->load->view( "polies/reports/medical_reports/export/pdf", $data, TRUE ); 
			$footer = lang('reports:recap_stock_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:recap_stock_label');		
			
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);	
			exit(0);
		}
		
		$this->index();

	}

	private function export_spa_excel()
	{
		if ($this->input->post())
		{
			$date_start = $this->input->post("f[date_start]") ;
			$date_end = $this->input->post("f[date_end]") ;
			$doctor_id = $this->input->post("f[doctor_id]") ;
			report_helper::export_excel_spa_medical_record($date_start, $date_end, $doctor_id);
		}
		
		$this->index();

	}

}

