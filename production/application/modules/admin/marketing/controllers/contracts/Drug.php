<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Drug extends ADMIN_Controller
{
	protected $nameroutes = 'marketing/contracts/drug';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('marketing');		
		$this->load->helper('marketing');
		$this->load->model('contract_drug_model');
	}
	
	/*
		@params
		(Object) $item -> Data Jasa
	*/
	public function index( $id )
	{
		$this->data['collection_drug'] = marketing_helper::get_contract_drug( $id );
		$this->data['add_contract_drug'] = base_url("{$this->nameroutes}/lookup_data/lookup_item");

		$this->load->view('contracts/drug/table', $this->data);
	}
	
	public function lookup_data($view)
	{
		if( $this->input->is_ajax_request() )
		{
			$this->load->view("contract/contracts/drug/lookup/{$view}", $this->data);
		}
	}
	
	public function datatable_collection()
	{
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "VW_Barang a";
		$db_where = [];
		$db_like = [];
		
		//prepare defautl flter
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Barang_ID") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nama_Barang") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from )
				;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("mKategori b", "a.Kategori_ID = b.Kategori_ID", "INNER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.Nama_Kategori 
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("mKategori b", "a.Kategori_ID = b.Kategori_ID", "INNER")
			;
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		
		// ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$this->db
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir) );
			}
        }
		
		// paging
		if( isset($start) && $length != '-1')
        {
            $this->db
				->limit( $length, $start );
        }
		
		// get
		$result = $this->db
					->get()
					->result()
					;

        // Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => $records_total,
				'recordsFiltered' => $records_filtered,
				'data' => array()
			);
		
		foreach($result as $row)
        {      
            $output['data'][] = $row;
        }
		
		response_json( $output );
    }
}

