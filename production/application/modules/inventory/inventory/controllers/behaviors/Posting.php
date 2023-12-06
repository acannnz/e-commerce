<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Posting extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/behaviors/posting'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('posting_model');
		$this->load->model('goods_receipt_model');
		$this->load->model('goods_receipt_detail_model');
		$this->load->model('order_model');	
		$this->load->model('supplier_model');
		$this->load->model('section_model');
		$this->load->model('procurement_model');
		
		$this->load->model('item_model');
		$this->load->model('item_category_model');
		$this->load->model('item_unit_model');
		$this->load->model('item_location_model');
	}
	
	public function index()
	{
		$this->data['form_action'] = base_url("{$this->nameroutes}/posting");
		$this->data['dropdown_section'] = $this->section_model->for_dropdown();
		
		$this->template
			->title(lang('heading:posting'),lang('heading:behaviors'))
			->set_breadcrumb(lang('heading:behaviors'))
			->set_breadcrumb(lang('heading:posting'), site_url($this->nameroutes))
			->build("behaviors/posting/index", $this->data);
	}
	
	public function posting()
	{				
		if( $this->input->post('confirm') == 1 ) 
		{
			$data = $this->input->post();
					
			$posting_data = $this->input->post("selected");
			$additional = $this->input->post("additional");
			$validation = [
				'No_Penerimaan[]' => $posting_data,
				'Lokasi_ID' => $additional['location_id']
			];
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->posting_model->rules['insert'] );
			$this->form_validation->set_data( $validation );
			if( $this->form_validation->run() )
			{
				if( empty($posting_data) )
				{
					response_json( ['status' => 'error', 'message' => lang('message:empty_data')] );	
				}
				
				if ( ! $posting_data = $this->posting_model->get_selected_posting_data( $posting_data ) )
				{
					response_json( ['status' => 'error', 'message' => lang('message:error_refresh_data')] );	
				}
				
				/* LOAD: BACK OFFICE DATABASE*/
				$this->db_bo = $this->load->database('BO_1', TRUE);
				
				$this->db->trans_begin();
				$this->db_bo->trans_begin();
				
					foreach( $posting_data as $row )
					{	
						if ( $row->PiutangSHAkun_ID == 0 || empty($row->PiutangSHAkun_ID) )
						{
							response_json( ['status' => 'error', 'message' => lang('message:section_accounts_not_valid')] );	
						}
						
						$typehutang = 1;
						$AkunPersediaan = 0;
						$AkunDP_ID = 0;
						
						if( $procurement = $this->procurement_model->get_one( $row->JenisPengadaanID ) )
						{
							$typehutang = $procurement->TypeHutang_ID;
							$AkunPersediaan = $procurement->HPPAKUN_ID;
							$PotonganAkun_ID = $procurement->PotonganAkun_ID; # Akun Diskon Biasa
							$PPNMasukan_ID = $procurement->PPNMasukan_ID;
							$DiskonAkun_ID = $procurement->DiskonAkun_ID; # Akun CN Faktur
							$AkunDP_ID = $procurement->DPAkun_ID;	
						}
						
						/*if ( $section = $this->section_model->get_one( $row->SectionID ) )
						{
							$PotonganAkun_ID = $section->DiskonAkun_ID;
						}	*/				
						
						$CurDP = 0;
						$Order_ID = 0;
						$CurHutangSum = 0;
						
						if( $_get_down_payment = $this->order_model->get_down_payment( $row->No_Penerimaan ) )
						{
							$CurDP = $_get_down_payment->Nilai_DP;
							$Order_ID = $_get_down_payment->Order_ID;
						}
						
						if ( $AkunPersediaan == 0 || empty($AkunPersediaan) )
						{	
							$this->db->trans_rollback();
							response_json( ['status' => 'error', 'message' => lang('message:hpp_not_valid')] );
						}
						
						if ( $CurDP > 0 && $AkunDP_ID == 0 )
						{	
							$this->db->trans_rollback();
							response_json( ['status' => 'error', 'message' => lang('message:down_payment_account_not_valid')] );
						}
						
						
						##############################
						### BACK OFFICE START TRANS ##
						##############################
						
						$AkunHutang_ID = 0;
						if ( $_get_type_hutang = $this->db_bo->where('TypeHutang_ID', $typehutang)->get('AP_mTypeHutang')->row() )
						{
							$AkunHutang_ID = $_get_type_hutang->Akun_ID;
						}
						
						$JmlNoBukti = 0;
						if ( $_count_factur = $this->db_bo->like('No_Faktur', $row->NoBukti)->count_all_results('AP_trFaktur') > 0 )
						{
							$JmlNoBukti = ++$_count_factur;
							$row->NoBukti = sprintf('%s-%s', $row->NoBukti, $_count_factur); 
						}
						
						$CurNilaiReal = $row->Total_Nilai;
						$CurNilaiHutang = $CurNilaiReal;
						$CurNilaiPersediaan = 0;
												
						$_insert_factur = [
							'No_Faktur' => $row->NoBukti,
							'Supplier_ID' => $row->Supplier_ID,
							'Currency_ID' => 1,
							'Tgl_Faktur' => $row->Tgl_Penerimaan,
							'Nilai_Faktur' => $CurNilaiHutang - $CurDP,
							'Keterangan' => $row->Keterangan .' - '. @$row->No_DO,
							'User_ID' => $this->user_auth->User_ID,
							'Tgl_Update' => date('Y-m-d'),
							'Diakui_Hutang' => 1,
							'Tgl_Pengakuan' => date('Y-m-d'),
							'Jenis_Pos' => 'RC',
							'JenisHutang_ID' => $typehutang,
							'Nilai_Tukar' => 1,
							'HisCurrencyID' => 1,
							'Kode_Proyek' => 9,
							'Tgl_JatuhTempo' => $row->Tgl_JatuhTempo,
							'Sisa' => $CurNilaiHutang - $CurDP,
							'JasaID' => $row->No_Penerimaan
						];
						$this->db_bo->insert('AP_trFaktur', $_insert_factur);
						
						$_insert_factur_detail = [
							'No_Faktur' => $row->NoBukti,
							'Akun_ID' => $AkunPersediaan,
							'Keterangan' => 'Penerimaan Pembelian',
							'Harga_Transaksi' => ( $CurNilaiHutang + $row->Potongan + $row->NilaiDiskon + $row->CN) - $row->NilaiPPN,
							'Pos' => 'D',
							'SectionID' => $row->SectionID
						];
						$this->db_bo->insert('AP_trFakturDetail', $_insert_factur_detail);
						
						if ( $row->NilaiPPN > 0 )
						{
							$_insert_factur_detail = [
								'No_Faktur' => $row->NoBukti,
								'Akun_ID' => $PPNMasukan_ID,
								'Keterangan' => 'Pajak Pembelian',
								'Harga_Transaksi' => $row->NilaiPPN,
								'Pos' => 'D',
								'SectionID' => $row->SectionID
							];
							$this->db_bo->insert('AP_trFakturDetail', $_insert_factur_detail);
						}
						
						# if ( ($row->Potongan + $row->NilaiDiskon - $row->CN) > 0 && $additional['location_id'] != 1368 )
						if ( ($row->Potongan + $row->NilaiDiskon) > 0 && $additional['location_id'] != 1368 )
						{
							$_insert_factur_detail = [
								'No_Faktur' => $row->NoBukti,
								'Akun_ID' => $PotonganAkun_ID,
								'Keterangan' => 'Potongan Pembelian',
								#'Harga_Transaksi' => $row->Potongan + $row->NilaiDiskon - $row->CN,
								'Harga_Transaksi' => $row->Potongan + $row->NilaiDiskon,
								'Pos' => 'K',
								'SectionID' => $row->SectionID
							];
							$this->db_bo->insert('AP_trFakturDetail', $_insert_factur_detail);
						}
						
						#if ( $row->CN > 0 && $additional['location_id'] != 1368 )
						if ( $row->CN > 0 && $additional['location_id'] != 1368 )
						{
							$_insert_factur_detail = [
								'No_Faktur' => $row->NoBukti,
								'Akun_ID' => $DiskonAkun_ID,
								'Keterangan' => 'CN On Faktur',
								'Harga_Transaksi' => $row->CN,
								'Pos' => 'K',
								'SectionID' => $row->SectionID
							];
							$this->db_bo->insert('AP_trFakturDetail', $_insert_factur_detail);
						}
						
						if ( $CurDP > 0 )
						{
							$_insert_factur_detail = [
								'No_Faktur' => $row->NoBukti,
								'Akun_ID' => $AkunDP_ID,
								'Keterangan' => 'Uang Muka (DP) Supplier',
								'Harga_Transaksi' => $CurDP,
								'Pos' => 'K',
								'SectionID' => $row->SectionID
							];
							$this->db_bo->insert('AP_trFakturDetail', $_insert_factur_detail);
						}
						
						
						$_insert_factur_detail = [
							'No_Faktur' => $row->NoBukti,
							'Akun_ID' => $AkunHutang_ID,
							'Keterangan' => $row->Keterangan,
							'Harga_Transaksi' => $CurNilaiHutang - $CurDP,
							'Pos' => 'K',
							'SectionID' => $row->SectionID
						];
						$this->db_bo->insert('AP_trFakturDetail', $_insert_factur_detail);
						
						
						$this->goods_receipt_model->update(['Posting_GL' => 1], $row->Penerimaan_ID);
						
						$activities_description = sprintf( "Posting Gudang. # %s --> %s", $row->Keterangan, $row->No_Penerimaan );
						insert_user_activity( $activities_description, $row->No_Penerimaan, $this->goods_receipt_model->table);
																		
					}
										
				if ($this->db->trans_status() === FALSE || $this->db_bo->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$this->db_bo->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('message:posting_failed'),
							"code" => 500
						);
				}
				else
				{
					$this->db->trans_commit();
					$this->db_bo->trans_commit();
					$response = array(
							"status" => 'success',
							"message" => lang('message:posting_successfully'),
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
		
		$this->load->view("behaviors/posting/modal/confirm", $this->data);
	}
	
	public function cancel()
	{
		$this->data['form_action'] = base_url("{$this->nameroutes}/cancel_posting");
		$this->data['dropdown_section'] = $this->section_model->for_dropdown();
		$this->data['is_cancel'] = TRUE;
				
		$this->template
			->title(lang('heading:cancel_posting'),lang('heading:behaviors'))
			->set_breadcrumb(lang('heading:behaviors'))
			->set_breadcrumb(lang('heading:posting'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:cancel_posting'), site_url("{$this->nameroutes}/cancel"))
			->build("behaviors/posting/index", $this->data);
	}
	
	public function cancel_posting()
	{		
		
		if( $this->input->post('confirm') == 1 ) 
		{
			$data = $this->input->post();
					
			$posting_data = $this->input->post("selected");
			$approver = $this->input->post("approver");
			$additional = $this->input->post("additional");
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
				
			$this->load->helper("Approval");
			if ( Approval_helper::approve( 'BATAL POSTING INVENTORY', $approver['username'], $approver['password'] ) === FALSE )
			{
				$response["message"] = lang('auth_incorrect');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				response_json($response);
			}
			
			$validation = [
				'No_Penerimaan[]' => $posting_data,
				'Lokasi_ID' => $additional['location_id']
			];
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->posting_model->rules['insert'] );
			$this->form_validation->set_data( $validation );
			if( $this->form_validation->run() )
			{
				if( empty($posting_data) )
				{
					response_json( ['status' => 'error', 'message' => lang('message:empty_data')] );	
				}
				
				if ( ! $posting_data = $this->posting_model->get_selected_posting_data( $posting_data, TRUE ) )
				{
					response_json( ['status' => 'error', 'message' => lang('message:error_refresh_data')] );	
				}
				
				/* LOAD: BACK OFFICE DATABASE*/
				$this->db_bo = $this->load->database('BO_1', TRUE);
				
				$this->db->trans_begin();
				$this->db_bo->trans_begin();
				
					foreach( $posting_data as $row )
					{	
						/*if ( inventory_helper::check_closing_period( $row->Tgl_Penerimaan ) )
						{
							$period = DateTime::createFromFormat('Y-m-d', $row->Tgl_Penerimaan);
							$response = array(
										"status" => 'error',
										"message" => sprintf(lang('message:already_closing_period_in'), $period->format('F Y')),
										"code" => 500
									);
							response_json( $response );
						}*/
						
						if( ! $_factur = $this->db_bo->where('JasaID', $row->No_Penerimaan)->get('AP_trFaktur')->row() )
						{
							$response["message"] = lang('global:get_failed');
							$response["status"] = "error";
							$response["code"] = "500";				
							response_json( $response );		
						}
						
						if ( $_factur->Cancel_Voucher == 0 && $_factur->No_Voucher != '-' )
						{
							$response["message"] = lang('message:factur_already_created_voucher');
							$response["status"] = "error";
							$response["code"] = "500";					
							response_json( $response );
						}
			
						if ( $_factur->Posted == 1 )
						{
							$response["message"] = lang('message:factur_already_posted');
							$response["status"] = "error";
							$response["code"] = "500";								
							response_json( $response );						
						}
						
						$this->db_bo->where('No_Faktur', $_factur->No_Faktur)->delete('AP_trFakturDetail');
						$this->db_bo->where('No_Faktur', $_factur->No_Faktur)->delete('AP_trFaktur');
									
						$this->goods_receipt_model->update(['Posting_GL' => 0], $row->Penerimaan_ID);
						
						$activities_description = sprintf( "Batal Posting Gudang. # %s --> %s", $row->Keterangan, $row->No_Penerimaan );
						insert_user_activity( $activities_description, $row->No_Penerimaan, $this->goods_receipt_model->table);
						
					}
										
				if ($this->db->trans_status() === FALSE || $this->db_bo->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$this->db_bo->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('message:cancel_posting_failed'),
							"code" => 500
						);
				}
				else
				{
					$this->db->trans_commit();
					$this->db_bo->trans_commit();
					$response = array(
							"status" => 'success',
							"message" => lang('message:cancel_posting_successfully'),
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
		
		$this->load->view("behaviors/posting/modal/cancel", $this->data);
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
		
		$db_from = "{$this->goods_receipt_model->table} a";
		$db_where = array();
		$db_like = array();
		
		// preparing default filter
		$db_where['a.Status_Batal'] = 0;
		$db_where['a.Sumber_Penerimaan'] = 0; 
		$db_where['a.Posting_GL'] = 0;

		if ($this->input->post("is_cancel"))
		{
			$db_where['a.Posting_GL'] = 1;
		}
		
		if ($this->input->post("location_id"))
		{
			$db_where['a.Lokasi_ID'] = $this->input->post("location_id");
		}
		
		if ($this->input->post("date_from"))
		{
			$db_where['a.Tgl_Penerimaan >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till"))
		{
			$db_where['a.Tgl_Penerimaan <='] = $this->input->post("date_till");
		}
				
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.No_Penerimaan") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Tgl_Penerimaan") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Keterangan") ] = $keywords;
			$db_like[ $this->db->escape_str("c.SectionName") ] = $keywords;
			$db_like[ $this->db->escape_str("e.Nama_Supplier") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->goods_receipt_detail_model->table} b", "a.Penerimaan_ID = b.Penerimaan_ID", "LEFT OUTER" )
			->join( "{$this->section_model->table} c", "a.Lokasi_ID = c.Lokasi_ID", "LEFT OUTER" )
			->join( "{$this->order_model->table} d", "a.Order_ID = d.Order_ID", "LEFT OUTER" )
			->join( "{$this->supplier_model->table} e", "a.Supplier_ID = e.Supplier_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			d.JenisPengadaanID,
			a.Tgl_JatuhTempo, 
			a.User_ID,
			a.Penerimaan_ID,
			a.No_Penerimaan,
			a.Tgl_Penerimaan,
			SUM( b.Qty_Penerimaan * b.Harga_Beli) AS NilaiTransaksi,
			SUM(( b.Qty_Penerimaan * b.Harga_Beli ) * b.Diskon_1 / 100 ) AS NilaiDiskon, 
			SUM( b.Qty_Penerimaan * b.Diskon_Rp ) AS Diskon_RP,
			a.Nilai_DP,
			a.Keterangan,
			a.Supplier_ID,
			a.Currency_ID,
			c.SectionName,
			a.No_DO, 
			a.Pajak AS NilaiPPN,
			a.Ongkos_Angkut,
			a.Potongan,
			c.PiutangSHAkun_ID,
			a.Total_Nilai,
			e.Nama_Supplier,
			'Penerimaan' AS JenisTransaksi,
			'CN' = COALESCE
				(
					(
						SELECT SUM( VX.NilaiDiskon + VX.Diskon_RP ) FROM 
							(
								SELECT 
									COALESCE( SUM(( BLDetail.Qty_Penerimaan * BLDetail.Harga_Beli ) * BLDetail.Diskon_1 / 100), 0) AS NilaiDiskon, 
									COALESCE( SUM( BLDetail.Qty_Penerimaan * BLDetail.Diskon_Rp), 0) AS Diskon_RP,
									BLDetail.Barang_ID 
								FROM BL_trPenerimaanDetail AS BLDetail 
									INNER JOIN mBarang on mBarang.Barang_ID = BLDetail.Barang_ID
								WHERE 
									BLDetail.Penerimaan_ID = a.Penerimaan_ID 
									AND mBarang.FormulariumUmum = 1 
									AND ( BLDetail.Diskon_1 > 0 OR BLDetail.Diskon_Rp > 0 ) 
								GROUP BY BLDetail.Barang_ID
							) VX
					), 0
				)
EOSQL;
				
		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->goods_receipt_detail_model->table} b", "a.Penerimaan_ID = b.Penerimaan_ID", "LEFT OUTER" )
			->join( "{$this->section_model->table} c", "a.Lokasi_ID = c.Lokasi_ID", "LEFT OUTER" )
			->join( "{$this->order_model->table} d", "a.Order_ID = d.Order_ID", "LEFT OUTER" )
			->join( "{$this->supplier_model->table} e", "a.Supplier_ID = e.Supplier_ID", "LEFT OUTER" )
			->group_by([
				"d.JenisPengadaanID",
				"a.Penerimaan_ID",
				"a.Tgl_JatuhTempo", 
				"a.User_ID",
				"a.No_Penerimaan",
				"a.Tgl_Penerimaan",
				"a.Nilai_DP",
				"a.Keterangan",
				"a.Supplier_ID",
				"a.Currency_ID",
				"c.SectionName",
				"a.No_DO", 
				"a.Pajak",
				"a.Ongkos_Angkut",
				"a.Potongan",
				"c.PiutangSHAkun_ID",
				"a.Total_Nilai", 
				"e.Nama_Supplier"
			]);
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
			if ( trim($row->No_DO) == "" || empty($row->No_DO) || $row->No_DO == '00' || $row->No_DO == '00.' )
			{
				$NoBukti = $row->No_Penerimaan;
			} else {
				
				$date = DateTime::createFromFormat('Y-m-d H:i:s', $row->Tgl_Penerimaan);
				switch ($row->No_DO)
				{
					case 'CASH': 
						$NoBukti = sprintf("%s-%s", $row->No_DO, trim($row->No_Penerimaan));
						break;
					default: 
						$NoBukti = sprintf("%s-%s%s%s", $row->No_DO, $date->format('j'), $date->format('n'), $date->format('y'));
				}	

			}
			
			$row->NoBukti = $NoBukti;
			$row->Tgl_Penerimaan = substr($row->Tgl_Penerimaan, 0, 10);
			$row->Keterangan = "Penerimaan {$row->No_Penerimaan}";
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );		
    }		
}