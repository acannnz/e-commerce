<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Polyclinic_registrations extends Admin_Controller
{ 
	protected $_translation = 'reports';	
	protected $nameroutes = 'registrations/reports/polyclinic-registrations';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('registration');
		
		$this->page = lang( 'reports:polyclinic_registration_heading' );
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
			"datatables" => true,
			"form" => true,
			"lookup_doctor" => base_url("{$this->nameroutes}/lookup_doctor"),
		);
	
		if( $this->input->is_ajax_request() || $ajax )
		{
			$this->load->view(
				"reports/polyclinic_registrations/modal/dialog",
				array("form_child" => $this->load->view("reports/polyclinic_registrations/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:page") )
				->set_breadcrumb( lang("reports:polyclinic_registration_heading"), base_url("{$this->nameroutes}")  )
				->build('reports/polyclinic_registrations/dialog', $data );
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

			$collection = report_helper::get_polyclinic_registrations($post_data->date_start, $post_data->date_end, $post_data->DokterID );	
						
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
						);
			//print_r($data);exit;
			
			$html_content =  $this->load->view( "reports/polyclinic_registrations/export/pdf", $data, TRUE ); 
			$footer = lang('reports:polyclinic_registrations_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:polyclinic_registrations_label');		
			
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

			$collection = reports_helper::get_polyclinic_registrations($post_data->date_start, $post_data->date_end );	
						
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
						);
			//print_r($data);exit;
			
			$html_content =  $this->load->view( "reports/polyclinic_registrations/export/pdf", $data, TRUE ); 
			$footer = lang('reports:patient_reservation_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:patient_reservation_label');		
			
			//print $html_content;exit(0);
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		redirect("{$this->nameroutes}/dialog");

	}

	public function lookup_doctor( $is_ajax_request = false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'lookup/supplier_datatables', array("type" => "doctor") );
		} else
		{
			redirect( base_url( "{$this->nameroutes}" ) );
		}
	}	

}

