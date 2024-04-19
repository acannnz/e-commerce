<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class inquiry_helper
{
	
	public static function gen_evidence_number( $SectionID )
	{
		$CI = self::ci();
		$NOW = new DateTime();
		
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );
		
		$section = $CI->db->where( array("SectionID" => $SectionID) )->get("SIMmSection")->row();
		
		//$CI->load->model( "registrations/registration_m" );
		//$count = $CI->registration_m->count( array("DATE_FORMAT(registration_date, '%Y') =" => $date_y) );
		$count = (int) $CI->db
							->where(array(
									"Tanggal >=" => $date_start,
									"Tanggal <=" => $date_end,
									"SectionAsal" => $section->SectionID
								))
							->count_all_results( "GD_trAmprahan" )
							;
		$count++;
		
		$number = (string) (sprintf(self::_gen_format_evidence_number(), $date_y, $date_m, $date_d, $section->KodeNoBukti, $count));		
		return $number;
	}
	
	private static function _gen_format_evidence_number()
	{
		$format = "%02d%02d%02dAMP%s-%06d";
		return $format;
	}

	public static function gen_mutation_evidence_number( $SectionID )
	{
		$CI = self::ci();
		$NOW = new DateTime();
		
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );
		
		$section = $CI->db->where( array("SectionID" => $SectionID) )->get("SIMmSection")->row();
		
		//$CI->load->model( "registrations/registration_m" );
		//$count = $CI->registration_m->count( array("DATE_FORMAT(registration_date, '%Y') =" => $date_y) );
		$count = (int) $CI->db
							->where(array(
									"Tgl_Mutasi >=" => $date_start,
									"Tgl_Mutasi <=" => $date_end,
									"Lokasi_Asal" => $section->Lokasi_ID
								))
							->count_all_results( "GD_trMutasi" )
							;
		$count++;
		
		$number = (string) (sprintf(self::_gen_mutation_evidence_number(), $date_y, $date_m, $date_d, $section->KodeNoBukti, $count));		
		return $number;
	}
	
	private static function _gen_mutation_evidence_number()
	{
		$format = "%02d%02d%02dMUT%s-%06d";
		return $format;
	}

	public static function gen_mutation_return_evidence_number( $SectionID )
	{
		$CI = self::ci();
		$NOW = new DateTime();
		
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );
		
		$section = $CI->db->where( array("SectionID" => $SectionID) )->get("SIMmSection")->row();
		
		//$CI->load->model( "registrations/registration_m" );
		//$count = $CI->registration_m->count( array("DATE_FORMAT(registration_date, '%Y') =" => $date_y) );
		$count = (int) $CI->db
							->where(array(
									"Tgl_Mutasi >=" => $date_start,
									"Tgl_Mutasi <=" => $date_end,
								))
							->count_all_results( "GD_trReturMutasi" )
							;
		$count++;
		
		$number = (string) (sprintf(self::_gen_mutation_return_evidence_number(), $date_y, $date_m, $date_d, $count));		
		return $number;
	}
	
	private static function _gen_mutation_return_evidence_number()
	{
		$format = "%02d%02d%02dRTO-%06d";
		return $format;
	}
		
	public static function gen_opname_evidence_number(  )
	{
		$CI = self::ci();
		$NOW = new DateTime();
		
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );
				
		//$CI->load->model( "registrations/registration_m" );
		//$count = $CI->registration_m->count( array("DATE_FORMAT(registration_date, '%Y') =" => $date_y) );
		$count = (int) $CI->db
							->where(array(
									"Tgl_Opname >=" => $date_start,
									"Tgl_Opname <=" => $date_end,
								))
							->count_all_results( "GD_trOpname" )
							;
		$count++;
		
		$number = (string) (sprintf(self::_gen_opname_evidence_number(), $date_y, $date_m, $count));		
		return $number;
	}
	
	private static function _gen_opname_evidence_number()
	{
		$format = "%02d%02d-OPN-%06d";
		return $format;
	}

	public static function gen_bhp_number(  )
	{
		$CI = self::ci();
		$NOW = new DateTime();
		
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );

		//$CI->load->model( "registrations/registration_m" );
		//$count = $CI->registration_m->count( array("DATE_FORMAT(registration_date, '%Y') =" => $date_y) );
		$query =  $CI->db
						->select("MAX(NoBukti) as max_number")
						->where(array(
								"Tanggal >=" => $date_start,
								"Tanggal <=" => $date_end,
							))
						->get( "BILLFarmasi" )
						->row()
					;
		if (!empty($query->max_number))
		{
			$query->max_number++;
			$number = $query->max_number;
		} else {
			$number = (string) (sprintf(self::_gen_bhp_number(), $date_y, $date_m, $date_d, 1));		
		}
		
		
		return $number;
	}
	
	private static function _gen_bhp_number()
	{
		$format = "%02d%02d%02dBHP-%06d";
		return $format;
	}

	public static function gen_product_package_number(  )
	{
		$CI = self::ci();
		$count = (int) $CI->db
							->count_all_results( "SIMmPaketObat" )
							;
		$count++;
		
		$number = (string) (sprintf(self::_gen_product_package_number(), $count));		
		return $number;
	}
	
	private static function _gen_product_package_number()
	{
		$format = "PKO%03d";
		return $format;
	}

	public static function gen_bhp_package_number(  )
	{
		$CI = self::ci();
		$count = (int) $CI->db
							->count_all_results( "SIMmPaketBHP" )
							;
		$count++;
		
		$number = (string) (sprintf(self::_gen_bhp_package_number(), $count));		
		return $number;
	}
	
	private static function _gen_bhp_package_number()
	{
		$format = "PKB%03d";
		return $format;
	}

	public static function gen_helper_number(  )
	{

		$NOW = new DateTime();
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );

		$CI = self::ci();
		$query =  $CI->db
						->select("MAX(NoBuktiMemo) as max_number")
						->where(array(
								"Tanggal >=" => $date_start,
								"Tanggal <=" => $date_end,
							))
						->get( "SIMtrMemoPenunjang" )
						->row()
					;
		if (!empty($query->max_number))
		{
			$query->max_number++;
			$number = $query->max_number;
		} else {
			$number = (string) (sprintf(self::_gen_helper_number(), $date_y, $date_m, $date_d, 1));		
		}
		return $number;
	}
	
	private static function _gen_helper_number()
	{
		$format = "%02d%02d%02dMEM-%06d";
		return $format;
	}

	public static function gen_memo_number(  )
	{

		$NOW = new DateTime();
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );

		$CI = self::ci();
		$query =  $CI->db
						->select("MAX(NoUrut) as max_number")
						->where(array(
								"Tanggal >=" => $date_start,
								"Tanggal <=" => $date_end,
							))
						->get( "SIMtrMemo" )
						->row()
					;
		if (!empty($query->max_number))
		{
			$query->max_number++;
			$number = $query->max_number;
		} else {
			$number = (string) (sprintf(self::_gen_memo_number(), $date_y, $date_m, 1));		
		}
		return $number;
	}
	
	private static function _gen_memo_number()
	{
		$format = "%02d%02d%06d";
		return $format;
	}
	
	public static function get_mutation( $mutation_number )
	{
		$query = self::ci()->db->select("
								a.*, 
								b.Nama_Lokasi SectionAsal, 
								c.Nama_Lokasi SectionTujuan,
								d.Tanggal TanggalAmprah,
								d.Keterangan AS KeteranganAmprah
							")
							->from("GD_trMutasi a")
							->join("mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER")
							->join("mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER")
							->join("GD_trAmprahan d", "a.NoAmprahan = d.NoBukti", "LEFT OUTER")
							->where("a.No_Bukti", $mutation_number)
							->get();
							
		return $query->row();
	}

	public static function get_mutation_detail( $mutation_number )
	{
		$query = self::ci()->db->select("
								a.*, 
								b.Kode_Barang,
								b.Nama_Barang,
								b.Konversi,
								c.Kode_Satuan AS Satuan_Beli
							")
							->from("GD_trMutasiDetail a")
							->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
							->join("mSatuan c", "b.Beli_Satuan_Id = c.Satuan_ID", "LEFT OUTER")	
							->where("a.No_Bukti", $mutation_number)
							->get();
							
		return $query->result();
	}

	public static function get_mutation_return( $mutation_return_number )
	{
		$query = self::ci()->db->select("
								a.*, 
								b.Nama_Lokasi SectionAsal, 
								c.Nama_Lokasi SectionTujuan
							")
							->from("GD_trReturMutasi a")
							->join("mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER")
							->join("mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER")
							->where("a.No_Bukti", $mutation_return_number)
							->get();
							
		return $query->row();
	}

	public static function get_mutation_return_detail( $mutation_return_number )
	{
		$query = self::ci()->db->select("
								a.*, 
								b.Kode_Barang,
								b.Nama_Barang,
								b.Konversi,
								c.Kode_Satuan AS Satuan_Beli,
								d.Kode_Satuan AS Satuan_Stok
							")
							->from("GD_trReturMutasiDetail a")
							->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
							->join("mSatuan c", "b.Beli_Satuan_Id = c.Satuan_ID", "LEFT OUTER")	
							->join("mSatuan d", "b.Stok_Satuan_ID = d.Satuan_ID", "LEFT OUTER")	
							->where("a.No_Bukti", $mutation_return_number)
							->get();
							
		return $query->result();
	}
			
	private static function & ci()
	{
		return get_instance();
	}
}
