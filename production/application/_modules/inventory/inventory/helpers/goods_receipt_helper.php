<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Goods_receipt_helper
{	
	public static function check_closing_period( $date )
	{
		$date = DateTime::createFromFormat('Y-m-d', $date);
		$month = $date->format('m');
		$year = $date->format('Y');
		
		return (boolean)
			self::ci()->db->where(array(
								"DATEPART(YEAR, Tanggal)=" => $year,
								"DATEPART(MONTH, Tanggal)=" => $month
							))
							->count_all_results('GD_trPostedBulanan');
	}
	
	private static function & ci()
	{
		return get_instance();
	}	

}
