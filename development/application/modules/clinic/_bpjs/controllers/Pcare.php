<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Pcare extends ADMIN_Controller
{
	protected $nameroutes = 'bpjs/pcare';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('integration_insurance');

		$this->data['nameroutes'] = $this->nameroutes;

		$this->load->language('bpjs');
		$this->load->helper('bpjs');
		$this->load->model('integration_insurance_model');
		$this->load->model('registration_model');
		$this->load->model('patient_model');
		$this->load->model('section_model');
		$this->load->model('supplier_model');
	}

	public function index()
	{
		$this->data['datatables'] = TRUE;
		$this->data['form'] = TRUE;
		$this->data['option_doctor'] = option_doctor();
		$this->data['option_section'] = option_section('SectionIDBPJS IS NOT NULL');

		$this->template
			->set("heading", lang("heading:pcare"))
			->title(lang('heading:pcare'), lang('heading:bpjs'))
			->set_breadcrumb(lang('heading:bpjs'))
			->set_breadcrumb(lang('heading:pcare_list'), site_url($this->nameroutes))
			->build("pcare/datatable", $this->data);
	}

	public function create()
	{
		$item = (object) [
			'JenisLayanan' => NULL,
		];

		if ($this->input->post()) {
			$post_pcare = array_merge((array) $item, $this->input->post("f"));

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->pcare_model->rules['insert']);
			$this->form_validation->set_data($post_pcare);
			if ($this->form_validation->run()) {
				bpjs_helper::update_visite_outpatient();
			} else {
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}

			response_json($response);
		}

		$this->data['item'] = $item;
		$this->data['form_action'] = current_url();

		$this->template
			->set("heading", lang("heading:pcare_create"))
			->title(lang('heading:pcare_create'), lang('heading:bpjs'))
			->set_breadcrumb(lang('heading:pcare'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:pcare_create'))
			->build("pcare/form", $this->data);
	}

	public function update($id = NULL)
	{
		$this->data['item'] = $item = bpjs_helper::get_visite_outpatient($id);

		if ($this->input->post()) {
			$post_pcare = $this->input->post();

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->integration_insurance_model->rules['update']);
			$this->form_validation->set_data(['NoReg' => $id]);
			if ($this->form_validation->run()) {
				$response = bpjs_helper::update_visite_outpatient($id, $post_pcare);
			} else {
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
			response_json($response);
		}

		$this->data['is_edit'] = TRUE;
		$this->data['datatables'] = TRUE;
		$this->data['form'] = TRUE;
		$this->data['form_action'] = current_url();
		$this->data['get_visite_url'] = config_item('bpjs_api_baseurl') . "/visite/member/{$item->NoKartu}";
		$this->data['get_referral_url'] = config_item('bpjs_api_baseurl') . "/visite/nomor/{$item->NoKunjungan}";
		$this->data['update_visite_url'] = config_item('bpjs_api_baseurl') . "/visite";
		$this->data['get_consciousness_url'] = config_item('bpjs_api_baseurl') . "/kesadaran";
		$this->data['get_doctor_url'] = config_item('bpjs_api_baseurl') . "/dokter/100";
		$this->data['get_checkout_url'] = config_item('bpjs_api_baseurl') . "/pulang/{$item->StatusPelayanan}";
		$this->data['export_visite'] = base_url("{$this->nameroutes}/export_visite/{$id}");
		$this->data['export_history_visite'] = base_url("{$this->nameroutes}/export_history_visite/{$id}");
		$this->data['export_referral'] = base_url("{$this->nameroutes}/export_referral/{$id}");

		$this->template
			->set("heading", lang("heading:pcare_update"))
			->title(lang('heading:pcare_update'), lang('heading:bpjs'))
			->set_breadcrumb(lang('heading:pcare'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:pcare_update'))
			->build("pcare/form", $this->data);
	}

	public function delete($id = 0)
	{
		$this->data['item'] = $item = $this->pcare_model->get_one($id);

		if ($item  && (1 == $this->input->post('confirm'))) {

			$this->db->trans_begin();

			$this->pcare_model->delete($id);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				response_json(["status" => 'error', 'message' => lang('global:delete_failed'), 'success' => FALSE]);
			} else {
				$this->db->trans_commit();
				response_json(["status" => 'success', 'message' => lang('global:delete_successfully'), 'success' => TRUE]);
			}
		}

		$this->data['form_action'] = $form_action = current_url();
		$this->load->view('pcare/modal/delete', $this->data);
	}

	public function service($id = 0)
	{
		$this->data['collection'] = $collection = bpjs_helper::get_service_outpatient($id);

		$this->data['form_action'] = $form_action = current_url();
		$this->load->view('pcare/detail/services', $this->data);
	}

	public function drug($id = 0)
	{
		$this->data['collection'] = $collection = bpjs_helper::get_drug_outpatient($id);

		$this->data['form_action'] = $form_action = current_url();
		$this->load->view('pcare/detail/drugs', $this->data);
	}

	public function mcu($id = 0)
	{
		//$this->data['collection'] = $collection = bpjs_helper::get_drug_outpatient($id);

		$this->data['form_action'] = $form_action = current_url();
		$this->data['lookup_form'] = base_url("{$this->nameroutes}/modal/mcu");
		$this->load->view('pcare/detail/mcu', $this->data);
	}

	public function lookup($view, $id = 0)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$data = ['id' => $id];
			$this->load->view("pcare/lookup/{$view}", $data);
		}
	}

	public function modal($view, $is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view("pcare/modal/{$view}");
		}
	}

	public function dropdown_html($parent_id = 0)
	{
		if ($this->input->is_ajax_request()) {
			$parent_id = ($parent_id == 0) ? $this->input->get_post('parent_id') : $parent_id;

			$collection = array();
			$collection = $this->pcare_model->dropdown_html(['GroupJasa' => $parent_id]);

			response_json($collection);
		}
	}

	public function lookup_collection()
	{
		$this->datatable_collection(1);
	}

	public function datatable_collection($Status = 1)
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_from = "{$this->integration_insurance_model->table} a";
		$db_where = array();
		$db_like = array();

		//prepare defautl flter
		if ($this->input->post("date_from")) {
			$db_where['a.CreatedAt >='] = DateTime::createFromFormat('Y-m-d', $this->input->post("date_from"))->setTime(0, 0)->format('Y-m-d H:i:s');
		}

		if ($this->input->post("date_till")) {
			$db_where['a.CreatedAt <='] = DateTime::createFromFormat('Y-m-d', $this->input->post("date_till"))->setTime(23, 59)->format('Y-m-d H:i:s');
		}

		if ($this->input->post("NRM")) {
			$db_like['c.NRM'] = $this->input->post("NRM");
		}

		if ($this->input->post("Nama")) {
			$db_like['c.NamaPasien'] = $this->input->post("Nama");
		}

		if ($this->input->post("SectionID")) {
			$db_where['a.SectionID'] = $this->input->post("SectionID");
		}

		if ($this->input->post("DokterID")) {
			$db_where['a.DokterID'] = $this->input->post("DokterID");
		}
		// preparing default
		if (isset($search['value']) && !empty($search['value'])) {
			$keywords = $this->db->escape_str($search['value']);

			$db_like[$this->db->escape_str("a.{$this->pcare_model->index_key}")] = $keywords;
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
			->join("{$this->registration_model->table} b", "a.NoReg = b.NoReg", "INNER")
			->join("{$this->patient_model->table} c", "b.NRM = c.NRM", "INNER")
			->join("{$this->section_model->table} d", "a.SectionID = d.SectionID", "INNER")
			->join("{$this->supplier_model->table} e", "a.DokterID = e.Kode_Supplier", "INNER");
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
			a.NoBuktiIntegrasi,
			a.CreatedAt,
			a.NoUrut,
			a.NoKartu,
			c.NRM,
			c.NamaPasien,
			d.SectionName,
			e.Nama_Supplier AS DokterName
EOSQL;

		$this->db
			->select($db_select)
			->from($db_from)
			->join("{$this->registration_model->table} b", "a.NoReg = b.NoReg", "INNER")
			->join("{$this->patient_model->table} c", "b.NRM = c.NRM", "INNER")
			->join("{$this->section_model->table} d", "a.SectionID = d.SectionID", "INNER")
			->join("{$this->supplier_model->table} e", "a.DokterID = e.Kode_Supplier", "INNER");

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
			$row->CreatedAt = DateTime::createFromFormat('Y-m-d H:i:s.u', $row->CreatedAt)->format('Y-m-d');
			$output['data'][] = $row;
		}

		response_json($output);
	}

	public function export_visite($NoReg)
	{
		$item = bpjs_helper::get_visite_outpatient($NoReg);

		$data = [
			"item" => $item,
			"visite" => json_decode($this->input->post('visite'))
		];

		$html_content =  $this->load->view("pcare/export/visite", $data, TRUE);
		$file_name = 'Surat Rujukan FKTP';
		$this->load->helper("export");

		export_helper::generate_pdf($html_content, $file_name, $footer, $margin_bottom = 4, $header = NULL, $margin_top = 5, $orientation = 'L', $margin_left = 5, $margin_right = 12);
	}

	public function export_referral($NoReg)
	{
		$item = bpjs_helper::get_visite_outpatient($NoReg);

		$data = [
			"item" => $item,
			"referral" => json_decode($this->input->post('referral'))
		];

		$html_content =  $this->load->view("pcare/export/referral", $data, TRUE);
		$file_name = 'Surat Rujukan FKTP';
		$this->load->helper("export");

		export_helper::generate_pdf($html_content, $file_name, $footer, $margin_bottom = 4, $header = NULL, $margin_top = 5, $orientation = 'L', $margin_left = 5, $margin_right = 12);
		exit;
	}

	public function export_history($NoReg)
	{
		$item = bpjs_helper::get_visite_outpatient($NoReg);

		$data = [
			"item" => $item,
			"referral" => json_decode($this->input->post('referral'))
		];

		$html_content =  $this->load->view("pcare/export/referral", $data, TRUE);
		$file_name = 'Surat Rujukan FKTP';
		$this->load->helper("export");

		export_helper::generate_pdf($html_content, $file_name, $footer, $margin_bottom = 4, $header = NULL, $margin_top = 5, $orientation = 'L', $margin_left = 5, $margin_right = 12);
	}
}
