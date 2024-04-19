<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beginning_balance_m extends Public_Model
{
	public $table = 'AP_trPostedBulanan';
	public $primary_key = 'id';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'house_id' => array(
						'field' => 'house_id',
						'label' => lang( 'beginning_balances:house_label' ),
						'rules' => 'required'
					),
				'supplier_id' => array(
						'field' => 'supplier_id',
						'label' => lang( 'beginning_balances:supplier_label' ),
						'rules' => 'required'
					),
				'type_id' => array(
						'field' => 'type_id',
						'label' => lang( 'types:type_label' ),
						'rules' => 'required'
					),
				'value' => array(
						'field' => 'value',
						'label' => lang( 'beginning_balances:value_label' ),
						'rules' => 'required'
					),
				'date' => array(
						'field' => 'date',
						'label' => lang( 'beginning_balances:date_label' ),
						'rules' => 'required'
					),
				'beginning_balance' => array(
						'field' => 'beginning_balance',
						'label' => lang( 'beginning_balances:beginning_balance_label' ),
						'rules' => 'required'
					),
				'state' => array(
						'field' => 'state',
						'label' => lang( 'beginning_balances:state_label' ),
						'rules' => ''
					),
			));
		
		parent::__construct();
	}
	
	public function get_child_id ( $like, $where = null )
	{
		// Hanya mencari data child pada akun rekening!
		//SELECT * FROM `tab_payable_accounts`WHERE id NOT IN ( SELECT parent_id FROM `tab_payable_accounts`)
		
		$parents = $this->db->select('parent_id')
						->get('payable_accounts')
						->result();
			 
	
		foreach($parents as $item) {
			$array[] = $item->parent_id;         
		}
		
		$ignore = implode(',', array_unique($array));
		
		if (!empty($where) && is_array($where))
		{
			$this->db->where( $where );
		}
		
		$data = $this->db->select("id")
						->where_not_in('id', $ignore, FALSE)
						->like('account_number', $like, 'after')
						->get("payable_accounts")
						;
						
		if ( $data->num_rows() > 0)
		{
			return $data->result_array();
		}
		
		return false;
	}	
	
	public function get_payable_type_beginning_balances()
	{
		$data = $this->db->select("a.value, b.*")
						->from("gl_monthly_posted a")
						->join("ar_types b", "a.account_id = b.account_id", "RIGHT OUTER")
						->where( array("a.beginning_balance" => 1))
						->order_by("b.type_name", "ASC")
						->get()
						;
						
		if ($data->num_rows() > 0 )
		{
			return $data->result();
		}
		
		return false;
	}
	
	public function find_identical_posted( $post )
	{
		$count = $this->db->where(array(
								"SaldoAwal" => 1,
								"Supplier_ID" => $post->Supplier_ID,
								"Currency_ID" => $post->Currency_ID,
								"JenisHutang_ID" => $post->JenisHutang_ID,
								"DivisiID" => $post->DivisiID,
								"Kode_Proyek" => $post->Kode_Proyek							
							))
							->from("AP_trPostedBulanan")
							->count_all_results();
		
		return $count;
		
	}
	
	public function is_paid( $voucher_number )
	{
		$count = $this->db->where("No_Voucher", $voucher_number)
						->where("Sisa <>", "Nilai", FALSE)
						->from("AP_trVoucher")
						->count_all_results();
		
		return $count;
	
	}

	public function check_voucher_already_paid( $voucher_number )
	{
		$count = $this->db->where(array( 
							"No_Bukti" => $voucher_number,
							"JTransaksi_ID !=" => 200
						))
						->count_all_results("AP_trVoucherDetail")
						;
							
		return (boolean) $count;
	
	}

	public function check_close_book( $voucher_number )
	{
		$data = $this->db->where(array("No_Voucher" => $voucher_number))
						->get( "AP_trVoucher" )
						;
							
		return (boolean) $data->row()->TutupBuku;
	
	}

	public function get_hisscurency_id( $date )
	{
		return 
			@$this->db->where("Tanggal", $date)
				->get("TBJ_HisCurrency")
				->row()
				->HisCurrency_ID;
	}

	public function get_exchange_rate( $HisCurrency_ID )
	{
		$query = $this->db->where("HisCurrency_ID", $HisCurrency_ID)
					->get("TBJ_HisCurrencyDetail");
		
		$collection = array();			
		if ( $query->num_rows() > 0 ): foreach( $query->result() as $row ):
		
			$collection[$row->Currency_ID] = $row->Rate;
			
		endforeach; endif;
		
		return $collection;
	}
	
	private function gen_evidence_number( $post )
	{
		# SA,  Kode_Supplier, DivisiID, Kode_Proyek, JenisHutang_ID
		return
			sprintf("%s-%s-%s-%s-%s", "SA", $post->Kode_Supplier, $post->DivisiID, $post->Kode_Proyek, $post->JenisHutang_ID);
	}

	public function get_voucher_number( $params )
	{
		return 
			@$this->db->where( array(
					"No_Voucher" => $this->gen_evidence_number( $params )
				))
				->get("AP_trVoucher")
				->row()
				->No_Voucher;
	}
		
	public function get_beginning_balance_row( $params )
	{
		# WHERE Supplier_ID=4 AND Tgl_Saldo='2015-12-31 00:00:00' AND Kode_Proyek='9' 
		# AND DivisiID=11 AND JenisHutang_ID=4

		$db_select = <<<EOSQL
				b.Kode_Supplier, 
				b.Nama_Supplier, 
				c.Nama_Proyek, 
				a.Tgl_Saldo, 
				d.Currency_Code, 
				a.Nilai, 
				a.Supplier_ID, 
				a.Currency_ID, 
				a.JenisHutang_ID, 
				a.Kode_Proyek, 
				e.Nama_Divisi, 
				a.DivisiID
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "{$this->table} a" )
			->join("mSupplier b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->where( array(
				"a.Supplier_ID" => @$params->Supplier_ID,
				"a.SaldoAwal" => 1,
				"a.Kode_Proyek" => @$params->Kode_Proyek,
				"a.DivisiID" => @$params->DivisiID,
				"a.JenisHutang_ID" => @$params->JenisHutang_ID
			));
			
		return $query->get()->row();
	}
	
	public function create_data( $post )
	{
		$HisCurrency_ID = $this->get_hisscurency_id( $post->Tgl_Saldo );
		$exchange_rate = $this->get_exchange_rate(  $HisCurrency_ID );
		$user = $this->simple_login->get_user();
		
		$date = date('Y-m-d');
		$time = date('Y-m-d H:i:s');
		
		# VALUES ('2015-12-31' ,1000 ,4 ,1 ,4 ,'9' ,1 ,24542 ,1 ,1145,11)
		$posted = array(
				"Tgl_Saldo" => $post->Tgl_Saldo, 
				"Nilai" => $post->Nilai, 
				"Supplier_ID" => $post->Supplier_ID, 
				"Currency_ID" => $post->Currency_ID, 
				"JenisHutang_ID" => $post->JenisHutang_ID, 
				"Kode_Proyek" => $post->Kode_Proyek, 
				"SaldoAwal" => 1, 
				"HisCurrency_ID" => $HisCurrency_ID, 
				"Nilai_Tukar" => $exchange_rate[ $post->Currency_ID ], 
				"User_ID" => $user->User_ID, 
				"DivisiID" => $post->DivisiID
			);
		
		
		# Values(4,1,4,'2015-12-31','SA-PKS-0004-11-9-4','2015-12-31',1000,1000,'Saldo Awal',1145,'2015-12-31',200,1,1,'9', 11,'SEC078')
		$voucher = array(
				"Supplier_ID" => $post->Supplier_ID,
				"Currency_ID" => $post->Currency_ID,
				"JenisHutang_ID" => $post->JenisHutang_ID, 
				"Tgl_Voucher" => $post->Tgl_Saldo,
				"No_Voucher" => $this->gen_evidence_number( $post ),
				"Tgl_Tempo" => $post->Tgl_Saldo, 
				"Nilai" => $post->Nilai,
				"Sisa" => $post->Nilai, 
				"Keterangan" => 'Saldo Awal',
				"User_Id" => $user->User_ID, 
				"Tgl_Update" => $post->Tgl_Saldo, 
				"JTransaksi_ID" => 400,
				"Nilai_Tukar" => $exchange_rate[ $post->Currency_ID ], 
				"HisCurrencyID" => $HisCurrency_ID, 
				"Kode_Proyek" => $post->Kode_Proyek, 
				"DivisiID" => $post->DivisiID, 
				"SectionID" => "SEC078"
			);
			
		# Values('SA-PKS-0004-11-9-4','SA-PKS-0004-11-9-4','2015-12-31',400,1000,1000,0,'Saldo Awal','SEC078')
		
		$voucher_detail = array(
				"No_Voucher" => $this->gen_evidence_number( $post ),
				"No_Bukti" => $this->gen_evidence_number( $post ),
				"Tgl_transaksi" => $post->Tgl_Saldo,
				"JTransaksi_ID" => 400,
				"NilaiAsal" => $post->Nilai,
				"Debit" => 0,
				"Kredit" => $post->Nilai,
				"Keterangan" => "Saldo Awal",
				"SectionID" => 'SEC078'
			);
			
		# VALUES ('2015-12-31' ,'SA-PKS-0004-11-9-4' ,1000 ,4 ,1 ,1145 ,GETDATE() ,'Saldo Awal Sistem' ,'FAP'
		# 1,24542 ,4,'9','-',11,'2015-12-31',1000,1,'2015-12-31',1)
		$factur = array(
				"Tgl_Faktur" => $post->Tgl_Saldo, 
				"No_Faktur" => $this->gen_evidence_number( $post ),
				"No_Voucher" => $this->gen_evidence_number( $post ),
				"Nilai_Faktur" => $post->Nilai, 
				"Supplier_ID" => $post->Supplier_ID, 
				"Currency_ID" => $post->Currency_ID, 
				"User_ID" => $user->User_ID, 
				"Tgl_Update" => date('Y-m-d'), 
				"Keterangan" => "Saldo Awal Sistem", 
				"Jenis_Pos" => "FAP",
				"Nilai_Tukar" => $exchange_rate[ $post->Currency_ID ], 
				"HisCurrencyID" => $HisCurrency_ID,
				"JenisHutang_ID" => $post->JenisHutang_ID,
				"Kode_Proyek" => $post->Kode_Proyek,
				"NoKontrak" => '-',
				"DivisiID" => $post->DivisiID,
				"Tgl_JatuhTempo" => $post->Tgl_Saldo,
				"Sisa" => $post->Nilai,
				"DIAKUI_Hutang" => 1,
				"TGL_PENGAKUAN" => $post->Tgl_Saldo,
				"Posted" => 1
			);

		$this->db->trans_begin();
			$this->db->insert('AP_trPostedBulanan', $posted);
			$this->db->insert('AP_trVoucher', $voucher);
			$this->db->insert('AP_trVoucherDetail', $voucher_detail);
			$this->db->insert('AP_trFaktur', $factur);

			$activities_description = sprintf( "%s # %s # %s ", "INSERT SALDO HUTANG.", $post->Supplier_ID, $post->JenisHutang_ID );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".   $this->gen_evidence_number( $post ) ."','{$activities_description}','{$this->table}'");				
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
	}

	public function update_data( $post )
	{
		$HisCurrency_ID = $this->get_hisscurency_id( $post->Tgl_Saldo );
		$exchange_rate = $this->get_exchange_rate(  $HisCurrency_ID );
		$user = $this->simple_login->get_user();
		
		$date = date('Y-m-d');
		$time = date('Y-m-d H:i:s');

		$posted = array(
				"Nilai" => $post->Nilai, 
			);
			
		$posted_where = array(
				"SaldoAwal" => 1,
				"Supplier_ID" => $post->Supplier_ID,
				"Currency_ID" => $post->Currency_ID,
				"JenisHutang_ID" => $post->JenisHutang_ID,
				"DivisiID" => $post->DivisiID,
				"Kode_Proyek" => $post->Kode_Proyek,
			);		
		
		$voucher = array(
				"Nilai" => $post->Nilai,
				"Sisa" => $post->Nilai, 
			);
			
		$voucher_where = array(
				"No_Voucher" => $post->No_Voucher
			);
			
		$voucher_detail = array(
				"NilaiAsal" => $post->Nilai,
				"Debit" => $post->Nilai,
			);
		
		$voucher_detail_where = array(
				"No_Voucher" => $post->No_Voucher
			);
			
		$factur = array(
				"Nilai_Faktur" => $post->Nilai, 
				"Sisa" => $post->Nilai,
			);

		$factur_where = array(
				"No_FAktur" => $post->No_Voucher
			);

		$this->db->trans_begin();
			$this->db->update('AP_trPostedBulanan', $posted, $posted_where );
			$this->db->update('AP_trVoucher', $voucher, $voucher_where );
			$this->db->update('AP_trVoucherDetail', $voucher_detail, $voucher_detail_where );
			$this->db->update('AP_trFaktur', $factur, $factur_where );

			$activities_description = sprintf( "%s # %s # %s ", "UPDATE SALDO HUTANG.", $post->Supplier_ID, $post->JenisHutang_ID );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".   $post->No_Voucher ."','{$activities_description}','{$this->table}'");				
	
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
		
	}	

	public function delete_data( $post )
	{

		$user = $this->simple_login->get_user();
		$date = date('Y-m-d');
		$time = date('Y-m-d H:i:s');

		$posted_where = array(
				"SaldoAwal" => 1,
				"Supplier_ID" => $post->Supplier_ID,
				"Currency_ID" => $post->Currency_ID,
				"JenisHutang_ID" => $post->JenisHutang_ID,
				"DivisiID" => $post->DivisiID,
				"Kode_Proyek" => $post->Kode_Proyek,
			);
			
		$voucher_where = array(
				"No_Voucher" => $post->No_Voucher
			);
			
		$voucher_detail_where = array(
				"No_Voucher" => $post->No_Voucher
			);
			
		$factur_where = array(
				"No_Faktur" => $post->No_Voucher
			);

		$this->db->trans_begin();
			$this->db->delete('AP_trPostedBulanan', $posted_where );
			$this->db->delete('AP_trVoucherDetail', $voucher_detail_where );
			$this->db->delete('AP_trVoucher', $voucher_where );
			$this->db->delete('AP_trFaktur', $factur_where );
	
			$activities_description = sprintf( "%s # %s # %s ", "DELETE SALDO HUTANG.", $post->Supplier_ID, $post->JenisHutang_ID );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".   $post->No_Voucher ."','{$activities_description}','{$this->table}'");				

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
		
	}	

}