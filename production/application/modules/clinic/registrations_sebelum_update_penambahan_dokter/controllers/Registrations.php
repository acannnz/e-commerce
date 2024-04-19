<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Registrations extends Admin_Controller
{
	protected $_translation = 'registrations';
	protected $_model = 'registration_model';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('registration');

		$this->page = "common_registrations";
		$this->template->title(lang("registrations:page") . ' - ' . $this->config->item('company_name'));

		$this->load->model("registration_model");
		$this->load->model("registration_data_model");
		$this->load->model("registration_destination_model");
		$this->load->model("reservation_model");
		$this->load->model("patient_model");
		$this->load->model("vital_signs_model");
		$this->load->model("class_model");
		$this->load->model("patient_type_model");
		$this->load->model("patient_nrm_model");
		$this->load->model("memo_model");
		$this->load->model("cooperation_member_model");

		$this->load->model("common/patient_type_m");
		$this->load->model("common/supplier_m");
		$this->load->model("common/supplier_specialist_m");
		$this->load->model("section_model");
		$this->load->model("section_group_model");
		$this->load->model("common/time_m");

		$this->load->model('country_model');
		$this->load->model('province_model');
		$this->load->model('county_model');
		$this->load->model('district_model');
		$this->load->model('village_model');
		$this->load->model('area_model');

		$this->load->helper("registration");
		$this->load->helper("common/patient");

		$this->load->config('registrations');

		$this->load->model("regional_model");
		$this->load->model("vw_regional_model");
	}


	public function index()
	{
		$data = array(
			'page' => $this->page,
			'option_section' => $this->registration_model->get_option_section(),
			'form' => TRUE,
			'datatables' => TRUE,
			"option_doctor" => option_doctor(),
		);

		$this->template
			->set("heading", lang("registrations:page"))
			->set_breadcrumb(lang("registrations:breadcrumb"))
			->build('registrations/datatable', (isset($data) ? $data : NULL));
	}

	public function pasien_history($is_ajax_request = false)
	{
		$prev_date = date('Y-m-d', strtotime(' -3 day'));
		$history_pasien = $this->db
			->select(
				"a.NoReg,
								a.TglReg,
								a.NamaPasien_Reg as NamaPasien, 
								a.NRM, 
								a.Phone_Reg as NoTelp
							"
			)
			->from("{$this->registration_model->table} a")
			->join("{$this->patient_model->table} b", "a.NRM = b.NRM")
			->where([
				'TglReg' 	=> $prev_date,
				'Batal' 	=> 0
			])
			->get()
			->result();

		$data = array(
			"collection_history" => $history_pasien
		);

		$this->load->view('lookup/patient_history', $data);
	}

	public function patient_diagnosa_details($NoReg = NULL)
	{

		$NoReg = ($NoReg) ? $NoReg : $this->input->post("NoReg");

		if (!$this->input->is_ajax_request()) {
			show_error("Bad Request", 400);
		}

		$result = $this->db
			->select("c.KodeICD, c.Descriptions")
			->from("SIMtrRJ a")
			->join("SIMtrRJDiagnosaAwal b", "a.NoBukti = b.NOBukti")
			->join("mICD c", "c.KodeICD = b.KodeICD")
			->where([
				'a.RegNo' => $NoReg,
				'a.Batal' => 0
			])
			->get()
			->result();

		$data['item'] = $result;
		$this->load->view('lookup/patient_icd/details', $data);
	}

	public function create()
	{

		$item = [
			'NoReg' => registration_helper::gen_registration_number(),
			'PasienKTP' => 1,
			'TglReg' => date("Y-m-d"),
			'JamReg' => date("H:i:s"),
			'JenisKerjasamaID' => 3,
			'Agama' => 'HD',
			'NationalityID' => 'INA',
			'User_ID' => $this->user_auth->User_ID,
		];

		$patient = (object) [
			'NationalityID' => 'INA',
			'PropinsiID' => 1,
		];

		$vital = (object) [
			'Height' => 0,
			'Weight' => 0,
			'Temperature' => 0,
			'Systolic' => 0,
			'Diastolic' => 0,
			'HeartRate' => 0,
			'RespiratoryRate' => 0,
			'OxygenSaturation' => 0,
			'lingkarPerut' => 0,
			'Pain' => 0,
			'Parent' => 1
		];

		if ($this->input->post()) {
			$registration = $this->input->post("f");
			// print_r($registration);exit;
			$destinations = $this->input->post("destinations");
			if (@$registration["PasienBaru"] == 1) :
				$patient = $this->input->post("p");
			else :
				$patient = array_merge((array) $this->get_model()->get_patient($registration['NRM']), $this->input->post("p"));
			endif;
			$vital = array_merge((array) $vital, (array) $this->input->post("v"));

			if (empty($destinations)) {
				$message = [
					"status" => "error",
					"message" => "Anda Belum Memilih Section Tujuan!",
					"code" => 200
				];
				response_json($message);
			}

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->get_model()->rules['insert']);
			$this->form_validation->set_data($registration);
			if ($this->form_validation->run()) {
				$message = registration_helper::create_registration($registration, $destinations, $patient, $vital);
			} else {
				$message = [
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}

			response_json($message);
		}

		if ($this->input->is_ajax_request()) {
			$data = array(
				'item' => (object) $item,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
			);

			$this->load->view(
				'registrations/modal/create_edit',
				array('form_child' => $this->load->view('registrations/form', $data, true))
			);
		} else {
			$data = array(
				"page" => $this->page . "_" . strtolower(__FUNCTION__),
				"item" => (object) $item,
				"patient" => $patient,
				"vital" => $vital,
				"option_class" => $this->class_model->dropdown_data(['Active' => 1]),
				"option_patient_type" =>  $this->registration_model->get_option_patient_type(),
				"option_nationality" => $this->registration_model->get_option_nationality(),
				"option_province" => $this->province_model->to_list_data(15),
				"option_county" => $this->county_model->to_list_data(),
				"option_district" => $this->district_model->to_list_data(),
				"option_village" => $this->village_model->to_list_data(),
				"option_area" => $this->area_model->to_list_data(),
				"lookup_reservation" => base_url("registrations/lookup_reservation_from_registration"),
				"lookup_patients" => base_url("registrations/lookup_patient"),
				"lookup_family" => base_url("registrations/lookup_family"),
				"lookup_doctor_schedule" => base_url("registrations/lookup_doctor_schedule_from_registration"),
				"lookup_section" => base_url("registrations/lookup_section"),
				"lookup_supplier" => base_url("registrations/lookup_supplier"),
				"lookup_room" => base_url("registrations/lookup_room"),
				"lookup_cooperation" => base_url("registrations/lookup_cooperation"),
				"lookup_insurer" => base_url("registrations/lookup_insurer"),
				"lookup_patient_cooperation_card" => base_url("registrations/lookup_patient_cooperation_card"),
				"lookup_second_insurer" => base_url("registrations/lookup_second_insurer"),
				"lookup_patient_second_insurer_card" => base_url("registrations/lookup_patient_second_insurer_card"),
				"delete_registration_destination_link" => base_url("registrations/delete_registration_destination"),
				"form_url" => current_url(),
				"gen_mrn_link" => base_url("registrations/gen_mr_number"),
				"get_queue_link" => base_url("registrations/get_queue"),
				"form" => TRUE,
				"datatables" => TRUE,
				"list_provinsi" => array_replace(['' => '-- Pilih --'], $this->regional_model->dropdown_data(['Level_Ke' => 1])),
				"regional_lookup" => base_url("common/patients/lookup_regional"),
			);

			$this->template
				->set("heading", lang("registrations:create_heading"))
				->set_breadcrumb(lang("registrations:breadcrumb"), base_url("registrations"))
				->set_breadcrumb(lang("registrations:create_heading"))
				->build('registrations/form', $data);
		}
	}

	public function create_from_reservation($NoReservasi = 0)
	{
		// $reservasi = $this->db->where( "NoReservasi", $NoReservasi )->get("SIMtrReservasi")->row();
		$reservasi = $this->db->select("a.*")
			->from("SIMtrReservasi a")
			->join("mPasien b", "a.NRM = b.NRM", "LEFT OUTER")
			->join("SIMdCustomerKerjasama c", "b.CustomerKerjasamaID = c.CustomerKerjasamaID", "LEFT OUTER")
			->join("mCustomer d", "c.CustomerID = d.Customer_ID", "LEFT OUTER")
			->where("NoReservasi", $NoReservasi)
			->get()->row();
		$patient = $this->patient_model->get_one(@$reservasi->NRM, TRUE);
		$regional = $this->vw_regional_model->get_by(['DesaId' => @$patient['KodeRegional']]);
		// print_r($patient);exit;
		if ($reservasi->Registrasi == 1) {
			make_flashdata(array(
				'response_status' => 'error',
				'message' => "Data Reservasi sudah melakukan Registrasi"
			));

			redirect("reservastions");
		}

		if (!empty($reservasi->NRM)) {
			$patient = $this->registration_model->get_patient($reservasi->NRM);
		}

		$item = [
			'NoReg' => registration_helper::gen_registration_number(),
			'NoReservasi' => $NoReservasi,
			'JenisKerjasamaID' => $reservasi->JenisKerjasamaID,
			'TglReg' => date("Y-m-d"),
			'JamReg' => date("H:i:s"),
			'User_ID' => $this->user_auth->User_ID,
			'PenanggungIsPasien' => @$patient->PenanggungIsPasien,
			'PenanggungNRM' => @$patient->PenanggungNRM,
			'PenanggungNama' => @$patient->PenanggungNama,
			'PenanggungAlamat' => @$patient->PenanggungAlamat,
			'PenanggungPhone' => @$patient->PenanggungPhone,
			'PenanggungKTP' => @$patient->PenanggungKTP,
			'PenanggungHubungan' => @$patient->PenanggungHubungan,
			'PenanggungPekerjaan' => @$patient->PenanggungPekerjaan
		];

		$vital = (object) [
			'Height' => 0,
			'Weight' => 0,
			'Temperature' => 0,
			'Systolic' => 0,
			'Diastolic' => 0,
			'HeartRate' => 0,
			'RespiratoryRate' => 0,
			'OxygenSaturation' => 0,
			'Pain' => 0,
			'Parent' => 1
		];

		if ($this->input->is_ajax_request()) {
			$data = array(
				'item' => $item,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
			);

			$this->load->view(
				'registrations/modal/create_edit',
				array('form_child' => $this->load->view('registrations/form', $data, true))
			);
		} else {
			$data = array(
				"page" => $this->page . "_" . strtolower(__FUNCTION__),
				"item" => (object)$item,
				"patient" => $patient,
				"vital" => $vital,
				"section_destination" => $this->registration_model->get_section_destination_from_reservation($NoReservasi),
				"option_class" => $this->class_model->dropdown_data(['Active' => 1]),
				"option_patient_type" =>  $this->registration_model->get_option_patient_type(),
				"option_nationality" => $this->registration_model->get_option_nationality(),
				"option_province" => $this->province_model->to_list_data(15),
				"option_county" => $this->county_model->to_list_data(),
				"option_district" => $this->district_model->to_list_data(),
				"option_village" => $this->village_model->to_list_data(),
				"option_area" => $this->area_model->to_list_data(),
				"lookup_reservation" => base_url("registrations/lookup_reservation_from_registration"),
				"lookup_patients" => base_url("registrations/lookup_patient"),
				"lookup_family" => base_url("registrations/lookup_family"),
				"lookup_doctor_schedule" => base_url("registrations/lookup_doctor_schedule_from_registration"),
				"lookup_section" => base_url("registrations/lookup_section"),
				"lookup_supplier" => base_url("registrations/lookup_supplier"),
				"lookup_room" => base_url("registrations/lookup_room"),
				"lookup_cooperation" => base_url("registrations/lookup_cooperation"),
				"lookup_insurer" => base_url("registrations/lookup_insurer"),
				"lookup_patient_cooperation_card" => base_url("registrations/lookup_patient_cooperation_card"),
				"lookup_second_insurer" => base_url("registrations/lookup_second_insurer"),
				"lookup_patient_second_insurer_card" => base_url("registrations/lookup_patient_second_insurer_card"),
				"delete_registration_destination_link" => base_url("registrations/delete_registration_destination"),
				"gen_mrn_link" => base_url("registrations/gen_mr_number"),
				"get_queue_link" => base_url("registrations/get_queue"),
				"form_url" => base_url("registrations/create"),
				"form" => TRUE,
				"datatables" => TRUE,
				"list_provinsi" => array_replace(['' => '-- Pilih --'], $this->regional_model->dropdown_data(['Level_Ke' => 1])),
				"regional_lookup" => base_url("common/patients/lookup_regional"),
				"regional" => $regional,
			);

			$this->template
				->set("heading", lang("registrations:edit_heading"))
				->set_breadcrumb(lang("registrations:breadcrumb"), base_url("registrations"))
				->set_breadcrumb(lang("registrations:edit_heading"))
				->build('registrations/form', $data);
		}
	}

	public function edit($NoReg = NULL)
	{
		$item = $this->registration_model->get_one($NoReg, TRUE);
		$patient = $this->patient_model->get_one(@$item['NRM'], TRUE);
		$regional = $this->vw_regional_model->get_by(['DesaId' => @$patient['KodeRegional']]);
		if (empty($item)) {
			make_flashdata(array(
				'response_status' => 'error',
				'message' => lang('global:get_failed')
			));

			redirect("registrations");
		}

		if ($item['StatusPeriksa'] != 'Belum' || $item['StatusBayar'] != "Belum") {
			redirect("registrations/view/{$NoReg}");
		}

		if ($this->input->post()) {

			$registration = array_merge($item, $this->input->post('f'));
			$destinations = $this->input->post('destinations');
			$vital = $this->input->post("v");
			$patient = array_merge((array) $this->get_model()->get_patient($registration['NRM']), $this->input->post("p"));


			if (empty($this->input->post("destinations"))) {
				$message = [
					"status" => "error",
					"message" => "Anda Belum Memilih Section Tujuan!",
					"code" => 200
				];

				response_json($message);
			}

			if ($item['StatusPeriksa'] != 'Belum' || $item['StatusBayar'] != "Belum") {
				$message = [
					"status" => "error",
					"message" => "Data Tidak bisa Diubah! Data Ini sudah melakukan pemeriksaan!",
					"code" => 200
				];

				response_json($message);
			}

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->get_model()->rules['update']);
			$this->form_validation->set_data($registration);
			if ($this->form_validation->run()) {
				$message = registration_helper::update_registration($registration, $destinations, $patient, $vital);
			} else {
				$message = [
					'response_status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				];
			}

			response_json($message);
		}

		if ($this->input->is_ajax_request()) {
			$data = array(
				'item' => $this->item,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
			);

			$this->load->view(
				'registrations/modal/create_edit',
				array('form_child' => $this->load->view('registrations/form', $data, true))
			);
		} else {
			$data = [
				"page" => $this->page . "_" . strtolower(__FUNCTION__),
				"item" => (object) $item,
				"patient" => $this->registration_model->get_patient($item['NRM']),
				"vital" => $this->vital_signs_model->get_by(['NoReg' => $NoReg, 'Parent' => 1]),
				"section_destination" => $this->registration_model->get_section_destination($item['NoReg'], $item['NRM']),
				"cooperation" => $this->registration_model->get_customer(array("Kode_Customer" => $item['KodePerusahaan'])), // Perusahaan Kerja sama
				"second_insurer" => $this->registration_model->get_customer(array("Kode_Customer" => $item['PertanggunganKeduaCompanyID'])), // Pertanggungan Kedua (IKS)
				"option_class" => $this->class_model->dropdown_data(['Active' => 1]),
				"option_patient_type" =>  $this->registration_model->get_option_patient_type(),
				"option_nationality" => $this->registration_model->get_option_nationality(),
				"option_province" => $this->province_model->to_list_data(15),
				"option_county" => $this->county_model->to_list_data(),
				"option_district" => $this->district_model->to_list_data(),
				"option_village" => $this->village_model->to_list_data(),
				"option_area" => $this->area_model->to_list_data(),
				"lookup_patients" => base_url("registrations/lookup_patient"),
				"lookup_section" => base_url("registrations/lookup_section"),
				"lookup_doctor_schedule" => base_url("registrations/lookup_doctor_schedule_from_registration"),
				"lookup_supplier" => base_url("registrations/lookup_supplier"),
				"lookup_cooperation" => base_url("registrations/lookup_cooperation"),
				"lookup_insurer" => base_url("registrations/lookup_insurer"),
				"lookup_patient_cooperation_card" => base_url("registrations/lookup_patient_cooperation_card"),
				"lookup_second_insurer" => base_url("registrations/lookup_second_insurer"),
				"lookup_patient_second_insurer_card" => base_url("registrations/lookup_patient_second_insurer_card"),
				"form_url" => current_url(),
				"gen_mrn_link" => base_url("registrations/gen_mr_number"),
				"get_queue_link" => base_url("registrations/get_queue"),
				"delete_registration_destination_link" => base_url("registrations/delete_registration_destination"),
				"cancel_link" => base_url("registrations/cancel/$NoReg"),
				"create_link" => base_url("registrations/create"),
				"print_label" => base_url("registrations/print_label/$NoReg"),
				"form" => TRUE,
				"datatables" => TRUE,
				"is_edit" => TRUE,
				"list_provinsi" => array_replace(['' => '-- Pilih --'], $this->regional_model->dropdown_data(['Level_Ke' => 1])),
				"regional_lookup" => base_url("common/patients/lookup_regional"),
				"regional" => $regional,
				"print_label" => $this->print_label($NoReg),
			];

			$this->template
				->set("heading", lang("registrations:edit_heading"))
				->set_breadcrumb(lang("registrations:breadcrumb"), base_url("registrations"))
				->set_breadcrumb(lang("registrations:edit_heading"))
				->build('registrations/form', $data);
		}
	}

	public function view($NoReg)
	{
		$item = $this->registration_model->get_one($NoReg, TRUE);

		if ($item['StatusPeriksa'] == 'Belum' && $item['StatusBayar'] == "Belum") {
			redirect("registrations/edit/{$NoReg}");
		}

		if ($this->input->is_ajax_request()) {
			$data = array(
				'item' => $item,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
			);

			$this->load->view(
				'registrations/modal/create_edit',
				array('form_child' => $this->load->view('registrations/form', $data, true))
			);
		} else {
			$data = array(
				"page" => $this->page . "_" . strtolower(__FUNCTION__),
				"item" => (object) $item,
				"patient" => $this->registration_model->get_patient($item['NRM']),
				"vital" => $this->vital_signs_model->get_by(['NoReg' => $NoReg, 'Parent' => 1]),
				"section_destination" => $this->registration_model->get_section_destination($item['NoReg'], $item['NRM']),
				"cooperation" => $this->registration_model->get_customer(array("Kode_Customer" => $item['KodePerusahaan'])), // Perusahaan Kerja sama
				"second_insurer" => $this->registration_model->get_customer(array("Kode_Customer" => $item['PertanggunganKeduaCompanyID'])), // Pertanggungan Kedua (IKS)
				"option_class" => $this->class_model->dropdown_data(['Active' => 1]),
				"option_patient_type" =>  $this->registration_model->get_option_patient_type(),
				"option_nationality" => $this->registration_model->get_option_nationality(),
				"option_province" => $this->province_model->to_list_data(15),
				"option_county" => $this->county_model->to_list_data(),
				"option_district" => $this->district_model->to_list_data(),
				"option_village" => $this->village_model->to_list_data(),
				"option_area" => $this->area_model->to_list_data(),
				"lookup_patients" => base_url("registrations/lookup_patient"),
				"lookup_doctor_schedule" => base_url("registrations/lookup_doctor_schedule_from_registration"),
				"lookup_supplier" => base_url("registrations/lookup_supplier"),
				"lookup_cooperation" => base_url("registrations/lookup_cooperation"),
				"lookup_insurer" => base_url("registrations/lookup_insurer"),
				"lookup_patient_cooperation_card" => base_url("registrations/lookup_patient_cooperation_card"),
				"lookup_second_insurer" => base_url("registrations/lookup_second_insurer"),
				"lookup_patient_second_insurer_card" => base_url("registrations/lookup_patient_second_insurer_card"),
				"gen_mrn_link" => base_url("registrations/gen_mr_number"),
				"get_queue_link" => base_url("registrations/get_queue"),
				"delete_registration_destination_link" => base_url("registrations/delete_registration_destination"),
				"cancel_link" => base_url("registrations/cancel/$NoReg"),
				"create_link" => base_url("registrations/create"),
				// "print_label" => base_url("registrations/print_label/$NoReg"),
				"print_label" => $this->print_label($NoReg),
				"form" => TRUE,
				"datatables" => TRUE,
				"is_view" => TRUE,
				"is_edit" => TRUE,
			);

			$this->template
				->set("heading", "Lihat Registrasi")
				->set_breadcrumb(lang("registrations:breadcrumb"), base_url("registrations"))
				->set_breadcrumb("Lihat Registrasi")
				->build('registrations/form', $data);
		}
	}

	public function cancel($NoReg = NULL)
	{
		$item = $this->registration_model->get_one($NoReg);
		if ($this->input->post()) {
			if (empty($item)) {
				response_json([
					'status' => 'error',
					'message' => lang('global:get_failed')
				]);
			}

			if ($item->StatusBayar != "Belum" || $item->StatusBayar == "Sudah Bayar" || $item->StatusBayar == "Proses") {
				response_json([
					'status' => 'error',
					'message' => "Gagal Batal Registrasi! Pasien dengan Registrasi ini sudah dilakukan Pembayaran."
				]);
			}

			if ($item->StatusPeriksa != "Belum" || $item->StatusPeriksa == "CO") {
				response_json([
					'status' => 'error',
					'message' => "Gagal Batal Registrasi! Pasien dengan Registrasi ini sudah dilakukan Pemeriksaan."
				]);
			}

			if ($item->NoReg == $this->input->post('confirm')) {
				$this->db->trans_begin();

				$this->registration_model->update(["Batal" => 1], $NoReg);
				$this->registration_data_model->update(["Batal" => 1], $NoReg);

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					response_json([
						"status" => 'error',
						"message" => "Gagal Cancel Registrasi",
					]);
				} else {

					$this->db->trans_commit();
					response_json([
						"status" => 'success',
						"message" => "Berhasil Cancel Registrasi",
					]);
				}
			}

			redirect($this->input->post('r_url'));
		}

		$this->load->view('modal/cancel', ['item' => $item]);
	}

	public function get_regional($DesaID = NULL)
	{
		$regional = $this->vw_regional_model->get_by(['DesaId' => $DesaID]);
		if (!empty($regional)) {
			$response = [
				'status' => 'success',
				'code' => 200,
				'message' => 'Berhasil mengambil regional',
				'data' => $regional
			];
		} else {
			$response = [
				'status' => 'warning',
				'code' => 404,
				'message' => 'Data regional belum tersedia',
				'data' => []
			];
		}
		response_json($response);
	}
	public function patient_details($NoReg = NULL)
	{

		$NoReg = ($NoReg) ? $NoReg : $this->input->post("NoReg");

		if (!$this->input->is_ajax_request()) {
			show_error("Bad Request", 400);
		}

		if (($registration = registration_helper::get_personal_registration($NoReg)) === FALSE) {
			$response = array(
				'response_status' => 'error',
				'message' => lang('global:get_failed')
			);

			print_r(json_encode($response));
			exit;
		}

		$data['item'] = $registration;
		$data['section_destination'] = $this->registration_model->get_section_destination($NoReg);
		$this->load->view('patient/details', $data);
	}

	public function dropdown($selected = '')
	{
		if ($this->input->is_ajax_request()) {
			if ($this->get_model()->count()) {
				$items = $this->get_model()
					->as_object()
					->where(array("state" => 1))
					->order_by('registration_title', 'asc')
					->get_all();

				$options_html = "";

				if ($selected == "") {
					$options_html .= "\n<option data-id=\"0\" data-code=\"\" data-title=\"\" data-price=\"0\" value=\"\" selected>" . lang('global:select-empty') . "</option>";
				} else {
					$options_html .= "\n<option data-id=\"0\" data-code=\"\" data-title=\"\" data-price=\"0\" value=\"\">" . lang('global:select-empty') . "</option>";
				}

				foreach ($items as $item) {
					$item->id = (int) $item->id;
					$item->registration_price = (float) $item->registration_price;

					$attr_data = "data-id=\"{$item->id}\" data-code=\"{$item->code}\" data-title=\"{$item->registration_title}\" data-price=\"{$item->registration_price}\" ";

					if ($selected == $item->code) {
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\" selected>{$item->code} - {$item->registration_title}</option>";
					} else {
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\">{$item->code} - {$item->registration_title}</option>";
					}
				}

				print($options_html);
				exit();
			}
		}
	}

	public function lookup($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/datatable');
		}
	}

	public function lookup_doctor_schedule_from_registration($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/view_doctor_schedules');
		}
	}

	public function lookup_doctor_schedule($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/datatable_view_doctor_schedule');
		}
	}

	public function lookup_section($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/sections');
		}
	}

	public function lookup_doctor_section($index = NULL, $is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/supplier_sections', array("index" => $index, "type" => "doctor"));
		}
	}

	public function lookup_reservation_from_registration($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$data = array(
				"gen_mrn_link" => base_url("registrations/gen_mr_number"),
			);
			$this->load->view('lookup/reservations', $data);
		}
	}

	public function lookup_reservation($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/datatable_view_reservations');
		}
	}

	public function lookup_patient($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/patients');
		}
	}

	public function lookup_family($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/families');
		}
	}

	public function lookup_personal($family_id, $is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->helper('family');
			$data = [
				'family' => $this->family_model->get_one($family_id),
				'collection' => family_helper::get_family_member($family_id),
				'gen_mrn_link' => base_url("registrations/gen_mr_number"),
			];

			$this->load->view('lookup/personals', $data);
		}
	}

	public function lookup_insurer($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/insurers');
		}
	}

	public function lookup_supplier($type, $is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/suppliers', array("type" => $type));
		}
	}

	public function lookup_room($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/room');
		}
	}

	public function lookup_doctor_datatable($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/supplier_datatables', array("type" => "doctor"));
		}
	}

	// Cooperation == Perusahaan yang diajak kerja sama (BPJS, IKS)
	public function lookup_cooperation($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/cooperations', array());
		}
	}

	// Lookup kartu anggota kerja sama patient(BPJS, IKS)
	public function lookup_patient_cooperation_card($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/patient_cooperation_cards', array());
		}
	}

	// lookup_second_insurer == Pertanggungan Kedua (IKS)
	public function lookup_second_insurer($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/second_insurers', array());
		}
	}

	// Lookup kartu anggota kerja sama kedua patient(IKS)
	public function lookup_patient_second_insurer_card($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('lookup/patient_second_insurer_cards', array());
		}
	}

	public function lookup_collection()
	{
		$this->datatable_collection(1);
	}

	public function datatable_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_from = "{$this->registration_model->table} a";
		$db_where = array();
		$db_like = array();
		$db_custome_where = NULL;

		//MATIKAN KARNA ADA ZONE WAKTU
		// if( $this->input->post("date_from") ){
		// 	$db_where['a.TglReg >='] = DateTime::createFromFormat('Y-m-d', $this->input->post("date_from"))->setTime(0,0)->format('Y-m-d');
		// }

		// if( $this->input->post("date_till") ){
		// 	$db_where['a.TglReg <='] = DateTime::createFromFormat('Y-m-d', $this->input->post("date_till"))->setTime(0,0)->format('Y-m-d');
		// }

		if ($this->input->post("date_from")) {
			$db_where['a.TglReg >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till")) {
			$db_where['a.TglReg <='] = $this->input->post("date_till");
		}

		// if( $this->input->post("show_already_checked") == 0 ){
		// 	$db_where['a.StatusPeriksa'] = 'Belum';
		// }
		if ($this->input->post("show_already_checked") == 1) {
			$db_custome_where = "(a.StatusPeriksa ='Sudah' OR a.StatusPeriksa ='CO')";
			// $db_where['a.StatusPeriksa'] = 'Sudah';
			// $db_where['a.StatusPeriksa'] = 'CO';
		}
		if ($this->input->post("belum_periksa") == 1) {
			$db_where['a.StatusPeriksa'] = 'Belum';
			$db_where['a.Batal'] = 0;
		}
		if ($this->input->post("show_cancel") == 1) {
			$db_where['a.Batal'] = $this->input->post("show_cancel");
		}

		if ($this->input->post("NRM")) {
			$db_like['a.NRM'] = $this->input->post("NRM");
		}

		if ($this->input->post("NoReg")) {
			$db_like['a.NoReg'] = $this->input->post("NoReg");
		}


		if ($this->input->post("Nama")) {
			$db_like['a.NamaPasien_Reg'] = $this->input->post("Nama");
		}

		if ($TipePelayanan = $this->input->post("TipePelayanan")) {
			$db_like["a.{$TipePelayanan}"] = 1;
		}

		// preparing default
		if (isset($search['value']) && !empty($search['value'])) {
			$keywords = $this->db->escape_str($search['value']);

			$db_like[$this->db->escape_str("a.NoReg")] = $keywords;
			$db_like[$this->db->escape_str("a.NRM")] = $keywords;
			$db_like[$this->db->escape_str("a.TglReg")] = $keywords;
			$db_like[$this->db->escape_str("a.JamReg")] = $keywords;
			$db_like[$this->db->escape_str("a.NamaPasien_Reg")] = $keywords;
			$db_like[$this->db->escape_str("b.Phone")] = $keywords;
			$db_like[$this->db->escape_str("c.JenisKerjasama")] = $keywords;

			$db_like[$this->db->escape_str("a.NoReservasi")] = $keywords;
		}

		//get total records
		$this->db->from($db_from);
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_custome_where)) {
			$this->db->where($db_custome_where);
		}
		$records_total = $this->db->count_all_results();

		// get total filtered
		$this->db
			->from($db_from);

		if ($this->input->post("SectionID") || $this->input->post("DokterID")) {

			$this->db->join("SIMtrDataRegPasien r", "a.NoReg = r.NoReg", "INNER");

			if ($this->input->post("SectionID")) {
				$this->db->join("{$this->section_model->table} s", "r.SectionID = s.SectionID", "LEFT OUTER");
				$db_where['r.SectionID'] = $this->input->post("SectionID");
			}

			if ($this->input->post("DokterID")) {
				$this->db->join("{$this->supplier_m->table} t", "r.DokterID = t.Kode_Supplier", "LEFT OUTER");
				$db_where['r.DokterID'] = $this->input->post("DokterID");
			}
		}

		$this->db->join("{$this->patient_model->table} b", "a.NRM = b.NRM", "LEFT OUTER")
			->join("{$this->patient_type_m->table} c", "a.JenisKerjasamaID = c.JenisKerjasamaID", "LEFT OUTER")
			->join("mUser e", "a.User_ID = e.User_ID", "LEFT OUTER");

		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}
		if (!empty($db_custome_where)) {
			$this->db->where($db_custome_where);
		}
		$records_filtered = $this->db->count_all_results();

		// get result filtered
		$db_select = <<<EOSQL
			a.NoReg,
			a.NoReservasi,
			a.NRM,
			a.TglReg,
			a.JamReg,
			a.StatusPeriksa,
			a.StatusBayar,
			a.Batal,
			b.NamaPasien,
			b.Phone,
			b.Alamat,
			b.JenisKelamin,
			c.JenisKerjasamaID,
			c.JenisKerjasama,
			e.Nama_Singkat,
						
