<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recap_transactions extends Admin_Controller
{ 
	protected $_translation = 'reports';	
	protected $nameroutes = 'reports/recap_transactions';
	
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
		// $location = $this->session->userdata('pharmacy');
		$section  = $this->section_model->get_one("SECT0002");
		$item = (object) array(
			"SectionID" => $section->SectionID
		);
		$data = array(
			"item" => $item,
			"datepicker" => true,
			"form" => true,
			"option_shift" => config_item( 'shift' ) !== FALSE ? $this->db->order_by("IDShift")->get("SIMmShift")->result() : FALSE ,
			"option_user" => $this->db->where(['Status_Aktif' => 1, 'User_ID <>' => '490'])->order_by("Nama_Asli")->get("mUser")->result(),
		);
			
		if( $this->input->is_ajax_request() || $is_ajax )
		{
			$this->load->view(
				"reports/reports/recap_transactions/modal/dialog",
				array("form_child" => $this->load->view("reports/reports/recap_transactions/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:page") )
				->set_breadcrumb( 'Laporan Transaksi Obat', base_url("{$this->nameroutes}") )
				->build('reports/reports/recap_transactions/dialog', (isset($data) ? $data : NULL));
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
			$post_data = (object) $this->input->post('f');
			$collection = report_helper::get_recap_transactions($post_data->date_start, $post_data->date_end, $post_data->SectionID, $post_data->shift_id, $post_data->user_id );	
			$section = $this->section_model->get_one($post_data->SectionID);
			$option_shift = $this->db->where("IDShift",$post_data->shift_id )->get("SIMmShift")->row();
			$option_user = $this->db->where("User_ID", $post_data->user_id )->get("mUser")->row();							
			$data = [
				"post_data" => $post_data,	
				"collection" => $collection,
				"section" => $section,
				"shift" => $option_shift,
				"user"	=> $option_user,
			];
			
			$html_content =  $this->load->view( "reports/reports/recap_transactions/export/pdf", $data, TRUE ); 
			$footer = lang('reports:recap_transaction_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			$file_name = lang('reports:recap_transaction_label');		
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
			exit(0);
		}
		
		redirect("reports/dialog");

	}

	private function export_excel()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post('f');
			report_helper::export_excel_recap_transactions($post_data->date_start, $post_data->date_end, $post_data->SectionID, $post_data->shift_id,  $post_data->user_id);
			
			
			exit(0);
		}
	}
	

}

