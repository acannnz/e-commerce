<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class poly_helper
{	
	private static $_ci;
	private static $user_auth;
	private static $weekDay = ["MINGGU", "SENIN", "SELASA", "RABU", "KAMIS", "JUMAT", "SABTU"];
	
	public static function init()
	{
		self::$_ci = $_ci = self::ci();
		self::$user_auth = $_ci->simple_login->get_user();
	}
	
	public static function create_examination($rj, $diagnosis, $service, $service_component, $service_consumable, $vital, $soap, $nurse, $helper, $checkout, $consult, $patient = NULL, $odontogram)
	{
		self::init();
		$_ci = self::$_ci;
		
		$_ci->db->trans_begin();
			
			$rj['NoBukti'] = $NoBukti = self::gen_evidence_number($rj['SectionID']);
			$rj['Tanggal'] = date('Y-m-d');
			$rj['Jam'] = date('Y-m-d H:i:s');
			$_ci->poly_m->create( $rj );				
					
			if( !empty( $diagnosis)): foreach( $diagnosis as $row ):
				$row['NOBukti'] = $NoBukti;
				$_ci->poly_initial_diagnosis_model->create( $row );
			endforeach; endif;			
			
			if ( !empty($service))
			{
				$Nomor = $_ci->poly_m->get_max_number( "SIMtrRJTransaksi", ["NoBukti" => $rj['NoBukti']], "Nomor" );				
				foreach ( $service as $row )
				{
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
						$val['SectionID'] = $rj['SectionID'];
						$val['Nomor'] = $Nomor; # penomoran component bhp
						$_ci->poly_transaction_pop_model->create( $val );
						
						# Pengurangan Stock
						$section = $_ci->section_model->get_one($rj['SectionID']);
						$_insert_fifo = [
							'location_id' => $section->Lokasi_ID, 
							'item_id' => $val["Barang_ID"],  
							'item_unit_code' => $val["Satuan"],  
							'qty' => $val["Qty"], 
							'price' => $val["Harga"],  
							'conversion' => 1,  
							'evidence_number' => $rj['NoBukti'],  
							'trans_type_id' => 564,
							'in_out_state' => 0,
							'trans_date' => date('Y-m-d'),  
							'exp_date' => date('Y-m-d'),  
							'item_type_id' => 0, 
						];
						self::insert_warehouse_fifo( $_insert_fifo );
						
						// Menyiapkan data kartu gudang unutk bhp yg digunakan
						/*$qty_last_stock = $_ci->poly_m->get_last_stock_warehouse_card( array("Lokasi_ID" => $section->Lokasi_ID, "Barang_ID" => $val["Barang_ID"]) );
						$qty_saldo = $qty_last_stock - $val["Qty"];
						$kartu_gudang = array(
								"Lokasi_ID" => $section->Lokasi_ID,
								"Barang_ID" => $val["Barang_ID"],
								"No_Bukti" => $val["NoBUkti"],
								"JTransaksi_ID" => 564,
								"Tgl_Transaksi" => $bhp["Waktu"],
								"Kode_Satuan" => $val["Satuan"],
								"Qty_Masuk" => 0,
								"Harga_Masuk" =>  0,
								"Qty_Keluar" => $val["Qty"],
								"Harga_Keluar" => $val["Harga"],
								"Qty_Saldo" => $qty_saldo,
								"Harga_Persediaan" => $val["Harga"],
								"Jam" => $bhp["Waktu"],
						);
						$_ci->db->insert("GD_trKartuGudang", $kartu_gudang );*/
					endforeach; 
				}
			}
			
			// Vital Signs
			if((int) $vital['IdVitalSigns'] === 0):
				unset($vital['IdVitalSigns']);
				
				$vital['NoReg'] = $rj['RegNo'];
				$vital['NoPemeriksaan'] = $NoBukti;
				$vital['NRM'] = $rj['NRM'];
				$vital['CreatedBy'] = self::$user_auth->User_ID;
				$vital['CreatedAt'] = date('Y-m-d H:i:s');
				
				$_ci->emr_vital_signs_model->create($vital);
			else:
				$IdVitalSigns = $vital['IdVitalSigns'];
				unset($vital['IdVitalSigns']);
				
				$vital['NoPemeriksaan'] = $NoBukti;
				$vital['UpdatedBy'] = self::$user_auth->User_ID;
				$vital['UpdatedAt'] = date('Y-m-d H:i:s');
				$_ci->emr_vital_signs_model->update($vital, $IdVitalSigns);					
			endif;
			
			
			// SOAP Notes
			unset($soap['IdSOAPNotes']);
			
			$soap['NoReg'] = $rj['RegNo'];
			$soap['NoPemeriksaan'] = $NoBukti;
			$soap['DokterID'] = $rj['DokterID'];
			$soap['NRM'] = $rj['NRM'];
			$soap['CreatedBy'] = self::$user_auth->User_ID;
			$soap['CreatedAt'] = date('Y-m-d H:i:s');
			$_ci->emr_soap_notes_model->create($soap);
			
			if(config_item('bpjs_bridging') == 'TRUE'):
				$_ci->load->model('bpjs/Integration_insurance_model');
				$get_doctor = $_ci->supplier_model->get_by(['Kode_Supplier' => $rj['DokterID']]);
				
				$update_integration = [
					'DokterID' => $rj['DokterID'],
					'DokterIDIntegrasi' => $get_doctor->Kode_Supplier_BPJS
				];
				$_ci->Integration_insurance_model->update_by($update_integration, ['NoReg' => $rj['RegNo']]);
			endif;
			
			// Nurse
			if( !empty( $nurse)): foreach( $nurse as $row):
				$row['NoBukti'] = $NoBukti;
				$_ci->poly_nurse_model->create( $row );
			endforeach;	endif;
			
			$Nomor = $_ci->poly_m->get_max_number( "SIMtrDataRegPasien", array("NoReg" => $rj['RegNo']), "Nomor" );
			if( !empty( $helper)): foreach($helper as $k => $v):
				$v['NoBuktiHeader'] = $NoBukti;
				$v['NoBuktiMemo'] = self::gen_helper_number();
				$_ci->helper_memo_model->create( $v );
				
				$DataRegPasien = [
					'NoReg' => $v['NoReg'],
					'Nomor' => ++$Nomor,
					'Tanggal' => $rj['Tanggal'],
					'Jam' => $rj['Jam'],
					'JenisPasienID' => $v['JenisKerjasamaID'],
					'SectionAsalID' => $rj['SectionID'],
					'SectionID' => $v['SectionTujuanID'],
					'KelasAsalID' => "XX",
					'KelasID' => "XX",
					'Titip' => "0",
					'DokterID' => $v['DokterID'],
					'NoAntri' => 1 + $_ci->poly_m->get_max_number( "SIMtrDataRegPasien", ["SectionID" => $v['SectionTujuanID'], "Tanggal" => date("Y-m-d")], "NoAntri"),
					'SudahPeriksa' => 0,
					'RJ' => 1,
					'UmurThn' => $v['UmurThn'],
					'UmurBln' => $v['UmurBln'],
					'UmurHr' => $v['UmurHr'],
				];
				$_ci->registration_data_model->create( $DataRegPasien );				
			endforeach; endif;
				
			if( !empty( $consult) && $rj['TindakLanjut_KonsulMedik']): 
				foreach ( $consult as $k => $v):
					$DataRegPasien = [
						'NoReg' => $v['NoReg'],
						'Nomor' => ++$Nomor,
						'Tanggal' => $rj['Tanggal'],
						'Jam' => $rj['Jam'],
						'JenisPasienID' => $v['JenisKerjasamaID'],
						'SectionAsalID' => $rj['SectionID'],
						'SectionID' => $v['SectionID'],
						'KelasAsalID' => "XX",
						'KelasID' => "XX",
						'Titip' => "0",
						'DokterID' => $v['DokterID'],
						'NoAntri' => 1 + $_ci->poly_m->get_max_number( "SIMtrDataRegPasien", array("SectionID" => $v['SectionID'], "Tanggal" => date("Y-m-d")), "NoAntri"),
						'SudahPeriksa' => 0,
						'RJ' => 1,
						'UmurThn' => $v['UmurThn'],
						'UmurBln' => $v['UmurBln'],
						'UmurHr' => $v['UmurHr'],
					];
					$_ci->poly_destination_model->create( $v );
					$_ci->registration_data_model->create( $DataRegPasien );				
					
				endforeach;
			endif;
			
			$_update_registration_data = [
				'NoBIll' => $NoBukti, 
				'KdKelasPelayananSection' => '', 
				'DokterID' => $rj['DokterID'], 
				'SudahPeriksa' => 1,
				'DokterRawatID' => $rj['DokterID'],
				'PxKeluar_Pulang' => $checkout['PxKeluar_Pulang'],
				'PxKeluar_Dirujuk' => $checkout['PxKeluar_Dirujuk'],
				'PxKeluar_DirujukKeterangan' => $checkout['PxKeluar_DirujukKeterangan'],
				'PxMeninggal' => $checkout['PxMeninggal'],
				'MeninggalSblm48' => $checkout['MeninggalSblm48'],
				'MeninggalStl48' => $checkout['MeninggalStl48'],
				'MeninggalTgl' => $checkout['MeninggalTgl'],
				'MeninggalJam' => $checkout['MeninggalJam'],
			];
			$_ci->registration_data_model->update_by($_update_registration_data, [ 'NoReg' => $rj['RegNo'], 'SectionID' => $rj['SectionID'] ]);
						
			$_update_registration_data = [
				'Out' => 1,
				'Pulang_Tanggal' => date('Y-m-d'),
				'PxKeluar_Pulang' => $checkout['PxKeluar_Pulang'],
				'PxKeluar_Dirujuk' => $checkout['PxKeluar_Dirujuk'],
				'PxKeluar_DirujukKeterangan' => $checkout['PxKeluar_DirujukKeterangan'],
				'PxMeninggal' => $checkout['PxMeninggal'],
				'MeninggalSblm48' => $checkout['MeninggalSblm48'],
				'MeninggalStl48' => $checkout['MeninggalStl48'],
				'MeninggalTgl' => $checkout['MeninggalTgl'],
				'MeninggalJam' => $checkout['MeninggalJam'],
			];
			$_ci->registration_data_model->update_by($_update_registration_data, ['NoReg' => $rj['RegNo']]);
			
			$_update_registration = [
				// 'StatusPeriksa' => 'Belum',
				'StatusPeriksa' => 'Sudah',
				'DokterRawatID' => $rj['DokterID'],
				'PxKeluar_Pulang' => $checkout['PxKeluar_Pulang'],
				'PxKeluar_Dirujuk' => $checkout['PxKeluar_Dirujuk'],
				'PxKeluar_DirujukKeterangan' => $checkout['PxKeluar_DirujukKeterangan'],
				'PxMeninggal' => $checkout['PxMeninggal'],
				'MeninggalSblm48' => $checkout['MeninggalSblm48'],
				'MeninggalStl48' => $checkout['MeninggalStl48'],
				'MeninggalTgl' => $checkout['MeninggalTgl'],
				'MeninggalJam' => $checkout['MeninggalJam'],
			];				
			$_ci->registration_model->update($_update_registration, $rj['RegNo']);		
			
			if($rj['TindakLanjutCekUpUlang']):
				$_get_registration_data = $_ci->registration_data_model->get_by([ 'NoReg' => $rj['RegNo'], 'SectionID' => $rj['SectionID'] ]);
				$_get_patient = $_ci->patient_model->get_one( $rj['NRM'] );
				$_reservation = [
					'NoReservasi' => self::gen_reservation_number(),
					'Tanggal' => date('Y-m-d'),
					'Jam' => date('Y-m-d H:i:s'),
					'User_ID' => self::$user_auth->User_ID,
					'PasienBaru'=> 0,
					'NRM' => $_get_patient->NRM,
					'Nama' => $_get_patient->NamaPasien,
					'Alamat' => $_get_patient->Alamat,
					'Phone' => $_get_patient->Phone,
					'Email' => $_get_patient->Email,
					'TanggalLahir' => $_get_patient->TglLahir,
					'JenisKerjasamaID' => $_get_patient->JenisKerjasamaID,
					'KelasID' => 'XX',					
					'Registrasi' => 0,
					'Batal' => 0, 
					'Paid' => 0,
					'TipeReservasi' => 'RESERVASI CHECKUP POLI',
					'UntukSectionID' => $rj['SectionID'],
					'UntukDokterID' => $rj['DokterID'], 
					'UntukTanggal' => $rj['TglCekUp'], 
					'WaktuID' => $_get_registration_data->WaktuID,
					'UntukHari' => self::$weekDay[ date("w", strtotime($rj['TglCekUp'])) ], 
					'NoUrut' => self::get_reservation_queue( $rj['SectionID'], $rj['DokterID'], $rj['TglCekUp'], $_get_registration_data->WaktuID ),
				];
				$_ci->reservation_model->create($_reservation);
			endif;			
			
			if($rj['TindakLanjut_RI'] == 1):
				$_update_registration = [
					'SectionID' => $rj['SectionID'], 
					'SectionPerawatanID' => $rj['SectionID'],
					'StatusPeriksa' => 'Sudah'
				];
				$_update_registration['AkanRI'] = 1;
				$_update_registration['AkanRISectionID'] = $rj['SectionID'];
				$_update_registration['DokterRawatID'] = $rj['Konsul_DOkterID'];
				$_update_registration['AkanRIDokterID'] = $rj['Konsul_DOkterID'];
				
				$_ci->registration_model->update($_update_registration, $rj["RegNo"]);
			endif;
			
			if(!empty($patient)):
				$_ci->patient_model->update($patient, $rj['NRM'] );
			endif;
				
			if (!empty($odontogram)) :
				foreach ($odontogram as $k => $v) :
					$dataOdontogram = [
						'NoBukti' => $v['NoBukti'],
						'NoReg' => $v['NoReg'],
						'NRM' => $v['NRM'],
						'Kode_Supplier' => $v['Kode_Supplier'],
						'SectionID' => $v['SectionID'],
						'Tooth' => $v['Tooth'],
						'Odontogram_ID' => $v['Odontogram_ID'],
						'Note' => $v['Note'],
						'Created_at' => date('Y-m-d H:i:s'),
						'Created_by' =>
						self::$user_auth->User_ID
					];
	
					$_ci->db->insert("SIMtrEMROdontogram", $dataOdontogram);
	
				endforeach;
			endif;

		if ($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => lang('global:created_failed'),
				"code" => 500
			];
		}
		
		//$_ci->db->trans_rollback();
		$_ci->db->trans_commit();
		return [
			"NoBukti" => $NoBukti,
			"status" => 'success',
			"message" => lang('global:created_successfully'),
			"code" => 200
		];
	}
	
	public static function update_examination($rj, $diagnosis, $service, $service_component, $service_consumable, $vital, $soap, $nurse, $helper, $checkout, $consult, $patient = NULL, $odontogram)
	{
		self::init();
		$_ci = self::$_ci;
		
		$_ci->db->trans_begin();
			
			$_ci->poly_m->update( $rj, $rj['NoBukti']);				
							
			$_ci->poly_initial_diagnosis_model->delete( $rj['NoBukti']);
			if( !empty( $diagnosis)): foreach( $diagnosis as $row ):
				$row['NOBukti'] = $rj['NoBukti'];
				$_ci->poly_initial_diagnosis_model->create( $row );
			endforeach; endif;		
			
			$_ci->poly_transaction_detail_model->delete($rj['NoBukti']);
			$_ci->poly_transaction_pop_model->delete($rj['NoBukti']);
			$_ci->poly_transaction_model->delete($rj['NoBukti']);

			if ( !empty($service))
			{
				// Ketika Edit disimpan, maka Hapus semua data service dan simpan dengan data service yg baru, 
				// Return barang (BHP), dan simpan data component juga BHP jika ada
				$section = $_ci->section_model->get_one($rj['SectionID']);
				$return_service_consumable = $_ci->poly_transaction_pop_model->get_all(NULL, 0, ['NoBUkti' => $rj['NoBukti']], TRUE);
				foreach( $return_service_consumable as $val )
				{
					$_insert_fifo = [
						'location_id' => $section->Lokasi_ID, 
						'item_id' => $val["Barang_ID"],  
						'item_unit_code' => $val["Satuan"],  
						'qty' => $val["Qty"], 
						'price' => $val["Harga"],  
						'conversion' => 1,  
						'evidence_number' => $rj['NoBukti'].'-R',  
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
					
				$_ci->poly_transaction_detail_model->delete($rj['NoBukti']);
				$_ci->poly_transaction_pop_model->delete($rj['NoBukti']);
				$_ci->poly_transaction_model->delete($rj['NoBukti']);

				$Nomor = $_ci->poly_m->get_max_number( $_ci->poly_transaction_model->table, ['NoBukti' => $rj['NoBukti']], 'Nomor' );
				$service_insert = array();
				$service_component_insert = array();
				$service_consumable_insert = array();
				
				$section = $_ci->poly_m->get_row_data( "SIMmSection", array("SectionID" => $rj['SectionID'] ));
				foreach ( $service as $row )
				{
					$row['NoBukti'] = $rj['NoBukti'];
					$row['Nomor'] = ++$Nomor; # penomoran jasa 
					$_ci->poly_transaction_model->create( $row );
										
					foreach ( $service_component[$row['JasaID']] as $val ) # penomoran component berdasarkan jasa
					{
						$val['NoBukti'] = $rj['NoBukti'];
						$val['Nomor'] = $Nomor; 
						$_ci->poly_transaction_detail_model->create( $val );
					}

					if(!empty($service_consumable[$row['JasaID']])) foreach( $service_consumable[$row['JasaID']] as $val ) :
						$val['NoBUkti'] = $rj['NoBukti'];
						$val['Nomor'] = $Nomor; # penomoran component bhp
						$_ci->poly_transaction_pop_model->create( $val );
						
						# Pengurangan Stock
						$section = $_ci->section_model->get_one($rj['SectionID']);
						$_insert_fifo = [
							'location_id' => $section->Lokasi_ID, 
							'item_id' => $val["Barang_ID"],  
							'item_unit_code' => $val["Satuan"],  
							'qty' => $val["Qty"], 
							'price' => $val["Harga"],  
							'conversion' => 1,  
							'evidence_number' => $rj['NoBukti'],  
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
			
			// Vital Signs
			$IdVitalSigns = $vital['IdVitalSigns'];
			unset($vital['IdVitalSigns']);
			
			$vital['UpdatedBy'] = self::$user_auth->User_ID;
			$vital['UpdatedAt'] = date('Y-m-d H:i:s');
			$_ci->emr_vital_signs_model->update($vital, $IdVitalSigns);					
			
			
			// SOAP Notes
			$IdSOAPNotes = $soap['IdSOAPNotes'];
			unset($soap['IdSOAPNotes']);
			
			$soap['DokterID'] = $rj['DokterID'];
			$soap['UpdatedBy'] = self::$user_auth->User_ID;
			$soap['UpdatedAt'] = date('Y-m-d H:i:s');
			$_ci->emr_soap_notes_model->update($soap, $IdSOAPNotes);
			
			// Nurse
			$_ci->poly_nurse_model->delete( $rj['NoBukti'] );
			if( !empty( $nurse)): foreach( $nurse as $row):
				$row['NoBukti'] = $rj['NoBukti'];
				$_ci->poly_nurse_model->create( $row );
			endforeach;endif;			
		
			$_ci->registration_data_model->delete_by(['NoReg' => $rj['RegNo'], 'SectionAsalID' => $rj['SectionID']]);
			$_ci->helper_memo_model->delete_by(['NoBuktiHeader' => $rj['RegNo'], 'SectionID' => $rj['SectionID']]);
			$Nomor = $_ci->poly_m->get_max_number($_ci->registration_data_model->table, ['NoReg' => $rj['RegNo']], 'Nomor');	
			if( !empty( $helper)): foreach($helper as $k => $v):
				$v['NoBuktiHeader'] = $NoBukti;
				$v['NoBuktiMemo'] = self::gen_helper_number();
				$_ci->helper_memo_model->create( $v );
				
				$DataRegPasien = [
					'NoReg' => $v['NoReg'],
					'Nomor' => ++$Nomor,
					'Tanggal' => $rj['Tanggal'],
					'Jam' => $rj['Jam'],
					'JenisPasienID' => $v['JenisKerjasamaID'],
					'SectionAsalID' => $rj['SectionID'],
					'SectionID' => $v['SectionTujuanID'],
					'KelasAsalID' => "XX",
					'KelasID' => "XX",
					'Titip' => "0",
					'DokterID' => $v['DokterID'],
					'NoAntri' => 1 + $_ci->poly_m->get_max_number( "SIMtrDataRegPasien", ["SectionID" => $v['SectionTujuanID'], "Tanggal" => date("Y-m-d")], "NoAntri"),
					'SudahPeriksa' => 0,
					'RJ' => 1,
					'UmurThn' => $v['UmurThn'],
					'UmurBln' => $v['UmurBln'],
					'UmurHr' => $v['UmurHr'],
				];
				$_ci->registration_data_model->create( $DataRegPasien );				
			endforeach; endif;
						
			if( !empty( $consult)): 
				$_ci->poly_destination_model->delete($rj['NoBukti']);
				foreach ( $KonsulMedik as $k => $v):
					$DataRegPasien = [
						'NoReg' => $v['NoReg'],
						'Nomor' => ++$Nomor,
						'Tanggal' => $rj['Tanggal'],
						'Jam' => $rj['Jam'],
						'JenisPasienID' => $v['JenisKerjasamaID'],
						'SectionAsalID' => $rj['SectionID'],
						'SectionID' => $v['SectionID'],
						'KelasAsalID' => "XX",
						'KelasID' => "XX",
						'Titip' => "0",
						'DokterID' => $v['DokterID'],
						'NoAntri' => 1 + $_ci->poly_m->get_max_number( "SIMtrDataRegPasien", array("SectionID" => $v['SectionID'], "Tanggal" => date("Y-m-d")), "NoAntri"),
						'SudahPeriksa' => 0,
						'RJ' => 1,
						'UmurThn' => $v['UmurThn'],
						'UmurBln' => $v['UmurBln'],
						'UmurHr' => $v['UmurHr'],
					];
					$_ci->poly_destination_model->create( $v );
					$_ci->registration_data_model->create( $DataRegPasien );				
					
				endforeach;
			endif;
			
			// Update Status sudah periksa di registrasi dan dataregpasien
			$_update_registration_data = [
				'NoBIll' => $rj['NoBukti'], 
				'KdKelasPelayananSection' => '', 
				'DokterID' => $rj['DokterID'], 
				'SudahPeriksa' => 1,
				'DokterRawatID' => $rj['DokterID'],
				'PxKeluar_Pulang' => $checkout['PxKeluar_Pulang'],
				'PxKeluar_Dirujuk' => $checkout['PxKeluar_Dirujuk'],
				'PxMeninggal' => $checkout['PxMeninggal'],
				'MeninggalSblm48' => $checkout['MeninggalSblm48'],
				'MeninggalStl48' => $checkout['MeninggalStl48'],
				'MeninggalTgl' => $checkout['MeninggalTgl'],
				'MeninggalJam' => $checkout['MeninggalJam'],
			];
			$_ci->registration_data_model->update_by($_update_registration_data, [ 'NoReg' => $rj['RegNo'], 'SectionID' => $rj['SectionID'] ]);
						
			$_update_registration_data = [
				'Out' => 1,
				'Pulang_Tanggal' => date('Y-m-d'),
				'PxKeluar_Pulang' => $checkout['PxKeluar_Pulang'],
				'PxKeluar_Dirujuk' => $checkout['PxKeluar_Dirujuk'],
				'PxMeninggal' => $checkout['PxMeninggal'],
				'MeninggalSblm48' => $checkout['MeninggalSblm48'],
				'MeninggalStl48' => $checkout['MeninggalStl48'],
				'MeninggalTgl' => $checkout['MeninggalTgl'],
				'MeninggalJam' => $checkout['MeninggalJam'],
			];
			$_ci->registration_data_model->update_by($_update_registration_data, ['NoReg' => $rj['RegNo']]);
			
			$_update_registration = [
				'SectionID' => $rj['SectionID'], 
				'SectionPerawatanID' => $rj['SectionID'],
				// 'StatusPeriksa' => $rj['TindakLanjut_RI'] ? 'Sudah' : 'Belum',
				'StatusPeriksa' => 'Sudah',
				'DokterRawatID' => $rj['TindakLanjut_RI'] ? $rj['Konsul_DOkterID'] : $rj['DokterID'],
				'PxKeluar_Pulang' => $checkout['PxKeluar_Pulang'],
				'PxKeluar_Dirujuk' => $checkout['PxKeluar_Dirujuk'],
				'PxMeninggal' => $checkout['PxMeninggal'],
				'MeninggalSblm48' => $checkout['MeninggalSblm48'],
				'MeninggalStl48' => $checkout['MeninggalStl48'],
				'MeninggalTgl' => $checkout['MeninggalTgl'],
				'MeninggalJam' => $checkout['MeninggalJam'],
				'AkanRI' => $rj['TindakLanjut_RI'],
				'AkanRISectionID' => $rj['SectionID'],
				'DokterRawatID' => $rj['Konsul_DOkterID'],
				'AkanRIDokterID' => $rj['Konsul_DOkterID'],
			];				
			$_ci->registration_model->update($_update_registration, $rj['RegNo']);		
						
			if($rj['TindakLanjutCekUpUlang']):
				$_get_registration_data = $_ci->registration_data_model->get_by([ 'NoReg' => $rj['RegNo'], 'SectionID' => $rj['SectionID'] ]);
				$_get_patient = $_ci->patient_model->get_one( $rj['NRM'] );
				$_get_reservation  = $_ci->reservation_model->get_by( ['NoBuktiCekUp' => $rj['NoBukti']] );
				$_reservation = [
					'Tanggal' => date('Y-m-d'),
					'Jam' => date('Y-m-d H:i:s'),
					'User_ID' => self::$user_auth->User_ID,
					'PasienBaru'=> 0,
					'NRM' => $_get_patient->NRM,
					'Nama' => $_get_patient->NamaPasien,
					'Alamat' => $_get_patient->Alamat,
					'Phone' => $_get_patient->Phone,
					'Email' => $_get_patient->Email,
					'TanggalLahir' => $_get_patient->TglLahir,
					'JenisKerjasamaID' => $_get_patient->JenisKerjasamaID,
					'KelasID' => 'XX',
					'Registrasi' => 0,
					'Batal' => 0, 
					'Paid' => 0,
					'TipeReservasi' => 'RESERVASI CHECKUP POLI',
					'UntukSectionID' => $rj['SectionID'],
					'UntukDokterID' => $rj['DokterID'], 
					'UntukTanggal' => $rj['TglCekUp'], 
					'WaktuID' => $_get_registration_data->WaktuID,
					'UntukHari' => self::$weekDay[ date("w", strtotime($rj['TglCekUp'])) ], 
					'NoUrut' => self::get_reservation_queue( $rj['SectionID'], $rj['DokterID'], $rj['TglCekUp'], $_get_registration_data->WaktuID ),
				];
				if( !empty($_get_reservation) )
				{
					if( substr($_get_reservation->UntukTanggal, 0, 9) == $rj['TglCekUp']) unset($_reservation['NoUrut']);
					$_ci->reservation_model->update_by($_reservation, ['NoBuktiCekUp' => $rj['NoBukti']]);
				} else {
					$_reservation['NoReservasi'] = self::gen_reservation_number();
					$_reservation['NoBuktiCekUp'] = $rj['NoBukti'];
					$_ci->reservation_model->create($_reservation);
				}
			elseif( $rj['TindakLanjutCekUpUlang'] == 0  ):
				$_ci->reservation_model->delete_by(['NoBuktiCekUp' => @$rj['NoBukti']]);
			endif;
			
			if(!empty($patient)):
				$_ci->patient_model->update($patient, $rj['NRM'] );
			endif;

			if (!empty($odontogram)) :
				$_ci->db->where('NoBukti', $rj['NoBukti'])->delete("SIMtrEMROdontogram");
	
				foreach ($odontogram as $k => $v) :
					$dataOdontogram = [
						'NoBukti' => $v['NoBukti'],
						'NoReg' => $v['NoReg'],
						'NRM' => $v['NRM'],
						'Kode_Supplier' => $v['Kode_Supplier'],
						'SectionID' => $v['SectionID'],
						'Tooth' => $v['Tooth'],
						'Odontogram_ID' => $v['Odontogram_ID'],
						'Note' => $v['Note'],
						'Created_at' => date('Y-m-d H:i:s'),
						'Created_by' =>
						self::$user_auth->User_ID
					];
	
					$_ci->db->insert("SIMtrEMROdontogram", $dataOdontogram);
	
				endforeach;
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
			"NoBukti" => $rj['NoBukti'],
			"status" => 'success',
			"message" => lang('global:updated_successfully'),
			"code" => 200
		];
	}
	
	public static function save_examination_inpatient($inpatient, $diagnosis, $tool_usage, $nurse, $helper)
	{
		self::init();
		$_ci = self::$_ci;
		
		$_ci->db->trans_begin();
			if(!empty($inpatient['DiagnosaAkhirID'])):

				$_update_registration_data = [
					'SudahPeriksa' => 1,
					'DokterRawatID' => $inpatient['DokterRawatID'],
					'DiagnosaAkhirID' => $inpatient['DiagnosaAkhirID'],
					'KeteranganDiagnosa' => $inpatient['DiagnosaAkhirID'] .' - '. $inpatient['KeteranganDiagnosa'],
					'PxKeluar_Pulang' => $inpatient['PxKeluar_Pulang'],
					'PxKeluar_Dirujuk' => $inpatient['PxKeluar_Dirujuk'],
					'PxKeluar_PlgPaksa' => $inpatient['PxKeluar_PlgPaksa'],
					'PxMeninggal' => $inpatient['PxMeninggal'],
					'MeninggalSblm48' => $inpatient['MeninggalSblm48'],
					'MeninggalStl48' => $inpatient['MeninggalStl48'],
					'MeninggalTgl' => $inpatient['MeninggalTgl'],
					'MeninggalJam' => $inpatient['MeninggalJam'],
				];
				$_ci->registration_data_model->update_by($_update_registration_data, [ 'NoReg' => $inpatient['NoReg'], 'SectionID' => $inpatient['SectionID'] ]);
							
				$_update_registration_data = [
					'Out' => 1,
					'Pulang_Tanggal' => date('Y-m-d'),
					'PxKeluar_Pulang' => $inpatient['PxKeluar_Pulang'],
					'PxKeluar_Dirujuk' => $inpatient['PxKeluar_Dirujuk'],
					'PxKeluar_PlgPaksa' => $inpatient['PxKeluar_PlgPaksa'],
					'PxMeninggal' => $inpatient['PxMeninggal'],
					'MeninggalSblm48' => $inpatient['MeninggalSblm48'],
					'MeninggalStl48' => $inpatient['MeninggalStl48'],
					'MeninggalTgl' => $inpatient['MeninggalTgl'],
					'MeninggalJam' => $inpatient['MeninggalJam'],
				];
				$_ci->registration_data_model->update_by($_update_registration_data, ['NoReg' => $inpatient['NoReg']]);
				
				$_update_registration = [
					'StatusPeriksa' => 'Belum',
					'DokterRawatID' => $inpatient['DokterRawatID'],
					'PxKeluar_Pulang' => $inpatient['PxKeluar_Pulang'],
					'PxKeluar_Dirujuk' => $inpatient['PxKeluar_Dirujuk'],
					'PxKeluar_PlgPaksa' => $inpatient['PxKeluar_PlgPaksa'],
					'PxMeninggal' => $inpatient['PxMeninggal'],
					'MeninggalSblm48' => $inpatient['MeninggalSblm48'],
					'MeninggalStl48' => $inpatient['MeninggalStl48'],
					'MeninggalTgl' => $inpatient['MeninggalTgl'],
					'MeninggalJam' => $inpatient['MeninggalJam'],
				];				
				$_ci->registration_model->update($_update_registration, $inpatient['NoReg']);
				
				$_ci->room_detail_model->update_by(['Status' => 'K'], ['NoKamar' => '208.3', 'NoBed' => 'B.1']);
				
				$_ci->db->query("EXEC InsertUserActivities '".date('Y-m-d')."','".date('Y-m-d H:i:s')."',". self::$user_auth->User_ID .",'{$inpatient['NoReg']}','Update Pasien RI Pulang.#{$inpatient['NoReg']}#{$inpatient['NRM']}#{$inpatient['NamaPasien']}','SIMtrRJ'");
			else :
				
				$_ci->db->query("
					IF NOT EXISTS(SELECT NoReg FROM SIMtrDataRegPasien  WHERE NoReg='{$inpatient['NoReg']}' AND Active=1) 
						BEGIN  
							UPDATE SIMtrDataRegPasien SET Active=1, Out=0 
							WHERE NoReg='{$inpatient['NoReg']}' AND Kamar='{$inpatient['NoKamar']}' 
						END  
					ELSE  
						BEGIN  
							UPDATE SIMtrDataRegPasien SET Out=0 
							WHERE NoReg='{$inpatient['NoReg']}' AND Kamar='{$inpatient['NoKamar']}'
						END
				");
				
				$_update_registration = [
					'StatusPeriksa' => 'Sudah',
				];				
				$_ci->registration_model->update($_update_registration, $inpatient['NoReg']);
				
				$_ci->db->query("
					IF NOT EXISTS( SELECT SudahPeriksa FROM SIMtrDataRegPasien WHERE NoReg='{$inpatient['NoReg']}' AND SectionID='{$inpatient['SectionID']}' AND SudahPeriksa=1 ) 
					BEGIN 
						UPDATE SIMtrDataRegPasien SET SudahPeriksa=1 
						WHERE NoReg='{$inpatient['NoReg']}' AND SectionID='{$inpatient['SectionID']}' 
					END
				");
				
				$_ci->db->query("EXEC InsertUserActivities '".date('Y-m-d')."','".date('Y-m-d H:i:s')."',". self::$user_auth->User_ID .",'{$inpatient['NoReg']}','Update Pasien RI Sudah Periksa.#{$inpatient['NoReg']}#{$inpatient['NRM']}#{$inpatient['NamaPasien']}','SIMtrRJ'");
		
			endif;	
			
			if( !empty( $diagnosis)): 
				$_ci->registration_diagnosis_model->delete_by(['NoReg' => $inpatient['NoReg']]);
				
				foreach( $diagnosis as $row ):
				$row['NoReg'] = $inpatient['NoReg'];
				$_ci->registration_diagnosis_model->create( $row );
				endforeach; 
			endif;	
			
			if( !empty( $tool_usage)): 
				$_delete_not_in = [];
				foreach( $tool_usage as $row ):					
					if(! $_ci->supporting_tool_usage_model->count_all(['NoReg' => $inpatient['NoReg'], 'IDAlat' => $row['IDAlat'], 'SectionID' => $row['SectionID']])):
						$row['NoBukti'] = self::gen_tool_usage_number();
						$row['Tanggal'] = date('Y-m-d');
						$row['Jam'] = date('Y-m-d H:i:s');
						$row['UserID'] = self::$user_auth->User_ID;
						$row['NoIP'] = $_ci->input->ip_address();
						$_ci->supporting_tool_usage_model->create($row);
					endif;		
					
					$_delete_not_in[] = $row['NoBukti'];
				endforeach; 
				
				if(!empty($_delete_not_in)):
					$_ci->db->where_not_in('NoBukti', $_delete_not_in)
						->where(['NoReg' => $inpatient['NoReg'], 'SectionID' => $inpatient['SectionID'] ])
						->delete($_ci->supporting_tool_usage_model->table);
				endif;
			endif;						

			/*if( !empty( $nurse)): foreach( $nurse as $row):
				$row['NoBukti'] = $inpatient['NoReg'];
				$_ci->poly_nurse_model->create( $row );
			endforeach;	endif;*/
			
			if( !empty( $helper)): 
				$Nomor = $_ci->poly_m->get_max_number( "SIMtrDataRegPasien", ["NoReg" => $inpatient['NoReg']], "Nomor" );
				foreach($helper as $k => $v):
					$v['NoBuktiHeader'] = $inpatient['NoReg'];
					$v['NoBuktiMemo'] = self::gen_helper_number();
					$_ci->helper_memo_model->create( $v );
					
					$DataRegPasien = [
						'NoReg' => $inpatient['NoReg'],
						'Nomor' => ++$Nomor,
						'Tanggal' => date('Y-m-d'),
						'Jam' => date('Y-m-d H:i:s'),
						'JenisPasienID' => $v['JenisKerjasamaID'],
						'SectionAsalID' => $inpatient['SectionID'],
						'SectionID' => $v['SectionTujuanID'],
						'KelasAsalID' => "xx",
						'KelasID' => "xx",
						'Titip' => "0",
						'DokterID' => $v['DokterID'],
						'NoAntri' => 1 + $_ci->poly_m->get_max_number( "SIMtrDataRegPasien", ["SectionID" => $v['SectionTujuanID'], "Tanggal" => date("Y-m-d")], "NoAntri"),
						'SudahPeriksa' => 0,
						'RJ' => 1,
						'UmurThn' => $v['UmurThn'],
						'UmurBln' => $v['UmurBln'],
						'UmurHr' => $v['UmurHr'],
					];
					$_ci->registration_data_model->create( $DataRegPasien );				
				endforeach; 
			endif;
											
		if ($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => lang('global:created_failed'),
				"code" => 500
			];
		}
		//$_ci->db->trans_rollback();
		$_ci->db->trans_commit();
		return [
			"NoBukti" => $inpatient['NoReg'],
			"status" => 'success',
			"message" => lang('global:created_successfully'),
			"code" => 200
		];
	}
	
	public static function create_service_inpatient($rj, $service, $service_component, $service_consumable)
	{
		self::init();
		$_ci = self::$_ci;
		$_ci->load->model('poly_transaction_model');
		$_ci->load->model('poly_transaction_detail_model');	
		$_ci->load->model('poly_transaction_pop_model');		
		
		$_ci->db->trans_begin();
			
			$rj['NoBukti'] = $NoBukti = self::gen_evidence_number($rj['SectionID']);
			$rj['TglInput'] = date('Y-m-d');
			$rj['UserID'] = self::$user_auth->User_ID;
			$_ci->poly_m->create( $rj );				
								
			if ( !empty($service))
			{
				$Nomor = $_ci->poly_m->get_max_number( "SIMtrRJTransaksi", ["NoBukti" => $rj['NoBukti']], "Nomor" );				
				foreach ( $service as $row )
				{
					$row['NoBukti'] = $NoBukti;
					$row['Nomor'] = ++$Nomor; # penomoran jasa 
					$row['Waktu'] = date('Y-m-d H:i:s');
					$row['Jam'] = date('Y-m-d H:i:s');
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
						$val['Nomor'] = $Nomor; # penomoran component bhp
						$_ci->poly_transaction_pop_model->create( $val );
						
						# Pengurangan Stock
						$section = $_ci->section_model->get_one($rj['SectionID']);
						$_insert_fifo = [
							'location_id' => $section->Lokasi_ID, 
							'item_id' => $val["Barang_ID"],  
							'item_unit_code' => $val["Satuan"],  
							'qty' => $val["Qty"], 
							'price' => $val["Harga"],  
							'conversion' => 1,  
							'evidence_number' => $rj['NoBukti'],  
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
		
			$_ci->db->query("EXEC UpdateKunjunganPasien '{$rj['NRM']}','{$rj['Jam']}','SEC062','DOK-002'");
			$_ci->db->query("EXEC InsertUserActivities '{$rj['Tanggal']}','{$rj['Jam']}',". self::$user_auth->User_ID .",'{$rj['NoBukti']}','Input Biaya.#{$rj['NoBukti']}#{$rj['NRM']}#','SIMtrRJ'");
			
		if ($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => lang('global:created_failed'),
				"code" => 500
			];
		}
		//$_ci->db->trans_rollback();
		$_ci->db->trans_commit();
		return [
			"NoBukti" => $NoBukti,
			"status" => 'success',
			"message" => lang('global:created_successfully'),
			"code" => 200
		];
	}
	
	public static function update_service_inpatient($rj, $service, $service_component, $service_consumable)
	{
		self::init();
		$_ci = self::$_ci;
		$_ci->load->model('poly_transaction_model');
		$_ci->load->model('poly_transaction_detail_model');	
		$_ci->load->model('poly_transaction_pop_model');			
		
		$_ci->db->trans_begin();
			
			$rj['UserID'] = self::$user_auth->User_ID;
			$_ci->poly_m->update( $rj, $rj['NoBukti'] );				
			
			// Ketika Edit disimpan, maka Hapus semua data service dan simpan dengan data service yg baru, 
			// Return barang (BHP), dan simpan data component juga BHP jika ada
			$section = $_ci->section_model->get_one($rj['SectionID']);
			$return_service_consumable = $_ci->poly_transaction_pop_model->get_all(NULL, 0, ['NoBUkti' => $rj['NoBukti']], TRUE);
			foreach( $return_service_consumable as $val )
			{
				$_insert_fifo = [
					'location_id' => $section->Lokasi_ID, 
					'item_id' => $val["Barang_ID"],  
					'item_unit_code' => $val["Satuan"],  
					'qty' => $val["Qty"], 
					'price' => $val["Harga"],  
					'conversion' => 1,  
					'evidence_number' => $rj['NoBukti'].'-R',  
					'trans_type_id' => 562,
					'in_out_state' => 1,
					'trans_date' => date('Y-m-d'),  
					'exp_date' => date('Y-m-d'),  
					'item_type_id' => 0, 
				];
				self::insert_warehouse_fifo( $_insert_fifo );
			}				
			
			$_ci->poly_transaction_detail_model->delete($rj['NoBukti']);
			$_ci->poly_transaction_pop_model->delete($rj['NoBukti']);
			$_ci->poly_transaction_model->delete($rj['NoBukti']);
								
			if ( !empty($service))
			{				
				$Nomor = (int) @$_ci->poly_m->get_max_number( "SIMtrRJTransaksi", ["NoBukti" => $rj['NoBukti']], "Nomor" );
				
				foreach ( $service as $row )
				{										
					$row['NoBukti'] = $rj['NoBukti'];
					$row['Nomor'] = ++$Nomor; # penomoran jasa 
					$row['Waktu'] = date('Y-m-d H:i:s');
					$row['Jam'] = $rj['Jam'];
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
						$val['NoBukti'] = $rj['NoBukti'];
						$val['Nomor'] = $Nomor; 
						
						$_ci->poly_transaction_detail_model->create( $val );
					}

					if(!empty($service_consumable[$row['JasaID']])) foreach( $service_consumable[$row['JasaID']] as $val ) :
						$val['NoBUkti'] = $rj['NoBukti'];
						$val['Nomor'] = $Nomor; # penomoran component bhp
						$_ci->poly_transaction_pop_model->create( $val );
						
						# Pengurangan Stock
						$section = $_ci->section_model->get_one($rj['SectionID']);
						$_insert_fifo = [
							'location_id' => $section->Lokasi_ID, 
							'item_id' => $val["Barang_ID"],  
							'item_unit_code' => $val["Satuan"],  
							'qty' => $val["Qty"], 
							'price' => $val["Harga"],  
							'conversion' => 1,  
							'evidence_number' => $rj['NoBukti'],  
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
		
			$_ci->db->query("EXEC InsertUserActivities '". date('Y-m-d')."', '". date('Y-m-d H:i:s')."',". self::$user_auth->User_ID .",'{$rj['NoBukti']}','Edit Biaya.#{$rj['NoBukti']}#{$rj['NRM']}#','SIMtrRJ'");
			
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
			"NoBukti" => $rj['NoBukti'],
			"status" => 'success',
			"message" => lang('global:updated_successfully'),
			"code" => 200
		];
	}
	
	public static function delete_service_inpatient($NoBukti)
	{
		self::init();
		$_ci = self::$_ci;
		$_ci->load->model('poly_transaction_model');
		$_ci->load->model('poly_transaction_detail_model');	
		$_ci->load->model('poly_transaction_pop_model');			
		
		$_ci->db->trans_begin();
		
			$item = self::get_inpatient_examination_by(['a.NoBukti' => $NoBukti], TRUE);
			
			// Ketika Delete Biaya Return barang (BHP), dan simpan data component juga BHP jika ada
			$section = $_ci->section_model->get_one($item->SectionID);
			$return_service_consumable = $_ci->poly_transaction_pop_model->get_all(NULL, 0, ['NoBUkti' => $NoBukti], TRUE);
			foreach( $return_service_consumable as $val )
			{
				$_insert_fifo = [
					'location_id' => $section->Lokasi_ID, 
					'item_id' => $val["Barang_ID"],  
					'item_unit_code' => $val["Satuan"],  
					'qty' => $val["Qty"], 
					'price' => $val["Harga"],  
					'conversion' => 1,  
					'evidence_number' => $NoBukti.'-R',  
					'trans_type_id' => 562,
					'in_out_state' => 1,
					'trans_date' => date('Y-m-d'),  
					'exp_date' => date('Y-m-d'),  
					'item_type_id' => 0, 
				];
				self::insert_warehouse_fifo( $_insert_fifo );
			}				
							
			$_ci->poly_transaction_detail_model->delete($NoBukti);
			$_ci->poly_transaction_pop_model->delete($NoBukti);
			$_ci->poly_transaction_model->delete($NoBukti);	
			$_ci->poly_m->delete( $NoBukti );		
		
			$_ci->db->query("EXEC InsertUserActivities '". date('Y-m-d')."', '". date('Y-m-d H:i:s')."', ". self::$user_auth->User_ID .",'{$NoBukti}','Delete Biaya.#{$NoBukti}','SIMtrRJ'");
			
		if ($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => lang('global:deleted_failed'),
				"code" => 500
			];
		}
		//$_ci->db->trans_rollback();
		$_ci->db->trans_commit();
		return [
			"NoBukti" => $NoBukti,
			"status" => 'success',
			"message" => lang('global:deleted_successfully'),
			"code" => 200
		];
	}
	
	public static function save_doctor_treat($NoReg, $collection)
	{
		self::init();
		$_ci = self::$_ci;
		
		$_ci->db->trans_begin();
			
			$_delete_not_in = [];
			foreach($collection as $row):
				if(! $_ci->registration_doctor_treat_model->count_all(['NoReg' => $NoReg, 'DokterRawatID' => $row['DokterRawatID']])):
					$row['TglInput'] = date('Y-m-d H:i:s');
					$_ci->registration_doctor_treat_model->create($row);
				endif;		
				
				$_delete_not_in[] = $row['DokterRawatID'];
			endforeach;
			
			$_ci->db->where_not_in('DokterRawatID', $_delete_not_in)
					->where('NoReg', $NoReg)
					->delete($_ci->registration_doctor_treat_model->table);
											
		if ($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => lang('global:created_failed'),
				"code" => 500
			];
		}
		//$_ci->db->trans_rollback();
		$_ci->db->trans_commit();
		return [
			"NoBukti" => $NoReg,
			"status" => 'success',
			"message" => lang('global:created_successfully'),
			"code" => 200
		];
	}
	
	/*
		@params Nomor Registrasi, SectionID
	*/
	public static function get_outpatient($NoReg, $SectionID, $is_edit = false)
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
			a.RiwayatAlergi,
			a.RiwayatPenyakit,
			a.RiwayatObat,
			a.Phone,
			a.NoIdentitas
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
				->join("{$_ci->poly_m->table} e", "e.RegNo = a.NoReg", "INNER")
				->where(['e.Batal' => 0]);
								
		return $query->get()->row();		
	}
	
	/*
		@params Nomor Registrasi, SectionID
	*/
	public static function get_inpatient($NoReg, $SectionID)
	{
		$_ci = self::ci();
		
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
			c.SectionName as SectionAsal,
			f.NamaKelas as KelasAsalName,
			b.KamarAsal,
			b.NoBedAsal,
			a.PasienBlackList,
			a.VIP,
			a.VIPKeterangan,
			a.KdKelasPertanggungan,
			e.JKN,
			b.DokterRawatID,
			g.NamaDokter AS NamaDokterRawatID,
			b.DiagnosaAkhirID,
			h.Descriptions AS NamaDiagnosaAkhirID,
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
			a.RiwayatAlergi,
			a.RiwayatPenyakit,
			a.RiwayatObat
EOSQL;
		
		$query = $_ci->db->select($db_select)
					->from("VW_Registrasi a")
					->join("{$_ci->registration_data_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER")
					->join("{$_ci->section_model->table} c", "b.SectionAsalID = c.SectionID", "LEFT OUTER")
					->join("{$_ci->section_model->table} d", "b.SectionID = d.SectionID", "INNER")
					->join("{$_ci->class_model->table} e", "b.KelasID = e.KelasID", "INNER")
					->join("{$_ci->class_model->table} f", "b.KelasAsalID = f.KelasID", "LEFT OUTER")
					->join("Vw_Dokter G", "b.DokterRawatID = g.DokterID", "LEFT OUTER")
					->join("{$_ci->icd_model->table} h", "b.DiagnosaAkhirID = h.KodeICD", "LEFT OUTER")
					->where(['b.NoReg' => $NoReg, 'b.SectionID' => $SectionID])
				;
								
		return $query->get()->row();		
	}
	
	public static function get_inpatient_examination_by(Array $db_where, $return_row = FALSE )
	{
		$_ci = self::ci();
		$_ci->load->model("section_model");
		
		$db_select = <<<EOSQL
			a.NoBukti, 
			a.RegNo AS NoReg,
			a.SectionID,
			a.Tanggal,
			a.Jam,
			c.DokterID, 
			c.NamaDokter, 
			c.SpesialisName,  
			b.SectionName,
			a.ClosedTransaksi,
			a.Audit  
EOSQL;
		
		$query = $_ci->db->select($db_select)
					->from("{$_ci->poly_m->table} a")
					->join("{$_ci->section_model->table} b", "a.SectionID = b.SectionID", "INNER")
					->join("Vw_Dokter c", "a.DokterID = c.DokterID", "INNER")
					->where($db_where)
					->order_by('a.NoBukti, a.Tanggal')
				;
				
		if($return_row):
			$row = $query->get()->row();
			if(!empty($row)){
				$row->Tanggal = DateTime::createFromFormat('Y-m-d H:i:s.u', $row->Tanggal)->format('Y-m-d');
				$row->Jam = DateTime::createFromFormat('Y-m-d H:i:s.u', $row->Jam)->format('H:i:s');
			}
			return (object) $row;
		endif;
			
		$collection = [];
		foreach($query->get()->result() as $row):
			$row->Tanggal = DateTime::createFromFormat('Y-m-d H:i:s.u', $row->Tanggal)->format('Y-m-d');
			$row->Jam = DateTime::createFromFormat('Y-m-d H:i:s.u', $row->Jam)->format('H:i:s');
			$collection[] = $row;
		endforeach;
		
		return $collection;
	}
	
	public static function get_doctor_treat($NoReg)
	{
		$_ci = self::ci();
		
		$db_select = <<<EOSQL
			b.DokterID, 
			b.NamaDokter, 
			b.SpesialisName
EOSQL;
		
		$query = $_ci->db->select($db_select)
					->from("{$_ci->registration_model->table} a")
					->join("Vw_Dokter b", "a.DokterRawatID = b.DokterID", "INNER")
					->where(['NoReg' => $NoReg])
					;
				
		return $query->get()->row();
	}
	
	public static function get_registration_doctor_treat($NoReg)
	{
		$_ci = self::ci();
		
		$db_select = <<<EOSQL
			b.DokterID, 
			b.NamaDokter, 
			b.SpesialisName
EOSQL;
		
		$query = $_ci->db->select($db_select)
					->from("{$_ci->registration_doctor_treat_model->table} a")
					->join("Vw_Dokter b", "a.DokterRawatID = b.DokterID", "INNER")
					->where(['NoReg' => $NoReg])
					;
				
		return $query->get()->result();
	}
	
	public static function get_registration_diagnosis($NoReg)
	{
		$_ci = self::ci();
		
		$db_select = <<<EOSQL
			a.KodeICD, 
			b.Descriptions, 
			a.Ditanggung
EOSQL;
		
		$query = $_ci->db->select($db_select)
					->from("{$_ci->registration_diagnosis_model->table} a")
					->join("{$_ci->icd_model->table} b", "a.KodeICD = b.KodeICD", "INNER")
					->where(['a.NoReg' => $NoReg])
					;
				
		return $query->get()->result();
	}
		
	public static function get_supporting_tool_usage($NoReg, $SectionID)
	{
		$_ci = self::ci();
		
		$db_select = <<<EOSQL
			a.NoBukti, 
			a.NoReg, 
			a.SectionID, 
			a.Tanggal, 
			a.Jam, 
			a.IDAlat,
			b.NamaAlat,
			a.Jml,
			a.UserID,
			a.NoIP
EOSQL;
		
		$query = $_ci->db->select($db_select)
					->from("{$_ci->supporting_tool_usage_model->table} a")
					->join("{$_ci->supporting_tool_model->table} b", "a.IDAlat = b.IDAlat", "INNER")
					->where(['a.NoReg' => $NoReg, 'a.SectionID' => $SectionID])
					;
				
		$collection = [];
		foreach($query->get()->result() as $row)
		{
			$row->Tanggal = DateTime::createFromFormat('Y-m-d H:i:s.u', $row->Tanggal)->format('Y-m-d');
			$row->Jam = DateTime::createFromFormat('Y-m-d H:i:s.u', $row->Jam)->format('H:i:s');
			
			$collection[] = $row;
		}
		return $collection;
	}

	public static function gen_evidence_number( $SectionID = NULL, $NoReg = NULL, $Tanggal = NULL, $inpatient = FALSE )
	{
		$_ci = self::ci();
		$NOW = new DateTime();
		
		$SectionID = ($SectionID) ? $SectionID : config_item('section_id');	
		
		if($inpatient)
		{
			$query = $_ci->db->select("MAX(NoBukti) AS max_number")
					->where([
						"SectionID" => $SectionID, 
						"RegNo" => $NoReg, 
						"Batal" => 0,
						"Tanggal" => $Tanggal ? $Tanggal : date('Y-m-d')
					])
					->get( $_ci->poly_m->table )
					->row();
					
			if(!empty($query->max_number))
			{ 
				return $query->max_number;
			}
		}
			
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );
		
		$section = $_ci->section_model->get_one( $SectionID );
		$query = $_ci->db->select("MAX(NoBukti) AS max_number")
						->where([
							"SectionID" => $SectionID, 
							"LEFT(LTRIM([NoBukti]),2) =" => $date_y, 
						])
						->get( $_ci->poly_m->table )
						->row();
		if (!empty($query->max_number))
		{
			$query->max_number++;
			$max_number = $query->max_number;
			$arr_number = explode('-', $max_number);
			$number = (string) (sprintf( "%02d%02d%02d%s-%06d", $date_y, $date_m, $date_d, $section->KodeNoBukti, $arr_number[1]));		
		} else {
			$number = (string) (sprintf( "%02d%02d%02d%s-%06d", $date_y, $date_m, $date_d, $section->KodeNoBukti, 1));		
		}
		return $number;
	}
	
	public static function gen_prescription_number(  )
	{
		$_ci = self::ci();
		$NOW = new DateTime();
		
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );

		$query =  $_ci->db
						->select("MAX(NoResep) as max_number")
						->where([
							"LEN([NoResep]) =" => 16, 
							"LEFT(LTRIM([NoResep]),2) =" => $date_y, 
							"RIGHT(LEFT(LTRIM([NoResep]),9),3) =" => 'RSP',
						])
						->get( "SIMtrResep" )
						->row()
					;
		if (!empty($query->max_number))
		{
			$query->max_number++;
			$number = $query->max_number;
		} else {
			$number = (string) (sprintf("%02d%02d%02dRSP-%06d", $date_y, $date_m, $date_d, 1));		
		}
		
		
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

	public static function gen_helper_number(  )
	{
		$NOW = new DateTime();
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );

		$_ci = self::ci();
		$query =  $_ci->db->select("MAX(NoBuktiMemo) as max_number")
						->where([
							"LEN([NoBuktiMemo]) =" => 16,
							"LEFT(LTRIM([NoBuktiMemo]), 2) =" => $date_y,
							"RIGHT(LEFT(LTRIM([NoBuktiMemo]),9),3) =" => 'MEM',
						])
						->get( "SIMtrMemoPenunjang" )
						->row()
					;
		if (!empty($query->max_number))
		{
			$query->max_number++;
			$number = $query->max_number;
		} else {
			$number = (string) (sprintf("%02d%02d%02dMEM-%06d", $date_y, $date_m, $date_d, 1));		
		}
		return $number;
	}

	public static function gen_memo_number(  )
	{

		$NOW = new DateTime();
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );

		$_ci = self::ci();
		$query =  $_ci->db
						->select("MAX(NoUrut) as max_number")
						->where([
							"LEFT(LTRIM([NoBuktiMemo]), 2) =" => $date_y,
						])
						->get( "SIMtrMemo" )
						->row()
					;
		if (!empty($query->max_number))
		{
			$query->max_number++;
			$number = $query->max_number;
		} else {
			$number = (string) (sprintf("%02d%02d%06d", $date_y, $date_m, 1));		
		}
		return $number;
	}
	
	public static function gen_tool_usage_number(  )
	{
		$_ci = self::ci();
		$NOW = new DateTime();
		
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );

		$query =  $_ci->db->select("MAX(NoBukti) as max_number")
						->where([
							"LEFT(LTRIM([NoBukti]),2) =" => $date_y, 
						])
						->get($_ci->supporting_tool_usage_model->table)
						->row();
						
		if (!empty($query->max_number))
		{
			$query->max_number++;
			$number = $query->max_number;
		} else {
			$number = (string) (sprintf("%02d%02d%02dALT-%06d", $date_y, $date_m, $date_d, 1));		
		}
		
		
		return $number;
	}
	
	public static function gen_reservation_number()
	{
		$_ci = self::ci();
		$NOW = new DateTime();		
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );

		$query = $_ci->db->select("MAX(NoReservasi) AS max_number")
							->where([
								"LEN([NoReservasi]) =" => 16, 
								"LEFT(LTRIM([NoReservasi]),2) =" => $date_y, 
								"RIGHT(LEFT(LTRIM([NoReservasi]),9),3) =" => 'RES',
							])
							->get( $_ci->reservation_model->table )
							->row();
							
		if (!empty($query->max_number))
		{
			$query->max_number++;
			$number = $query->max_number;
		} else {			
			$number = (string) (sprintf("%02d%02d%02dRES-%06d", $date_y, $date_m, $date_d, 1));		
		}
		
		return $number;
	}
	
	public static function get_reservation_queue( $UntukSectionID, $UntukDokterID, $UntukTanggal, $WaktuID )
	{
		$_ci = self::ci();		
		$db_where = [
			'UntukSectionID' => $UntukSectionID, 
			'UntukDokterID' => $UntukDokterID, 
			'UntukTanggal' => $UntukTanggal, 
			'WaktuID' => $WaktuID
		];
		
		$query = $_ci->db
			->select("MAX(NoUrut) as Max")
			->where( $db_where )
			->get( $_ci->reservation_model->table )
			;
		
		$Max = 1;
		if ( $query->num_rows() > 0 )
		{
			$Max = $query->row()->Max + 1;	
			$Max = ($Max % 5) == 0 ? $Max + 1 : $Max;
		}
		return $Max;
	}
	
	public static function get_soap_history(Array $where)
	{
		$_ci = self::ci();
		$_ci->load->model('Emr_vital_signs_model');
		
		$where['b.parent'] = 1;
		
		$db_select = <<<EOSQL
			a.*, 
			b.Height,
			b.Weight,
			b.Temperature,
			b.Systolic,
			b.Diastolic,
			b.HeartRate,
			b.RespiratoryRate,
			b.OxygenSaturation,
			b.Pain,
			c.Nama_Supplier AS NamaDokter
EOSQL;
		
		$query = $_ci->db->select($db_select)
					->from("{$_ci->emr_soap_notes_model->table} a")
					->join("{$_ci->Emr_vital_signs_model->table} b", "a.NoPemeriksaan = b.NoPemeriksaan", "INNER")
					->join("{$_ci->supplier_model->table} c", "a.DokterID = c.Kode_Supplier", "INNER")
					->where($where)
					->get();
		
		$collection = [];
		foreach($query->result() as $row):
			$row->vital_signs = sprintf(
							"%s CM, %s KG, %sC, %s/%s MM/HG, %s BPM, %s RPM, SATS %s&permil;, Skala Nyeri %s",
							$row->Height, $row->Weight, $row->Temperature, $row->Systolic, $row->Diastolic, $row->HeartRate, $row->RespiratoryRate, $row->OxygenSaturation, $row->Pain
						);
			$collection[] = $row;
		endforeach;

		return $collection;
	}

	public static function get_drug_history(Array $where)
	{
		$_ci = self::ci();
		
		$db_select = <<<EOSQL
			a.NoBukti, 
			a.NoResep, 
			a.Tanggal,
			a.NoReg,
			c.Nama_Supplier AS NamaDokter
EOSQL;
		
		$query = $_ci->db->select($db_select)
					->from("BILLFarmasi a")
					->join("mSupplier c", "a.DokterID = c.Kode_Supplier", "LEFT OUTER JOIN")
					->join("SIMtrRegistrasi d", "a.NoReg = d.NoReg", "INNER")
					->where($where)
					->get();


		$collection = [];
		foreach($query->result() as $row):
			$collection[] = $row;
		endforeach;

		return $collection;
	}

	public static function get_drug_history_details( $NoBukti )
	{
		$select_table = <<<EOSQL
				b.*,
EOSQL;
			
		$result = self::ci()->db
			->select( $select_table )
			->from( "BILLFarmasi a" )
			->join( "BILLFarmasiDetail b","a.NoBukti = b.NoBukti", "INNER")
			->where([
				'a.NoBukti' => $NoBukti,
				'a.Batal' => 0,
				'a.Retur' => 0,
				'a.TipeTransaksi ' => null
			])
			->get()
			;
		
		if( $result->num_rows() > 0 )
		{
			return $result->result();
		}
		
		return FALSE;
	}


	
	public static function option_doctor( array $where = NULL)
	{
		self::init();
		$_ci = self::$_ci;		
		
		if(!empty($where))
		{
			$_ci->db->where($where);
		}
		$result = $_ci->db->select('Kode_Supplier AS DokterID, Nama_Supplier AS NamaDokter')
						->where_in('KodeKategoriVendor', ['V-002', 'V-009'])
						->get( $_ci->supplier_model->table)
						->result();
		$collection = [];
		foreach( $result as $v)
		{
			$collection[ $v->DokterID ] = $v->NamaDokter;
		}
		return $collection;
	}
	
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
