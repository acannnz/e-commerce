<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Family extends ADMIN_Controller
{
	protected $nameroutes = 'folder/family';
	protected $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('registration');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('family');		
		
		$this->load->model('family_model');
		$this->load->model('personal_model');
		$this->load->model('personal_to_family_model');
		
		$this->load->model('country_model');
		$this->load->model('province_model');
		$this->load->model('county_model');
		$this->load->model('district_model');
		$this->load->model('village_model');
		$this->load->model('area_model');
	}
		
	public function lookup_collection ()
	{
		$this->datatable_collection( 1 );
	}
	
	public function datatable_collection( $Status = 1 )
	{
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->family_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter
		$db_where['a.Status'] = $Status;

				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.NoFamily") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoKK") ] = $keywords;
			$db_like[ $this->db->escape_str("a.ReffNoFamily") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Address") ] = $keywords;
			$db_like[ $this->db->escape_str("b.PersonalName") ] = $keywords;	 
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->personal_model->table} b", "a.PersonalIdKK = b.Id", "INNER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.PersonalName
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->personal_model->table} b", "a.PersonalIdKK = b.Id", "INNER")
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

