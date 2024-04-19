<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

final class general_payment_helper
{
	private static $_ci;
	private static $user_auth;

	public static function init()
	{
		self::$_ci = $_ci = self::ci();
		self::$user_auth = $_ci->simple_login->get_user();
	}

	public static function create_general_payment($transaction, $payments, $discount, $additional, $item)
	{

		self::init();
		$_ci = self::$_ci;

		$transaction->NoBukti = self::gen_evidence_number();
		$transaction->Tanggal = date("Y-m-d");
		$transaction->Jam = date("Y-m-d H:i:s");
		$transaction->UserID = self::$user_auth->User_ID;
		$transaction->Shift = $_ci->session->userdata('shift_id');
		$transaction->SectionID = 'SEC079';
		$transaction->FromDate = $item->TglReg;
		$transaction->ToDate = date("Y-m-d");

		$JenisBayar = [];
		$JenisBayar['DIJAMIN BPJS']['NilaiBayar'] = $payments['BPJS'];
		// $JenisBayar['BEBAN/KEUNTUNGAN KLINIK']['NilaiBayar'] = $payments['Beban'];
		// $JenisBayar['BON KARYAWAN']['NilaiBayar'] = $payments['BonKaryawan'];
		$JenisBayar['DIJAMIN KE PERUSAHAAN']['NilaiBayar'] = $payments['DijaminPerusahaan'];
		$JenisBayar['KARTU KREDIT/DEBIT']['NilaiBayar'] = $payments['KartuKredit'];
		$JenisBayar['KARTU KREDIT/DEBIT 2']['NilaiBayar'] = $payments['KartuKredit_2'];
		$transaction->NilaiPembayaranKKAwal = $payments['KartuKredit'] > 0 ? $payments['KartuKredit'] : 0;
		$transaction->NilaiPembayaranKKAwal2 = $payments['KartuKredit_2'] > 0 ? $payments['KartuKredit_2'] : 0;
		$JenisBayar['KREDIT / BON']['NilaiBayar'] = $payments['Kredit'];
		$JenisBayar['TAGIHAN L.O.G']['NilaiBayar'] = $payments['TagihanLOG'];
		$JenisBayar['TUNAI']['NilaiBayar'] = $payments['Tunai'];
		// $JenisBayar['SKTM']['NilaiBayar'] = 0;
		// $JenisBayar['KARTU BALI SEHAT']['NilaiBayar'] = 0;
		// $JenisBayar['EVENT HEALTHY DAY']['NilaiBayar'] = 0;
		// print_r($transaction);exit;
		$_ci->db->trans_begin();
		$_registration = $_ci->registration_model->get_one($transaction->NoReg);
		$transaction->SectionPerawatanID = $_registration->SectionPerawatanID;

		$_ci->cashier_model->create($transaction);

		$_update_registration = [
			'TglKeluar' => date("Y-m-d"),
			'JamKeluar' => date("Y-m-d h:m:s"),
			'PxKeluar_Pulang' => 1,
			'StatusPeriksa' => 'Sudah',
			'StatusBayar' => 'Sudah Bayar',
			'ProsesPayment' => 0
		];
		$_ci->registration_model->update($_update_registration, $transaction->NoReg);

		$_update_registration_data = [
			'Pulang_Tanggal' => date('Y-m-d'),
			'PxKeluar_Pulang' => 1,
			'Out' => 1,
		];
		$_ci->registration_data_model->update($_update_registration_data, $transaction->NoReg);

		// Update data mPasien, for kunjungan, SedangDirawat = 0
		$_ci->db
			->set("TotalKunjunganRawatJalan", "TotalKunjunganRawatJalan + 1", FALSE)
			->set("KunjunganRJ_TahunIni", "KunjunganRJ_TahunIni + 1", FALSE)
			->set("SedangDirawat", 0)
			->where("NRM", $item->NRM)
			->update($_ci->patient_model->table);

		// Insert user Activity
		$time = date("Y-m-d H:i:s");
		$activities_description = sprintf("MULAI : %s # %s # %s # %s # %s # %s", $additional->time_start_proccess, "INPUT KASIR.", $transaction->NoBukti, $item->NRM, $item->NamaPasien, $item->SectionName);
		$_ci->db->query("EXEC InsertUserActivities '{$transaction->Tanggal}','$time', " . self::$user_auth->User_ID . ", '{$item->NoBukti}','{$activities_description}','{$_ci->cashier_model->table}'");

		// input discount if not empty
		if (!empty($discount)) {
			foreach ($discount as $row) {
				$row['NoBukti'] = $transaction->NoBukti;
				$_ci->cashier_discount_model->create($row);
			}
		}

		// Jenis-Jenis Pembayaran yang digunakan					
		$tipeBayar = $_ci->db->select("IDBayar,Description")->where("Active", 1)->get("mJenisBayar");
		foreach ($tipeBayar->result() as $row) {

			$JenisBayar[$row->Description]['NoBukti'] = $transaction->NoBukti;
			$JenisBayar[$row->Description]['IDBayar'] = $row->IDBayar;
		}

		// INPUT DETAIL PEMBAYARAN & Insert user activity
		foreach ($JenisBayar as $key => $val) {
			// print_r($JenisBayar);exit;
			$_ci->cashier_detail_model->create($val);
			$activities_description = sprintf(" %s # %s # %s # %s # %s", "INPUT DETAIL BAYAR KASIR.", $transaction->NoBukti, $key, "NILAI", $val['NilaiBayar']);
			$_ci->db->query("EXEC InsertUserActivities '$transaction->Tanggal','$time', " . self::$user_auth->User_ID . ", '$item->NoBukti','$activities_description','SIMtrKasirDetail'");
		}

		// Insert to SIMtrKasirDetailGroupJasa
		$detail_group_jasa = [];
		if (!empty($item->group_detail_cost)) {
			foreach ($item->group_detail_cost as $row) {
				$detail_group_jasa[] = [
					"NoBukti" => $transaction->NoBukti,
					"GroupJasaID" => $row->GroupJasaID,
					"Nilai" => $row->Nilai,
					"NilaiOrig" => $row->NilaiOrig,
				];
			}
			$_ci->cashier_detail_service_group_model->mass_create($detail_group_jasa);
		}


		if ($_ci->db->trans_status() === FALSE) {
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => lang('global:created_failed'),
				"code" => 500
			];
		}
		//$_ci->db->trans_rollback();
		$_ci->db->trans_commit();
		$get_NoReg =  $_ci->db->where(["NoReg" => $item->NoReg, "Batal" => 0])->get("SIMtrKasir")->row();
		// print_r($get_NoReg);
		// exit;
		return [
			// "NoBukti" => $transaction->NoBukti,
			"NoBukti" => $get_NoReg->NoBukti,
			"status" => 'success',
			"message" => lang('global:created_successfully'),
			"code" => 200
		];
	}

	public static function update_general_payment($transaction, $payments, $discount, $additional, $item)
	{

		self::init();
		$_ci = self::$_ci;

		unset($transaction->Jam);
		unset($transaction->Tanggal);
		unset($transaction->UserID);
		unset($transaction->Shift);
		unset($transaction->SectionID);
		unset($transaction->SectionPerawatanID);

		$JenisBayar = [];
		$JenisBayar['DIJAMIN BPJS']['NilaiBayar'] = $payments['BPJS'];
		$JenisBayar['BEBAN/KEUNTUNGAN KLINIK']['NilaiBayar'] = $payments['Beban'];
		$JenisBayar['BON KARYAWAN']['NilaiBayar'] = $payments['BonKaryawan'];
		$JenisBayar['DIJAMIN KE PERUSAHAAN']['NilaiBayar'] = $payments['DijaminPerusahaan'];
		$JenisBayar['KARTU KREDIT/DEBIT']['NilaiBayar'] = $payments['KartuKredit'];
		$transaction->NilaiPembayaranKKAwal = $payments['KartuKredit'] > 0 ? $payments['KartuKredit'] : 0;
		$JenisBayar['KREDIT / BON']['NilaiBayar'] = $payments['Kredit'];
		$JenisBayar['TAGIHAN L.O.G']['NilaiBayar'] = $payments['TagihanLOG'];
		$JenisBayar['TUNAI']['NilaiBayar'] = $payments['Tunai'];

		$_ci->db->trans_begin();

		$_ci->cashier_model->update($transaction, $item->NoBukti);
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		$activities_description = sprintf("%s # %s # %s # %s # %s", "EDIT KASIR.", $item->NoBukti, $item->NRM, $item->NamaPasien, $item->SectionName);
		$_ci->db->query("EXEC InsertUserActivities '{$date}','{$time}', " . self::$user_auth->User_ID . ", '{$item->NoBukti}','{$activities_description}','SIMtrKasir'");

		if (!empty($discount)) {
			$_ci->cashier_discount_model->delete_by(["NoBukti" => $item->NoBukti]);
			foreach ($discount as $row) {
				$row['NoBukti'] = $item->NoBukti;
				$_ci->cashier_discount_model->create($row);
			}
		} else {
			$_ci->cashier_discount_model->delete_by(['NoBukti' => $item->NoBukti]);
		}

		// Jenis-Jenis Pembayaran yang digunakan					
		$tipeBayar = $_ci->db->select("IDBayar, Description")->where("Active", 1)->get("mJenisBayar");
		foreach ($tipeBayar->result() as $row) {

			$JenisBayar[$row->Description]['NoBukti'] = $item->NoBukti;
			$JenisBayar[$row->Description]['IDBayar'] = $row->IDBayar;
		}

		// INPUT DETAIL PEMBAYARAN & Insert user activity
		foreach ($JenisBayar as $key => $val) {
			$_ci->cashier_detail_model->update_by($val, ["NoBukti" => $item->NoBukti, "IDBayar" => $val['IDBayar']]);
			$activities_description = sprintf(" %s # %s # %s # %s # %s", "EDIT DETAIL BAYAR KASIR.", $item->NoBukti, $key, "NILAI", $val['NilaiBayar']);
			$_ci->db->query("EXEC InsertUserActivities '{$date}','{$time}', " . self::$user_auth->User_ID . ", '{$item->NoBukti}','{$activities_description}','SIMtrKasirDetail'");
		}

		$_ci->registration_model->update(["ProsesPayment" => 0], $transaction->NoReg);

		if ($_ci->db->trans_status() === FALSE) {
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => lang('global:updated_failed'),
				"code" => 500
			];
		}
		$_ci->db->trans_commit();
		return [
			"NoBukti" => $item->NoBukti,
			"status" => 'success',
			"message" => lang('global:updated_successfully'),
			"code" => 200
		];
	}

	public static function gen_evidence_number()
	{
		$CI = self::ci();
		$NOW = new DateTime();

		$date_start = $NOW->format("Y-m-01 00:00:00.000");
		$date_end = $NOW->format("Y-m-t 00:00:00.000");
		$date_y = $NOW->format("y");
		$date_m = $NOW->format("m");
		$date_d = $NOW->format("d");

		// SELECT MAX(right([NoBukti],6)) AS MyID FROM [SIMtrKasir] WHERE LEN([NoBukti])=18 AND LEFT(LTRIM([NoBukti]),2)='18' AND RIGHT(LEFT(LTRIM([NoBukti]),11),5)='INVRJ'

		$query =  $CI->db->select("MAX(right([NoBukti],6)) AS MAX")
			//->where(array("RJ" => "RJ", "Tanggal >=" => $date_start,"Tanggal <=" => $date_end))
			//->where_in("SectionID", array("SEC079"))
			->where([
				'LEN([NoBukti]) =' => 18,
				'LEFT(LTRIM([NoBukti]), 2) =' => $date_y,
				'RIGHT(LEFT(LTRIM([NoBukti]),11),5) =' => 'INVRJ'
			])
			->get("SIMtrKasir")
			->row();

		if (!empty($query->MAX)) {
			$number = $query->MAX;
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

	public static function get_item($NoReg)
	{
		$query = self::ci()->db
			->select("
				a.StatusBayar,
				a.StatusPeriksa,
				a.RawatInap,
				a.NoReg,
				a.TglReg,
				a.JamReg,
				a.NRM,
				a.NamaPasien,
				a.JenisKerjasamaID,
				a.JenisKerjasama,
				a.DokterRawatID AS DokterID ,
				b.Nama_Supplier AS NamaDokter,
				a.Alamat,
				a.Nama_Customer,
				a.NoKartu,
				a.JenisPembayaran,
				a.Kode_Customer,	
				a.CustomerKerjasamaID,
				a.KelasPelayananNomor as NomorPelayanan,
				a.KelasPertanggunganNomor,
				a.KdKelasPertanggungan,
				a.KdKelas,
				a.NamaKelas,			
				a.SectionID,
				c.SectionName
			")
			->from("VW_Registrasi a")
			->join("mSupplier b", "a.DokterRawatID = b.Kode_Supplier", "LEFT OUTER")
			->join("SIMmSection c", "a.SectionID = c.SectionID", "LEFT OUTER")
			->where(['a.NoReg' => $NoReg])
			->get();

		if ($query->num_rows() > 0) {
			return $query->row();
		}

		return (object) [];
	}

	public static function get_detail_cost($NoReg, $Status)
	{
		return self::ci()->db
			->query("Select * from dbo.GetDetailRincianBiaya('$NoReg', $Status ) ")
			->result();
	}

	public static function get_total_cost($NoReg, $Status)
	{
		return self::ci()->db
			->query("Select   
						round(sum((Qty*(Nilai-(Nilai*disc/100))) + Hext + BiayaResep),0)  as Nilai,
						round(sum((Qty*(HargaOrig-(HargaOrig*disc/100))) + Hext + BiayaResep),0) as NilaiOrig
					from dbo.GetDetailRincianBiaya('$NoReg', $Status ) 
					")
			->row();
	}

	public static function get_group_detail_cost($NoReg, $Status)
	{
		return self::ci()->db
			->query("Select   
						round(sum((Qty*(Nilai-(Nilai*disc/100))) + Hext + BiayaResep),0)  as Nilai,
						round(sum((Qty*(HargaOrig-(HargaOrig*disc/100))) + Hext + BiayaResep),0) as NilaiOrig,
						0 as KelebihanPlafon,
						GroupJasa,
						GroupJasaID,
						round(sum(Qty*DiskonTdkLangsung),0)  as DiskonTdkLangsung 
					from dbo.GetDetailRincianBiaya('$NoReg', $Status ) 
					group by GroupJAsa,GroupJasaID")
			->result();
	}

	public static function find_discount($nobukti, $iddiscount)
	{
		return (int) self::ci()->db
			->where(array(
				"IDDiscount" => $iddiscount,
				"NoBukti" => $nobukti,
			))
			->count_all_results("SIMtrKasirDiscount");
	}

	public static function get_detail_discount($NoBukti = NULL)
	{
		$query = self::ci()->db
			->select(
				" a.NoBukti,
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
			->from("SIMtrKasirDiscount a")
			->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
			->join("mDiscount c", "a.IDDiscount = c.IDDiscount", "LEFT OUTER")
			->join("SIMmListJasa d", "a.JasaID = d.JasaID", "LEFT OUTER")
			->where("a.NoBukti", $NoBukti)
			->get();

		if ($query->num_rows() > 0) {
			$collection = array();
			foreach ($query->result() as $row) {
				//$row->NilaiDiskon = number_format( $row->NilaiDiskon, 2, '.', ',');
				$collection[] = $row;
			}
			return $collection;
		}

		return false;
	}

	public static function get_detail_payment($NoBukti = NULL)
	{
		$query = self::ci()->db
			->where("NoBukti", $NoBukti)
			->get("SIMtrKasirDetail");

		if ($query->num_rows() > 0) {
			$collection = array();
			foreach ($query->result() as $row) {
				$collection[$row->IDBayar] = $row->NilaiBayar;
			}

			return $collection;
		}

		return false;
	}

	public static function check_invoice_state($NoBukti = NULL)
	{
		if (empty($NoBukti)) {
			return FALSE;
		}

		$audit = self::ci()->db->select("Audit AS state")
			->where("NoBukti", $NoBukti)
			->get("SIMtrKasir")
			->row();

		$closing = self::ci()->db->select("NoInvoice AS state")
			->where("NoInvoice", $NoBukti)
			->get("SIMtrKasirClosingDetail")
			->row();

		$outstanding = self::ci()->db->select("NoInvoice AS state")
			->where(array("NoInvoice" => $NoBukti, "Batal" => 0))
			->get("SIMtrPembayaranOutStanding")
			->row();

		$collection = (object) array(
			"audit" => (object) array(
				"state" => @$audit->state,
				"message" => "Pasien Sudah Di Proses Audit, Tidak Dapat Melanjutkan Transaksi"
			),
			"closing" => (object) array(
				"state" => @$closing->state,
				"message" => "Pasien Sudah Di Proses Closing, Tidak Dapat Melanjutkan Transaksi"
			),
			"outstanding" => (object) array(
				"state" => @$outstanding->state,
				"message" => "Pasien Sudah Melakukan Pembayaran Outstanding, Tidak Dapat Melanjutkan Transaksi"
			),
		);

		return $collection;
	}

	public static function approval($Approve_User, $Approve_Pswd)
	{
		$count = (int) self::ci()->db
			->where(array("Approve_User" => $Approve_User, "Approve_Pswd" => $Approve_Pswd, "Approve_Function" => "CANCEL INVOICE PASIEN"))
			->count_all_results("ListApprove");

		return $count > 0 ? TRUE : FALSE;
	}

	public static function get_kwitansi($NoBukti)
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
			->get();

		if ($query->num_rows() > 0) {
			return $query->row();
		}
	}

	public static function money_to_text($bilangan)
	{
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



		$angka = array(
			'0', '0', '0', '0', '0', '0', '0', '0', '0', '0',
			'0', '0', '0', '0', '0', '0'
		);
		$kata = array(
			'', 'satu', 'dua', 'tiga', 'empat', 'lima',
			'enam', 'tujuh', 'delapan', 'sembilan'
		);
		$tingkat = array('', 'ribu', 'juta', 'milyar', 'triliun');

		$panjang_bilangan = strlen($bilangan);

		/* pengujian panjang bilangan */
		if ($panjang_bilangan > 15) {
			$kalimat = "Diluar Batas";
			return $kalimat;
		}

		/* mengambil angka-angka yang ada dalam bilangan,
		dimasukkan ke dalam array */
		for ($i = 1; $i <= $panjang_bilangan; $i++) {
			$angka[$i] = substr($bilangan, - ($i), 1);
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
			if ($angka[$i + 2] != "0") {
				if ($angka[$i + 2] == "1") {
					$kata1 = "seratus";
				} else {
					$kata1 = $kata[$angka[$i + 2]] . " ratus";
				}
			}

			/* untuk puluhan atau belasan */
			if ($angka[$i + 1] != "0") {
				if ($angka[$i + 1] == "1") {
					if ($angka[$i] == "0") {
						$kata2 = "sepuluh";
					} elseif ($angka[$i] == "1") {
						$kata2 = "sebelas";
					} else {
						$kata2 = $kata[$angka[$i]] . " belas";
					}
				} else {
					$kata2 = $kata[$angka[$i + 1]] . " puluh";
				}
			}

			/* untuk satuan */
			if ($angka[$i] != "0") {
				if ($angka[$i + 1] != "1") {
					$kata3 = $kata[$angka[$i]];
				}
			}

			/* pengujian angka apakah tidak nol semua,
			lalu ditambahkan tingkat */
			if (($angka[$i] != "0") or ($angka[$i + 1] != "0") or
				($angka[$i + 2] != "0")
			) {
				$subkalimat = "$kata1 $kata2 $kata3 " . $tingkat[$j] . " ";
			}

			/* gabungkan variabe sub kalimat (untuk satu blok 3 angka)
			ke variabel kalimat */
			$kalimat = $subkalimat . $kalimat;
			$i = $i + 3;
			$j = $j + 1;
		}

		/* mengganti satu ribu jadi seribu jika diperlukan */
		if (($angka[5] == "0") and ($angka[6] == "0")) {
			$kalimat = str_replace("satu ribu", "seribu", $kalimat);
		}

		return trim($kalimat . "");
	}

	public static function money_to_text_english($number)
	{
		$numbers = array(
			'0', '0', '0', '0', '0', '0', '0', '0', '0', '0',
			'0', '0', '0', '0', '0', '0'
		);
		$words = array(
			'', 'one', 'two', 'three', 'four', 'five',
			'six', 'seven', 'eight', 'nine'
		);
		$tens = array(
			'', '', 'twenty', 'thirty', 'forty', 'fifty',
			'sixty', 'seventy', 'eighty', 'ninety'
		);
		$teens = array(
			'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen',
			'sixteen', 'seventeen', 'eighteen', 'nineteen'
		);
		$levels = array('', 'thousand', 'million', 'billion', 'trillion');

		$number_length = strlen($number);

		// Check if the number is out of range
		if ($number_length > 15) {
			return "Out of Range";
		}

		// Extract the digits from the number and store them in an array
		for ($i = 1; $i <= $number_length; $i++) {
			$numbers[$i] = substr($number, - ($i), 1);
		}

		$i = 1;
		$j = 0;
		$sentence = "";

		// Iterate through the numbers array
		while ($i <= $number_length) {
			$subsentence = "";
			$word1 = "";
			$word2 = "";
			$word3 = "";

			// Handle hundreds
			if ($numbers[$i + 2] != "0") {
				$word1 = $words[$numbers[$i + 2]] . " hundred";
			}

			// Handle tens or teens
			if ($numbers[$i + 1] != "0") {
				if ($numbers[$i + 1] == "1") {
					$word2 = $teens[$numbers[$i]];
				} else {
					$word2 = $tens[$numbers[$i + 1]];
					if ($numbers[$i] != "0") {
						$word2 .= '';
					}
				}
			}

			// Handle ones
			if ($numbers[$i] != "0" && $numbers[$i + 1] != "1") {
				$word3 = $words[$numbers[$i]];
			}

			// Check if the number is not all zeros, then add the level
			if ($numbers[$i] != "0" || $numbers[$i + 1] != "0" || $numbers[$i + 2] != "0") {
				$subsentence = "$word1 $word2 $word3 " . $levels[$j] . " ";
			}

			// Concatenate the subsentence to the sentence
			$sentence = $subsentence . $sentence;
			$i = $i + 3;
			$j = $j + 1;
		}

		// Replace "one thousand" with "a thousand" if necessary
		$sentence = str_replace("one thousand", "a thousand", $sentence);

		return trim($sentence);
	}

	private static function &ci()
	{
		return get_instance();
	}
}
