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
	
	
	public function closing( $closing_date )
	{
		$date = DateTime::createFromFormat("Y-m", $closing_date);
		$date_start = $date->format('Y-m-01');
		$date_end = $date->format('Y-m-t');
		$user = $this->simple_login->get_user();
		$accounting_period = config_item('TypePeriodeAkuntansi');
		$end_of_period = ( $date->format('m') == 12 ) ? 1 : 0;		

		set_time_limit(0);

		$this->db->trans_begin();
		
			$this->db->query("EXEC CreateTutupBuku_Hutang_New '{$date_start}','{$date_end}',{$user->User_ID},{$end_of_period}, {$accounting_period} ");
			
		if ( FALSE === $this->db->trans_status() )
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		
		$this->db->trans_commit();
		return TRUE;
	}	
		
	# Mengecek apakah ada data Faktur atau voucher yang belum di POSTING
	public function check_un_posting_data( $closing_date )
	{
		$date = DateTime::createFromFormat("Y-m", $closing_date);
		
		$this->db->select('MAX(No_Faktur) AS MAX')
				->from('AP_trFaktur')
				->where(array(
					"Posted" => 0,
					"Cancel_Faktur" => 0,
					"Diakui_Hutang" => 1,
					"datepart(month, Tgl_Faktur) =" => $date->format("m"),
					"datepart(year, Tgl_Faktur) =" => $date->format("Y")
				))
				->where('No_Faktur IS NOT NULL', NULL, FALSE);

		$_union_factur = $this->db->get_compiled_select();

		$this->db->select('MAX(No_Voucher) AS MAX')
				->from('AP_trVoucher')
				->where(array(
					"Posted" => 0,
					"Cancel_Voucher" => 0,
					"datepart(month, Tgl_Voucher) =" => $date->format("m"),
					"datepart(year, Tgl_Voucher) =" => $date->format("Y")
				))
				->where_in("JTransaksi_ID", array(402, 403, 406, 407), false);
		
		$_union_voucher = $this->db->get_compiled_select();
		
		$union_check = $this->db->from(" ( {$_union_factur} UNION {$_union_voucher} ) AS UNION_CHECK")
							->get()->row()->MAX;
		
		return (boolean) $union_check;
	}
	
	public function check_un_posting_logistic_data( $closing_date )
	{
		$date = DateTime::createFromFormat("Y-m", $closing_date);
		$date_start = $date->format("Y-m-01");
		$date_end = $date->format("Y-m-t");
		
		$check = $this->db->from("dbo.LOG_DataVerifikasiBelumTerposting( '{$date_start}', '{$date_end}')")
					->count_all_results();
		
		return (boolean) $check;
	} 
	
	public function check_voucher_incorrect_transaction( $closing_date )
	{
		$date = DateTime::createFromFormat("Y-m", $closing_date);
		
		$check = $this->db->from('AP_trVoucher a')
					->join('AP_trVoucherDetail b', 'a.No_Voucher = b.No_Voucher', 'INNER')
					->where(array(
						"TutupBuku" => 1,
						"Cancel_Voucher" => 0,
						"datepart(month, Tgl_Voucher) =" => $date->format("m"),
						"datepart(year, Tgl_Voucher) =" => $date->format("Y")
					))
					->get();

		return ( $check->num_rows() > 0) ? $check->row() : FALSE;
	}
	
	public function check_previous_month_closing( $closing_date )
	{
		$date = DateTime::createFromFormat("Y-m", $closing_date);
		$date->modify("last day of previous month");
	  
	 	$check = $this->db->from('AP_trPostedBulanan a')
					->join('TBJ_HisCurrency b', 'a.HisCurrency_ID = b.HisCurrency_ID', 'INNER')
					->where(array(
						"b.Tanggal" => $date->format('Y-m-t')
					))
					->count_all_results();
					
		return (boolean) $check;
	}

	public function check_current_period_transaction( $closing_date )
	{
		$date = DateTime::createFromFormat("Y-m", $closing_date);
			  
	 	$check = $this->db->from('AP_trVoucher a')
					->join('AP_trVoucherDetail b', 'a.No_Voucher = b.No_Voucher', 'INNER')
					->where(array(
						"a.Cancel_Voucher" => 0,
						"datepart(month, Tgl_Voucher) =" => $date->format("m"),
						"datepart(year, Tgl_Voucher) =" => $date->format("Y")
					))
					->count_all_results();
					
		return (boolean) $check;
	}
	
	public function get_last_gl_balance_date()
	{
		$query = $this->db->select("MAX(b.Tanggal) date")
					->from("TBJ_PostedBulanan a")
					->join("TBJ_HisCurrency b", "a.HisCurrency_ID = b.HisCurrency_ID", "INNER")
					->get();
					
		return substr($query->row()->date, 0, 10);
	}
	
	public function check_accordance_with_gl( $closing_date )
	{
		$date = DateTime::createFromFormat("Y-m-d", $closing_date); 
		$_ap_value = $this->db->select("SUM (Nilai*Nilai_Tukar) AS Nilai")
							->from('AP_trPostedbulanan')
							->where('Tgl_Saldo', $date->format('Y-m-t'))
							->get()
							->row();

		$_gl_value = $this->db->select("SUM (Nilai*Nilai_Tukar) AS Nilai")
							->from('TBJ_PostedBulanan a')
							->join('TBJ_HisCurrency b', 'a.HisCurrency_ID = b.HisCurrency_ID')
							->where('b.Tanggal', $date->format('Y-m-t'))
							->where('a.Akun_ID IN ( SELECT Akun_ID FROM AP_mTypeHutang )')
							->get()
							->row();

		return ( abs($_ap_value->Nilai - $_gl_value->Nilai) > 1 ) ? FALSE : TRUE;
		
	}
	
	public function trouble_ap_tipe_not_macth( $closing_date )
	{
		$date = DateTime::createFromFormat("Y-m-d", $closing_date); 
		$date->modify('first day of next month');
		$this->db->from("dbo.TROUBLE_AP_Tipe_Tidak_Cocok_Dengan_Akun('". $date->format('Y-m-t') ."') ");
		
		return (boolean) $this->db->count_all_results();
	}
	
	public function trouble_ap_balance_not_macth( $closing_date )
	{
		$date = DateTime::createFromFormat("Y-m-d", $closing_date); 
		$date->modify('first day of next month');
		$this->db->from("dbo.TROUBLE_Saldo_AP_Tidak_Cocok_Dengan_GL('". $date->format('Y-m-t') ."') ");
		
		return (boolean) $this->db->count_all_results();
	}
	
	public function check_cancelled_card_payable( $closing_date )
	{
		$where_in = $this->db->select('No_Faktur')
							->from('AP_trFaktur')
							->group_start()
								->or_where('Cancel_Faktur', 1)
								->or_where('Diakui_Hutang', 0)
							->group_end()
							->where('Tgl_Faktur >', $closing_date )
							->get_compiled_select();
		
		$check = $this->db->select('MAX(No_Bukti)')
						->from('AP_trKartuHutang')
						->where_in('NoReferensiFaktur', array( $where_in ), FALSE )
						->count_all_results();
		
		return (boolean) $check;
	}
	
	public function check_not_related_card_payable( $closing_date )
	{
		$where_in = $this->db->select('No_Faktur')
							->from('AP_trFaktur')
							->where('Tgl_Faktur >', $closing_date )
							->get_compiled_select();
		
		$check = $this->db->select('MAX(No_Bukti)')
						->from('AP_trKartuHutang')
						->where_not_in('NoReferensiFaktur', array( $where_in ), FALSE )
						->where('Tanggal >', $closing_date)
						->like('No_Bukti', 'FAP')
						->not_like('No_Bukti', 'SA-')
						->count_all_results();
		
		return (boolean) $check;
	}
	
	public function recap_payable_not_macth( $closing_date )
	{
		$date = DateTime::createFromFormat('Y-m-d', $closing_date );
		$date->modify('first day of next month');
		$date_start = $date->format('Y-m-01');
		$date_end = $date->format('Y-m-t');
		
		$_recap_factur = $this->db->select("SUM(Nilai_Faktur) AS Nilai")
							->from('AP_trFaktur')
							->where( array(
								'Cancel_Faktur' => 0,
								'Posted' => 1,
								'Diakui_Hutang' => 1,
								'Tgl_Faktur >=' => $date_start,
								'Tgl_Faktur <=' => $date_end
							))
							->get()->row()->Nilai;
							
		$_recap_voucher = $this->db->select("SUM(b.Kredit - b.Debit) AS Nilai")
							->from('AP_trVoucher a')
							->join('AP_trVoucherDetail b', 'a.No_Voucher= b.No_Voucher', 'INNER')
							->where( array(
								'Cancel_Voucher' => 0,
								'Posted' => 1,
								'Tgl_Voucher >=' => $date_start,
								'Tgl_Voucher <=' => $date_end
							))
							->where_in('a.JTransaksi_ID', array(406, 407), FALSE)
							->get()->row()->Nilai;

		$_recap_cashier = $this->db->select("SUM(b.Kredit - b.Debet) AS Nilai")
							->from('GC_trGeneralCashier a')
							->join('GC_trGeneralCashierDetail b', 'a.No_Bukti= b.No_Bukti', 'INNER')
							->where( array(
								'Posted' => 1,
								'Tgl_Transaksi >=' => $date_start,
								'Tgl_Transaksi <=' => $date_end
							))
							->where_in('b.Akun_ID', array("Select Akun_ID  from AP_mTypeHutang"), FALSE)
							->get()->row()->Nilai;
		
		$_recap_payable = $_recap_factur + $_recap_voucher + $_recap_cashier;
				
		$_recap_general_ledger = $this->db->select("SUM(b.Kredit - b.Debit) AS Nilai")
							->from('TBJ_Transaksi a')
							->join('TBJ_Transaksi_Detail b', 'a.No_Bukti= b.No_Bukti', 'INNER')
							->where( array(
								'a.Transaksi_Date >=' => $date_start,
								'a.Transaksi_Date <=' => $date_end,
							))
							->where_in('b.Akun_ID', array("Select Akun_ID  from AP_mTypeHutang"), FALSE)
							->get()->row()->Nilai;
		
		return  ( (round( $_recap_payable, 2) - round( $_recap_general_ledger, 2 )) > 1) ? TRUE : FALSE;
		
	}
	
	public function card_payable_not_macth_aging()
	{
		$check = $this->db->from('dbo.AP_CekKecocokanKartuDenganAging()')
					->count_all_results();
		
		return (boolean) $check;
	}
	
	public function card_type_payable_not_macth_aging()
	{
		$check = $this->db->from('dbo.AP_CekKecocokanTipeKartuDenganAging()')
					->get();
		
		return $check->num_rows() > 0 ? $check->row() : FALSE;
	}
	
	public function closing_cancel( $cancel_date )
	{
		$date = DateTime::createFromFormat("Y-m", $cancel_date);
		$date_start = $date->format('Y-m-01');
		$date_end = $date->format('Y-m-t');
		$end_of_period = ( $date->format('m') == 12 ) ? 1 : 0;		

		set_time_limit(0);

		$this->db->trans_begin();
			$this->db->reset_query();

			$this->db->query("EXEC BatalkanTutupBuku_Hutang_New '{$date_start}','{$date_end}', {$end_of_period} ");
			
			$this->db->reset_query();
					
			$this->db->set(['TutupBuku' => 0])
				->where(['Tgl_Voucher >= ' => $date_start, 'Tgl_Voucher <= ' => $date_end])
				->update('AP_trVoucher');
				
			$this->db->reset_query();
			
			$this->db->set(['TutupBuku' => 0])
				->where(['Tanggal >= ' => $date_start, 'Tanggal <= ' => $date_end])
				->update('AP_trKartuHutang');
			
		if ( FALSE === $this->db->trans_status() )
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		
		$this->db->trans_commit();
		return TRUE;
	}
	
	public function check_general_ledger_closing_period( $closing_date )
	{
		$date = DateTime::createFromFormat('Y-m', $closing_date);
		$check = $this->db->from('TBJ_PostedBulanan a')
						->join('TBJ_HisCurrency b', 'a.HisCurrency_ID = b.HisCurrency_ID', 'INNER')
						->where( array(
							"datepart(month, Tanggal) =" => $date->format("m"),
							"datepart(year, Tanggal) =" => $date->format("Y")
						))
						->count_all_results();
		
		return (boolean) $check;
	}

	public function check_next_closing_period( $closing_date )
	{
		$date = DateTime::createFromFormat('Y-m', $closing_date);
		$date->modify('first day of next month');
		
		$check = $this->db->from('AP_trPostedBulanan a')
						->join('TBJ_HisCurrency b', 'a.HisCurrency_ID = b.HisCurrency_ID', 'INNER')
						->where( array(
							"datepart(month, b.Tanggal) =" => $date->format("m"),
							"datepart(year, b.Tanggal) =" => $date->format("Y")
						))
						->count_all_results();
		
		return (boolean) $check;
	}	
}


