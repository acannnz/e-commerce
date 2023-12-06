<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Audit_detail_ar_model extends CI_Model
{
	public $table = 'SIMtrAuditDetail_AR';  
	protected $index_key = 'NoBukti';
	public $rules;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->rules = [
			'insert' => [
				[
					'field' => 'Keterangan',
                	'label' => lang('label:description'),
               		'rules' => 'required'
				],
			]
		];	
	}
	
	public function create($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->affected_rows();
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
	
	public function get_collection($key, $db_bo, $to_array = FALSE)
	{
		$this->load->model('customer_model');		
		$db_bo = $this->load->database($db_bo, TRUE);
		
		$query = $this->db
			->select('a.*, b.Akun_No, b.Akun_Name, c.Nama_Customer')
			->from("{$this->table} a")
			->join("{$db_bo->database}.dbo.Mst_Akun b", "a.AkunID = b.Akun_ID", "INNER")
			->join("{$this->customer_model->table} c", "a.CustomerID = c.Customer_ID", "INNER")
			->where($this->index_key, $key)
			->get();	
				
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
				$option_html .= "<option value=\"{$item->Kelas_ID}\">{$item->Kode_Kelas} - {$item->Nama_Kelas}</option>";
			}
		}
		
		return $option_html;
	}
}

