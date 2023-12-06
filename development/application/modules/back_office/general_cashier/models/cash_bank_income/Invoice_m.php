<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_m extends Public_Model
{
	public $table = 'GC_trGeneralCashier';
	public $table_detail = 'GC_trGeneralCashierDetail';
	public $primary_key = 'No_Bukti';
	public $foreign_key = 'No_Referensi';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
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
			));
		
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
	public function get_invoice_collection( $No_Bukti )
	{		
		$db_select = <<<EOSQL
			b.Tgl_Invoice, 
			b.No_Invoice, 
			a.Kredit, 
			a.Debet AS Debit, 
			a.NilaiAsal AS Sisa, 
			(a.NilaiAsal - a.Kredit) AS Saldo,
			b.Keterangan,
			b.JenisPiutang_ID,
			b.Akun_ID,
			c.Nama_Customer
EOSQL;

		$query = $this->db
					->select( $db_select )
					->from("{$this->table_detail} a")
					->join("AR_trInvoice b", "a.No_Referensi = b.No_Invoice", "INNER" )
					->join("mCustomer c", "b.Customer_ID = c.Customer_ID", "LEFT OUTER" )
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
		INSERT INTO GC_trGeneralCashier (Tgl_Transaksi, No_Bukti, Customer_ID, Currency_ID,Pakai_Referensi,Type_Transaksi,Tgl_Update,User_ID,Nilai_tukar,Kode_Proyek,DIvisiID,AkunBG_ID,Keterangan,Debet,Kredit,SectionID) 
		VALUES ('2018-02-08' ,'18/02/BKM/0001' ,NULL ,1,1,'BKM','2018-02-08',1145,1,'',0,2039,'tes',100000,0,'SEC078')
		EXEC InsertUserActivities '2018-02-08','2018-02-08 10:07:54',1145,'18/02/BKM/0001',
		'INSERT BBM.#18/02/BKM/0001',''

		INSERT INTO GC_trGeneralCashierDetail (No_Bukti, NilaiAsal, Debet, Kredit, No_Referensi, akun_ID,Keterangan,Kode_Proyek,DivisiID,Tgl_Tempo,SectionID) 
		VALUES ('18/02/BKM/0001' ,100000 ,0 ,100000 ,'2018/01/INV/002' ,2468,'--->2018/01/INV/002','1',9,'2018-02-08','SEC078')

		Insert into AR_trInvoiceDetail(No_Invoice,No_Bukti,JTransaksi_ID,Debit,Kredit,Keterangan,Tgl_transaksi,SectionID)
		Values('2018/01/INV/002','18/02/BKM/0001',204,0,100000,'-','2018-02-08','SEC078')
		Update AR_trInvoice set Sudah_Dibuatkan_Bukti=1, Sisa=sisa- 100000 where No_Invoice='2018/01/INV/002'
		EXEC InsertUserActivities '2018-02-08','2018-02-08 10:07:54',1145,'18/02/BKM/0001','INSERT BBM DETAIL.#18/02/BKM/0001#2018/01/INV/002#0',''

		INSERT INTO GC_trGeneralCashierDetailFaktur (No_Referensi, Keterangan, NoFaktur, Sisa, Dibayar, NoBukti, Akun_ID) VALUES ('2018/01/INV/002' ,'--->2018/01/INV/002' ,'2018/01/FAR/002' ,50000 ,50000 ,'18/02/BKM/0001' ,2468)
		UPDATE AR_trFaktur set Sisa=Sisa-50000 where No_Faktur='2018/01/FAR/002'
		INSERT INTO AR_trInvoiceFaktur(No_Bukti,No_Invoice,No_Faktur,Sisa,Debet,Kredit)VALUES('18/02/BKM/0001','2018/01/INV/002','2018/01/FAR/002',0,0,50000)
		EXEC InsertUserActivities '2018-02-08','2018-02-08 10:07:54',1145,'18/02/BKM/0001','INPUT BBM DETAIL FAKTUR.#18/02/BKM/0001#2018/01/INV/002#2018/01/FAR/002#50000',''

		INSERT INTO GC_trGeneralCashierDetailFaktur (No_Referensi, Keterangan, NoFaktur, Sisa, Dibayar, NoBukti, Akun_ID) VALUES ('2018/01/INV/002' ,'--->2018/01/INV/002' ,'2018/01/FAR/003' ,50000 ,50000 ,'18/02/BKM/0001' ,2468)
		UPDATE AR_trFaktur set Sisa=Sisa-50000 where No_Faktur='2018/01/FAR/003'
		INSERT INTO AR_trInvoiceFaktur(No_Bukti,No_Invoice,No_Faktur,Sisa,Debet,Kredit)VALUES('18/02/BKM/0001','2018/01/INV/002','2018/01/FAR/003',0,0,50000)
		EXEC InsertUserActivities '2018-02-08','2018-02-08 10:07:54',1145,'18/02/BKM/0001','INPUT BBM DETAIL FAKTUR.#18/02/BKM/0001#2018/01/INV/002#2018/01/FAR/003#50000',''
	*/
	public function create_data( $cashier, $invoice, $factur )
	{
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");

		$cashier_detail = array();
		$invoice_detail = array();
		$invoice_factur = array();

		$cashier_debit = $cashier_kredit = 0;
		foreach($invoice as $row)
		{
			// prepare cashier detail
			$cashier_detail[] = array(
				$this->primary_key  => $cashier[ $this->primary_key ],
				$this->foreign_key => $row[ 'No_Invoice' ],
				"NilaiAsal" => $row['Sisa'],
				"Debet" => $row['Debit'],
				"Kredit" => $row['Kredit'],
				"Akun_ID" => $row["Akun_ID"],
				"Keterangan" => $cashier['Keterangan'] ." ---> ". $row[ 'No_Invoice' ],
				"Kode_Proyek" => $cashier["Kode_Proyek"],
				"DivisiID" => $cashier["DivisiID"],
				"Tgl_Tempo" => $cashier['Tgl_Transaksi'],
				"SectionID" => config_item('SectionIDCorporate'),
			);
			
			// prepare mutation invoice detail
			$invoice_detail[] = array(
				"No_Invoice"  => $row[ "No_Invoice" ],
				$this->primary_key => $cashier[ $this->primary_key ],
				"NilaiAsal" => 0,
				"Debit" => $row['Debit'],
				"Kredit" => $row['Kredit'],
				"Keterangan" => "-",
				"Tgl_transaksi" => $cashier['Tgl_Transaksi'],
				"JTransaksi_ID" => 204,
				"SectionID" => config_item('SectionIDCorporate'),
			);
			
			// prepare cashier factur
			foreach($factur[ $row[ 'No_Invoice' ] ] as $val)
			{
				$invoice_factur[] = array(
					"NoBukti"  => $cashier[ $this->primary_key ],
					$this->foreign_key => $row[ "No_Invoice" ],
					"NoFaktur" => $val['No_Faktur'],
					"Sisa" => $val['Sisa'],
					"Dibayar" => $val['Kredit'],
					"Keterangan" => $val["Keterangan"] ." --> " . $cashier["Keterangan"],
					"Akun_ID" => $val['Akun_ID']
				);
			}
			
			$cashier_debit = $cashier_debit + $row['Debit'];
			$cashier_kredit = $cashier_kredit + $row['Kredit'];
		}	
		
		$cashier['Debet'] = $cashier_kredit;
		$cashier['Kredit'] = $cashier_debit;
		
		$this->db->trans_begin();
			$this->db->insert( $this->table, $cashier);
			
			$activities_description = sprintf( "%s %s # %s #", "INSERT ", $cashier[ 'Type_Transaksi' ] , $cashier[ $this->primary_key ] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $cashier[ $this->primary_key ] ."','{$activities_description}','{$this->table}'");				
			
			foreach ( $cashier_detail as $row )
			{
				$this->db->insert( $this->table_detail, $row);

				$activities_description = sprintf( "%s %s # %s # %s ", "INSERT DETAIL.", $cashier[ 'Type_Transaksi' ], $cashier[ $this->primary_key ], $row[ $this->foreign_key ] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $row[ $this->foreign_key ] ."','{$activities_description}','{$this->table_detail}'");				
			}
			
			foreach ( $invoice_detail as $row )
			{
				$this->db->insert( "AR_trInvoiceDetail", $row);

				$this->db->set( "Sisa", "Sisa - ". $row['Kredit'], FALSE)
						->set( "Sudah_Dibuatkan_Bukti", 1)
						->where( "No_Invoice",  $row[ "No_Invoice" ] )
						->update( "AR_trInvoice" );
				
				$activities_description = sprintf( "%s (%s) # %s # %s", "INSERT INVOICE DETAIL.", $cashier[ 'Type_Transaksi' ], $row[ 'No_Invoice' ], $cashier[ $this->primary_key ] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $row[ 'No_Invoice' ] ."','{$activities_description}','AR_trInvoiceDetail'");				
			}
			
			foreach ( $invoice_factur as $row )
			{
				$this->db->insert( "GC_trGeneralCashierDetailFaktur", $row);

				$this->db->set( "Sisa", "Sisa - ". $row['Dibayar'], FALSE)
						->where( "No_Faktur",  $row['NoFaktur'] )
						->update( "AR_trFaktur" );
						
				$insert_invoice_factur = [
					"No_Bukti"  => $row['NoBukti'],
					"No_Invoice" => $row[ $this->foreign_key ],
					"No_Faktur" => $row['NoFaktur'],
					"Sisa" => $row['Sisa'],
					"Debet" => 0,
					"Kredit" => $row['Dibayar'],
				];
				$this->db->insert( "AR_trInvoiceFaktur", $insert_invoice_factur);
							
				$activities_description = sprintf( "%s (%s) # %s # %s # %s ", "INSERT GENERAL CASHIER FAKTUR.", $cashier[ 'Type_Transaksi' ], $cashier[ $this->primary_key ], $row[ $this->foreign_key ], $row['NoFaktur'] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $row['NoFaktur'] ."','{$activities_description}','GC_trGeneralCashierDetailFaktur'");				
			}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}
	
	public function update_data( $header, $No_Invoice )
	{
		$this->load->model("type_m");
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		$receivable_type =$this->type_m->get_row( $header['JenisPiutang_ID'] );
						
		$this->db->trans_begin();
			$this->db->update( $this->table, $header, array( $this->primary_key => $No_Invoice));
			
			$activities_description = sprintf( "%s # %s # %s ", "UPDATE INVOICE.", $No_Invoice, $receivable_type['Nama_Type'] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $No_Invoice ."','{$activities_description}','{$this->table}'");				
		
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
		
		
		$this->db->trans_begin();
			
			$this->db->query("EXEC GC_batalkan_TRANSAKSI_BBM '{$cashier->No_Bukti}','". $_SERVER['REMOTE_ADDR'] ."#{$user->Nama_Asli}'");
			
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
	}
}