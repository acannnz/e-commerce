<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Group_model extends CI_Model
{
	public $table = 'mGroup';
	public $index_key = 'Group_ID';
	public $rules;
	
	public function __construct()
	{
		$this->rules = [
			'insert' => [
				[
					'field' => 'Username',
                	'label' => lang('label:name'),
               		'rules' => "required|trim|is_unique[{$this->table}.Username]"
				],
				[
					'field' => 'Nama_Asli',
                	'label' => lang('label:name'),
               		'rules' => "required"
				],
				[
					'field' => 'Nama_Singkat',
                	'label' => lang('label:nick_name'),
               		'rules' => "required"
				],
				[
					'field' => 'PasswordWeb',
                	'label' => lang('label:password'),
               		'rules' => "required"
				],
				[
					'field' => 'Status_Aktif',
                	'label' => lang('global:status'),
               		'rules' => "required"
				],
			],
			'update' => [
				[
					'field' => 'Username',
                	'label' => lang('label:name'),
               		'rules' => "required|trim|is_unique[{$this->table}.Username]"
				],
				[
					'field' => 'Nama_Asli',
                	'label' => lang('label:name'),
               		'rules' => "required"
				],
				[
					'field' => 'Nama_Singkat',
                	'label' => lang('label:nick_name'),
               		'rules' => "required"
				],
				[
					'field' => 'Status_Aktif',
                	'label' => lang('global:status'),
               		'rules' => "required"
				],
			],
			'update_unique' => [
				[
					'field' => 'Nama_Asli',
                	'label' => lang('label:name'),
               		'rules' => "required"
				],
				[
					'field' => 'Nama_Singkat',
                	'label' => lang('label:nick_name'),
               		'rules' => "required"
				],
				[
					'field' => 'Status_Aktif',
                	'label' => lang('global:status'),
               		'rules' => "required"
				],
			]
		];
	}
	
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
	
	public function get_all($where = NULL, $limit = NULL, $offset = 0, $to_array = FALSE)
	{
		if (!is_null($where) && !empty($where)){ $this->db->where($where); }
		
		$query = $this->db
			->order_by('Nomor_Urut', 'ASC')
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
				$option_data[$item->KelompokSection] = $item->KelompokSection;
			}
		}	
		return $option_data;
	}
	
	public function dropdown_html($where = NULL)
	{
		$option_html = '';
		if($items = $this->get_all(NULL, 0, $where))
		{
			foreach($items as $item)
			{
				$option_html .= "<option value=\"{$item->{$this->index_key}}\">{$item->{$this->index_key}}</option>";
			}
		} else {
			$option_html .= "<option value=\"\">". lang('global:select-empty') ."</option>";
		}
		
		return $option_html;
	}

}

