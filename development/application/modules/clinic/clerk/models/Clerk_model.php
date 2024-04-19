<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Clerk_model extends CI_Model
{
	public $table = 'SIMtrClerk';
	public $index_key = 'KodeClerk';
	public $rules;
	
	public function __construct()
	{
		parent::__construct();

		$this->rules = [
			'start' => [
				[
					'field' => 'KodeClerk',
                	'label' => lang('label:code'),
               		'rules' => "required|is_unique[{$this->table}.{$this->index_key}]"
				],
				[
					'field' => 'UserID',
                	'label' => lang('global:user'),
               		'rules' => "required"
				],
				[
					'field' => 'SectionID',
                	'label' => lang('label:section'),
               		'rules' => "required"
				],
				[
					'field' => 'WaktuMulaiClerk',
                	'label' => lang('label:date'),
               		'rules' => "required"
				],
			],
			'end' => [
				[
					'field' => 'WaktuAkhirClerk',
                	'label' => lang('label:qty'),
               		'rules' => "required"
				],
				[
					'field' => 'JumlahTotalSystem',
                	'label' => lang('label:amount_total'),
               		'rules' => "required"
				],
				[
					'field' => 'JumlahTotalClerk',
                	'label' => lang('label:amount_clerk'),
               		'rules' => "required"
				],
				[
					'field' => 'JumlahTotalSelisih',
                	'label' => lang('label:amount_diff'),
               		'rules' => "required"
				],
				[
					'field' => 'StatusClerk',
                	'label' => lang('global:state'),
               		'rules' => "required"
				],
			],
			'update' => [
				[
					'field' => 'pwch_name',
                	'label' => lang('label:name'),
               		'rules' => "required"
				],
				[
					'field' => 'pwch_updated_at',
                	'label' => lang('global:updated_at'),
               		'rules' => ""
				],
				[
					'field' => 'pwch_updated_by',
                	'label' => lang('global:updated_by'),
               		'rules' => ""
				],
			]
		];
	}
	
	public function create($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->affected_rows();
		//return $this->db->insert_id(); // if have a auto increment primary key
	}
	
	public function mass_create($collection)
	{
		return $this->db->insert_batch($this->table, $collection);
	}
	
	public function update($data, $key)
	{
		$this->db->where($this->index_key, $key);
		return $this->db->update($this->table, $data);
	}
	
	public function update_by($data, Array $where)
	{
		$this->db->where($where);
		return $this->db->update($this->table, $data);
	}
	
	public function delete($key)
	{
		$this->db->where($this->index_key, $key);
		return $this->db->delete($this->table);
	}
	
	public function delete_by(Array $where)
	{
		$this->db->where($where);
		return $this->db->delete($this->table);
	}
	
	public function get_one($key, $to_array = FALSE)
	{
		$this->db->where($this->index_key, $key);
		$query = $this->db->get($this->table, 1);
		return (TRUE == $to_array) ? $query->row_array() : $query->row();
	}
	
	public function get_by(Array $where, $to_array = FALSE)
	{
		$this->db->where($where);
		$query = $this->db->get($this->table, 1);
		return (TRUE == $to_array) ? $query->row_array() : $query->row();
	}
	
	public function get_all($limit = NULL, $offset = 0, $where = NULL, $to_array = FALSE)
	{
		if (!is_null($where) && !empty($where)){ $this->db->where($where); }
		
		$query = $this->db
			->order_by($this->index_key, 'ASC')
			->get($this->table, $limit, $offset);		
		return (TRUE == $to_array) ? $query->result_array() : $query->result();
	}
	
	public function count_all($where = NULL)
	{
		if (!is_null($where) && !empty($where)){ $this->db->where($where); }
		
		$this->db->where($where);		
		return (int) ($this->db->count_all_results($this->table));
	}	
	
	public function dropdown_data( $where = NULL)
	{
		$collection = [];
		foreach( $this->get_all( NULL, 0, $where ) as $row )
		{
			$name = $this->prefix.'name';
			$collection[ $row->{$this->index_key} ]	= $row->{$name};
		}
		
		return $collection;
	}

	public function dt_records_total( $db_where = NULL)
	{
		$this->db->from( $this->table ." a" );
		if( !empty($db_where) ) $this->db->where( $db_where );
		return $this->db->count_all_results();
	}

	public function dt_records_filtered($db_where = NULL, $db_like = NULL)
	{
		$this->db->from("{$this->table} a")
			->join("mUser b", "a.UserID = b.User_ID")
			;
		if( !empty($db_where) ) $this->db->where( $db_where );
		if( !empty($db_like) ) $this->db->group_start()->or_like( $db_like )->group_end();
		return $this->db->count_all_results();

	}

	public function dt_results($db_where = NULL, $db_like = NULL, $order = NULL, $columns = NULL, $start = NULL, $length = NULL)
	{
		$this->db->select("a.*, b.Nama_Singkat")
			->from("$this->table a")
			->join("mUser b", "a.UserID = b.User_ID")
			;
			
		if( !empty($db_where) ) $this->db->where( $db_where );
		if( !empty($db_like) ) $this->db->group_start()->or_like( $db_like )->group_end();
		
		// ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( $columns[$sort_column]['orderable'] == 'true' )
				$this->db
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir) );
        }
		
		// paging
		if( isset($start) && $length != '-1')
            $this->db->limit( $length, $start );
		
		// get results
		return $this->db->get()->result();
	}
	
}

