<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Verification_helper
{
	private static $_trans_date;
	private static $user_auth;
	private static $_ci;
	
	private static $_is_split = FALSE;
	private static $_is_multi_payment = FALSE;
	private static $_split_payment = [];
	private static $_split_component = [];
	
	public static function init()
	{
		self::$_ci = $_ci = get_instance();
		
		$_ci->BO_1 = $_ci->load->database('BO_1', TRUE);
		
		if(config_item('multi_bo') === 1)
		$_ci->BO_2 = $_ci->load->database('BO_2', TRUE);
		
		$_ci->load->model('audit_model');
		$_ci->load->model('audit_cost_model');
		$_ci->load->model('audit_coefficient_model');
		$_ci->load->model('audit_revenue_model');
		$_ci->load->model('audit_service_model');
		$_ci->load->model('audit_component_model');
		$_ci->load->model('audit_category_surgery_model');
		$_ci->load->model('audit_section_model');
		$_ci->load->model('audit_journal_payment_model');
		$_ci->load->model('audit_detail_ar_model');
		$_ci->load->model('audit_detail_ap_model');
		$_ci->load->model('audit_honor_model');
		
		$_ci->load->model('cashier_model');
		$_ci->load->model('cashier_detail_model');
		$_ci->load->model('cashier_discount_model');
		$_ci->load->model('discount_model');
		$_ci->load->model('type_payment_model');
		
		$_ci->load->model('registration_model');
		$_ci->load->model('reservation_model');
		
		$_ci->load->model('section_model');
		$_ci->load->model('supplier_model');
		$_ci->load->model('customer_model');
		
		$_ci->load->model('deposit_model');
		$_ci->load->model('booking_payment_model');
		$_ci->load->model('service_group_model');
		$_ci->load->model('service_component_model');
		$_ci->load->model('merchan_model');
		
		$_ci->load->model('otc_drug_model');
		$_ci->load->model('bill_pharmacy_model');
		$_ci->load->model('outstanding_payment_model');

		$_ci->load->library('simple_login');		
		self::$user_auth = $_ci->simple_login->get_user();
		
	}
	
	// Rawat Inap
	public static function audit_inpatient( $date, $NoBuktiRJ )
	{
		$_ci = self::$_ci;	
		self::$_trans_date = $date;
		
		$_response = [
			'state' => 1, 
			'message' => lang('message:revenue_recognition_successfully')
		];

		$_ci->db->trans_begin();
			
			$collection = self::_get_examination_trans( self::$_trans_date, 1 );
			
			if ( !empty( $collection ))	: foreach( $collection as $row ) :
				
				$_prepare_audit = [
					'TglTransaksi' => $row->Tanggal,
					'JamTransaksi' => $row->Jam,
					'NoInvoice' => $row->NoBukti,
					'NRM' => $row->NRM,
					'NoReg' => $row->NoReg,
					'PoliKlinik' => $row->Poliklinik,
					'NamaPasien' => $row->NamaPasien,
					'TipeTransaksi' => 'RAWAT INAP',
					'RawatInap' => TRUE,
					'SectionName' =>  '', //$row->SectionName,
					'PasienAsuransi' => (boolean) $row->PasienAsuransi,
					'TipePasien' => !empty($row->JenisKerjasamaID) ? $row->JenisKerjasamaID : 3, // 1,
					'CustomerID' => $row->Kode_Customer,
					'Keterangan' => $row->NamaPasien,
					'CurCoPay' => 0, //(float) $row->Copay,
					'DokterBon' => $row->Nama_Supplier,
					'NamaPegawai' => sprintf("%s->%s", $row->NIK, !empty($row->Nama_Customer) ? $row->Nama_Customer : $row->NamaPegawai),
					'NIK' => $row->NIK,
					'KodeDokter' => $row->DokterBonID,
				];
				
				$_response = self::_audit_examination( $_prepare_audit );
				
				/*
					State Of Progress:
					0 -> error : All data must be ROLLBACK
					1 -> success : All data must be COMMIT
					2 -> unfinish : break transaction, COMMIT ALL DATA before error, and update data status which error!
				*/
				switch ( $_response['state'] )
				{
					case 0:
						$_ci->db->trans_rollback();
						return $_response;
					case 2: 
						break 2;
				}
							
			endforeach; endif;
		
		//$_ci->db->trans_rollback();													
		($_ci->db->trans_status() === FALSE) ? $_ci->db->trans_rollback() : $_ci->db->trans_commit();
			
		return $_response;		
	}
	
	// Rawat Jalan
	public static function audit_outpatient( $date, $NoBuktiRJ )
	{
		$_ci = self::$_ci;
		self::$_trans_date = $date;
		// print_r($date);exit;
		$_response = [
			'state' => 1, 
			'message' => lang('message:revenue_recognition_successfully')
		];
		
		$_ci->db->trans_begin();
		// print_r($NoBuktiRJ);exit;
			$collection = self::_get_examination_trans( self::$_trans_date, 0, $NoBuktiRJ );
			
			if ( !empty( $collection ))	: foreach( $collection as $row ) :

				$_prepare_audit = [
					'TglTransaksi' => $row->Tanggal,
					'JamTransaksi' => $row->Jam,
					'NoInvoice' => $row->NoBukti,
					'NRM' => $row->NRM,
					'NoReg' => $row->NoReg,
					'PoliKlinik' => $row->Poliklinik,
					'NamaPasien' => $row->NamaPasien,
					'TipeTransaksi' => 'RAWAT JALAN',
					'RawatInap' => FALSE,
					'SectionName' => $row->SectionName,
					'PasienAsuransi' => (boolean) $row->PasienAsuransi,
					'TipePasien' => !empty($row->JenisKerjasamaID) ? $row->JenisKerjasamaID : 1,
					'CustomerID' => $row->Kode_Customer,
					'Keterangan' => $row->NamaPasien,
					'CurCoPay' => (float) $row->Copay,
					'DokterBon' => $row->Nama_Supplier,
					'NamaPegawai' => sprintf("%s->%s", $row->NIK, !empty($row->Nama_Customer) ? $row->Nama_Customer : $row->NamaPegawai),
					'NIK' => $row->NIK,
					'KodeDokter' => $row->DokterBonID,
				];
				// print_r($_prepare_audit);exit;
				/*
					State Of Progress:
					0 -> error : All data must be ROLLBACK
					1 -> success : All data must be COMMIT
					2 -> unfinish : break transaction, COMMIT ALL DATA before error, and update data status which error!
				*/
					
				self::$_is_split = FALSE;
				self::$_is_multi_payment = FALSE;
				self::$_split_component = []; // Clear self::$_split_component before process.
				self::$_split_payment = []; // Clear self::$_split_payment before process.
				$_response = config_item('multi_bo') == 'TRUE' 
							? self::_audit_examination_with_split( $_prepare_audit )
							: self::_audit_examination( $_prepare_audit );
				switch ( $_response['state'] )
				{
					case 0:
						$_ci->db->trans_rollback();
						return $_response;
					case 2: 
						break 2;
				}
			
				if(!empty(self::$_split_component) || self::$_is_split ){
					
					$_response = self::_audit_split_examination( $_prepare_audit, $_response['evidence_number'] );
					switch ( $_response['state'] )
					{
						case 0:
							$_ci->db->trans_rollback();
							return $_response;
						case 2: 
							break 2;
					}
				}
						//echo 123;exit;	
			endforeach; endif;
		
		//$_ci->db->trans_rollback();
		($_ci->db->trans_status() === FALSE) ? $_ci->db->trans_rollback() : $_ci->db->trans_commit();
		return $_response;
	}	
	
	/*
		@params
		(date) $date -> Tgl pembayaran pada transaksi kasir
		(int) $inpatient -> status rawat inap pada kasir ( 1 = Rawat inap, 0 = Rawat jalan )
	*/
	private static function _get_examination_trans( $date, $inpatient, $NoBuktiRJ )
	{
		$_ci = self::$_ci;
		// print_r($NoBuktiRJ);exit;
		$date = DateTime::createFromFormat('Y-m-d', $date)->setTime(0,0);
		$date->modify('+1 day');
		$date->modify('+8 hour');
		// print_r($date);exit;
		$_ci->db->where([
				'a.Jam <=' => $date->format('Y-m-d H:i:s'),
				'a.Batal' => 0,
				'a.Audit' => 0,
				'b.RawatInap' => $inpatient,
			]);
			
		$db_select = "
			a.Tanggal,
			a.Jam,
			a.NoBukti,
			a.NoReg,
			b.NRM,
			b.NamaPasien_Reg AS NamaPasien,
			c.Poliklinik,
			c.SectionName,
			b.PasienAsuransi,
			b.JenisKerjasamaID,
			b.KodePerusahaan AS Kode_Customer,
			a.Copay,
			sup.Nama_Customer as Nama_Supplier,
			peg.Nama_Customer as NamaPegawai,
			a.NIK,	 	
			peg.Nama_Customer,
			a.DokterBonID
		";
		
		$query = $_ci->db->select( $db_select )
						->from("{$_ci->cashier_model->table} a")
						->join("{$_ci->registration_model->table} b", "a.NoReg = b.NoReg", "INNER")
						->join("{$_ci->section_model->table} c", "a.SectionPerawatanID = c.SectionID", "INNER")
						->join("{$_ci->customer_model->table} sup", "a.DokterBonID = sup.Kode_Customer", "LEFT OUTER")
						->join("{$_ci->customer_model->table} peg", "a.NIK = peg.Kode_Customer", "LEFT OUTER")
						->where('a.NoBukti', $NoBuktiRJ)
						->get()
						;
		
		return $query->result();		
	}
	
	/*
		@params	
		(Array) $arguments :
		
			(tipe) 		key 			=> value 
		{  
			(date)		'TglTransaksi' 	=> Tanggal transaksi pada pembayaran kasir,
			(string) 	'NoInvoice' 	=> No Invoice pada pembayaran kasir,
			(string) 	'NRM' 			=> Nomor medical record pasien,
			(string) 	'NoReg' 		=> Nomor Registrasi pasien,
			(string) 	'PoliKlinik' 	=> Jenis poiliklinik pada section,
			(string) 	'NamaPasien' 	=> Nama pasien,
			(string) 	'TipeTransaksi' => Jenis Transaksi Perawatan ('RAWAT INAP', 'RAWAT JALAN'),
			(boolean) 	'RawatInap' 	=> Status data rawat inap atau tidak (TRUE, FALSE),
			(string) 	'SectionName' 	=> Nama section, untuk rawat inap nilainya '' ,
			(booleand) 	'PasienAsuransi'=> Status data pasien asuransi (TRUE, FALSE),
			(int) 		'TipePasien' 	=> Tipe pasien (jika null, standar rawat jalan 1, standar rawat inap 3),
			(string) 	'CustomerID' 	=> Kode Perusahaan(Kode_Customer) BPJS IKS,
			(string) 	'Keterangan' 	=> Keterangan berisi nama pasien,
			(float) 	'CurCoPay' 		=> Nilai co payment, untuk rawat inap nilainya 0,
			(string) 	'DokterBon' 	=> Nama_Supplier (dokter bon) ,
			(stirng) 	'NamaPegawai' 	=> Berisi NIK dan nama pegawainya (%s->%s, nik, nama pegawai),
			(string) 	'NIK' 			=> NIK pegawai,
			(string) 	'KodeDokter' 	=> DokterBonID (Kode Supplier),
		}
	*/
	private static function _audit_examination( $arguments )
	{
		$_ci = self::$_ci;
		extract($arguments);
		
		$AkunNo_BonDokter = "1010303005";
		$CurNilaiPPNAkum = 0;
		$NaikKelas = (boolean) $_ci->db->from("{$_ci->cashier_model->table} a")
									->join("{$_ci->registration_model->table} b", "a.NoReg = b.NoReg", "INNER")
									->where(['NoBukti' => $NoInvoice, 'NaikKelas' => 1 ])
									->count_all_results();
		
		$CurNilaiDeposit = (float) @$_ci->db->select("SUM(NilaiDeposit) AS Total")
										->where(['Batal' => 0, 'NoReg' => $NoReg])
										->get("{$_ci->deposit_model->table}")
										->row()->Total;
										
		$CurBookingPayment = (float) @$_ci->db->select("SUM( a.Deposit + a.NilaiPembayaranCC ) AS Total")
										->from("{$_ci->booking_payment_model->table} a")
										->join("{$_ci->reservation_model->table} b", "a.NoReservasi = b.NoReservasi", "INNER")
										->join("{$_ci->registration_model->table} c", "b.NoReservasi = c.NoReservasi", "INNER")
										->where(['a.Batal' => 0, 'c.NoReg' => $NoReg])
										->get()->row()->Total;
										
		$CurOTCDrug = (float) @$_ci->db->select("SUM( a.NilaiPembayaran + a.NilaiPembayaranCC ) AS Total")
										->from("{$_ci->otc_drug_model->table} a")
										->join("{$_ci->reservation_model->table} b", "a.NoReservasi = b.NoReservasi", "INNER")
										->join("{$_ci->registration_model->table} c", "b.NoReservasi = c.NoReservasi", "INNER")
										->where(['a.Batal' => 0, 'c.NoReg' => $NoReg])
										->get()->row()->Total;
										
		$CurNilaiDeposit = $CurNilaiDeposit + $CurBookingPayment + $CurOTCDrug;
		
		switch ( $TipePasien ):
			case 1:
				$AkunNoPiutangPasien = config_item('AkunLawanPendatanHC');
				if ( $_ci->registration_model->count_all(['NoReg' => $NoReg, 'IKSMixed' => 1]) )
				{
					$AkunNoPiutangPasien = config_item('AkunLawanPendapatanIKSMIXED');
				}
				
				$StrTipePasien = 'HC';
			break;
			case 2:
				$AkunNoPiutangPasien = config_item('AkunLawanPendatanIKS');
				$StrTipePasien = 'IKS';
			break;
			case 3:
				$AkunNoPiutangPasien = config_item('AkunLawanPendatanUMUM');
				$StrTipePasien = 'UMUM';			
			break;
			case 4:
				$AkunNoPiutangPasien = config_item('AkunLawanPendatanEXECUTIVE');
				$StrTipePasien = 'EXECUTIVE';			
			break;
			case 9:
				$AkunNoPiutangPasien = config_item('AkunLawanPendapatanBPJS');
				$StrTipePasien = 'BPJS';			
			break;
		endswitch;
		
		$AkunIdLawan = (int) @$_ci->db->where('Akun_No', $AkunNoPiutangPasien)->get('mst_akun')->row()->Akun_ID;
		
		if ( $_insurer = $_ci->customer_model->get_by(['Kode_Customer' => $CustomerID]) ):
			$IntCustomerID = $_insurer->Customer_ID;
			$IntCustomerID_Penanggung = $_insurer->Customer_Id_Penanggung_RJ;
			$IntCustomerID_Penanggung_RI = $_insurer->Customer_Id_Penanggung;
		endif;
		
		if ( $RawatInap ):
			$IntCustomerID = (@$IntCustomerID_Penanggung_RI != 0 || !empty($IntCustomerID_Penanggung_RI) ) ? $IntCustomerID_Penanggung_RI : @$IntCustomerID;
		else:
			$IntCustomerID = (@$IntCustomerID_Penanggung != 0 || !empty($IntCustomerID_Penanggung)) ? $IntCustomerID_Penanggung : @$IntCustomerID;
		endif;
		
		$NoBukti = self::gen_audit_number();
		
		$_insert_audit = [
			"NoBukti" => $NoBukti,
			"Tanggal" => date('Y-m-d'),
			"Jam" => date('Y-m-d H:i:s'),
			"TglTransaksi" => $TglTransaksi,
			"Posting" => 0,
			"NoInvoice" => $NoInvoice,
			"Kelompok" => $TipeTransaksi,
			"UserID" => self::$user_auth->User_ID,
			"NoReg" => $NoReg,
			"PostingKeBackOffice" => $_ci->BO_1->initial
		];
		$_ci->audit_model->create( $_insert_audit );
		
		/*if ( $TipePasien == 9 && $NaikKelas == FALSE ):
			
			$_get_cost_rs = $_ci->db->query("SELECT * FROM GetCostRS('{$NoInvoice}', '{$NoReg}', '', '')")->row();
			$_insert_audit_cost = [
				'NoBukti' => $NoBukti,
				'Keterangan' => $_get_cost_rs->Keterangan,
				'Jumlah' => $_get_cost_rs->Jumlah,
				'NilaiTransaksi' => $_get_cost_rs->NominalTotal,
				'CostRS' => $_get_cost_rs->CostRSTotal
			];
			$_ci->audit_cost_model->create( $_insert_audit_cost );
			
			$_get_coefficient = $_ci->db->query("
										SELECT JasaName, Komponen, Jumlah, KelompokRemun, 
											Bobot, Koefesien, IDDokter, JmlPetugas 
										from dbo.Honor_rawatInap_Periode_Umum_PerNoreg_Header ('{$NoReg}') 
									")->row();
			
			$_insert_audit_coefficient = [
				'NoBukti' => $NoBukti,
				'JasaID' => $_get_coefficient->JasaName,
				'Komponen' => $_get_coefficient->Komponen,
				'Jumlah' => $_get_coefficient->Jumlah,
				'Kelompok' => $_get_coefficient->KelompokRemun,
				'Bobot' => $_get_coefficient->Bobot,
				'Koefesien' => $_get_coefficient->Koefesien,
				'DokterID' => $_get_coefficient->IDDokter,
				'jmlPetugas' => $_get_coefficient->JmlPetugas,
			];
			$_ci->audit_coefficient_model->create( $_insert_audit_coefficient );
			
		endif;*/
		
		$CurNilai = 0;
		if( $_get_cashier = $_ci->cashier_model->get_by(['NoBukti' => $NoInvoice, 'Batal' => 0, 'Audit' => 0]) ):
			
			$CurCOPay = (float) $_get_cashier->CoPay;
			$StrRJ = !empty($_get_cashier->RJ) ? $_get_cashier->RJ : 'RJ';
			
		endif;
		
		$_sub_service_group = $_ci->db->select("
					SectionName, ser.AkunNoRI, ser.AkunNORJ, ser.AkunNOUGD, ser.AkunNoRI as AkunNOOnCall,
					ROUND(SUM((JmlPemakaian * (Harga * ((100 - Disc) / 100))) + Hext), 0) AS Harga,
					ROUND(SUM((JmlPemakaian * (HargaOrig * ((100 - Disc) / 100))) + Hext), 0) AS HargaOrig, GroupVerifikator as Tipe
				")
				->from(" Verifikator('{$NoInvoice}', 0) ver ")
				->join("{$_ci->service_group_model->table} ser", "ver.GroupJasaID = ser.GroupJasaID" )
				->where(['Kelompok' => 'RINCIAN BIAYA', 'ver.KelompokPostingan' => 'GROUP JASA'])
				->group_by(["SectionName", "ser.AkunNoRI", "ser.AkunNORJ", "ser.AkunNOUGD", "GroupVerifikator"])
				->get_compiled_select();
		
		$_union_service_group = $_ci->db->select("
					union_ser.AkunNoRI1, union_ser.AkunNORJ, union_ser.AkunNOUGD, union_ser.AkunNOOnCall, 
					SUM(union_ser.Harga) as Harga, SUM(HargaOrig) as HargaOrig, union_ser.Tipe
				")
				->from("( {$_sub_service_group} ) AS union_ser ")
				->group_by([
					"union_ser.Tipe", "union_ser.AkunNoRI", "union_ser.AkunNORJ",
					"union_ser.AkunNOUGD", "union_ser.AkunNOOnCall" 
				])
				->get_compiled_select();

		$_union_cost_component = $_ci->db->select("
					cos.AkunNoRI, cos.AkunNORJ, cos.AkunNOUGD, cos.AkunNoRI as AkunNOOnCall,
					ROUND(SUM((JmlPemakaian * (Harga * ((100 - Disc) / 100))) + Hext), 0) AS Harga,
					ROUND(SUM((JmlPemakaian * (HargaOrig * ((100 - Disc) / 100))) + Hext), 0) AS HargaOrig, GroupVerifikator as Tipe 
				")
				->from(" Verifikator('{$NoInvoice}', 0) ver ")
				->join("{$_ci->cost_component_model->table} cos", "ver.KomponenID = cos.KomponenBiayaID" )
				->where(['Kelompok' => 'RINCIAN BIAYA', 'ver.KelompokPostingan' => 'GROUP JASA'])
				->group_by(["cos.AkunNoRI", "cos.AkunNORJ", "cos.AkunNOUGD", "GroupVerifikator"])
				->get_compiled_select();				
		
		if( $_union_collection = $_ci->db->query(" {$_union_service_group} UNION {$_union_cost_component} ")->result() ):
			foreach ( $_union_collection as $row ):
				// print_r($row);exit;
				$CurNilaiAkumJasa = 0;
				$CurHarga = round($row->Harga, 0);
                $CurHargaOrig = round($row->HargaOrig, 0);
				$StrAkunMA = "";
				
				switch( $row->Tipe ):
					case 'RJ':
						$StrAkun = $row->AkunNORJ;
					break;
					case 'OC':
						$StrAkun = $row->AkunNOOnCall;
					break;
					case 'UGD':
						$StrAkun = $row->AkunNOUGD;
					break;
					case 'RI':
						$StrAkun = $row->AkunNoRI;
					break;
					default:
					
						if( $RawatInap ):
							$StrAkun = $row->AkunNoRI;
						else:
							
							if( substr($SectionName, 0, 3) == 'UGD' ):
								$StrAkun = $row->AkunNOUGD;
							elseif( $PoliKlinik = "ON CALL" ):
								$StrAkun = $row->AkunNOOnCall;
							else:
								$StrAkun = $row->AkunNORJ;
							endif;
						endif;
					break;
				endswitch;
								
				$NoBuktiJurnal = sprintf("%s#%s#", $NoInvoice, "PEND");
				
				// pendapatan obat RJ/UGD
				if ( $StrAkun == "4010204" || $StrAkun == "4010105" ) :
					$AkunPPN = '2010205';
					$CurNilaiPendapatan = round( round($row->Harga, 0) * 100 / 110, 0); 
					$CurNilaiPPN = round( round($row->Harga, 0) - $CurNilaiPendapatan, 0);
					$CurNilaiPPNAkum = $CurNilaiPPNAkum + $CurNilaiPPN;
				endif;
				
				 //jurnal pendapatan                            
				$_insert_audit_revenue = [
					"NoBuktiJurnal" => $NoBuktiJurnal,
					"Debet" => 0,
					"Kredit" => ( $StrAkun == "4010204" || $StrAkun == "4010105" ) ? $CurNilaiPendapatan : round($row->Harga, 0),
					"Keterangan" => $Keterangan,
					"Posted" => 0,
					"AkunNo" => $StrAkun,
					"NoBukti" => $NoBukti,
				];
				$_ci->audit_revenue_model->create( $_insert_audit_revenue );
				
				$_sub_ver_service = $_ci->db->select("No_Bukti, Nama_Jasa, JmlPemakaian, Harga, Nomor, Disc")
					->from(" Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)")
					->where(['Akun_No' => $StrAkun])
					->group_by(["No_Bukti", "Nama_Jasa", "JmlPemakaian", "Harga", "Nomor", "Disc"])
					->get_compiled_select();
		
				$_ver_service = $_ci->db->select("
						Nama_Jasa, SUM(ver.JmlPemakaian) AS JmlPemakaian,
					 	ROUND(SUM(ver.JmlPemakaian * ( ver.Harga * ( 100 - ver.Disc ) / 100)), 0) AS Harga
					")
					->from("( {$_sub_ver_service} ) AS ver ")
					->group_by(["ver.Nama_jasa" ])
					->get();
					
				if( $_ver_service->num_rows() > 0): 
					foreach( $_ver_service->result() as $val ):
					
						// insert Audit pendapatan jasa
						$CurNilaiJasa = $val->Harga;
						$CurNilaiAkumJasa = $CurNilaiAkumJasa + $val->Harga;
						
						$_insert_audit_service = [
							"NoBuktiJurnal" => $NoBuktiJurnal,
							"Nilai" => round($val->Harga, 0),
							"Keterangan" => $Keterangan,
							"AkunNo" => $StrAkun,
							"NoBukti" => $NoBukti,
							"JasaName" => $val->Nama_Jasa,
							"Qty" => round($val->JmlPemakaian, 0)
						];
						$_ci->audit_service_model->create( $_insert_audit_service );
						
						
						// insert Audit pendapatan jasa (Komponen)
						$CurNilaiJasaDetail = 0;
						$_ver_service_component = $_ci->db->select("Komponen, ROUND(SUM(JmlPemakaian * (HargaKomponen * (100 - Disc) / 100)), 0) AS HargaKomponen")
							->from("Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)")
							->where(["HargaKomponen >" => 0, "Akun_No" => $StrAkun, "Nama_Jasa" => $val->Nama_Jasa])
							->group_by(["Komponen" ])
							->get();

						if( $_ver_service_component->num_rows() > 0 ):
							foreach($_ver_service_component->result() as $com): // $com  == component
								
								$CurNilaiJasaDetail = $CurNilaiJasaDetail + $com->HargaKomponen;
								$_insert_audit_component = [
									"NoBuktiJurnal" => $NoBuktiJurnal,
									"Nilai" => round($com->HargaKomponen, 0),
									"Keterangan" => $Keterangan,
									"AkunNo" => $StrAkun,
									"NoBukti" => $NoBukti,
									"JasaName" => trim($val->Nama_Jasa),
									"Qty" => round($val->JmlPemakaian, 0),
									"Komponen" => trim($com->Komponen)
								];
								$_ci->audit_component_model->create( $_insert_audit_component );
							
							endforeach;
						endif;
						
						if( ($CurNilaiJasaDetail - $CurNilaiJasa) > 100):
							self::_cancel_audit( $NoBukti, $NoInvoice );
							return [
								'state' => 2, 
								'message' => sprintf(lang('message:audit_service_audit_component_not_match'), $NoInvoice, $val->Nama_Jasa)
							];
						endif;		
				
						// insert Audit Kategori Operasi
						$CurNilaiJasaDetail = 0;
						$_sub_ver_category = $_ci->db->select("No_Bukti, KategoriOperasi, JmlPemakaian, Harga, Nomor, Disc")
							->from(" Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)")
							->where(['Akun_No' => $StrAkun, 'Nama_Jasa' => $val->Nama_Jasa])
							->group_by(['No_Bukti', 'KategoriOperasi', 'JmlPemakaian', 'Harga', 'Nomor', 'Disc'])
							->get_compiled_select();
				
						$_ver_category = $_ci->db->select("
								ver.KategoriOperasi, SUM( ver.JmlPemakaian ) AS JmlPemakaian,
								ROUND(SUM(ver.JmlPemakaian * ( ver.Harga * (100 - ver.Disc) / 100)), 0) AS Harga
							")
							->from("( {$_sub_ver_category} ) AS ver ")
							->group_by(["ver.KategoriOperasi" ])
							->get();
							
						if( $_ver_category->num_rows() > 0 ):
							foreach( $_ver_category->result() as $cat ): // $cat  == category surgery
							
								$CurNilaiJasaDetail = $CurNilaiJasaDetail + $cat->Harga;
								$_insert_audit_category = [
									"NoBuktiJurnal" => $NoBuktiJurnal,
									"Nilai" => round($cat->Harga, 0),
									"Keterangan" => $Keterangan,
									"AkunNo" => $StrAkun,
									"NoBukti" => $NoBukti,
									"JasaName" => trim($val->Nama_Jasa),
									"Qty" => round($cat->JmlPemakaian, 0),
									"KategoriOperasi" => trim($cat->KategoriOperasi)
								];
								$_ci->audit_category_surgery_model->create( $_insert_audit_category );
								
							endforeach;
						endif;
						
						if( ($CurNilaiJasaDetail - $CurNilaiJasa) > 100):
							self::_cancel_audit( $NoBukti, $NoInvoice );
							return [
								'state' => 2, 
								'message' => sprintf(lang('message:audit_service_audit_category_not_match'), $NoInvoice, $val->Nama_Jasa)
							];
						endif;	
						
						// insert Audit Pendapatan Section
						$CurNilaiJasaDetail = 0;
						$_sub_ver_section = $_ci->db->select("No_Bukti, SectionName, JmlPemakaian, Harga, Nomor, Disc")
							->from(" Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)")
							->where(['Akun_No' => $StrAkun, 'Nama_Jasa' => $val->Nama_Jasa])
							->group_by(['No_Bukti', 'SectionName', 'JmlPemakaian', 'Harga', 'Nomor', 'Disc'])
							->get_compiled_select();
				
						$_ver_section = $_ci->db->select("
								ver.SectionName, SUM( ver.JmlPemakaian ) AS JmlPemakaian,
								ROUND(SUM(ver.JmlPemakaian * ( ver.Harga * (100 - ver.Disc) / 100)), 0) AS Harga
							")
							->from("( {$_sub_ver_section} ) AS ver ")
							->group_by(["ver.SectionName" ])
							->get();
						
						if( $_ver_section->num_rows() > 0 ):
							foreach( $_ver_section->result() as $sec ): // $sec  == section
							
								$CurNilaiJasaDetail = $CurNilaiJasaDetail + $sec->Harga;
								
								$_insert_audit_section = [
									"NoBuktiJurnal" => $NoBuktiJurnal,
									"Nilai" => round($sec->Harga, 0),
									"Keterangan" => $Keterangan,
									"AkunNo" => $StrAkun,
									"NoBukti" => $NoBukti,
									"JasaName" => trim($val->Nama_Jasa),
									"Qty" => round($sec->JmlPemakaian, 0),
									"SectionName" => trim($sec->SectionName)
								];
								$_ci->audit_section_model->create( $_insert_audit_section );
																
							endforeach;
						endif;
						
						if( ($CurNilaiJasaDetail - $CurNilaiJasa) > 100):
							self::_cancel_audit( $NoBukti, $NoInvoice );
							return [
								'state' => 2, 
								'message' => sprintf(lang('message:audit_service_audit_section_not_match'), $NoInvoice, $val->Nama_Jasa)
							];
						endif;	
						
						
					endforeach;  /* End Foreach Audit Pendapatan Jasa*/
				endif;
				
				$CurNilai = $CurNilai + $row->Harga;
				
				if ( ($CurNilaiAkumJasa - round($row->Harga, 0)) > 100 ):
					self::_cancel_audit( $NoBukti, $NoInvoice );
					return [
						'state' => 2, 
						'message' => sprintf(lang('message:audit_total_audit_detail_not_match'), $NoInvoice, $StrAkun )
					];
				endif;
							
			endforeach; /* End Foreach Audit Pendapatan*/
		endif;
		
		
		if ( $CurNilaiPPNAkum > 0 ):
		
			$_insert_audit_revenue = [
					"NoBuktiJurnal" => $NoBuktiJurnal,
					"Debet" => 0,
					"Kredit" => $CurNilaiPPNAkum,
					"Keterangan" => $Keterangan,
					"Posted" => 0,
					"AkunNo" => $AkunPPN,
					"NoBukti" => $NoBukti,
				];
			$_ci->audit_revenue_model->create( $_insert_audit_revenue );
			
		endif;
		
		$_insert_audit_revenue = [
					"NoBuktiJurnal" => $NoBuktiJurnal,
					"Debet" => $CurNilai,
					"Kredit" => 0,
					"Keterangan" => $Keterangan,
					"Posted" => 0,
					"AkunNo" => $AkunNoPiutangPasien,
					"NoBukti" => $NoBukti,
				];
		$_ci->audit_revenue_model->create( $_insert_audit_revenue );
		
		$CurHutangInsentif = 0;
		$CurNilai = 0;
		
		if( config_item('PakeJurnalDiskonTidakLangsung') == 1 ): // Audit Pendapatan dengan diskon tidak langsung
			
			$_indirect_discount = $_ci->db->select("SUM(DiskonTdklangsung) AS Diskon")
										->where($_ci->cashier_model->index_key, $NoInvoice)
										->get( $_ci->cashier_model->table )
										->row();
										
			if( (float) @$_indirect_discount->Diskon > 0  ):
				
				$StrNoBuktiJurnal = sprintf("%s#%s#", $NoInvoice, "DiscTL");
				$StrKeterangan = sprintf("Diskon Tidak Langsung->Pasien : %s", $NamaPasien);
				
				$_insert_audit_revenue = [
					"NoBuktiJurnal" => $StrNoBuktiJurnal,
					"Debet" => (float) $_indirect_discount->Diskon,
					"Kredit" => 0,
					"Keterangan" => $StrKeterangan,
					"Posted" => 0,
					"AkunNo" => '4010501',
					"NoBukti" => $NoBukti,
				];
				$_ci->audit_revenue_model->create( $_insert_audit_revenue );
				
				$_insert_audit_revenue = [
					"NoBuktiJurnal" => $StrNoBuktiJurnal,
					"Debet" => 0,
					"Kredit" => (float) $_indirect_discount->Diskon,
					"Keterangan" => $StrKeterangan,
					"Posted" => 0,
					"AkunNo" => $AkunNoPiutangPasien,
					"NoBukti" => $NoBukti,
				];
				$_ci->audit_revenue_model->create( $_insert_audit_revenue );
				
				
			endif;
			
		endif;
		
		// Audit pendapatan Diskon
		$_discount = $_ci->db->select("SUM(b.NilaiDiscount) as Diskon, c.AkunNo, c.NamaDiscount")
							->from("{$_ci->cashier_model->table} a")
							->join("{$_ci->cashier_discount_model->table} b", "a.NoBukti = b.NoBukti", 'INNER')
							->join("{$_ci->discount_model->table} c", "b.IDDiscount = c.IDDiscount", 'INNER')
							->where(['c.DiskonTdkLangsung' => 0, 'a.Batal' => 0, 'a.NoBukti' => $NoInvoice])
							->group_by(['c.AkunNo', 'c.NamaDiscount'])
							->get()->result();
		
		if ( !empty( $_discount )): // Audit pendapatan Diskon (debit)
			foreach( $_discount as $dis): // dis = discount
				
				if ($dis->Diskon > 0):
					
					$StrNoBuktiJurnal = sprintf("%s#%s#", $NoInvoice, "DISC");
					$StrKeterangan = sprintf("Diskon Penjualan(%s) -> Pasien : %s", $dis->NamaDiscount, $NamaPasien);
					
					$_insert_audit_revenue = [
						"NoBuktiJurnal" => $StrNoBuktiJurnal,
						"Debet" => (float) $dis->Diskon,
						"Kredit" => 0,
						"Keterangan" => $StrKeterangan,
						"Posted" => 0,
						"AkunNo" => $dis->AkunNo,
						"NoBukti" => $NoBukti,
					];
					$_ci->audit_revenue_model->create( $_insert_audit_revenue );
					
					$CurNilai = $CurNilai + $dis->Diskon;
					
				endif;
				
			endforeach;
		endif;
	
		if ( $CurNilai != 0 ): // Audit pendapatan Diskon (kredit)
		
			$StrKeterangan = sprintf("Diskon Penjualan -> Pasien : %s", $NamaPasien);
					
			$_insert_audit_revenue = [
				"NoBuktiJurnal" => $StrNoBuktiJurnal,
				"Debet" => 0,
				"Kredit" => (float) round($CurNilai, 0),
				"Keterangan" => $StrKeterangan,
				"Posted" => 0,
				"AkunNo" => $AkunNoPiutangPasien,
				"NoBukti" => $NoBukti,
			];
			$_ci->audit_revenue_model->create( $_insert_audit_revenue );
		
		endif;
		
		
		// Audit Pembayaran Jurnal (deposit)
		if( $CurNilaiDeposit > 0 ): 
			
			$StrNoBuktiJurnal = sprintf("%s#%s#", $NoInvoice, "PELDEP");
			$StrKeterangan = sprintf("Balance Deposit : %s", $NamaPasien);
			
			$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal debit
				"NoBuktiJurnal" => $StrNoBuktiJurnal,
				"Debet" => (float) $CurNilaiDeposit,
				"Kredit" => 0,
				"Keterangan" => $StrKeterangan,
				"AkunNo" => config_item('RekeningDeposit'),
				"NoBukti" => $NoBukti,
			];
			$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
			
			$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal kredit
				"NoBuktiJurnal" => $StrNoBuktiJurnal,
				"Debet" => 0,
				"Kredit" => (float) $CurNilaiDeposit,
				"Keterangan" => $StrKeterangan,
				"AkunNo" => $AkunNoPiutangPasien,
				"NoBukti" => $NoBukti,
			];
			$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
			
		endif;
		
		
		// Audit Pembayaran Jurnal (detail kasir)
		$_cashier_detail = $_ci->db->select("b.*, d.Akun_No, a.KodeCustomerPenjamin, a.DiskonTdkLangsung")
							->from("{$_ci->cashier_model->table} a")
							->join("{$_ci->cashier_detail_model->table} b", "a.NoBukti = b.NoBukti", 'INNER')
							->join("{$_ci->type_payment_model->table} c", "b.IDBayar = c.IDBayar", 'INNER')
							->join("mst_akun d", "c.Akun_Id = d.Akun_ID", 'LEFT OUTER')
							->where(['NilaiBayar !=' => 0, 'a.NoBukti' => $NoInvoice])
							->order_by('b.IDBayar', 'ASC')
							->get()->result();
		
		if( !empty($_cashier_detail)):
			foreach( $_cashier_detail as $cad ): // cad = cashier detail
				
				$CurNilaiDiskonTdkLangsung = $cad->DiskonTdkLangsung;
				$StrNoBuktiJurnal = sprintf("%s#%s#%s", $NoInvoice, "BYR", $cad->IDBayar);
				$StrKeterangan = sprintf("Pembayaran Pasien : %s", $NamaPasien);
				
				if( $cad->NilaiBayar < 0 ):
					
					if( $cad->IDBayar == 4 ):
						
						$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal kredit
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => 0,
							"Kredit" => $cad->NilaiBayar * -1,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => config_item('AkunKelebihanDeposit'),
							"NoBukti" => $NoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
						
						$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal debit
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => $cad->NilaiBayar * -1,
							"Kredit" => 0,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => $AkunNoPiutangPasien,
							"NoBukti" => $NoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
						
					elseif( $cad->IDBayar == 12 && $cad->IDBayar != 9 ):
						
						$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal kredit
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => 0,
							"Kredit" => $cad->NilaiBayar * -1,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => $cad->Akun_No,
							"NoBukti" => $NoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
						
						$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal debit
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => $cad->NilaiBayar * -1,
							"Kredit" => 0,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => $AkunNoPiutangPasien,
							"NoBukti" => $NoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
						
					endif;
					
					goto skip_if_minus;
					
				endif;
				
				
				if( ($cad->NilaiBayar > $CurCOPay) || $TipePasien === 9 || $TipePasien === 12 ):
				
					switch ( $cad->IDBayar ):
						
						case 12:
							
							if( $TipePasien === 9 ): // Audit Pembayaran jurnal BPJS
								
								$CurNilaiKeuntunganBPJS = $cad->NilaiBayar;
								if( $CurNilaiKeuntunganBPJS < 0 ): // Nilai Keuntungan BPJS
									
									$CurNilaiKeuntunganBPJS = $CurNilaiKeuntunganBPJS * -1;
									$_insert_audit_journal_payment = [ 
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => 0,
										"Kredit" => $CurNilaiKeuntunganBPJS,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => config_item('AkunNoKeuntunganBPJS'),
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
									$_insert_audit_journal_payment = [
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => $CurNilaiKeuntunganBPJS,
										"Kredit" => 0,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => $AkunNoPiutangPasien,
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
								elseif( $CurNilaiKeuntunganBPJS > 0 ):

									$_insert_audit_journal_payment = [ 
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => $CurNilaiKeuntunganBPJS,
										"Kredit" => 0,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => config_item('AkunNoKeuntunganBPJS'),
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
									$_insert_audit_journal_payment = [
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => 0,
										"Kredit" => $CurNilaiKeuntunganBPJS,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => $AkunNoPiutangPasien,
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
								endif;
								
							else:
							
								// Jika bukan pasien JKN
								if( !empty($cad->Akun_No) ):
									
									$_insert_audit_journal_payment = [ 
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => $cad->NilaiBayar,
										"Kredit" => 0,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => $cad->Akun_No,
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
									$_insert_audit_journal_payment = [
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => 0,
										"Kredit" => $cad->NilaiBayar,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => $AkunNoPiutangPasien,
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
								endif;
								
							endif;	
						break;
						
						case 13:
						
							if ( @$IntCustomerID === 0 || empty($IntCustomerID) ):
								self::_cancel_audit( $NoBukti, $NoInvoice );
								return [
									'state' => 2, 
									'message' => sprintf(lang('message:audit_bpjs_customer_null'), $NRM, $No_Invoice )
								];
							endif;
							
							$NoBuktiAR = sprintf("%s_%s", $NoInvoice, "BPJS");
							$KeteranganBPJS = ($CurNilaiDiskonTdkLangsung > 0)
											? sprintf("%s#%s#%s%s", $StrKeterangan, 'BPJS', 'Diskon Tdk Langsung', number_format( $CurNilaiDiskonTdkLangsung, 2, '.', ',') )
											: sprintf("%s#%s", $StrKeterangan, 'BPJS');
							
							// audit detail AR			
							$_check_audit_AR = $_ci->audit_detail_ar_model->count_all(['NoBukti' => $NoBukti, 'NoBuktiTransaksi' => $NoInvoice]);
							$_insert_audit_detail_ar = [
								'NoBukti' => $NoBukti,
								'NoBuktiTransaksi' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'Nomor' => 1,
								'CustomerID' => $IntCustomerID,
								'NilaiPiutang' => $cad->NilaiBayar,
								'AkunID' => config_item('IDAkunPiutangBPJS'),
								'Keterangan' => $KeteranganBPJS,
								'NRM' => $NRM,
								'NamaPasien' => $NamaPasien,
								'AkunLawanID' => $AkunIdLawan,
								'NoBuktiAR' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'NoReg' => $NoReg,
								'TglClosing' => $TglTransaksi,
								'CustomerIDDitagihkanKe' => $IntCustomerID,
								'NamaPenanggung' => $NamaPasien,
								'TypePiutangID' => config_item('TypePiutangBPJS')
							];
							$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
						break;
						
						case 11:
						
							if ( $_insurer = $_ci->customer_model->get_by(['Kode_Customer' => $cad->KodeCustomerPenjamin]) ):
								$IntCustomerID_LOG = $_insurer->Customer_ID;
								$IntCustomerID_Penanggung_LOG = $_insurer->Customer_Id_Penanggung_RJ;
								$IntCustomerID_Penanggung_RI_LOG = $_insurer->Customer_Id_Penanggung;
							endif;
							
							if ( @$IntCustomerID_LOG === 0 || empty($IntCustomerID_LOG)  ):
								self::_cancel_audit( $NoBukti, $NoInvoice );
								return [
									'state' => 2, 
									'message' => sprintf(lang('message:audit_log_customer_null'), $NRM, $No_Invoice )
								];
							endif;
							
							$NoBuktiAR = sprintf("%s#%s", $NoInvoice, "11");
							
							$KeteranganLOG = ($CurNilaiDiskonTdkLangsung > 0)
										? sprintf("%s#%s#%s%s", $StrKeterangan, 'LOG/IKS', 'Diskon Tdk Langsung', number_format( $CurNilaiDiskonTdkLangsung, 2, '.', ',') )
										: sprintf("%s#%s", $StrKeterangan, 'LOG/IKS');
							
							// audit detail AR			
							$_check_audit_AR = $_ci->audit_detail_ar_model->count_all(['NoBukti' => $NoBukti, 'NoBuktiTransaksi' => $NoInvoice]);
							$_insert_audit_detail_ar = [
								'NoBukti' => $NoBukti,
								'NoBuktiTransaksi' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'Nomor' => 1,
								'CustomerID' => $IntCustomerID_LOG,
								'NilaiPiutang' => $cad->NilaiBayar,
								'AkunID' => config_item('IDAkunPiutangIKS'),
								'Keterangan' => $KeteranganLOG,
								'NRM' => $NRM,
								'NamaPasien' => $NamaPasien,
								'AkunLawanID' => $AkunIdLawan,
								'NoBuktiAR' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'NoReg' => $NoReg,
								'TglClosing' => $TglTransaksi,
								'CustomerIDDitagihkanKe' => $IntCustomerID_LOG,
								'NamaPenanggung' => $NamaPasien,
								'TypePiutangID' => config_item('TypePiutangIKS')
							];
							$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
							
						break;
						
						case 6:
							if( $TipePasien == 1 ):
								
								if ( @$IntCustomerID === 0 || empty($IntCustomerID) ):
									self::_cancel_audit( $NoBukti, $NoInvoice );
									return [
										'state' => 2, 
										'message' => sprintf(lang('message:audit_achc_customer_null'), $NRM, $No_Invoice )
									];
								endif;
								
								$KeteranganLOG = ($CurNilaiDiskonTdkLangsung > 0)
												? sprintf("%s %s %s", $StrKeterangan, 'Diskon Tdk Langsung', number_format( $CurNilaiDiskonTdkLangsung, 2, '.', ',') )
												: $StrKeterangan;
								
								$_insert_audit_detail_ar = [
									'NoBukti' => $NoBukti,
									'NoBuktiTransaksi' => $NoInvoice,
									'Nomor' => 1,
									'CustomerID' => $IntCustomerID,
									'NilaiPiutang' => $cad->NilaiBayar,
									'AkunID' => config_item('IDAkunPiutangHC'),
									'Keterangan' => $KeteranganLOG,
									'NRM' => $NRM,
									'NamaPasien' => $NamaPasien,
									'AkunLawanID' => $AkunIdLawan,
									'NoBuktiAR' => $NoInvoice,
									'NoReg' => $NoReg,
									'TglClosing' => $TglTransaksi,
									'CustomerIDDitagihkanKe' => $IntCustomerID,
									'NamaPenanggung' => $NamaPasien,
									'TypePiutangID' => config_item('TypePiutangHC')
								];
								$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
		
							endif;
						break;
						
						case 5:
							
							if ( @$IntCustomerID === 0 || empty($IntCustomerID) ):
								self::_cancel_audit( $NoBukti, $NoInvoice );
								return [
									'state' => 2, 
									'message' => sprintf(lang('message:audit_iks_customer_null'), $NRM, $NoInvoice )
								];
							endif;
							
							$KeteranganLOG = ($CurNilaiDiskonTdkLangsung > 0)
										? sprintf("%s %s %s", $StrKeterangan, 'Diskon Tdk Langsung', number_format( $CurNilaiDiskonTdkLangsung, 2, '.', ',') )
										: $StrKeterangan;
							
							$_check_audit_AR = $_ci->audit_detail_ar_model->count_all(['NoBukti' => $NoBukti, 'NoBuktiTransaksi' => $NoInvoice]);
							$_insert_audit_detail_ar = [
								'NoBukti' => $NoBukti,
								'NoBuktiTransaksi' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'Nomor' => 1,
								'CustomerID' => $IntCustomerID,
								'NilaiPiutang' => $cad->NilaiBayar,
								'Keterangan' => $KeteranganLOG,
								'NRM' => $NRM,
								'NamaPasien' => $NamaPasien,
								'AkunLawanID' => $AkunIdLawan,
								'NoBuktiAR' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'NoReg' => $NoReg,
								'TglClosing' => $TglTransaksi,
								'CustomerIDDitagihkanKe' => $IntCustomerID,
								'NamaPenanggung' => $NamaPasien,
							];
							
							switch ($TipePasien):
								case 1:
									$_insert_audit_detail_ar['AkunID'] = config_item('IDAkunPiutangHC');
									$_insert_audit_detail_ar['TypePiutangID'] = config_item('TypePiutangHC');
								break;
								case 2:
									$_insert_audit_detail_ar['AkunID'] = config_item('IDAkunPiutangIKS');
									$_insert_audit_detail_ar['TypePiutangID'] = config_item('TypePiutangIKS');
								break;
							endswitch;
							
							$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
							
						break;
						
						case 19:
							// Bon karyawan
							if( config_item('PiutangKaryawanMulai') == 1 && ( $NIK != "" || $KodeDokter != "" )):
								
								$StrKodeCustomer = !empty($NIK) ? $NIK : $KodeDokter;
								$StrNoBuktiAR = sprintf("%s#%s", $NoInvoice, '19');
								$StrKeterangan = sprintf("%s#%s", $StrKeterangan, $StrKodeCustomer);
								$_get_customer = $_ci->customer_model->get_by( ['Kode_Customer' => $StrKodeCustomer] );
								
								if ( empty($_get_customer->Customer_ID) ):
									self::_cancel_audit( $NoBukti, $NoInvoice );
									return [
										'state' => 2, 
										'message' => sprintf(lang('message:audit_employee_customer_null'), $StrKodeCustomer )
									];
								endif;
								
								$_insert_audit_detail_ar = [
									'NoBukti' => $NoBukti,
									'NoBuktiTransaksi' => $StrNoBuktiAR,
									'Nomor' => 1,
									'CustomerID' => $_get_customer->Customer_ID,
									'NilaiPiutang' => $cad->NilaiBayar,
									'AkunID' => !empty($NIK) ? config_item('IDAkunPiutangKaryawan') : config_item('IDAkunPiutangDokter'),
									'Keterangan' => $KeteranganLOG,
									'NRM' => $NRM,
									'NamaPasien' => $NamaPasien,
									'AkunLawanID' => $AkunIdLawan,
									'NoBuktiAR' => $StrNoBuktiAR,
									'NoReg' => $NoReg,
									'TglClosing' => $TglTransaksi,
									'CustomerIDDitagihkanKe' => $_get_customer->Customer_ID,
									'NamaPenanggung' => $NamaPasien,
									'TypePiutangID' => !empty($NIK) ? config_item('TypePiutangKaryawan') : config_item('TypePiutangDokter')
								];
								$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
								
							endif;
						break;
						
						case 4:
							
							$_insert_audit_journal_payment = [ 
								"NoBuktiJurnal" => $StrNoBuktiJurnal,
								"Debet" => $cad->NilaiBayar - $CurCoPay,
								"Kredit" => 0,
								"Keterangan" => $StrKeterangan,
								"AkunNo" => $cad->Akun_No,
								"NoBukti" => $NoBukti,
							];
							$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
							
							$_insert_audit_journal_payment = [
								"NoBuktiJurnal" => $StrNoBuktiJurnal,
								"Debet" => 0,
								"Kredit" => $cad->NilaiBayar - $CurCoPay,
								"Keterangan" => $StrKeterangan,
								"AkunNo" => $AkunNoPiutangPasien,
								"NoBukti" => $NoBukti,
							];
							$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
							
						break;
						
						case 2: case 9: case 20:
							if( $cad->Akun_No != "" || $DokterBon != "" ):
							
								$StrKeterangan = !empty($cad->Akun_No)
												? sprintf("%s#%s", $StrKeterangan, (!empty($NamaPegawai) && ($NamaPasien != $NamaPegawai)) ? $NamaPegawai : NULL )
												: sprintf("%s#%s", $StrKeterangan, $DokterBon );
								
								$_insert_audit_journal_payment = [ 
									"NoBuktiJurnal" => $StrNoBuktiJurnal,
									"Debet" => $cad->NilaiBayar,
									"Kredit" => 0,
									"Keterangan" => $StrKeterangan,
									"AkunNo" => !empty($cad->Akun_No) ? $cad->Akun_No : $AkunNo_BonDokter,
									"NoBukti" => $NoBukti,
								];
								$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
								
								$_insert_audit_journal_payment = [
									"NoBuktiJurnal" => $StrNoBuktiJurnal,
									"Debet" => 0,
									"Kredit" => $cad->NilaiBayar,
									"Keterangan" => $StrKeterangan,
									"AkunNo" => $AkunNoPiutangPasien,
									"NoBukti" => $NoBukti,
								];
								$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
								
							endif;
						break;
						
						case 7: case 10:
							
							if(!empty($_get_cashier)):
								
								$credit_card[] = (object) [
									'NilaiPembayaranKKAwal' => $_get_cashier->NilaiPembayaranKKAwal,
									'AddCharge' => $_get_cashier->AddCharge,
									'IDBank' => $_get_cashier->IDBank
								];								
								$credit_card[] = (object) [
									'NilaiPembayaranKKAwal' => $_get_cashier->NilaiPembayaranKKAwal1,
									'AddCharge' => $_get_cashier->AddCharge1,
									'IDBank' => $_get_cashier->IDBank1
								];								
								$credit_card[] = (object) [
									'NilaiPembayaranKKAwal' => $_get_cashier->NilaiPembayaranKKAwal2,
									'AddCharge' => $_get_cashier->AddCharge2,
									'IDBank' => $_get_cashier->IDBank2
								];								
								$credit_card[] = (object) [
									'NilaiPembayaranKKAwal' => $_get_cashier->NilaiPembayaranKKAwal3,
									'AddCharge' => $_get_cashier->AddCharge3,
									'IDBank' => $_get_cashier->IDBank3
								];								
								
								foreach( $credit_card as $cc ): // cc = credit card
									if ( (float) $cc->NilaiPembayaranKKAwal != 0 ):
										$_get_merchan_account = $_ci->db->select('b.Akun_No')
																		->from("{$_ci->merchan_model->table} a")
																		->join("{$_ci->BO_1->database}.dbo.Mst_Akun b", "a.Akun_ID_Tujuan = b.Akun_ID", "INNER")
																		->where('ID', $cc->IDBank)
																		->get()->row();
																		
										$StrNoBuktiJurnal = sprintf("%s#%s#%s#%s#", $NoInvoice, 'BYR', $cad->IDBayar, $cc->IDBank);
										
										$_insert_audit_journal_payment = [ 
											"NoBuktiJurnal" => $StrNoBuktiJurnal,
											"Debet" => $cc->NilaiPembayaranKKAwal + $cc->AddCharge,
											"Kredit" => 0,
											"Keterangan" => $StrKeterangan,
											"AkunNo" => $_get_merchan_account->Akun_No,
											"NoBukti" => $NoBukti,
										];
										$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
										
										$_insert_audit_journal_payment = [
											"NoBuktiJurnal" => $StrNoBuktiJurnal,
											"Debet" => 0,
											"Kredit" => $cc->NilaiPembayaranKKAwal,
											"Keterangan" => $StrKeterangan,
											"AkunNo" => $AkunNoPiutangPasien,
											"NoBukti" => $NoBukti,
										];
										$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
										
										if( $cc->AddCharge > 0 ):
										
											$_insert_audit_journal_payment = [
												"NoBuktiJurnal" => $StrNoBuktiJurnal,
												"Debet" => 0,
												"Kredit" => $cc->AddCharge,
												"Keterangan" => $StrKeterangan,
												"AkunNo" => config_item('akunIDAddCharge'),
												"NoBukti" => $NoBukti,
											];
											$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
											
										endif;
									endif;
									
								endforeach; // End foreach cc = credit card
							endif; // End if !empty($_get_cashier)
						break;
						
					endswitch; // End switch IDBayar
				
				endif; // End if( ($cad->NilaiBayar > $CurCOPay) || $TipePasien == 9 || $TipePasien == 12 )
				
				
				skip_if_minus: // goto label
				
			endforeach; // End loop Audit Pembayaran Jurnal (detail kasir)
		endif; // End if !empty($_cashier_detail)
		
		$activities_description = sprintf( "Input VERIF NON PIUTANG - PROSES SAVE DATA. # %s - %s", $NoInvoice, $NamaPasien );
		insert_user_activity( $activities_description, 'VERIFIKATOR', self::$user_auth->Nama_Asli );
		
		
		/*$_trans_date = self::$_trans_date;
		if( $StrRJ == 'RI' ): // Jika Rawat inap, hitung honor dokter
		
			$_db_select = "
				NoReg, 
				IDDokter,
				Kelas,
				Komponen,
				Keterangan,
				JenisKerjasama,
				Tanggal,
				NoInvoice,
				SUM(Discount) AS Discount,
				Tarif, 
				SUM(jumlah) AS Jumlah,
				SUM(nilaiAlat) AS NilaiAlat,
				GroupJasaID,
				JasaName
			";
			$_get_honor = $_ci->db->select( $_db_select )
								->from("HOnor_RawatInap_Periode_UMUM('{$_trans_date}', '{$_trans_date}')")
								->where(["IDDokter NOT IN ('XX', 'xx', 'RSU', 'RSIA')" => NULL, 'NoInvoice' => $NoInvoice])
								->group_by([
									'Noreg', 
									'IDDokter',
									'Kelas',
									'Komponen',
									'Keterangan',
									'JenisKerjasama',
									'Tanggal',
									'NoInvoice',
									'Tarif',
									'GroupJasaID',
									'JasaName'
								])->get()->result();
			
			if( $_get_honor ):
				foreach( $_get_honor as $hon ): // hon = honor
					
					$StrKeteranganMixed = sprintf("%s%s%s", $hon->Keterangan, $NoInvoice, $hon->Tarif);
					$StrJasaName = sprintf("%s#%s", $hon->JasaName, $hon->Komponen);
					
					$_insert_audit_honor = [
						"JenisKerjasama" => $TipePasien,
						"TglTransaksi" => self::$_trans_date,
						"JasaName" => $StrJasaName,
						"Kelas" => $hon->Kelas,
						"Keterangan" => $hon->Keterangan,
						"Qty" => $hon->jumlah,
						"Tarif" => $hon->Tarif,
						"Diskon" => $hon->Discount,
						"KeteranganMixed" => $StrKeteranganMixed,
						"NoInvoice" => $NoInvoice,
						"TglClosing" => self::$_trans_date,
						"NoBukti" => $NoBukti,
						"DokterID" => $hon->IDDokter,
						"NoReg" => $hon->NoReg,
						"JasaID" => $hon->Komponen,
						"NRM" => $NRM,
						"NilaiALat" => $hon->NilaiAlat,
						"Ket" => 'UMUM',
						"GroupJasaID" => $hon->GroupJasaID,
						"Potongan" => 0,
						"KeteranganPotongan" => '-'
					];
					
					if( ! $_ci->audit_honor_model->create( $_insert_audit_honor ) ):
	
						self::_cancel_audit( $NoBukti, $NoInvoice );
						return [
							'state' => 2, 
							'message' => lang('message:error_refresh_data')
						];					
					endif;
					
				endforeach; // end foreach honor
			endif; // endif ( $_get_honor )
		endif; // end if ( $StrRJ == 'RI' )*/
		
		if(config_item('VerifikatorHitungHonor') == 1)
			self::_audit_honor($NoBukti, $NoInvoice);
		
		$_ci->cashier_model->update(['Audit' => 1], $NoInvoice);
		
		return [
			'state' => 1, 
			'message' => lang('message:revenue_recognition_successfully')
		];
	}
	
	/*
		@params	
		(Array) $arguments :
		
			(tipe) 		key 			=> value 
		{  
			(date)		'TglTransaksi' 	=> Tanggal transaksi pada pembayaran kasir,
			(string) 	'NoInvoice' 	=> No Invoice pada pembayaran kasir,
			(string) 	'NRM' 			=> Nomor medical record pasien,
			(string) 	'NoReg' 		=> Nomor Registrasi pasien,
			(string) 	'PoliKlinik' 	=> Jenis poiliklinik pada section,
			(string) 	'NamaPasien' 	=> Nama pasien,
			(string) 	'TipeTransaksi' => Jenis Transaksi Perawatan ('RAWAT INAP', 'RAWAT JALAN'),
			(boolean) 	'RawatInap' 	=> Status data rawat inap atau tidak (TRUE, FALSE),
			(string) 	'SectionName' 	=> Nama section, untuk rawat inap nilainya '' ,
			(booleand) 	'PasienAsuransi'=> Status data pasien asuransi (TRUE, FALSE),
			(int) 		'TipePasien' 	=> Tipe pasien (jika null, standar rawat jalan 1, standar rawat inap 3),
			(string) 	'CustomerID' 	=> Kode Perusahaan(Kode_Customer) BPJS IKS,
			(string) 	'Keterangan' 	=> Keterangan berisi nama pasien,
			(float) 	'CurCoPay' 		=> Nilai co payment, untuk rawat inap nilainya 0,
			(string) 	'DokterBon' 	=> Nama_Supplier (dokter bon) ,
			(stirng) 	'NamaPegawai' 	=> Berisi NIK dan nama pegawainya (%s->%s, nik, nama pegawai),
			(string) 	'NIK' 			=> NIK pegawai,
			(string) 	'KodeDokter' 	=> DokterBonID (Kode Supplier),
		}
	*/
	private static function _audit_examination_with_split( $arguments )
	{
		$_ci = self::$_ci;
		extract($arguments);
		
		// Account Prefix Untuk Group Jasa, Jika Polikinik Spesialis maka Gunakan Akun_2 (Rekening Ke 2)
		$_account_suffix = '';
		$_db_suffix = 'BO_1';
		if( $PoliKlinik == 'SPESIALIS' ){
			$_account_suffix = '';		
			$_db_suffix = 'BO_1';
		}
				
		$AkunNo_BonDokter = "1010303005";
		$CurNilaiPPNAkum = 0;
		$NaikKelas = (boolean) $_ci->db->from("{$_ci->cashier_model->table} a")
									->join("{$_ci->registration_model->table} b", "a.NoReg = b.NoReg", "INNER")
									->where(['NoBukti' => $NoInvoice, 'NaikKelas' => 1 ])
									->count_all_results();
		
		$CurNilaiDeposit = (float) @$_ci->db->select("SUM(NilaiDeposit) AS Total")
										->where(['Batal' => 0, 'NoReg' => $NoReg])
										->get("{$_ci->deposit_model->table}")
										->row()->Total;
										
		$CurBookingPayment = (float) @$_ci->db->select("SUM( a.Deposit + a.NilaiPembayaranCC ) AS Total")
										->from("{$_ci->booking_payment_model->table} a")
										->join("{$_ci->reservation_model->table} b", "a.NoReservasi = b.NoReservasi", "INNER")
										->join("{$_ci->registration_model->table} c", "b.NoReservasi = c.NoReservasi", "INNER")
										->where(['a.Batal' => 0, 'c.NoReg' => $NoReg])
										->get()->row()->Total;
										
		$CurOTCDrug = (float) @$_ci->db->select("SUM( a.NilaiPembayaran + a.NilaiPembayaranCC ) AS Total")
										->from("{$_ci->otc_drug_model->table} a")
										->join("{$_ci->reservation_model->table} b", "a.NoReservasi = b.NoReservasi", "INNER")
										->join("{$_ci->registration_model->table} c", "b.NoReservasi = c.NoReservasi", "INNER")
										->where(['a.Batal' => 0, 'c.NoReg' => $NoReg])
										->get()->row()->Total;
										
		$CurNilaiDeposit = $CurNilaiDeposit + $CurBookingPayment + $CurOTCDrug;
		
		switch ( $TipePasien ):
			case 1:
				$AkunNoPiutangPasien = config_item("AkunLawanPendatanHC{$_account_suffix}");
				if ( $_ci->registration_model->count_all(['NoReg' => $NoReg, 'IKSMixed' => 1]) )
				{
					$AkunNoPiutangPasien = config_item("AkunLawanPendapatanIKSMIXED");
				}
				
				$StrTipePasien = 'HC';
			break;
			case 2:
				$AkunNoPiutangPasien = config_item("AkunLawanPendatanIKS{$_account_suffix}");
				$StrTipePasien = 'IKS';
			break;
			case 3:
				$AkunNoPiutangPasien = config_item("AkunLawanPendatanUMUM{$_account_suffix}");
				$StrTipePasien = 'UMUM';			
			break;
			case 4:
				$AkunNoPiutangPasien = config_item("AkunLawanPendatanEXECUTIVE{$_account_suffix}");
				$StrTipePasien = 'EXECUTIVE';			
			break;
			case 9:
				$AkunNoPiutangPasien = config_item("AkunLawanPendapatanBPJS{$_account_suffix}");
				$StrTipePasien = 'BPJS';			
			break;
		endswitch;
		
		$AkunIdLawan = (int) @$_ci->{$_db_suffix}->where('Akun_No', $AkunNoPiutangPasien)->get('Mst_Akun')->row()->Akun_ID;
		
		if ( $_insurer = $_ci->customer_model->get_by(['Kode_Customer' => $CustomerID]) ):
			$IntCustomerID = $_insurer->Customer_ID;
			$IntCustomerID_Penanggung = $_insurer->Customer_Id_Penanggung_RJ;
			$IntCustomerID_Penanggung_RI = $_insurer->Customer_Id_Penanggung;
			
		endif;
		
		// if ( $RawatInap ):
		// 	$IntCustomerID = (@$IntCustomerID_Penanggung_RI !== 0 || !empty($IntCustomerID_Penanggung_RI) ) ? $IntCustomerID_Penanggung_RI : @$IntCustomerID;
		// else:
		// 	$IntCustomerID = (@$IntCustomerID_Penanggung !== 0 || !empty($IntCustomerID_Penanggung)) ? $IntCustomerID_Penanggung : @$IntCustomerID;
		// endif;

		$NoBukti = self::gen_audit_number();
		
		$_insert_audit = [
			"NoBukti" => $NoBukti,
			"Tanggal" => date('Y-m-d'),
			"Jam" => date('Y-m-d H:i:s'),
			"TglTransaksi" => $TglTransaksi,
			"Posting" => 0,
			"NoInvoice" => $NoInvoice,
			"Kelompok" => $TipeTransaksi,
			"UserID" => self::$user_auth->User_ID,
			"NoReg" => $NoReg,
			"PostingKeBackOffice" => $_db_suffix
		];
		$_ci->audit_model->create( $_insert_audit );
		
		/*if ( $TipePasien == 9 && $NaikKelas == FALSE ):
			
			$_get_cost_rs = $_ci->db->query("SELECT * FROM GetCostRS('{$NoInvoice}', '{$NoReg}', '', '')")->row();
			$_insert_audit_cost = [
				'NoBukti' => $NoBukti,
				'Keterangan' => $_get_cost_rs->Keterangan,
				'Jumlah' => $_get_cost_rs->Jumlah,
				'NilaiTransaksi' => $_get_cost_rs->NominalTotal,
				'CostRS' => $_get_cost_rs->CostRSTotal
			];
			$_ci->audit_cost_model->create( $_insert_audit_cost );
			
			$_get_coefficient = $_ci->db->query("
										SELECT JasaName, Komponen, Jumlah, KelompokRemun, 
											Bobot, Koefesien, IDDokter, JmlPetugas 
										from dbo.Honor_rawatInap_Periode_Umum_PerNoreg_Header ('{$NoReg}') 
									")->row();
									
			$_insert_audit_coefficient = [
				'NoBukti' => $NoBukti,
				'JasaID' => $_get_coefficient->JasaID,
				'Komponen' => $_get_coefficient->Komponen,
				'Jumlah' => $_get_coefficient->Jumlah,
				'Kelompok' => $_get_coefficient->KelompokRemun,
				'Bobot' => $_get_coefficient->Bobot,
				'Koefesien' => $_get_coefficient->Koefesien,
				'DokterID' => $_get_coefficient->IDDokter,
				'jmlPetugas' => $_get_coefficient->JmlPetugas,
			];
			$_ci->audit_coefficient_model->create( $_insert_audit_coefficient );
			
		endif;*/
		
		$CurNilai = 0;
		if( $_get_cashier = $_ci->cashier_model->get_by(['NoBukti' => $NoInvoice, 'Batal' => 0, 'Audit' => 0]) ):
			
			$CurCOPay = (float) $_get_cashier->CoPay;
			$StrRJ = !empty($_get_cashier->RJ) ? $_get_cashier->RJ : 'RJ';
			
		endif;
		
		if( $PoliKlinik == 'SPESIALIS' ){
			self::$_is_split = (float) $_ci->db->where_in('KomponenID', ['DT60', 'DT51'])
											->where(["HargaKomponen >" => 0])
											->count_all_results(" Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)");
			if(self::$_is_split){
				self::$_split_component = $_ci->db->select("
												KomponenID,
												Komponen, 
												ROUND(SUM(JmlPemakaian * (HargaKomponen * (100 - Disc) / 100)), 0) AS HargaKomponen
											")
											->from("Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)")
											->where_in('KomponenID', ['DT60', 'DT51'])
											->where(["HargaKomponen >" => 0])
											->group_by(["KomponenID", "Komponen" ])
											->get()
											->result();
			}
		}		
		
		if(self::$_is_split)
			 $_ci->db->where_not_in('KomponenID', ['DT60', 'DT51']);
			 
		$_sub_service_group = $_ci->db->select("
					SectionName, 
					ser.AkunNoRI{$_account_suffix} AS AkunNoRI, 
					ser.AkunNORJ{$_account_suffix} AS AkunNORJ, 
					ser.AkunNOUGD{$_account_suffix} AS AkunNOUGD, 
					ser.AkunNoRI{$_account_suffix} as AkunNOOnCall,
					ROUND(SUM((JmlPemakaian * (HargaKomponen * ((100 - Disc) / 100))) + Hext), 0) AS Harga,
					ROUND(SUM((JmlPemakaian * (HargaOrig * ((100 - Disc) / 100))) + Hext), 0) AS HargaOrig, GroupVerifikator as Tipe
				")
				->from(" Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0) ver ")
				->join("{$_ci->service_group_model->table} ser", "ver.GroupJasaID = ser.GroupJasaID", 'INNER' )
				->where(['Kelompok' => 'RINCIAN BIAYA', 'ver.KelompokPostingan' => 'GROUP JASA'])
				->group_by(["SectionName", "ser.AkunNoRI{$_account_suffix}", "ser.AkunNORJ{$_account_suffix}", "ser.AkunNOUGD{$_account_suffix}", "GroupVerifikator"])
				->get_compiled_select();
		
		$_union_service_group = $_ci->db->select("
					union_ser.AkunNoRI, union_ser.AkunNORJ, union_ser.AkunNOUGD, union_ser.AkunNOOnCall, 
					SUM(union_ser.Harga) as Harga, SUM(HargaOrig) as HargaOrig, union_ser.Tipe
				")
				->from("( {$_sub_service_group} ) AS union_ser ")
				->group_by([
					"union_ser.Tipe", "union_ser.AkunNoRI", "union_ser.AkunNORJ",
					"union_ser.AkunNOUGD", "union_ser.AkunNOOnCall" 
				])
				->get_compiled_select();
				
		if(self::$_is_split)
			 $_ci->db->where_not_in('KomponenID', ['DT60', 'DT51']);
			 
		$_union_cost_component = $_ci->db->select("
					cos.AkunNoRI{$_account_suffix} AS AkunNoRI, 
					cos.AkunNORJ{$_account_suffix} AS AkunNORJ, 
					cos.AkunNOUGD{$_account_suffix} AS AkunNOUGD, 
					cos.AkunNoRI{$_account_suffix} AS AkunNOOnCall,
					ROUND(SUM((JmlPemakaian * (HargaKomponen * ((100 - Disc) / 100))) + Hext), 0) AS Harga,
					ROUND(SUM((JmlPemakaian * (HargaOrig * ((100 - Disc) / 100))) + Hext), 0) AS HargaOrig, 
					GroupVerifikator as Tipe 
				")
				->from(" Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0) ver ")
				->join("{$_ci->service_component_model->table} cos", "ver.KomponenID = cos.KomponenBiayaID", 'INNER' )
				->where(['Kelompok' => 'RINCIAN BIAYA', 'ver.KelompokPostingan' => 'KOMPONEN'])
				->group_by(["cos.AkunNoRI{$_account_suffix}", "cos.AkunNORJ{$_account_suffix}", "cos.AkunNOUGD{$_account_suffix}", "GroupVerifikator"])
				->get_compiled_select();				
		
		if( $_union_collection = $_ci->db->query(" {$_union_service_group} UNION {$_union_cost_component} ")->result() ):
			
			foreach ( $_union_collection as $row ):
				
				$CurNilaiAkumJasa = 0;
				$CurHarga = round($row->Harga, 0);
                $CurHargaOrig = round($row->HargaOrig, 0);
				$StrAkunMA = "";
				
				switch( $row->Tipe ):
					case 'RJ':
						$StrAkun = $row->AkunNORJ;
					break;
					case 'OC':
						$StrAkun = $row->AkunNOOnCall;
					break;
					case 'UGD':
						$StrAkun = $row->AkunNOUGD;
					break;
					case 'RI':
						$StrAkun = $row->AkunNoRI;
					break;
					// default:
					
					// 	if( $RawatInap ):
					// 		$StrAkun = $row->AkunNoRI;
					// 	else:
						
					// 		if( $RawatInap ):
					// 			$StrAkun = $row->AkunNoRI;
					// 		else:
							
					// 			if( substr($SectionName, 0, 3) == 'UGD' ):
					// 				$StrAkun = $row->AkunNOUGD;
					// 			elseif( $PoliKlinik = "ON CALL" ):
					// 				$StrAkun = $row->AkunNOOnCall;
					// 			else:
					// 				$StrAkun = $row->AkunNORJ;
					// 			endif;
					// 		endif;
					// 	endif;
					// break;
				endswitch;
				
				$NoBuktiJurnal = sprintf("%s#%s#", $NoInvoice, "PEND");
				
				// pendapatan obat RJ/UGD
				/*if ( in_array($StrAkun, ["4010202", "4010203", "4010204", "4010205", "4010206", "4010207", "4010208"]) && $PoliKlinik == 'UMUM' ) :
					$AkunPPN = '2010208';
					$CurNilaiPendapatan = round( round($row->Harga, 0) * 100 / 110, 0); 
					$CurNilaiPPN = round( round($row->Harga, 0) - $CurNilaiPendapatan, 0);
					$CurNilaiPPNAkum = $CurNilaiPPNAkum + $CurNilaiPPN;
				endif;*/
		
				 //jurnal pendapatan                            
				$_insert_audit_revenue = [
					"NoBuktiJurnal" => $NoBuktiJurnal,
					"Debet" => 0,
					//"Kredit" => ( in_array($StrAkun, ["4010202", "4010103", "4010204", "4010105", "4010206", "4010107", "4010108"]) && $PoliKlinik == 'UMUM' ) ? $CurNilaiPendapatan : round($row->Harga, 0),
					"Kredit" => round($row->Harga, 0),
					"Keterangan" => $Keterangan,
					"Posted" => 0,
					"AkunNo" => $StrAkun,
					"NoBukti" => $NoBukti,
				];
				// print_r($_insert_audit_revenue);exit;
				$_ci->audit_revenue_model->create( $_insert_audit_revenue );
				
				if(self::$_is_split)
			 		$_ci->db->where_not_in('KomponenID', ['DT60', 'DT51']);
			 				
				$_sub_ver_service = $_ci->db->select("No_Bukti, Nama_Jasa, JmlPemakaian, HargaKomponen AS Harga, Nomor, Disc")
					->from(" Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)")
					->group_start()
						->or_where(["Akun_No{$_account_suffix}" => $StrAkun])
						//->or_where(['Akun_No' => $StrAkun, 'Akun_No_2' => $StrAkun])
					->group_end()
					->group_by(["No_Bukti", "Nama_Jasa", "JmlPemakaian", "HargaKomponen", "Nomor", "Disc", "KomponenID"])
					->get_compiled_select();
		
				$_ver_service = $_ci->db->select("
						Nama_Jasa, SUM(ver.JmlPemakaian) AS JmlPemakaian,
					 	ROUND(SUM(ver.JmlPemakaian * ( ver.Harga * ( 100 - ver.Disc ) / 100)), 0) AS Harga
					")
					->from("( {$_sub_ver_service} ) AS ver ")
					->group_by(["ver.Nama_jasa" ])
					->get();
					
				if( $_ver_service->num_rows() > 0): 
					foreach( $_ver_service->result() as $val ):
						$CurNilaiJasa = $val->Harga;						
						// insert Audit pendapatan jasa (Komponen)
						$CurNilaiJasaDetail = 0;
						
						if(self::$_is_split)
							$_ci->db->where_not_in('KomponenID', ['DT60', 'DT51']);
	
						$_ver_service_component = $_ci->db->select("
								KomponenID,
								Komponen, 
								ROUND(SUM(JmlPemakaian * (HargaKomponen * (100 - Disc) / 100)), 0) AS HargaKomponen
							")
							->from("Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)")
							->group_start()
								->or_where(["Akun_No{$_account_suffix}" => $StrAkun])
								//->or_where(['Akun_No' => $StrAkun, 'Akun_No_2' => $StrAkun])
							->group_end()
							->where(["HargaKomponen >" => 0, "Nama_Jasa" => $val->Nama_Jasa])
							->group_by(["KomponenID", "Komponen" ])
							->get();
						
						$_insert_audit_component = [];
						if( $_ver_service_component->num_rows() > 0 ):
							foreach($_ver_service_component->result() as $com): // $com  == component
								/*if(in_array($com->KomponenID, ['DT60', 'DT51']) && $PoliKlinik == 'SPESIALIS')
								{ 
									$CurNilaiJasa = $CurNilaiJasa - round($com->HargaKomponen, 0);
									self::$_split_component[$com->KomponenID] = (float)@self::$_split_component[$com->KomponenID] + round($com->HargaKomponen, 0);
									
									// Mengurangi Nilai Pendapatan, sesuai nilai komponen yg displit.
									$row->Harga = $row->Harga - round($com->HargaKomponen, 0);									
									if ( in_array($StrAkun, ["4010202", "4010103", "4010204", "4010105", "4010206", "4010107", "4010108"]) && $PoliKlinik == 'UMUM' ) :
										$AkunPPN = '2010208';
										$CurNilaiPendapatan = round( round($row->Harga, 0) * 100 / 110, 0); 
										$CurNilaiPPN = round( round($row->Harga, 0) - $CurNilaiPendapatan, 0);
										$CurNilaiPPNAkum = $CurNilaiPPNAkum + $CurNilaiPPN;
									endif;
									
									$_update_audit_revenue = [
										"Kredit" => (in_array($StrAkun, ["4010202", "4010103", "4010204", "4010105", "4010206", "4010107", "4010108"]) && $PoliKlinik == 'UMUM' ) ? $CurNilaiPendapatan : round($row->Harga, 0),
									];
									$_ci->audit_revenue_model->update_by( $_update_audit_revenue, ["NoBuktiJurnal" => $NoBuktiJurnal, "AkunNo" => $StrAkun, "Keterangan" => $Keterangan, "NoBukti" => $NoBukti] );
									
									continue; 								
								}*/
								
								$CurNilaiJasaDetail = $CurNilaiJasaDetail + $com->HargaKomponen;
								$_insert_audit_component[] = [
									"NoBuktiJurnal" => $NoBuktiJurnal,
									"Nilai" => round($com->HargaKomponen, 0),
									"Keterangan" => $Keterangan,
									"AkunNo" => $StrAkun,
									"NoBukti" => $NoBukti,
									"JasaName" => trim($val->Nama_Jasa),
									"Qty" => round($val->JmlPemakaian, 0),
									"Komponen" => trim($com->Komponen)
								];
								//$_ci->audit_component_model->create( $_insert_audit_component );
							
							endforeach;
						endif;

						if( ($CurNilaiJasaDetail - $CurNilaiJasa) > 100):
							self::_cancel_audit( $NoBukti, $NoInvoice );
							return [
								'state' => 2, 
								'message' => sprintf(lang('message:audit_service_audit_component_not_match'), $NoInvoice, $val->Nama_Jasa)
							];
						endif;		
						
						###########################################################
						### Hide Sementara, Tunggu Info apakah akan  			###
						### menggunakan Audit Pendapatan Section & Kat Operasi 	###
						###########################################################
						
						// insert Audit Kat Operasi
						/*$CurNilaiJasaDetail = 0;
						if($PoliKlinik == 'SPESIALIS')
							$_ci->db->where_not_in('KomponenID', ['DT60', 'DT51']);
						$_sub_ver_category = $_ci->db->select("No_Bukti, KategoriOperasi, JmlPemakaian, SUM(HargaKomponen) AS Harga, Nomor, Disc")
							->from(" Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)")
							->group_start()
								->or_where(['Akun_No' => $StrAkun, 'Akun_No_2' => $StrAkun])
							->group_end()
							->where(['Nama_Jasa' => $val->Nama_Jasa])
							->group_by(['No_Bukti', 'KategoriOperasi', 'JmlPemakaian', 'Nomor', 'Disc'])
							->get_compiled_select();
				
						$_ver_category = $_ci->db->select("
								ver.KategoriOperasi, SUM( ver.JmlPemakaian ) AS JmlPemakaian,
								ROUND(SUM(ver.JmlPemakaian * ( ver.Harga * (100 - ver.Disc) / 100)), 0) AS Harga
							")
							->from("( {$_sub_ver_category} ) AS ver ")
							->group_by(["ver.KategoriOperasi" ])
							->get();
						
						$_insert_audit_category = [];	
						if( $_ver_category->num_rows() > 0 ):
							foreach( $_ver_category->result() as $cat ): // $cat  == category surgery							
								$CurNilaiJasaDetail = $CurNilaiJasaDetail + $cat->Harga;
								$_insert_audit_category[] = [
									"NoBuktiJurnal" => $NoBuktiJurnal,
									"Nilai" => round($cat->Harga, 0),
									"Keterangan" => $Keterangan,
									"AkunNo" => $StrAkun,
									"NoBukti" => $NoBukti,
									"JasaName" => trim($val->Nama_Jasa),
									"Qty" => round($cat->JmlPemakaian, 0),
									"KategoriOperasi" => trim($cat->KategoriOperasi)
								];
								//$_ci->audit_category_surgery_model->create( $_insert_audit_category );
								
							endforeach;
						endif;
						
						if( ($CurNilaiJasaDetail - $CurNilaiJasa) > 100):
							self::_cancel_audit( $NoBukti, $NoInvoice );
							return [
								'state' => 2, 
								'message' => sprintf(lang('message:audit_service_audit_category_not_match'), $NoInvoice, $val->Nama_Jasa)
							];
						endif;	
						
						// insert Audit Pendapatan Section
						$CurNilaiJasaDetail = 0;
						if($PoliKlinik == 'SPESIALIS') 
							$_ci->db->where_not_in('KomponenID', ['DT60', 'DT51']);
						$_sub_ver_section = $_ci->db->select("No_Bukti, SectionName, JmlPemakaian, SUM(HargaKomponen) AS Harga, Nomor, Disc")
							->from("Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)")
							->group_start()
								->or_where(['Akun_No' => $StrAkun, 'Akun_No_2' => $StrAkun])
							->group_end()
							->where(['Nama_Jasa' => $val->Nama_Jasa])
							->group_by(['No_Bukti', 'SectionName', 'JmlPemakaian', 'Nomor', 'Disc'])
							->get_compiled_select();
				
						$_ver_section = $_ci->db->select("
								ver.SectionName, SUM( ver.JmlPemakaian ) AS JmlPemakaian,
								ROUND(SUM(ver.JmlPemakaian * ( ver.Harga * (100 - ver.Disc) / 100)), 0) AS Harga
							")
							->from("( {$_sub_ver_section} ) AS ver ")
							->group_by(["ver.SectionName" ])
							->get();
						
						$_insert_audit_section = [];
						if( $_ver_section->num_rows() > 0 ):
							foreach( $_ver_section->result() as $sec ): // $sec  == section
								$CurNilaiJasaDetail = $CurNilaiJasaDetail + $sec->Harga;
								
								$_insert_audit_section[] = [
									"NoBuktiJurnal" => $NoBuktiJurnal,
									"Nilai" => round($sec->Harga, 0),
									"Keterangan" => $Keterangan,
									"AkunNo" => $StrAkun,
									"NoBukti" => $NoBukti,
									"JasaName" => trim($val->Nama_Jasa),
									"Qty" => round($sec->JmlPemakaian, 0),
									"SectionName" => trim($sec->SectionName)
								];
								//$_ci->audit_section_model->create( $_insert_audit_section );
																
							endforeach;
						endif;

						if( ($CurNilaiJasaDetail - $CurNilaiJasa) > 100):
							self::_cancel_audit( $NoBukti, $NoInvoice );
							return [
								'state' => 2, 
								'message' => sprintf(lang('message:audit_service_audit_section_not_match'), $NoInvoice, $val->Nama_Jasa)
							];
						endif;	*/
						
						// insert Audit pendapatan jasa
						$CurNilaiAkumJasa = $CurNilaiAkumJasa + $CurNilaiJasa;
						$_insert_audit_service = [
							"NoBuktiJurnal" => $NoBuktiJurnal,
							"Nilai" => round($CurNilaiJasa, 0),
							"Keterangan" => $Keterangan,
							"AkunNo" => $StrAkun,
							"NoBukti" => $NoBukti,
							"JasaName" => $val->Nama_Jasa,
							"Qty" => round($val->JmlPemakaian, 0)
						];
						$_ci->audit_service_model->create( $_insert_audit_service );
						
						if(!empty($_insert_audit_component))
							$_ci->audit_component_model->mass_create( $_insert_audit_component );
						/*if(!empty($_insert_audit_category))
							$_ci->audit_category_surgery_model->mass_create( $_insert_audit_category );		
						if(!empty($_insert_audit_section))
							$_ci->audit_section_model->mass_create( $_insert_audit_section );*/
					endforeach; /* End Foreach Audit Pendapatan Jasa*/
				endif; /* End if( $_ver_service->num_rows() > 0) */
				
				$CurAuditHarga = round($row->Harga, 0);
				$CurNilai = $CurNilai + $CurAuditHarga;		
				
				if ( ($CurNilaiAkumJasa - $CurAuditHarga) > 100 ):
					
					self::_cancel_audit( $NoBukti, $NoInvoice );
					return [
						'state' => 2, 
						'message' => sprintf(lang('message:audit_total_audit_detail_not_match'), $NoInvoice, $StrAkun )
					];
				endif;
							
			endforeach; /* End Foreach Audit Pendapatan*/
		endif;
		
		if ( $CurNilaiPPNAkum > 0 ):
		
			$_insert_audit_revenue = [
					"NoBuktiJurnal" => $NoBuktiJurnal,
					"Debet" => 0,
					"Kredit" => $CurNilaiPPNAkum,
					"Keterangan" => $Keterangan,
					"Posted" => 0,
					"AkunNo" => $AkunPPN,
					"NoBukti" => $NoBukti,
				];
			$_ci->audit_revenue_model->create( $_insert_audit_revenue );
			
		endif;
						
		$_insert_audit_revenue = [
				"NoBuktiJurnal" => $NoBuktiJurnal,
				"Debet" => $CurNilai,
				"Kredit" => 0,
				"Keterangan" => $Keterangan,
				"Posted" => 0,
				"AkunNo" => $AkunNoPiutangPasien,
				"NoBukti" => $NoBukti,
			];			
		$_ci->audit_revenue_model->create( $_insert_audit_revenue );
		
		if(!empty(self::$_split_component)):
			$HargaKomponenSplit = 0;
			foreach(self::$_split_component as $split):
				$HargaKomponenSplit = $HargaKomponenSplit + $split->HargaKomponen;					
			endforeach;
			self::$_split_payment['specialist'] = $CurNilai;
			self::$_split_payment['apik'] = $HargaKomponenSplit;
		endif;
		
		$CurHutangInsentif = 0;
		$CurNilai = 0;
		
		if( config_item('PakeJurnalDiskonTidakLangsung') == 1 ): // Audit Pendapatan dengan diskon tidak langsung
			
			$_indirect_discount = $_ci->db->select("SUM(DiskonTdklangsung) AS Diskon")
										->where($_ci->cashier_model->index_key, $NoInvoice)
										->get( $_ci->cashier_model->table )
										->row();
										
			if( (float) @$_indirect_discount->Diskon > 0  ):
				
				$StrNoBuktiJurnal = sprintf("%s#%s#", $NoInvoice, "DiscTL");
				$StrKeterangan = sprintf("Diskon Tidak Langsung->Pasien : %s", $NamaPasien);
				
				$_insert_audit_revenue = [
					"NoBuktiJurnal" => $StrNoBuktiJurnal,
					"Debet" => (float) $_indirect_discount->Diskon,
					"Kredit" => 0,
					"Keterangan" => $StrKeterangan,
					"Posted" => 0,
					"AkunNo" => '4010501',
					"NoBukti" => $NoBukti,
				];
				$_ci->audit_revenue_model->create( $_insert_audit_revenue );
				
				$_insert_audit_revenue = [
					"NoBuktiJurnal" => $StrNoBuktiJurnal,
					"Debet" => 0,
					"Kredit" => (float) $_indirect_discount->Diskon,
					"Keterangan" => $StrKeterangan,
					"Posted" => 0,
					"AkunNo" => $AkunNoPiutangPasien,
					"NoBukti" => $NoBukti,
				];
				$_ci->audit_revenue_model->create( $_insert_audit_revenue );
				
				
			endif;
			
		endif;
		
		// Audit pendapatan Diskon
		if($PoliKlinik == 'SPESIALIS')
		{
			$_ci->db->where_not_in('c.IDDiscount', ['DSC07','DSC45']);
		}
		
		$_discount = $_ci->db->select("SUM(b.NilaiDiscount) as Diskon, c.AkunNo, c.IDDiscount, c.NamaDiscount, c.DiskonTotal")
							->from("{$_ci->cashier_model->table} a")
							->join("{$_ci->cashier_discount_model->table} b", "a.NoBukti = b.NoBukti", 'INNER')
							->join("{$_ci->discount_model->table} c", "b.IDDiscount = c.IDDiscount", 'INNER')
							->where(['c.DiskonTdkLangsung' => 0, 'a.Batal' => 0, 'a.NoBukti' => $NoInvoice])
							->group_by(['c.AkunNo', 'c.IDDiscount', 'c.NamaDiscount', 'c.DiskonTotal'])
							->get()->result();
		
		if ( !empty( $_discount )): // Audit pendapatan Diskon (debit)
			foreach( $_discount as $dis): // dis = discount
				
				if ($dis->Diskon > 0):
					
					$StrNoBuktiJurnal = sprintf("%s#%s#", $NoInvoice, "DISC");
					$StrKeterangan = sprintf("Diskon Penjualan(%s) -> Pasien : %s", $dis->NamaDiscount, $NamaPasien);
					
					$discount_value = (float) $dis->Diskon;
					if (self::$_is_split && $dis->DiskonTotal == 1) 
					{
						$discount_value = (float) self::$_split_payment['specialist'];
					}
					
					$_insert_audit_revenue = [
						"NoBuktiJurnal" => $StrNoBuktiJurnal,
						"Debet" => round($discount_value, 0),
						"Kredit" => 0,
						"Keterangan" => $StrKeterangan,
						"Posted" => 0,
						"AkunNo" => $dis->AkunNo,
						"NoBukti" => $NoBukti,
					];
					$_ci->audit_revenue_model->create( $_insert_audit_revenue );
					
					$CurNilai = $CurNilai + $discount_value;
					
				endif;
				
			endforeach;
		endif;
	
		if ( $CurNilai != 0 ): // Audit pendapatan Diskon (kredit)
		
			$StrKeterangan = sprintf("Diskon Penjualan -> Pasien : %s", $NamaPasien);
			if( self::$_split_component )
				self::$_split_payment['specialist'] = self::$_split_payment['specialist'] - $CurNilai;	
			$_insert_audit_revenue = [
				"NoBuktiJurnal" => $StrNoBuktiJurnal,
				"Debet" => 0,
				"Kredit" => (float) round($CurNilai, 0),
				"Keterangan" => $StrKeterangan,
				"Posted" => 0,
				"AkunNo" => $AkunNoPiutangPasien,
				"NoBukti" => $NoBukti,
			];
			$_ci->audit_revenue_model->create( $_insert_audit_revenue );
		
		endif;
		
		
		// Audit Pembayaran Jurnal (deposit)
		if( $CurNilaiDeposit > 0 ): 
			
			$StrNoBuktiJurnal = sprintf("%s#%s#", $NoInvoice, "PELDEP");
			$StrKeterangan = sprintf("Balance Deposit : %s", $NamaPasien);
			
			$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal debit
				"NoBuktiJurnal" => $StrNoBuktiJurnal,
				"Debet" => (float) $CurNilaiDeposit,
				"Kredit" => 0,
				"Keterangan" => $StrKeterangan,
				"AkunNo" => config_item('RekeningDeposit'),
				"NoBukti" => $NoBukti,
			];
			$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
			
			$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal kredit
				"NoBuktiJurnal" => $StrNoBuktiJurnal,
				"Debet" => 0,
				"Kredit" => (float) $CurNilaiDeposit,
				"Keterangan" => $StrKeterangan,
				"AkunNo" => $AkunNoPiutangPasien,
				"NoBukti" => $NoBukti,
			];
			$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
			
		endif;
		
		
		# Jika Metode Pembayaran Tidak Hanya Tunai dan Terdapat Komponen Obat/Vaksin Pada Transaksi Poliklinik Spesialis
		# Maka Skip Jurnal Pembayaran
		$payment_cash = (bool) $_ci->cashier_detail_model->count_all(['NoBukti' => $NoInvoice, 'IDBayar' => 4, 'NilaiBayar >' => 0]);
		$payment_bank = (bool) $_ci->cashier_detail_model->count_all(['NoBukti' => $NoInvoice, 'IDBayar' => 7, 'NilaiBayar >' => 0]);
		$payment_cooperation = (bool) $_ci->cashier_detail_model->count_all(['NoBukti' => $NoInvoice, 'IDBayar' => 5, 'NilaiBayar >' => 0]);
		
		if( $payment_cash && ($payment_bank || $payment_cooperation) ){ self::$_is_multi_payment = TRUE; }
		if(!empty(self::$_split_component) && self::$_is_multi_payment === TRUE ):
			goto skip_if_split_multi_payment;
		endif;
		if($PoliKlinik == 'SPESIALIS' && $payment_bank && !$payment_cash && !$payment_cooperation):
			self::$_is_split = TRUE;
			goto skip_if_split_multi_payment;
		endif;

		
		# Audit Pembayaran Jurnal (detail kasir)
		$_cashier_detail = $_ci->db->select("b.*, d.Akun_No, a.KodeCustomerPenjamin, a.DiskonTdkLangsung")
							->from("{$_ci->cashier_model->table} a")
							->join("{$_ci->cashier_detail_model->table} b", "a.NoBukti = b.NoBukti", 'INNER')
							->join("{$_ci->type_payment_model->table} c", "b.IDBayar = c.IDBayar", 'INNER')
							->join("Mst_Akun d", "c.Akun_Id = d.Akun_ID", 'LEFT OUTER')
							->where(['NilaiBayar !=' => 0, 'a.NoBukti' => $NoInvoice])
							->order_by('b.IDBayar', 'ASC')
							->get()->result();
		
		if( !empty($_cashier_detail)): 
			foreach( $_cashier_detail as $cad ): // cad = cashier detail
				
				$CurNilaiDiskonTdkLangsung = $cad->DiskonTdkLangsung;
				$StrNoBuktiJurnal = sprintf("%s#%s#%s", $NoInvoice, "BYR", $cad->IDBayar);
				$StrKeterangan = sprintf("Pembayaran Pasien : %s", $NamaPasien);
				
				if( $cad->NilaiBayar < 0 ):
					
					if( $cad->IDBayar == 4 ):
						
						$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal kredit
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => 0,
							"Kredit" => $cad->NilaiBayar * -1,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => config_item('AkunKelebihanDeposit'),
							"NoBukti" => $NoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
						
						$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal debit
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => $cad->NilaiBayar * -1,
							"Kredit" => 0,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => $AkunNoPiutangPasien,
							"NoBukti" => $NoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
						
					elseif( $cad->IDBayar == 12 && $cad->IDBayar != 9 ):
						
						$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal kredit
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => 0,
							"Kredit" => $cad->NilaiBayar * -1,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => $cad->Akun_No,
							"NoBukti" => $NoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
						
						$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal debit
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => $cad->NilaiBayar * -1,
							"Kredit" => 0,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => $AkunNoPiutangPasien,
							"NoBukti" => $NoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
						
					endif;
					
					goto skip_if_minus;
					
				endif;
				
				
				if( ($cad->NilaiBayar > @$CurCOPay) || $TipePasien == 9 || $TipePasien == 12 ):
				
					switch ( $cad->IDBayar ):
						
						case 12:
							
							if( $TipePasien == 9 ): // Audit Pembayaran jurnal BPJS
								
								$CurNilaiKeuntunganBPJS = $cad->NilaiBayar;
								if( $CurNilaiKeuntunganBPJS < 0 ): // Nilai Keuntungan BPJS
									
									$CurNilaiKeuntunganBPJS = $CurNilaiKeuntunganBPJS * -1;
									$_insert_audit_journal_payment = [ 
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => 0,
										"Kredit" => $CurNilaiKeuntunganBPJS,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => config_item('AkunNoKeuntunganBPJS'),
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
									$_insert_audit_journal_payment = [
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => $CurNilaiKeuntunganBPJS,
										"Kredit" => 0,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => $AkunNoPiutangPasien,
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
								elseif( $CurNilaiKeuntunganBPJS > 0 ):

									$_insert_audit_journal_payment = [ 
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => $CurNilaiKeuntunganBPJS,
										"Kredit" => 0,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => config_item('AkunNoKeuntunganBPJS'),
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
									$_insert_audit_journal_payment = [
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => 0,
										"Kredit" => $CurNilaiKeuntunganBPJS,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => $AkunNoPiutangPasien,
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
								endif;
								
							else:
							
								// Jika bukan pasien JKN
								if( !empty($cad->Akun_No) ):
									
									$_insert_audit_journal_payment = [ 
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => $cad->NilaiBayar,
										"Kredit" => 0,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => $cad->Akun_No,
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
									$_insert_audit_journal_payment = [
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => 0,
										"Kredit" => $cad->NilaiBayar,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => $AkunNoPiutangPasien,
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
								endif;
								
							endif;	
						break;
						
						case 13:
						
							if ( @$IntCustomerID === 0 || empty($IntCustomerID) ):
								self::_cancel_audit( $NoBukti, $NoInvoice );
								return [
									'state' => 2, 
									'message' => sprintf(lang('message:audit_bpjs_customer_null'), $NRM, $NoInvoice )
								];
							endif;
							
							$NoBuktiAR = sprintf("%s_%s", $NoInvoice, "BPJS");
							$KeteranganBPJS = ($CurNilaiDiskonTdkLangsung > 0)
											? sprintf("%s#%s#%s%s", $StrKeterangan, 'BPJS', 'Diskon Tdk Langsung', number_format( $CurNilaiDiskonTdkLangsung, 2, '.', ',') )
											: sprintf("%s#%s", $StrKeterangan, 'BPJS');
							
							// audit detail AR			
							$_check_audit_AR = $_ci->audit_detail_ar_model->count_all(['NoBukti' => $NoBukti, 'NoBuktiTransaksi' => $NoInvoice]);
							$_insert_audit_detail_ar = [
								'NoBukti' => $NoBukti,
								'NoBuktiTransaksi' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'Nomor' => 1,
								'CustomerID' => $IntCustomerID,
								'NilaiPiutang' => $cad->NilaiBayar,
								'AkunID' => config_item('IDAkunPiutangBPJS'),
								'Keterangan' => $KeteranganBPJS,
								'NRM' => $NRM,
								'NamaPasien' => $NamaPasien,
								'AkunLawanID' => $AkunIdLawan,
								'NoBuktiAR' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'NoReg' => $NoReg,
								'TglClosing' => $TglTransaksi,
								'CustomerIDDitagihkanKe' => $IntCustomerID,
								'NamaPenanggung' => $NamaPasien,
								'TypePiutangID' => config_item('TypePiutangBPJS')
							];
							$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
						break;
						
						case 11:
						
							if ( $_insurer = $_ci->customer_model->get_by(['Kode_Customer' => $cad->KodeCustomerPenjamin]) ):
								$IntCustomerID_LOG = $_insurer->Customer_ID;
								$IntCustomerID_Penanggung_LOG = $_insurer->Customer_Id_Penanggung_RJ;
								$IntCustomerID_Penanggung_RI_LOG = $_insurer->Customer_Id_Penanggung;
							endif;
							
							if ( @$IntCustomerID_LOG === 0 || empty($IntCustomerID_LOG)  ):
								self::_cancel_audit( $NoBukti, $NoInvoice );
								return [
									'state' => 2, 
									'message' => sprintf(lang('message:audit_log_customer_null'), $NRM, $No_Invoice )
								];
							endif;
							
							$NoBuktiAR = sprintf("%s#%s", $NoInvoice, "11");
							
							$KeteranganLOG = ($CurNilaiDiskonTdkLangsung > 0)
										? sprintf("%s#%s#%s%s", $StrKeterangan, 'LOG/IKS', 'Diskon Tdk Langsung', number_format( $CurNilaiDiskonTdkLangsung, 2, '.', ',') )
										: sprintf("%s#%s", $StrKeterangan, 'LOG/IKS');
							
							// audit detail AR			
							$_check_audit_AR = $_ci->audit_detail_ar_model->count_all(['NoBukti' => $NoBukti, 'NoBuktiTransaksi' => $NoInvoice]);
							$_insert_audit_detail_ar = [
								'NoBukti' => $NoBukti,
								'NoBuktiTransaksi' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'Nomor' => 1,
								'CustomerID' => $IntCustomerID_LOG,
								'NilaiPiutang' => $cad->NilaiBayar,
								'AkunID' => config_item('IDAkunPiutangIKS'),
								'Keterangan' => $KeteranganLOG,
								'NRM' => $NRM,
								'NamaPasien' => $NamaPasien,
								'AkunLawanID' => $AkunIdLawan,
								'NoBuktiAR' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'NoReg' => $NoReg,
								'TglClosing' => $TglTransaksi,
								'CustomerIDDitagihkanKe' => $IntCustomerID_LOG,
								'NamaPenanggung' => $NamaPasien,
								'TypePiutangID' => config_item('TypePiutangIKS')
							];
							$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
							
						break;
						
						case 6:
							if( $TipePasien == 1 ):
								
								if ( @$IntCustomerID == 0 || empty($IntCustomerID) ):
									self::_cancel_audit( $NoBukti, $NoInvoice );
									return [
										'state' => 2, 
										'message' => sprintf(lang('message:audit_achc_customer_null'), $NRM, $No_Invoice )
									];
								endif;
								
								$KeteranganLOG = ($CurNilaiDiskonTdkLangsung > 0)
												? sprintf("%s %s %s", $StrKeterangan, 'Diskon Tdk Langsung', number_format( $CurNilaiDiskonTdkLangsung, 2, '.', ',') )
												: $StrKeterangan;
								
								$_insert_audit_detail_ar = [
									'NoBukti' => $NoBukti,
									'NoBuktiTransaksi' => $NoInvoice,
									'Nomor' => 1,
									'CustomerID' => $IntCustomerID,
									'NilaiPiutang' => $cad->NilaiBayar,
									'AkunID' => config_item('IDAkunPiutangHC'),
									'Keterangan' => $KeteranganLOG,
									'NRM' => $NRM,
									'NamaPasien' => $NamaPasien,
									'AkunLawanID' => $AkunIdLawan,
									'NoBuktiAR' => $NoInvoice,
									'NoReg' => $NoReg,
									'TglClosing' => $TglTransaksi,
									'CustomerIDDitagihkanKe' => $IntCustomerID,
									'NamaPenanggung' => $NamaPasien,
									'TypePiutangID' => config_item('TypePiutangHC')
								];
								$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
		
							endif;
						break;
						
						case 5:								
							if ( @$IntCustomerID === 0 || empty($IntCustomerID) ):
								
								self::_cancel_audit( $NoBukti, $NoInvoice );
								return [
									'state' => 2, 
									'message' => sprintf(lang('message:audit_iks_customer_null'), $NRM, $NoInvoice )
								];
							endif;
							
							$KeteranganLOG = ($CurNilaiDiskonTdkLangsung > 0)
										? sprintf("%s %s %s", $StrKeterangan, 'Diskon Tdk Langsung', number_format( $CurNilaiDiskonTdkLangsung, 2, '.', ',') )
										: $StrKeterangan;
							
							$_insert_audit_detail_ar = [
								'NoBukti' => $NoBukti,
								'NoBuktiTransaksi' => $NoInvoice,
								'Nomor' => 1,
								'CustomerID' => $IntCustomerID,
								'NilaiPiutang' => !empty(self::$_split_payment) ? self::$_split_payment['specialist'] :  $cad->NilaiBayar,
								'Keterangan' => $KeteranganLOG,
								'NRM' => $NRM,
								'NamaPasien' => $NamaPasien,
								'AkunLawanID' => $AkunIdLawan,
								'NoBuktiAR' => $NoInvoice,
								'NoReg' => $NoReg,
								'TglClosing' => $TglTransaksi,
								'CustomerIDDitagihkanKe' => $IntCustomerID,
								'NamaPenanggung' => $NamaPasien,
							];
							
							switch ($TipePasien):
								case 1:
									$_insert_audit_detail_ar['AkunID'] = config_item('IDAkunPiutangHC');
									$_insert_audit_detail_ar['TypePiutangID'] = config_item('TypePiutangHC');
								break;
								case 2:
									$_insert_audit_detail_ar['AkunID'] = config_item('IDAkunPiutangIKS');
									$_insert_audit_detail_ar['TypePiutangID'] = config_item('TypePiutangIKS');
								break;
							endswitch;
							
							$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
							
						break;
						
						case 19:
							// Bon karyawan
							if( config_item('PiutangKaryawanMulai') == 1 && ( $NIK != "" || $KodeDokter != "" )):
								
								$StrKodeCustomer = !empty($NIK) ? $NIK : $KodeDokter;
								$StrNoBuktiAR = sprintf("%s#%s", $NoInvoice, '19');
								$StrKeterangan = sprintf("%s#%s", $StrKeterangan, $StrKodeCustomer);
								$_get_customer = $_ci->customer_model->get_by( ['Kode_Customer' => $StrKodeCustomer] );
								
								if ( empty($_get_customer->Customer_ID) ):
									self::_cancel_audit( $NoBukti, $NoInvoice );
									return [
										'state' => 2, 
										'message' => sprintf(lang('message:audit_employee_customer_null'), $StrKodeCustomer )
									];
								endif;
								
								$_insert_audit_detail_ar = [
									'NoBukti' => $NoBukti,
									'NoBuktiTransaksi' => $StrNoBuktiAR,
									'Nomor' => 1,
									'CustomerID' => $_get_customer->Customer_ID,
									'NilaiPiutang' => !empty(self::$_split_component) ? round(self::$_split_payment['specialist'], 0) : $cad->NilaiBayar,
									'AkunID' => !empty($NIK) ? config_item("IDAkunPiutangKaryawan_{$_db_suffix}") : config_item('IDAkunPiutangDokter_{$_db_suffix}'),
									'Keterangan' => $StrKeterangan,
									'NRM' => $NRM,
									'NamaPasien' => $NamaPasien,
									'AkunLawanID' => $AkunIdLawan,
									'NoBuktiAR' => $StrNoBuktiAR,
									'NoReg' => $NoReg,
									'TglClosing' => $TglTransaksi,
									'CustomerIDDitagihkanKe' => $_get_customer->Customer_ID,
									'NamaPenanggung' => $_get_customer->Nama_Customer,
									'TypePiutangID' => !empty($NIK) ? config_item("TypePiutangKaryawan_{$_db_suffix}") : config_item("TypePiutangDokter_{$_db_suffix}")
								];
								$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
								
							endif;
						break;
						
						case 4:
							
							# Jika Pembayaran hanya dengan Tunai dan Terdapat Komponen Obat/Vaksin Pada Transaksi Poliklinik Spesialis
							# Maka Split Jurnal Pembayaran							
							$_insert_audit_journal_payment = [ 
								"NoBuktiJurnal" => $StrNoBuktiJurnal,
								//"Debet" => $cad->NilaiBayar - $CurCoPay,
								"Debet" => !empty(self::$_split_component) ? round(self::$_split_payment['specialist'], 0) : $cad->NilaiBayar,
								"Kredit" => 0,
								"Keterangan" => $StrKeterangan,
								"AkunNo" => $cad->Akun_No,
								"NoBukti" => $NoBukti,
							];
							$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
							
							$_insert_audit_journal_payment = [
								"NoBuktiJurnal" => $StrNoBuktiJurnal,
								"Debet" => 0,
								//"Kredit" => $cad->NilaiBayar - $CurCoPay,
								"Kredit" => !empty(self::$_split_component) ? round(self::$_split_payment['specialist'], 0) : $cad->NilaiBayar,
								"Keterangan" => $StrKeterangan,
								"AkunNo" => $AkunNoPiutangPasien,
								"NoBukti" => $NoBukti,
							];
							$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
							
						break;
						
						case 2: case 9: case 20:
							if( $cad->Akun_No != "" || $DokterBon != "" ):
							
								$StrKeterangan = !empty($cad->Akun_No)
												? sprintf("%s#%s", $StrKeterangan, (!empty($NamaPegawai) && ($NamaPasien != $NamaPegawai)) ? $NamaPegawai : NULL )
												: sprintf("%s#%s", $StrKeterangan, $DokterBon );
								
								$_insert_audit_journal_payment = [ 
									"NoBuktiJurnal" => $StrNoBuktiJurnal,
									"Debet" => $cad->NilaiBayar,
									"Kredit" => 0,
									"Keterangan" => $StrKeterangan,
									"AkunNo" => !empty($cad->Akun_No) ? $cad->Akun_No : $AkunNo_BonDokter,
									"NoBukti" => $NoBukti,
								];
								$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
								
								$_insert_audit_journal_payment = [
									"NoBuktiJurnal" => $StrNoBuktiJurnal,
									"Debet" => 0,
									"Kredit" => $cad->NilaiBayar,
									"Keterangan" => $StrKeterangan,
									"AkunNo" => $AkunNoPiutangPasien,
									"NoBukti" => $NoBukti,
								];
								$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
								
							endif;
						break;
						
						case 7: case 10:
							
							if(!empty($_get_cashier)):
								$credit_card = [];
								
								$credit_card[] = (object) [
									'NilaiPembayaranKKAwal' => $_get_cashier->NilaiPembayaranKKAwal,
									'AddCharge' => $_get_cashier->AddCharge,
									'IDBank' => $_get_cashier->IDBank
								];								
								$credit_card[] = (object) [
									'NilaiPembayaranKKAwal' => $_get_cashier->NilaiPembayaranKKAwal1,
									'AddCharge' => $_get_cashier->AddCharge1,
									'IDBank' => $_get_cashier->IDBank1
								];								
								$credit_card[] = (object) [
									'NilaiPembayaranKKAwal' => $_get_cashier->NilaiPembayaranKKAwal2,
									'AddCharge' => $_get_cashier->AddCharge2,
									'IDBank' => $_get_cashier->IDBank2
								];								
								$credit_card[] = (object) [
									'NilaiPembayaranKKAwal' => $_get_cashier->NilaiPembayaranKKAwal3,
									'AddCharge' => $_get_cashier->AddCharge3,
									'IDBank' => $_get_cashier->IDBank3
								];								
								
								if(!empty($credit_card)):			
									$_is_credit_card = FALSE;							
									foreach( $credit_card as $cc ): // cc = credit card
										
										if ( (float) $cc->NilaiPembayaranKKAwal != 0 ):
											$_is_credit_card = TRUE;
											$_get_merchan_account = $_ci->db->select('a.NamaBank, b.Akun_No')
																			->from("{$_ci->merchan_model->table} a")
																			->join("Mst_Akun b", "a.Akun_ID_Tujuan = b.Akun_ID", "LEFT OUTER")
																			->where('ID', $cc->IDBank)
																			->get()->row();
											if(empty($_get_merchan_account->Akun_No)){
												self::_cancel_audit( $NoBukti, $NoInvoice );
												return [
													'state' => 2, 
													'message' => sprintf("%s dengan bank %s, Rekening COA nya belum diSetup. Silahkan Setup di Admin", $StrKeterangan, $_get_merchan_account->NamaBank )
												];
											}
											$StrNoBuktiJurnal = sprintf("%s#%s#%s#%s#", $NoInvoice, 'BYR', $cad->IDBayar, $cc->IDBank);
											
											$_insert_audit_journal_payment = [ 
												"NoBuktiJurnal" => $StrNoBuktiJurnal,
												"Debet" => $cc->NilaiPembayaranKKAwal + $cc->AddCharge,
												"Kredit" => 0,
												"Keterangan" => $StrKeterangan,
												"AkunNo" => $_get_merchan_account->Akun_No,
												"NoBukti" => $NoBukti,
											];
											$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
											
											$_insert_audit_journal_payment = [
												"NoBuktiJurnal" => $StrNoBuktiJurnal,
												"Debet" => 0,
												"Kredit" => $cc->NilaiPembayaranKKAwal,
												"Keterangan" => $StrKeterangan,
												"AkunNo" => ($PoliKlinik == 'SPESIALIS') ? '2019912' : $AkunNoPiutangPasien, // Jika Spesialis maka Gunakan Rekening Hutang Titipan
												"NoBukti" => $NoBukti,
											];
											$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
											
											if( $cc->AddCharge > 0 ):
											
												$_insert_audit_journal_payment = [
													"NoBuktiJurnal" => $StrNoBuktiJurnal,
													"Debet" => 0,
													"Kredit" => $cc->AddCharge,
													"Keterangan" => $StrKeterangan,
													"AkunNo" => config_item('akunIDAddCharge'),
													"NoBukti" => $NoBukti,
												];
												$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
												
											endif;
										endif;	
									endforeach; // End foreach cc = credit card
									
									if( $PoliKlinik == 'SPESIALIS' && $_is_credit_card ) // Jika Poliklinik Membayar dengan Kartu Kredit, maka Audit menjadi milik PT. Apik
										$_ci->audit_model->update(['PostingKeBackOffice' => 'BO_1'], $NoBukti);
									
								endif;
							endif; // End if !empty($_get_cashier)
						break;
						
					endswitch; // End switch IDBayar
				
				endif; // End if( ($cad->NilaiBayar > $CurCOPay) || $TipePasien == 9 || $TipePasien == 12 )
				
				
				skip_if_minus: // goto label
				
			endforeach; // End loop Audit Pembayaran Jurnal (detail kasir)
		endif; // End if !empty($_cashier_detail)
		
		skip_if_split_multi_payment:
		
		$activities_description = sprintf( "Input VERIF NON PIUTANG - PROSES SAVE DATA. # %s - %s", $NoInvoice, $NamaPasien );
		insert_user_activity( $activities_description, 'VERIFIKATOR', self::$user_auth->Nama_Asli );
		
		
		$_trans_date = self::$_trans_date;
		if( $StrRJ == 'RI' || $StrRJ == 'ODC' ): // Jika Rawat inap, hitung honor dokter
		
			$_db_select = "
				NoReg, 
				IDDokter,
				Kelas,
				Komponen,
				Keterangan,
				JenisKerjasama,
				Tanggal,
				NoInvoice,
				SUM(Discount) AS Discount,
				Tarif, 
				SUM(jumlah) AS Jumlah,
				SUM(nilaiAlat) AS NilaiAlat,
				GroupJasaID,
				JasaName
			";
			$_get_honor = $_ci->db->select( $_db_select )
								->from("HOnor_RawatInap_Periode_UMUM('{$_trans_date}', '{$_trans_date}')")
								->where(["IDDokter NOT IN ('XX', 'xx', 'RSU', 'RSIA')" => NULL, 'NoInvoice' => $NoInvoice])
								->group_by([
									'Noreg', 
									'IDDokter',
									'Kelas',
									'Komponen',
									'Keterangan',
									'JenisKerjasama',
									'Tanggal',
									'NoInvoice',
									'Tarif',
									'GroupJasaID',
									'JasaName'
								])->get()->result();
			
			if( $_get_honor ):
				foreach( $_get_honor as $hon ): // hon = honor
					
					$StrKeteranganMixed = sprintf("%s%s%s", $hon->Keterangan, $NoInvoice, $hon->Tarif);
					$StrJasaName = sprintf("%s#%s", $hon->JasaName, $hon->Komponen);
					
					$_insert_audit_honor = [
						"JenisKerjasama" => $TipePasien,
						"TglTransaksi" => self::$_trans_date,
						"JasaName" => $StrJasaName,
						"Kelas" => $hon->Kelas,
						"Keterangan" => $hon->Keterangan,
						"Qty" => $hon->jumlah,
						"Tarif" => $hon->Tarif,
						"Diskon" => $hon->Discount,
						"KeteranganMixed" => $StrKeteranganMixed,
						"NoInvoice" => $NoInvoice,
						"TglClosing" => self::$_trans_date,
						"NoBukti" => $NoBukti,
						"DokterID" => $hon->IDDokter,
						"NoReg" => $hon->NoReg,
						"JasaID" => $hon->Komponen,
						"NRM" => $NRM,
						"NilaiALat" => $hon->NilaiAlat,
						"Ket" => 'UMUM',
						"GroupJasaID" => $hon->GroupJasaID,
						"Potongan" => 0,
						"KeteranganPotongan" => '-'
					];
					
					if( ! $_ci->audit_honor_model->create( $_insert_audit_honor ) ):
	
						self::_cancel_audit( $NoBukti, $NoInvoice );
						return [
							'state' => 2, 
							'message' => lang('message:error_refresh_data')
						];					
					endif;
					
				endforeach; // end foreach honor
			endif; // endif ( $_get_honor )
		endif; // end if ( $StrRJ == 'RI' || $StrRJ == 'ODC' )
		
		if(config_item('VerifikatorHitungHonor') == 1)
			self::_audit_honor($NoBukti, $NoInvoice);
		
		if(empty(self::$_split_component) && !self::$_is_split)
			$_ci->cashier_model->update(['Audit' => 1], $NoInvoice);
		
		return [
			'evidence_number' => $NoBukti,
			'state' => 1, 
			'message' => lang('message:revenue_recognition_successfully')
		];
	}
	
	/*
		@params	
		(Array) $arguments :
		
			(tipe) 		key 			=> value 
		{  
			(date)		'TglTransaksi' 	=> Tanggal transaksi pada pembayaran kasir,
			(string) 	'NoInvoice' 	=> No Invoice pada pembayaran kasir,
			(string) 	'NRM' 			=> Nomor medical record pasien,
			(string) 	'NoReg' 		=> Nomor Registrasi pasien,
			(string) 	'PoliKlinik' 	=> Jenis poiliklinik pada section,
			(string) 	'NamaPasien' 	=> Nama pasien,
			(string) 	'TipeTransaksi' => Jenis Transaksi Perawatan ('RAWAT INAP', 'RAWAT JALAN'),
			(boolean) 	'RawatInap' 	=> Status data rawat inap atau tidak (TRUE, FALSE),
			(string) 	'SectionName' 	=> Nama section, untuk rawat inap nilainya '' ,
			(booleand) 	'PasienAsuransi'=> Status data pasien asuransi (TRUE, FALSE),
			(int) 		'TipePasien' 	=> Tipe pasien (jika null, standar rawat jalan 1, standar rawat inap 3),
			(string) 	'CustomerID' 	=> Kode Perusahaan(Kode_Customer) BPJS IKS,
			(string) 	'Keterangan' 	=> Keterangan berisi nama pasien,
			(float) 	'CurCoPay' 		=> Nilai co payment, untuk rawat inap nilainya 0,
			(string) 	'DokterBon' 	=> Nama_Supplier (dokter bon) ,
			(stirng) 	'NamaPegawai' 	=> Berisi NIK dan nama pegawainya (%s->%s, nik, nama pegawai),
			(string) 	'NIK' 			=> NIK pegawai,
			(string) 	'KodeDokter' 	=> DokterBonID (Kode Supplier),
		}
	*/
	private static function _audit_split_examination( $arguments, $NoBukti )
	{
		$_ci = self::$_ci;
		extract($arguments);
		
		// Account Prefix Untuk Group Jasa, Jika Polikinik Spesialis maka Gunakan Akun_2 (Rekening Ke 2)
		$_account_suffix = '';
		$_db_suffix = 'BO_1';
		/*if( $PoliKlinik == 'SPESIALIS' ){
			$_account_suffix = '';		
			$_db_suffix = 'BO_2';
		}*/
				
		$AkunNo_BonDokter = "1010303005";
		$CurNilaiPPNAkum = 0;
		$NaikKelas = (boolean) $_ci->db->from("{$_ci->cashier_model->table} a")
									->join("{$_ci->registration_model->table} b", "a.NoReg = b.NoReg", "INNER")
									->where(['NoBukti' => $NoInvoice, 'NaikKelas' => 1 ])
									->count_all_results();
		
		$CurNilaiDeposit = (float) @$_ci->db->select("SUM(NilaiDeposit) AS Total")
										->where(['Batal' => 0, 'NoReg' => $NoReg])
										->get("{$_ci->deposit_model->table}")
										->row()->Total;
										
		$CurBookingPayment = (float) @$_ci->db->select("SUM( a.Deposit + a.NilaiPembayaranCC ) AS Total")
										->from("{$_ci->booking_payment_model->table} a")
										->join("{$_ci->reservation_model->table} b", "a.NoReservasi = b.NoReservasi", "INNER")
										->join("{$_ci->registration_model->table} c", "b.NoReservasi = c.NoReservasi", "INNER")
										->where(['a.Batal' => 0, 'c.NoReg' => $NoReg])
										->get()->row()->Total;
										
		$CurOTCDrug = (float) @$_ci->db->select("SUM( a.NilaiPembayaran + a.NilaiPembayaranCC ) AS Total")
										->from("{$_ci->otc_drug_model->table} a")
										->join("{$_ci->reservation_model->table} b", "a.NoReservasi = b.NoReservasi", "INNER")
										->join("{$_ci->registration_model->table} c", "b.NoReservasi = c.NoReservasi", "INNER")
										->where(['a.Batal' => 0, 'c.NoReg' => $NoReg])
										->get()->row()->Total;
										
		$CurNilaiDeposit = $CurNilaiDeposit + $CurBookingPayment + $CurOTCDrug;
		
		switch ( $TipePasien ):
			case 1:
				$AkunNoPiutangPasien = config_item("AkunLawanPendatanHC{$_account_suffix}");
				if ( $_ci->registration_model->count_all(['NoReg' => $NoReg, 'IKSMixed' => 1]) )
				{
					$AkunNoPiutangPasien = config_item("AkunLawanPendapatanIKSMIXED");
				}
				
				$StrTipePasien = 'HC';
			break;
			case 2:
				$AkunNoPiutangPasien = config_item("AkunLawanPendatanIKS{$_account_suffix}");
				$StrTipePasien = 'IKS';
			break;
			case 3:
				$AkunNoPiutangPasien = config_item("AkunLawanPendatanUMUM{$_account_suffix}");
				$StrTipePasien = 'UMUM';			
			break;
			case 4:
				$AkunNoPiutangPasien = config_item("AkunLawanPendatanEXECUTIVE{$_account_suffix}");
				$StrTipePasien = 'EXECUTIVE';			
			break;
			case 9:
				$AkunNoPiutangPasien = config_item("AkunLawanPendapatanBPJS{$_account_suffix}");
				$StrTipePasien = 'BPJS';			
			break;
		endswitch;
		
		$AkunIdLawan = (int) @$_ci->{$_db_suffix}->where('Akun_No', $AkunNoPiutangPasien)->get('Mst_Akun')->row()->Akun_ID;
		
		if ( $_insurer = $_ci->customer_model->get_by(['Kode_Customer' => $CustomerID]) ):
			$IntCustomerID = $_insurer->Customer_ID;
			$IntCustomerID_Penanggung = $_insurer->Customer_Id_Penanggung_RJ;
			$IntCustomerID_Penanggung_RI = $_insurer->Customer_Id_Penanggung;
		endif;
		
		if ( $RawatInap ):
			$IntCustomerID = (@$IntCustomerID_Penanggung_RI != 0 || !empty($IntCustomerID_Penanggung_RI) ) ? $IntCustomerID_Penanggung_RI : $IntCustomerID;
		else:
			$IntCustomerID = (@$IntCustomerID_Penanggung != 0 || !empty($IntCustomerID_Penanggung)) ? $IntCustomerID_Penanggung : @$IntCustomerID;
		endif;

		
		$NoBukti = $NoBukti ."-SPLIT";
		
		$_insert_audit = [
			"NoBukti" => $NoBukti,
			"Tanggal" => date('Y-m-d'),
			"Jam" => date('Y-m-d H:i:s'),
			"TglTransaksi" => $TglTransaksi,
			//"JamTransaksi" => $JamTransaksi,
			"Posting" => 0,
			"NoInvoice" => $NoInvoice,
			"Kelompok" => $TipeTransaksi,
			"UserID" => self::$user_auth->User_ID,
			"NoReg" => $NoReg,
			"PostingKeBackOffice" => $_db_suffix
		];
		$_ci->audit_model->create( $_insert_audit );
		
		/*if ( $TipePasien == 9 && $NaikKelas == FALSE ):
			
			$_get_cost_rs = $_ci->db->query("SELECT * FROM GetCostRS('{$NoInvoice}', '{$NoReg}', '', '')")->row();
			$_insert_audit_cost = [
				'NoBukti' => $NoBukti,
				'Keterangan' => $_get_cost_rs->Keterangan,
				'Jumlah' => $_get_cost_rs->Jumlah,
				'NilaiTransaksi' => $_get_cost_rs->NominalTotal,
				'CostRS' => $_get_cost_rs->CostRSTotal
			];
			$_ci->audit_cost_model->create( $_insert_audit_cost );
			
			$_get_coefficient = $_ci->db->query("
										SELECT JasaName, Komponen, Jumlah, KelompokRemun, 
											Bobot, Koefesien, IDDokter, JmlPetugas 
										from dbo.Honor_rawatInap_Periode_Umum_PerNoreg_Header ('{$NoReg}') 
									")->row();
									
			$_insert_audit_coefficient = [
				'NoBukti' => $NoBukti,
				'JasaID' => $_get_coefficient->JasaName,
				'Komponen' => $_get_coefficient->Komponen,
				'Jumlah' => $_get_coefficient->Jumlah,
				'Kelompok' => $_get_coefficient->KelompokRemun,
				'Bobot' => $_get_coefficient->Bobot,
				'Koefesien' => $_get_coefficient->Koefesien,
				'DokterID' => $_get_coefficient->IDDokter,
				'jmlPetugas' => $_get_coefficient->JmlPetugas,
			];
			$_ci->audit_coefficient_model->create( $_insert_audit_coefficient );
			
		endif;*/
		
		$CurNilai = 0;
		if( $_get_cashier = $_ci->cashier_model->get_by(['NoBukti' => $NoInvoice, 'Batal' => 0, 'Audit' => 0]) ):
			
			$CurCOPay = (float) $_get_cashier->CoPay;
			$StrRJ = !empty($_get_cashier->RJ) ? $_get_cashier->RJ : 'RJ';
			
		endif;
				
		$_cost_component = $_ci->db->select("
					cos.AkunNoRI{$_account_suffix} AS AkunNoRI, 
					cos.AkunNORJ{$_account_suffix} AS AkunNORJ, 
					cos.AkunNOUGD{$_account_suffix} AS AkunNOUGD, 
					cos.AkunNoRI{$_account_suffix} AS AkunNOOnCall,
					ROUND(SUM((JmlPemakaian * (HargaKomponen * ((100 - Disc) / 100))) + Hext), 0) AS Harga,
					ROUND(SUM((JmlPemakaian * (HargaOrig * ((100 - Disc) / 100))) + Hext), 0) AS HargaOrig, 
					GroupVerifikator as Tipe ,
					KomponenID
				")
				->from(" Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0) ver ")
				->join("{$_ci->service_component_model->table} cos", "ver.KomponenID = cos.KomponenBiayaID", 'INNER' )
				->where(['Kelompok' => 'RINCIAN BIAYA'])
				->where_in('KomponenID', ['DT60', 'DT51'])
				->group_by(["cos.AkunNoRI{$_account_suffix}", "cos.AkunNORJ{$_account_suffix}", "cos.AkunNOUGD{$_account_suffix}", "GroupVerifikator", "KomponenID"])
				->get();				
		
		if( $_cost_component->num_rows() > 0 ):
			
			foreach ( $_cost_component->result() as $row ):
				
				$CurNilaiAkumJasa = 0;
				$CurHarga = round($row->Harga, 0);
                $CurHargaOrig = round($row->HargaOrig, 0);
				$StrAkunMA = "";
				
				switch( $row->Tipe ):
					case 'RJ':
						$StrAkun = $row->AkunNORJ;
					break;
					case 'OC':
						$StrAkun = $row->AkunNOOnCall;
					break;
					case 'UGD':
						$StrAkun = $row->AkunNOUGD;
					break;
					case 'RI':
						$StrAkun = $row->AkunNoRI;
					break;
					default:
					
						if( $RawatInap ):
							$StrAkun = $row->AkunNoRI;
						else:
						
							if( $RawatInap ):
								$StrAkun = $row->AkunNoRI;
							else:
							
								if( substr($SectionName, 0, 3) == 'UGD' ):
									$StrAkun = $row->AkunNOUGD;
								elseif( $PoliKlinik = "ON CALL" ):
									$StrAkun = $row->AkunNOOnCall;
								else:
									$StrAkun = $row->AkunNORJ;
								endif;
							endif;
						endif;
					break;
				endswitch;
				
				if( $row->KomponenID == 'DT51'): // Jika Komponen Obat, maka Gunakan Akun Pendapatan Sesuai Section-nya
					$_ci->db->select('c.Akun_No')
							->from("{$_ci->registration_model->table} a")
							->join("{$_ci->section_model->table} b", "a.SectionPerawatanID = b.SectionID", 'INNER')
							->join("Mst_Akun c", 'b.PendapatanObatAkun_ID = c.Akun_ID', 'INNER');
					$StrAkun = $_ci->db->get()->row()->Akun_No;
					
				endif;
							
				$NoBuktiJurnal = sprintf("%s#%s#", $NoInvoice, "PEND-SPLIT");
				
				// pendapatan obat RJ/UGD
				/*if ( in_array($StrAkun, ["4010202", "4010103", "4010204", "4010105", "4010206", "4010107", "4010108"]) ) :
					$AkunPPN = '2010208';
					$CurNilaiPendapatan = round( round($row->Harga, 0) * 100 / 110, 0); 
					$CurNilaiPPN = round( round($row->Harga, 0) - $CurNilaiPendapatan, 0);
					$CurNilaiPPNAkum = $CurNilaiPPNAkum + $CurNilaiPPN;
				endif;*/
		
				 //jurnal pendapatan                            
				$_insert_audit_revenue = [
					"NoBuktiJurnal" => $NoBuktiJurnal,
					"Debet" => 0,
					//"Kredit" => in_array($StrAkun, ["4010202", "4010103", "4010204", "4010105", "4010206", "4010107", "4010108"]) ? $CurNilaiPendapatan : round($row->Harga, 0),
					"Kredit" => round($row->Harga, 0),
					"Keterangan" => $Keterangan,
					"Posted" => 0,
					"AkunNo" => $StrAkun,
					"NoBukti" => $NoBukti,
				];
				$_ci->audit_revenue_model->create( $_insert_audit_revenue );
				
				$_sub_ver_service = $_ci->db->select("No_Bukti, Nama_Jasa, JmlPemakaian, HargaKomponen, Nomor, Disc")
					->from(" Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)")
					->where(["KomponenID" => $row->KomponenID])
					->group_by(["No_Bukti", "Nama_Jasa", "JmlPemakaian", "HargaKomponen", "Nomor", "Disc"])
					->get_compiled_select();
		
				$_ver_service = $_ci->db->select("
						Nama_Jasa, SUM(ver.JmlPemakaian) AS JmlPemakaian,
					 	ROUND(SUM(ver.JmlPemakaian * ( ver.HargaKomponen * ( 100 - ver.Disc ) / 100)), 0) AS Harga
					")
					->from("( {$_sub_ver_service} ) AS ver ")
					->group_by(["ver.Nama_jasa" ])
					->get();

				$CurHargaKomponen = 0;
				if( $_ver_service->num_rows() > 0): 
					foreach( $_ver_service->result() as $val ):
						
						$CurNilaiJasa = $val->Harga;
						
						// insert Audit pendapatan jasa (Komponen)
						$CurNilaiJasaDetail = 0;
						$_ver_service_component = $_ci->db->select("
								KomponenID,
								Komponen, 
								ROUND(SUM(JmlPemakaian * (HargaKomponen * (100 - Disc) / 100)), 0) AS HargaKomponen
							")
							->from("Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)")
							->where(["HargaKomponen >" => 0, "Nama_Jasa" => $val->Nama_Jasa, "KomponenID" => $row->KomponenID])
							->group_by(["KomponenID", "Komponen"])
							->get();
						
						$_insert_audit_component = [];
						if( $_ver_service_component->num_rows() > 0 ):
							foreach($_ver_service_component->result() as $com): // $com  == component
								$CurNilaiJasaDetail = $CurNilaiJasaDetail + $com->HargaKomponen;
								$_insert_audit_component[] = [
									"NoBuktiJurnal" => $NoBuktiJurnal,
									"Nilai" => round($com->HargaKomponen, 0),
									"Keterangan" => $Keterangan,
									"AkunNo" => $StrAkun,
									"NoBukti" => $NoBukti,
									"JasaName" => trim($val->Nama_Jasa),
									"Qty" => round($val->JmlPemakaian, 0),
									"Komponen" => trim($com->Komponen)
								];
								//$_ci->audit_component_model->create( $_insert_audit_component );
							
							endforeach;
						endif;
						
						if( ($CurNilaiJasaDetail - $CurNilaiJasa) > 100):
							self::_cancel_audit( $NoBukti, $NoInvoice );
							return [
								'state' => 2, 
								'message' => sprintf(lang('message:audit_service_audit_component_not_match'), $NoInvoice, $val->Nama_Jasa)
							];
						endif;		
						
						###########################################################
						### Hide Sementara, Tunggu Info apakah akan  			###
						### menggunakan Audit Pendapatan Section & Kat Operasi 	###
						###########################################################
				
						// insert Audit Kategori Operasi
						/*$CurNilaiJasaDetail = 0;
						if($PoliKlinik == 'SPESIALIS')
							$_ci->db->where_in('KomponenID', ['DT60', 'DT51']);
						$_sub_ver_category = $_ci->db->select("No_Bukti, KategoriOperasi, JmlPemakaian, SUM(HargaKomponen) AS Harga, Nomor, Disc")
							->from(" Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)")
							->group_start()
								->or_where(['Akun_No' => $StrAkun, 'Akun_No_2' => $StrAkun])
							->group_end()
							->where(['Nama_Jasa' => $val->Nama_Jasa])
							->group_by(['No_Bukti', 'KategoriOperasi', 'JmlPemakaian', 'Nomor', 'Disc'])
							->get_compiled_select();
				
						$_ver_category = $_ci->db->select("
								ver.KategoriOperasi, SUM( ver.JmlPemakaian ) AS JmlPemakaian,
								ROUND(SUM(ver.JmlPemakaian * ( ver.Harga * (100 - ver.Disc) / 100)), 0) AS Harga
							")
							->from("( {$_sub_ver_category} ) AS ver ")
							->group_by(["ver.KategoriOperasi" ])
							->get();
						
						$_insert_audit_category = [];	
						if( $_ver_category->num_rows() > 0 ):
							foreach( $_ver_category->result() as $cat ): // $cat  == category surgery		
								$CurNilaiJasaDetail = $CurNilaiJasaDetail + $cat->Harga;
								$_insert_audit_category[] = [
									"NoBuktiJurnal" => $NoBuktiJurnal,
									"Nilai" => round($cat->Harga, 0),
									"Keterangan" => $Keterangan,
									"AkunNo" => $StrAkun,
									"NoBukti" => $NoBukti,
									"JasaName" => trim($val->Nama_Jasa),
									"Qty" => round($cat->JmlPemakaian, 0),
									"KategoriOperasi" => trim($cat->KategoriOperasi)
								];
								//$_ci->audit_category_surgery_model->create( $_insert_audit_category );
								
							endforeach;
						endif;
						
						if( ($CurNilaiJasaDetail - $CurNilaiJasa) > 100):
							self::_cancel_audit( $NoBukti, $NoInvoice );
							return [
								'state' => 2, 
								'message' => sprintf(lang('message:audit_service_audit_category_not_match'), $NoInvoice, $val->Nama_Jasa)
							];
						endif;	
						
						// insert Audit Pendapatan Section
						$CurNilaiJasaDetail = 0;
						if($PoliKlinik == 'SPESIALIS')
							$_ci->db->where_in('KomponenID', ['DT60', 'DT51']);
						$_sub_ver_section = $_ci->db->select("No_Bukti, SectionName, JmlPemakaian, SUM(HargaKomponen) AS Harga, Nomor, Disc")
							->from("Verifikator_NEW_WITH_KOMPONEN('{$NoInvoice}', 0)")
							->group_start()
								->or_where(['Akun_No' => $StrAkun, 'Akun_No_2' => $StrAkun])
							->group_end()
							->where(['Nama_Jasa' => $val->Nama_Jasa])
							->group_by(['No_Bukti', 'SectionName', 'JmlPemakaian', 'Nomor', 'Disc'])
							->get_compiled_select();
				
						$_ver_section = $_ci->db->select("
								ver.SectionName, SUM( ver.JmlPemakaian ) AS JmlPemakaian,
								ROUND(SUM(ver.JmlPemakaian * ( ver.Harga * (100 - ver.Disc) / 100)), 0) AS Harga
							")
							->from("( {$_sub_ver_section} ) AS ver ")
							->group_by(["ver.SectionName" ])
							->get();
						
						$_insert_audit_section = [];
						if( $_ver_section->num_rows() > 0 ):
							foreach( $_ver_section->result() as $sec ): // $sec  == section
								$CurNilaiJasaDetail = $CurNilaiJasaDetail + $sec->Harga;
								
								$_insert_audit_section[] = [
									"NoBuktiJurnal" => $NoBuktiJurnal,
									"Nilai" => round($sec->Harga, 0),
									"Keterangan" => $Keterangan,
									"AkunNo" => $StrAkun,
									"NoBukti" => $NoBukti,
									"JasaName" => trim($val->Nama_Jasa),
									"Qty" => round($sec->JmlPemakaian, 0),
									"SectionName" => trim($sec->SectionName)
								];
								//$_ci->audit_section_model->create( $_insert_audit_section );
																
							endforeach;
						endif;
						
						if( ($CurNilaiJasaDetail - $CurNilaiJasa) > 100):
							self::_cancel_audit( $NoBukti, $NoInvoice );
							return [
								'state' => 2, 
								'message' => sprintf(lang('message:audit_service_audit_section_not_match'), $NoInvoice, $val->Nama_Jasa)
							];
						endif;	*/
						
						// insert Audit pendapatan jasa
						$CurNilaiAkumJasa = $CurNilaiAkumJasa + $CurNilaiJasa;
						$_insert_audit_service = [
							"NoBuktiJurnal" => $NoBuktiJurnal,
							"Nilai" => round($CurNilaiJasa, 0),
							"Keterangan" => $Keterangan,
							"AkunNo" => $StrAkun,
							"NoBukti" => $NoBukti,
							"JasaName" => $val->Nama_Jasa,
							"Qty" => round($val->JmlPemakaian, 0)
						];
						// Jika Jasa Terdapat Komponent Obat atau Vaksin, maka simpan Jasa Dengan audit yg baru
						if($_insert_audit_component)
							$_ci->audit_service_model->create( $_insert_audit_service );
						
						if(!empty($_insert_audit_component))
							$_ci->audit_component_model->mass_create( $_insert_audit_component );
						/*if(!empty($_insert_audit_category))
							$_ci->audit_category_surgery_model->mass_create( $_insert_audit_category );		
						if(!empty($_insert_audit_section))
							$_ci->audit_section_model->mass_create( $_insert_audit_section );*/				
						
					endforeach; /* End Foreach Audit Pendapatan Jasa*/
					
					//$CurHargaKomponen = $CurHargaKomponen + ($row->Harga - $CurNilaiJasa);
				endif; /* End if( $_ver_service->num_rows() > 0) */
				
				$CurAuditHarga = round($row->Harga, 0);

				$CurNilai = $CurNilai + $CurAuditHarga;
				
				if ( ($CurNilaiAkumJasa - $CurAuditHarga) > 100 ):
					self::_cancel_audit( $NoBukti, $NoInvoice );
					return [
						'state' => 2, 
						'message' => sprintf(lang('message:audit_total_audit_detail_not_match'), $NoInvoice, $StrAkun )
					];
				endif;
							
			endforeach; /* End Foreach Audit Pendapatan*/
		endif;
		
		
		if ( $CurNilaiPPNAkum > 0 ):
		
			$_insert_audit_revenue = [
					"NoBuktiJurnal" => $NoBuktiJurnal,
					"Debet" => 0,
					"Kredit" => $CurNilaiPPNAkum,
					"Keterangan" => $Keterangan,
					"Posted" => 0,
					"AkunNo" => $AkunPPN,
					"NoBukti" => $NoBukti,
				];
			$_ci->audit_revenue_model->create( $_insert_audit_revenue );
			
		endif;
	
		//Input jurnal Pendapatan Debet
		if(!empty($NoBuktiJurnal))
		{
			$_insert_audit_revenue = [
					"NoBuktiJurnal" => $NoBuktiJurnal,
					"Debet" => $CurNilai,
					"Kredit" => 0,
					"Keterangan" => $Keterangan,
					"Posted" => 0,
					"AkunNo" => $AkunNoPiutangPasien,
					"NoBukti" => $NoBukti,
				];			
			$_ci->audit_revenue_model->create( $_insert_audit_revenue );
		}
		
		$CurNilai = 0;		
		// Audit pendapatan Diskon
		$_discount = $_ci->db->select("SUM(b.NilaiDiscount) as Diskon, c.AkunNo, c.IDDiscount, c.NamaDiscount")
							->from("{$_ci->cashier_model->table} a")
							->join("{$_ci->cashier_discount_model->table} b", "a.NoBukti = b.NoBukti", 'INNER')
							->join("{$_ci->discount_model->table} c", "b.IDDiscount = c.IDDiscount", 'INNER')
							->where(['c.DiskonTdkLangsung' => 0, 'a.Batal' => 0, 'a.NoBukti' => $NoInvoice])
							->where_in('c.IDDiscount', ['DSC67','DSC45', 'DSC35','DSC36','DSC37'])
							->group_by(['c.AkunNo', 'c.IDDiscount', 'c.NamaDiscount'])
							->get()->result();
		
		if ( !empty( $_discount )): // Audit pendapatan Diskon (debit)
			foreach( $_discount as $dis): // dis = discount
				
				if ($dis->Diskon > 0):
					
					$StrNoBuktiJurnal = sprintf("%s#%s#", $NoInvoice, "DISC");
					$StrKeterangan = sprintf("Diskon Penjualan(%s) -> Pasien : %s", $dis->NamaDiscount, $NamaPasien);
														
					$_insert_audit_revenue = [
						"NoBuktiJurnal" => $StrNoBuktiJurnal,
						"Debet" => (float) round(self::$_split_payment['apik'], 0),
						"Kredit" => 0,
						"Keterangan" => $StrKeterangan,
						"Posted" => 0,
						"AkunNo" => $dis->AkunNo,
						"NoBukti" => $NoBukti,
					];
					$_ci->audit_revenue_model->create( $_insert_audit_revenue );
					
					$CurNilai = $CurNilai + (float) round(self::$_split_payment['apik'], 0);
					
				endif;				
			endforeach;
		endif;
		
		if ( $CurNilai != 0 ): // Audit pendapatan Diskon (kredit)
		
			$StrKeterangan = sprintf("Diskon Penjualan -> Pasien : %s", $NamaPasien);
			$_insert_audit_revenue = [
				"NoBuktiJurnal" => $StrNoBuktiJurnal,
				"Debet" => 0,
				"Kredit" => (float) round($CurNilai, 0),
				"Keterangan" => $StrKeterangan,
				"Posted" => 0,
				"AkunNo" => $AkunNoPiutangPasien,
				"NoBukti" => $NoBukti,
			];
			$_ci->audit_revenue_model->create( $_insert_audit_revenue );
		
		endif;
		
		// Audit Pembayaran Jurnal (detail kasir)
		$_split_component_paid = 0; // Nilai Komponen Split yg sudah Dibayar
		$_cashier_detail = $_ci->db->select("b.*, d.Akun_No, a.KodeCustomerPenjamin, a.DiskonTdkLangsung")
							->from("{$_ci->cashier_model->table} a")
							->join("{$_ci->cashier_detail_model->table} b", "a.NoBukti = b.NoBukti", 'INNER')
							->join("{$_ci->type_payment_model->table} c", "b.IDBayar = c.IDBayar", 'INNER')
							->join("Mst_Akun d", "c.Akun_Id = d.Akun_ID", 'LEFT OUTER')
							->where(['NilaiBayar !=' => 0, 'a.NoBukti' => $NoInvoice])
							->order_by('b.IDBayar', 'ASC')
							->get()->result();
	
		if( !empty($_cashier_detail)):
			foreach( $_cashier_detail as $cad ): // cad = cashier detail
				
				$CurNilaiDiskonTdkLangsung = $cad->DiskonTdkLangsung;
				$StrNoBuktiJurnal = sprintf("%s#%s#%s", $NoInvoice, "BYR", $cad->IDBayar);
				$StrKeterangan = sprintf("Pembayaran Pasien : %s", $NamaPasien);
				
				if( $cad->NilaiBayar < 0 ):
					
					if( $cad->IDBayar == 4 ):
						
						$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal kredit
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => 0,
							"Kredit" => $cad->NilaiBayar * -1,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => config_item('AkunKelebihanDeposit'),
							"NoBukti" => $NoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
						
						$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal debit
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => $cad->NilaiBayar * -1,
							"Kredit" => 0,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => $AkunNoPiutangPasien,
							"NoBukti" => $NoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
						
					elseif( $cad->IDBayar == 12 && $cad->IDBayar != 9 ):
						
						$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal kredit
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => 0,
							"Kredit" => $cad->NilaiBayar * -1,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => $cad->Akun_No,
							"NoBukti" => $NoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
						
						$_insert_audit_journal_payment = [ // audit Pembayaran Jurnal debit
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => $cad->NilaiBayar * -1,
							"Kredit" => 0,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => $AkunNoPiutangPasien,
							"NoBukti" => $NoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
						
					endif;
					
					goto skip_if_minus;
					
				endif;
				
				
				if( ($cad->NilaiBayar > @$CurCOPay) || $TipePasien == 9 || $TipePasien == 12 ):
				
					switch ( $cad->IDBayar ):
						
						case 12:
							
							if( $TipePasien == 9 ): // Audit Pembayaran jurnal BPJS
								
								$CurNilaiKeuntunganBPJS = $cad->NilaiBayar;
								if( $CurNilaiKeuntunganBPJS < 0 ): // Nilai Keuntungan BPJS
									
									$CurNilaiKeuntunganBPJS = $CurNilaiKeuntunganBPJS * -1;
									$_insert_audit_journal_payment = [ 
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => 0,
										"Kredit" => $CurNilaiKeuntunganBPJS,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => config_item('AkunNoKeuntunganBPJS'),
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
									$_insert_audit_journal_payment = [
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => $CurNilaiKeuntunganBPJS,
										"Kredit" => 0,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => $AkunNoPiutangPasien,
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
								elseif( $CurNilaiKeuntunganBPJS > 0 ):

									$_insert_audit_journal_payment = [ 
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => $CurNilaiKeuntunganBPJS,
										"Kredit" => 0,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => config_item('AkunNoKeuntunganBPJS'),
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
									$_insert_audit_journal_payment = [
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => 0,
										"Kredit" => $CurNilaiKeuntunganBPJS,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => $AkunNoPiutangPasien,
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
								endif;
								
							else:
							
								// Jika bukan pasien JKN
								if( !empty($cad->Akun_No) ):
									
									$_insert_audit_journal_payment = [ 
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => $cad->NilaiBayar,
										"Kredit" => 0,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => $cad->Akun_No,
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
									$_insert_audit_journal_payment = [
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => 0,
										"Kredit" => $cad->NilaiBayar,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => $AkunNoPiutangPasien,
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									
								endif;
								
							endif;	
						break;
						
						case 13:
						
							if ( @$IntCustomerID == 0 || empty($IntCustomerID) ):
								self::_cancel_audit( $NoBukti, $NoInvoice );
								return [
									'state' => 2, 
									'message' => sprintf(lang('message:audit_bpjs_customer_null'), $NRM, $No_Invoice )
								];
							endif;
							
							$NoBuktiAR = sprintf("%s_%s", $NoInvoice, "BPJS");
							$KeteranganBPJS = ($CurNilaiDiskonTdkLangsung > 0)
											? sprintf("%s#%s#%s%s", $StrKeterangan, 'BPJS', 'Diskon Tdk Langsung', number_format( $CurNilaiDiskonTdkLangsung, 2, '.', ',') )
											: sprintf("%s#%s", $StrKeterangan, 'BPJS');
							
							// audit detail AR			
							$_check_audit_AR = $_ci->audit_detail_ar_model->count_all(['NoBukti' => $NoBukti, 'NoBuktiTransaksi' => $NoInvoice]);
							$_insert_audit_detail_ar = [
								'NoBukti' => $NoBukti,
								'NoBuktiTransaksi' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'Nomor' => 1,
								'CustomerID' => $IntCustomerID,
								'NilaiPiutang' => $cad->NilaiBayar,
								'AkunID' => config_item('IDAkunPiutangBPJS'),
								'Keterangan' => $KeteranganBPJS,
								'NRM' => $NRM,
								'NamaPasien' => $NamaPasien,
								'AkunLawanID' => $AkunIdLawan,
								'NoBuktiAR' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'NoReg' => $NoReg,
								'TglClosing' => $TglTransaksi,
								'CustomerIDDitagihkanKe' => $IntCustomerID,
								'NamaPenanggung' => $NamaPasien,
								'TypePiutangID' => config_item('TypePiutangBPJS')
							];
							$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
						break;
						
						case 11:
						
							if ( $_insurer = $_ci->customer_model->get_by(['Kode_Customer' => $cad->KodeCustomerPenjamin]) ):
								$IntCustomerID_LOG = $_insurer->Customer_ID;
								$IntCustomerID_Penanggung_LOG = $_insurer->Customer_Id_Penanggung_RJ;
								$IntCustomerID_Penanggung_RI_LOG = $_insurer->Customer_Id_Penanggung;
							endif;
							
							if ( @$IntCustomerID_LOG == 0 || empty($IntCustomerID_LOG)  ):
								self::_cancel_audit( $NoBukti, $NoInvoice );
								return [
									'state' => 2, 
									'message' => sprintf(lang('message:audit_log_customer_null'), $NRM, $No_Invoice )
								];
							endif;
							
							$NoBuktiAR = sprintf("%s#%s", $NoInvoice, "11");

							
							$KeteranganLOG = ($CurNilaiDiskonTdkLangsung > 0)
										? sprintf("%s#%s#%s%s", $StrKeterangan, 'LOG/IKS', 'Diskon Tdk Langsung', number_format( $CurNilaiDiskonTdkLangsung, 2, '.', ',') )
										: sprintf("%s#%s", $StrKeterangan, 'LOG/IKS');
							
							// audit detail AR			
							$_check_audit_AR = $_ci->audit_detail_ar_model->count_all(['NoBukti' => $NoBukti, 'NoBuktiTransaksi' => $NoInvoice]);
							$_insert_audit_detail_ar = [
								'NoBukti' => $NoBukti,
								'NoBuktiTransaksi' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'Nomor' => 1,
								'CustomerID' => $IntCustomerID_LOG,
								'NilaiPiutang' => $cad->NilaiBayar,
								'AkunID' => config_item('IDAkunPiutangIKS'),
								'Keterangan' => $KeteranganLOG,
								'NRM' => $NRM,
								'NamaPasien' => $NamaPasien,
								'AkunLawanID' => $AkunIdLawan,
								'NoBuktiAR' => ($_check_audit_AR > 0) ? $NoBuktiAR : $NoInvoice,
								'NoReg' => $NoReg,
								'TglClosing' => $TglTransaksi,
								'CustomerIDDitagihkanKe' => $IntCustomerID_LOG,
								'NamaPenanggung' => $NamaPasien,
								'TypePiutangID' => config_item('TypePiutangIKS')
							];
							$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
							
						break;
						
						case 6:
							if( $TipePasien == 1 ):
								
								if ( @$IntCustomerID == 0 || empty($IntCustomerID) ):
									self::_cancel_audit( $NoBukti, $NoInvoice );
									return [
										'state' => 2, 
										'message' => sprintf(lang('message:audit_achc_customer_null'), $NRM, $No_Invoice )
									];
								endif;
								
								$KeteranganLOG = ($CurNilaiDiskonTdkLangsung > 0)
												? sprintf("%s %s %s", $StrKeterangan, 'Diskon Tdk Langsung', number_format( $CurNilaiDiskonTdkLangsung, 2, '.', ',') )
												: $StrKeterangan;
								
								$_insert_audit_detail_ar = [
									'NoBukti' => $NoBukti,
									'NoBuktiTransaksi' => $NoInvoice,
									'Nomor' => 1,
									'CustomerID' => $IntCustomerID,
									'NilaiPiutang' => $cad->NilaiBayar,
									'AkunID' => config_item('IDAkunPiutangHC'),
									'Keterangan' => $KeteranganLOG,
									'NRM' => $NRM,
									'NamaPasien' => $NamaPasien,
									'AkunLawanID' => $AkunIdLawan,
									'NoBuktiAR' => $NoInvoice,
									'NoReg' => $NoReg,
									'TglClosing' => $TglTransaksi,
									'CustomerIDDitagihkanKe' => $IntCustomerID,
									'NamaPenanggung' => $NamaPasien,
									'TypePiutangID' => config_item('TypePiutangHC')
								];
								$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
		
							endif;
						break;
						
						case 5:								
							if ( @$IntCustomerID == 0 || empty($IntCustomerID) ):
								self::_cancel_audit( $NoBukti, $NoInvoice );
								return [
									'state' => 2, 
									'message' => sprintf(lang('message:audit_iks_customer_null'), $NRM, $No_Invoice )
								];
							endif;
							
							$KeteranganLOG = ($CurNilaiDiskonTdkLangsung > 0)
										? sprintf("%s %s %s", $StrKeterangan, 'Diskon Tdk Langsung', number_format( $CurNilaiDiskonTdkLangsung, 2, '.', ',') )
										: $StrKeterangan;
										
							$_insert_audit_detail_ar = [
								'NoBukti' => $NoBukti,
								'NoBuktiTransaksi' => $NoInvoice,
								'Nomor' => 1,
								'CustomerID' => $IntCustomerID,
								'NilaiPiutang' => !empty(self::$_split_payment) ? self::$_split_payment['apik'] :  $cad->NilaiBayar,
								'Keterangan' => $KeteranganLOG,
								'NRM' => $NRM,
								'NamaPasien' => $NamaPasien,
								'AkunLawanID' => $AkunIdLawan,
								'NoBuktiAR' => $NoInvoice,
								'NoReg' => $NoReg,
								'TglClosing' => $TglTransaksi,
								'CustomerIDDitagihkanKe' => $IntCustomerID,
								'NamaPenanggung' => $NamaPasien,
							];
							
							switch ($TipePasien):
								case 1:
									$_insert_audit_detail_ar['AkunID'] = config_item('IDAkunPiutangHC');
									$_insert_audit_detail_ar['TypePiutangID'] = config_item('TypePiutangHC');
								break;
								case 2:
									$_insert_audit_detail_ar['AkunID'] = config_item('IDAkunPiutangIKS');
									$_insert_audit_detail_ar['TypePiutangID'] = config_item('TypePiutangIKS');
								break;
							endswitch;
							
							$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
							
						break;
						
						case 19:
							// Bon karyawan
							if( config_item('PiutangKaryawanMulai') == 1 && ( $NIK != "" || $KodeDokter != "" )):
								
								$StrKodeCustomer = !empty($NIK) ? $NIK : $KodeDokter;
								$StrNoBuktiAR = sprintf("%s#%s", $NoInvoice, '19');
								$StrKeterangan = sprintf("%s#%s", $StrKeterangan, $StrKodeCustomer);
								$_get_customer = $_ci->customer_model->get_by( ['Kode_Customer' => $StrKodeCustomer] );
								
								if ( empty($_get_customer->Customer_ID) ):
									self::_cancel_audit( $NoBukti, $NoInvoice );
									return [
										'state' => 2, 
										'message' => sprintf(lang('message:audit_employee_customer_null'), $StrKodeCustomer )
									];
								endif;
								
								$_insert_audit_detail_ar = [
									'NoBukti' => $NoBukti,
									'NoBuktiTransaksi' => $StrNoBuktiAR,
									'Nomor' => 1,
									'CustomerID' => $_get_customer->Customer_ID,
									'NilaiPiutang' => round(self::$_split_payment['apik'], 0), // $cad->NilaiBayar,
									'AkunID' => !empty($NIK) ? config_item("IDAkunPiutangKaryawan_{$_db_suffix}") : config_item("IDAkunPiutangDokter_{$_db_suffix}"),
									'Keterangan' => $StrKeterangan,
									'NRM' => $NRM,
									'NamaPasien' => $NamaPasien,
									'AkunLawanID' => $AkunIdLawan,
									'NoBuktiAR' => $StrNoBuktiAR,
									'NoReg' => $NoReg,
									'TglClosing' => $TglTransaksi,
									'CustomerIDDitagihkanKe' => $_get_customer->Customer_ID,
									'NamaPenanggung' => $_get_customer->Nama_Customer,
									'TypePiutangID' => !empty($NIK) ? config_item("TypePiutangKaryawan_{$_db_suffix}") : config_item("TypePiutangDokter_{$_db_suffix}")
								];
								$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
								
							endif;
						break;
						
						case 4: // KAS
							
							# Jika Pembayaran HANYA dengan Tunai dan Terdapat Komponen Obat/Vaksin Pada Transaksi Poliklinik Spesialis
							# Maka Split Jurnal Pembayaran
							$NilaiBayar = (!empty(self::$_split_payment) && !self::$_is_multi_payment) ? self::$_split_payment['apik'] : $cad->NilaiBayar;
							$NilaiPiutangPasien	= $NilaiBayar;
							$_insert_audit_journal_payment = [ 
								"NoBuktiJurnal" => $StrNoBuktiJurnal,
								//"Debet" => $cad->NilaiBayar - $CurCoPay,
								"Debet" => $NilaiBayar,
								"Kredit" => 0,
								"Keterangan" => $StrKeterangan,
								"AkunNo" => $cad->Akun_No,
								"NoBukti" => $NoBukti,
							];
							$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
							
							if(self::$_is_multi_payment === TRUE):		
								$NilaiPiutangPasien = $NilaiBayar - self::$_split_payment['specialist'];
								if( $NilaiPiutangPasien >= 0 )
								{
									$_insert_audit_journal_payment = [
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => 0,
										//"Kredit" => $cad->NilaiBayar - $CurCoPay,
										"Kredit" => round(self::$_split_payment['specialist'], 0),
										"Keterangan" => $StrKeterangan,
										"AkunNo" => '2019912',
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									self::$_split_payment['specialist'] = 0;
								}
								
								if( $NilaiPiutangPasien < 0 )
								{
									$_insert_audit_journal_payment = [
										"NoBuktiJurnal" => $StrNoBuktiJurnal,
										"Debet" => 0,
										//"Kredit" => $cad->NilaiBayar - $CurCoPay,
										"Kredit" => $NilaiBayar,
										"Keterangan" => $StrKeterangan,
										"AkunNo" => '2019912',
										"NoBukti" => $NoBukti,
									];
									$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
									self::$_split_payment['specialist'] = self::$_split_payment['specialist'] - $NilaiBayar;
								}			
							endif;
							
							if( $NilaiPiutangPasien > 0 )
							{
								$_insert_audit_journal_payment = [
									"NoBuktiJurnal" => $StrNoBuktiJurnal,
									"Debet" => 0,
									//"Kredit" => $cad->NilaiBayar - $CurCoPay,
									"Kredit" => $NilaiPiutangPasien,
									"Keterangan" => $StrKeterangan,
									"AkunNo" => $AkunNoPiutangPasien,
									"NoBukti" => $NoBukti,
								];
								$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
								self::$_split_payment['apik'] = round(self::$_split_payment['apik']) - $NilaiPiutangPasien;
							}							
							
							/*if( ! self::$_is_multi_payment )
							{
								$_insert_audit_journal_payment = [
									"NoBuktiJurnal" => $StrNoBuktiJurnal,
									"Debet" => 0,
									//"Kredit" => $cad->NilaiBayar - $CurCoPay,
									"Kredit" => $NilaiBayar,
									"Keterangan" => $StrKeterangan,
									"AkunNo" => $AkunNoPiutangPasien,
									"NoBukti" => $NoBukti,
								];
								$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
							}*/
							
						break;
						
						case 2: case 9: case 20:
							if( $cad->Akun_No != "" || $DokterBon != "" ):
							
								$StrKeterangan = !empty($cad->Akun_No)
												? sprintf("%s#%s", $StrKeterangan, (!empty($NamaPegawai) && ($NamaPasien != $NamaPegawai)) ? $NamaPegawai : NULL )
												: sprintf("%s#%s", $StrKeterangan, $DokterBon );
								
								$_insert_audit_journal_payment = [ 
									"NoBuktiJurnal" => $StrNoBuktiJurnal,
									"Debet" => $cad->NilaiBayar,
									"Kredit" => 0,
									"Keterangan" => $StrKeterangan,
									"AkunNo" => !empty($cad->Akun_No) ? $cad->Akun_No : $AkunNo_BonDokter,
									"NoBukti" => $NoBukti,
								];
								$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
								
								$_insert_audit_journal_payment = [
									"NoBuktiJurnal" => $StrNoBuktiJurnal,
									"Debet" => 0,
									"Kredit" => $cad->NilaiBayar,
									"Keterangan" => $StrKeterangan,
									"AkunNo" => $AkunNoPiutangPasien,
									"NoBukti" => $NoBukti,
								];
								$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
								
							endif;
						break;
						
						case 7:
							if(!empty($_get_cashier)):
								$credit_card[] = (object) [
									'NilaiPembayaranKKAwal' => $_get_cashier->NilaiPembayaranKKAwal,
									'AddCharge' => $_get_cashier->AddCharge,
									'IDBank' => $_get_cashier->IDBank
								];								
								$credit_card[] = (object) [
									'NilaiPembayaranKKAwal' => $_get_cashier->NilaiPembayaranKKAwal1,
									'AddCharge' => $_get_cashier->AddCharge1,
									'IDBank' => $_get_cashier->IDBank1
								];								
								$credit_card[] = (object) [
									'NilaiPembayaranKKAwal' => $_get_cashier->NilaiPembayaranKKAwal2,
									'AddCharge' => $_get_cashier->AddCharge2,
									'IDBank' => $_get_cashier->IDBank2
								];								
								$credit_card[] = (object) [
									'NilaiPembayaranKKAwal' => $_get_cashier->NilaiPembayaranKKAwal3,
									'AddCharge' => $_get_cashier->AddCharge3,
									'IDBank' => $_get_cashier->IDBank3
								];							
								
								foreach( $credit_card as $cc ): // cc = credit card
									if ( (float) $cc->NilaiPembayaranKKAwal != 0 ):

										$_get_merchan_account = $_ci->db->select('a.NamaBank, b.Akun_No')
																			->from("{$_ci->merchan_model->table} a")
																			->join("Mst_Akun b", "a.Akun_ID_Tujuan = b.Akun_ID", "LEFT OUTER")
																			->where('ID', $cc->IDBank)
																			->get()->row();
																			
										if(empty($_get_merchan_account->Akun_No)){
											self::_cancel_audit( $NoBukti, $NoInvoice );											
											return [
												'state' => 2, 
												'message' => sprintf("%s metode bayar dengan bank %s, Rekening COA nya belum diSetup. Silahkan Setup di Admin", $StrKeterangan, $_get_merchan_account->NamaBank )
											];
										}
																		
										$StrNoBuktiJurnal = sprintf("%s#%s#%s#%s#", $NoInvoice, 'BYR', $cad->IDBayar, $cc->IDBank);
										
										$_insert_audit_journal_payment = [ 
											"NoBuktiJurnal" => $StrNoBuktiJurnal,
											"Debet" => $cc->NilaiPembayaranKKAwal + $cc->AddCharge,
											"Kredit" => 0,
											"Keterangan" => $StrKeterangan,
											"AkunNo" => $_get_merchan_account->Akun_No,
											"NoBukti" => $NoBukti,
										];
										$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
										
										if( !empty(self::$_split_payment) ):
											
											$_insert_audit_journal_payment = [
												"NoBuktiJurnal" => $StrNoBuktiJurnal,
												"Debet" => 0,
												"Kredit" => round(self::$_split_payment['apik']),
												"Keterangan" => $StrKeterangan,
												"AkunNo" => $AkunNoPiutangPasien,
												"NoBukti" => $NoBukti,
											];
											$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
											
											if( self::$_split_payment['specialist'] > 0 ):
												$_insert_audit_journal_payment = [
													"NoBuktiJurnal" => $StrNoBuktiJurnal,
													"Debet" => 0,
													"Kredit" => round(self::$_split_payment['specialist']),
													"Keterangan" => $StrKeterangan,
													"AkunNo" => '2019912', // Rekening Hutang Titipan
													"NoBukti" => $NoBukti,
												];
												$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
											endif;
											
										else :
											$_insert_audit_journal_payment = [
												"NoBuktiJurnal" => $StrNoBuktiJurnal,
												"Debet" => 0,
												"Kredit" => $cc->NilaiPembayaranKKAwal,
												"Keterangan" => $StrKeterangan,
												"AkunNo" => '2019912',
												"NoBukti" => $NoBukti,
											];
											$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
										endif;
										
										if( $cc->AddCharge > 0 ):
										
											$_insert_audit_journal_payment = [
												"NoBuktiJurnal" => $StrNoBuktiJurnal,
												"Debet" => 0,
												"Kredit" => $cc->AddCharge,
												"Keterangan" => $StrKeterangan,
												"AkunNo" => config_item('akunIDAddCharge'),
												"NoBukti" => $NoBukti,
											];
											$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
											
										endif;
									endif;
									
								endforeach; // End foreach cc = credit card
							endif; // End if !empty($_get_cashier)
						break;
						
					endswitch; // End switch IDBayar
				
				endif; // End if( ($cad->NilaiBayar > $CurCOPay) || $TipePasien == 9 || $TipePasien == 12 )
				
				
				skip_if_minus: // goto label
				
			endforeach; // End loop Audit Pembayaran Jurnal (detail kasir)
		endif; // End if !empty($_cashier_detail)
		
		$_ci->cashier_model->update(['Audit' => 1], $NoInvoice);
		
		return [
			'state' => 1, 
			'message' => lang('message:revenue_recognition_successfully')
		];
	}
		
	public static function _audit_honor( $pStrNoBukti, $pStrNoInvoice )
	{
		$_ci = self::$_ci;
		$db_suffix = (config_item('multi_bo') == 'TRUE') ? 'BO_1' : 'BO_2';
		
		$result = $_ci->db->order_by("JenisHonor, DokterID")
					->get("HOnor_Verifikator('{$pStrNoInvoice}')")
					->result();
					
		if(!empty($result)):
			$dStrDokterID = NULL;
			$dIntCOunterDokter = 1;
			
			foreach($result as $row):
			
				switch($row->JenisHonor):				
				case "RI":
					$dIntTypeHutang = config_item('TypeHutangHonorRI');
					$dIntAkunHPP = config_item('AkunIDHPPHonorRI');
					$dStrTambahanBukti = "RI";
				case "RADIOLOGI":
					$dIntTypeHutang = config_item('TypeHutangHonorBaca');
					$dIntAkunHPP = config_item('AkunIDHPPBaca');
					$dStrTambahanBukti = "RAD";
					
				case "PETUGAS RADIOLOGI":
					$dIntTypeHutang = config_item('TypeHutangPetugasRntg');
					$dIntAkunHPP = config_item('AkunIDHPPPetugasRntg');
					$dStrTambahanBukti = "P-RAD";
					
				case "RJ":
					$dIntTypeHutang = config_item('TypeHutangHonorRJ');
					$dIntAkunHPP = config_item('AkunIDHPPHonorRJ');
					$dStrTambahanBukti = "RJ";
					
				case "OK": case "HONOR OK":
					$dIntTypeHutang = config_item('TypeHutangHonorOK');
					$dIntAkunHPP = config_item('AkunIDHPPHonorOK');
					$dStrTambahanBukti = "OK";
					
				case "LAB":
					$dIntTypeHutang = config_item('TypeHutangHonorLab');
					$dIntAkunHPP = config_item('AkunIDHPPHonorLab');
					$dStrTambahanBukti = "LAB";
					
				case "JASA KIRIM":
					$dIntTypeHutang = config_item('TypeHutangHonorJasaKirim');
					$dIntAkunHPP = config_item('AkunIDHPPHonorJasaKirim');
					$dStrTambahanBukti = "KRM";

				case "INSENTIF":
					$dIntTypeHutang = config_item('TypeHutangInsentif');
					$dIntAkunHPP = config_item('AkunIDHPPInsentif');
					$dStrTambahanBukti = "INSF";
					
				case "KOMISI OBAT":
					$dIntTypeHutang = config_item('TypeHutangHonorKomisiObat');
					$dIntAkunHPP = config_item('AkunIDHPPHonorKomisiObat');
					$dStrTambahanBukti = "K-OBT";
				endswitch;				
				
				$_get_type = $_ci->{$db_suffix}
								->where('TypeHutang_ID', $dIntTypeHutang)
								->get('AP_mTypeHutang')
								->row();
				$dIntAkunHutang = !empty($_get_type) ? $_get_type->Akun_ID : 0;
				
				if( @$dStrDokterID == $row->DokterID):
					$dIntCOunterDokter = $dIntCOunterDokter + 1;
				else:
					$dIntCOunterDokter = 1;
					$dIntPersenHonor = 100;
					$_get_doctor = $_ci->db
									->select("Akun_ID, COALESCE(HonorDefault, 100) AS HonorDefault")
									->where('Kode_Supplier', $row->DokterID)
									->get('mSupplier')
									->row();
					if(!empty($_get_doctor)):
						if($_get_doctor->HonorDefault === 0)
							continue; // Lanjutkan perhitungan Honor Dokter lain, jika Dokter ini honornya 0%
						
						$dintAkunIDPajak = !empty($_get_doctor->Akun_ID) ? $_get_doctor->Akun_ID : 0;
						$dIntPersenHonor = !empty($_get_doctor->HonorDefault) ? $_get_doctor->HonorDefault : 100;
					endif;
				endif;
				
				if($row->JasaID != ''):
					$dStrNoBuktiAP = sprintf("%s#%s#AP#%s#%s#%s", $pStrNoInvoice, $row->DokterID, $dIntCOunterDokter, $row->JasaID, $dStrTambahanBukti);
				else:
					$dStrNoBuktiAP = sprintf("%s#%s#AP#%s#%s", $pStrNoInvoice, $row->DokterID, $dIntCOunterDokter, $dStrTambahanBukti);
				endif;
				
				if( $dIntPersenHonor == 100 ):
					$_insert_audit_detail_ap = [
						'NoBukti' => $pStrNoBukti,
						'NoBuktiTransaksi' => $dStrNoBuktiAP,
						'Nomor' => $dIntCOunterDokter,
						'Supplier_ID' => $row->Supplier_ID,
						'Tarif' => round($row->Tarif, 0),
						'Qty' => $row->Qty,
						'Discount' => $row->Discount,
						'Nilai_Hutang' => round($row->Nilai_Hutang, 0),
						'Akun_ID' => $dIntAkunHutang,
						'Keterangan' => $row->Keterangan,
						'NRM' => $row->NRM,
						'NamaPasien' => $row->NamaPasien,
						'AkunLawanID' => $dIntAkunHPP,
						'NoBuktiAP' => $dStrNoBuktiAP,
						'TipeHutangID' => $dIntTypeHutang,
						'NoReg' => $row->NoReg,
						'TglTindakan' => $row->TglTindakan,
						'TglClosing' => $row->TglClosing,
						'JasaID' => $row->JasaID,
						'JenisJasaName' => $row->JenisJasaName,
						'KomponenName' => $row->KomponenName,
						'RSU' => $row->RSU,
						'THT' => $row->THT,
						'JenisHonor' => $row->JenisHonor,
						'KelasID' => $row->KelasID,
						'Pajak' => $row->Pajak,
						'AkunIDPajak' => $dintAkunIDPajak
					];
					$_ci->audit_detail_ap_model->create($_insert_audit_detail_ap);
					
				else:
				
					$_insert_audit_detail_ap = [
						'NoBukti' => $pStrNoBukti,
						'NoBuktiTransaksi' => $dStrNoBuktiAP,
						'Nomor' => $dIntCOunterDokter,
						'Supplier_ID' => $row->Supplier_ID,
						'Tarif' => (round($row->Tarif, 0) * $dIntPersenHonor) / 100,
						'Qty' => $row->Qty,
						'Discount' => ($row->Discount * dIntPersenHonor) / 100,
						'Nilai_Hutang' => (round($row->Nilai_Hutang, 0) * dIntPersenHonor) / 100,
						'Akun_ID' => $dIntAkunHutang,
						'Keterangan' => $row->Keterangan,
						'NRM' => $row->NRM,
						'NamaPasien' => $row->NamaPasien,
						'AkunLawanID' => $dIntAkunHPP,
						'NoBuktiAP' => $dStrNoBuktiAP,
						'TipeHutangID' => $dIntTypeHutang,
						'NoReg' => $row->NoReg,
						'TglTindakan' => $row->TglTindakan,
						'TglClosing' => $row->TglClosing,
						'JasaID' => $row->JasaID,
						'JenisJasaName' => $row->JenisJasaName,
						'KomponenName' => $row->KomponenName,
						'RSU' => $row->RSU,
						'THT' => $row->THT,
						'JenisHonor' => $row->JenisHonor,
						'KelasID' => $row->KelasID,
						'Pajak' => $row->Pajak,
						'AkunIDPajak' => $dintAkunIDPajak
					];
					$_ci->audit_detail_ap_model->create($_insert_audit_detail_ap);				
				endif;
				
				$dStrDokterID = $row->DokterID;
					
			endforeach;
			
			return TRUE;
			
		endif;
	}
	
	public static function audit_otc_drug( $date )
	{
		$_ci = self::$_ci;
		
		$_ci->db->trans_begin();
		
			$_response = [
				'state' => 1, 
				'message' => lang('message:revenue_recognition_successfully')
			];
			
			if( $collection = self::_get_otc_drug_trans( $date ) ):
				
				foreach( $collection as $row ):
				
					$TglTransaksi = !empty($row->Tanggal) ? $row->Tanggal : date('Y-m-d');
										
					$_response = self::_audit_otc_drug( $TglTransaksi, $row->NoBukti, $row->PendapatanObatAkun_ID );
					
					/*
						State Of Progress:
						0 -> error : All data must be ROLLBACK
						1 -> success : All data must be COMMIT
						2 -> unfinish : break transaction, COMMIT ALL DATA before error, and update data status which error!
					*/					
					switch ( $_response['state'] )
					{
						case 0:
							$_ci->db->trans_rollback();
							return $_response;
						case 2: 
							break 2;
					}
					
				endforeach;
			endif;
		
		($_ci->db->trans_status() === FALSE) ? $_ci->db->trans_rollback() : $_ci->db->trans_commit(); 
		//$_ci->db->trans_rollback();
			
		return $_response;
	}
	
	private static function _get_otc_drug_trans( $date )
	{
		$_ci = self::$_ci;
		$date = DateTime::createFromFormat('Y-m-d', $date)->setTime(0,0);
		$date->modify('+1 day');
		$date->modify('+8 hour');
		
		$_ci->db->where([
				//"a.Tipe NOT IN ('RESERVASI KAMAR', 'TATA BOGA')" => NULL,
				'a.Batal' => 0,
				'a.Audit' => 0,
				'a.Jam <=' => $date->format('Y-m-d H:i:s'),
			]);
		$_ci->db->where_not_in('a.Tipe', ['RESERVASI KAMAR', 'TATA BOGA']);
		
		$db_select = "
			a.Tanggal,
			a.NoBukti,
			c.PendapatanObatAkun_ID
		";
		
		$query = $_ci->db->select( $db_select )
						->from("{$_ci->otc_drug_model->table} a")
						->join("{$_ci->bill_pharmacy_model->table} b", "a.NoBuktiFarmasi = b.NoBukti", 'INNER')
						->join("{$_ci->section_model->table} c", "b.SectionAsalID = c.SectionID", 'INNER')
						->get()
						;
						
		return $query->result();
	}
	
	/*
		@params
		(date) TglTransaksi -> Tanggal transaksi obat bebas
		(string) NoInvoice -> NoBukti transasksi obat bebas
	*/
	private static function _audit_otc_drug( $TglTransaksi, $NoInvoice, $IntAkunPendapatanObat)
	{
		$_ci = self::$_ci;
		
		$StrPiutangDokter = "1010303005";
		$StrAkunPPN = "2010205";
		
		if( $IntAkunPendapatanObat == '' || empty($IntAkunPendapatanObat) ):
		
			return [
				'state' => 0, 
				'message' => lang('message:account_otc_drug')
			];
		endif;
		
		if( $_get_account = $_ci->db->where('Akun_ID', $IntAkunPendapatanObat)->get('Mst_Akun')->row() ):
			$IntAkunLawanID = !empty($_get_account->Akun_ID) ? $_get_account->Akun_ID : '';
			$StrAkunPendapatanObat = !empty($_get_account->Akun_No) ? $_get_account->Akun_No : '';
		endif;
		
		if( $_get_account = $_ci->db->where('Akun_No', $StrAkunPPN)->get('Mst_Akun')->row() ):
			$IntAkunPPN = !empty($_get_account->Akun_ID) ? $_get_account->Akun_ID : '';
		endif;
		
		$_get_account = $_ci->db->select('b.Akun_No')
							->from("{$_ci->type_payment_model->table} a")
							->join('Mst_Akun b', 'a.Akun_ID = b.Akun_ID', 'INNER')
							->where('IDBayar', 12)
							->get()->row();
		if( $_get_account ):
			$StrAkunBebanRS = !empty($_get_account->Akun_No) ? $_get_account->Akun_No : 0;		
		endif;

		$_get_account = $_ci->db->select('b.Akun_No')
							->from("{$_ci->type_payment_model->table} a")
							->join('Mst_Akun b', 'a.Akun_ID = b.Akun_ID', 'INNER')
							->where('IDBayar', 19)
							->get()->row();
		if( $_get_account ):
			$StrAkunPiutangKaryawan = !empty($_get_account->Akun_No) ? $_get_account->Akun_No : 0;		
		endif;
		
		/*if( @$StrAkunBebanRS == '' || empty($StrAkunBebanRS) ):
			return [
				'state' => 0, 
				'message' => lang('message:account_hospital_expense')
			];
		endif;*/
		
		
		if( @$IntAkunLawanID == '' || empty($IntAkunLawanID) ):
			return [
				'state' => 0, 
				'message' => lang('message:account_otc_drug')
			];
			
		endif;
		
		
		$StrNoBukti = self::gen_audit_number();
		
		$_insert_audit = [
			'NoBukti' => $StrNoBukti,
			'Tanggal' => date('Y-m-d'),
			'Jam' => date('Y-m-d H:i:s'),
			'TglTransaksi' => $TglTransaksi,
			'Posting' => 0,
			'NoInvoice' => $NoInvoice,
			'Kelompok' => 'OBAT BEBAS',
			'UserID' => self::$user_auth->User_ID,
			'NoReg' => '-',
			"PostingKeBackOffice" => 'BO_1'
		];
		$_ci->audit_model->create( $_insert_audit );
		
		if( $_ci->otc_drug_model->count_all(['NilaiPembayaran >' => 0,'NilaiPembayaranCC >' => 0])):
			$BlnDoubel = TRUE;
		endif;
		
		$BlnAdaJurnalPiutang = FALSE;
		
		$_db_select = "
				ROUND(a.NilaiTransaksi, 0) AS Nilai,
				ROUND(a.Kredit, 0) AS Kredit,
				ROUND(a.NilaiPembayaranHC, 0) AS NilaiPembayaranHC,
				ROUND(a.NilaiPembayaranIKS, 0) AS NilaiPembayaranIKS,
				ROUND(a.NilaiPembayaranCC, 0) AS NilaiPembayaranCC,
				ROUND(a.NilaiAntarUnit, 0) AS NilaiAntarUnit,
				ROUND(a.NilaiPembayaran, 0) AS NilaiPembayaran, 
				ROUND(a.NilaiPembayaranBPJS, 0) AS NilaiPembayaranBPJS,
				ROUND(a.NilaiPembayaranBebanRS, 0) AS NilaiPembayaranBebanRS,
				ROUND(a.NilaiPembayaranBonKaryawan, 0) AS NilaiPembayaranBonKaryawan,
				ROUND(a.NilaiPembayaranBonDokter, 0) AS NilaiPembayaranBonDokter,
				a.Tanggal,
				a.KodeCustomerPenjamin,
				a.NIK,
				a.Keterangan,
				a.DokterID,
				a.AddCharge AS AddCharge,
				b.Keterangan,
				d.SectionName,
				e.Akun_No,
				f.Nama_Customer AS Nama_Supplier";
		$_get_payment_value = $_ci->db->select( $_db_select )
									->from("{$_ci->otc_drug_model->table} a")
									->join("{$_ci->bill_pharmacy_model->table} b", "a.NoBuktiFarmasi = b.NoBukti", 'INNER')
									->join("{$_ci->merchan_model->table} c", "a.IDBank = c.ID", 'LEFT OUTER')
									->join("{$_ci->section_model->table} d", "b.SectionID = d.SectionID", 'LEFT OUTER')
									->join("Mst_Akun e", "c.Akun_ID_Tujuan = e.Akun_ID", 'LEFT OUTER')
									->join("{$_ci->customer_model->table} f", "a.DokterID = f.Kode_Customer", 'LEFT OUTER')
									->where('a.NoBukti', $NoInvoice)
									->get()->row();	
		
		if( $_get_payment_value ):
			
			if( $_get_customer = $_ci->customer_model->get_by(['Kode_Customer' => $_get_payment_value->KodeCustomerPenjamin ]) ) :
				$IntCustomerID = $_get_customer->Customer_ID;
			endif;
			
			$StrNoBuktiJurnal = sprintf("%s#%s#", $NoInvoice, 'OB');
			$StrKeterangan = "Pembayaran Obat Bebas {$_get_payment_value->Keterangan}";
			
			if( (float) $_get_payment_value->NilaiAntarUnit != 0 ): 
			
				if( @$IntCustomerID == 0 || empty($IntCustomerID) ):
					self::_cancel_audit( $StrNoBukti, $NoInvoice );
					return [
						'state' => 2, 
						'message' => sprintf(lang('message:audit_otc_log_customer_null'), $NoInvoice)
					];	
				endif;
				
				$_insert_audit_detail_ar = [
					'NoBukti' => $StrNoBukti,
					'NoBuktiTransaksi' => $NoInvoice,
					'Nomor' => 1,
					'CustomerID' => $IntCustomerID,
					'NilaiPiutang' => $_get_payment_value->NilaiPembayaran,
					'AkunID' => config_item('IDAkunPiutangLOG'),
					'Keterangan' => $StrKeterangan,
					'NRM' => 'OBAT BEBAS',
					'NamaPasien' => $_get_payment_value->Keterangan,
					'AkunLawanID' => $IntAkunLawanID,
					'NoBuktiAR' => $NoInvoice,
					'NoReg' => 'OBAT BEBAS',
					'TglClosing' => $TglTransaksi,
					'CustomerIDDitagihkanKe' => $IntCustomerID,
					'NamaPenanggung' => $_get_payment_value->Keterangan,
					'TypePiutangID' => config_item('TypePiutangLOG')
				];
				$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
			
			endif;
			
			if( (float) $_get_payment_value->NilaiPembayaranBPJS != 0 ): 
			
				$BlnAdaJurnalPiutang = TRUE;
				
				$_insert_audit_detail_ar = [
					'NoBukti' => $StrNoBukti,
					'NoBuktiTransaksi' => $NoInvoice,
					'Nomor' => 1,
					'CustomerID' => 22,
					'NilaiPiutang' => $_get_payment_value->NilaiPembayaranBPJS,
					'AkunID' => config_item('IDAkunPiutangBPJS'),
					'Keterangan' => $StrKeterangan,
					'NRM' => 'OBAT BEBAS',
					'NamaPasien' => $_get_payment_value->Keterangan,
					'AkunLawanID' => $IntAkunLawanID,
					'NoBuktiAR' => $NoInvoice,
					'NoReg' => 'OBAT BEBAS',
					'TglClosing' => $TglTransaksi,
					'CustomerIDDitagihkanKe' => 22,
					'NamaPenanggung' => $_get_payment_value->Keterangan,
					'TypePiutangID' => config_item('TypePiutangBPJS')
				];
				$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
				
			endif;
			
			if( (float) $_get_payment_value->NilaiPembayaranBebanRS != 0 ):
			
				$_insert_audit_journal_payment = [
					"NoBuktiJurnal" => $StrNoBuktiJurnal,
					"Debet" => $_get_payment_value->NilaiPembayaranBebanRS,
					"Kredit" => 0,
					"Keterangan" => $StrKeterangan,
					"AkunNo" => $StrAkunBebanRS,
					"NoBukti" => $StrNoBukti,
				];
				$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
				
			endif;								
			
			if( (float) $_get_payment_value->NilaiPembayaranBonKaryawan != 0 ):
				
				$StrKeterangan = sprintf("%s#%s#%s", "Pembayaran Obat Bebas", $_get_payment_value->NIK, $_get_payment_value->Keterangan );
				
				if( config_item('PiutangKaryawanMulai') == 1 ):
					
					if( $_get_customer = $_ci->customer_model->get_by(['NIK' => $_get_payment_value->NIK]) ):
						$IntCustomerIDPegawai = $_get_customer->Customer_ID;
					endif;
					
					if( empty($IntCustomerIDPegawai) || @$IntCustomerIDPegawai == 0):
						self::_cancel_audit( $StrNoBukti, $NoInvoice);
						return [
							'state' => 2, 
							'message' => sprintf(lang('message:audit_employee_customer_null'), $_get_payment_value->NIK, 'Perusahaan')
						];
					endif;
					
					$BlnAdaJurnalPiutang = TRUE;
					
					$_insert_audit_detail_ar = [
						'NoBukti' => $StrNoBukti,
						'NoBuktiTransaksi' => $NoInvoice,
						'Nomor' => 1,
						'CustomerID' => $IntCustomerIDPegawai,
						'NilaiPiutang' => $_get_payment_value->NilaiPembayaranBonKaryawan,
						'AkunID' => config_item('IDAkunPiutangKaryawan'),
						'Keterangan' => $StrKeterangan,
						'NRM' => 'OBAT BEBAS',
						'NamaPasien' => $_get_payment_value->Keterangan,
						'AkunLawanID' => $IntAkunLawanID,
						'NoBuktiAR' => $NoInvoice,
						'NoReg' => 'OBAT BEBAS',
						'TglClosing' => $TglTransaksi,
						'CustomerIDDitagihkanKe' => $IntCustomerIDPegawai,
						'NamaPenanggung' => $StrKeterangan,
						'TypePiutangID' => config_item('TypePiutangKaryawan')
					];
					$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
				
				else:
				
					$_insert_audit_journal_payment = [
						"NoBuktiJurnal" => $StrNoBuktiJurnal,
						"Debet" => $_get_payment_value->NilaiPembayaranBonKaryawan,
						"Kredit" => 0,
						"Keterangan" => $StrKeterangan,
						"AkunNo" => $StrAkunPiutangKaryawan,
						"NoBukti" => $StrNoBukti,
					];
					$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
					
				endif;
				
			endif;
			
			if( (float) $_get_payment_value->NilaiPembayaranBonDokter != 0 ):
				
				$StrKeterangan = sprintf("%s#%s#%s", "Pembayaran Obat Bebas", $_get_payment_value->Nama_Supplier, $_get_payment_value->Keterangan );
				
				if( config_item('PiutangKaryawanMulai') == 1 ):
					
					if( $_get_customer = $_ci->customer_model->get_by(['Kode_Customer' => $_get_payment_value->DokterID]) ):
						$IntCustomerIDPegawai = $_get_customer->Customer_ID;
					endif;
					
					if( empty($IntCustomerIDPegawai) || @$IntCustomerIDPegawai == 0):
						self::_cancel_audit( $StrNoBukti, $NoInvoice);
						return [
							'state' => 2, 
							'message' => sprintf(lang('message:audit_employee_customer_null'), $_get_payment_value->DokterID, 'Dokter')
						];
					endif;
					
					$BlnAdaJurnalPiutang = TRUE;
					
					$_insert_audit_detail_ar = [
						'NoBukti' => $StrNoBukti,
						'NoBuktiTransaksi' => $NoInvoice,
						'Nomor' => 1,
						'CustomerID' => $IntCustomerIDPegawai,
						'NilaiPiutang' => $_get_payment_value->NilaiPembayaranBonDokter,
						'AkunID' => config_item('IDAkunPiutangDokter'),
						'Keterangan' => $StrKeterangan,
						'NRM' => 'OBAT BEBAS',
						'NamaPasien' => $_get_payment_value->Keterangan,
						'AkunLawanID' => $IntAkunLawanID,
						'NoBuktiAR' => $NoInvoice,
						'NoReg' => 'OBAT BEBAS',
						'TglClosing' => $TglTransaksi,
						'CustomerIDDitagihkanKe' => $IntCustomerIDPegawai,
						'NamaPenanggung' => $StrKeterangan,
						'TypePiutangID' => config_item('TypePiutangDokter')
					];
					$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
				
				else:
				
					$_insert_audit_journal_payment = [
						"NoBuktiJurnal" => $StrNoBuktiJurnal,
						"Debet" => $_get_payment_value->NilaiPembayaranBonDokter,
						"Kredit" => 0,
						"Keterangan" => $StrKeterangan,
						"AkunNo" => $StrPiutangDokter,
						"NoBukti" => $StrNoBukti,
					];
					$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
					
				endif;
				
			endif;
			
			if( (float) $_get_payment_value->Kredit != 0 ):
			
				$_insert_audit_journal_payment = [
					"NoBuktiJurnal" => $StrNoBuktiJurnal,
					"Debet" => $_get_payment_value->Kredit,
					"Kredit" => 0,
					"Keterangan" => $StrKeterangan,
					"AkunNo" => config_item('AkunBayar_OB_Kredit'),
					"NoBukti" => $StrNoBukti,
				];
				$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
				
			endif;	
			
			if( (float) $_get_payment_value->NilaiPembayaranHC != 0 ):
			
				$_insert_audit_journal_payment = [
					"NoBuktiJurnal" => $StrNoBuktiJurnal,
					"Debet" => $_get_payment_value->NilaiPembayaranHC,
					"Kredit" => 0,
					"Keterangan" => $StrKeterangan,
					"AkunNo" => config_item('AkunBayar_OB_Asuransi'),
					"NoBukti" => $StrNoBukti,
				];
				$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
				
			endif;	
			
			if( (float) $_get_payment_value->NilaiPembayaran != 0 ):
			
				if( @$BlnDoubel ):
					
					$StrNoBuktiJurnal = sprintf("%s#%s#%s", $NoInvoice, 'OB', 'TUNAI');
					
					$_insert_audit_journal_payment = [
						"NoBuktiJurnal" => $StrNoBuktiJurnal,
						"Debet" => $_get_payment_value->NilaiPembayaran,
						"Kredit" => 0,
						"Keterangan" => $StrKeterangan,
						"AkunNo" => config_item('AkunBayar_OB_Tunai'),
						"NoBukti" => $StrNoBukti,
					];
					$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
					
					$_insert_audit_journal_payment = [
						"NoBuktiJurnal" => $StrNoBuktiJurnal,
						"Debet" => 0,
						"Kredit" => $_get_payment_value->NilaiPembayaran,
						"Keterangan" => $StrKeterangan,
						"AkunNo" => $StrAkunPendapatanObat,
						"NoBukti" => $StrNoBukti,
					];
					$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
					
					$CurNilaiPPN = 0;
					if( $CurNilaiPPN > 0 ):
						// set PPN to default : 0
						$_insert_audit_journal_payment = [
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => 0,
							"Kredit" => 0,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => $StrAkunPPN,
							"NoBukti" => $StrNoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
					endif;	
					
				else:
				
					$_insert_audit_journal_payment = [
						"NoBuktiJurnal" => $StrNoBuktiJurnal,
						"Debet" => $_get_payment_value->NilaiPembayaran,
						"Kredit" => 0,
						"Keterangan" => $StrKeterangan,
						"AkunNo" => config_item('AkunBayar_OB_Tunai'),
						"NoBukti" => $StrNoBukti,
					];
					$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
				
				endif;
				
			endif;	
		
			if( (float) $_get_payment_value->NilaiPembayaranIKS != 0 ):
				
				$BlnAdaJurnalPiutang = TRUE;
				$_insert_audit_detail_ar = [
					'NoBukti' => $StrNoBukti,
					'NoBuktiTransaksi' => $NoInvoice,
					'Nomor' => 1,
					'CustomerID' => $IntCustomerID,
					'NilaiPiutang' => $_get_payment_value->NilaiPembayaranIKS,
					'AkunID' => config_item('IDAkunPiutangIKS'),
					'Keterangan' => $_get_payment_value->Keterangan,
					'NRM' => 'OBAT BEBAS',
					'NamaPasien' => $_get_payment_value->Keterangan,
					'AkunLawanID' => $IntAkunLawanID,
					'NoBuktiAR' => $NoInvoice,
					'NoReg' => 'OBAT BEBAS',
					'TglClosing' => $TglTransaksi,
					'CustomerIDDitagihkanKe' => $IntCustomerID,
					'NamaPenanggung' => $StrKeterangan,
					'TypePiutangID' => config_item('TypePiutangIKS')
				];
				$_ci->audit_detail_ar_model->create( $_insert_audit_detail_ar  );
				
			endif;	
					
			if( (float) $_get_payment_value->NilaiPembayaranCC != 0 ):
				
				$StrAkun = $_get_payment_value->Akun_No ? $_get_payment_value->Akun_No : config_item('RekeningKasBesar');
				$CurNilai = $_get_payment_value->NilaiPembayaranCC;
				$CurAddCharge = round( $CurNilai * $_get_payment_value->AddCharge / 100, 0);
				
				if( @$BlnDoubel ):
					
					$StrNoBuktiJurnal = sprintf("%s#%s#%s", $NoInvoice, 'OB', 'CC');
					$_insert_audit_journal_payment = [
						"NoBuktiJurnal" => $StrNoBuktiJurnal,
						"Debet" => $CurNilai + $CurAddCharge,
						"Kredit" => 0,
						"Keterangan" => $StrKeterangan,
						"AkunNo" => $StrAkun,
						"NoBukti" => $StrNoBukti,
					];
					$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
				
					$CurNilaiPendapatan = $CurNilai;
                    $CurNilaiPPN = 0;

					if( $CurNilaiPPN > 0 ):
						// set PPN to default : 0
						$_insert_audit_journal_payment = [
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => 0,
							"Kredit" =>  $CurNilaiPPN,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => $StrAkunPPN,
							"NoBukti" => $StrNoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
					endif;
					
					$_insert_audit_journal_payment = [
						"NoBuktiJurnal" => $StrNoBuktiJurnal,
						"Debet" => 0,
						"Kredit" =>  $CurNilaiPendapatan,
						"Keterangan" => $StrKeterangan,
						"AkunNo" => $StrAkunPendapatanObat,
						"NoBukti" => $StrNoBukti,
					];
					$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
					
					if( $CurAddCharge > 0 ):
					
						$_insert_audit_journal_payment = [
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => 0,
							"Kredit" =>  $CurAddCharge,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => config_item('akunIDAddCharge'),
							"NoBukti" => $StrNoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
					
					endif;
				else:
						
					$_insert_audit_journal_payment = [
						"NoBuktiJurnal" => $StrNoBuktiJurnal,
						"Debet" => $CurNilai + $CurAddCharge,
						"Kredit" => 0,
						"Keterangan" => $StrKeterangan,
						"AkunNo" => $StrAkun,
						"NoBukti" => $StrNoBukti,
					];
					
					$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
					
					if( $CurAddCharge > 0 ):
					
						$_insert_audit_journal_payment = [
							"NoBuktiJurnal" => $StrNoBuktiJurnal,
							"Debet" => 0,
							"Kredit" =>  $CurAddCharge,
							"Keterangan" => $StrKeterangan,
							"AkunNo" => config_item('akunIDAddCharge'),
							"NoBukti" => $StrNoBukti,
						];
						$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
					
					endif;
				endif;
			endif;	
				
			if( @$BlnDoubel == FALSE && @$BlnAdaJurnalPiutang == FALSE ):
				
				$CurNilaiPendapatan = $_get_payment_value->Nilai;
				$CurNilaiPPN = 0;
				
				$_insert_audit_journal_payment = [
					"NoBuktiJurnal" => $StrNoBuktiJurnal,
					"Debet" => 0,
					"Kredit" => $CurNilaiPendapatan,
					"Keterangan" => $StrKeterangan,
					"AkunNo" => $StrAkunPendapatanObat,
					"NoBukti" => $StrNoBukti,
				];
				$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
				
				if( $CurNilaiPPN > 0 ):
					// set PPN to default : 0
					$_insert_audit_journal_payment = [
						"NoBuktiJurnal" => $StrNoBuktiJurnal,
						"Debet" => 0,
						"Kredit" =>  $CurNilaiPPN,
						"Keterangan" => $StrKeterangan,
						"AkunNo" => $StrAkunPPN,
						"NoBukti" => $StrNoBukti,
					];
					$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
					
				endif;
			endif;
		endif;
		
		$activities_description = sprintf( "Input VERIF NON PIUTANG - PROSES SAVE DATA OBAT BEBAS. # %s", $NoInvoice );
		insert_user_activity( $activities_description, 'VERIFIKATOR', self::$user_auth->Nama_Asli );
		
		$_ci->otc_drug_model->update(['Audit' => 1], $NoInvoice);
		
		return [
			'state' => 1, 
			'message' => lang('message:revenue_recognition_successfully')
		];
	}
	
	public static function audit_outstanding( $date )
	{
		$_ci = self::$_ci;
		
		$_ci->db->trans_begin();
		
			$_response = [
				'state' => 1, 
				'message' => lang('message:revenue_recognition_successfully')
			];
			
			if( $collection = self::_get_outstanding_trans( $date ) ):
				foreach( $collection as $row ):
				
					$TglTransaksi = !empty($row->Tanggal) ? $row->Tanggal : date('Y-m-d');
					
					$_response = self::_audit_outstanding( $TglTransaksi, $row->NoBukti, $row->NamaPasien, $row->NoReg, $row );
					
					/*
						State Of Progress:
						0 -> error : All data must be ROLLBACK
						1 -> success : All data must be COMMIT
						2 -> unfinish : break transaction, COMMIT ALL DATA before error, and update data status which error!
					*/					
					switch ( $_response['state'] )
					{
						case 0:
							$_ci->db->trans_rollback();
							return $_response;
						case 2: 
							break 2;
					}
					
				endforeach;
			endif;
															
		($_ci->db->trans_status() === FALSE) ? $_ci->db->trans_rollback() : $_ci->db->trans_commit();// $_ci->db->trans_rollback();
			
		return $_response;
	}	
	
	private static function _get_outstanding_trans( $date )
	{
		$_ci = self::$_ci;
		$date = DateTime::createFromFormat('Y-m-d', $date);
		$date->modify('+1 day');
		
		$_ci->db->where([
				'a.Tanggal <' => $date->format('Y-m-d'), 
				'a.Audit' => 0, 
				'a.Batal' => 0
			]);
			
		$db_select = "
			a.Tanggal,
			a.NoBukti,
			b.NoReg,
			b.NRM,
			c.NamaPasien,
			a.*
		";
		
		$query = $_ci->db->select( $db_select )
						->from("{$_ci->outstanding_payment_model->table} a")
						->join("{$_ci->registration_model->table} b", "a.NoReg = b.NoReg", "INNER")
						->join("{$_ci->patient_model->table} c", "b.NRM = b.NRM", "INNER")
						->get();
						
		return $query->result();
	}
	
	/*
		@params
		(date) TglTransaksi -> Tanggal transaksi outstanding
		(string) NoInvoice -> NoBukti transasksi outstanding
		(string) NamaPasien -> Nama pasien transaksi outstanding 
		(string) NoReg -> No Registrasi transaksi outstanding
		(object) _oustanding -> row data transaksi outstanding
	*/
	private static function _audit_outstanding( $TglTransaksi, $NoInvoice, $NamaPasien, $NoReg, $_oustanding )
	{
		$_ci = self::$_ci;
		
		$StrNoBukti = self::_gen_audit_number();
		
		if( $_oustanding->PotongHonor == 1 ):
			$TglTransaksi = $_oustanding->TanggalHonor;
		elseif( $_oustanding->Others == 1 ):
			$TglTransaksi = $_oustanding->TanggalOthers;
		endif;
		
		$_insert_audit = [
			'NoBukti' => $StrNoBukti,
			'Tanggal' => date('Y-m-d'),
			'Jam' => date('Y-m-d H:i:s'),
			'TglTransaksi' => $TglTransaksi,
			'Posting' => 0,
			'NoInvoice' => $NoInvoice,
			'Kelompok' => 'OUTSTANDING',
			'UserID' => self::$user_auth->User_ID,
			'NoReg' => $NoReg
		];
		$_ci->audit_model->create( $_insert_audit );
		
		$_db_select = "
			a.NilaiPembayaran AS Nilai,
			a.Tunai,
			a.CC,
			a.BRITunai,
			a.Diskon,
			a.PotongHonor,
			a.Others,
			a.AddCharge, 
			b.Akun_ID_Tujuan AS Akun_ID,
			c.Akun_No,
			c.Akun_Name,
		";
		
		$_get_payment = $_ci->db->select( $_db_select )
							->from("{$_ci->outstanding_payment_model->table} a")
							->join("{$_ci->merchan_model->table} b", "a.IDBank = b.ID", "LEFT OUTER")
							->join("{$_ci->BO_1->database}.dbo.Mst_Akun c", "b.Akun_ID_Tujuan = c.Akun_ID", "LEFT OUTER")
							->where('NoBukti', $NoInvoice)
							->get()->row();
		
		if( $_get_payment ):
			
			$CurNilai = $_get_payment->Nilai;
			$CurNilaiAddCharge = $_get_payment->AddCharge;
			$StrNoBuktiJurnal = $NoInvoice;
			$StrKeterangan = sprintf("Pembayaran OutStanding %s", $NamaPasien);
			
			if( $_get_payment->Tunai == 1 || $_get_payment->Tunai == TRUE ):
				$StrAkun = config_item('RekeningTunai');
				return [
					'state' => 0, 
					'message' => lang('message:account_outstanding_cash')
				];
			elseif( $_get_payment->CC == 1 || $_get_payment->CC == TRUE ):
				$StrAkun = $_get_payment->Akun_No;
				return [
					'state' => 0, 
					'message' => lang('message:account_outstanding_cc')
				];
			elseif( $_get_payment->BRITunai == 1 || $_get_payment->BRITunai == TRUE ):
				$StrAkun = config_item('RekeningBRI');
			elseif( $_get_payment->Diskon == 1 || $_get_payment->Diskon== TRUE ):
				$StrAkun = config_item('RekeningDiskon');
				return [
					'state' => 0, 
					'message' => lang('message:account_outstanding_discount')
				];
			elseif( $_get_payment->PotongHonor == 1 || $_get_payment->CCPotongHonor== TRUE ):
				$StrAkun = config_item('AkunNoPotongHonor');
				return [
					'state' => 0, 
					'message' => lang('message:account_outstanding_honor')
				];
			elseif( $_get_payment->Others == 1 || $_get_payment->Others == TRUE ):
				$StrAkun = config_item('AkunNoOthers');
				return [
					'state' => 0, 
					'message' => lang('message:account_outstanding_other')
				];
			endif;
			
			$_insert_audit_journal_payment = [
				"NoBuktiJurnal" => $StrNoBuktiJurnal,
				"Debet" => $CurNilai + $CurNilaiAddCharge,
				"Kredit" => 0,
				"Keterangan" => $StrKeterangan,
				"AkunNo" => $StrAkun,
				"NoBukti" => $StrNoBukti,
			];
			$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
			
			$_insert_audit_journal_payment = [
				"NoBuktiJurnal" => $StrNoBuktiJurnal,
				"Debet" => 0,
				"Kredit" => $CurNilai,
				"Keterangan" => $StrKeterangan,
				"AkunNo" => config_item('RekeningPiutangPerorangan'),
				"NoBukti" => $StrNoBukti,
			];
			$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
			
			if ( $CurNilaiAddCharge > 0 ):
			
				$_insert_audit_journal_payment = [
					"NoBuktiJurnal" => $StrNoBuktiJurnal,
					"Debet" => 0,
					"Kredit" => $CurNilaiAddCharge,
					"Keterangan" => $StrKeterangan,
					"AkunNo" => config_item('akunIDAddCharge'),
					"NoBukti" => $StrNoBukti,
				];
				$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
				
			endif;	
		endif;
	
		$activities_description = sprintf( "Input VERIF NON PIUTANG - PROSES SAVE DATA OUTSTANDING. # %s - %s", $NoInvoice, $NamaPasien );
		insert_user_activity( $activities_description, 'VERIFIKATOR', self::$user_auth->Nama_Asli );
		
		$_ci->outstanding_payment_model->update(['Audit' => 1], $NoInvoice);
		
		return [
			'state' => 1, 
			'message' => lang('message:revenue_recognition_successfully')
		];
	}
	
	public static function audit_deposit( $date )
	{
		$_ci = self::$_ci;
		
		$_ci->db->trans_begin();
		
			$_response = [
				'state' => 1, 
				'message' => lang('message:revenue_recognition_successfully')
			];
			
			if( $collection = self::_get_deposit_trans( $date ) ):
				foreach( $collection as $row ):
				
					$TglTransaksi = !empty($row->Tanggal) ? $row->Tanggal : date('Y-m-d');
					
					$_response = self::_audit_deposit( $TglTransaksi, $row->NoBukti, $row->NamaPasien, $row->NoReg, $row );

					/*
						State Of Progress:
						0 -> error : All data must be ROLLBACK
						1 -> success : All data must be COMMIT
						2 -> unfinish : break transaction, COMMIT ALL DATA before error, and update data status which error!
					*/					
					switch ( $_response['state'] )
					{
						case 0:
							$_ci->db->trans_rollback();
							return $_response;
						case 2: 
							break 2;
					}
					
				endforeach;
			endif;
															
		($_ci->db->trans_status() === FALSE) ? $_ci->db->trans_rollback() : $_ci->db->trans_commit();// $_ci->db->trans_rollback();
			
		return $_response;
	}	
	
	private static function _get_deposit_trans( $date )
	{
		$_ci = self::$_ci;
		$date = DateTime::createFromFormat('Y-m-d', $date);
		$date->modify('+1 day');
		
		$_ci->db->where([
				'a.Tanggal' => $date->format('Y-m-d'), 
				'a.Audit' => 0, 
				'a.Batal' => 0,
				'b.PasienAsuransi' => 0
			]);
			
		$query = $_ci->db->from("{$_ci->deposit_model->table} a")
						->join("{$_ci->registration_model->table} b", "a.NoReg = b.NoReg", "INNER")
						->get();
					
		return $query->result();
	}
	
	/*
		@params
		(date) TglTransaksi -> Tanggal transaksi deposit
		(string) NoInvoice -> NoBukti transasksi deposit
		(string) NamaPasien -> Nama pasien transaksi deposit
		(string) NoReg -> No Registrasi transaksi deposit
		(object) _deposit -> row data transaksi deposit
	*/
	private static function _audit_deposit( $TglTransaksi, $NoInvoice, $NamaPasien, $NoReg, $_deposit )
	{
		$_ci = self::$_ci;
		
		$StrNoBukti = self::_gen_audit_number();
		
		$_insert_audit = [
			'NoBukti' => $StrNoBukti,
			'Tanggal' => date('Y-m-d'),
			'Jam' => date('Y-m-d H:i:s'),
			'TglTransaksi' => $TglTransaksi,
			'Posting' => 0,
			'NoInvoice' => $NoInvoice,
			'Kelompok' => 'DEPOSIT',
			'UserID' => self::$user_auth->User_ID,
			'NoReg' => $NoReg
		];
		$_ci->audit_model->create( $_insert_audit );
		
		$_db_select = "
			a.NilaiDeposit,
			a.Tunai,
			a.KartuKredit,
			a.BRITunai,
			a.AddCharge, 
			b.Akun_No
		";
		
		$_get_payment = $_ci->db->select( $_db_select )
							->from("{$_ci->deposit_model->table} a")
							->join("{$_ci->merchan_model->table} b", "a.IDBank = b.ID", "LEFT OUTER")
							->join("Mst_Akun c", "b.Akun_ID_Tujuan = c.Akun_ID", "LEFT OUTER")
							->where('NoBukti', $NoInvoice)
							->get()->row();
		
		if( $_get_payment ):
		
			$CurNilai = $_get_payment->NilaiDeposit;
			$CurNilaiAddCharge = $_get_payment->AddCharge;
			$StrAkun = $_get_payment->Akun_No;
		
			$IntTunai = (int) $_get_payment->Tunai;
			$IntBRI = (int) $_get_payment->BRITunai;
			$IntKartuKredit = (int) $_get_payment->KartuKredit;	
			
			$StrAkun = (@$IntTunai == 1) ? config_item('RekeningKasBesar') : $StrAkun;
			
			$_get_account = $_ci->db->from("{$type_payment_model->table} a")
									->join("Mst_Akun b", "a.Akun_ID = b.Akun_ID", "INNER")
									->where("a.IDBayar", 2)
									->get()->row();
									
			$StrAkun = (@$IntBRI == 1) ? @$_get_account->Akun_No : $StrAkun;
		
			$StrNoBuktiJurnal = sprintf("%s#%s#", $NoInvoice, "DEP");
			$StrKeterangan = sprintf("Deposit Pasien %s", $NamaPasien);
			
			$_insert_audit_journal_payment = [
				"NoBuktiJurnal" => $StrNoBuktiJurnal,
				"Debet" => $CurNilai + $CurNilaiAddCharge,
				"Kredit" => 0,
				"Keterangan" => $StrKeterangan,
				"AkunNo" => $StrAkun,
				"NoBukti" => $StrNoBukti,
			];
			$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
			
			$_insert_audit_journal_payment = [
				"NoBuktiJurnal" => $StrNoBuktiJurnal,
				"Debet" => 0,
				"Kredit" => $CurNilai,
				"Keterangan" => $StrKeterangan,
				"AkunNo" => config_item('RekeningDeposit'),
				"NoBukti" => $StrNoBukti,
			];
			$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
			
			if ( $CurNilaiAddCharge > 0 ):
			
				$_insert_audit_journal_payment = [
					"NoBuktiJurnal" => $StrNoBuktiJurnal,
					"Debet" => 0,
					"Kredit" => $CurNilaiAddCharge,
					"Keterangan" => $StrKeterangan,
					"AkunNo" => config_item('akunIDAddCharge'),
					"NoBukti" => $StrNoBukti,
				];
				$_ci->audit_journal_payment_model->create( $_insert_audit_journal_payment );
				
			endif;	
		endif;
		
		$activities_description = sprintf( "Input VERIF NON PIUTANG - PROSES SAVE DATA DEPOSIT. # %s - %s", $NoInvoice, $NamaPasien );
		insert_user_activity( $activities_description, 'VERIFIKATOR', self::$user_auth->Nama_Asli );
		
		$_ci->deposit_model->update(['Audit' => 1], $NoInvoice);
		
		return [
			'state' => 1, 
			'message' => lang('message:revenue_recognition_successfully')
		];
	}
	
	private static function _cancel_audit( $NoBukti, $NoInvoice )
	{
		$_ci = self::$_ci;
		
		$_ci->audit_model->update(['Batal' => 1], $NoBukti);
		$_ci->cashier_model->update(['Audit' => 0], $NoInvoice);
		$_ci->bill_pharmacy_model->update(['IncomeAudit' => 0], $NoInvoice);
		$_ci->deposit_model->update(['Audit' => 0], $NoInvoice);
		$_ci->outstanding_payment_model->update(['Audit' => 0], $NoInvoice);
		$_ci->otc_drug_model->update(['Audit' => 0], $NoInvoice);
		
		return;
	}
	
	public static function cancel_audit( $item, $item_split = NULL )
	{
		
		$_ci = self::$_ci;
		$_ci->BO_1 = $_ci->load->database('BO_1', TRUE);	
		
		if(config_item('multi_bo') === 1)
		$_ci->BO_2 = $_ci->load->database('BO_2', TRUE);	
		
		switch( $item->Kelompok ){
			case 'RAWAT JALAN':
				$_get_section = $_ci->registration_model->get_one( $item->NoReg );	
				break;
			case 'RAWAT INAP':
				$_get_section = $_ci->registration_model->get_one( $item->NoReg );	
				break;
			case 'OBAT BEBAS':
				$_get_section = $_ci->db->select('SectionAsalID AS SectionID')
									->from("{$_ci->otc_drug_model->table} a")
									->join("{$_ci->bill_pharmacy_model->table} b", "a.NoBuktiFarmasi = b.NoBukti", "INNER")
									->where('a.NoBukti', $item->NoInvoice)
									->get()->row();	
				break;
			/*case 'OUTSTANDING':
				$_get_section = $_ci->registration_model->get_one( $item->NoReg );	
			case 'DEPOSIT':
				$_get_section = $_ci->registration_model->get_one( $item->NoReg );	*/
		}
		
		$section = (object)['PoliKlinik' => 'UMUM'];
		if(!empty($_get_section))
			$section = $_ci->section_model->get_one( @$_get_section->SectionID );
		
		if(config_item('multi_bo') === 1):		
			if( in_array($section->PoliKlinik, ['UMUM','UGD', 'NONE'] ))
			{
				$_db_suffix = 'BO_1';
			} elseif( $section->PoliKlinik == 'SPESIALIS' ){
				$_db_suffix = 'BO_2';
			}
			
			if($item_split){
				$_db_suffix_split = strpos($item_split->NoBukti, '-SPLIT') 
								? 'BO_1' : 'BO_2';
			}
		else
			$_db_suffix = 'BO_1';
			$_db_suffix_split = 'BO_1';
		endif;
		
		$_ci->db->trans_begin();
		$_ci->BO_1->trans_begin();
		
		if(config_item('multi_bo') === 1)
		$_ci->BO_2->trans_begin();
			
			$_ci->audit_model->update(['Batal' => 1], $item->NoBukti);
			$_ci->cashier_model->update(['Audit' => 0], $item->NoInvoice);
			$_ci->bill_pharmacy_model->update(['IncomeAudit' => 0], $item->NoInvoice);
			$_ci->deposit_model->update(['Audit' => 0], $item->NoInvoice);
			$_ci->outstanding_payment_model->update(['Audit' => 0], $item->NoInvoice);
			$_ci->otc_drug_model->update(['Audit' => 0], $item->NoInvoice);
			
			$_ci->BO_1->where('No_Faktur', $item->NoInvoice)->delete('AR_trFakturDetail');
			$_ci->BO_1->where('No_Faktur', $item->NoInvoice)->delete('AR_trFaktur');
			
			$activities_description = sprintf( "CANCEL INCOME: # %s # %s # %s ", $item->NRM, $item->NoReg, $item->NoBukti );
			insert_user_activity( $activities_description, $item->NoBukti, 'INCOME' );
			
			if( $item_split )
			{
				$_ci->audit_model->update(['Batal' => 1], $item_split->NoBukti);
				$_ci->cashier_model->update(['Audit' => 0], $item_split->NoInvoice);
				$_ci->bill_pharmacy_model->update(['IncomeAudit' => 0], $item_split->NoInvoice);
				$_ci->deposit_model->update(['Audit' => 0], $item_split->NoInvoice);
				$_ci->outstanding_payment_model->update(['Audit' => 0], $item_split->NoInvoice);
				$_ci->otc_drug_model->update(['Audit' => 0], $item_split->NoInvoice);
				
				$_ci->BO_1->where('No_Faktur', $item_split->NoInvoice)->delete('AR_trFakturDetail');
				$_ci->BO_1->where('No_Faktur', $item_split->NoInvoice)->delete('AR_trFaktur');
				
				$activities_description = sprintf( "CANCEL INCOME: # %s # %s # %s ", $item_split->NRM, $item_split->NoReg, $item_split->NoBukti );
				insert_user_activity( $activities_description, $item_split->NoBukti, 'INCOME' );
			}
		
		//if( $_ci->db->trans_status() === FALSE || $_ci->BO_1->trans_status() === FALSE || $_ci->BO_2->trans_status() === FALSE ) 
		if( $_ci->db->trans_status() === FALSE || $_ci->BO_1->trans_status() === FALSE) 
		{
			$_ci->db->trans_rollback();
			$_ci->BO_1->trans_rollback();
			
			if(config_item('multi_bo') === 1)
			$_ci->BO_2->trans_rollback();
			return FALSE;
		} 
		
		/*$_ci->db->trans_rollback();
		$_ci->BO_1->trans_rollback();
		$_ci->BO_2->trans_rollback();*/
		
		$_ci->db->trans_commit();
		$_ci->BO_1->trans_commit();
		
		if(config_item('multi_bo') === 1)
		$_ci->BO_2->trans_commit();
		return TRUE;
	}
	
	public static function cancel_posting( $item, $item_split = NULL )
	{		
		$_ci = self::$_ci;
		$_ci->BO_1 = $_ci->load->database('BO_1', TRUE);	
		
		if(config_item('multi_bo') === 1)
		$_ci->BO_2 = $_ci->load->database('BO_2', TRUE);	
		
		
		$_db_suffix = $item->PostingKeBackOffice;
		
		$_closing_gl = $_ci->{$_db_suffix}->from("TBJ_PostedBulanan a")
								->join("TBJ_HisCurrency b", "a.Hiscurrency_ID = b.Hiscurrency_ID", "INNER")
								->where('Tanggal >=', $item->Tanggal)
								->count_all_results();

		$_closing_ar = $_ci->{$_db_suffix}->from("AR_trPostedBulanan")
								->where('Tgl_Saldo >=', $item->Tanggal)
								->count_all_results();	
								
		$_closing_ap = $_ci->{$_db_suffix}->from("AP_trPostedBulanan")
								->where('Tgl_Saldo >=', $item->Tanggal)
								->count_all_results();		
								
		if( $_closing_gl || $_closing_ar || $_closing_ap ):
			response_json([
				'status' => 'error',
				'success' => FALSE,
				'state' => 0,
				'message' => lang('message:already_closing_bo')
			]);
		endif;
		
		$_invoice_created = $_ci->{$_db_suffix}->from("AR_trInvoice a")
								->join("AR_trInvoiceDetail b", "a.No_Invoice = b.No_Invoice", "INNER")
								->where("a.Cancel_Invoice", 0)
								->like('No_Bukti', $item->NoInvoice)
								->count_all_results();	

		if( $_invoice_created ):
			response_json([
				'status' => 'error',
				'success' => FALSE,
				'state' => 0,
				'message' => lang('message:already_creating_invoice')
			]);
		endif;
		
		$_closing_officer = $_ci->db->from("{$_ci->cashier_model->table} a")
								->join("{$_ci->cashier_detail_model->table} b", "a.NoBukti = b.NoBukti", "INNER")
								->where([
									"NIK !=" => '',
									"a.NoBukti" => $item->NoInvoice,
									"DATEPART(day, GETDATE()) >" => 26,
									"DATEPART(day, Tanggal) <=" => 25,
									"DATEDIFF(month, Tanggal, getdate()) =" => 0,
									"b.IDBayar" => 19,
									"b.NilaiBayar >" => 0,
								])
								->count_all_results();	
								
		$_closing_otc = $_ci->db->from("{$_ci->otc_drug_model->table}")
								->where([
									"NIK !=" => '',
									"NoBukti" => $item->NoInvoice,
									"DATEPART(day, GETDATE()) >" => 26,
									"DATEPART(day, Tanggal) <=" => 25,
									"DATEDIFF(month, Tanggal, getdate()) =" => 0,
									"NilaiPembayaranBonKaryawan !=" => 0,
								])
								->count_all_results();	

		if( $_closing_officer || $_closing_otc ):
			response_json([
				'status' => 'error',
				'success' => FALSE,
				'state' => 0,
				'message' => lang('message:already_closing_officer')
			]);
		endif;		
		
		$_voucher_created = $_ci->{$_db_suffix}->from("AP_trVoucherDetail a")
								->join("AP_trVoucher b", "a.No_Voucher = b.No_Voucher")
								->like('a.No_Bukti', $item->NoInvoice)
								->where('b.Cancel_Voucher', 0)
								->count_all_results();		
								
		if( $_voucher_created ):
			response_json([
				'status' => 'error',
				'success' => FALSE,
				'state' => 0,
				'message' => lang('message:already_creating_voucher')
			]);
		endif;
		
		if( $item_split ):
					
			$_db_suffix_split = $item_split->PostingKeBackOffice;
			
			$_closing_gl = $_ci->{$_db_suffix_split}->from("TBJ_PostedBulanan a")
									->join("TBJ_HisCurrency b", "a.Hiscurrency_ID = b.Hiscurrency_ID", "INNER")
									->where('Tanggal >=', $item_split->Tanggal)
									->count_all_results();
	
			$_closing_ar = $_ci->{$_db_suffix_split}->from("AR_trPostedBulanan")
									->where('Tgl_Saldo >=', $item_split->Tanggal)
									->count_all_results();		
									
			if( $_closing_gl || $_closing_ar ):
				response_json([
					'status' => 'error',
					'success' => FALSE,
					'state' => 0,
					'message' => lang('message:already_closing_gl')
				]);
			endif;
			
			$_invoice_created = $_ci->{$_db_suffix_split}->from("AR_trInvoice a")
									->join("AR_trInvoiceDetail b", "a.No_Invoice = b.No_Invoice", "INNER")
									->where("a.Cancel_Invoice", 0)
									->like('No_Bukti', $item_split->NoInvoice)
									->count_all_results();	
	
			if( $_invoice_created ):
				response_json([
					'status' => 'error',
					'success' => FALSE,
					'state' => 0,
					'message' => lang('message:already_creating_invoice')
				]);
			endif;
			
			$_closing_officer = $_ci->db->from("{$_ci->cashier_model->table} a")
									->join("{$_ci->cashier_detail_model->table} b", "a.NoBukti = b.NoBukti", "INNER")
									->where([
										"NIK !=" => '',
										"a.NoBukti" => $item_split->NoInvoice,
										"DATEPART(day, GETDATE()) >" => 26,
										"DATEPART(day, Tanggal) <=" => 25,
										"DATEDIFF(month, Tanggal, getdate()) =" => 0,
										"b.IDBayar" => 19,
										"b.NilaiBayar >" => 0,
									])
									->count_all_results();	
									
			$_closing_otc = $_ci->db->from("{$_ci->otc_drug_model->table}")
									->where([
										"NIK !=" => '',
										"NoBukti" => $item_split->NoInvoice,
										"DATEPART(day, GETDATE()) >" => 26,
										"DATEPART(day, Tanggal) <=" => 25,
										"DATEDIFF(month, Tanggal, getdate()) =" => 0,
										"NilaiPembayaranBonKaryawan !=" => 0,
									])
									->count_all_results();	
	
			if( $_closing_officer || $_closing_otc ):
				response_json([
					'status' => 'error',
					'success' => FALSE,
					'state' => 0,
					'message' => lang('message:already_closing_officer')
				]);
			endif;		
			
			$_voucher_created = $_ci->{$_db_suffix_split}->from("AP_trVoucherDetail")
									->like('No_Bukti', $item_split->NoInvoice)
									->count_all_results();		
									
			if( $_voucher_created ):
				response_json([
					'status' => 'error',
					'success' => FALSE,
					'state' => 0,
					'message' => lang('message:already_creating_voucher')
				]);
			endif;
		endif;
	
		$_ci->db->trans_begin();
		$_ci->BO_1->trans_begin();
		
		if(config_item('multi_bo') === 1)
		$_ci->BO_2->trans_begin();
			
			$_ci->{$_db_suffix}->like('No_Bukti', $item->NoInvoice)->delete('GC_trGeneralCashierDetail');
			$_ci->{$_db_suffix}->like('No_Bukti', $item->NoInvoice)->delete('GC_trGeneralCashier');
			$_ci->{$_db_suffix}->like('No_Bukti', $item->NoInvoice)->delete('TBJ_Transaksi_Detail');
			$_ci->{$_db_suffix}->like('No_Bukti', $item->NoInvoice)->delete('TBJ_Transaksi');
			$_ci->{$_db_suffix}->like('NoReferensiFaktur', $item->NoInvoice)->delete('AR_trKartuPiutang');
			$_ci->{$_db_suffix}->like('No_Faktur', $item->NoInvoice)->delete('AR_trFakturDetail');
			$_ci->{$_db_suffix}->like('No_Faktur', $item->NoInvoice)->delete('AR_trFaktur');
			$_ci->{$_db_suffix}->like('NoReferensiFaktur', $item->NoInvoice)->delete('AP_trKartuHUtang');
			$_ci->{$_db_suffix}->like('No_Faktur', $item->NoInvoice)->delete('AP_trFakturDetail');
			$_ci->{$_db_suffix}->like('No_Faktur', $item->NoInvoice)->delete('AP_trFaktur');
			
			$_ci->audit_model->update(['Posting' => 0], $item->NoBukti );
			
			$activities_description = sprintf( "CANCEL POSTING VERIFIKATOR TO AKUNTING .# %s", $item->NoBukti );
			insert_user_activity( $activities_description, $item->NoBukti, self::$user_auth->Nama_Asli );
			
			if( $item_split ):
				$_ci->{$_db_suffix_split}->like('No_Bukti', $item_split->NoInvoice)->delete('GC_trGeneralCashierDetail');
				$_ci->{$_db_suffix_split}->like('No_Bukti', $item_split->NoInvoice)->delete('GC_trGeneralCashier');
				$_ci->{$_db_suffix_split}->like('No_Bukti', $item_split->NoInvoice)->delete('TBJ_Transaksi_Detail');
				$_ci->{$_db_suffix_split}->like('No_Bukti', $item_split->NoInvoice)->delete('TBJ_Transaksi');
				$_ci->{$_db_suffix_split}->like('NoReferensiFaktur', $item_split->NoInvoice)->delete('AR_trKartuPiutang');
				$_ci->{$_db_suffix_split}->like('No_Faktur', $item_split->NoInvoice)->delete('AR_trFakturDetail');
				$_ci->{$_db_suffix_split}->like('No_Faktur', $item_split->NoInvoice)->delete('AR_trFaktur');
				$_ci->{$_db_suffix_split}->like('NoReferensiFaktur', $item_split->NoInvoice)->delete('AP_trKartuHUtang');
				$_ci->{$_db_suffix_split}->like('No_Faktur', $item_split->NoInvoice)->delete('AP_trFakturDetail');
				$_ci->{$_db_suffix_split}->like('No_Faktur', $item_split->NoInvoice)->delete('AP_trFaktur');
				
				$_ci->audit_model->update(['Posting' => 0], $item_split->NoBukti );
				
				$activities_description = sprintf( "CANCEL POSTING VERIFIKATOR TO AKUNTING .# %s", $item_split->NoBukti );
				insert_user_activity( $activities_description, $item_split->NoBukti, self::$user_auth->Nama_Asli );
			endif;
		//if( $_ci->db->trans_status() === FALSE || $_ci->BO_1->trans_status() === FALSE || $_ci->BO_2->trans_status() === FALSE ) 
		if( $_ci->db->trans_status() === FALSE || $_ci->BO_1->trans_status() === FALSE ) 
		{
			$_ci->db->trans_rollback();
			$_ci->BO_1->trans_rollback();
			
			if(config_item('multi_bo') === 1)
			$_ci->BO_2->trans_rollback();
			
			return FALSE;
		} 
		
		$_ci->db->trans_commit();
		$_ci->BO_1->trans_commit();
		
		if(config_item('multi_bo') === 1)
		$_ci->BO_2->trans_commit();
		return TRUE;
	}
		
	public static function gen_audit_number( $date = NULL )
	{
		$_ci = self::ci();

		$date = DateTime::createFromFormat('Y-m-d', ($date != NULL) ? $date : date('Y-m-d') );
		
		$date_start = $date->format( "Y-m-01 00:00:00.000" );
		$date_end = $date->format( "Y-m-t 00:00:00.000" );
		$date_y = $date->format( "y" );
		$date_m = $date->format( "m" );
		$date_d = $date->format( "d" );
		
		$query =  $_ci->db->select("MAX( Right(NoBukti, 6) ) as max_number")
						->where(array(
							"DATEPART(YEAR, Tanggal) =" => $date->format( "Y" ),
							"DATEPART(MONTH, Tanggal) =" => $date->format( "m" ),
						))
						->like("NoBukti", "INC-")
						->get( "{$_ci->audit_model->table}" )
						->row();
						
		$max_number = !empty($query->max_number) ? ++$query->max_number : 1;
		$number = (string) (sprintf(self::_gen_audit_number(), $date_y, $date_m, $max_number));
		return $number;
	}
	
	private static function _gen_audit_number()
	{
		$format = "%02d%02dINC-%06d";
		return $format;
	}	
	
	public static function check_closing_period( $date )
	{
		$date = DateTime::createFromFormat('Y-m-d', $date);
		$month = $date->format('m');
		$year = $date->format('Y');
		
		return (boolean)
			self::ci()->db->where(array(
								"DATEPART(YEAR, Tanggal)=" => $year,
								"DATEPART(MONTH, Tanggal)=" => $month
							))
							->count_all_results('GD_trPostedBulanan');
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

	public static function insert_warehouse_fifo_mutation( Array $args )
	{
		
		$defaults = [
			'location_id' => 0, 
			'item_id' => 0,  
			'item_unit_code' => 0,  
			'qty' => 0, 
			'price' => 0,  
			'evidence_number' => '',  
			'trans_type_id' => 520,
			'trans_date' => date('Y-m-d'),  
			'item_type_id' => 0, 
			'to_location_id' => 0,
			'exp_date' => date('Y-m-d'),  
		];
		
		$arguments = array_merge( $defaults, $args );
		extract($arguments);
		
		# [IsiKartuGudangFIFO_Mutasi](@LokasiID int, @BarangID int,@KodeSatuan varchar(10),@Qty float,@Harga money,
		# @NoBukti varchar(50),@JenisTransaksi int,@TglTransaksi varchar(50),@JenisBarang smallint,
		# @LokasiIDTujuan int,@ExpDate varchar(50))
		
		self::ci()->db->query("
				EXEC IsiKartuGudangFIFO_Mutasi 
					{$location_id}, {$item_id}, '{$item_unit_code}', {$qty}, {$price},
					'{$evidence_number}', {$trans_type_id}, '{$trans_date}', {$item_type_id}, {$to_location_id}, '{$exp_date}'
			");
	}

	public static function insert_price_change( Array $args )
	{
		self::ci()->load->library('simple_login');
		$user = self::ci()->simple_login->get_user();
		
		$defaults = [
			'location_id' => 0, 
			'item_id' => 0,  
			'trans_date' => date('Y-m-d'),  
			'price' => 0,  
			'user_id' => $user->User_ID, 
		];
		
		$arguments = array_merge( $defaults, $args );
		extract($arguments);
		
		# EXEC InsertHargaChange IntLokasi_ID, Barang_Id, 'Tgl_Penerimaan', harga, user
		self::ci()->db->query("
				EXEC InsertHargaChange {$location_id}, {$item_id}, '{$trans_date}', {$price}, {$user_id} 
			");
	}
	
	public static function insert_supplier_item( Array $args )
	{
		self::ci()->load->library('simple_login');
		$user = self::ci()->simple_login->get_user();
		
		$defaults = [
			'supplier_id' => 0, 
			'item_id' => 0,  
			'trans_date' => date('Y-m-d'),  
			'price' => 0,  
			'user_id' => $user->User_ID, 
		];
		
		$arguments = array_merge( $defaults, $args );
		extract($arguments);
		
		# EXEC InsertBarangSupplier Supplier_ID, Barang_ID, 'Tgl_Penerimaan', Harga_Beli, User.ID
		self::ci()->db->query("
				EXEC InsertBarangSupplier {$supplier_id}, {$item_id}, '{$trans_date}', {$price}, {$user_id} 
			");
	}
		
	private static function & ci()
	{
		return get_instance();
	}	

}
