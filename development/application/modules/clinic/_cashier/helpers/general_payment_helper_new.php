<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class general_payment_helper
{

	public static function gen_evidence_number( )
	{
		$CI = self::ci();
		$NOW = new DateTime();
		
		$date_start = $NOW->format( "Y-m-01 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );
		
		$query =  $CI->db->select("MAX(NoBukti) AS MAX")
							->where(array("RJ" => "RJ", "Tanggal >=" => $date_start,"Tanggal <=" => $date_end))
							->where_in("SectionID", array("SEC079"))
							->get( "SIMtrKasir" )
							->row()
							;
							
		if (!empty($query->MAX))
		{
			$number = $query->MAX;
			list($front, $number) = explode("-", $number);
			$number++;
			$number = (string) (sprintf(self::_gen_format_evidence_number(), $date_y, $date_m, $date_d, 'INVRJ', $number));	
		} else {
			$number = (string) (sprintf(self::_gen_format_evidence_number(), $date_y, $date_m, $date_d, 'INVRJ', 1));
		}
						
		return $number;
	}
	
	private static function _gen_format_evidence_number()
	{
		$format = "%02d%02d%02d%s-%06d";
		return $format;
	}
	
	public static function get_item( $NoReg )
	{
		$query = self::ci()->db
			->select("
				a.*, 
				b.Kode_Supplier, 
				b.Nama_Supplier, 
				c.SectionName,
				d.Status,
				d.StatusBayar,
				d.StatusPeriksa,
				d.NRM,
				d.NamaPasien_Reg,
				d.AlamatPasien_Reg,
				d.TglReg,
				d.JamReg,
				d.JenisKerjasamaID,
				e.Alamat
			")
			->from("SIMtrDataRegPasien a")
			->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
			->join("SIMmSection c", "a.SectionID = c.SectionID", "LEFT OUTER")
			->join("SIMtrRegistrasi d", "a.NoReg = d.NoReg", "LEFT OUTER")
			->join("mPasien e", "d.NRM = e.NRM", "LEFT OUTER")
			->where(array(
					"a.NoReg" => $NoReg,
				))
			->order_by("a.RJ", "ASC")
			->order_by("a.Nomor", "DESC")
			->limit(1)
			->get()
			;
		
		if ( $query->num_rows() > 0)
		{
			return $query->row();
		}
		
		return (object) array();
	}
	
	public static function get_detail_cost( $NoReg, $Status )
	{
		$state = array("RI" => 0, "RJ" => 1);
		return self::ci()->db
			->query("Select * from dbo.GetDetailRincianBiaya('$NoReg', $state[$Status] ) ")
			->result();
	}

	public static function get_total_cost( $NoReg, $Status )
	{
		$state = array("RI" => 0, "RJ" => 1);
		return self::ci()->db
			->query("Select   
						round(sum((Qty*(Nilai-(Nilai*disc/100))) + Hext),0)  as Nilai,
						round(sum((Qty*(HargaOrig-(HargaOrig*disc/100))) + Hext),0) as NilaiOrig
					from dbo.GetDetailRincianBiaya('$NoReg', $state[$Status] ) 
					")
			->row();
	}

	public static function get_group_detail_cost( $NoReg, $Status )
	{
		$state = array("RI" => 0, "RJ" => 1);
		return self::ci()->db
			->query("Select   
						round(sum((Qty*(Nilai-(Nilai*disc/100))) + Hext),0)  as Nilai,
						round(sum((Qty*(HargaOrig-(HargaOrig*disc/100))) + Hext),0) as NilaiOrig,
						0 as KelebihanPlafon,
						GroupJasa,
						GroupJasaID,
						round(sum(Qty*DiskonTdkLangsung),0)  as DiskonTdkLangsung 
					from dbo.GetDetailRincianBiaya('$NoReg', $state[$Status] ) 
					group by GroupJAsa,GroupJasaID")
			->result();
	}
	
	public static function find_discount( $nobukti, $iddiscount )
	{
		return (int) self::ci()->db
			->where(array(
					"IDDiscount" => $iddiscount,
					"NoBukti" => $nobukti,
				))
			->count_all_results( "SIMtrKasirDiscount" )
			;
	}

	public static function get_detail_discount( $NoBukti = NULL )
	{
		$query = self::ci()->db
				->select( " a.NoBukti,
							a.IDDiscount,
							a.DokterID as IDDokter,
							a.Persen, 
							a.NilaiDiscount as NilaiDiskon,
							a.Keterangan,
							a.JasaID as IDJasa,
							a.KelasID as Kelas,
							b.Nama_Supplier as NamaDokter,
							c.NamaDiscount,
							d.JasaName as NamaJasa" 
						)
				->from( "SIMtrKasirDiscount a" )
				->join( "mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER" )
				->join( "mDiscount c", "a.IDDiscount = c.IDDiscount", "LEFT OUTER" )
				->join( "SIMmListJasa d", "a.JasaID = d.JasaID", "LEFT OUTER" )
				->where("a.NoBukti", $NoBukti)
				->get();

		if ( $query->num_rows() > 0 )
		{
			$collection = array();
			foreach ($query->result() as $row ) 
			{
				$row->NilaiDiskon = number_format( $row->NilaiDiskon, 2, '.', ',');
				$collection[]= $row;
			}
			return $collection;
		}
		
		return false;
	}
	
	public static function get_detail_payment( $NoBukti = NULL )
	{
		$query = self::ci()->db
			->where("NoBukti", $NoBukti )
			->get("SIMtrKasirDetail")
			;
		
		if ( $query->num_rows() > 0)
		{
			$collection = array();
			foreach( $query->result() as $row )
			{
				$collection[ $row->IDBayar ] = $row->NilaiBayar;
			}
			
			return $collection;
		}
		
		return false;
	}
	
	public static function check_invoice_state( $NoBukti = NULL )
	{
		if ( empty($NoBukti) )
		{
			return FALSE;
		}
		
		$audit = self::ci()->db->select("Audit AS state")
						->where ("NoBukti", $NoBukti)
						->get("SIMtrKasir")
						->row()
						;

		$closing = self::ci()->db->select("NoInvoice AS state")
						->where ("NoInvoice", $NoBukti)
						->get("SIMtrKasirClosingDetail")
						->row()
						;

		$outstanding = self::ci()->db->select("NoInvoice AS state")
						->where ( array( "NoInvoice" => $NoBukti, "Batal" => 0))
						->get("SIMtrPembayaranOutStanding")
						->row()
						;
														
		$collection = (object) array(
			"audit" => (object) array( 
				"state" => @$audit->state, 
				"message" => "Pasien Sudah Di Proses Audit, Tidak Dapat Melanjutkan Transaksi" ),
			"closing" => (object) array( 
				"state" => @$closing->state,
				"message" => "Pasien Sudah Di Proses Closing, Tidak Dapat Melanjutkan Transaksi" ),
			"outstanding" => (object) array( 
				"state" => @$outstanding->state,
				"message" => "Pasien Sudah Melakukan Pembayaran Outstanding, Tidak Dapat Melanjutkan Transaksi" ),
		);

		return $collection;
	}
	
	public static function approval( $Approve_User, $Approve_Pswd )
	{
		$count = (int) self::ci()->db
					->where( array("Approve_User" => $Approve_User, "Approve_Pswd" => $Approve_Pswd, "Approve_Function" => "CANCEL INVOICE PASIEN") )
					->count_all_results("ListApprove");
		
		return $count > 0 ? TRUE : FALSE;
	}
	
	public static function get_kwitansi( $NoBukti )
	{
		$query = self::ci()->db
			->select("
				a.*, 
				b.NamaPasien_Reg AS NamaPasien, 
			")
			->from("SIMtrKasir a")
			->join("SIMtrRegistrasi b", "a.NoReg = b.NoReg", "LEFT OUTER")
			->where(array(
					"a.NoBukti" => $NoBukti,
				))
			->get()
			;
		
		if ( $query->num_rows() > 0)
		{
			return $query->row();
		}
	}

	public static function money_to_text($angka){
		$money = array("","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan","sepuluh","sebelas");
		if($angka < 12 ){
			return " ".$money[$angka];
		}elseif($angka < 20 ){
			return self::money_to_text($angka-10)." belas";
		}elseif($angka < 100 ){
			return self::money_to_text($angka/10)." puluh" . self::money_to_text($angka%10);
		}elseif($angka < 200){
			return "seratus".self::money_to_text($angka-100);
		}elseif($angka < 1000){
			return self::money_to_text($angka /100)." ratus".self::money_to_text($angka%100);
		}elseif($angka < 2000){
			return "seribu".self::money_to_text($angka-1000);
		}elseif($angka < 1000000 ){
			return self::money_to_text($angka/1000). " ribu".self::money_to_text($angka%1000);
		}elseif($angka < 1000000000 ){
			return self::money_to_text($angka < 1000000). " juta".self::money_to_text($angka%1000000);
		}
	}
		
	private static function & ci()
	{
		return get_instance();
	}
}
