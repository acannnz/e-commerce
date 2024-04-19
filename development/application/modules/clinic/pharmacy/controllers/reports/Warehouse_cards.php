<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Warehouse_cards extends Admin_Controller
{
	protected $_translation = 'reports';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('pharmacy');
		$this->load->model("section_model");

		$this->page = lang('reports:page');
		$this->template->title($this->page . ' - ' . $this->config->item('company_name'));
	}

	public function index()
	{
		if ($this->input->is_ajax_request()) {
			echo "<script language=\"javascript\">window.location=\"" . base_url("pharmacy/reports/warehouse-cards") . "\";</script>";
			exit();
		} else {
			redirect("pharmacy/reports/warehouse-cards/dialog");
		}
	}


	public function dialog($is_ajax = FALSE)
	{
		$location = $this->session->userdata('pharmacy');
		$section = $this->section_model->get_one($location['section_id']);
		$item = (object) [
			"Lokasi_ID" => $section->Lokasi_ID
		];

		$data = array(
			"item" => $item,
			"datepicker" => true,
			"form" => true,
			"datatables" => true,
			"lookup_products" => base_url("pharmacy/reports/warehouse-cards/lookup_products")
		);

		if ($this->input->is_ajax_request() || $is_ajax) {
			$this->load->view(
				"reports/warehouse_cards/modal/dialog",
				array("form_child" => $this->load->view("reports/warehouse_cards/dialog", $data, true))
			);
		} else {
			$this->template
				->set("heading", $this->page)
				->set_breadcrumb(lang("reports:patient_reservation_page"), base_url("pharmacy/reports/warehouse-cards"))
				->set_breadcrumb(lang("reports:patient_reservation_breadcrumb"))
				->build('reports/warehouse_cards/dialog', (isset($data) ? $data : NULL));
		}
	}

	public function export()
	{
		if ($this->input->post()) {
			$this->load->helper(["export", "report"]);

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
			// print_r($collection);
			// exit;
			$section = $this->section_model->get_by(['Lokasi_ID' => $post_data->Lokasi_ID]);
			$data = [
				"post_data" 	=> $post_data,
				"collection" 	=> $collection,
				"last_data" 	=> $last_data,
				"barang" 		=> $barang,
				"section" 		=> $section,
			];


			$html_content =  $this->load->view("reports/warehouse_cards/export/pdf", $data, TRUE);
			$footer = lang('reports:warehouse_card_label') . "&nbsp; : &nbsp;" . date("d M Y") . "&nbsp;" . date("H:i:s");

			$file_name = lang('reports:warehouse_card_label');

			export_helper::generate_pdf($html_content, $file_name, $footer, $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);



			exit(0);
		}

		redirect("reports/dialog");
	}

	private function export_excel()
	{
		if ($this->input->post()) {
			$post_data = (object) $this->input->post('f');
			report_helper::export_excel_get_warehouse_cards($post_data->date_start, $post_data->date_end, $post_data->Barang_ID, $post_data->Lokasi_ID);


			exit(0);
		}
		redirect("reports/dialog");
	}

	public function lookup_products($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('reports/warehouse_cards/lookup/products');
		} else {
			redirect(base_url("pharmacy/reports/warehouse-cards"));
		}
	}
}
