<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Vw_regional_model extends CI_Model
{
	public $table = 'Vw_mRegional';
	public $rules;
	
	public function __construct()
	{

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
}

