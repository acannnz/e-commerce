<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Most_diseases extends ADMIN_Controller
{
	protected $nameroutes = 'reports/most_diseases';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('reports');

		$this->page = 'Laporan';
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}

	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("reports/most_diseases")."\";</script>";
			exit();
		} else
		{
			redirect( "reports/most_diseases/dialog" );
		}
	}

	public function dialog ( $is_ajax = FALSE)
	{
		$data = array(
				"datepicker" => true,
				"form" => true,
				"url_export" => base_url("reports/most_diseases/export"),
			);
			
		if( $this->input->is_ajax_request() || $is_ajax )
		{
			$this->load->view(
				"reports/most_diseases/modal/dialog",
				array("form_child" => $this->load->view("reports/most_diseases/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( $this->page, base_url("reports/most_diseases") )
				->set_breadcrumb( 'Laporan 10 Besar Penyakit' )
				->build('reports/reports/most_diseases/dialog', (isset($data) ? $data : NULL));
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
			$post_data = (object) $this->input->post("f");
			$collection = report_helper::get_most_diseases($post_data->date_start, $post_data->date_end );	
			
			$data = array(
						"post_data" => $post_data,	
						"collection" => $collection,
					);
			
			$html_content =  $this->load->view( "reports/reports/most_diseases/export/pdf", $data, TRUE ); 
			$footer = 'Laporan 10 Besar Penyakit'."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = 'Laporan 10 Besar Penyakit';		
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'P', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		redirect("{$this->nameroutes}/dialog");

	}

	private function export_excel()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post('f');
			report_helper::export_excel_most_diseases($post_data->date_start, $post_data->date_end );
			
			
			exit(0);
		}

	}
}
