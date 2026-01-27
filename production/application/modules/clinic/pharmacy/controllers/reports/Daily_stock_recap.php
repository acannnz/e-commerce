<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Daily_stock_recap extends Admin_Controller
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
			echo "<script language=\"javascript\">window.location=\"" . base_url("pharmacy/reports/daily-stock-recap") . "\";</script>";
			exit();
		} else {
			redirect("pharmacy/reports/daily-stock-recap/dialog");
		}
	}


	public function dialog($is_ajax = FALSE)
	{
		$location = $this->session->userdata('pharmacy');
		$section  = $this->section_model->get_one($location['section_id']);
		$item = (object) array(
			"Lokasi_ID" => $section->Lokasi_ID
		);

		$data = array(
			"item" => $item,
			"datepicker" => true,
			"form" => true,
		);

		if ($this->input->is_ajax_request() || $is_ajax) {
			$this->load->view(
				"reports/daily_stock_recap/modal/dialog",
				array("form_child" => $this->load->view("reports/daily_stock_recap/dialog", $data, true))
			);
		} else {
			$this->template
				->set("heading", $this->page)
				->set_breadcrumb(lang("reports:page"))
				->set_breadcrumb('Laporan Rekap Stok Harian', base_url("pharmacy/reports/daily-stock-recap"))
				->build('reports/daily_stock_recap/dialog', (isset($data) ? $data : NULL));
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
			$post_data = (object) $this->input->post('f');

			$collection = report_helper::get_daily_stock_recap_data($post_data->date_start, $post_data->date_end, $post_data->Lokasi_ID);
			$section = $this->section_model->get_by(['Lokasi_ID' => $post_data->Lokasi_ID]);
			
			$data = [
				"post_data" => $post_data,
				"collection" => $collection,
				"section" => $section,
			];
			$html_content =  $this->load->view("reports/daily_stock_recap/export/pdf", $data, TRUE);
			
			$footer = 'Laporan Rekap Stok Harian' . "&nbsp; : &nbsp;" . date("d M Y") . "&nbsp;" . date("H:i:s");
			$file_name = 'Laporan Rekap Stok Harian.pdf';

			// Ensure no output interference - NUCLEAR OPTION
			error_reporting(0);
			ini_set('display_errors', 0);
			while (ob_get_level() > 0) {
				ob_end_clean();
			}
			
			export_helper::generate_pdf($html_content, $file_name, $footer, $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
			exit(0);
		}

		redirect("reports/dialog");
	}

	private function export_excel()
	{
		if ($this->input->post()) {
			$post_data = (object) $this->input->post('f');
			
			// Ensure no output interference - NUCLEAR OPTION
			error_reporting(0);
			ini_set('display_errors', 0);
			while (ob_get_level() > 0) {
				ob_end_clean();
			}
			
			report_helper::export_excel_daily_stock_recap($post_data->date_start, $post_data->date_end, $post_data->Lokasi_ID);

			exit(0);
		}
	}
}
