<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class BPJS_helper
{
	private static $user_auth;
	private static $_ci;

	public static function init()
	{
		self::$_ci = $_ci = self::ci();

		$_ci->load->library('simple_login');
		self::$user_auth = $_ci->simple_login->get_user();
	}

	public static function get_registration_outpatient($NoReg)
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('integration_insurance_model');

		$db_select = <<<EOSQL
			a.NoReg,
			a.NoBuktiIntegrasi AS NoKunjungan,
			a.NoKartu,
			a.NoUrut,
			a.CreatedAt AS TglDaftar,
			a.DokterID,
			a.DokterIDIntegrasi AS KdDokter,
			a.SectionID,
			a.SectionIDIntegrasi AS KdPoli
EOSQL;

		$query = $_ci->db
			->select($db_select)
			->from("{$_ci->integration_insurance_model->table} a")
			->where(['a.NoReg' => $NoReg])
			->get();

		$data = $query->row();

		if (!empty($data))
			$data->TglDaftar = DateTime::createFromFormat('Y-m-d H:i:s.u', $data->TglDaftar)->format('d-m-Y');

		return $data;
	}

	public static function get_visite_outpatient($NoReg)
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('integration_insurance_model');
		$_ci->load->model('supplier_model');
		$_ci->load->model('section_model');
		$_ci->load->model('emr_vital_signs_model');
		$_ci->load->model('poly_model');
		$_ci->load->model('poly_initial_diagnosis_model');
		$_ci->load->model('registration_model');
		$_ci->load->model('patient_model');

		$db_select = <<<EOSQL
			a.NoReg,
			a.NoBuktiIntegrasi AS NoKunjungan,
			a.NoKartu,
			a.NoUrut,
			a.CreatedAt AS TglDaftar,
			a.DokterID,
			a.DokterIDIntegrasi AS KdDokter,
			a.SectionID,
			a.SectionIDIntegrasi AS KdPoli,
			a.CheckoutState,
			a.CheckoutReferralDestination,
			a.CheckoutReferralCondition,
			a.CheckoutReferralDate,
			a.CheckoutReferralSpecialist,
			a.CheckoutReferralSubSpecialist,
			a.CheckoutReferralAcomodation,
			a.CheckoutReferralNote,
			b.RawatJalan,
			b.RawatInap,
			c.NRM,
			c.NamaPasien,
			c.TglLahir,
			c.JenisKelamin,
			c.Alamat,
			d.Systolic,
			d.Diastolic,
			d.Weight,
			d.Height,
			d.RespiratoryRate,
			d.HeartRate,
			d.Temperature,
			d.OxygenSaturation,
			d.Pain,
			d.lingkarPerut,
			e.NoBukti AS NoPemeriksaan,
			e.Therapi,
			f.SectionName
EOSQL;

		$query = $_ci->db
			->select($db_select)
			->from("{$_ci->integration_insurance_model->table} a")
			->join("{$_ci->registration_model->table} b", "a.NoReg = b.NoReg", "INNER")
			->join("{$_ci->patient_model->table} c", "b.NRM = c.NRM", "INNER")
			->join("{$_ci->emr_vital_signs_model->table} d", "a.NoReg = d.NoReg", "INNER")
			->join("{$_ci->poly_model->table} e", "a.NoReg = e.RegNo", "INNER")
			->join("{$_ci->section_model->table} f", "a.SectionID = f.SectionID", "INNER")
			->where(['a.NoReg' => $NoReg, 'd.Parent' => 1])
			->get();

		$data = $query->row();
		foreach ($_ci->poly_initial_diagnosis_model->get_all(['NoBukti' => $data->NoPemeriksaan]) as $icd) :
			$data->icd[] = $icd->KodeICD;
			$data->icdName[] = $icd->Descriptions;
		endforeach;


		$data->StatusPelayanan = $data->RawatJalan == 1 ? 'RJ' : 'RI';
		$data->TglDaftar = DateTime::createFromFormat('Y-m-d H:i:s.u', $data->TglDaftar)->format('d-m-Y');
		$data->TglLahir = DateTime::createFromFormat('Y-m-d H:i:s.u', $data->TglLahir)->format('d-m-Y');

		if (!empty($data->CheckoutReferralDate))
			$data->CheckoutReferralDate = DateTime::createFromFormat('Y-m-d', $data->CheckoutReferralDate)->format('d-m-Y');

		return $data;
	}

	public static function get_service_outpatient($NoReg)
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('poly_model');
		$_ci->load->model('poly_transaction_model');
		$_ci->load->model('service_model');
		$_ci->load->model('integration_insurance_service_model');

		$db_select = <<<EOSQL
			a.NoBukti,
			b.JasaID,
			CAST(b.Tarif AS INT) AS Tarif,
			c.JasaIDBPJS,
			c.JasaName,
			d.NoBuktiTindakanIntegrasi
