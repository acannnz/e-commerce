<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recap_stocks extends Admin_Controller
{ 
	protected $_translation = 'reports';	
	protected $nameroutes = 'reports/recap_stocks';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('reports');
		$this->load->model("section_model");
		
		$this->page = lang( 'reports:page' );
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("{$this->nameroutes}")."\";</script>";
			exit();
		} else
		{
			redirect( "{$this->nameroutes}/dialog" );
		}
	}
	

	public function dialog ( $is_ajax = FALSE)
	{
		$data = array(
				"option_section" => $this->section_model->get_all(null, 0, ['StatusAktif' => 1], ['RJ','FARMASI','GUDANG']),
				"datepicker" => true,
				"form" => true,
				"lookup_products" => base_url("{$this->nameroutes}/lookup_products"),
				"url_export" => base_url("{$this->nameroutes}/export"),
			);

		if( $this->input->is_ajax_request() || $is_ajax )
		{
			$this->load->view(
				"reports/reports/recap_stocks/modal/dialog",
				array("form_child" => $this->load->view("reports/reports/recap_stocks/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:patient_reservation_page"), base_url("{$this->nameroutes}") )
				->set_breadcrumb( lang("reports:patient_reservation_breadcrumb") )
				->build('reports/reports/recap_stocks/dialog', (isset($data) ? $data : NULL));
		}
	}
	
	public function export()
	{
		if ( $this->input->post() )
		{
			$this->load->helper( "export" );
			$this->load->helper( "report" );
			
			switch ( $this->input->post("export_to") ) :
				case "pdf" :
					$this->export_pdf();
				break;	
				case "excel" :
					$this->export_excel();
				break;
			endswitch;
		}
	}

	private function export_pdf()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post("f") ;
			$collection = report_helper::get_recap_stocks($post_data->date_start, $post_data->date_end, $post_data->Lokasi_ID );	
			$section = $this->section_model->get_by(['Lokasi_ID' => $post_data->Lokasi_ID]);					
			$data = [
				"post_data" 	=> $post_data,	
				"collection" 	=> $collection,
				"section" 		=> $section,
			];
						
			$html_content =  $this->load->view( "reports/reports/recap_stocks/export/pdf", $data, TRUE ); 
			$footer = lang('reports:recap_stock_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			$file_name = lang('reports:recap_stock_label');		
						
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			exit(0);
		}

	}

	private function export_excel()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post('f');
			report_helper::export_excel_get_recap_stocks($post_data->date_start, $post_data->date_end, $post_data->Lokasi_ID);
			
			exit(0);
		}

	}

	public function lookup_products( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'reports/reports/recap_stocks/lookup/products');
		} else
		{
			redirect( base_url( "{$this->nameroutes}" ) );
		}
	}	

}

