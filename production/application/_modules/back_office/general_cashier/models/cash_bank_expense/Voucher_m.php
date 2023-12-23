<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voucher_m extends Public_Model
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
						'label' => lang( 'cash_bank_expense:evidence_number_label' ),
						'rules' => 'required'
					),
				'Tgl_Transaksi' => array(
						'field' => 'Tgl_Transaksi',
						'label' => lang( 'cash_bank_expense:date_label' ),
						'rules' => 'required'
					),
				'Type_Transaksi' => array(
						'field' => 'Type_Transaksi',
						'label' => lang( 'cash_bank_expense:type_label' ),
						'rules' => 'required'
					),
				'Debet' => array(
						'field' => 'Debet',
						'label' => lang( 'cash_bank_expense:pay_label' ),
						'rules' => 'required'
					),
				'AkunBG_ID' => array(
						'field' => 'AkunBG_ID',
						'label' => lang( 'cash_bank_expense:account_label' ),
						'rules' => 'required'
					),
				'Keterangan' => array(
						'field' => 'Keterangan',
						'label' => lang( 'cash_bank_expense:description_label' ),
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
			//->join("mSupplier b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER" )
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
	
	#get_voucher_collection
	public function get_voucher_collection( $No_Bukti )
	{		
		$db_select = <<<EOSQL
			b.Tgl_Voucher, 
			b.No_Voucher, 
			a.Kredit, 
			a.Debet AS Debit, 
			a.NilaiAsal AS Sisa, 
			(a.NilaiAsal - a.Debet) AS Saldo,
			b.Keterangan,
			b.JenisHutang_ID,
			b.Akun_ID,
			c.Nama_Supplier
EOSQL;

		$query = $this->db
					->select( $db_select )
					->from("{$this->table_detail} a")
					->join("AP_trVoucher b", "a.No_Referensi = b.No_Voucher", "INNER" )
					->join("mSupplier c", "b.Supplier_ID = c.Supplier_ID", "LEFT OUTER" )
					->where("a.No_Bukti", $No_Bukti)
					->get();
			
		return ($query->num_rows() > 0) ? $query->result() : NULL;		
	}
	
	public function get_voucher_detail( $No_Voucher )
	{		
		$db_select = <<<EOSQL
			a.No_Faktur,
			a.No_Voucher,
			a.Keterangan,
			a.Sisa,
			a.Sisa AS Debit,
			b.Akun_ID
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "AP_trFaktur a" )
			->join("AP_mTypeHutang b", "a.JenisHutang_ID = b.TypeHutang_ID", "LEFT OUTER" )
			->where(array(
				"a.No_Voucher" => $No_Voucher,
			))
			->get()
			;

		return ($query->num_rows() > 0) ? $query->result() : NULL;		
	}

	public function get_general_cashier_factur_collection( $No_Voucher )
	{		
		$db_select = <<<EOSQL
			b.No_Faktur,
			b.No_Voucher,
			b.Keterangan,
			a.Sisa,
			a.Dibayar AS Debit,
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "GC_trGeneralCashierDetailFaktur a" )
			->join("AP_trFaktur b", "a.NoFaktur = b.No_Faktur", "INNER" )
			->where(array(
				"b.No_Voucher" => $No_Voucher,
			))
			->get()
			;
			
		return ($query->num_rows() > 0) ? $query->result() : NULL;		
	}
	
	/*
	*/
	public function create_data( $cashier, $voucher, $factur )
	{
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");

		$cashier_detail = array();
		$voucher_detail = array();
		$voucher_factur = array();

		$cashier_debit = $cashier_kredit = 0;
		foreach($voucher as $row)
		{
			// prepare cashier detail
			$cashier_detail[] = array(
				$this->primary_key  => $cashier[ $this->primary_key ],
				$this->foreign_key => $row[ 'No_Voucher' ],
				"NilaiAsal" => $row['Sisa'],
				"Debet" => $row['Debit'],
				"Kredit" => $row['Kredit'],
				"Akun_ID" => $row["Akun_ID"],
				"Keterangan" => $cashier['Keterangan'] ." ---> ". $row[ 'No_Voucher' ],
				"Kode_Proyek" => $cashier["Kode_Proyek"],
				"DivisiID" => $cashier["DivisiID"],
				"Tgl_Tempo" => $cashier['Tgl_Transaksi'],
				"SectionID" => config_item('SectionIDCorporate'),
			);
			
			// prepare mutation voucher detail
			$voucher_detail[] = array(
				"No_Voucher"  => $row[ "No_Voucher" ],
				$this->primary_key => $cashier[ $this->primary_key ],
				"NilaiAsal" => 0,
				"Debit" => $row['Debit'],
				"Kredit" => $row['Kredit'],
				"Keterangan" => "-",
				"Tgl_transaksi" => $cashier['Tgl_Transaksi'],
				"JTransaksi_ID" => 405,
				"SectionID" => config_item('SectionIDCorporate'),
			);
			
			// prepare cashier factur
			foreach($factur[ $row[ 'No_Voucher' ] ] as $val)
			{
				$voucher_factur[] = array(
					"NoBukti"  => $cashier[ $this->primary_key ],
					$this->foreign_key => $row[ "No_Voucher" ],
					"NoFaktur" => $val['No_Faktur'],
					"Sisa" => $val['Sisa'],
					"Dibayar" => $val['Debit'],
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
			
			foreach ( $voucher_detail as $row )
			{
				$this->db->insert( "AP_trVoucherDetail", $row);

				$this->db->set( "Sisa", "Sisa - ". $row['Debit'], FALSE)
						->set( "Sudah_Dibuatkan_Bukti", 1)
						->where( "No_Voucher",  $row[ "No_Voucher" ] )
						->update( "AP_trVoucher" );
				
				$activities_description = sprintf( "%s (%s) # %s # %s", "INSERT VOUCHER DETAIL.", $cashier[ 'Type_Transaksi' ], $row[ 'No_Voucher' ], $cashier[ $this->primary_key ] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $row[ 'No_Voucher' ] ."','{$activities_description}','AP_trVoucherDetail'");				
			}
			
			foreach ( $voucher_factur as $row )
			{
				$this->db->insert( "GC_trGeneralCashierDetailFaktur", $row);

				$this->db->set( "Sisa", "Sisa - ". $row['Dibayar'], FALSE)
						->where( "No_Faktur",  $row['NoFaktur'] )
						->update( "AP_trFaktur" );
				
				# INSERT INTO AP_trVoucherFaktur(No_Bukti, No_Voucher, No_Faktur, Sisa, Debet, Kredit)
				# VALUES(Me.NoBukti, Me.No_Referensi, Me.NoFaktur, 0, Me.Dibayar, 0)"
				$insert_voucher_factur = [
					"No_Bukti"  => $row['NoBukti'],
					"No_Voucher" => $row[ $this->foreign_key ],
					"No_Faktur" => $row['NoFaktur'],
					"Sisa" => $row['Sisa'],
					"Debet" => $row['Dibayar'],
					"Kredit" => 0,
				];
				$this->db->insert( "AP_trVoucherFaktur", $insert_voucher_factur);
				
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
	
	public function update_data( $header, $No_Voucher )
	{
		$this->load->model("type_m");
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		$receivable_type =$this->type_m->get_row( $header['JenisHutang_ID'] );
						
		$this->db->trans_begin();
			$this->db->update( $this->table, $header, array( $this->primary_key => $No_Voucher));
			
			$activities_description = sprintf( "%s # %s # %s ", "UPDATE VOUCHER.", $No_Voucher, $receivable_type['Nama_Type'] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $No_Voucher ."','{$activities_description}','{$this->table}'");				
		
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
			
			$this->db->query("EXEC GC_batalkan_TRANSAKSI_BBK '{$cashier->No_Bukti}','". $_SERVER['REMOTE_ADDR'] ."#{$user->Nama_Asli}'");
			
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
	}
}