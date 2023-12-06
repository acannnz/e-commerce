<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class outstanding_payment_helper
{
	
	public static function gen_evidence_number( )
	{
		$CI = self::ci();
		
		$NOW = new DateTime();
		$date_start = $NOW->format( "Y-m-01" );
		$date_end = $NOW->format( "Y-m-t" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );
		
		$query =  $CI->db->select("MAX(NoBukti) AS MAX")
							->where(array("Tanggal >=" => $date_start,"Tanggal <=" => $date_end))
							->where_in("SectionID", array("SEC079","SEC080"))
							->get( "SIMtrPembayaranOutStanding" )
							->row()
							;
		if (!empty($query->MAX))
		{
			$number = $query->MAX;
			$number++;
		} else {
			$number = (string) (sprintf(self::_gen_format_evidence_number(), $date_y, $date_m, $date_d, 'PBO', 1));		
		}
		
		return $number;
	}
	
	private static function _gen_format_evidence_number()
	{
		$format = "%02d%02d%02d/%s/%06d";
		return $format;
	}
	
	public static function get_billing( $NoFarmasi )
	{
		$CI = self::ci();	
		
		$query = $CI->db->select("a.*, b.Nama_Supplier, c.SectionName, d.JenisKerjasama")
					->from("BILLFarmasi a")
					->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
					->join("SIMmSection c", "a.SectionID = c.SectionID", "LEFT OUTER")
					->join("SIMmJenisKerjasama d", "a.KerjasamaID = d.JenisKerjasamaID", "LEFT OUTER")
					->where("a.NoBukti", $NoFarmasi)
					->get()
					;
		if ( $query->num_rows() > 0)
		{
			return $query->row(); 
		}
		
		return FALSE; 		
	}

	public static function get_billing_detail( $NoFarmasi )
	{
		$CI = self::ci();	
		
		$query = $CI->db->select("a.JmlObat AS Qty, a.Harga, a.Disc, b.Nama_Barang")
					->from("BILLFarmasiDetail a")
					->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
					->where("a.NoBukti", $NoFarmasi)
					->get()
					;
		if ( $query->num_rows() > 0)
		{
			return $query->result(); 
		}
		
		return FALSE; 		
	}

	public static function outstanding_value( $NoInvoice )
	{
		$CI = self::ci();	
		
		//Select SUM(NilaiBayar) from SIMtrKasirDetail where NoBUkti='170226INVRI-003276' and IDBayar=9
		$query = $CI->db->select("SUM(NilaiBayar) AS NilaiBayar")
					->from("SIMtrKasirDetail a")
					->where(array("a.NoBukti" => $NoInvoice, "IDBayar" => 9))
					->get()
					;
		if ( $query->num_rows() > 0)
		{
			return $query->row()->NilaiBayar; 
		}
		
		return 0.00; 		
	}
	
	public static function accumulated_payment( $NoInvoice )
	{
		$CI = self::ci();	
		
		//Select sum(NilaiPembayaran) as JML from SIMtrPembayaranOutStanding where NoInvoice='170226INVRI-003276'  and Batal=0		
		$query = $CI->db->select("SUM(NilaiPembayaran) AS AkumulasiPembayaran")
					->from("SIMtrPembayaranOutStanding a")
					->where(array("a.NoInvoice" => $NoInvoice, "Batal" => 0))
					->get()
					;
		if ( $query->row()->AkumulasiPembayaran > 0)
		{
			return $query->row()->AkumulasiPembayaran; 
		}
		
		return 0.00; 		
	}

	public static function get_outstanding( $NoBukti )
	{
		$CI = self::ci();	
		
		$query = $CI->db->select("a.*, c.NRM, c.NamaPasien, d.JenisKerjasama, e.NamaBank, f.Jam AS TanggalInvoice")
					->from("SIMtrPembayaranOutStanding a")
					->join("SIMtrRegistrasi b", "a.NoReg = b.NoReg", "LEFT OUTER")
					->join("mPasien c", "b.NRM = c.NRM", "LEFT OUTER")
					->join("SIMmJenisKerjasama d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER")
					->join("SIMmMerchan e", "a.IDBank = e.ID", "LEFT OUTER")
					->join("SIMtrKasir f", "a.NoInvoice = f.NoBukti", "LEFT OUTER")
					->where("a.NoBukti", $NoBukti)
					->get()
					;
					
		if ( $query->num_rows() > 0)
		{
			return $query->row(); 
		}
		
		return FALSE; 		
	}

	public static function money_to_text( $number ){
		$money = ["","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan","sepuluh","sebelas"];
		if($number < 12 ){
			return " ".$money[$number];
		}elseif($number < 20 ){
			return self::money_to_text($number-10)." belas";
		}elseif($number < 100 ){
			return self::money_to_text($number/10)." puluh" . self::money_to_text($number%10);
		}elseif($number < 200){
			return " seratus".self::money_to_text($number-100);
		}elseif($number < 1000){
			return self::money_to_text($number /100)." ratus".self::money_to_text($number%100);
		}elseif($number < 2000){
			return " seribu".self::money_to_text($number-1000);
		}elseif($number < 1000000 ){
			return self::money_to_text($number/1000). " ribu".self::money_to_text($number%1000);
		}elseif($number < 1000000000 ){
			return self::money_to_text($number / 1000000). " juta".self::money_to_text($number%1000000);
		}
	}
			
	private static function & ci()
	{
		return get_instance();
	}
}
