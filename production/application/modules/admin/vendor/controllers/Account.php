<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Account extends ADMIN_Controller
{
	protected $nameroutes = 'vendor/account';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('vendor');		
		$this->load->helper('vendor');
		$this->load->model('account_model');
		$this->load->model('account_group_model');
	}
	
	public function index()
	{
		show_404();
	}
		
	public function lookup_collection ()
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
		
		$db_from = "{$this->account_model->table} a";
		$db_where = $this->input->get_post('_where');
		$db_expression = (array) $this->input->get_post('_expression');
		$db_like = array();
		
		//prepare defautl flter
		if( $this->input->get_post('bank_only') )
		{
			$db_where['b.Bank'] = 1;
		}
		
		$this->input->get_post('_where');
		
		$db_where['a.Induk'] = 0;
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Akun_No") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Akun_Name") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from )
				->join("{$this->account_group_model->table} b", "a.GroupAkunDetailID = b.GroupAkunDetailId", "INNER")
				;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->account_group_model->table} b", "a.GroupAkunDetailID = b.GroupAkunDetailId", "INNER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->account_group_model->table} b", "a.GroupAkunDetailID = b.GroupAkunDetailId", "INNER")
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
		
		$this->db->order_by("Akun_No", 'ASC');
		
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

