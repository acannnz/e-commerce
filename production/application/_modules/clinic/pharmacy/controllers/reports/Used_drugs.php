<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Used_drugs extends Admin_Controller
{
	protected $_translation = 'reports';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('pharmacy');
		$this->load->model("section_model");
		
		$this->page = lang('reports:page');
		$this->template->title($this->page . ' - ' . $this->config->item('company_name'));
	}

	public function index()
	{

		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("pharmacy/reports/used-drugs")."\";</script>";
			exit();
		} else
		{
			redirect( "pharmacy/reports/used-drugs/dialog" );
		}
	}

	public function dialog ( $is_ajax = FALSE)
	{
		$location = $this->session->userdata('pharmacy');
		$section = $this->section_model->get_one($location['section_id']);
		$item = (object) [
				"Lokasi_ID" => $section->Lokasi_ID
		];
		$data = array(
				"item" => $item,
				"datepicker" => true,
				"form" => true,
				"datatables" => true,
				"option_poli" => $this->section_model->get_all(['TipePelayanan' => 'RJ']),
				"option_type_patients" => $this->db->get('SIMmJenisKerjasama')->result(),
			);
			
		if( $this->input->is_ajax_request() || $is_ajax )
		{
			$this->load->view(
				"reports/used_drugs/modal/dialog",
				array("form_child" => $this->load->view("reports/used_drugs/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb("Laporan Penggunaan Obat", base_url("pharmacy/reports/used-drugs") )
				->set_breadcrumb( "Laporan Penggunaan Obat")
				->build('reports/used_drugs/dialog', (isset($data) ? $data : NULL));
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

	public function export_pdf()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post("f") ;
			$collection = report_helper::get_used_drugs($post_data->date_start, $post_data->date_end, $post_data->SectionID, $post_data->KerjasamaID );	
			$section = $this->section_model->get_one($post_data->SectionID);					
			$data = [
				"post_data" 	=> $post_data,	
				"collection" 	=> $collection,
				"section" 		=> $section,
			];
						
			$html_content =  $this->load->view( "reports/used_drugs/export/pdf", $data, TRUE ); 
			$footer = 'Laporan Penggunaan Obat'."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			$file_name = 'Laporan Penggunaan Obat';		
						
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			exit(0);
		}
		
		redirect("reports/dialog");
	}

	public function export_excel()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post('f');
			report_helper::export_excel_get_used_drugs($post_data->date_start, $post_data->date_end, $post_data->SectionID, $post_data->KerjasamaID);
			
			exit(0);
		}
		redirect("reports/dialog");
	}
}
