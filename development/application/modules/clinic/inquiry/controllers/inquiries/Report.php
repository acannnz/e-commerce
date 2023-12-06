<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends Admin_Controller 
{ 
	protected $_translation = 'inventory';	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('inquiry_m');
		
		$this->page = "Laporan";
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("cahier/general-payment")."\";</script>";
			exit();
		} else
		{
			redirect( "cahier/general-payment" );
		}
	}
	
	public function stock_opname( $id, $with_price = NULL )
	{		
		$item = $this->db->where("No_Bukti", $id)->get( "GD_trOpname" )->row();	
		$collection = $this->inquiry_m->get_opname_detail( $item->No_Bukti );
		$section = $this->db->where('Lokasi_ID', $item->Lokasi_ID)->get('SIMmSection')->row();
		
		$data = array(
					"item"=> $item,
					"collection" => $collection,
					"section" => $section,
					"user" => $this->user_auth,
				);
		
		if($with_price)
		{
			$html_content =  $this->load->view( "inquiries/report/stock_opname_with_price", $data, TRUE ); 
			$orientation = 'L';
		} else {
			$html_content =  $this->load->view( "inquiries/report/stock_opname", $data, TRUE ); 
			$orientation = 'P';
		}
	
		$file_name = "Stock Opname";		
		$this->load->helper( "export" );

		export_helper::generate_pdf( $html_content, $file_name, '' , $margin_bottom = 5.0, $header = NULL, $margin_top = 5.0, $orientation);
		exit(0);
		
	}
}
