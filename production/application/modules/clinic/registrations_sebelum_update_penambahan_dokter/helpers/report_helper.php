<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class report_helper 
{
 
	public static function get_polyclinic_registrations( $date_start, $date_end, $DokterID = NULL )
	{
		
		$db = self::ci()->db;
		$select_table = <<<EOSQL
			b.NoReg,
			b.TglReg,
			b.NRM,
			b.PasienBaru,
			c.NamaPasien, 
			c.Alamat,
			c.Phone,
			d.Nama_Supplier
EOSQL;
		
		$db
			->select( $select_table )
			->from( "SIMtrRegistrasiTujuan a" )
			->join( "SIMtrRegistrasi b", "a.NoReg = b.NoReg", "INNER" )
			->join( "mPasien c", "b.NRM = c.NRM", "INNER" )
			->join( "mSupplier d", "a.DokterID = d.Kode_Supplier", "LEFT OUTER" )
			;
		
		if( $DokterID )
		{
			$db->where( "a.DokterID", $DokterID );
		} 
			
		$db->where( "b.Batal", 0 );
		$db->where( "b.TglReg >=", $date_start);
		$db->where( "b.TglReg <=", $date_end );			

		$db->order_by( "b.NoReg");
		
		$query = $db->get();
		if( $query->num_rows() )
		{
			return $query->result();
		}
		
		return FALSE;
	}

	public static function get_registration_patient_types( $date_start, $date_end )
	{
		
		$db = self::ci()->db;
		
		// Get All Type of patients
		$type = $db->get("SIMmJenisKerjasama");
		
		if ( $type->num_rows() > 0 )
		{
			$collection = array();
			foreach ( $type->result() as $row )
			{
	
				$from_table = "SIMtrRegistrasi";
				$select_table = <<<EOSQL
					count(JenisKerjasamaID) AS total_type
EOSQL;
				
				$db
					->select( $select_table )
					->from( $from_table )
					;
					
				$db->group_start();
					$db->where( "JenisKerjasamaID", $row->JenisKerjasamaID );
					$db->where( "Batal", 0 );
					$db->where( "TglReg >=", $date_start);
					$db->where( "TglReg <=", $date_end );			
				$db->group_end();
				
				$query = $db->get()->row();
				$row->total_type = 	$query->total_type > 0 ? $query->total_type : 0;
				
				$collection[] = $row; 
			}
			
			return $collection;
		}
		
		return FALSE;
		
	}
	
	public static function get_registration_label( $NoReg ){
		$db = self::ci()->db;
		
		$query = $db->select("
							a.NoReg, 
							a.TglReg,
							a.NRM, 
							a.NamaPasien_Reg, 
							a.UmurThn, 
							b.TglLahir, 
							CASE 
								WHEN b.JenisKelamin = 'M' 
								   THEN 'L' 
								   ELSE 'P' 
							END as JenisKelamin ")
					->from("SIMtrRegistrasi a")
					->join("mPasien b", "a.NRM = b.NRM", "LEFT OUTER")
					->where("a.NoReg", $NoReg)
					->get()
					;
					
		return $query->num_rows() > 0 ? $query->row() : FALSE;
		
	}
	
	public static function get_first_registration_patient( $date_start,  $date_end){
		$db = self::ci()->db;

		$P = 0;
		$L = 0;
		
		$data = $db->select("
						a.NoReg, 
						a.TglReg,
						a.NRM, 
						a.NamaPasien_Reg, 
						a.UmurThn, 
						a.TglLahir, 
						a.JenisKelamin")
					->from("VW_Registrasi a")
					->where([
								"a.PasienBaru " => 1,
								"a.TglReg >=" => $date_start,
								"a.TglReg <=" => $date_end,
					])->get()->result();
		
		foreach ($data as $key => $value) {

			if ($value->JenisKelamin == 'M') {
				$L++;
			} elseif ($value->JenisKelamin == 'F') {
				$P++;
			} 

			$diagnosa = $db->select("
								c.Descriptions
							")
							->from('SIMtrRJ a')
							->join("SIMtrRJDiagnosaAwal b", "b.NOBukti = a.NoBukti", "LEFT OUTER")
							->join("mICD c", "c.KodeICD = b.KodeICD", "LEFT OUTER")
							->where("a.RegNo", $value->NoReg)
							->get()
							->row();

			$value->Diagnosa = $diagnosa;
			$collection['data'][] = $value;
		}
		
		$collection['JenisKelamin'][] = [
			"JenisKelamin" => 'Laki - Laki',
			"Total" => $L,
		];
		$collection['JenisKelamin'][] = [
			"JenisKelamin" => 'Perempuan',
			"Total" => $P,
		];

		return !empty($data) ? $collection : FALSE;
		
	}
	
	private static function & ci()
	{
		return get_instance();
	}

}