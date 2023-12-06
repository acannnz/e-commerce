<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Item_typegroup_model extends CI_Model
{
	public $table = 'SIMmKelompokJenisObat';
	protected $index_key = 'KelompokJenis';
	
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
			->order_by('Kelompok', 'ASC')
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
	
	public function dropdown_data($where = NULL)
	{
		if($items = $this->get_all(NULL, 0, $where))
		{
			foreach($items as $item)
			{
				$option_data[$item->{$this->index_key}] = $item->KelompokJenis;
			}
		}	
		return $option_data;
	}
	
	public function to_list_data($group = NULL)
	{
		$this->db->order_by('Kelompok', 'ASC')
			->order_by('KelompokJenis', 'ASC');			
		if (!is_null($group) && !empty($group))
		{
			$this->db->where('Kelompok', $group);
		}			
		$query = $this->db->get($this->table);
		
		$populate = [];
		if ($items = $query->result())
		{
			foreach($items as $item){ $populate[$item->KelompokJenis] = $item->KelompokJenis; }
		}
		
		return $populate;
	}
	
	public function to_list_html($first_label = '', $group = NULL)
	{
		$this->db->order_by('Kelompok', 'ASC')
			->order_by('KelompokJenis', 'ASC');			
		if (!is_null($group) && !empty($group))
		{
			$this->db->where('Kelompok', $group);
		}			
		$query = $this->db->get($this->table);
		
		if ('' != $first_label){ $option_html = "<option value=\"0\">{$first_label}</option>"; }
		if ($items = $query->result())
		{
			foreach($items as $item)
			{
				if (!is_null($group) && !empty($group))
				{
					$option_html .= "<option value=\"{$item->KelompokJenis}\">{$item->KelompokJenis}</option>";
				} else
				{
					$option_html .= "<option value=\"{$item->KelompokJenis}\">{$item->Kelompok} - {$item->KelompokJenis}</option>";
				}
			}
		}
		
		return $option_html;
	}
}

