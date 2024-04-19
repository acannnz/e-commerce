<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Closing_m extends Public_Model
{
	public $table = '';
	public $primary_key = 'id';
	
	public function __construct()
	{
		$this->rules = array(
				'insert' => array(
					'level' => array(
							'field' => 'level',
							'label' => lang( 'closing:level_label' ),
							'rules' => 'required'
						),
					'digit' => array(
							'field' => 'digit',
							'label' => lang( 'closing:digit_label' ),
							'rules' => ''
						),
					'state' => array(
							'field' => 'state',
							'label' => lang( 'closing:state_label' ),
							'rules' => ''
						),
			));
		
		parent::__construct();
	}
	
	public function get_child_id ( $like  )
	{
		// Hanya mencari data child pada akun rekening!
		//SELECT * FROM `tab_accounting_accounts`WHERE id NOT IN ( SELECT parent_id FROM `tab_accounting_accounts`)
		
		$parents = $this->db->select('parent_id')
						//->like( $like )
						->get('accounting_accounts')
						->result();
			 
	
		foreach($parents as $item) {
			$array[] = $item->parent_id;         
		}
		
		$ignore = implode(',', array_unique($array));
		
		
		$data = $this->db->select("id")
						->where_not_in('id', $ignore, FALSE)
						->like( $like )
						->like('account_number', $like , 'after')
						//->or_like('account_number', "2", 'after')
						//->or_like('account_number', "3", 'after')
						->get("accounting_accounts")
						;

		return $data->result_array();
	}		
	
	public function get_child_data ( $like, $where = NULL  )
	{
		// Hanya mencari data child pada akun rekening!
		
		$parents = $this->db->select('parent_id')
						->get('accounting_accounts')
						->result();
			 
	
		foreach($parents as $item) {
			$array[] = $item->parent_id;         
		}
		
		$ignore = implode(',', array_unique($array));
		
		if (is_array($where))
		{
			$this->db->where($where);
		}
		
		$data = $this->db->select("*")
						->where_not_in('id', $ignore, FALSE)
						->like('account_number', $like , 'after')
						->get("accounting_accounts")
						;

		return $data->result();
	}		
	
	public function closing( $close_date )
	{
		$today = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		
		$date = DateTime::createFromFormat("Y-m", $close_date );
		$date_start = $date->format('Y-m-01');
		$date_end = $date->format('Y-m-t');
		$month = $date->format('m');
		
		$end_of_period = ( $month == 12 ) ? 1 : 0;
		$type_accounting_period = config_item('TypePeriodeAkuntansi');	
		$user = $this->simple_login->get_user();
		
		set_time_limit(0);
		
		$this->db->trans_begin();
		
			$activities_description = sprintf( "%s # %s ", "TUTUP BUKU GL START.", $time );			
			$this->db->query("EXEC InsertUserActivities '$today','$time', {$user->User_ID} ,'TUTUP BUKU START','$activities_description','XXX'");
			
			# CreateTutupBuku](@FromDate datetime, @ToDate datetime,@userId int,@AkhirPeriode int,@TypePeriodeAkuntansi int)
			$this->db->query("EXEC CreateTutupBuku '{$date_start}', '{$date_end}', {$user->User_ID}, {$end_of_period}, {$type_accounting_period} ");
			
			# CreateTutupBukuLabaRugi](@FromDate datetime, @ToDate datetime,@userId int,@AkhirPeriode int,@Periode_Akuntansi int)
			$this->db->query("EXEC CreateTutupBukuLabaRugi '{$date_start}', '{$date_end}', {$user->User_ID}, {$end_of_period}, {$type_accounting_period} ");
	
			if ( config_item('Dengan Cash Flow') )
			{
				# TutupBukuCashFlow](@FromDate as varchar(50),@ToDate as Varchar(50),@AkhirTahun int,@UserId int)
				$this->db->query("EXEC CreateTutupBukuLabaRugi '{$date_start}', '{$date_end}', {$end_of_period}, {$user->User_ID} ");
			}
			
			$activities_description = sprintf( "%s # %s ", "TUTUP BUKU GL SELESAI.", $time );			
			$this->db->query("EXEC InsertUserActivities '$today','$time', {$user->User_ID} ,'TUTUP BUKU END','$activities_description','XXX'");
			
		if ( FALSE === $this->db->trans_status() )
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		
		$this->db->trans_commit();
		
		return TRUE;
	}	
	
	public function cancel_closing( $cancel_date )
	{
		
		$date = DateTime::createFromFormat("Y-m", $cancel_date );
		$date_start = $date->format('Y-m-01');
		$date_end = $date->format('Y-m-t');
		$month = $date->format('m');
		$time = $date->format('Y-m-d H:i:s');
		$today = $date->format('Y-m-d');
		
		$end_of_period = ( $month == 12 ) ? 1 : 0;
		$type_accounting_period = config_item('TypePeriodeAkuntansi');	
		$user = $this->simple_login->get_user();
		
		set_time_limit(0);

		$this->db->trans_begin();
		
			$activities_description = sprintf( "%s # %s ", "PEMBATALAN TUTUP BUKU GL START.", $time );			
			$this->db->query("EXEC InsertUserActivities '$today','$time', {$user->User_ID} ,'PEMBATALAN TUTUP BUKU START','$activities_description','XXX'");
			
			# BatalkanTutupBuku](@FromDate varchar(50), @ToDate varchar(50),@AkhirPeriode int)
			$this->db->query("EXEC BatalkanTutupBuku '{$date_start}', '{$date_end}', {$end_of_period} ");
			
			if ( config_item('Dengan Cash Flow') )
			{
				# Batal_TutupBukuCashFlow](@Tanggal varchar(50),@AkhirTahun int)
				$this->db->query("EXEC Batal_TutupBukuCashFlow '{$date_end}', {$end_of_period} ");
			}
			
			$activities_description = sprintf( "%s # %s ", "PEMBATALAN TUTUP BUKU GL SELESAI.", $time );			
			$this->db->query("EXEC InsertUserActivities '$today','$time', {$user->User_ID} ,'PEMBATALAN TUTUP BUKU START','$activities_description','XXX'");

		if ( FALSE === $this->db->trans_status() )
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		
		$this->db->trans_commit();
		return TRUE;
				
	}
	
	public function last_period_closing()
	{
		/*
			SELECT   max(dbo.TBJ_HisCurrency.Tanggal) " & _
           	FROM         dbo.TBJ_PostedBulanan INNER JOIN " & _
          	dbo.TBJ_HisCurrency ON dbo.TBJ_PostedBulanan.HisCurrency_ID = dbo.TBJ_HisCurrency.HisCurrency_ID
		*/
		
		$query = $this->db->select("MAX(b.Tanggal) AS max")
						->from("TBJ_PostedBulanan a")
						->join("TBJ_HisCurrency b", "a.HisCurrency_ID = b.HisCurrency_ID", "INNER")
						->get();
			
						
		if ( $query->num_rows() > 0 ) 
		{	
			$date = DateTime::createFromFormat("Y-m-d", substr($query->row()->max, 0, 9) );
			
			return $date->format('Y-m');
		}
		
		return date("Y-m");
		
	}
	
	public function check_rate_currency( $date = NULL )
	{		
		$HisCurrency_ID = $this->db->select("MAX(HisCurrency_ID) AS HisCurrency_ID")->where( "Tanggal", $date )->get("TBJ_HisCurrency")->row()->HisCurrency_ID;
		
		$currencies = $this->db->get("Mst_Currency")->result();	
		foreach ( $currencies as $row ) 
		{
			// check rate currency
			$check = $this->db->where(array("HisCurrency_ID" => $HisCurrency_ID, "Currency_ID" => $row->Currency_ID))
							->count_all_results("TBJ_HisCurrencyDetail");
			if( $check == 0 )
			{
				return FALSE;
			}
		}
		return TRUE;

	}
	
	/*
		
	SELECT dbo.TBJ_Transaksi_Detail.Akun_ID,mst_akun.akun_no,mst_akun.Akun_Name
	   FROM dbo.TBJ_Transaksi_Detail 
	   INNER JOIN dbo.TBJ_Transaksi ON dbo.TBJ_Transaksi_Detail.No_Bukti = dbo.TBJ_Transaksi.No_Bukti 
	   INNER JOIN dbo.Mst_Akun ON dbo.TBJ_Transaksi_Detail.Akun_ID = dbo.Mst_Akun.Akun_ID 
	   INNER JOIN dbo.Mst_GroupAkunDetail ON dbo.Mst_Akun.GroupAkunDetailID = dbo.Mst_GroupAkunDetail.GroupAkunDetailId  
	   WHERE     (dbo.Mst_GroupAkunDetail.Cash = 0) 
		AND (dbo.Mst_GroupAkunDetail.Bank = 0) 
		AND (dbo.TBJ_Transaksi_Detail.No_Bukti IN  
			(SELECT  dbo.TBJ_Transaksi_Detail.No_Bukti  
				FROM dbo.TBJ_Transaksi_Detail 
				INNER JOIN tbj_transaksi ON TBJ_Transaksi_Detail.No_bukti = TBJ_Transaksi.No_Bukti 
				INNER JOIN dbo.Mst_Akun ON dbo.TBJ_Transaksi_Detail.Akun_ID = dbo.Mst_Akun.Akun_ID 
				INNER JOIN dbo.Mst_GroupAkunDetail ON dbo.Mst_Akun.GroupAkunDetailID = dbo.Mst_GroupAkunDetail.GroupAkunDetailId  
				WHERE  TBJ_Transaksi.Transaksi_Date>= '2015-01-01' 
					and TBJ_Transaksi.Transaksi_Date< '2015-01-31' 
					AND   ((dbo.Mst_GroupAkunDetail.Cash = 1) OR  (dbo.Mst_GroupAkunDetail.Bank = 1))
			)
		)  
	 and TBJ_Transaksi_Detail.akun_id not in(select tbcf_Detail.akun_id from tbcf_Detail) 
	 GROUP BY dbo.TBJ_Transaksi_Detail.Akun_ID,mst_akun.akun_no,mst_akun.Akun_Name
	
		-- Proses tutup buku tidak dapat dilakukan. 
		-- Karena masih adanya cash flow yang belum disetup  & vbCrLf & Lengkapi setup cash flow terlebih dahulu
		-- Apakah anda ingin melihat detail rekening yang belum di-setup?	
	*/
	
	public function check_cash_flow( $close_date )
	{
		$date = DateTime::createFromFormat("Y-m", $close_date );
		$date_start = $date->format('Y-m-01');
		$date_end = $date->format('Y-m-t');
		
		$check = $this->db->select("TBJ_Transaksi_Detail.Akun_ID,mst_akun.akun_no,mst_akun.Akun_Name")
						->from("TBJ_Transaksi_Detail a")
						->join("TBJ_Transaksi b", "a.No_Bukti = b.No_Bukti", "INNER")
						->join("Mst_Akun c", "a.Akun_ID = c.Akun_ID", "INNER")
						->join("Mst_GroupAkunDetail d","c.GroupAkunDetailID = d.GroupAkunDetailId")
						->where("d.cash", 0)
						->where("d.Bank", 0)
						->where(
							"a.No_Bukti IN 
							( 
								SELECT  dbo.TBJ_Transaksi_Detail.No_Bukti  
								FROM TBJ_Transaksi_Detail a
								INNER JOIN tbj_transaksi b ON a.No_bukti = b.No_Bukti 
								INNER JOIN Mst_Akun c ON a.Akun_ID = c.Akun_ID 
								INNER JOIN Mst_GroupAkunDetail d ON c.GroupAkunDetailID = d.GroupAkunDetailId  
								WHERE  b.Transaksi_Date>= '{$date_start}' 
									and b.Transaksi_Date< '{$date_end}' 
									AND (
											d.Cash = 1 
											OR d.Bank = 1
										)
							)
							"
						)
						->where("a.Akun_ID NOT IN(SELECT Akun_id from TBCF_Detail) ")
						->group_by("a.Akun_ID, c.Akun_No, c.Akun_Name")
						->count_all_results()
						;
						
		return (boolean) $check;
		
	}
	
	# select top 1 Supplier_ID from AP_trPostedBulanan where datepart(month,tgl_saldo)="month" and datepart(year,tgl_saldo)="year"
	public function check_posted_payable( $close_date )
	{
		$date = DateTime::createFromFormat("Y-m", $close_date );
		$month = $date->format('m');
		$year = $date->format('Y');
		
		$check = $this->db->where(array(
								"DATEPART(month, Tgl_Saldo) =" => $month,
								"DATEPART(year, Tgl_Saldo) =" => $year,								
							))
						->count_all_results("AP_trPostedBulanan")
						;
		// Jika tidak ada Transaksi tutup buku, maka harus tutup buku di Hutang	
		return (boolean) $check;
	}

	# select top 1 Customer_ID from AR_trPostedBulanan where datepart(month,tgl_saldo)="month" and datepart(year,tgl_saldo)="year"
	public function check_posted_receivable( $close_date )
	{
		$date = DateTime::createFromFormat("Y-m", $close_date );
		$month = $date->format('m');
		$year = $date->format('Y');
		
		$check = $this->db->where(array(
								"DATEPART(month, Tgl_Saldo) =" => $month,
								"DATEPART(year, Tgl_Saldo) =" => $year,								
							))
						->count_all_results("AR_trPostedBulanan")
						;
		// Jika tidak ada Transaksi tutup buku, maka harus tutup buku di Piutang				
		return (boolean) $check;
	}
	
	# Select top 1 GC_trGeneralCashier.No_Bukti from GC_trGeneralCashier 
	# inner join GC_trGeneralCashierDetail on GC_trGeneralCashier.No_Bukti=GC_trGeneralCashierDetail.No_Bukti 
	# where GC_trGeneralCashier.Status_Batal=0 
	# and datepart(month,tgl_transaksi)="month" 
    # and datepart(year,tgl_transaksi)="year" 
	# and GC_trGeneralCashier.posted = 0 
	# And (NoBG = '' OR NoBG IS NULL) "
	
	public function check_posted_general_cashier( $close_date )
	{
		$date = DateTime::createFromFormat("Y-m", $close_date );
		$month = $date->format('m');
		$year = $date->format('Y');
		
		$check = $this->db->from("GC_trGeneralCashier a")
						->join("GC_trGeneralCashierDetail b", "a.No_Bukti = b.No_Bukti", "INNER")
						->where(array(
								"DATEPART(month, Tgl_Transaksi) =" => $month,
								"DATEPART(year, Tgl_Transaksi) =" => $year,	
								"a.posted =" => 0,
								"Status_Batal" => 0,
							))
						->where("( NoBG = '' OR NoBG IS NULL )")
						->count_all_results()
						;
		// Mencari transaksi General Cashier yang belum di Posting				
		return (boolean) $check;
	}
	
	public function check_transaction_already_posted( $close_date )
	{
		$date = DateTime::createFromFormat("Y-m", $close_date );
		$month = $date->format('m');
		$year = $date->format('Y');
		
		// Mencari Transaksi yg sudah Posting pada Tanggal Tutup Buku
		$query = $this->db->select("No_Bukti")
						->where(array(
								"DATEPART(month, Transaksi_Date) =" => $month,
								"DATEPART(year, Transaksi_Date) =" => $year,	
								"Posted" => 1,
							))
						->get("TBJ_Transaksi");
						
		$collection = NULL;				
		if ( $query->num_rows() > 0 ): 
			foreach ( $query->result() as $row ):
			
				$collection = sprintf("%s %s,", $collection, $row->No_Bukti);
				
			endforeach; 
			
			return $collection;
			
		endif;
		
		return FALSE;
	}
	
	public function check_already_closing( $close_date )
	{
		$date = DateTime::createFromFormat("Y-m", $close_date );
		$date_end = $date->format('Y-m-t');
		
		// Mencari apakah sudah Tutup Buku pada periode yg diminta
		$check = $this->db->where(array("Tanggal" => $date_end))
						->from("TBJ_PostedBulanan a")
						->join("TBJ_HisCurrency b", "a.HisCurrency_ID = b.HisCurrency_ID", "INNER")
						->count_all_results();
						
		return (boolean) $check;
	}

	public function check_previous_month_closing( $close_date )
	{
		$date = DateTime::createFromFormat("Y-m", $close_date );
		$date->modify("last day of previous month");
		$previous_month = $date->format('Y-m-d');
		
		// Mengecek apakah bulan sebelumnya sudah tutup buku atau belum
		$check = $this->db->where(array("Tanggal" => $previous_month))
						->from("TBJ_PostedBulanan a")
						->join("TBJ_HisCurrency b", "a.HisCurrency_ID = b.HisCurrency_ID", "INNER")
						->count_all_results();
						
		return (boolean) $check;
	}
	
	####################
	# Cancel Close Book #
	####################
	
	/*
		SELECT tbj_postedbulanan.hisCurrency_id from tbj_postedbulanan inner join " & _
	  	tbj_hiscurrency on tbj_postedbulanan.hiscurrency_id=tbj_hiscurrency.hiscurrency_id " & _
	  	WHERE Konsolidasi=1 and datepart(month,tbj_hiscurrency.Tanggal)=" & DatePart("m", dDtTglAkhir) & " and datepart(year,tbj_hiscurrency.Tanggal)=" & DatePart("yyyy", dDtTglAkhir) & "
	*/
	
	public function check_closing_consolidation_cooperation( $cancel_date )
	{
		$date = DateTime::createFromFormat("Y-m", $cancel_date );

		$month = $date->format('m');
		$year = $date->format('Y');
		
		$check = $this->db->where(array(
								"DATEPART(month, Tanggal) =" => $month,
								"DATEPART(year, Tanggal) =" => $year,	
								"a.Konsolidasi" => 1,
							))
						->from("TBJ_PostedBulanan a")
						->join("TBJ_HisCurrency b", "a.HisCurrency_ID = b.HisCurrency_ID", "INNER")
						->count_all_results()
						;
						
		// Mencari transaksi posted bulanan yang sudah konsolidasi
		return (boolean) $check;
	}
	
	public function check_already_closing_next_month( $cancel_date )
	{
		$date = DateTime::createFromFormat("Y-m", $cancel_date );
		$date->modify('first day of next month');
		$month = $date->format('m');
		$year = $date->format('Y');
		
		$check = $this->db->where(array(
								"DATEPART(month, Tanggal) =" => $month,
								"DATEPART(year, Tanggal) =" => $year,	
							))
						->from("TBJ_PostedBulanan a")
						->join("TBJ_HisCurrency b", "a.HisCurrency_ID = b.HisCurrency_ID", "INNER")
						->count_all_results()
						;
						
		// Mencari transaksi posted bulanan yang sudah konsolidasi
		return (boolean) $check;
	}
}