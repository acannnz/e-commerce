<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beginning_balance extends Admin_Controller
{
	protected $_translation = 'general_ledger';	
	protected $_model = 'beginning_balance_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_ledger');
				
		$this->load->model("account_m");		
		$this->load->model("beginning_balance_m");		
		$this->load->helper("general_ledger");
				
		$this->page = "beginning_balances";
		$this->template->title( lang("beginning_balances:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{				
		$data = array(
				"page" => $this->page,
				"beginning_balance_date" => general_ledger_helper::get_beginning_balance_date(),
				"lookup_rate_currency" => base_url("general-ledger/beginning_balance/lookup_rate_currency"),
				"form" => TRUE,
				"datatables" => TRUE,
				"navigation_minimized" => TRUE,	
			);
			
		$this->template
			->set( "heading", lang("beginning_balances:page") )
			->set_breadcrumb( lang("beginning_balances:page"), base_url("general-ledger/beginning-balance") )
			->build('beginning_balances/form', (isset($data) ? $data : NULL));
	}
	
	public function activa( )
	{
		$data = array(
				"populate_url" => base_url("general-ledger/beginning-balance/detail_collection/activa"),
				"update_url" => base_url("general-ledger/beginning-balance/item_update"),
				"form" => TRUE,
			);
		
		$this->load->view( "beginning_balances/tables/activa", $data );		
		
	}

	public function pasiva( )
	{
		
		$data = array(
				"populate_url" => base_url("general-ledger/beginning-balance/detail_collection/pasiva"),
				"update_url" => base_url("general-ledger/beginning-balance/item_update"),
				"form" => TRUE,
			);
		
		$this->load->view( "beginning_balances/tables/pasiva", $data );		
	}
	
	public function create()
	{		
		if( $this->input->post() && $this->input->is_ajax_request() ) 
		{
			
								
			$keterangan = $this->input->post("Keterangan");
			$activa = (object) $this->input->post("activa");
			$pasiva = (object) $this->input->post("pasiva");
			
			if ( !$this->get_model()->check_rate_currency() )
			{
				$response = array(
						"message" => lang('beginning_balances:empty_rate_currency'),
						"status" => "error",
						"code" => "500",
					);
				
				print_r( json_encode($response, JSON_NUMERIC_CHECK));
				exit(0);
			}

			if ( $this->get_model()->check_existing_next_monthly_posted() )
			{
				$begin_date = general_ledger_helper::get_beginning_balance_date();
				$next_month = DateTime::createFromFormat("Y-m-d", $begin_date );
				$next_month->add(new DateInterval('P1D'));				
				$next_month = $next_month->format('F Y');
				
				$response = array(
						"message" => sprintf(lang('beginning_balances:existing_next_monthly_posted'), $begin_date, $next_month),
						"status" => "error",
						"code" => "500",
					);
				
				print_r( json_encode($response, JSON_NUMERIC_CHECK));
				exit(0);
			}

			if ( $this->get_model()->check_existing_transaction() )
			{
				$begin_date = general_ledger_helper::get_beginning_balance_date();
				$response = array(
						"message" => sprintf(lang('beginning_balances:existing_transaction'), $begin_date),
						"status" => "error",
						"code" => "500",
					);
				
				print_r( json_encode($response, JSON_NUMERIC_CHECK));
				exit(0);
			}			
						
			$response =
				$this->get_model()->create_data($activa, $pasiva, $keterangan)
					? array(
							"message" => lang('global:created_successfully'),
							"status" => "success",
							"code" => "200",
						)
					: array(
							"message" => lang('global:created_failed'),
							"status" => "erorr",
							"code" => "500",
						)
					;
			 
			print_r( json_encode($response, JSON_NUMERIC_CHECK));
			exit(0);

		} 
	}

	public function submit_rate_currency()
	{
		if ( $this->input->post() && $this->input->is_ajax_request() )
		{
			$post = $this->input->post('f');

			$response =
				$this->get_model()->submit_rate_currency( $post )
					? array(
							"message" => lang('global:created_successfully'),
							"status" => "success",
							"code" => "200",
						)
					: array(
							"message" => lang('global:created_failed'),
							"status" => "erorr",
							"code" => "500",
						)
					;
			 
			print_r( json_encode($response, JSON_NUMERIC_CHECK));
			exit(0);

			
		}
	}

	public function lookup_rate_currency( $is_ajax_request = FALSE )
	{
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== FALSE )
		{
			$date = general_ledger_helper::get_beginning_balance_date();
			$data = array(
					"date" => general_ledger_helper::get_beginning_balance_date(),
					"form_child" => $this->load->view("beginning_balances/form/rate_currency", 
									array(
										"currency_rate" => general_ledger_helper::get_rate_currency($date),
										"submit_url" => base_url("general-ledger/beginning-balance/submit_rate_currency")
										),
									TRUE
								),					
				);
			$this->load->view( 'beginning_balances/modal/rate_currency', $data );
		} 
	}

	public function detail_collection( $group )
    {
		switch($group) {
			case "activa":
				$this->_detail_collection( array(1) ); break;
			case "pasiva":
				$this->_detail_collection( array(2, 3) ); break;
		}
		
	}
				
	private function _detail_collection( $group )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->account_m->table} a";
		$db_where = array();
		$db_like = array();
		
		//$db_where['a.Induk'] = 0;
		
		// get total records
		$this->db->from( $db_from )
				->join("Mst_Currency b", "a.Currency_id = b.Currency_ID", "LEFT OUTER")
				;

		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($group) ){ $this->db->where_in( "Group_ID", $group, TRUE); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db->from( $db_from )
				->join("Mst_Currency b", "a.Currency_id = b.Currency_ID", "LEFT OUTER")
				;

		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($group) ){ $this->db->where_in( "Group_ID", $group, TRUE); }
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
				a.Akun_ID,
				a.Akun_No,
				a.Akun_Name,
				a.Induk,
				b.Currency_Code,
				0 AS Nilai
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("Mst_Currency b", "a.Currency_id = b.Currency_ID", "LEFT OUTER")
			;

		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($group) ){ $this->db->where_in( "Group_ID", $group, TRUE); }
		
		$this->db
			->order_by("a.Akun_No");

		// paging
		if( isset($start) && $length != '-1')
        {
            $this->db
				->limit( $length, $start );
        }
		
		// get
		$result = $this->db
					->get()
					->result()
					;
		
        // Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => $records_total,
				'recordsFiltered' => $records_filtered,
				'data' => array()
			);

		$his_currency_id = $this->get_model()->get_begin_his_currency();
        foreach($result as $row)
        {	
			if ( $his_currency_id ){
				$account_begin = $this->get_model()->get_beginning_balance_value( $row, $his_currency_id );
				$row->Nilai = (float) @$account_begin->Nilai;
				$row->Nilai_Tukar = (float) @$account_begin->Nilai_Tukar;
				$row->Currency_Code = empty($account_begin->Currency_Code) ? $row->Currency_Code : $account_begin->Currency_Code;
			}
			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }	
	
}