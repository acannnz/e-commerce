<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posting_m extends Public_Model
{
	public $table = 'GC_trGeneralCashier';
	public $table_detail = 'GC_trGeneralCashierDetail';
	public $primary_key = 'No_Bukti';
	
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
		$this->load->helper('general_cashier');
		$this->HisCurrency_ID = general_cashier_helper::get_his_currency( date('Y-m-d') ) ;		

	}
		
	private function _get_header( $No_Bukti )
	{
		$db_select = <<<EOSQL
			ABS(SUM(b.Kredit - b.Debet)) AS Nilai, 
			SUBSTRING( a.No_Bukti, 1, 50 ) as No_Bukti, 
			a.Tgl_Transaksi,  
			a.Type_Transaksi,
			a.Status_Batal,
			a.Posted,
			a.Currency_ID,
			a.Keterangan,
			a.Nilai_Tukar,
			a.Kode_Proyek,
			a.Supplier_ID,
			a.Customer_ID,
			a.DivisiID,
EOSQL;
		
		$this->db
			->select( $db_select )
			->from( "{$this->table} a" )
			->join( "{$this->table_detail} b", "a.No_Bukti = b.No_Bukti", "INNER" )
			->where( "a.No_Bukti", $No_Bukti )
			->group_by(array(
				'a.No_Bukti',
				'a.Tgl_Transaksi', 
				'a.Type_Transaksi',
				'a.Status_Batal',
				'a.Posted',
				'a.Currency_ID',
				'a.Keterangan',
				'a.Nilai_Tukar',
				'a.Kode_Proyek',
				'a.Supplier_ID',
				'a.Customer_ID',
				'a.DivisiID'
			));
						
		return $this->db->get()->row();
	}
		
	private function _insert_header( $No_Bukti )
	{
		
		$_get_data = $this->_get_header( $No_Bukti );
		 		
		$prepare = array(
			'Relasi_ID' => 0,
			'Currency_ID' => $_get_data->Currency_ID,
			'HisCurrency_ID' => $this->HisCurrency_ID,
			'Transaksi_Date' => $_get_data->Tgl_Transaksi,
			'No_Bukti' => $_get_data->No_Bukti,
			'Kode_Transfer' => 'GC',
			'Tgl_Update' => date('Y-m-d'),
			'Debit' => $_get_data->Nilai,
			'Kredit' => $_get_data->Nilai,
			'Nilai_Tukar' => $_get_data->Nilai_Tukar,
			'User_ID' => $this->user->User_ID,
			'Integrasi' => 1,
			'Type_Jurnal' => 1,
			'Keterangan' => $_get_data->Keterangan,
			'Kode_Proyek' => $_get_data->Kode_Proyek,
			'DivisiID' => $_get_data->DivisiID,
			'Customer_ID' => $_get_data->Customer_ID,
			'Supplier_ID' => $_get_data->Supplier_ID
		);
		
		$this->db->insert("TBJ_Transaksi", $prepare);
		$this->_header= $_get_data;
	}
	
	// insert factur detail
	private function _insert_detail()
	{	
		$this->db->query("
			INSERT TBJ_Transaksi_Detail 
					(No_Bukti, Akun_ID, Debit, Kredit, Keterangan, Kode_Proyek, DivisiID, SectionID) 
				SELECT  
					a.No_Bukti, b.Akun_ID, b.Debet, b.Kredit, b.Keterangan, 
					b.Kode_Proyek, b.DivisiID, b.SectionID 
				FROM GC_trGeneralCashierDetail b 
					INNER JOIN GC_trGeneralCashier a ON b.No_Bukti = a.No_Bukti
				WHERE  a.No_Bukti = '{$this->_header->No_Bukti}'
			UNION
				SELECT  
					a.No_Bukti, a.AkunBG_ID, SUM(a.Debet), 0, a.Keterangan,
					a.Kode_Proyek, a.DivisiID, a.SectionID 
				FROM GC_trGeneralCashier a
				WHERE a.Debet <> 0 AND a.No_Bukti = '{$this->_header->No_Bukti}'
				GROUP BY a.No_Bukti, a.AkunBG_ID, a.Keterangan, a.Kode_Proyek, a.DivisiID, a.SectionID
			UNION
				SELECT  
					a.No_Bukti, a.AkunBG_ID, 0, SUM(a.Kredit), a.Keterangan, 
					a.Kode_Proyek, a.DivisiID, a.SectionID 
				FROM GC_trGeneralCashier a
				WHERE a.Kredit <> 0 and   (a.No_Bukti = '{$this->_header->No_Bukti}')
				GROUP BY a.No_Bukti, a.AkunBG_ID, a.Keterangan, a.Kode_Proyek, a.DivisiID, a.SectionID
			");			
	}

	// insert factur detail
	private function _insert_detail_rkk_rkd()
	{	
	
		$this->db->query("
				INSERT INTO Tbj_Transaksi_Detail 
					(No_Bukti, Akun_ID, Debit, Kredit, Keterangan,Kode_Proyek,DivisiID,SectionID)
					
					SELECT 
						c.NoBukti,AR_mTypePiutang.Akun_ID,
						0 AS Debet, SUM(c.Dibayar) AS Kredit, '{$this->_header->Keterangan} -> '+ d.No_Faktur , 
						d.Kode_Proyek, d.DivisiID, b.SectionID 
					FROM GC_trGeneralCashier a 
						INNER JOIN GC_trGeneralCashierDetail b ON a.No_Bukti = b.No_Bukti 
						INNER JOIN GC_trGeneralCashierDetailFaktur c ON b.No_Bukti = c.NoBukti AND b.No_Referensi = c.No_Referensi 
						INNER JOIN AR_trFaktur d ON c.NoFaktur = d.No_Faktur
						INNER JOIN AR_mTypePiutang e on d.JenisPiutang_ID = e.TypePiutang_ID 
					WHERE  a.No_Bukti = '{$this->_header->No_Bukti}'
					GROUP BY c.NoBukti, e.Akun_ID, c.Keterangan , d.No_Faktur, d.Kode_Proyek, d.DivisiID, b.SectionID 
						
				UNION
							
					SELECT c.NoBukti, e.Akun_ID, SUM(c.Dibayar)  AS Debet, 0 AS Kredit,
							'{$this->_header->Keterangan} -> '+ d.No_Faktur  , d.Kode_Proyek, d.DivisiID, b.SectionID 
					FROM GC_trGeneralCashier a 
						INNER JOIN GC_trGeneralCashierDetail b on a.No_Bukti = b.No_Bukti 
						INNER JOIN GC_trGeneralCashierDetailFaktur c on b.No_Bukti = c.NoBukti and b.No_Referensi = c.No_Referensi 
						INNER JOIN AP_trFaktur d on c.NoFaktur = d.No_Faktur 
						INNER JOIN AP_mTypeHutang e on d.JenisHutang_ID = e.TypeHutang_ID 
					WHERE  a.No_Bukti = '{$this->_header->No_Bukti}'
					GROUP BY   c.NoBukti, e.Akun_ID, c.Keterangan, d.No_Faktur, d.Kode_Proyek, d.DivisiID, b.SectionID
			
				UNION 
				
					SELECT  b.No_Bukti, b.AkunBG_ID, 0, SUM(a.Debet), a.Keterangan, a.Kode_Proyek, a.DivisiID, a.SectionID 
						FROM GC_trGeneralCashierDetail a
						INNER JOIN GC_trGeneralCashier b ON a.No_Bukti = b.No_Bukti
					WHERE a.Debet <> 0 AND b.No_Bukti = '{$this->_header->No_Bukti}'
					GROUP BY b.No_Bukti, b.AkunBG_ID, a.Keterangan, a.Kode_Proyek, a.DivisiID, a.SectionID
				
				UNION
				
					SELECT  b.No_Bukti, b.AkunBG_ID, SUM(a.Kredit), 0, a.Keterangan, a.Kode_Proyek, a.DivisiID, a.SectionID 
						FROM GC_trGeneralCashierDetail a 
						INNER JOIN GC_trGeneralCashier b ON a.No_Bukti = b.No_Bukti
					WHERE a.Kredit <> 0 AND   b.No_Bukti = '{$this->_header->No_Bukti}'
					GROUP BY b.No_Bukti, b.AkunBG_ID, a.Keterangan, a.Kode_Proyek, a.DivisiID, a.SectionID
			");			
	}
	
	private function _insert_detail_cash_bank()
	{	

		$this->db->query("
				INSERT INTO Tbj_Transaksi_Detail 
					( No_Bukti, Akun_ID, Debit, Kredit, Keterangan, Kode_Proyek, DivisiID, SectionID)
					
					SELECT c.NoBukti, e.Akun_ID, 0 AS Debet, SUM(c.Dibayar) AS Kredit, c.Keterangan + ' -> ' + d.No_Faktur , 
						d.Kode_Proyek, d.DivisiID, b.SectionID 
					FROM GC_trGeneralCashier a 
						INNER JOIN GC_trGeneralCashierDetail b ON a.No_Bukti = b.No_Bukti 
						INNER JOIN GC_trGeneralCashierDetailFaktur c ON b.No_Bukti = c.NoBukti AND b.No_Referensi = c.No_Referensi 
						INNER JOIN AR_trFaktur d on c.NoFaktur = d.No_Faktur 
						INNER JOIN AR_mTypePiutang e on d.JenisPiutang_ID = e.TypePiutang_ID 
					WHERE  a.No_Bukti = '{$this->_header->No_Bukti}'
					GROUP BY   c.NoBukti, e.Akun_ID, c.Keterangan , d.No_Faktur, d.Kode_Proyek, d.DivisiID, b.SectionID
					
				UNION
						
					SELECT c.NoBukti, e.Akun_ID, SUM(c.Dibayar) AS Debet, 0 AS Kredit, c.Keterangan +' -> '+ d.No_Faktur , d.Kode_Proyek, d.DivisiID, b.SectionID
					FROM GC_trGeneralCashier a 
						INNER JOIN GC_trGeneralCashierDetail b ON a.No_Bukti = b.No_Bukti
						INNER JOIN GC_trGeneralCashierDetailFaktur c ON b.No_Bukti = c.NoBukti AND b.No_Referensi = c.No_Referensi 
						INNER JOIN AP_trFaktur d on c.NoFaktur = d.No_Faktur 
						INNER JOIN AP_mTypeHutang e on d.JenisHutang_ID = e.TypeHutang_ID 
					WHERE  a.No_Bukti = '{$this->_header->No_Bukti}'
					GROUP BY c.NoBukti, e.Akun_ID, c.Keterangan , d.No_Faktur, d.Kode_Proyek, d.DivisiID, b.SectionID
			
				UNION
		
					SELECT  b.No_Bukti, b.AkunBG_ID, 0, SUM( a.Debet), a.Keterangan, a.Kode_Proyek, a.DivisiID, a.SectionID 
					FROM GC_trGeneralCashierDetail a
						INNER JOIN GC_trGeneralCashier b ON a.No_Bukti = b.No_Bukti
					WHERE a.Debet <> 0 AND b.No_Bukti = '{$this->_header->No_Bukti}'
					GROUP BY b.No_Bukti, b.AkunBG_ID, a.Keterangan, a.Kode_Proyek, a.DivisiID, a.SectionID
		
				UNION
		
					SELECT  b.No_Bukti, b.AkunBG_ID, SUM(a.Kredit), 0, a.Keterangan, a.Kode_Proyek, a.DivisiID, a.SectionID 
					FROM GC_trGeneralCashierDetail a 
						INNER JOIN GC_trGeneralCashier b ON a.No_Bukti = b.No_Bukti
					WHERE a.Kredit <> 0 AND b.No_Bukti = '{$this->_header->No_Bukti}'
					GROUP BY b.No_Bukti, b.AkunBG_ID, a.Keterangan, a.Kode_Proyek, a.DivisiID, a.SectionID
			");			
	}

	public function posting_data( $postings )
	{
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		
		$this->db->reset_query();
		$this->_before_posting();
				
		$this->db->trans_begin();
			foreach ( $postings as $posting ):
			
				$this->_insert_header( $posting['No_Bukti']); 
				
				if( ! general_cashier_helper::check_general_cashier_detail_factur($posting['No_Bukti']) )
				{
					$this->_insert_detail();
						
				} else 
				{				
					if ( in_array( $this->_header->Type_Transaksi, array('RKD', 'RKK') ))
					{
						$this->_insert_detail_rkk_rkd();
						
					} else 
					{
						$this->_insert_detail_cash_bank();
					}
				}
            	
				$this->db->update('GC_trGeneralCashier', array( "Posted" => 1 ), array("No_Bukti" => $posting['No_Bukti']));
				$this->db->update('GC_trPettyCash', array( "Posted" => 1 ), array("No_Bukti" => $posting['No_Bukti']));
            
				$activities_description = sprintf( "%s # %s", "POSTING GENERAL CASHIER TO GL.", $posting[ 'No_Bukti' ] );			
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
	
	public function posting_cancel( $postings )
	{
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		
		$this->_before_posting();
				
		$this->db->trans_begin();
			foreach ( $postings as $posting ):
			
				$this->db->delete("TBJ_Transaksi_Detail", array('No_Bukti' => $posting['No_Bukti']));
				$this->db->delete("TBJ_Transaksi", array('No_Bukti' => $posting['No_Bukti']));
            
				$this->db->update('GC_trGeneralCashier', array( "Posted" => 0 ), array("No_Bukti" => $posting['No_Bukti']));
				$this->db->update('GC_trPettyCash', array( "Posted" => 0 ), array("No_Bukti" => $posting['No_Bukti']));

				$activities_description = sprintf( "%s # %s", "POSTING CANCEL GENERAL CASHIER TO GL.", $posting[ 'No_Bukti' ] );			
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