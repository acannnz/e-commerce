<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Section_model extends CI_Model
{
	public $table = 'SIMmSection';
	protected $index_key = 'SectionID';
	
	public function create($data)
	{
		return $this->db->insert($this->table, $data);
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
	
	public function to_list_data()
	{
		$query = $this->db
			->where('TipePelayanan','FARMASI')
			->order_by('SectionName', 'ASC')
			->get($this->table);	
		
		if ($items = $query->result())
		{
			foreach($items as $item){ $populate[$item->Lokasi_ID] = $item->SectionName; }
		}
		
		return $populate;
	}
	
	public function to_list_html($first_label = '')
	{
		$query = $this->db
			->where('TipePelayanan','FARMASI')
			->order_by('SectionName', 'ASC')
			->get($this->table);
		
		$option_html = "<option value=\"\">{$first_label}</option>";
		if ($items = $query->result())
		{
			foreach($items as $item)
			{
				$option_html .= "<option value=\"{$item->Lokasi_ID}\">{$item->SectionName}</option>";
			}
		}
		
		return $option_html;
	}
	
	public function for_dropdown( $all_section = FALSE )
	{
		if ( $all_section == FALSE)
		{
			$this->db->where('TipePelayanan','FARMASI');
		}
		
		$this->db->select('*')
			->from($this->table)
			->order_by('SectionName', 'ASC')
			;

		$query = $this->db->get();
	
		$result = array();
		foreach($query->result() as $row){
			$result[$row->Lokasi_ID] = $row->SectionName;
		}
		
		return $result;
	}
	
	public function for_dropdown_section( $all_section = FALSE ){
	
		if ( $all_section == FALSE)
		{
			$this->db->where('TipePelayanan','FARMASI');
		}
		
		$this->db->select('*')
			->from($this->table)
			->order_by('SectionName', 'ASC')
			;

		$query = $this->db->get();
		
		$result = array();
		foreach($query->result() as $row){
			$result[$row->SectionID] = $row->SectionName;
		}
		
		return $result;
	}

}

