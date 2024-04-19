<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Data_close extends Admin_Controller
{
	protected $_translation = 'drug_payment';
	protected $_model = 'drug_payment_m';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('cashier');

		$this->load->model('drug_payment_m');
		$this->load->model('pharmacy_m');
		$this->load->model('common/patient_type_m');
		$this->load->model('common/supplier_m');
		$this->load->model('common/section_m');
		$this->load->model('common/customer_m');

		$this->load->helper('drug_payment');
	}

	public function index()
	{
		$data = array();

		$this->load->view('drug_payment/datatable/close', (isset($data) ? $data : NULL));
	}

	public function lookup($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('registrations/lookup/datatable');
		} else {
			$data = array(
				'page' => $this->page,
				'datatables' => TRUE,
				'form' => TRUE,
			);

			$this->template
				->set("heading", "Lookup Box")
				->set_breadcrumb(lang("drug_payment:page"), base_url("drug_payment"))
				->set_breadcrumb("Lookup Box")
				->build('registrations/lookup', (isset($data) ? $data : NULL));
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

		$db_from = "{$this->drug_payment_m->table} a";
		$db_where = array();
		$db_like = array();

		$db_where['a.tipe'] = "OBAT BEBAS";
		//$db_where['a.Batal'] = 0;

		// $location = $this->session->userdata('pharmacy');
		// $db_where['a.SectionID'] = $location['section_id'];
		$location = 'SECT0002';
		$db_where['e.SectionID'] = $location;

		if ($this->input->get_post("date_from")) {
			$date_start = DateTime::createFromFormat('Y-m-d', $this->input->get_post("date_from"))->setTime(0, 0)->modify('+8 hour');
			$db_where['a.Jam >='] = $date_start->format('Y-m-d H:i:s');
		}

		if ($this->input->get_post("date_till")) {
			$date_end = DateTime::createFromFormat('Y-m-d', $this->input->get_post("date_till"))->setTime(0, 0)->modify('+1 day')->modify('+8 hour');
			$db_where['a.Jam <='] = $date_end->format('Y-m-d H:i:s');
		}

		// preparing default
		if (isset($search['value']) && !empty($search['value'])) {
			$keywords = $this->db->escape_str($search['value']);

			$db_like[$this->db->escape_str("a.NoBukti")] = $keywords;
			$db_like[$this->db->escape_str("a.Jam")] = $keywords;
			$db_like[$this->db->escape_str("a.Keterangan")] = $keywords;
			$db_like[$this->db->escape_str("a.NoBuktiFarmasi")] = $keywords;
			$db_like[$this->db->escape_str("c.JenisKerjasama")] = $keywords;
			$db_like[$this->db->escape_str("d.Nama_Supplier")] = $keywords;
			$db_like[$this->db->escape_str("e.SectionName")] = $keywords;
		}

		//get total records
		$this->db->from($db_from)
			->join("{$this->pharmacy_m->table} b", "a.NoBuktiFarmasi = b.NoBukti", "LEFT OUTER")
			->join("{$this->section_m->table} e", "b.SectionID = e.SectionID", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		$records_total = $this->db->count_all_results();

		// get total filtered
		$this->db
			->from($db_from)
			->join("{$this->pharmacy_m->table} b", "a.NoBuktiFarmasi = b.NoBukti", "LEFT OUTER")
			->join("{$this->patient_type_m->table} c", "b.KerjasamaID = c.JenisKerjasamaID", "LEFT OUTER")
			->join("{$this->supplier_m->table} d", "b.DokterID = d.Kode_Supplier", "LEFT OUTER")
			->join("{$this->section_m->table} e", "b.SectionID = e.SectionID", "LEFT OUTER")
			->join("{$this->customer_m->table} f", "b.KodePerusahaan = f.Kode_Customer", "LEFT OUTER");
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
			a.Jam,			
			a.Keterangan,
			a.NoBuktiFarmasi,
			c.JenisKerjasama,
			d.Nama_Supplier,
			e.SectionName,
			f.Nama_Customer,
			a.Batal
			
EOSQL;

		$this->db
			->select($db_select)
			->from($db_from)
			->join("{$this->pharmacy_m->table} b", "a.NoBuktiFarmasi = b.NoBukti", "LEFT OUTER")
			->join("{$this->patient_type_m->table} c", "b.KerjasamaID = c.JenisKerjasamaID", "LEFT OUTER")
			->join("{$this->supplier_m->table} d", "b.DokterID = d.Kode_Supplier", "LEFT OUTER")
			->join("{$this->section_m->table} e", "b.SectionID = e.SectionID", "LEFT OUTER")
			->join("{$this->customer_m->table} f", "b.KodePerusahaan = f.Kode_Customer", "LEFT OUTER");
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
}
