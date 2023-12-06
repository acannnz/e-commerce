<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends Admin_Controller
{
	protected $_translation = '';	
	protected $_model = '';
	
	public function __construct()  
	{
		parent::__construct();
		$this->simple_login->check_user_role('cashier');
				
		$this->load->helper("general_payment");
	}
	
	public function index( $item = NULL )
	{		
		$data = array(
			"item" => $item,
			"collection" => general_payment_helper::get_detail_payment( @$item->NoBukti ),
		);
		
		$this->load->view( 'general_payment/form/payment', @$data );	
	}

	public function view( $item = NULL )
	{		
		$data = array(
			"item" => $item,
			"collection" => general_payment_helper::get_detail_payment( @$item->NoBukti ),
		);
		
		$this->load->view( 'general_payment/form/payment_view', @$data );	
	}
	
	public function lookup_form_credit_card( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$data = array(
				"lookup_merchan" => base_url("cashier/general-payments/payment/lookup_merchan")
			);
			
			$this->load->view( 
					'general_payment/modal/credit_card', 
					array('form_child' => $this->load->view('general_payment/form/credit_card', $data, true))
				);
		} 
	}
	
	public function lookup_form_credit_bon( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$data = array(
				"lookup_customer" => base_url("cashier/general-payments/payment/lookup_customer"),
			);
			
			$this->load->view( 
					'general_payment/modal/credit_bon', 
					array('form_child' => $this->load->view('general_payment/form/credit_bon', $data, true))
				);
		} 
	}

	public function lookup_merchan( $is_ajax_request = false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'cashier/general_payment/lookup/merchan' );
		} 
	}
	
	public function lookup_customer( $is_ajax_request = false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'cashier/general_payment/lookup/credit_customer' );
		} 
	}	
}