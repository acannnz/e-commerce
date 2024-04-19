<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Goods_receipt_detail_model extends CI_Model
{
	public $table = 'BL_trPenerimaanDetail'; 
	protected $index_key = 'Penerimaan_ID';
	
	public function create($data)
	{
		//$this->db->query("SET IDENTITY_INSERT BL_trPermintaan ON");
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
	
	public function get_all_by($where = NULL, $to_array = FALSE, $id)
	{
		if( !is_null($id) && !empty($id) )
		{
			$this->load->model('goods_receipt_model');
			$goods_receipt = $this->goods_receipt_model->get_one( $id );
						
			$where = [ "a.{$this->index_key}" => $id, 'c.Lokasi_ID' => $goods_receipt->Lokasi_ID ];
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
			a.*,
			b.Kode_Barang,
			b.Barang_ID,
			b.Nama_Barang,
			(a.Harga_Beli * (a.Qty_PO - a.Qty_Telah_Terima) - Diskon_Rp ) AS sub_total,
			b.Konversi,
			c.JenisBarangID,
			c.Min_Stok,
			c.Max_Stok,
			c.Qty_Stok,
			e.Kode_Satuan,
			d.Nama_Kategori";

		$this->db
			->select( $db_select )
			->from("{$this->table} a" )
			->join("{$t_item} b", "a.Barang_ID = b.Barang_ID", "INNER")
			->join("{$t_item_location} c", "c.Barang_ID = b.Barang_ID", "INNER")
			->join("{$t_item_category} d", "d.Kategori_ID = b.Kategori_ID", "LEFT OUTER")
			->join("{$t_item_unit} e", "b.Stok_Satuan_id = e.Satuan_Id", "INNER")
			;
			
		$query = $this->db
			->order_by($this->index_key, 'ASC')
			->get();
		
		$collection = [];
		foreach($query->result() as $row)
		{
			$collection[] = (TRUE == $to_array) ? (array) $row : $row;
		}

		return $collection;
			
		//return (TRUE == $to_array) ? $query->result_array() : $query->result();
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
	
	public function gen_request_number()
	{
		$date = date("Y-m-d");
		$month = date("m");
		$years = substr(date("Y"),2,2);
		$y = date("Y");
		
		
		$order_number = $this->db->query("SELECT MAX(No_Penerimaan) AS No_Penerimaan from BL_trPenerimaan where year(Tgl_Penerimaan) = '$y'")->row();
		//$order_number = $this->db->get();
		
		//print_r($order_number->No_Permintaan);exit;
		
		if (empty($order_number->No_Penerimaan))
		{
			$kode = "$years"."/"."$month"."/"."RC"."/"."000001";
		} else {
			$order_number->No_Penerimaan++;
			$kode = $order_number->No_Penerimaan;
		}
		return (string) $kode;
	}
	
	public function gen_request_id()
	{
		$date = date("Y-m-d");
		$month = date("m");
		$years = substr(date("Y"),2,2);
		$y = date("Y");
		
		
		$order_number = $this->db->query("SELECT MAX(Penerimaan_ID) AS Penerimaan_ID from BL_trPenerimaan")->row();
		$order_number->Penerimaan_ID++;
		$kode = $order_number->Penerimaan_ID;
		
		return (string) $kode;
	}
	
	public function summerize($permintaan_id){
		$sum = $this->db->query("Select (Qty_Permintaan * Harga_Terakhir) AS sub_total from BL_trPermintaanDetail where Permintaan_ID = '$permintaan_id' ")->result();
		
		//print_r($sum);exit;
		$jumlah_total = 0;
		foreach($sum as $row){
			$jumlah_total = $jumlah_total + $row->sub_total;
		}
		return $jumlah_total;
	}
	


}

