<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Amprahan_detail_model extends CI_Model
{
	public $table = 'GD_trAmprahanDetail';  
	protected $index_key = 'NoBukti';
	
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
	
	public function delete_not_in($key, Array $where_not_in)
	{
		$this->db->where($this->index_key, $key);
		$this->db->where_not_in('Barang_ID', $where_not_in, FALSE );
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
		
		$this->load->model('item_model');
		
		$query = $this->db
			->select("a.*, b.Kode_Barang, b.Nama_Barang, b.Konversi, b.HRataRata")
			->from("{$this->table} a")
			->join("{$this->item_model->table} b", "a.Barang_ID = b.Barang_ID", "INNER")
			->order_by($this->index_key, 'ASC')
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

	public function get_amprahan_detail( Array $where, $to_array = FALSE )
	{		
		$this->load->model('item_model');
		$this->load->model('item_unit_model');
		$this->load->model('item_category_model');
		$this->load->model('item_subcategory_model');
		$this->load->model('item_typegroup_model');
		
		$db_select = <<<EOSQL
			a.Barang_ID,
			b.Kode_Barang,
			b.Nama_Barang,
			a.Qty,
			a.QtyStok,
			a.Satuan,
			c.Nama_Satuan,
			f.Nama_Satuan,
			d.Nama_Kategori,
			e.Nama_Sub_Kategori,
			b.Harga_jual,
			b.HRataRata,
			b.Stok_Satuan_ID,
			b.Kategori_id,
			b.SubKategori_id,
			b.Konversi,
			g.Akun_ID_Mutasi
			
EOSQL;

		$query = $this->db
					->select( $db_select )
					->from( "{$this->table} a" )
					->join( "{$this->item_model->table} b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER" )
					->join( "{$this->item_unit_model->table} c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
					->join( "{$this->item_category_model->table} d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
					->join( "{$this->item_subcategory_model->table} e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
					->join( "{$this->item_unit_model->table} f", "b.Beli_Satuan_id = f.Satuan_ID", "LEFT OUTER" )
					->join( "{$this->item_typegroup_model->table} g"," b.KelompokJenis = g.KelompokJenis", "LEFT OUTER" )
					->where( $where )
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return ( $to_array === FALSE ) ? $query->result() : $query->result_array();
		}
		
		return false;
	}

}

