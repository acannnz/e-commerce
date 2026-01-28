<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Closing extends Admin_Controller
{
	protected $_translation = 'payable';	
	protected $_model = 'Closing_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('payable');
		
		$this->load->helper("payable");
		
		$this->page = "payable_close_books";
		$this->template->title( lang("closing:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{		
		$data = array(
				'page' => $this->page,
				"last_closing" => payable_helper::get_last_closing_period(),
				"beginning_balance_date" => payable_helper::get_beginning_balance_date(),
				"currency_rate" => payable_helper::get_rate_currency( date('Y-m-d') ),
				"closing_url" => base_url("payable/closing/close"),
				"form" => TRUE,
			);


		$this->template
			->set( "heading", lang("closing:page") )
			->set_breadcrumb( lang("payables:page") )
			->set_breadcrumb( lang("closing:breadcrumb"), base_url("payable/closing") )
			->build('closing/form', (isset($data) ? $data : NULL));
	}

	public function close()
	{
		if ( $this->input->post() && $this->input->is_ajax_request()  )
		{
			$response = array(
					"status" => "success",
					"message" => lang('closing:success_status'),
					"code" => "200",
				);
			
			$approver = $this->input->post('approver');
			$this->load->helper("Approval");
			if ( Approval_helper::approve( 'TUTUP BUKU AP', $approver['username'], $approver['password'] ) === FALSE )
			{
				$response["message"] = lang('auth_incorrect');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			}

			$closing_date = $this->input->post("closing_date");
			$date = DateTime::createFromFormat("Y-m", $closing_date);
			
			if ( $this->get_model()->check_un_posting_data( $closing_date ) === TRUE )
			{
				$response["message"] = lang('closing:un_posting_data');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit();
			
			}
			
			if ( config_item('WithLog') == 1 )
			{ 
				if ($this->get_model()->check_un_posting_logistic_data( $closing_date ) === TRUE )
				{
					$response["message"] = lang('closing:un_posting_logistic_data');
					$response["status"] = "error";
					$response["code"] = "500";
					
					print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
					exit();
				}
			}
			
			if ( $check = $this->get_model()->check_voucher_incorrect_transaction( $closing_date ) !== FALSE )
			{
				$response["message"] = sprintf(lang('closing:voucher_incorrect_transaction'), @$check->No_Voucher, $date->format('F Y'));
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit();
			
			}
			
			if ( $this->get_model()->check_previous_month_closing( $closing_date ) === FALSE )
			{
				$response["message"] = lang('closing:unclosing_previous_month');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit();
			
			}

			/*if ( $this->get_model()->check_current_period_transaction( $closing_date ) === FALSE )
			{
				$response["message"] = lang('closing:period_empty_transaction');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit();
			}*/
			
			// Validasi dimatakan sementara
			//$this->_balance_match(); # cek kecocokan saldo
			
			if ( $this->get_model()->closing( $closing_date ) === FALSE )
			{
				$response["message"] = lang('closing:failure_status');
				$response["status"] = "error";
				$response["code"] = "500";
				
			}

			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit();
		}
		
		$this->load->view('closing/modal/closing');
		
	}
	
	private function _balance_match()
	{	
		$_balance_date = $this->get_model()->get_last_gl_balance_date();
	
		if( $this->get_model()->check_accordance_with_gl( $_balance_date ) === FALSE )
		{
			$response["message"] = lang('closing:not_accordance_with_gl');
			$response["status"] = "error";
			$response["code"] = "500";
			
			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit();
		}
		
		if( $this->get_model()->trouble_ap_tipe_not_macth( $_balance_date ) )
		{
			$response["message"] = lang('closing:trouble_ap_tipe_not_macth');
			$response["status"] = "error";
			$response["code"] = "500";
			
			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit();
		}

		if( $this->get_model()->trouble_ap_balance_not_macth( $_balance_date ) )
		{
			$response["message"] = lang('closing:trouble_ap_balance_not_macth');
			$response["status"] = "error";
			$response["code"] = "500";
			
			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit();
		}

		if( $this->get_model()->check_cancelled_card_payable( $_balance_date ) === TRUE )
		{
			$response["message"] = lang('closing:check_cancelled_card_payable');
			$response["status"] = "error";
			$response["code"] = "500";
			
			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit();
		}
		
		if( $this->get_model()->check_not_related_card_payable( $_balance_date )  === TRUE )
		{
			$response["message"] = lang('closing:check_not_related_card_payable');
			$response["status"] = "error";
			$response["code"] = "500";
			
			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit();
		}		
		
		if( $this->get_model()->recap_payable_not_macth( $_balance_date )  === TRUE )
		{
			$response["message"] = lang('closing:recap_payable_not_macth');
			$response["status"] = "error";
			$response["code"] = "500";
			
			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit();
		}		

		if( $this->get_model()->card_payable_not_macth_aging() === TRUE )
		{
			$response["message"] = lang('closing:card_payable_not_macth_aging');
			$response["status"] = "error";
			$response["code"] = "500";
			
			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit();
		}		

		if( $check = $this->get_model()->card_type_payable_not_macth_aging() === TRUE )
		{
			$response["message"] = sprintf(lang('closing:card_type_payable_not_macth_aging'), $check->Tipe );
			$response["status"] = "error";
			$response["code"] = "500";
			
			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit();
		}		
		
		
	}
	
	public function cancel()
	{

		$data = array(
				'page' => strtolower(__FUNCTION__)."_".$this->page,
				"form" => TRUE,
				"closing_cancel_url" => base_url("payable/closing/closing_cancel"),
				"last_closing" => payable_helper::get_last_closing_period(),
				"beginning_balance_date" => payable_helper::get_beginning_balance_date(),
			);
									
		$this->template->title( lang("closing:cancel_page") . ' - ' . $this->config->item('company_name') );

		$this->template
			->set( "heading", lang("closing:cancel_page") )
			->set_breadcrumb( lang("payables:page") )
			->set_breadcrumb( lang("closing:breadcrumb"), base_url("payable/closing") )
			->set_breadcrumb( lang("closing:cancel_breadcrumb"), base_url("payable/closing/cancel")  )
			->build('closing/form_cancel', (isset($data) ? $data : NULL));
	}
	
	public function closing_cancel()
	{
		if ( $this->input->post() && $this->input->is_ajax_request() )
		{
			
			$response = array(
					"status" => "success",
					"message" => lang('closing:cancel_success_status'),
					"code" => "200",
				);
			
			$approver = $this->input->post('approver');
			$this->load->helper("Approval");
			if ( Approval_helper::approve( 'BATAL TUTUP BUKU AP', $approver['username'], $approver['password'] ) === FALSE )
			{
				$response["message"] = lang('auth_incorrect');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			}

			$cancel_date = $this->input->post("cancel_date");
			$date = DateTime::createFromFormat("Y-m", $cancel_date);
			
			if( $this->get_model()->check_general_ledger_closing_period( $cancel_date ) === TRUE )
			{
				$response["message"] = lang('closing:check_general_ledger_closing_period');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit();
			}	

			if( $this->get_model()->check_next_closing_period( $cancel_date ) === TRUE )
			{
				$response["message"] = lang('closing:check_next_closing_period');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit();
			}	
								
			if ( $this->get_model()->closing_cancel( $cancel_date ) === FALSE )
			{
				$response["message"] = lang('closing:cancel_proses_failure');
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit;
		}
		
		$this->load->view('closing/modal/closing_cancel');
	}
}

