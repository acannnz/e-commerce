<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mutation_stocks extends Admin_Controller
{
	protected $_translation = 'reports';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		$this->load->model('section_model');

		$this->page = lang('reports:page');
		$this->template->title($this->page . ' - ' . $this->config->item('company_name'));
	}

	public function index()
	{
		if ($this->input->is_ajax_request()) {
			echo "<script language=\"javascript\">window.location=\"" . base_url("pharmacy/reports/mutation-stocks") . "\";</script>";
			exit();
		} else {
			redirect("pharmacy/reports/mutation-stocks/dialog");
		}
	}


	public function dialog($is_ajax = FALSE)
	{
		$location = $this->session->userdata('pharmacy');
		$data = array(
			"datepicker" => true,
			"form" => true,
			"lookup_products" => base_url("inventory/reports/mutation_stocks/lookup_products")
		);

		if ($this->input->is_ajax_request() || $is_ajax) {
			$this->load->view(
				"reports/mutation_stocks/modal/dialog",
				array("form_child" => $this->load->view("reports/mutation_stocks/dialog", $data, true))
			);
		} else {
			$data['section'] = $location['section_id'];
			print_r($data['section']);exit;
			$data['dropdown_section'] = $this->section_model->for_dropdown(true, ['SectionID !=' => $location['section_id']]);
			$this->template
				->set("heading", $this->page)
				->set_breadcrumb(lang("reports:patient_reservation_page"), base_url("pharmacy/reports/mutation-stocks"))
				->set_breadcrumb(lang("reports:patient_reservation_breadcrumb"))
				->build('reports/mutation_stocks/dialog', (isset($data) ? $data : NULL));
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

			if (!empty($post_data->to_location))
				$this->db->where(['c.Lokasi_Tujuan' => $post_data->to_location]);

			$get_collection = $this->db->select('c.Tgl_Mutasi, b.Nama_Barang, a.Qty, a.Harga, a.Kode_Satuan, Asal.SectionName SectionAsal, Tujuan.SectionName SectionTujuan')
				->from("GD_trMutasiDetail a")
				->join('mBarang b', 'a.Barang_ID = b.Barang_ID', 'INNER')
				->join('GD_trMutasi c', 'a.No_Bukti = c.No_Bukti', 'INNER')
				->join('SIMmSection Asal', 'c.Lokasi_Asal = Asal.Lokasi_ID', 'INNER')
				->join('SIMmSection Tujuan', 'c.Lokasi_Tujuan = Tujuan.Lokasi_ID', 'INNER')
				->where([
					'c.Tgl_Mutasi >=' => $post_data->date_start,
					'c.Tgl_Mutasi <=' => $post_data->date_end,
					'c.Lokasi_Asal' => $this->section->Lokasi_ID,
				])
				->order_by('c.Tgl_Mutasi')
				->get()->result();

			$collection = [];
			foreach ($get_collection as $key => $item) {
				$collection[$item->SectionTujuan][] = $item;
			}

			$data = [
				"post_data" => $post_data,
				"section" => $this->section,
				"collection" => $collection,
			];

			$html_content =  $this->load->view("reports/mutation_stocks/export/pdf", $data, TRUE);
			$file_name = lang('reports:mutation_stock_label');

			export_helper::generate_pdf($html_content, $file_name, $footer = null, $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'P', $margin_left = 8, $margin_right = 8);

			exit(0);
		}

		redirect("reports/dialog");
	}

	private function export_excel()
	{
		if ($this->input->post()) {
			$post_data = (object) $this->input->post("f");

			if (!empty($post_data->to_location))
				$this->db->where(['c.Lokasi_Tujuan' => $post_data->to_location]);

			$get_collection = $this->db->select('c.No_Bukti, c.Tgl_Mutasi, b.Nama_Barang, a.Diskon,a.Qty, a.Harga, a.Kode_Satuan, Asal.SectionName SectionAsal, Tujuan.SectionName SectionTujuan')
				->from("GD_trMutasiDetail a")
				->join('mBarang b', 'a.Barang_ID = b.Barang_ID', 'INNER')
				->join('GD_trMutasi c', 'a.No_Bukti = c.No_Bukti', 'INNER')
				->join('SIMmSection Asal', 'c.Lokasi_Asal = Asal.Lokasi_ID', 'INNER')
				->join('SIMmSection Tujuan', 'c.Lokasi_Tujuan = Tujuan.Lokasi_ID', 'INNER')
				->where([
					'c.Tgl_Mutasi >=' => $post_data->date_start,
					'c.Tgl_Mutasi <=' => $post_data->date_end,
					'c.Lokasi_Asal' => $this->section->Lokasi_ID,
				])
				->order_by('c.Tgl_Mutasi')
				->get()->result();

			$collection = [];
			foreach ($get_collection as $item) {
				$collection[$item->SectionTujuan][] = $item;
			}

			report_helper::export_mutation($post_data, $this->section, $collection);
		}

		redirect("reports/dialog");
	}

	public function lookup_products($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('reports/mutation_stocks/lookup/products');
		} else {
			redirect(base_url("pharmacy/reports/mutation-stocks"));
		}
	}
}
