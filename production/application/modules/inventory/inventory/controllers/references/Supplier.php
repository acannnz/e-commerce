<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Supplier extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/references/supplier'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('supplier_model');
	}
	
	public function index()
	{
		show_404();
	}
	
	
	
	public function lookup()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$t_main = $this->db->dbprefix($this->get_model()->table);
		
		// get total records
		$records_total = $this->db->count_all_results($t_main);
		
		// preparing filter
		$db_like = [];
		if (isset($search['value']) && ! empty($search['value']))
        {
            $words = $this->db->escape_str($search['value']);			
			$db_like[$this->db->escape_str("{$t_main}.Kode_Supplier")] = $words;
			$db_like[$this->db->escape_str("{$t_main}.Nama_Supplier")] = $words;
        }
		
		// get total filtered
		if (! empty($db_like))
		{
			$this->db->group_start();
			foreach($db_like as $field => $match)
			{
				$this->db->or_like($field, $match, 'both', TRUE);
			}
			$this->db->group_end();
		}
		$records_filtered = $this->db->count_all_results($t_main);
		
		
		// get result filtered
		$db_select = <<<EOSQL
			{$t_main}.Supplier_ID AS Id, 
			{$t_main}.Kode_Supplier AS Kode, 
			{$t_main}.Nama_Supplier AS Nama
EOSQL;
		
		$db_order = [ 
				"Kode" => "{$t_main}.Kode_Supplier",
				"Nama" => "{$t_main}.Nama_Supplier",
				"Supplier_ID" => "{$t_main}.Supplier_ID"
			];
		
		$this->db->select($db_select)->where('KodeKategoriVendor','V-001');
		
		if (! empty($db_like))
		{
			$this->db->group_start();
			foreach($db_like as $field => $match)
			{
				$this->db->or_like($field, $match, 'both', TRUE);
			}
			$this->db->group_end();
		}
		if (isset($order))
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$field = $columns[intval($this->db->escape_str($sort_column))]['data'];
				$this->db->order_by($db_order[$field], $this->db->escape_str($sort_dir));
			}
        }
		if (isset($start) && $length != '-1')
        {
            $this->db->limit($length, $start);
        }
		
		$query = $this->db->get($t_main);
		$result = $query->result_array();
		
        $output = [
				'draw' => intval($draw),
				'recordsTotal' => $records_total,
				'recordsFiltered' => $records_filtered,
				'data' => []
			];        
        foreach($result as $row)
        {
			$output['data'][] = $row;
        }
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($output))
			->_display();
		
		exit(0);
    }
	
	public function get_model()
	{
		return $this->supplier_model;
	}
}

