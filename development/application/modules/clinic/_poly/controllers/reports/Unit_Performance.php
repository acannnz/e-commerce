<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unit_Performance extends Admin_Controller
{ 
	protected $_translation = 'reports';	
	protected $nameroutes = 'poly/reports';	
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role(['inpatient', 'outpatient']);
		
		$this->page = lang( 'reports:unit_performance_heading' );
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("{$this->nameroutes}/unit-performance")."\";</script>";
			exit();
		} else
		{
			redirect();
		}
	}
	

	public function dialog( $type )
	{
		$location = $this->session->userdata($type);
		$section = $this->db->where("SectionID", $location['section_id'])->get("SIMmSection")->row();
		$item = (object) array(
				"Lokasi_ID" => $section->Lokasi_ID
			);

		$data = array(
				"item" => $item,
				"datepicker" => true,
				"form" => true,
				"export_url" => base_url("{$this->nameroutes}/unit-performance/export/{$type}"),
				"lookup_products" => base_url("{$this->nameroutes}/unit-performance/lookup_products")
			);
			
		if( $this->input->is_ajax_request())
		{
			$this->load->view(
				"polies/reports/unit_performance/modal/dialog",
				array("form_child" => $this->load->view("polies/reports/unit_performance/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:page") )
				->set_breadcrumb( lang("reports:unit_performance_heading"), base_url("{$this->nameroutes}/unit-performance") )
				->build('polies/reports/unit_performance/dialog', (isset($data) ? $data : NULL));
		}
	}
	
	public function export($type)
	{
		if ( $this->input->post() )
		{
			$this->load->helper( "export" );
			$this->load->helper( "report" );			
			switch ( $this->input->post("export_to") ) :
				case "pdf" :
					$this->export_pdf($type);
				break;	
				case "excel" :
					$this->export_excel($type);
				break;
			endswitch;
		}
	}

	private function export_pdf( $type )
	{
		if ($this->input->post())
		{
			$location = $this->session->userdata($type);
			$post_data = (object) $this->input->post("f") ;

			$collection = report_helper::get_unit_performance($post_data->date_start, $post_data->date_end, $location['section_id'] );	
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
							"section" => $this->db->where("SectionID", $location['section_id'])->get("SIMmSection")->row(),
							"file_name" => lang('reports:unit_performance_heading')
						);
						
			//print_r($collection);exit;
			
			$html_content =  $this->load->view( "polies/reports/unit_performance/export/pdf", $data, TRUE ); 
			$footer = '';//lang('reports:unit_performance_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:unit_performance_heading');		
			//print $html_content;exit(0);
						
			export_helper::print_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);		
	
			exit(0);
		}
		
		$this->index();
	}

	private function export_excel()
	{
		if ($this->input->post())
		{
			$date_start = $this->input->post("f[date_start]");
			$date_end = $this->input->post("f[date_end]");		
			report_helper::export_excel_unit_performance($date_start, $date_end );	
		}
		
		$this->index();

	}

	public function lookup_products( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'polies/reports/unit_performance/lookup/products');
		} else
		{
			redirect( base_url( "{$this->nameroutes}/unit-performance" ) );
		}
	}	

}

