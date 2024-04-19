<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class pharmacy_helper
{
	
	public static function gen_evidence_number( $SectionID, $date = NULL )
	{
		$CI = self::ci();
		$NOW = ($date) ? DateTime::createFromFormat('Y-m-d H:i:s', $date) : new DateTime();	
		
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );
		
		$section = $CI->db->where( array("SectionID" => $SectionID) )->get("SIMmSection")->row();
		$query =  $CI->db->select("MAX(RIGHT(NoBukti, 6)) AS MAX")
							->where(array("DATEPART(YEAR, Tanggal) =" => $NOW->format( "Y" ), "SectionID" => $SectionID))
							->get("BILLFarmasi")
							->row();
		
		if (!empty($query->MAX))
		{					
			$max_number = ++$query->MAX;
			//$arr_number = explode('-', $max_number);
			$number = (string) sprintf(self::_gen_format_evidence_number(), $date_y, $date_m, $date_d, $section->KodeNoBukti, $max_number);//$arr_number[1]);
		} else {
			$number = (string) sprintf(self::_gen_format_evidence_number(), $date_y, $date_m, $date_d, $section->KodeNoBukti, 2);
		}

		return $number;
	}
	
	private static function _gen_format_evidence_number()
	{
		$format = "%02d%02d%02d%s-%06d";
		return $format;
	}

	public static function gen_prescription_number(  )
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
						->select("MAX(NoResep) as max_number")
						->where(array(
								"Tanggal >=" => $date_start,
								"Tanggal <=" => $date_end,
							))
						->get( "SIMtrResep" )
						->row()
					;
		if (!empty($query->max_number))
		{
			$query->max_number++;
			$number = $query->max_number;
		} else {
			$number = (string) (sprintf(self::_gen_prescription_number(), $date_y, $date_m, $date_d, 1));		
		}
		
		
		return $number;
	}
	
	private static function _gen_prescription_number()
	{
		$format = "%02d%02d%02dRSP-%06d";
		return $format;
	}
	
	public static function insert_warehouse_fifo( Array $args )
	{
		$defaults = [
			'location_id' => 0, 
			'item_id' => 0,  
			'item_unit_code' => 0,  
			'qty' => 0, 
			'price' => 0,  
			'conversion' => 1,  
			'evidence_number' => '',  
			'trans_type_id' => 0,
			'in_out_state' => 1,
			'trans_date' => date('Y-m-d'),  
			'exp_date' => date('Y-m-d'),  
			'item_type_id' => 0, 
		];
		
		$arguments = array_merge( $defaults, $args );
		extract($arguments);
		
		$price = $price / $conversion;
		
		/*EXEC IsiKartuGudangFIFO 
			Lokasi_ID, Barang_Id, 'Kode_Satuan_Stok', dIntQtyTerima, Harga_Beli / Barang_Konversi,
			'Penerimaan_No_Penerimaan', jenisTranskasiID, in_out_state, Penerimaan_Tgl_Penerimaan, Exp_Date, JenisBarangID
			in state = 1
			out state= 0	
		*/
		
		self::ci()->db->query("
				EXEC IsiKartuGudangFIFO 
					{$location_id}, {$item_id}, '{$item_unit_code}', {$qty}, {$price},
					'{$evidence_number}', {$trans_type_id}, {$in_out_state}, '{$trans_date}', '{$exp_date}', {$item_type_id} 
			");
		
	}
	
	private static function & ci()
	{
		return get_instance();
	}
}
