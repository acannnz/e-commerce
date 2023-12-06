<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Close_books extends Admin_Controller
{
	protected $_translation = 'payable';	
	protected $_model = 'close_book_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_cashier');
		
		$this->load->helper("payable");
		$this->load->model('close_book_m');
		
		$this->page = "payable_close_books";
		$this->template->title( lang("close_books:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		if ( $this->input->post() && $this->input->is_ajax_request()  )
		{
			$close_date = $this->input->post("date");
			$house_id = $this->_house_id;
			
			$response = array(
					"status" => "success",
					"error" => "",
					"code" => "200",
				);
			
			if ( $this->close_book_m->check_un_posting_data( $close_date ) )
			{
				$response["message"] = lang('close_books:un_posting_data');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit();
			
			}
			
			
			if ($this->get_model()->close_book( $close_date, $house_id ) === FALSE)
			{
				$response["message"] = lang('close_books:proses_failure');
				$response["status"] = "error";
				$response["code"] = "500";
				
			}

			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit();

		} else {
		
			$data = array(
					'page' => $this->page,
					"form" => TRUE,
					'datatables' => TRUE,
					"last_close_books" => $this->get_model()->last_close_books(),
				);
	
			$this->template
				->set( "heading", lang("close_books:page") )
				->set_breadcrumb( lang("general_ledger:page"), base_url("general_ledger") )
				->set_breadcrumb( lang("close_books:breadcrumb") )
				->build('close_books/form', (isset($data) ? $data : NULL));
		}
	}

	public function cancel()
	{

		if ( $this->input->post() && $this->input->is_ajax_request() )
		{

			$cancel_date = $this->input->post("date");
			$house_id = $this->_house_id;
			
			$response = array(
					"status" => "success",
					"error" => "",
					"code" => "200",
				);
			
			if ( $this->get_model()->cancel_close_books( $cancel_date, $house_id ) === FALSE )
			{
				$response["message"] = lang('close_books:proses_failure');
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit;

		} else {

			$data = array(
					'page' => strtolower(__FUNCTION__)."_".$this->page,
					"form" => TRUE,
					'datatables' => TRUE,
					"last_close_books" => $this->get_model()->last_close_books(),
				);
						
			$this->template->title( lang("close_books:cancel_page") . ' - ' . $this->config->item('company_name') );
	
			$this->template
				->set( "heading", lang("close_books:cancel_page") )
				->set_breadcrumb( lang("general_ledger:page"), base_url("general_ledger") )
				->set_breadcrumb( lang("close_books:breadcrumb"), base_url("general_ledger/close_books") )
				->set_breadcrumb( lang("close_books:cancel_breadcrumb") )
				->build('close_books/form', (isset($data) ? $data : NULL));
		}
	}
}

