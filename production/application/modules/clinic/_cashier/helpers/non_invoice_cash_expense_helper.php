<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class non_invoice_cash_expense_helper
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
							->where(array("Tipe" => "KNI", "Tanggal >=" => $date_start,"Tanggal <=" => $date_end))
							->where_in("SectionID", array("SEC079","SEC080"))
							->get( "SIMtrPenerimaanNonPasien" )
							->row()
							;
		if (!empty($query->MAX))
		{
			$number = $query->MAX;
			list($front, $number) = explode("-", $number);
			$number++;
			$number = (string) (sprintf(self::_gen_format_evidence_number(), $date_y, $date_m, $date_d, 'KNI', $number));	
		} else {
			$number = (string) (sprintf(self::_gen_format_evidence_number(), $date_y, $date_m, $date_d, 'KNI', 1));		
		}
		
		return $number;
	}
	
	private static function _gen_format_evidence_number()
	{
		$format = "%02d%02d%02d%s-%06d";
		return $format;
	}
	
	public static function get_non_invoice_cash_expense( $NoBukti )
	{
		$CI = self::ci();	
		$DBBO = $CI->load->database('BO_1', TRUE);
		
		$query = $CI->db->select("a.*, b.SectionName, c.Nama_Singkat, d.Akun_No, d.Akun_Name, e.ID AS MerchanID, e.NamaBank AS MerchanName, f.Akun_No AS AkunMerchanNo, f.Akun_Name AS AkunMerchanName")
					->from("SIMtrPenerimaanNonPasien a")
					->join("SIMmSection b", "a.SectionID = b.SectionID", "LEFT OUTER")
					->join("mUser c", "a.UserID = c.User_ID", "LEFT OUTER")
					->join("{$DBBO->database}.dbo.Mst_Akun d", "a.AkunID = d.Akun_ID", "LEFT OUTER")
					->join("SIMmMerchan e", "a.MerchanID = e.Id", "LEFT OUTER")
					->join("{$DBBO->database}.dbo.Mst_Akun f", "a.AkunMerchanID = f.Akun_ID", "LEFT OUTER")
					->where("a.NoBukti", $NoBukti)
					->get()
					;
					
		if ( $query->num_rows() > 0)
		{
			return $query->row(); 
		}
		
		return FALSE; 		
	}
	
	public static function get_option_section()
	{
		$query = self::ci()->db
					->where(["StatusAktif" => 1])
					->where_in("TipePelayanan", ["RJ", "KASIR", "FARMASI"])
					->get("SIMmSection");
		
		$collection = [];
		foreach( $query->result() as $row ):
			$collection[$row->SectionID] = $row->SectionName;
		endforeach;
		
		return $collection;
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
