<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Stock_opname extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/transactions/stock_opname'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('stock_opname_model');
		$this->load->model('stock_opname_detail_model');
		
		$this->load->model('section_model');
		$this->load->model('user_model');
		
		$this->load->model('item_model');
		$this->load->model('item_category_model');
		$this->load->model('item_unit_model');
		$this->load->model('item_location_model');
		$this->load->model('item_typegroup_model');
	}
	
	//load note list view
	public function index()
	{
		$this->data['dropdown_section_to'] = $this->section_model->for_dropdown();
		$this->data['dropdown_type_group'] = $this->item_typegroup_model->to_list_data();
		
		$this->template
			->title(lang('heading:stock_opname_list'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:stock_opname_list'), site_url($this->nameroutes))
			->build("transactions/stock_opname/index", $this->data);
	}
	
	public function create()
	{		
		$this->data['item'] = $item = (object) [
			'No_Bukti' => inventory_helper::gen_opname_evidence_number( date('Y-m-d') ),
			'Tgl_Opname' => date("Y-m-d"),
			'Tgl_Update' => date('Y-m-d H:i:s'), 
			'Lokasi_ID' => 0,
			'Keterangan' => '',
			'User_ID' => $this->user_auth->User_ID,
			'Status_Batal' => 0,
			'Posting_KG' => 0, 
			'Posting_GL' => 0, 
			'Posted' => 0, 
			'Kategori_ID' => NULL,
			'KelompokJenis' => ''
		];
		
		if( $this->input->post() ) 
		{
			$data = $this->input->post();
					
			$post_header = array_merge( (array) $item, $this->input->post("header"));
			$post_details = $this->input->post("details");
			$additional = $this->input->post("additional");
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
				
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->stock_opname_model->rules['insert'] );
			$this->form_validation->set_data( $post_header );
			if( $this->form_validation->run() )
			{
				
				if( empty($post_details) )
				{
					response_json( ['status' => 'error', 'message' => lang('message:empty_detail_item')] );	
				}

				$this->db->trans_begin();
				
					/*
						INSERT INTO GD_trOpname (No_Bukti, Tgl_Opname, Keterangan, Lokasi_ID, User_ID, Tgl_Update, Status_Batal, Posting_KG, Posting_GL, Posted,Kategori_ID,KelompokJenis) VALUES ('1803-OPN-000002' ,'2018-03-08' ,'' ,1366 ,1876 ,'2018-03-08' ,0 ,0 ,0 ,0,NULL,'ORAL')
						EXEC InsertUserActivities '2018-03-08','2018-03-08 14:02:56',1876,'1803-OPN-000002',
						'Input Opname.#1803-OPN-000002#GUDANG FARMASI##GUDANG FARMASI','KOHAKU'
						INSERT INTO GD_trOpnameDetail (Stock_Akhir, Qty_Opname, Harga_Rata, Keterangan, No_Bukti, Barang_ID, Kode_Satuan,JenisBarangID) VALUES (-55 ,0 ,683.17 ,'' ,'1803-OPN-000002' ,5257 ,'BIJI',0)
					*/
										
					$this->stock_opname_model->create( $post_header );		
					
					$activities_description = sprintf( "Input Stock Opname. # %s # %s # %s", $post_header['No_Bukti'], $post_header['Lokasi_ID'], $additional['section_name']);
					insert_user_activity( $activities_description, $post_header['No_Bukti'], $this->stock_opname_model->table);
					
					$_prepare_detail = [
						'No_Bukti' => $post_header['No_Bukti'],
					];
					
					foreach( $post_details as $row ):				
						
						$this->form_validation->set_rules( $this->stock_opname_detail_model->rules['insert'] );
						$this->form_validation->set_data( $row );
						if( $this->form_validation->run() === FALSE )
						{
							$this->db->trans_rollback();
							response_json( ["status" => 'error', "message" => $this->form_validation->get_all_error_string()] );
						}
							
						$_prepare_detail = array_merge( $_prepare_detail, $row );
						$this->stock_opname_detail_model->create( $_prepare_detail );
						
					endforeach;
					
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('global:created_failed'),
							"code" => 500
						);
				}
				else
				{
					$this->db->trans_commit();
					$response = array(
							"id" => $post_header['No_Bukti'],
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}				

			} else
			{
				$response = array(
						"status" => 'error',
						"message" => $this->form_validation->get_all_error_string(),
						"code" => 500
					);
			}
			
			response_json( $response );			
		}
		
		$this->data['form_action'] = $form_action = current_url();
		
		$this->data['dropdown_section'] = $this->section_model->for_dropdown();
		$this->data['dropdown_type_group'] = $this->item_typegroup_model->to_list_data();
		$this->data['view_detail_opname'] = $this->view_detail_opname();

		$this->template
			->title(lang('heading:stock_opname'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:stock_opname_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:stock_opname'))
			->build("transactions/stock_opname/form", $this->data);
	}
	
	/*
		@params
		(String) id -> No_Bukti Stock Opname
	*/
	public function update( $id )
	{		
		$this->data['item'] = $item = $this->stock_opname_model->get_one( $id );
		
		if ( $item->Posted == 1)
		{
			redirect("{$this->nameroutes}/view/{$id}", "location");
		}
		
		if( $this->input->post() ) 
		{
			$data = $this->input->post();
					
			$post_header = array_merge( (array) $item, $this->input->post("header"));
			$post_details = $this->input->post("details");
			$additional = $this->input->post("additional");
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
				
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->stock_opname_model->rules['insert'] );
			$this->form_validation->set_data( $post_header );
			if( $this->form_validation->run() )
			{
				
				if( empty($post_details) )
				{
					response_json( ['status' => 'error', 'message' => lang('message:empty_detail_item')] );	
				}

				$this->db->trans_begin();								
					if ( $additional['process_stock_opname'] == 1 )
					{
						$this->stock_opname_model->update( ['Posted' => 1], $id );		
						$activities_description = sprintf( "Proses Stock Opname. # %s # %s # %s", $item->No_Bukti, $item->Lokasi_ID, $additional['section_name']);
						insert_user_activity( $activities_description, $item->No_Bukti, $this->stock_opname_model->table);
					}
					
					$_prepare_detail = [
						'No_Bukti' => $item->No_Bukti,
					];
					$_delete_where_not = [];
					foreach( $post_details as $row ):				
						
						$this->form_validation->set_rules( $this->stock_opname_detail_model->rules['insert'] );
						$this->form_validation->set_data( $row );
						if( $this->form_validation->run() === FALSE )
						{
							$this->db->trans_rollback();
							response_json( ["status" => 'error', "message" => $this->form_validation->get_all_error_string()] );
						}
						
						if ( $_get_detail = $this->stock_opname_detail_model->get_by( ['No_Bukti' => $item->No_Bukti, 'Barang_ID' => $row['Barang_ID']] ) )
						{
							$_update_detail = array_merge( (array) $_get_detail, $row);
							$this->stock_opname_detail_model->update_by( $_update_detail, ['No_Bukti' => $item->No_Bukti, 'Barang_ID' => $row['Barang_ID']] );
						} else {
							$_prepare_detail = array_merge( $_prepare_detail, $row );
							$this->stock_opname_detail_model->create( $_prepare_detail );
						}
						
						if ( $additional['process_stock_opname'] == 1 )
						{
							$_insert_warehouse_fifo = [
								'location_id' => $item->Lokasi_ID, 
								'item_id' => $row['Barang_ID'],  
								'item_unit_code' => $row['Kode_Satuan'],  
								'qty' => abs( $row['Stock_Akhir'] - $row['Qty_Opname'] ), 
								'price' => $row['Harga_Rata'],  
								'conversion' => 1,  
								'evidence_number' => $id,  
								'trans_date' => $item->Tgl_Opname,  
								'exp_date' => @$row['Tgl_Expired'] ? $row['Tgl_Expired'] : 'NULL',  
								'item_type_id' => $row['JenisBarangID'], 
							];
							
							if ( $row['Qty_Opname'] > $row['Stock_Akhir'] ) 
							{
								$_insert_warehouse_fifo['trans_type_id'] = 560; // Stock opname bernilai plus
								$_insert_warehouse_fifo['in_out_state'] = 1;
								
							} elseif ( $row['Qty_Opname'] < $row['Stock_Akhir'] ) {
								
								$_insert_warehouse_fifo['trans_type_id'] = 561; // Stock opname bernilai min
								$_insert_warehouse_fifo['in_out_state'] = 0;					
							}
							inventory_helper::insert_warehouse_fifo( $_insert_warehouse_fifo );
						}
						
						$_delete_where_not[] = $row['Barang_ID'];
												
					endforeach;
					
					$this->stock_opname_detail_model->delete_not_in( $id, $_delete_where_not );
					
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => ( $additional['process_stock_opname'] == 1 ) 
											? lang('message:stock_opname_process_failed')
											: lang('global:updated_failed'),
							"code" => 500
						);
				}
				else
				{
					$this->db->trans_commit();
					$response = array(
							"id" => $post_header['No_Bukti'],
							"posted" => 1,
							"status" => 'success',
							"message" => ( $additional['process_stock_opname'] == 1 )  
											? lang('message:stock_opname_process_successfully')
											: lang('global:updated_successfully'),
							"code" => 200
						);
				}				

			} else
			{
				$response = array(
						"status" => 'error',
						"message" => $this->form_validation->get_all_error_string(),
						"code" => 500
					);
			}
			
			response_json( $response );			
		}
		
		$this->data['collection'] = $this->stock_opname_detail_model->get_stock_opname_detail( ['No_Bukti' => $id, 'd.Lokasi_ID' => $item->Lokasi_ID] );
		
		$this->data['form_action'] = $form_action = current_url();	
		$this->data['dropdown_section'] = $this->section_model->for_dropdown();
		$this->data['dropdown_type_group'] = $this->item_typegroup_model->to_list_data();
		$this->data['view_detail_opname'] = $this->view_detail_opname();
		$this->data['is_edit'] = TRUE;

		$this->template
			->title(lang('heading:stock_opname'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:stock_opname_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:stock_opname'))
			->build("transactions/stock_opname/form", $this->data);
	}
	
	public function view( $id = 0 )
	{
		$this->data['item'] = $item = $this->stock_opname_model->get_one( $id );
		
		$this->data['collection'] = $this->stock_opname_detail_model->get_stock_opname_detail( ['No_Bukti' => $id, 'd.Lokasi_ID' => $item->Lokasi_ID] );
		$this->data['form_action'] = $form_action = current_url();	
		$this->data['dropdown_section'] = $this->section_model->for_dropdown();
		$this->data['dropdown_type_group'] = $this->item_typegroup_model->to_list_data();
		$this->data['view_detail_opname'] = $this->view_detail_opname();
		$this->data['is_edit'] = TRUE;
		
		$this->template
			->title(lang('heading:stock_opname_view'), lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:stock_opname_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:stock_opname_view'))
			->build("transactions/stock_opname/form", $this->data);
	}
	
	public function cancel($id = 0)
	{
		$this->data['item'] = $item = $this->stock_opname_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 
			if ( $item->Realisasi == 1 )
			{
				$response = array(
							"status" => 'error',
							"message" => lang('message:already_realization'),
						);
				response_json( $response );
			}
			
			$this->db->trans_begin();
				
				$this->stock_opname_model->update( ['Batal' => 1 ], $id );

				$activities_description = sprintf( "Delete Stock_opname di Gudang. # %s # %s # %s ", $item->No_Bukti, $item->SectionAsal, $item->SectionTujuan );
				insert_user_activity( $activities_description, $item->No_Bukti, $this->stock_opname_model->table);
	
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				response_json(["status" => 'error', 'message' => lang('global:cancel_failed'), 'success' => FALSE]);
			} else
			{
				$this->db->trans_commit();
				response_json(["status" => 'success', 'message' => lang('global:cancel_successfully'), 'success' => TRUE]);
			}
		} 
		
		$this->data['form_action'] = $form_action = current_url();
		$this->load->view('transactions/stock_opname/modal/cancel', $this->data);
	}
	
	private function view_detail_opname()
	{
		$data = [
			'item' => @$this->data['item'],
			'collection' => @$this->data['collection'],
			'item_lookup' => base_url("{$this->nameroutes}/lookup_item"),
	];
		return $this->load->view('transactions/stock_opname/form/detail_opname', $data, TRUE);
	}
	
	public function lookup_collection()
	{
		$this->datatable_collection();
	}
	
	public function datatable_collection( )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->stock_opname_model->table} a";
		$db_where = array();
		$db_like = array();
				
		
		if ( $this->input->post("location_id") )
		{
			$db_where['a.Lokasi_ID'] = $this->input->post("location_id");
		}

		if ( $this->input->post("type_group") != "ALL" )
		{
			$db_where['a.KelompokJenis'] = $this->input->post("type_group");
		}
		
		if ($this->input->post("period"))
		{
			$date = DateTime::createFromFormat("Y-m", $this->input->post("period") ); 
			$db_where['a.Tgl_Opname >= '] = $date->format('Y-m-01');
			$db_where['a.Tgl_Opname <= '] = $date->format('Y-m-t');
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.No_Bukti") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->user_model->table} b", "a.User_ID = b.User_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.No_Bukti,
			a.Tgl_Opname,
			a.KelompokJenis,
			a.Keterangan,
			a.Posted,
			b.Nama_Singkat
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->user_model->table} b", "a.User_ID = b.User_ID", "LEFT OUTER" )
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
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['name'], $this->db->escape_str($sort_dir) );
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
			$row->Tgl_Opname = substr($row->Tgl_Opname, 0, 10);
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );		
    }	
	
	public function lookup_item( $is_ajax_request=false )
	{
		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/stock_opname/lookup/lookup_item_multiple' );
		}
	}
	
	public function lookup_section( $is_ajax_request=false )
	{
		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/stock_opname/lookup/lookup_section' );
		}
	}


}

