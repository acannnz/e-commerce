<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Daily_cash_report_fo extends ADMIN_Controller
{
	protected $nameroutes = 'cashier/reports/daily_cash_report_fo';

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
		$option_user = $this->db->select("a.User_ID,a.Nama_Asli")
						->from("mUser a")
						->join("mUserGroup b",'a.User_ID = b.User_ID','INNER')
						->join("mGroup c",'c.Group_ID = b.Group_ID','INNER')
						->where([
							'a.Status_Aktif' => 1, 
							// 'a.User_ID <>' => '490'
						])
						->where_in("b.Group_ID", [64,66])
						->group_by("a.User_ID,a.Nama_Asli")
						->order_by("a.Nama_Asli")
						->get()
						->result();

		$data = array(
				"datepicker" => true,
				"form" => true,
				"url_export" => base_url("{$this->nameroutes}/export"),
				"tipe_pasien" => $this->patient_type_model->get_all(),
				"section" => $this->section_model->get_all(null, 0, ['TipePelayanan' => 'RJ']),
				"option_doctor" => option_doctor(),
				"option_shift" => config_item( 'shift' ) !== FALSE ? $this->db->order_by("IDShift")->get("SIMmShift")->result() : FALSE ,
				"option_user" => $option_user,
				"Shift" => $this->session->userdata('shift_id'),
				"User_ID" => $this->user_auth->User_ID,
			);
			
		if( $this->input->is_ajax_request() || $is_ajax )
		{
			$this->load->view(
				"general_payment/report/daily_cash_report_fo/modal/dialog",
				array("form_child" => $this->load->view("general_payment/report/daily_cash_report_fo/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( $this->page, base_url("{$this->nameroutes}") )
				->set_breadcrumb( 'Laporan Harian Kas FO' )
				->build('general_payment/report/daily_cash_report_fo/dialog', (isset($data) ? $data : NULL));
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
			$collection = report_helper::get_daily_cash_report_fo($post_data->date_start, $post_data->date_end, $post_data->user_id, $post_data->shift_id);	
			$data = array(
					"post_data" => $post_data,	
					"collection" => $collection,
				);
			
			$html_content =  $this->load->view( "general_payment/report/daily_cash_report_fo/export/pdf", $data, TRUE ); 
			$footer = 'Laporan Harian Kas FO'."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = 'Laporan Harian Kas FO';		
			
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
			report_helper::export_excel_daily_cash_report_fo($post_data->date_start, $post_data->date_end, $post_data->user_id, $post_data->shift_id);
			
			
			exit(0);
		}

	}
}
