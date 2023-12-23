<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export extends Admin_Controller 
{ 
	protected $_translation = 'payable';	
	protected $_model = 'voucher_m';  
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('payable');
		
		$this->load->helper( "payable" );
		
		$this->page = "Print Detail Biaya";
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
			redirect( "payable/vouchers" );
		}
	}
	
	public function voucher(){
		
		$No_Voucher = $this->input->get("No_Voucher");
		$item = $this->get_model()->get_row( $No_Voucher );
		$data = array(
					"item"=> $item,
					"collection" => $this->get_model()->get_detail_collection( $No_Voucher ),
					"spelled" => ucwords( payable_helper::money_to_text( round($item->Sisa, 2) )." Rupiah" ),
					"user" => $this->user_auth,
				);

		$html_content =  $this->load->view( "vouchers/print/voucher", $data, TRUE ); 
		$file_name = "Voucher";		
		$this->load->helper( "export" );

		export_helper::generate_pdf( $html_content, $file_name, '' , $margin_bottom = 5.0, $header = NULL, $margin_top = 10.0, $orientation = 'L');
		exit(0);
		
	}
}
