<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_ledger extends Admin_Controller
{
	protected $_translation = 'general_ledger';	
	protected $_model = 'journal_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_ledger');
		
		$this->load->helper("general_ledger");
		
		$this->load->model("general_ledger/journal_m");
		$this->load->model("general_ledger/account_m");
		
		$this->page = "general_ledger";
		$this->template->title( lang("general_ledger:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE,
				"currencies" => general_ledger_helper::get_option_currency(),
				"currency_default" => general_ledger_helper::get_default_currency_id(),
				"lookup_accounts" => base_url("general-ledger/lookup_accounts"), 
			);
		
		$this->template
			->set( "heading", lang("general_ledger:page") )
			->set_breadcrumb( lang("general_ledger:page"), base_url("general_ledger") )
			->build('general_ledger/general_ledger/form', (isset($data) ? $data : NULL));
	}
	
	public function details( )
	{
		
		$data = array(
				"currency_default" => general_ledger_helper::get_default_currency_id(),
				"populate_url" => base_url("general_ledger/datatable_collection"),
				"form" => TRUE,
				"datatables" => TRUE,
			);
		
		return	$this->load->view( "general_ledger/general_ledger/table/tables", $data );		
		
	}

	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'general_ledger/lookup/datatable' );
		} else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( "Lookup Box" )
				->build('general_ledger/lookup', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_accounts(  ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'general_ledger/general_ledger/lookup/accounts', array() );
		} 
	}
	
	public function print_journal_transactions( $from = NULL, $till =  NULL, $account_id = NULL )
	{
		if( is_null($account_id) ){ $account_id = $this->input->get_post("account_id"); }
		if( is_null($from) ){ $from = $this->input->get_post("date_start"); }
		if( is_null($till) ){ $till = $this->input->get_post("date_till"); }
		
		$this->load->helper( "export" );
		$data = array(
				"collections" => array(),
				"from" => $from,
				"till" => $till
			);		
				
		if( ! $data['collections'] = general_ledger_helper::get_journal_by_id( $from, $till, $account_id ) )
		{
			make_flashdata(array(
					'response_status' => 'error',
					'message' => lang('general_ledger:not_found_journal')
				));			
			redirect( "general_ledger" );
		}

		// Mengambil Saldo bulan sebelumnya
		$before_balance = $this->get_model()->get_before_balance($account_id, $from);
		
		if (!empty($before_balance)) :
			
			$before_balance->journal_date = date('Y-m-01', strtotime($from)); 
			$before_balance->journal_number = "-";
			$before_balance->debit = 0.00;
			$before_balance->credit = 0.00;
			$before_balance->balance = @$before_balance->value;
			$before_balance->notes = lang("general_ledger:beginning_balance_label");
			$before_balance->normal_pos = @$account_detail->normal_pos;
			
			array_unshift( $data['collections'], $before_balance);
		endif;	
		
		$account_data = $this->account_m->find_account(array("id" => $account_id));		
		$data["file_name"] = sprintf( "Journal-%s.pdf", $account_data->account_name );
		$data["account"] = $account_data;
		$data["house"] = $this->house_m->get_house( $this->_house_id );
		
		$NOW = new DateTime( "NOW" );
		$timestamp = $NOW->getTimestamp();
					
		//print_r($data);exit(0);
			
		$html_header = "";
		$html_footer = "";
		$html_content = $this->load->view( "general_ledger/general_ledger/print/journals", $data, TRUE );
		
		//print $html_content;exit(0);
		
		$this->load->helper( "report" );
		export_helper::print_pdf( $html_content, $file_name );
		
		exit(0);
	}
	
	public function export_general_ledger()
	{
		if ( $this->input->post() )
		{
			$post  = (object) $this->input->post('f');
	
			general_ledger_helper::export_general_ledger( $post );		
			exit(0);
		}
	}
	
	public function lookup_collection()
	{
		$this->datatable_collection( 1 );
	}
	
	public function datatable_collection( $state=false )
    {
		
		// prepare defautl flter
		$post  = (object) $this->input->get('f');		
		//general_ledger_helper::get_rate_currency($post->date_till);
		
		$post->currencyRate = general_ledger_helper::get_currency_exchange_rate( $post->Currency_ID, $post->date_till) == 0
								? 1
								: general_ledger_helper::get_currency_exchange_rate( $post->Currency_ID, $post->date_till);			
		$post->convertCurrency_ID = $post->convertCurrency_ID ? $post->convertCurrency_ID : NULL;
		$post->convert = !empty($post->convertCurrency_ID) ? 1 : 0;
		/*if( general_ledger_helper::check_beginning_balance( $post->date_start ) ) // !
		{
			$response["message"] = lang('global:created_failed');
			$response["status"] = "error";
			$response["code"] = "500";
			
			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit;
		}*/
		
		$this->db->query("EXEC CekHisCurrency '$post->date_till'");		
		
		$credit_summary = $debit_summary = 0;
		$beginning_balance = general_ledger_helper::get_beginning_balance_akun($post);
		/*$collection[] = array(
				"Tanggal" => $post->date_start,
				"NoBukti" => "-",
				"Keterangan" => lang("general_ledger:beginning_balance_label"),
				"Debit" => number_format( $beginning_balance->D > 0 ? $beginning_balance->D : 0 , 2, ',', '.'),
				"Kredit" => number_format( $beginning_balance->K > 0 ? $beginning_balance->K : 0 , 2, ',', '.'),
				"Saldo" => number_format(abs( $beginning_balance->D) , 2, ',', '.'),
			);*/
		$transactions = general_ledger_helper::get_general_ledger_details($post);
		foreach( $transactions as $row ){
			$credit_summary = $credit_summary + $row->Kredit;
			$debit_summary = $debit_summary + $row->Debit;
			$row->Debit = number_format($row->Debit, 2, ',', '.');
			$row->Kredit = number_format($row->Kredit, 2, ',', '.');
			$row->Saldo = number_format($row->Saldo, 2, ',', '.');
			$collection[] = $row;
		}
		
        // Output
        $output = array(
				'beginning_value' => number_format($beginning_balance->D > 0 ? $beginning_balance->D : $beginning_balance->K, 2, ',', '.'),
				'ending_value' => $row->Saldo,
				'credit_summary' => number_format($credit_summary, 2, ',', '.'),
				'debit_summary' => number_format($debit_summary, 2, ',', '.'),
				'collection' => $collection,
			);

		$this->template
			->build_json($output);
    }

}