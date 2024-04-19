<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Doctor_drug_incentives extends Admin_Controller
{ 
	protected $_translation = 'reports';	
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('pharmacy');
		
		$this->page = lang( 'reports:page' );
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("pharmacy/reports/doctor-drug-incentives")."\";</script>";
			exit();
		} else
		{
			redirect( "pharmacy/reports/doctor-drug-incentives/dialog" );
		}
	}
	

	public function dialog ( $is_ajax = FALSE)
	{
		$data = [
			"option_doctor" => option_doctor(),
			"datepicker" => true,
			"form" => true,
		];
			
		if( $this->input->is_ajax_request() || $is_ajax )
		{
			$this->load->view(
				"reports/doctor_drug_incentives/modal/dialog",
				array("form_child" => $this->load->view("reports/doctor_drug_incentives/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:page") )
				->set_breadcrumb( 'Laporan Insentif Obat', base_url("pharmacy/reports/doctor-drug-incentives") )
				->build('reports/doctor_drug_incentives/dialog', (isset($data) ? $data : NULL));
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
			$date_start = $this->input->post('f[date_start]');
			$date_end = $this->input->post('f[date_end]');
			
			report_helper::get_recap_transactions($date_start, $date_end);

			$collection = report_helper::get_recap_transactions($post_data->date_start, $post_data->date_end, $post_data->SectionID );	
			$section = $this->db->where("SectionID", config_item('section_id'))->get("SIMmSection")->row();						
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
							"section" => $section,
						);
			
			$html_content =  $this->load->view( "reports/doctor_drug_incentives/export/pdf", $data, TRUE ); 
			$footer = lang('reports:recap_transaction_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:recap_transaction_label');		
			
			print $html_content;exit(0);
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'P', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		redirect("reports/dialog");

	}

	private function export_excel()
	{
		if ($this->input->post())
		{
			$date_start = $this->input->post('f[date_start]');
			$date_end = $this->input->post('f[date_end]');		
			$doctor_id = $this->input->post('f[doctor_id]');
			report_helper::export_doctor_drug_incentives($date_start, $date_end, $doctor_id);
			
			exit(0);
		}
		
		show_404();
	}

	public function lookup_products( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'reports/recap_transactions/lookup/products');
		} else
		{
			redirect( base_url( "pharmacy/reports/doctor-drug-incentives" ) );
		}
	}	

}