EOSQL;

		$query = $_ci->db
			->select($db_select)
			->from("{$_ci->poly_model->table} a")
			->join("{$_ci->poly_transaction_model->table} b", "a.NoBukti = b.NoBukti", "INNER")
			->join("{$_ci->service_model->table} c", "b.JasaID = c.JasaID", "INNER")
			->join("{$_ci->integration_insurance_service_model->table} d", "a.RegNo = d.NoReg AND b.JasaID = d.JasaID", "LEFT OUTER")
			->where(['a.RegNo' => $NoReg, 'a.Batal' => 0, 'c.JasaIDBPJS !=' => '', 'c.JasaIDBPJS IS NOT NULL' => NULL])
			->get();

		return $query->result();
	}

	public static function get_drug_outpatient($NoReg)
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('pharmacy_model');
		$_ci->load->model('pharmacy_detail_model');
		$_ci->load->model('item_model');
		$_ci->load->model('drug_dosage_model');
		$_ci->load->model('integration_insurance_drug_model');

		$db_select = <<<EOSQL
			a.NoBukti,
			b.Barang_ID AS BarangID,
			c.Barang_ID_BPJS AS BarangIDBPJS,
			(b.JmlObat - b.JmlRetur) AS JmlObat,
			b.NamaResepObat,
			b.Nama_Barang AS NamaBarang,
			d.Signa1,
			d.Signa2,
			e.NoBuktiObatIntegrasi
