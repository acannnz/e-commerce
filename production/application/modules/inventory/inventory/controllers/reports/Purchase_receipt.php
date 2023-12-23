<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_receipt extends Admin_Controller
{
	protected $nameroutes = 'inventory/reports/purchase_receipt';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');

		$this->load->language('reports');
		$this->load->model('section_model');
		$this->load->model('item_typegroup_model');

		$this->page = 'Penerimaan Pembelian';
		$this->template->title($this->page);
	}

	public function index()
	{
		if ($this->input->is_ajax_request()) {
			echo "<script language=\"javascript\">window.location=\"" . base_url("{$this->nameroutes}") . "\";</script>";
			exit();
		} else {
			redirect("{$this->nameroutes}/dialog");
		}
	}


	public function dialog($is_ajax = FALSE)
	{
		$option_section = $this->section_model->get_all(NULL, 0, ['KelompokSection' => 11, 'StatusAktif' => 1]);
		$option_kelompok_jenis = $this->item_typegroup_model->dropdown_data(['Kelompok' => 'OBAT']);
		// print_r($option_kelompok_jenis);
		// exit;
		$data = [
			"lookup_supplier" => base_url("{$this->nameroutes}/lookup_supplier"),
			"option_section" => $option_section,
			"option_kelompok_jenis" => $option_kelompok_jenis,
			"nameroutes" => $this->nameroutes,
			"datatables" => true,
			"datepicker" => true,
			"form" => true,
			"option_payment_type" => [
				'' => 'Semua',
				1 => 'Kredit',
				2 => 'Tunai',
				3 => 'Konsinyasi',
			]
		];

		if ($this->input->is_ajax_request() || $is_ajax) {
			$this->load->view(
				"reports/purchase_receipt/modal/dialog",
				array("form_child" => $this->load->view("reports/purchase_receipt/dialog", $data, true))
			);
		} else {
			$this->template
				->set("heading", $this->page)
				->set_breadcrumb('Laporan')
				->set_breadcrumb('Laporan Penerimaan Pembelian', base_url("{$this->nameroutes}"))
				->build('reports/purchase_receipt/dialog', (isset($data) ? $data : NULL));
		}
	}

	public function export()
	{
		if ($this->input->post()) {
			$this->load->helper("export");
			$this->load->helper("report");

			switch ($this->input->post("export_to")):
				case "pdf":
					$this->export_pdf();
					break;
				case "excel":
					$this->export_excel();
					break;
			endswitch;
		}
	}

	private function export_pdf()
	{
		if ($this->input->post()) {
			$post_data = (object) $this->input->post("f");

			$collection = report_helper::get_warehouse_cards($post_data->date_start, $post_data->date_end, $post_data->Barang_ID, $post_data->Lokasi_ID);
			$barang = report_helper::get_barang($post_data->Barang_ID, $post_data->Lokasi_ID);
			$last_data = end($collection);	// Mengambil data kartu terakhir untuk stok akhir
			$section = $this->db->where("SectionID", "SECT0002")->get("SIMmSection")->row();
			$data = array(
				"post_data" => $post_data,
				"collection" => $collection,
				"last_data" => $last_data,
				"barang" => $barang,
				"section" => $section,
			);

			//print_r($barang);exit;

			$html_content =  $this->load->view("reports/warehouse_cards/export/pdf", $data, TRUE);
			$footer = lang('reports:warehouse_card_label') . "&nbsp; : &nbsp;" . date("d M Y") . "&nbsp;" . date("H:i:s");

			$file_name = lang('reports:warehouse_card_label');

			//print $html_content;exit(0);

			export_helper::generate_pdf($html_content, $file_name, $footer, $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);



			exit(0);
		}

		redirect("{$this->nameroutes}/dialog");
	}

	private function export_excel()
	{
		if ($this->input->post()) {
			$opsi = $this->input->post('f[opsi]');
			$date_start = $this->input->post('f[date_start]');
			$date_end = $this->input->post('f[date_end]');
			$location = $this->input->post('f[location]');
			$supplier = $this->input->post('f[supplier_id]');
			$payment_type = $this->input->post('f[Type_Pembayaran]');
			$kelompok_jenis = $this->input->post('f[KelompokJenis]');

			report_helper::export_purchase_receipt($date_start, $date_end, $location, $supplier, $opsi, $payment_type, $kelompok_jenis);

			exit(0);
		}
	}

	public function lookup_supplier($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('reports/purchase_receipt/lookup/supplier');
		} else {
			redirect(base_url("{$this->nameroutes}"));
		}
	}
}
