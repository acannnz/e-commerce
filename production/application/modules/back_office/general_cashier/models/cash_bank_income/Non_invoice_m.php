<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Non_invoice_m extends Public_Model
{
	public $table = 'GC_trGeneralCashier';
	public $table_detail = 'GC_trGeneralCashierDetail';
	public $primary_key = 'No_Bukti';
	public $foreign_key = 'No_Referensi';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array(
			'insert' => array(
				'No_Bukti' => array(
						'field' => 'No_Bukti',
						'label' => lang( 'cash_bank_income:evidence_number_label' ),
						'rules' => 'required'
					),
				'Tgl_Transaksi' => array(
						'field' => 'Tgl_Transaksi',
						'label' => lang( 'cash_bank_income:date_label' ),
						'rules' => 'required'
					),
				'Type_Transaksi' => array(
						'field' => 'Type_Transaksi',
						'label' => lang( 'cash_bank_income:type_label' ),
						'rules' => 'required'
					),
				'Kredit' => array(
						'field' => 'Kredit',
						'label' => lang( 'cash_bank_income:pay_label' ),
						'rules' => 'required'
					),
				'AkunBG_ID' => array(
						'field' => 'AkunBG_ID',
						'label' => lang( 'cash_bank_income:account_label' ),
						'rules' => 'required'
					),
				'Keterangan' => array(
						'field' => 'Keterangan',
						'label' => lang( 'cash_bank_income:description_label' ),
						'rules' => 'required'
					),
				),
			'update' => array(
				'No_Bukti' => array(
						'field' => 'No_Bukti',
						'label' => lang( 'cash_bank_income:evidence_number_label' ),
						'rules' => 'required'
					),
				'Tgl_Transaksi' => array(
						'field' => 'Tgl_Transaksi',
						'label' => lang( 'cash_bank_income:date_label' ),
						'rules' => 'required'
					),
				'Type_Transaksi' => array(
						'field' => 'Type_Transaksi',
						'label' => lang( 'cash_bank_income:type_label' ),
						'rules' => 'required'
					),
				'AkunBG_ID' => array(
						'field' => 'AkunBG_ID',
						'label' => lang( 'cash_bank_income:account_label' ),
						'rules' => 'required'
					),
				'Keterangan' => array(
						'field' => 'Keterangan',
						'label' => lang( 'cash_bank_income:description_label' ),
						'rules' => 'required'
					),			
				)
			);
		
		parent::__construct();
	}
	
	public function get_row( $No_Bukti )
	{		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			g.Akun_ID, 
			g.Akun_No, 
			g.Akun_Name
			
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "{$this->table} a" )
			//->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
			->join("Mst_Akun g", "a.AkunBG_ID = g.Akun_ID", "LEFT OUTER" )
			/*->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("mUser f", "a.User_ID= f.User_ID", "LEFT OUTER" )*/
			->where("a.{$this->primary_key}", $No_Bukti )
			->get()
			;
			
		return ($query->num_rows() > 0) ? $query->row() : NULL;
				
	}
	
	#get_invoice_collection
	public function get_detail_collection( $No_Bukti )
	{		
		$db_select = <<<EOSQL
			a.Kredit, 
			a.Debet AS Debit, 
			a.Keterangan,
			b.Akun_ID,
			b.Akun_No,
			b.Akun_Name,
			c.SectionID,
			c.SectionName
EOSQL;

		$query = $this->db
					->select( $db_select )
					->from("{$this->table_detail} a")
					->join("Mst_Akun b", "a.Akun_ID = b.Akun_ID", "INNER" )
					->join("SIMmSection c", "a.SectionID = c.SectionID", "INNER" )
					->where("a.No_Bukti", $No_Bukti)
					->get();
			
		return ($query->num_rows() > 0) ? $query->result() : NULL;		
	}
	
	public function get_invoice_detail( $No_Invoice )
	{		
		$db_select = <<<EOSQL
			a.No_Faktur,
			a.No_Invoice,
			a.Keterangan,
			a.Sisa,
			a.Sisa AS Kredit,
			b.Akun_ID
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "AR_trFaktur a" )
			->join("AR_mTypePiutang b", "a.JenisPiutang_ID = b.TypePiutang_ID", "LEFT OUTER" )
			->where(array(
				"a.No_Invoice" => $No_Invoice,
			))
			->get()
			;
			
		return ($query->num_rows() > 0) ? $query->result() : NULL;		
	}

	public function get_general_cashier_factur_collection( $No_Invoice )
	{		
		$db_select = <<<EOSQL
			b.No_Faktur,
			b.No_Invoice,
			b.Keterangan,
			a.Sisa,
			a.Dibayar AS Kredit,
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "GC_trGeneralCashierDetailFaktur a" )
			->join("AR_trFaktur b", "a.NoFaktur = b.No_Faktur", "INNER" )
			->where(array(
				"b.No_Invoice" => $No_Invoice,
			))
			->get()
			;
			
		return ($query->num_rows() > 0) ? $query->result() : NULL;		
	}
	
	/*
		EXEC CekHisCurrency '2018-02-09'

		INSERT INTO GC_trGeneralCashier (Tgl_Transaksi, Type_Transaksi, No_Bukti, Instansi, Status_Batal, Pakai_Referensi, User_ID, Tgl_Update, Currency_ID, Supplier_ID,Job_Code,NIlai_tukar,Kode_Proyek,DivisiID,JenisTransaksiBank,AkunBG_ID,Keterangan,Debet,Kredit,SectionID) 
		VALUES ('2018-02-09' ,'BKM' ,'18/02/BKM/0003' ,'-' ,'0' ,0 ,1145 ,GETDATE() ,1 ,NULL,'',1,'9',9,'',2041,'Pendapatan cafetaria 09-02-2018',200000,0,'SEC078')
		EXEC InsertUserActivities '2018-02-09','2018-02-09 18:29:30',1145,'18/02/BKM/0003','INSERT BBMNV.#18/02/BKM/0003',''
		
		INSERT INTO GC_trGeneralCashierDetail (no_Bukti,No_Kartu_Pembayaran, Tgl_Tempo, Keterangan, Debet, Kredit, No_Referensi, Akun_ID, TypePembayaran_ID,Kode_proyek,DivisiID,SectionID) 
		VALUES ('18/02/BKM/0003','' ,NULL ,'Pendapatan cafetaria 09-02-2018 (pagi)' ,0 ,200000 ,'-' ,2391 ,NULL,'9',9,'SEC029')
	*/

	public function create_data( $cashier, $detail )
	{
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");

		$cashier_detail = array();

		$cashier_debit = $cashier_kredit = 0;
		foreach($detail as $row)
		{

			// prepare cashier detail
			$cashier_detail[] = array(
				$this->primary_key  => $cashier[ $this->primary_key ],
				$this->foreign_key => "-",
				"Debet" => $row['Debit'],
				"Kredit" => $row['Kredit'],
				"Akun_ID" => $row["Akun_ID"],
				"Keterangan" => $cashier['Keterangan'],
				"Kode_Proyek" => $cashier["Kode_Proyek"],
				"DivisiID" => $cashier["DivisiID"],
				"No_Kartu_Pembayaran" => '',
				"TypePembayaran_ID" => NULL,
				"Tgl_Tempo" => NULL,
				"SectionID" => $row['SectionID'],
			);
			
			$cashier_debit = $cashier_debit + $row['Debit'];
			$cashier_kredit = $cashier_kredit + $row['Kredit'];
		}	
		
		$cashier['Debet'] = $cashier_kredit;
		$cashier['Kredit'] = $cashier_debit;
		
		$this->db->trans_begin();
			$this->db->insert( $this->table, $cashier);
			
			$activities_description = sprintf( "%s %sNV # %s #", "INSERT ", $cashier[ 'Type_Transaksi' ] , $cashier[ $this->primary_key ] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $cashier[ $this->primary_key ] ."','{$activities_description}','{$this->table}'");				
			
			foreach ( $cashier_detail as $row )
			{
				$this->db->insert( $this->table_detail, $row);

				$activities_description = sprintf( "%s %sNV # %s ", "INSERT DETAIL.", $cashier[ 'Type_Transaksi' ], $cashier[ $this->primary_key ] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $row[ $this->foreign_key ] ."','{$activities_description}','{$this->table_detail}'");				
			}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}
	
	public function update_data( $cashier, $detail )
	{
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");

		$cashier_detail = array();

		$cashier_debit = $cashier_kredit = 0;
		foreach($detail as $row)
		{

		// prepare cashier detail
			$cashier_detail[] = array(
				$this->primary_key  => $cashier[ $this->primary_key ],
				$this->foreign_key => "-",
				"Debet" => $row['Debit'],
				"Kredit" => $row['Kredit'],
				"Akun_ID" => $row["Akun_ID"],
				"Keterangan" => $cashier['Keterangan'],
				"Kode_Proyek" => 1,
				"DivisiID" => 9,
				"No_Kartu_Pembayaran" => '',
				"TypePembayaran_ID" => NULL,
				"Tgl_Tempo" => NULL,
				"SectionID" => $row['SectionID'],
			);

			$cashier_debit = $row['Debit'];
			$cashier_kredit = $row['Kredit'];
		}	
		
		$cashier['Debet'] = $cashier_kredit;
		$cashier['Kredit'] = $cashier_debit;
		
		$this->db->trans_begin();
			$this->db->update( $this->table, $cashier, array( $this->primary_key  => $cashier[ $this->primary_key ] ));
			
			$activities_description = sprintf( "%s %sNV # %s #", "UPDATE ", $cashier[ 'Type_Transaksi' ] , $cashier[ $this->primary_key ] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $cashier[ $this->primary_key ] ."','{$activities_description}','{$this->table}'");				
			
			$this->db->delete( $this->table_detail, array( $this->primary_key => $cashier[ $this->primary_key ] ) );
			
			foreach ( $cashier_detail as $row )
			{
				$this->db->insert( $this->table_detail, $row);

				$activities_description = sprintf( "%s %sNV # %s ", "UPDATE DETAIL.", $cashier[ 'Type_Transaksi' ], $cashier[ $this->primary_key ] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $row[ $this->foreign_key ] ."','{$activities_description}','{$this->table_detail}'");				
			}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;		
	}
	
	public function cancel_data( $cashier )
	{
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		
		$this->db->trans_begin();
		
			$this->db->update( $this->table, array("Status_Batal" => 1), array( $this->primary_key  => $cashier->No_Bukti ));

			$activities_description = sprintf( "%s %sNV # %s #", "CANCEL ", $cashier->Type_Transaksi, $cashier->No_Bukti );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'". $cashier->No_Bukti ."','{$activities_description}','{$this->table}'");				
			
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
	}
}