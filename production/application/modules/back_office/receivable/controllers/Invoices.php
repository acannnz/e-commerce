<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoices extends Admin_Controller
{
	protected $_translation = 'receivable';	
	protected $_model = 'invoice_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('receivable');
				
		$this->load->model( "invoice_m" );
		
		$this->load->helper("receivable");
		
		$this->load->model("receivable/type_m");
		$this->load->model("general_ledger/account_m");
		
		$this->page = lang("invoices:page");
		$this->template->title( lang("invoices:page") . ' - ' . $this->config->item('company_name') );
	}

	public function index()
	{
		$data = array(
				'page' => $this->page,
				'options_type' => $this->type_m->get_option_type(),
				"beginning_balance_date" => receivable_helper::get_beginning_balance_date(),
				"lookup_customers" => base_url("receivable/invoices/lookup_customers"),
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE
			);
		
		$this->template
			->set( "heading", lang("invoices:page") )
			->set_breadcrumb( lang("receivables:page") )
			->set_breadcrumb( lang("invoices:page"), base_url("receivable/invoices") )
			->build('invoices/datatable', (isset($data) ? $data : NULL));
	}

	public function create(  )
	{

		$item_data = array(
				"No_Invoice" => receivable_helper::gen_invoice_number( date("Y-m-d") ),
				"Currency_ID" => 1,
				"Customer_ID" => 0,
				"Tgl_Invoice" => date("Y-m-d H:i:s"),
				"Tgl_Tempo" => date("Y-m-d H:i:s"),
				"Tgl_Update" => date("Y-m-d H:i:s"),
				"Nilai" => 0,
				"Sisa" => 0,
				"Keterangan" => NULL,
				"User_ID" => $this->user_auth->User_ID,
				"JenisPiutang_ID" => NULL,
				"Nilai_Tukar" => 1,
				"HisCurrencyID" => 1,
				"Cancel_Invoice" => 0,
				"Kode_Proyek" => 1,
				"DivisiID" => 9,
				"SectionID" => config_item('SectionIDCorporate'),
				"TutupBuku" => 0,
				"Posted" => 0
			);
			
		$this->load->library( 'my_object', $item_data, 'item' );
		if( $this->input->post() ) 
		{
			
									
			$this->load->library( 'form_validation' );
			
			$detail = $this->input->post("detail");
			$header = array_merge($item_data, $this->input->post("header") );
			# Menambha JenisPiutang Pada Header Sesuai Detailnya;
			$header['JenisPiutang_ID'] = $detail[0]['JenisPiutang_ID'];
			$header['No_Invoice'] = receivable_helper::gen_invoice_number( $header['Tgl_Invoice'] );			

			foreach($detail as $row)
			{
				if ( $header['Tgl_Invoice'] < $row['Tgl_transaksi'] )
				{
					$response["message"] = sprintf(lang('invoices:transaction_date_incorret'), $header['Tgl_Invoice'], $row['Tgl_transaksi'] );
					$response["status"] = "error";
					$response["code"] = "500";				
					
					print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
					exit(0);
				}
			}
			
			if ( empty( $detail ))
			{
				$response["message"] = lang('invoices:details_cannot_empty');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
			
			if ( receivable_helper::check_closing_period( $header['Tgl_Invoice'] ) === TRUE )
			{
				$date = DateTime::createFromFormat("Y-m-d", $header['Tgl_Invoice'] );
				$response["message"] = sprintf(lang('invoices:already_closing_period'), $date->format("F Y") );
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
			
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $header );

			if( $this->form_validation->run() )
			{

				$response = array(
						"status" => "success",
						"message" => lang('global:created_successfully'),
						"code" => "200",
						"No_Invoice" => urlencode($header['No_Invoice'])
					);
				
				if ($this->get_model()->create_data( $header, $detail ) === FALSE)
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
						"beginning_balance_date" => receivable_helper::get_beginning_balance_date(),
						"is_ajax_request" => TRUE,
						"is_modal" => TRUE,
						"form" => TRUE,
						"datatables" => TRUE,
						"lookup_customers" => base_url("receivable/invoices/lookup_customers"),
						"submit_url" => base_url("receivable/invoices/create"),
						"create_url" => base_url("receivable/invoices/create"),
					);
				
				$this->load->view( 
						'invoices/modal/create_edit', 
						array('form_child' => $this->load->view('invoices/form', $data, true))
					);
			} else
			{
				$data = array(
						"page" => $this->page."_".strtolower(__FUNCTION__),
						"item" => (object) $this->item->toArray(),
						"beginning_balance_date" => receivable_helper::get_beginning_balance_date(),				
						"form" => TRUE,
						"datatables" => TRUE,
						"lookup_customers" => base_url("receivable/invoices/lookup_customers"),
						"submit_url" => base_url("receivable/invoices/create"),
						"create_url" => base_url("receivable/invoices/create"),
					);
				$this->template
					->set( "heading", lang("invoices:create_heading") )
					->set_breadcrumb( lang("receivables:page") )
					->set_breadcrumb( lang("invoices:page"), base_url("receivable/invoices") )
					->set_breadcrumb( lang("invoices:create_heading") )
					->build('invoices/form', $data);
			}
		}
	}

	public function edit()
	{
		$No_Invoice = $this->input->get("No_Invoice");		
		$item = $this->get_model()->get_row( $No_Invoice );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', (array) $item, 'item' );

		if( $this->input->post() ) 
		{
			
						
			$response = array(
					"message" => lang('global:updated_successfully'),
					"status" => "success",
					"code" => "200",
				);
			
			$detail = $this->input->post("detail");
			$header = $this->input->post("header");
			
			$this->load->library( 'form_validation' );
			
			$this->form_validation->set_rules( $this->get_model()->rules['update'] );
			$this->form_validation->set_data( $header );

			foreach($detail as $row)
			{
				if ( $header['Tgl_Invoice'] < $row['Tgl_transaksi'] )
				{
					$response["message"] = sprintf(lang('invoices:transaction_date_incorret'), $header['Tgl_Invoice'], $row['Tgl_transaksi'] );
					$response["status"] = "error";
					$response["code"] = "500";				
					
					print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
					exit(0);
				}
			}

			if ( empty( $detail ))
			{
				$response["message"] = lang('invoices:details_cannot_empty');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
			
			if ( receivable_helper::check_closing_period( $header['Tgl_Invoice'] ) === TRUE )
			{
				$date = DateTime::createFromFormat("Y-m-d", $header['Tgl_Invoice'] );
				$response["message"] = sprintf(lang('invoices:already_closing_period'), $date->format("F Y") );
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
			
			if( $this->form_validation->run() )
			{
				$response["No_Invoice"] = urlencode($No_Invoice);

				if( $this->get_model()->update_data( $header, $No_Invoice) == FALSE )
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
						"beginning_balance_date" => receivable_helper::get_beginning_balance_date(),
						"lookup_customers" => base_url("receivable/invoices/lookup_customers"),
						"create_url" => base_url("receivable/invoices/create"),
						"submit_url" => base_url("receivable/invoices/edit")."?No_Invoice=". urlencode( $No_Invoice ),
						"cancel_url" => base_url("receivable/invoices/cancel")."?No_Invoice=". urlencode( $No_Invoice ),
					);
				
				$this->load->view( 
						'invoices/modal/create_edit', 
						array('form_child' => $this->load->view('invoices/form', $data, true))
					);
			} else
			{
				$data = array(
						"page" => $this->page,
						"item" => (object) $this->item->toArray(),
						"form" => TRUE,
						"datatables" => TRUE,
						"is_edit" => TRUE,
						"beginning_balance_date" => receivable_helper::get_beginning_balance_date(),
						"lookup_customers" => base_url("receivable/invoices/lookup_customers"),
						"create_url" => base_url("receivable/invoices/create"),
						"submit_url" => base_url("receivable/invoices/edit")."?No_Invoice=". urlencode( $No_Invoice ),
						"cancel_url" => base_url("receivable/invoices/cancel")."?No_Invoice=". urlencode( $No_Invoice ),
					);
					
				$this->template
					->set( "heading", lang("invoices:edit_heading") )
					->set_breadcrumb( lang("receivables:page") )
					->set_breadcrumb( lang("invoices:page"), base_url("receivable/invoices") )
					->set_breadcrumb( lang("invoices:edit_heading") )
					->build('invoices/form', $data);
			}
		}

	}
	
	public function cancel()
	{
		$No_Invoice = $this->input->get("No_Invoice");		
		$item = $this->get_model()->get_row( $No_Invoice );
		
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', (array) $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			$this->load->helper("Approval");
			if ( Approval_helper::approve( 'CANCEL INVOICE', $username, $password ) === FALSE )
			{
				$response["message"] = lang('auth_incorrect');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			}
						
			if( empty($item->No_Invoice) )
			{
				$response["message"] = lang('global:get_failed');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}

			if( $this->get_model()->check_already_mutation( $item->No_Invoice ) === TRUE )
			{
				$response["message"] = lang('invoices:already_mutation');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}

			if ( $item->Posted === 1 )
			{
				$response["message"] = lang('invoices:already_posted');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			
			}
						
			if( $item->No_Invoice == $this->input->post( 'confirm' ) )
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
				"redirect_url" => base_url("receivable/invoices/edit")."?No_Invoice=". urlencode( $No_Invoice ),
				"cancel_url" => base_url("receivable/invoices/cancel")."?No_Invoice=". urlencode( $No_Invoice ),
			);
			
			$this->load->view( 'invoices/modal/cancel', $data );	
		}
	}
	
	public function detail( $item, $is_edit = FALSE )
	{
		$data = array(
				"item" => $item,
				"collection" => $this->get_model()->get_detail_collection( $item->No_Invoice ),
				"lookup_facturs" => base_url("receivable/invoices/lookup_facturs"), 
			);
		
		if ($is_edit === TRUE)
		{
			return	$this->load->view( "invoices/details/tables_edit", $data );		
			
		} else 
		{
			return	$this->load->view( "invoices/details/tables", $data );		
		}
		
	}

	public function detail_mutation( $item )
	{
		$data = array(
				"item" => $item,
				"collection" => $this->get_model()->get_detail_mutation_collection( $item->No_Invoice ),
			);
		
		return	$this->load->view( "invoices/details/mutation", $data );		
		
	}
	
	public function get_invoice_detail()
	{
		if ( $this->input->get("No_Invoice") )
		{
			if ( $collection = $this->get_model()->get_detail_collection( $this->input->get("No_Invoice") ) )
			{
				$response["collection"] = $collection;
				$response["status"] = "success";
				$response["code"] = "200";			
										
			} else {
				
				$response["message"] = lang('global:get_failed')." : ".@$this->db->_error_message();
				$response["status"] = "error";
				$response["code"] = "500";								
			}
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK) );
			exit(0);
		}
		
	}
			
	public function lookup( $is_ajax_request = false, $by_customer = true )
	{
		if( $this->input->is_ajax_request() ||  $is_ajax_request !== false )
		{
			$this->load->view( 'invoices/lookup/datatable', array("by_customer" => $by_customer) );
		} 
	}	

	public function lookup_customers( ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'invoices/lookup/customers' );
		} 
	}

	public function lookup_facturs( $trId = NULL ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{				
			$data = array(
				"trId" => $trId,
				"lookup_customers" => base_url("receivable/invoices/lookup_customers"), 
			);
			$this->load->view( 'invoices/lookup/facturs', $data );
		} 
	}

	public function lookup_by_customer( $customer_id )
	{
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 'invoices/lookup/datatable', array("customer_id" => $customer_id) );
		} 
	}	

	public function lookup_collection( )
	{
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->get_model()->table} a";
		$db_where = array();
		$db_or_where_group = array();
		$db_like = array();
		
		// prepare defautl flter
		$filter = $this->input->post('f');	
		
		$db_or_where_group['a.JTransaksi_ID'] = 200;
		$db_or_where_group['a.JTransaksi_ID '] = 201;
		$db_where['a.Cancel_Invoice'] = 0;

		if ( !empty($filter['date_start']))
		{
			$db_where['a.Tgl_Invoice >='] = $filter['date_start'];
		}

		if ( !empty($filter['date_end']))
		{
			$db_where['a.Tgl_Invoice <='] = $filter['date_end'];
		}
		if ( !empty($filter['Customer_ID']))
		{
			$db_where['a.Customer_ID'] = $filter['Customer_ID'];		
		}
		
		if ( !empty($filter['view_state']) )
		{
			switch ($filter['view_state'])
			{
				case "all" :  break;
				case "active" : $db_where['a.Cancel_Invoice'] = 0; break;
				case "cancel" : $db_where['a.Cancel_Invoice'] = 1; break;
			}
		}
		
		// get total records
		$this->db->from( $db_from )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where_group) ){ $this->db->group_start()->or_where( $db_or_where_group )->group_end(); }		
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where_group) ){ $this->db->group_start()->or_where( $db_or_where_group )->group_end(); }		
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.Tgl_Invoice, 
			a.No_Invoice, 
			a.Nilai, 
			a.Sisa, 
			a.Cancel_Invoice, 
			a.Sudah_Dibuatkan_Bukti,
			a.Keterangan,
			a.JenisPiutang_ID,
			c.Nama_Proyek,
			d.Currency_Code, 
			e.Nama_Divisi,
			f.Nama_Type 
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("AR_mTypePiutang f", "a.JenisPiutang_ID = f.TypePiutang_ID", "LEFT OUTER")
			;
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
			$row->Sisa = number_format($row->Sisa, 2, '.',',');
			$row->Tgl_Invoice = substr($row->Tgl_Invoice, 0, 10);
            $output['data'][] = $row;
		}
		
		$this->template
			->build_json( $output );
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
		
		$db_where['a.No_Invoice !='] = '';
		$db_or_where_group['a.JTransaksi_ID'] = 200;
		$db_or_where_group['a.JTransaksi_ID '] = 201;
		$db_not_like['a.No_Invoice'] = 'IMA';

		if ( !empty($filter['date_start']))
		{
			$db_where['a.Tgl_Invoice >='] = $filter['date_start'];
		}

		if ( !empty($filter['date_end']))
		{
			$db_where['a.Tgl_Invoice <='] = $filter['date_end'];
		}
		if ( !empty($filter['Customer_ID']))
		{
			$db_where['a.Customer_ID'] = $filter['Customer_ID'];		
		}
		
		if ( !empty($filter['view_state']) )
		{
			switch ($filter['view_state'])
			{
				case "all" :  break;
				case "active" : $db_where['a.Cancel_Invoice'] = 0; break;
				case "cancel" : $db_where['a.Cancel_Invoice'] = 1; break;
			}
		}
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            /*$keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.invoice_number") ] = $keywords;
			$db_like[ $this->db->escape_str("a.invoice_date") ] = $keywords;
			$db_like[ $this->db->escape_str("a.description") ] = $keywords;
			$db_like[ $this->db->escape_str("a.value") ] = $keywords;*/

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
			->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("AR_mTypePiutang f", "a.JenisPiutang_ID = f.TypePiutang_ID", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where_group) ){ $this->db->group_start()->or_where( $db_or_where_group )->group_end(); }		
		if( !empty($db_not_like) ){ $this->db->not_like( $db_not_like ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.Tgl_Invoice, 
			a.No_Invoice, 
			b.Nama_Customer, 
			a.Nilai, 
			a.Sisa, 
			a.Cancel_Invoice, 
			a.Sudah_Dibuatkan_Bukti,
			c.Nama_Proyek,
			d.Currency_Code, 
			e.Nama_Divisi 
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("AR_mTypePiutang f", "a.JenisPiutang_ID = f.TypePiutang_ID", "LEFT OUTER")
			;
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
			$row->Tgl_Invoice = substr($row->Tgl_Invoice, 0, 10);
            $output['data'][] = $row;
		}
		
		$this->template
			->build_json( $output );
    }


}



