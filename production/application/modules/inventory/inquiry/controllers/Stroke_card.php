<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Stroke_card extends Admin_Controller
{ 
	//protected $_translation = 'products';	
	protected $_model = 'reservation_m';  
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper( "recipient" );
		
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
	
	public function print_report($recipient_code, $stat = false)
	{
		if ($stat)
		{
			$product  = recipient_helper::get_product($recipient_code);		
			$recipient  = recipient_helper::get_recipient($recipient_code);	
			
			$sub_total = 0;
			$discount_total = 0;
			$grand_total = 0;
			
			foreach ($product as $products) :
				$sub_total += $products['total_by_product'];
			endforeach;
			
			$data = array(
							"reports" => $product,
							"sub_total" => $sub_total,
							"other"=> $recipient,
							"grand_total" => $recipient[0]['payment_total'],
							"recipient_code" => $recipient_code,
							"file_name" => 'Bukti Penerimaan',
						);
			$html_content =  $this->load->view( "recipients/stroke_recipient/print", $data, 'Bukti Penerimaan' ); 
			
			$file_name = 'Stroke Recipient';		
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
