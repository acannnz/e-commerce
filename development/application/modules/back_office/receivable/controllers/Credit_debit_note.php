<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Credit_debit_note extends Admin_Controller
{
	protected $_translation = 'receivable';	
	protected $_model = 'credit_debit_note_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('receivable');
				
		$this->load->model( "credit_debit_note_m" );
		
		$this->load->helper("receivable");
		
		$this->load->model("receivable/type_m");
		$this->load->model("general_ledger/account_m");
		
		$this->page = "receivable";
		$this->template->title( lang("credit_debit_notes:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				'options_type' => $this->type_m->get_option_type(),
				"beginning_balance_date" => receivable_helper::get_beginning_balance_date(),
				"lookup_customers" => base_url("receivable/credit-debit-note/lookup_customers"),
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE
			);
		
		$this->template
			->set( "heading", lang("credit_debit_notes:page") )
			->set_breadcrumb( lang("receivables:page") )
			->set_breadcrumb( lang("credit_debit_notes:page"), base_url("receivable/credit-debit-note") )
			->build('credit_debit_notes/datatable', (isset($data) ? $data : NULL));
	}

	public function create(  )
	{

		$item_data = array(
				"No_Invoice" => receivable_helper::gen_credit_debit_note_number( date("Y-m-d") ),
				"Currency_ID" => 1,
				"Customer_ID" => 0,
				"Tgl_Invoice" => date("Y-m-d H:i:s"),
				"Tgl_Tempo" => date("Y-m-d H:i:s"),
				"Tgl_Update" => date("Y-m-d H:i:s"),
				"Nilai" => 0,
				"Sisa" => 0,
				"Keterangan" => NULL,
				"User_Id" => $this->user_auth->User_ID,
				"JTransaksi_ID" => NULL,
				"JenisPiutang_ID" => NULL,
				"Akun_ID" => NULL,
				"Nilai_Tukar" => 1,
				"HisCurrencyID" => 1,
				"Cancel_Invoice" => 0,
				"Kode_Proyek" => 1,
				"DivisiID" => 9,
				"SectionID" => config_item('SectionIDCorporate'),
				"Cancel_Invoice" => 0,
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
			$nota["No_Invoice"] = receivable_helper::gen_credit_debit_note_number( $nota["Tgl_Invoice"] );
			$invoice = $this->input->post("invoice");
			$factur = $this->input->post("factur");
			
			if( empty($invoice) )
			{
				$response["message"] = lang('credit_debit_notes:invoice_not_selected');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}
			
			$tot_invoice_debit = $tot_invoice_credit = 0;
			foreach($invoice as $row)
			{
				if ( $nota['Tgl_Invoice'] < $row['Tgl_Invoice'] )
				{
					$response["message"] = sprintf(lang('credit_debit_notes:transaction_date_incorret'), $row['No_Invoice'] );
					$response["status"] = "error";
					$response["code"] = "500";				
					
					print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
					exit(0);
				}

				if ( $row['Debit'] == 0 && $row['Kredit'] == 0 )
				{
					$response["message"] = sprintf(lang('credit_debit_notes:empty_increase_decrease'), $row['No_Invoice'] );
					$response["status"] = "error";
					$response["code"] = "500";				
					
					print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
					exit(0);
				}

				if ( $row['Nilai'] < $row['Kredit'] )
				{
					$response["message"] = sprintf(lang('credit_debit_notes:decrease_value_exceed'), $row['No_Invoice'] );
					$response["status"] = "error";
					$response["code"] = "500";				
					
					print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
					exit(0);
				}
				
				$tot_invoice_debit  = $tot_invoice_debit + $row['Debit'];
				$tot_invoice_credit = $tot_invoice_credit + $row['Kredit'];
			}
			
			if ( $tot_invoice_debit > 0 && $tot_invoice_credit > 0 )
			{
				$response["message"] = lang('credit_debit_notes:simultaneously_increase_decrease');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			}
		
			if ( receivable_helper::check_closing_period( $nota['Tgl_Invoice'] ) === TRUE )
			{
				$date = DateTime::createFromFormat("Y-m-d", $nota['Tgl_Invoice'] );
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
						"No_Invoice" => urlencode($nota['No_Invoice'])
					);
				
				if ($this->get_model()->create_data( $nota, $invoice, $factur ) === FALSE)
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
						"create_url" => base_url("receivable/credit-debit-note/create"),
						"lookup_customers" => base_url("receivable/credit-debit-note/lookup_customers"),
						"lookup_invoices" => ase_url("receivable/credit-debit-note/lookup_invoices"),
						"lookup_accounts" => base_url("receivable/credit-debit-note/lookup_accounts"),
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
						"create_url" => base_url("receivable/credit-debit-note/create"),
						"lookup_customers" => base_url("receivable/credit-debit-note/lookup_customers"),
						"lookup_invoices" => base_url("receivable/credit-debit-note/lookup_invoices"),
						"lookup_accounts" => base_url("receivable/credit-debit-note/lookup_accounts"),
					);
				
				$this->template
					->set( "heading", lang("credit_debit_notes:create_heading") )
					->set_breadcrumb( lang("receivables:page") )
					->set_breadcrumb( lang("credit_debit_notes:page"), base_url("receivable/credit-debit-note") )
					->set_breadcrumb( lang("credit_debit_notes:create_heading") )
					->build('credit_debit_notes/form', $data);
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
					$response["message"] = sprintf(lang('credit_debit_notes:transaction_date_incorret'), $header['Tgl_Invoice'], $row['Tgl_transaksi'] );
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
			
			if ( receivable_helper::check_closing_period( $header['Tgl_Invoice'] ) === TRUE )
			{
				$date = DateTime::createFromFormat("Y-m-d", $header['Tgl_Invoice'] );
				$response["message"] = sprintf(lang('credit_debit_notes:already_closing_period'), $date->format("F Y") );
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
						"lookup_customers" => base_url("receivable/credit-debit-note/lookup_customers"),
						"create_url" => base_url("receivable/credit-debit-note/create"),
						"submit_url" => base_url("receivable/credit-debit-note/edit")."?No_Invoice=". urlencode( $No_Invoice ),
						"delete_url" => base_url("receivable/credit-debit-note/delete")."?No_Invoice=". urlencode( $No_Invoice ),
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
						"beginning_balance_date" => receivable_helper::get_beginning_balance_date(),
						"lookup_customers" => base_url("receivable/credit-debit-note/lookup_customers"),
						"create_url" => base_url("receivable/credit-debit-note/create"),
						"submit_url" => base_url("receivable/credit-debit-note/edit")."?No_Invoice=". urlencode( $No_Invoice ),
						"delete_url" => base_url("receivable/credit-debit-note/delete")."?No_Invoice=". urlencode( $No_Invoice ),
					);
					
				$this->template
					->set( "heading", lang("credit_debit_notes:edit_heading") )
					->set_breadcrumb( lang("receivables:page") )
					->set_breadcrumb( lang("credit_debit_notes:page"), base_url("receivable/credit-debit-note") )
					->set_breadcrumb( lang("credit_debit_notes:edit_heading") )
					->build('credit_debit_notes/form', $data);
			}
		}
	}
	
	public function delete()
	{
		$No_Invoice = $this->input->get("No_Invoice");		
		$item = $this->get_model()->get_row( $No_Invoice );
		
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', (array) $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			$nota = array_merge($this->item->toArray(), $this->input->post("nota"));
			$invoice = $this->input->post("invoice");
			$factur = $this->input->post("factur");
			
			if( empty($item->No_Invoice) )
			{
				$response["message"] = lang('global:get_failed');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);			
			}

			if ( receivable_helper::check_closing_period( $nota['Tgl_Invoice'] ) === TRUE )
			{
				$date = DateTime::createFromFormat("Y-m-d", $nota['Tgl_Invoice'] );
				$response["message"] = sprintf(lang('credit_debit_notes:already_closing_period'), $date->format("F Y") );
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
						
			if( $item->No_Invoice == $nota['No_Invoice'] )
			{
				if ( $this->get_model()->delete_data( $nota, $invoice, $factur ) === TRUE )
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
				"redirect_url" => base_url("receivable/credit-debit-note/edit")."?No_Invoice=". urlencode( $No_Invoice ),
				"delete_url" => base_url("receivable/credit-debit-note/delete")."?No_Invoice=". urlencode( $No_Invoice ),
			);
			
			$this->load->view( 'credit_debit_notes/modal/delete', $data );	
		}
	}
		
	
	public function invoices( $item, $is_edit = FALSE )
	{
		$data = array(
				"item" => $item,
				"collection" => $this->get_model()->get_invoice_collection( $item->No_Invoice ),
				"get_invoice_detail_url" => (!$is_edit) 
											? base_url("receivable/credit-debit-note/get_invoice_detail")
											: base_url("receivable/credit-debit-note/get_invoice_factur"),
				"form_invoice_detail_url" => base_url("receivable/credit-debit-note/lookup_form_invoice_detail")
			);
		
		if ($is_edit === TRUE)
		{
			return	$this->load->view( "credit_debit_notes/form/invoice_edit", $data );		
			
		} else 
		{
			return	$this->load->view( "credit_debit_notes/form/invoice", $data );		
		}
		
	}

	public function get_invoice_detail()
	{
		if ( $this->input->get("No_Invoice") )
		{
			if ( $collection = $this->get_model()->get_invoice_detail( $this->input->get("No_Invoice") ) )
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

	public function get_invoice_factur()
	{
		if ( $this->input->get("No_Invoice") && $this->input->get("No_Bukti") )
		{
			if ( $collection = $this->get_model()->get_invoice_factur( $this->input->get("No_Invoice"), $this->input->get("No_Bukti") ) )
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
		
	public function lookup_form_invoice_detail( $is_edit = FALSE ){
	
		if( $this->input->is_ajax_request() )
		{
			$data = array(
				"No_Invoice" => $this->input->get("No_Invoice"),
				"is_edit" => $is_edit
			);
			$this->load->view( 'credit_debit_notes/form/invoice_details', $data );
		} 
	}

	public function lookup_accounts(  ){
	
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 'credit_debit_notes/lookup/accounts' );
		} 
	}

	public function lookup_customers( ){
	
		if( $this->input->is_ajax_request() )
		{	
			$data = array(
				"load_datatable" => $this->load->view("common/customers/lookup/datatable_back_office", array(), TRUE)
			);
			$this->load->view( 'credit_debit_notes/lookup/customers', $data );
			
		} 
	}

	public function lookup_invoices( ){
	
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 'credit_debit_notes/lookup/invoices' );
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
		
		$this->load->model("common/customer_m");
		
		$db_from = "{$this->get_model()->table} a";
		$db_where = array();
		$db_or_where_group = array();
		$db_like = array();
		$db_not_like = array();
		
		// prepare defautl flter
		$filter = $this->input->post('f');	
		
		$db_where['a.No_Invoice <>'] = '';
		$db_or_where_group['a.JTransaksi_ID '] = 205;
		$db_or_where_group['a.JTransaksi_ID'] = 206;

		if ( !empty($filter['date_start']))
		{
			$db_where['a.Tgl_Invoice >='] = $filter['date_start'];
		}

		if ( !empty($filter['date_end']))
		{
			$db_where['a.Tgl_Invoice <='] = $filter['date_end'];
		}
		if ( !empty($filter['customer_id']))
		{
			$db_where['a.Customer_ID'] = $filter['customer_id'];		
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
			->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
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
			->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
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
			a.Tgl_Invoice, 
			a.No_Invoice, 
			b.Nama_Customer, 
			a.Nilai, 
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
			$row->Tgl_Invoice = substr($row->Tgl_Invoice, 0, 10);
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
}



