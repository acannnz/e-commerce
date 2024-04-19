<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beginning_balance_m extends Public_Model
{
	public $table = 'AR_trPostedBulanan';
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
				'customer_id' => array(
						'field' => 'customer_id',
						'label' => lang( 'beginning_balances:customer_label' ),
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
		//SELECT * FROM `tab_receivable_accounts`WHERE id NOT IN ( SELECT parent_id FROM `tab_receivable_accounts`)
		
		$parents = $this->db->select('parent_id')
						->get('receivable_accounts')
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
						->get("receivable_accounts")
						;
						
		if ( $data->num_rows() > 0)
		{
			return $data->result_array();
		}
		
		return false;
	}	
	
	public function get_receivable_type_beginning_balances( $house_id )
	{
		$data = $this->db->select("a.value, b.*")
						->from("gl_monthly_posted a")
						->join("ar_types b", "a.account_id = b.account_id", "RIGHT OUTER")
						->where( array("a.house_id" => $house_id, "a.beginning_balance" => 1))
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
								"Customer_ID" => $post->Customer_ID,
								"Currency_ID" => $post->Currency_ID,
								"JenisPiutang_ID" => $post->JenisPiutang_ID,
								"DivisiID" => $post->DivisiID,
								"Kode_Proyek" => $post->Kode_Proyek							
							))
							->from("AR_trPostedBulanan")
							->count_all_results();
		
		return $count;
		
	}
	
	public function is_paid( $invoice_number )
	{
		$count = $this->db->where("No_Invoice", $invoice_number)
						->where("Sisa <>", "Nilai", FALSE)
						->from("AR_trInvoice")
						->count_all_results();
		
		return $count;
	
	}

	public function check_invoice_already_paid( $invoice_number )
	{
		$count = $this->db->where(array( 
							"No_Bukti" => $invoice_number,
							"JTransaksi_ID !=" => 200
						))
						->count_all_results("AR_trInvoiceDetail")
						;
							
		return (boolean) $count;
	
	}

	public function check_close_book( $invoice_number )
	{
		$data = $this->db->where(array("No_Invoice" => $invoice_number))
						->get( "AR_trInvoice" )
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
		# SA,  Kode_Customer, DivisiID, Kode_Proyek, JenisPiutang_ID
		return
			sprintf("%s-%s-%s-%s-%s", "SA", $post->Kode_Customer, $post->DivisiID, $post->Kode_Proyek, $post->JenisPiutang_ID);
	}

	public function get_invoice_number( $params )
	{
		return 
			@$this->db->where( array(
					"No_Invoice" => $this->gen_evidence_number( $params )
				))
				->get("AR_trInvoice")
				->row()
				->No_Invoice;
	}
		
	public function get_beginning_balance_row( $params )
	{
		# WHERE Customer_ID=4 AND Tgl_Saldo='2015-12-31 00:00:00' AND Kode_Proyek='9' 
		# AND DivisiID=11 AND JenisPiutang_ID=4

		$db_select = <<<EOSQL
				b.Kode_Customer, 
				b.Nama_Customer, 
				c.Nama_Proyek, 
				a.Tgl_Saldo, 
				d.Currency_Code, 
				a.Nilai, 
				a.Customer_ID, 
				a.Currency_ID, 
				a.JenisPiutang_ID, 
				a.Kode_Proyek, 
				e.Nama_Divisi, 
				a.DivisiID
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "{$this->table} a" )
			->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->where( array(
				"a.Customer_ID" => @$params->Customer_ID,
				"a.SaldoAwal" => 1,
				"a.Kode_Proyek" => @$params->Kode_Proyek,
				"a.DivisiID" => @$params->DivisiID,
				"a.JenisPiutang_ID" => @$params->JenisPiutang_ID
			));
			
		return $query->get()->row();
	}
	
	public function create_data( $post )
	{
		$HisCurrency_ID = $this->get_hisscurency_id( $post->Tgl_Saldo );
		$exchange_rate = $this->get_exchange_rate(  $HisCurrency_ID );
		$user = $this->simple_login->get_user();
		
		# VALUES ('2015-12-31' ,1000 ,4 ,1 ,4 ,'9' ,1 ,24542 ,1 ,1145,11)
		$posted = array(
				"Tgl_Saldo" => $post->Tgl_Saldo, 
				"Nilai" => $post->Nilai, 
				"Customer_ID" => $post->Customer_ID, 
				"Currency_ID" => $post->Currency_ID, 
				"JenisPiutang_ID" => $post->JenisPiutang_ID, 
				"Kode_Proyek" => $post->Kode_Proyek, 
				"SaldoAwal" => 1, 
				"HisCurrency_ID" => $HisCurrency_ID, 
				"Nilai_Tukar" => $exchange_rate[ $post->Currency_ID ], 
				"User_ID" => $user->User_ID, 
				"DivisiID" => $post->DivisiID
			);
		
		
		# Values(4,1,4,'2015-12-31','SA-PKS-0004-11-9-4','2015-12-31',1000,1000,'Saldo Awal',1145,'2015-12-31',200,1,1,'9', 11,'SEC078')
		$invoice = array(
				"Customer_ID" => $post->Customer_ID,
				"Currency_ID" => $post->Currency_ID,
				"JenisPiutang_ID" => $post->JenisPiutang_ID, 
				"Tgl_Invoice" => $post->Tgl_Saldo,
				"No_Invoice" => $this->gen_evidence_number( $post ),
				"Tgl_Tempo" => $post->Tgl_Saldo, 
				"Nilai" => $post->Nilai,
				"Sisa" => $post->Nilai, 
				"Keterangan" => 'Saldo Awal',
				"User_Id" => $user->User_ID, 
				"Tgl_Update" => $post->Tgl_Saldo, 
				"JTransaksi_ID" => 200,
				"Nilai_Tukar" => $exchange_rate[ $post->Currency_ID ], 
				"HisCurrencyID" => $HisCurrency_ID, 
				"Kode_Proyek" => $post->Kode_Proyek, 
				"DivisiID" => $post->DivisiID, 
				"SectionID" => "SEC078"
			);
			
		# Values('SA-PKS-0004-11-9-4','SA-PKS-0004-11-9-4','2015-12-31',200,1000,1000,0,'Saldo Awal','SEC078')
		
		$invoice_detail = array(
				"No_Invoice" => $this->gen_evidence_number( $post ),
				"No_Bukti" => $this->gen_evidence_number( $post ),
				"Tgl_transaksi" => $post->Tgl_Saldo,
				"JTransaksi_ID" => 200,
				"NilaiAsal" => $post->Nilai,
				"Debit" => $post->Nilai,
				"Kredit" => 0,
				"Keterangan" => "Saldo Awal",
				"SectionID" => 'SEC078'
			);
			
		# VALUES ('2015-12-31' ,'SA-PKS-0004-11-9-4' ,1000 ,4 ,1 ,1145 ,GETDATE() ,'Saldo Awal Sistem' ,'FAP'
		# 1,24542 ,4,'9','-',11,'2015-12-31',1000,1,'2015-12-31',1)
		$factur = array(
				"Tgl_Faktur" => $post->Tgl_Saldo, 
				"No_Faktur" => $this->gen_evidence_number( $post ),
				"Nilai_Faktur" => $post->Nilai, 
				"Customer_ID" => $post->Customer_ID, 
				"Currency_ID" => $post->Currency_ID, 
				"User_ID" => $user->User_ID, 
				"Tgl_Update" => date('Y-m-d'), 
				"Keterangan" => "Saldo Awal Sistem", 
				"Jenis_Pos" => "FAP",
				"Nilai_Tukar" => $exchange_rate[ $post->Currency_ID ], 
				"HisCurrencyID" => $HisCurrency_ID,
				"JenisPiutang_ID" => $post->JenisPiutang_ID,
				"Kode_Proyek" => $post->Kode_Proyek,
				"NoKontrak" => '-',
				"DivisiID" => $post->DivisiID,
				"Tgl_JatuhTempo" => $post->Tgl_Saldo,
				"Sisa" => $post->Nilai,
				"DIAKUI_Piutang" => 1,
				"TGL_PENGAKUAN" => $post->Tgl_Saldo,
				"Posted" => 1
			);

		$this->db->trans_begin();
			$this->db->insert('AR_trPostedBulanan', $posted);
			$this->db->insert('AR_trInvoice', $invoice);
			$this->db->insert('AR_trInvoiceDetail', $invoice_detail);
			$this->db->insert('AR_trFaktur', $factur);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
	}
	
	/*
		UPDATE AR_trPostedBulanan  SET Tgl_Saldo='2015-12-31' ,Nilai=10000 ,Customer_ID=4 ,Currency_ID=1 
		,JenisPiutang_ID=4 ,Kode_Proyek='9' ,SaldoAwal=1 ,HisCurrency_ID=24542 ,Nilai_Tukar=1 
		,User_ID=1145,DivisiID=11 
		WHERE Tgl_Saldo='2015-12-31 00:00:00' AND Customer_ID=4 AND Currency_ID=1 
		AND JenisPiutang_ID=4 AND DivisiID=11 AND Kode_Proyek='9'
		
		Update AR_trInvoice SET Customer_ID=4,Nilai=10000,Sisa=10000,Kode_Proyek='9' ,DivisiID=11  
			WHERE No_Invoice='SA-PKS-0004-11-9-4'
		
		UPDATE AR_trInvoiceDetail SET NilaiAsal=10000,Debit=10000 
		WHERE No_invoice='SA-PKS-0004-11-9-4' and  No_Bukti='SA-PKS-0004-11-9-4'
		
		UPDATE AR_trFaktur set Nilai_Faktur=10000,sisa=10000 
		where No_FAktur='SA-PKS-0004-11-9-4'
		
	*/

	public function update_data( $post )
	{
		$HisCurrency_ID = $this->get_hisscurency_id( $post->Tgl_Saldo );
		$exchange_rate = $this->get_exchange_rate(  $HisCurrency_ID );
		$user = $this->simple_login->get_user();
		
		$posted = array(
				"Nilai" => $post->Nilai, 
			);
			
		$posted_where = array(
				"SaldoAwal" => 1,
				"Customer_ID" => $post->Customer_ID,
				"Currency_ID" => $post->Currency_ID,
				"JenisPiutang_ID" => $post->JenisPiutang_ID,
				"DivisiID" => $post->DivisiID,
				"Kode_Proyek" => $post->Kode_Proyek,
			);		
		
		$invoice = array(
				"Nilai" => $post->Nilai,
				"Sisa" => $post->Nilai, 
			);
			
		$invoice_where = array(
				"No_Invoice" => $post->No_Invoice
			);
			
		$invoice_detail = array(
				"NilaiAsal" => $post->Nilai,
				"Debit" => $post->Nilai,
			);
		
		$invoice_detail_where = array(
				"No_Invoice" => $post->No_Invoice
			);
			
		$factur = array(
				"Nilai_Faktur" => $post->Nilai, 
				"Sisa" => $post->Nilai,
			);

		$factur_where = array(
				"No_FAktur" => $post->No_Invoice
			);

		$this->db->trans_begin();
			$this->db->update('AR_trPostedBulanan', $posted, $posted_where );
			$this->db->update('AR_trInvoice', $invoice, $invoice_where );
			$this->db->update('AR_trInvoiceDetail', $invoice_detail, $invoice_detail_where );
			$this->db->update('AR_trFaktur', $factur, $factur_where );
	
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

		$posted_where = array(
				"SaldoAwal" => 1,
				"Customer_ID" => $post->Customer_ID,
				"Currency_ID" => $post->Currency_ID,
				"JenisPiutang_ID" => $post->JenisPiutang_ID,
				"DivisiID" => $post->DivisiID,
				"Kode_Proyek" => $post->Kode_Proyek,
			);
			
		$invoice_where = array(
				"No_Invoice" => $post->No_Invoice
			);
			
		$invoice_detail_where = array(
				"No_Invoice" => $post->No_Invoice
			);
			
		$factur_where = array(
				"No_Faktur" => $post->No_Invoice
			);

		$this->db->trans_begin();
			$this->db->delete('AR_trPostedBulanan', $posted_where );
			$this->db->delete('AR_trInvoiceDetail', $invoice_detail_where );
			$this->db->delete('AR_trInvoice', $invoice_where );
			$this->db->delete('AR_trFaktur', $factur_where );
	
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
		
	}	

}