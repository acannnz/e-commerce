<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;


class Queue extends Admin_Controller
{
	protected $_translation = 'registrations';
	protected $_model = 'registration_model';
	public function __construct()
	{
		parent::__construct();

		$this->page = "common_registrations";
		$this->template->title(lang("registrations:page") . ' - ' . $this->config->item('company_name'));

		$this->load->model("registration_model");
		$this->load->model("patient_model");

		$this->load->helper("registration");
		$this->load->helper("common/patient");

		$this->load->config('registrations');

		$this->load->helper("poly/poly");
	}


	public function index()
	{

		$queue_active = $this->db->where(["Tanggal" => date('Y-m-d'), "status_antrian" => 1])
			->order_by("NoAntrian", "DESC")
			->get('SIMtrAntrian')->row();

		$data = array(
			'page' => $this->page,
			'form' => TRUE,
			'datatables' => TRUE,
			"queue_active" => empty($queue_active) ? 0 : intval(@$queue_active->NoAntrian),
			"queue_calling_url" => base_url("queue/queue_calling"),
			"queue_skip_url" => base_url("queue/queue_skip"),
		);

		$this->template
			->set("heading", "Antrian")
			->set_breadcrumb("Antrian")
			->build('queue/datatable', (isset($data) ? $data : NULL));
	}

	public function queue_active()
	{

		$data = array(
			'page' => $this->page,
			'form' => TRUE,
			'datatables' => TRUE,
		);

		$this->load->view('queue/queue_active', $data);
	}

	public function queue_processed()
	{
		$data = array(
			'page' => $this->page,
			'form' => TRUE,
			'datatables' => TRUE,
		);

		$this->load->view('queue/queue_processed', $data);
	}
	// insert antrian FIFO
	public function queue_insert_fifo()
	{
		if ($this->input->post()) {
			$registration = $this->input->post("patient");
			$item = poly_helper::get_outpatient($registration['NoReg'], $registration['SectionID']);
			$antrian = [
				'NoReg' => $item->NoReg,
				'Tanggal' => date('Y-m-d'),
				'SectionID' => $item->SectionID,
				'DokterID' => $item->DokterID,
				'Jam' => date('Y-m-d h:i:s'),
				'StatusAntrian' => 0,
			];

			$this->db->trans_begin();

			$this->db->insert("SIMtrAntrianFIFO", $antrian );
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$message = [
					"status" => 'error',
					"message" => 'Terjadi Kesalahan, silahkan hubungi IT Support',
					"code" => 500
				];
			}
			
			$this->db->trans_commit();
			$message = [
				"status" => 'success',
				"message" => 'Berhasil memanggil antrean!',
				"code" => 200
			];

			response_json($message);
		}
		// $queue_active = $this->db->where(["Tanggal" => date('Y-m-d'), "status_antrian" => 1])
		// 	->order_by("NoAntrian", "DESC")
		// 	->get('SIMtrAntrian')->row();

		// $queue_left = $this->db->select("COUNT(NoReg) as queue_left")->where(["Tanggal" => date('Y-m-d'), "status_antrian" => 0])
		// 	->get('SIMtrAntrian')->row();

		// $data = array(
		// 	"page" => $this->page,
		// 	"form" => TRUE,
		// 	"datatables" => TRUE,
		// 	"option_doctor" => option_doctor(),
		// 	"queue_active" => empty($queue_active) ? 0 : intval(@$queue_active->NoAntrian),
		// 	"queue_left" => empty($queue_left) ? 0 : intval(@$queue_left->queue_left),
		// 	"queue_calling_url" => base_url("queue/queue_calling"),
		// 	"refresh_queue_url" => base_url("queue/refresh_queue"),
		// );

		// // $data['queue_left'] = intval($data['queue_left']) < 0 ? 0 : $data['queue_left'];

