<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inquiry extends Admin_Controller
{
	protected $_translation = 'inquiry';	
	protected $_model = 'inquiry_m';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->page = "inquiry";
		$this->template->title( "Amprahan" . ' - ' . $this->config->item('company_name') );

		$this->load->model( "common/patient_m" );
		$this->load->model( "common/patient_type_m" );
		$this->load->model( "common/zone_m" );
		$this->load->model( "common/nationality_m" );
		$this->load->model( "common/supplier_m" );
		$this->load->model( "common/supplier_specialist_m" );
		$this->load->model( "common/section_m" );
		$this->load->model( "common/time_m" );
				
		$this->load->helper( "inquiry" );
		$this->load->helper( "common/patient" );
		$this->load->helper( "common/zone" );

	}
	
	public function _list(  $SectionID = null  )
	{
		$section = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $SectionID));
		
		$data = array(
				'page' => $this->page,
				'section' => $section,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		$this->template
			->set( "heading", "List Amprahan" )
			->set_breadcrumb( lang("inquiry:breadcrumb") )
			->set_breadcrumb( "Amprahan", current_url() )
			->build('inquiries/datatable', (isset($data) ? $data : NULL));
	}
	
	public function request_( $SectionID = null )
	{

		/*$item = $this->db->where("NoResep", $NoResep)->get( "SIMtrResep" )->row_array();
		$inquiry = $this->inquiry_m->get_inquiry_data( array("NoReg" => $NoReg, "SectionID" => "SEC002"));
		$patient = $this->inquiry_m->get_patient( $item['NRM'] );
		$cooperation = $this->inquiry_m->get_customer( array("Kode_Customer" => $item['KodePerusahaan']) );*/

		$item = array(
			'NoBukti' => inquiry_helper::gen_evidence_number( $SectionID ),
			'Tanggal' => date("Y-m-d"),
			'SectionAsal' => $SectionID,
		);
	
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			$data = $this->input->post();
					
			$amprahan = $this->input->post("amprahan");
			$amprahan["UserID"] = $this->user_auth->User_ID; // tambhakan data user id			

			$amprahan_detail = $this->input->post("amprahan_detail");

			$this->load->library( 'form_validation' );
			$this->item->addData( $this->input->post("amprahan") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);

			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
										
					$this->db->insert("GD_trAmprahan", $amprahan );				
					$this->db->insert_batch("GD_trAmprahanDetail", $amprahan_detail );
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
							"NoBukti" => $amprahan['NoBukti'],
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
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK) );
			exit(0);
			
		}

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $SectionID));
		
		$option_section_from = $this->inquiry_m->get_options("SIMmSection", array("TipePelayanan" => $section_from->TipePelayanan, "StatusAktif" => 1), array("by" => "SectionName", "sort" => "ASC"));
		$option_section_pharmacy = $this->inquiry_m->get_option_section_pharmacy();

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object)$this->item->toArray(),
					"patient" => @$patient,
					"cooperation" => @$cooperation,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => (object) @$this->item->toArray(),
					"inquiry" => @$inquiry,
					"section" => @$section_from,
					"option_section_from" => $option_section_from,
					"option_section_pharmacy" => $option_section_pharmacy,
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_products" => base_url("inquiry/inquiries/details/lookup_product"),
					"create_url" => base_url("inquiry/request_/{$section_from->SectionID}"),
				);
			//print_r($this->inquiry_m->get_option_patient_type());exit;
			
			$this->template
				->build('inquiries/form', $data);
		}
	}

	public function mutation_( $SectionID = null )
	{
		$this->page = "mutation";

		/*$item = $this->db->where("NoResep", $NoResep)->get( "SIMtrResep" )->row_array();
		$inquiry = $this->inquiry_m->get_inquiry_data( array("NoReg" => $NoReg, "SectionID" => "SEC002"));
		$patient = $this->inquiry_m->get_patient( $item['NRM'] );
		$cooperation = $this->inquiry_m->get_customer( array("Kode_Customer" => $item['KodePerusahaan']) );*/

		$item = array(
			'NoBukti' => inquiry_helper::gen_mutation_evidence_number( $SectionID ),
			'Tanggal' => date("Y-m-d"),
			'SectionAsal' => $SectionID,
		);
	
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			$data = $this->input->post();
			
			//print_r($data);exit;
					
			$mutasi = $this->input->post("mutasi");
			$mutasi_detail = $this->input->post("mutasi_detail");

			$this->load->library( 'form_validation' );
			$this->item->addData( $this->input->post("mutasi") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);

			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
										
					$this->db->insert("GD_trMutasi", $mutasi );				
					$this->db->insert_batch("GD_trMutasiDetail", $mutasi_detail );

					foreach( $mutasi_detail as $k => $v )
					{
						// Mangambil total stok terakhir yang ada pada kartu gudang
						$qty_last_stock = $this->inquiry_m->get_last_stock_warehouse_card( array("Lokasi_ID" => $mutasi['Lokasi_Asal'], "Barang_ID" => $v["Barang_ID"]) );
						$qty_saldo = $qty_last_stock - $v["Qty"];
						$kartu_gudang_out = array( // Data untuk Mutasi Out
								"Lokasi_ID" => $mutasi['Lokasi_Asal'],
								"Barang_ID" => $v["Barang_ID"],
								"No_Bukti" => $v["No_Bukti"],
								"JTransaksi_ID" => 521,
								"Tgl_Transaksi" => $mutasi["Tgl_Mutasi"],
								"Kode_Satuan" => $v["Kode_Satuan"],
								"Qty_Masuk" => 0,
								"Harga_Masuk" =>  0,
								"Qty_Keluar" => $v["Qty"],
								"Harga_Keluar" => $v["Harga"],
								"Qty_Saldo" => $qty_saldo,
								"Harga_Persediaan" => $v["Harga"],
								"Jam" => date("Y-m-d H:i:s"),
						);
						$this->db->insert("GD_trKartuGudang", $kartu_gudang_out );

						$qty_last_stock = $this->inquiry_m->get_last_stock_warehouse_card( array("Lokasi_ID" => $mutasi['Lokasi_Tujuan'], "Barang_ID" => $v["Barang_ID"]) );
						$qty_saldo = $qty_last_stock + $v["Qty"];
						$kartu_gudang_in = array( // data untuk Mutasi in
								"Lokasi_ID" => $mutasi['Lokasi_Tujuan'],
								"Barang_ID" => $v["Barang_ID"],
								"No_Bukti" => $v["No_Bukti"],
								"JTransaksi_ID" => 520,
								"Tgl_Transaksi" => $mutasi["Tgl_Mutasi"],
								"Kode_Satuan" => $v["Kode_Satuan"],
								"Qty_Masuk" => $v["Qty"],
								"Harga_Masuk" => $v["Harga"],
								"Qty_Keluar" => 0,
								"Harga_Keluar" => 0,
								"Qty_Saldo" => $qty_saldo,
								"Harga_Persediaan" => $v["Harga"],
								"Jam" => date("Y-m-d H:i:s"),
						);
						$this->db->insert("GD_trKartuGudang", $kartu_gudang_in );	

						//Update data Pada GD_trAmprahanDetail
						$this->db->update("GD_trAmprahanDetail", array("Realisasi" => 1, "QtyRealisasiPertama" => $v["Qty"] ), array("NoBukti" => $mutasi['NoAmprahan'], "Barang_ID" => $v["Barang_ID"] ));
					}
					
					//Update data Pada GD_trAmprahan
					$this->db->update("GD_trAmprahan", array("Realisasi" => 1, "JamUpdate" => date("Y-m-d H:i:s")), array("NoBukti" => $mutasi['NoAmprahan']));
										
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
							"No_Bukti" => $mutasi['No_Bukti'],
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
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK) );
			exit(0);
			
		}

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $SectionID));
		
		$option_section_from = $this->inquiry_m->get_options("SIMmSection", array("TipePelayanan" => $section_from->TipePelayanan, "StatusAktif" => 1), array("by" => "SectionName", "sort" => "ASC"));
		$option_section_pharmacy = $this->inquiry_m->get_option_section_pharmacy();

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object)$this->item->toArray(),
					"patient" => @$patient,
					"cooperation" => @$cooperation,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => (object) @$this->item->toArray(),
					"inquiry" => @$inquiry,
					"section" => @$section_from,
					"user" => $this->simple_login->get_user(),
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_inquiry" => base_url("inquiry/lookup_inquiry"),
					"lookup_products" => base_url("inquiry/inquiries/details/lookup_product"),
					"create_url" => base_url("inquiry/mutation_/{$section_from->SectionID}"),
				);
			
			$this->template
				->title('Mutasi')
				->build('inquiries/form_mutation', $data);
		}
	}

	public function mutation_view_( $mutation_number ,$SectionID = null )
	{
		$this->page = "mutation";
		
		$item = inquiry_helper::get_mutation( $mutation_number );

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $SectionID));
		$option_section_from = $this->inquiry_m->get_options("SIMmSection", array("TipePelayanan" => $section_from->TipePelayanan, "StatusAktif" => 1), array("by" => "SectionName", "sort" => "ASC"));
		$option_section_pharmacy = $this->inquiry_m->get_option_section_pharmacy();

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $item,
					"patient" => @$patient,
					"cooperation" => @$cooperation,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{

			$controller = $this->uri->segment(2);

			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					'item' => $item,
					"inquiry" => @$inquiry,
					"section" => @$section_from,
					"user" => $this->simple_login->get_user(),
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_inquiry" => base_url("inquiry/lookup_inquiry"),
					"lookup_products" => base_url("inquiry/inquiries/details/lookup_product"),
					"create_url" => base_url("inquiry/{$controller}/mutation"),
				);
			
			$this->template
				->title('Mutasi')
				->build('inquiries/form_mutation_view', $data);
		}
	}
		
	public function mutation_return_( $SectionID = null )
	{

		$this->page = "mutation_return";

		$item = array(
			'NoBukti' => inquiry_helper::gen_mutation_return_evidence_number( $SectionID ),
			'Tanggal' => date("Y-m-d"),
			'SectionAsal' => $SectionID,
		);
	
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			$data = $this->input->post();
			
			//print_r($data);exit;
					
			$retur_mutasi = $this->input->post("retur_mutasi");
			$retur_mutasi_detail = $this->input->post("retur_mutasi_detail");

			$this->load->library( 'form_validation' );
			$this->item->addData( $this->input->post("retur_mutasi") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);

			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
										
					$this->db->insert("GD_trReturMutasi", $retur_mutasi );				
					$this->db->insert_batch("GD_trReturMutasiDetail", $retur_mutasi_detail );

					foreach( $retur_mutasi_detail as $k => $v )
					{
						// Mangambil total stok terakhir yang ada pada kartu gudang
						$qty_last_stock = $this->inquiry_m->get_last_stock_warehouse_card( array("Lokasi_ID" => $retur_mutasi['Lokasi_Asal'], "Barang_ID" => $v["Barang_ID"]) );
						$qty_saldo = $qty_last_stock - $v["Qty"];
						$kartu_gudang_out = array( // Data untuk Mutasi Out
								"Lokasi_ID" => $retur_mutasi['Lokasi_Asal'],
								"Barang_ID" => $v["Barang_ID"],
								"No_Bukti" => $v["No_Bukti"],
								"JTransaksi_ID" => 566,
								"Tgl_Transaksi" => $retur_mutasi["Tgl_Mutasi"],
								"Kode_Satuan" => $v["Kode_Satuan"],
								"Qty_Masuk" => 0,
								"Harga_Masuk" =>  0,
								"Qty_Keluar" => $v["Qty"],
								"Harga_Keluar" => $v["Harga"],
								"Qty_Saldo" => $qty_saldo,
								"Harga_Persediaan" => $v["Harga"],
								"Jam" => date("Y-m-d H:i:s"),
						);
						$this->db->insert("GD_trKartuGudang", $kartu_gudang_out );

						$qty_last_stock = $this->inquiry_m->get_last_stock_warehouse_card( array("Lokasi_ID" => $retur_mutasi['Lokasi_Tujuan'], "Barang_ID" => $v["Barang_ID"]) );
						$qty_saldo = $qty_last_stock + $v["Qty"];
						$kartu_gudang_in = array( // data untuk Mutasi in
								"Lokasi_ID" => $retur_mutasi['Lokasi_Tujuan'],
								"Barang_ID" => $v["Barang_ID"],
								"No_Bukti" => $v["No_Bukti"],
								"JTransaksi_ID" => 566,
								"Tgl_Transaksi" => $retur_mutasi["Tgl_Mutasi"],
								"Kode_Satuan" => $v["Kode_Satuan"],
								"Qty_Masuk" => $v["Qty"],
								"Harga_Masuk" => $v["Harga"],
								"Qty_Keluar" => 0,
								"Harga_Keluar" => 0,
								"Qty_Saldo" => $qty_saldo,
								"Harga_Persediaan" => $v["Harga"],
								"Jam" => date("Y-m-d H:i:s"),
						);
						$this->db->insert("GD_trKartuGudang", $kartu_gudang_in );	
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
							"No_Bukti" => $mutasi['No_Bukti'],
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
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK) );
			exit(0);
			
		}

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $SectionID));
		
		$option_section_from = $this->inquiry_m->get_options("SIMmSection", array("TipePelayanan" => "RJ", "StatusAktif" => 1), array("by" => "SectionName", "sort" => "ASC"));
		$option_section_pharmacy = $this->inquiry_m->get_option_section_pharmacy();

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object)$this->item->toArray(),
					"patient" => @$patient,
					"cooperation" => @$cooperation,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{
			
			
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => (object) @$this->item->toArray(),
					"inquiry" => @$inquiry,
					"section" => @$section_from,
					"option_section_from" => $option_section_from,
					"user" => $this->simple_login->get_user(),
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_inquiry" => base_url("inquiry/lookup_inquiry"),
					"lookup_products" => base_url("inquiry/inquiries/details/lookup_product"),
					"create_url" => base_url("inquiry/mutation_return_/{$section_from->SectionID}"),
				);
			
			$this->template
				->title('Retur Mutasi')
				->build('inquiries/form_mutation_return', $data);
		}
	}

	public function mutation_return_view_( $mutation_return_number, $SectionID = null )
	{

		$this->page = "mutation_return";
		
		$item = inquiry_helper::get_mutation_return( $mutation_return_number );

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $SectionID));
		
		$option_section_from = $this->inquiry_m->get_options("SIMmSection", array("TipePelayanan" => "RJ", "StatusAktif" => 1), array("by" => "SectionName", "sort" => "ASC"));
		$option_section_pharmacy = $this->inquiry_m->get_option_section_pharmacy();

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{
			
			$controller = $this->uri->segment(2);
			
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
					"section" => @$section_from,
					"option_section_from" => $option_section_from,
					"user" => $this->simple_login->get_user(),
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_inquiry" => base_url("inquiry/lookup_inquiry"),
					"lookup_products" => base_url("inquiry/inquiries/details/lookup_product"),
					"create_url" => base_url("inquiry/{$controller}/mutation_return"),
				);
			
			$this->template
				->title('Retur Mutasi')
				->build('inquiries/form_mutation_return_view', $data);
		}
	}
	
	public function stock_opname_( $SectionID = null )
	{

		$this->page = "stock_opname";
		/*$item = $this->db->where("NoResep", $NoResep)->get( "SIMtrResep" )->row_array();
		$inquiry = $this->inquiry_m->get_inquiry_data( array("NoReg" => $NoReg, "SectionID" => "SEC002"));
		$patient = $this->inquiry_m->get_patient( $item['NRM'] );
		$cooperation = $this->inquiry_m->get_customer( array("Kode_Customer" => $item['KodePerusahaan']) );*/

		$item = array(
			'No_Bukti' => inquiry_helper::gen_opname_evidence_number(),
			'Tanggal' => date("Y-m-d"),
			'SectionAsal' => $SectionID,
		);
	
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			$data = $this->input->post();
			
			//print_r($data);exit;
					
			$opname = $this->input->post("opname");
			$opname_detail = $this->input->post("opname_detail");

			$this->load->library( 'form_validation' );
			$this->item->addData( $this->input->post("opname") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);

			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
										
					$this->db->insert("GD_trOpname", $opname );				
					$this->db->insert_batch("GD_trOpnameDetail", $opname_detail );

					/*foreach( $retur_mutasi_detail as $k => $v )
					{
						// Mangambil total stok terakhir yang ada pada kartu gudang
						$qty_last_stock = $this->inquiry_m->get_last_stock_warehouse_card( array("Lokasi_ID" => $retur_mutasi['Lokasi_Asal'], "Barang_ID" => $v["Barang_ID"]) );
						$qty_saldo = $qty_last_stock - $v["Qty"];
						$kartu_gudang_minus = array( // Data untuk Mutasi Out
								"Lokasi_ID" => $retur_mutasi['Lokasi_Asal'],
								"Barang_ID" => $v["Barang_ID"],
								"No_Bukti" => $v["No_Bukti"],
								"JTransaksi_ID" => 566,
								"Tgl_Transaksi" => $retur_mutasi["Tgl_Mutasi"],
								"Kode_Satuan" => $v["Kode_Satuan"],
								"Qty_Masuk" => 0,
								"Harga_Masuk" =>  0,
								"Qty_Keluar" => $v["Qty"],
								"Harga_Keluar" => $v["Harga"],
								"Qty_Saldo" => $qty_saldo,
								"Harga_Persediaan" => $v["Harga"],
								"Jam" => date("Y-m-d H:i:s"),
						);
						$this->db->insert("GD_trKartuGudang", $kartu_gudang_out );

						$qty_last_stock = $this->inquiry_m->get_last_stock_warehouse_card( array("Lokasi_ID" => $retur_mutasi['Lokasi_Tujuan'], "Barang_ID" => $v["Barang_ID"]) );
						$qty_saldo = $qty_last_stock + $v["Qty"];
						$kartu_gudang_plus = array( // data untuk Mutasi in
								"Lokasi_ID" => $retur_mutasi['Lokasi_Tujuan'],
								"Barang_ID" => $v["Barang_ID"],
								"No_Bukti" => $v["No_Bukti"],
								"JTransaksi_ID" => 566,
								"Tgl_Transaksi" => $retur_mutasi["Tgl_Mutasi"],
								"Kode_Satuan" => $v["Kode_Satuan"],
								"Qty_Masuk" => $v["Qty"],
								"Harga_Masuk" => $v["Harga"],
								"Qty_Keluar" => 0,
								"Harga_Keluar" => 0,
								"Qty_Saldo" => $qty_saldo,
								"Harga_Persediaan" => $v["Harga"],
								"Jam" => date("Y-m-d H:i:s"),
						);
						$this->db->insert("GD_trKartuGudang", $kartu_gudang_in );	
					}*/
															
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
							"No_Bukti" => $opname['No_Bukti'],
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
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK) );
			exit(0);
			
		}

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $SectionID));
		
		$option_kelompok_jenis_obat = $this->inquiry_m->get_options("SIMmKelompokJenisObat", array("Kelompok" => "OBAT"), array("by" => "KelompokJenis", "sort" => "ASC"));

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object)$this->item->toArray(),
					"patient" => @$patient,
					"cooperation" => @$cooperation,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => (object) @$this->item->toArray(),
					"inquiry" => @$inquiry,
					"section" => @$section_from,
					"option_kelompok_jenis_obat" => $option_kelompok_jenis_obat,
					"user" => $this->simple_login->get_user(),
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_inquiry" => base_url("inquiry/lookup_inquiry"),
					"lookup_product_opname" => base_url("inquiry/inquiries/detail_opnames/lookup_product_opname"),
					"create_url" => base_url("inquiry/stock_opname_/{$section_from->SectionID}"),
				);
			//print_r($data);exit;
			
			$this->template
				->title('Stock Opname')
				->build('inquiries/form_stock_opname', $data);
		}
	}

	public function stock_opname_view_( $SectionID = null, $No_Bukti )
	{
		$this->page = "stock_opname";

		$item = $this->db->where("No_Bukti", $No_Bukti)->get( "GD_trOpname" )->row_array();	

		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			$data = $this->input->post();
			
			//print_r($data);exit;
					
			$opname = $this->input->post("opname");
			$opname_detail = $this->input->post("opname_detail");

			$this->load->library( 'form_validation' );
			$this->item->addData( $this->input->post("opname") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);

			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
					$this->db->delete("GD_trOpnameDetail", array("No_Bukti" => $No_Bukti) );
					$this->db->insert_batch("GD_trOpnameDetail", $opname_detail );

					if ( empty($this->input->post("Posted")))
					{		
						$this->db->update("GD_trOpname", array("Tgl_Update" => date("Y-m-d H:i:s")), array("No_Bukti" => $No_Bukti));										
					}

					if ( $this->input->post("Posted") == 1)
					{		
						$this->db->update("GD_trOpname", array("Posted" => 1, "Tgl_Update" => date("Y-m-d H:i:s")), array("No_Bukti" => $No_Bukti));										

						foreach( $opname_detail as $k => $v )
						{
							// Mangambil total stok terakhir yang ada pada kartu gudang
							$selisih = $v['Stock_Akhir'] - $v['Qty_Opname'];
							
							$qty_last_stock = $this->inquiry_m->get_last_stock_warehouse_card( array("Lokasi_ID" => $opname['Lokasi_ID'], "Barang_ID" => $v["Barang_ID"]) );
							
							if ($v['Stock_Akhir'] < $v['Qty_Opname'])
							{
								$qty_saldo = $qty_last_stock + abs($selisih);
								$kartu_gudang = array( // Data untuk Opaname Plus
										"Lokasi_ID" => $opname['Lokasi_ID'],
										"Barang_ID" => $v["Barang_ID"],
										"No_Bukti" => $v["No_Bukti"],
										"JTransaksi_ID" => 560,
										"Tgl_Transaksi" => $opname["Tgl_Opname"],
										"Kode_Satuan" => $v["Kode_Satuan"],
										"Qty_Masuk" => abs($selisih),
										"Harga_Masuk" => $v["Harga_Rata"],
										"Qty_Keluar" => 0,
										"Harga_Keluar" => 0,
										"Qty_Saldo" => $qty_saldo,
										"Harga_Persediaan" => $v["Harga_Rata"],
										"Jam" => date("Y-m-d H:i:s"),
								);
								// insert ke kartu gudang
								$this->db->insert("GD_trKartuGudang", $kartu_gudang );	
							}
	
							if ($v['Stock_Akhir'] > $v['Qty_Opname']) 
							{
								$qty_saldo = $qty_last_stock - abs($selisih);
								$kartu_gudang = array( // data untuk Opname Minus
										"Lokasi_ID" => $opname['Lokasi_ID'],
										"Barang_ID" => $v["Barang_ID"],
										"No_Bukti" => $v["No_Bukti"],
										"JTransaksi_ID" => 561,
										"Tgl_Transaksi" => $opname["Tgl_Opname"],
										"Kode_Satuan" => $v["Kode_Satuan"],
										"Qty_Masuk" => 0,
										"Harga_Masuk" => 0,
										"Qty_Keluar" => abs($selisih),
										"Harga_Keluar" => $v["Harga_Rata"],
										"Qty_Saldo" => $qty_saldo,
										"Harga_Persediaan" => $v["Harga_Rata"],
										"Jam" => date("Y-m-d H:i:s"),
								);
								// insert ke kartu gudang
								$this->db->insert("GD_trKartuGudang", $kartu_gudang );	
							}
							
							// Update tanggal expired di mBarangLokasiNew, Jika Terdapat data Tanggal expired
							if ( !empty($v["Tgl_Expired"]))
							{
								$this->db->update("mBarangLokasiNew", array("Tgl_Expired" => $v["Tgl_Expired"]), array("Lokasi_ID" => $opname['Lokasi_ID'], "Barang_ID" => $v["Barang_ID"]) );
							}
							
						}
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
							"No_Bukti" => $opname['No_Bukti'],
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
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK) );
			exit(0);
			
		}

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $SectionID));
		
		$option_kelompok_jenis_obat = $this->inquiry_m->get_options("SIMmKelompokJenisObat", array("Kelompok" => "OBAT"), array("by" => "KelompokJenis", "sort" => "ASC"));

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object)$this->item->toArray(),
					"patient" => @$patient,
					"cooperation" => @$cooperation,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{

			$controller = $this->uri->segment(2);

			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => (object) @$this->item->toArray(),
					"section" => @$section_from,
					"option_kelompok_jenis_obat" => $option_kelompok_jenis_obat,
					"user" => $this->simple_login->get_user(),
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_inquiry" => base_url("inquiry/lookup_inquiry"),
					"lookup_product_opname" => base_url("inquiry/inquiries/detail_opnames/lookup_product_opname"),
					"create_url" => base_url("inquiry/{$controller}/stock_opname"),
				);
			
			$this->template
				->title('Detail Stock Opname')
				->build('inquiries/form_stock_opname_view', $data);
		}
	}
				
	public function edit( $NoReg=0 )
	{
		
		$item = $this->db->where("NoReg", $NoReg)->get( $this->inquiry_m->table )->row_array();
		$patient = $this->inquiry_m->get_patient( $item['NRM'] );
		$section_destination = $this->inquiry_m->get_section_destination( $item['NoReg'] );
		$cooperation = $this->inquiry_m->get_customer( array("Kode_Customer" => $item['KodePerusahaan']) ); // Perusahaan Kerja sama
		$second_insurer = $this->inquiry_m->get_customer( array("Kode_Customer" => $item['PertanggunganKeduaCompanyID']) ); // Pertanggungan Kedua (IKS)
		
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			if( $this->form_validation->run() )
			{
				if( $this->get_model()->update( $this->item->toArray(), @$id ) )
				{
					$this->get_model()->delete_cache( 'common_inquirys.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'common/inquirys' );
				} else
				{
					make_flashdata(array(
							'response_status' => 'error',
							'message' => lang('global:updated_failed')
						));
				}
			} else
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					));
			}
		}
		
		$option_patient_type = $this->inquiry_m->get_option_patient_type();
		$option_dosis = $this->inquiry_m->get_options("SIMmDosisObat", array(), array("by" => "KodeDosis", "sort" => "ASC"));

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $this->item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => (object)$this->item->toArray(),
					"patient" => $patient,
					"option_dosis" => $option_dosis,
					"lookup_supplier" => base_url("inquiry/lookup_supplier"),
					"lookup_cooperation" => base_url("inquiry/lookup_cooperation"),
					"form" => TRUE,
					"datatables" => TRUE,
					"is_edit" => TRUE,
				);
		
			$this->template
				->set( "heading", lang("inquiry:edit_heading") )
				->set_breadcrumb( lang("inquiry:breadcrumb"), base_url("inquirys") )
				->set_breadcrumb( lang("inquiry:edit_heading") )
				->build('inquiries/form', $data);
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
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( $this->item->id == $this->input->post( 'confirm' ) )
			{
				$this->get_model()->where( $id )->delete();				
				
				$this->get_model()->delete_cache( 'common_inquirys.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'inquiries/modal/delete', array('item' => $this->item) );
	}
	
	public function _lookup( $SectionID = null )
	{
		if( $this->input->is_ajax_request() )
		{
			$data = array(
				"SectionID" => $SectionID
			);
			$this->load->view( 'inquiries/lookup/datatable', $data );
		} else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("inquirys") )
				->set_breadcrumb( "Lookup Box" )
				->build('inquiries/lookup', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_product( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'inquiries/lookup/products' );
		} 
	}

	public function lookup_inquiry( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'inquiries/lookup/inquiries' );
		} 
	}
	
	public function lookup_collection()
	{
		$this->datatable_collection( 1 );
	}

	public function datatable_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->inquiry_m->table} a";
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

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionAsal = b.SectionID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionTujuan = c.SectionID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionAsal = b.SectionID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionTujuan = c.SectionID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
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
			a.Keterangan,
			d.Nama_Singkat
			
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionAsal = b.SectionID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionTujuan = c.SectionID", "LEFT OUTER" )
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
			
        $is_mutation = $this->input->post("mutation");
        foreach($result as $row)
        {	
			if ( $is_mutation )
			{
				$row->detail = $this->inquiry_m->get_inquiry_detail( $row->NoBukti );
			}		
			
            $output['data'][] = $row;
        }
		
		//print_r($output);exit;
		
		$this->template
			->build_json( $output );
    }

	public function datatable_opname()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		//$db_from = "{$this->inquiry_m->table} a";
		$db_from = "GD_trOpname a";
		$db_where = array();
		$db_like = array();
		
		if ( $this->input->post("Lokasi_ID") )
		{
			$db_where['a.Lokasi_ID'] = $this->input->post("Lokasi_ID");
		}

		if ( $this->input->post("KelompokJenis") != "ALL" )
		{
			$db_where['a.KelompokJenis'] = $this->input->post("KelompokJenis");
		}
						
		if ($this->input->post("Periode"))
		{
			$date = date_create_from_format("Y-m", $this->input->post("Periode") ); 
			$db_where['a.Tgl_Opname >= '] = date_format($date, 'Y-m-01');
			$db_where['a.Tgl_Opname <= '] = date_format($date, 'Y-m-t');
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "mUser b", "a.User_ID = b.User_ID", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "mUser b", "a.User_ID = b.User_ID", "LEFT OUTER" )
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
			->join( "mUser b", "a.User_ID = b.User_ID", "LEFT OUTER" )
			
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
			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }

	public function datatable_mutation()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		//$db_from = "{$this->inquiry_m->table} a";
		$db_from = "GD_trMutasi a";
		$db_where = array();
		$db_like = array();
		
		$db_where['d.SectionID'] = config_item('section_id');

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
						
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			->join( "SImmSection d", "a.Lokasi_Asal = b.Lokasi_ID")
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			->join( "SImmSection d", "a.Lokasi_Asal = b.Lokasi_ID")
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
			->join( "mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			->join( "SImmSection d", "a.Lokasi_Asal = b.Lokasi_ID")
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
			
            $output['data'][] = $row;
        }
				
		$this->template
			->build_json( $output );
    }
	

	public function datatable_mutation_return()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		//$db_from = "{$this->inquiry_m->table} a";
		$db_from = "GD_trReturMutasi a";
		$db_where = array();
		$db_like = array();
		
		$db_where['d.SectionID'] = config_item('section_id');

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
						
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			->join( "SImmSection d", "a.Lokasi_Asal = b.Lokasi_ID")
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			->join( "SImmSection d", "a.Lokasi_Asal = b.Lokasi_ID")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		
		/*
		
			SELECT TOP 50 
				GD_trReturMutasi.No_Bukti No_Bukti, GD_trReturMutasi.Tgl_Mutasi Tgl_Mutasi , ReturAsal.Nama_Lokasi ReturAsal_Nama_Lokasi , 
				ReturTujuan.Nama_Lokasi ReturTujuan_Nama_Lokasi , GD_trReturMutasi.No_Bukti 
			
			FROM   
				GD_trReturMutasi    
				LEFT OUTER JOIN mLokasi ReturAsal    ON GD_trReturMutasi.Lokasi_Asal=ReturAsal.Lokasi_ID  
				LEFT OUTER JOIN mLokasi ReturTujuan  ON GD_trReturMutasi.Lokasi_Tujuan=ReturTujuan.Lokasi_ID  
				inner join SImmSection on GD_trReturMutasi.Lokasi_Tujuan = SImmSection.Lokasi_ID  
				
			WHERE SImmSection.SectionID = 'SECT0002'
				And GD_trReturMutasi.Tgl_Mutasi>'01/Jan/2016' 
				And GD_trReturMutasi.Tgl_Mutasi<'30/Jun/2016'  
			ORDER BY Tgl_Mutasi ASC
		
		*/
		
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
			->join( "mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			->join( "SImmSection d", "a.Lokasi_Asal = b.Lokasi_ID")
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
			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
				
	public function autocomplete()
	{
		$words = $this->input->get_post('query');
		
		$this->db
			->select( array("id", "code", "inquiry_title") )
			;
			
		$this->db
			->from( "common_inquirys" )
			;
		
		$this->db
			->group_start()
				->where(array(
						'deleted_at' => NULL,
						'state' => 1
					))
			->group_end()
			;
		
		$this->db
			->group_start()
			->or_like(array(
					"code" => $words,
					"inquiry_title" => $words,
					"inquiry_description" => $words,
				))
			->group_end();
			
		$result = $this->db
			->get()
			->result()
			;
		
		if( $result )
		{
			$collection = array();
			foreach( $result as $item )
			{
				array_push($collection, array(
						"name" => $item->inquiry_title,
						"id" => $item->id,
					));
			}
		} else
		{
			$collection = array(array(
					"value" => 0,
					"label" => lang( "global:no_match" ),
					"id" => 0,
				));
		}
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode($collection);
		exit(0);
	}
	
	public function gen_mr_number()
	{
		if ( $this->input->is_ajax_request() )		
		{
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);

			if ($mrn = patient_helper::gen_mr_number())
			{
				$response['mrn'] = $mrn;
			} else {
				$response = array(
						"status" => "error",
						"message" => "Failed Generate Medical Recordd Number",
						"code" => 500
					);
			}
			
			print_r(json_encode( $response, JSON_NUMERIC_CHECK ));
			exit(0);
					
		}
	}

	public function time_dropdown( $selected='' )
	{
		if( $this->input->is_ajax_request() )
		{
			$items = $this->db
				->order_by('Keterangan', 'asc')
				->get("SIMmWaktuPraktek")
				->result()
				;
			
			$options_html = "";
			
			if( $selected == "" )
			{
				$options_html .= "\n<option data-waktuid=\"0\" data-keterangan=\"\" value=\"\" selected>".lang( 'global:select-empty' )."</option>";
			} else
			{
				$options_html .= "\n<option data-waktuid=\"0\" data-keterangan=\"\" value=\"\">".lang( 'global:select-empty' )."</option>";
			}
			
			foreach($items as $item)
			{
				
				$attr_data = "data-waktuid=\"{$item->WaktuID}\" data-keterangan=\"{$item->Keterangan}\" ";
				
				if( $selected == $item->WaktuID)
				{
					$options_html .= "\n<option {$attr_data} value=\"{$item->WaktuID}\" selected>{$item->Keterangan}</option>";
				} else
				{
					$options_html .= "\n<option {$attr_data} value=\"{$item->WaktuID}\">{$item->Keterangan}</option>";
				}
			}
			
			print( $options_html );
			exit();
		}
	}

	public function print_report($nrm,$dob, $stat = false)
	{
		if ($stat)
		{
			
			str_replace('%20','',$dob);
			$data = array(
							"nrm" => $nrm,
							"dob" => date("Y-m-d",strtotime($dob)),
						);
			
			//print_r($data);exit;
			$html_content =  $this->load->view( "inquiry/print", $data, 'Label '); 
			
			$file_name = 'Print  Label';		
			$this->load->helper( "report" );
	
			report_helper::generate_pdf( $html_content, $file_name, date("Y-M-d") , $margin_bottom = 1.0, $header = NULL, $margin_top = 0.3, $orientation = 'P');
	
			
	
			exit(0);
		}
	}

}



