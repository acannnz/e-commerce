<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Goods_receipt extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/transactions/goods_receipt'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('goods_receipt_model');
		$this->load->model('goods_receipt_detail_model');
		$this->load->model('goods_receipt_penerimaan_model');
		$this->load->model('order_model');
		$this->load->model('order_detail_model');
		$this->load->model('batch_card_model');
		$this->load->model('purchase_request_model');
		$this->load->model('supplier_model');
		$this->load->model('section_model');
		$this->load->model('procurement_model');
		$this->load->model('item_model');
		$this->load->model('item_category_model');
		$this->load->model('item_location_model');
		$this->load->model('item_unit_model');
		$this->load->model('item_type_model');
		$this->load->model('currency_model');
		$this->load->model('user_model');
	}
	
	//load note list view
	public function index()
	{
		$this->data['dropdown_section'] = $dropdown_section = $this->section_model->for_dropdown();	
		$this->data['view_datatable_open'] = $this->load->view('transactions/goods_receipt/datatable/open', $this->data, TRUE );
		$this->data['view_datatable_realization'] = $this->load->view('transactions/goods_receipt/datatable/realization', $this->data, TRUE );
		
		
		$this->template
			->title(lang('heading:goods_receipt'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:goods_receipt_list'), site_url($this->nameroutes))
			->build("transactions/goods_receipt/index", $this->data);
	}
	
	public function detail()
	{
		$this->data['dropdown_section'] = $this->section_model->for_dropdown();	
		$this->data['update_state'] = base_url("{$this->nameroutes}/update_detail");
		
		$this->template
			->title('Detail Penerimaan Barang',lang('heading:transactions'))
			->set_breadcrumb('Daftar Detail Penerimaan Barang')
			// ->set_breadcrumb(lang('heading:goods_receipt_detail'), site_url("{$this->nameroutes}/detail"))
			->build("transactions/goods_receipt/detail/index", $this->data);
	}
	
	/*
		@params
		(String) id -> Order_ID
	*/
	public function create( $id = NULL )
	{
		if( $order = $this->order_model->get_one( $id ) )
		{
			$this->data['order'] = $order;
			$purchase_request = $this->purchase_request_model->get_by( ['No_Permintaan' => $order->No_Order] );
			$this->data['collection'] = $this->_prepare_open_detail_collection( $id );
			$this->data['supplier'] = $this->supplier_model->get_one( $purchase_request->Supplier_ID );
		}
		
		$item = (object)[
			"Tgl_Penerimaan" => date('Y-m-d'),
			"No_Penerimaan" => inventory_helper::gen_goods_receipt_evidence_number( date('Y-m-d') ),
			"Type_Pembayaran" => 1,
			"Term_Pembayaran" => 0,
			"Type_Diskon" => 1,
			"Ongkos_Angkut" => 0,
			"Potongan" => 0,
			"Nilai_DP" => 0,
			"Keterangan" => NULL,
			"No_DO" => NULL,
			"User_ID" => $this->user_auth->User_ID,
			"Tgl_Update" => date('Y-m-d'),
			"Posting_KG" => 0,
			"Posting_GL" => 0,
			"Currency_ID" => 1,
			"Supplier_ID" => @$order->Supplier_ID,
			"Order_ID" => @$order->Order_ID,
			"Total_Nilai" => 0,
			"Pajak" => 0,
			"No_Retur_Penjualan" => '',
			"Sumber_Penerimaan" => 0,
			"Lokasi_ID" => @$purchase_request->Gudang_ID,
			"Tgl_JatuhTempo" => date('Y-m-d'),
			"Pembelian_Asset" => 0,
			"IncludePPN" => 0,
		];
		
		if( $this->input->post() ) 
		{
			$post_header = array_merge( (array) $item, $this->input->post("header") );
			$post_header['No_Penerimaan'] = inventory_helper::gen_goods_receipt_evidence_number( $post_header['Tgl_Penerimaan'] );
			$post_details = $this->input->post("details");
			$additional = $this->input->post("additional");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->goods_receipt_model->rules['insert']);
			$this->form_validation->set_data($post_header);
			if( $this->form_validation->run())
			{							
			
				if ( inventory_helper::check_closing_period( $post_header['Tgl_Penerimaan'] ))
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
				
				$this->db->trans_begin();
				
					$currentStockQty = (float) $this->item_location_model->get_by( [ 'Lokasi_ID' => $post_header['Lokasi_ID'], 'Barang_ID' => $row['Barang_ID'] ] )->Qty_Stok;

					$insert_id = $this->goods_receipt_model->create( $post_header );

					$activities_description = sprintf( "Input Penerimaan. # %s # %s # %s # Ongkir : %s ", $post_header['No_Penerimaan'], $additional['Supplier_Name'], $additional['SectionName'], $post_header['Ongkos_Angkut']  );
					insert_user_activity( $activities_description, $post_header['No_Penerimaan'], $this->goods_receipt_model->table);
										
					$_prepare_detail = [
						"Penerimaan_ID" => $insert_id,
						"Kode_Pajak" => NULL,
						"Rate_Pajak" => 0,
						"NoBatch" => NULL,
						"PPn" => 0,
					];
					
					foreach ($post_details as $row)
					{
						$_insert_detail = array_merge($_prepare_detail, $row);

						$this->goods_receipt_detail_model->create( $_insert_detail ); 
						
						$_order_detail = [
							"data" => [
								"Qty_Tlh_Dibeli" => $row['Qty_Telah_Terima'] + $row['Qty_Penerimaan'],
								"Harga_Order" => $row['Harga_Beli']
							],
							"where" => [
								'Order_ID' => $post_header['Order_ID'], 
								'Barang_ID' => $row['Barang_ID']
							]
						];						
						$this->order_detail_model->update_by( $_order_detail['data'], $_order_detail['where'] );

						$HargaPajak = $row['Harga_Beli'] + ($row['Harga_Beli'] * $row['Rate_Pajak'] / 100);
						$curHarga = ($HargaPajak - ($HargaPajak * $row['Diskon_1'] / 100)) / $row['Konversi'];
						$qtyTerima = $row['Qty_Penerimaan'] * $row['Konversi'];
						
						$_insert_fifo = [
							'location_id' => $post_header['Lokasi_ID'], 
							'item_id' => $row['Barang_ID'],  
							'item_unit_code' => $row['Kode_Satuan'],  
							'qty' => $qtyTerima, 
							'price' => $row['Harga_Beli'],  
							'conversion' => $row['Konversi'],  
							'evidence_number' => $post_header['No_Penerimaan'],  
							'trans_type_id' => 501,
							'in_out_state' => 1,
							'trans_date' => $post_header['Tgl_Penerimaan'],  
							'exp_date' => $row['Exp_Date'],  
							'item_type_id' => $row['JenisBarangID'], 
						];					
						inventory_helper::insert_warehouse_fifo( $_insert_fifo );
						
						if ( $post_header['Order_ID'] != "" )
						{
							$_order_detail = [
								"data" => [
									"Qty_Penerimaan" => $row['Qty_Penerimaan'],
								],
								"where" => [
									'Order_ID' => $post_header['Order_ID'], 
									'Barang_ID' => $row['Barang_ID']
								]
							];
							$this->order_detail_model->update_by( $_order_detail['data'], $_order_detail['where'] );
						}
						
						$item_data = $this->item_model->get_one( $row['Barang_ID'] );
						
						if ( $row['Qty_Penerimaan'] > 0 )
						{	
							$currentAveragePrice = (float) $item_data->HRataRata;
							$currentSalePrice = (float) $item_data->Harga_Jual;
							
							$averagePrice = ( $currentStockQty + $row['Qty_Penerimaan'] <= 0 )
											? $curHarga
											: (( $currentStockQty * $currentAveragePrice ) + ( $curHarga * ($row['Qty_Penerimaan']/$row['Konversi']))) / ( $currentStockQty + ($row['Qty_Penerimaan']/$row['Konversi']) );

							if(config_item('pakai_harga_tertinggi') == 'TRUE'){
								if ( $curHarga > $currentSalePrice ) // Cek harga tertinggi
								{
									$this->item_model->update( ['Harga_Jual' => $curHarga ], $row['Barang_ID'] );
								}
							}
							
							$_insert_price_change = [
								'location_id' => $post_header['Lokasi_ID'],
								'item_id' => $row['Barang_ID'],  
								'trans_date' => $post_header['Tgl_Penerimaan'], 
								'price' => $curHarga,  
							];
							inventory_helper::insert_price_change( $_insert_price_change );
							
							$_insert_supplier_item = [
								'supplier_id' => $post_header['Supplier_ID'],
								'item_id' => $row['Barang_ID'],  
								'trans_date' => $post_header['Tgl_Penerimaan'], 
								'price' => $row['Harga_Beli'],  
							];
							inventory_helper::insert_supplier_item( $_insert_supplier_item );
							
							$_update_item = [
								'Konversi' => $row['Konversi'],
								'HRataRata' => $averagePrice,
								'Harga_Beli' => $row['Harga_Beli'],
								'UserID' => $this->user_auth->User_ID,
								'DateUpdate' => $post_header['Tgl_Penerimaan']
							];
							
							$this->item_model->update( $_update_item, $row['Barang_ID'] );
						}
						
						$activities_description = sprintf( "Input Penerimaan Item. # %s # %s # %s # Qty : %s # Harga: %s # %s ", $post_header['No_Penerimaan'], $row['Barang_ID'], $item_data->Nama_Barang, $row['Qty_Penerimaan'], $row['Harga_Beli'], $additional['SectionName'] );
						insert_user_activity( $activities_description, $post_header['No_Penerimaan'], $this->goods_receipt_detail_model->table);
						
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
							"Penerimaan_ID" => $insert_id,
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
		
		$this->data['item'] = $item;
		$this->data['form_action'] = $form_action = current_url();
		$this->data['dropdown_section'] = $this->section_model->for_dropdown();
		$this->data['lookup_purchase_order'] = 'lookup_purchase_order';
				
		$this->template
			->title(lang('heading:goods_receipt'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:goods_receipt_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:goods_receipt'))
			->build("transactions/goods_receipt/form", $this->data);
	}

	public function create_penerimaan( $id = NULL )
	{
		if( $order = $this->order_model->get_one( $id ) )
		{
			$this->data['order'] = $order;
			$purchase_request = $this->purchase_request_model->get_by( ['No_Permintaan' => $order->No_Order] );
			$this->data['collection'] = $this->_prepare_open_detail_collection( $id );
			$this->data['supplier'] = $this->supplier_model->get_one( $purchase_request->Supplier_ID );
		}
		
		$item = (object)[
			"Tgl_Penerimaan" => date('Y-m-d'),
			"No_Penerimaan" => inventory_helper::gen_goods_receipt_evidence_number( date('Y-m-d') ),
			"Type_Pembayaran" => 1,
			"Term_Pembayaran" => 0,
			"Type_Diskon" => 1,
			"Ongkos_Angkut" => 0,
			"Potongan" => 0,
			"Nilai_DP" => 0,
			"Keterangan" => NULL,
			"No_DO" => NULL,
			"User_ID" => $this->user_auth->User_ID,
			"Tgl_Update" => date('Y-m-d'),
			"Posting_KG" => 0,
			"Posting_GL" => 0,
			"Currency_ID" => 1,
			"Supplier_ID" => @$order->Supplier_ID,
			"Order_ID" => @$order->Order_ID,
			"Total_Nilai" => 0,
			"Pajak" => 0,
			"No_Retur_Penjualan" => '',
			"Sumber_Penerimaan" => 0,
			"Lokasi_ID" => @$purchase_request->Gudang_ID,
			"Tgl_JatuhTempo" => date('Y-m-d'),
			"Pembelian_Asset" => 0,
			"IncludePPN" => 0,
		];
		
		if( $this->input->post() ) 
		{
			$post_header = array_merge( (array) $item, $this->input->post("header") );
			$post_header['No_Penerimaan'] = inventory_helper::gen_goods_receipt_evidence_number( $post_header['Tgl_Penerimaan'] );
			$post_detail = $this->input->post("details");
			$additional = $this->input->post("additional");
			// print_r($post_header);exit;
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->goods_receipt_penerimaan_model->rules['insert']);
			$this->form_validation->set_data($post_header);
			if( $this->form_validation->run())
			{							
			
				if ( inventory_helper::check_closing_period( $post_header['Tgl_Penerimaan'] ))
				{
					$response = array(
								"status" => 'error',
								"message" => lang('message:already_closing_period'),
								"code" => 500
							);
					
					response_json( $response );
				}
				
				if ( empty( $post_detail ))
				{
					$response = array(
								"status" => 'error',
								"message" => lang('message:empty_detail_item'),
								"code" => 500
							);
					
					response_json( $response );
				}
				
				$this->db->trans_begin();
				
					$insert_id = $this->goods_receipt_penerimaan_model->create( $post_header );

					$activities_description = sprintf( "Input Penerimaan. # %s # %s # %s # Ongkir : %s ", $post_header['No_Penerimaan'], $additional['Supplier_Name'], $additional['SectionName'], $post_header['Ongkos_Angkut']  );
					insert_user_activity( $activities_description, $post_header['No_Penerimaan'], $this->goods_receipt_penerimaan_model->table);
										
					$_prepare_detail = [
						"Penerimaan_ID" => $insert_id,
						"Kode_Pajak" => NULL,
						"Rate_Pajak" => 0,
						"NoBatch" => NULL,
						"PPn" => 0,
					];
					
					foreach ($post_detail as $row)
					{
						$_insert_detail = array_merge($_prepare_detail, $row);
						$this->goods_receipt_detail_model->create( $_insert_detail ); 
						
						$_order_detail = [
							"data" => [
								"Qty_Tlh_Dibeli" => $row['Qty_Penerimaan'],
								"Harga_Order" => $row['Harga_Beli']
							],
							"where" => [
								'Order_ID' => 0, 
								'Barang_ID' => $row['Barang_ID']
							]
						];						
						$this->order_detail_model->update_by( $_order_detail['data'], $_order_detail['where'] );

						$diskon_persen = $row['Harga_Beli'] * ($row['Diskon_1'] / 100);
						$curHarga = (($row['Harga_Beli'] + ($row['Harga_Beli'] * $row['Rate_Pajak'] / 100)) / $row['Konversi']) - $diskon_persen;
						$qtyTerima = $row['Qty_Penerimaan'] * $row['Konversi'];
						
						// # Pengurangan Stock Farmasi
						// if ($post_header['Supplier_ID'] == 141) 
						// {
						// 	$_insert_fifo = [
						// 		'location_id' => 1426, 
						// 		'item_id' => $row['Barang_ID'],  
						// 		'item_unit_code' => $row['Kode_Satuan'],  
						// 		'qty' => $qtyTerima, 
						// 		'price' => $row['Harga_Beli'],  
						// 		'conversion' => $row['Konversi'],  
						// 		'evidence_number' => $post_header['No_Penerimaan'],  
						// 		'trans_type_id' => 501,
						// 		'in_out_state' => 0,
						// 		'trans_date' => $post_header['Tgl_Penerimaan'],  
						// 		'exp_date' => '1990-01-01',  
						// 		'item_type_id' => $row['JenisBarangID'],
						// 	];			
						// 	inventory_helper::insert_warehouse_fifo( $_insert_fifo );
						// }	

						$_insert_fifo = [
							'location_id' => $post_header['Lokasi_ID'], 
							'item_id' => $row['Barang_ID'],  
							'item_unit_code' => $row['Kode_Satuan'],  
							'qty' => $qtyTerima, 
							'price' => $row['Harga_Beli'],  
							'conversion' => $row['Konversi'],  
							'evidence_number' => $post_header['No_Penerimaan'],  
							'trans_type_id' => 501,
							'in_out_state' => 1,
							'trans_date' => $post_header['Tgl_Penerimaan'],  
							'exp_date' => '1990-01-01',  
							'item_type_id' => $row['JenisBarangID'], 
						];			
						inventory_helper::insert_warehouse_fifo( $_insert_fifo );

						$item_data = $this->item_model->get_one( $row['Barang_ID'] );
						
						if ( $row['Qty_Penerimaan'] > 0 )
						{	
							$currentAveragePrice = (float) $item_data->HRataRata;
							$currentSalePrice = (float) $item_data->Harga_Jual;
							$currentStockQty = (float) $this->item_location_model->get_by( [ 'Lokasi_ID' => $post_header['Lokasi_ID'], 'Barang_ID' => $row['Barang_ID'] ] )->Qty_Stok;
							
							$averagePrice = ( $currentStockQty + $row['Qty_Penerimaan'] <= 0 )
											? $curHarga
											: (( $currentStockQty * $currentAveragePrice ) + ( $curHarga * ($row['Qty_Penerimaan']/$row['Konversi']))) / ( $currentStockQty + ($row['Qty_Penerimaan']/$row['Konversi']) );

							if(config_item('pakai_harga_tertinggi') == 'TRUE'){
								if ( $curHarga > $currentSalePrice ) // Cek harga tertinggi
								{
									$this->item_model->update( ['Harga_Jual' => $curHarga ], $row['Barang_ID'] );
								}
							}
							
							$_insert_price_change = [
								'location_id' => $post_header['Lokasi_ID'],
								'item_id' => $row['Barang_ID'],  
								'trans_date' => $post_header['Tgl_Penerimaan'], 
								'price' => $curHarga,  
							];
							inventory_helper::insert_price_change( $_insert_price_change );
							
							$_insert_supplier_item = [
								'supplier_id' => $post_header['Supplier_ID'],
								'item_id' => $row['Barang_ID'],  
								'trans_date' => $post_header['Tgl_Penerimaan'], 
								'price' => $row['Harga_Beli'],  
							];
							inventory_helper::insert_supplier_item( $_insert_supplier_item );
							
							$_update_item = [
								'Konversi' => $row['Konversi'],
								'HRataRata' => $averagePrice,
								'Harga_Beli' => $row['Harga_Beli'],
								'UserID' => $this->user_auth->User_ID,
								'DateUpdate' => $post_header['Tgl_Penerimaan']
							];
							
							$this->item_model->update( $_update_item, $row['Barang_ID'] );
						}
						
						$activities_description = sprintf( "Input Penerimaan Item. # %s # %s # %s # Qty : %s # Harga: %s # %s ", $post_header['No_Penerimaan'], $row['Barang_ID'], $item_data->Nama_Barang, $row['Qty_Penerimaan'], $row['Harga_Beli'], $additional['SectionName'] );
						insert_user_activity( $activities_description, $post_header['No_Penerimaan'], $this->goods_receipt_detail_model->table);
						
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
							"Penerimaan_ID" => $insert_id,
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
		
		$this->data['item'] = $item;
		$this->data['form_action'] = $form_action = current_url();
		$this->data['dropdown_section'] = $this->section_model->for_dropdown_far();
		$this->data['lookup_purchase_order'] = 'lookup_purchase_order';
		$this->data['item_lookup'] = base_url("$this->nameroutes/lookup_item");
		$this->data['item_lookup_konsinyasi'] = base_url("$this->nameroutes/lookup_item_konsinyasi");
		$this->data['supplier_lookup'] = base_url("$this->nameroutes/lookup_supplier");
		
		$this->template
			->title(lang('heading:goods_receipt'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:goods_receipt_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:goods_receipt'))
			->build("transactions/goods_receipt/form_penerimaan", $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->goods_receipt_model->get_one($id);
		
		//if( $this->input->post() )  matikan update, karena tidak dipakai
		if( false )
		{
			$post_header = array_merge( (array) $item, $this->input->post("header") );
			$post_details = $this->input->post("details");
			$additional = $this->input->post("additional");
		
			if ( inventory_helper::check_closing_period( $post_header['Tgl_Penerimaan'] ))
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
			
			/*$this->form_validation->set_rules($this->purchase_request_model->rules['insert']);
			$this->form_validation->set_data($post_header);*/
			
			if( !$this->form_validation->run())
			{								
				$this->db->trans_begin();
				
					$this->goods_receipt_model->update( $post_header, $id );

					$activities_description = sprintf( "Update Penerimaan. # %s # %s # %s # Ongkir : %s ", $post_header['No_Penerimaan'], $additional['Supplier_Name'], $additional['SectionName'], $post_header['Ongkos_Angkut']  );
					insert_user_activity( $activities_description, $post_header['No_Penerimaan'], $this->goods_receipt_model->table);
										
					$_prepare_detail = [
						"Penerimaan_ID" => $id,
						"Kode_Pajak" => NULL,
						"Rate_Pajak" => 0,
						"NoBatch" => NULL,
						"PPn" => 0,
					];
					
					foreach ($post_details as $row)
					{
						$_insert_detail = array_merge($_prepare_detail, $row);
						$this->goods_receipt_detail_model->create( $_insert_detail ); 
						
						$_order_detail = [
							"data" => [
								"Qty_Tlh_Dibeli" => $row['Qty_Telah_Terima'] + $row['Qty_Penerimaan'],
								"Harga_Order" => $row['Harga_Beli']
							],
							"where" => [
								'Order_ID' => $post_header['Order_ID'], 
								'Barang_ID' => $row['Barang_ID']
							]
						];						
						$this->order_detail_model->update_by( $_order_detail['data'], $_order_detail['where'] );
						
						$curHarga = ($row['Harga_Beli'] + ($row['Harga_Beli'] * $row['Rate_Pajak'] / 100) ) / $row['Konversi'];
						$qtyTerima = $row['Qty_Penerimaan'] * $row['Konversi'];
						
						$_insert_fifo = [
							'location_id' => $post_header['Lokasi_ID'], 
							'item_id' => $row['Barang_ID'],  
							'item_unit_code' => $row['Kode_Satuan'],  
							'qty_in' => $qtyTerima, 
							'price' => $row['Harga_Beli'],  
							'conversion' => $row['Konversi'],  
							'evidence_number' => $post_header['No_Penerimaan'],  
							'trans_date' => $post_header['Tgl_Penerimaan'],  
							'exp_date' => $row['Exp_Date'],  
							'item_type_id' => $row['JenisBarangID'], 
						];					
						inventory_helper::insert_warehouse_fifo( $_insert_fifo );
						
						if ( $post_header['Order_ID'] != "" )
						{
							$_order_detail = [
								"data" => [
									"Qty_Penerimaan" => $row['Qty_Penerimaan'],
								],
								"where" => [
									'Order_ID' => $post_header['Order_ID'], 
									'Barang_ID' => $row['Barang_ID']
								]
							];
							$this->order_detail_model->update_by( $_order_detail['data'], $_order_detail['where'] );
						}
						
						$item_data = $this->item_model->get_one( $row['Barang_ID'] );
						if ( $row['Qty_Penerimaan'] > 0)
						{	
							$currentAveragePrice = (float) $item_data->HRataRata;
							$currentSalePrice = (float) $item_data->Harga_Jual;
							$currentStockQty = (float) $this->item_location_model->get_by( [ 'Lokasi_ID' => $post_header['Lokasi_ID'], 'Barang_ID' => $row['Barang_ID'] ] )->Qty_Stok;
							
							$averagePrice = ( $currentStockQty + $row['Qty_Penerimaan'] <= 0 )
											? $curHarga
											: (( $currentStockQty * $currentAveragePrice ) + ( $curHarga * $row['Qty_Penerimaan'])) / ( $currentStockQty + $row['Qty_Penerimaan'] );
							
							if(config_item('pakai_harga_tertinggi') == 'TRUE'){
								if ( $curHarga > $currentSalePrice ) // Cek harga tertinggi
								{
									$this->item_model->update( ['Harga_Jual' => $curHarga ], $row['Barang_ID'] );
								}
							}
							
							$_insert_price_change = [
								'location_id' => $post_header['Lokasi_ID'],
								'item_id' => $row['Barang_ID'],  
								'trans_date' => $post_header['Tgl_Penerimaan'], 
								'price' => $curHarga,  
							];
							inventory_helper::insert_price_change( $_insert_price_change );
							
							$_insert_supplier_item = [
								'supplier_id' => $post_header['Supplier_ID'],
								'item_id' => $row['Barang_ID'],  
								'trans_date' => $post_header['Tgl_Penerimaan'], 
								'price' => $row['Harga_Beli'],  
							];
							inventory_helper::insert_supplier_item( $_insert_supplier_item );
							
							$_update_item = [
								'Konversi' => $row['Konversi'],
								'HRataRata' => $averagePrice,
								'Harga_Beli' => $row['Harga_Beli'],
								'UserID' => $this->user_auth->User_ID,
								'DateUpdate' => $post_header['Tgl_Penerimaan']
							];
							
							$this->item_model->update( $_update_item, $row['Barang_ID'] );
						}
						
						$activities_description = sprintf( "Input Penerimaan Item. # %s # %s # %s # Qty : %s # Harga: %s # %s ", $post_header['No_Penerimaan'], $row['Barang_ID'], $item_data->Nama_Barang, $row['Qty_Penerimaan'], $row['Harga_Beli'], $additional['SectionName'] );
						insert_user_activity( $activities_description, $post_header['No_Penerimaan'], $this->goods_receipt_detail_model->table);
						
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
							"Penerimaan_ID" => $id,
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
		
		$this->data['collection'] = $this->goods_receipt_detail_model->get_all_by( NULL, [], $id );
		$this->data['order'] = $this->order_model->get_one( $item->Order_ID );
		$this->data['supplier'] = $this->supplier_model->get_one( $item->Supplier_ID );
		$this->data['dropdown_section'] = $this->section_model->for_dropdown();
		$this->data['form_action'] = $form_action = current_url();
		$this->data['cancel_url'] = base_url("{$this->nameroutes}/cancel/{$id}");
		$this->data['lookup_purchase_order'] = base_url("{$this->nameroutes}/lookup_purchase_order");		
		$this->data['is_edit'] = TRUE;		
		
		$this->template
			->title(lang('heading:goods_receipt'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:goods_receipt_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:goods_receipt'))
			->build("transactions/goods_receipt/form", $this->data);
	}
	
	public function view($id = 0)
	{
		$this->data['item'] = $item = $this->goods_receipt_model->get_one($id);
				
		$this->data['collection'] = $this->goods_receipt_detail_model->get_all_by( NULL, [], $id );
		$this->data['order'] = $this->order_model->get_one( $item->Order_ID );
		$this->data['supplier'] = $this->supplier_model->get_one( $item->Supplier_ID );
		$this->data['dropdown_section'] = $this->section_model->for_dropdown();
		$this->data['form_action'] = $form_action = current_url();
		$this->data['is_edit'] = TRUE;		
		
		$this->load->view("transactions/goods_receipt/view", $this->data);
	}
	
	public function print_factur($id)
	{
		$item = $this->goods_receipt_model->get_one($id);
		$collection = $this->goods_receipt_detail_model->get_all_by( NULL, [], $id );
		$user = $this->user_model->get_one($item->User_ID);
		$supplier = $this->supplier_model->get_one($item->Supplier_ID);
		$gudang = $this->section_model->get_by(['Lokasi_ID' => $item->Lokasi_ID]);

		$file_name = "FAKTUR PENERIMAAN BARANG";

		$data = array(
			"item" => $item,
			"collection" => $collection,
			"supplier" => $supplier,
			"file_name" => $file_name,
			"user" => $user,
			"gudang" => $gudang
		);

		$html_content =  $this->load->view("transactions/goods_receipt/print/print_faktur", $data, TRUE);

		$footer = "&nbsp;" . date("d M Y") . "&nbsp;" . date("H:i:s");
		$this->load->helper("export");
		export_helper::generate_pdf($html_content, $file_name, $footer, $margin_bottom = 5, $header = NULL, $margin_top = 2, 'A4',$orientation = 'L', $margin_left = 8, $margin_right = 8);

		exit(0);
	}

	public function cancel($id = 0)
	{
		$this->data['item'] = $item = $this->goods_receipt_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 
			if ( inventory_helper::check_closing_period( date('Y-m-d', strtotime($item->Tgl_Penerimaan)) ))
			{
				$response = array(
							"status" => 'error',
							"message" => lang('message:already_closing_period'),
							"code" => 500
						);
				
				response_json( $response );
			}
			
			$this->db->trans_begin();
				
				$this->goods_receipt_model->update( ['Status_Batal' => 1 ], $id );

				$activities_description = sprintf( "Batal Penerimaan. # %s # Ongkir : %s ", $item->No_Penerimaan, $item->Ongkos_Angkut  );
				insert_user_activity( $activities_description, $item->No_Penerimaan, $this->goods_receipt_model->table);
				
				$collection = $this->goods_receipt_detail_model->get_all_by( NULL, TRUE, $id );
				foreach( $collection as $row )
				{
					$stock_price = $row['Konversi'] > 0 
									? $row['Harga_Beli'] / $row['Konversi']
									: $row['Harga_Beli'];
					$stock_qty = $row['Konversi'] > 0 
									? $row['Qty_Penerimaan'] * $row['Konversi']
									: $row['Qty_Penerimaan'];
					
					$_insert_fifo = [
						'location_id' => $item->Lokasi_ID, 
						'item_id' => $row['Barang_ID'],  
						'item_unit_code' => $row['Kode_Satuan'],  
						'qty' => $stock_qty, 
						'price' => $stock_price,  
						'conversion' => $row['Konversi'],  
						'evidence_number' => $item->No_Penerimaan. "-R",  
						'trans_type_id' => 565,
						'in_out_state' => 0,
						'trans_date' => $item->Tgl_Penerimaan,  
						'exp_date' => $row['Exp_Date'],  
						'item_type_id' => $row['JenisBarangID'], 
					];					
					inventory_helper::insert_warehouse_fifo( $_insert_fifo );
					
					$_get_order_detail = $this->order_detail_model->get_by(['Order_ID' => $item->Order_ID, 'Barang_ID' => $row['Barang_ID']], TRUE);
					// return quantity to order
					$_order_detail = [
							"data" => [
								"Qty_Tlh_Dibeli" => $_get_order_detail['Qty_Tlh_Dibeli'] - $row['Qty_Penerimaan'],
								"Qty_Penerimaan" => $_get_order_detail['Qty_Penerimaan'] - $row['Qty_Penerimaan'],
							],
							"where" => [
								'Order_ID' => $item->Order_ID, 
								'Barang_ID' => $row['Barang_ID']
							]
						];										
					$this->order_detail_model->update_by( $_order_detail['data'], $_order_detail['where'] );
					
					$activities_description = sprintf( "Batal Penerimaan Item. # %s # %s # %s # Qty : %s # Harga: %s", $item->No_Penerimaan, $row['Barang_ID'], $row['Nama_Barang'], $row['Qty_Penerimaan'], $row['Harga_Beli'] );
					insert_user_activity( $activities_description, $item->No_Penerimaan, $this->goods_receipt_detail_model->table);
					
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
		
		$this->data['form_action'] = $form_action = current_url();
		$this->load->view('transactions/goods_receipt/modal/cancel', $this->data);
	}
	
	public function update_detail($Penerimaan_ID = 0, $Barang_ID = 0, $OutStok = 0)
	{
		$this->data['item'] = $item = $this->goods_receipt_detail_model->get_by(['Penerimaan_ID' => $Penerimaan_ID, 'Barang_ID' => $Barang_ID]);
		
		if( $this->input->post('confirm') == 1 )
		{
		
			/*$this->form_validation->set_rules($this->purchase_request_model->rules['insert']);
			$this->form_validation->set_data($post_header);*/
			
			if( !$this->form_validation->run())
			{								
				$this->db->trans_begin();
				
				 $this->goods_receipt_detail_model->update_by(['OutStok' => $OutStok], ['Penerimaan_ID' => $Penerimaan_ID, 'Barang_ID' => $Barang_ID]);
				
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
							"status" => 'success',
							"message" => lang('global:updated_successfully'),
							"code" => 200,
							"success" => TRUE,
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
		
		$this->data['form_action'] = $form_action = current_url();
		$this->load->view('transactions/goods_receipt/detail/update', $this->data);
	}
	
	/*
		@params
		(String) id -> Order ID
	*/
	private function _prepare_open_detail_collection( $id )
	{
		$_get_collection = $this->order_detail_model->get_all_by( NULL, FALSE, $id);

		$collection = [];
		foreach ( $_get_collection as $row )
		{
			$Qty_Penerimaan = $row->Qty_Order - $row->Qty_Tlh_Dibeli;
			$CN_Faktur = (float) $row->CN_Faktur;
			$Diskon_Rp = (float) $row->Harga_Order * $Qty_Penerimaan * $row->CN_Faktur / 100;
			$sub_total = (float) ($row->Harga_Order * $Qty_Penerimaan) - ($row->Harga_Order * $Qty_Penerimaan * $row->CN_Faktur / 100);
			$add = [
				"Barang_ID"	=> $row->Barang_ID,
				"Kode_Barang" => $row->Kode_Barang,
				"Nama_Barang" => $row->Nama_Barang,
				"Kode_Satuan" => $row->Kode_Satuan,
				"Qty_PO" => (float) $row->Qty_Order,
				"Qty_Telah_Terima" => (float) $row->Qty_Tlh_Dibeli,
				"Qty_Penerimaan" => $Qty_Penerimaan,
				"Harga_Beli" => (float) $row->Harga_Order,
				"Diskon_1" => $CN_Faktur,
				"Diskon_Rp" => $Diskon_Rp,
				"sub_total" => $sub_total,
				"Exp_Date" => date('Y-m-d'),
				"NoBatch" => NULL,
				"JenisBarangID" => $row->JenisBarangID,
				"Konversi" => $row->Konversi
			];		
			
			$collection[] = $add;
		}
		
		return $collection;
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
		
		$db_from = "{$this->order_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter
		$db_where_exists = $this->db->from("{$this->order_detail_model->table} b")
									->where("a.Order_ID", "b.Order_ID", FALSE)
									->where("b.Qty_Order >", "b.Qty_Penerimaan", FALSE)
									->get_compiled_select();
									
		$db_where['a.Status_Batal'] = 0;

		if( $this->input->post("date_from") ){
			$db_where['a.Tgl_Order >='] = $this->input->post("date_from");
		}

		if( $this->input->post("date_till") ){
			$db_where['a.Tgl_Order <='] = $this->input->post("date_till");
		}		

		if( $this->input->post("Gudang_ID") ){
			$db_where['c.Gudang_ID'] = $this->input->post("Gudang_ID");
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.No_Order") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Tgl_Order") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Nama_Supplier") ] = $keywords;
			 
        }
		
		// get total records
		$this->db->from( $db_from )
				->join("{$this->purchase_request_model->table} c", "a.No_Order = c.No_Permintaan", "INNER");
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->supplier_model->table} b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER")
			->join("{$this->purchase_request_model->table} c", "a.No_Order = c.No_Permintaan", "INNER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_where_exists) ){ $this->db->where( "EXISTS ({$db_where_exists})", NULL, FALSE ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.Kode_Supplier,
			b.Nama_Supplier,
			c.Tgl_Dibutuhkan AS Tgl_JatuhTempo,
			c.Gudang_ID,
			c.Keterangan
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->supplier_model->table} b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER")
			->join("{$this->purchase_request_model->table} c", "a.No_Order = c.No_Permintaan", "INNER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_where_exists) ){ $this->db->where( "EXISTS ({$db_where_exists})", NULL, FALSE ); }
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

	public function datatable_realization()	
    {
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->goods_receipt_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter								
		//$db_where['a.Status_Batal'] = 0;

		if( $this->input->post("date_from") ){
			$db_where['a.Tgl_Penerimaan >='] = $this->input->post("date_from");
		}

		if( $this->input->post("date_till") ){
			$db_where['a.Tgl_Penerimaan <='] = $this->input->post("date_till");
		}		

		if( $this->input->post("Gudang_ID") ){
			$db_where['a.Lokasi_ID'] = $this->input->post("Gudang_ID");
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.No_Penerimaan") ] = $keywords;
			$db_like[ $this->db->escape_str("a.No_DO") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Tgl_Penerimaan") ] = $keywords;
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
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.Kode_Supplier,
			b.Nama_Supplier
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->supplier_model->table} b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER")
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
	
	public function datatable_detail()	
    {
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->goods_receipt_detail_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter								
		$db_where['b.Status_Batal'] = 0;

		if( $this->input->post("date_from") ){
			$db_where['b.Tgl_Penerimaan >='] = $this->input->post("date_from");
		}

		if( $this->input->post("date_till") ){
			$db_where['b.Tgl_Penerimaan <='] = $this->input->post("date_till");
		}		

		if( $this->input->post("Gudang_ID") ){
			$db_where['b.Lokasi_ID'] = $this->input->post("Gudang_ID");
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("d.Nama_Barang") ] = $keywords;
			$db_like[ $this->db->escape_str("b.No_Penerimaan") ] = $keywords;
			$db_like[ $this->db->escape_str("c.Nama_Supplier") ] = $keywords;
			 
        }
		
		// get total records
		$this->db->from( $db_from )	
			->join("{$this->goods_receipt_model->table} b", "a.Penerimaan_ID = b.Penerimaan_ID", "LEFT OUTER")
			->join("{$this->supplier_model->table} c", "b.Supplier_ID = c.Supplier_ID", "LEFT OUTER")
			->join("{$this->item_model->table} d", "a.Barang_ID = d.Barang_ID", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->goods_receipt_model->table} b", "a.Penerimaan_ID = b.Penerimaan_ID", "LEFT OUTER")
			->join("{$this->supplier_model->table} c", "b.Supplier_ID = c.Supplier_ID", "LEFT OUTER")
			->join("{$this->item_model->table} d", "a.Barang_ID = d.Barang_ID", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.No_Penerimaan,
			b.Tgl_Penerimaan,
			b.No_DO,
			d.Nama_Barang,
			c.Kode_Supplier,
			c.Nama_Supplier
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->goods_receipt_model->table} b", "a.Penerimaan_ID = b.Penerimaan_ID", "LEFT OUTER")
			->join("{$this->supplier_model->table} c", "b.Supplier_ID = c.Supplier_ID", "LEFT OUTER")
			->join("{$this->item_model->table} d", "a.Barang_ID = d.Barang_ID", "LEFT OUTER")
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
			$row->Tgl_Penerimaan = DateTime::createFromFormat('Y-m-d H:i:s', $row->Tgl_Penerimaan)->format("d/M/Y");
			$row->Tgl_Expired = !empty($row->Exp_Date) ? DateTime::createFromFormat('Y-m-d H:i:s.u', $row->Exp_Date)->format("d/M/Y") : ''; 
            $output['data'][] = $row;
        }
		
		response_json( $output );
    }
	
	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/goods_receipt/lookup/lookup_supplier' );
		}
	}
	
	public function lookup_purchase_order( $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/goods_receipt/lookup/lookup_purchase_order', $this->data );
		}
	}

	public function lookup_item( $is_ajax_request=false )
	{
		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/goods_receipt/lookup/lookup_item' );
		}
	}
	
	public function lookup_item_konsinyasi( $is_ajax_request=false )
	{
		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/goods_receipt/lookup/lookup_item_konsinyasi' );
		}
	}
}

