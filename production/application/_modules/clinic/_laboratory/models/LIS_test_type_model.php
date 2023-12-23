<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class LIS_test_type_model extends CI_Model
{
	public $table = 'LISTtrTestJenisTest';
	protected $index_key = 'NoSystem';
	
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
	
	public function get_max($where = NULL)
	{
		if (!is_null($where) && !empty($where)){ $this->db->where($where); }
		
		$this->db->where($where);		
		return (int) ($this->db->count_all_results($this->table));
	}
	
	public function count_all($field = 'NoSystem', $where = NULL)
	{
		if (!is_null($where) && !empty($where)){ $this->db->where($where); }
		
		$this->db->select("MAX({$field}) AS MAX")
			->where($where)
			->get($this->table);
					
		return @$query->row()->MAX;
	}
	
	public function to_list_html($first_label = '')
	{
		$option_html = "<option value=\"0\">{$first_label}</option>";		
		if ($items = $this->get_all())
		{
			foreach($items as $item)
			{
				$option_html .= "<option value=\"{$item->Supplier_ID}\">{$item->Kode_Supplier} - {$item->Nama_Supplier}</option>";
			}
		}
		
		return $option_html;
	}
	
	public function get_examination($key, $to_array = FALSE)
	{
		$this->db->select("
				a.NoBukti AS NoBill, a.Tanggal, a.LokasiPasien,
				a.RawatInap, b.UmurThn, b.UmurBln, b.UmurHr, c.* 
			")
			->from("{$this->helper_m->table} a")
			->join("{$this->registration_model->table} b", "a.RegNo = b.NoReg", "INNER")
			->join("{$this->patient_model->table} c", "a.NRM = b.NRM", "INNER")
			->where("a.NoBukti", $key);
		
		$query = $this->db->get();
		return (TRUE == $to_array) ? $query->row_array() : $query->row();
	}
}

