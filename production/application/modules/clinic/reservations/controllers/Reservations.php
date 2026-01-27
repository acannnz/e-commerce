<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reservations extends Admin_Controller
{
	protected $_translation = 'reservations';
	//protected $_model = 'reservation_m'; 

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('reservation');

		$this->page = "common_reservations";
		$this->template->title(lang("reservations:page") . ' - ' . $this->config->item('company_name'));

		$this->load->helper('reservation');
		$this->load->helper('registrations/registration');
		$this->load->model('reservation_m');
		$this->load->model('patient_model');
		$this->load->model('common/patient_type_m');
		$this->load->model('common/section_m');
		$this->load->model('common/supplier_m');
	}

	public function index($UntukTanggal = NULL)
	{
		$data = array(
			"option_section" => $this->reservation_m->get_option_section(),
			"page" => $this->page,
			"form" => TRUE,
			"datatables" => TRUE,
			"option_doctor" => option_doctor(),
			"UntukTanggal" => $UntukTanggal

		);

		$this->template
			->set("heading", "Reservasi")
			->set_breadcrumb(lang("reservations:breadcrumb"))
			->build('reservations/datatable', (isset($data) ? $data : NULL));
	}

	public function index_reminder()
	{
		$data = array(
			'page' => $this->page,
			"form" => TRUE,
			'datatables' => TRUE,
		);

		$this->template
			->set("heading", "Reservasi")
			->set_breadcrumb("List Reservasi")
			->build('reservations/datatable_reminder', (isset($data) ? $data : NULL));
	}

	public function create()
	{
		$patient_type = $this->patient_type_m->options_type();
		$option_section = $this->reservation_m->get_option_section();
		$option_time = $this->reservation_m->get_option_time();

		$weekDay = array("MINGGU", "SENIN", "SELASA", "RABU", "KAMIS", "JUMAT", "SABTU");
		$item = array(
			'NoReservasi' => reservation_helper::gen_reservation_number(),
			'Tanggal' => date("Y-m-d"),
			'Jam' => date("Y-m-d H:i:s"),
			'User_ID' => $this->user_auth->User_ID,
			'PasienBaru' => 0,
			'Registrasi' => 0,
			'Batal' => 0,
			'Paid' => 0,
			'TipeReservasi' => 'RESERVASI POLI',
			'UntukTanggal' => date("Y-m-d"),
			'UntukHari' => $weekDay[date("w")],
		);


		if ($this->input->post()) {

			$item = array_merge($item, $this->input->post("f"));
			$item['NoReservasi'] = reservation_helper::gen_reservation_number();
			$item['UntukJam'] = $item['UntukTanggal'] . " " . date("H:i:s");

			$weekDay = array("MINGGU", "SENIN", "SELASA", "RABU", "KAMIS", "JUMAT", "SABTU");
			$item['UntukHari'] = $weekDay[date("w", strtotime($item['UntukTanggal']))];

			$this->load->library('form_validation');
			//$this->form_validation->set_rules( $this->reservation_m->rules['insert'] );
			$this->form_validation->set_data($item);

			if (!$this->form_validation->run()) {
				if ($this->db->insert("SIMtrReservasi", $item)) {
					make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:created_successfully')
					));

					redirect('reservations/index/' . $item['UntukTanggal']);
				} else {
					make_flashdata(array(
						'response_status' => 'error',
						'message' => lang('global:created_failed')
					));
				}
			} else {
				make_flashdata(array(
					'response_status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				));
			}
		}

		if ($this->input->is_ajax_request()) {
			$data = array(
				'item' => (object)$item,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
			);

			$this->load->view(
				'reservations/modal/create_edit',
				array('form_child' => $this->load->view('reservations/form', $data, true))
			);
		} else {
			$data = array(
				"page" => $this->page . "_" . strtolower(__FUNCTION__),
				"item" => (object)$item,
				'option_type_patient' => $patient_type,
				'option_section' => $option_section,
				'option_time' => $option_time,
				"lookup_patient" => base_url("reservations/lookup_patient"),
				"lookup_schedule" => base_url("reservations/lookup_schedule"),
				"lookup_doctor" => base_url("reservations/lookup_doctor"),
				"get_reservation_queue" => base_url("reservations/get_reservation_queue"),
				"form" => TRUE,
				"datatables" => TRUE,
			);

			$this->template
				->set("heading", lang("reservations:create_heading"))
				->set_breadcrumb(lang("reservations:breadcrumb"), base_url("reservations"))
				->set_breadcrumb(lang("reservations:create_heading"))
				->build('reservations/form', $data);
		}
	}

	public function calender($UntukDokterID = NULL)
	{

		// $patient_type = $this->patient_type_m->options_type();
		// $option_time = $this->reservation_m->get_option_time();
		$option_section = $this->reservation_m->get_option_section();
		$doctor = $this->db->where("Kode_Supplier", @$UntukDokterID)->get("mSupplier")->row();

		$weekDay = array("MINGGU", "SENIN", "SELASA", "RABU", "KAMIS", "JUMAT", "SABTU");
		$item = array(
			'NoReservasi' => reservation_helper::gen_reservation_number(),
			'Tanggal' => date("Y-m-d"),
			'Jam' => date("Y-m-d H:i:s"),
			'User_ID' => $this->user_auth->User_ID,
			'PasienBaru' => 0,
			'Registrasi' => 0,
			'Batal' => 0,
			'Paid' => 0,
			'TipeReservasi' => 'RESERVASI POLI',
			'UntukHari' => $weekDay[date("w")],
			'UntukDokterID' => @$UntukDokterID,
		);


		if ($this->input->post()) {

			$item = array_merge($item, $this->input->post("f"));
			$pasien = $this->db->where("NRM", @$item['NRM'])->get("mPasien")->row();
			$item['NoReservasi'] = reservation_helper::gen_reservation_number();
			$item['UntukTanggal'] = $item['Tanggal'];
			$item['Phone'] = $pasien->Phone;
			$item['Email'] = $pasien->Email;
			$item['Alamat'] = $pasien->Alamat;

			$weekDay = array("MINGGU", "SENIN", "SELASA", "RABU", "KAMIS", "JUMAT", "SABTU");
			$item['UntukHari'] = $weekDay[date("w", strtotime($item['UntukTanggal']))];

			$this->load->library('form_validation');
			$this->form_validation->set_data($item);

			if (!$this->form_validation->run()) {
				if ($this->db->insert("SIMtrReservasi", $item)) {
					make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:created_successfully')
					));

					redirect('reservations/calender/' . $item['UntukDokterID']);
				} else {
					make_flashdata(array(
						'response_status' => 'error',
						'message' => lang('global:created_failed')
					));
				}
			} else {
				make_flashdata(array(
					'response_status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				));
			}
		}

		if ($this->input->is_ajax_request()) {
			$data = array(
				"item" => (object)$item,
				"is_ajax_request" => TRUE,
				"get_calender" => base_url("reservations/calender_collection"),
				"lookup_doctor" => base_url("reservations/lookup_doctor"),
				"lookup_patient" => base_url("reservations/lookup_patient"),
				"is_modal" => TRUE,
				'option_section' => $option_section,
				'UntukDokterID' => $UntukDokterID,
				'doctor' => $doctor,
				"get_reservation_queue" => base_url("reservations/get_reservation_queue"),
			);

			$this->load->view(
				'reservations/modal/create_edit',
				array('form_child' => $this->load->view('reservations/form_calendar', $data, true))
			);
		} else {
			$data = array(
				"page" => $this->page . "_" . strtolower(__FUNCTION__),
				"item" => (object)$item,
				"form" => TRUE,
				"get_calender" => base_url("reservations/calender_collection"),
				"lookup_doctor" => base_url("reservations/lookup_doctor"),
				"lookup_patient" => base_url("reservations/lookup_patient"),
				"datatables" => TRUE,
				'option_section' => $option_section,
				'UntukDokterID' => $UntukDokterID,
				'doctor' => $doctor,
				"get_reservation_queue" => base_url("reservations/get_reservation_queue"),
			);

			$this->template
				->set("heading", 'Data Pasien Reservasi')
				->set_breadcrumb('Calender Reservasi', base_url("reservations/calender"))
				->set_breadcrumb('Data Pasien Reservasi')
				->build('reservations/calender/form', $data);
		}
	}

	public function calender_collection($UntukDokterID = NULL)
	{
		$data = $this->input->get_post(NULL, TRUE);	
		if (!empty($data['DokterID'])) {
			$collection = [];
			$where = array('UntukDokterID' => $data['DokterID'], 'Batal' => 0);

			$this->db->where($where);

			if (!empty($data['start'])) {
				$this->db->where('UntukTanggal >=', date('Y-m-d', strtotime($data['start'])));
			}
			if (!empty($data['end'])) {
				$this->db->where('UntukTanggal <=', date('Y-m-d', strtotime($data['end'])));
			}

			$item = $this->db->get($this->reservation_m->table)->result();


			foreach ($item as $key => $value) {
				$datetimeString = $value->Waktu;
				$dateTime = new DateTime($datetimeString);
				$timeString = $dateTime->format('H:i:s');

				if ($value->Registrasi == 1) {
					$v = [
						'title' => $value->Nama,
						'start' => date('Y-m-d', strtotime($value->UntukTanggal)) . 'T' . $timeString,
						'color' => 'green',
						'description' => $value->Memo,
						'UntukDokterID' => $UntukDokterID
					];
				} else {
					$v = [
						'title' => $value->Nama,
						'start' => date('Y-m-d', strtotime($value->UntukTanggal)) . 'T' . $timeString,
						'color' => 'red',
						'description' => $value->Memo,
						'UntukDokterID' => $UntukDokterID
					];
				}


				$collection[] = $v;
			}

			response_json($collection);
		} else {
			response_json([]);
		}
	}

	public function edit($NoReservasi = 0)
	{
		if ($NoReservasi == 0) {
			make_flashdata(array(
				'response_status' => 'erorr',
				'message' => lang('global:error')
			));
			redirect("reservations");
		}

		$item = $this->db->where("NoReservasi", $NoReservasi)->get($this->reservation_m->table)->row();
		$pasien = $this->db->where("NRM", $item->NRM)->get($this->patient_model->table)->row();
		if ($item->Phone == '' && $item->Email == '' && $item->Alamat == '') {
			$item->Phone = $pasien->Phone;
			$item->Email = $pasien->Email;
			$item->Alamat = $pasien->Alamat;
		}

		$doctor = $this->db->where("Kode_Supplier", $item->UntukDokterID)->get("mSupplier")->row();
		$option_section = $this->reservation_m->get_option_section();
		$option_time = $this->reservation_m->get_option_time();
		$patient_type = $this->patient_type_m->options_type();

		if ($this->input->post()) {
			$data = $this->input->post("f");

			$weekDay = array("MINGGU", "SENIN", "SELASA", "RABU", "KAMIS", "JUMAT", "SABTU");
			$data['UntukHari'] = $weekDay[date("w", strtotime($data['UntukTanggal']))];

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->reservation_m->rules['insert']);
			$this->form_validation->set_data($data);

			if ($item->Registrasi ==  1) {
				make_flashdata(array(
					'response_status' => 'error',
					'message' => "Data Reservasi ini sudah melakukan Registrasi"
				));
				redirect("reservations/edit/$NoReservasi");
			}

			if (!$this->form_validation->run()) {
				if ($this->db->update($this->reservation_m->table, $data, array("NoReservasi" => $NoReservasi))) {

					make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:updated_successfully')
					));

					redirect('reservations/index/' . $data['UntukTanggal']);
				} else {
					make_flashdata(array(
						'response_status' => 'error',
						'message' => lang('global:updated_failed')
					));
				}
			} else {
				make_flashdata(array(
					'response_status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				));
			}
		}

		if ($this->input->is_ajax_request()) {
			$data = array(
				'item' => $item,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
			);

			$this->load->view(
				'reservations/modal/create_edit',
				array('form_child' => $this->load->view('reservations/form', $data, true))
			);
		} else {
			$data = array(
				"item" => $item,
				"doctor" => $doctor,
				"option_section" => $option_section,
				"option_time" => $option_time,
				"option_type_patient" => $patient_type,
				"form_url" => current_url(),
				"lookup_patient" => base_url("reservations/lookup_patient"),
				"lookup_schedule" => base_url("reservations/lookup_schedule"),
				"lookup_doctor" => base_url("reservations/lookup_doctor"),
				"get_reservation_queue" => base_url("reservations/get_reservation_queue"),
				"cancel_link" => base_url("reservations/cancel/$NoReservasi"),
				"form" => TRUE,
				"is_edit" => TRUE,
				"datatables" => TRUE,
			);

			$this->template
				->set("heading", "Reservasi")
				->set_breadcrumb(lang("reservations:breadcrumb"), base_url("reservations"))
				->set_breadcrumb(lang("reservations:edit_heading"))
				->build('reservations/form', $data);
		}
	}

	public function cancel($NoReservasi = NULL)
	{
		$item = $this->reservation_m->get_one($NoReservasi);
		if ($this->input->post()) {
			if ($item->Registrasi ==  1) {
				make_flashdata(array(
					'response_status' => 'error',
					'message' => "Data Reservasi ini sudah melakukan Registrasi"
				));
				redirect("reservations/edit/$NoReservasi");
			}


			if ($item->NoReservasi == $this->input->post('confirm')) {
				$this->db->trans_begin();

				$this->reservation_m->update(["Batal" => 1], $NoReservasi);

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					make_flashdata(array(
						'response_status' => 'error',
						'message' => "Gagal Cancel Reservasi"
					));
				} else {

					$this->db->trans_commit();
					make_flashdata(array(
						'response_status' => 'success',
						'message' => "Berhasil Cancel Reservasi"
					));
				}
			}
			redirect($this->input->post('r_url'));
		}

		$this->load->view('modal/cancel', ['item' => $item]);
	}

	public function lookup_patient($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			if ($this->input->get_post("is_modal")) {
				$data["is_modal"] = TRUE;
			}

			$this->load->view('lookup/patients', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_doctor($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$data = array(
				"type" => "doctor"
			);
			$this->load->view('lookup/suppliers', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_doctor_datatable($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$data = array(
				"type" => "doctor"
			);
			$this->load->view('lookup/supplier_datatables', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_schedule($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			if ($this->input->get_post("is_modal")) {
				$data["is_modal"] = TRUE;
			}

			$this->load->view('lookup/schedules', (isset($data) ? $data : NULL));
		}
	}


	public function lookup($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('reservations/lookup/datatable');
		} else {
			$data = array(
				'page' => $this->page,
				'datatables' => TRUE,
				'form' => TRUE,
			);

			$this->template
				->set("heading", "Lookup Box")
				->set_breadcrumb(lang("common:page"), base_url("common"))
				->set_breadcrumb("Lookup Box")
				->build('reservations/lookup', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_collection()
	{
		$this->datatable_collection(1);
	}


	public function datatable_collection($state = false)
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_from = "{$this->reservation_m->table} a";
		$db_where = array();
		$db_or_where = array();
		$db_like = array();

		if ($this->input->post("reminder") == 1) {
			$db_where['a.UntukTanggal'] = date('Y-m-d', strtotime("+3 days"));
		}

		/*if( $this->input->post("date_from") ){
			$db_where['a.Tanggal >='] = $this->input->post("date_from");
		}

		if( $this->input->post("date_till") ){
			$db_where['a.Tanggal <='] = $this->input->post("date_till");
		}*/

		if ($this->input->post("for_date_from")) {
			$db_where['a.UntukTanggal >='] = $this->input->post("for_date_from");
		}

		if ($this->input->post("for_date_till")) {
			$db_where['a.UntukTanggal <='] = $this->input->post("for_date_till");
		}

		if ($this->input->post("NRM")) {
			$db_like['a.NRM'] = $this->input->post("NRM");
		}

		if ($this->input->post("Nama")) {
			$db_like['a.Nama'] = $this->input->post("Nama");
		}

		if ($this->input->post("Phone")) {
			$db_like['a.Phone'] = $this->input->post("Phone");
		}

		if ($this->input->post("SectionID")) {
			$db_where['a.UntukSectionID'] = $this->input->post("SectionID");
		}

		if ($this->input->post("DokterID")) {
			$db_where['a.UntukDokterID'] = $this->input->post("DokterID");
		}

		if ($this->input->post("show_already_registration")) {
			$db_or_where['a.Registrasi'] = $this->input->post("show_already_registration");
		}

		if ($this->input->post("show_cancel")) {
			$db_or_where['a.Batal'] = $this->input->post("show_cancel");
		} else {
			$db_or_where['a.Batal'] = 0;
		}

		// preparing default
		if (isset($search['value']) && !empty($search['value'])) {
			$keywords = $this->db->escape_str($search['value']);

			$db_like[$this->db->escape_str("a.NoReservasi")] = $keywords;

			$db_like[$this->db->escape_str("a.NRM")] = $keywords;
		}

		// get total records
		$this->db->from($db_from);
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		$records_total = $this->db->count_all_results();

		// get total filtered
		$this->db
			->from($db_from)
			->join("{$this->section_m->table} b", "a.UntukSectionID=b.SectionID", "LEFT OUTER")
			->join("{$this->supplier_m->table} c", "a.UntukDokterID=c.Kode_Supplier", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_or_where)) {
			$this->db->group_start()->or_where($db_or_where)->group_end();
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}
		$records_filtered = $this->db->count_all_results();

		// get result filtered
		$db_select = <<<EOSQL
			a.NoReservasi,
			a.Tanggal,
			a.Jam,
			a.PasienBaru,
			a.NRM,
			a.Nama,
			a.Alamat,
			d.Phone,
			a.UntukSectionID,
			a.UntukDokterID,
			a.UntukHari,
			a.UntukTanggal,
			a.NoUrut,
			a.Memo,
			b.SectionName,
			c.Nama_Supplier,
			a.Waktu,
			a.Registrasi,
			a.Batal
			
EOSQL;

		$this->db
			->select($db_select)
			->from($db_from)
			->join("{$this->section_m->table} b", "a.UntukSectionID=b.SectionID", "LEFT OUTER")
			->join("{$this->supplier_m->table} c", "a.UntukDokterID=c.Kode_Supplier", "LEFT OUTER")
			->join("{$this->patient_model->table} d", "a.NRM=d.NRM", "LEFT OUTER")
			// ->join("SIMmWaktuPraktek d","a.WaktuId=d.WaktuId","LEFT OUTER")
		;
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_or_where)) {
			$this->db->group_start()->or_where($db_or_where)->group_end();
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
			try {
				$estimasi = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->Waktu);
				if ($estimasi !== false) {
					// if ($row->NoUrut != 1) {
					// 	$add_minute = ($row->NoUrut - 1) * 6;
					// 	$estimasi->modify("+{$add_minute} minutes");
					// }
					$row->estimation_time = $estimasi->format('H:i');
					$output['data'][] = $row;
				} else {
					// Handle invalid datetime format here
					// You might log an error or take appropriate action
				}
			} catch (Exception $e) {
				// Handle any other exceptions that might occur during date manipulation
			}
		}


		$this->template
			->build_json($output);
	}

	public function get_reservation_queue()
	{
		if ($this->input->is_ajax_request()) {
			$params = $this->input->get('f');
			$response = array(
				"status" => "success",
				"message" => "",
				"code" => 200,
				"NoUrut" => reservation_helper::get_reservation_queue($params['UntukSectionID'], $params['UntukDokterID'], $params['UntukTanggal'], $params['Waktu'])
			);
			response_json($response);
		}
	}
}
