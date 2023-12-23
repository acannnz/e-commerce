<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

final class registration_helper
{
	private static $_ci;
	private static $user_auth;

	public static function init()
	{
		self::$_ci = $_ci = self::ci();
		self::$user_auth = $_ci->simple_login->get_user();
	}

	public static function create_registration($registration, $destinations, $patient, $vital)
	{
		self::init();
		$_ci = self::$_ci;

		$_ci->db->trans_begin();

		$NoReg = self::gen_registration_number();
		$NRM = (@$registration["PasienBaru"] == 1) ? self::gen_general_nrm_number() : $patient['NRM'];
		$JenisPasien = $_ci->patient_type_model->get_one($registration['JenisKerjasamaID']);
		$registration['CustomerKerjasamaID'] = (int) @$registration['CustomerKerjasamaID'];

		if (@$registration["PasienBaru"] == 1) {
			$_insert_patient = [
				'NRM' => $NRM,
				'JenisPasien' => $JenisPasien->JenisKerjasama,
				'CustomerKerjasamaID' => @$registration['CustomerKerjasamaID'],
				'JenisKerjasamaID' => @$registration['JenisKerjasamaID'],
				'CompanyID' => @$registration['KodePerusahaan'],
				'NoKartu' => @$registration['NoAnggota'],
				'KdKelas' => $registration['KdKelas'],
				'Aktive_Keanggotaan' => 1,
				'UmurSaatInput' => $registration['UmurThn'],
				'PenanggungIsPasien' => @$registration['PenanggungIsPasien'],
				'PenanggungNRM' => @$registration['PenanggungNRM'],
				'PenanggungNama' => @$registration['PenanggungNama'],
				'PenanggungAlamat' => @$registration['PenanggungAlamat'],
				'PenanggungPhone' => @$registration['PenanggungPhone'],
				'PenanggungKTP' => @$registration['PenanggungKTP'],
				'PenanggungHubungan' => @$registration['PenanggungHubungan'],
				'PasienKTP' => $patient['PasienKTP'],

				'NamaPasien' => @$patient['NamaPasien'], // Updated 0217020, Dan
				'NamaAlias' => @$patient['NamaAlias'],
				'JenisKelamin' => @$patient['JenisKelamin'],
				'TglLahir' => @$patient['TglLahir'],
				'TempatLahir' => @$patient['TempatLahir'],
				'NoIdentitas' => @$patient['NoIdentitas'],
				'Agama' => @$patient['Agama'],
				'Alamat' => @$patient['Alamat'],
				'DesaID' => @$patient['DesaID'],
				'KecamatanID' => @$patient['KecamatanID'],
				'KabupatenID' => @$patient['KabupatenID'],
				'PropinsiID' => @$patient['PropinsiID'],
				'NationalityID' => @$patient['NationalityID'],
				'Pekerjaan' => @$patient['Pekerjaan'],
				'Email' => @$patient['Email'],
				'Phone' => @$patient['Phone'],
				'RiwayatAlergi' => @$patient['RiwayatAlergi'],
				'CatatanPatient' => @$patient['CatatanPatient'],

				'PersonalId' => $_ci->input->post('FamilyFolder') ? $_ci->input->post('FamilyFolder') : NULL,
				'SedangDirawat' => 1,
				'KodeRegional' => @$patient['KodeRegional'],
			];

			$_insert_patient = array_merge($patient, $_insert_patient);

			$_ci->patient_model->create($_insert_patient);
			//$_ci->personal_model->update(['PersonalFirstVisitDate' => date('Y-m-d')], $_ci->input->post('FamilyFolder'));

		} else {
			$_get_patient_type = $_ci->patient_type_model->get_one($registration['JenisKerjasamaID']);
			$_update_patient = [
				'JenisPasien' => $JenisPasien->JenisKerjasama,
				'KdKelas' => $registration['KdKelas'],
				'PenanggungPekerjaan' => @$registration['PenanggungPekerjaan'],
				'PenanggungIsPasien' => @$registration['PenanggungIsPasien'],
				'PenanggungNRM' => @$registration['PenanggungNRM'],
				'PenanggungNama' => @$registration['PenanggungNama'],
				'PenanggungAlamat' => @$registration['PenanggungAlamat'],
				'PenanggungPhone' => @$registration['PenanggungPhone'],
				'PenanggungKTP' => @$registration['PenanggungKTP'],
				'PenanggungHubungan' => @$registration['PenanggungHubungan'],

				'NamaPasien' => @$patient['NamaPasien'], // Updated 0217020, Dan
				'NamaAlias' => @$patient['NamaAlias'],
				'JenisKelamin' => @$patient['JenisKelamin'],
				'TglLahir' => @$patient['TglLahir'],
				'TempatLahir' => @$patient['TempatLahir'],
				'NoIdentitas' => @$patient['NoIdentitas'],
				'Agama' => @$patient['Agama'],
				'Alamat' => @$patient['Alamat'],
				'DesaID' => @$patient['DesaID'],
				'KecamatanID' => @$patient['KecamatanID'],
				'KabupatenID' => @$patient['KabupatenID'],
				'PropinsiID' => @$patient['PropinsiID'],
				'NationalityID' => @$patient['NationalityID'],
				'Pekerjaan' => @$patient['Pekerjaan'],
				'Email' => @$patient['Email'],
				'Phone' => @$patient['Phone'],
				'RiwayatAlergi' => @$patient['RiwayatAlergi'],
				'CatatanPatient' => @$patient['CatatanPatient'],

				'SedangDirawat' => 1,
				'KodeRegional' => @$patient['KodeRegional'],
			];

			if (!empty($registration['CustomerKerjasamaID']) && $registration['CustomerKerjasamaID'] != 0) {
				$_update_patient['CustomerKerjasamaID'] = @$registration['CustomerKerjasamaID'];
				$_update_patient['JenisKerjasamaID'] = @$registration['JenisKerjasamaID'];
				$_update_patient['CompanyID'] = @$registration['KodePerusahaan'];
				$_update_patient['NoKartu'] = @$registration['NoAnggota'];
				$_update_patient['KdKelas'] = $registration['KdKelas'];
				$_update_patient['Aktive_Keanggotaan'] = 1;
			}

			$_ci->patient_model->update($_update_patient, $NRM);
		}

		$section_destination = $_ci->section_model->get_one($destinations[0]['SectionID']);

		$_ci->db->query("EXEC UpdateKunjunganPasien '{$NRM}','" . date("Y-m-d") . "' , '" . config_item('section_id') . "' , '{$registration['KdKelas']}' ");

		// Jika Pasien adalah Anggota Kerjasama Baru
		if ($_ci->input->post("AnggotaBaru")) {
			if (empty($registration['NoAnggota'])) {
				$_ci->db->trans_rollback();
				return [
					"status" => 'error',
					"message" => 'Nomor Kartu Anggota Kerjasama belum terisi',
					"code" => 500
				];
			}

			if ($_ci->cooperation_member_model->count_all(['NoAnggota' => $registration['NoAnggota'], "NRM" => $NRM, "CustomerKerjasamaID" => $registration['CustomerKerjasamaID']])) {
				$cooperation_card = [
					"CustomerKerjasamaID" => $registration['CustomerKerjasamaID'],
					"NRM" => $NRM,
					"Nama" => $patient['NamaPasien'],
					"Active" => 1,
					"Klp" => @$patient['Klp'],
					"TglLahir" => $patient['TglLahir'],
					"Alamat" => $patient['Alamat'],
					"Phone" => $patient['Phone'],
					"Gender" => $patient['JenisKelamin'],
				];
				$_ci->cooperation_member_model->update($cooperation_card, $registration['NoAnggota']);
			} else {

				if ($_ci->cooperation_member_model->count_all(['NoAnggota' => $registration['NoAnggota']])) {
					$_ci->db->trans_rollback();
					return [
						"status" => 'error',
						"message" => "Nomor Kartu {$registration['NoAnggota']} sudah pernah terdaftar disistem, tidak dapat menyimpan sebagai Anggota baru",
						"code" => 500
					];
				}

				$cooperation_card = [
					"CustomerKerjasamaID" => $registration['CustomerKerjasamaID'],
					"NRM" => $NRM,
					"NoAnggota" => $registration['NoAnggota'],
					"Nama" => $patient['NamaPasien'],
					"Active" => 1,
					"Klp" => @$patient['Klp'],
					"TglLahir" => $patient['TglLahir'],
					"Alamat" => $patient['Alamat'],
					"Phone" => $patient['Phone'],
					"Gender" => $patient['JenisKelamin'],
				];
				$_ci->cooperation_member_model->create($cooperation_card);
			}
		}

		$registration['NoReg'] = $NoReg;
		$registration['NRM'] = $NRM;
		$registration['NamaPasien_Reg'] = @$patient['NamaPasien'];
		$registration['StatusPeriksa'] = "Belum";
		$registration['StatusBayar'] = "Belum";
		$registration['RawatJalan'] = (int)@$registration['RawatJalan'];
		$registration['RawatInap'] = (int)@$registration['RawatInap'];
		$registration['Status'] = @$registration['RawatJalan'] == 1 ? "RJ" : "RI";
		$registration['JenisKunjungan'] = self::check_patient_visit($NRM);
		$registration['JmlHariRawat'] = 1;

		$registration['MarkUp'] = 0;
		$registration['VIP'] = 0;
		$registration['IKSMixed'] = 0;
		$registration['NonPBI'] = 0;
		$registration['PasienKTP'] = 1; //(int) @$patient["PasienKTP"];

		end($destinations); // pointer array key to the last
		$key = key($destinations); // get last array key
		$registration['DokterRawatID'] = @$destinations[$key]['DokterID']; // get last DokterID
		$registration['SectionID'] = @$destinations[$key]['SectionID']; // get last SectionID
		$registration['SectionPerawatanID'] = @$destinations[$key]['SectionID']; // get last SectionID
		$registration['SectionMasukID'] = @$destinations[$key]['SectionID']; // get last SectionID
		reset($destinations);

		$registration['KlinikReproduksi'] = 0;
		$registration['SectionInputID'] = "SEC055";
		$registration['JenisPembayaran'] = self::get_payment_type($registration["JenisKerjasamaID"]);
		$registration['User_ID'] = self::$user_auth->User_ID;
		$registration['TglReg'] = date('Y-m-d');
		$registration['JamReg'] = date('Y-m-d H:i:s');

		$destination_data = $DataRegPasien = [];
		$Nomor = 1;
		foreach ($destinations as $k => $v) {
			$queue_where = (object) array("DokterID" => $v['DokterID'], "SectionID" => $v['SectionID'], "WaktuID" => $v['WaktuID'], "Tanggal" => date("Y-m-d"));
			//$number_of_queues = self::get_number_of_queue( $queue_where );
			//$queue = self::get_queue( $queue_where );
			//$queue = $queue > $_ci->config->item("start_queue") ? $queue : $_ci->config->item("start_queue");
			$queue = $v['NoAntri'];
			// Update antrian pada Jadwal
			$_ci->db->update("SIMtrDokterJagaDetail", array("NoAntrianTerakhir" => $queue, "Realisasi" => 1), (array) $queue_where);

			$DataRegPasien[$k] = [
				'NoReg' => $NoReg,
				'Nomor' => $Nomor++,
				'Tanggal' => date('Y-m-d'),
				'Jam' => $registration['JamReg'],
				'JenisPasienID' => $v['JenisKerjasamaID'],
				'SectionAsalID' => "SEC000",
				'SectionID' => $v['SectionID'],
				'KelasAsalID' => $registration['KdKelas'],
				'KelasID' => $registration['KdKelas'],
				'Kamar' => @$registration['NoKamar'],
				'NoBed' => @$registration['NoBed'],
				'Titip' => "0",
				'DokterID' => $v['DokterID'],
				'WaktuID' => $v['WaktuID'],
				// 'Waktu' => $v['Waktu'],
				'NoAntri' => $queue,
				'SudahPeriksa' => 0,
				'RJ' => (int) @$registration['RawatJalan'],
				'UmurThn' => $v['UmurThn'],
				'UmurBln' => $v['UmurBln'],
				'UmurHr' => $v['UmurHr'],
				'Active' => 1,
			];

			// Update NoUrut Pada Registrasi Tujuan
			unset($v['NoAntri']);
			$v['NoUrut'] = $queue;
			$v['NoReg'] = $NoReg;
			$destination_data[$k] = $v;
		}

		$_ci->registration_model->create($registration);
		$_ci->registration_data_model->mass_create($DataRegPasien);
		$_ci->registration_destination_model->mass_create($destination_data);

		$vital['NoReg'] = $NoReg;
		$vital['NRM'] = $NRM;
		$vital['CreatedBy'] = self::$user_auth->User_ID;
		$vital['CreatedAt'] = date('Y-m-d H:i:s');
		$_ci->vital_signs_model->create($vital);

		if (!empty($registration['Keterangan'])) {
			$memo_data = [
				"NoReg" => $NoReg,
				"Tanggal" => date("Y-m-d"),
				"Jam" => date("Y-m-d H:i:s"),
				"SectionID" => @$destinations[$key]['SectionID'],
				"Memo" => $registration['Keterangan'],
				"User_ID" => self::$user_auth->User_ID
			];
			$_ci->memo_model->create($memo_data);
		}

		if (!empty($registration['NoReservasi'])) {
			$_ci->reservation_model->update(["Registrasi" => 1], $registration['NoReservasi']);
		}

		if (@$registration['RawatInap'] == 1) {
			$_ci->load->model('room_detail_model');
			$_ci->load->model('room_model');
			$_ci->load->model('registration_doctor_treat_model');

			$_ci->room_detail_model->update_by(['Status' => 'I'], ['NoKamar' => @$registration['NoKamar'], 'NoBed' => @$registration['NoBed']]);
			$_ci->room_model->update(['Status' => 'I'], @$registration['NoKamar']);

			if (!$_ci->registration_doctor_treat_model->count_all(['NoReg' => $registration['NoReg'], 'DokterRawatID' => $registration['DokterRawatID']])) {
				$_ci->registration_doctor_treat_model->create(['NoReg' => $registration['NoReg'], 'DokterRawatID' => $registration['DokterRawatID']]);
			}

			if (config_item('AutoRIPertama') && $_ci->registration_data_model->count_all(['NoReg' => $registration['NoReg'], 'SectionID' => $registration['SectionID'], 'Kamar' => $registration['NoKamar'], 'NoBed' => $registration['NoBed'], 'Active' => 1, 'Out' => 1, 'Batal' => 0])) {
				$_ci->db->query("EXEC InsertJasaRIAutomatis_PertamaMasuk '{$registration['NoReg']}','{$registration['SectionID']}','{$registration['NoKamar']}','{$registration['NoBed']}'");
			}

			$activities_description = sprintf("%s # %s # GeneralNRM %s # %s # %s", "INPUT REG.", $NoReg, $NRM, @$patient['NamaPasien'], config_item('section_id'));
			$_ci->db->query("EXEC InsertUserActivities '" . date("Y-m-d") . "','" . date("Y-m-d H:i:s") . "', " . self::$user_auth->User_ID . " ,'{$NoReg}','$activities_description','SIMtrRegistrasi'");
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
		return [
			"NoReg" => $NoReg,
			"status" => 'success',
			"message" => lang('global:created_successfully'),
			"code" => 200
		];
	}

	public static function update_registration($registration, $destinations, $patient, $vital)
	{
		self::init();
		$_ci = self::$_ci;


		$_ci->db->trans_begin();

		$registration['JenisPembayaran'] = $_ci->db->where("JenisKerjasamaID", $registration["JenisKerjasamaID"])->get("SIMmJenisKerjasama")->row()->JenisPembayaran;

		foreach ($destinations as $k => $v) {
			$queue_where = (object) array("DokterID" => $v['DokterID'], "SectionID" => $v['SectionID'], "WaktuID" => $v['WaktuID'], "Tanggal" => date("Y-m-d"));
			//$number_of_queues = registration_helper::get_number_of_queue( $queue_where );
			//$queue = registration_helper::get_queue( $queue_where );
			//$queue = $queue > $_ci->config->item("start_queue") ? $queue : $_ci->config->item("start_queue");
			$queue = $v['NoAntri'];
			// Update antrian pada Jadwal
			$_ci->db->update("SIMtrDokterJagaDetail", array("NoAntrianTerakhir" => $queue, "Realisasi" => 1), (array) $queue_where);

			$DataRegPasien['NoReg'] = $registration['NoReg'];
			$DataRegPasien['Tanggal'] = $registration['TglReg'];
			$DataRegPasien['Jam'] = $registration['JamReg'];
			$DataRegPasien['JenisPasienID'] = $v['JenisKerjasamaID'];
			$DataRegPasien['SectionAsalID'] = "SEC000";
			$DataRegPasien['SectionID'] = $v['SectionID'];
			$DataRegPasien['KelasAsalID'] = "xx";
			$DataRegPasien['KelasID'] = "xx";
			$DataRegPasien['Titip'] = 0;
			$DataRegPasien['DokterID'] = $v['DokterID'];
			$DataRegPasien['WaktuID'] = $v['WaktuID'];
			$DataRegPasien['NoAntri'] = $queue;
			$DataRegPasien['SudahPeriksa'] = 0;
			$DataRegPasien['RJ'] = 1;
			$DataRegPasien['UmurThn'] = $v['UmurThn'];
			$DataRegPasien['UmurBln'] = $v['UmurBln'];
			$DataRegPasien['UmurHr'] = $v['UmurHr'];
			// Update NoUrut Pada Registrasi Tujuan
			$v['NoUrut'] = $queue;
			unset($v['NoAntri']);

			$already_available_destination = $_ci->registration_destination_model->count_all(['NoReg' => $registration['NoReg'], 'SectionID' => $v['SectionID']]);
			if ($already_available_destination) {
				$_ci->registration_data_model->update_by($DataRegPasien, ['NoReg' => $registration['NoReg'], 'SectionID' => $v['SectionID']]); // hide where: 'DokterID' => $v['DokterID'], 'WaktuID' => $v['WaktuID']
				$_ci->registration_destination_model->update_by($v, ['NoReg' => $registration['NoReg'], 'SectionID' => $v['SectionID']]);
			} else {
				$Nomor = (int) $_ci->db->select("max(Nomor) as Nomor")->where("NoReg", $registration['NoReg'])->get("SIMtrDataRegPasien")->row()->Nomor;
				$DataRegPasien['Nomor'] = ++$Nomor;
				$v['NoReg'] = $registration['NoReg'];

				$_ci->registration_data_model->create($DataRegPasien);
				$_ci->registration_destination_model->create($v);
			}
		}

		$update_patient = array(
			'CompanyID' => @$registration['KodePerusahaan'],
			'NoKartu' => @$registration['NoAnggota'],
			'JenisKerjasamaID' => @$registration['JenisKerjasamaID'],

			'NamaPasien' => @$patient['NamaPasien'], // Updated 0217020, Dan
			'NamaAlias' => @$patient['NamaAlias'],
			'JenisKelamin' => @$patient['JenisKelamin'],
			'TglLahir' => @$patient['TglLahir'],
			'TempatLahir' => @$patient['TempatLahir'],
			'NoIdentitas' => @$patient['NoIdentitas'],
			'Agama' => @$patient['Agama'],
			'Alamat' => @$patient['Alamat'],
			'DesaID' => @$patient['DesaID'],
			'KecamatanID' => @$patient['KecamatanID'],
			'KabupatenID' => @$patient['KabupatenID'],
			'PropinsiID' => @$patient['PropinsiID'],
			'NationalityID' => @$patient['NationalityID'],
			'Pekerjaan' => @$patient['Pekerjaan'],
			'Email' => @$patient['Email'],
			'Phone' => @$patient['Phone'],
			'RiwayatAlergi' => @$patient['RiwayatAlergi'],
			'CatatanPatient' => @$patient['CatatanPatient'],
			'KodeRegional' => @$patient['KodeRegional'],

			'PenanggungPekerjaan' => @$registration['PenanggungPekerjaan'],
			'PenanggungIsPasien' => @$registration['PenanggungIsPasien'],
			'PenanggungNRM' => @$registration['PenanggungNRM'],
			'PenanggungNama' => @$registration['PenanggungNama'],
			'PenanggungAlamat' => @$registration['PenanggungAlamat'],
			'PenanggungPhone' => @$registration['PenanggungPhone'],
			'PenanggungKTP' => @$registration['PenanggungKTP'],
			'PenanggungHubungan' => @$registration['PenanggungHubungan'],
			'SedangDirawat' => 1,
		);

		$_ci->patient_model->update($update_patient, $registration['NRM']);

		// $patient = $_ci->input->post("p");
		// Jika Pasien adalah Anggota Kerjasama Baru
		if ($_ci->input->post("AnggotaBaru")) {
			$cooperation_card = array(
				"CustomerKerjasamaID" => $registration['CustomerKerjasamaID'],
				"NRM" => $registration['NRM'],
				"NoAnggota" => $registration['NoAnggota'],
				"Nama" => $patient['NamaPasien'],
				"Active" => $patient['Aktive_Keanggotaan'],
				"Klp" => @$patient['Klp'],
				"TglLahir" => $patient['TglLahir'],
				"Alamat" => $patient['Alamat'],
				"Phone" => $patient['Phone'],
				"Gender" => $patient['JenisKelamin'],
			);
			$_ci->cooperation_member_model->create($cooperation_card);
		}

		# pdate SIMtrDataRegPasien set JenisPasienID=" & FormFieldColl.Item("JenisKerjasamaID") & " where NoReg='" & Me.NoReg & "'"
		$_ci->registration_data_model->update(["JenisPasienID" => $registration['JenisKerjasamaID']], $registration['NoReg']);
		$_ci->registration_model->update($registration, $registration['NoReg']);

		$vital['UpdatedBy'] = self::$user_auth->User_ID;
		$vital['UpdatedAt'] = date('Y-m-d H:i:s');
		$_ci->vital_signs_model->update_by($vital, ['NoReg' => $registration['NoReg'], 'Parent' => 1]);

		$section_destination = $_ci->section_model->get_one($destinations[0]['SectionID']);
		$activities_description = sprintf("%s # %s # GeneralNRM %s # %s # %s", "UPDATE REG.", $registration['NoReg'], $registration['NRM'], @$patient['NamaPasien'], config_item('section_id'));
		$_ci->db->query("EXEC InsertUserActivities '" . date("Y-m-d") . "','" . date("Y-m-d H:i:s") . "', " . self::$user_auth->User_ID . " ,'" . $registration['NoReg'] . "','$activities_description','SIMtrRegistrasi'");

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
		return [
			"NoReg" => $registration['NoReg'],
			"status" => 'success',
			"message" => lang('global:created_successfully'),
			"code" => 200
		];
	}

	public static function transfer_inpatient($registration, $destinations)
	{
		self::init();
		$_ci = self::$_ci;

		$_ci->db->trans_begin();

		$patient = $_ci->patient_model->get_one($registration['NRM'], TRUE);
		$section_destination = $_ci->section_model->get_one($destinations['SectionID']);

		$_ci->db->query("EXEC UpdateKunjunganPasien '{$registration['NRM']}','" . date("Y-m-d") . "' , '" . config_item('section_id') . "' , '{$registration['KdKelas']}' ");

		// Jika Pasien adalah Anggota Kerjasama Baru
		if ($_ci->input->post("AnggotaBaru")) {
			$cooperation_card = [
				"CustomerKerjasamaID" => $registration['CustomerKerjasamaID'],
				"NRM" => $registration['NRM'],
				"NoAnggota" => $registration['NoAnggota'],
				"Nama" => $patient['NamaPasien'],
				"Active" => 1,
				"Klp" => @$patient['Klp'],
				"TglLahir" => $patient['TglLahir'],
				"Alamat" => $patient['Alamat'],
				"Phone" => $patient['Phone'],
				"Gender" => $patient['JenisKelamin'],
			];
			$_ci->cooperation_member_model->create($cooperation_card);
		}

		$registration['RawatInap'] = 1;
		$registration['DokterRawatID'] = @$destinations['DokterID']; // get last DokterID
		$registration['SectionID'] = @$destinations['SectionID']; // get last SectionID
		$registration['SectionPerawatanID'] = @$destinations['SectionID']; // get last SectionID
		$registration['SectionMasukID'] = @$destinations['SectionID']; // get last SectionID

		$Nomor = (int) @$_ci->db->select("max(Nomor) as Nomor")->where("NoReg", $registration['NoReg'])->get("SIMtrDataRegPasien")->row()->Nomor;
		$DataRegPasien['Nomor'] = ++$Nomor;
		$DataRegPasien = [
			'NoReg' => $registration['NoReg'],
			'Nomor' => $Nomor,
			'Tanggal' => date('Y-m-d'),
			'Jam' => $registration['JamReg'],
			'JenisPasienID' => $destinations['JenisKerjasamaID'],
			'SectionAsalID' => "SEC000",
			'SectionID' => $destinations['SectionID'],
			'KelasAsalID' => $registration['KdKelas'],
			'KelasID' => $registration['KdKelas'],
			'Kamar' => @$registration['NoKamar'],
			'NoBed' => @$registration['NoBed'],
			'Titip' => "0",
			'DokterID' => $destinations['DokterID'],
			'SudahPeriksa' => 0,
			'RJ' => 0,
			'UmurThn' => $destinations['UmurThn'],
			'UmurBln' => $destinations['UmurBln'],
			'UmurHr' => $destinations['UmurHr'],
			'Active' => 1,
		];

		$registration_update = [
			'AkanRI' => 0,
			'RawatInap' => 1,
			'DokterRawatID' => @$destinations['DokterID'],
			'SectionID' => @$destinations['SectionID'],
			'SectionPerawatanID' => @$destinations['SectionID'],
			'SectionMasukID' => @$destinations['SectionID']
		];
		$_ci->registration_model->update($registration_update, $registration['NoReg']);
		$_ci->registration_data_model->create($DataRegPasien);

		if (!empty($registration['Keterangan'])) {
			$memo_data = [
				"NoReg" => $NoReg,
				"Tanggal" => date("Y-m-d"),
				"Jam" => date("Y-m-d H:i:s"),
				"SectionID" => @$destinations[$key]['SectionID'],
				"Memo" => $registration['Keterangan'],
				"User_ID" => self::$user_auth->User_ID
			];
			$_ci->memo_model->create($memo_data);
		}

		$_ci->load->model('room_detail_model');
		$_ci->load->model('room_model');
		$_ci->load->model('registration_doctor_treat_model');

		$_ci->room_detail_model->update_by(['Status' => 'I'], ['NoKamar' => @$registration['NoKamar'], 'NoBed' => @$registration['NoBed']]);
		$_ci->room_model->update(['Status' => 'I'], @$registration['NoKamar']);

		if (!$_ci->registration_doctor_treat_model->count_all(['NoReg' => $registration['NoReg'], 'DokterRawatID' => $registration['DokterRawatID']])) {
			$_ci->registration_doctor_treat_model->create(['NoReg' => $registration['NoReg'], 'DokterRawatID' => $registration['DokterRawatID']]);
		}

		if (config_item('AutoRIPertama') && $_ci->registration_data_model->count_all(['NoReg' => $registration['NoReg'], 'SectionID' => $registration['SectionID'], 'Kamar' => $registration['NoKamar'], 'NoBed' => $registration['NoBed'], 'Active' => 1, 'Out' => 0, 'Batal' => 0])) {
			$_ci->db->query("EXEC InsertJasaRIAutomatis_PertamaMasuk '{$registration['NoReg']}','{$registration['SectionID']}','{$registration['NoKamar']}','{$registration['NoBed']}'");
		}

		$activities_description = sprintf("%s # %s # NRM %s # %s # %s", "TRANSFER PASIEN TO RI", $registration['NoReg'], $registration['NRM'], @$patient['NamaPasien'], config_item('section_id'));
		$_ci->db->query("EXEC InsertUserActivities '" . date("Y-m-d") . "','" . date("Y-m-d H:i:s") . "', " . self::$user_auth->User_ID . " ,'{$registration['NoReg']}','$activities_description','SIMtrRegistrasi'");

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
		return [
			"NoReg" => $registration['NoReg'],
			"status" => 'success',
			"message" => lang('registrations:transfer_inpatient_successfully'),
			"code" => 200
		];
	}

	public static function get_personal_registration($NoReg)
	{
		$select_table = <<<EOSQL
				a.NoReg,
				a.NRM,
				a.UmurThn, 
				a.TglReg, 
				b.NamaPasien,
				b.TglLahir, 
				CASE 
					WHEN b.JenisKelamin = 'M' 
					   THEN 'male' 
					   ELSE 'female' 
				END as JenisKelamin,
				b.Alamat,
				b.Phone,
				b.Email,
				c.Nationality,
				a.StatusPeriksa
EOSQL;

		$result = self::ci()->db
			->select($select_table)
			->from("SIMtrRegistrasi a")
			->join("mPasien b", "a.NRM = b.NRM", "LEFT OUTER")
			->join("mNationality c", "b.NationalityID = c.NationalityID", "LEFT OUTER")
			->where('a.NoReg', $NoReg)
			->get();

		if ($result->num_rows() > 0) {
			return $result->row();
		}

		return FALSE;
	}

	public static function gen_registration_number()
	{
		self::init();
		$_ci = self::$_ci;
		$NOW = new DateTime();
		$date_y = $NOW->format("y");
		$date_m = $NOW->format("m");
		$date_d = $NOW->format("d");
		# SELECT MAX([NoReg]) AS MyID FROM [SIMtrRegistrasi] 
		# WHERE LEN([NoReg])=16 AND LEFT(LTRIM([NoReg]),2)='18' AND RIGHT(LEFT(LTRIM([NoReg]),9),3)='REG'

		$query = $_ci->db
			->select('MAX([NoReg]) AS max')
			->where(array(
				"LEN([NoReg]) =" => 16,
				"LEFT(LTRIM([NoReg]),2) =" => $date_y,
				"RIGHT(LEFT(LTRIM([NoReg]),9),3) =" => 'REG'
			))
			->get($_ci->registration_model->table)
			->row();

		if (!empty($query->max)) {
			$max_number = ++$query->max;
			$arr_number = explode('-', $max_number);
			$number = (string) (sprintf("%02d%02d%02dREG-%06d", $date_y, $date_m, $date_d, $arr_number[1]));
		} else {
			$number = (string) (sprintf("%02d%02d%02dREG-%06d", $date_y, $date_m, $date_d, 1));
		}

		return $number;
	}

	public static function gen_general_nrm_number()
	{
		self::init();
		$_ci = self::$_ci;

		$query = $_ci->db->select('MAX([NRM]) AS max')
			->get($_ci->patient_model->table)
			->row();

		if (!empty($query->max)) {
			$number = 1 . str_replace('.', '', $query->max);
			$number = ++$number;
			$number = substr($number, 1);
			$arrayNumber = @str_split($number, 2);
			$number = @implode('.', $arrayNumber);
		} else {
			$number = '00.00.01';
		}
		return $number;
	}

	public static function check_patient_visit($NRM)
	{
		self::init();
		$_ci = self::ci();
		$_date = new DateTime("NOW");
		$start_month = $_date->format("Y-m-01");
		$end_month = $_date->format("Y-m-t");

		$check = $_ci->registration_model->count_all(["NRM" => $NRM, "TglReg >="  => $start_month, "TglReg <=" => $end_month]);

		return $check > 0 ? "Lama" : "Baru";
	}

	public static function get_payment_type($JenisKerjasamaID)
	{
		self::init();
		$_ci = self::ci();

		$get = $_ci->db->where("JenisKerjasamaID", $JenisKerjasamaID)
			->get("SIMmJenisKerjasama")
			->row();

		return $get->JenisPembayaran;
	}

	// Mencari Jumlah Antrian
	public static function get_number_of_queue($params)
	{
		self::init();
		$_ci = self::$_ci;

		$_date = new DateTime("NOW");
		$today = $_date->format("Y-m-d");

		$count = (int) @$_ci->db
			->where(array("SectionID" => $params->SectionID, "DokterID" => $params->DokterID, "WaktuID" => $params->WaktuID, "Tanggal" => $today))
			->count_all_results("SIMtrDataRegPasien");

		return $count;
	}

	public static function get_queue($params)
	{
		self::init();
		$_ci = self::$_ci;

		$_date = new DateTime("NOW");
		$today = $_date->format("Y-m-d");

		$today = DateTime::createFromFormat('Y-m-d', date('Y-m-d'))->setTime(0, 0);
		$today->add(new DateInterval('PT8H'));

		$queue1 = @$_ci->db
			->select("MAX(NoAntri) AS queue ")
			->where(array("SectionID" => $params->SectionID, "Jam >" => $today->format('Y-m-d 08:00:00')))
			->get("SIMtrDataRegPasien")
			->row()
			->queue;

		$queue2 = @$_ci->db
			->where(array("SectionID" => $params->SectionID, "Jam >" => $today->format('Y-m-d 08:00:00')))
			->count_all_results("SIMtrDataRegPasien");

		if (!($queue1 === NULL) || !($queue2 === NULL)) {
			return (int) @max(array(++$queue1, ++$queue2));
		}

		return 1;
	}

	private static function &ci()
	{
		return get_instance();
	}
}
