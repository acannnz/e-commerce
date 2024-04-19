<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Procurement_model extends CI_Model
{
	public $table = 'GD_mJenisPengadaan';
	protected $index_key = 'JenisPengadaanID';
	
	public function create($data)
	{
		$this->db->insert($this->table, $data);
		return (int) $this->db->insert_id(); 
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
	
	public function to_list_data($location_id = 0, $first_label = '')
	{
		$location_id = (int) $location_id;
		
		if (0 == $location_id){ $items = $this->get_all(); }
		else { $items = $this->get_all(NULL,0,['Lokasi_ID' => $location_id]); }
		
		$populate = ['' => $first_label];
		if ($items = $this->get_all())
		{
			foreach($items as $item)
			{
				$populate[$item->JenisPengadaanID] = $item->JenisPengadaan;
			}
		}
		
		return $populate;
	}

	public function to_list_data_group()
	{	
		$populate = [];
		if ($items = $this->get_all())
		{
			foreach($items as $item)
			{
				$populate[$item->Lokasi_ID][$item->JenisPengadaanID] = $item->JenisPengadaan;
			}
		}
		
		return $populate;
	}
		
	public function to_list_html($location_id = 0, $first_label = '')
	{
		$location_id = (int) $location_id;
		
		if (0 == $location_id){ $items = $this->get_all(); }
		else { $items = $this->get_all(NULL,0,['Lokasi_ID' => $location_id]); }
		
		$option_html = "<option value=\"0\">{$first_label}</option>";
		if ($items = $this->get_all())
		{
			foreach($items as $item)
			{
				$option_html .= "<option value=\"{$item->JenisPengadaanID}\">{$item->JenisPengadaan}</option>";
			}
		}
		
		return $option_html;
	}
	
	
	public function for_dropdown(){
		$result = array();
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('TypeHutang_ID',3);
		$query = $this->db->get();
		foreach($query->result() as $row){
			$result[$row->JenisPengadaanID] = $row->JenisPengadaan;
		}
		
		return $result;
	}

}

