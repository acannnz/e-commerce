<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Closing extends Admin_Controller
{
	protected $_translation = 'general_ledger';	
	protected $_model = 'closing_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_ledger');
		
		$this->load->helper("general_ledger");
		
		$this->page = "general_ledger_closing";
		$this->template->title( lang("closing:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{	
		$data = array(
				"page" => $this->page,
				"form" => TRUE,
				"datatables" => TRUE,
				"last_period_closing" => $this->get_model()->last_period_closing(),
				"currency_rate" => general_ledger_helper::get_rate_currency( date('Y-m-d') ),
				"closing_url" => base_url("general-ledger/closing/close"),
			);
		
		$this->template
			->set( "heading", lang("closing:page") )
			->set_breadcrumb( lang("general_ledger:page"), base_url("general_ledger") )
			->set_breadcrumb( lang("closing:breadcrumb") )
			->build('closing/form', (isset($data) ? $data : NULL));
	}

	public function close()
	{
		if ( $this->input->post() && $this->input->is_ajax_request()  )
		{			
			$close_date = $this->input->post("closing_date");
			$date = DateTime::createFromFormat("Y-m",  $close_date );
			general_ledger_helper::get_rate_currency( $date->format('Y-m-01') );
			general_ledger_helper::get_rate_currency( $date->format('Y-m-t') );
			
			$this->load->helper("Approval");
			$approver = $this->input->post('approver');
			if ( Approval_helper::approve( 'TUTUP BUKU GL', $approver['username'], $approver['password'] ) === FALSE )
			{
				$response["message"] = lang('auth_incorrect');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			}

			if ( config_item('Dengan Cash Flow') )
			{
				if ( !$this->get_model()->check_cash_flow( $close_date ) )
				{
					$response["message"] = lang('closing:unsetup_cash_flow');
					$response["status"] = "error";
					$response["code"] = "500";
					
					print_r(json_encode($response, JSON_NUMERIC_CHECK));
					exit(0);
				}
			}

			if ( config_item('Hanya GL') == 0 )
			{
				$payable = $this->get_model()->check_posted_payable( $close_date );
				$receivable = $this->get_model()->check_posted_receivable( $close_date );
				
				if ( $payable == FALSE || $receivable  == FALSE )
				{
					$response["message"] = $payable == FALSE ? lang('closing:payable_module')." " : NULL;
					$response["message"] .= $receivable == FALSE ? lang('closing:receivable_module')." " : NULL ;
					$response["message"] .= lang('closing:unclosing_payabale_receivable');
											
					$response["status"] = "error";
					$response["code"] = "500";
					
					print_r(json_encode($response, JSON_NUMERIC_CHECK));
					exit(0);
				}
			
				if ( $this->get_model()->check_posted_general_cashier( $close_date ) == TRUE )
				{
					$response["message"] = lang('closing:unposted_general_cashier');
					$response["status"] = "error";
					$response["code"] = "500";
					
					print_r(json_encode($response, JSON_NUMERIC_CHECK));
					exit(0);
				}
			}	
			
			if ( $evidence_number = $this->get_model()->check_transaction_already_posted( $close_date ) != FALSE )
			{
				$response["message"] = sprintf(lang('closing:transaction_already_posted'), implode(', ',$evidence_number), $date->format('F Y'));
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode($response, JSON_NUMERIC_CHECK));
				exit(0);
			}	
			
			if ( $this->get_model()->check_already_closing( $close_date ) == TRUE )
			{
				$response["message"] = sprintf(lang('closing:already_closing'), $date->format('F Y'));
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode($response, JSON_NUMERIC_CHECK));
				exit(0);
			}		

			if ( $this->get_model()->check_previous_month_closing( $close_date ) == FALSE )
			{
				$response["message"] = lang('closing:unclosing_previous_month');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode($response, JSON_NUMERIC_CHECK));
				exit(0);
			}
						
			$response = array(
					"message" => lang('closing:prosess_success'),
					"status" => "success",
					"code" => "200",
				);
			
			if ( $this->get_model()->closing( $close_date ) === FALSE )
			{
				$response["message"] = lang('closing:prosess_failure');
				$response["status"] = "error";
				$response["code"] = "500";
			}

			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit(0);

		}	
		
		$this->load->view('closing/modal/closing');
	}

	public function cancel()
	{
		$data = array(
				'page' => strtolower(__FUNCTION__)."_".$this->page,
				"form" => TRUE,
				"currency_rate" => general_ledger_helper::get_rate_currency( date('Y-m-d') ),
				"last_period_closing" => $this->get_model()->last_period_closing(),
				"cancel_closing_url" => base_url("general-ledger/closing/close_cancel"),
			);					

		$this->template
			->title( lang("closing:cancel_page") . ' - ' . $this->config->item('company_name') )
			->set( "heading", lang("closing:cancel_page") )
			->set_breadcrumb( lang("general_ledger:page"), base_url("general-ledger") )
			->set_breadcrumb( lang("closing:breadcrumb"), base_url("general-ledger/closing") )
			->set_breadcrumb( lang("closing:cancel_breadcrumb") )
			->build('closing/cancel', (isset($data) ? $data : NULL));
	}
	
	public function close_cancel()
	{
		if ( $this->input->post() && $this->input->is_ajax_request() )
		{
			$cancel_date = $this->input->post("cancel_date");
			$date = DateTime::createFromFormat("Y-m",  $cancel_date );
						
			$this->load->helper("Approval");
			$approver = $this->input->post('approver');
			if ( Approval_helper::approve( 'BATAL TUTUP BUKU GL', $approver['username'], $approver['password'] ) === FALSE )
			{
				$response["message"] = lang('auth_incorrect');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			}

			if ( $this->get_model()->check_closing_consolidation_cooperation( $cancel_date ) )
			{
				$response["message"] = lang('closing:consolidation_cooperation_data');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode($response, JSON_NUMERIC_CHECK));
				exit(0);
			}

			if ( $this->get_model()->check_already_closing_next_month( $cancel_date ) )
			{
				$response["message"] = lang('closing:already_closing_next_month');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode($response, JSON_NUMERIC_CHECK));
				exit(0);
			}
			
			$response = array(
					"message" => lang('closing:cancel_prosess_success'),
					"status" => "success",
					"code" => "200",
				);

			if ($this->get_model()->cancel_closing( $cancel_date ) === FALSE)
			{
				$response["message"] = lang('closing:cancel_prosess_failure');
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit(0);

		}	
		
		$this->load->view('closing/modal/closing_cancel');
	}	
}

