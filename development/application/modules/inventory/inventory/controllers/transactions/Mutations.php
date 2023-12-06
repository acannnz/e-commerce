<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Mutations extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/transactions/mutations'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('mutations_model');
		$this->load->model('mutations_detail_model');
		
		$this->load->model('user_model');
		$this->load->model('amprahan_model');
		$this->load->model('amprahan_detail_model');
	
		$this->load->model('location_model');	
		$this->load->model('supplier_model');
		$this->load->model('section_model');
	
		$this->load->model('procurement_model');
		$this->load->model('item_model');
		$this->load->model('item_category_model');
		$this->load->model('item_location_model');
		$this->load->model('item_unit_model');
	}
	
	//load note list view
	public function index()
	{
		
		$this->data['view_datatable_open'] = $this->_view_datatable_open();
		$this->data['view_datatable_realization'] = $this->_view_datatable_realization();
		
		$this->template
			->title(lang('heading:mutation_list'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:mutation_list'), site_url($this->nameroutes))
			->build("transactions/mutations/index", $this->data);
	}
	
	/*
		@params
		(String) $id -> NoBukti Amprahan
	*/
	public function create( $id = NULL)
	{		
	
		if ( !empty($id) && !$this->input->post() )
		{
			$this->data['amprahan'] = $amprahan = $this->amprahan_model->get_one( $id );
			$this->data['collection'] = $this->get_amprahan_detail( $id );
		}
	
		$this->data['item'] = $item = (object) array(
			'No_Bukti' => inventory_helper::gen_mutation_evidence_number( date('Y-m-d') ),
			'Tgl_Mutasi' => date("Y-m-d"),
			'Keterangan' => NULL,
			'NoAmprahan' => @$amprahan->NoBukti,
			'Lokasi_Asal' => @$amprahan->Lokasi_Tujuan,
			'Lokasi_Tujuan' => @$amprahan->Lokasi_Asal,
			'JamMutasi' => date('Y-m-d H:i:s'),
			'Tgl_Update' => date('Y-m-d'),
			'Keterangan' => '',
			'Status_Batal' => 0,
			'Posting_KG' => 0,
			'Posting_GL' => 0,
			'Posting_Unit' => 0,
			'Approve' => 1,
			'User_ID' => $this->user_auth->User_ID,
			'UserGudangPusat' => $this->user_auth->User_ID,
			'TglInputGudangPusat' => date('Y-m-d'),
		);
			
		if( $this->input->post() ) 
		{
			$post_header = array_merge( (array) $item, $this->input->post("header") );
			$post_header['No_Bukti'] = inventory_helper::gen_mutation_evidence_number( $post_header['Tgl_Mutasi'] );
			$post_detail = $this->input->post("details");
			$additional = $this->input->post('additional');

			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->mutations_model->rules['insert'] );
			$this->form_validation->set_data( $post_header );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
			
			if( $this->form_validation->run() )
			{
				$this->db->trans_begin();
										
					$this->mutations_model->create( $post_header );
					$activities_description = sprintf("Input Mutasi.# %s # %s # Asal : %s # Tujuan: %s", $post_header['No_Bukti'], $post_header['NoAmprahan'], $additional['section_from_name'], $additional['section_to_name']); 
					insert_user_activity($activities_description, $post_header['No_Bukti'], $this->mutations_model->table);
									
					$this->amprahan_model->update([
							'Realisasi' => 1,
							'Disetujui' => 1, 
							'DisetujuiTgl' => date('Y-m-d'), 
							'DisetujuiUserID' => $this->user_auth->User_ID
						], $post_header['NoAmprahan'] );					

					foreach( $post_detail as $row )
					{			
						$this->form_validation->set_rules( $this->mutations_detail_model->rules['insert'] );
						$this->form_validation->set_data( $row );
						if( $this->form_validation->run() === FALSE )
						{
							$this->db->trans_rollback();
							response_json(['status' => 'error', 'message' => $this->form_validation->get_all_error_string()]);
						}
						
						$row['No_Bukti'] = $post_header['No_Bukti'];
						$this->mutations_detail_model->create( $row );
								
						$_where_amprahan_detail = [
							'NoBukti' => $post_header['NoAmprahan'],
							'Barang_ID' => $row['Barang_ID']
						];
						
						if ( !empty( $post_header['NoAmprahan'] ) )
						{							
							$this->amprahan_detail_model->update_by( ['QtyRealisasiPertama' => $row['Qty']], $_where_amprahan_detail);
						}
						
						if ( $row['Qty'] >= $row['QtyAmprah'])
						{
							$this->amprahan_detail_model->update_by( ['Realisasi' => 1], $_where_amprahan_detail);		
						}
						
						// $item = $this->item_model->get_one( $row['Barang_ID'] );
						
						$_insert_warehouse_fifo_mutation = [
							'location_id' => $post_header['Lokasi_Asal'], 
							'item_id' => $row['Barang_ID'],  
							'item_unit_code' => $row['Kode_Satuan'],  
							'qty' => $row['Qty'], 
							'price' => $row['Harga'],  
							'evidence_number' => $row['No_Bukti'],  
							'trans_type_id' => 520,
							'trans_date' => $post_header['Tgl_Mutasi'],  
							'item_type_id' => $row['JenisBarangID'], 
							'to_location_id' => $post_header['Lokasi_Tujuan'],
							'exp_date' => $post_header['Tgl_Mutasi'],  
						];
						inventory_helper::insert_warehouse_fifo_mutation( $_insert_warehouse_fifo_mutation );
					}
															
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
			
			response_json($response);			
		}
		
		$this->data["view_detail_mutation"] = $this->_view_detail_mutation( $item );
		$this->data['dropdown_section_from'] = $this->section_model->for_dropdown();
		$this->data["form_actions"] = current_url();
		
		if( $this->input->is_ajax_request() )
		{
			$this->data["is_ajax_request"] = TRUE;
			$this->data["is_modal"] = TRUE;
			
			$this->load->view( 
					'transactions/mutations/modal/create_edit', 
					array('form_child' => $this->load->view('transactions/mutations/form', $this->data, true))
				);
		} else
		{
			$this->data["form"] = TRUE;
			$this->data["datatables"] = TRUE;
			$this->data["lookup_amprahan"] = base_url("inventory/transactions/mutations/lookup_amprahan");
			$this->data["lookup_products"] = base_url("inventory/transactions/mutations/lookup_products");
			
			
			$this->template
				->title(lang('heading:mutations'),lang('heading:transactions'))
				->set_breadcrumb(lang('heading:transactions'))
				->set_breadcrumb(lang('heading:mutation_list'), site_url($this->nameroutes))
				->set_breadcrumb(lang('heading:mutations'))
				->build("transactions/mutations/form", $this->data);
		}
		
	}
	
	/*
		@params
		(String) id -> No_Bukti Mutasi
	*/
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->mutations_model->get_one( $id );
		
		$this->data["form"] = TRUE;
		$this->data["datatables"] = TRUE;
		$this->data["is_edit"] = TRUE;
		$this->data['amprahan'] = $amprahan = $this->amprahan_model->get_one( $item->NoAmprahan );
		$this->data['collection'] = $this->mutations_detail_model->get_mutation_detail(['No_Bukti' => $id]);
		$this->data["view_detail_mutation"] = $this->_view_detail_mutation( $item );
		$this->data['dropdown_section_from'] = $this->section_model->for_dropdown();
		$this->data["form_actions"] = current_url();
		
		
		$this->template
			->title(lang('heading:mutation_view'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:mutation_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:mutation_view'))
			->build("transactions/mutations/form", $this->data);
	}
		
	public function _view_detail_mutation( $item = NULL )
	{
		$data = array(
				"collection" => @$this->data['collection'],
				"is_edit" => @$this->data['is_edit']
			);
			
		return $this->load->view( 'transactions/mutations/form/detail_mutations', $data, TRUE );		
	}
	
	private function _view_datatable_open()
	{
		$data = [
			'nameroutes' => $this->nameroutes,
			'dropdown_section_from' => $this->section_model->for_dropdown_section( TRUE ),
			'dropdown_section_to' => $this->section_model->for_dropdown_section(),	
		];	

		return $this->load->view('transactions/mutations/datatable/open', $data, TRUE );
	}

	private function _view_datatable_realization()
	{
		$data = [
			'nameroutes' => $this->nameroutes,
			'dropdown_section_from' => $this->section_model->for_dropdown(),
			'dropdown_section_to' => $this->section_model->for_dropdown( TRUE ),	
		];	

		return $this->load->view('transactions/mutations/datatable/realization', $data, TRUE );
	}
	
	/*
		@params
		(String) $id -> NoAmprahan
	*/
	public function get_amprahan_detail( $id = NULL, $is_ajax = FALSE )
	{
		$id = $id ? $id : $this->input->get_post('id');
		$amprahan = $this->amprahan_model->get_one( $id );
		
		$collection = [];
		$retrieve_collection = $this->amprahan_detail_model->get_amprahan_detail( ['NoBukti' => $id] );
		foreach( $retrieve_collection as $amprah )
		{
			$item_location = $this->item_location_model->get_by( ['Barang_ID' => $amprah->Barang_ID, 'Lokasi_ID' => $amprahan->Lokasi_Tujuan] );
			$row = [
				'Barang_ID' => $amprah->Barang_ID,
				'Kode_Barang' => $amprah->Kode_Barang,
				'Nama_Barang' => $amprah->Nama_Barang,
				'Kode_Satuan' => $amprah->Satuan,
				'Konversi' => $amprah->Konversi,
				'Qty_Stok' => @$item_location->Qty_Stok,
				'QtyAmprah' => $amprah->Qty,
				'Qty' => $amprah->Qty,
				'Harga' => $amprah->Harga_jual,
				'HRataRata' => $amprah->HRataRata,
				'MutasiAkun_ID' => @$amprah->Akun_ID_Mutasi,
				'JenisBarangID' => @$item_location->JenisBarangID
			];
			
			$collection[] = $row;
		}
		
		return ( $is_ajax || $this->input->is_ajax_request() ) ? response_json($collection) : $collection;
	}
	
	public function lookup_collection ( $open = 1 )
	{
		$this->datatable_collection( $open );
	}
	
	public function datatable_collection( $open = 1 )
	{
		switch ( $open )
		{	
			case 1: $this->datatable_open(); break;
			case 0: $this->datatable_realization(); break;
		}
	}
		
	public function datatable_open()
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
		
		$db_where['a.Batal'] = 0;
		$db_where['a.Realisasi'] = 0;

		if ( $this->input->post("section_from") )
		{
			$db_where['a.SectionAsal'] = $this->input->post("section_from");
		}
				
		if ( $this->input->post("section_to") )
		{
			$db_where['a.SectionTujuan'] = $this->input->post("section_to");
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
			->join( "{$this->user_model->table} d", "a.UserID = d.User_ID", "LEFT OUTER" )
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
			$row->Tanggal = substr($row->Tanggal, 0, 10);
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );		
    }
	
	public function datatable_realization()
	{		
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->mutations_model->table} a";
		$db_where = array();
		$db_like = array();
		
		if ( $this->input->post("location_from") )
		{
			$db_where['a.Lokasi_Asal'] = @$this->input->post("location_from");
		}

		if ( $this->input->post("location_to") )
		{
			$db_where['a.Lokasi_Tujuan'] = @$this->input->post("location_to");
		}
		
		if ( $this->input->post("date_from") )
		{
			$db_where['a.Tgl_Mutasi >='] = $this->input->post("date_from");
		}

		if ( $this->input->post("date_till") )
		{
			$db_where['a.Tgl_Mutasi <='] = $this->input->post("date_till");
		}
										
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.No_Bukti") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Keterangan") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->location_model->table} b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "{$this->location_model->table} c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
				
		// get result filtered
		$db_select = <<<EOSQL
			a.No_Bukti, 
			a.Tgl_Mutasi, 
			b.Nama_Lokasi NamaLokasiAsal, 
			c.Nama_Lokasi NamaLokasiTujuan, 
			a.Keterangan
						
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->location_model->table} b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "{$this->location_model->table} c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
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
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );	
	}
	
	public function lookup_item( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/mutations/lookup/lookup_item' );
		}
	}
	
	public function lookup_section( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/mutations/lookup/lookup_section' );
		}
	}

	public function lookup_amprahan( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/mutations/lookup/lookup_amprahan' );
		}
	}


}

