<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class general_cashier_helper
{
	private static $_tbl = "payable_services";
	
	public static function gen_evidence_number( $date, $transaction_type = "BKK" )
	{
		
		// Mengecek jika parameter Type tidak sesuai dengan standar
		if ( ! in_array( $transaction_type, array("BKK", "BKM", "BBK", "BBM", "MUT" )) )
		{
			return false;
		}

		$date = DateTime::createFromFormat('Y-m-d', $date);
		$month = $date->format("m");
		$year = $date->format("y");
		$year_full = $date->format("Y");
		$like = sprintf("%s/%s/%s", $year, $month, $transaction_type);
		
		$order_number = @self::ci()->db
			->select( "MAX(No_Bukti) AS max_number " )
			->where(array(
				"DATEPART(month, Tgl_Transaksi) =" => $month,
				"DATEPART(year, Tgl_Transaksi) =" => $year_full,
			))
			->like("No_Bukti", $like, 'after')
			->get( "GC_trGeneralCashier" )
			->row()
			;
		
		if (empty($order_number->max_number))
		{
			$mix = "%s/%s/%s/%04d";
			$gen_number = sprintf( $mix, $year, $month, $transaction_type, 1);

		} else {
			$order_number->max_number++;
			$gen_number = $order_number->max_number;
		}

		return (string) $gen_number;

		return (string) $code;
	}

	public static function gen_credit_debit_note_number( $house_id )
	{
		$house_code = @self::ci()->db->where("id", $house_id)->get("common_houses")->row()->code;
		$date = date("Y-m-d");
		$day = date("d");
		$month = date("m");
		$year = date("Y");
		
		$order_number = @self::ci()->db
			->select( "MAX(voucher_number) AS voucher_number" )
			->where(array("house_id" => $house_id, "voucher_date" => $date ))
			->where_in("transaction_type_id", array(406, 407), false)
			->get( "tab_ap_vouchers" )
			->row()
			;
		
		if (empty($order_number->voucher_number))
		{
			$mix = "%s/HNT/%s/%s/%s/%03d";
			$kode = sprintf( $mix, $house_code, $year, $month, $day, 1);

		} else {
			$order_number->voucher_number++;
			$kode = $order_number->voucher_number;
		}

		return (string) $kode;
	}

	public static function get_his_currency( $date )
	{
		self::ci()->db->query("exec CekHisCurrency '". $date ."' ");

		$query = self::ci()->db->where("Tanggal", $date)->get( "TBJ_HisCurrency" );
		
		return ( $query->num_rows() > 0 ) ? $query->row()->HisCurrency_ID : 1;
	}

	public static function get_beginning_balance_date()
	{
		$date = DateTime::createFromFormat("Y-m-d", config_item('Tanggal Mulai System') );
		$date->sub(new DateInterval('P1D'));
		return $date->format('Y-m-d');
	}
	
	public static function check_general_ledger_closing_transaction( $No_Bukti )
	{
		return (boolean)
			self::ci()->db->from( "TBJ_Transaksi" )
					->where("No_Bukti", $No_Bukti)
					->where("Posted", 1)
					->count_all_results();
	}
	
	public static function check_receivable_closing_period( $date )
	{
		$date = DateTime::createFromFormat('Y-m-d', $date);
		
		return (boolean)
			self::ci()->db->from( "AR_trPostedBulanan a" )
					->join( "TBJ_HisCurrency b", "a.HisCurrency_ID = b.HisCurrency_ID", "INNER")
					->where("b.Tanggal", $date->format('Y-m-t'))
					->count_all_results();
	}

	public static function check_payable_closing_period( $date )
	{
		$date = DateTime::createFromFormat('Y-m-d', $date);
		
		return (boolean)
			self::ci()->db->from( "AP_trPostedBulanan a" )
					->join( "TBJ_HisCurrency b", "a.HisCurrency_ID = b.HisCurrency_ID", "INNER")
					->where("b.Tanggal", $date->format('Y-m-t'))
					->count_all_results();
	}
	
	public static function check_general_ledger_closing_period( $date )
	{
		$date = DateTime::createFromFormat('Y-m-d', $date);
		
		return (boolean)
			self::ci()->db->from( "TBJ_PostedBulanan a" )
					->join( "TBJ_HisCurrency b", "a.HisCurrency_ID = b.HisCurrency_ID", "INNER")
					->where("b.Tanggal", $date->format('Y-m-t'))
					->count_all_results();
	}
	
	public static function check_reconciliation_data( $No_Bukti )
	{
		return (boolean)
			self::ci()->db->from( "GC_trGeneralCashier" )
					->where("No_Bukti", $No_Bukti)
					->get()->row()->Rekonsiliasi;
	}
	
	public static function check_general_cashier_detail_factur( $No_Bukti )
	{
		return (boolean)
			self::ci()->db->from( "GC_trGeneralCashierDetailFaktur" )
					->where("NoBukti", $No_Bukti)
					->count_all_results();
	}

	private static function & ci()
	{
		return get_instance();
	}
}
