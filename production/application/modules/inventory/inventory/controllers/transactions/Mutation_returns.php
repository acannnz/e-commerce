<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Mutation_returns extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/transactions/mutation_returns'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('mutation_return_model');
		$this->load->model('mutation_return_detail_model');
		
		$this->load->model('supplier_model');
		$this->load->model('section_model');
		
		$this->load->model('item_model');
		$this->load->model('item_category_model');
		$this->load->model('item_unit_model');
		$this->load->model('item_location_model');
	}
	
	//load note list view
	public function index()
	{
		$this->data['dropdown_section_to'] = $this->section_model->for_dropdown();
		
		$this->template
			->title(lang('heading:mutation_return_list'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:mutation_return_list'), site_url($this->nameroutes))
			->build("transactions/mutation_returns/index", $this->data);
	}
	
	public function create()
	{		
	
		$this->data['item'] = $item = (object) [
			'No_Bukti' => inventory_helper::gen_mutation_return_evidence_number( date('Y-m-d') ),
			'Tgl_Mutasi' => date("Y-m-d"),
			'Tgl_Update' => date('Y-m-d H:i:s'), 
			'Lokasi_Tujuan' => 0,
			'Lokasi_Asal' => 0,
			'Keterangan' => '',
			'User_ID' => $this->user_auth->User_ID,
		];
		
		if( $this->input->post() ) 
		{
			$data = $this->input->post();
					
			$post_header = array_merge( (array) $item, $this->input->post("header"));
			$post_header['No_Bukti'] = inventory_helper::gen_mutation_return_evidence_number( date('Y-m-d') );
			$post_details = $this->input->post("details");
			$additional = $this->input->post("additional");
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
				
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->mutation_return_model->rules['insert'] );
			$this->form_validation->set_data( $post_header );
			if( $this->form_validation->run() )
			{
				
				if( empty($post_details) )
				{
					response_json( ['status' => 'error', 'message' => lang('message:empty_detail_item')] );	
				}
				
				$this->db->trans_begin();
										
					$this->mutation_return_model->create( $post_header );		
					
					$activities_description = sprintf( "Input Retur Mutasi. # %s # Asal: %s # Tujuan: %s", $post_header['No_Bukti'], $additional['section_from_name'], $additional['section_to_name'] );
					insert_user_activity( $activities_description, $post_header['No_Bukti'], $this->mutation_return_model->table);
					
					$_prepare_detail = [
						'No_Bukti' => $post_header['No_Bukti'],
						'Qty_Stok' => 0, 
						'QtyAmprah' => 0
					];
					
					foreach( $post_details as $row ):				
						
						$this->form_validation->set_rules( $this->mutation_return_detail_model->rules['insert'] );
						$this->form_validation->set_data( $row );
						if( $this->form_validation->run() === FALSE )
						{
							$this->db->trans_rollback();
							response_json( ["status" => 'error', "message" => $this->form_validation->get_all_error_string()] );
						}
							
						$_prepare_detail = array_merge( $_prepare_detail, $row );
						$this->mutation_return_detail_model->create( $_prepare_detail );
						
						$_insert_fifo_out = [
							'location_id' => $post_header['Lokasi_Asal'], 
							'item_id' => $row['Barang_ID'],  
							'item_unit_code' => $row['Kode_Satuan'],  
							'qty' => $row['Qty'], 
							'price' => $row['Harga'],  
							'conversion' => 1,  
							'evidence_number' => $post_header['No_Bukti'],  
							'trans_type_id' => 566,
							'in_out_state' => 0,
							'trans_date' => date('Y-m-d'),  
							'exp_date' => 'Null',  
							'item_type_id' => 0, 
						];
						inventory_helper::insert_warehouse_fifo( $_insert_fifo_out );
						
						$_insert_fifo_in = [
							'location_id' => $post_header['Lokasi_Tujuan'], 
							'item_id' => $row['Barang_ID'],  
							'item_unit_code' => $row['Kode_Satuan'],  
							'qty' => $row['Qty'], 
							'price' => $row['Harga'],  
							'conversion' => 1,  
							'evidence_number' => $post_header['No_Bukti'],  
							'trans_type_id' => 566,
							'in_out_state' => 1,
							'trans_date' => date('Y-m-d'),  
							'exp_date' => 'Null',  
							'item_type_id' => 0, 
						];
						inventory_helper::insert_warehouse_fifo( $_insert_fifo_in );
						
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
		
		$this->data['dropdown_section_from'] = $this->section_model->for_dropdown( TRUE );
		$this->data['dropdown_section_to'] = $this->section_model->for_dropdown();
		$this->data['gen_evidence_number_url'] = base_url("{$this->nameroutes}/gen_evidence_number");
		$this->data['item_lookup'] 		= base_url("{$this->nameroutes}/lookup_item");


		$this->template
			->title(lang('heading:mutation_returns'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:mutation_return_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:mutation_returns'))
			->build("transactions/mutation_returns/form", $this->data);
	}
	
	public function view( $id = 0 )
	{
		$this->data['item'] = $item = $this->mutation_return_model->get_one( $id );
		
		$this->data['form_action'] = current_url();
		$this->data['collection'] = $this->mutation_return_detail_model->get_mutation_return_detail( ['No_Bukti' => $id ] );
		$this->data['is_edit'] = TRUE;
		$this->data['dropdown_section_from'] = $this->section_model->for_dropdown( TRUE );
		$this->data['dropdown_section_to'] = $this->section_model->for_dropdown();
		
		$this->template
			->title(lang('heading:mutation_return_view'), lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:mutation_return_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:mutation_return_view'))
			->build("transactions/mutation_returns/form", $this->data);
	}
	
	public function cancel($id = 0)
	{
		$this->data['item'] = $item = $this->mutation_return_model->get_one($id);
		
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
				
				$this->mutation_return_model->update( ['Batal' => 1 ], $id );

				$activities_description = sprintf( "Delete Mutation_returns di Gudang. # %s # %s # %s ", $item->No_Bukti, $item->SectionAsal, $item->SectionTujuan );
				insert_user_activity( $activities_description, $item->No_Bukti, $this->mutation_return_model->table);
	
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
		$this->load->view('transactions/mutation_returns/modal/cancel', $this->data);
	}
	
	public function gen_evidence_number()
	{
		$date = $this->input->get('date');
		$section_id = $this->input->get('section_id');
		
		$output = [];
		if ( $output['evidence_number'] = inventory_helper::gen_inquiry_number( $date, $section_id ) )
		{
			$output['status'] = 'success';
		} else {
			$output['status'] = 'error';		
		}
		
		response_json( $output );
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
		
		$db_from = "{$this->mutation_return_model->table} a";
		$db_where = array();
		$db_like = array();
				
		if ( $this->input->post("location_to") )
		{
			$db_where['a.Lokasi_Tujuan'] = $this->input->post("location_to");
		} else {
			$warehouse = [];
			foreach ( $this->section_model->for_dropdown() as $k => $v )
			{
				$warehouse[] = $k; 
			}
			$db_where["a.Lokasi_Tujuan IN ('". implode( "','", $warehouse ) ."')"] = NULL;
		}
		
		if ($this->input->post("date_from"))
		{
			$db_where['a.Tgl_Mutasi >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till"))
		{
			$db_where['a.Tgl_Mutasi <='] = $this->input->post("date_till");
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.No_Bukti") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Tgl_Mutasi") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Keterangan") ] = $keywords;
			$db_like[ $this->db->escape_str("b.SectionName") ] = $keywords;
			$db_like[ $this->db->escape_str("c.SectionName") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->section_model->table} b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "{$this->section_model->table} c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.SectionName AS SectionAsalName,
			c.SectionName AS SectionTujuanName
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->section_model->table} b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "{$this->section_model->table} c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
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
			$row->Tgl_Mutasi = substr($row->Tgl_Mutasi, 0, 10);
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );		
    }	
	
	public function lookup_item( $is_ajax_request=false )
	{
		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/mutation_returns/lookup/lookup_item' );
		}else{
			$this->load->view( 'transactions/mutation_returns/lookup/lookup_item' );
		}
	}
	
	public function lookup_section( $is_ajax_request=false )
	{
		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/mutation_returns/lookup/lookup_section' );
		}else{
			$this->load->view( 'transactions/mutation_returns/lookup/lookup_section' );
		}
	}


}

