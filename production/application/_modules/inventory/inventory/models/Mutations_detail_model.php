<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Mutations_detail_model extends CI_Model
{
	public $table = 'GD_trMutasiDetail';  
	protected $index_key = 'No_Bukti';
	public $rules;
	
	public function __construct()
	{
		parent::__construct();

		$this->rules['insert'] = [
			[
				'field' => 'Barang_ID',
				'label' => lang('label:item'),
				'rules' => 'required'
			],
			[
				'field' => 'Kode_Satuan',
				'label' => lang('label:item_unit'),
				'rules' => 'required'
			],
			[
				'field' => 'Qty',
				'label' => lang('label:qty_mutation'),
				'rules' => 'required|greater_than[0]'
			],
		];
	
	}
	
	public function create($data)
	{
		//$this->db->query("SET IDENTITY_INSERT BL_trPenerimaan ON");
		$this->db->insert($this->table, $data);
		//$this->db->query("SET IDENTITY_INSERT BL_trPermintaan OFF");
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
	
	public function get_mutation_detail( Array $where, $to_array = FALSE )
	{		
		$this->load->model('item_model');
		
		$db_select = <<<EOSQL
			a.*,
			b.Kode_Barang,
			b.Nama_Barang,
			b.Konversi
EOSQL;

		$query = $this->db
					->select( $db_select )
					->from( "{$this->table} a" )
					->join( "{$this->item_model->table} b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER" )
					->where( $where )
					->get()
					;

		return ( $to_array === FALSE ) ? $query->result() : $query->result_array();
	}

}

