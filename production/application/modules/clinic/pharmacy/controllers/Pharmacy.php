<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pharmacy extends Admin_Controller
{
	protected $_translation = 'pharmacy';
	protected $_model = 'pharmacy_m';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('pharmacy');
		$this->simple_login->set_pharmacy();

		//jika berlaku clerk
		if (config_item('use_clerk') == 'TRUE') {
			if (empty($this->session->userdata('KodeClerk'))) {
				redirect('clerk/start');
			}
		}

		$this->page = "pharmacy";
		$this->template->title("Farmasi" . ' - ' . $this->config->item('company_name'));

		$this->load->model("common/patient_m");
		$this->load->model("common/patient_type_m");
		$this->load->model("common/zone_m");
		$this->load->model("common/nationality_m");
		$this->load->model("common/supplier_m");
		$this->load->model("common/supplier_specialist_m");
		$this->load->model("common/section_m");
		$this->load->model("common/time_m");

		$this->load->model("section_model");

		$pharmacy = $this->session->userdata('pharmacy');
		$this->section = $this->section_model->get_by(['SectionID' => $pharmacy['section_id']]);

		$this->load->helper("pharmacy");
		$this->load->helper("common/patient");
		$this->load->helper("common/zone");
		$this->load->helper('drug_payment');
	}

	public function index()
	{
		$data = [
			'page' => $this->page,
			"form" => TRUE,
			'datatables' => TRUE,
		];

		$this->template
			->set("heading", "Farmasi")
			->set_breadcrumb("Farmasi")
			->build('pharmacies/datatable', (isset($data) ? $data : NULL));
	}


	public function selling($NoResep = NULL)
	{
		$item = [
			"KerjasamaID" => 3,
			"Jam" => date('Y-m-d H:i:s'),
			"Tanggal" => date('Y-m-d'),
			"SectionInput" => $this->section->SectionID,
			"SectionID" => $this->section->SectionID,
			"SectionAsalID" => $this->section->SectionID,
			"Shift" => $this->session->userdata('shift_id')
		];

		if ($this->input->post()) {
			$farmasi = array_merge($item, $this->input->post("farmasi"));
			$farmasi["NoBukti"] = pharmacy_helper::gen_evidence_number($this->section->SectionID, $farmasi['Jam']);

			$farmasi_detail = array();
			foreach ($this->input->post("farmasi_detail") as $row) {
				$row["NoBukti"] = $farmasi["NoBukti"];
				$farmasi_detail[] = $row;
			}

			$this->load->library('form_validation');

			$this->form_validation->set_rules($this->get_model()->rules['insert']);
			$this->form_validation->set_data($this->input->post("farmasi"));

			$response = array(
				"status" => "success",
				"message" => "",
				"code" => 200
			);

			if (!$this->form_validation->run()) {

				$this->db->trans_begin();

				$section_from = $this->db->where('SectionID', $farmasi['SectionAsalID'])->get('SIMmSection')->row();

				if (in_array($section_from->PoliKlinik, ['UGD', 'UMUM'])) {
					$farmasi['ObatBebas'] = 0;
				} elseif ($section_from->PoliKlinik == 'SPESIALIS' || $this->section->SectionID == 'SectionAsalID') {
					$farmasi['ObatBebas'] = 0;
				} else {
					$farmasi['ObatBebas'] = 1;
				}

				$this->db->insert("BILLFarmasi", $farmasi);

				$section = $this->pharmacy_m->get_row_data("SIMmSection", array("SectionID" => $farmasi['SectionID']));
				foreach ($farmasi_detail as $k => $v) {
					$this->db->insert("BILLFarmasiDetail", $v);
					if ($v["Barang_ID"] != 0) {
						// Mangambil total stok terakhir yang ada pada kartu gudang
						$qty_last_stock = $this->pharmacy_m->get_last_stock_warehouse_card(array("Lokasi_ID" => $section->Lokasi_ID, "Barang_ID" => $v["Barang_ID"]));
						$qty_saldo = $qty_last_stock - $v["JmlObat"];
						$kartu_gudang = array(
							"Lokasi_ID" => $section->Lokasi_ID,
							"Barang_ID" => $v["Barang_ID"],
							"No_Bukti" => $v["NoBukti"],
							"JTransaksi_ID" => 564,
							"Tgl_Transaksi" => $farmasi["Tanggal"],
							"Kode_Satuan" => $v["Satuan"],
							"Qty_Masuk" => 0,
							"Harga_Masuk" =>  0,
							"Qty_Keluar" => $v["JmlObat"],
							"Harga_Keluar" => $v["Harga"],
							"Qty_Saldo" => $qty_saldo,
							"Harga_Persediaan" => $v["Harga"],
							"Jam" => $farmasi["Jam"],
						);
						$this->db->insert("GD_trKartuGudang", $kartu_gudang);
						$this->db->update("mBarang", array("Tgl_Transaksi_Terakhir" => $farmasi['Tanggal']), array("Barang_ID" => $v['Barang_ID']));
						//$this->db->set('ClosePayment', 1);
						//$this->db->where('NoReg', $farmasi['NoReg']);
						//$this->db->where('ObatBebas', 0);
						//$this->db->update('BillFarmasi');

						$farmasi_pemakaian = array(
							"IDPemakaian" => 1,
							"NoBukti" => $farmasi['NoBukti'],
							"Barang_ID" => $v["Barang_ID"],
							"NoUrut" => ++$k,
							"Jam" => date("Y-m-d H:i:s"),
							"Tanggal" => date("Y-m-d"),
							"JmlObat" => $v["JmlObat"],
							"JmlSdhDipakai" => 0,
							"JmlDipakai" => $v["JmlObat"],
							"UseID" => $this->user_auth->User_ID,
							"NoPemakaian" => $farmasi['NoBukti']
						);

						$this->db->insert("BILLFarmasiPemakaian", $farmasi_pemakaian);
					}
				}

				// Insert User Aktivities
				$activities_description = sprintf("%s # %s # %s", "INSERT BILLING OBAT BEBAS FARMASI", $farmasi['NoBukti'], $section->SectionName);
				$this->db->query("EXEC InsertUserActivities '" . $farmasi['Tanggal'] . "','" . $farmasi['Jam'] . "'," . $this->user_auth->User_ID . ",'" . $farmasi['NoBukti'] . "','" . $activities_description . "','BILLFarmasi'");


				// Jika Merupakan Resep Dokter, Maka Update data Realisasi Resep Menjadi 1
				if (!empty($farmasi['NoResep'])) {
					$this->db->update("SIMtrResep", array("Realisasi" => 1), array("NoResep" => $farmasi['NoResep']));
				}

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$response = array(
						"status" => 'error',
						"message" => lang('global:created_failed'),
						"code" => 500
					);
				} else {
					//$this->db->trans_rollback();
					$this->db->trans_commit();
					$response = array(
						"NoBukti" => $farmasi['NoBukti'],
						"status" => 'success',
						"message" => lang('global:created_successfully'),
						"code" => 200
					);
				}
			} else {
				$response = array(
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				);
			}

			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit(0);
		}

		#JIKA RESEP DARI SECTION LAIN
		if ($NoResep) {
			$item = array_merge($item, (array) $this->pharmacy_m->get_prescription_data($NoResep));
		}
		$item["NoBukti"] = pharmacy_helper::gen_evidence_number($this->section->SectionID);


		$option_patient_type = $this->pharmacy_m->get_option_patient_type();
		$option_section = $this->pharmacy_m->get_options("SIMmSection", array("TipePelayanan" => "RJ", "StatusAktif" => 1), array("by" => "SectionName", "sort" => "ASC"));


		$data = [
			"page" => $this->page . "_" . strtolower(__FUNCTION__),
			"item" => (object) @$item,
			"user" => $this->user_auth,
			"section" => $this->section,
			"option_patient_type" => @$option_patient_type,
			"option_section" => $option_section,
			"option_dosis" => $this->pharmacy_m->get_options("SIMmDosisObat", array(), array("by" => "KodeDosis", "sort" => "ASC")),
			"lookup_supplier" => base_url("pharmacy/lookup_supplier"),
			"form" => TRUE,
			"datatables" => TRUE,
			"typeahead" => TRUE,
			"lookup_supplier" => base_url("pharmacy/lookup_supplier"),
			"lookup_examination" => base_url("pharmacy/lookup_examination"),
			"lookup_cooperation" => base_url("pharmacy/lookup_cooperation"),
			"lookup_prescription" => base_url("pharmacy/lookup_prescription"),
			"lookup_products" => base_url("pharmacy/pharmacies/details/lookup_product"),
			"lookup_patient" => base_url("pharmacy/lookup_patient"),
		];

		$this->template
			->set("heading", "Penjualan Obat Bebas")
			->set_breadcrumb("Farmasi", base_url("pharmacy"))
			->set_breadcrumb("Penjualan Obat Bebas")
			->build('pharmacies/form_selling', $data);
	}

	public function selling_view($NoBukti = NULL)
	{

		$item = $this->pharmacy_m->get_pharmacy_data(["NoBukti" => $NoBukti]);
		$cooperation = $this->db->where('Kode_Customer', $item->KodePerusahaan)->get('mCustomer')->row();
		$option_patient_type = $this->pharmacy_m->get_option_patient_type();

		if ($this->input->is_ajax_request()) {
			$data = array(
				'item' => (object)$item,
				"patient" => @$patient,
				"cooperation" => @$cooperation,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
			);

			$this->load->view(
				'pharmacy/modal/create_edit',
				array('form_child' => $this->load->view('pharmacy/form', $data, true))
			);
		} else {
			$data = array(
				"page" => $this->page . "_" . strtolower(__FUNCTION__),
				"item" => @$item,
				"user" => $this->simple_login->get_user(),
				"pharmacy" => @$pharmacy,
				"cooperation" => $cooperation,
				"option_patient_type" => @$option_patient_type,
				"form" => TRUE,
				"datatables" => TRUE,
				"return_link" => base_url("pharmacy/selling-return/{$item->NoBukti}"),
				"create_link" => base_url("pharmacy/selling"),
				"pay_link" => base_url("pharmacy/drug-payment/pay/{$item->NoBukti}"),
				"print_billing_link" => base_url("pharmacy/print_billing/"),
			);

			$this->template
				->set("heading", "Lihat Penjualan Obat Bebas")
				->set_breadcrumb("Farmasi", base_url("pharmacy"))
				->set_breadcrumb("Lihat Penjualan Obat Bebas")
				->build('pharmacies/form_selling_view', $data);
		}
	}

	public function selling_return($NoBukti = null)
	{

		$item = $this->pharmacy_m->get_pharmacy_data(array("NoBukti" => $NoBukti));

		if ($this->input->post()) {

			$data = $this->input->post();
			$confirm = $this->input->post("confirm");

			$this->load->library('form_validation');

			$response = array(
				"response_status" => "success",
				"message" => "",
				"code" => 200
			);

			if ($item->ClosePayment == 1) {
				$response = array(
					"response_status" => 'error',
					"message" => "Data ini sudah melakukan Pembayaran diKasir!",
					"code" => 200
				);

				make_flashdata($response);
				redirect("pharmacy/selling-view/{$item->NoBukti}");
			}

			if ($confirm == @$item->NoBukti) {

				$this->db->trans_begin();

				$this->db->update("BILLFarmasi", array("Retur" => 1), array("NoBukti" => @$item->NoBukti));

				// Ambil data detail farmasi dari KartuGudang, kemudian sesuaikan data yg akan diretur
				$farmasi_gudang = $this->pharmacy_m->get_result_data("GD_trKartuGudang", array("No_Bukti" => @$item->NoBukti));

				/*print_r( $farmasi_detail );
					exit;*/

				foreach ($farmasi_gudang as $v) {
					// Ambil stok terakhir yang ada di kartu gudang,
					$qty_last_stock = $this->pharmacy_m->get_last_stock_warehouse_card(array("Lokasi_ID" => $v->Lokasi_ID, "Barang_ID" => $v->Barang_ID));
					$qty_saldo = $qty_last_stock + $v->Qty_Keluar;

					if (($qty_last_stock + $v->Qty_Keluar) > 0) {
						$HPP = (($v->Harga_Keluar * $qty_last_stock) + ($v->Qty_Keluar *  $v->Harga_Keluar)) / $qty_last_stock + $v->Qty_Keluar;
					} else {
						$HPP = ($v->Harga_Keluar * $qty_last_stock) + ($v->Qty_Keluar *  $v->Harga_Keluar);
					}

					$kartu_gudang = array(
						"Lokasi_ID" => $v->Lokasi_ID,
						"Barang_ID" => $v->Barang_ID,
						"No_Bukti" => $v->No_Bukti . "-R",
						"JTransaksi_ID" => 562,
						"Tgl_Transaksi" => date("Y-m-d"),
						"Kode_Satuan" => $v->Kode_Satuan,
						"Qty_Masuk" => $v->Qty_Keluar,
						"Harga_Masuk" => $v->Harga_Keluar,
						"Qty_Keluar" => 0,
						"Harga_Keluar" => 0,
						"Qty_Saldo" => $qty_saldo,
						"Harga_Persediaan" => $HPP,
						"Jam" => date("Y-m-d H:i:s"),
					);

					$this->db->insert("GD_trKartuGudang", $kartu_gudang);
				}

				$farmasi_detail = $this->pharmacy_m->get_farmasi_detail(@$item->NoBukti);

				foreach ($farmasi_detail as $row) {

					if (@$row->Satuan == "RESEP" || @$row->Barang_ID == 0) {
						continue;
					}

					$this->db->update("BILLFarmasiDetail", array("JmlRetur" => $row->JmlObat, "NoRetur" => $item->NoBukti . "-R"), $row);
				}

				// Update SIMtrResep Realisasi menjadi 0
				$this->db->update("SIMtrResep", array("Realisasi" => 0), array("NoResep" => $item->NoResep));


				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$response = array(
						"response_status" => 'error',
						"message" => lang('global:created_failed'),
						"code" => 500
					);
				} else {
					$this->db->trans_commit();
					$response = array(
						"NoBukti" => $farmasi['NoBukti'],
						"response_status" => 'success',
						"message" => "Retur Transaksi Berhasil!",
						"code" => 200
					);

					make_flashdata($response);

					redirect("pharmacy/selling");
				}
			} else {
				$response = array(
					"response_status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				);
			}

			make_flashdata($response);
			redirect("pharmacy/selling-view/{$item->NoBukti}");
		}

		if ($this->input->is_ajax_request()) {
			$data = array(
				"item" => $item,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
			);

			$this->load->view('pharmacies/modal/return', $data);
		}
	}

	public function edit($NoReg = 0)
	{

		$item = $this->db->where("NoReg", $NoReg)->get($this->pharmacy_m->table)->row_array();
		$patient = $this->pharmacy_m->get_patient($item['NRM']);
		$section_destination = $this->pharmacy_m->get_section_destination($item['NoReg']);
		$cooperation = $this->pharmacy_m->get_customer(array("Kode_Customer" => $item['KodePerusahaan'])); // Perusahaan Kerja sama
		$second_insurer = $this->pharmacy_m->get_customer(array("Kode_Customer" => $item['PertanggunganKeduaCompanyID'])); // Pertanggungan Kedua (IKS)

		if ($this->input->post()) {


			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->get_model()->rules['insert']);
			$this->form_validation->set_data($this->input->post("f"));

			if ($this->form_validation->run()) {
				if ($this->get_model()->update($this->item->toArray(), @$id)) {
					$this->get_model()->delete_cache('common_pharmacys.collection');

					make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:updated_successfully')
					));

					redirect('common/pharmacys');
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

		$option_patient_type = $this->pharmacy_m->get_option_patient_type();
		$option_dosis = $this->pharmacy_m->get_options("SIMmDosisObat", array(), array("by" => "KodeDosis", "sort" => "ASC"));

		if ($this->input->is_ajax_request()) {
			$data = array(
				'item' => $item,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
			);

			$this->load->view(
				'pharmacy/modal/create_edit',
				array('form_child' => $this->load->view('pharmacy/form', $data, true))
			);
		} else {
			$data = array(
				"page" => $this->page . "_" . strtolower(__FUNCTION__),
				"item" => (object)$item,
				"patient" => $patient,
				"option_dosis" => $option_dosis,
				"lookup_supplier" => base_url("pharmacy/lookup_supplier"),
				"lookup_cooperation" => base_url("pharmacy/lookup_cooperation"),
				"form" => TRUE,
				"datatables" => TRUE,
				"is_edit" => TRUE,
			);

			$this->template
				->set("heading", lang("pharmacy:edit_heading"))
				->set_breadcrumb(lang("pharmacy:breadcrumb"), base_url("pharmacys"))
				->set_breadcrumb(lang("pharmacy:edit_heading"))
				->build('pharmacies/form', $data);
		}
	}

	public function lookup($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('pharmacies/lookup/datatable');
		} else {
			$data = array(
				'page' => $this->page,
				'datatables' => TRUE,
				'form' => TRUE,
			);

			$this->template
				->set("heading", "Lookup Box")
				->set_breadcrumb(lang("common:page"), base_url("pharmacys"))
				->set_breadcrumb("Lookup Box")
				->build('pharmacies/lookup', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_supplier($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('pharmacies/lookup/suppliers', array("type" => "doctor"));
		}
	}

	public function lookup_examination($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('pharmacies/lookup/examinations');
		}
	}

	public function lookup_prescription($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('pharmacies/lookup/prescriptions');
		}
	}

	// Cooperation == Perusahaan yang diajak kerja sama (BPJS, IKS)
	public function lookup_cooperation($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('pharmacies/lookup/cooperations', array());
		}
	}

	public function lookup_collection()
	{
		$this->datatable_collection();
	}

	public function datatable_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_from = "BILLFarmasi a";
		$this->load->model("registration_m");
		$db_where = array();
		$db_like = array();

		//$db_where['a.ClosePayment'] = 1;

		if ($this->input->post("date_from")) {
			$db_where['a.tanggal >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till")) {
			$db_where['a.tanggal <='] = $this->input->post("date_till");
		}

		if ($this->input->post("NRM") && strpos($this->input->post("NRM"), "_") === FALSE) {
			$db_like['b.NRM'] = $this->input->post("NRM");
		}

		if ($this->input->post("Nama")) {
			$db_like['b.NamaPasien_Reg'] = $this->input->post("Nama");
		}

		if ($this->input->post("DokterID")) {
			$db_where['a.DokterID'] = $this->input->post("DokterID");
		}

		if ($this->input->post('is_retur')) {
			$db_where['a.ClosePayment'] = 0;
			$db_where['a.Retur'] = 0;
			$db_where['a.Batal'] = 0;
			$db_where['a.SectionID'] = $this->section->SectionID;
			//$db_where['b.StatusBayar'] = 'Belum';
		}

		// preparing default
		if (isset($search['value']) && !empty($search['value'])) {
			$keywords = $this->db->escape_str($search['value']);

			$db_like[$this->db->escape_str("a.NoBukti")] = $keywords;
			$db_like[$this->db->escape_str("a.Tanggal")] = $keywords;
			$db_like[$this->db->escape_str("a.Jam")] = $keywords;
			$db_like[$this->db->escape_str("a.DokterID")] = $keywords;
			$db_like[$this->db->escape_str("a.Keterangan")] = $keywords;
			$db_like[$this->db->escape_str("c.NamaPasien")] = $keywords;
			$db_like[$this->db->escape_str("e.Nama_Supplier")] = $keywords;
		}

		// get total records
		$this->db->from($db_from)
			->join("{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER")
			->join("{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER")
			->join("{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER")
			->join("{$this->supplier_m->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER")
			->join("{$this->section_m->table} f", "a.SectionID = f.SectionID", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		$records_total = $this->db->count_all_results();

		// get total filtered
		$this->db
			->from($db_from)
			->join("{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER")
			->join("{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER")
			->join("{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER")
			->join("{$this->supplier_m->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER")
			->join("{$this->section_m->table} f", "a.SectionID = f.SectionID", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}
		$records_filtered = $this->db->count_all_results();

		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti,
			c.NRM,
			a.Jam,
			a.Tanggal,
			c.NamaPasien,
			d.JenisKerjasama,
			e.Nama_Supplier,
			f.SectionName,
			a.ObatBebas,
			a.NoReg,
			a.Keterangan
			
EOSQL;

		$this->db
			->select($db_select)
			->from($db_from)
			->join("{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER")
			->join("{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER")
			->join("{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER")
			->join("{$this->supplier_m->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER")
			->join("{$this->section_m->table} f", "a.SectionID = f.SectionID", "LEFT OUTER");

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

	public function get_pharmacy_detail()
	{
		if ($this->input->get()) {
			$NoBukti = $this->input->get('NoBukti');
			$collection = $this->pharmacy_m->get_farmasi_detail($NoBukti);
			response_json($collection);
		}
	}

	public function print_report($nrm, $dob, $stat = false)
	{
		if ($stat) {

			str_replace('%20', '', $dob);
			$data = array(
				"nrm" => $nrm,
				"dob" => date("Y-m-d", strtotime($dob)),
			);

			//print_r($data);exit;
			$html_content =  $this->load->view("pharmacy/print", $data, 'Label ');

			$file_name = 'Print  Label';
			$this->load->helper("report");

			report_helper::generate_pdf($html_content, $file_name, date("Y-M-d"), $margin_bottom = 1.0, $header = NULL, $margin_top = 0.3, $orientation = 'P');



			exit(0);
		}
	}

	// Print Billing
	public function print_billing()
	{
		if ($this->input->post()) {
			$data = $this->input->post();

			$item = drug_payment_helper::get_billing($data['NoBukti']);
			$detail = drug_payment_helper::get_billing_detail($data['NoBukti']);
			//print_r($item);exit;
			$type_payment_used = drug_payment_helper::get_type_payment_used($data['NoBukti']);
			//print_r($type_payment_used);exit;
			$collection = [];
			$sub_total = [];
			$grand_total =  0;
			foreach ($detail as $row) {
				if ($row->Qty <= $row->JmlRetur) continue;

				$row->SubTotal = $row->Qty * $row->Harga - ($row->Qty * $row->Harga * $row->Disc / 100) + $row->HExt + $row->BiayaResep;

				if (empty($sub_total[$row->NamaResepObat])) {
					$sub_total[$row->NamaResepObat] = $row->SubTotal;
				} else {
					$sub_total[$row->NamaResepObat] = $sub_total[$row->NamaResepObat] + $row->SubTotal;
				}

				$grand_total = $grand_total + $row->SubTotal;


				if (empty($collection[$row->NamaResepObat]) && $row->Nama_Barang == $row->NamaResepObat) {
					$collection[$row->NamaResepObat] = $row;
				} elseif (!empty($collection[$row->NamaResepObat]) && $row->Nama_Barang == $row->NamaResepObat) {
					$collection[$row->NamaResepObat]->Qty += $row->Qty;
					$collection[$row->NamaResepObat]->HExt += $row->HExt;
					$collection[$row->NamaResepObat]->BiayaResep += $row->BiayaResep;
					$collection[$row->NamaResepObat]->SubTotal += $row->SubTotal;
				} else {
					$collection[] = $row;
				}
			}

			//TERBILANG
			$money_to_format = drug_payment_helper::money_to_text($grand_total);
			//print_r($money_to_format);exit;
			$money_to_format_english = drug_payment_helper::money_to_text_english($grand_total);
			//print_r($money_to_format_english);exit;

			$data = array(
				"item" => $item,
				"collection" => $collection,
				"sub_total" => $sub_total,
				"grand_total" => $grand_total,
				"detail_money_to_text" => $money_to_format,
				"detail_money_to_text_english" => $money_to_format_english,
				"type_payment_used" => $type_payment_used,
				"user" => $this->user_auth,
				"section" => $this->section,
			);

			//print_r($type_payment_used);exit;

			// PDF Content
			$html_content = $this->load->view("drug_payment/print/billing", $data, TRUE);
			//print_r($html_content);exit;
			$file_name = "billing-OB.pdf";

			// print_r($html_content);exit;
			$this->load->helper("export");

			$data_print = chunk_split(base64_encode(export_helper::print_pdf_string($html_content, $file_name, $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P', $margin_left = 6, $margin_right = 5)));

			$message =  [
				"data_print" => $data_print,
				"status" => 'success',
				"message" => lang('global:updated_successfully'),
				"code" => 200
			];
			response_json($message);
		}
	}

	public function lookup_patient($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			if ($this->input->get_post("is_modal")) {
				$data["is_modal"] = TRUE;
			}

			$this->load->view('pharmacies/lookup/patients', (isset($data) ? $data : NULL));
		}
	}
}
