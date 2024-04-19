<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Service_test_model extends CI_Model
{
	public $table = 'SIMmListJasaTest';
	public $index_key = 'JasaID';
	public $rules;
	
	public function __construct()
	{
		$this->rules = [
			'insert' => [
				[
					'field' => 'JasaID',
                	'label' => lang('label:service'),
               		'rules' => "required"
				],
				[
					'field' => 'KelasID',
                	'label' => lang('label:class'),
               		'rules' => "required"
				],
				[
					'field' => 'KategoriOperasiID',
                	'label' => lang('label:operation_category'),
               		'rules' => "required"
				],
				[
					'field' => 'DokterID',
                	'label' => lang('label:doctor'),
               		'rules' => "required"
				],
				[
					'field' => 'Harga_Lama',
                	'label' => lang('label:old_price'),
               		'rules' => "required"
				],
				[
					'field' => 'Harga_Baru',
                	'label' => lang('label:new_price'),
               		'rules' => "required"
				],
				[
					'field' => 'TglHargaBaru',
                	'label' => lang('label:operation_category'),
               		'rules' => "required"
				],
			],
			'update' => [
				[
					'field' => 'KategoriJasaName',
                	'label' => lang('label:name'),
               		'rules' => "required"
				],
				[
					'field' => 'GroupJasa',
                	'label' => lang('label:service_group'),
               		'rules' => "required"
				],
				[
					'field' => 'NamaInternational',
                	'label' => lang('label:international_name'),
               		'rules' => "required"
				],
			]
		];
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
				$option_data[$item->{$this->index_key}] = $item->KategoriJasaName;
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
				$option_html .= "<option value=\"{$item->{$this->index_key}}\">{$item->{$this->index_key}} - {$item->KategoriJasaName}</option>";
			}
		} else {
			$option_html .= "<option value=\"\">". lang('global:select-empty') ."</option>";
		}
		
		return $option_html;
	}
	
}

