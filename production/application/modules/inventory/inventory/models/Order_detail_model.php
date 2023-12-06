<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Order_detail_model extends CI_Model
{
	public $table = 'BL_trOrderDetail'; 
	protected $index_key = 'Order_ID';
	
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
		$this->db->where_not_in('Barang_ID', $where_not_in );
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
	
	public function get_all_by($where = NULL, $to_array = FALSE, $id)
	{
		if( !is_null($id) && !empty($id) )
		{
			$this->load->model('order_model');
			$order = $this->order_model->get_one( $id );
			
			$this->load->model('purchase_request_model');
			$purchase = $this->purchase_request_model->get_by( ['No_Permintaan' => $order->No_Order] );
			
			$where = [ "a.{$this->index_key}" => $id, 'c.Lokasi_ID' => $purchase->Gudang_ID ];
		}
				
		if (!is_null($where) && !empty($where)){ $this->db->where($where); }
		
		$this->load->model('item_model');
		$this->load->model('item_category_model');
		$this->load->model('item_location_model');
		$this->load->model('item_unit_model');
		
		$t_item = $this->item_model->table;
		$t_item_category = $this->item_category_model->table;
		$t_item_unit = $this->item_unit_model->table;
		$t_item_location = $this->item_location_model->table;
		
			
		$db_select = "
			b.Kode_Barang,
			b.Barang_ID,
			b.Nama_Barang,
			a.Harga_Order,
			a.Qty_Order,
			a.Qty_Tlh_Dibeli,
			(a.Harga_Order * (a.Qty_Order - a.Qty_Tlh_Dibeli) ) AS Jumlah_Total,
			b.Konversi,
			c.JenisBarangID,
			c.Min_Stok,
			c.Max_Stok,
			c.Qty_Stok,
			e.Kode_Satuan,
			d.Nama_Kategori,
			d.CN_Faktur";

		$this->db
			->select( $db_select )
			->from("{$this->table} a" )
			->join("{$t_item} b", "a.Barang_ID = b.Barang_ID", "INNER")
			->join("{$t_item_location} c", "c.Barang_ID = b.Barang_ID", "INNER")
			->join("{$t_item_category} d", "d.Kategori_ID = b.Kategori_ID", "LEFT")
			->join("{$t_item_unit} e", "b.Stok_Satuan_id = e.Satuan_Id", "INNER")
			;
			
		$query = $this->db
			->order_by($this->index_key, 'ASC')
			->get();
			
		return (TRUE == $to_array) ? $query->result_array() : $query->result();
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
}