EOSQL;

		if ($this->input->post("SectionID")) {
			$db_select .= <<<EOSQL
			s.SectionName,
EOSQL;
		}

		if ($this->input->post("DokterID")) {
			$db_select .= <<<EOSQL
			t.Nama_Supplier,
EOSQL;
		}

		$this->db
			->select($db_select)
			->from($db_from);

		if ($this->input->post("SectionID") || $this->input->post("DokterID")) {

			$this->db->join("SIMtrDataRegPasien r", "a.NoReg = r.NoReg", "INNER");

			if ($this->input->post("SectionID")) {
				$this->db->join("{$this->section_model->table} s", "r.SectionID = s.SectionID", "LEFT OUTER");
				$db_where['r.SectionID'] = $this->input->post("SectionID");
			}

			if ($this->input->post("DokterID")) {
				$this->db->join("{$this->supplier_m->table} t", "r.DokterID = t.Kode_Supplier", "LEFT OUTER");
				$db_where['r.DokterID'] = $this->input->post("DokterID");
			}
		}

		$this->db->join("{$this->patient_model->table} b", "a.NRM = b.NRM", "LEFT OUTER")
			->join("{$this->patient_type_m->table} c", "a.JenisKerjasamaID = c.JenisKerjasamaID", "LEFT OUTER")
			->join("mUser e", "a.User_ID = e.User_ID", "LEFT OUTER");

		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}
		if (!empty($db_custome_where)) {
			$this->db->where($db_custome_where);
		}

		// ordering
		if (isset($order)) {
			$sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];

			if ($columns[$sort_column]['orderable'] == 'true') {
				$this->db
					->order_by($columns[intval($this->db->escape_str($sort_column))]['name'], $this->db->escape_str($sort_dir));
			}
		}

		// paging
		if (isset($start) && $length != '-1') {
			$this->db
				->limit($length, $start);
		}

		// get
		$result = $this->db
			->get()
			->result();

		// Output
		$output = array(
			'draw' => intval($draw),
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_filtered,
			'data' => array()
		);

		foreach ($result as $row) {
			$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->TglReg);
			$time = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->JamReg);

			$row->TglReg = $date->format('Y-m-d');
			$row->JamReg = $time->format('H:i:s');

			$output['data'][] = $row;
		}

		$this->template
			->build_json($output);
	}

	public function doctor_schedule_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_from = "SIMtrDokterJagaDetail AS a";
		$db_where = array();
		$db_like = array();

		$db_where['a.tanggal'] = date("Y-m-d");

		// preparing default
		if (isset($search['value']) && !empty($search['value'])) {
			$keywords = $this->db->escape_str($search['value']);

			$db_like[$this->db->escape_str("b.Nama_Supplier")] = $keywords;
			$db_like[$this->db->escape_str("e.SpesialisName")] = $keywords;
			$db_like[$this->db->escape_str("c.SectionName")] = $keywords;
			$db_like[$this->db->escape_str("d.Keterangan")] = $keywords;
		}

		// get total records
		$this->db->from($db_from)
			->join("{$this->supplier_m->table} b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
			->join("{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER")
			->join("{$this->section_model->table} c", "a.SectionID = c.SectionID", "LEFT OUTER")
			->join("{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		$records_total = $this->db->count_all_results();

		// get total filtered
		$this->db
			->from($db_from)
			->join("{$this->supplier_m->table} b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
			->join("{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER")
			->join("{$this->section_model->table} c", "a.SectionID = c.SectionID", "LEFT OUTER")
			->join("{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}
		$records_filtered = $this->db->count_all_results();

		// get result filtered
		$db_select = <<<EOSQL
			a.DokterID,
			a.SectionID,
			a.Cancel,
			a.DokterPenggantiID,
			a.NoAntrianTerakhir,
			a.WaktuID,
			b.Nama_Supplier,
			e.SpesialisName,
			c.SectionName,
			d.Keterangan
			
EOSQL;

		$this->db
			->select($db_select)
			->from($db_from)
			->join("{$this->supplier_m->table} b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
			->join("{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER")
			->join("{$this->section_model->table} c", "a.SectionID = c.SectionID", "LEFT OUTER")
			->join("{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}

		// ordering
		if (isset($order)) {
			$sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];

			if ($columns[$sort_column]['orderable'] == 'true') {
				$this->db
					->order_by($columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir));
			}
		}

		// paging
		if (isset($start) && $length != '-1') {
			$this->db
				->limit($length, $start);
		}

		// get
		$result = $this->db
			->get()
			->result();

		// Output
		$output = array(
			'draw' => intval($draw),
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_filtered,
			'data' => array()
		);

		foreach ($result as $row) {
			if ($row->Cancel == 1 && !empty($row->DokterPenggantiID)) {
				$row->NamaDokterPengganti = "-";
			} else if ($row->Cancel == 1 && empty($row->DokterPenggantiID)) {
				$row->NamaDokterPengganti = "-";
			} else if ($row->Cancel == 0) {
				$row->NamaDokterPengganti = "-";
			}

			$queue_where = (object) array("DokterID" => $row->DokterID, "SectionID" => $row->SectionID, "WaktuID" => $row->WaktuID, "Tanggal" => date("Y-m-d"));
			$queue = registration_helper::get_queue($queue_where);
			$row->NoAntri = $queue > $this->config->item("start_queue") ? $queue : $this->config->item("start_queue");
			// Update antrian pada Jadwal			
			$output['data'][] = $row;
		}

		//print_r($output);exit;

		$this->template
			->build_json($output);
	}

	public function reservation_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_from = "SIMtrReservasi a";
		$db_where = array();
		$db_like = array();

		if ($this->input->post("today")) {
			$db_where['a.UntukTanggal'] = date("Y-m-d");
		}

		if (!$this->input->post("is_register")) {
			$db_where['a.Registrasi'] = 0;
		}


		// preparing default
		if (isset($search['value']) && !empty($search['value'])) {
			$keywords = $this->db->escape_str($search['value']);

			$db_like[$this->db->escape_str("a.NoReservasi")] = $keywords;
			$db_like[$this->db->escape_str("e.NRM")] = $keywords;
			$db_like[$this->db->escape_str("a.Nama")] = $keywords;
			$db_like[$this->db->escape_str("a.Alamat")] = $keywords;
			$db_like[$this->db->escape_str("c.SectionName")] = $keywords;
			$db_like[$this->db->escape_str("b.Nama_Supplier")] = $keywords;
			$db_like[$this->db->escape_str("a.UntukHari")] = $keywords;
			$db_like[$this->db->escape_str("a.UntukTanggal")] = $keywords;
			$db_like[$this->db->escape_str("d.Keterangan")] = $keywords;
		}

		// get total records
		$this->db->from($db_from)
			->join("{$this->supplier_m->table} b", "a.UntukDokterID = b.Kode_Supplier", "LEFT OUTER")
			->join("{$this->section_model->table} c", "a.UntukSectionID = c.SectionID", "LEFT OUTER")
			->join("{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER")
			->join("{$this->patient_model->table} e", "a.NRM = e.NRM", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		$records_total = $this->db->count_all_results();

		// get total filtered
		$this->db
			->from($db_from)
			->join("{$this->supplier_m->table} b", "a.UntukDokterID = b.Kode_Supplier", "LEFT OUTER")
			->join("{$this->section_model->table} c", "a.UntukSectionID = c.SectionID", "LEFT OUTER")
			->join("{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER")
			->join("{$this->patient_model->table} e", "a.NRM = e.NRM", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}
		$records_filtered = $this->db->count_all_results();

		// get result filtered
		$db_select = <<<EOSQL

			a.NoReservasi
			,a.Tanggal
			,a.Jam
			,a.PasienBaru
			,a.NRM
			,a.Nama
			,a.Alamat
			,a.Phone
			,a.UntukSectionID
			,a.UntukDokterID
			,a.UntukHari
			,a.UntukTanggal
			,a.UntukJam
			,a.NoUrut AS NoAntri
			,a.User_ID
			,a.Registrasi
			,a.JmlAntrian
			,a.WaktuID
			,a.JenisKerjasamaID
			,a.TanggalLahir
			,a.Memo
			,a.Email
			,a.TglPerkiraan_1
			,a.TglPerkiraan_2
			,a.Tindakan_SC
			,a.Tindakan_Normal
			,a.KelasID
			,a.Deposit
			,a.PermintaanKhusus
			,a.Batal
			,a.Paid
			,a.PaidNoBukti
			,a.UserIDPaid
			,a.TipeReservasi
			,a.NamaSuami
			,a.KodeCUstomerIKS
			
			,b.Nama_Supplier
			,c.SectionName
			,d.Keterangan AS KeteranganWaktu


			,e.NoIdentitas
			,e.JenisKelamin
			,e.TglLahir
			,e.TglLahirDiketahui
			,e.UmurSaatInput
			,e.Pekerjaan
			,e.PropinsiID
			,e.KabupatenID
			,e.KecamatanID
			,e.DesaID
			,e.BanjarID
			
			,e.JenisPasien
			,e.AnggotaBaru
			,e.CustomerKerjasamaID
			,e.NoKartu
			,e.Klp
			,e.JabatanDiPerusahaan
			,e.PasienLoyal
			
			,e.TotalKunjunganRawatInap
			,e.TotalKunjunganRawatJalan
			,e.KunjunganRJ_TahunIni
			,e.KunjunganRI_TahunIni
			
			,e.EtnisID
			,e.NationalityID
			,e.PasienVVIP
			,e.PasienKTP
			,e.TglInput
			,e.UserID
			,e.CaraDatangPertama
			,e.DokterID_ReferensiPertama
			,e.SedangDirawat
			,e.KodePos
			
			,e.TglRegKasusKecelakaanBaru
			,e.NoRegKecelakaanBaru
			,e.Aktive_Keanggotaan
			,e.Agama
			,e.NoANggotaE
			,e.NamaAnggotaE
			,e.GenderAnggotaE
			,e.TglTidakAktif
			,e.TipePasienAsal
			,e.NoKartuAsal
			,e.NamaPerusahaanAsal

			,e.PenanggungIsPasien
			,e.PenanggungNRM
			,e.PenanggungNama
			,e.PenanggungAlamat
			,e.PenanggungPhone
			,e.PenanggungKTP
			,e.PenanggungHubungan
			,e.PenanggungPekerjaan
			
			,e.Aktif
			,e.PasienBlackList
			,e.NamaIbuKandung
			,e.NonPBI
			,e.KdKelas
			,e.Prematur
			,e.NamaAlias
						
			
			
EOSQL;

		$this->db
			->select($db_select)
			->from($db_from)
			->join("{$this->supplier_m->table} b", "a.UntukDokterID = b.Kode_Supplier", "LEFT OUTER")
			->join("{$this->section_model->table} c", "a.UntukSectionID = c.SectionID", "LEFT OUTER")
			->join("{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER")
			->join("{$this->patient_model->table} e", "a.NRM = e.NRM", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}

		// ordering
		if (isset($order)) {
			$sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];

			if ($columns[$sort_column]['orderable'] == 'true') {
				$this->db
					->order_by($columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir));
			}
		}

		// paging
		if (isset($start) && $length != '-1') {
			$this->db
				->limit($length, $start);
		}

		// get
		$result = $this->db
			->get()
			->result();

		// Output
		$output = array(
			'draw' => intval($draw),
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_filtered,
			'data' => array()
		);

		foreach ($result as $row) {
			$params = (object) array(
				"SectionID" => $row->UntukSectionID,
				"DokterID" => $row->UntukDokterID,
				"WaktuID" => $row->WaktuID,
			);

			//$queue = registration_helper::get_queue( $params );
			//$row->NoUrut = $queue > $this->config->item("start_queue") ? $queue : $this->config->item("start_queue");

			$output['data'][] = $row;
		}

		$this->template
			->build_json($output);
	}

	public function gen_mr_number()
	{
		if ($this->input->is_ajax_request()) {
			$response = [
				"status" => "success",
				"message" => "",
				"code" => 200
			];

			if ($mrn = registration_helper::gen_general_nrm_number()) {
				$response['mrn'] = $mrn;
			} else {
				$response = [
					"status" => "error",
					"message" => "Failed Generate Medical Recordd Number",
					"code" => 500
				];
			}
			response_json($response);
		}
	}

	public function time_dropdown($selected = '')
	{
		if ($this->input->is_ajax_request()) {
			$items = $this->db
				->order_by('Keterangan', 'asc')
				->get("SIMmWaktuPraktek")
				->result();

			$options_html = "";

			if ($selected == "") {
				$options_html .= "\n<option data-waktuid=\"0\" data-keterangan=\"\" value=\"\" selected>" . lang('global:select-empty') . "</option>";
			} else {
				$options_html .= "\n<option data-waktuid=\"0\" data-keterangan=\"\" value=\"\">" . lang('global:select-empty') . "</option>";
			}

			foreach ($items as $item) {

				$attr_data = "data-waktuid=\"{$item->WaktuID}\" data-keterangan=\"{$item->Keterangan}\" ";

				if ($selected == $item->WaktuID) {
					$options_html .= "\n<option {$attr_data} value=\"{$item->WaktuID}\" selected>{$item->Keterangan}</option>";
				} else {
					$options_html .= "\n<option {$attr_data} value=\"{$item->WaktuID}\">{$item->Keterangan}</option>";
				}
			}

			print($options_html);
			exit();
		}
	}

	public function get_queue()
	{
		if ($this->input->is_ajax_request() && $this->input->post()) {
			$response = [
				"status" => "success",
				"message" => "",
				"code" => 200
			];

			$params = (object) $this->input->post();

			if ($queue = registration_helper::get_queue($params)) {
				$response['queue'] = $queue;
			} else {
				$response = [
					"status" => "error",
					"message" => "Failed Get Queue",
					"code" => 500
				];
			}

			response_json($response);
		}
	}

	public function delete_registration_destination()
	{
		if ($this->input->post() && $this->input->is_ajax_request()) {
			$data_post = $this->input->post();
			$db_where = [
				'NoReg' => $data_post['NoReg'],
				'SectionID' => $data_post['SectionID'],
				'DokterID' => $data_post['DokterID'],
				'WaktuID' => $data_post['WaktuID'],
			];
			$registration_data = $this->registration_data_model->get_by($db_where);

			if (empty($registration_data))
				exit(0);

			if ((bool) $registration_data->SudahPeriksa) {
				$response = [
					"status" => "error",
					"message" => "Registrasi Tujuan Tidak Bisa Dihapus! Karena Pasien Sudah Dilakukan pemeriksaan!",
					"code" => 200
				];
				response_json($response);
			}

			$this->db->trans_begin();
			$this->registration_destination_model->delete_by($db_where);
			$this->registration_data_model->delete_by($db_where);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$response = [
					"status" => 'error',
					"message" => lang('global:deleted_failed'),
					"code" => 500
				];
			} else {
				$this->db->trans_commit();
				$response = [
					"status" => 'success',
					"message" => lang('global:deleted_successfully'),
					"code" => 200
				];
			}
		}

		response_json($response);
	}


	// public function print_label( $NoReg= NULL )
	// {
	// 	$this->load->helper("report");
	// 	$item = report_helper::get_registration_label( $NoReg );

	// 	if ( empty($item) ){
	// 		redirect("registrations");
	// 	}

	// 	$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $item->TglReg);		

	// 	$counter = explode("-", $item->NoReg);
	// 	$item->NoRegLabel = sprintf("%s-%s", $date->format('y'), $counter[1]);

	// 	$data = array(
	// 				"item" => $item,
	// 			);

	// 	$html_content =  $this->load->view( "print/label", $data, TRUE); 
	// 	$file_name = 'Print  Label';		
	// 	$this->load->helper( "export" );

	// 	export_helper::generate_label_pdf( $html_content, $file_name, NULL , $margin_bottom = 1.0, $header = NULL, $margin_top = 0.3, $orientation = 'P', $margin_left = 0.3, $margin_right = 0.3);	
	// 	exit(0);

	// }

	public function print_label($NoReg = NULL)
	{
		$this->load->helper("report");
		$item = report_helper::get_registration_label($NoReg);

		if (empty($item)) {
			redirect("registrations");
		}

		$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $item->TglReg);

		$counter = explode("-", $item->NoReg);
		$item->NoRegLabel = sprintf("%s-%s", $date->format('y'), $counter[1]);

		$item->NoAntri = $this->db->where('NoReg', $NoReg)->from('SIMtrDataRegPasien')->get()->row()->NoAntri;

		$data = array(
			"item" => $item,
		);

		$html_content =  $this->load->view("print/label", $data, TRUE);
		$file_name = 'Print  Label';
		$this->load->helper("export");

		return chunk_split(base64_encode(export_helper::print_pdf_string($html_content, $file_name, $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P', $margin_left = 6, $margin_right = 5)));
	}
}
