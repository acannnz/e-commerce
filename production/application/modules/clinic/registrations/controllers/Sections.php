<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sections extends Admin_Controller
{
	protected $_translation = 'registrations';
	protected $_model = 'section_model';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('registration');

		$this->load->model("section_model");

		$this->page = "sections";
		$this->template->title(lang("sections:page") . ' - ' . $this->config->item('company_name'));
	}

	public function index()
	{
		$data = array(
			'page' => $this->page,
			"form" => TRUE,
			'datatables' => TRUE,
		);

		$this->template
			->set("heading", lang("sections:page"))
			->set_breadcrumb(lang("common:page"), base_url("common"))
			->set_breadcrumb(lang("sections:breadcrumb"))
			->build('sections/datatable', (isset($data) ? $data : NULL));
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

		$db_from = "{$this->section_model->table} a";
		$db_where = [];
		$db_where_or = [];
		$db_like = [];

		// prepare defautl flter
		$db_where['a.StatusAktif'] = 1;

		if ($this->input->post('TipePelayanan') == 'RawatJalan') {
			$db_where_or['a.TipePelayanan'] = 'RJ';
			$db_where_or['a.TipePelayanan '] = 'PENUNJANG';
		} else {
			$db_where['a.TipePelayanan'] = 'RI';
		}

		if ($this->input->post('KelompokSectionID')) {
			$db_where['a.KelompokSection'] = $this->input->post('KelompokSectionID');
		}

		// preparing default
		if (isset($search['value']) && !empty($search['value'])) {
			$keywords = $this->db->escape_str($search['value']);

			$db_like[$this->db->escape_str("a.SectionID")] = $keywords;
			$db_like[$this->db->escape_str("a.SectionName")] = $keywords;
		}

		// get total records
		$this->db->from($db_from);
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		$records_total = $this->db->count_all_results();

		// get total filtered
		$this->db
			->from($db_from);

		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}
		if (!empty($db_where_or)) {
			$this->db->group_start()->or_where($db_where_or)->group_end();
		}
		$records_filtered = $this->db->count_all_results();


		// get result filtered
		$db_select = <<<EOSQL
			a.SectionName,
			a.SectionID,
			a.SectionIDBPJS,
			b.Supplier_ID,
			b.Kode_Supplier,
			b.Nama_Supplier

EOSQL;

		$this->db
			->select($db_select)
			->from($db_from)
			->join("mSupplier b", "a.SectionID = b.SectionID", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}
		if (!empty($db_where_or)) {
			$this->db->group_start()->or_where($db_where_or)->group_end();
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
			$output['data'][] = $row;
		}

		$this->template
			->build_json($output);
	}
	public function autocomplete()
	{
		$words = $this->input->get_post('query');

		$this->db
			->select(array("id", "code", "section_title"));

		$this->db
			->from("common_sections");

		$this->db
			->group_start()
			->where(array(
				'deleted_at' => NULL,
				'state' => 1
			))
			->group_end();

		$this->db
			->group_start()
			->or_like(array(
				"code" => $words,
				"section_title" => $words,
				"section_description" => $words,
			))
			->group_end();

		$result = $this->db
			->get()
			->result();

		if ($result) {
			$collection = array();
			foreach ($result as $item) {
				array_push($collection, array(
					"name" => $item->section_title,
					"id" => $item->id,
				));
			}
		} else {
			$collection = array(array(
				"value" => 0,
				"label" => lang("global:no_match"),
				"id" => 0,
			));
		}

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($collection);
		exit(0);
	}
}
