<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Credit_debit_note extends Admin_Controller
{
	protected $_translation = 'payable';	
	protected $_model = 'credit_debit_note_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('payable');
				
		$this->load->model( "credit_debit_note_m" );
		
		$this->load->helper("payable");
		
		$this->load->model("payable/type_m");
		$this->load->model("general_ledger/account_m");
		
		$this->page = "payable";
		$this->template->title( lang("credit_debit_notes:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				'options_type' => $this->type_m->get_option_type(),
				"beginning_balance_date" => payable_helper::get_beginning_balance_date(),
				"lookup_suppliers" => base_url("payable/credit-debit-note/lookup_suppliers"),
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE
			);
		
		$this->template
			->set( "heading", lang("credit_debit_notes:page") )
			->set_breadcrumb( lang("payables:page") )
			->set_breadcrumb( lang("credit_debit_notes:page"), base_url("payable/credit-debit-note") )
			->build('credit_debit_notes/datatable', (isset($data) ? $data : NULL));
	}

	public function create(  )
	{

		$item_data = array(
				"No_Voucher" => payable_helper::gen_credit_debit_note_number( date("Y-m-d") ),
				"Currency_ID" => 1,
				"Supplier_ID" => 0,
				"Tgl_Voucher" => date("Y-m-d H:i:s"),
				"Tgl_Tempo" => date("Y-m-d H:i:s"),
				"Tgl_Update" => date("Y-m-d H:i:s"),
				"Nilai" => 0,
				"Sisa" => 0,
				"Keterangan" => NULL,
				"User_Id" => $this->user_auth->User_ID,
				"JTransaksi_ID" => NULL,
				"JenisHutang_ID" => NULL,
				"Akun_ID" => NULL,
				"Nilai_Tukar" => 1,
				"HisCurrencyID" => 1,
				"Cancel_Voucher" => 0,
				"Kode_Proyek" => 1,
				"DivisiID" => 9,
				"SectionID" => config_item('SectionIDCorporate'),
				"Cancel_Voucher" => 0,
				"TutupBuku" => 0,
				"Posted" => 0
			);
			
		$this->load->library( 'my_object', $item_data, 'item' );
		if( $this->input->post() ) 
		{
			
									
			$this->load->library( 'form_validation' );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => "200",
				);

			$nota = array_merge($this->item->toArray(), $this->input->post("nota"));
			$nota["No_Voucher"] = payable_helper::gen_credit_debit_note_number( $nota["Tgl_Voucher"] );
			$voucher = $this->input->post("voucher");
			$factur = $this->input->post("factur");
			
			if( empty($voucher) )
			{
				$response["message"] = lang('credit_debit_notes:voucher_not_selected');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}
			
			$tot_voucher_debit = $tot_voucher_credit = 0;
			foreach($voucher as $row)
			{
				if ( $nota['Tgl_Voucher'] < $row['Tgl_Voucher'] )
				{
					$response["message"] = sprintf(lang('credit_debit_notes:transaction_date_incorret'), $row['No_Voucher'] );
					$response["status"] = "error";
					$response["code"] = "500";				
					
					print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
					exit(0);
				}

				if ( $row['Debit'] == 0 && $row['Kredit'] == 0 )
				{
					$response["message"] = sprintf(lang('credit_debit_notes:empty_increase_decrease'), $row['No_Voucher'] );
					$response["status"] = "error";
					$response["code"] = "500";				
					
					print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
					exit(0);
				}

				if ( $row['Nilai'] < $row['Kredit'] )
				{
					$response["message"] = sprintf(lang('credit_debit_notes:decrease_value_exceed'), $row['No_Voucher'] );
					$response["status"] = "error";
					$response["code"] = "500";				
					
					print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
					exit(0);
				}
				
				$tot_voucher_debit  = $tot_voucher_debit + $row['Debit'];
				$tot_voucher_credit = $tot_voucher_credit + $row['Kredit'];
			}
			
			if ( $tot_voucher_debit > 0 && $tot_voucher_credit > 0 )
			{
				$response["message"] = lang('credit_debit_notes:simultaneously_increase_decrease');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			}
		
			if ( payable_helper::check_closing_period( $nota['Tgl_Voucher'] ) === TRUE )
			{
				$date = DateTime::createFromFormat("Y-m-d", $nota['Tgl_Voucher'] );
				$response["message"] = sprintf(lang('credit_debit_notes:already_closing_period'), $date->format("F Y") );
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
			
			$this->form_validation->set_rules( $this->credit_debit_note_m->rules['insert'] );
			$this->form_validation->set_data( $nota );
			
			if( $this->form_validation->run() )
			{

				$response = array(
						"status" => "success",
						"message" => lang('global:created_successfully'),
						"code" => "200",
						"No_Voucher" => urlencode($nota['No_Voucher'])
					);
				
				if ($this->get_model()->create_data( $nota, $voucher, $factur ) === FALSE)
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
						"submit_url" => current_url(),
						"create_url" => base_url("payable/credit-debit-note/create"),
						"lookup_suppliers" => base_url("payable/credit-debit-note/lookup_suppliers"),
						"lookup_vouchers" => ase_url("payable/credit-debit-note/lookup_vouchers"),
						"lookup_accounts" => base_url("payable/credit-debit-note/lookup_accounts"),
					);
				
				$this->load->view( 
						'credit_debit_notes/modal/create_edit', 
						array('form_child' => $this->load->view('credit_debit_notes/form', $data, true))
					);
			} else
			{
				$data = array(
						"page" => $this->page."_".strtolower(__FUNCTION__),
						"item" => (object) $this->item->toArray(),
						"form" => TRUE,
						"datatables" => TRUE,
						"web_stroge" => TRUE,
						"navigation_minimized" => TRUE,
						"submit_url" => current_url(),
						"create_url" => base_url("payable/credit-debit-note/create"),
						"lookup_suppliers" => base_url("payable/credit-debit-note/lookup_suppliers"),
						"lookup_vouchers" => base_url("payable/credit-debit-note/lookup_vouchers"),
						"lookup_accounts" => base_url("payable/credit-debit-note/lookup_accounts"),
					);
				
				$this->template
					->set( "heading", lang("credit_debit_notes:create_heading") )
					->set_breadcrumb( lang("payables:page") )
					->set_breadcrumb( lang("credit_debit_notes:page"), base_url("payable/credit-debit-note") )
					->set_breadcrumb( lang("credit_debit_notes:create_heading") )
					->build('credit_debit_notes/form', $data);
			}
		}
	}
		
	public function edit()
	{
		$No_Voucher = $this->input->get("No_Voucher");		
		$item = $this->get_model()->get_row( $No_Voucher );
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
				if ( $header['Tgl_Voucher'] < $row['Tgl_transaksi'] )
				{
					$response["message"] = sprintf(lang('credit_debit_notes:transaction_date_incorret'), $header['Tgl_Voucher'], $row['Tgl_transaksi'] );
					$response["status"] = "error";
					$response["code"] = "500";				
					
					print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
					exit(0);
				}
			}

			if ( empty( $detail ))
			{
				$response["message"] = lang('credit_debit_notes:details_cannot_empty');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
			
			if ( payable_helper::check_closing_period( $header['Tgl_Voucher'] ) === TRUE )
			{
				$date = DateTime::createFromFormat("Y-m-d", $header['Tgl_Voucher'] );
				$response["message"] = sprintf(lang('credit_debit_notes:already_closing_period'), $date->format("F Y") );
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
			
			if( $this->form_validation->run() )
			{
				$response["No_Voucher"] = urlencode($No_Voucher);

				if( $this->get_model()->update_data( $header, $No_Voucher) == FALSE )
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
						"beginning_balance_date" => payable_helper::get_beginning_balance_date(),
						"lookup_suppliers" => base_url("payable/credit-debit-note/lookup_suppliers"),
						"create_url" => base_url("payable/credit-debit-note/create"),
						"submit_url" => base_url("payable/credit-debit-note/edit")."?No_Voucher=". urlencode( $No_Voucher ),
						"delete_url" => base_url("payable/credit-debit-note/delete")."?No_Voucher=". urlencode( $No_Voucher ),
					);
				
				$this->load->view( 
						'credit_debit_notes/modal/create_edit', 
						array('form_child' => $this->load->view('credit_debit_notes/form', $data, true))
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
						"beginning_balance_date" => payable_helper::get_beginning_balance_date(),
						"lookup_suppliers" => base_url("payable/credit-debit-note/lookup_suppliers"),
						"create_url" => base_url("payable/credit-debit-note/create"),
						"submit_url" => base_url("payable/credit-debit-note/edit")."?No_Voucher=". urlencode( $No_Voucher ),
						"delete_url" => base_url("payable/credit-debit-note/delete")."?No_Voucher=". urlencode( $No_Voucher ),
					);
					
				$this->template
					->set( "heading", lang("credit_debit_notes:edit_heading") )
					->set_breadcrumb( lang("payables:page") )
					->set_breadcrumb( lang("credit_debit_notes:page"), base_url("payable/credit-debit-note") )
					->set_breadcrumb( lang("credit_debit_notes:edit_heading") )
					->build('credit_debit_notes/form', $data);
			}
		}
	}
	
	public function delete()
	{
		$No_Voucher = $this->input->get("No_Voucher");		
		$item = $this->get_model()->get_row( $No_Voucher );
		
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', (array) $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			$nota = array_merge($this->item->toArray(), $this->input->post("nota"));
			$voucher = $this->input->post("voucher");
			$factur = $this->input->post("factur");
			
			if( empty($item->No_Voucher) )
			{
				$response["message"] = lang('global:get_failed');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}

			if ( payable_helper::check_closing_period( $nota['Tgl_Voucher'] ) === TRUE )
			{
				$date = DateTime::createFromFormat("Y-m-d", $nota['Tgl_Voucher'] );
				$response["message"] = sprintf(lang('credit_debit_notes:already_closing_period'), $date->format("F Y") );
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
						
			if( $item->No_Voucher == $nota['No_Voucher'] )
			{
				if ( $this->get_model()->delete_data( $nota, $voucher, $factur ) === TRUE )
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
				"redirect_url" => base_url("payable/credit-debit-note/edit")."?No_Voucher=". urlencode( $No_Voucher ),
				"delete_url" => base_url("payable/credit-debit-note/delete")."?No_Voucher=". urlencode( $No_Voucher ),
			);
			
			$this->load->view( 'credit_debit_notes/modal/delete', $data );	
		}
	}
		
	
	public function vouchers( $item, $is_edit = FALSE )
	{
		$data = array(
				"item" => $item,
				"collection" => $this->get_model()->get_voucher_collection( $item->No_Voucher ),
				"get_voucher_detail_url" => (!$is_edit) 
											? base_url("payable/credit-debit-note/get_voucher_detail")
											: base_url("payable/credit-debit-note/get_voucher_factur"),
				"form_voucher_detail_url" => base_url("payable/credit-debit-note/lookup_form_voucher_detail")
			);
		
		if ($is_edit === TRUE)
		{
			return	$this->load->view( "credit_debit_notes/form/voucher_edit", $data );		
			
		} else 
		{
			return	$this->load->view( "credit_debit_notes/form/voucher", $data );		
		}
		
	}

	public function get_voucher_detail()
	{
		if ( $this->input->get("No_Voucher") )
		{
			if ( $collection = $this->get_model()->get_voucher_detail( $this->input->get("No_Voucher") ) )
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

	public function get_voucher_factur()
	{
		if ( $this->input->get("No_Voucher") && $this->input->get("No_Bukti") )
		{
			if ( $collection = $this->get_model()->get_voucher_factur( $this->input->get("No_Voucher"), $this->input->get("No_Bukti") ) )
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
		
	public function lookup_form_voucher_detail( $is_edit = FALSE ){
	
		if( $this->input->is_ajax_request() )
		{
			$data = array(
				"No_Voucher" => $this->input->get("No_Voucher"),
				"is_edit" => $is_edit
			);
			$this->load->view( 'credit_debit_notes/form/voucher_details', $data );
		} 
	}

	public function lookup_accounts(  ){
	
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 'credit_debit_notes/lookup/accounts' );
		} 
	}

	public function lookup_suppliers( ){
	
		if( $this->input->is_ajax_request() )
		{	
			$data = array(
				"load_datatable" => $this->load->view("common/suppliers/lookup/datatable_back_office", array(), TRUE)
			);
			$this->load->view( 'credit_debit_notes/lookup/suppliers', $data );
			
		} 
	}

	public function lookup_vouchers( ){
	
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 'credit_debit_notes/lookup/vouchers' );
		} 
	}
	
	public function datatable_collection( $credit_debit_note_number = false )
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
		$db_or_where_group = array();
		$db_like = array();
		$db_not_like = array();
		
		// prepare defautl flter
		$filter = $this->input->post('f');	
		
		$db_where['a.No_Voucher <>'] = '';
		$db_or_where_group['a.JTransaksi_ID '] = 406;
		$db_or_where_group['a.JTransaksi_ID'] = 407;

		if ( !empty($filter['date_start']))
		{
			$db_where['a.Tgl_Voucher >='] = $filter['date_start'];
		}

		if ( !empty($filter['date_end']))
		{
			$db_where['a.Tgl_Voucher <='] = $filter['date_end'];
		}
		if ( !empty($filter['supplier_id']))
		{
			$db_where['a.Supplier_ID'] = $filter['supplier_id'];		
		}
		
		if ( !empty($filter['type_id']))
		{
			$db_where['a.JenisHutang_ID'] = $filter['type_id'];		
		}
		
		if ( !empty($filter['view_state']) )
		{
			switch ($filter['view_state'])
			{
				case "all" :  break;
				case "active" : $db_where['a.Cancel_Voucher'] = 0; break;
				case "cancel" : $db_where['a.Cancel_Voucher'] = 1; break;
			}
		}

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            /*$keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.voucher_number") ] = $keywords;
			$db_like[ $this->db->escape_str("a.voucher_date") ] = $keywords;
			$db_like[ $this->db->escape_str("a.description") ] = $keywords;
			$db_like[ $this->db->escape_str("a.value") ] = $keywords;*/

        }
		
		// get total records
		$this->db->from( $db_from )
			->join("mSupplier b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "INNER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "INNER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where_group) ){ $this->db->group_start()->or_where( $db_or_where_group )->group_end(); }		
		if( !empty($db_not_like) ){ $this->db->not_like( $db_not_like ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("mSupplier b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "INNER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "INNER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where_group) ){ $this->db->group_start()->or_where( $db_or_where_group )->group_end(); }		
		if( !empty($db_not_like) ){ $this->db->not_like( $db_not_like ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.Tgl_Voucher, 
			a.No_Voucher, 
			b.Nama_Supplier, 
			a.Nilai, 
			a.Cancel_Voucher, 
			a.Sudah_Dibuatkan_Bukti,
			c.Nama_Proyek,
			d.Currency_Code, 
			e.Nama_Divisi 
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("mSupplier b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "INNER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "INNER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
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
			$row->Tgl_Voucher = substr($row->Tgl_Voucher, 0, 10);
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
}



