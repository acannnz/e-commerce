<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consumables extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	protected $nameroutes = 'poly/polies';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('poly');
		
		$this->load->model("poly_m");
		$this->load->helper("poly");
		
	}
	
	public function index( $item = NULL )
	{
		$data = array(
				"collection" => $this->poly_m->get_child_data( "BILLFarmasi", array("NoReg" => @$item->RegNo,"SectionAsalID" => config_item('section_id'), "Retur" => 0 ) ),
				"form" => TRUE,
				'datatables' => TRUE,
				'nameroutes' => $this->nameroutes,
				'create_consumable' => base_url("{$this->nameroutes}/consumables/item_create"),
				'delete_consumable' => base_url("{$this->nameroutes}/consumables/item_delete"),
				'view_consumable' => base_url("{$this->nameroutes}/consumables/item_view"),
			);

		$this->load->view( 'polies/form/consumables', $data );		
	}

	public function lookup_prescription( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'polies/lookup/consumables' );
		} 
	}

	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'polies/consumables/lookup/suppliers' );
		} 
	}
	
	public function lookup_product( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request == true )
		{	
			$this->load->view( 'polies/consumables/lookup/products' );
		} 
	}

	public function item_create()
	{
		$item = array(
				'NoBukti' => poly_helper::gen_bhp_number(),
				'IncludeJasa' => 1,
			);
			
		if( $this->input->post() ) 
		{
			

			$bhp = $this->input->post("f");
			$detail = $this->input->post("details");
			
			$this->load->library( 'form_validation' );		
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("f") );
			
			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
					$bhp['UserID'] = $this->user_auth->User_ID;
					$this->db->insert("BILLFarmasi", $bhp );				
					$this->db->insert_batch("BILLFarmasiDetail", $detail );

					$section = $this->poly_m->get_row_data( "SIMmSection", array("SectionID" => $bhp['SectionID'] ));
					foreach( $detail as $k => $v )
					{
						// Mangambil total stok terakhir yang ada pada kartu gudang
						$qty_last_stock = $this->poly_m->get_last_stock_warehouse_card( array("Lokasi_ID" => $section->Lokasi_ID, "Barang_ID" => $v["Barang_ID"]) );
						$qty_saldo = $qty_last_stock - $v["JmlObat"];
						$kartu_gudang = array(
								"Lokasi_ID" => $section->Lokasi_ID,
								"Barang_ID" => $v["Barang_ID"],
								"No_Bukti" => $v["NoBukti"],
								"JTransaksi_ID" => 564,
								"Tgl_Transaksi" => $bhp["Tanggal"],
								"Kode_Satuan" => $v["Satuan"],
								"Qty_Masuk" => 0,
								"Harga_Masuk" =>  0,
								"Qty_Keluar" => $v["JmlObat"],
								"Harga_Keluar" => $v["Harga"],
								"Qty_Saldo" => $qty_saldo,
								"Harga_Persediaan" => $v["Harga"],
								"Jam" => $bhp["Jam"],
						);
	
						$this->db->insert("GD_trKartuGudang", $kartu_gudang );
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
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}				

			} else
			{
				$response = array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					);
			}
			
			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit(0);
		}
		
		$option_pharmacy = $this->poly_m->get_options("SIMmSection", array("KelompokSection"  => "FARMASI", "GroupSection" => "4"));
		$lookup_supplier = base_url("{$this->nameroutes}/consumables/lookup_supplier");
		$lookup_product = base_url("{$this->nameroutes}/consumables/lookup_product");

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					'nameroutes' => $this->nameroutes,
					"option_pharmacy" => $option_pharmacy,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);
			
			$this->load->view( 'polies/consumables/form', $data );
		}
	}

	public function item_delete()
	{
		
		if( $this->input->post() ) 
		{
			
			$NoBukti = $this->input->post("NoBukti");
			
			$this->load->library( 'form_validation' );
			/*$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );			*/
			if( !$this->form_validation->run() )
			{
				$this->db->trans_begin();
					
					$this->db->update("BILLFarmasi", ["Retur" => 1], ["NoBukti" => @$NoBukti]);
					# $detail = $this->db->where( array("No_Bukti" => @$NoBukti) )->get("GD_trKartuGudang")->result();
					$detail = $this->db->where( ["NoBukti" => @$NoBukti] )->get("BILLFarmasiDetail")->result();
					
					foreach( $detail as $v )
					{
						$_insert_warehouse_fifo = [
							'location_id' => $this->section->Lokasi_ID, 
							'item_id' => $v->Barang_ID,  
							'item_unit_code' => $v->Satuan,  
							'qty' => $v->JmlObat, 
							'price' => $v->Harga,  
							'conversion' => 1,  
							'evidence_number' => $NoBukti.'-R',  
							'trans_type_id' => 562,
							'in_out_state' => 1,
							'trans_date' => date('Y-m-d'),  
							'exp_date' => NULL,  
							'item_type_id' => 0, 
						];
						
						poly_helper::insert_warehouse_fifo( $_insert_warehouse_fifo );
						
						/*// Ambil stok terakhir yang ada di kartu gudang,
						$qty_last_stock = $this->poly_m->get_last_stock_warehouse_card( array("Lokasi_ID" => $v->Lokasi_ID, "Barang_ID" => $v->Barang_ID) );
						 $qty_saldo = $qty_last_stock + $v->Qty_Keluar;
	
						if ( ( $qty_last_stock + $v->Qty_Keluar ) > 0 )
						{
							
							$HPP = (($v->Harga_Keluar * $qty_last_stock) + ( $v->Qty_Keluar *  $v->Harga_Keluar)) / $qty_last_stock + $v->Qty_Keluar;
						} else {
							$HPP = ($v->Harga_Keluar * $qty_last_stock) + ( $v->Qty_Keluar *  $v->Harga_Keluar);
						}

						$kartu_gudang = array(
								"Lokasi_ID" => $v->Lokasi_ID,
								"Barang_ID" => $v->Barang_ID,
								"No_Bukti" => $v->No_Bukti."-R",
								"JTransaksi_ID" => 562,
								"Tgl_Transaksi" => date("Y-m-d"),
								"Kode_Satuan" => $v->Kode_Satuan,
								"Qty_Masuk" => $v->Qty_Keluar,
								"Harga_Masuk" => $v->Harga_Keluar,
								"Qty_Keluar" => 0,
								"Harga_Keluar" => 0,
								"Qty_Saldo" => $qty_saldo,
								"Harga_Persediaan" => $HPP,
								"Jam" => date("Y-m-d H:i:s"),
						);
	
						$this->db->insert("GD_trKartuGudang", $kartu_gudang );*/
					}
										
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('global:deleted_failed'),
							"code" => 500
						);
				}
				//$this->db->trans_rollback();
				$this->db->trans_commit();
				$response = array(
						"status" => 'success',
						"message" => lang('global:deleted_successfully'),
						"code" => 200
					);

			} else
			{
				$response = array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					);
			}
			
			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit(0);
		}

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					'nameroutes' => $this->nameroutes,
					"option_pharmacy" => $option_pharmacy,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);
			
			$this->load->view( 'polies/consumables/form', $data );
		} 
	}			

	public function item_view( $NoBukti )
	{

		if( $this->input->is_ajax_request() )
		{
			
			$item = $this->poly_m->get_row_data("BILLFarmasi", array("NoBukti"  => $NoBukti));
			$doctor = $this->poly_m->get_row_data("mSupplier", array("Kode_Supplier"  => $item->DokterID));
			$collection = $this->poly_m->get_consumable_detail_data( array("NoBukti"  => $NoBukti));
			$option_pharmacy = $this->poly_m->get_options("SIMmSection", array("KelompokSection"  => "FARMASI", "GroupSection" => "4"));
			$lookup_supplier = base_url("{$this->nameroutes}/consumables/lookup_supplier");
			$lookup_product = base_url("{$this->nameroutes}/consumables/lookup_product");

			$data = array(
					'item' => $item,
					'doctor' => $doctor,
					"collection" => $collection,
					"option_pharmacy" => $option_pharmacy,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product,
					'nameroutes' => $this->nameroutes,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->load->view( 'polies/consumables/form_view', $data );
		} 
	}
		
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'icd/lookup/datatable' );
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
		
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		$db_where['deleted_at'] = NULL;
		if( $state !== false )
		{
			$db_where['state'] = 1;
		}
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            for($i=0; $i<count($columns); $i++)
            {
                if( isset($columns[$i]['searchable']) && $columns[$i]['searchable'] == 'true')
                {
                	$db_like[$columns[$i]['data']] = $search['value'];
				}
            }
        }
		
		// get total records
		$this->db->from( "common_icd" );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db->from( "common_icd" );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$this->db->from( "common_icd" );
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
			$row->created_at = strftime(config_item('date_format'), @$row->created_at);
			$row->updated_at = strftime(config_item('date_format'), @$row->updated_at);
			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
}