<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class receivable_helper
{
	public static function gen_factur_number( $date )
	{
		$date = DateTime::createFromFormat("Y-m-d", $date );
		$month = $date->format("m");
		$year = $date->format("Y");
		$like = sprintf("%s/%s/FAR", $year, $month);
		
		$order_number = @self::ci()->db
			->select( "MAX(No_Faktur) AS max_number " )
			->where(array(
				"DATEPART(month, Tgl_Faktur) =" => $month,
				"DATEPART(year, Tgl_Faktur) =" => $year,
			))
			->like("No_Faktur", $like, 'after')
			->get( "AR_TrFaktur" )
			->row()
			;
		
		if (empty($order_number->max_number))
		{
			$mix = "%s/%s/FAR/%03d";
			$gen_number = sprintf( $mix, $year, $month, 1);

		} else {
			$order_number->max_number++;
			$gen_number = $order_number->max_number;
		}

		return (string) $gen_number;
	}

	public static function gen_invoice_number( $date )
	{
		$date = DateTime::createFromFormat("Y-m-d", $date );
		$month = $date->format("m");
		$year = $date->format("Y");
		$like = sprintf("%s/%s/INV", $year, $month);
		
		$order_number = @self::ci()->db
			->select( "MAX(No_Invoice) AS max_number " )
			->where(array(
				"DATEPART(month, Tgl_Invoice) =" => $month,
				"DATEPART(year, Tgl_Invoice) =" => $year,
			))
			->like("No_Invoice", $like, 'after')
			->get( "AR_trInvoice" )
			->row()
			;
		
		if (empty($order_number->max_number))
		{
			$mix = "%s/%s/INV/%03d";
			$gen_number = sprintf( $mix, $year, $month, 1);

		} else {
			$order_number->max_number++;
			$gen_number = $order_number->max_number;
		}

		return (string) $gen_number;
	}

	public static function gen_credit_debit_note_number( $date )
	{
		$date = DateTime::createFromFormat("Y-m-d", $date );
		$month = $date->format("m");
		$year = $date->format("Y");
		$like = sprintf("%s/%s/PNT", $year, $month);
		
		$order_number = @self::ci()->db
			->select( "MAX(No_Invoice) AS max_number " )
			->where(array(
				"DATEPART(month, Tgl_Invoice) =" => $month,
				"DATEPART(year, Tgl_Invoice) =" => $year,
			))
			->like("No_Invoice", $like, 'after')
			->get( "AR_trInvoice" )
			->row()
			;
		
		if (empty($order_number->max_number))
		{
			$mix = "%s/%s/PNT/%03d";
			$gen_number = sprintf( $mix, $year, $month, 1);

		} else {
			$order_number->max_number++;
			$gen_number = $order_number->max_number;
		}

		return (string) $gen_number;
	}
	
	public static function get_option_currency()
	{
		self::ci()->db->order_by( 'Currency_default', 'DESC' );
		
		$query = self::ci()->db->get( "Mst_Currency" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$data[ $row->Currency_ID ] = $row->Currency_Name;
			} 
		} 
		
		return $data;
	}

	public static function get_option_division()
	{
		self::ci()->db->order_by( 'Nama_Divisi' );
		
		$query = self::ci()->db->get( "mDivisi" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$data[ $row->Divisi_ID ] = $row->Nama_Divisi;
			} 
		} 
		
		return $data;
	}
	
	public static function get_option_project()
	{
		self::ci()->db->order_by( 'Nama_Proyek' );
		
		$query = self::ci()->db->get( "mProyek" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$data[ $row->Kode_Proyek ] = $row->Nama_Proyek;
			} 
		} 
		
		return $data;
	}

	public static function get_customer( $Customer_ID )
	{
		$query = self::ci()->db->where("Customer_ID", $Customer_ID)->get( "mCustomer" );
		
		return ( $query->num_rows() > 0 ) ? $query->row() : NULL;
	}

	public static function get_his_currency( $date )
	{
		self::ci()->db->query("exec CekHisCurrency '". $date ."' ");

		$query = self::ci()->db->where("Tanggal", $date)->get( "TBJ_HisCurrency" );
		
		return ( $query->num_rows() > 0 ) ? $query->row()->HisCurrency_ID : 1;
	}

	public static function get_rate_currency( $date = NULL )
	{
		$date = empty($date) ? date("Y-m-d") : $date;
		
		$HisCurrency_ID = self::get_his_currency( $date );
		
		$query = self::ci()->db
				->from("Mst_Currency a")
				->join("TBJ_HisCurrencyDetail b", "a.Currency_ID = b.Currency_ID", "LEFT OUTER")
				->join("TBJ_HisCurrency c", "b.HisCurrency_ID = c.HisCurrency_ID", "LEFT OUTER")
				->where(array(
						"c.HisCurrency_ID" => $HisCurrency_ID,
					))
				->get();
				
		return $query->num_rows() > 0 ? $query->result() : FALSE;						
	}
	
	public static function get_rate_currency_by_id( $id, $date = NULL )
	{
		$date = empty($date) ? date("Y-m-d") : $date;
		
		$HisCurrency_ID = self::get_his_currency( $date );
		
		$query = self::ci()->db
				->from("TBJ_HisCurrencyPosted a")
				->join("TBJ_HisCurrency b", "a.HisCurrency_ID = b.HisCurrency_ID", "INNER")
				->where(array(
						"b.HisCurrency_ID" => $HisCurrency_ID,
						"a.Currency_ID" => $id
					))
				->get();
				
		return $query->num_rows() > 0 ? $query->row()->Rate : 1;						
	}
				
	public static function get_beginning_balance_details( $data  )
	{
		$query = self::ci()->db
				->select("
					b.Kode_Customer , b.Nama_Customer, c.Nama_Proyek, 
					a.Tgl_Saldo, d.Currency_Code, a.Nilai, a.Customer_ID, a.Currency_ID, a.JenisPiutang_ID , 
					a.Kode_Proyek, e.Nama_Divisi, a.DivisiID 
				")
				->from("AR_trPostedBulanan a")
				->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
				->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
				->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
				->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
				->where( array( 
					"a.JenisPiutang_ID" => $post->receivable_type,
					"a.SaldoAwal" => 1
				))
				->get()
				;
		
		return $query->result();
	}	
	
	public static function get_beginning_balance_date()
	{
		$date = DateTime::createFromFormat("Y-m-d", config_item('Tanggal Mulai System') );
		$date->sub(new DateInterval('P1D'));
		return $date->format('Y-m-d');
	}
	
	public static function get_last_closing_period()
	{
		self::ci()->db->reset_query();
		$query = @self::ci()->db->select("MAX(Tgl_Saldo) AS Max")
						->from("AR_trPostedbulanan")
						->get()
						->row();
			
		if ( !empty($query->Max) ) 
		{	
			$date = DateTime::createFromFormat("Y-m-d", substr($query->Max, 0, 9) );
			
			return $date->format('Y-m');
		}
		
		return DateTime::createFromFormat("Y-m-d", config_item('Tanggal Mulai System'))
						->modify('-1day')
						->format('Y-m');		
	}
	
	public static function check_closing_period( $date )
	{
		$date = DateTime::createFromFormat("Y-m-d", $date );
		$month = $date->format("m");
		$year = $date->format("Y");
		
		return (boolean)
			self::ci()->db->where(array(
						"DATEPART(month, Tgl_Saldo) =" => $month,
						"DATEPART(year, Tgl_Saldo) =" => $year,
					))
					->count_all_results( "AR_trPostedBulanan" );	
	}
	
	public static function money_to_text($angka){
		$money = array("","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan","sepuluh","sebelas");
		if($angka < 12 ){
			return " ".$money[$angka];
		}elseif($angka < 20 ){
			return self::money_to_text($angka-10)." belas";
		}elseif($angka < 100 ){
			return self::money_to_text($angka/10)." puluh" . self::money_to_text($angka%10);
		}elseif($angka < 200){
			return "seratus".self::money_to_text($angka-100);
		}elseif($angka < 1000){
			return self::money_to_text($angka /100)." ratus".self::money_to_text($angka%100);
		}elseif($angka < 2000){
			return "seribu".self::money_to_text($angka-1000);
		}elseif($angka < 1000000 ){
			return self::money_to_text($angka/1000). " ribu".self::money_to_text($angka%1000);
		}elseif($angka < 1000000000 ){
			return self::money_to_text($angka / 1000000). " juta".self::money_to_text($angka%1000000);
		}
	}
	
	private static function & ci()
	{
		return get_instance();
	}
}
