<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Currency_model extends CI_Model
{
	public $table = 'Mst_Currency';
	public $index_key = 'Currency_ID';
	public $rules;
	
	public function __construct()
	{
		$this->rules = [
			'insert' => [
				[
					'field' => 'Currency_Code',
                	'label' => lang('label:code'),
               		'rules' => "required|is_unique[{$this->table}.Kode_Kategori]"
				],
				[
					'field' => 'Currency_Name',
                	'label' => lang('label:name'),
               		'rules' => "required"
				],
			],
			'update' => [
				[
					'field' => 'Currency_Name',
                	'label' => lang('label:name'),
               		'rules' => "required"
				],
			]
		];
		
		$this->db = $this->load->database('BO_1', TRUE);
	}
	
	public function create($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id(); 
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
			->order_by('Currency_default', 'DESC')
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
				$option_data[$item->Currency_ID] = $item->Currency_Name;
			}
		}	
		return $option_data;
	}
	
	public function dropdown_html($where = NULL)
	{
		if($items = $this->get_all(NULL, 0, $where))
		{
			foreach($items as $item)
			{
				$option_html .= "<option value=\"{$item->Currency_ID}\">{$item->Currency_Name}</option>";
			}
		}	
		return $option_html;
	}
	
}

