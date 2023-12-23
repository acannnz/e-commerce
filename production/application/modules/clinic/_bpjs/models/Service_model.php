<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Service_model extends CI_Model
{
	public $table = 'SIMmListJasa';
	public $index_key = 'JasaID';
	public $rules;
	
	public function __construct()
	{
		$this->rules = [
			'insert' => [
				[
					'field' => $this->index_key,
                	'label' => lang('label:code'),
               		'rules' => "required|is_unique[{$this->table}.{$this->index_key}]"
				],
				[
					'field' => 'JasaIDBPJS',
                	'label' => lang('label:code') .' BPJS',
               		'rules' => "is_unique[{$this->table}.JasaIDBPJS]"
				],
				[
					'field' => 'JasaName',
                	'label' => lang('label:name'),
               		'rules' => "required"
				],
				[
					'field' => 'JasaNameEnglish',
                	'label' => lang('label:international_name'),
               		'rules' => "required"
				],
				[
					'field' => 'KategoriJasaID',
                	'label' => lang('label:category'),
               		'rules' => "required"
				],
				[
					'field' => 'GroupJasaID',
                	'label' => lang('label:group'),
               		'rules' => "required"
				],
			],
			'update' => [
				[
					'field' => 'JasaName',
                	'label' => lang('label:name'),
               		'rules' => "required"
				],
				[
					'field' => 'JasaNameEnglish',
                	'label' => lang('label:international_name'),
               		'rules' => "required"
				],
				[
					'field' => 'KategoriJasaID',
                	'label' => lang('label:category'),
               		'rules' => "required"
				],
				[
					'field' => 'GroupJasaID',
                	'label' => lang('label:group'),
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
				$option_data[$item->Kode_Kategori] = $item->Kategori_Name;
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
				$option_html .= "<option value=\"{$item->Kode_Kategori}\">{$item->Kode_Kategori} - {$item->Kategori_Name}</option>";
			}
		}	
		return $option_html;
	}
	
	public function dropdown_static($key)
	{
		$option_static['KelompokPostingan'] = [
			'GROUP JASA' => strtoupper( lang('label:service_group') ),
			'KOMPONEN' => strtoupper( lang('label:component') )
		];
		
		$option_static['ModelInsentif'] = [
			'NONE' => strtoupper( lang('label:none') ),
			'DETAIL' => strtoupper( lang('label:detail') ),
			'KOMPONEN' => strtoupper( lang('label:component') )
		];
		
		$option_static['PoliKlinik'] = [
			'NONE' => strtoupper( lang('label:none') ),
			'UMUM' => strtoupper( lang('label:general') ),
			'SPESIALIS' => strtoupper( lang('label:specialist') )
		];

		return $option_static[$key];
	}
	
}

