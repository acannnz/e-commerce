<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Amprahan extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/transactions/amprahan'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('section_model');
		$this->load->model('user_model');
		$this->load->model('amprahan_model');
		$this->load->model('amprahan_detail_model');
		
		$this->load->model('supplier_model');
		$this->load->model('section_model');
		$this->load->model('procurement_model');
		$this->load->model('item_model');
		$this->load->model('item_category_model');
		$this->load->model('item_unit_model');
		$this->load->model('item_location_model');
	}
	
	//load note list view
	public function index()
	{
		$this->template
			->title(lang('heading:amprahan_list'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:amprahan_list'), site_url($this->nameroutes))
			->build("transactions/amprahan/index", $this->data);
	}
	
	public function create()
	{		
		$this->data['item'] = $item = (object) [
			'NoBukti' => inventory_helper::gen_inquiry_number( date('Y-m-d') ),
			'Tanggal' => date("Y-m-d"),
			'UserID' => $this->user_auth->User_ID,
			'JamUpdate' => date('Y-m-d H:i:s'), 
			'Batal' => 0,
			'Realisasi' => 0,
			'Disetujui' => 0,
			'DisetujuiTgl' => NULL,
			'DisetujuiUserID' => NULL,
		];
		
		if( $this->input->post() ) 
		{
			$data = $this->input->post();
					
			$post_header = array_merge( (array) $item, $this->input->post("header"));
			$post_header['NoBukti'] = inventory_helper::gen_inquiry_number( date('Y-m-d'), $post_header['SectionAsal'] );
			$post_details = $this->input->post("details");
			$additional = $this->input->post("additional");
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
				
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->amprahan_model->rules['insert'] );
			$this->form_validation->set_data( $post_header );
			if( $this->form_validation->run() )
			{
				
				if( empty($post_details) )
				{
					response_json( ['status' => 'error', 'message' => lang('message:empty_detail_item')] );	
				}
				
				$this->db->trans_begin();
										
					$this->amprahan_model->create( $post_header );		
					
					$activities_description = sprintf( "Input Amprahan di Gudang. # %s # %s # %s", $post_header['NoBukti'], $additional['section_from_name'], $additional['section_to_name'] );
					insert_user_activity( $activities_description, $post_header['NoBukti'], $this->amprahan_model->table);
					
					$section = $this->section_model->get_one( $post_header['SectionAsal'] );
					
					$_prepare_detail = [
						'NoBukti' => $post_header['NoBukti'],
						'Realisasi' => 0,
						'QtyRealisasiPertama' => 0,
					];
					
					foreach( $post_details as $row ):					
						$item_location = $this->item_location_model->get_by( ['Barang_ID' => $row['Barang_ID'], 'Lokasi_ID' => $section->Lokasi_ID ] );
						$row['QtyStok'] = (float) @$item_location->Qty_Stok;
						
						$_prepare_detail = array_merge( $_prepare_detail, $row );
						
						$this->amprahan_detail_model->create( $_prepare_detail );
						
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
							"id" => $post_header['NoBukti'],
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
		
		$this->data['dropdown_section_from'] = $this->section_model->for_dropdown_section( TRUE );
		$this->data['dropdown_section_to'] = $this->section_model->for_dropdown_section();
		$this->data['gen_evidence_number_url'] = base_url("{$this->nameroutes}/gen_evidence_number");
		$this->data['item_lookup'] 		= base_url("{$this->nameroutes}/lookup_item");


		$this->template
			->title(lang('heading:amprahan'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:amprahan_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:amprahan'))
			->build("transactions/amprahan/form", $this->data);
	}
	
	public function update( $id = 0 )
	{
		$this->data['item'] = $item = $this->amprahan_model->get_one( $id );
		
		if( $this->input->post() ) 
		{
			$data = $this->input->post();
					
			$post_header = array_merge( (array) $item, $this->input->post("header"));
			$post_header['NoBukti'] = inventory_helper::gen_inquiry_number( date('Y-m-d'), $post_header['SectionAsal'] );
			$post_details = $this->input->post("details");
			$additional = $this->input->post("additional");
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
				
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->amprahan_model->rules['insert'] );
			$this->form_validation->set_data( $post_header );
			if( $this->form_validation->run() )
			{
							
				if( empty($post_details) )
				{
					response_json( ['status' => 'error', 'message' => lang('message:empty_detail_item')] );	
				}

				$this->db->trans_begin();
										
					$this->amprahan_model->update( $post_header, $id );		
					
					$activities_description = sprintf( "Update Amprahan di Gudang. # %s # %s # %s", $post_header['NoBukti'], $additional['section_from_name'], $additional['section_to_name'] );
					insert_user_activity( $activities_description, $post_header['NoBukti'], $this->amprahan_model->table);
					
					$section = $this->section_model->get_one( $post_header['SectionAsal'] );
					$_prepare_detail = [
						'NoBukti' => $post_header['NoBukti'],
						'Realisasi' => 0,
						'QtyRealisasiPertama' => 0,
					];
					
					foreach( $post_details as $row ):		
						
						$item_location = $this->item_location_model->get_by( ['Barang_ID' => $row['Barang_ID'], 'Lokasi_ID' => $section->Lokasi_ID ] );
						$row['QtyStok'] = $item_location->Qty_Stok;
						$row['NoBukti'] = $post_header['NoBukti'];

						if ( $amprahan_detail = $this->amprahan_detail_model->get_by( ['NoBukti' => $id, 'Barang_ID' => $row['Barang_ID']], TRUE ))			
						{							
							$_update_detail = array_merge( $amprahan_detail, $row );
							$this->amprahan_detail_model->update_by( $_update_detail, ['NoBukti' => $id, 'Barang_ID' => $row['Barang_ID']] );
						} else {							
							$_prepare_detail = array_merge( $_prepare_detail, $row );
							$this->amprahan_detail_model->create( $_prepare_detail );
						}
						
						$where_not_in[] = $row['Barang_ID'];
					endforeach;
					
					$this->amprahan_detail_model->delete_not_in( $id, $where_not_in );
					
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('global:updated_failed'),
							"code" => 500
						);
				}
				else
				{
					$this->db->trans_commit();
					$response = array(
							"id" => $id,
							"status" => 'success',
							"message" => lang('global:updated_successfully'),
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
		
		$this->data['form_action'] = current_url();
		$this->data['collection'] = $this->amprahan_detail_model->get_all( NULL, 0, ['NoBukti' => $id] );
		$this->data['is_edit'] = TRUE;
		$this->data['dropdown_section_from'] = $this->section_model->for_dropdown_section( TRUE );
		$this->data['dropdown_section_to'] = $this->section_model->for_dropdown_section();
		$this->data['gen_evidence_number_url'] = base_url("{$this->nameroutes}/gen_evidence_number");
		$this->data['cancel_url'] = base_url("{$this->nameroutes}/cancel/{$id}");
		$this->data['item_lookup'] 		= base_url("{$this->nameroutes}/lookup_item");
		
		$this->template
			->title(lang('heading:amprahan'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:amprahan_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:amprahan'))
			->build("transactions/amprahan/form", $this->data);
	}
	
	public function cancel($id = 0)
	{
		$this->data['item'] = $item = $this->amprahan_model->get_one($id);
		
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
				
				$this->amprahan_model->update( ['Batal' => 1 ], $id );

				$activities_description = sprintf( "Delete Amprahan di Gudang. # %s # %s # %s ", $item->NoBukti, $item->SectionAsal, $item->SectionTujuan );
				insert_user_activity( $activities_description, $item->NoBukti, $this->amprahan_model->table);
	
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
		$this->load->view('transactions/amprahan/modal/cancel', $this->data);
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
		
		$db_from = "{$this->amprahan_model->table} a";
		$db_where = array();
		$db_like = array();
		
		if ( $this->input->post("SectionID") )
		{
			$db_where['a.SectionAsal'] = $this->input->post("SectionID");
		}
				
		if ( $this->input->post("SectionTujuanID") )
		{
			$db_where['a.SectionTujuan'] = $this->input->post("SectionTujuanID");
			$db_where['a.Batal'] = 0;
			$db_where['a.Realisasi'] = 0;
		}
		
		if ( !empty($this->input->post("is_from_mutation")) )
		{
			$db_where['a.Realisasi'] = 0;
			$db_where['a.Batal'] = 0;
			$warehouse = [];
			foreach ( $this->section_model->for_dropdown_section() as $k => $v )
			{
				$warehouse[] = $k; 
			}
			$db_where["a.SectionTujuan IN ('". implode( "','", $warehouse ) ."')"] = NULL;
		}
		
		if ($this->input->post("date_from"))
		{
			$db_where['a.Tanggal >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till"))
		{
			$db_where['a.Tanggal <='] = $this->input->post("date_till");
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Tanggal") ] = $keywords;
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
			->join( "{$this->section_model->table} b", "a.SectionAsal = b.SectionID", "LEFT OUTER" )
			->join( "{$this->section_model->table} c", "a.SectionTujuan = c.SectionID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti,
			a.Tanggal,
			a.SectionAsal AS SectionAsalID,
			a.SectionTujuan AS SectionTujuanID,
			b.SectionName AS SectionAsalName,
			b.Lokasi_ID AS Lokasi_Tujuan,
			c.SectionName AS SectionTujuanName,
			c.Lokasi_ID AS Lokasi_Asal,
			a.Disetujui,
			a.Realisasi,
			a.Batal,
			a.Keterangan,
			d.Nama_Singkat
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->section_model->table} b", "a.SectionAsal = b.SectionID", "LEFT OUTER" )
			->join( "{$this->section_model->table} c", "a.SectionTujuan = c.SectionID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
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
			$row->Tanggal = substr($row->Tanggal, 0, 10);
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );		
    }	
	
	public function lookup_item( $is_ajax_request=false )
	{
		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/amprahan/lookup/lookup_item' );
		}else{
			$this->load->view( 'transactions/amprahan/lookup/lookup_item' );
		}
	}
	
	public function lookup_section( $is_ajax_request=false )
	{
		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/amprahan/lookup/lookup_section' );
		}else{
			$this->load->view( 'transactions/amprahan/lookup/lookup_section' );
		}
	}


}

