<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Room extends ADMIN_Controller
{
	protected $nameroutes = 'registrations/room';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('registration');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('registrations');		
		$this->load->model('room_model');
		$this->load->model('room_detail_model');
		$this->load->model('room_status_model');
		$this->load->model('section_model');
		$this->load->model('class_model');
	}
		
	public function lookup_data( $view, $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("room/lookup/{$view}");
		}
	}
	
	public function dropdown_html( $parent_id=0 )
	{
		if( $this->input->is_ajax_request() )
		{
			$parent_id = ($parent_id == 0) ? $this->input->get_post('parent_id') : $parent_id;
			
			$collection = array();
			$collection = $this->room_model->dropdown_html( ['GroupJasa' => $parent_id] );
		
			response_json( $collection );
		}
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
		
		$db_from = "{$this->room_model->table} a";
		$db_where = [];
		$db_like = [];
		
		//prepare defautl flter
		if($this->input->get_post('SectionID', TRUE))
			$db_where['a.SalID'] = $this->input->get_post('SectionID', TRUE);
		
		if($this->input->get_post('KelasID', TRUE))
			$db_where['a.KelasID'] = $this->input->get_post('KelasID', TRUE);		
		
		if($this->input->get_post('Status', TRUE))
			$db_where['a.Status'] = $this->input->get_post('Status', TRUE);		

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.{$this->room_model->index_key}") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoKamar") ] = $keywords;
			$db_like[ $this->db->escape_str("b.SectionName") ] = $keywords;
			$db_like[ $this->db->escape_str("a.JmlBed") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoLantai") ] = $keywords;
			$db_like[ $this->db->escape_str("c.NamaKelas") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->section_model->table} b", "a.SalID = b.SectionID", "LEFT OUTER")
			->join("{$this->class_model->table} c", "a.KelasID = c.KelasID", "LEFT OUTER")
			->join("{$this->room_detail_model->table} d", "a.NoKamar = d.NoKamar", "INNER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*, 
			d.NoBed,
			b.SectionName, 
			c.NamaKelas
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->section_model->table} b", "a.SalID = b.SectionID", "LEFT OUTER")
			->join("{$this->class_model->table} c", "a.KelasID = c.KelasID", "LEFT OUTER")
			->join("{$this->room_detail_model->table} d", "a.NoKamar = d.NoKamar", "INNER")
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
		
		$room_status = $this->room_status_model->dropdown_static();
		foreach($result as $row)
        {      
			$row->Status = @$room_status[$row->Status] ? $room_status[$row->Status] : 'NONE';
            $output['data'][] = $row;
        }
		
		response_json( $output );
    }
}