EOSQL;

		$query = $_ci->db
			->select($db_select)
			->from("{$_ci->pharmacy_model->table} a")
			->join("{$_ci->pharmacy_detail_model->table} b", "a.NoBukti = b.NoBukti", "INNER")
			->join("{$_ci->item_model->table} c", "b.Barang_ID = c.Barang_ID", "INNER")
			->join("{$_ci->drug_dosage_model->table} d", "b.DosisID = d.IDDosis", "INNER")
			->join("{$_ci->integration_insurance_drug_model->table} e", "a.NoReg = e.NoReg AND b.Barang_ID = e.BarangID", "LEFT OUTER")
			->where(['a.NoReg' => $NoReg, 'a.Batal' => 0, 'a.Retur' => 0, 'c.Barang_ID_BPJS !=' => '', 'c.Barang_ID_BPJS IS NOT NULL' => NULL])
			->get();

		return $query->result();
	}

	public static function save_registration($post_data)
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('integration_insurance_model');

		$_ci->db->trans_begin();

		$post_data['CreatedBy'] = self::$user_auth->User_ID;
		$post_data['CreatedAt'] = date('Y-m-d H:i:s');
		$_ci->integration_insurance_model->create($post_data);

		if ($_ci->db->trans_status() === FALSE) {
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => 'Gagal Simpan Pendaftaran BPJS',
			];
		}
		$_ci->db->trans_commit();
		return [
			"status" => 'success',
			"message" => 'Berhasil Simpan Pendaftaran BPJS',
		];
	}

	public static function delete_registration($post_data)
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('integration_insurance_model');

		$_ci->db->trans_begin();

		$_ci->integration_insurance_model->delete_by($post_data);

		if ($_ci->db->trans_status() === FALSE) {
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => 'Gagal Hapus Pendaftaran BPJS',
			];
		}
		$_ci->db->trans_commit();
		return [
			"status" => 'success',
			"message" => 'Berhasil Hapus Pendaftaran BPJS',
		];
	}

	public static function save_checkout_outpatient($NoReg, $data)
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('integration_insurance_model');

		$_ci->db->trans_begin();
		$reg = self::get_visite_outpatient($NoReg);

		if (!empty($data['CheckoutReferralDate']))
			$data['CheckoutReferralDate'] = DateTime::createFromFormat('d-m-Y', $data['CheckoutReferralDate'])->format('Y-m-d');

		$data['UpdatedBy'] = self::$user_auth->User_ID;
		$data['UpdatedAt'] = date('Y-m-d H:i:s');
		$_ci->integration_insurance_model->update_by($data, ['NoReg' => $NoReg, 'SectionID' => $reg->SectionID]);

		if ($_ci->db->trans_status() === FALSE) {
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => 'Gagal update Status Pulang Kunjungan',
			];
		}
		$_ci->db->trans_commit();
		return [
			"status" => 'success',
			"message" => 'Berhasil update Status Pulang Kunjungan',
		];
	}

	public static function save_visite_outpatient($NoReg, $NoBuktiIntegrasi)
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('integration_insurance_model');

		$_ci->db->trans_begin();
		$reg = self::get_visite_outpatient($NoReg);

		$_update = [
			'NoBuktiIntegrasi' => $NoBuktiIntegrasi,
			'NoPemeriksaan' => $reg->NoPemeriksaan,
			'UpdatedBy' => self::$user_auth->User_ID,
			'UpdatedAt' => date('Y-m-d H:i:s')
		];

		$_ci->integration_insurance_model->update_by($_update, ['NoReg' => $NoReg, 'SectionID' => $reg->SectionID]);
		if ($_ci->db->trans_status() === FALSE) {
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => 'Gagal update No Kunjungan',
			];
		}
		$_ci->db->trans_commit();
		return [
			"status" => 'success',
			"message" => 'Berhasil update No Kunjungan',
		];
	}

	public static function update_visite_outpatient($NoReg, $postData)
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('integration_insurance_model');
		$_ci->load->model('poly_model');
		$_ci->load->model('emr_vital_signs_model');
		$_ci->load->model('poly_initial_diagnosis_model');


		$_ci->db->trans_begin();
		$reg = self::get_visite_outpatient($NoReg);

		// Pemeriksaan
		$_update = [
			'Therapi' => $postData['examination']['Therapi'],
		];
		$_ci->poly_model->update($_update, $reg->NoPemeriksaan);

		// Vital Signs
		$_ci->emr_vital_signs_model->update_by($postData['vital'], ['NoReg' => $NoReg, 'NoPemeriksaan' => $reg->NoPemeriksaan]);

		// Diagnosis/ICD
		$_ci->poly_initial_diagnosis_model->delete($reg->NoPemeriksaan);
		/*$_batch = array_map(function ($icd) use ($reg){
				if(empty($icd)) return false;
				return [
					'NOBukti' => $reg->NoPemeriksaan,
					'KodeICD' => $icd,
					'Keterangan' => '',
					'Ditanggung' => 1,
					'NoKartu' => $reg->NoKartu,
					'JenisKerjasamaID' => 9
				];
			}, $postData['diagnosys']);*/
		$_batch = [];
		foreach ($postData['diagnosys'] as $icd) :
			if (empty($icd)) continue;

			$_batch[] = [
				'NOBukti' => $reg->NoPemeriksaan,
				'KodeICD' => $icd,
				'Keterangan' => '',
				'Ditanggung' => 1,
				'NoKartu' => $reg->NoKartu,
				'JenisKerjasamaID' => 9
			];
		endforeach;
		$_ci->poly_initial_diagnosis_model->mass_create($_batch);

		// IntegrasiAsuransi
		$_update = [
			'CheckoutState' => $postData['integration']['CheckoutState'],
			'CheckoutReferralDestination' => NULL,
			'CheckoutReferralCondition' => NULL,
			'CheckoutReferralDate' => NULL,
			'CheckoutReferralSpecialist' => NULL,
			'CheckoutReferralSubSpecialist' => NULL,
			'CheckoutReferralAcomodation' => NULL,
			'CheckoutReferralNote' => NULL,
			'UpdatedBy' => self::$user_auth->User_ID,
			'UpdatedAt' => date('Y-m-d H:i:s')
		];

		if ($postData['integration']['CheckoutState'] == 4) {
			$_update = array_merge($_update, $postData['checkout']);
			$_update['CheckoutReferralDate'] = DateTime::createFromFormat('d-m-Y', $_update['CheckoutReferralDate'])->format('Y-m-d');
		}

		$_ci->integration_insurance_model->update_by($_update, ['NoReg' => $NoReg, 'SectionID' => $reg->SectionID]);

		if ($_ci->db->trans_status() === FALSE) {
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => 'Gagal update No Kunjungan',
			];
		}
		$_ci->db->trans_commit();
		return [
			"status" => 'success',
			"message" => 'Berhasil update No Kunjungan',
		];
	}

	public static function save_service($post_data)
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('integration_insurance_service_model');

		$_ci->db->trans_begin();

		$post_data['CreatedBy'] = self::$user_auth->User_ID;
		$post_data['CreatedAt'] = date('Y-m-d H:i:s');
		$_ci->integration_insurance_service_model->create($post_data);

		if ($_ci->db->trans_status() === FALSE) {
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => 'Gagal Simpan Tindakan BPJS',
			];
		}
		$_ci->db->trans_commit();
		return [
			"status" => 'success',
			"message" => 'Berhasil Simpan Tindakan BPJS',
		];
	}

	public static function save_drug($post_data)
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('integration_insurance_drug_model');

		$_ci->db->trans_begin();

		$post_data['CreatedBy'] = self::$user_auth->User_ID;
		$post_data['CreatedAt'] = date('Y-m-d H:i:s');
		$_ci->integration_insurance_drug_model->create($post_data);

		if ($_ci->db->trans_status() === FALSE) {
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => 'Gagal Simpan Obat BPJS',
			];
		}
		$_ci->db->trans_commit();
		return [
			"status" => 'success',
			"message" => 'Berhasil Simpan Obat BPJS',
		];
	}

	private static function &ci()
	{
		return get_instance();
	}
}
