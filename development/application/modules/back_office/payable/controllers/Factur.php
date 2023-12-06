<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Factur extends Admin_Controller
{
	protected $_translation = 'payable';	
	protected $_model = 'factur_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('payable');
				
		$this->load->model( "factur_m" );
		
		$this->load->helper("payable");
		
		$this->load->model("payable/factur_detail_m");
		$this->load->model("payable/type_m");
		$this->load->model("general_ledger/account_m");
		
		$this->page = "payables";
		$this->template->title( lang("facturs:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				'options_type' => $this->type_m->get_option_type(),
				"beginning_balance_date" => payable_helper::get_beginning_balance_date(),
				"lookup_suppliers" => base_url("payable/factur/lookup_suppliers"),
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE
			);
		
		$this->template
			->set( "heading", lang("facturs:page") )
					->set_breadcrumb( lang("payable:page") )
					->set_breadcrumb( lang("facturs:page"), base_url("payable/factur") )
			->build('facturs/datatable', (isset($data) ? $data : NULL));
	}

	public function create(  )
	{
		$item_data = array(
				"No_Faktur" => payable_helper::gen_factur_number( date("Y-m-d") ),
				"Currency_ID" => 1,
				"Supplier_ID" => 0,
				"Tgl_Faktur" => date("Y-m-d H:i:s"),
				"Tgl_JatuhTempo" => date("Y-m-d H:i:s"),
				"Tgl_Update" => date("Y-m-d H:i:s"),
				"Nilai_Faktur" => 0,
				"Sisa" => 0,
				"Keterangan" => NULL,
				"User_ID" => $this->user_auth->User_ID,
				"JenisHutang_ID" => NULL,
				"Nilai_Tukar" => 1,
				"HisCurrencyID" => 1,
				"Jenis_Pos" => 'NPOS',
				//"SupplierID_Transaksi" => 0,
				"Diakui_Hutang" => 1,
				"Tgl_Pengakuan" => date("Y-m-d H:i:s"),
				"NRM" => NULL,
				//"Diagnosa" => NULL,
				"Kode_Proyek" => 1,
				"DivisiID" => 9,
			);
			
		$this->load->library( 'my_object', $item_data, 'item' );
		if( $this->input->post() ) 
		{
			
									
			$this->load->library( 'form_validation' );
			
			$detail = $this->input->post("detail");
			$header = array_merge($item_data, $this->input->post("header") );
			$header['Keterangan'] = $detail[0]['Keterangan']; // Mengambil keterangan dari detail
			$header['No_Faktur'] = payable_helper::gen_factur_number( $header['Tgl_Faktur'] );			

			if ( empty( $detail ))
			{
				$response["message"] = lang('facturs:details_cannot_empty');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
				
			}

			foreach ($detail as $row)
			{
				if( empty($row['SectionID']) )
				{
					$response["message"] = lang('facturs:section_details_cannot_empty');
					$response["status"] = "error";
					$response["code"] = "500";
					
					print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
					exit(0);
				}
			}

			if ( $this->get_model()->check_closing_period( $header['Tgl_Faktur'] ) == TRUE )
			{
				$response["message"] = lang('facturs:already_closing_period');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
			
			$this->form_validation->set_rules( $this->factur_m->rules['insert'] );
			$this->form_validation->set_data( $header );

			if( $this->form_validation->run() )
			{

				$response = array(
						"status" => "success",
						"message" => lang('global:created_successfully'),
						"code" => "200",
						"No_Faktur" => urlencode($header['No_Faktur'])
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
						"is_ajax_request" => TRUE,
						"is_modal" => TRUE,
						"form" => TRUE,
						"datatables" => TRUE,
						"options_type" => $this->type_m->get_result(),
						"beginning_balance_date" => payable_helper::get_beginning_balance_date(),
						"lookup_suppliers" => base_url("payable/factur/lookup_suppliers"),
						"submit_url" => base_url("payable/factur/create"),
						"create_url" => base_url("payable/factur/create"),
					);
				
				$this->load->view( 
						'facturs/modal/create_edit', 
						array('form_child' => $this->load->view('facturs/form', $data, true))
					);
			} else
			{
				$data = array(
						"page" => $this->page."_".strtolower(__FUNCTION__),
						"item" => (object) $this->item->toArray(),
						"form" => TRUE,
						"datatables" => TRUE,
						"options_type" => $this->type_m->get_result(),
						"beginning_balance_date" => payable_helper::get_beginning_balance_date(),				
						"lookup_suppliers" => base_url("payable/factur/lookup_suppliers"),
						"submit_url" => base_url("payable/factur/create"),
						"create_url" => base_url("payable/factur/create"),
					);
				$this->template
					->set( "heading", lang("facturs:create_heading") )
					->set_breadcrumb( lang("payable:page") )
					->set_breadcrumb( lang("facturs:page"), base_url("payable/factur") )
					->set_breadcrumb( lang("facturs:create_heading") )
					->build('facturs/form', $data);
			}
		}
	}
		
	public function edit()
	{
		$No_Faktur = $this->input->get("No_Faktur");		
		$item = $this->get_model()->get_row( $No_Faktur );
		
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
			$header['Keterangan'] = $detail[0]['Keterangan'];
			
			$this->load->library( 'form_validation' );
			
			$this->form_validation->set_rules( $this->get_model()->rules['update'] );
			$this->form_validation->set_data( $header );

			
			if ( $this->get_model()->check_already_created_vouchers( $No_Faktur ) == TRUE )
			{
				$response["message"] = lang('facturs:already_created_vouchers');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			
			}

			if ( $item->Posted === 1 )
			{
				$response["message"] = lang('facturs:already_posted');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			
			}
			
			if( $this->form_validation->run() )
			{
				$response["No_Faktur"] = urlencode($No_Faktur);

				if( $this->get_model()->update_data( $header, $detail, $No_Faktur) == FALSE )
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
						"options_type" => $this->type_m->get_result(),
						"beginning_balance_date" => payable_helper::get_beginning_balance_date(),
						"lookup_suppliers" => base_url("payable/factur/lookup_suppliers"),
						"create_url" => base_url("payable/factur/create"),
						"submit_url" => base_url("payable/factur/edit")."?No_Faktur=". urlencode( $No_Faktur ),
						"cancel_url" => base_url("payable/factur/cancel")."?No_Faktur=". urlencode( $No_Faktur ),
						"print_url" => base_url("payable/facturs/export/factur")."?No_Faktur=". urlencode( $No_Faktur ),
					);
				
				$this->load->view( 
						'facturs/modal/create_edit', 
						array('form_child' => $this->load->view('facturs/form', $data, true))
					);
			} else
			{
				$data = array(
						"page" => $this->page,
						"item" => (object) $this->item->toArray(),
						"form" => TRUE,
						"datatables" => TRUE,
						"is_edit" => TRUE,
						"options_type" => $this->type_m->get_result(),
						"beginning_balance_date" => payable_helper::get_beginning_balance_date(),
						"lookup_suppliers" => base_url("payable/factur/lookup_suppliers"),
						"create_url" => base_url("payable/factur/create"),
						"submit_url" => base_url("payable/factur/edit")."?No_Faktur=". urlencode( $No_Faktur ),
						"cancel_url" => base_url("payable/factur/cancel")."?No_Faktur=". urlencode( $No_Faktur ),
						"print_url" => base_url("payable/facturs/export/factur")."?No_Faktur=". urlencode( $No_Faktur ),
					);
					
				$this->template
					->set( "heading", lang("facturs:edit_heading") )
					->set_breadcrumb( lang("payable:page") )
					->set_breadcrumb( lang("facturs:page"), base_url("payable/factur") )
					->set_breadcrumb( lang("facturs:edit_heading") )
					->build('facturs/form', $data);
			}
		}

	}
	
	public function cancel()
	{
		$No_Faktur = $this->input->get("No_Faktur");		
		$item = $this->get_model()->get_row( $No_Faktur );
		
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', (array) $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			$this->load->helper("Approval");
			if ( Approval_helper::approve( 'CANCEL FAKTUR AP', $username, $password ) === FALSE )
			{
				$response["message"] = lang('auth_incorrect');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			}

			if( empty($item->No_Faktur) )
			{
				$response["message"] = lang('global:get_failed');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}
			
			if ( $this->get_model()->check_already_created_vouchers( $No_Faktur ) == TRUE )
			{
				$response["message"] = lang('facturs:already_created_vouchers');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			}

			if ( $item->Posted === 1 )
			{
				$response["message"] = lang('facturs:already_posted');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			
			}
						
			if( $item->No_Faktur == $this->input->post( 'confirm' ) )
			{
				if ( $this->get_model()->cancel_data( $this->input->post( 'confirm' )) === TRUE )
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
				"redirect_url" => base_url("payable/factur/edit")."?No_Faktur=". urlencode( $No_Faktur ),
				"cancel_url" => base_url("payable/factur/cancel")."?No_Faktur=". urlencode( $No_Faktur ),
			);
			
			$this->load->view( 'facturs/modal/delete', $data );	
		}
	}
			
	public function details( $factur, $is_edit = FALSE )
	{
		$data = array(
				"item" => $factur,
				"collection" => $this->get_model()->get_detail_collection( $factur->No_Faktur ),
				"form_action" => base_url("payable/factur/items")."?No_Faktur=". urlencode($factur->No_Faktur),
				"populate_url" => base_url("payable/factur/detail_collection")."?No_Faktur=". urlencode($factur->No_Faktur),
				"lookup_accounts" => base_url("payable/factur/lookup_accounts"), 
				"form" => TRUE,
				"datatables" => TRUE,
			);
		
		if ($is_edit === TRUE)
		{
			return	$this->load->view( "facturs/facturs/tables_edit", $data );		
			
		} else 
		{
			return	$this->load->view( "facturs/facturs/tables", $data );		
		}
		
	}

	public function lookup_accounts( $trId = NULL )
	{
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 'facturs/lookup/accounts', array("trId" => $trId ) );
		} 
	}

	public function lookup_suppliers( ){
	
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 'facturs/lookup/suppliers' );
		} 
	}
	
	public function lookup( $is_ajax_request = FALSE ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== FALSE )
		{
			$this->load->view( 'facturs/lookup/datatable' );
		} 
	}
	public function lookup_collection( $factur_number = false )
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
		
		// Default Filter		
		$db_where['a.Diakui_Hutang'] = 1;
		$db_where['a.Cancel_Faktur'] = 0;
		$db_or_where_group['a.No_Voucher'] = "-";
		$db_or_where_group['a.Cancel_Voucher'] = 1;
		
		if ( !empty( $this->input->post('Supplier_ID') ))
		{
			$db_where['a.Supplier_ID'] = $this->input->post('Supplier_ID');
		}

		// search filter	
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.No_Faktur") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Tgl_Faktur") ] = $keywords;
			$db_like[ $this->db->escape_str("b.No_DO") ] = $keywords;									
        }
				
		// get total records
		$this->db->from( $db_from )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where_group) ){ $this->db->group_start()->or_where( $db_or_where_group )->group_end(); }		
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("Klinik_Kulhen.dbo.BL_trPenerimaan b", "a.No_Faktur = b.No_Penerimaan", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where_group) ){ $this->db->group_start()->or_where( $db_or_where_group )->group_end(); }		
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.No_Faktur,
			a.Keterangan,
			a.Tgl_Faktur, 
			d.Currency_Code, 
			a.Nilai_Faktur, 
			a.JenisHutang_ID,
			b.No_DO
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("Klinik_Kulhen.dbo.BL_trPenerimaan b", "a.No_Faktur = b.No_Penerimaan", "LEFT OUTER" )
			;

		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where_group) ){ $this->db->group_start()->or_where( $db_or_where_group )->group_end(); }		
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
			$row->Tgl_Faktur = substr($row->Tgl_Faktur, 0, 10);
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
	
	public function datatable_collection( $factur_number = false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$this->load->model("common/supplier_m");
		
		$db_from = "{$this->get_model()->table} a";
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		
		$filter = $this->input->post('f');
		
		if ( !empty($filter['date_start']))
		{
			$db_where['a.Tgl_Faktur >='] = $filter['date_start'];
		}

		if ( !empty($filter['date_end']))
		{
			$db_where['a.Tgl_Faktur <='] = $filter['date_end'];
		}

		if ( !empty($filter['type_id']))
		{
			$db_where['a.JenisHutang_ID'] = $filter['type_id'];		
		}

		if ( !empty($filter['supplier_id']))
		{
			$db_where['a.Supplier_ID'] = $filter['supplier_id'];		
		}
		
		if ( is_numeric($filter['factur_cancel']))
		{
			$db_where['a.Cancel_Faktur'] = $filter['factur_cancel'];		
		}
					
		// search filter	
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.No_Faktur") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Tgl_Faktur") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Keterangan") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nilai_Faktur") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Sisa") ] = $keywords;

			$db_like[ $this->db->escape_str("b.Nama_Supplier") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Kode_Supplier") ] = $keywords;						
        }
		
		// get total records
		$this->db->from( $db_from )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("mSupplier b", "a.Supplier_ID = b.Supplier_ID", "INNER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.No_Faktur,
			a.Keterangan,
			b.Kode_Supplier, 
			b.Nama_Supplier, 
			c.Nama_Proyek, 
			a.Tgl_Faktur, 
			d.Currency_Code, 
			a.Nilai_Faktur, 
			a.Supplier_ID, 
			a.Currency_ID, 
			a.JenisHutang_ID, 
			a.Kode_Proyek, 
			e.Nama_Divisi, 
			a.DivisiID,
			f.Nama_Singkat
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("mSupplier b", "a.Supplier_ID = b.Supplier_ID", "INNER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "INNER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "INNER" )
			->join("mUser f", "a.User_ID= f.User_ID", "LEFT OUTER" )
			;

		if( !empty($db_where) ){ $this->db->where( $db_where ); }
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
			$row->Tgl_Faktur = substr($row->Tgl_Faktur, 0, 10);
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
		
}



