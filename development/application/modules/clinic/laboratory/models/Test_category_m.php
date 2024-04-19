<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test_category_m extends Public_Model
{
	public $table = 'LISmKategoriTest';
	public $index_key = 'KategoriTestID';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array(
			'insert' => array(
			array(
					'field' => 'KategoriTestID',
					'label' => 'Kategiru Test ID',
					'rules' => 'required'
				),
			array(
					'field' => 'KategoriTestNama',
					'label' => 'Nama Test',
					'rules' => 'required'
				),
			array(
					'field' => 'FormatDefault',
					'label' => 'Default',
					'rules' => ''
				),
			array(
					'field' => 'NoUrut',
					'label' => 'Nomor Urut',
					'rules' => ''
				),
			array(
					'field' => 'FormatNo',
					'label' => 'Nomor Format',
					'rules' => ''
				),
		),
		'update' => array(
			array(
					'field' => 'KategoriTestNama',
					'label' => 'Nama Test',
					'rules' => 'required'
				),
			array(
					'field' => 'FormatDefault',
					'label' => 'Default',
					'rules' => ''
				),
			array(
					'field' => 'NoUrut',
					'label' => 'Nomor Urut',
					'rules' => ''
				),
			array(
					'field' => 'FormatNo',
					'label' => 'Nomor Format',
					'rules' => ''
				),
		));
		
		parent::__construct();
	}
	
	public function create($data)
	{
		$this->db->insert($this->table, $data);
		return (int) $this->db->affected_rows(); 
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
	
	public function to_list_html($first_label = '')
	{
		$option_html = "<option value=\"0\">{$first_label}</option>";		
		if ($items = $this->get_all())
		{
			foreach($items as $item)
			{
				$option_html .= "<option value=\"{$item->KategoriTestID}\">{$item->KategoriTestNama}</option>";
			}
		}
		
		return $option_html;
	}
	
	public function options_type()
	{
		$result = array();
		$this->db->select('KategoriTestID,KategoriTestNama');
		$this->db->from('LISmKategoriTest');
		//$this->db->where('code',$code);
		$query = $this->db->get();
		foreach($query->result_array() as $row)
		{
			$result[] = $row;
		}
		return $result;

	}	

	
	

}