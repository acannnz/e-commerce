<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Non_invoices extends Admin_Controller
{
	protected $_translation = 'general_cashier';	
	protected $_model = 'cash_bank_income/non_invoice_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_cashier');
				
		$this->load->helper("general_cashier");

		$this->load->model("general_ledger/account_m");
		
		$this->page = lang("cash_bank_income:page");
		$this->template->title( lang("cash_bank_income:page") . ' - ' . $this->config->item('company_name') );
	}

	public function index()
	{
		$data = array(
				'page' => $this->page,
				"beginning_balance_date" => general_cashier_helper::get_beginning_balance_date(),
				"lookup_customers" => base_url("general-cashier/cash-bank-income/non-invoices/lookup_customers"),
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE
			);
		
		$this->template
			->set( "heading", lang("cash_bank_income:non_invoice_list_heading") )
			->set_breadcrumb( lang("general_cashier:page") )
			->set_breadcrumb( lang("cash_bank_income:page") )
			->set_breadcrumb( lang("cash_bank_income:non_invoice_page"), base_url("general-cashier/cash-bank-income/non-invoices") )
			->build('cash_bank_income/non_invoices/datatable', (isset($data) ? $data : NULL));
	}

	public function create(  )
	{
		$item_data = array(
			"Tgl_Transaksi" => date('Y-m-d'),
			"Customer_ID" => 0,
			"Currency_ID" => 1,
			"Pakai_Referensi" => 0,
			"Type_Transaksi" => "BKM",
			"Tgl_Update" => date('Y-m-d'),
			"User_ID" => $this->user_auth->User_ID,
			"Nilai_tukar" => 1,
			"Kode_Proyek" => 1,
			"DivisiID" => 9,
			"AkunBG_ID" => 0,
			"Keterangan" => NULL,
			"Instansi" => NULL,
			"Supplier_ID" => NULL,
			"Job_Code" => NULL,
			"Debet" => 0,
			"Kredit" => 0,
			"SectionID" => config_item('SectionIDCorporate')
		);
			
		$this->load->library( 'my_object', $item_data, 'item' );
		if( $this->input->post() ) 
		{
			
									
			$this->load->library( 'form_validation' );
			
			$cashier = array_merge($this->item->toArray(), $this->input->post("cashier"));
			$cashier["No_Bukti"] = general_cashier_helper::gen_evidence_number( $cashier["Tgl_Transaksi"], $cashier["Type_Transaksi"] );
			$detail = $this->input->post("detail");
			
			$date = DateTime::createFromFormat("Y-m-d", $cashier['Tgl_Transaksi'] );

			if( empty($detail) )
			{
				$response["message"] = lang('cash_bank_income:details_cannot_empty');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}
			
			foreach($detail as $row)
			{
				if ( $row['Kredit'] == 0 )
				{
					$response["message"] = sprintf(lang('cash_bank_income:empty_account_value'), $row['No_Invoice'] );
					$response["status"] = "error";
					$response["code"] = "500";				
					
					print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
					exit(0);
				}
			}
					
			if( general_cashier_helper::check_general_ledger_closing_period( $date->format('Y-m-d') ) === TRUE )
			{
				$response["message"] = sprintf(lang('cash_bank_income:general_ledger_already_closing_period'), $date->format('F Y'));
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}
			
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $cashier );

			if( $this->form_validation->run() )
			{

				$response = array(
						"status" => "success",
						"message" => lang('global:created_successfully'),
						"code" => "200",
						"No_Bukti" => urlencode( $cashier['No_Bukti'] )
					);
				
				if ($this->get_model()->create_data( $cashier, $detail ) === FALSE)
				{
					$response["message"] = lang('global:created_failed');
					$response["status"] = "error";
					$response["code"] = "500";
				}
				
			} else
			{

				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
			exit(0);

		} else {
		
			if( $this->input->is_ajax_request() )
			{
				$data = array(
						"item" => (object) $this->item->toArray(),
						"beginning_balance_date" => general_cashier_helper::get_beginning_balance_date(),
						"is_ajax_request" => TRUE,
						"is_modal" => TRUE,
						"form" => TRUE,
						"datatables" => TRUE,
						"web_stroge" => TRUE,
						"lookup_accounts" => base_url("general-cashier/cash-bank-income/non-invoices/lookup_accounts"),
						"lookup_accounts_credit" => base_url("general-cashier/cash-bank-income/non-invoices/lookup_accounts_credit"),
						"submit_url" => base_url("general-cashier/cash-bank-income/non-invoices/create"),
						"create_url" => base_url("general-cashier/cash-bank-income/non-invoices/create"),
					);
				
				$this->load->view( 
						'cash_bank_income/non_invoices/modal/create_edit', 
						array('form_child' => $this->load->view('cash_bank_income/non_invoices/form', $data, true))
					);
			} else
			{
				$data = array(
						"page" => $this->page."_".strtolower(__FUNCTION__),
						"item" => (object) $this->item->toArray(),
						"beginning_balance_date" => general_cashier_helper::get_beginning_balance_date(),				
						"form" => TRUE,
						"datatables" => TRUE,
						"web_stroge" => TRUE,
						"lookup_accounts" => base_url("general-cashier/cash-bank-income/non-invoices/lookup_accounts"),
						"lookup_accounts_credit" => base_url("general-cashier/cash-bank-income/non-invoices/lookup_accounts_credit"),
						"submit_url" => base_url("general-cashier/cash-bank-income/non-invoices/create"),
						"create_url" => base_url("general-cashier/cash-bank-income/non-invoices/create"),
					);

				$this->template
					->set( "heading", lang("cash_bank_income:non_invoice_create_heading") )
					->set_breadcrumb( lang("general_cashier:page") )
					->set_breadcrumb( lang("cash_bank_income:page") )
					->set_breadcrumb( lang("cash_bank_income:non_invoice_page"), base_url("general-cashier/cash-bank-income/non-invoices") )
					->set_breadcrumb( lang("cash_bank_income:non_invoice_create_heading") )
					->build('cash_bank_income/non_invoices/form', $data);
			}
		}
	}

	public function edit()
	{
		$No_Bukti = $this->input->get("No_Bukti");		
		$item = $this->get_model()->get_row( $No_Bukti );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', (array) $item, 'item' );

		if( $this->input->post() ) 
		{
			$response = array(
					"message" => lang('global:updated_successfully'),
					"status" => "success",
					"code" => "200",
				);
				
			$cashier = $this->input->post("cashier");
			$cashier["No_Bukti"] = $No_Bukti;
			$detail = $this->input->post("detail");

			$date = DateTime::createFromFormat("Y-m-d", $cashier['Tgl_Transaksi'] );

			if( empty($detail) )
			{
				$response["message"] = lang('cash_bank_income:details_cannot_empty');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}
			
			foreach($detail as $row)
			{
				if ( $row['Kredit'] == 0 )
				{
					$response["message"] = sprintf(lang('cash_bank_income:empty_account_value'), $row['No_Invoice'] );
					$response["status"] = "error";
					$response["code"] = "500";				
					
					print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
					exit(0);
				}
			}
					
			if( general_cashier_helper::check_general_ledger_closing_period( $date->format('Y-m-d') ) === TRUE )
			{
				$response["message"] = sprintf(lang('cash_bank_income:general_ledger_already_closing_period'), $date->format('F Y'));
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}
			
			if( general_cashier_helper::check_reconciliation_data( $item->No_Bukti ) === TRUE )
			{
				$response["message"] = lang('cash_bank_income:reconciliation_data');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}

			if ( $item->Posted === 1 )
			{
				$response["message"] = lang('cash_bank_income:data_already_posted');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			
			}
			
			$this->load->library( 'form_validation' );
			
			$this->form_validation->set_rules( $this->get_model()->rules['update'] );
			$this->form_validation->set_data( $cashier );
			
			if( $this->form_validation->run() )
			{
				$response["No_Bukti"] = urlencode($No_Bukti);

				if( $this->get_model()->update_data( $cashier, $detail) == FALSE )
				{
												
					$response["message"] = lang('global:updated_failed');
					$response["status"] = "error";
					$response["code"] = "500";				
				}
			} else
			{

				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";				
			}

			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit(0);

		} else {
			
			if( $this->input->is_ajax_request() )
			{
				$data = array(
						'item' => (object) $this->item->toArray(),
						"is_ajax_request" => TRUE,
						"is_modal" => TRUE,
						"is_edit" => TRUE,
						"form" => TRUE,
						"datatables" => TRUE,
						"beginning_balance_date" => general_cashier_helper::get_beginning_balance_date(),
						"lookup_accounts" => base_url("general-cashier/cash-bank-income/non-invoices/lookup_accounts"),
						"lookup_accounts_credit" => base_url("general-cashier/cash-bank-income/non-invoices/lookup_accounts_credit"),
						"create_url" => base_url("general-cashier/cash-bank-income/non-invoices/create"),
						"submit_url" => base_url("general-cashier/cash-bank-income/non-invoices/edit")."?No_Bukti=". urlencode( $No_Bukti ),
						"cancel_url" => base_url("general-cashier/cash-bank-income/non-invoices/cancel")."?No_Bukti=". urlencode( $No_Bukti ),
					);
				
				$this->load->view( 
						'cash_bank_income/non_invoices/modal/create_edit', 
						array('form_child' => $this->load->view('cash_bank_income/non_invoices/form', $data, true))
					);
			} else
			{
				$data = array(
						"page" => $this->page,
						"item" => (object) $this->item->toArray(),
						"form" => TRUE,
						"datatables" => TRUE,
						"is_edit" => TRUE,
						"web_stroge" => TRUE,
						"beginning_balance_date" => general_cashier_helper::get_beginning_balance_date(),
						"lookup_accounts" => base_url("general-cashier/cash-bank-income/non-invoices/lookup_accounts"),
						"lookup_accounts_credit" => base_url("general-cashier/cash-bank-income/non-invoices/lookup_accounts_credit"),
						"create_url" => base_url("general-cashier/cash-bank-income/non-invoices/create"),
						"submit_url" => base_url("general-cashier/cash-bank-income/non-invoices/edit")."?No_Bukti=". urlencode( $No_Bukti ),
						"cancel_url" => base_url("general-cashier/cash-bank-income/non-invoices/cancel")."?No_Bukti=". urlencode( $No_Bukti ),
					);
					
				$this->template
					->set( "heading", lang("credit_debit_notes:non_invoice_edit_heading") )
					->set_breadcrumb( lang("general_cashier:page") )
					->set_breadcrumb( lang("cash_bank_income:page") )
					->set_breadcrumb( lang("cash_bank_income:non_invoice_page"), base_url("general-cashier/cash-bank-income/non-invoices") )
					->set_breadcrumb( lang("cash_bank_income:non_invoice_edit_heading") )
					->build('cash_bank_income/non_invoices/form', $data);
			}
		}
	}
	
	public function cancel()
	{
		$No_Bukti = $this->input->get("No_Bukti");		
		$item = $this->get_model()->get_row( $No_Bukti );
		
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', (array) $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
						
			if( empty($item->No_Bukti) )
			{
				$response["message"] = lang('global:get_failed');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}

			$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $item->Tgl_Transaksi);
			if( general_cashier_helper::check_general_ledger_closing_period( $date->format('Y-m-d') ) === TRUE )
			{
				$response["message"] = sprintf(lang('cash_bank_income:general_ledger_already_closing_period'), $date->format('F Y'));
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}

			if( general_cashier_helper::check_reconciliation_data( $item->No_Bukti ) === TRUE )
			{
				$response["message"] = lang('cash_bank_income:reconciliation_data');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}

			if ( $item->Posted === 1 )
			{
				$response["message"] = lang('cash_bank_income:data_already_posted');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			}
						
			if( $item->No_Bukti == $this->input->post( 'confirm' ) )
			{
				if ( $this->get_model()->cancel_data( $item ) === TRUE )
				{
					$response["message"] = lang('global:deleted_successfully');
					$response["status"] = "success";
					$response["code"] = "200";			
											
				} else {
					
					$response["message"] = lang('global:deleted_failed')." : ".$this->db->_error_message();
					$response["status"] = "error";
					$response["code"] = "500";								
				}
			} else {
				$response["message"] = "Internal server error!";
				$response["status"] = "error";
				$response["code"] = "500";								
			}

			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit(0);			
			
		} else {
			
			$data  = array(
				"item" => (object) $this->item->toArray(),
				"redirect_url" => base_url("general-cashier/cash-bank-income/non-invoices/edit")."?No_Bukti=". urlencode( $No_Bukti ),
				"cancel_url" => base_url("general-cashier/cash-bank-income/non-invoices/cancel")."?No_Bukti=". urlencode( $No_Bukti ),
			);
			
			$this->load->view( 'cash_bank_income/non_invoices/modal/cancel', $data );	
		}
	}
	
	public function detail( $item, $is_edit = FALSE )
	{
		$data = array(
				"item" => $item,
				"collection" => $this->get_model()->get_detail_collection( @$item->No_Bukti ),
				"lookup_invoices" => base_url("general-cashier/cash-bank-income/non-invoices/lookup_invoices"), 
				"form_invoice_detail_url" => base_url("general-cashier/cash-bank-income/non-invoices/lookup_form_invoice_detail"),
				"get_invoice_detail_url" => (!$is_edit) 
											? base_url("general-cashier/cash-bank-income/non-invoices/get_invoice_detail")
											: base_url("general-cashier/cash-bank-income/non-invoices/get_general_cashier_factur"),
			);
		
		$this->load->view( "cash_bank_income/non_invoices/details/accounts", $data );		
		
	}
	
	public function lookup( $is_ajax_request = false )
	{
		if( $this->input->is_ajax_request() ||  $is_ajax_request !== false )
		{
			$this->load->view( 'cash_bank_income/non_invoices/lookup/datatable' );
		} 
	}	

	public function lookup_accounts( $type = 'GC-Cash' ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$data = array(
				'type' => $type
			);
			
			$this->load->view( 'cash_bank_income/non_invoices/lookup/accounts', $data );
		} 
	}

	public function lookup_accounts_credit( $index = false ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{			
			$data = array(
				"index" => $index
			);
			$this->load->view( 'cash_bank_income/non_invoices/lookup/accounts_credit', $data );
		} 
	}
	
	public function datatable_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$this->load->model("common/customer_m");
		
		$db_from = "{$this->get_model()->table} a";
		$db_where = array();
		$db_or_where_group = array();
		$db_like = array();
		$db_not_like = array();
		
		// prepare defautl flter	
		$filter = $this->input->post('f');	
		$db_where['a.Pakai_Referensi '] = 0;

		if ( !empty($filter['date_start']))
		{
			$db_where['a.Tgl_Transaksi >='] = $filter['date_start'];
		}

		if ( !empty($filter['date_end']))
		{
			$db_where['a.Tgl_Transaksi <='] = $filter['date_end'];
		}

		if ( !empty($filter['Type_Transaksi']))
		{
			$db_where['a.Type_Transaksi'] = $filter['Type_Transaksi'];		
		}else{
			$db_or_where_group['a.Type_Transaksi '] = 'BKM';
			$db_or_where_group['a.Type_Transaksi'] = 'BBM';
		}
		
		if ( !empty($filter['search_text']))
		{
			$db_like[ $this->db->escape_str("a.Keterangan") ] = $filter['search_text'];
			$db_like[ $this->db->escape_str("a.No_Bukti") ] = $filter['search_text'];
			$db_like[ $this->db->escape_str("a.Kredit") ] = $filter['search_text'];
			$db_like[ $this->db->escape_str("f.Akun_No") ] = $filter['search_text'];
			$db_like[ $this->db->escape_str("f.Akun_Name") ] = $filter['search_text'];
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.Keterangan") ] = $keywords;
			$db_like[ $this->db->escape_str("a.No_Bukti") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Kredit") ] = $keywords;
			$db_like[ $this->db->escape_str("f.Akun_No") ] = $keywords;
			$db_like[ $this->db->escape_str("f.Akun_Name") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where_group) ){ $this->db->group_start()->or_where( $db_or_where_group )->group_end(); }		
		if( !empty($db_not_like) ){ $this->db->not_like( $db_not_like ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("Mst_Akun f", "a.AkunBG_ID = f.Akun_ID", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where_group) ){ $this->db->group_start()->or_where( $db_or_where_group )->group_end(); }		
		if( !empty($db_not_like) ){ $this->db->not_like( $db_not_like ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			e.Kode_Customer, 
			e.Nama_Customer, 
			a.Tgl_Transaksi, 
			a.No_Bukti,
			a.Posted,
			a.Status_Batal,
			SUM(b.Kredit) AS Nilai,
			a.Keterangan,
			c.Nama_Proyek,
			d.Nama_Divisi,
			f.Akun_No,
			f.Akun_Name  
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->get_model()->table_detail} b", "a.No_Bukti = b.No_Bukti", "INNER OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("mDivisi d", "a.DivisiID = d.Divisi_ID", "LEFT OUTER" )
			->join("mCustomer e", "a.Customer_ID = e.Customer_ID", "LEFT OUTER" )
			->join("Mst_Akun f", "a.AkunBG_ID = f.Akun_ID", "LEFT OUTER")
			->group_by( array(
				'e.Kode_Customer', 
				'e.Nama_Customer', 
				'a.Tgl_Transaksi', 
				'a.No_Bukti',
				'a.Posted',
				'a.Status_Batal',
				'a.Keterangan',
				'c.Nama_Proyek',
				'd.Nama_Divisi',
				'f.Akun_No',
				'f.Akun_Name'
			));
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where_group) ){ $this->db->group_start()->or_where( $db_or_where_group )->group_end(); }		
		if( !empty($db_not_like) ){ $this->db->not_like( $db_not_like ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		
		// ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$this->db
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir) );
			}
        }
		
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
        
        foreach($result as $row)
        {
			$row->Tgl_Transaksi = substr($row->Tgl_Transaksi, 0, 10);
            $output['data'][] = $row;
		}
		
		$this->template
			->build_json( $output );
    }
}



