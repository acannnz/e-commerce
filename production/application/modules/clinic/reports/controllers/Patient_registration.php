<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Patient_registration extends ADMIN_Controller
{
	protected $nameroutes = 'reports/patient_registration';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('reports');
		$this->load->model([
			"patient_type_model",
			"section_model",
			"patient_model",
			"desa_model",
			"class_model"
		]);

		$this->page = 'Laporan';
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}

	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("reports/patient_registration")."\";</script>";
			exit();
		} else
		{
			redirect( "reports/patient_registration/dialog" );
		}
	}

	public function dialog ( $is_ajax = FALSE)
	{
		$data = array(
				"datepicker" => true,
				"form" => true,
				"url_export" => base_url("reports/patient_registration/export"),
				"tipe_pasien" => $this->patient_type_model->get_all(),
				"section" => $this->section_model->get_all(null, 0, ['TipePelayanan' => 'RJ']),
				"kelas" => $this->class_model->get_all(),
				"desa" => $this->desa_model->get_all()
			);
			// print_r($data);exit;
			
		if( $this->input->is_ajax_request() || $is_ajax )
		{
			$this->load->view(
				"reports/patient_registration/modal/dialog",
				array("form_child" => $this->load->view("reports/patient_registration/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( $this->page, base_url("reports/patient_registration") )
				->set_breadcrumb( 'Registrasi Pasien' )
				->build('reports/reports/patient_registration/dialog', (isset($data) ? $data : NULL));
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
			// print_r($post_data);exit;
			$section = $this->section_model->get_one( $post_data->section );
			// $kelas = $this->class_model->get_one($post_data->kelas);
			$collection = report_helper::get_registration_patient_types($post_data->date_start, $post_data->date_end, $post_data->tipe_pasien, $post_data->section, $post_data->jeniskelamin, $post_data->desa, $post_data->kelas, $post_data->patient_age );	
			// print_r($collection);exit;
			$data = array(
						"post_data" => $post_data,	
						"collection" => $collection,
						"section" => $section,
						// "kelas" => $kelas
					);
			// print_r($data);exit;
			$html_content =  $this->load->view( "reports/reports/patient_registration/export/pdf", $data, TRUE ); 
			$footer = 'Laporan Registrasi Pasien'."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = 'Laporan Registrasi Pasien';		
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		redirect("{$this->nameroutes}/dialog");

	}

	private function export_excel()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post('f');
			report_helper::export_excel_registration_patient_types($post_data->date_start, $post_data->date_end, $post_data->tipe_pasien, $post_data->section );
			
			
			exit(0);
		}

	}
}
