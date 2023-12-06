<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class report_helper 
{
 
	public static function get_patien_reservations( $date_start, $date_end )
	{
		
		$db = self::ci()->db;
		
		$from_table = "SIMtrReservasi a";
		$select_table = <<<EOSQL
			a.NoReservasi,
			a.Nama,
			a.Tanggal AS TanggalReservasi, 
			a.UntukJam AS UntukTanggalJam,
			a.Alamat,
			a.Phone,
			b.Nama_Supplier,
			c.SectionName
			
EOSQL;
		
		$db
			->select( $select_table )
			->from( $from_table )
			->join( "mSupplier b", "a.UntukDokterID = b.Kode_Supplier", "LEFT OUTER" )
			->join( "SIMmSection c", "a.UntukSectionID = c.SectionID", "LEFT OUTER" )
			;
			
		$db->group_start();
			$db->where( "a.Batal", 0 );
			$db->where( "a.Tanggal >=", $date_start);
			$db->where( "a.Tanggal <=", $date_end );			
		$db->group_end();
		$db->order_by( "a.NoReservasi");
		
		$query = $db->get();
		if( $query->num_rows() )
		{
			return $query->result();
		}
		
		return FALSE;
	}

	private static function & ci()
	{
		return get_instance();
	}

}