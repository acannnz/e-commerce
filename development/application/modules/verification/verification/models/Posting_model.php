<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Posting_model extends CI_Model
{
	public $table = 'SIMtrAudit'; 
	protected $index_key = 'NoBukti';
	public $rules;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->rules['insert'] = [
			[
				'field' => 'NoBukti[]',
				'label' => lang('message:empty_data'),
				'rules' => 'required'
			],
			
		];
	
	}
	
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

	public function delete_not_in($key, Array $where_not_in)
	{	
		$this->db->where($this->index_key, $key);
		$this->db->where_not_in('Barang_ID', $where, FALSE );
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
	
	public function get_selected_posting_data( Array $db_where_key_in, $is_cancel = FALSE)
	{
		$db_where = [];
		
		if ($is_cancel === TRUE)
		{
			$db_where['a.Batal'] = 0;
			$db_where['a.Posting'] = 1;
		}
		
		$db_select = <<<EOSQL
			a.NoBukti,
			a.Tanggal,
			a.NoInvoice,
			a.TglTransaksi,
			a.Catatan,
			c.NamaPasien,
			d.Nama_Customer AS Nama_Asisting
EOSQL;
				
		$this->db
			->select( $db_select )
			->from( "{$this->audit_model->table} a" )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->customer_model->table} d", "b.AssCompanyID_MA = d.Kode_Customer", "LEFT OUTER" );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_where_key_in) ){ $this->db->where_in( "a.{$this->index_key}", $db_where_key_in ); }
		
		$query = $this->db->get();
		
		$collection = [];
		foreach ( $query->result() as $row )
		{		
			$row->Tanggal = substr($row->Tanggal, 0, 10);
			$row->TglTransaksi = substr($row->TglTransaksi, 0, 10);
			
			$collection[] = $row;
		}
		
		return $collection;
	}
}

