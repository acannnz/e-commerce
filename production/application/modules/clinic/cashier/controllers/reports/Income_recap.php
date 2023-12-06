<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Income_recap extends ADMIN_Controller
{
	protected $nameroutes = 'cashier/reports/income_recap';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('cashier');
		$this->load->model([
			"patient_type_model",
			"section_model"
		]);

		$this->page = 'Laporan';
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
				"datepicker" => true,
				"form" => true,
				"url_export" => base_url("{$this->nameroutes}/export"),
				"tipe_pasien" => $this->patient_type_model->get_all(),
				"section" => $this->section_model->get_all(null, 0, ['TipePelayanan' => 'RJ']),
				"option_doctor" => option_doctor()
			);
			
		if( $this->input->is_ajax_request() || $is_ajax )
		{
			$this->load->view(
				"general_payment/report/income_recap/modal/dialog",
				array("form_child" => $this->load->view("general_payment/report/income_recap/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( $this->page, base_url("{$this->nameroutes}") )
				->set_breadcrumb( 'Laporan Rekap Transaksi' )
				->build('general_payment/report/income_recap/dialog', (isset($data) ? $data : NULL));
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
			$section = $this->section_model->get_one( $post_data->section );
			$collection = report_helper::get_income_recap($post_data->date_start, $post_data->date_end, $post_data->tipe_pasien, $post_data->section, $post_data->doctor_id );	
			$data = array(
					"post_data" => $post_data,	
					"collection" => $collection,
					"section" => $section
				);
			
			$html_content =  $this->load->view( "general_payment/report/income_recap/export/pdf", $data, TRUE ); 
			$footer = 'Laporan Rekap Transaksi Kasir'."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = 'Laporan Rekap Transaksi Kasir';		
			
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
			report_helper::export_excel_income_recap($post_data->date_start, $post_data->date_end, $post_data->tipe_pasien, $post_data->section, $post_data->doctor_id);
			
			
			exit(0);
		}

	}
}
