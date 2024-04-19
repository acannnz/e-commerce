<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Purchase_request extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/transactions/purchase_request'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('purchase_request_model');
		$this->load->model('purchase_request_detail_model');
		$this->load->model('supplier_model');
		$this->load->model('section_model');
		$this->load->model('procurement_model');
		$this->load->model('item_model');
		$this->load->model('item_category_model');
		$this->load->model('item_location_model');
		$this->load->model('item_unit_model');
		$this->load->model('order_model');
		$this->load->model('user_model');
		
	}
	
	//load note list view
	public function index()
	{
		$this->data['dropdown_section'] = $this->section_model->for_dropdown();		
		$this->template
			->title(lang('heading:purchase_request_list'),lang('heading:transactions'))
			// ->set_breadcrumb(lang('heading:transactions'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:purchase_request_list'))
			->build("transactions/purchase_request/index", $this->data);
	}
	
	public function create()
	{
		$item = (object)[
			"Tgl_Permintaan" => date('Y-m-d'),
			"No_Permintaan" => inventory_helper::gen_purchase_evidence_number( date('Y-m-d') ),
			"Tgl_Dibutuhkan" => date('Y-m-d'),
			"Keterangan" => NULL,
			"User_ID" => $this->user_auth->User_ID,
			"Tgl_Update" => date('Y-m-d'),
			"Currency_ID" => 1,
			"Departemen_ID" => NULL,
			"Supplier_ID" => NULL,
			"Gudang_ID" => 1367, 
			"Pembelian_Asset" => 0,
			"JenisPengadaanID" => 1
		];
		
		if( $this->input->post() ) 
		{
			$post_header = array_merge( (array) $item, $this->input->post("header") );
			$post_header['No_Permintaan'] = inventory_helper::gen_purchase_evidence_number( $post_header['Tgl_Permintaan'] );
			$post_details = $this->input->post("details");
			$additional = $this->input->post("additional");
		
			if ( inventory_helper::check_closing_period( $post_header['Tgl_Permintaan'] ))
			{
				$response = array(
							"status" => 'error',
							"message" => lang('message:already_closing_period'),
							"code" => 500
						);
				
				response_json( $response );
			}
			
			if ( empty( $post_details ))
			{
				$response = array(
							"status" => 'error',
							"message" => lang('message:empty_detail_item'),
							"code" => 500
						);
				
				response_json( $response );
			}

			if ( empty( $post_header['Supplier_ID'] ) || $post_header['Supplier_ID'] == 0)
			{
				$response = array(
							"status" => 'error',
							"message" => 'Nama Supplier belum diisi',
							"code" => 500
						);
				
				response_json( $response );
			}
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->purchase_request_model->rules['insert']);
			$this->form_validation->set_data($post_header);

			if( $this->form_validation->run())
			{								
				$this->db->trans_begin();
				
					$Permintaan_ID = $this->purchase_request_model->create( $post_header );
					
					$data_details = [];
					foreach ($post_details as $row)
					{
						$row['Permintaan_ID'] = $Permintaan_ID;
						$data_details[] = $row;
					}					
					$this->purchase_request_detail_model->mass_create( $data_details ); 
					
					if (config_item('UsePurchasing') == 0)
					{
						$this->load->model('order_model');
						$this->load->model('order_detail_model');
						
						$prepare_order = [
							"Tgl_Order" => $post_header['Tgl_Permintaan'],
							"No_Order" => $post_header['No_Permintaan'],
							"Type_Pembayaran" => 1,
							"Term_Pembayaran" => 0,
							"Type_Diskon" => 1,
							"Ongkos_Angkut" => 0,
							"Nilai_DP" => 0,
							"Keterangan" => $post_header['Keterangan'],
							"Status_Batal" => 0,
							"User_ID" => $this->user_auth->User_ID,
							"Tgl_Update" => date('Y-m-d'),
							"Supplier_ID" => $post_header['Supplier_ID'],
							"Currency_ID" => 1,
							"Total_Nilai" => $additional['grand_total'],
							"Pajak" => 0 ,
							"Pembelian_Asset" => $post_header['Pembelian_Asset'],
							"JenisPengadaanID" => $post_header['JenisPengadaanID']
						];
						
						$inserted_id = $this->order_model->create( $prepare_order );
						
						$data_details = [];
						foreach ($post_details as $row)
						{
							$prepare_detail = [
								"Qty_Order" => $row['Qty_Permintaan'],
								"Harga_Order" => $row['Harga_Terakhir'],
								"Diskon_1" => 0,
								"PPn" => 0,
								"No_Permintaan" => $post_header['No_Permintaan'],
								"Order_ID" => $inserted_id,
								"Barang_ID" => $row['Barang_ID'],
								"Kode_Satuan" => $row['Kode_Satuan'],
								"Kode_Pajak" => '',
								"Rate_Pajak" => 0,
								"JenisBarangID" => $row['JenisBarangID'],
								"Harga_Jual" => 0
							];

							$data_details[] = $prepare_detail;
						}					
						
						$this->order_detail_model->mass_create( $data_details );
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
							"Permintaan_ID" => $Permintaan_ID,
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}			
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}

			response_json( $response );
		}
		
		$this->data['form_action'] = current_url();
		$this->data['dropdown_section'] = $this->section_model->to_list_data();
		$this->data['dropdown_procurement'] = $this->procurement_model->to_list_data();
		$this->data['dropdown_procurement_group'] = $this->procurement_model->to_list_data_group();
		$this->data['item_lookup'] = base_url("$this->nameroutes/lookup_item");
		$this->data['supplier_lookup'] = base_url("$this->nameroutes/lookup_supplier");		
		$this->data['item'] = $item;
		
		$this->template
			->title(lang('heading:purchase_request'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:purchase_request_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:purchase_request'))
			->build("transactions/purchase_request/form", $this->data);
	}
	
	public function update($id = 0)
	{
		$id = (int) $id;
		
		$item = $this->purchase_request_model->get_one($id);
				
		if( $this->input->post() ) 
		{
			$post_header =  $this->input->post("header");
			$post_header['Tgl_Update'] = date('Y-m-d H:i:s');
			$post_header['User_ID'] = $this->user_auth->User_ID;

			$post_details = $this->input->post("details");
			$additional = $this->input->post("additional");

			if ( inventory_helper::check_closing_period( $post_header['Tgl_Permintaan'] ))
			{
				$response = array(
							"status" => 'error',
							"message" => lang('message:already_closing_period'),
							"code" => 500
						);
				
				response_json( $response );
			}

			if ( empty( $post_details ))
			{
				$response = array(
							"status" => 'error',
							"message" => lang('message:empty_detail_item'),
							"code" => 500
						);
				
				response_json( $response );
			}


			if ( empty( $post_header['Supplier_ID'] ) || $post_header['Supplier_ID'] == 0)
			{
				$response = array(
							"status" => 'error',
							"message" => 'Nama Supplier belum diisi',
							"code" => 500
						);
				
				response_json( $response );
			}
			
			/*$this->form_validation->set_rules($this->purchase_request_model->rules['insert']);
			$this->form_validation->set_data($post_header);*/
						
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->purchase_request_model->rules['update']);
			$this->form_validation->set_data($post_header);

			if( $this->form_validation->run())
			{								
				$this->db->trans_begin();
				
					$this->purchase_request_model->update( $post_header, $id );
					
					$db_where_not_in = []; // Untuk menghapus barang yg tidak ada pada post data
					foreach ($post_details as $row)
					{
						$db_where = [];
						$db_where['Permintaan_ID'] = $id;
						$db_where['Barang_ID'] = $row['Barang_ID'];
						
						if ( !empty($this->purchase_request_detail_model->get_by( $db_where ) ))
						{
							$this->purchase_request_detail_model->update_by( $row, $db_where);
						} else {
							$row['Permintaan_ID'] = $id;
							$this->purchase_request_detail_model->create( $row );
						}
						
						$db_where_not_in[] = $row['Barang_ID'];
					}					
					
					$this->purchase_request_detail_model->delete_not_in($id, $db_where_not_in );
					
					if (config_item('UsePurchasing') == 0)
					{
						$this->load->model('order_model');
						$this->load->model('order_detail_model');
						
						$order = $this->order_model->get_by( ['No_Order' => $item->No_Permintaan] );
						
						$prepare_order = [
							"Tgl_Order" => $post_header['Tgl_Permintaan'],
							"No_Order" => $item->No_Permintaan,
							"Keterangan" => $post_header['Keterangan'],
							"User_ID" => $this->user_auth->User_ID,
							"Tgl_Update" => date('Y-m-d'),
							"Supplier_ID" => $post_header['Supplier_ID'],
							"Total_Nilai" => $additional['grand_total'],
							"Pembelian_Asset" => $post_header['Pembelian_Asset'],
						];
						
						$this->order_model->update( $prepare_order, $order->Order_ID );
						
						$db_where_not_in = []; // Untuk menghapus barang yg tidak ada pada post data
						foreach ($post_details as $row)
						{
							$db_where = [];
							$db_where['Order_ID'] = $order->Order_ID;
							$db_where['Barang_ID'] = $row['Barang_ID'];
							
							if ( !empty($this->order_detail_model->get_by( $db_where ) ))
							{
								$prepare_detail = [
									"Qty_Order" => $row['Qty_Permintaan'],
									"Harga_Order" => $row['Harga_Terakhir'],
									"No_Permintaan" => $item->No_Permintaan,
									"Barang_ID" => $row['Barang_ID'],
									"Kode_Satuan" => $row['Kode_Satuan'],
									"JenisBarangID" => $row['JenisBarangID'],
								];
								
								$this->order_detail_model->update_by( $prepare_detail, $db_where);
							} else {
								$prepare_detail = [
									"Qty_Order" => $row['Qty_Permintaan'],
									"Harga_Order" => $row['Harga_Terakhir'],
									"Diskon_1" => 0,
									"PPn" => 0,
									"No_Permintaan" => $item->No_Permintaan,
									"Order_ID" => $order->Order_ID,
									"Barang_ID" => $row['Barang_ID'],
									"Kode_Satuan" => $row['Kode_Satuan'],
									"Kode_Pajak" => '',
									"Rate_Pajak" => 0,
									"JenisBarangID" => $row['JenisBarangID'],
									"Harga_Jual" => 0
								];
	
								$this->order_detail_model->create( $prepare_detail );
							}
	
							$db_where_not_in[] = $row['Barang_ID'];
						}					
						
						if(!empty($db_where_not_in))
							$this->order_detail_model->delete_not_in($order->Order_ID, $db_where_not_in );
											
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
				
				$this->db->trans_commit();
				$response = array(
						"Permintaan_ID" => $id,
						"status" => 'success',
						"message" => lang('global:created_successfully'),
						"code" => 200
					);
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}

			response_json( $response );
		}
		
		$this->data['item'] = $item;
		$this->data['collection'] = $this->purchase_request_detail_model->get_all( NULL, 0, [ 'Permintaan_ID' => $item->Permintaan_ID, 'c.Lokasi_ID' => $item->Gudang_ID ]);
		$this->data['supplier'] = $this->supplier_model->get_one($item->Supplier_ID);
		$this->data['form_action'] = current_url();
		$this->data['dropdown_section'] = $this->section_model->to_list_data();
		$this->data['dropdown_procurement'] = $this->procurement_model->to_list_data( $item->Gudang_ID );
		$this->data['item_lookup'] = base_url("$this->nameroutes/lookup_item");
		$this->data['supplier_lookup'] = base_url("$this->nameroutes/lookup_supplier");		
		$this->data['cancel_url'] = base_url("$this->nameroutes/cancel/{$id}");		
		$this->data['is_edit'] = TRUE;		

		$this->template
			->title(lang('heading:purchase_request'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:purchase_request_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:purchase_request'))
			->build("transactions/purchase_request/form", $this->data);
	}
	
	public function print_po($id)
	{
		$item = $this->purchase_request_model->get_one($id);

		$collection = $this->purchase_request_detail_model->get_all(NULL, 0, ['Permintaan_ID' => $item->Permintaan_ID, 'c.Lokasi_ID' => $item->Gudang_ID]);
		$user = $this->user_model->get_one($item->User_ID);
		$supplier = $this->supplier_model->get_one($item->Supplier_ID);
		$gudang = $this->section_model->get_by(['Lokasi_ID' => $item->Gudang_ID]);

		$file_name = "SURAT PESANAN";

		$data = array(
			"item" => $item,
			"collection" => $collection,
			"supplier" => $supplier,
			"file_name" => $file_name,
			"user" => $user,
			"gudang" => $gudang
		);

		$html_content =  $this->load->view("transactions/purchase_request/print/print_po", $data, TRUE);

		$footer = "&nbsp;" . date("d M Y") . "&nbsp;" . date("H:i:s");
		$this->load->helper("export");
		export_helper::generate_pdf($html_content, $file_name, $footer, $margin_bottom = 5, $header = NULL, $margin_top = 2, 'A5',$orientation = 'L', $margin_left = 8, $margin_right = 8);

		exit(0);
	}

	public function cancel($id = 0)
	{
		$id = (int) @$id;
		$this->data['item'] = $item = $this->purchase_request_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 
			if ( inventory_helper::check_closing_period( date('Y-m-d', strtotime($item->Tgl_Permintaan)) ))
			{
				$response = array(
							"status" => 'error',
							"message" => lang('message:already_closing_period'),
							"code" => 500
						);
				
				response_json( $response );
			}
			
			$this->db->trans_begin();
				
				$this->purchase_request_model->update( ['Status_Batal' => 1 ], $item->Permintaan_ID );
				
				if(config_item('UsePurchasing') == 0)
				{
					$this->load->model('order_model');
					$this->order_model->update_by( ['Status_Batal' => 1 ], ['No_Order' => $item->No_Permintaan] );
				}
			
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
		
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/cancel/{$id}");
		$this->load->view('transactions/purchase_request/modal/cancel', $this->data);
	}
	
	public function get_detail_collection( $id = 0)
	{
		if ( $this->input->is_ajax_request() && $id !== 0 )
		{
			$collection = $this->purchase_request_detail_model->get_all( NULL, 0, NULL, FALSE, $id);
			response_json( $collection );
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
		
		$db_from = "{$this->purchase_request_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter
		//$db_where['a.Status_Batal'] = 0;

		if( $this->input->post("date_from") ){
			$db_where['a.Tgl_Permintaan >='] = $this->input->post("date_from");
		}

		if( $this->input->post("date_till") ){
			$db_where['a.Tgl_Permintaan <='] = $this->input->post("date_till");
		}		

		if( $this->input->post("date_till") ){
			$db_where['a.Tgl_Permintaan <='] = $this->input->post("date_till");
		}		

		if( $this->input->post("Gudang_ID") ){
			$db_where['a.Gudang_ID'] = $this->input->post("Gudang_ID");
		}
		
		if( $this->input->post("Permintaan_ID") ){
			$db_where['a.Permintaan_ID'] = $this->input->post("Permintaan_ID");
		}
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.No_Permintaan") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Tgl_Dibutuhkan") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Nama_Supplier") ] = $keywords;
			 
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->supplier_model->table} b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER")
			->join("{$this->section_model->table} c", "a.Gudang_ID = c.Lokasi_ID", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.Status, 
			a.Tgl_Permintaan,
			a.No_Permintaan,
			a.Tgl_Dibutuhkan,
			a.Status_Batal,
			a.Permintaan_ID,
			a.Keterangan,
			b.Nama_Supplier,
			c.SectionName
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->supplier_model->table} b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER")
			->join("{$this->section_model->table} c", "a.Gudang_ID = c.Lokasi_ID", "LEFT OUTER")
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
		
		response_json( $output );
	}
	
	public function lookup_item( $is_ajax_request=false )
	{
		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/purchase_request/lookup/lookup_item' );
		}
	}
	
	public function lookup_supplier( $is_ajax_request=false )
	{
		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/purchase_request/lookup/lookup_supplier' );
		}
	}

}

