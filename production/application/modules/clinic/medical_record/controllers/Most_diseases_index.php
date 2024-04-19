<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Most_diseases_index extends ADMIN_Controller
{
	protected $nameroutes = 'medical_record/most_diseases_index';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('medical_record');

		$this->page = 'Laporan';
		$this->report_title = 'Laporan Index Penyakit Rawat Jalan';
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );

		$this->load->model("section_model");
	}

	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("medical_record/most_diseases_index")."\";</script>";
			exit();
		} else
		{
			redirect( "medical_record/most_diseases_index/dialog" );
		}
	}

	public function dialog ( $is_ajax = FALSE)
	{
		$data = array(
				"datepicker" => true,
				"form" => true,
				"url_export" => base_url("medical_record/most_diseases_index/export"),
				"report_title" => $this->report_title,
				'option_section' => $this->section_model->to_list_data(['TipePelayanan' => 'RJ','StatusAktif' => 1]),
			);
			
		if( $this->input->is_ajax_request() || $is_ajax )
		{
			$this->load->view(
				"medical_record/reports/most_diseases_index/modal/dialog",
				array("form_child" => $this->load->view("medical_record/reports/most_diseases_index/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( $this->page, base_url("medical_record/most_diseases_index") )
				->set_breadcrumb( $this->report_title)
				->build('medical_record/reports/most_diseases_index/dialog', (isset($data) ? $data : NULL));
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
			$collection = report_helper::get_most_diseases_index($post_data->date_start, $post_data->date_end, $post_data->section_id );	
			$section = $this->section_model->get_one($post_data->section_id);

			$data = array(
						"post_data" => $post_data,	
						"collection" => $collection,
						"report_title" => $this->report_title,
						"section" => @$section
					);
			
			$html_content =  $this->load->view( "medical_record/reports/most_diseases_index/export/pdf", $data, TRUE ); 
			$footer = $this->report_title."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			export_helper::generate_pdf( $html_content, $data['report_title'].'.pdf', $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		redirect("{$this->nameroutes}/dialog");

	}

	private function export_excel()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post('f');
			report_helper::export_excel_most_diseases_index($post_data->date_start, $post_data->date_end, $post_data->section_id, $this->report_title );

			exit(0);
		}

	}
}
