<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_opname extends Admin_Controller
{ 
	protected $_translation = 'reports';	
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('pharmacy');
		$this->load->model("section_model");
		
		$this->page = lang( 'reports:stock_opname_label' );
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("pharmacy/reports/stock-opname")."\";</script>";
			exit();
		} else
		{
			redirect( "pharmacy/reports/stock-opname/dialog" );
		}
	}
	

	public function dialog ( $is_ajax = FALSE)
	{
		$location = $this->session->userdata('pharmacy');
		$section = $this->db->where("SectionID", $location['section_id'])->get("SIMmSection")->row();
		$item = (object) array(
				"SectionID" => $section->SectionID,
				"SectionName" => $section->SectionName
			);

		$data = array(
				"item" => $item,
				"datepicker" => true,
				"form" => true,
				"lookup_products" => base_url("pharmacy/reports/stock-opname/lookup_products")
			);
			
		if( $this->input->is_ajax_request() || $is_ajax )
		{
			$this->load->view(
				"reports/stock_opname/modal/dialog",
				array("form_child" => $this->load->view("reports/stock_opname/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:page") )
				->set_breadcrumb( lang("reports:stock_opname_label"), base_url("pharmacy/reports/stock-opname") )
				->build('reports/stock_opname/dialog', (isset($data) ? $data : NULL));
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
			$collection = report_helper::get_stock_opname($post_data->date_start, $post_data->date_end, $post_data->SectionID );
			$section = $this->section_model->get_one($post_data->SectionID);					
			$data = [
				"post_data" 	=> $post_data,	
				"collection" 	=> $collection,
				"section" 		=> $section,
			];
						
			
			$html_content =  $this->load->view( "reports/stock_opname/export/pdf", $data, TRUE ); 
			$footer = lang('reports:stock_opname_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:stock_opname_label');		
			
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
			report_helper::export_excel_stock_opname($post_data->date_start, $post_data->date_end, $post_data->SectionID, (int) @$post_data->show_zero_difference );
		}
		
		redirect("reports/dialog");

	}

	public function lookup_products( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'reports/stock_opname/lookup/products');
		} else
		{
			redirect( base_url( "pharmacy/reports/stock-opname" ) );
		}
	}	

}

