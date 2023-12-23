<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posting_m extends Public_Model
{
	public $table = 'ar_facturs';
	public $primary_key = 'id';
	
	public $rules;
	public $user;
	public $HisCurrency_ID = 1;
	public $_header;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'remain' => array(
						'field' => 'remain',
						'label' => lang( 'facturs:remain_label' ),
						'rules' => 'required'
					),
			));
			
		parent::__construct();
	}

	private function _before_posting()
	{
		$this->user = $this->simple_login->get_user();
		$this->load->helper('receivable');
		$this->HisCurrency_ID = receivable_helper::get_his_currency( date('Y-m-d') ) ;		

	}
	private function _get_factur( $No_Faktur )
	{
		$db_select = <<<EOSQL
			a.Tgl_Faktur AS Tgl_Transaksi, 
			a.Tgl_Update, 
			a.No_Faktur AS No_Bukti, 
			a.Nilai_Faktur AS Nilai, 
			a.Keterangan, 
			a.Customer_ID, 
			a.Currency_ID,
			a.Nilai_Tukar,
			a.Kode_Proyek,
			a.DivisiID,
			'SEC078' AS SectionID
EOSQL;
		
		$this->db
			->select( $db_select )
			->from( 'AR_trFaktur a' )
			->where("a.No_Faktur", $No_Faktur);
			
		return $this->db->get()->row();
	}
	
	private function _get_invoice( $No_Invoice )
	{
		$db_select = <<<EOSQL
			a.Tgl_Invoice AS Tgl_Transaksi, 
			a.Tgl_Update, 
			a.No_Invoice AS No_Bukti, 
			a.Nilai, 
			a.Keterangan, 
			a.Customer_ID, 
			a.Currency_ID,
			a.Nilai_Tukar,
			a.Kode_Proyek,
			a.DivisiID,
			a.SectionID,
			b.Akun_ID,
			a.Akun_ID AS Akun_ID2
EOSQL;

		$this->db
			->select( $db_select )
			->from( 'AR_trInvoice a' )
			->join( "AR_mTypePiutang b", "a.JenisPiutang_ID = b.TypePiutang_ID", "INNER" )
			->where( "a.No_Invoice", $No_Invoice );
			
		return $this->db->get()->row();
	}
		
	private function _get_cashier()
	{
		$db_select = <<<EOSQL
			a.Tgl_Transaksi, 
			a.No_Bukti,
			sum( b.Debet ) AS Nilai ,  
			a.Customer_ID,
			a.Currency_ID,  
			a.Nilai_Tukar,
			a.Kode_Proyek,
			a.DivisiID
EOSQL;

		$this->db
			->select( $db_select )
			->from( "GC_trGeneralCashier a" )
			->join( "GC_trGeneralCashierDetail b", "a.No_Bukti = b.No_Bukti", "INNER" )
			->where("a.No_Bukti", $No_Bukti)
			->group_by( array( 'a.Tgl_Transaksi', 'a.No_Bukti',  'a.Currency_ID',
						'a.Customer_ID','d.Currency_ID', 'a.Nilai_Tukar', 'a.Kode_Proyek',
						'a.DivisiID', 'b.SectionID' ) );
						
		return $this->db->get()->row();
	}

	private function _insert_cashier( $No_Bukti )
	{
		$_get_cashier = $this->_get_cashier( $No_Bukti );
		$prepare = array(
			'Relasi_ID' => 1,
			'Currency_ID' => $_get_cashier->Currency_ID,
            'HisCurrency_ID' => $this->Hiscurrency,
			'Transaksi_Date' => $_get_cashier->Tgl_Transaksi,
			'No_Bukti' => $_get_cashier->No_Bukti,
			'Kode_Transfer' => 'GC',
			'Tgl_Update' => date('Y-m-d'),
			'Debit' => $_get_cashier->Nilai,
			'Kredit' => $_get_cashier->Nilai,
			'Nilai_Tukar' => $_get_cashier->Nilai_Tukar,
			'User_ID' => $this->user->User_ID,
			'Integrasi' => 1,
			'Type_Jurnal' => 1,
			'Kode_Proyek' => $_get_cashier->Kode_Proyek,
			'DivisiID' => $_get_cashier->DivisiID,
			'Customer_ID' => $_get_cashier->Customer_ID,
		);
		
		$this->db->insert("TBJ_Transaksi", $prepare);
		$this->db->update("GC_trGeneralCashier", array("Posted" => 1), array("No_Bukti" => $No_Bukti ));
	}
		
	private function _insert_cashier_detail( $No_Bukti )
	{
		
		$this->db->query(" 
					INSERT INTO Tbj_Transaksi_Detail 
						(No_Bukti, Akun_ID, Debit, Kredit, Keterangan,Kode_Proyek,DivisiID,SectionID) 
					  
					SELECT  b.No_Bukti, a.Akun_ID, a.Debet, a.Kredit, a.Keterangan,a.Kode_Proyek,
							a.DivisiID,a.SectionID 
					FROM GC_trGeneralCashierDetail a 
						INNER JOIN GC_trGeneralCashier b ON a.No_Bukti = b.No_Bukti
					WHERE  b.No_Bukti = '{$No_Bukti}' "
				);
	}

	private function _insert_header( $No_Bukti, $source )
	{
		
		$_get_data = call_user_func( array( $this, "_get_{$source}" ), $No_Bukti );
		//$_get_data = $this->_get_{ $source }( $No_Bukti );
		
		$prepare = array(
			'Relasi_ID' => 0,
			'Currency_ID' => $_get_data->Currency_ID,
			'HisCurrency_ID' => $this->HisCurrency_ID,
			'Transaksi_Date' => $_get_data->Tgl_Transaksi,
			'No_Bukti' => $_get_data->No_Bukti,
			'Kode_Transfer' => 'AR',
			'Tgl_Update' => $_get_data->Tgl_Update,
			'Debit' => $_get_data->Nilai,
			'Kredit' => $_get_data->Nilai,
			'Nilai_Tukar' => $_get_data->Nilai_Tukar,
			'User_ID' => $this->user->User_ID,
			'Integrasi' => 1,
			'Type_Jurnal' => 1,
			'Keterangan' => $_get_data->Keterangan,
			'Kode_Proyek' => $_get_data->Kode_Proyek,
			'DivisiID' => $_get_data->DivisiID,
			'Customer_ID' => $_get_data->Customer_ID
		);
		
		$this->db->insert("TBJ_Transaksi", $prepare);
		$this->_header= $_get_data;
	}
	
	// insert factur detail
	private function _insert_detail_case_201()
	{
		
		$this->db->query("
				INSERT TBJ_Transaksi_Detail 
					(No_Bukti, Akun_ID, Debit, Kredit, Keterangan,Kode_Proyek,DivisiID,SectionID) 
				SELECT 
					a.No_Faktur AS No_Bukti,Akun_ID,
					'Debit'=
						CASE 
							WHEN Pos='K' then 0 
							WHEN Pos='D' then abs(Harga_Transaksi) 
						END,
			   		'Kredit'= 
						Case
							WHEN Pos='D' then 0 
							WHEN Pos='K' then abs(Harga_Transaksi)
						END,
					a.Keterangan,
					b.Kode_proyek,
					b.DivisiID,
					a.SectionID 
				FROM AR_trFakturDetail a 
					INNER JOIN AR_trFaktur b on a.No_Faktur = b.No_Faktur 
				WHERE a.No_Faktur='{$this->_header->No_Bukti}'
			");
			
		$this->db->update('AR_trFaktur', array("Posted" => 1), array("No_Faktur" => $this->_header->No_Bukti));
	}
	
	// Adjustmen penambahan piutang
	private function _insert_detail_case_202()
	{
		$prepare_debit = array(
			'No_Bukti' => $this->_header->No_Bukti,
			'Akun_ID' => $this->_header->Akun_ID,
			'Debit' =>  abs($this->_header->Nilai),
			'Kredit' => 0,
			'Keterangan' => $this->_header->Keterangan,
			'Kode_Proyek' => $this->_header->Kode_Proyek,
			'DivisiID' => $this->_header->DivisiID,
			'SectionID'  => $this->_header->SectionID
		);

		$prepare_credit = array(
			'No_Bukti' => $this->_header->No_Bukti,
			'Akun_ID' => config_item('Rekening Penambahan Piutang'),
			'Debit' =>  0,
			'Kredit' => abs($this->_header->Nilai),
			'Keterangan' => $this->_header->Keterangan,
			'Kode_Proyek' => $this->_header->Kode_Proyek,
			'DivisiID' => $this->_header->DivisiID,
			'SectionID'  => $this->_header->SectionID
		);
		
		$this->db->insert("TBJ_Transaksi_Detail", $prepare_debit);
		$this->db->insert("TBJ_Transaksi_Detail", $prepare_credit);
		
		$this->db->update('AR_trInvoice', array("Posted" => 1), array("No_Invoice" => $this->_header->No_Bukti));
		$this->db->update('AR_trFaktur', array("Posted" => 1), array("No_Faktur" => $this->_header->No_Bukti));
	
	}
	
	// Adjustmen pengurangan piutang
	private function _insert_detail_case_203()
	{
		$prepare_debit = array(
			'No_Bukti' => $this->_header->No_Bukti,
			'Akun_ID' => config_item('Rekening Pengurangan Piutang'),
			'Debit' =>  abs($this->_header->Nilai),
			'Kredit' => 0,
			'Keterangan' => $this->_header->Keterangan,
			'Kode_Proyek' => $this->_header->Kode_Proyek,
			'DivisiID' => $this->_header->DivisiID,
			'SectionID'  => $this->_header->SectionID
		);

		$prepare_credit = array(
			'No_Bukti' => $this->_header->No_Bukti,
			'Akun_ID' => $this->_header->Akun_ID,
			'Debit' =>  0,
			'Kredit' => abs($this->_header->Nilai),
			'Keterangan' => $this->_header->Keterangan,
			'Kode_Proyek' => $this->_header->Kode_Proyek,
			'DivisiID' => $this->_header->DivisiID,
			'SectionID'  => $this->_header->SectionID
		);
		
		$this->db->insert("TBJ_Transaksi_Detail", $prepare_debit);
		$this->db->insert("TBJ_Transaksi_Detail", $prepare_credit);
		
		$this->db->update('AR_trInvoice', array("Posted" => 1), array("No_Invoice" => $this->_header->No_Bukti));
		$this->db->update('AR_trFaktur', array("Posted" => 1), array("No_Faktur" => $this->_header->No_Bukti));
	}
	
	// Nota Debit
	private function _insert_detail_case_205()
	{		
		// insert Debit
		$this->db->query("
			INSERT INTO TBJ_Transaksi_Detail
				(No_Bukti, Akun_ID, Debit, Kredit, Keterangan, Kode_Proyek, DivisiID, SectionID)
			SELECT a.No_Invoice, c.Akun_ID, a.Debet, a.Kredit, 'Penyesuaian ' + a.No_Faktur, 
				b.Kode_Proyek , b.DivisiID,'". config_item('SectionIDCorporate') ."' 
			FROM AR_trInvoiceFaktur a 
				INNER JOIN AR_trFaktur b ON a.No_Faktur = b.No_Faktur 	
				INNER JOIN AR_mTypePiutang c ON b.JenisPiutang_ID = c.TypePiutang_ID  
			WHERE a.No_Invoice='{$this->_header->No_Bukti}'
		");
		
		$prepare_credit = array(
			'No_Bukti' => $this->_header->No_Bukti,
			'Akun_ID' => $this->_header->Akun_ID2,
			'Debit' =>  0,
			'Kredit' => abs($this->_header->Nilai),
			'Keterangan' => $this->_header->Keterangan,
			'Kode_Proyek' => $this->_header->Kode_Proyek,
			'DivisiID' => $this->_header->DivisiID,
			'SectionID'  => $this->_header->SectionID
		);
	
		// insert Kredit
		$this->db->insert("TBJ_Transaksi_Detail", $prepare_credit);	
			
		$this->db->update('AR_trInvoice', array("Posted" => 1), array("No_Invoice" => $this->_header->No_Bukti));
                                
	}

	// Nota Kredit
	private function _insert_detail_case_206()
	{		
		$prepare_debit = array(
			'No_Bukti' => $this->_header->No_Bukti,
			'Akun_ID' => $this->_header->Akun_ID2,
			'Debit' => abs($this->_header->Nilai),
			'Kredit' => 0,
			'Keterangan' => $this->_header->Keterangan,
			'Kode_Proyek' => $this->_header->Kode_Proyek,
			'DivisiID' => $this->_header->DivisiID,
			'SectionID'  => $this->_header->SectionID
		);
	
		// insert Debit
		$this->db->insert("TBJ_Transaksi_Detail", $prepare_debit);	

		// Insert Kredit
		$this->db->query("
			INSERT INTO TBJ_Transaksi_Detail
				(No_Bukti, Akun_ID, Debit, Kredit, Keterangan, Kode_Proyek, DivisiID, SectionID)
			SELECT a.No_Invoice, c.Akun_ID, a.Debet, a.Kredit, 'Penyesuaian ' + a.No_Faktur, 
				b.Kode_Proyek , b.DivisiID,'". config_item('SectionIDCorporate') ."' 
			FROM AR_trInvoiceFaktur a 
				INNER JOIN AR_trFaktur b ON a.No_Faktur = b.No_Faktur 	
				INNER JOIN AR_mTypePiutang c ON b.JenisPiutang_ID = c.TypePiutang_ID  
			WHERE a.No_Invoice='{$this->_header->No_Bukti}'
		");
			
		$this->db->update('AR_trInvoice', array("Posted" => 1), array("No_Invoice" => $this->_header->No_Bukti));        
	}    
	
	// Retur Penjualan
	private function _insert_detail_case_207()
	{
		$prepare_debit = array(
			'No_Bukti' => $this->_header->No_Bukti,
			'Akun_ID' => config_item('Rekening Retur Penjualan'),
			'Debit' =>  abs( $this->_header->Nilai ),
			'Kredit' => 0,
			'Keterangan' => $this->_header->Keterangan,
			'Kode_Proyek' => $this->_header->Kode_Proyek,
			'DivisiID' => $this->_header->DivisiID,
			'SectionID'  => $this->_header->SectionID
		);
		
		$prepare_credit = array(
			'No_Bukti' => $this->_header->No_Bukti,
			'Akun_ID' => $this->_header->Akun_ID,
			'Debit' =>  0,
			'Kredit' => abs( $this->_header->Nilai ),
			'Keterangan' => $this->_header->Keterangan,
			'Kode_Proyek' => $this->_header->Kode_Proyek,
			'DivisiID' => $this->_header->DivisiID,
			'SectionID'  => $this->_header->SectionID
		);
		
		$this->db->insert("TBJ_Transaksi_Detail", $prepare_debit);
		$this->db->insert("TBJ_Transaksi_Detail", $prepare_credit);
		
		$this->db->update('AR_trInvoice', array("Posted" => 1), array("No_Invoice" => $this->_header->No_Bukti));
		
	}
	
	public function posting_data( $postings )
	{
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		
		$this->_before_posting();

		set_time_limit(0);
				
		$this->db->trans_begin();
			foreach ( $postings as $posting ):
				if ( $posting['JTransaksi_ID'] == 901 )
				{
					$this->_insert_cashier( $posting['No_Bukti'] ); // insert GC to GL
					$this->_insert_cashier_detail( $posting['No_Bukti'] ); // insert GC detail to GL
				} else {
					$this->_insert_header( $posting['No_Bukti'], $posting['source']); // insert AR to GL
					call_user_func( array( $this, "_insert_detail_case_".$posting['JTransaksi_ID'] )); // insert AR detail to GL
					//$this->_insert_detail_case_{$posting['JTransaksi_ID']}(); 
				}
				
				$activities_description = sprintf( "%s # %s", "POSTING PIUTANG TO GL.", $posting[ 'No_Bukti' ] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$this->user->User_ID} ,'".   $posting[ 'No_Bukti' ] ."','{$activities_description}','". $_SERVER['REMOTE_ADDR'] ."'");				

			endforeach;
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}
	
}