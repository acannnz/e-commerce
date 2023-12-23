<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Patient_reservations extends Admin_Controller
{ 
	protected $_translation = 'reports';	
	protected $_model = 'report_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('reservation');
		
		$this->page = lang( 'reports:page' );
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("reservations/reports/patient-reservations")."\";</script>";
			exit();
		} else
		{
			redirect( "reservations/reports/patient-reservations/dialog" );
		}
	}
	

	public function dialog ( $ajax = FALSE)
	{
		
		if( $this->input->is_ajax_request() || $ajax )
		{
			$this->load->view(
				"reports/patient_reservations/modal/dialog",
				array("form_child" => $this->load->view("reports/patient_reservations/dialog", array(), true))
			);
		} else
		{
			$data = array(
					"datepicker" => true,
					"form" => true,
				);
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:patient_reservation_page"), base_url("reservations/reports/patient-reservations") )
				->set_breadcrumb( lang("reports:patient_reservation_breadcrumb") )
				->build('reports/patient_reservations/dialog', (isset($data) ? $data : NULL));
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

			$collection = report_helper::get_patien_reservations($post_data->date_start, $post_data->date_end );	
						
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
						);
			//print_r($data);exit;
			
			$html_content =  $this->load->view( "reports/patient_reservations/export/pdf", $data, TRUE ); 
			$footer = lang('reports:patient_reservation_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:patient_reservation_label');		
			
			//print $html_content;exit(0);
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		redirect("reports/dialog");

	}

	private function export_excel()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post("f") ;

			$collection = reports_helper::get_patien_reservations($post_data->date_start, $post_data->date_end );	
						
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
						);
			//print_r($data);exit;
			
			$html_content =  $this->load->view( "reports/patient_reservations/export/pdf", $data, TRUE ); 
			$footer = lang('reports:patient_reservation_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:patient_reservation_label');		
			
			//print $html_content;exit(0);
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		redirect("reports/dialog");

	}

	public function lookup_products( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'reports/patient_reservations/lookup/products');
		} else
		{
			redirect( base_url( "reservations/reports/patient-reservations" ) );
		}
	}	

}

