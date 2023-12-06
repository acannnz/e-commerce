<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class laboratory_helper
{
	private static $_ci;
	private static $user_auth;
	private static $weekDay = ["MINGGU", "SENIN", "SELASA", "RABU", "KAMIS", "JUMAT", "SABTU"];
	
	public static function init()
	{
		self::$_ci = $_ci = self::ci();
		self::$user_auth = $_ci->simple_login->get_user();
	}
	
	public static function gen_evidence_number( $SectionID )
	{
		$CI = self::ci();
		$NOW = new DateTime();
		
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );
		
		$section = $CI->db->where( array("SectionID" => $SectionID) )->get("SIMmSection")->row();
		$count = (int) $CI->db
							->where(array(
									"Tanggal >=" => $date_start,
									"Tanggal <=" => $date_end,
									"SectionID" => $section->SectionID
								))
							->count_all_results( "SIMtrRJ" )
							;
		$count++;
		
		$number = (string) (sprintf("%02d%02d%02d%s-%06d", $date_y, $date_m, $date_d, $section->KodeNoBukti, $count));		
		return $number;
	}

	public static function gen_bhp_number(  )
	{
		$_ci = self::ci();
		$NOW = new DateTime();
		
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );

		$query =  $_ci->db
						->select("MAX(NoBukti) as max_number")
						->where([
							"LEN([NoBukti]) =" => 16, 
							"LEFT(LTRIM([NoBukti]),2) =" => $date_y, 
							"RIGHT(LEFT(LTRIM([NoBukti]),9),3) =" => 'BHP',
						])
						->get( "BILLFarmasi" )
						->row()
					;
		if (!empty($query->max_number))
		{
			$query->max_number++;
			$number = $query->max_number;
		} else {
			$number = (string) (sprintf("%02d%02d%02dBHP-%06d", $date_y, $date_m, $date_d, 1));		
		}
				
		return $number;
	}
	
	public static function gen_category_test_number()
	{
		$CI = self::ci();
		$NOW = new DateTime();
		
		$date_start = $NOW->format( "Y-m-d 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );
		
		$count = @$CI->db->select('MAX(KategoriTestID) as Max')
							->get( "LISmKategoriTest" )
							->row()
							->Max;
		$count++;
		
		$number = (string) sprintf("%03d", $count);		
		return $number;
	}
	
	/*
		@params Nomor Registrasi, SectionID
	*/
	public static function get_registration_data($NoReg, $SectionID, $is_edit = false)
	{
		$_ci = self::ci();
		$_ci->load->model('registration_data_model');
		$_ci->load->model('section_model');
		
		$db_select = <<<EOSQL
			b.NoReg,
			a.TglReg,
			a.JamReg,
			b.SectionID,
			d.SectionName,
			a.Batal,
			b.Kamar,
			b.NoBed,
			b.Tanggal,
			b.Nomor,
			b.KelasID,
			b.Titip,
			a.NoKartu AS NoAnggota,
			a.TglLahir,
			b.UmurThn,
			b.UmurBln,
			b.UmurHr,
			a.NRM,
			a.NamaPasien,
			a.JenisKelamin,
			a.JenisKerjasamaID,
			a.JenisKerjasama,
			a.Kerjasama,
			a.CustomerKerjasamaID,
			a.Kode_Customer,
			a.Nama_Customer AS NamaPerusahaan,
			a.NoKartu,
			b.KelasAsalID,
			a.PasienLoyal,
			a.PasienVVIP,
			a.Alamat,
			a.PasienKTP,
			a.NamaKelas,
			a.MarkUp,
			a.StatusBayar,
			b.Tanggal,
			b.Jam,
			b.SectionAsalID,
			c.SectionName AS SectionAsal,
			b.KamarAsal,
			b.NoBedAsal,
			a.PasienBlackList,
			a.VIP,
			a.VIPKeterangan,
			a.KdKelasPertanggungan,
			b.DokterID,
			g.NamaDokter AS NamaDokter,
			b.DiagnosaAkhirID,
			b.KeteranganDiagnosa,
			b.PxKeluar_Pulang,
			b.PxKeluar_PlgPaksa,
			b.PxKeluar_Dirujuk,
			b.PxMeninggal,
			b.MeninggalSblm48,
			b.MeninggalStl48,
			b.MeninggalTgl,
			b.MeninggalJam,
			a.StatusPeriksa,
			a.ProsesPayment,
EOSQL;
		
		$query = $_ci->db->select($db_select)
					->from("VW_Registrasi a")
					->join("{$_ci->registration_data_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER")
					->join("{$_ci->section_model->table} c", "b.SectionAsalID = c.SectionID", "LEFT OUTER")
					->join("{$_ci->section_model->table} d", "b.SectionID = d.SectionID", "INNER")
					->join("Vw_Dokter g", "b.DokterID = g.DokterID", "LEFT OUTER")
					->where(['b.NoReg' => $NoReg, 'b.SectionID' => $SectionID]);
				
		if($is_edit)
			$_ci->db->select('e.TindakLanjutCekUpUlang, e.TglCekUp')
				->join("{$_ci->laboratory_m->table} e", "e.RegNo = a.NoReg", "INNER")
				->where(['e.Batal' => 0]);
								
		return $query->get()->row();		
	}
	
	public static function get_examination($NoBukti)
	{
		$_ci = self::ci();
		$_ci->load->model('registration_data_model');
		$_ci->load->model('section_model');
		
		$db_select = <<<EOSQL
			a.NoReg,
			a.TglReg,
			a.JamReg,
			a.Batal,
			a.NoKartu AS NoAnggota,
			a.TglLahir,
			a.NRM,
			a.NamaPasien,
			a.JenisKelamin,
			a.JenisKerjasamaID,
			a.JenisKerjasama,
			a.Kerjasama,
			a.CustomerKerjasamaID,
			a.Kode_Customer,
			a.Nama_Customer AS NamaPerusahaan,
			a.NoKartu,
			a.PasienLoyal,
			a.PasienVVIP,
			a.Alamat,
			a.PasienKTP,
			a.NamaKelas,
			a.MarkUp,
			a.StatusBayar,
			a.PasienBlackList,
			a.VIP,
			a.VIPKeterangan,
			a.KdKelasPertanggungan,
			a.StatusPeriksa,
			a.ProsesPayment,
			
			b.SectionID,			
			b.Kamar,
			b.NoBed,
			b.Tanggal,
			b.Nomor,
			b.KelasID,
			b.Titip,			
			b.UmurThn,
			b.UmurBln,
			b.UmurHr,
			b.KelasAsalID,			
			b.Tanggal,
			b.Jam,
			b.SectionAsalID,
			b.KamarAsal,
			b.NoBedAsal,
			b.DokterID,
			b.DiagnosaAkhirID,
			b.KeteranganDiagnosa,
			
			c.SectionName AS SectionAsal,
			d.SectionName,
			
			h.NoBukti,
			h.DokterPengirimID,
			h.SupplierPengirimID,
			h.DokterID,
			h.AnalisID,
			h.TransferDokterID,
			h.DirujukVendorID,
			
			i.Nama_Supplier AS DokterPengirimName,			
			j.Nama_Supplier AS SupplierPengirimName,			
			k.Nama_Supplier AS DokterName,			
			l.Nama_Supplier AS AnalisName,			
			m.Nama_Supplier AS TransferDokterName,			
			n.Nama_Supplier AS DirujukVendorIDName			
EOSQL;
		
		$query = $_ci->db->select($db_select)
					->from("VW_Registrasi a")
					->join("{$_ci->registration_data_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER")
					->join("{$_ci->section_model->table} c", "b.SectionAsalID = c.SectionID", "LEFT OUTER")
					->join("{$_ci->section_model->table} d", "b.SectionID = d.SectionID", "INNER")
					->join("{$_ci->laboratory_m->table} h", "a.NoReg = h.RegNo", "LEFT OUTER")
					->join("{$_ci->supplier_model->table} i", "h.DokterPengirimID = i.Kode_Supplier", "LEFT OUTER")
					->join("{$_ci->supplier_model->table} j", "h.SupplierPengirimID = j.Kode_Supplier", "LEFT OUTER")
					->join("{$_ci->supplier_model->table} k", "h.DokterID = k.Kode_Supplier", "LEFT OUTER")
					->join("{$_ci->supplier_model->table} l", "h.AnalisID = l.Kode_Supplier", "LEFT OUTER")
					->join("{$_ci->supplier_model->table} m", "h.TransferDokterID = m.Kode_Supplier", "LEFT OUTER")
					->join("{$_ci->supplier_model->table} n", "h.DirujukVendorID = n.Kode_Supplier", "LEFT OUTER")					
					->where(['h.NoBukti' => $NoBukti, 'h.Batal' => 0]);
												
		return $query->get()->row();		
	}
	
	public static function create_examination($lab, $service, $service_component, $service_consumable, $checkout)
	{
		self::init();
		$_ci = self::$_ci;
		
		$_ci->db->trans_begin();
			
			$lab['NoBukti'] = $NoBukti = self::gen_evidence_number($lab['SectionID']);
			$lab['Tanggal'] = date('Y-m-d');
			$lab['Jam'] = date('Y-m-d H:i:s');
			$lab['TglInput'] = date('Y-m-d');
			$_ci->laboratory_m->create( $lab );				
			
			$_ci->db->query("EXEC UpdateKunjunganPasien '{$lab['NRM']}', '{$lab['Jam']}', '{$lab['SectionID']}','{$lab['DokterID']}'");
					
			if ( !empty($service))
			{
				$Nomor = $_ci->laboratory_m->get_max_number( "SIMtrRJTransaksi", ["NoBukti" => $lab['NoBukti']], "Nomor" );				
				foreach ( $service as $row ):
				
					$row['NoBukti'] = $NoBukti;
					$row['Nomor'] = ++$Nomor; # penomoran jasa 
					$_ci->poly_transaction_model->create( $row );
					
					if(empty($service_component[$row['JasaID']]))
					{
						$_ci->db->trans_rollback();
						return [
							"status" => 'error',
							"message" => "Transaksi tidak dapat lanjutkan. Jasa {$row['JasaID']} tidak memiliki Detail. Silahkan hapus dan pilih ulang Jasa tersebut.",
							"code" => 401
						];
					}
										
					foreach ( $service_component[$row['JasaID']] as $val ) # penomoran component berdasarkan jasa
					{
						$val['NoBukti'] = $NoBukti;
						$val['Nomor'] = $Nomor; 
						$_ci->poly_transaction_detail_model->create( $val );
					}

					if(!empty($service_consumable[$row['JasaID']])) foreach( $service_consumable[$row['JasaID']] as $val ) :
						$val['NoBUkti'] = $NoBukti;
						$val['SectionID'] = $lab['SectionID'];
						$val['Nomor'] = $Nomor; # penomoran component bhp
						$_ci->poly_transaction_pop_model->create( $val );
						
						# Pengurangan Stock
						$section = $_ci->section_model->get_one($lab['SectionID']);
						$_insert_fifo = [
							'location_id' => $section->Lokasi_ID, 
							'item_id' => $val["Barang_ID"],  
							'item_unit_code' => $val["Satuan"],  
							'qty' => $val["Qty"], 
							'price' => $val["Harga"],  
							'conversion' => 1,  
							'evidence_number' => $lab['NoBukti'],  
							'trans_type_id' => 564,
							'in_out_state' => 0,
							'trans_date' => date('Y-m-d'),  
							'exp_date' => date('Y-m-d'),  
							'item_type_id' => 0, 
						];
						self::insert_warehouse_fifo( $_insert_fifo );

					endforeach; 
					$_ci->db->query("EXEC InsertUserActivities '{$lab['Tanggal']}','{$lab['Jam']}', ".self::$user_auth->User_ID.",'{$lab['NoBukti']}','INPUT DETAIL BILLING.#NRM #{$lab['NRM']}#{$lab['NoBukti']}#{$row['JasaID']}', 'SIMtrRJTransaksi'");
				endforeach;
			}
			
			$_update_registration_data = [
				'NoBIll' => $NoBukti, 
				'DokterID' => $lab['DokterID'], 
				'SudahPeriksa' => 1,
				'TglPeriksa' => date('Y-m-d'),
				'JamPeriksa' => date('Y-m-d H:i:s'),
				'DokterRawatID' => $lab['DokterID'],
			];
			$_ci->registration_data_model->update_by($_update_registration_data, [ 'NoReg' => $lab['RegNo'], 'SectionID' => $lab['SectionID'] ]);
			
			if(!empty($lab['TransferSectionID'])):
				$_insert_registration_data = [
					'NoReg' => $lab['RegNo'],
					'Nomor' => (int) $_ci->laboratory_m->get_max_number( "SIMtrDataRegPasien", array("NoReg" => $lab['RegNo']), "Nomor" ),
					'Tanggal' => $lab['Tanggal'],
					'Jam' => $lab['Jam'],
					'SectionAsalID' => $lab['SectionID'],
					'SectionID' => $lab['TransferSectionID'],
					'KelasID' => $lab['KdKelas'],
					'DokterID' => $lab['DokterID'],
					'NoAntri' => 1 + $_ci->laboratory_m->get_max_number( "SIMtrDataRegPasien", ["SectionID" => $lab['TransferSectionID'], 'DokterID' => $lab['TransferDokterID'], "Tanggal" => date("Y-m-d")], "NoAntri"),
					'SudahPeriksa' => 0,
					'RJ' => 1,
					'KelasAsalID' => $lab['KdKelas'],
					'Titip' => 0,
					'JenisPasienID' => $lab['JenisKerjasamaID'],
					'UmurThn' => $lab['Umur_Th'],
					'UmurBln' => $lab['Umur_Bln'],
					'UmurHr' => $lab['Umur_Hr']
				];			
				$_ci->registration_data_model->create($_insert_registration_data);	
							
			else:			
				$_update_registration = [
					'StatusPeriksa' => 'CO',
					'DokterRawatID' => $lab['DokterID'],
					'PxKeluar_Pulang' => 1,
				];				
				$_ci->registration_model->update($_update_registration, $lab['RegNo']);		
			endif;
			
			$_ci->db->query("EXEC InsertUserActivities '{$lab['Tanggal']}','{$lab['Jam']}', ".self::$user_auth->User_ID.",'{$lab['NoBukti']}','Input BILLING.#{$lab['RegNo']}#{$lab['NRM']}#{$lab['NoBukti']}', 'SIMtrRJ'");
					
		if ($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => lang('global:created_failed'),
				"code" => 500
			];
		}
		
		$_ci->db->trans_commit();
		return [
			"NoBukti" => $NoBukti,
			"status" => 'success',
			"message" => lang('global:created_successfully'),
			"code" => 200
		];
	}
	
	public static function update_examination($lab, $service, $service_component, $service_consumable)
	{
		self::init();
		$_ci = self::$_ci;
		
		$_ci->db->trans_begin();
			
			$_ci->laboratory_m->update( $lab, $lab['NoBukti']);				
			$_ci->db->query("EXEC InsertUserActivities '".date('Y-m-d')."','".date('Y-m-d H:i:s')."', ".self::$user_auth->User_ID.",'{$lab['NoBukti']}','Edit BILLING.#{$lab['RegNo']}#{$lab['NRM']}#{$lab['NoBukti']}', 'SIMtrRJ'");
							
			if ( !empty($service))
			{
				// Ketika Edit disimpan, maka Hapus semua data service dan simpan dengan data service yg baru, 
				// Return barang (BHP), dan simpan data component juga BHP jika ada
				$section = $_ci->section_model->get_one($lab['SectionID']);
				$return_service_consumable = $_ci->poly_transaction_pop_model->get_all(NULL, 0, ['NoBUkti' => $lab['NoBukti']], TRUE);
				foreach( $return_service_consumable as $val )
				{
					$_insert_fifo = [
						'location_id' => $section->Lokasi_ID, 
						'item_id' => $val["Barang_ID"],  
						'item_unit_code' => $val["Satuan"],  
						'qty' => $val["Qty"], 
						'price' => $val["Harga"],  
						'conversion' => 1,  
						'evidence_number' => $lab['NoBukti'].'-R',  
						'trans_type_id' => 562,
						'in_out_state' => 1,
						'trans_date' => date('Y-m-d'),  
						'exp_date' => date('Y-m-d'),  
						'item_type_id' => 0, 
					];
					self::insert_warehouse_fifo( $_insert_fifo );
					
					/*// Ambil stok terakhir yang ada di kartu gudang,
					$qty_last_stock = $_ci->poly_m->get_last_stock_warehouse_card( array("Lokasi_ID" => $v->Lokasi_ID, "Barang_ID" => $v->Barang_ID) );
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
							"No_Bukti" => $v->No_Bukti .'-R',
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
					$_ci->db->insert("GD_trKartuGudang", $kartu_gudang );*/
				}
					
				$_ci->poly_transaction_detail_model->delete($lab['NoBukti']);
				$_ci->poly_transaction_pop_model->delete($lab['NoBukti']);
				$_ci->poly_transaction_model->delete($lab['NoBukti']);

				$Nomor = $_ci->laboratory_m->get_max_number( $_ci->poly_transaction_model->table, ['NoBukti' => $lab['NoBukti']], 'Nomor' );
				$service_insert = array();
				$service_component_insert = array();
				$service_consumable_insert = array();
				
				$section = $_ci->laboratory_m->get_row_data( "SIMmSection", array("SectionID" => $lab['SectionID'] ));
				foreach ( $service as $row )
				{
					$row['NoBukti'] = $lab['NoBukti'];
					$row['Nomor'] = ++$Nomor; # penomoran jasa 
					$_ci->poly_transaction_model->create( $row );
										
					foreach ( $service_component[$row['JasaID']] as $val ) # penomoran component berdasarkan jasa
					{
						$val['NoBukti'] = $lab['NoBukti'];
						$val['Nomor'] = $Nomor; 
						$_ci->poly_transaction_detail_model->create( $val );
					}

					if(!empty($service_consumable[$row['JasaID']])) foreach( $service_consumable[$row['JasaID']] as $val ) :
						$val['NoBUkti'] = $lab['NoBukti'];
						$val['Nomor'] = $Nomor; # penomoran component bhp
						$_ci->poly_transaction_pop_model->create( $val );
						
						# Pengurangan Stock
						$section = $_ci->section_model->get_one($lab['SectionID']);
						$_insert_fifo = [
							'location_id' => $section->Lokasi_ID, 
							'item_id' => $val["Barang_ID"],  
							'item_unit_code' => $val["Satuan"],  
							'qty' => $val["Qty"], 
							'price' => $val["Harga"],  
							'conversion' => 1,  
							'evidence_number' => $lab['NoBukti'],  
							'trans_type_id' => 564,
							'in_out_state' => 0,
							'trans_date' => date('Y-m-d'),  
							'exp_date' => date('Y-m-d'),  
							'item_type_id' => 0, 
						];
						self::insert_warehouse_fifo( $_insert_fifo );
					endforeach;
				}				
			}	
			
			if(!empty($lab['TransferSectionID'])):
			
				$already_transfer = $_ci->laboratory_m->get_row_data( "SIMtrDataRegPasien", ['SectionAsalID' => $lab['SectionID'], "SectionID" => $lab['TransferSectionID'], 'DokterID' => $lab['TransferDokterID']]);
				if(empty($already_transfer)):
					$_insert_registration_data = [
						'NoReg' => $lab['RegNo'],
						'Nomor' => (int) $_ci->laboratory_m->get_max_number( "SIMtrDataRegPasien", array("NoReg" => $lab['RegNo']), "Nomor" ),
						'Tanggal' => $lab['Tanggal'],
						'Jam' => $lab['Jam'],
						'SectionAsalID' => $lab['SectionID'],
						'SectionID' => $lab['TransferSectionID'],
						'KelasID' => $lab['KdKelas'],
						'DokterID' => $lab['DokterID'],
						'NoAntri' => 1 + $_ci->laboratory_m->get_max_number( "SIMtrDataRegPasien", ["SectionID" => $lab['TransferSectionID'], 'DokterID' => $lab['TransferDokterID'], "Tanggal" => date("Y-m-d")], "NoAntri"),
						'SudahPeriksa' => 0,
						'RJ' => 1,
						'KelasAsalID' => $lab['KdKelas'],
						'Titip' => 0,
						'JenisPasienID' => $lab['JenisKerjasamaID'],
						'UmurThn' => $lab['Umur_Th'],
						'UmurBln' => $lab['Umur_Bln'],
						'UmurHr' => $lab['Umur_Hr']
					];			
					$_ci->registration_data_model->create($_insert_registration_data);	
				endif;
				
				$_update_registration = [
					'StatusPeriksa' => 'Belum',
					'DokterRawatID' => $lab['DokterID'],
					'PxKeluar_Pulang' => 0,
				];				
				$_ci->registration_model->update($_update_registration, $lab['RegNo']);				
			else:			
				$_update_registration = [
					'StatusPeriksa' => 'CO',
					'DokterRawatID' => $lab['DokterID'],
					'PxKeluar_Pulang' => 1,
				];				
				$_ci->registration_model->update($_update_registration, $lab['RegNo']);		
			endif;
			
		if ($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => lang('global:updated_failed'),
				"code" => 500
			];
		}
		//$_ci->db->trans_rollback();
		$_ci->db->trans_commit();
		return [
			"NoBukti" => $lab['NoBukti'],
			"status" => 'success',
			"message" => lang('global:updated_successfully'),
			"code" => 200
		];
	}
	
	public static function get_examination_test($key, $is_edit = FALSE)
	{
		$_ci = self::ci(); 
		
		if( $is_edit ):
			$_ci->db->select("
					z.*, a.NoBukti AS NoBill, a.Tanggal, a.LokasiPasien,
					a.RawatInap, b.NoReg, a.DokterID, a.AnalisID, c.* 
				")
				->from("{$_ci->lis_test_sample_model->table} z")
				->join("{$_ci->laboratory_m->table} a", "z.NoSystem = a.NoBukti", "INNER")
				->join("{$_ci->registration_model->table} b", "a.RegNo = b.NoReg", "INNER")
				->join("{$_ci->patient_model->table} c", "a.NRM = c.NRM", "INNER")
				->where("a.NoBukti", $key);
		else:
			$_ci->db->select("
					a.NoBukti AS NoBill, a.Tanggal, a.LokasiPasien,
					a.RawatInap, b.NoReg, b.UmurThn, b.UmurBln, b.UmurHr, 
					a.DokterID, a.AnalisID, c.* 
				")
				->from("{$_ci->laboratory_m->table} a")
				->join("{$_ci->registration_model->table} b", "a.RegNo = b.NoReg", "INNER")
				->join("{$_ci->patient_model->table} c", "b.NRM = c.NRM", "INNER")
				->where("a.NoBukti", $key);
		endif;
		
		$query = $_ci->db->get();
		return $query->row();
	}
	
	public static function get_examination_test_result( $NoBukti, $is_edit = FALSE )
	{
		$_ci = self::ci();
		$_ci->load->model('poly_transaction_model');
		
		if( $is_edit ):
			$query = $_ci->db->select("d.TestID, b.*, d.NamaTest, d.Satuan, d.Harga, e.KategoriTestNama ")
						->from("{$_ci->lis_test_sample_model->table} a")
						->join("{$_ci->lis_test_type_model->table} b", "a.NoSystem = b.NoSystem and a.SampleID = b.SampelID", "INNER")
						->join("{$_ci->test_type_m->table} d", "b.TestID = d.testID", "INNER")
						->join("{$_ci->test_category_m->table} e", "d.KategoriTestiID = e.KategoriTestID", "LEFT OUTER")
						->where("a.NoSystem", $NoBukti)
						->order_by("d.KategoriTestiID, d.NoUrut")
						->get();
		else:
			$query = $_ci->db->select("d.TestID, a.Qty, d.NamaTest, d.Satuan, d.Harga, e.KategoriTestNama ")
						->from("{$_ci->poly_transaction_model->table} a")
						->join("{$_ci->service_model->table} b", "a.JasaID = b.JasaID", "INNER")
						->join("{$_ci->service_test_model->table} c", "b.JasaID = c.JasaID", "INNER")
						->join("{$_ci->test_type_m->table} d", "c.TestID = d.testID", "INNER")
						->join("{$_ci->test_category_m->table} e", "d.KategoriTestiID = e.KategoriTestID", "LEFT OUTER")
						->where("a.NoBukti", $NoBukti)
						->order_by("d.KategoriTestiID, d.NoUrut ")
						->get();
		endif;
					
		return $query->result();
	
	}
	
	public static function get_examination_test_result_report( $NoBukti )
	{
		$_ci = self::ci();
		
		$query = $_ci->db->select("d.TestID, b.*, d.NamaTest, d.Satuan, d.Harga, e.KategoriTestNama ")
					->from("{$_ci->lis_test_type_model->table} b")
					->join("{$_ci->test_type_m->table} d", "b.TestID = d.testID", "INNER")
					->join("{$_ci->test_category_m->table} e", "d.KategoriTestiID = e.KategoriTestID", "LEFT OUTER")
					->where("b.NoSystem", $NoBukti)
					->order_by("d.KategoriTestiID, d.NoUrut")
					->get();
					
		return $query->result();
	
	}
		
	/*
		@params
			pStrNoBill As String, 
			pStrJenisTest As String, 
			pStrSex As String, 
			pIntUmur_Th As Integer, 
			pIntUmur_Bln As Integer, 
			pIntUmur_Hr As Integer, 
			pStrTypeKelahiran As String, 
		
		@returns
			NilaiRujukan As String, 
			NilaiRujukanKeterangan As String, 
	*/
	public static function get_reference_value( Array $params )
	{
		$_ci = self::ci();
		extract($params);
		
		if( $pIntUmur_Th > 85 ) $pIntUmur_Th = 85;
		$dIntHari = ($pIntUmur_Th * 365) + ($pIntUmur_Bln * 30) + ($pIntUmur_Hr);
		
		if( strpos($pStrJenisTest, 'GLUC2') ):
			$dStrWhere = " left(LISmNilaiRujukan.TestID, 5)='GLUC2' and Namatest='{$pStrNamaTest}'";
		else:
			$dStrWhere = " TestID='{$pStrJenisTest}'";
		endif;
		
		
		if( $pStrSex <> "All" ):	
			$dStrWhere .=" AND (Sex='{$pStrSex}' OR Sex='A')";
			$dStrSex = $pStrSex == "M" ? "Male" : "Female";
		else:
			$dStrSex = $pStrSex;
		endif;
		
		if( $pStrTypeKelahiran <> "" ):
			$dStrWhere .= " AND TypeKelahiran='{$pStrTypeKelahiran}'";
		else:
			$dStrWhere .= " AND TypeKelahiran='Normal'";
		endif;		
		
		//$dStrSex = "";
		
		if( $dIntHari <> 0 ):
			if( strpos($pStrJenisTest, "BIL-D") ):
				# Khusus untuk bill direct yang 1 bulan masuk ke >1 bulan
				$dStrWhere .= " AND UmurTotal_Hr < {$dIntHari} + 1 AND UmurTotal_Hr2 > {$dIntHari} ";
			else:
			
				$dStrWhere .= " AND UmurTotal_Hr < {$dIntHari} AND UmurTotal_Hr2 > {$dIntHari} ";
			endif;
		endif;
		
		if( strpos($pStrJenisTest, 'GLUC2') ):
			$get_reference = $_ci->db->select("a.*")
						->from("{$_ci->test_type_detail_m->table} a")
						->join("{$_ci->test_type_m->table} b", "a.TestID = b.TestID", "INNER")
						->where( $dStrWhere )
						->get()->row();
		else:
			$get_reference = $_ci->db
						->from("{$_ci->test_type_detail_m->table} a")
						->where( $dStrWhere )
						->get()->row();
		endif;
		
		$pStrNilaiRujukan = $pStrNilaiRujukanKeterangan = $dstrUmur = $dStrUmur2 = "";
		if( !empty($get_reference) ):
			//$pStrNilaiRujukan = sprintf("%s-%s-%s", $get_reference->NilaiRujukan, $dStrSex, $get_reference->KelompokUmur);
			$pStrNilaiRujukan = $get_reference->NilaiRujukan;
			
			if($get_reference->KelompokUmur == 'All'):
			
				$pStrNilaiRujukanKeterangan = $dStrSex;
				
			elseif( $get_reference->KelompokUmur == "Per Umur" ):
			
				if( $get_reference->Umur_Th_1 <> 0 ):
					$dstrUmur = "{$get_reference->Umur_Th_1} Y ";
				endif;
				if( $get_reference->Umur_Bln_1 <> 0 ):
					$dstrUmur .= "{$get_reference->Umur_Bln_1} M ";
				endif;
				if( $get_reference->Umur_Hari_1 <> 0 ):
					if ($dstrUmur == "")
						$dstrUmur .= "{$get_reference->Umur_Hari_1} D ";
				endif;
				
				if( $get_reference->Umur_Th_2 <> 0 ):
					$dStrUmur2 = "{$get_reference->Umur_Th_2} Y ";
				endif;				
				if( $get_reference->Umur_Bln_2 <> 0 ):
					$dStrUmur2 .= "{$get_reference->Umur_Bln_2} M ";
				endif;								
				if( $get_reference->Umur_Hari_2 <> 0 ):
					if ($dStrUmur2 == "")
						$dStrUmur2 .= "{$get_reference->Umur_Hari_2} D ";
				endif;
				
				if( $get_reference->dstrUmur <> "" ):
					$dstrUmur = " {$get_reference->OperatorUmur1} {$dstrUmur}";
				endif;
				if( $get_reference->dStrUmur2 <> ""  ):
					$dStrUmur2 = "{$get_reference->OperatorUmur2} {$dStrUmur2}";
				endif;
				if( $get_reference->dstrUmur <> "" ):
					$dStrUmur2 = " AND  {$dStrUmur2}";
				endif;
				
				if( $get_reference->Umur_Th_2 >= 100 ):
					$dStrUmur2 = "";
				endif;
				
				$pStrNilaiRujukanKeterangan = sprintf("%s %s%s", @$dStrSex, @$dstrUmur, @$dStrUmur2);
			
			else:
				$pStrNilaiRujukanKeterangan = sprintf("%s %s", @$dStrSex, $get_reference->KelompokUmur );
			endif;
			
			$pStrNilaiRujukanKeterangan .= ' '.$get_reference->Keterangan;
		endif;
		
		# di edit 11 JUN 2014 ----- Keterangan dihilangkan
    	//$pStrNilaiRujukanKeterangan = "";
		
		if( $pStrJenisTest == "HBSAG" || $pStrJenisTest == "AHCV" || $pStrJenisTest == "BILTS" ):
			$get_explanation = $_ci->test_type_detail_m->get_one( $pStrJenisTest );
			
			$pStrNilaiRujukanKeterangan = (@$get_explanation->Keterangan <> '' && !empty($get_explanation->Keterangan)) ? $get_explanation->Keterangan : '';
		endif;
		
		return (object)['NilaiRujukan' => @$pStrNilaiRujukan, 'NilaiRujukanKeterangan' => @$pStrNilaiRujukanKeterangan];
	}
	
	public static function create_examination_test($item, $header, $results)
	{
		$_ci = self::ci();
		$_ci->load->model('cashier_model');
		$_ci->load->model('laboratory_m');
		
		if( (boolean) $_ci->cashier_model->count_all(['NoReg' => $item->NoReg, 'Batal' => 0]) ) 
		{
			return ["status" => 'error', "message" => "Tidak dapat melakukan perubahan hasil pemeriksaan laboratorium <br/>Karena sudah ada closing kasir."];
		}
		
		$_ci->db->trans_begin();	
		
			$_test_sample = [
				'NoSystem' => $item->NoBill, 
				'Tanggal' => date('Y-m-d'), 
				'NRM' => $item->NRM, 
				'PasienNama' => $item->NamaPasien, 
				'PasienAlamat' => $item->Alamat, 
				'TglLahir' => $item->TglLahir, 
				'JenisKelamin' => $item->JenisKelamin, 
				'SampleID' => $header->SampleID, 
				'JenisSample' => $header->JenisSample, 
				'Ruang' => '', 
				'Kelas' => '', 
				'Keterangan' => $header->Keterangan, 
				'MesinID' => '',
				'Color' => '',
				'Clarity' => '',
				'Pasien_UmurTh' => $item->Pasien_UmurTh,
				'Pasien_UmurBln' => $item->Pasien_UmurBln,
				'Pasien_UmurHr' => $item->Pasien_UmurHr,
				'Urgent' => 1
			];
			$_ci->lis_test_sample_model->create( $_test_sample );
			
			$_ci->laboratory_m->update(['adahasil' => 1], $item->NoBill);
			
			foreach($results as $row):
				$row = (object) $row;
				$_test_type = [
					'Nilai' => $row->Nilai, 
					'Satuan' => $row->Satuan, 
					'NilaiRujukan' => $row->NilaiRujukan, 
					'Keterangan' => $row->Keterangan, 
					'HasilTidakNormal_Flag' => 'N', 
					'StatusHasil' => '', 
					'Operator' => '', 
					'NoSystem' => $item->NoBill, 
					'SampelID' => $header->SampleID, 
					'TestID' => $row->TestID,
					'Harga' => $row->Harga,
					'NamaTest' => $row->NamaTest,
					'MesinID' => $row->MesinID
				];
				$_ci->lis_test_type_model->create( $_test_type );
			endforeach;
		
		if ($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return ["status" => 'error', "message" => lang('global:created_failed'), "code" => 500 ];
		}
		
		$_ci->db->trans_commit();
		return ["status" => 'success', "message" => lang('global:created_successfully'), "code" => 200];
	}
	
	public static function update_examination_test($item, $header, $results)
	{
		$_ci = self::ci();
		$_ci->load->model('cashier_model');
		$_ci->load->model('laboratory_m');
		
		if( (boolean) $_ci->cashier_model->count_all(['NoReg' => $item->NoReg, 'Batal' => 0]) ) 
		{
			return ["status" => 'error', "message" => "Tidak dapat melakukan perubahan hasil pemeriksaan laboratorium <br/>Karena sudah ada closing kasir."];
		}
		
		$_ci->db->trans_begin();	
		
			$_test_sample = [
				'Tanggal' => date('Y-m-d'), 
				'NRM' => $item->NRM, 
				'PasienNama' => $item->NamaPasien, 
				'PasienAlamat' => $item->Alamat, 
				'TglLahir' => $item->TglLahir, 
				'JenisKelamin' => $item->JenisKelamin, 
				'JenisSample' => $header->JenisSample, 
				'Ruang' => '', 
				'Kelas' => 'XX', 
				'Keterangan' => $header->Keterangan, 
				'MesinID' => '',
				'Color' => '',
				'Clarity' => '',
				'Pasien_UmurTh' => $item->Pasien_UmurTh,
				'Pasien_UmurBln' => $item->Pasien_UmurBln,
				'Pasien_UmurHr' => $item->Pasien_UmurHr,
				'Urgent' => 0
			];
			$_ci->lis_test_sample_model->update_by($_test_sample, ['NoSystem' => $item->NoBill, 'SampleID' => $header->SampleID]);
			
			$_ci->laboratory_m->update(['AdaHasil' => 1], $item->NoBill);
			
			foreach($results as $row):
				$row = (object) $row;
				$_test_type = [
					'Nilai' => $row->Nilai, 
					'Satuan' => $row->Satuan, 
					'NilaiRujukan' => $row->NilaiRujukan, 
					'Keterangan' => $row->Keterangan, 
					'HasilTidakNormal_Flag' => !empty($row->HasilTidakNormal_Flag) ? $row->HasilTidakNormal_Flag : 'N', 
					'StatusHasil' => '', 
					'Operator' => '', 
					'NoSystem' => $item->NoBill, 
					'SampelID' => $header->SampleID, 
					'TestID' => $row->TestID,
					'Harga' => $row->Harga,
					'NamaTest' => $row->NamaTest,
					'MesinID' => $row->MesinID
				];
				$_ci->lis_test_type_model->update_by( $_test_type, ['NoSystem' => $item->NoBill, 'SampelID' => $header->SampleID, 'TestID' => $row->TestID]);
			endforeach;
		
		if ($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return ["status" => 'error', "message" => lang('global:updated_failed'), "code" => 500 ];
		}
		
		$_ci->db->trans_commit();
		return ["status" => 'success', "message" => lang('global:updated_successfully'), "code" => 200];
	}
	
	public static function verify_result($item, $header, $results)
	{
		$_ci = self::ci();
		
		//try {
			$collection = new ArrayObject($results);
			foreach($collection as $key => $row ):
				$params = [
					'pStrNoBill' => $item->NoBill, 
					'pStrJenisTest' => $row['TestID'], 
					'pStrSex' => $item->JenisKelamin, 
					'pIntUmur_Th' => $item->Pasien_UmurTh, 
					'pIntUmur_Bln' => $item->Pasien_UmurBln, 
					'pIntUmur_Hr' => $item->Pasien_UmurHr, 
					'pStrTypeKelahiran' => "", 
				];
				$get_reference_value = self::get_reference_value( $params );		
				$results[$key]['NilaiRujukan'] = $get_reference_value->NilaiRujukan;
				
				if($row['TestID'] == "TP2M" || $row['TestID'] == "TP2"):
					$dBlnAda13 = TRUE;
					$dDblNilai13 = $row['Nilai'];
				elseif($row['TestID'] == "ALB2"):
					$dBlnAda14 = TRUE;
					$dDblNilai14 = (float) $row['Nilai'];
					$dStrSampleID = $item->SampelID;
					$dStrMesinID = $item->MesinID;
				elseif($row['TestID'] == config_item('Kode_Glubolin')):
					$dBlnAdaGlobulin = TRUE;
				endif;	
			endforeach;
			
			if(@$dBlnAda13 && @$dBlnAda14 && ! @$dBlnAdaGlobulin ):
				$params = [
					'pStrNoBill' => $item->NoBill, 
					'pStrJenisTest' => config_item('Kode_Glubolin'), 
					'pStrSex' => $item->JenisKelamin, 
					'pIntUmur_Th' => $item->Pasien_UmurTh, 
					'pIntUmur_Bln' => $item->Pasien_UmurBln, 
					'pIntUmur_Hr' => $item->Pasien_UmurHr, 
					'pStrTypeKelahiran' => "", 
				];
				$get_reference_value = self::get_reference_value( $params );
				
				$results[] = [
					'TestID' => config_item('Kode_Glubolin'),
					'Nilai' => $dDblNilai13 - $dDblNilai14,
					'HasilTidakNormal_Flag' => "N",
					'Tampilkan' => 1,
					'SampelID' => $dStrSampleID,
					'MesinID' => $dStrMesinID,
					'NilaiRujukan' => $get_reference_value->NilaiRujukan,
					'Keterangan' => $get_reference_value->NilaiRujukanKeterangan,
				];
			endif;
			
			$collection = new ArrayObject($results);
			## VERIFY Nilai Rujukan
			foreach($collection as $row ):
				
				if($row['TestID'] == 'BEACT'):
					$dStrNilai = explode("- +", $row['Nilai']);
				else:
					$dStrNilai = explode(" - +", $row['Nilai']);
					if(count($dStrNilai) > 2) goto __skip;
					
					$dStrNilai = explode("+", $row['Nilai']);					
					if(count($dStrNilai) > 2) goto __skip;
					
					$dStrNilai = explode("-", $row['Nilai']);
					if(count($dStrNilai) > 2) goto __skip;
					
					$dStrNilai = explode("/", $row['Nilai']);
					if(count($dStrNilai) > 2) goto __skip;
					
					$dStrNilai = explode("'", $row['Nilai']);
					if(count($dStrNilai) > 2) goto __skip;
					
					$dStrNilai = explode("<", $row['Nilai']);
					if(count($dStrNilai) > 2) goto __skip;
				endif;
									
				
				$dStrNilai = explode(">", $row['Nilai']);
				if(count($dStrNilai) > 2): #2
					# Ada Nilai
				else:
					if($row['Nilai'] !== 0): #3
						# Nilai Rujukan Awal
						# Nilai Rujukan Akhir
						
						if($row['TestID'] != 'BEACT'):
							$dStrNilaiRujukan = explode("-", trim($row['NilaiRujukan']));
						else:
							$dStrNilaiRujukan = explode(" - +", trim($row['NilaiRujukan']));
						endif;

						if(count($dStrNilaiRujukan) === 2 && is_numeric($dStrNilaiRujukan[0]) && is_numeric($dStrNilaiRujukan[1])): #4
							$dstrNilaiAwal = trim($dStrNilaiRujukan[0]);
							$dstrNilaiAkhir = trim($dStrNilaiRujukan[1]);
							$dstrNilaiAwal = $dstrNilaiAwal == "" ? 0 : $dstrNilaiAwal;
							$dstrNilaiAkhir = $dstrNilaiAkhir == "" ? 0 : $dstrNilaiAkhir;
							
							if((float)$row['Nilai'] > (float)$dstrNilaiAwal && (float)$row['Nilai'] <= (float)$dstrNilaiAkhir):
								if($row['HasilTidakNormal_Flag'] <> "C"):
									$results[$key]['HasilTidakNormal_Flag'] = "N";
								 endif;
							else:
								if($row['HasilTidakNormal_Flag'] <> "C"):
									$results[$key]['HasilTidakNormal_Flag'] = "A";
								endif;
							endif;

						else: #4
						
							$dStrNilaiRujukan = explode(">=", trim($row['NilaiRujukan']));
							# Nilai Rujukannya Adalah Lebih Besar Sama dengan
							if(count($dStrNilaiRujukan) === 2 && is_numeric($dStrNilaiRujukan[1] )): #5
								// dstrNilaiAwal = dStrNilaiRujukan(0)
								$dstrNilaiAkhir = trim($dStrNilaiRujukan[1]);
								
								if((float)$row['Nilai'] >= (float)$dstrNilaiAkhir):
									if($row['HasilTidakNormal_Flag'] <> "C"):
									   $results[$key]['HasilTidakNormal_Flag'] = "N";									   
									endif;
								else:
									if($row['HasilTidakNormal_Flag'] <> "C"):
										$results[$key]['HasilTidakNormal_Flag'] = "A";
									endif;
								endif;
								
							else: #5
							
								$dStrNilaiRujukan = explode("<=", trim($row['NilaiRujukan']));
								# Nilai Rujukannya Lebih Kecil Sama dengan
								if(count($dStrNilaiRujukan) === 2 && is_numeric($dStrNilaiRujukan[1] )): #6
									// dstrNilaiAwal = dStrNilaiRujukan(0)
									$dstrNilaiAkhir = trim($dStrNilaiRujukan[1]);
																	
									if((float)$row['Nilai'] <= (float)$dstrNilaiAkhir):
										if($row['HasilTidakNormal_Flag'] <> "C"):
										   $results[$key]['HasilTidakNormal_Flag'] = "N";									   
										endif;
									else:
										if($row['HasilTidakNormal_Flag'] <> "C"):
											$results[$key]['HasilTidakNormal_Flag'] = "A";
										endif;
									endif;
									
								else: #6
								
									$dStrNilaiRujukan = explode(">", trim($row['NilaiRujukan']));
									# Nilai Rujukannya Adalah Lebih Besar
									if(count($dStrNilaiRujukan) === 2 && is_numeric($dStrNilaiRujukan[1] )): #7
										//dstrNilaiAwal = dStrNilaiRujukan(0)
										$dstrNilaiAkhir = trim($dStrNilaiRujukan[1]);									 
										 
										if((float)$row['Nilai'] > (float)$dstrNilaiAkhir):
											if($row['HasilTidakNormal_Flag'] <> "C"):
											   $results[$key]['HasilTidakNormal_Flag'] = "N";									   
											endif;
										 else:
											if($row['HasilTidakNormal_Flag'] <> "C"):
												$results[$key]['HasilTidakNormal_Flag'] = "A";
											endif;
										 endif;
										 
									else: #7
									
										$dStrNilaiRujukan = explode("<", trim($row['NilaiRujukan']));
										# Nilai Rujukannya Adalah Lebih Kecil
										if(count($dStrNilaiRujukan) === 2 && is_numeric($dStrNilaiRujukan[1])): #8
											// dstrNilaiAwal = dStrNilaiRujukan(0)
											$dstrNilaiAkhir = trim($dStrNilaiRujukan[1]);
											 
											if($row['Nilai'] == $row['NilaiRujukan']): #9
												if($row['HasilTidakNormal_Flag'] <> "C"):
												   $results[$key]['HasilTidakNormal_Flag'] = "N";									   
												endif;
											 else: #9
												if((float)$row['Nilai'] < (float)$dstrNilaiAkhir): #10
													if($row['HasilTidakNormal_Flag'] <> "C"):
													   $results[$key]['HasilTidakNormal_Flag'] = "N";									   
													endif;
												else: #10
													if($row['HasilTidakNormal_Flag'] <> "C"):
														$results[$key]['HasilTidakNormal_Flag'] = "A";
													endif;
												endif; #10
											endif; #9
										endif; #8
										
									endif; #7
								endif; #6
							endif; #5
						
						endif; #4
						
					endif; #3
					
				endif; #2

				__skip: 
			endforeach;
			
		//} catch (Exception $e) {
			
			//return ["status" => 'error', "message" => "Proses Verifikasi Gagal dilakukan: {$e} ", "code" => 500 ];
		//}		

		return ["status" => 'success', "collection" => $results, "message" => "Proses Verifikasi Berhasil dilakukan", "code" => 200];
	}
	
	public static function verify_result_old($item, $header, $results)
	{
		$_ci = self::ci();
		
		//try {
			$collection = new ArrayObject($results);
			foreach($collection as $key => $row ):
				$params = [
					'pStrNoBill' => $item->NoBill, 
					'pStrJenisTest' => $row['TestID'], 
					'pStrSex' => $item->JenisKelamin, 
					'pIntUmur_Th' => $item->Pasien_UmurTh, 
					'pIntUmur_Bln' => $item->Pasien_UmurBln, 
					'pIntUmur_Hr' => $item->Pasien_UmurHr, 
					'pStrTypeKelahiran' => "", 
				];
				$get_reference_value = self::get_reference_value( $params );		
				$results[$key]['NilaiRujukan'] = $get_reference_value->NilaiRujukan;
				
				if($row['TestID'] == "TP2M" || $row['TestID'] == "TP2"):
					$dBlnAda13 = TRUE;
					$dDblNilai13 = $row['Nilai'];
				elseif($row['TestID'] == "ALB2"):
					$dBlnAda14 = TRUE;
					$dDblNilai14 = (float) $row['Nilai'];
					$dStrSampleID = $item->SampelID;
					$dStrMesinID = $item->MesinID;
				elseif($row['TestID'] == config_item('Kode_Glubolin')):
					$dBlnAdaGlobulin = TRUE;
				endif;	
			endforeach;
			
			if(@$dBlnAda13 && @$dBlnAda14 && ! @$dBlnAdaGlobulin ):
				$params = [
					'pStrNoBill' => $item->NoBill, 
					'pStrJenisTest' => config_item('Kode_Glubolin'), 
					'pStrSex' => $item->JenisKelamin, 
					'pIntUmur_Th' => $item->Pasien_UmurTh, 
					'pIntUmur_Bln' => $item->Pasien_UmurBln, 
					'pIntUmur_Hr' => $item->Pasien_UmurHr, 
					'pStrTypeKelahiran' => "", 
				];
				$get_reference_value = self::get_reference_value( $params );
				
				$results[] = [
					'TestID' => config_item('Kode_Glubolin'),
					'Nilai' => $dDblNilai13 - $dDblNilai14,
					'HasilTidakNormal_Flag' => "N",
					'Tampilkan' => 1,
					'SampelID' => $dStrSampleID,
					'MesinID' => $dStrMesinID,
					'NilaiRujukan' => $get_reference_value->NilaiRujukan,
					'Keterangan' => $get_reference_value->NilaiRujukanKeterangan,
				];
			endif;
			
			$collection = new ArrayObject($results);
			## VERIFY Nilai Rujukan
			foreach($collection as $row ):
				
				if($row['TestID'] == 'BEACT'):
					$dStrNilai = explode("- +", $row['Nilai']);
				else:
					$dStrNilai = explode(" - +", $row['Nilai']);
					if($dStrNilai[0] > 0) goto __skip;
					
					$dStrNilai = explode("+", $row['Nilai']);					
					if($dStrNilai[0] > 0) goto __skip;
					
					$dStrNilai = explode("-", $row['Nilai']);
					if($dStrNilai[0] > 0) goto __skip;
					
					$dStrNilai = explode("/", $row['Nilai']);
					if($dStrNilai[0] > 0) goto __skip;
					
					$dStrNilai = explode("'", $row['Nilai']);
					if($dStrNilai[0] > 0) goto __skip;
					
					$dStrNilai = explode("<", $row['Nilai']);
				endif;
									
				if($dStrNilai[0] > 0): #1
					# Ada Nilai					
				else:
					$dStrNilai = explode(">", $row['Nilai']);
					if($dStrNilai[0] > 0): #2
						# Ada Nilai
					else:
						if($row['Nilai'] != 0): #3
							# Nilai Rujukan Awal
							# Nilai Rujukan Akhir
							
							if($row['TestID'] != 'BEACT'):
								$dStrNilaiRujukan = explode("-", trim($row['NilaiRujukan']));
							else:
								$dStrNilaiRujukan = explode(" - +", trim($row['NilaiRujukan']));
							endif;
							
							if($dStrNilai[0] > 0): #4
								$dstrNilaiAwal = trim($dStrNilaiRujukan[0]);
								$dstrNilaiAkhir = trim($dStrNilaiRujukan[$dStrNilaiRujukan[0]]);
								$dstrNilaiAwal = $dstrNilaiAwal == "" ? 0 : $dstrNilaiAwal;
								$dstrNilaiAkhir = $dstrNilaiAkhir == "" ? 0 : $dstrNilaiAkhir;
								
								if((float)$row['Nilai'] >= (float)$dstrNilaiAwal && (float)$row['Nilai'] <= (float)$dstrNilaiAkhir):
									if($row['HasilTidakNormal_Flag'] <> "C"):
										$results[$key]['HasilTidakNormal_Flag'] = "N";
									 endif;
								else:
									if($row['HasilTidakNormal_Flag'] <> "C"):
										$results[$key]['HasilTidakNormal_Flag'] = "A";
									endif;
								endif;

							else: #4
							
								$dStrNilaiRujukan = explode(">=", trim($row['NilaiRujukan']));
								# Nilai Rujukannya Adalah Lebih Besar Sama dengan
								if($dStrNilaiRujukan[0] > 0): #5
									// dstrNilaiAwal = dStrNilaiRujukan(0)
									$dstrNilaiAkhir = trim($dStrNilaiRujukan[$dStrNilaiRujukan[0]]);
									
									if((float)$row['Nilai'] >= (float)$dstrNilaiAkhir):
										if($row['HasilTidakNormal_Flag'] <> "C"):
										   $results[$key]['HasilTidakNormal_Flag'] = "N";									   
										endif;
									else:
										if($row['HasilTidakNormal_Flag'] <> "C"):
											$results[$key]['HasilTidakNormal_Flag'] = "A";
										endif;
									endif;
									
								else: #5
								
									$dStrNilaiRujukan = explode("<=", trim($row['NilaiRujukan']));
									# Nilai Rujukannya Lebih Kecil Sama dengan
									if($dStrNilaiRujukan[0] > 0): #6
										// dstrNilaiAwal = dStrNilaiRujukan(0)
										$dstrNilaiAkhir = trim($dStrNilaiRujukan[$dStrNilaiRujukan[0]]);
																		
										if((float)$row['Nilai'] <= (float)$dstrNilaiAkhir):
											if($row['HasilTidakNormal_Flag'] <> "C"):
											   $results[$key]['HasilTidakNormal_Flag'] = "N";									   
											endif;
										else:
											if($row['HasilTidakNormal_Flag'] <> "C"):
												$results[$key]['HasilTidakNormal_Flag'] = "A";
											endif;
										endif;
										
									else: #6
									
										$dStrNilaiRujukan = explode(">", trim($row['NilaiRujukan']));
										# Nilai Rujukannya Adalah Lebih Besar
										if($dStrNilaiRujukan[0] > 0): #7
											//dstrNilaiAwal = dStrNilaiRujukan(0)
											$dstrNilaiAkhir = trim($dStrNilaiRujukan[$dStrNilaiRujukan[0]]);									 
											 
											if((float)$row['Nilai'] > (float)$dstrNilaiAkhir):
												if($row['HasilTidakNormal_Flag'] <> "C"):
												   $results[$key]['HasilTidakNormal_Flag'] = "N";									   
												endif;
											 else:
												if($row['HasilTidakNormal_Flag'] <> "C"):
													$results[$key]['HasilTidakNormal_Flag'] = "A";
												endif;
											 endif;
											 
										else: #7
										
											$dStrNilaiRujukan = explode("<", trim($row['NilaiRujukan']));
											# Nilai Rujukannya Adalah Lebih Kecil
											if($dStrNilaiRujukan[0] > 0): #8
												// dstrNilaiAwal = dStrNilaiRujukan(0)
												$dstrNilaiAkhir = trim($dStrNilaiRujukan[$dStrNilaiRujukan[0]]);
												 
												if($row['Nilai'] == $row['NilaiRujukan']): #9
													if($row['HasilTidakNormal_Flag'] <> "C"):
													   $results[$key]['HasilTidakNormal_Flag'] = "N";									   
													endif;
												 else: #9
													if((float)$row['Nilai'] < (float)$dstrNilaiAkhir): #10
														if($row['HasilTidakNormal_Flag'] <> "C"):
														   $results[$key]['HasilTidakNormal_Flag'] = "N";									   
														endif;
													else: #10
														if($row['HasilTidakNormal_Flag'] <> "C"):
															$results[$key]['HasilTidakNormal_Flag'] = "A";
														endif;
													endif; #10
												endif; #9
											endif; #8
											
										endif; #7
									endif; #6
								endif; #5
							
							endif; #4
							
						endif; #3
						
					endif; #2
					
				endif; #1
				
				__skip: 
			endforeach;
			
		//} catch (Exception $e) {
			
			//return ["status" => 'error', "message" => "Proses Verifikasi Gagal dilakukan: {$e} ", "code" => 500 ];
		//}		
		
		return ["status" => 'success', "collection" => $results, "message" => "Proses Verifikasi Berhasil dilakukan", "code" => 200];
	}
	
	/*
		## CETAK HASIL
		exec sp_reset_connection
		SELECT COUNT(SIMtrRegistrasi.NoReg) AS jml FROM SIMtrRegistrasi  LEFT OUTER JOIN SIMtrDataRegPasien    ON SIMtrRegistrasi.NoReg=SIMtrDataRegPasien.NoReg  left OUTER JOIN SIMmSection on SIMtrDataRegPasien.SectionID=SIMmSection.SectionID   left OUTER JOIN SIMmSection Asal on SIMtrDataRegPasien.SectionAsalID=ASal.SectionID  LEFT OUTER JOIN mPasien  ON SIMtrRegistrasi.NRM=mPasien.NRM  WHERE  SIMtrRegistrasi.Batal=0 and SIMmSection.SectionName='LABORATORIUM' and Sudahperiksa=1 and SIMtrDataRegPasien.batal=0
		
		SELECT TOP 50 SIMtrDataRegPasien.Tanggal Registrasi_Tanggal ,SIMtrDataRegPasien.Kamar,SIMtrDataRegPasien.NoBed , SIMtrRegistrasi.NoReg NoReg , SIMtrRegistrasi.TglReg TglReg , SIMtrRegistrasi.JamReg JamReg , Pasien.NRM  , Pasien.NamaPasien  , Pasien.JenisKelamin NRM_JenisKelamin,Pasien.Alamat,Pasien.Phone , mCustomer.Nama_Customer NRM_NamaPerusahaan , Pasien.NoKartu NRM_NoKartu , Pasien.PasienLoyal NRM_PasienLoyal,Dimutasikan , Pasien.PasienVVIP NRM_PasienVVIP,SIMtrDataRegPasien.Out,SIMtrDataRegPasien.SudahPeriksa,SIMmKelas.NamaKelas,SIMtrDataRegPasien.Nomor,SIMmJenisKerjasama.JenisKerjasama as JenisPasienKerjasama,SIMtrDataRegPasien.NoBill,SIMtrDataRegPasien.DokterID,SIMtrDataRegPasien.Kamar,SIMtrDataRegPasien.RJ,SIMmKelas.NamaKelas,SectionAsal.SectionName as SectionNameAsal,SIMtrDataRegPasien.Nomor,SIMtrDataRegPasien.MCU,SIMtrDataRegPasien.NOMCU,SIMtrDataRegPasien.Memo,Pasien.PasienBlackList,SIMtrRegistrasi.VIP , SIMtrRegistrasi.PasienAsuransi,ASS.Nama_Customer as Asuransi,SIMtrRegistrasi.CaseNo,SIMtrRegistrasi.ReffNo,SIMtrRegistrasi.StatusBayar,Asal.SectionName as SectionAsal,SIMtrRegistrasi.RawatInap  FROM  (    SIMtrRegistrasi    LEFT OUTER JOIN SIMtrDataRegPasien    ON SIMtrRegistrasi.NoReg=SIMtrDataRegPasien.NoReg  left outer join SIMmSection on SIMtrDataRegPasien.SectionID=SIMmSection.SectionID  left outer join SIMmSection SectionAsal on SIMtrDataRegPasien.SectionAsalID=SectionAsal.SectionID  LEFT OUTER JOIN mCUstomer ASS on SIMtrRegistrasi.AsuransiID=ASS.Kode_Customer  )  LEFT OUTER JOIN mPasien Pasien ON SIMtrRegistrasi.NRM=Pasien.NRM  left outer join mCustomer on Pasien.CompanyID=mCustomer.Kode_Customer  left outer join SIMmKelas ON SIMtrDataRegPasien.KelasID=SIMmKelas.KelasID  LEFT OUTER JOIN SIMmJenisKerjasama ON SIMtrDataRegPasien.JenisPasienID=SIMmJenisKerjasama.JenisKerjasamaID  left outer join SIMmSection Asal on SIMtrDataRegPasien.SectionASalID=Asal.SectionID  WHERE  SIMtrRegistrasi.Batal=0 and SIMmSection.SectionName='LABORATORIUM' and Sudahperiksa=1 and SIMtrDataRegPasien.batal=0 ORDER BY Registrasi_Tanggal ASC
		
		Select Batal from SIMtrRJ where NoBukti='181122LAB-000001'
		
		exec sp_reset_connection
		
		Select KualiatasBahan from LISmKualitasBahan ORDER BY KualiatasBahan asc
		
		exec sp_reset_connection
		
		SELECT SIMtrRJ.* FROM SIMtrRJ   WHERE NoBukti='181122LAB-000001'
		
		SELECT * FROM mPasien WHERE NRM='00.00.11'
		
		SELECT * FROM mDokter WHERE DokterID='DOK-003'
		
		SELECT * FROM mAnalis WHERE AnalisID='DOK-001'
		
		SELECT COUNT(*) AS jml FROM LISTtrTestJenisTest   WHERE NoSystem='181122LAB-000001' AND Batal=0 
		
		SELECT LISTtrTestJenisTest.NoSystem, LISTtrTestJenisTest.SampelID,LISTtrTestJenisTest.MesinID, LISTtrTestJenisTest.TestID,LISTtrTestJenisTest.Keterangan  FROM LISTtrTestJenisTest inner join LISmJenisTest on LISTtrTestJenisTest.TEstID=LISmJenisTest.TestID   WHERE NoSystem='181122LAB-000001' AND Batal=0  ORDER BY LISmJenisTest.KategoriTestiID asc,LISmJenisTest.NoUrut asc
		
		SELECT * FROM LISTtrTestJenisTest  WHERE NoSystem='181122LAB-000001' AND SampelID='Z01' AND MesinID='0' AND TestID='HCG-01' AND Keterangan='pas mantap'
		
		SELECT * FROM Vw_JenisTest WHERE TestID='HCG-01'
		
		select  LIStrGambarDL.Gambar, LIStrGambarDL.BarcodeID  from LISTtrTestJenisTest inner join LIStrGambarDL on LISTtrTestJenisTest.NoSystem=LIStrGambarDL.NoSystem  where LISTtrTestJenisTest.NoSystem ='181122LAB-000001' and LISTtrTestJenisTest.Tampilkan=1
		
		exec sp_reset_connection
		
		SELECT * FROM msupplier WHERE Kode_Supplier='DOK-001'
		
		exec sp_reset_connection
		
		exec sp_reset_connection
		
		
		
		### VERIFY HASIL
		
		exec sp_reset_connection
	
		Select * from LISmNilaiRujukan  WHERE TestID='HCG-01' AND (Sex='M' OR Sex='A') AND TypeKelahiran='Normal' AND UmurTotal_Hr<512 AND UmurTotal_Hr2>512
		
		exec sp_reset_connection
										
		
		Select * from LISmNilaiRujukan  WHERE TestID='' AND (Sex='M' OR Sex='A') AND TypeKelahiran='Normal' AND UmurTotal_Hr<512 AND UmurTotal_Hr2>512
		
		exec sp_reset_connection
		
		exec sp_reset_connection
		
	
		
		
		## SAVE CETAK HASIL
		
		Select SIMtrKasir.NoBukti,datediff(day,SIMtrKasir.Tanggal,getdate()) as Beda from SIMtrKasir inner join SIMtrRJ on SIMtrKasir.Noreg=SIMtrRJ.NOreg where SIMtrKasir.Noreg='181122LAB-000001' and SIMtrKasir.Batal=0 
		
		exec sp_reset_connection
		
		set implicit_transactions on 
		
		UPDATE SIMtrRJ  SET NoBukti='181122LAB-000001' ,Tanggal='2018-11-22 00:00:00' ,DokterID='DOK-003' ,RawatInap=0 ,LokasiPasien='' ,Keterangan='' ,NRM='00.00.11',AnalisID='DOK-001',NamaPasien='HARANALENDRA I GST AGUNG MADE',JenisKelamin='M',Umur_Th=1,Umur_Bln=4,Umur_Hr=27,Accept=1,PAPSmear=0,TglLahir='25/Jun/2017',Memo='',CatatanHasil='',PublishHasil=0,UserIDPublish=1876,TglPublish='2018-12-05 11:10:20' WHERE NoBukti='181122LAB-000001'
		
		EXEC InsertUserActivities '2018-11-22','2018-12-05 11:10:20',1876,'181122LAB-000001','UPDATE HASIL TEST LAB.#181122LAB-000001# PUBLISH ORIG:False# PUBLISH CURR:False','SIMtrRJ'
		
		UPDATE LISTtrTestJenisTest  SET Nilai='12' ,Satuan='mIU/ml' ,NilaiRujukan='-' ,HasilTidakNormal_Flag='A' ,Keterangan='pas mantap' ,NoSystem='181122LAB-000001' ,SampelID='Z01' ,TestID='HCG-01',NamaTest='HCG',Tampilkan=1 WHERE NoSystem='181122LAB-000001' AND SampelID='Z01' AND TestID='HCG-01' AND Keterangan='pas mantap'
		
		IF @@TRANCOUNT > 0 COMMIT TRAN
		
		exec sp_reset_connection
		
		SELECT SIMtrRJ.* FROM SIMtrRJ   WHERE NoBukti='181122LAB-000001'
		
		SELECT * FROM mPasien WHERE NRM='00.00.11'
		
		SELECT * FROM mDokter WHERE DokterID='DOK-003'
		
		SELECT * FROM mAnalis WHERE AnalisID='DOK-001'
		
		SELECT COUNT(*) AS jml FROM LISTtrTestJenisTest   WHERE NoSystem='181122LAB-000001' AND Batal=0 
		
		SELECT LISTtrTestJenisTest.NoSystem, LISTtrTestJenisTest.SampelID,LISTtrTestJenisTest.MesinID, LISTtrTestJenisTest.TestID,LISTtrTestJenisTest.Keterangan  FROM LISTtrTestJenisTest inner join LISmJenisTest on LISTtrTestJenisTest.TEstID=LISmJenisTest.TestID   WHERE NoSystem='181122LAB-000001' AND Batal=0  ORDER BY LISmJenisTest.KategoriTestiID asc,LISmJenisTest.NoUrut asc
		
		SELECT * FROM LISTtrTestJenisTest  WHERE NoSystem='181122LAB-000001' AND SampelID='Z01' AND MesinID='0' AND TestID='HCG-01' AND Keterangan='pas mantap'
		
		SELECT * FROM Vw_JenisTest WHERE TestID='HCG-01'
		
		select  LIStrGambarDL.Gambar, LIStrGambarDL.BarcodeID  from LISTtrTestJenisTest inner join LIStrGambarDL on LISTtrTestJenisTest.NoSystem=LIStrGambarDL.NoSystem  where LISTtrTestJenisTest.NoSystem ='181122LAB-000001' and LISTtrTestJenisTest.Tampilkan=1
		
		exec sp_reset_connection
		
	*/
	
	public static function insert_warehouse_fifo( Array $args )
	{
		$defaults = [
			'location_id' => 0, 
			'item_id' => 0,  
			'item_unit_code' => 0,  
			'qty' => 0, 
			'price' => 0,  
			'conversion' => 1,  
			'evidence_number' => '',  
			'trans_type_id' => 0,
			'in_out_state' => 1,
			'trans_date' => date('Y-m-d'),  
			'exp_date' => date('Y-m-d'),  
			'item_type_id' => 0, 
		];
		
		$arguments = array_merge( $defaults, $args );
		extract($arguments);
	
		$price = $price / $conversion;
		
		/*EXEC IsiKartuGudangFIFO 
			Lokasi_ID, Barang_Id, 'Kode_Satuan_Stok', dIntQtyTerima, Harga_Beli / Barang_Konversi,
			'Penerimaan_No_Penerimaan', jenisTranskasiID, in_out_state, Penerimaan_Tgl_Penerimaan, Exp_Date, JenisBarangID
			in state = 1
			out state= 0	
		*/
		self::ci()->db->query("
				EXEC IsiKartuGudangFIFO 
					{$location_id}, {$item_id}, '{$item_unit_code}', {$qty}, {$price},
					'{$evidence_number}', {$trans_type_id}, {$in_out_state}, '{$trans_date}', '{$exp_date}', {$item_type_id} 
			");
		
	}
	
	private static function & ci()
	{
		return get_instance();
	}
}