		// $this->load->view('queue/queue', $data);
	}
	// tampilan antrian
	public function queue_view()
	{
		$option_section = $this->db
						->where(array("StatusAktif" => 1))
						->where_in("TipePelayanan", array("RJ", "PENUNJANG"))
						->where_in("PoliKlinik", array("UMUM", "SPESIALIS","NONE"))
						->order_by("SectionID")
						->get("SIMmSection")->result();

		if (12 % count($option_section) == 0) {
			$column_width = 12 / count($option_section);
		} else {
			for ($i = 1; $i > 0; $i++) {
				$total_section = $i + count($option_section);

				if (12 % $total_section == 0) {
					$column_width = 12 / $total_section;
					break;
				}
			}
		}
		
		$this->load->model("registration_model");
		foreach ($option_section as $key => $val) {
			// $queue = $this->db->where("StatusPeriksa IS NOT NULL")->where(['SectionID' => $val->SectionID, 'Tanggal' => date("Y-m-d 00:00:00.000")])->order_by('NoAntrian', 'desc')->get('SIMtrAntrian')->row();
			$queue = $this->db
					->from("SIMtrDataRegPasien a")
					->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
					->where([
						'a.Tanggal >=' => date("Y-m-d 00:00:00.000"),
						'a.Tanggal <=' => date("Y-m-d 00:00:00.000"),
						'a.RJ' => 1,
						'a.SudahPeriksa' => 0,
						'a.Batal' => 0,
						'a.SectionID' => $val->SectionID
					])->count_all_results();

			if (empty($queue)) {
				$val->queue = 0;
			} else {
				$val->queue = $queue;
			}
		}

		// $queue_active = $this->db->where(["Tanggal" => date('Y-m-d'), "status_antrian" => 1])
		// 				->order_by("NoAntrian", "DESC")
		// 				->get('SIMtrAntrian')->row();

		// $queue_left = $this->db->select("COUNT(NoReg) as queue_left")->where(["Tanggal" => date('Y-m-d'), "status_antrian" => 0])
		// 				->get('SIMtrAntrian')->row();

		$data = array(
			"page" => $this->page,
			"form" => TRUE,
			"datatables" => TRUE,
			"option_doctor" => option_doctor(),
			// "queue_active" => empty($queue_active) ? 0 : intval(@$queue_active->NoAntrian),
			// "queue_left" => empty($queue_left) ? 0 : intval(@$queue_left->queue_left),
			"queue_calling_url" => base_url("queue/queue_calling"),
			"refresh_queue_url" => base_url("queue/refresh_queue"),
			"option_section" => $option_section,
			"column_width" => $column_width,
		);

		$this->load->view('queue/queue', $data);
	}
	//pemangilan antrian dengan nama pasien
	public function queue_sscalling_new()
	{
			$queue_active 	= (object) $this->input->post("patient");
			$item 			= poly_helper::get_outpatient($queue_active->NoReg, $queue_active->SectionID);
			//JIKA POLI KIA KB
			$sectionName = ($item->SectionID == 'SECT0017') ? 'POLI KIA-K B' : $item->SectionName;

			$queue_description		= strtolower($item->NamaPasien. '. Silahkan Menuju .' .$sectionName);
			$queue_description		= htmlspecialchars($queue_description);
			$queue_description		= rawurlencode($queue_description);
			$file_headers = @get_headers("https://translate.google.com");
			
			if($file_headers) {  //JIKA DAPAT MENGAMBIL DATA DARI GOOGLE TRANSLATE
				$html = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q='.$queue_description.'&tl=ID');
				$connected = true;
			}
			else {  //JIKA TIDAK DAPAT MENGAMBIL DATA DARI GOOGLE TRANSLATE
				if($item->SectionID == 'SECT0017'){ //POLI KIA
					$mp3 = "queue_default_kia.mp3";
				}
				elseif($item->SectionID == 'SEC010'){ //POLI GIGI
					$mp3 = "queue_default_gigi.mp3";
				}
				elseif($item->SectionID == 'SEC007'){ //POLI UMUM
				}
				else{
					$mp3 = "queue_default_umum.mp3";
					// $mp3 = "queue_default_laboratorium.mp3";
				}
				$html = file_get_contents(realpath(FCPATH . "../../resource/sound/{$mp3}"));
				$connected = false;
			}

			$player	= "<audio id='player' controls='controls' autoplay style='display:none'><source src='data:audio/mpeg;base64,".base64_encode(@$html)."'></audio>";
	
			if(!empty($item )){
				$message = [
					"status" 	=> "success",
					"message" 	=> "Antrean Terpanggil",
					"html" 		=> @$player,
					"data" 		=> $item,
					"connected" => $connected
				];
	
			}else{
				$message = [
					"status" => "error",
					"message" => "Data Pasien tidak tersedia",
					"html" => '',
					"data" => [],
					"connected" => $connected
				];
			}

			response_json($message);

	}
	//pemangilan antrian
	public function queue_calling()
	{
		if ($this->input->is_ajax_request()) {

			$this->db->trans_begin();

			$queue_active = $this->db->where(["Tanggal" => date('Y-m-d'), "status_antrian" => 0])
				->order_by("NoAntrian", "ASC")
				->get('SIMtrAntrian')->row();

			$queue_left = $this->db->select("COUNT(NoReg) as queue_left")->where(["Tanggal" => date('Y-m-d'), "status_antrian" => 0])
				->get('SIMtrAntrian')->row();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				// $this->db->trans_rollback();
				$this->db->trans_commit();

				$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200,
					"queue" => empty($queue_active) ? 0 : intval(@$queue_active->NoAntrian),
					"queue_left" => empty($queue_left) ? 0 : intval(@$queue_left->queue_left) - 1,
				);

				$response['queue_left'] = intval($response['queue_left']) < 0 ? 0 : $response['queue_left'];

				$response['number_spell'] = explode('#', str_replace(' ', '', $this->number_spell($response['queue'])));
				unset($response['number_spell'][count($response['number_spell']) - 1]);
			}

			response_json($response);
		}
	}

	//skip antrian saat ini
	public function queue_skip()
	{
		if ($this->input->is_ajax_request()) {

			$this->db->trans_begin();


			$queue_active = $this->db->where(["Tanggal" => date('Y-m-d'), "status_antrian" => 0])
				->order_by("NoAntrian", "ASC")
				->get('SIMtrAntrian')->row();

			$this->db->where(['NoReg' => $queue_active->NoReg, 'NoAntrian' => $queue_active->NoAntrian])->update('SIMtrAntrian', ['status_antrian' => 2]);

			$queue_active = $this->db->where(["Tanggal" => date('Y-m-d'), "status_antrian" => 0])
				->order_by("NoAntrian", "ASC")
				->get('SIMtrAntrian')->row();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				// $this->db->trans_rollback();
				$this->db->trans_commit();

				$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200,
					"queue" => empty($queue_active) ? 0 : intval(@$queue_active->NoAntrian),
				);
			}

			response_json($response);
		}
	}

	// refresh antrian terbaru saat ada trigger
	public function refresh_queue()
	{
		if ($this->input->is_ajax_request()) {

			$option_section = $this->db
			->where(array("StatusAktif" => 1))
			->where_in("TipePelayanan", array("RJ", "PENUNJANG"))
			->where_in("PoliKlinik", array("UMUM", "SPESIALIS", "UGD","NONE"))
			->order_by("SectionID")
			->get("SIMmSection")->result();

			if (12 % count($option_section) == 0) {
				$column_width = 12 / count($option_section);
				} else {
				for ($i = 1; $i > 0; $i++) {
					$total_section = $i + count($option_section);

					if (12 % $total_section == 0) {
						$column_width = 12 / $total_section;
						break;
					}
				}
			}

			$this->load->model("registration_model");
			foreach ($option_section as $key => $val) {
			// $queue = $this->db->where("StatusPeriksa IS NOT NULL")->where(['SectionID' => $val->SectionID, 'Tanggal' => date("Y-m-d 00:00:00.000")])->order_by('NoAntrian', 'desc')->get('SIMtrAntrian')->row();
			$queue = $this->db
					->from("SIMtrDataRegPasien a")
					->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
					->where([
						'a.Tanggal >=' => date("Y-m-d 00:00:00.000"),
						'a.Tanggal <=' => date("Y-m-d 00:00:00.000"),
						'a.RJ' => 1,
						'a.SudahPeriksa' => 0,
						'a.Batal' => 0,
						'a.SectionID' => $val->SectionID
					])->count_all_results();

			if (empty($queue)) {
				$val->queue = 0;
			} else {
				$val->queue = $queue;
				}
			}

			$response = [
				"data" => $option_section,
				"status" => "success",
				"message" => "",
				"code" => 200,
			];

			response_json($response);
		}
	}
	// public function refresh_queue()
	// {
	// 	if ($this->input->is_ajax_request()) {

	// 		$queue_active = $this->db->where(["Tanggal" => date('Y-m-d'), "status_antrian" => 1])
	// 			->order_by("NoAntrian", "DESC")
	// 			->get('SIMtrAntrian')->row();

	// 		$queue_left = $this->db->select("COUNT(NoReg) as queue_left")->where(["Tanggal" => date('Y-m-d'), "status_antrian" => 0])
	// 			->get('SIMtrAntrian')->row();

	// 		$response = [
	// 			"status" => "success",
	// 			"message" => "",
	// 			"code" => 200,
	// 			"queue_active" => empty($queue_active) ? 0 : intval(@$queue_active->NoAntrian),
	// 			"queue_left" => empty($queue_left) ? 0 : intval(@$queue_left->queue_left),
	// 		];

	// 		$response['queue_left'] = intval($response['queue_left']) < 0 ? 0 : $response['queue_left'];

	// 		response_json($response);
	// 	}
	// }

	//get antrian saat registrasi
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

	public function datatable_queue_collection($active = 0)
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

		$db_where['a.TglReg ='] = date("Y-m-d");

		if ($this->input->post('status_antrian') == 'active') {
			$db_where['c.status_antrian'] = 0;
		} else {
			$db_where['c.status_antrian<>'] = 0;
		}

		// preparing default
		if (isset($search['value']) && !empty($search['value'])) {
			$keywords = $this->db->escape_str($search['value']);
		}

		//get total records
		$this->db->from($db_from)
			->join("SIMtrAntrian c", "a.NoReg = c.NoReg", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		$records_total = $this->db->count_all_results();

		// get total filtered
		$this->db
			->from($db_from)
			->join("{$this->patient_model->table} b", "a.NRM = b.NRM", "LEFT OUTER")
			->join("SIMtrAntrian c", "a.NoReg = c.NoReg", "LEFT OUTER");

		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
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
			c.NoAntrian,
			c.status_antrian,
EOSQL;


		$this->db
			->select($db_select)
			->from($db_from)
			->join("{$this->patient_model->table} b", "a.NRM = b.NRM", "LEFT OUTER")
			->join("SIMtrAntrian c", "a.NoReg = c.NoReg", "LEFT OUTER");

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
					->order_by($columns[intval($this->db->escape_str($sort_column))]['name'], $this->db->escape_str($sort_dir));
			}
		}

		$this->db->order_by("c.NoAntrian", "ASC");
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
			$output['data'][] = $row;
		}

		$this->template
			->build_json($output);
	}

	// generate terbilang angka, digunakan untuk memanggil antrian
	public function number_spell($nilai = 0)
	{
		$nilai = abs($nilai);
		$huruf = array("", "satu#", "dua#", "tiga#", "empat#", "lima#", "enam#", "tujuh#", "delapan#", "sembilan#", "sepuluh#", "sebelas#");
		$temp = "";
		if ($nilai < 12) {
			$temp = " " . $huruf[$nilai];
		} else if ($nilai < 20) {
			$temp = $this->number_spell($nilai - 10) . " belas#";
		} else if ($nilai < 100) {
			$temp = $this->number_spell($nilai / 10) . " puluh#" . $this->number_spell($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus#" . $this->number_spell($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = $this->number_spell($nilai / 100) . " ratus#" . $this->number_spell($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu#" . $this->number_spell($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = $this->number_spell($nilai / 1000) . " ribu#" . $this->number_spell($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = $this->number_spell($nilai / 1000000) . " juta#" . $this->number_spell($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = $this->number_spell($nilai / 1000000000) . " milyar#" . $this->number_spell(fmod($nilai, 1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = $this->number_spell($nilai / 1000000000000) . " trilyun#" . $this->number_spell(fmod($nilai, 1000000000000));
		}
		return $temp;
	}

	//antrian poli
	public function queue_poli()
	{
		$option_section = $this->db
			->where(array("StatusAktif" => 1))
			->where_in("TipePelayanan", array("RJ", "PENUNJANG"))
			->where_in("PoliKlinik", array("UMUM", "SPESIALIS", 'UGD'))
			->order_by("SectionID")
			->get("SIMmSection")->result();

		if (12 % count($option_section) == 0) {
			$column_width = 12 / count($option_section);
		} else {
			for ($i = 1; $i > 0; $i++) {
				$total_section = $i + count($option_section);

				if (12 % $total_section == 0) {
					$column_width = 12 / $total_section;
					break;
				}
			}
		}

		foreach ($option_section as $key => $val) {
			$queue = $this->db->where("StatusPeriksa IS NOT NULL")->where(['SectionID' => $val->SectionID, 'Tanggal' => date("Y-m-d 00:00:00.000")])->order_by('NoAntrian', 'desc')->get('SIMtrAntrian')->row();
			if (empty($queue)) {
				$val->queue = "-";
			} else {
				$val->queue = $queue->NoAntrian;
			}
		}

		$queue_active = $this->db->where(["a.Tanggal" => date('Y-m-d'), "a.StatusPeriksa" => "Proses"])
			->join("SIMmSection b", "a.SectionID=b.SectionID", "INNER")
			->get('SIMtrAntrian a')->row();

		$data = array(
			"page" => $this->page,
			"form" => TRUE,
			"datatables" => TRUE,
			"option_doctor" => option_doctor(),
			"option_section" => $option_section,
			"column_width" => $column_width,
			"queue_active" => $queue_active,
			"queue_calling_url" => base_url("queue/queue_calling"),
		);

		$this->load->view('queue/queue_poli', $data);
	}
}
