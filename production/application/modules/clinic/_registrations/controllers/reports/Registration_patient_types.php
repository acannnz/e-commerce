<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registration_patient_types extends Admin_Controller
{ 
	protected $_translation = 'reports';	
	protected $nameroutes = 'registrations/reports/registration-patient-types';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('registration');
		
		$this->page = lang( 'reports:registration_patient_type_heading' );
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
	

	public function dialog ( $ajax = FALSE)
	{
		$data = array(
			"datepicker" => true,
			"form" => true,
		);
		
		if( $this->input->is_ajax_request() || $ajax )
		{
			$this->load->view(
				"reports/registration_patient_types/modal/dialog",
				array("form_child" => $this->load->view("reports/registration_patient_types/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:page") )
				->set_breadcrumb( lang("reports:registration_patient_type_heading"), base_url("{$this->nameroutes}") )
				->build('reports/registration_patient_types/dialog', $data );
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

			$collection = report_helper::get_registration_patient_types($post_data->date_start, $post_data->date_end );	
						
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
						);
			//print_r($collection);exit;
			
			$html_content =  $this->load->view( "reports/registration_patient_types/export/pdf", $data, TRUE ); 
			$footer = lang('reports:registration_patient_type_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:registration_patient_type_label');		
			
			//print $html_content;exit(0);
			
			export_helper::print_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		redirect("{$this->nameroutes}/dialog");

	}

	private function export_excel()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post("f") ;

			$collection = reports_helper::get_registration_patient_types($post_data->date_start, $post_data->date_end );	
						
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
						);
			//print_r($data);exit;
			
			$html_content =  $this->load->view( "reports/registration_patient_types/export/pdf", $data, TRUE ); 
			$footer = lang('reports:patient_reservation_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:patient_reservation_label');		
			
			//print $html_content;exit(0);
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		redirect("{$this->nameroutes}/dialog");

	}

	public function lookup_products( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'reports/registration_patient_types/lookup/products');
		} else
		{
			redirect( base_url( "{$this->nameroutes}" ) );
		}
	}	

}

