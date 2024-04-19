<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class item_usage_helper 
{
 
	public static function gen_evidence_number(  )
	{
		$CI = self::ci();
		$NOW = new DateTime();
		
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );

		$query =  $CI->db
						->select("MAX(NoBukti) as max_number")
						->where(array(
								"Tanggal >=" => $date_start,
								"Tanggal <=" => $date_end,
							))
						->get( "SIMtrPemakaian" )
						->row()
					;
		if (!empty($query->max_number))
		{
			$query->max_number++;
			$number = $query->max_number;
		} else {
			$number = (string) (sprintf(self::_gen_evidence_number(), $date_y, $date_m, $date_d, 1));		
		}
		
		
		return $number;
	}
	
	private static function _gen_evidence_number()
	{
		$format = "%02d%02d%02dPMB-%06d";
		return $format;
	}
			
	private static function & ci()
	{
		return get_instance();
	}

}