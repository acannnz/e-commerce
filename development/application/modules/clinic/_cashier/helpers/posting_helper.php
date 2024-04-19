<?php
final class Posting_helper
{
	public static $user_auth;
	public static $_ci;
		
	public static function init()
	{
		$_ci = self::$_ci = self::ci();
		
		$_ci->BO_1 = $_ci->load->database('BO_1', TRUE);
		$_ci->BO_2 = $_ci->load->database('BO_2', TRUE);
		
		$_ci->load->model('audit_model');
		$_ci->load->model('audit_detail_ap_model');
		$_ci->load->model('audit_detail_ar_model');
		$_ci->load->model('audit_revenue_model');
		$_ci->load->model('audit_journal_payment_model');
		$_ci->load->model('audit_section_model');
		
		$_ci->load->model('cashier_model');
		$_ci->load->model('registration_model');
		$_ci->load->model('otc_drug_model');
		$_ci->load->model('bill_pharmacy_model');
		$_ci->load->model('section_model');
		
		$_ci->load->library('simple_login');		
		self::$user_auth = $_ci->simple_login->get_user();
	}
	
	public static function get_his_currency( $date )
	{
		$query = self::ci()->db->where("Tanggal", $date)->get( "TBJ_HisCurrency" );
		if( $query->num_rows() > 0 )
		{
			self::ci()->db->query("exec CekHisCurrency '". $date ."' ");
		}

		$query = self::ci()->db->where("Tanggal", $date)->get( "TBJ_HisCurrency" );
		
		return ( $query->num_rows() > 0 ) ? $query->row()->HisCurrency_ID : 1;
	}
	
	public static function posting( $posting_data )
	{
		$_ci = self::$_ci;
		self::get_his_currency( date('Y-m-d') );
		
		$_ci->db->trans_begin();
		$_ci->BO_1->trans_begin();
		$_ci->BO_2->trans_begin();
		
			$_response = [
				'state' => 1, 
				'message' => lang('message:posting_successfully')
			];
			
			foreach( $posting_data as $row ):
				
				$_response = self::_posting( $row->NoBukti, $row->TglTransaksi );
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
						$_ci->BO_1->trans_rollback();
						$_ci->BO_2->trans_rollback();
						return $_response;
					case 2: 
						break 2;
				}
				
			endforeach;
															
		if($_ci->db->trans_status() === FALSE || $_ci->BO_1->trans_status() === FALSE || $_ci->BO_2->trans_status() === FALSE ) 
		{
			$_ci->db->trans_rollback();
			$_ci->BO_1->trans_rollback();
			$_ci->BO_2->trans_rollback();
			
			return [
				'state' => 0, 
				'message' => lang('message:posting_failed')
			];

		} else {
			$_ci->db->trans_commit();
			$_ci->BO_1->trans_commit();
			$_ci->BO_2->trans_commit();
			
			/*$_ci->db->trans_rollback();
			$_ci->BO_1->trans_rollback();
			$_ci->BO_2->trans_rollback();*/
						
			$_response = [
				'state' => 1, 
				'message' => lang('message:posting_successfully')
			];
		}
		
