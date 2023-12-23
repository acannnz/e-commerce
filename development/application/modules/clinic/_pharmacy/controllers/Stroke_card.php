<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Stroke_card extends Admin_Controller
{ 
	//protected $_translation = 'products';	
	protected $_model = 'pharmacy_m';  
	
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
			echo "<script language=\"javascript\">window.location=\"".base_url("reports/dialog")."\";</script>";
			exit();
		} else
		{
			redirect( "registration" );
		}
	}
	
	public function dialog ()
	{
		if( $this->input->is_ajax_request() )
			{
				$this->load->view("billing/form/dialog");
			} else
			{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:page"), base_url("reports") )
				->set_breadcrumb( lang("reports:breadcrumb") )
				->build('billing/form/dialog', (isset($data) ? $data : NULL));
			}
	}
	
	public function selling($NoBukti)
	{
		if ($NoBukti)
		{
			
			$data = $this->db->where("NoBukti", $NoBukti)->get("BILLFarmasi")->row();
			$detail = $this->db->where("NoBukti", $NoBukti)->get("BILLFarmasiDetail")->result();
			
			// print_r($data);
			// print_r($detail);
			// exit;
			
			$data = array(
							"data" => $data,
							"detaiil" => $detail,
							"file_name" => 'Bukti Penerimaan',
						);
			$html_content =  $this->load->view( "recipients/stroke_recipient/print", $data, 'Bukti Penerimaan' ); 
			
			$file_name = 'Stoke Farmasi';		
			$this->load->helper( "report" );
	
			report_helper::generate_pdf( $html_content, $file_name, date("Y-M-d") , $margin_bottom = 1.0, $header = NULL, $margin_top = 0.3, $orientation = 'P');

	
			exit(0);
		}
	}
	
	public function lookup_products( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'billing/lookup/products');
		} else
		{
			redirect( base_url( "common/chart_templates/lookup" ) );
		}
	}	
}
