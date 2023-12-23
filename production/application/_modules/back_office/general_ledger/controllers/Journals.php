<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Journals extends Admin_Controller
{
	protected $_translation = 'general_ledger';	
	protected $_model = 'journal_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_ledger');
				
		$this->load->model( "account_m" );
		
		$this->load->helper("general_ledger");
		
		$this->page = "general_ledger";
		$this->template->title( lang("journals:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
				"option_division" => general_ledger_helper::get_option_division(),
				"option_project" => general_ledger_helper::get_option_project(),
			);
		
		$this->template
			->set( "heading", lang("journals:page") )
			->set_breadcrumb( lang("journals:page"), base_url("general-ledger/journals") )
			->build('journals/datatable', (isset($data) ? $data : NULL));
	}

	public function create()
	{
		$item_data = array(
				'No_Bukti' => general_ledger_helper::gen_journal_number( date('Y-m-d')),
				'Relasi_ID' => 0,
				'Currency_ID' => general_ledger_helper::get_default_currency_id(),
				'HisCurrency_ID' => general_ledger_helper::get_his_currency( date("Y-m-d") ),
				'Transaksi_Date' => date("Y-m-d"),
				'Kode_transfer' => NULL,
				'Tgl_Update' => date("Y-m-d H:i:s"),
				'Debit' => 0,
				'Kredit' => 0,
				'Nilai_Tukar' => general_ledger_helper::get_currency_exchange_rate( general_ledger_helper::get_default_currency_id() ),
				'Posted' => 0,
				'User_ID' => $this->user_auth->User_ID,
				'Integrasi' => 0,
				'Type_Jurnal' => 1,
				'Keterangan' => NULL,
				'Recurring_Name' => NULL,
				'Kode_Proyek' => NULL,
				'DivisiID' => NULL,
			);

		if( $this->input->post() ) 
		{								
			$item = (object) array_merge($item_data, $this->input->post("f"));
			$item->No_Bukti = general_ledger_helper::gen_journal_number( $item->Transaksi_Date );
			
			$details = (object) $this->input->post("details");
				
			$response = array(
					"message" => lang('global:created_successfully'),
					"status" => "success",
					"code" => "200",
					$this->get_model()->primary_key => $item->{$this->get_model()->primary_key},
				);			
			
			$this->load->library( 'form_validation' );			
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( (array) $item );
			if( $this->form_validation->run() )
			{
				if( ! $this->get_model()->create_data($item, $details) )
				{					
					$response["message"] = lang('global:created_failed');
					$response["status"] = "error";
					$response["code"] = "500";
				}
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500aaa";
			}
			
			$this->template->build_json( $response );

		} else {
		
			if( $this->input->is_ajax_request() )
			{
				$data = array(
						"item" => (object) $item_data,
						"is_ajax_request" => TRUE,
						"is_modal" => TRUE,
						"form" => TRUE,
						"datatables" => TRUE,
						"option_currency" => general_ledger_helper::get_option_currency(),
						"option_division" => general_ledger_helper::get_option_division(),
						"option_project" => general_ledger_helper::get_option_project(),
					);
				
				$this->load->view( 
						'journals/modal/create_edit', 
						array('form_child' => $this->load->view('journals/form', $data, true))
					);
			} else
			{

				$data = array(
						"page" => $this->page."_".strtolower(__FUNCTION__),
						"item" => (object) $item_data,
						"form" => TRUE,
						"datatables" => TRUE,
						"option_currency" => general_ledger_helper::get_option_currency(),
						"option_division" => general_ledger_helper::get_option_division(),
						"option_project" => general_ledger_helper::get_option_project(),
					);
				
				$this->template
					->set( "heading", lang("journals:create_heading") )
					->set_breadcrumb( lang("general_ledgers:page"), base_url("general-ledger") )
					->set_breadcrumb( lang("journals:breadcrumb"), base_url("general-ledger/journals") )
					->set_breadcrumb( lang("journals:create_heading") )
					->build('journals/form', $data);
			}
		}
	}
		
	public function edit( $No_Bukti = NULL )
	{		
		$No_Bukti = !empty($No_Bukti) ? $No_Bukti : $this->input->get( $this->get_model()->primary_key );
		$item = $this->get_model()->get_row( $No_Bukti );
		
		if( ! $item ){ $item = array( $this->get_model()->primary_key => 0); }
		$this->load->library( 'my_object', $item, 'item' );

		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$details = (object) $this->input->post("details");
			
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			$response = array(
					"message" => lang('global:updated_successfully'),
					"status" => "success",
					"code" => "200",
				);
			
			if( $this->form_validation->run() )
			{
				if( ! $this->get_model()->update_data( (object) $this->item->toArray(), $details ) )
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

			$this->template->build_json( $response );

		} else {
			
			if( $this->input->is_ajax_request() )
			{
				$data = array(
						"item" => (object) $this->item->getData(),
						"option_currency" => general_ledger_helper::get_option_currency(),
						"option_division" => general_ledger_helper::get_option_division(),
						"option_project" => general_ledger_helper::get_option_project(),
						"is_ajax_request" => TRUE,
						"is_modal" => TRUE,
						"is_edit" => TRUE,
						"form" => TRUE,
						"datatables" => TRUE,
					);
				
				$this->load->view( 
						'journals/modal/create_edit', 
						array('form_child' => $this->load->view('journals/form', $data, true))
					);
			} else
			{
				$data = array(
						"page" => $this->page,
						"item" => (object) $this->item->getData(),
						"option_currency" => general_ledger_helper::get_option_currency(),
						"option_division" => general_ledger_helper::get_option_division(),
						"option_project" => general_ledger_helper::get_option_project(),
						"form" => TRUE,
						"datatables" => TRUE,
						"is_edit" => TRUE,
					);
				
				$this->template
					->set( "heading", lang("journals:edit_heading") )
					->set_breadcrumb( lang("general_ledger:page"), base_url("general-ledger") )
					->set_breadcrumb( lang("journals:breadcrumb"), base_url("general-ledger/journals") )
					->set_breadcrumb( lang("journals:edit_heading") )
					->build('journals/form', $data);
			}
		}

	}
	
	public function delete( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->get_model()->as_array()->get( $id );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			if( 0 == @$this->item->id )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
			}
			
			if( $this->item->id == $this->input->post( 'confirm' ) )
			{
				if ( $this->get_model()->delete_transaction_journal( $this->item->No_Bukti ))
				{
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:deleted_successfully')
						));				
						
				} else {
					
					make_flashdata(array(
						'response_status' => 'error',
						'message' => $this->db->_error_message()
					));
				
				}
			}
			
			redirect("general-ledger/journals");

		} else {
			
			$this->load->view( 'journals/modal/delete', array('item' => $this->item, "child") );	
		}
		
	}
		
	public function update( $No_Bukti = '' )
	{
		if( $No_Bukti == '' )
		{
			$No_Bukti = $this->input->get_post( 'No_Bukti', TRUE );
		}
		
		$data = array(
				"No_Bukti" => $No_Bukti,
				"form_action" => base_url("general_ledger/journals/update")."?No_Bukti={$No_Bukti}",
				"count_balance_url" => base_url("general_ledger/journals/count_tariff_total")."?No_Bukti={$No_Bukti}",
				"balance" => 0,
			);
		
		if( $this->input->is_ajax_request() )
		{
			if( $this->input->post() )
			{
				//if( $tariff_total = (float) $this->input->post( 'tariff_total', TRUE ) )
				//{
					$tariff_total = (float) $this->input->post( 'tariff_total', TRUE );
					
					$this->get_model()->update(
							array('tariff_total' => $tariff_total), 
							array('No_Bukti' => $No_Bukti)
						);
					
					exit();
				//}
			}
			
			if( $this->input->post() )
			{
				//if( $service_tariff = (float) $this->input->post( 'service_tariff', TRUE ) )
				//{
					$service_tariff = (float) $this->input->post( 'service_tariff', TRUE );
					$this->chart_m->update(
							array('service_tariff' => $service_tariff), 
							array('No_Bukti' => $No_Bukti)
						);					
					exit();
				//}
			}
		} else
		{
			if( $this->session->has_userdata( "applied.chart" ) )
			{
				$item = $this->session->userdata( "applied.chart" );
			} else
			{
				if( chart_helper::find_chart($No_Bukti) )
				{
					$item = chart_helper::get_chart( $No_Bukti );
				}
			}
			
			if( isset($item) )
			{
				$data['item'] = $item;
				$data['service_tariff'] = $item->service_tariff;
			}
			
			$this->load->view( "form/services", $data );
		}
	}
	
	public function details( $journal )
	{
		
		$data = array(
				"No_Bukti" => $journal->No_Bukti,
				"journal" => $journal,
				"collection" => $this->get_model()->get_detail( $journal->No_Bukti ),
				"option_section" => general_ledger_helper::get_option_section(),
				"form_action" => base_url("general_ledger/journals/items")."?No_Bukti={$journal->No_Bukti}",
				"populate_url" => base_url("general_ledger/journals/detail_collection")."?No_Bukti={$journal->No_Bukti}",
				"lookup_accounts" => base_url("general_ledger/journals/lookup_accounts"), 
				"create_url" => base_url("general_ledger/journals/item_create")."?No_Bukti={$journal->No_Bukti}",
				"update_url" => base_url("general_ledger/journals/item_update")."?No_Bukti={$journal->No_Bukti}",
				"delete_url" => base_url("general_ledger/journals/item_delete")."?No_Bukti={$journal->No_Bukti}",
				"details_module_url" => base_url("components/services/autocomplete"),
				"details_search_url" => base_url("components/services/autocomplete"),
				"details_apply_url" => base_url("general_ledger/journals/component_apply")."?No_Bukti={$journal->No_Bukti}",
				"form" => TRUE,
				"datatables" => TRUE,
			);
		
		$this->load->view( "journals/journals/tables", $data );		
		
	}
	
	public function lookup_accounts( $trId = 0, $No_Bukti = NULL ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'journals/lookup/accounts', array("trId" => $trId, "No_Bukti" => $No_Bukti) );
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
		
		$db_from = "{$this->get_model()->table} a";
		$db_where = array();
		$db_where_group = NULL;
		$db_like = array();
				
		$db_where_group = "Kode_transfer is NULL OR Kode_transfer != 'EXC'";
		
		if ($this->input->post('date_from'))
		{			
			$db_where['a.Transaksi_Date >='] = $this->input->post('date_from');
		}

		if ($this->input->post('date_till'))
		{			
			$db_where['a.Transaksi_Date <='] = $this->input->post('date_till');
		}

		if ($this->input->post('DivisiID'))
		{			
			$db_where['a.DivisiID'] = $this->input->post('DivisiID');
		}
		
		if ($this->input->post('Kode_Proyek'))
		{			
			$db_where['a.Kode_Proyek'] = $this->input->post('Kode_Proyek');
		}
		
		if ($this->input->post('Keterangan'))
		{			
			$db_like['a.Keterangan'] = $this->input->post('Keterangan');
		}		
		
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.Debit") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Kredit") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Keterangan") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "Mst_Currency b", "a.Currency_ID = b.Currency_ID", "LEFT OUTER" )
			->join( "mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join( "mDivisi d", "a.DivisiID = d.Divisi_ID", "LEFT OUTER" )
			->join( "Mst_Relasi e", "a.Relasi_ID = e.Relasi_ID", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_where_group) ){ $this->db->group_start()->where( $db_where_group )->group_end(); }		
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "Mst_Currency b", "a.Currency_ID = b.Currency_ID", "LEFT OUTER" )
			->join( "mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join( "mDivisi d", "a.DivisiID = d.Divisi_ID", "LEFT OUTER" )
			->join( "Mst_Relasi e", "a.Relasi_ID = e.Relasi_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_where_group) ){ $this->db->group_start()->where( $db_where_group )->group_end(); }		
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();


/*TBJ_Transaksi.Transaksi_Date Transaksi_Date , TBJ_Transaksi.No_Bukti No_Bukti , TBJ_Transaksi.Debit Debit , 
	TBJ_Transaksi.Kredit Kredit , TBJ_Transaksi.Keterangan Keterangan,tbj_transaksi.POsted , Currency.Currency_Code Currency_Currency_Code , 
	Relasi.Relasi_Name Relasi_Relasi_Name ,TBJ_Transaksi.Kode_transfer,mProyek.Nama_Proyek,mDivisi.Nama_Divisi */
		
		// get result filtered
		$db_select = <<<EOSQL
			a.Transaksi_Date,
			a.No_Bukti,
			a.Debit,
			a.Kredit,
			a.Keterangan,
			a.Posted,
			b.Currency_Code,
			c.Nama_Proyek,
			d.Nama_Divisi,
			e.Relasi_Name
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "Mst_Currency b", "a.Currency_ID = b.Currency_ID", "LEFT OUTER" )
			->join( "mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join( "mDivisi d", "a.DivisiID = d.Divisi_ID", "LEFT OUTER" )
			->join( "Mst_Relasi e", "a.Relasi_ID = e.Relasi_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_where_group) ){ $this->db->group_start()->where( $db_where_group )->group_end(); }		
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
            $output['data'][] = $row;
        }
		$this->template
			->build_json( $output );
    }
		
}



