<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class drug_payment_helper
{
	public static function gen_evidence_number( $date = NULL )
	{
		$CI = self::ci();	
		$NOW = ($date) ? DateTime::createFromFormat('Y-m-d H:i:s', $date) : new DateTime();
		
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );
		
		$query =  $CI->db->select("MAX(RIGHT(NoBukti, 6)) AS MAX")
							->where(array("DATEPART(YEAR, TANGGAL) =" => $NOW->format( "Y" )))
							->get( "SIMtrPembayaranObatBebas" )
							->row();
							
		if (!empty($query->MAX))
		{			
			$max_number = ++$query->MAX;
			//$arr_number = explode('/', $max_number);
			$number = (string) (sprintf(self::_gen_format_evidence_number(), $date_y, $date_m, $date_d, 'POB', $max_number));
		} else {
			$number = (string) (sprintf(self::_gen_format_evidence_number(), $date_y, $date_m, $date_d, 'POB', 1));		
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
		
		$query = $CI->db->select("a.*, z.NoBukti AS NoBuktiBill,  z.JumlahBayar, z.NilaiKembalian, b.Nama_Supplier, c.SectionName, d.JenisKerjasama")
					->from("BILLFarmasi a")
					->join("SIMtrPembayaranObatBebas z", "a.NoBukti = z.NoBuktiFarmasi", "INNER")
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
		
		$query = $CI->db->select("a.JmlObat AS Qty, a.JmlRetur, a.Harga, a.Disc, a.Nama_Barang, a.NamaResepObat, a.HExt, a.BiayaResep")
					->from("BILLFarmasiDetail a")
					//->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER JOIN")
					->where("a.NoBukti", $NoFarmasi)
					->get()
					;
		if ( $query->num_rows() > 0)
		{
			return $query->result(); 
		}
		
		return FALSE; 		
	}

	public static function get_type_payment_used( $NoFarmasi )
	{
		$CI = self::ci();	
		
		$query = $CI->db
						->where( array("NoBuktiFarmasi" => $NoFarmasi, "Batal" => 0))
						->get("SIMtrPembayaranObatBebas")
						;

		if ( $query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$collection = array(
					'IKS' => $row->NilaiPembayaranIKS,
					'BPJS' => $row->NilaiPembayaranBPJS,
					'Beban Klinik' => $row->NilaiPembayaranBebanRS,
					'Kartu Kredit' => $row->NilaiPembayaranCC,
					'Add Charge' => $row->NilaiPembayaranCC * $row->AddCharge / 100,
					'Hutang' => $row->Kredit,
					'Tunai' => $row->JumlahBayar, //$row->NilaiPembayaran,
					'Kembalian' => $row->NilaiKembalian,
				);
			} 
			return $collection;
		}
		
		return FALSE; 		
	}
		
	private static function & ci()
	{
		return get_instance();
	}
}
