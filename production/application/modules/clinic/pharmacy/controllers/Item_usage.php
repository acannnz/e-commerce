<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_usage extends Admin_Controller
{ 
	protected $_translation = 'item_usage';	
	protected $_model = 'item_usage_m'; 
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('pharmacy');
		
		$this->page = "common_item_usage";
		$this->template->title( lang("item_usage:page") . ' - ' . $this->config->item('company_name') );
		
		$this->load->model("item_usage_m");
		$this->load->model( "common/section_m" );

		$this->load->helper("item_usage");
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("item_usage:page") )
			->set_breadcrumb( lang("item_usage:breadcrumb") )
			->build('item_usage/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
	  
		$item = (object) array(
				"NoBukti" => item_usage_helper::gen_evidence_number(),
				"Tanggal" => date("Y-m-d"),
				"Jam" => date("Y-m-d H:i:s"),
				"SectionID" => $this->section->SectionID,
				"Keterangan" => NULL,
				"StatusBatal" => 0, 
				"UserIDBatal" => NULL,
				"TanggalBatal" => NULL, 
				"JamBatal" => NULL, 
				"AlasanBatal" => '', 
				"UserID" => $this->user_auth->User_ID
			);

			
		if( $this->input->post() ) 
		{
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
				
			$data = $this->input->post();
			$header = $this->input->post('f');			
			$header = (object) array_merge( (array) $item, $header );
			$detail = $this->input->post('d');
			
			$validation = TRUE;
			
			if ( empty($header->NoBukti))
			{
				$validation = FALSE;
				$message = "No Bukti Tidak ditemukan! Silahkan hubungi IT Support.";
			}
			
			if ( empty($detail))
			{
				$validation = FALSE;
				$message = "Anda Belum Memilih Barang! Silahkan Pilih Terlebih Dahulu.";
			}
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( (array) $header );
			
			if( !$this->form_validation->run() && $validation )
			{
				/*print_r($header);
				print_r($detail);
				exit;*/
				$this->db->trans_begin();
							
					$this->db->insert( "SIMtrPemakaian", $header );
					
					// Insert User Aktivities
					$activities_description = sprintf( "%s # %s # %s # %s", "Input Pemakaian Barang.", $item->NoBukti, $this->section->SectionName, $this->user_auth->Nama_Asli );			
					$this->db->query("EXEC InsertUserActivities '$item->Tanggal','$header->Jam', {$this->user_auth->User_ID} ,'$header->NoBukti','$activities_description','SIMtrPemakaian'");

					foreach( $detail as $val ):
					
						$val['NoBukti'] = $header->NoBukti;

						// Menyiapkan data kartu gudang unutk bhp yg digunakan
						$qty_last_stock = $this->item_usage_m->get_last_stock_warehouse_card( array("Lokasi_ID" => $this->section->Lokasi_ID, "Barang_ID" => $val["BarangID"]) );
						$qty_saldo = $qty_last_stock - $val["QtyPemakaian"];
						$kartu_gudang = array(
								"Lokasi_ID" => $this->section->Lokasi_ID,
								"Barang_ID" => $val["BarangID"],
								"No_Bukti" => $val["NoBukti"],
								"JTransaksi_ID" => 564,
								"Tgl_Transaksi" => $header->Jam,
								"Kode_Satuan" => $val["Satuan"],
								"Qty_Masuk" => 0,
								"Harga_Masuk" =>  0,
								"Qty_Keluar" => $val["QtyPemakaian"],
								"Harga_Keluar" => $val["Harga"],
								"Qty_Saldo" => $qty_saldo,
								"Harga_Persediaan" => $val["Harga"],
								"Jam" => $header->Jam,
						);
	
						$this->db->insert("GD_trKartuGudang", $kartu_gudang );
						$this->db->insert("SIMtrPemakaianDetail", $val );

						// Insert User Aktivities
						$activities_description = sprintf( "%s # %s # %s # %s", "Input Pemakaian Barang Detail.", $header->NoBukti, $data['Kode_Barang'], $data['Nama_Barang'] );			
						$this->db->query("EXEC InsertUserActivities '$header->Tanggal','$header->Jam', {$this->user_auth->User_ID} ,'$header->NoBukti','$activities_description','SIMtrPemakaianDetail'");

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
							"NoBukti" => $header->NoBukti,
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}		

			} else
			{
				$response = array(
						"status" => 'error',
						"message" => !$validation ? $message : $this->form_validation->get_all_error_string(),
						"code" => 500
					);
			}
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK) );
			exit(0);
		}

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"section" => $this->section,
					"lookup_product" => base_url("pharmacy/item_usage/lookup_product"),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'item_usage/modal/create_edit', 
					array('form_child' => $this->load->view('item_usage/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
					"section" => $this->section,
					"lookup_item" => base_url("pharmacy/item_usage/lookup_item"),
					"form" => TRUE,
					"datatables" => TRUE,
				);

			$this->template
				->set( "heading", lang("item_usage:create_heading") )
				->set_breadcrumb( lang("item_usage:breadcrumb"), base_url("pharmacy/item-usage") )
				->set_breadcrumb( lang("item_usage:create_heading") )
				->build('item_usage/form', $data);
		}
	}
	
	public function view( $NoBukti = 0 )
	{
		$item = $this->item_usage_m->get_item_usage( $NoBukti );
		$collection = $this->item_usage_m->get_item_usage_detail( $NoBukti );

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"collection" => $collection,
					"cancel_link" => base_url("pharmacy/item-usage/cancel/$item->NoBukti"),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->load->view( 
					'item_usage/modal/create_edit', 
					array('form_child' => $this->load->view('item_usage/view', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $item,
					"collection" => $collection,
					"cancel_link" => base_url("pharmacy/item-usage/cancel/$item->NoBukti"),
					"form" => TRUE,
					"datatables" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("item_usage:view_heading") )
				->set_breadcrumb( lang("item_usage:breadcrumb"), base_url("pharmacy/item-usage") )
				->set_breadcrumb( lang("item_usage:view_heading") )
				->build('item_usage/view', $data);
		}
	}
	
	public function cancel( $NoBukti )
	{
		
		$item = $this->item_usage_m->get_item_usage( $NoBukti );		

		if( $this->input->post() ) 
		{
			
			
			if( empty($item) )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( $item->NoBukti == $this->input->post( 'confirm' ) )
			{
				
				$this->db->trans_begin();
					$header = (object) array(
						"StatusBatal" => 1, 
						"UserIDBatal" => $this->user_auth->User_ID, 
						"TanggalBatal" => date("Y-m-d"),
						"JamBatal" => date("Y-m-d H:i:s"),
						"AlasanBatal" => $this->input->post("AlasanBatal")
					);
					
					$this->db->update("SIMtrPemakaian", $header, array("NoBukti" => $item->NoBukti) );					
					// Insert User Aktivities
					$activities_description = sprintf( "%s # %s # %s # %s", "Cancel Pemakaian Barang.", $item->NoBukti, $this->section->SectionName, $this->user_auth->Nama_Asli );			
					$this->db->query("EXEC InsertUserActivities '$header->TanggalBatal','$header->JamBatal', {$this->user_auth->User_ID} ,'$item->NoBukti','$activities_description','SIMtrPemakaian'");
					
					$detail = $this->item_usage_m->get_item_usage_detail( $NoBukti );

					foreach( $detail as $val ):

						// Menyiapkan data kartu gudang unutk bhp yg digunakan
						$qty_last_stock = $this->item_usage_m->get_last_stock_warehouse_card( array("Lokasi_ID" => $this->section->Lokasi_ID, "Barang_ID" => $val->BarangID) );
						$qty_saldo = $qty_last_stock + $val->QtyPemakaian;
	
						if ( ( $qty_last_stock + $v->QtyPemakaian ) > 0 )
						{
							$HPP = (($val->Harga * $qty_last_stock) + ( $val->QtyPemakaian *  $val->Harga)) / $qty_last_stock + $v->QtyPemakaian;
						} else {
							$HPP = ($val->Harga * $qty_last_stock) + ( $val->QtyPemakaian *  $val->Harga);
						}

						$kartu_gudang = array(
								"Lokasi_ID" => $this->section->Lokasi_ID,
								"Barang_ID" => $val->BarangID,
								"No_Bukti" => $item->NoBukti."-R",
								"JTransaksi_ID" => 562,
								"Tgl_Transaksi" => $header->JamBatal,
								"Kode_Satuan" => $val->Satuan,
								"Qty_Masuk" => 0,
								"Harga_Masuk" =>  0,
								"Qty_Keluar" => $val->QtyPemakaian,
								"Harga_Keluar" => $val->Harga,
								"Qty_Saldo" => $qty_saldo,
								"Harga_Persediaan" => $HPP,
								"Jam" => $header->JamBatal,
						);
	
						$this->db->insert("GD_trKartuGudang", $kartu_gudang );

						// Insert User Aktivities
						$activities_description = sprintf( "%s # %s # %s # %s", "Input Pemakaian Barang Detail.", $item->NoBukti, $val->Kode_Barang, $val->Nama_Barang );			
						$this->db->query("EXEC InsertUserActivities '$header->TanggalBatal','$header->JamBatal', {$this->user_auth->User_ID} ,'$item->NoBukti','$activities_description','SIMtrPemakaianDetail'");

					endforeach;
						
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:cancel_failed')
						));
				}
				else
				{
					$this->db->trans_commit();
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:cancel_successfully')
						));
						
					redirect("pharmacy/item-usage/view/$item->NoBukti");
				}	
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'item_usage/modal/cancel', array('item' => $item) );
	}
	
	
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'item_usage/lookup/datatable' );
		} else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( "Lookup Box" )
				->build('item_usage/lookup', (isset($data) ? $data : NULL));
		}
	}

	
	public function lookup_item( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{			
			$this->load->view( 'item_usage/lookup/items', (isset($data) ? $data : NULL) );
		}
	}

	public function lookup_collection()
	{
		$this->datatable_collection( 1 );
	}
	
	public function datatable_collection( $state=false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->item_usage_m->table} a";
		$db_where = array();
		$db_like = array();
		
		$db_where['a.SectionID'] = $this->section->SectionID;
		
		if ($this->input->post("date_from"))
		{
			$db_where['a.Tanggal >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till"))
		{
			$db_where['a.Tanggal <='] = $this->input->post("date_till");
		}
		
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Keterangan") ] = $keywords;
			$db_like[ $this->db->escape_str("a.SectionName") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionID = b.SectionID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionID = b.SectionID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti,
			a.Jam,
			a.Keterangan,
			a.StatusBatal,
			b.SectionName
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionID = b.SectionID", "LEFT OUTER" )
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

}