		return $_response;
	}	
	
	private static function _posting( $NoInvoice, $date )
	{
		$_ci = self::$_ci;
		
		$item = $_ci->audit_model->get_one($NoInvoice);
		
		$_db_suffix = $item->PostingKeBackOffice;
		$_db_bo = $_ci->{$_db_suffix}->database . ".dbo.";
		
		$_closing_gl = $_ci->{$_db_suffix}->from("TBJ_PostedBulanan a")
								->join("TBJ_HisCurrency b", "a.Hiscurrency_ID = b.Hiscurrency_ID", "INNER")
								->where('Tanggal >=', $date)
								->count_all_results();
		if( $_closing_gl ):
			return [
				'state' => 0,
				'message' => lang('message:already_closing_gl')
			];
		endif;
		
		if( config_item('PostingDoubleDB') == 1 ):
			
			$IntAkunRAKHospital = 0;
			if( $_get_account = $_ci->{$_db_suffix}->where('Akun_No', config_item('RAK_Hospital'))->get('Mst_Akun')->row()):
		
				$IntAkunRAKHospital = $_get_account->Akun_ID;
			endif;
			
			$StrRJ = "RJ";
			$_get_audit = $_ci->db->select( "b.RJ, c.JenisKerjasamaID" )
								->from("{$_ci->audit_model->table} a")
								->join("{$_ci->cashier_model->table} b", "a.NoInvoice = b.NoBukti", "INNER")
								->join("{$_ci->registration_model->table} c", "a.NoReg = c.NoReg", "INNER")
								->where("a.NoBukti", $NoInvoice)
								->get()->row();
			if( $_get_audit ):
				$StrRJ = $_get_audit->RJ;
				$StrTIpePasien = $_get_audit->JenisKerjasamaID;
			endif;
			
			if( $_get_account = $_ci->{$_db_suffix}->where('Akun_No', config_item('RAK_Corporate'))->get('Mst_Akun')->row()):
				
				$IntAkunRAKPusat = $_get_account->Akun_ID;
			endif;
			
		endif;
		
		
		$IntHisCurrencyId = 0;
		$_his_currency = $_ci->{$_db_suffix}->select("MIN(HisCurrency_ID) AS ID ")->where('Tanggal <=', $date)->get('TBJ_HisCurrency')->row();
		if( @$_his_currency->ID ):
			$IntHisCurrencyId = $_his_currency->ID;
		endif;
		
		$_db_select = "
				a.*,
				b.UnitLuar,
				b.NRM,
				c.NamaPasien,
				b.JenisKerjasamaID,
				b.PasienAsuransi,
				b.TglReg,
				b.CaseNo,
				b.ReffNo,
				b.SectionID
			";
		$_get_audit = $_ci->db->select( $_db_select )
							->from("{$_ci->audit_model->table} a")
							->join("{$_ci->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER")
							->join("{$_ci->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER")
							->where("a.NoBukti", $NoInvoice)
							->get()->row();
									
		if( $_get_audit ):
			
			$BlnUnitLuar = $_get_audit->UnitLuar;
			
			if( config_item('VerifikatorPostingKeAP') == 1 ):
			
				$_db_select = "
					NoBuktiAP, Supplier_ID,
					FLOOR(Nilai_Hutang * (100 - Pajak) /100) AS Nilai,
					FLOOR(Nilai_Hutang * Pajak / 100) AS Pajak,
					Keterangan, TipeHutangID, Akun_ID, NRM, NamaPasien,
					Nomor, Tarif, Qty, Discount, NoReg, TglTindakan, TglClosing,
					JasaID, JenisJasaName, KomponenName, RSU, JenisHonor, AkunLawanID, AkunIDPajak 	
				";
				$_get_audit_ap = $_ci->db->select( $_db_select )
										->where('NoBukti', $_get_audit->NoBukti)
										->get( $_ci->audit_detail_ap_model->table )->result();
				if( $_get_audit_ap ):
					foreach( $_get_audit_ap as $aap ): // aap = audit detail ap
						
						$_insert_factur = [
							'Tgl_Faktur' => $aap->Tgl_Closing, 
							'No_Faktur' => $aap->NoBuktiAP, 
							'Nilai_Faktur' => $aap->Nilai, 
							'Supplier_ID' => $aap->Supplier_ID, 
							'Currency_ID' => 1, 
							'User_ID' => self::$user_auth->User_ID, 
							'Tgl_Update' => date('Y-m-d'), 
							'Keterangan' => $aap->Keterangan, 
							'Jenis_Pos' => 'FAP',
							'Nilai_Tukar' => 1,
							'HisCurrencyID' => 1,
							'JenisHutang_ID' => $aap->TipeHutangID,
							'Kode_Proyek' => config_item('KodeProyek'),
							'NoKontrak' => '-',
							'DivisiID' => config_item('IDDivisi'),
							'Akun_ID' => $aap->Akun_ID,
							'Tgl_JatuhTempo' => $aap->TglClosing,
							'Sisa' => $aap->Nilai,
							'Diakui_Hutang' => 1,
							'Tgl_Pengakuan' => $aap->TglClosing,
							'NRM' => $aap->NRM,
							'NoReg' => $aap->NoReg
						];
						$_ci->{$_db_suffix}->insert( 'AP_trFaktur', $_insert_factur);
						
						$CurNilaiHutang = $aap->Nilai;
						$CurNilaiPajak = $aap->Pajak;
						
						// detail faktur
						$_insert_factur_detail = [
							'No_Faktur' => $aap->NoBuktiAP,
							'Akun_ID' => $aap->AkunLawanID,
							'Keterangan' => $aap->Keterangan, 
							'Harga_Transaksi' => $CurNilaiHutang + $CurNilaiPajak,
							'Pos' => 'D',
							'Qty' => 1,
							'SectionID' => config_item('SectionIDCorporate')
						];
						$_ci->{$_db_suffix}->insert( 'AP_trFakturDetail', $_insert_factur_detail);
						
						$_insert_factur_detail = [
							'No_Faktur' => $aap->NoBuktiAP,
							'Akun_ID' => $aap->AkunID,
							'Keterangan' => $aap->Keterangan, 
							'Harga_Transaksi' => $CurNilaiHutang,
							'Pos' => 'K',
							'Qty' => 1,
							'SectionID' => config_item('SectionIDCorporate')
						];
						$_ci->{$_db_suffix}->insert( 'AP_trFakturDetail', $_insert_factur_detail);
						
						if( $CurNilaiPajak> 0 ):
						
							$_insert_factur_detail = [
								'No_Faktur' => $aap->NoBuktiAP,
								'Akun_ID' => $aap->AkunIDPajak,
								'Keterangan' => $aap->Keterangan, 
								'Harga_Transaksi' => $CurNilaiPajak,
								'Pos' => 'K',
								'Qty' => 1,
								'SectionID' => config_item('SectionIDCorporate')
							];
							$_ci->{$_db_suffix}->insert(  'AP_trFakturDetail', $_insert_factur_detail);
							
						endif;	
					endforeach; // endforeach $_get_audit_ap
				endif;
			endif; // endif verifikatorPostingKeAP
			
			if( config_item('PostingMAAR') == 1 || config_item('PostingIKSAR') ):
				
				$_get_audit_ar = $_ci->db->select( "a.*, b.NRM" )
										->from( "{$_ci->audit_detail_ar_model->table} a" )
										->join( "{$_ci->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
										->where('a.NoBukti', $_get_audit->NoBukti)
										->get()->result();
										
				if( $_get_audit_ar ): 
					foreach( $_get_audit_ar as $key => $aar ): // aar = audit detail ar
						
						$StrNoBukti_AR = ( $key + 1 == 1 ) ? $aar->NoBuktiAR : sprintf("%s_%s", $aar->NoBuktiAR, $key + 1);
						$IntAkunPPNID = 0;
						$CurNilaiPPN = 0;
						
						if( config_item('PostingDoubleDB') == 1 ):
							
							if( config_item('AdaVerifikasiPiutang') ):
							
								$_insert_tbj_trans = [
									'Relasi_Id' => 0,
									'Currency_ID' => 1,
									'HisCurrency_ID' => $IntHisCurrencyId,
									'Transaksi_Date' => $date,
									'No_Bukti' => $aar->NoBuktiAR,
								  	'Kode_Transfer' => 'FO',
									'Tgl_Update' => $date,
									'Debit' => $aar->NilaiPiutang,
									'Kredit' => $aar->NilaiPiutang,
									'Nilai_Tukar' => 1,
								  	'User_ID' => self::$user_auth->User_ID,
									'Integrasi' => 1,
									'Type_Jurnal' => 1,
									'Keterangan' => $aar->Keterangan,
									'Kode_Proyek' => 1,
									'NRM' => $aar->NRM,
									'NoReg' => $aar->NoReg
								];
								$_ci->{$_db_suffix}->insert( 'TBJ_Transaksi', $_insert_tbj_trans);
								
								$_insert_tbj_trans_detail = [
									'No_Bukti' => $aar->NoBuktiAR,
									'Akun_ID' => @$IntAkunRAKHospital,
									'Debit' => $aar->NilaiPiutang,
									'Kredit' => 0,
									'Keterangan' => $aar->Keterangan,
									'SectionID' => config_item('SectionIDCorporate')
								];
								$_ci->{$_db_suffix}->insert( 'TBJ_Transaksi_Detail', $_insert_tbj_trans_detail);
								
								$_insert_tbj_trans_detail = [
									'No_Bukti' => $aar->NoBuktiAR,
									'Akun_ID' => $aar->AkunLAwanID,
									'Debit' => 0,
									'Kredit' => $aar->NilaiPiutang,
									'Keterangan' => $aar->Keterangan,
									'SectionID' => config_item('SectionIDCorporate')
								];
								$_ci->{$_db_suffix}->insert( 'TBJ_Transaksi_Detail', $_insert_tbj_trans_detail);
								
								$IntAkunTypePiutang = 0;
								$_get_receivable_type = $_ci->{$_db_suffix}->where('TypePiutang_ID', config_item('TypePiutangAsuransiPusat'))
																->get('AR_mTypePiutang')->row();
								if( $_get_receivable_type ):
									$IntAkunTypePiutang = $_get_receivable_type->Akun_ID;
								endif;
								
								$_insert_factur = [
									'Tgl_Faktur' => $aar->Tgl_Closing, 
									'No_Faktur' => $aar->NoBuktiAR, 
									'Nilai_Faktur' => $aar->NilaiPiutang, 
									'Customer_ID' => $aar->CustomerID, 
									'Currency_ID' => 1, 
									'User_ID' => self::$user_auth->User_ID, 
									'Tgl_Update' => date('Y-m-d'), 
									'Keterangan' => $aar->Keterangan, 
									'Jenis_Pos' => 'FAR',
									'Nilai_Tukar' => 1,
									'HisCurrencyID' => 1,
									'JenisHutang_ID' => config_item('TypePiutangAsuransiPusat'),
									'Kode_Proyek' => config_item('KodeProyekPusat'),
									'NoKontrak' => '-',
									'DivisiID' => config_item('IDDivisiPusat'),
									'Tgl_JatuhTempo' => $aar->TglClosing,
									'Sisa' => $aar->NilaiPiutang,
									'NRM' => $aar->NRM,
									'CustomerID_Transaksi' => $aar->CustomerID,
									'Diakui_Piutang' => 1,
									'Tgl_Pengakuan' => $aar->TglClosing,
									'NamaPasien' => $aar->NamaPasien,
									'ReffNo' => $_get_audit->ReffNo,
									'CaseNo' => $_get_audit->CaseNo,
									'TglReg' => $_get_audit->TglReg
								];
								$_ci->{$_db_suffix}->insert( 'AR_trFaktur', $_insert_factur);
								
								// detail faktur
								$_insert_factur_detail = [
									'No_Faktur' => $aar->NoBuktiAR,
									'Akun_ID' => @$IntAkunTypePiutang,
									'Keterangan' => $aar->Keterangan, 
									'Harga_Transaksi' => $arr->NilaiPiutang,
									'Pos' => 'D',
									'Qty' => 1,
									'SectionID' => config_item('SectionIDCorporate')
								];
								$_ci->{$_db_suffix}->insert( 'AR_trFakturDetail', $_insert_factur_detail);
								
								$_insert_factur_detail = [
									'No_Faktur' => $aar->NoBuktiAR,
									'Akun_ID' => @$IntAkunRAKPusat,
									'Keterangan' => $aar->Keterangan, 
									'Harga_Transaksi' => $aar->NilaiPiutang,
									'Pos' => 'K',
									'Qty' => 1,
									'SectionID' => config_item('SectionIDCorporate')
								];
								$_ci->{$_db_suffix}->insert( 'AR_trFakturDetail', $_insert_factur_detail);
							
							else: // else ( config_item('AdaVerifikasiPiutang') )
								
								$_get_receivable_type = $_ci->{$_db_suffix}->where('TypePiutang_ID', config_item('TypePiutangAsuransiPusat'))
																->get('AR_mTypePiutang')->row();
								$IntAkunTypePiutang = 0;
								if( $_get_receivable_type ):
									$IntAkunTypePiutang = $_get_receivable_type->Akun_ID;
								endif;
								
								$_insert_factur = [
									'Tgl_Faktur' => $aar->Tgl_Closing, 
									'No_Faktur' => $aar->NoBuktiAR, 
									'Nilai_Faktur' => $aar->NilaiPiutang, 
									'Customer_ID' => $aar->CustomerID, 
									'Currency_ID' => 1, 
									'User_ID' => self::$user_auth->User_ID, 
									'Tgl_Update' => date('Y-m-d'), 
									'Keterangan' => $aar->Keterangan, 
									'Jenis_Pos' => 'FAR',
									'Nilai_Tukar' => 1,
									'HisCurrencyID' => 1,
									'JenisHutang_ID' => config_item('TypePiutangAsuransiPusat'),
									'Kode_Proyek' => config_item('KodeProyekPusat'),
									'NoKontrak' => '-',
									'DivisiID' => config_item('IDDivisiPusat'),
									'Tgl_JatuhTempo' => $aar->TglClosing,
									'Sisa' => $aar->NilaiPiutang,
									'NRM' => $aar->NRM,
									'CustomerID_Transaksi' => $aar->CustomerID,
									'TglReg' => $_get_audit->TglReg,
									'Cancel_Faktur' => 0,
								];
								$_ci->{$_db_suffix}->insert( 'AR_trFaktur', $_insert_factur);
								
								// detail faktur
								$_insert_factur_detail = [
									'No_Faktur' => $aar->NoBuktiAR,
									'Akun_ID' => @$IntAkunTypePiutang,
									'Keterangan' => $aar->Keterangan, 
									'Harga_Transaksi' => $arr->NilaiPiutang,
									'Pos' => 'D',
									'Qty' => 1,
									'SectionID' => config_item('SectionIDCorporate')
								];
								$_ci->{$_db_suffix}->insert(  'AR_trFakturDetail', $_insert_factur_detail);
								
								$_insert_factur_detail = [
									'No_Faktur' => $aar->NoBuktiAR,
									'Akun_ID' => @$IntAkunRAKPusat,
									'Keterangan' => $aar->Keterangan, 
									'Harga_Transaksi' => $aar->NilaiPiutang,
									'Pos' => 'K',
									'Qty' => 1,
									'SectionID' => config_item('SectionIDCorporate')
								];
								$_ci->{$_db_suffix}->insert( 'AR_trFakturDetail', $_insert_factur_detail);
								
							endif; // endif( config_item('AdaVerifikasiPiutang') )
							
						else:
							// posting ke BO Hospital saja
                            // insert ke AR_trFaktur
							
							$_insert_factur = [
								'Tgl_Faktur' => $aar->TglClosing, 
								'No_Faktur' => $StrNoBukti_AR, 
								'Nilai_Faktur' => $aar->NilaiPiutang, 
								'Customer_ID' => $aar->CustomerID, 
								'Currency_ID' => 1, 
								'User_ID' => self::$user_auth->User_ID, 
								'Tgl_Update' => date('Y-m-d'), 
								'Keterangan' => $aar->Keterangan, 
								'Jenis_Pos' => 'FAR',
								'Nilai_Tukar' => 1,
								'HisCurrencyID' => 1,
								'JenisPiutang_ID' => $aar->TypePiutangID,
								'Kode_Proyek' => config_item('KodeProyek'),
								'NoKontrak' => '-',
								'DivisiID' => config_item('IDDivisi'),
								'Tgl_JatuhTempo' => $aar->TglClosing,
								'Sisa' => $aar->NilaiPiutang,
								'NRM' => $aar->NRM,
								'CustomerID_Transaksi' => $aar->CustomerID,
								'Diakui_Piutang' => 1,
								'Tgl_Pengakuan' => $aar->TglClosing,
								'NamaPasien' => $aar->NamaPasien,
							];
							$_ci->{$_db_suffix}->insert( 'AR_trFaktur', $_insert_factur);
							
							// detail faktur
							$_insert_factur_detail = [
								'No_Faktur' => $StrNoBukti_AR,
								'Akun_ID' => $aar->AkunID,
								'Keterangan' => $aar->Keterangan, 
								'Harga_Transaksi' => $aar->NilaiPiutang, 
								'Pos' => 'D',
								'Qty' => 1,
								'SectionID' => config_item('SectionIDCorporate')
							];
							$_ci->{$_db_suffix}->insert( 'AR_trFakturDetail', $_insert_factur_detail);
							
							if( $CurNilaiPPN > 0 ):
								
								$_insert_factur_detail = [
									'No_Faktur' => $StrNoBukti_AR,
									'Akun_ID' => $aar->AkunLawanID,
									'Keterangan' => $aar->Keterangan, 
									'Harga_Transaksi' => $CurNilaiHutang - $CurNilaiPPN,
									'Pos' => 'K',
									'Qty' => 1,
									'SectionID' => config_item('SectionIDCorporate')
								];
								$_ci->{$_db_suffix}->insert( 'AR_trFakturDetail', $_insert_factur_detail);
								
								$_insert_factur_detail = [
									'No_Faktur' => $StrNoBukti_AR,
									'Akun_ID' => $IntAkunPPNID,
									'Keterangan' => $aar->Keterangan, 
									'Harga_Transaksi' => $CurNilaiPPN,
									'Pos' => 'K',
									'Qty' => 1,
									'SectionID' => config_item('SectionIDCorporate')
								];
								$_ci->{$_db_suffix}->insert( 'AR_trFakturDetail', $_insert_factur_detail);
							
							else:
								
								$_insert_factur_detail = [
									'No_Faktur' => $StrNoBukti_AR,
									'Akun_ID' => $aar->AkunLawanID,
									'Keterangan' => $aar->Keterangan, 
									'Harga_Transaksi' => $aar->NilaiPiutang,
									'Pos' => 'K',
									'Qty' => 1,
									'SectionID' => config_item('SectionIDCorporate')
								];
								$_ci->{$_db_suffix}->insert( 'AR_trFakturDetail', $_insert_factur_detail);
							
							endif;
							
						endif; // endif( config_item('PostingDoubleDB') == 1 )				
					endforeach; // endforeach ( $_get_audit_ar as $key => $aar )
				endif; //endif ( $_get_audit_ar )
				
			endif; // endif( config_item('PostingMAAR') == 1 || config_item('PostingIKSAR')
			
			$_get_audit_revenue = $_ci->db->select("NoBuktiJurnal, SUM(Debet) AS D, SUM(Kredit) AS K")
										->from( $_ci->audit_revenue_model->table )
										->where([
											'NoBukti' => $_get_audit->NoBukti,
											'NoBuktiJurnal !=' => ''
										])
										->group_start()
											->or_where([
												'TipeTransaksi IS NULL' => NULL,
												'TipeTransaksi' => 'PENDAPATAN'
											])
										->group_end()
										->group_by('NoBuktiJurnal')
										->get()->result();			
			// posting pendapatan
			if( $_get_audit_revenue ):
				foreach( $_get_audit_revenue as $are ): // are = audit revenue
			
					if( round($are->K, 2) == 0 && round($are->D, 2) == 0 ):
						goto skip_for_zero;
					endif;
					
					$StrKeterangan = sprintf("Postingan FO %s --> %s", $_get_audit->NoReg, $_get_audit->NamaPasien);
					
					$_insert_tbj_trans = [
						'Relasi_Id' => 0,
						'Currency_ID' => 1,
						'HisCurrency_ID' => $IntHisCurrencyId,
						'Transaksi_Date' => $date,
						'No_Bukti' => $are->NoBuktiJurnal,
						'Kode_Transfer' => 'FO',
						'Tgl_Update' => $date,
						'Debit' => round($are->D, 2),
						'Kredit' => round($are->K, 2),
						'Nilai_Tukar' => 1,
						'User_ID' => self::$user_auth->User_ID,
						'Integrasi' => 1,
						'Type_Jurnal' => 1,
						'Keterangan' => $StrKeterangan,
						'Kode_Proyek' => 1,
						'NRM' => $_get_audit->NRM,
						'NoReg' => $_get_audit->NoReg
					];
					$_ci->{$_db_suffix}->insert(  'TBJ_Transaksi', $_insert_tbj_trans);
					
					// postingan DEBET-nya (lawan dari pendapatan)
					// ini orig nya
					$_db_select = "
						a.Debet,
						a.Kredit,
						a.Keterangan,
						b.Akun_ID
					";
					$_get_audit_revenue_debt = $_ci->db->select( $_db_select )
													->from("{$_ci->audit_revenue_model->table} a" )
													->join("{$_db_bo}Mst_Akun b", "a.AkunNo = b.Akun_No", "INNER")
													->where([
														'a.NoBukti' => $_get_audit->NoBukti,
														'a.NoBuktiJurnal' => $are->NoBuktiJurnal,
														'a.Debet >' => 0
													])
													->not_like('a.NoBuktiJurnal', 'DISC')
													->get()->result();
					if( $_get_audit_revenue_debt ):
						foreach( $_get_audit_revenue_debt as $ard): // ard = audit revenue debt
						
							$CurNilaiLawanPendapatan = $ard->Akun_ID;
                            $StrNoBuktiDebet = $are->NoBuktiJurnal;
                            $StrKeterangan = $ard->Keterangan;
							
						endforeach;
					endif;
					
					########################################################
					#### Untuk Sementara Ambil Dari SIMtrAuditPendapatan ###
					########################################################
					/*if( $CurNilaiLawanPendapatan != 0 ):
						$CurNilaiDebet = 0;
						
						$_db_select ="
							SUM(a.Nilai) AS Nilai,
							a.Keterangan,c.SectionID,
							b.Akun_ID,c.SectionName 
						";
						$_get_audit_section = $_ci->db->select($_db_select)
													->from("{$_ci->audit_section_model->table} a")
													->join("{$_db_bo}Mst_Akun b", "a.AkunNo = b.Akun_No", 'INNER')
													->join("{$_ci->section_model->table} c", "a.SectionName = c.SectionName")
													->where([
														'a.NoBukti' => $_get_audit->NoBukti,
														'a.NoBuktiJurnal' => $are->NoBuktiJurnal
													])
													->group_by(['a.Keterangan', 'c.SectionID', 'b.Akun_ID', 'c.SectionName'])
													->get()->result();
						
						if( $_get_audit_section ):
							foreach( $_get_audit_section as $ase ): // ase = audit section
								
								$_insert_tbj_trans_detail = [
									'No_Bukti' => $are->NoBuktiJurnal,
									'Akun_ID' => @$ase->Akun_ID,
									'Debit' => 0,
									'Kredit' => round($ase->Nilai, 2),
									'Keterangan' => sprintf("%s#%s", $ase->Keterangan, $ase->SectionName),
									'SectionID' => !empty($ase->SectionID) ? $ase->SectionID : config_item('SectionIDCorporate')
								];
								$_ci->{$_db_suffix}->insert( 'TBJ_Transaksi_Detail', $_insert_tbj_trans_detail);

								$CurNilaiDebet = $CurNilaiDebet + round( $ase->Nilai, 2);
							endforeach;
							
							// ini untuk mengamankan agar selalu balance
							$_insert_tbj_trans_detail = [
								'No_Bukti' => $StrNoBuktiDebet,
								'Akun_ID' => @$CurNilaiLawanPendapatan,
								'Debit' => $CurNilaiDebet,
								'Kredit' => 0,
								'Keterangan' => $StrKeterangan,
								'SectionID' => config_item('SectionIDCorporate')
							];
							$_ci->{$_db_suffix}->insert( 'TBJ_Transaksi_Detail', $_insert_tbj_trans_detail);

						endif;	//endif ( $_get_audit_section )

					else:*/
						
						// ini query untuk discount dll, yang tidak masuk ke simtrauditpendapatansection
						
						$_get_audit_revenue_detail = $_ci->db->select("a.Debet, a.Kredit, b.Akun_ID, a.Keterangan")
															->from("{$_ci->audit_revenue_model->table} a")
															->join("{$_db_bo}Mst_Akun b", "a.AkunNo = b.Akun_No", "INNER")
															->where([
																'a.NoBukti' => $_get_audit->NoBukti,
																'a.NoBuktiJurnal' => $are->NoBuktiJurnal,
																'a.Kredit >' => 0,
															])
															->get()->result();
						if( $_get_audit_revenue_detail ):
							foreach( $_get_audit_revenue_detail as $ard ): // ard = audit revenue detail
								
								$_insert_tbj_trans_detail = [
									'No_Bukti' => $are->NoBuktiJurnal,
									'Akun_ID' => $ard->Akun_ID,
									'Debit' => round($ard->Debet, 2),
									'Kredit' => round($ard->Kredit, 2),
									'Keterangan' => $ard->Keterangan,
									'SectionID' => config_item('SectionIDCorporate')
								];
								$_ci->{$_db_suffix}->insert( 'TBJ_Transaksi_Detail', $_insert_tbj_trans_detail );							
								
							endforeach;
						endif;

						$_get_audit_revenue_detail = $_ci->db->select("a.Debet, a.Kredit, b.Akun_ID, a.Keterangan")
															->from("{$_ci->audit_revenue_model->table} a")
															->join("{$_db_bo}Mst_Akun b", "a.AkunNo = b.Akun_No", "INNER")
															->where([
																'a.NoBukti' => $_get_audit->NoBukti,
																'a.NoBuktiJurnal' => $are->NoBuktiJurnal,
																'a.Debet >' => 0,
															])
															->get()->result();
						if( $_get_audit_revenue_detail ):
							foreach( $_get_audit_revenue_detail as $ard ): // ard = audit revenue detail
								
								$_insert_tbj_trans_detail = [
									'No_Bukti' => $are->NoBuktiJurnal,
									'Akun_ID' => $ard->Akun_ID,
									'Debit' => round($ard->Debet, 2),
									'Kredit' => round($ard->Kredit, 2),
									'Keterangan' => $ard->Keterangan,
									'SectionID' => config_item('SectionIDCorporate')
								];
								$_ci->{$_db_suffix}->insert( 'TBJ_Transaksi_Detail', $_insert_tbj_trans_detail);							
								
							endforeach;
						endif;									
					//endif; // endif( $CurNilaiLawanPendapatan != 0 )
										
				endforeach; // endforeach $are = audit revenue
			endif; // endif ($_get_audit_revenue )
			
			skip_for_zero:
			
			$_get_audit_revenue_hpp = $_ci->db->select("NoBuktiJurnal, SUM(Debet) AS D, SUM(Kredit) AS K")
											->from( $_ci->audit_revenue_model->table)
											->where([
												'NoBukti' => $_get_audit->NoBukti,
												'NoBuktiJurnal !=' => '',
												'TipeTransaksi' => 'HPP'
											])
											->group_by('NoBuktiJurnal')
											->get()->result();
			if( $_get_audit_revenue_hpp ):
				
				foreach( $_get_audit_revenue_hpp as $arh ): // arh = audit revenue hpp
				
					$StrKeterangan = sprintf("Postingan FO#HPP %s --> %s", $_get_audit->NoReg, $_get_audit->NamaPasien);
					
					$_insert_tbj_trans = [
						'Relasi_Id' => 0,
						'Currency_ID' => 1,
						'HisCurrency_ID' => $IntHisCurrencyId,
						'Transaksi_Date' => $date,
						'No_Bukti' => $arh->NoBuktiJurnal,
						'Kode_Transfer' => 'FO',
						'Tgl_Update' => $date,
						'Debit' => round($are->D, 2),
						'Kredit' => round($are->K, 2),
						'Nilai_Tukar' => 1,
						'User_ID' => self::$user_auth->User_ID,
						'Integrasi' => 1,
						'Type_Jurnal' => 1,
						'Keterangan' => $StrKeterangan,
						'Kode_Proyek' => 1,
						'NRM' => $_get_audit->NRM,
						'NoReg' => $_get_audit->NoReg
					];
					$_ci->{$_db_suffix}->insert(  'TBJ_Transaksi', $_insert_tbj_trans);
					
					// postingan DEBET-nya (lawan dari pendapatan)
					// ini orig nya
					$_get_audit_revenue_debt = $_ci->db->select("a.Debet, a.Kredit, b.Akun_ID, a.Keterangan, a.SectionID")
														->from("{$_ci->audit_revenue_model->table} a")
														->join("{$_db_bo}Mst_Akun b", "a.AkunNo = b.Akun_No", "INNER")
														->where([
															'a.NoBukti' => $_get_audit->NoBukti,
															'a.NoBuktiJurnal' => $arh->NoBuktiJurnal,
														])
														->get()->result();
					if( $_get_audit_revenue_debt ):
						foreach( $_get_audit_revenue_debt as $ard ):
						
							$_insert_tbj_trans_detail = [
								'No_Bukti' => $arh->NoBuktiJurnal,
								'Akun_ID' => $ard->Akun_ID,
								'Debit' => round($ard->Debet, 2),
								'Kredit' => round($ard->Kredit, 2),
								'Keterangan' => $ard->Keterangan,
								'SectionID' => $ard->SectionID
							];
							$_ci->{$_db_suffix}->insert( 'TBJ_Transaksi_Detail', $_insert_tbj_trans_detail);							
							
						endforeach; 
					endif; // endif( $_get_audit_revenue_debt )
					
				endforeach; // endforeach( $_get_audit_revenue_hpp as $arh )
			endif; // endif( $_get_audit_revenue_hpp )
	
	
			# posting gc		
			$_get_audit_journal_payment = $_ci->db->select("NoBuktiJurnal, SUM(Debet) AS D, SUM(Kredit) AS K")
											->from( $_ci->audit_journal_payment_model->table)
											->where([
												'NoBukti' => $_get_audit->NoBukti,
												'NoBuktiJurnal !=' => '',
											])
											->group_by('NoBuktiJurnal')
											->get()->result();
			
			if( $_get_audit_journal_payment ):
				foreach( $_get_audit_journal_payment as $ajp ): // ajp = audit journal payment
					
					$BlnSudahAdaJurnal = $_ci->{$_db_suffix}->where('No_Bukti', $ajp->NoBuktiJurnal)->count_all_results('GC_trGeneralCashier')
											? TRUE
											: FALSE;		
							
					if ($BlnSudahAdaJurnal) { goto skip_for_already_journal; }
					 
					$BlnAdaAkunBank = FALSE;
					$StrTypeTransaksi = NULL;
					
					$_get_audit_journal_detail = $_ci->db->from( $_ci->audit_journal_payment_model->table)
														->where([
															'NoBukti' => $_get_audit->NoBukti,
															'NoBuktiJurnal' => $ajp->NoBuktiJurnal,
														])->get()->result();
					if( $_get_audit_journal_detail ):
						foreach( $_get_audit_journal_detail as $ajd ): // ajd = audit journal payment (detail)
							$_get_account = [];
							$_get_account = $_ci->{$_db_suffix}->select('Bank, Cash')
													->from('Mst_Akun a')
													->join('Mst_GroupAkunDetail b', 'a.GroupAkunDetailID = b.GroupAkunDetailId', 'INNER')
													->where('Akun_No', $ajd->AkunNo)
													->group_start()
														->or_where(['BANK' => 1, 'CASH' => 1])
													->group_end()
													->get()->row();
													
							if( $_get_account ):
								$BlnAdaAkunBank = TRUE;								
								$StrTypeTransaksi = (@$_get_account->Bank == 1) ? 'BBM' : 'BKM';									
							endif;
							
							if( empty($StrTypeTransaksi) ):
							
								$_get_account = $_ci->{$_db_suffix}->where('Akun_No', $ajd->AkunNo)
														->where_in('Akun_No', [ config_item('AkunNoPotongHonor'), config_item('AkunNoOthers')] )
														->get('Mst_Akun')->row();
														
								if( $_get_account && @$_get_account->Akun_No ):
									$StrTypeTransaksi = "RKD";
									$BlnAdaAkunBank = TRUE;
								endif;
							endif;
							
						endforeach; // endforeach( $_get_audit_journal_detail as $ajd )
					endif; // endif( $_get_audit_journal_detail )
										 
					if( $BlnAdaAkunBank ):
						
						$StrKeterangan = "Postingan FO {$_get_audit->NamaPasien}";
						
						$_db_select = "
							a.Debet, a.Kredit, b.Akun_ID,
							a.Keterangan, c.Bank, c.Cash,
							a.Keterangan, b.Akun_No
						";
						$_get_audit_journal_detail = $_ci->db->select( $_db_select )
															->from("{$_ci->audit_journal_payment_model->table} a")
															->join("{$_db_bo}Mst_Akun b", 'a.AkunNo = b.Akun_No', 'INNER')
															->join("{$_db_bo}Mst_GroupAkunDetail c", 'b.GroupAkunDetailID = c.GroupAkunDetailId', 'INNER')
															->where([
																'a.NoBukti' => $_get_audit->NoBukti,
																'a.NoBuktiJurnal' => $ajp->NoBuktiJurnal
															])
															->group_start()
																->or_where(['a.Debet >' => 0, 'a.Kredit >' => 0 ])
															->group_end()
															->order_by("c.Bank DESC, c.Cash DESC, a.Debet DESC")
															->get()->result();
						
						if( $_get_audit_journal_detail ):							
							foreach( $_get_audit_journal_detail as $ajd ):
								
								if( $ajd->Bank || $ajd->Cash || in_array($ajd->Akun_No, [ config_item('AkunNoPotongHonor'), config_item('AkunNoOthers') ]) ):
									
									$StrSqlDetail = "";
									$StrNoBuktiJurnal = $ajp->NoBuktiJurnal;
									
									$_insert_general_cashier = [
										'Currency_Id' => 1,
										'Tgl_Transaksi' => $date,
										'No_Bukti' => $StrNoBuktiJurnal, 
										'Pakai_Referensi' => 0,
										'Instansi' => $StrKeterangan,
										'Type_Transaksi' => $StrTypeTransaksi,
										'Posted' => 0,
										'Status_Batal' => 0,
										'User_ID' => self::$user_auth->User_ID,
										'Tgl_Update' => date('Y-m-d'),
										'Keterangan' => $ajd->Keterangan,
										'Nilai_Tukar' => 1,
										'Kode_Proyek' => config_item('KodeProyek'),
										'DivisiID' => config_item('IDDivisi'),
										'AkunBG_ID' => $ajd->Akun_ID,
										'Debet' => $ajd->Debet,
										'Kredit' => $ajd->Kredit,
										'SectionID' => config_item('SectionIDCorporate')
									];
									$_ci->{$_db_suffix}->insert( 'GC_trGeneralCashier', $_insert_general_cashier);
								
								else:
								
									$_insert_general_cashier_detail = [
										'No_Bukti' => $ajp->NoBuktiJurnal,
										'Akun_ID' => $ajd->Akun_ID,
										'Debet' => $ajd->Debet,
										'Kredit' => $ajd->Kredit,
										'Keterangan' => $ajd->Keterangan,
										'SectionID' => config_item('SectionIDCorporate')
									];
									$_ci->{$_db_suffix}->insert( 'GC_trGeneralCashierDetail', $_insert_general_cashier_detail);
									
								endif; // endif( $ajd->Bank || $ajd->Cash || in_array($ajd->Akun_No, [ config_item('AkunNoPotongHonor'), config_item('AkunNoOthers') ]) )
							endforeach; // endforeach( $_get_audit_journal_detail as $ajd )
							
							skip_for_already_journal:
							
						else:
							goto skip_for_cash;
						endif; // endif( $_get_audit_journal_detail )
					
					else:
						skip_for_cash:
						
						$StrKeterangan = sprintf("Postingan FO %s --> %s", $_get_audit->NoReg, $_get_audit->NamaPasien);
						
						$_insert_tbj_trans = [
							'Relasi_Id' => 0,
							'Currency_ID' => 1,
							'HisCurrency_ID' => $IntHisCurrencyId,
							'Transaksi_Date' => $date,
							'No_Bukti' => $ajp->NoBuktiJurnal,
							'Kode_Transfer' => 'FO',
							'Tgl_Update' => $date,
							'Debit' => round($ajp->D, 2),
							'Kredit' => round($ajp->K, 2),
							'Nilai_Tukar' => 1,
							'User_ID' => self::$user_auth->User_ID,
							'Integrasi' => 1,
							'Type_Jurnal' => 1,
							'Keterangan' => $StrKeterangan,
							'Kode_Proyek' => 1,
							'NRM' => $_get_audit->NRM,
							'NoReg' => $_get_audit->NoReg
						];
						$_ci->{$_db_suffix}->insert( 'TBJ_Transaksi', $_insert_tbj_trans);
						
						$_get_audit_journal_detail = $_ci->db->select("a.Debet, a.Kredit, b.Akun_ID, a.Keterangan")
															->from("{$_ci->audit_journal_payment_model->table} a")
															->join("{$_db_bo}Mst_Akun b", "a.AkunNo = b.Akun_No", "INNER")
															->where([
																'NoBukti' => $_get_audit->NoBukti,
																'NoBuktiJurnal' => $ajp->NoBuktiJurnal,
															])
															->get()->result();

						if( $_get_audit_journal_detail ):
							foreach( $_get_audit_journal_detail as $ajd ):
								
								$_insert_tbj_trans_detail = [
									'No_Bukti' => $ajp->NoBuktiJurnal,
									'Akun_ID' => $ajd->Akun_ID,
									'Debit' => round($ajd->Debet, 2),
									'Kredit' => round($ajd->Kredit, 2),
									'Keterangan' => $ajd->Keterangan,
									'SectionID' => config_item('SectionIDCorporate')
								];
								$_ci->{$_db_suffix}->insert( 'TBJ_Transaksi_Detail', $_insert_tbj_trans_detail );
								
							endforeach;
						endif;
						
					endif; // endif( $BlnAdaAkunBank )
					
				endforeach; // endforeach ( $_get_audit_journal_payment as $ajp )
			endif; // endif ( $_get_audit_journal_payment )
				
	
			$_ci->audit_model->update(['Posting' => 1], $_get_audit->NoBukti );
			
			$activities_description = sprintf( "POSTING VERIFIKATOR TO AKUNTING .# %s", $NoInvoice );
			insert_user_activity( $activities_description, $NoInvoice, self::$user_auth->Nama_Asli );
			
		endif; // endif ($_get_audit)
				
		return [
			'state' => 1, 
			'message' => lang('message:posting_successfully')
		];		
	}
	
	
	private static function & ci()
	{
		return get_instance();
	}	
}