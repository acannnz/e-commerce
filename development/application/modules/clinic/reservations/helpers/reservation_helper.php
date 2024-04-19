<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class reservation_helper
{
	
	public static function gen_reservation_number()
	{
		$_ci = self::ci();
		$NOW = new DateTime();		
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );

		$query = $_ci->db->select("MAX(NoReservasi) AS max_number")
							->where([
								"LEN([NoReservasi]) =" => 16, 
								"LEFT(LTRIM([NoReservasi]),2) =" => $date_y, 
								"RIGHT(LEFT(LTRIM([NoReservasi]),9),3) =" => 'RES',
							])
							->get( $_ci->reservation_m->table )
							->row();
							
		if (!empty($query->max_number))
		{
			$query->max_number++;
			$number = $query->max_number;
		} else {			
			$number = (string) (sprintf("%02d%02d%02dRES-%06d", $date_y, $date_m, $date_d, 1));		
		}
		
		return $number;
	}

	public static function get_reservation_queue( $UntukSectionID, $UntukDokterID, $UntukTanggal, $WaktuID )
	{
		$_ci = self::ci();		
		$db_where = [
			'UntukSectionID' => $UntukSectionID, 
			'UntukDokterID' => $UntukDokterID, 
			'UntukTanggal' => $UntukTanggal, 
			'WaktuID' => $WaktuID
		];
		
		$query = $_ci->db
			->select("MAX(NoUrut) as Max")
			->where( $db_where )
			->get( $_ci->reservation_m->table )
			;
		
		$Max = 1;
		if ( $query->num_rows() > 0 )
		{
			$Max = $query->row()->Max + 1;	
			$Max = ($Max % 5) == 0 ? $Max + 1 : $Max;
		}
		return $Max;
	}
		
	private static function & ci()
	{
		return get_instance();
	}
}
