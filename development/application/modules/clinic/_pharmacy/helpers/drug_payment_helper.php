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
		
		$query = $CI->db->select("a.*, z.NoBukti AS NoBuktiBill,  z.JumlahBayar, z.NilaiKembalian, b.Nama_Supplier, e.Alamat, e.TglLahir, e.NRM, e.NamaPasien, e.NoIdentitas, e.Phone, e.JenisKelamin, e.Email, e.PenanggungNama, e.JenisPasien, c.SectionName, d.JenisKerjasama, z.AddCharge")
					->from("BILLFarmasi a")
					->join("SIMtrPembayaranObatBebas z", "a.NoBukti = z.NoBuktiFarmasi", "INNER")
					->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
					->join("SIMmSection c", "a.SectionID = c.SectionID", "LEFT OUTER")
					->join("SIMmJenisKerjasama d", "a.KerjasamaID = d.JenisKerjasamaID", "LEFT OUTER")
					->join("mPasien e", "a.NRM = e.NRM", "LEFT OUTER")
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
		
		$query = $CI->db->select("a.JmlObat AS Qty, a.JmlRetur, a.Harga, a.Disc, a.Nama_Barang, a.NamaResepObat, a.HExt, a.BiayaResep, a.Satuan, b.Barang_ID")
					->from("BILLFarmasiDetail a")
					->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER JOIN")
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
	
	public static function money_to_text($bilangan){
		// $money = array("satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan","sepuluh","sebelas","dua belas");
		// return " ".$money[$angka];
		// if($angka < 12 ){
		// 	return " ".$money[$angka];
		// }elseif($angka < 20 ){
		// 	return self::money_to_text($angka-10)." belas";
		// }elseif($angka < 100 ){
		// 	return self::money_to_text($angka/10)." puluh" . self::money_to_text($angka%10);
		// }elseif($angka < 200){
		// 	return " seratus".self::money_to_text($angka-100);
		// }elseif($angka < 1000){
		// 	return self::money_to_text($angka /100)." ratus".self::money_to_text($angka%100);
		// }elseif($angka < 2000){
		// 	return "seribu".self::money_to_text($angka-1000);
		// }elseif($angka < 1000000 ){
		// 	return self::money_to_text($angka/1000). " ribu".self::money_to_text($angka%1000);
		// }elseif($angka < 1000000000 ){
		// 	return self::money_to_text($angka < 1000000). " juta".self::money_to_text($angka%1000000);
		// }

		

		$angka = array('0','0','0','0','0','0','0','0','0','0',
				'0','0','0','0','0','0');
		$kata = array('','satu','dua','tiga','empat','lima',
			'enam','tujuh','delapan','sembilan');
		$tingkat = array('','ribu','juta','milyar','triliun');

		$panjang_bilangan = strlen($bilangan);

		/* pengujian panjang bilangan */
		if ($panjang_bilangan > 15) {
			$kalimat = "Diluar Batas";
			return $kalimat;
		}

		/* mengambil angka-angka yang ada dalam bilangan,
		dimasukkan ke dalam array */
		for ($i = 1; $i <= $panjang_bilangan; $i++) {
			$angka[$i] = substr($bilangan,-($i),1);
		}

		$i = 1;
		$j = 0;
		$kalimat = "";


		/* mulai proses iterasi terhadap array angka */
		while ($i <= $panjang_bilangan) {

			$subkalimat = "";
			$kata1 = "";
			$kata2 = "";
			$kata3 = "";

			/* untuk ratusan */
			if ($angka[$i+2] != "0") {
				if ($angka[$i+2] == "1") {
					$kata1 = "seratus";
				} else {
					$kata1 = $kata[$angka[$i+2]] . " ratus";
				}
			}

			/* untuk puluhan atau belasan */
			if ($angka[$i+1] != "0") {
				if ($angka[$i+1] == "1") {
					if ($angka[$i] == "0") {
						$kata2 = "sepuluh";
					} elseif ($angka[$i] == "1") {
						$kata2 = "sebelas";
					} else {
						$kata2 = $kata[$angka[$i]] . " belas";
					}
				} else {
					$kata2 = $kata[$angka[$i+1]] . " puluh";
				}
			}

			/* untuk satuan */
			if ($angka[$i] != "0") {
				if ($angka[$i+1] != "1") {
					$kata3 = $kata[$angka[$i]];
				}
			}

			/* pengujian angka apakah tidak nol semua,
			lalu ditambahkan tingkat */
			if (($angka[$i] != "0") OR ($angka[$i+1] != "0") OR
			($angka[$i+2] != "0")) {
				$subkalimat = "$kata1 $kata2 $kata3 " . $tingkat[$j] . " ";
			}

			/* gabungkan variabe sub kalimat (untuk satu blok 3 angka)
			ke variabel kalimat */
			$kalimat = $subkalimat . $kalimat;
			$i = $i + 3;
			$j = $j + 1;

		}

		/* mengganti satu ribu jadi seribu jika diperlukan */
		if (($angka[5] == "0") AND ($angka[6] == "0")) {
			$kalimat = str_replace("satu ribu","seribu",$kalimat);
		}

		return trim($kalimat. "");
	}
	
	public static function money_to_text_english($number){
	$numbers = array('0','0','0','0','0','0','0','0','0','0',
			'0','0','0','0','0','0');
	$words = array('','one','two','three','four','five',
		'six','seven','eight','nine');
	$levels = array('','thousand','million','billion','trillion');

	$number_length = strlen($number);

	/* checking the length of the number */
	if ($number_length > 15) {
		$sentence = "Out of Range";
		return $sentence;
	}

	/* extracting the digits from the number,
	and storing them in an array */
	for ($i = 1; $i <= $number_length; $i++) {
		$numbers[$i] = substr($number,-($i),1);
	}

	$i = 1;
	$j = 0;
	$sentence = "";


	/* starting the iteration process on the numbers array */
	while ($i <= $number_length) {

		$subsentence = "";
		$word1 = "";
		$word2 = "";
		$word3 = "";

		/* for hundreds */
		if ($numbers[$i+2] != "0") {
			if ($numbers[$i+2] == "1") {
				$word1 = "one hundred";
			} else {
				$word1 = $words[$numbers[$i+2]] . " hundred";
			}
		}

		/* for tens or teens */
		if ($numbers[$i+1] != "0") {
			if ($numbers[$i+1] == "1") {
				if ($numbers[$i] == "0") {
					$word2 = "ten";
				} elseif ($numbers[$i] == "1") {
					$word2 = "eleven";
				} else {
					$word2 = $words[$numbers[$i]] . "teen";
				}
			} else {
				$word2 = $words[$numbers[$i+1]] . "ty";
			}
		}

		/* for ones */
		if ($numbers[$i] != "0") {
			if ($numbers[$i+1] != "1") {
				$word3 = $words[$numbers[$i]];
			}
		}

		/* checking if the number is not all zeros,
		then adding the level */
		if (($numbers[$i] != "0") OR ($numbers[$i+1] != "0") OR
		($numbers[$i+2] != "0")) {
			$subsentence = "$word1 $word2 $word3 " . $levels[$j] . " ";
		}

		/* concatenating the subsentence variable (for each block of 3 digits)
		to the sentence variable */
		$sentence = $subsentence . $sentence;
		$i = $i + 3;
		$j = $j + 1;

	}

	/* replacing "one thousand" with "a thousand" if necessary */
	if (($numbers[5] == "0") AND ($numbers[6] == "0")) {
		$sentence = str_replace("one thousand","a thousand",$sentence);
	}

	return trim($sentence. "");
}

		
	private static function & ci()
	{
		return get_instance();
	}
}
